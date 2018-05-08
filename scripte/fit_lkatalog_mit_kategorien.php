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

// leistung_id  leistungskategorie_id Bezeichnung leistungseinheit leistungseinheit_abk preis_pro_einheit created modified

// leistung_id leistung leistungseinheit mengen_von mengen_bis kategorie kategorie2 preis preiseinheit dussmann mertens_henk reserve1 reserve2
$leistungen = $db->query_rows(
        'SELECT leistung_id, kategorie FROM mm_leistungskatalog_alt_mit_matrix'
);

foreach( $leistungen as $_lk) {
//    die( print_r($_lk, 1));
    $_lkid = $db->query_one('SELECT leistungskategorie_id FROM mm_leistungskategorie WHERE leistungskategorie LIKE ' . $db->quote($_lk['kategorie']));
    if (!$_lkid) {
        $db->query('INSERT INTO mm_leistungskategorie SET leistungskategorie = ' . $db->quote($_lk['kategorie']));
        if ($db->error()) die('#' . __LINE__ . $db->error() . '<br>' . $db->lastQuery);
        $_lkid = $db->insert_id();
    }
    
    $db->query(
            'INSERT INTO mm_leistungskatalog(leistung_id, leistungskategorie_id, Bezeichnung, leistungseinheit, leistungseinheit_abk, preis_pro_einheit, created, modified) ' . PHP_EOL
           .' SELECT leistung_id, ' . $_lkid . ', leistung, leistungseinheit, leistungseinheit, preis, NOW(), NOW() FROM mm_leistungskatalog_alt_mit_matrix ' . PHP_EOL
           .' WHERE leistung_id = ' . $_lk['leistung_id']
    );
    if ($db->error()) die('#' . __LINE__ . $db->error() . '<br>' . $db->lastQuery);
}
        
