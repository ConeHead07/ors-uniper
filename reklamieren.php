<?php 
require_once('header.php');
require_once($SitesBaseDir . '/umzugsantrag_sendmail.php');
require_once($InclBaseDir . 'umzugsantrag.inc.php');

$grund = getRequest('grund', '');
$aid = (int)getRequest('aid', 0);
$inputReklaLeistungen = (array)getRequest('leistungen', []);
$leistungsIds = [];
$leistungsMengen = [];
$leistungsMengenById = [];
$log = [];
$errors = [];
$sFormattedGrund = '';


if (count($inputReklaLeistungen)) {
    $log[] = __LINE__;
    $leistungsIds = [];
    $leistungsMengen = [];
    $tmpLeistungsMengenById = [];

    foreach($inputReklaLeistungen as $_lstg) {
        $log[] = __LINE__;
        $_id = (int)$_lstg['leistung_id'];
        $_mng = ((int)$_lstg['menge'] > 0 ) ? (int)$_lstg['menge'] : 1;

        $leistungsIds[] = $_id;
        $leistungsMengen[] = $_mng;
        $tmpLeistungsMengenById[$_id] = $_mng;
    }
    $log[] = __LINE__;
    $leistungsMengenById = $tmpLeistungsMengenById;
}

function reklaResponseError(int $aid, array $errors = []) {
    global $inputReklaLeistungen, $leistungsIds, $leistungsMengen, $leistungsMengenById, $log;
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode([
        'type' => 'error',
        'errors' => $errors,
        'msg' => implode("\n", $errors),
        'aid' => $aid,
        'inputReklaLeistungen' => $inputReklaLeistungen,
        'leistungsIds' => $leistungsIds,
        'leistungsMengen' => $leistungsMengen,
        'leistungsMengenByIds' => $leistungsMengenById,
        'log' => $log

    ]);
    exit;
}

function reklaResponseSuccess(array $daten = []) {
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

    $result = 'Aufnahme einer Reklamation von :name am :datumzeit.' . "\n";
    $result = strtr($result, [ ':name' => $uname, ':datumzeit' => $datumzeit]);

    $result.= 'Reklamierte Leistungen: ' . "\n";
    foreach($leistungen as $lstg) {
        $result.= "- $lstg\n";
    }
    if (trim($grund)) {
        $result.= 'Begründung der Reklamation' . "\n";
        $result.= $grund;
    } else {
        $result.= 'Ohne Begründung' . "\n";
    }

    return $result;
}

if (!$aid) {
    $errors[] = 'Fehlende Auftrags-ID!';
}
if (!trim($grund)) {
    $errors[] = 'Für die Reklamation ist die Angabe eines Grundes erforderlich!';
}
if (!is_array($leistungsIds) || empty($leistungsIds)) {
    $errors[] = 'Für die Reklamation ist die Angabe der Leistungen erforderlich!';
} else {

    $leistungsIds = array_filter($leistungsIds, 'is_numeric');
    $leistungsIds = array_map('intval', $leistungsIds);
    $leistungsIds = array_unique($leistungsIds);

    if (empty($leistungsIds)) {
        $errors[] = 'Für die Reklamation wurden keine Artikel/Leistungen ausgewählt!';
    }
}

if (count($errors)) {
    return reklaResponseError($aid, $errors);
}

$lids = implode(',', $leistungsIds);


$sqlAuftrag = 'SELECT 
 anrede, name, vorname, email, fon, strasse, plz, ort, land, ansprechpartner,
 ansprechpartner_email, ansprechpartner_fon, bemerkungen, antragsteller_uid
 FROM mm_umzuege WHERE aid = :aid';

$sqlLstgRefs = 'SELECT * FROM mm_umzuege_leistungen WHERE aid = :aid AND leistung_id IN(' . $lids . ')';

$sqlLeistungen = 'SELECT 
    ktl.leistung_id, ktl.Bezeichnung, ktl.Groesse, ktl.Farbe, ktg.leistungskategorie,
    CONCAT(
      IF (TRIM(IFNULL(ktg.leistungskategorie, "")) = "", "Ohne Kategorie: ", CONCAT(ktg.leistungskategorie, ": ")),
      ktl.Bezeichnung,
      IF(TRIM(IFNULL(ktl.Farbe, ""))="", "", CONCAT(", ", ktl.Farbe)), 
      IF(TRIM(IFNULL(ktl.Farbe, ""))="", "", CONCAT(", ", ktl.Farbe)) 
    ) AS Leistung
 FROM mm_leistungskatalog AS ktl 
 JOIN mm_leistungskategorie ktg ON(ktl.leistungskategorie_id = ktg.leistungskategorie_id)
 WHERE ktl.leistung_id IN (' . $lids . ')';

