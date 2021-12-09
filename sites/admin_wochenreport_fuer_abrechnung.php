<?php
/**
 * Created by PhpStorm.
 * User: f.barthold
 * Date: 07.12.2021
 * Time: 09:54
 */
require_once('../header.php');

$datumvon = trim((!empty($_REQUEST['datumvon']))   ? $_REQUEST['datumvon'] : '2021-11-01');
$datumbis = trim((!empty($_REQUEST['datumbis']))   ? $_REQUEST['datumbis'] : '2021-12-31');
$output = getRequest('output', 'pdf');
$datumfeld = 'abgeschlossen_am';

if ($datumvon && strtotime($datumvon) && $datumbis && strtotime($datumbis)) {
    $timevon = strtotime($datumvon);
    $timebis = strtotime($datumbis);
} elseif ($datumvon && strtotime($datumvon)) {
    $timevon = strtotime($datumvon);
    $timebis = strtotime('next thursday', $timevon);
} elseif ($datumbis && strtotime($datumbis)) {
    $timebis = strtotime($datumbis);
    $timevon = strtotime('previous friday', $timebis);
} else {
    $timebis = strtotime('previous thursday');
    $timevon = strtotime('previous friday', $timebis);
}

$datumvon = date('Y-m-d', $timevon);
$datumbis = date('Y-m-d', $timebis);

function array2Table(array $array) {
    if (empty($array)) {
        return 'Empty List: ' . print_r($array, 1);
    }

    $NL = "\n";
    $num = count($array);
    $cols = array_keys($array[0]);

    $t = '   ';
    $t2 = $t . $t;
    $t3 = $t2. $t;

    $tbl = "<table class='tblList' width='100%'>$NL"
        . "$t<thead>$NL"
        . "$t2<tr>$NL"
        . "$t3<th>" . implode('</th><th>', $cols) . "</th>$NL"
        . "$t2</tr>$NL"
        . "$t<tbody>$NL";
    for($i = 0; $i < $num; $i++) {
        $row = $array[$i];
        $tbl.= "$t2<tr>$NL$t3<td>" . implode("</td>$NL$t3<td>", $row) . "</td>$NL$t2</tr>$NL";
    }
    $tbl.= "$t</tbody>$NL</table>"
    ;
    return $tbl;
}

$sqlAuftraege = 'SELECT 
      a.aid, u.personalnr AS kid, a.umzugsstatus, a.abgeschlossen_am, 
      a.umzugstermin, a.tour_kennung, a.plz, a.ort, a.umzug, a.service,
      GROUP_CONCAT(
        k.kategorie_abk ORDER BY k.kategorie_abk SEPARATOR ""
      )  AS LstAbk,
      GROUP_CONCAT(
        CONCAT_WS(" ", k.leistungskategorie, lk.Bezeichnung, lk.Farbe, lk.Groesse, lk.preis_pro_einheit) ORDER BY k.kategorie_abk SEPARATOR "\n"
      )  AS Lstg,
      SUM(lk.preis_pro_einheit) AS Summe
    FROM mm_umzuege AS a
    JOIN mm_user AS u ON (a.antragsteller_uid = u.uid)
    JOIN mm_umzuege_leistungen AS ul ON (a.aid = ul.aid)
    JOIN mm_leistungskatalog AS lk ON (ul.leistung_id = lk.leistung_id)
    LEFT JOIN mm_leistungskategorie k ON (lk.leistungskategorie_id = k.leistungskategorie_id)
    WHERE umzugsstatus = "abgeschlossen" AND abgeschlossen = "Ja"
    AND ' . $datumfeld . ' BETWEEN ' . $db::quote($datumvon) . ' AND ' . $db::quote($datumbis) . '
    GROUP BY a.aid
';

