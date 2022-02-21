<?php
require_once('header.php');
require_once($SitesBaseDir . '/umzugsantrag_sendmail.php');
require_once($InclBaseDir . 'umzugsantrag.inc.php');

$aid = (int)getRequest('aid', 0);
$numGelesen = (int)getRequest('numGelesen', 0);
$errors = [];

$data = compact('aid', 'numGelesen');

function bmResponseError(int $aid, array $errors = []) {
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode([
        'type' => 'error',
        'errors' => $errors,
        'msg' => implode("\n", $errors),
        'aid' => $aid

    ]);
    exit;
}

function bmResponseSuccess(array $daten = []) {
    header('Content-Type: application/json; charset=UTF-8');
    $daten['type'] = 'success';
    echo json_encode($daten);
    exit;
}

if (!$aid) {
    $errors[] = 'Fehlende Auftrags-ID!';
}

if (count($errors)) {
    return bmResponseError($aid, $errors);
}

$sqlAuftrag = 'SELECT 
 aid, neue_bemerkungen_fuer_admin, antragsteller_uid
 FROM mm_umzuege WHERE aid = :aid';

$antrag = $db->query_row($sqlAuftrag, ['aid' => $aid]);

if (empty($antrag)) {
    $errors[] = 'Es wurde kein Auftrag mit der ID ' . $aid . ' gefunden!';
}

if (count($errors)) {
    return bmResponseError($aid, $errors);
}

if (!$numGelesen) {
    $numGelesen = (int)$antrag['neue_bemerkungen_fuer_admin'];
}

if ((int)$antrag['neue_bemerkungen_fuer_admin'] < 1) {
    bmResponseSuccess([ 'aid' => $aid, 'neue_bemerkungen_fuer_admin' => 0] );
}


$NL = "\n";
$sqlUpdate = 'UPDATE mm_umzuege SET 
  neue_bemerkungen_fuer_admin = IF(0 > neue_bemerkungen_fuer_admin - 5, 0, neue_bemerkungen_fuer_admin - 5) 
  WHERE aid = :aid
';
$success = $db->query($sqlUpdate, [
    'aid' => $aid,
    'numGelesen' => $numGelesen
]);

bmResponseSuccess([
    'aid' => $aid,
    'neue_bemerkungen_fuer_admin' => 0,
    'dbsuccess' => $success
]);
