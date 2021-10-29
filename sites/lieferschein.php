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
require(__DIR__ . '/../header.php');
require_once $MConf['AppRoot'] . 'header.php';
require_once $MConf['AppRoot'] . $MConf['Inc_Dir'] . 'conf_lib.php';
require_once $MConf['AppRoot'] . $MConf['Class_Dir'] . 'dbconn.class.php';
require_once $MConf['AppRoot'] . $MConf['Class_Dir'] . 'SmtpMailer.class.php';
require_once $MConf['AppRoot'] . $MConf['Inc_Dir'] . 'conn.php';
require_once $MConf['AppRoot'] . $MConf['Inc_Dir'] . 'tcpdf_include.php';
require_once $MConf['AppRoot'] . $MConf['Modul_Dir'] . 'lieferschein/lieferschein.model.php';
require_once $MConf['AppRoot'] . $MConf['Modul_Dir'] . 'lieferschein/lieferschein.model.php';

$AID = $_REQUEST['id'] ?? 0;

if (empty($AID)) {
    die('INGUELTIGER SEITENAUFRUF! Es wurde keine AuftragsID übergeben');
}

$art = $_REQUEST['art'] ?? '';
$istKommissionsSchein = $art === 'kommission';

if ($AID ) {
    $lsmodel = new LS_Model((int)$AID);
    $auftrag = $lsmodel->getAuftragsdaten();
    if (!$auftrag) {
        die('UNGUELTIGER SEITENAUFRUF! Es wurde kein Auftrag zur übergebenen ID gefunden!');
    }
    if ($auftrag['umzugsstatus'] === 'abgeschlossen') {
        $lsPdf = $lsmodel->getAbgenommenenLieferscheinPDF();
        if ($lsPdf) {
            header('Content-Type: application/pdf');
            echo $lsPdf;
            exit;
        }
    }
    }
    $leistungen = $lsmodel->getLeistungen();
    $lieferschein = $lsmodel->loadLieferschein(true)->getData();

    $aLeistungsLabels = array_map(function($item) { return $item['Kategorie']; }, $leistungen);

    if (!count($leistungen)) {
        die('UNGUELTIGER SEITENAUFRUF! Es wurde keine Leistungen zum angegebenen Auftrag gefunden!');
    }

    if (!$istKommissionsSchein) {
        $pdfclass = new \module\Pdf\MertensLieferscheinPDF();
    } else {
        $pdfclass = new \module\Pdf\MertensKommissionsscheinPDF();
    }

    $pdfclass->setAuftragsdaten($auftrag);
    $pdfclass->setLeistungen($leistungen);
    if (!$istKommissionsSchein) {
        $pdfclass->setLieferscheindaten($lieferschein);
    }
    $pdfclass->create();
    $pdfclass->Output('example_001.pdf', 'I');