$antrag = $db->query_row($sqlAuftrag, ['aid' => $aid]);
$akLeistungenRefs = $db->query_rows($sqlLstgRefs, 0, ['aid' => $aid]);
$akLeistungen = $db->query_rows($sqlLeistungen);

if (empty($antrag)) {
    $errors[] = 'Es wurde kein Auftrag mit der ID ' . $aid . ' gefunden!';
}
if (empty($akLeistungenRefs)) {
    $errors[] = 'Es wurden keine im Auftrag enthaltenen Leistungen ausgewählt!';
}

if (count($errors)) {
    return reklaResponseError($aid, $errors);
}

$aGrundLeistungen = [];
foreach($akLeistungen as $_lstg) {
    $_gl = $_lstg['Leistung'];
    $_lid = $_lstg['leistung_id'];
    if (!empty($leistungsMengenById[$_lid])) {
        $_gl.= ', Rekla-Menge: ' . $leistungsMengenById[$_lid];
    }
    $aGrundLeistungen[] = $_gl;
}

$sFormattedGrund = getFormattedBemerkung($grund, $aGrundLeistungen);


$colNames = ['ref_aid', 'umzug', 'service', ];
$quotedDaten = [ $aid, $db::quote('Rekla'), $db::quote('Rekla') ];
$antrag['token'] = $db->expr('SUBSTR( MD5( CONCAT_WS("-", CURRENT_TIMESTAMP, ' . $db::quote($user['uid']) . ', RAND() )  ) ,5, 10)');
$antrag['bemerkungen'] = $sFormattedGrund;
$antrag['antragsdatum'] = $db->expr('NOW()');
$antrag['antragsstatus'] = 'gesendet';
$antrag['umzugsstatus'] = 'beantragt';
$antrag['umzugsstatus_vom'] = $db->expr('NOW()');
$antrag['created_uid'] = $user['uid'];
$antrag['createdby'] = $user['user'];
$antrag['created'] = $db->expr('NOW()');
foreach($antrag as $k => $v) {
    $colNames[] = $k;
    $quotedDaten[] = $db::quote($v);
}

$db->query(
    'INSERT INTO mm_umzuege (' . implode(', ', $colNames) . ')
 VALUES(' . implode(',', $quotedDaten) . ')'
);

$reklaAid = $db->insert_id();
if (!$reklaAid) {
    return reklaResponseError($aid, ['Systemfehler: Reklamation konnte nicht angelegt werden!']);
}

$NL = "\n";
$sqlStatusUpdate = 'UPDATE mm_umzuege SET 
  reklamiert_am = :reklamiert_am,
  reklamiert_von = :reklamiert_von,
  bemerkungen = CONCAT(bemerkungen, IF(TRIM(IFNULL(bemerkungen, ""))="", "", :separator), :grund)
  WHERE aid = :aid
';
$db->query($sqlStatusUpdate, [
    'reklamiert_am' => $db->expr('NOW()'),
    'reklamiert_von' => $user['user'],
    'separator' => "\n\n",
    'grund' => $sFormattedGrund,
    'aid' => $aid
]);

$reklaLeistungen = [];
foreach($akLeistungenRefs as $_lstg) {
    $_id = (int)$_lstg['id'];
    $_lid = $_lstg['leistung_id'];
    $_rekla = $_lstg;
    unset($_rekla['id']);
    $_rekla['aid'] = $reklaAid;
    if (!empty($leistungsMengenById[$_lid])) {
        $_rekla['menge_mertens'] = (int)$leistungsMengenById[$_lid];
    } else {
        $_rekla['menge_mertens'] = (int)$_lstg['menge_mertens'];
    }
    $reklaLeistungen[] = $_rekla;

    $_sqlUp = 'UPDATE mm_umzuege_leistungen SET 
menge_rekla = ' . $_rekla['menge_mertens'] . ', 
menge2_rekla = ' . $_rekla['menge2_mertens'] . ' 
WHERE id = ' . $_id;
    $db->query($_sqlUp);
}

foreach( $reklaLeistungen as $_lstg) {
    $_data = [
        'aid' => $reklaAid,
        'leistung_id' => $_lstg['leistung_id'],
        'menge_mertens' => $_lstg['menge_mertens'],
        'menge2_mertens' => $_lstg['menge2_mertens'],
        'menge_property' => $_lstg['menge_property'],
        'menge2_property' => $_lstg['menge2_property'],
        'createdby' => 'mertens',
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

if (umzugsantrag_mailinform($aid, 'reklamation', $reklaAid)) {
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

reklaResponseSuccess([
    'aid' => $aid,
    'leistungen' => $leistungsMengenById,
    'reklaAid' => $reklaAid
]);
