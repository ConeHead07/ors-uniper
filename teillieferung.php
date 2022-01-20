<?php 
require_once('header.php');
require_once($SitesBaseDir . '/umzugsantrag_sendmail.php');
require_once($InclBaseDir . 'umzugsantrag.inc.php');

$grund = getRequest('grund', '');
$aid = (int)getRequest('aid', 0);
$inputTeilLeistungen = (array)getRequest('leistungen', []);
$leistungsIds = [];
$inputLeistungsMengen = [];
$inputLeistungsMengenByLID = [];
$log = [];
$errors = [];
$sFormattedGrund = '';
$akLeistungsMengenByLID = [];
$akLeistungenFullByLID = [];
$aSubMengenByLID = [];
$akMengenUpdatesByLID = [];


if (count($inputTeilLeistungen)) {
    $log[] = __LINE__;
    $leistungsIds = [];
    $inputLeistungsMengen = [];
    $tmpLeistungsMengenById = [];

    foreach($inputTeilLeistungen as $_lstg) {
        $log[] = __LINE__;
        $_id = (int)$_lstg['leistung_id'];
        $_mng = ((float)$_lstg['menge'] > 0 ) ? (float)$_lstg['menge'] : 1;

        $leistungsIds[] = $_id;
        $inputLeistungsMengen[] = $_mng;
        $tmpLeistungsMengenById[$_id] = $_mng;
    }
    $log[] = __LINE__;
    $inputLeistungsMengenByLID = $tmpLeistungsMengenById;
}

function teilResponseError(int $aid, array $errors = []) {
    global $inputTeilLeistungen, $leistungsIds, $inputLeistungsMengen, $inputLeistungsMengenByLID, $log;
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode([
        'type' => 'error',
        'errors' => $errors,
        'msg' => implode("\n", $errors),
        'aid' => $aid,
        'inputReklaLeistungen' => $inputTeilLeistungen,
        'leistungsIds' => $leistungsIds,
        'leistungsMengen' => $inputLeistungsMengen,
        'leistungsMengenByIds' => $inputLeistungsMengenByLID,
        'log' => $log

    ]);
    exit;
}

