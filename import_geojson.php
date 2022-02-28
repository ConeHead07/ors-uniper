<?php

require_once('header.php');
require_once($SitesBaseDir . '/umzugsantrag_sendmail.php');
require_once($InclBaseDir . 'umzugsantrag.inc.php');

$jsonFile = __DIR__ . '/data/Alle_AuftraegeMitLatLng.json';

$jsonString = file_get_contents($jsonFile);
$jsonData = json_decode($jsonString);
// $jsonString = '';
$errors = [];

$log = [];
$log[] = __LINE__;

function guidv4()
{
    if (function_exists('com_create_guid') === true)
        return trim(com_create_guid(), '{}');

    $data = '';

    if (function_exists('mcrypt_create_iv')) {
        $data = mcrypt_create_iv(32, MCRYPT_DEV_RANDOM);
    }
    if (empty($data) && function_exists('openssl_random_pseudo_bytes')) {
        $data = openssl_random_pseudo_bytes(16, $cstrong);
    }
    if (empty($data)) {
        $data = random_bytes(16);
    }


    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function geoResponseError(array $errors = []) {
    global $log;
    $log[] = __LINE__;
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode([
        'type' => 'error',
        'errors' => $errors,
        'msg' => implode("\n", $errors),
        'log' => $log
    ]);
    exit;
}

function geoResponseSuccess(array $daten = []) {
    global $log;
    header('Content-Type: application/json; charset=UTF-8');
    $daten['type'] = 'success';
    $daten['log'] = $log;
    echo json_encode($daten);
    exit;
}


function geoResponseDebug(array $daten = []) {
    global $log, $jsonFile, $jsonString, $jsonData;
    header('Content-Type: application/json; charset=UTF-8');
    $log[] = __LINE__;
    $daten['type'] = 'debug';
    $daten['debug'] = array(
        'log' => $log,
        'jsonFile' => $jsonFile,
        'jsonData' => array_slice($jsonData, 0, 1),
        'jsonString' => substr($jsonString, 0, 200));
    $daten['log'] = $log;
    echo json_encode($daten);
    exit;
}



if (count($errors)) {
    $log[] = __LINE__;
    return geoResponseError($aid, $errors);
}
$log[] = __LINE__;

$currAid = 0;
$iNumInserts = 0;
$iNumUpdates = 0;
$sqlCheckAid = 'SELECT id FROM mm_geolocations WHERE orig_target_id = :aid LIMIT 1';


$sqlInsertCols = 'INSERT INTO mm_geolocations (uuid, lat, lng, strasse, plz, ort, land, orig_target, orig_target_id)' . "\n"
    . ' VALUES';
$sqlValues = [];
$iNumJsonData = count($jsonData);
$NL = "\n";
for($i = 0; $i < $iNumJsonData; $i++) {
    $it = $jsonData[$i];
    if (empty($it->lat) || empty($it->lng)) {
        $errors[] = 'Unvollständige Geodaten in Json-Daten[' . $i . '] mit aid ' . $it->aid . ': ' . json_encode($it);
        continue;
    }
    $currAid = (int)$it->aid;
    $existingId =$db->query_one($sqlCheckAid, [ 'aid' => $currAid]);
    if ($existingId) {

        $sqlUpdate = 'UPDATE mm_geolocations SET '  . $NL
        . ' lat = ' . $db::quote($it->lat) . ', '  . $NL
        . ' lng = ' . $db::quote($it->lng) . ', '   . $NL
        . ' strasse = ' . $db::quote($it->strasse) . ', ' . $NL
        . ' plz = ' . $db::quote($it->plz) . ', '   . $NL
        . ' ort = ' . $db::quote($it->ort) . ', '   . $NL
        . ' land = ' . $db::quote($it->land) . ' ' . $NL
        . ' WHERE id = ' . (int)$existingId;
        if ($db->query($sqlUpdate)) {
            ++$iNumUpdates;
        }
        // geoResponseDebug(compact('i', 'existingId', 'iNumInserts', 'iNumUpdates', 'sqlInsertCols', 'sqlInsertValues', '_sql'));
    } else {
        $sqlInsertValues = '('
            . implode(', ', [
                $db::quote(guidv4()),
                $db::quote($it->lat),
                $db::quote($it->lng),
                $db::quote($it->strasse),
                $db::quote($it->plz),
                $db::quote($it->ort),
                $db::quote($it->land),
                $db::quote('ors_uniper.mm_umzuege.aid'),
                $db::quote($it->aid)
            ])
            . ') ';
        $sqlValues[] = $sqlInsertValues;
        $_sql = $sqlInsertCols . $sqlInsertValues;
        try {
            $sth = $db->query($_sql);
            if ($sth === true) {
                ++$iNumInserts;
            } elseif ($sth === false) {
                geoResponseError([
                    'Cannot insert geolocation-Item',
                    $it,
                    $_sql
                ]);
            } elseif ($sth && $sth->num_rows) {
                $iNumInserts += $sth->num_rows;
            }
            // geoResponseDebug(compact('i', 'sth', 'iNumInserts', 'sqlInsertCols', 'sqlInsertValues', '_sql'));

        } catch (\Exception $e) {
            geoResponseError([
                'error' => $e->getMessage(),
                'iNumInserts' => $iNumInserts,
                'sql' => $_sql,
                'TraceAsString' => $e->getTraceAsString(),
                'Trace' => $e->getTrace(),
                'Line' => $e->getLine(),
                'File' => $e->getFile(),
            ]);
        }
    }
    // geoResponseDebug(compact('i', 'iNumInserts', 'iNumUpdates', 'sqlInsertCols', 'sqlInsertValues', '_sql'));
}
$jsonData = [];

$log[] = __LINE__;
geoResponseSuccess([
    'errors' => $errors,
    'iNumInserts' => $iNumInserts,
    'iNumUpdates' => $iNumUpdates,
]);

/*
$sql = $sqlInsertCols . "\n"
    . implode(",\n", $sqlValues);
try {
    $sth = $db->query( $sql );
    geoResponseSuccess([
        'num_imports' => $sth->num_rows,
    ]);
} catch(\Exception $e) {
    geoResponseError([
        'error' => $e->getMessage(),
        'sql' => $sql,
        'TraceAsString' => $e->getTraceAsString(),
        'Trace' => $e->getTrace(),
        'Line'=> $e->getLine(),
        'File'=> $e->getFile(),
    ]);
}
*/


