<?php
set_time_limit(30);
/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Default Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */
function LOX($line, $file) {
    // echo '#' . $line . ' ' . $file . "<br>\n";
}

// Include the main TCPDF library (search for installation path).
require(__DIR__ . '/../include/conf.php');
require_once $MConf['AppRoot'] . 'header.php';
require_once $MConf['AppRoot'] . $MConf['Inc_Dir'] . 'conf_lib.php';
require_once $MConf['AppRoot'] . $MConf['Class_Dir'] . 'dbconn.class.php';
require_once $MConf['AppRoot'] . $MConf['Class_Dir'] . 'SmtpMailer.class.php';
require_once $MConf['AppRoot'] . $MConf['Inc_Dir'] . 'conn.php';
require_once $MConf['AppRoot'] . $MConf['Inc_Dir'] . 'tcpdf_include.php';
require_once $MConf['AppRoot'] . $MConf['Modul_Dir'] . 'lieferschein/lieferschein.model.php';
require_once $MConf['AppRoot'] . $MConf['Modul_Dir'] . 'lieferschein/lieferschein.model.php';

use module\Pdf\MertensBasePDF;

$AID = $_REQUEST['id'] ?? 0;
if ($AID && !empty($_REQUEST['img'])) {
    $img = $_REQUEST['img'] ?? '';
    $lid = $_REQUEST['lid'] ?? '';

    if ($img && in_array($img, ['mt', 'kd']) && $AID && $lid) {
        $lsmodel = new LS_Model($AID, $lid);
        $daten = $lsmodel->getData();

        if (substr($daten["sig_{$img}_dataurl"], 0, 5) === 'data:') {
            $p = strpos($daten["sig_{$img}_dataurl"], ',');
            $dataurlStartInfo = substr($daten["sig_{$img}_dataurl"], 0, $p);
            list($mimeType, $encoding) = explode(';', substr($dataurlStartInfo, 5, -1));

            header('Content-Type: ' . $mimeType);
            $binary = base64_decode(substr($daten["sig_{$img}_dataurl"], $p + 1));

            echo $binary;
            exit;
        }
    }
    die('Image-Error');
}

if (empty($AID)) {
    die('INGUELTIGER SEITENAUFRUF! Es wurde keine AuftragsID übergeben');
}

$art = $_REQUEST['art'] ?? '';
$istKommissionsSchein = $art === 'kommission';


