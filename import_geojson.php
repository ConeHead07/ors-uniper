<?php
ini_set('error_reporting', 1);
ini_set('display_errors	', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);

require_once 'header.php';
require_once $SitesBaseDir . '/umzugsantrag_sendmail.php';
require_once $InclBaseDir . 'umzugsantrag.inc.php';
ini_set('error_reporting', 1);
ini_set('display_errors	', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);

$jsonFile = __DIR__ . '/data/Alle_AuftraegeMitLatLng.json';

$jsonString = file_get_contents($jsonFile);
$jsonData = json_decode($jsonString);
// $jsonString = '';
$errors = [];

$log = [];
$log[] = __LINE__;


$timeIn = time();
$timeOffset = time();
function getDuration() {
    global $timeIn, $timeOffset;
    $now = time();
    $total = $now - $timeIn;
    $step  = $now - $timeOffset;
    $timeOffset = $now;

    return compact('total', 'step' );
}

function printDuration($line, $msg) {
    $a = getDuration();
    echo "#{$line} Total: {$a['total']}s, Step: {$a['step']}s; " . $msg;
    ob_implicit_flush(true);
}

$increment = 0;
function guidv4()
{
    global $increment;
    $increment+= 37 * 37;
    $mc = microtime(true);
    $rnd = rand($increment, $increment * 1000);
    $fake = md5(md5($increment) . md5($mc) . md5($rnd));
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split($fake, 4));

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
    printDuration(__LINE__, " i: $i, AID: {$it->aid},");
    if (empty($it->lat) || empty($it->lng)) {
        printDuration(__LINE__, "NO GeoData Found<br>");
        $errors[] = 'Unvollständige Geodaten in Json-Daten[' . $i . '] mit aid ' . $it->aid . ': ' . json_encode($it);
        continue;
    }
    printDuration(__LINE__, " {$it->lat},{$it->lng}");

    $currAid = (int)$it->aid;
    $existingId =$db->query_one($sqlCheckAid, [ 'aid' => $currAid]);
    if ($existingId) {
        echo ' |#' . __LINE__ . ': Update existing';

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
        printDuration(__LINE__, 'Insert');
        $sqlInsertValues = '('
            . implode(', ', [
                $db::quote(guidv4()),
                $db::quote($it->lat),
                $db::quote($it->lng),
                $db::quote($it->strasse),
                $db::quote($it->plz),
                $db::quote($it->ort),
                $db::quote($it->land),
                $db::quote('ors_zurich.mm_umzuege.aid'),
                $db::quote($it->aid)
            ])
            . ') ';
        $sqlValues[] = $sqlInsertValues;
        $_sql = $sqlInsertCols . $sqlInsertValues;
        try {
            printDuration(__LINE__, 'execute query');
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
            echo ' |#' . __LINE__ . ': exception<br>';
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
    printDuration(__LINE__, 'done<br>');
    // geoResponseDebug(compact('i', 'iNumInserts', 'iNumUpdates', 'sqlInsertCols', 'sqlInsertValues', '_sql'));

    if ($i > 2) {
        printDuration(__LINE__, 'Exit Script-Execution!');
        exit;
    }
}
$jsonData = [];

$log[] = __LINE__;
geoResponseSuccess([
    'errors' => $errors,
    'iNumInserts' => $iNumInserts,
    'iNumUpdates' => $iNumUpdates,
]);

