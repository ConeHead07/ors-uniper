<?php 
require_once("../header.php");
echo '#'.__LINE__ . '<br>' . PHP_EOL;
$rows = get_malformed_files();
foreach($rows as $row) {
    $file = fitFileName($row['dok_datei']);
    echo 'fitFileName:<br>';
    echo 'VON '. $row['dok_datei'] . '<br>' . PHP_EOL;
    echo 'NACH ' . $file . '<br>' . PHP_EOL;
    echo '<hr/>' . PHP_EOL;
    continue;
    copy('../attachments/' . $row['dok_datei'], '../attachments/' . $file);
    save_wellformed_file($row['dokid'], $file);
}

function get_malformed_files() {
	global $_TABLE;
	global $db;
	global $user;
	
	$sql = "SELECT dokid, dok_datei FROM `".$_TABLE["umzugsanlagen"]."` \n";
	$sql.= " WHERE length(dok_datei) > 90 ";
        $sql.= " OR dok_datei LIKE \"% %\"";
//        $sql.= " OR dok_datei LIKE \"%ä%\"";
//        $sql.= " OR dok_datei LIKE \"%ö%\"";
//        $sql.= " OR dok_datei LIKE \"%ü%\"";
	$rows = $db->query_rows($sql);
        if ($db->error()) {
            die($db->error() . '<br>' . $db->lastQuery);
        }
        return $rows;
}

function save_wellformed_file($dokid, $file) {
	global $_TABLE;
	global $db;
	global $user;
	
	$aFileInfo = pathinfo($file);
	
	$sql = "UPDATE `".$_TABLE["umzugsanlagen"]."` SET \n";
	$sql.= " dok_datei = " . $db->quote($file) . "\n";
	$sql.= " WHERE `dokid` = \"" . ((int)($dokid)) . "\"\n";
	$db->query($sql);
	$rows = $db->query_rows($sql);
        if ($db->error()) {
            die($db->error() . '<br>' . $db->lastQuery);
        }
}

function fitFileName($file) {
    $p = pathinfo($file);
    $rpl = array(
        'ä' => 'ae',
        'Ä' => 'Ae',
        'ö' => 'oe',
        'Ö' => 'Oe',
        'ü' => 'ue',
        'Ü' => 'Ue',
        'ß' => 'ss',
    );
    return substr(preg_replace('/[^a-zA-Z0-9\._]/', '', strtr($p['filename'],$rpl)), 0, 50).'_'.time().'.'.$p['extension'];
}
