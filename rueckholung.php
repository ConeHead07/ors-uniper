<?php 
require_once('header.php');
require_once($SitesBaseDir . '/umzugsantrag_sendmail.php');
require_once($InclBaseDir . 'umzugsantrag.inc.php');

$grund = getRequest('grund', '');
$aid = (int)getRequest('aid', 0);
$inputLeistungen = (array)getRequest('leistungen', []);
$inputLeistungsMengenByLID = [];
$errors = [];

if (count($inputLeistungen)) {
    $log[] = __LINE__;

    foreach($inputLeistungen as $_lstg) {
        $log[] = __LINE__;
        $_id = (int)$_lstg['leistung_id'];
        $_mng = ((float)$_lstg['menge'] > 0 ) ? (float)$_lstg['menge'] : 1;

        if ($_id === 0 || $_mng === 0) {
            continue;
        }
        $inputLeistungsMengenByLID[$_id] = $_mng;
    }
}

function rueckResponseError(int $aid, array $errors = []) {
    global $inputLeistungen, $inputLeistungsMengenByLID, $log;
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode([
        'type' => 'error',
        'errors' => $errors,
        'msg' => implode("\n", $errors),
        'aid' => $aid,
        'inputReklaLeistungen' => $inputLeistungen,
        'leistungsMengenByIds' => $inputLeistungsMengenByLID,
        'log' => $log

    ]);
    exit;
}

function rueckResponseSuccess(array $daten = []) {
    header('Content-Type: application/json; charset=UTF-8');
    $daten['type'] = 'success';
    echo json_encode($daten);
    exit;
}

function getFormattedBemerkung(string $grund, array $leistungen) {
    global $user;

    $uid = $user['uid'];
    $uname = $user['user'];
    $datumzeit = date('d.m.Y') . ' um ' . date('H:i');

    $result = 'Rückholung angelegt von :name am :datumzeit.' . "\n";
    $result = strtr($result, [ ':name' => $uname, ':datumzeit' => $datumzeit]);

    $result.= 'Leistungen der Rückholung: ' . "\n";
    foreach($leistungen as $lstg) {
        $result.= "- $lstg\n";
    }
    if (trim($grund)) {
        $result.= 'Begründung der Rückholung' . "\n";
        $result.= $grund;
    } else {
        $result.= 'Ohne Begründung' . "\n";
    }

    return $result;
}

if (!$aid) {
    $errors[] = 'Fehlende Auftrags-ID!';
}

if (empty($inputLeistungsMengenByLID)) {
    $errors[] = 'Für die Rückholung ist die Angabe der Leistungen erforderlich!';
}

if (count($errors)) {
    return rueckResponseError($aid, $errors);
}

$lids = implode(',', array_keys($inputLeistungsMengenByLID));


$sqlAuftrag = 'SELECT 
 anrede, name, vorname, email, fon, strasse, plz, ort, land, ansprechpartner,
 ansprechpartner_email, ansprechpartner_fon, bemerkungen, antragsteller_uid
 FROM mm_umzuege WHERE aid = :aid';

$sqlLeistungen = 'SELECT 
    ktl.leistung_id, ktl.Bezeichnung, ktl.Groesse, ktl.Farbe, ktg.leistungsart, ktg.leistungskategorie,
    ktl.Bezeichnung AS Leistung
 FROM mm_leistungskatalog AS ktl 
 JOIN mm_leistungskategorie ktg ON(ktl.leistungskategorie_id = ktg.leistungskategorie_id)
 WHERE ktl.leistung_id IN (' . $lids . ')';

$antrag = $db->query_row($sqlAuftrag, ['aid' => $aid]);

if (empty($antrag)) {
    $errors[] = 'Es wurde kein Auftrag mit der ID ' . $aid . ' gefunden!';
}
$uid = $antrag['antragsteller_uid'];

if (empty($errors)) {

    $akLeistungen = $db->query_rows($sqlLeistungen);

    if (count($akLeistungen) < count(array_keys($inputLeistungsMengenByLID))) {
        $errors[] = 'Es konnten nicht alle ausgewählten Leistungen im Katalog gefunden werden!';
    }
}