$sqlLeistungen = 'SELECT 
      k.leistungskategorie, k.leistungskategorie AS Kategorie, lk.leistungseinheit, lk.waehrung, lk.leistung_id, lk.Bezeichnung, lk.Farbe, lk.Groesse, lk.preis_pro_einheit,
      COUNT(DISTINCT ul.aid ) AS Auftraege,
      COUNT(1) AS Menge,
      COUNT(1) AS menge_mertens,
      SUM(lk.preis_pro_einheit),
      GROUP_CONCAT(ul.aid ORDER BY ul.aid SEPARATOR ",") AS csv_ul_aids 
    FROM mm_umzuege_leistungen AS ul
    JOIN mm_leistungskatalog AS lk ON (ul.leistung_id = lk.leistung_id)
    JOIN mm_leistungskategorie AS k ON (lk.leistungskategorie_id = k.leistungskategorie_id)
    WHERE ul.aid IN (
      SELECT aid FROM mm_umzuege 
      WHERE umzugsstatus = "abgeschlossen" AND abgeschlossen = "Ja" 
      AND (vorgangsnummer IS NULL OR TRIM(vorgangsnummer) = "")
      AND ' . $datumfeld . ' BETWEEN ' . $db::quote($datumvon) . ' AND ' . $db::quote($datumbis) . '
    )
    GROUP BY lk.leistung_id, k.leistungskategorie, lk.Bezeichnung, lk.Farbe, lk.Groesse, lk.preis_pro_einheit
';

$sqlTeilLeistungen = 'SELECT 
      k.leistungskategorie, k.leistungskategorie AS Kategorie, lk.leistungseinheit, lk.waehrung, lk.leistung_id, lk.Bezeichnung, lk.Farbe, lk.Groesse, lk.preis_pro_einheit,
      COUNT(DISTINCT ul.aid ) AS Auftraege,
      COUNT(1) AS Menge,
      COUNT(1) AS menge_mertens,
      SUM(lk.preis_pro_einheit),
      GROUP_CONCAT(ul.id ORDER BY ul.id SEPARATOR ",") AS csv_ul_ids
    FROM mm_umzuege_leistungen AS ul
    JOIN mm_leistungskatalog AS lk ON (ul.leistung_id = lk.leistung_id)
    JOIN mm_leistungskategorie AS k ON (lk.leistungskategorie_id = k.leistungskategorie_id)
    WHERE ul.aid IN (
      SELECT aid FROM mm_umzuege 
      WHERE umzugsstatus = "bestaetigt"
      AND (vorgangsnummer IS NULL OR TRIM(vorgangsnummer) = "")
      AND ' . $datumfeld . ' BETWEEN ' . $db::quote($datumvon) . ' AND ' . $db::quote($datumbis) . '
    ) AND (
        ul.lieferstatus = "Geliefert" 
        AND (ul.rechnungsnr IS NULL OR TRIM(ul.rechnungsnr) = "")
        AND ul.lieferdatum BETWEEN ' . $db::quote($datumvon) . ' AND ' . $db::quote($datumbis) . '
    )
    GROUP BY lk.leistung_id, k.leistungskategorie, lk.Bezeichnung, lk.Farbe, lk.Groesse, lk.preis_pro_einheit
';

$sthA = $db->query($sqlAuftraege);
$sthL = $db->query($sqlLeistungen);
$sthT = $db->query($sqlTeilLeistungen);

$rowsA = $sthA->fetch_all(MYSQLI_ASSOC);
$rowsL = $sthL->fetch_all(MYSQLI_ASSOC);
$rowsT = $sthT->fetch_all(MYSQLI_ASSOC);

$csvAids = [];
$csvAidsGrouped = array_column($rowsL, 'csv_ul_aids');
foreach($csvAidsGrouped as $_ids) {
    $_aIds = explode(',', $_ids);
    foreach($_aIds as $_aid) {
        $csvAids[] = $_aid;
    }
}

$csvUlids = [];
$csvUlidsGrouped = array_column($rowsT, 'csv_ul_ids');
foreach($csvUlidsGrouped as $_ids) {
    $_aIds = explode(',', $_ids);
    foreach($_aIds as $_aid) {
        $csvUlids[] = $_aid;
    }
}

$linkUrl = $MConf["WebRoot"] . 'index.php?s=abrechnung';
$linkUrl.= '&datumvon=' . rawurlencode($datumvon);
$linkUrl.= '&datumbis=' . rawurlencode($datumbis);
$linkUrl.= '&datumfeld=' . rawurlencode($datumfeld);
$linkUrl.= '&q[aids]=' . rawurlencode(implode(',', $csvAids));
$linkUrl.= '&q[ulids]=' . rawurlencode(implode(',', $csvUlids));

