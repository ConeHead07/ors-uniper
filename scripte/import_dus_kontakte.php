<?php

set_time_limit(90);
header('Content-Type: text/html; charset="ISO-8859-1"');
//header('Content-Type: text/html; charset="UTF-8"');

require_once dirname(__FILE__)."/../include/conf.php";
require_once $MConf["AppRoot"] . $MConf["Inc_Dir"]   . "conf_lib.php";
require_once $MConf["AppRoot"] . $MConf["Class_Dir"] . "dbconn.class.php";
require_once $MConf["AppRoot"] . $MConf["Inc_Dir"]   . "conn.php";
require_once $MConf["AppRoot"] . $MConf["Class_Dir"] . "CsvXls2Array.class.php";
require_once dirname(__FILE__)."/functions.php";

// we,standort,funktion,region_bank,dus_mitarbeiter,dus_mobil,dus_email,dsd_nl,mh_rm,mh_rm_mobil,mh_rm_tel,mh_rm_email 
$file = '../import_csv/Dussmann_Kontakte_RM_STOM.csv';

$sqlÍnsert = 'INSERT IGNORE INTO mm_helper_dus_kontakte (we,stadtname,adresse,standort,funktion,region_bank,dus_mitarbeiter,dus_mobil,dus_email,dsd_nl,mh_rm,mh_rm_mobil,mh_rm_tel,mh_rm_email) ' . PHP_EOL
            .' VALUES(:we,:stadtname,:adresse,:standort,:funktion,:region_bank,:dus_mitarbeiter,:dus_mobil,:dus_email,:dsd_nl,:mh_rm,:mh_rm_mobil,:mh_rm_tel,:mh_rm_email)';

$sqlDus = 'SELECT g.id, stadtname, adresse, regionalmanager_uid, standortmanager_uid, CONCAT(ru.nachname, ", ", ru.vorname) rm, CONCAT(su.nachname, ", ", su.vorname) stom FROM mm_stamm_gebaeude g ' . PHP_EOL
        .' LEFT JOIN mm_user ru ON (g.regionalmanager_uid = ru.uid)' . PHP_EOL
        .' LEFT JOIN mm_user su ON (g.standortmanager_uid = su.uid)' . PHP_EOL
        .' WHERE g.id = :we';

// id 	gebaeude 	gebaeudename 	nutzflaeche 	belegschaft 	nutzflaeche_pro_ma 	flaeche_pro_ma 	bundesland 	plz 	stadt 	stadtname 	adresse 	campus 	NL 	schlagwort 	Objektstatus 	gebaeudecluster 	top42 	Aktiv 	regionalmanager 	standortmanager 	objektleiter 	regionalmanager_uid 	standortmanager_uid 	objektleiter_uid 	mertenshenk_uid 	created 	modified 
$sqlInsertGebaeude = 'INSERT INTO mm_stamm_gebaeude(id,	gebaeude, gebaeudename, nutzflaeche, belegschaft, nutzflaeche_pro_ma, flaeche_pro_ma, bundesland, plz, stadt, stadtname, adresse, campus, NL, schlagwort, Objektstatus, gebaeudecluster, top42, Aktiv, regionalmanager, standortmanager, objektleiter, regionalmanager_uid, standortmanager_uid, objektleiter_uid, mertenshenk_uid, created) '
                    .' VALUES(:we, CONCAT(:stadtname, " ", :adresse), CONCAT(:stadtname, " ", :adresse), 0, 0, 0, 0, "", "", :stadtname, :stadtname, :adresse, "", :dsd_nl, "", "", "", 0, 1, "", "", "", 0, 0, 0, 0, NOW())';

$sqlCheckUser = 'SELECT * FROM mm_user WHERE nachname LIKE :nachname AND vorname LIKE :vorname LIMIT 1';

$sqlInsertUser = 'INSERT INTO mm_user(user, email, fon, mobil, pw, gruppe, anrede, vorname, nachname, firma, authentcode, created) ' . PHP_EOL
                .' VALUES(:user, :email, :fon, :mobil, :pw, :gruppe, :anrede, :vorname, :nachname, :firma, :authentcode, NOW())';


