<?php
require_once 'header.php';

require_once $InclBaseDir . 'umzugsantrag.inc.php';
require_once $InclBaseDir . 'umzugsmitarbeiter.inc.php';

require_once $ModulBaseDir . '/lieferschein/lieferschein.model.php';
require_once $SitesBaseDir . '/umzugsantrag_sendmail.php';
require_once $SitesBaseDir . '/umzugsantrag_speichern.php';

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

if (false && $lsmodel->getAuftragsStatus() === 'abgeschlossen') {
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
$lsmodel->bDebugValidation = true;
$input = $lsmodel->validateInput($aPostInput);
if (isset($input['sig_mt_dataurl_geodata'])) {
    die('#' . __LINE__ . ' ' . __FILE__ . ' ' . print_r(compact('input'), 1));
}
$errors   = $lsmodel->getValidationErrors();
$warnings = $lsmodel->getValidationWarnings();
$debugLog = $lsmodel->getDebugLog();
$aMissingLeistungsIds = $lsmodel->aMissingLeistungsIds;
$aMissungFPIds = $lsmodel->aMissingFPIds;

$aLeistungen = $lsmodel->getLeistungen();
function arrayFindFirst(array $list, $query) {
    if (!is_callable($query) && !is_array($query)) {
        return null;
    }

    if (is_callable($query)) {
        foreach($list as $idx => $row) {
            if ($query($row)) {
                return $row;
            }
        }
        return null;
    }

    foreach($list as $idx => $row) {
        foreach($query as $fld => $val) {
            if (!empty($val) && !empty($row[$fld]) && $val != $row[$fld]) {
                continue 2;
            }
        }
        return $row;
    }
    return null;
}
$aLidToItem = [];
$aLidToItem['f224'] = arrayFindFirst($aLeistungen, function($row) {
    return $row['leistung_id'] == 224;
});
$aLidToItem['f219'] = arrayFindFirst($aLeistungen, function($row) {
    return $row['leistung_id'] == 219;
});
$aLidToItem['a224'] = arrayFindFirst($aLeistungen, ['leistung_id' => 224]);
$aLidToItem['a219'] = arrayFindFirst($aLeistungen, ['leistung_id' => 219]);


$reklaBem = '';
$aLieferpos = isset($aPostInput['lieferpos']) ? $aPostInput['lieferpos'] : []; // {219: "rekla", 223: "fehlt", 224: "rekla"}#

$aReklas = [];
$aFehlt = [];
foreach($aLieferpos as $_id => $_stat) {
    switch($_stat) {
        case 'fehlt':
            $aFehlt[] = arrayFindFirst($aLeistungen, [ 'leistung_id' => $_id]);
            break;

        case 'rekla':
            $aRekla[] = arrayFindFirst($aLeistungen, [ 'leistung_id' => $_id]);
            break;
    }
}

$incompleteMsg = '';
if (!empty($errors['etikettierung_erfolgt'])) {
    $incompleteMsg.= $errors['etikettierung_erfolgt'] . "\n";
}
if (!empty($warnings['etikettierung_erfolgt'])) {
    $incompleteMsg.= $warnings['etikettierung_erfolgt'] . "\n";
}
if (!empty($errors['funktionspruefung_erfolgt'])) {
    $incompleteMsg.= $errors['funktionspruefung_erfolgt'] . "\n";
}
if (!empty($warnings['funktionspruefung_erfolgt'])) {
    $incompleteMsg.= $warnings['funktionspruefung_erfolgt'] . "\n";
}

if (count($aFehlt)) {
    $incompleteMsg.= count($aFehlt) . " bestellte Leistungen fehlten bei der Auslieferung:\n";
    foreach($aFehlt as $_row) {
        $incompleteMsg.= '- '
            . $_row['Kategorie'] . ': '
            . trim(trim($_row['Bezeichnung'] . ', ' . $_row['Groesse'] . $_row['Farbe'] ), ',') . "\n";
    }
}

if (count($aFehlt)) {
    $incompleteMsg.= count($aFehlt) . " bestellte Leistungen wurden bei der Auslieferung reklamiert:\n";
    foreach($aFehlt as $_row) {
        $incompleteMsg.= '- '
            . $_row['Kategorie'] . ': '
            . trim(trim($_row['Bezeichnung'] . ', ' . $_row['Groesse'] . $_row['Farbe'] ), ',') . "\n";
    }
}
$rekla_bemerkung = $aPostInput['rekla_bemerkung'] ?? '';

if ($rekla_bemerkung) {
    $incompleteMsg.= "\nBemerkung vom Auslieferteam:\n" . $rekla_bemerkung . "\n";
}

if ($incompleteMsg) {
    $intro = 'Automatisiert generierte Informationen zur Auslieferung' . "\n";
    $incompleteMsg = $intro . $incompleteMsg;
}

if (0) {
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode([
        'type' => 'debug',
        'errors' => $errors,
        'debug' => [
            compact('aPostInput',
                'input',
                'errors',
                'warnings',
                'debugLog',
                'aMissingLeistungsIds',
                'aMissungFPIds',
                'aLidToItem',
                'incompleteMsg')
        ]
    ]);
    // exit;
}

$debugLog = [ __FILE__ ];
$debugLog[] = __LINE__;

if ($input) {
    $debugLog[] = __LINE__;
    $input['schlussbericht'] = $incompleteMsg;
    $lid = $lsmodel->save($input);

    $auftrag = $lsmodel->getAuftragsdaten();
    $leistungen = $lsmodel->getLeistungen();
    $lsdaten = $lsmodel->getData();

    $debugLog[] = __LINE__;
    $pdf = new \module\Pdf\MertensLieferscheinPDF();
    $pdf->setAuftragsdaten($auftrag);
    $pdf->setLeistungen($leistungen);
    $pdf->setLieferscheindaten($lsdaten);
    $pdf->setBemerkung($rekla_bemerkung);
    $pdf->create();
    $debugLog[] = __LINE__;
    $lsPdf = $pdf->Output('lsdoc_' . $lid . '.pdf', 'S' );

    // $iNumMengenUpdates = update_gelieferte_mengen($aid, $aLeistungenMitMengen, $lieferdatum, $lieferschein_id);

    if (empty($lsPdf) || strlen($lsPdf) < 10) {
        $error = 'Lieferschein konnte nicht generiert werden!';
    } else {
        $msg.= 'Lieferchein wurde generiert (' . number_format(strlen($lsPdf) /1024, 2, ',', '.')  . 'KB)!' . "\n";
    }

    if (!$lsmodel->updateLieferscheinPdf($lsPdf)) {
        $error.= 'Lieferschein konnte nicht in Datenbank gespeichert werden!';
    } else {
        $msg.= 'Lieferschein wurde gespeicherr!' . "\n";
    }
    $error.= $lsmodel->getError();

    $lsPdfLengthByLid = $lsmodel->getLieferscheinPdfLength($lid);
    $lsPdfLength = $lsmodel->getLieferscheinPdfLength();

    if (!$incompleteMsg) {
        $debugLog[] = __LINE__;
        if ($lsmodel->auftragAbschliessen()) {
            $debugLog[] = __LINE__;
            $msg .= 'Auftrag wurde abgeschlossen!' . "\n";
        } else {
            $debugLog[] = __LINE__;
        }

        if (umzugsantrag_mailinform($aid, 'abgeschlossen', 'Ja')) {
            $msg.= 'Unterzeichneter Lieferschein wurde per Mail an den Kunden verschickt!' . "\n";
        }
    } else {
        $debugLog[] = __LINE__;
        $inputItem = [
            'aid' => $aid,
            'id' => $aid,
            'add_bemerkungen' => $incompleteMsg,
        ];
        umzugsantrag_add_bemerkung($inputItem);
        $debugLog[] = __LINE__;
    }
    $error.= $lsmodel->getError();
}

if ($error) {
    $errors['procesing'] = $error;
}

header('Content-Type: application/json; charset=UTF-8');
echo json_encode([
    'type' => (!$errors && $lid) ? 'success' : 'error',
    'errors' => $errors,
    'warnings' => $warnings,
    'msg' => $msg,
    'id' => $lid,

    'lid' => $lid,
    'strlen_lsPdf' => strlen($lsPdf),
    'lsPdfLengthByLid' => $lsPdfLengthByLid,
    'lPdfLength' => $lsPdfLength,
    'debug' => [
        compact(
            'aPostInput',
            'input',
            'errors',
            'warnings',
            'debugLog',
            'aMissingLeistungsIds',
            'aMissungFPIds',
            'aLidToItem',
            'incompleteMsg')
    ],
//    'auftrag' => $auftrag,
//    'leistungen' => $leistungen,
//    'input' => $input,
//    'lsdaten' => $lsdaten
]);