if (strcmp($output, 'web') === 0) {
    echo "<link rel='stylesheet' type='text/css' href='/css/tablelisting.css' />";
    echo '<pre>' . $sqlAuftraege . ";\n" . $sqlLeistungen . ";\n" . '</pre>';
    echo '<h6>Aufträge</h6>' . "\n";
    echo array2Table($rowsA);
    echo "<br>\n";
    echo "<h6>Leistungen</h6>\n";
    echo array2Table($rowsL);
    echo 'CSV-Aids: ' . json_encode($csvAids) . "<br>\n";
    echo "<br>\n";
    echo "<h6>Teil-Leistungen</h6>\n";
    echo array2Table($rowsT);
    echo 'CSV-Ulids: ' . json_encode($csvUlids) . "<br>\n";
    echo '<a href="' . $linkUrl . '" target="abrechnung">Abrechnungs-Link</a>';

} elseif (strcmp($output, 'mail') === 0)  {

    $aTo = [ ['email' => 'frank.barthold@gmail.com', 'anrede' => 'Frank Barthold'] ];
    $sSubject = 'Reporting';
    $sHtmlBody = 'Hallo, <br>
<br>
anbei das Reporting abrechenbarer Leistungen für den Zeitraum 
vom ' . date('d.m.', $timevon) . ' bis ' . date('d.m.Y', $timebis) . '.<br>
<br>
<b>Link zur Abrechnung:</b><br>
<a href="' . $linkUrl . '" target="abrechnung">Abrechnungs-Link</a><br>
<br>
Im Anhang befindet sich eine kumulierte Auflistung der Leistungen als PDF.<br>
<br>

Zusätzliche Informationen:<br>
<b>Aufträge: ' . count($rowsA) . '</b><br>
' . (count($rowsA) ? array2Table($rowsA) . '<br>' : '') . '<br>

<b>Kumulierte Leistungen: ' . count($rowsL) . '</b><br>
' . (count($rowsL) ? array2Table($rowsL) . '<br>' : '') . '<br>

<b>Kumulierte Leistungen aus Teillieferungen: ' . count($rowsT) . '</b><br>
' . (count($rowsT) ? array2Table($rowsT) . '<br>' : '') . '<br>

Mit besten Grüßen
Uniper NewNormal Homeoffice

    ';
    $sTxtBody = '';
    $aAttachments = [];
    $aUseHeaders = [];

    $tmp = sys_get_temp_dir();
    $filename = 'AbrechnungsLeisungen_' . $datumvon . '_bis_' . $datumbis. '.pdf';
    $tmpFile = "$tmp/$filename";
    $pdf = new \module\Pdf\MertensAbrechnungPDF();

    $pdf->setAuftragsdaten([
        'vorname' => '',
        'name' => 'Uniper NewNormal HomeOffice',
    ]);
    $pdf->setZeitraum(date('d.m.', $timevon) . ' bis ' . date('d.m.Y') );

    $pdf->setLeistungen($rowsL);
    $pdf->create();
    $pdf->Output($tmpFile, 'F' );

    $aAttachments[] = [
        'type' => 'file',
        'mimeType' => 'application/pdf',
        'name' => $filename,
        'file' => $tmpFile,
    ];

    if (!empty($rowsT) && count($rowsT) > 0) {
        $filenameT = 'Teillieferungen_' . $datumvon . '_bis_' . $datumbis. '.pdf';
        $tmpFileT = "$tmp/$filenameT";

        $pdf->setLeistungen($rowsT);
        $pdf->create();
        $pdf->Output($tmpFileT, 'F' );

        $aAttachments[] = [
            'type' => 'file',
            'mimeType' => 'application/pdf',
            'name' => $filename,
            'file' => $tmpFileT,
        ];
    }

    SmtpMailer::getNewInstance()
        ->sendMultiMail($aTo, $sSubject, $sHtmlBody, $sTxtBody, $aAttachments, $aUseHeaders);

    echo 'Mail wurde gesendet an ' . json_encode($aTo);

} else {
    $filename = 'AbrechnungsLeisungen_' . $datumvon . '_bis_' . $datumbis. '.pdf';
    $pdf = new \module\Pdf\MertensAbrechnungPDF();

    $pdf->setAuftragsdaten([
        'vorname' => '',
        'name' => 'Uniper NewNormal HomeOffice',
    ]);
    $pdf->setZeitraum(date('d.m.', $timevon) . ' bis ' . date('d.m.Y') );

    $pdf->setLeistungen($rowsL);
    $pdf->create();
    $pdf->Output($filename, 'I' );
}
