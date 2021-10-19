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
require_once $MConf['AppRoot'] . $MConf['Inc_Dir'] . 'conf_lib.php';
require_once $MConf['AppRoot'] . $MConf['Class_Dir'] . 'dbconn.class.php';
require_once $MConf['AppRoot'] . $MConf['Class_Dir'] . 'SmtpMailer.class.php';
require_once $MConf['AppRoot'] . $MConf['Inc_Dir'] . 'conn.php';
require_once $MConf['AppRoot'] . $MConf['Inc_Dir'] . 'tcpdf_include.php';
//============================================================+
// File name   : example_001.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 001 for TCPDF class
//               Default Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+
LOX(__FILE__, __LINE__);
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        LOX(__FILE__, __LINE__);
        $x = 149;
        $y = 10;
        $w = 50;
        $h = 50;
        $fitbox = 'RT'; // Maybe false
        // Logo
        $image_file = '../images/mertens_logo.jpg';
        if (0) {
            $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false,
                300, '', false, false, 0, false, false, false);
        } else {
            $this->Image($image_file, $x, $y, $w, $h, 'JPG', '', 'T', true,
                300, '', false, false, 0, $fitbox, false, false);
        }
        if (false) {
            // Set font
            $this->SetFont('helvetica', 'B', 20);
            // Title
            $this->setCellPaddings(['right' => 0]);
            $this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        }
    }

    // Page footer
    public function Footer() {
        LOX(__FILE__, __LINE__);
        $image_file = '../images/mertens_prime_cert.jpg';
        $img_x = 175;
        $img_y = 10;
        $img_w = 14;
        $img_h = 14;
        $fitbox = 'RT'; // Maybe false
        // Position at 15 mm from bottom
        $this->SetY(-100);
        // Set font
        $this->SetFont('helvetica', 'I', 8);

        /*
Seite 1 von 20
Zertifiziertes Managementsystem | DIN EN ISO 9001:2015
merTens AG
Experience- & InnovationCENTER Rhein-Ruhr: Stahlwerk Becker 8, D- 47877 Willich, Fon +49 2154 4705 0, Fax +49 2154 4705 40000, Email info@mertens.ag
Internet www.mertens.ag, Business- & InspirationOFFICE Rhein-Main: Mainzer Straße 97, D- 65189 Wiesbaden, Business- & InspirationOFFICE
im merTensHAUPTSTADTQUARTIER Berlin-Brandenburg: Stresemannstraße 65, D- 10963 Berlin, Bankverbindungen: SPK Neuss, IBAN
DE02 3055 0000 0080 1096 89, Swift-Bic WELA DE DNXXX, SPK Krefeld, IBAN DE25 3205 0000 0000 2540 29, Swift-Bic SPKR DE 33XXX
Amtsgericht: Krefeld HRB 14453, Ust.-Id.-Nr.: DE 120585703, St.-Nr.: 102/5832/1139, Vorstand: Mike Mertens, Matthias Engenhorst, Aufsichtsratsvorsitzender:
Thomas R. Brünger, Unsere AGB finden Sie unter: www.mertens.ag/agb
         */
        $text = 'Experience- & InnovationCENTER Rhein-Ruhr: Stahlwerk Becker 8, D- 47877 Willich, Fon +49 2154 4705 0, Fax +49 2154 4705 40000, Email info@mertens.ag
Internet www.mertens.ag, Business- & InspirationOFFICE Rhein-Main: Mainzer Straße 97, D- 65189 Wiesbaden, Business- & InspirationOFFICE
im merTensHAUPTSTADTQUARTIER Berlin-Brandenburg: Stresemannstraße 65, D- 10963 Berlin, Bankverbindungen: SPK Neuss, IBAN
DE02 3055 0000 0080 1096 89, Swift-Bic WELA DE DNXXX, SPK Krefeld, IBAN DE25 3205 0000 0000 2540 29, Swift-Bic SPKR DE 33XXX
Amtsgericht: Krefeld HRB 14453, Ust.-Id.-Nr.: DE 120585703, St.-Nr.: 102/5832/1139, Vorstand: Mike Mertens, Matthias Engenhorst, Aufsichtsratsvorsitzender:
Thomas R. Brünger, Unsere AGB finden Sie unter: www.mertens.ag/agb';
        $curr = $this->getAliasNumPage();
        $tot = $this->getAliasNbPages();

        $pageCaption = $this->y . ' Seite  ' . $curr . ' von '. $tot .' ';
        // Wegen unbeeinflussbaren rechten Aussenabtands von Total wird die Cell-Breite überzogen
        $this->Cell(190, '', $pageCaption, 1, '', 'R');
        $this->Ln(18);
        $this->Cell(165, 20, 'Zertifiziertes Managementsystem | DIN EN ISO 9001:2015', 1, '', 'R',
            '', '', '', '', 'B', 'B');
        $this->x;
        $this->Image($image_file, 178, $this->y - 14, $img_w, $img_h, 'JPG', '', 'T', true,
            300, '', false, false, 0, $fitbox, false, false);
        $this->Ln(11);
        $this->Cell(17, 5, 'merTens AG', 1, '', 'L',
            '', '', '', '', '', '');
        $this->writeHTMLCell(160, 5, '', '',
            '<div style="border-bottom:2px solid yellow;"></div>', 1, '', '', '', '', '');
//
        $this->Ln(20);

        $html = '<table width="99%">' . "\n";
        $html.= '<tr>';
        $html.= '<td colspan="3">' . $text . '</td>';
        $html.= '</tr>';
        $html.= "</table>";
        $this->writeHTML($html);

//        $html = '<table width="99%">' . "\n";
//        $html.= '<tr>';
//        $html.= '<td width="10%"></td>';
//        $html.= '<td width="80%" align="right" valign="bottom"><br>Zertifiziertes Managementsystem | DIN EN ISO 9001:2015</td>';
//        $html.= '<td width="10%" align="righ"><img src="' . $image_file . '" width="30" height="30" border="0"></td>';
//        $html.= '</tr>';
//        $html.= '</table>';
//
//        $html = '<table width="100%">' . "\n";
//        $html.= '<tr>';
//        $html.= '<td>merTens AG</td>';
//        $html.= '<td></td>';
//        $html.= '<td><hr/></td>';
//        $html.= '</tr>';
//        $html.= "</table>";
//
//        $html = '<table width="100%">' . "\n";
//        $html.= '<tr>';
//        $html.= '<td colspan="3">' . $text . '</td>';
//        $html.= '</tr>';
//        $html.= "</table>";
//        $this->writeHTML($html);

        /*
        $this->Cell(120, '', "$pageCaption", 1, '', 'R');
        $this->Ln();
        $this->Image($image_file, 178, '', $img_w, $img_h, 'JPG', '', 'T', true,
            300, '', false, false, 0, $fitbox, false, false);
        $image = '';
        $firmaWithLine = 'merTens AG '; // Gefolgt von einer gelben Linie
        $this->Cell('merTens AG ');
        $this->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 4, 'color' => array(255, 0, 0)));
        $this->Ln();
        $this->Cell($text);
        */

    }
}

