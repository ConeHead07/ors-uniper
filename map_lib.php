<?php
require_once __DIR__ . '/include/conf.php';
require_once $MConf['AppRoot'] . $MConf['Inc_Dir'] . 'conf_lib.php';
require_once $MConf['AppRoot'] . $MConf['Class_Dir'] . 'dbconn.class.php';
require_once $MConf['AppRoot'] . $MConf['Inc_Dir'] . 'conn.php';

$increment = time();
function getUuid()
{
    global $increment;
    $increment += 37 * 37;
    $mc = microtime(true);
    $rnd = rand($increment, $increment * 1000);
    $fake = md5(md5($increment) . md5($mc) . md5($rnd));
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split($fake, 4));
}

// https://maps.googleapis.com/maps/api/js/GeocodeService.Search?4sHerborner%20Weg%201%2C%2040229%20D%C3%BCsseldorf%2C%20Germany&7sUS&9sde-DE&callback=_xdc_._tmk16d&key=AIzaSyAiXjlLRIAZtK4c5O4CP_b0wLzNOJ4MOGY&channel=88&token=41191
$GOOGLE_API_KEY_HERE = 'AIzaSyBqXGv1kg-0Fz6cHnbvac-LqVs4E4iGSlk';

function searchGmap($address)
{
    global $GOOGLE_API_KEY_HERE;

    $url = 'https://maps.google.com/maps/api/geocode/json?key='
        . $GOOGLE_API_KEY_HERE
        . '&address=' . str_replace(' ', '+', $address)
        . '&sensor=false';
    try {

        $ch = curl_init();

        // set URL
        curl_setopt($ch, CURLOPT_URL, $url);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $data = curl_exec($ch);
        $error = curl_error($ch);
        $errno = curl_errno($ch);

        // close curl resource to free up system resources
        curl_close($ch);

        if (!$data) {
            return [
                'success' => false,
                'message' => $error,
                'url' => str_replace($GOOGLE_API_KEY_HERE, 'XXX', $url),
                'curl_errno' => $errno
            ];
        }

        $data_location = $url;
        $data = file_get_contents($data_location);
        usleep(200000);
        // turn this on to see if we are being blocked
        // echo $data;
        $data = json_decode($data);
        if ($data->status === 'OK') {
            $lat = $data->results[0]->geometry->location->lat;
            $lng = $data->results[0]->geometry->location->lng;
            $addr = $data->results[0]->formatted_address;
            $place_id = $data->results[0]->place_id;

            if($lat && $lng) {
                return array(
                    'success' => true,
                    'lat' => $lat,
                    'lng' => $lng,
                    'addr' => $addr,
                    'google_place_id' => $place_id,
                );
            }
        }
        if($data->status == 'OVER_QUERY_LIMIT') {
            return array(
                'success' => false,
                'message' => 'Google Amp API OVER_QUERY_LIMIT, Please update your google map api key or try tomorrow'
            );
        }

    } catch (Exception $e) {

    }

    return array('success' => false, 'lat' => null, 'lng' => null, 'addr' => null, 'google_place_id' => null);
}

function parseAddress($address) {
    $parts = explode(',', $address);
    $strasse = $parts[0];
    $plz = '';
    $ort = '';
    $land = '';

    if (count($parts) > 1) {
        if (preg_match('#^(\d+)\s+(\D+)$#', trim($parts[1]), $m)) {
            $plz = $m[1];
            $ort = $m[2];
        } elseif (preg_match('#^(\d+)$#', trim($parts[1]), $m)) {
            $plz = $m[1];
        } else {
            $ort = trim($parts[1]);
        }
    }
    return compact('strasse', 'plz', 'ort', 'land');
}