if ($AID ) {
        
    $auftrag = $db->query_row(
        'SELECT a.*, u.personalnr, u.personalnr AS kid
FROM mm_umzuege a
JOIN mm_user u ON (a.antragsteller_uid = u.uid)
WHERE aid = ' . (int)$AID
    );

    if (!$auftrag) {
        die('UNGUELTIGER SEITENAUFRUF! Es wurde kein Auftrag zur übergebenen ID gefunden!');
    }
    $ktgIdLieferung = 18;
    $ktgIdRabatt = 25;


    $leistungen = $db->query_rows(
        'SELECT 
l.*,
k.leistungskategorie_id, k.Bezeichnung, k.leistungseinheit, k.preis_pro_einheit, k.waehrung,
ktg.leistungskategorie AS Kategorie
FROM mm_umzuege_leistungen l
LEFT JOIN  `mm_leistungskatalog` k ON l.leistung_id = k.leistung_id
LEFT JOIN  `mm_leistungskategorie` ktg ON k.leistungskategorie_id = ktg.leistungskategorie_id
WHERE aid = ' . (int)$AID . ' AND k.leistungskategorie_id NOT IN (' . $ktgIdLieferung . ', ' . $ktgIdRabatt . ')');

    $lsmodel = new LS_Model((int)$AID);
    $lieferschein = $lsmodel->loadLieferschein(true)->getData();

    $aLeistungsLabels = array_map(function($item) { return $item['Kategorie']; }, $leistungen);

    if (!count($leistungen)) {
        die('UNGUELTIGER SEITENAUFRUF! Es wurde keine Leistungen zum angegebenen Auftrag gefunden!');
    }

}

$pdfclass = new \module\Pdf\MertensLieferscheinPDF();

$pdfclass->setAuftragsdaten($auftrag);
$pdfclass->setLeistungen($leistungen);
$pdfclass->setLieferscheindaten($lieferschein);
$pdfclass->create();
$pdfclass->Output('example_001.pdf', 'I');
exit;

$sig_mt = '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;';
$sig_kd = '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;';
$sig_datum = '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;';
$sig_pruefung = '&nbsp;';
$sig_etikettierung = '';
$sig_ankunft = '&nbsp; &nbsp; :&nbsp; &nbsp;';
$sig_abfahrt = '&nbsp; &nbsp; :&nbsp; &nbsp;';

$aEtikettenCheck = [];
foreach($aLeistungsLabels as $k) {
    $aEtikettenCheck[$k] = '&nbsp;';
}

if (!$istKommissionsSchein && !empty($lieferschein)) {
    if ($lieferschein['sig_mt_dataurl']) {
        $p = strpos($lieferschein['sig_mt_dataurl'], ',') + 1;
        $sig_mt_src = '@' . substr($lieferschein['sig_mt_dataurl'], $p);
        $sig_mt = "<img src=\"" . $sig_mt_src . "\" height=\"12\" width=\"40\">";
    }
    if ($lieferschein['sig_kd_dataurl']) {
        $p = strpos($lieferschein['sig_kd_dataurl'], ',') + 1;
        $sig_kd_src = '@' . substr($lieferschein['sig_kd_dataurl'], $p);
        $sig_kd = "<img src=\"$sig_kd_src\" height=\"12\">";
    }
    if ($lieferschein['lieferdatum']) {
        $sig_datum = date('d.m.Y', strtotime($lieferschein['lieferdatum']));
    }
    if ($lieferschein['funktionspruefung_erfolgt'] && strpos($lieferschein['funktionspruefung_erfolgt'], 'Schreibtisch') !== false) {
        $sig_pruefung = 'x';
    }
    if ($lieferschein['etikettierung_erfolgt']) {
        $sig_etikettierung = $lieferschein['etikettierung_erfolgt'];
        if ($sig_etikettierung[0] === '{' || $sig_etikettierung[0] === '[') {
            $sigEtJson = json_decode($sig_etikettierung, JSON_OBJECT_AS_ARRAY);
            $aEtikettierteItem = array_values($sigEtJson);
            foreach($aEtikettierteItem as $k) {
                if (isset($aEtikettenCheck[$k])) {
                    $aEtikettenCheck[$k] = 'x';
                }
            }
        }
    }
    if ($lieferschein['ankunft']) {
        $sig_ankunft = substr($lieferschein['ankunft'], 0, 5);
    }
    if ($lieferschein['abfahrt']) {
        $sig_abfahrt = substr($lieferschein['abfahrt'], 0, 5);
    }
}

$aEtikettenCheck2 = [];
foreach($aEtikettenCheck as $k => $check) {
    $aEtikettenCheck2[] = "[ $check ] $k";
}
$sig_etiketen_check = implode($aEtikettenCheck2, ' &nbsp; &nbsp; &nbsp; &nbsp;');


// create new PDF document
// $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// $pdf = new LS_PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf = new MertensBasePDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('merTens AG');
$pdf->SetTitle(!$istKommissionsSchein ? 'Lieferschein' : 'Kommissionsschein');
$pdf->SetSubject('Lieferung NewHomeOffice');
$pdf->SetKeywords('merTens, ORS, Uniper, NewHomeOffice');
$pdf->SetHeaderData('', '', '', '', [255, 255, 255], [255, 255, 255]);


// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


$pdf->setFooterData(array(0,64,0), array(0,64,128));

$sender = 'merTens AG - Stahlwerk Becker 8 - D-47877 Willich';

switch($auftrag['land']) {
    case 'Belgien':
        $laenderKuerzel = 'BE';
        break;
    case 'Deutschland':
        $laenderKuerzel = 'D';
        break;
    case 'England':
        $laenderKuerzel = 'EN';
        break;
    case 'Niederlande':
        $laenderKuerzel = 'NL';
        break;
    case 'Ungarn':
        $laenderKuerzel = 'HU';
        break;

    default:
        $laenderKuerzel = $auftrag['land'];
}

$recipient = $auftrag['vorname'] . ' ' . $auftrag['name'] . "\r\n";
$recipient.= $auftrag['strasse'] . "\r\n";
$recipient.= "$laenderKuerzel-{$auftrag['plz']} {$auftrag['ort']}\r\n";

switch($art) {
    case 'kommission':
        $lieferscheinCaption = "Kommissionierschein\nNr. {number}";
        break;

    default:
        $lieferscheinCaption = "Lieferschein\nNr. {number}";
}

$aBriefkopfRefData = [
    ['Referenznummer', '12345'],
    ['Kundennummer', '6789'],
    ['Ihre Durchwahl', '1234 567 890'],
    ['Ihr Bestellzeichen', 'MM50607090'],
    ['', ''],
//    ['Fachberator', 'Jochen Herbermann'],
//    ['Telefon', '+49 177 9699 499'],
//    ['Sachbearbeiter', 'Björn Bongartz'],
//    ['Telefon', '+49 2154 4705 1121'],
//    ['', ''],
    ['', 'Willich, ' . date('d.m.Y')],
];


$tblColHeader = <<<EOT
<table style="border:1px solid gray" width="99%" cellspacing="0" cellpadding="1" border="0">
    <tr>
        <td style="font-weight:bold;padding-bottom:10px;" width="8%" align="left">Anzahl</td>
        <td style="padding-bottom:10px;" width="8%">&nbsp;</td>
        <td style="font-weight:bold;padding-bottom:10px;" width="63%">Artikel</td>
        <td style="font-weight:bold;padding-bottom:10px;" width="10%" align="right">B-Menge</td>
        <td style="font-weight:bold;padding-bottom:10px;" width="10%" align="right">R-Menge</td>
    </tr>
    <tr>
        <td colspan="5">
        <hr style="height:2px;margin-top:10px"/>
        </td>
    </tr>
EOT;


$iNumLstg = count($leistungen);
for($i = 0; $i < $iNumLstg; $i++) {

    $_item = $leistungen[$i];
    $unit = $_item['leistungseinheit'];
    $kategorie = $_item['Kategorie'];
    $anzahl = $_item['menge_mertens'];
    $artikel = $_item['Bezeichnung'];
    $BMenge = $anzahl;
    $RMenge = 0;
    $tblColHeader.= <<<EOT
    <tr>
        <td style="font-weight:bold;" align="left"><font weight="bold">$anzahl</font></td>
        <td style="font-weight:bold;" align="right">$unit</td>
        <td align="left">
<div style="font-weight:bold;">$kategorie</div>
$artikel</td>
        <td style="font-weight:bold;" align="right">$BMenge</td>
        <td style="font-weight:bold;" align="right">$RMenge</td>
    </tr>
EOT;
}
$tblColHeader.= '
</table
';



$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
// set margins
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$fitbox = 'RT';
$pdf->AddPage();

$pdf->SetFont('helvetica', 'I', 8);
// Page number

$pdf->Ln(10);
$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(123);
$pdf->MultiCell(107, 10, $sender,
    0, 'L', 0, 0, '', '', true, '', '', '', 10, 'B', '');


$pdf->SetFont('helvetica', 'B', 12);
$pdf->SetTextColor(0);
$pdf->MultiCell(55, 15, $lieferscheinCaption,
    0, 'L', 0, 0, '', '', true, '', '', '', 15, 'T', '');
$pdf->Ln(20);

$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(107, 60, $recipient,
    0, 'L', 0, 0, '', '', true);



$iNumRefLines = count($aBriefkopfRefData);
if ($iNumRefLines > 20) {
    die('#' . __LINE__ . ' ' . __FILE__ . ' ' . print_r(compact('iNumRefLines'), 1));
}
for($ri = 0; $ri < $iNumRefLines; $ri++) {
    $_lbl = $aBriefkopfRefData[$ri][0];
    $_val = $aBriefkopfRefData[$ri][1] ?? '';

    if (1) $pdf->MultiCell(
        107, 5, '',
        0, 'L', 0, 0, PDF_MARGIN_LEFT, '', true, '', '', '', '', 'T', '');
    $pdf->MultiCell(
        35, 5, $_lbl,
        0, 'L', 0, 0, '', '', true, '', '', '', '', 'T', '');

    $pdf->MultiCell(
        35, 5, $_val,
        0, 'R', 0, 0, '', '', true, '', '', '', '', 'T', '');
    $pdf->Ln(5);
    if ($ri > 20) {
        break;
    }
}
$pdf->Ln(4);

$pdf->writeHTML($tblColHeader);



$kundenAbnahme = <<<EOT
<div>Die Ware wurde ordnungsgemäß geliefert und in einwandfreiem Zustand montiert. Ebenfalls bestätigen Sie hiermit, dass
durch uns keine Schäden an Ihrem Gebäude und Ihren Räumlichkeiten entstanden sind. Sollten Schäden entstanden
sein, notieren Sie diese bitte auf dem beiliegendem Reklamationsformular.</div>
<div></div>
<table width="99%" cellpadding="0" cellspacing="0">
    <tr>
        <td width="35%">Ihr Montageteam der merTens AG</td>
        <td>$sig_mt<hr width="250px"></td>    
    </tr>
    <tr>
        <td colspan="2">
            <div style="height:5px;overflow:hidden;"></div>
            <table>
                <tr>
                <td width="50">Ankunft</td>
                <td width="35">$sig_ankunft<hr width="35px"></td>
                <td width="100"> Uhr &nbsp; &nbsp; &nbsp; &nbsp; Abfahrt </td>
                <td width="35">$sig_abfahrt<hr></td>
                <td width="30"> Uhr</td>
                </tr>
             </table>
        </td>    
    </tr>
    <tr>
        <td colspan="2"><div style="height:5px;overflow:hidden;"></div>
            Etikettierung erfolgt:<br>
            $sig_etiketen_check                             
        </td>
    </tr>
    <tr>
        <td colspan="2"><div style="height:5px;overflow:hidden;"></div>
            Funktionsprüfung erfolgt:<br>
            [ $sig_pruefung ] Schreibtisch                              
        </td>
    </tr>
    <tr>
        <td>
            <div style="height:5px;overflow:hidden;"></div>
            $sig_datum<hr width="60px">
            <br>(Datum)
        </td>
        <td>
            <div style="height:5px;overflow:hidden;"></div>
            $sig_kd<hr width="250px">
            <br>(Name Kunde Blockbuchstaben / Unterschrift
        </td>   
    </tr>

</table>
EOT;
if (!$istKommissionsSchein) {
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Ln(10);
    $pdf->writeHTML($kundenAbnahme);
}



// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
if (1) {
    // set margins
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
}

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
// $pdf->SetFont('dejavusans', '', 14, '', true);


// Close and output PDF document
// This method has several options, check the source code documentation for more information.

$pdf->Output('example_001.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