$mode = $_REQUEST['mode'] ?? '';
if (empty($AID)) {
    LOX(__FILE__, __LINE__);
    $AID = $_REQUEST["id"] ?? 0;
}

if (empty($AID)) {
    die('INGUELTIGER SEITENAUFRUF! Es wurde keine AuftragsID übergeben');
}

if ($AID ) {
    LOX(__FILE__, __LINE__);
    $view = '';
    if (true || $mode === 'property') {
        LOX(__FILE__, __LINE__);
        $auftrag = $db->query_row('SELECT * FROM mm_umzuege WHERE aid = ' . (int)$AID);
        switch($auftrag['umzugsstatus']) {
            case 'beantragt':
            case 'angeboten':
                $view = 'kalkulation';
                break;
            case 'abgeschlossen':
                $view = 'rechnung';
        }

        if (!$auftrag) {
            die('UNGUELTIGER SEITENAUFRUF! Es wurde kein Auftrag zur übergebenen ID gefunden!');
        }
        $ktgIdLieferung = 18;
        $ktgIdRabatt = 25;

        LOX(__FILE__, __LINE__);
        $leistungen = $db->query_rows(
            'SELECT 
 l.*,
 k.leistungskategorie_id, k.Bezeichnung, k.leistungseinheit, k.preis_pro_einheit, k.waehrung,
 ktg.leistungskategorie AS Kategorie
 FROM mm_umzuege_leistungen l
LEFT JOIN  `mm_leistungskatalog` k ON l.leistung_id = k.leistung_id
LEFT JOIN  `mm_leistungskategorie` ktg ON k.leistungskategorie_id = ktg.leistungskategorie_id
WHERE aid = ' . (int)$AID . ' AND k.leistungskategorie_id NOT IN (' . $ktgIdLieferung . ', ' . $ktgIdRabatt . ')');

        if (!count($leistungen)) {
            die('UNGUELTIGER SEITENAUFRUF! Es wurde keine Leistungen zum angegebenen Auftrag gefunden!');
        }
    }
}

LOX(__FILE__, __LINE__);
// create new PDF document
// $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('merTens AG');
$pdf->SetTitle('Lieferschein');
$pdf->SetSubject('Lieferung NewHomeOffice');
$pdf->SetKeywords('merTens, ORS, Uniper, NewHomeOffice');
$pdf->SetHeaderData('', '', '', '', [255, 255, 255], [255, 255, 255]);
LOX(__FILE__, __LINE__);
if (0) {
    $sHeaderTitle = '';
    $sHeaderText = '';
    $aHeaderTextColor = array(0, 64, 255);
    $aHeaderLineColor = array(0, 64, 128);

// set default header data
    $pdf->SetHeaderData(
        PDF_HEADER_LOXO,
        PDF_HEADER_LOXO_WIDTH,
        $sHeaderTitle,
        $sHeaderText,
        $aHeaderTextColor,
        $aHeaderLineColor
    );

    LOX(__FILE__, __LINE__);
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

}
LOX(__FILE__, __LINE__);
// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

LOX(__FILE__, __LINE__);
$pdf->setFooterData(array(0,64,0), array(0,64,128));

$sender = 'merTens AG - Stahlwerk Becker 8 - D-47877 Willich';

LOX(__FILE__, __LINE__);
$recipient = <<<EOT
DKV MOBILITY SERVICES BUSINESS CENTER
GmbH + Co. KG
Herr Danny Kopper
Balcke-Dürr-Allee 3
D-40882 Ratingen
EOT;

switch($auftrag['land']) {
    case 'Deutschland':
        $laenderKuerzel = 'D';
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


$lieferscheinCaption = "Lieferschein\nNr. {number}";

$aBriefkopfRefData = [
    ['Referenznummer', '12345'],
    ['Kundennummer', '6789'],
    ['Ihre Durchwahl', '1234 567 890'],
    ['Ihr Bestellzeichen', 'MM50607090'],
    ['', ''],
    ['Fachberator', 'Jochen Herbermann'],
    ['Telefon', '+49 177 9699 499'],
    ['Sachbearbeiter', 'Björn Bongartz'],
    ['Telefon', '+49 2154 4705 1121'],
    ['', ''],
    ['', 'Willich, ' . date('d.m.Y')],
];

LOX(__FILE__, __LINE__);
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

LOX(__FILE__, __LINE__);
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


LOX(__FILE__, __LINE__);
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
// set margins
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$fitbox = 'RT';
$pdf->AddPage();

LOX(__FILE__, __LINE__);
$pdf->SetFont('helvetica', 'I', 8);
// Page number
if (false) {
    $pdf->Cell(0, 10, 'Page ' . $pdf->getAliasNumPage() . '/' . $pdf->getAliasNbPages(), 0, false, 'L', 0, '', 0, false, 'T', 'M');
}
$pdf->Ln(40);
$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(123);
$pdf->MultiCell(107, 10, $sender,
    0, 'L', 0, 0, '', '', true, '', '', '', 10, 'B', '');

LOX(__FILE__, __LINE__);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->SetTextColor(0);
$pdf->MultiCell(55, 15, $lieferscheinCaption,
    0, 'L', 0, 0, '', '', true, '', '', '', 15, 'T', '');
$pdf->Ln(20);

$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(107, 60, $recipient,
    0, 'L', 0, 0, '', '', true);


LOX(__FILE__, __LINE__);
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

LOX(__FILE__, __LINE__);
$bShowDemo = false;
if ($bShowDemo) {
// add a page
// set header and footer fonts
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
// set margins
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $fitbox = 'RT';
    $pdf->AddPage();
    $x = 149;
    $y = 10;
    $w = 50;
    $h = 50;
    if (1) {
        $pdf->Image('../images/mertens_logo.jpg', $x, $y, $w, $h, 'JPG', '', '', true,
            300, '', false, false, 0, $fitbox, false, false);
    }

    LOX(__FILE__, __LINE__);
// add a page
    $pdf->AddPage();

// set JPEG quality
    $pdf->setJPEGQuality(75);

// Image method signature:
// Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// Example of Image from data stream ('PHP rules')
    $imgdata = base64_decode('iVBORw0KGgoAAAANSUhEUgAAABwAAAASCAMAAAB/2U7WAAAABlBMVEUAAAD///+l2Z/dAAAASUlEQVR4XqWQUQoAIAxC2/0vXZDrEX4IJTRkb7lobNUStXsB0jIXIAMSsQnWlsV+wULF4Avk9fLq2r8a5HSE35Q3eO2XP1A1wQkZSgETvDtKdQAAAABJRU5ErkJggg==');

// The '@' character is used to indicate that follows an image data stream and not an image file name
    $pdf->Image('@'.$imgdata);

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// Image example with resizing
    $pdf->Image('../images/image_demo.jpg', 15, 140, 75, 113, 'JPG', 'http://www.tcpdf.org', '', true, 150, '', false, false, 1, false, false, false);

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    if ($bShowDemo) {
// test fitbox with all alignment combinations

        $horizontal_alignments = array('L', 'C', 'R');
        $vertical_alignments = array('T', 'M', 'B');

        $x = 15;
        $y = 35;
        $w = 30;
        $h = 30;
        $iNumHA = count($horizontal_alignments);
        $iNumVA = count($vertical_alignments);
// test all combinations of alignments
        for ($hi = 0; $hi < $iNumHA; ++$hi) {
            $fitbox = $horizontal_alignments[$hi] . ' ';
            $x = 15;
            for ($vi = 0; $vi < $iNumVA; ++$vi) {
                LOX(__FILE__, __LINE__);
                $fitbox[1] = $vertical_alignments[$vi];
                $pdf->Rect($x, $y, $w, $h, 'F', array(), array(128, 255, 128));
                $pdf->Image('../images/image_demo.jpg', $x, $y, $w, $h, 'JPG', '', '', false, 300, '', false, false, 0, $fitbox, false, false);
                $x += 32; // new column
            }
            $y += 32; // new row
        }

        $x = 115;
        $y = 35;
        $w = 25;
        $h = 50;
        for ($hi = 0; $hi < $iNumHA; ++$hi) {

            $fitbox = $horizontal_alignments[$hi] . ' ';
            $x = 115;
            for ($vi = 0; $vi < $iNumVA; ++$vi) {
                LOX(__FILE__, __LINE__);
                $fitbox[1] = $vertical_alignments[$vi];
                $pdf->Rect($x, $y, $w, $h, 'F', array(), array(128, 255, 255));
                $pdf->Image('../images/image_demo.jpg', $x, $y, $w, $h, 'JPG', '', '', false, 300, '', false, false, 0, $fitbox, false, false);
                $x += 27; // new column
            }
            $y += 52; // new row
        }
    }

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// Stretching, position and alignment example

    $pdf->SetXY(110, 200);
    $pdf->Image('../images/image_demo.jpg', '', '', 40, 40, '', '', 'T', false, 300, '', false, false, 1, false, false, false);
    $pdf->Image('../images/image_demo.jpg', '', '', 40, 40, '', '', '', false, 300, '', false, false, 1, false, false, false);

// -------------------------------------------------------------------
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
$pdf->SetFont('dejavusans', '', 14, '', true);

if (1) {
    LOX(__FILE__, __LINE__);
    // Add a page
    // This method has several options, check the source code documentation for more information.
    $pdf->AddPage();
}
LOX(__FILE__, __LINE__);
// set text shadow effect
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

if ($bShowDemo) {
    LOX(__FILE__, __LINE__);
// Set some content to print
    $html = <<<EOD
    <h1>Welcome to <a href="http://www.tcpdf.org" style="text-decoration:none;background-color:#CC0000;color:black;">&nbsp;<span style="color:black;">TC</span><span style="color:white;">PDF</span>&nbsp;</a>!</h1>
    <i>This is the first example of TCPDF library.</i>
    <p>This text is printed using the <i>writeHTMLCell()</i> method but you can also use: <i>Multicell(), writeHTML(), Write(), Cell() and Text()</i>.</p>
    <p>Please check the source code documentation and other examples for further information.</p>
    <p style="color:#CC0000;">TO IMPROVE AND EXPAND TCPDF I NEED YOUR SUPPORT, PLEASE <a href="http://sourceforge.net/donate/index.php?group_id=128076">MAKE A DONATION!</a></p>
EOD;

// Print text using writeHTMLCell()
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
}

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
LOX(__FILE__, __LINE__);
$pdf->Output('example_001.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

