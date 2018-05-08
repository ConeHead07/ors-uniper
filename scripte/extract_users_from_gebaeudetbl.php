<?php
set_time_limit(90);
//header('Content-Type: text/html; charset="ISO-8859-1"');
header('Content-Type: text/html; charset="UTF-8"');

/* @var $db dbconn */
$db = null;

require_once dirname(__FILE__)."/../include/conf.php";
require_once dirname(__FILE__)."/functions.php";

require_once $MConf["AppRoot"] . $MConf["Inc_Dir"]   . "conf_lib.php";
//die( '<pre>' . print_r(get_included_files(),1) );
require_once $MConf["AppRoot"] . $MConf["Class_Dir"] . "dbconn.class.php";
//die( '<pre>' . print_r(get_included_files(),1) );
require_once $MConf["AppRoot"] . $MConf["Inc_Dir"]   . "conn.php";

$rows = $db->query_rows(
        'SELECT id, adresse, stadtname, regionalmanager, standortmanager, objektleiter FROM ' . $db->quoteIdentifier($_TABLE['gebaeude'])
);

$users = $db->query_rows(
        'SELECT uid, Concat(nachname, " ", vorname) name FROM mm_user'
);
foreach($users as $_u) {
    $db->query('update mm_stamm_gebaeude regionalmanager_uid = :uid WHERE regionalmanager = :name', $_u);
    $db->query('update mm_stamm_gebaeude standortmanager_uid = :uid WHERE standortmanager = :name', $_u);
    $db->query('update mm_stamm_gebaeude objektleiter_uid = :uid WHERE objektleiter = :name', $_u);
}


$num_user_updates = 0;
$num_user_inserts = 0;
$num_gebaeude_user_inserts = 0;

function onDbErrorDieLog($note, $finish = 0) {
    global $db;
    global $num_user_updates;
    global $num_user_inserts;
    global $num_gebaeude_user_inserts;
    
    if ($db->error() || $finish) {
        die(implode(PHP_EOL, array(
            '<pre>',
            $note,
            $db->error(), 
            $db->lastQuery, 
            'user_updates: ' . $num_user_updates, 
            'user_inserts: ' . $num_user_inserts,
            'zust_inserts: ' . $num_gebaeude_user_inserts,
            '</pre>',
        )));
    }
}

$userCaches = array();

function update_user_ort($row) {
    global $db;
    global $num_user_inserts;
    global $num_user_updates;
    global $num_gebaeude_user_inserts;
    global $userCaches;
    
    $zustaendigkeiten = array();
    
    if (!empty($row['regionalmanager'])) $zustaendigkeiten[] = array($row['regionalmanager'], 3);
    if (!empty($row['standortmanager'])) $zustaendigkeiten[] = array($row['standortmanager'], 2);
    if (!empty($row['objektleiter']))    $zustaendigkeiten[] = array($row['objektleiter'], 1);
    
    if (!count($zustaendigkeiten)) return 0;
    
    foreach($zustaendigkeiten as $_pair) {
        
        
        $name = $_pair[0];
        $zid  = $_pair[1];
        
        $parts = explode(',', $name);
        $nn = trim($parts[0]);
        $vn = trim(implode(',', array_slice($parts, 1)));
        
        $userCacheKey = $vn . ' ' . $nn;
        if (empty($userCaches[$userCacheKey])) {
            $u = $db->query_singlerow(
                'SELECT * FROM mm_user WHERE vorname = :vorname AND nachname = :nachname LIMIT 1',
                array('vorname' => $vn, 'nachname' => $nn )
            );
            $userCaches[$userCacheKey] = $u;
        } else {
            $u = $userCaches[$userCacheKey];
        }
        if ($u) {
            $_uid = $u['uid'];
            if (false === strpos($u['standortverwaltung'], trim($row['stadtname']) ) )  {
                $db->query('UPDATE mm_user SET standortverwaltung = CONCAT(standortverwaltung, ", ", "' . $row['stadtname'] . '") '
                          .'WHERE uid = ' . (int)$_uid);
                $u['standortverwaltung'].= ', ' . $row['stadtname'];
                onDbErrorDieLog('#' . __LINE__);
                ++$num_user_updates;
            }
        } else {
            $username = fitUsername($vn.'.'.$nn);
            $db->query(
                   'INSERT INTO mm_user (user, email, pw, gruppe, freigegeben, ' . PHP_EOL
                  .'anrede, vorname, nachname, standortverwaltung, authentcode, ' . PHP_EOL
                  .'registerdate, onlinestatus, created, modified) ' . PHP_EOL
                  .'VALUES (:user, :email, :pw, :gruppe, :freigegeben, ' . PHP_EOL
                  .':anrede, :vorname, :nachname, :standortverwaltung, :authentcode, ' . PHP_EOL
                  .':registerdate, :onlinestatus, :created, :modified)',
                  array(
                    'user'  => fitUsername($username),
                    'email' => fitUsername($username) . '@dussmann.de',
                    'pw'    => md5( getPwByUsername($username) ),
                    'gruppe' => 'kunde_report',
                    'freigegeben' => 'Ja',
                    'anrede'   => !preg_match('/[aeui]\b/', $vn) ? 'Herr' : 'Frau',
                    'vorname'  => $vn,
                    'nachname' => $nn,
                    'standortverwaltung' => $row['stadtname'],
                    'authentcode' => md5($vn . time() . $nn) ,
                    'registerdate' => new DbExpr('NOW()'),
                    'onlinestatus' => 'loggedout',
                    'created'      => new DbExpr('NOW()'),
                    'modified'     => new DbExpr('NOW()'),
                )
            );
            onDbErrorDieLog('#' . __LINE__);
            ++$num_user_inserts;
            $_uid = $db->insert_id();
        }

        $vals = array('uid'=>$_uid, 'gid' => $row['id'], 'zid' => $zid);
        $db->query(
            'INSERT IGNORE INTO mm_gebaeude_user (uid, gebaeude_id, zustaendigkeits_id)'
           .'VALUES (:uid, :gid, :zid)',
           $vals);
//        echo '#' . __LINE__ . ' INSERT ' . print_r($vals,1) . '<br>' . PHP_EOL;
//        echo '#' . __LINE__ . ' ' . $db->lastQuery . '<br>' . PHP_EOL;
        onDbErrorDieLog('#' . __LINE__);
        if ($db->affected_rows()) ++$num_gebaeude_user_inserts;
    }
}

foreach($rows as $row) {
    update_user_ort($row);
}
onDbErrorDieLog('#' . __LINE__ . ' FINISHED', 1);

echo '<pre>' . print_r($rows, 1) . '</pre>';