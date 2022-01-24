<?php
/**
 * Created by PhpStorm.
 * User: f.barthold
 * Date: 07.12.2021
 * Time: 09:54
 */
require_once('../header.php');

$userGruppe = $user['gruppe'];
$istAdmin = $userGruppe === 'admin';
$istSuperAdmin = $istAdmin && $user['adminmode'] === 'superadmin';

if (!$istAdmin) {
    die('Du hast leider keine Berechtigung die Seite aufzurufen. Bitte wende dich an einen Administrator!');
}

$datumvon = trim((!empty($_REQUEST['datumvon'])) ? $_REQUEST['datumvon'] : ''); // '2021-11-01'
$datumbis = trim((!empty($_REQUEST['datumbis'])) ? $_REQUEST['datumbis'] : ''); // '2021-12-31'

$output = getRequest('output', 'web');
$mailto = getRequest('mailto', 'Customer_Support@mertens.ag');
$abgerechnet = getRequest('abgerechnet', '');
$all = getRequest('all', '');
$vorgangsnr = strip_tags(getRequest('vorgangsnummer', ''));
$wwsnr = strip_tags(getRequest('wwsnr', ''));

if (empty($abgerechnet)) {
    $abgerechnet = empty($all) ? 'nein' : 'alle';
}
if (empty($abgerechnet)) {
    $abgerechnet = 'nein';
}

if (empty($vorgangsnr) && !empty($wwsnr)) {
    $vorgangsnr = $wwsnr;
}
$datumfeld = 'abgeschlossen_am';

$abgerechnet_alle = ($abgerechnet === 'alle') ? 'checked' : '';
$abgerechnet_ja = ($abgerechnet === 'ja') ? 'checked' : '';
$abgerechnet_nein = ($abgerechnet === 'nein') ? 'checked' : '';

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
        return '';
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
        . implode( '', array_map(function($v) use($t3) { return "$t3<th class=\"fld-$v\">$v</th>\n"; }, $cols))
        // . "$t3<th>" . implode('</th><th>', $cols) . "</th>$NL"
        . "$t2</tr>$NL"
        . "$t2</thead>$NL"
        . "$t<tbody>$NL";
    for($i = 0; $i < $num; $i++) {
        $row = $array[$i];
        $tbl.= "$t2<tr>";
        foreach($row as $fld => $val) {
            $tbl.= "$NL$t3<td class='fld-$fld'>" . $val . "</td>$NL";
        }
        $tbl.= "$NL$t2</tr>$NL";
    }
    $tbl.= "$t</tbody>$NL</table>"
    ;
    return $tbl;
}

