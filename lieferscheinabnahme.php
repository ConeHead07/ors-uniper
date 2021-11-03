<?php 
require_once('header.php');

require_once($InclBaseDir . 'umzugsantrag.inc.php');
require_once($InclBaseDir . 'umzugsmitarbeiter.inc.php');

require_once($ModulBaseDir . '/lieferschein/lieferschein.model.php');
require_once($SitesBaseDir . '/umzugsantrag_sendmail.php');

if (function_exists('activity_log')) {
    register_shutdown_function('activity_log');
}

$lid = 0;
$input = [];
$msg = '';
$error = '';
$errors = [];
$sigFiles = [];
$tmpImg = '';
$tmpSize = 0;
$commaPos = false;
$base64Start = false;
$base64Data = '';

$aid = $_REQUEST['aid'] ?? 0;

if (!$aid) {
    echo json_encode([
        'type' => 'error',
        'errors' => ['aid' => 'Es wurde keine Auftrags-ID übergeben'],
        'id' => 0,
    ]);
    exit;
}

$lsmodel = new LS_Model((int)$aid);

if ($lsmodel->getAuftragsStatus() === 'abgeschlossen') {
    $lsPdfExists = $lsmodel->getLieferscheinPdfLength();
    if ($lsPdfExists) {
        echo json_encode([
            'type' => 'error',
            'errors' => ['aid' => 'Der Auftrag wurde bereits abgeschlossen und der Lieferschein unterschrieben!'],
            'id' => 0,
        ]);
        exit;
    }

}

$aPostInput = $_POST;
$input = $lsmodel->validateInput($aPostInput);
if (isset($input['sig_mt_dataurl_geodata'])) {
    die('#' . __LINE__ . ' ' . __FILE__ . ' ' . print_r(compact('input'), 1));
}
$errors = $lsmodel->getValidationErrors();

if (false !== $input) {
    $lid = $lsmodel->save($input);

    $auftrag = $lsmodel->getAuftragsdaten();
    $leistungen = $lsmodel->getLeistungen();
    $lsdaten = $lsmodel->getData();

    $pdf = new \module\Pdf\MertensLieferscheinPDF();
    $pdf->setAuftragsdaten($auftrag);
    $pdf->setLeistungen($leistungen);
    $pdf->setLieferscheindaten($lsdaten);
    $pdf->create();
    $lsPdf = $pdf->Output('lsdoc_' . $lid . '.pdf', 'S' );

    if (empty($lsPdf) || strlen($lsPdf) < 10) {
        $error = 'Lieferschein konnte nicht generiert werden!';
    } else {
        $msg.= 'Lieferchein wurde generiert: ' . strlen($lsPdf) . '!' . "\n";
    }

    if (!$lsmodel->updateLieferscheinPdf($lsPdf)) {
        $error.= 'Lieferschein konnte nicht in Datenbank gespeichert werden!';
    } else {
        $msg.= 'Lieferschein wurde gespeicherr!' . "\n";
    }
    $error.= $lsmodel->getError();

    $lsPdfLengthByLid = $lsmodel->getLieferscheinPdfLength($lid);
    $lsPdfLength = $lsmodel->getLieferscheinPdfLength();

    if ($lsmodel->auftragAbschliessen()) {
        $msg.= 'Auftrag wurde abgeschlossen!' . "\n";
    }
    $error.= $lsmodel->getError();

    if (umzugsantrag_mailinform($aid, 'abgeschlossen', 'Ja')) {
        $msg.= 'Unterzeichneter Lieferschein wurde per Mail an den Kunden verschickt!' . "\n";
    }

}

if ($error) {
    $errors['procesing'] = $error;
}

header('Content-Type: application/json; charset=UTF-8');
echo json_encode([
    'type' => (!$errors && $lid) ? 'success' : 'error',
    'errors' => $errors,
    'msg' => $msg,
    'id' => $lid,

    'lid' => $lid,
    'strlen_lsPdf' => strlen($lsPdf),
    'lsPdfLengthByLid' => $lsPdfLengthByLid,
    'lPdfLength' => $lsPdfLength,
    'auftrag' => $auftrag,
    'leistungen' => $leistungen,
    'lsdaten' => $lsdaten
]);