if (empty($errors)) {

    foreach($akLeistungen as $_lstg) {
        $_lid = $_lstg['leistung_id'];
        $akLeistungenByLID[$_lid] = $_lstg;
    }

    foreach($inputLeistungsMengenByLID as $_lid => $_mng) {

        if (empty($akLeistungenByLID[$_lid])) {
            $errors[] = 'Systemfehler: Für Leistung ' . $_lid . ' konnten keine Artikeldaten zugeordnet werden.' . "\n"
                . 'Missing akLeistungenFullByLID[' . $_lid . ']!';
        }
    }
}

if (count($errors)) {
    return rueckResponseError($aid, $errors);
}

$aGrundLeistungen = [];
foreach($akLeistungen as $_lstg) {
    $_gl = $_lstg['Leistung'];
    $_lid = $_lstg['leistung_id'];
    if (!empty($inputLeistungsMengenByLID[$_lid])) {
        $_gl.= ', RH-Menge: ' . $inputLeistungsMengenByLID[$_lid];
    }
    $aGrundLeistungen[] = $_gl;
}

$sFormattedGrund = getFormattedBemerkung($grund, $aGrundLeistungen);

$colNames = ['ref_aid', 'umzug', 'service', ];
$quotedDaten = [ $aid, $db::quote('Ja'), $db::quote('Ja') ];
$antrag['token'] = $db->expr('SUBSTR( MD5( CONCAT_WS("-", CURRENT_TIMESTAMP, ' . $db::quote($user['uid']) . ', RAND() )  ) ,5, 10)');
$antrag['bemerkungen'] = $sFormattedGrund;
$antrag['antragsdatum'] = $db->expr('NOW()');
$antrag['umzugsstatus'] = 'beantragt';
$antrag['umzugsstatus_vom'] = $db->expr('NOW()');
$antrag['created'] = $db->expr('NOW()');
foreach($antrag as $k => $v) {
    $colNames[] = $k;
    $quotedDaten[] = $db::quote($v);
}

$db->query(
    'INSERT INTO mm_umzuege (' . implode(', ', $colNames) . ')
 VALUES(' . implode(',', $quotedDaten) . ')'
);

$newAid = $db->insert_id();
if (!$newAid) {
    return rueckResponseError($aid, ['Systemfehler: Rückholung konnte nicht angelegt werden!']);
}

$NL = "\n";
$sqlStatusUpdate = 'UPDATE mm_umzuege SET 
  rueckholung_am = :rueckholung_am,
  rueckholung_von = :rueckholung_von,
  bemerkungen = CONCAT(bemerkungen, IF(TRIM(IFNULL(bemerkungen, ""))="", "", :separator), :grund)
  WHERE aid = :aid
';

$db->query($sqlStatusUpdate, [
    'rueckholung_am' => $db->expr('NOW()'),
    'rueckholung_von' => $user['user'],
    'separator' => "\n\n",
    'grund' => $sFormattedGrund,
    'aid' => $aid
]);

foreach($inputLeistungsMengenByLID as $_lid => $_mng) {
    $_data = [
        'aid' => $newAid,
        'leistung_id' => $_lid,
        'hauptauftragsmenge' => $_mng,
        'menge_mertens' => $_mng,
        'menge2_mertens' => 1,
        'menge_property' => $_mng,
        'menge2_property' => 1,
        'createdby' => 'property',
    ];

    $_vals = array_values($_data);
    $_keys = array_keys($_data);
    $_qVals = array_map(function($val) use($db) { return $db::quote($val); }, $_vals);
    $_sqlIns = 'INSERT INTO mm_umzuege_leistungen(' . implode(', ', $_keys) . ')'
        . ' VALUES('
        . implode(', ', $_qVals)
        . ')';
    $db->query($_sqlIns);
}

if (false && umzugsantrag_mailinform($aid, 'rueckholung', $newAid)) {
    $iNumMails = umzugsantrag_mailinform_get_numMails();
    if ($iNumMails > 0) {
        if ($user['gruppe'] === 'admin') {
            $msg .= "Mail wurde gesendet [Anzahl: $iNumMails]!<br>\n";
        } else {
            $msg .= "Ihre Daten wurden weitergeleiter!<br>\n";
        }
    }
} else {
    if ($user['gruppe'] === 'admin') {
        $error.= "Fehler beim Mailversand [#213]!<br>\n";
    } else {
        $error.= "Fehler im Nachrichtensystem [#215]!<br>\n";
    }
}

rueckResponseSuccess([
    'aid' => $aid,
    'leistungen' => $inputLeistungsMengenByLID,
    'newAid' => $newAid
]);
