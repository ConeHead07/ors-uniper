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
class EtikettenPDF extends TCPDF {
    private static $iXPointsDefault = 141.732;
    private static $iYPointsDefault = 225.772;

    public function __construct(string $orientation = 'L', string $unit = 'mm', $format = 'ORS_ETIKETT', bool $unicode = true, string $encoding = 'UTF-8', bool $diskcache = false, $pdfa = false)
    {
        if (empty($format)) {
            $format = [self::$iXPointsDefault, self::$iYPointsDefault];
        }
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);

        // set document information
        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor('merTens AG');
        $this->SetTitle('Etiketten');
        $this->SetSubject('Lieferung NewHomeOffice');
        $this->SetKeywords('merTens, ORS, Uniper, NewHomeOffice');

        $this->setHeaderData('', '', '', '', '', '');
        $this->setFooterData('', '');

        // remove default header/footer
        $this->setPrintHeader(false);
        $this->setPrintFooter(false);

// set font
        $this->SetFont('helvetica', '', 9);
    }

    public function addArtikel(string $lsNr, string $lsDatum, array $Artikel) {
        $lsArtikel = $Artikel['Artikel'];
        $link = $Artikel['Link'];

//         141.732;
//         225.772;
        $page_format = array(
            'MediaBox' => array ('llx' => 0, 'lly' => 0, 'urx' => 80, 'ury' =>50),
            'CropBox' => array ('llx' => 0, 'lly' => 0, 'urx' => 80, 'ury' => 50),
            'BleedBox' => array ('llx' => 0, 'lly' => 0, 'urx' => 80, 'ury' => 50),
            'TrimBox' => array ('llx' => 10, 'lly' => 0, 'urx' => 80, 'ury' => 50),
//            'ArtBox' => array ('llx' => 0, 'lly' => 0, 'urx' => 140, 'ury' => 225),
        );
        // $this->SetMargins(2, 2, 2, true);
        $this->SetTopMargin(2);
        $this->SetLeftMargin(2);
        $this->setPrintHeader(false);
        $this->setPrintFooter(false);
        $this->getPageHeight();
        $this->setFooterMargin(0);
        $this->bMargin = 0;
        $this->pagedim[$this->page]['bm'] = 0;

        $this->AddPage('L', $page_format);
        // $this->SetMargins(2, 2, 2, true);
        $this->SetTopMargin(2);
        $this->SetLeftMargin(2);
        $this->setPrintHeader(false);
        $this->setPrintFooter(false);
        $this->getPageHeight();
        $this->setFooterMargin(0);
        $this->bMargin = 0;
        $this->pagedim[$this->page]['bm'] = 0;


        // new style
        $style = array(
            'border' => false,
            'padding' => 0,
            'fgcolor' => array(0,0,0),
            'bgcolor' => false
        );

        $innerContentMaxW = 72; // x,y,w.h in mm als Fließkommazahl
        $x = 4;
        $y = 4;
        $w = 72;
        if ($link) {
            // QRCODE,H : QR-CODE Best error correction
            $bw = 22;
            $bh = 22;
            $this->write2DBarcode($link, 'QRCODE,L', $x, $y, $bw, $bh, $style, 'N');
            $x+= $bw + 3;
            $w-= ($bw + 3);
        }

        $html = "<p>$lsNr<br>$lsDatum<br>$lsArtikel</p>";

        // $this->writeHTML($html, '', true, '', '', '');
        $border = 0;
        $this->writeHTMLCell($w, '', $x, $y, $html, $border);

    }

    public function setArtikels(string $lsNr, string $lsDatum, array $aArtikels) {

        $iNumPages = count($aArtikels);
        for ($i = 0; $i < $iNumPages; $i++) {
            $this->addArtikel($lsNr, $lsDatum, $aArtikels[$i]);
        }
    }
}

$ktgIdLieferung = 18;
$ktgIdRabatt = 25;
$mode = $_REQUEST['mode'] ?? '';
if (empty($AID)) {
    $AID = $_REQUEST["id"] ?? 0;
}
if (empty($lid)) {
    $lid= $_REQUEST["lid"] ?? 0;
}

if (empty($AID)) {
    die('INGUELTIGER SEITENAUFRUF! Es wurde keine AuftragsID übergeben');
}

if ($AID ) {
    LOX(__FILE__, __LINE__);
    $view = '';
    if (true) {
        $auftrag = $db->query_row('SELECT * FROM mm_umzuege WHERE aid = ' . (int)$AID);
        if (!$lid) {
            $lid = $db->query_one(
                'SELECT lid FROM mm_lieferscheine WHERE aid = :aid ORDER BY lid DESC',
                [ 'aid' => $AID]
            );
        }

        if (!$lid) {
            die('Fehlende Lieferschein-ID oder es wurde noch kein Lieferschein angelegt!');
        }
        $lieferschein = $db->query_row(
            'SELECT * FROM mm_lieferscheine WHERE aid = :aid AND lid = :lid',
            [ 'aid' => (int)$AID, 'lid' => $lid]
        );

        if (!is_array($lieferschein) || empty($lieferschein)) {
            die('Es wurde kein Lieferschein mit der ID ' . $lid . ' gefunden!');
        }

        LOX(__FILE__, __LINE__);
        $leistungen = $db->query_rows(
            'SELECT l.*,
                    k.leistungskategorie_id,
                    k.Bezeichnung,
                    k.produkt_link AS Link,
                    ktg.leistungskategorie AS Kategorie
                 FROM mm_umzuege_leistungen l
                 LEFT JOIN  `mm_leistungskatalog` k ON l.leistung_id = k.leistung_id
                 LEFT JOIN  `mm_leistungskategorie` ktg ON k.leistungskategorie_id = ktg.leistungskategorie_id
                 WHERE aid = ' . (int)$AID . ' AND k.leistungskategorie_id NOT IN (' . $ktgIdLieferung . ', ' . $ktgIdRabatt . ')');

        if (!count($leistungen)) {
            die('UNGUELTIGER SEITENAUFRUF! Es wurde keine Leistungen zum angegebenen Auftrag gefunden!');
        }

        $lsNr = 'UNIPER-4-' . str_pad($AID, 5, '0', STR_PAD_LEFT);
        $lsDatum = date('d.m.Y', strtotime($lieferschein['lieferdatum']));

        $aArtikels = array_map(
            function($item) { return
                [
                    'Kategorie' => $item['Kategorie'],
                    'Bezeichnung' => $item['Bezeichnung'],
                    'Artikel' =>$item['Kategorie'] . ' ' . $item['Bezeichnung'],
                    'Link' => $item['Link'],
                ];
            },
            $leistungen
        );

        $pdf = new EtikettenPDF();

        $pdf->setArtikels($lsNr, $lsDatum, $aArtikels);

        $pdf->Output('lieferschein-' . $lid . '.pdf', 'I');
    }
}

//============================================================+
// END OF FILE
//============================================================+

