<?php
set_time_limit(90);
//header('Content-Type: text/html; charset="ISO-8859-1"');
header('Content-Type: text/html; charset="UTF-8"');

/* @var $db dbconn */
$db = null;

require_once dirname(__FILE__)."/../include/conf.php";
require_once $MConf["AppRoot"] . $MConf["Inc_Dir"]   . "conf_lib.php";
//die( '<pre>' . print_r(get_included_files(),1) );
require_once $MConf["AppRoot"] . $MConf["Class_Dir"] . "dbconn.class.php";
//die( '<pre>' . print_r(get_included_files(),1) );
require_once $MConf["AppRoot"] . $MConf["Inc_Dir"]   . "conn.php";

$users = $db->query_rows(
        'SELECT uid, Concat(nachname, ", ", vorname) name FROM mm_user'
);
foreach($users as $_u) {
    $db->query('update mm_stamm_gebaeude set regionalmanager_uid = :uid WHERE regionalmanager = :name', $_u);
    echo $db->lastQuery . '<br>'."\n";
    echo 'betroffen: ' . $db->affected_rows() . '<br>' . PHP_EOL;
    if ($db->error()) die($db->error ());
    
    $db->query('update mm_stamm_gebaeude set standortmanager_uid = :uid WHERE standortmanager = :name', $_u);
    echo $db->lastQuery . '<br>'."\n";
    echo 'betroffen: ' . $db->affected_rows() . '<br>' . PHP_EOL;
    if ($db->error()) die($db->error ());
    
    $db->query('update mm_stamm_gebaeude set objektleiter_uid = :uid WHERE objektleiter = :name', $_u);
    echo $db->lastQuery . '<br>'."\n";
    echo 'betroffen: ' . $db->affected_rows() . '<br>' . PHP_EOL;
    if ($db->error()) die($db->error ());
}