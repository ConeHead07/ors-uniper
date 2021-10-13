<?php

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Default Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once( __DIR__ . '/../include/tcpdf_include.php');
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

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
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
            $this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        }
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

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

if (0) {
    $sHeaderTitle = '';
    $sHeaderText = '';
    $aHeaderTextColor = array(0, 64, 255);
    $aHeaderLineColor = array(0, 64, 128);

// set default header data
    $pdf->SetHeaderData(
        PDF_HEADER_LOGO,
        PDF_HEADER_LOGO_WIDTH,
        $sHeaderTitle,
        $sHeaderText,
        $aHeaderTextColor,
        $aHeaderLineColor
    );
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

}
// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$pdf->setFooterData(array(0,64,0), array(0,64,128));

$sender = 'merTens AG - Stahlwerk Becker 8 - D-47877 Willich';

$recipient = <<<EOT
DKV MOBILITY SERVICES BUSINESS CENTER
GmbH + Co. KG
Herr Danny Kopper
Balcke-Dürr-Allee 3
D-40882 Ratingen
EOT;

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

$tblColHeader = <<<EOT
<table style="border:1px solid gray" width="100%" cellspacing="0" cellpadding="1" border="0">
    <tr>
        <td width="10%">Anzahl</td>
        <td width="60%">Artikel</td>
        <td width="15%">B-Menge</td>
        <td width="15%">R-Menge</td>
    </tr>
    <tr>
        <td colspan="4"><hr></td>
    </tr>
    <tr>
        <td>1</td>
        <td>Schreibtisch</td>
        <td>1</td>
        <td>0</td>
    </tr>
</table>
EOT;

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
$pdf->Cell(0, 10, 'Page '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages(), 0, false, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln(40);
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
for($i = 0; $i < $iNumRefLines; $i++) {
    $_lbl = $aBriefkopfRefData[$i][0];
    $_val = $aBriefkopfRefData[$i][1] ?? '';

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
}
$pdf->Ln(4);

$pdf->writeHTML($tblColHeader);

if (1) {
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

// test fitbox with all alignment combinations

    $horizontal_alignments = array('L', 'C', 'R');
    $vertical_alignments = array('T', 'M', 'B');

    $x = 15;
    $y = 35;
    $w = 30;
    $h = 30;
// test all combinations of alignments
    for ($i = 0; $i < 3; ++$i) {
        $fitbox = $horizontal_alignments[$i].' ';
        $x = 15;
        for ($j = 0; $j < 3; ++$j) {
            $fitbox[1] = $vertical_alignments[$j];
            $pdf->Rect($x, $y, $w, $h, 'F', array(), array(128,255,128));
            $pdf->Image('../images/image_demo.jpg', $x, $y, $w, $h, 'JPG', '', '', false, 300, '', false, false, 0, $fitbox, false, false);
            $x += 32; // new column
        }
        $y += 32; // new row
    }

    $x = 115;
    $y = 35;
    $w = 25;
    $h = 50;
    for ($i = 0; $i < 3; ++$i) {
        $fitbox = $horizontal_alignments[$i].' ';
        $x = 115;
        for ($j = 0; $j < 3; ++$j) {
            $fitbox[1] = $vertical_alignments[$j];
            $pdf->Rect($x, $y, $w, $h, 'F', array(), array(128,255,255));
            $pdf->Image('../images/image_demo.jpg', $x, $y, $w, $h, 'JPG', '', '', false, 300, '', false, false, 0, $fitbox, false, false);
            $x += 27; // new column
        }
        $y += 52; // new row
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
    // Add a page
    // This method has several options, check the source code documentation for more information.
    $pdf->AddPage();
}

// set text shadow effect
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

if (1) {
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
$pdf->Output('example_001.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

