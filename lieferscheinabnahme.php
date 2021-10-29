<?php 
require_once('header.php');

require_once($InclBaseDir . 'umzugsantrag.inc.php');
require_once($InclBaseDir . 'umzugsmitarbeiter.inc.php');

require_once($ModulBaseDir . '/lieferschein/lieferschein.model.php');

if (function_exists('activity_log')) {
    register_shutdown_function('activity_log');
}

$id = 0;
$input = [];
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
}

$lsmodel = new LS_Model((int)$aid);

$aPostInput = $_POST;
$input = $lsmodel->validateInput($aPostInput);
if (isset($input['sig_mt_dataurl_geodata'])) {
    die('#' . __LINE__ . ' ' . __FILE__ . ' ' . print_r(compact('input'), 1));
}
$errors = $lsmodel->getValidationErrors();

if (false !== $input) {
    $id = $lsmodel->save($input);

    $auftrag = $lsmodel->getAuftragsdaten();
    $leistungen = $lsmodel->getLeistungen();
    $lsdaten = $lsmodel->getData();

    $pdf = new \module\Pdf\MertensLieferscheinPDF();
    $pdf->setAuftragsdaten($auftrag);
    $pdf->setLeistungen($leistungen);
    $pdf->setLieferscheindaten($lsdaten);
    $pdf->create();
    $lsPdf = $pdf->Output('lsdoc.pdf', 'S' );

    $lsmodel->updateLieferscheinPdf($lsPdf);
    $error.= $lsmodel->getError();

    $lsmodel->auftragAbschliessen();
    $error.= $lsmodel->getError();

}

if ($error) {
    $errors['procesing'] = $error;
}

header('Content-Type: application/json; charset=UTF-8');
echo json_encode([
    'type' => (!$errors && $id) ? 'success' : 'error',
    'errors' => $errors,
    'id' => $id,
]);



