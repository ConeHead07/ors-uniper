<?php
require("header.php");

$imgDir = 'images/leistungskatalog/';
$webBaseDir = $MConf['WebRoot'] . $imgDir;
$appBaseDir = $MConf["AppRoot"] . $imgDir;
$id = (int)$_GET['id'];

if (!$id) {
    exit;
}

$sql = 'SELECT image '
	. ' FROM `mm_leistungskatalog` '
	. ' WHERE leistung_id = ' . $id . '';
$row = $db->query_row($sql);

if (!$row) {
    echo 'no ID!';
    exit;
}
$file = $appBaseDir . $row['image'];

if ($row['image'] && file_exists($file)) {
    header('Content-Type: image/jpeg');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
} else {
    echo 'File Not Found!';
}

$sql = 'SELECT image_urldata '
    . ' FROM `mm_leistungskatalog` '
    . ' WHERE leistung_id = ' . $id . '';
$row = $db->query_row($sql);

$urldataPrefix = 'data:image/jpg;base64,';
$imgOffset = strlen($urldataPrefix);
$content = substr($row['image_urldata'], $imgOffset);

header('Content-Type: image/jpeg');
header('Content-Length: ' . strlen($content));

echo $content;




