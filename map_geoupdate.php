<?php
require_once 'header.php';
require_once 'map_lib.php';

$status = !isset($_REQUEST['status']) ? $_REQUEST['status'] : 'beantragt';
$ort = !empty($_REQUEST['ort']) ? $_REQUEST['ort'] : '';
$mitTourkennung = !empty($_REQUEST['mitTourkennung']) ? $_REQUEST['mitTourkennung'] : '';
$tourkennung = !empty($_REQUEST['tourkennung']) ? $_REQUEST['tourkennung'] : '';
$aValidStatus = [ 'beantragt', 'angeboten', 'bestaetigt', 'abgeschlossen' ];
if ($status && !in_array($status, $aValidStatus)) {
    $status = current($aValidStatus);
}


$sqlAuftragWithoutGeoData = <<<EOT
    SELECT MIN(a.aid) AS aid, 
        a.strasse,
        a.plz,
        a.ort,
        a.land,
        CONCAT(SUBSTRING_INDEX(a.strasse, ',', 1), ", ", a.plz, " ", a.ort, ", ", a.land) AS Adresse
     FROM mm_umzuege a 
     LEFT JOIN mm_geolocations gl ON (
        CONCAT(SUBSTRING_INDEX(a.strasse, ',', 1), ", ", a.plz, " ", a.ort, ", ", a.land) 
        LIKE
        CONCAT(SUBSTRING_INDEX(gl.strasse, ',', 1), ", ", gl.plz, " ", gl.ort, ", ", gl.land) 
     )
     WHERE IFNULL(gl.orig_target_id, "") = ""
     GROUP BY 
        a.strasse,
        a.plz,
        a.ort,
        a.land
     LIMIT 10
EOT;

$sqlInsertGeodata = <<<EOT
    INSERT INTO mm_geolocations 
    (uuid, lat, lng, strasse, plz, ort, land, orig_target, orig_target_id, geo_source)
    VALUES
    (:uuid, :lat, :lng, :strasse, :plz, :ort, :land, "ors_uniper.mm_umzuege.aid", orig_target_id, "gmaps")
EOT;

$rows = $db->query_rows($sqlAuftragWithoutGeoData);
foreach ($rows as $_row) {
    $address = $_row['Adresse'];
    echo 'SEARCH FOR ' . $address . "<br>\n";
    $result = searchGmap($address);
    if ($result && !empty($result['success']) && !empty($result['lat']) && !empty($result['lng'])) {
        echo 'FOUND ' . json_encode($result) . "<br>\n";
        $aParams = array_merge($row, $row, $result, [
            'uuid' => getUuid(),
            'strasse' => $_row['strasse'],
            'orig_target_id' => $_row['aid']
        ]);
        insertGeoCache($aParams);

        echo 'INSERT GEODATA ' . json_encode($aParams) . "<br>\n";
        $db->affected_rows();
        echo 'INSERT ID ' . $db->last_insert_id() . "<br>\n";
    } else {
        echo 'NOT FOUND ' . json_encode($result) . "<br>\n";
    }
}