if (strtolower($abgerechnet) === 'ja') {
    if ($vorgangsnr) {
        $andWhere = 'AND IFNULL(vorgangsnummer, "") = ' . $db::quote($vorgangsnr);
    } else {
        $andWhere = 'AND IFNULL(vorgangsnummer, "") != ""';
    }
} elseif (strtolower($abgerechnet) === 'nein') {
    $andWhere = 'AND IFNULL(vorgangsnummer, "") = ""';
} else {
    $andWhere = '';
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
      SUM(lk.preis_pro_einheit * ul.menge_mertens * IFNULL(ul.menge2_mertens,1)) AS Summe
    FROM mm_umzuege AS a
    JOIN mm_user AS u ON (a.antragsteller_uid = u.uid)
    JOIN mm_umzuege_leistungen AS ul ON (a.aid = ul.aid)
    JOIN mm_leistungskatalog AS lk ON (ul.leistung_id = lk.leistung_id)
    LEFT JOIN mm_leistungskategorie k ON (lk.leistungskategorie_id = k.leistungskategorie_id)
    WHERE umzugsstatus = "abgeschlossen" AND abgeschlossen = "Ja"
      AND service != "Rekla" 
      AND DATE_FORMAT(' . $datumfeld . ', "%Y-%m-%d") BETWEEN ' . $db::quote($datumvon) . ' AND ' . $db::quote($datumbis) . '
    ' . $andWhere . '
    GROUP BY a.aid
';

$sqlLeistungen = 'SELECT 
      k.leistungskategorie, k.leistungskategorie AS Kategorie, lk.leistungseinheit, lk.waehrung, lk.leistung_id, lk.Bezeichnung, lk.Farbe, lk.Groesse, lk.preis_pro_einheit,
      COUNT(DISTINCT ul.aid ) AS Auftraege,
      SUM(ul.menge_mertens * IFNULL(ul.menge2_mertens,1)) AS Menge,
      SUM(ul.menge_mertens * IFNULL(ul.menge2_mertens,1)) AS menge_mertens,
      SUM(lk.preis_pro_einheit * ul.menge_mertens * IFNULL(ul.menge2_mertens,1)) AS Summe,
      GROUP_CONCAT(ul.aid ORDER BY ul.aid SEPARATOR ",") AS csv_ul_aids 
    FROM mm_umzuege_leistungen AS ul
    JOIN mm_leistungskatalog AS lk ON (ul.leistung_id = lk.leistung_id)
    JOIN mm_leistungskategorie AS k ON (lk.leistungskategorie_id = k.leistungskategorie_id)
    WHERE ul.aid IN (
      SELECT aid FROM mm_umzuege 
      WHERE umzugsstatus = "abgeschlossen" AND abgeschlossen = "Ja" 
        AND service != "Rekla" 
        AND DATE_FORMAT(' . $datumfeld . ', "%Y-%m-%d") BETWEEN ' . $db::quote($datumvon) . ' AND ' . $db::quote($datumbis) . '
      ' .$andWhere . '
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
      AND service != "Rekla" 
      AND (vorgangsnummer IS NULL OR TRIM(vorgangsnummer) = "")
      AND DATE_FORMAT(' . $datumfeld . ', "%Y-%m-%d") BETWEEN ' . $db::quote($datumvon) . ' AND ' . $db::quote($datumbis) . '
    ) AND (
        ul.lieferstatus = "Geliefert" 
        AND (ul.rechnungsnr IS NULL OR TRIM(ul.rechnungsnr) = "")
        AND DATE_FORMAT(ul.lieferdatum, "%Y-%m-%d") BETWEEN ' . $db::quote($datumvon) . ' AND ' . $db::quote($datumbis) . '
    )
    GROUP BY lk.leistung_id, k.leistungskategorie, lk.Bezeichnung, lk.Farbe, lk.Groesse, lk.preis_pro_einheit
';

$sthA = $db->query($sqlAuftraege);
$sthL = $db->query($sqlLeistungen);
$sthT = $db->query($sqlTeilLeistungen);

$rowsA = $sthA->fetch_all(MYSQLI_ASSOC);
$rowsL = $sthL->fetch_all(MYSQLI_ASSOC);
$rowsT = $sthT->fetch_all(MYSQLI_ASSOC);

// echo '<pre>' . print_r(compact('sqlAuftraege', 'sqlLeistungen', 'rowsA', 'rowsL'), 1) . '</pre>';

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

$createPdf = (strcmp($output, 'pdf') === 0 || strcmp($output, 'mail') === 0);
$pdf = null;

if ($createPdf) {
    $pdf = new \module\Pdf\MertensAbrechnungPDF();

    $pdf->setAuftragsdaten([
        'vorname' => '',
        'name' => 'Uniper NewNormal HomeOffice',
    ]);
    $pdf->setZeitraum(date('d.m.', $timevon) . ' bis ' . date('d.m.Y', $timebis) );

    if (count($rowsL) || !count($rowsT)) {
        $pdf->setTableCaption('Abgeschlossene Leistungen');
        $pdf->setLeistungen($rowsL);
        $isWrittenRowsT = false;
    } elseif(count($rowsT)) {
        $pdf->setTableCaption('Erbrachte Teilleistungen');
        $pdf->setLeistungen($rowsL);
        $isWrittenRowsT = true;
    }
    $pdf->create();

    if (!$isWrittenRowsT && !empty($rowsT) && count($rowsT) > 0) {
        /*
         $filenameT = 'Teillieferungen_' . $datumvon . '_bis_' . $datumbis. '.pdf';
         $tmpFileT = "$tmp/$filenameT";
        */

        $pdf->AddPage();
        $pdf->setLeistungen($rowsT);
        $auftragsliste = $pdf->getAuftragsliste();

        $pdf->Ln(10);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->setTableCaption('Erbrachte Teilleistungen');
        $this->Ln(10);
        $pdf->writeHTML($auftragsliste);

        /*
         $pdf->create();
         $pdf->Output($tmpFileT, 'F' );
         $aAttachments[] = [
            'type' => 'file',
            'mimeType' => 'application/pdf',
            'name' => $filename,
            'file' => $tmpFileT,
        ];
        */
    }
}

if (strcmp($output, 'web') === 0) {
    echo <<<HEREDOC
<html>
    <head>
        <link rel='stylesheet' type='text/css' href='/css/tablelisting.css' />
        <style>
        * {
            font-family: Arial, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
        }
        body, body * {
            font-size:.8rem;
        }
        h2 {
            font-size: 1.5rem;
        }
        h3 {
            font-size: 1.1rem;
        }
        button {
            background-color: #0078dc;
            color: white;
            border: 1px solid #E0E0E0;
            border-radius: 5px;
            font-weight: bold;
            font-size: 1rem;
            padding: .5rem 2rem;
            cursor: pointer;
        }
        button:hover {
            background-color: #006ed1;
        }
        
        .fld-Auftraege, .fld-Menge, .fld-menge_mertens, .fld-preis_pro_einheit, 
        th.fld-Auftraege, th.fld-Menge, th.fld-menge_mertens,        
        .fld-Summe,
        .tblList td.fld-Summe,
         .tblList thead tr th.fld-Summe,
         .tblList thead tr th.fld-Auftraege,
         .tblList thead tr th.fld-Menge,
         .tblList thead tr th.fld-menge_mertens,
         .tblList thead tr th.fld-preis_pro_einheit  { 
            text-align: right;
        }
        
        </style>
    </head>
    <body>
        <form id="frmStat" name="frmStat" method="get" action="?">
            <div id="frmFilterBox" style="margin:0 1rem 1rem 1rem; border:1px solid #E0E0E0; padding:1rem; border-radius:8px;">
                <h2 style="margin-top: 0">Abrechenbare Leistungen nach Zeitraum filtern</h2>
                <div>
                <b>Von: </b> <input type="date" name="datumvon" style="border:0;border-bottom:1px solid #E0E0E0;text-align:right;padding-right:5px" value="{$datumvon}">
                <b>Bis: </b> <input type="date" name="datumbis" style="border:0;border-bottom:1px solid #E0E0E0;text-align:right;padding-right:5px"  value="{$datumbis}">
                </div>
                <div style="margin: 0.5rem 0">
                <b>Export-Ziel: </b>
                <label><input type="radio" name="output" value="web" checked> Webansicht</label>
                <label><input type="radio" name="output" value="pdf"> PDF</label>
                <label><input type="radio" name="output" value="mail"> Mail</label>
                <input type="text" name="mailto" value="$mailto" placeholder="Mailempfänger">
                </div>
                <div style="margin: 0.5rem 0">
                <b>Abgerechnete (mit Rechnungsr): </b>
                <label><input type="radio" name="abgerechnet" $abgerechnet_alle value="alle"> Alle (Mit und ohne RNR)</label>
                <label><input type="radio" name="abgerechnet" $abgerechnet_nein value="nein"> Ohne RNR</label>
                <label><input type="radio" name="abgerechnet" $abgerechnet_ja value="ja"> Mit RNR</label>
                <input type="text" name="vorgangsnummer" value="$vorgangsnr" placeholder="Rechnungsnr">
                </div>
                <button type="submit"> Starten </button>
            </div>
        </form> 
        <div style="margin:0 1rem 1rem 1rem; border:1px solid #E0E0E0; padding:1rem; border-radius:8px;">
HEREDOC;

    echo '<h2 style="margin-top: 0">Abrechenbare Leistungen, erbracht im Zeitraum ' . date('d.m.', $timevon) . ' bis ' . date('d.m.Y', $timebis) . '</h2>';
    echo '<h3>Aufträge</h3>' . "\n";
    if (count($rowsA)) {
        $summeA = array_sum(array_column($rowsA, 'Summe'));
        $rowsA = array_map(function($v) use($summeA) {
            $orig = $v;
            $re = [
                'aid' => $v['aid'],
                'kid' => $v['kid'],
                'LiefDat' => $v['umzugstermin'],
                'Abgeschl.' => $v['abgeschlossen_am'],
                'Tour' => $v['tour_kennung'],
                'Ort' => $v['ort'],
                'lstAbk' => $v['LstAbk'],
                'Leistungen' => $v['Lstg'],
                'Summe' => $v['Summe']
            ];
            foreach($re as $col => $val) {
                switch($col) {
                    case 'Lstg':
                    case 'Leistungen':
                        $re[$col] = '<div>' . implode("</div>\n<div>", explode("\n", $val)) . '</div>';
                        break;

                    case 'Abgeschl.':
                    case 'abgeschlossen_am':
                    case 'umzugstermin':
                    case 'LiefDat':
                        $re[$col] = date("d.m.Y", strtotime($val));
                        break;

                    case 'Summe':
                        $re[$col] = number_format((float)$val, 2, ',', '.');
                        break;
                }
            }
            return $re;
        }, $rowsA);
        echo '<div style="text-align:right;font-weight:bold;">' . number_format($summeA, 2, ',', '.') . '&euro;</div>' . "\n";
        echo array2Table($rowsA);
    } else {
        echo '<div><i>Keine</i></div>' . "\n";
    }
    echo "<br>\n";
    echo "<h3>Leistungen</h3>\n";
    if (count($rowsL)) {
        $summeL = array_sum(array_column($rowsL, 'Summe'));
        $rowsL = array_map(function($v) {
            $summe = $v['Summe'];
            unset($v['Kategorie']);
            unset($v['leistungseinheit']);
            unset($v['waehrung']);
            unset($v['leistung_id']);
            unset($v['csv_ul_aids']);
            unset($v['Summe']);
            $v['Summe'] = $summe;
            foreach($v as $col => $val) {
                switch($col) {
                    case 'Summe':
                        $v[$col] = number_format((float)$val, 2, ',', '.');
                        break;

                    case 'csv_ul_aids':
                        $v[$col] = str_replace(',', ', ', $val);
                        break;
                }
            }
            return $v;
        }, $rowsL);
        echo '<div style="text-align:right;font-weight:bold;">' . number_format($summeL, 2, ',', '.') . '&euro;</div>' . "\n";
        echo array2Table($rowsL);
        echo '<div style="font-size: x-small;display: none;">CSV-Aids: ' . json_encode($csvAids) . "</div>\n";
    } else {
        echo '<div><i>Keine</i></div>' . "\n";
    }
    echo "<br>\n";
    echo "<h3>Teil-Leistungen</h3>\n";
    if (count($rowsT)) {
        echo array2Table($rowsT);
        echo '<div style="font-size: x-small;display: none;">CSV-Ulids: ' . json_encode($csvUlids) . "</div>\n";
    } else {
        echo '<div><i>Keine</i></div>' . "\n";
    }
    echo "<br>\n";
    if (count($csvAids) || count($csvUlids)) {
        echo '<a href="' . $linkUrl . '" target="abrechnung">Abrechnungs-Link</a>';
    }
    echo '<pre style="display: none;">' . $sqlAuftraege . ";\n" . $sqlLeistungen . ";\n" . '</pre>';

    echo <<<HEREDOC
        </div>
    </body>
</html>
HEREDOC;


} elseif (strcmp($output, 'mail') === 0)  {
    if ($mailto === 'Customer_Support@mertens.ag') {
        $aTo = [ ['email' => $mailto, 'anrede' => 'MAG_Customer_Support'] ];
    } elseif ($mailto === 'k.gerring@mertens.ag') {
        $aTo = [ ['email' => $mailto, 'anrede' => 'Kim Gerring'] ];
    } elseif ($mailto === 'f.barthold@mertens.ag' || $mailto === 'frank.barthold@gmail.com') {
        $aTo = [ ['email' => $mailto, 'anrede' => 'Frank Barthold'] ];
    } else {
        $aTo = [ ['email' => $mailto ] ];
    }
    $sSubject = 'ORS Uniper: Reporting abgeschlossener Auftraege';
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

    $pdf->Output($tmpFile, 'F' );

    $aAttachments[] = [
        'type' => 'file',
        'mimeType' => 'application/pdf',
        'name' => $filename,
        'file' => $tmpFile,
    ];

    SmtpMailer::getNewInstance()
        ->sendMultiMail($aTo, $sSubject, $sHtmlBody, $sTxtBody, $aAttachments, $aUseHeaders);

    echo 'Mail wurde gesendet an ' . json_encode($aTo);

} else {
    $filename = 'AbrechnungsLeisungen_' . $datumvon . '_bis_' . $datumbis. '.pdf';
    $pdf->Output($filename, 'I' );
}