function searchCache($address) {
    global $db;
    $parts = parseAddress($address);

    $sql = <<<EOT
 SELECT 
  strasse, plz, ort, land, 
  CONCAT_WS(", ", strasse, TRIM(CONCAT(plz, " ", ort)), land) AS addr,
  lat, lng, 
  google_place_id
 FROM mm_geolocations 

EOT;

    $sql.= ' WHERE 1 > 0
        AND strasse LIKE ' . $db::quote($parts['strasse']);

    if ($parts['plz']) {
        $sql.= ' AND plz LIKE ' . $db::quote($parts['plz']);
    }
    if ($parts['ort']) {
        $sql.= ' AND ort LIKE ' . $db::quote($parts['ort']);
    }
    if ($parts['land']) {
        $sql.= ' AND land LIKE ' . $db::quote($parts['land']);
    }

    $row = $db->query_row($sql);
    if ($row) {
        return array_merge([ 'success' => true ], $row);
    }
    return [ 'success' => false, 'message' => 'Address not found in Cache!' ];


    return $row;
}

function insertGeoCache(array $geoData) {
    global $db;
    // ALTER TABLE `mm_geolocations` ADD COLUMN `google_place_id` VARCHAR(50) NULL DEFAULT '' AFTER `land`;

    $sqlInsertGeodata = <<<EOT
INSERT INTO mm_geolocations 
(uuid, lat, lng, strasse, plz, ort, land, orig_target, orig_target_id, google_place_id, geo_source)
VALUES
(:uuid, :lat, :lng, :strasse, :plz, :ort, :land, "ors_zurich.mm_umzuege.aid", orig_target_id, :google_place_id, "gmaps")
EOT;

    $aParams = [
        'uuid' => getUuid(),
        'lat' => $geoData['lat'],
        'lng' => $geoData['lng'],
        'strasse' => $geoData['strasse'],
        'plz' => $geoData['plz'],
        'ort' => $geoData['ort'],
        'land' => $geoData['land'],
        'google_place_id' => $geoData['google_place_id'] ?? '',
        'orig_target' => $geoData['orig_target'] ?? null,
        'orig_target_id' => $geoData['orig_target_id'] ?? null,
    ];

    // echo 'INSERT GEODATA ' . json_encode($aParams) . "<br>\n";
    $db->query($sqlInsertGeodata, $aParams);
    $db->affected_rows();

    return true;
}

function searchAddress(string $address) {
    if (empty($address)) {
        throw new Exception('Empty Address!');
    }
    $cache = searchCache($address);
    if ($cache && !empty($cache['lat']) && !empty($cache['lng'])) {
        return $cache;
    }

    $result = searchGmap($address);
    if ($result && !empty($result['lat']) && !empty($result['lng'])) {
        $parsedAddr = parseAddress($result['addr']);
        $result= array_merge($result, $parsedAddr);
        insertGeoCache($result);
        return $result;
    }

    return $result;
}

function getJsonErrorByCodeId(int $codeId) {
    switch($codeId) {
        case JSON_ERROR_NONE: return "[$codeId] " . 'Kein Fehler aufgetreten.';
        case JSON_ERROR_DEPTH: return "[$codeId] " . ' Die maximale Stacktiefe wurde überschritten.';
        case JSON_ERROR_STATE_MISMATCH: return "[$codeId] " . 'Ungültiges oder missgestaltetes JSON';
        case JSON_ERROR_CTRL_CHAR: return "[$codeId] " . 'Steuerzeichenfehler, möglicherweise unkorrekt kodiert.';
        case JSON_ERROR_SYNTAX: return "[$codeId] " . 'Syntaxfehler.';
        case JSON_ERROR_UTF8: return "[$codeId] " . 'Missgestaltete UTF-8 Zeichen, möglicherweise fehlerhaft kodiert';
        case JSON_ERROR_RECURSION: return "[$codeId] " . 'Eine oder mehrere rekursive Referenzen im zu kodierenden Wert';
        case JSON_ERROR_INF_OR_NAN: return "[$codeId] " . 'Eine oder mehrere NAN oder INF Werte im zu kodierenden Wert';
        case JSON_ERROR_UNSUPPORTED_TYPE: return "[$codeId] " . 'Ein Wert eines Typs, der nicht kodiert werden kann, wurde übergeben';
        case JSON_ERROR_INVALID_PROPERTY_NAME: return "[$codeId] " . 'Ein Eigenschaftsname, der nicht kodiert werden kann, wurde übergeben';
        case JSON_ERROR_UTF16: return "[$codeId] " . 'Deformierte UTF-16 Zeichen; möglicherweise fehlerhaft kodiert';
        default: return "[$codeId] Unbekannter Fehlerocde!";
    }
}

