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

    public function __construct(string $orientation = 'L', string $unit = 'mm', $format = '', bool $unicode = true, string $encoding = 'UTF-8', bool $diskcache = false, $pdfa = false)
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
    }

    //Page header
    public function Header() {
    }

    // Page footer
    public function Footer() {

    }

    public function addArtikel(string $lsNr, string $lsDatum, string $lsArtikel) {
        $html = <<<EOT
            <table width="100%" height="100">
                <tr>
                    <td>$lsNr<br>$lsDatum<br>$lsArtikel</td>
                </tr>
            </table>
EOT;
        $this->AddPage();
        $this->writeHTML($html);
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
    if (true || $mode === 'property') {
        LOX(__FILE__, __LINE__);
        $auftrag = $db->query_row('SELECT * FROM mm_umzuege WHERE aid = ' . (int)$AID);
        if (!$lid) {
            $lid = $db->query_one(
                'SELECT lid FROM mm_lieferscheine WHERE aid = :aid ORDER BY lid DESC',
                [ 'aid' => $AID]
            );
        }
        $lieferschein = $db->query_row(
            'SELECT * FROM mm_lieferscheine WHERE aid = :aid = lid = :lid',
            [ 'aid' => (int)$AID, 'lid' => $lid]
        );

        LOX(__FILE__, __LINE__);
        $leistungen = $db->query_rows(
            'SELECT l.*, k.leistungskategorie_id, k.Bezeichnung, ktg.leistungskategorie AS Kategorie
 FROM mm_umzuege_leistungen l
LEFT JOIN  `mm_leistungskatalog` k ON l.leistung_id = k.leistung_id
LEFT JOIN  `mm_leistungskategorie` ktg ON k.leistungskategorie_id = ktg.leistungskategorie_id
WHERE aid = ' . (int)$AID . ' AND k.leistungskategorie_id NOT IN (' . $ktgIdLieferung . ', ' . $ktgIdRabatt . ')');

        $aArtikel = array_map(
            function($item) { return $item['Kategorie'] . ' ' . $item['Bezeichnung']; },
            $leistungen
        );
        if (!count($leistungen)) {
            die('UNGUELTIGER SEITENAUFRUF! Es wurde keine Leistungen zum angegebenen Auftrag gefunden!');
        }

        $pdf = new EtikettenPDF();

        $pdf->setArtikels();
    }
}

LOX(__FILE__, __LINE__);
// create new PDF document
// $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf = new EtikettenPDF();

$pdf->setArtikels();



$pdf->Output('example_001.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

