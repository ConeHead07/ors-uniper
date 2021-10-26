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
$errors = [];
$sigFiles = [];
$tmpImg = '';
$tmpSize = 0;
$commaPos = false;
$base64Start = false;
$base64Data = '';

$lsmodel = new LS_Model();

$aPostInput = $_POST;
$input = $lsmodel->validateInput($aPostInput);
if (isset($input['sig_mt_dataurl_geodata'])) {
    die('#' . __LINE__ . ' ' . __FILE__ . ' ' . print_r(compact('input'), 1));
}
$errors = $lsmodel->getValidationErrors();

if (false !== $input) {
    $id = $lsmodel->insert($input);
}

header('Content-Type: application/json; charset=UTF-8');
echo json_encode([
    'type' => (!$errors && $id) ? 'success' : 'error',
    'errors' => $errors,
    'id' => $id,
]);



