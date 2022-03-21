<?php
require_once dirname(__DIR__) . '/header.php';
require_once $InclBaseDir . 'php_json.php';
require_once __DIR__ . '/umzugsantrag_stdlib.php';
require_once $InclBaseDir.'umzugsgruppierungen.lib.php';

// Get ID, falls Antrag bereits vorhanden
$AID = getRequest('aid','');
$UID = getRequest('uid','');
$KID = getRequest('kid','');

if (empty($AID) && empty($UID) && empty($KID)) {
    die('Es wurde keine ID übergeben');
}

if (empty($UID) && !empty($AID) ) {
    $UID = $db->query_one('SELECT antragsteller_uid FROM mm_umzuege WHERE aid = :aid', [ 'aid' => $AID]);
    if (empty($UID)) {
        die('Ungültige Auftrags-ID');
    }
}

if (empty($UID) && !empty($KID)) {
    $UID = $db->query_one('SELECT uid FROM mm_user WHERE personalnr = :kid', [ 'kid' => $KID]);
    if (empty($UID)) {
        die('Ungültige KID ' . $KID);
    }
}

$UID = (int)$UID;

$kunde = $db->query_row('SELECT * FROM mm_user WHERE uid = :uid', [ 'uid' => $UID]);

if (empty($kunde)) {
    die('Es konnten kein User mit der ID ' . $UID . ' gefunden werden!');
}


if (!in_array($user['gruppe'], [ 'admin', 'kunde_report', 'umzugsteam']) && $kunde['uid'] != $user['uid']) {
    die('UNERLAUBTER ZUGRIFF! Zugriff nur für Administratoren');
}

require_once $InclBaseDir . 'umzugsantrag.inc.php';
require_once $InclBaseDir . 'umzugsmitarbeiter.inc.php';
require_once $InclBaseDir . 'umzugsanlagen.inc.php';
require_once $InclBaseDir . 'leistungskatalog.inc.php';
require_once $InclBaseDir . 'dienstleister.inc.php';

$kundenAuftraege = getAllOtherUserAuftraegeByUID($UID);

$Tpl = new myTplEngine();
$Tpl->assign('aItems', $kundenAuftraege);

echo '<h2>' . $kunde['user'] . ' KID: ' . $kunde['personalnr'] . ' (UID: ' . $kunde['uid'] . ')</h2>';
echo $Tpl->fetch('admin_umzugsformular_auftraege.tpl.html');