function teilResponseSuccess(array $daten = []) {
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
if (false && !trim($grund)) {
    $errors[] = 'Für die Teillieferung ist die Angabe eines Grundes erforderlich!';
}
if (!is_array($leistungsIds) || empty($leistungsIds)) {
    $errors[] = 'Für die Teillieferung ist die Angabe der Leistungen erforderlich!';
} else {

    $leistungsIds = array_filter($leistungsIds, 'is_numeric');
    $leistungsIds = array_map('intval', $leistungsIds);
    $leistungsIds = array_unique($leistungsIds);

    if (empty($leistungsIds)) {
        $errors[] = 'Für die Teillieferung wurden keine Artikel/Leistungen ausgewählt!';
    }
}

if (count($errors)) {
    return teilResponseError($aid, $errors);
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
      IF(TRIM(IFNULL(ktl.Farbe, "")) = "", "", CONCAT(", ", ktl.Farbe)), 
      IF(TRIM(IFNULL(ktl.Groesse, "")) = "", "", CONCAT(", ", ktl.Groesse)) 
    ) AS Leistung
 FROM mm_leistungskatalog AS ktl 
 JOIN mm_leistungskategorie ktg ON(ktl.leistungskategorie_id = ktg.leistungskategorie_id)
 WHERE ktl.leistung_id IN (' . $lids . ')';

$sqlUnterAuftragsMengen = 'SELECT
    a.aid, a.ref_aid, a.service, a.umzug, a.umzugstermin AS liefertermin, a.umzugsstatus,
    al.id, al.leistung_id, menge_mertens AS menge, menge_rekla AS rekla, menge_geliefert AS geliefert, 
    al.lieferdatum AS geliefert_am
    FROM mm_umzuege a
    JOIN mm_umzuege_leistungen al ON (a.aid = al.aid)
    WHERE a.ref_aid = :aid AND al.leistung_id IN(' . $lids . ') 
    AND a.umzugsstatus NOT IN("temp", "storniert") AND a.abgeschlossen NOT LIKE "Storniert"
';

$antrag = $db->query_row($sqlAuftrag, ['aid' => $aid]);
$akLeistungenRefs = $db->query_rows($sqlLstgRefs, 0, ['aid' => $aid]);

if (empty($antrag)) {
    $errors[] = 'Es wurde kein Auftrag mit der ID ' . $aid . ' gefunden!';
}
if (empty($akLeistungenRefs)) {
    $errors[] = 'Es wurden keine im Auftrag enthaltenen Leistungen ausgewählt!';
}

if (empty($errors)) {

    $akLeistungen = $db->query_rows($sqlLeistungen);

    foreach($akLeistungenRefs as $_lstg) {
        $_lid = $_lstg['leistung_id'];
        $akLeistungsMengenByLID[$_lid] = $_lstg;
    }

    foreach($akLeistungen as $_lstg) {
        $_lid = $_lstg['leistung_id'];
        $akLeistungenFullByLID[$_lid] = $_lstg;
    }

    $aSubLeistungsMengen = $db->query_rows($sqlUnterAuftragsMengen, 0, ['aid' => $aid]);

    foreach ($aSubLeistungsMengen as $_lstg) {
        $_lid = $_lstg['leistung_id'];
        $_service = strtolower($_lstg['service']);
        if (!isset($aSubMengenByLID[$_lid])) {
            $aSubMengenByLID[$_lid]['total'] = ['menge' => 0, 'rekla' => 0, 'teil' => 0];
            $aSubMengenByLID[$_lid]['teil'] = [];
            $aSubMengenByLID[$_lid]['rekla'] = [];
        }
        $aSubMengenByLID[$_lid]['total']['menge'] += (float)$_lstg['menge'];
        $aSubMengenByLID[$_lid]['total'][$_service] += (float)$_lstg['menge'];

        $aSubMengenByLID[$_lid][$_service][] = $_lstg;
    }

    foreach($inputLeistungsMengenByLID as $_lid => $_mng) {

        if (empty($akLeistungsMengenByLID[$_lid])) {
            $errors[] = 'Systemfehler: Für Leistung ' . $_lid . ' konnten keine Mengen zugeordnet werden.' . "\n"
            . 'Missing akLeistungsMengenByLID[' . $_lid . ']!';
        }

        if (empty($akLeistungenFullByLID[$_lid])) {
            $errors[] = 'Systemfehler: Für Leistung ' . $_lid . ' konnten keine Artikeldaten zugeordnet werden.' . "\n"
                . 'Missing akLeistungenFullByLID[' . $_lid . ']!';
        }

        $_aSubs = $akLeistungsMengenByLID[$_lid] ?? [];
        $_aMengen = $akLeistungsMengenByLID[$_lid];
        $_aArtikel = $akLeistungenFullByLID[$_lid];
        $_auftragsmenge = (float)$_aMengen['menge_mertens'];
        $_hauptmenge = (float)$_aMengen['hauptauftragsmenge'];
        $_submenge = (!empty($_aSubs) && isset($_aSubs['total']['menge'])) ? (float)$_aSubs['total']['menge'] : 0;

        if (!$_auftragsmenge) {
            $errors[] = $_aArtikel['Leistung'] .': Die restliche Auftragsmenge liegt bereits bei 0.';
        } elseif ($_auftragsmenge < $_mng) {
            $errors[] = $_aArtikel['Leistung'] .': Die neue Menge der Teillieferung ' . number_format($_mng, 2, ',', '.')
                . ' ist größer als die Auftragsmenge ' . number_format($_auftragsmenge, 2, ',', '.') . '.';
        } elseif ($_submenge > 0.0 && $_hauptmenge < ($_mng + $_submenge)) {
            $_sm = (float)(int)$_submenge       === $_submenge       ? (int)$_submenge       : number_format($_submenge, 2, ',', '.');
            $_tm = (float)(int)$_aSubs['teil']  === $_aSubs['teil']  ? (int)$_aSubs['teil']  : number_format($_aSubs['teil'], 2, ',', '.');
            $_rm = (float)(int)$_aSubs['rekla'] === $_aSubs['rekla'] ? (int)$_aSubs['rekla'] : number_format($_aSubs['rekla'], 2, ',', '.');
            $_hm = (float)(int)$_hauptmenge     === $_hauptmenge     ? (int)$_hauptmenge     : number_format($_hauptmenge, 2, ',', '.');
            $errors[] = $_aArtikel['Leistung'] .': Die Gesamtmenge (' . $_sm . ')'
                . ' an bestehenden Teillierferungen (' . $_tm . ') und Reklas ('  . $_rm .  ')'
                . ' überschreitet mit dieser Menge (' . $_mng . ')'
                . ' die ursprüngliche Auftragsmenge (' . $_hm . ')!';
        }

        $akMengenUpdatesByLID[$_lid] = $_auftragsmenge - $_mng;
    }
}

if (count($errors)) {
    return teilResponseError($aid, $errors);
}

$aGrundLeistungen = [];
foreach($akLeistungen as $_lstg) {
    $_gl = $_lstg['Leistung'];
    $_lid = $_lstg['leistung_id'];
    if (!empty($inputLeistungsMengenByLID[$_lid])) {
        $_gl.= ', Teil-Menge: ' . $inputLeistungsMengenByLID[$_lid];
    }
    $aGrundLeistungen[] = $_gl;
}

$sFormattedGrund = getFormattedBemerkung($grund, $aGrundLeistungen);

$colNames = ['ref_aid', 'umzug', 'service', ];
$quotedDaten = [ $aid, $db::quote('Teil'), $db::quote('Teil') ];
$antrag['bemerkungen'] = $sFormattedGrund;
$antrag['antragsdatum'] = $db->expr('NOW()');
$antrag['umzugsstatus'] = 'beantragt';
$antrag['umzugsstatus_vom'] = $db->expr('NOW()');
foreach($antrag as $k => $v) {
    $colNames[] = $k;
    $quotedDaten[] = $db::quote($v);
}

$db->query(
    'INSERT INTO mm_umzuege (' . implode(', ', $colNames) . ')
 VALUES(' . implode(',', $quotedDaten) . ')'
);

$teilAid = $db->insert_id();
if (!$teilAid) {
    return teilResponseError($aid, ['Systemfehler: Teillieferung konnte nicht angelegt werden!']);
}

$NL = "\n";
$sqlStatusUpdate = 'UPDATE mm_umzuege SET 
  teilmenge_am = :teilmenge_am,
  teilmenge_von = :teilmenge_von,
  bemerkungen = CONCAT(bemerkungen, IF(TRIM(IFNULL(bemerkungen, ""))="", "", :separator), :grund)
  WHERE aid = :aid
';
$db->query($sqlStatusUpdate, [
    'teilmenge_am' => $db->expr('NOW()'),
    'teilmenge_von' => $user['user'],
    'separator' => "\n\n",
    'grund' => $sFormattedGrund,
    'aid' => $aid
]);
$sqlMengenUpdate = 'UPDATE mm_umzuege_leistungen 
SET 
  menge_mertens = :mngM,
  menge_property = :mngP
WHERE 
  aid = :aid 
  AND leistung_id = :lid
';
foreach($akMengenUpdatesByLID as $_lid => $_mng) {
    $db->query($sqlMengenUpdate, [
        'aid' => $aid,
        'lid' => $_lid,
        'mngM' => $_mng,
        'mngP' => $_mng
    ]);
}

$teilLeistungen = [];
foreach($akLeistungenRefs as $_lstg) {
    $_id = (int)$_lstg['id'];
    $_lid = $_lstg['leistung_id'];
    $_teil = $_lstg;
    unset($_teil['id']);
    $_teil['aid'] = $teilAid;
    if (!empty($inputLeistungsMengenByLID[$_lid])) {
        $_teil['menge_mertens'] = (int)$inputLeistungsMengenByLID[$_lid];
    } else {
        $_teil['menge_mertens'] = (int)$_lstg['menge_mertens'];
     }
    $teilLeistungen[] = $_teil;
}

foreach($teilLeistungen as $_lstg) {
    $_data = [
        'aid' => $teilAid,
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

if (false && umzugsantrag_mailinform($aid, 'teillieferung', $teilAid)) {
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

teilResponseSuccess([
    'aid' => $aid,
    'leistungen' => $inputLeistungsMengenByLID,
    'teilAid' => $teilAid
]);