$fp = fopen($file, 'r');
$i = 0;
$error = '';
if ($fp) {
    try {
        echo '<style>' . PHP_EOL;
        echo 'tr.matched1, tr.matched1 td { background:#afa; }' . PHP_EOL;
        echo 'tr.matched0, tr.matched0 td { background:#faa; }' . PHP_EOL;
        echo 'tr.matchedunknown, tr.matchedunknown td { background:#aaf; }' . PHP_EOL;
        echo '</style>' . PHP_EOL;
        echo '<table border=1 cellspacing=0 cellpadding=1>';
        while (!feof($fp)) {
            $cols = explode(';', trim(fgets($fp, 1500)) );
            if (count($cols) < 2) continue;
            if (!$i) {
                $fields = $cols;
                echo '<thead>';
                echo '<tr><th>' . implode('</th><th>', $cols) . '</th><th>matched</th></tr>' . PHP_EOL;
                echo '</thead>';
                echo '<tbody>';
            } else {
                $row = array_combine($fields, $cols);
                $t = explode(',', $row['standort']);
                if (count($t) < 2) {
                    $t = explode('(', $row['standort']);
                    if (count($t) < 2) {
                        $error.= '#' . __LINE__ . ' Standort kann nicht geparst werden: ' . $row['standort'] . '('.$row['we'].') <br>' . PHP_EOL;
                        continue;
                    } else {
                        $row['stadtname'] = trim(implode('(', array_slice($t,0, -1)));
                        $row['adresse'] = trim(array_pop($t));
                    }
                } else {
                    $row['stadtname'] = trim(implode(',', array_slice($t,0, -1)));
                    $row['adresse'] = trim(array_pop($t));
                }
                
                
                $db->query($sqlÍnsert, $row);
                if ($db->error()) {
                    die('#' . __LINE__ . ' ' . $db->error() . '<br>' . PHP_EOL . $db->lastQuery);
                }
                
                $checkGebRow = $db->query_row($sqlDus, $row);
                if (!$checkGebRow) {
                    $error.= "Für den Standort " . $row['standort'] . "(".$row['we'].") wurde kein Gebäudeeintrag gefunden!<br>" . PHP_EOL;
                    $db->query($sqlInsertGebaeude, $row);                    
                    if ($db->error()) {
                        die('#' . __LINE__ . ' ' . $db->error() . '<br>' . PHP_EOL . $db->lastQuery);
                    }
                    $checkGebRow = $db->query_row($sqlDus, $row);
                }
                
                if ($db->error()) {
                    die('#' . __LINE__ . ' ' . $db->error() . '<br>' . PHP_EOL . $db->lastQuery);
                }
                
                $matched = 0;
                $name = '';
                if ($row['funktion'] == 'RM') {
                    $matched = (int)($checkGebRow && $checkGebRow['rm'] == $row['dus_mitarbeiter']);
                    $name = $checkGebRow['rm'];
                } elseif ($row['funktion'] == 'STOM') {
                    $matched = (int)($checkGebRow && $checkGebRow['stom'] == $row['dus_mitarbeiter']);
                    $name = $checkGebRow['stom'];
                } else {
                    $matched = 'unknown';
                }
                
                if (1 || $matched != 1) {
                    $tName = explode(',', $row['dus_mitarbeiter']);
                    if (count($tName) == 2 ) {
                        $ma = array( 'vorname' => trim($tName[1]), 'nachname' => trim($tName[0]) );                        
                        $_usr = $db->query_row($sqlCheckUser, $ma );
                        if ($db->error()) {
                            die('#' . __LINE__ . ' ' . $db->error() . '<br>' . PHP_EOL . $db->lastQuery);
                        }
                        
                        if (!$_usr) {
                            $_usr = $ma;
                            $_usr['user']   = fitUsername($ma['vorname'] . '.' . $ma['nachname']);
                            $_usr['pw']     = getPwByUsername($_usr['user']);
                            $_usr['email']  = $row['dus_email'];
                            $_usr['fon']    = '';
                            $_usr['mobil']  = $row['dus_mobil'];
                            $_usr['anrede'] = getAnredeByVorname($ma['vorname']);
                            $_usr['firma']  = 'Dussmann';
                            $_usr['authentcode'] = md5($ma['vorname'] . time() . $ma['nachname']);
                            $_usr['gruppe'] = 'kunde_report';
                            
                            $db->query($sqlInsertUser, $_usr);
                            if ($db->error()) {
                                die('#' . __LINE__ . ' ' . $db->error() . '<br>' . PHP_EOL . $db->lastQuery);
                            }
                            $_uid = $db->insert_id();
                            $_usr = $db->query_row('SELECT * FROM mm_user WHERE uid = ' . (int)$_uid);
                        }
                        
                        $tName = explode(',', $row['mh_rm']);
                        if (count($tName) == 2 ) {
                            $ma = array( 'vorname' => trim($tName[1]), 'nachname' => trim($tName[0]) );                        
                            $_mh_usr = $db->query_row($sqlCheckUser, $ma );
                            if ($db->error()) {
                                die('#' . __LINE__ . ' ' . $db->error() . '<br>' . PHP_EOL . $db->lastQuery);
                            }

                            if (!$_mh_usr) {
                                $_mh_usr = $ma;
                                $_mh_usr['user']   = fitUsername($ma['vorname'] . '.' . $ma['nachname']);
                                $_mh_usr['pw']     = getPwByUsername($_usr['user']);
                                $_mh_usr['email']  = $row['mh_rm_email'];
                                $_mh_usr['fon']    = $row['mh_rm_tel'];
                                $_mh_usr['mobil']  = $row['mh_rm_mobil'];
                                $_mh_usr['anrede'] = getAnredeByVorname($ma['vorname']);
                                $_mh_usr['firma']  = 'Mertens-Henk';
                                $_mh_usr['authentcode'] = md5($ma['vorname'] . time() . $ma['nachname']);
                                $_mh_usr['gruppe'] = 'umzugsteam';

                                $db->query($sqlInsertUser, $_mh_usr);
                                if ($db->error()) {
                                    die('#' . __LINE__ . ' ' . $db->error() . '<br>' . PHP_EOL . $db->lastQuery);
                                }
                                $_mh_uid = $db->insert_id();
                                $_mh_usr = $db->query_row('SELECT * FROM mm_user WHERE uid = ' . (int)$_mh_uid);
                            }
                        }
                        
                        $sqlUpdateGebaeude = 'UPDATE mm_stamm_gebaeude SET ';
                        
                        if ($row['funktion'] == 'RM') {
                            $sqlUpdateGebaeude.= ' regionalmanager = :name, ';
                            $sqlUpdateGebaeude.= ' regionalmanager_uid = :uid, ';
                        } else {
                            $sqlUpdateGebaeude.= ' standortmanager = :name, ';
                            $sqlUpdateGebaeude.= ' standortmanager_uid = :uid, ';
                        }
                        $sqlUpdateGebaeude.= ' mertenshenk_uid = :mh_uid ';
                        $sqlUpdateGebaeude.= ' WHERE id = :id';
                        
                        $db->query($sqlUpdateGebaeude, array(
                            'name' => $row['dus_mitarbeiter'],
                            'uid' => $_usr['uid'],
                            'mh_uid' => $_mh_usr['uid'],
                            'id'  => $checkGebRow['id'],
                        ));
                        if ($db->error()) {
                            die('#' . __LINE__ . ' ' . $db->error() . '<br>' . PHP_EOL . $db->lastQuery);
                        }
                    }
                }
                
                echo '<tr class="matched'.$matched.'"><td>' . implode('</td><td>', $cols) . '</td><td>'.$name.'</td></tr>' . PHP_EOL;
            }
            ++$i;
        }
        if ($i) echo '</tbody>';
    } catch(Exception $e) {
        echo $e->getMessage();
        fclose($fp);
    }
    if (is_resource($fp)) fclose($fp);
}
echo '</table>';
echo $error;

