<?php
require_once 'header.php';
require_once $SitesBaseDir . '/umzugsantrag_sendmail.php';
require_once $SitesBaseDir . '/umzugsantrag_speichern.php';
require_once $InclBaseDir  . 'umzugsantrag.inc.php';

$aid = (int)getRequest('aid', 0);
$grund = getRequest('grund', '');
$bemerkung = getRequest('bemerkung', '');
$datum = getRequest('datum', '');
$zeit = getRequest('zeit', '');
$errors = [];

$data = compact('aid', 'grund', 'bemerkung', 'datum', 'zeit');

function zvResponseError(int $aid, array $errors = []) {
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode([
        'type' => 'error',
        'errors' => $errors,
        'msg' => implode("\n", $errors),
        'aid' => $aid,

    ]);
    exit;
}

function zvResponseSuccess(array $daten = []) {
    header('Content-Type: application/json; charset=UTF-8');
    $daten['type'] = 'success';
    echo json_encode($daten);
    exit;
}

function getFormattedBemerkung(string $grund, array $data) {
    global $user;

    $uid = $user['uid'];
    $uname = $user['user'];
    $datumzeit = date('d.m.Y') . ' um ' . date('H:i');

    $result = 'Erfolglose Zustellung gemeldet von :name am :datumzeit.' . "\n";
    $result = strtr($result, [ ':name' => $uname, ':datumzeit' => $datumzeit]);
    $result.= 'Datum: ' . date('d.m.Y', strtotime($data['datum'])) . "\n";
    $result.= 'Uhrzeit: ' . substr($data['zeit'], 0, 5) . "\n";
    $result.= 'Grund: ' . $data['grund'] . "\n";

    if (!empty($data['bemerkung'])) {
        $result.= 'Bemerkung: ' . "\n" . $data['bemerkung'] . "\n";
    }

    return $result;
}

if (!$aid) {
    $errors[] = 'Fehlende Auftrags-ID!';
}
if (!trim($grund)) {
    $errors[] = 'Für die Unzustellbarkeit ist die Angabe eines Grundes erforderlich!';
}

if (count($errors)) {
    return zvResponseError($aid, $errors);
}

$sqlAuftrag = 'SELECT 
 anrede, name, vorname, email, fon, strasse, plz, ort, land, ansprechpartner,
 ansprechpartner_email, ansprechpartner_fon, bemerkungen, antragsteller_uid
 FROM mm_umzuege WHERE aid = :aid';

$antrag = $db->query_row($sqlAuftrag, ['aid' => $aid]);

if (empty($antrag)) {
    $errors[] = 'Es wurde kein Auftrag mit der ID ' . $aid . ' gefunden!';
}

if (count($errors)) {
    return zvResponseError($aid, $errors);
}


$sFormattedGrund = getFormattedBemerkung($grund, $data);

$colNames = ['ref_aid', 'umzug', 'service', ];
$quotedDaten = [ $aid, $db::quote('Teil'), $db::quote('Teil') ];
$antrag['bemerkungen'] = $sFormattedGrund;


$db->query(
    'INSERT INTO mm_zustellversuche (aid, datum, uhrzeit, grund, bemerkung)
 VALUES(:aid, :datum, :uhrzeit, :grund, :bemerkung)', [
     'aid' => $aid,
        'datum' => $datum,
        'uhrzeit' => $zeit,
        'grund' => $grund,
        'bemerkung' => $bemerkung
    ]
);

$zvID = $db->insert_id();
if (!$zvID) {
    return zvResponseError($aid, ['Zustellversuch konnte nicht angelegt werden!']);
}

$NL = "\n";
$sqlStatusUpdate = 'UPDATE mm_umzuege SET 
  bemerkungen = CONCAT(bemerkungen, IF(TRIM(IFNULL(bemerkungen, ""))="", "", :separator), :grund)
  WHERE aid = :aid
';
$db->query($sqlStatusUpdate, [
    'separator' => "\n\n",
    'grund' => $sFormattedGrund,
    'aid' => $aid
]);

$inputItem = [
    'aid' => $aid,
    'id' => $aid,
    'add_bemerkungen' => $sFormattedGrund,
];
umzugsantrag_add_bemerkung($inputItem);
$error = '';

zvResponseSuccess([
    'aid' => $aid,
    'msg' => $msg,
    'error' => $error
]);
