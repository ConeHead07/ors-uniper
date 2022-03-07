<?php
require_once 'header.php';
require_once 'map_lib.php';

$address = isset($_REQUEST['address']) ? $_REQUEST['address'] : '';
$ort = !empty($_REQUEST['ort']) ? $_REQUEST['ort'] : '';

if ($address && count(explode(',', $address)) < 2) {

    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(['success' => false, 'error' => 'Addresse zu ungenau']);
    exit;
}

$result = searchAddress($address);

if ($result && !empty($result['success'])) {

    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($result);
    exit;
}

if (is_array($result)) {
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($result);
    exit;
}

header('Content-Type: application/json; charset=UTF-8');
echo json_encode(['success' => false, 'error' => 'Unbekannter Fehler']);
