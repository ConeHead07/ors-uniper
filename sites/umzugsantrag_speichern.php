<?php 
if (basename(__FILE__)==basename($_SERVER["PHP_SELF"])) require_once("../header.php");

require_once($InclBaseDir."umzugsantrag.inc.php");
require_once($InclBaseDir."umzugsmitarbeiter.inc.php");
require_once($InclBaseDir."user.inc.php");
require_once($InclBaseDir."umzugsgruppierungen.lib.php");

function umzugsleistungen_leeren($AID) {
    global $db;
    
    $sql = "DELETE FROM mm_umzuege_leistungen WHERE aid = :aid";
    $db->query($sql, array('aid'=>$AID));
}

function umzugsleistungen_laden($AID) {
    global $db;
    
    $sql = "SELECT ul.*, lk.leistungseinheit FROM mm_umzuege_leistungen ul ";
    $sql.= " LEFT JOIN mm_leistungskatalog lk ON ul.leistung_id = lk.leistung_id ";
    $sql.= " WHERE aid = :aid";
    return $db->query_rows($sql, 0, array('aid'=>$AID));
}

function umzugsleistungen_inputWithShipping($AID, array $aInputLeistungen) {
    global $db;
    // Annahme: leistung_ref_id in leistungen bezieht sich auf automatisch anzupassende Shipping-Positionen

    $arr = !empty($aInputLeistungen['leistung_id']) ? $aInputLeistungen['leistung_id'] : [];
    $aLIds = array_map('intval', array_filter($arr, 'is_numeric'));
    if (!count($aLIds)) {
        return [];
    }

    $aHasPos = [
        'Stuhl' => 0,
        'Schreibtisch' => 0,
        'Leuchte' => 0,
    ];
    $rowsById = [];
    $iNumLeistungen = count($arr);
    for($i = 0; $i < $iNumLeistungen; $i++) {
        $_id = (int)$aInputLeistungen['leistung_id'][$i];
        if (!$_id) continue;
        $rowsById[$_id] = [
            'aid' => $AID,
            'leistung_id' => $_id,
            'menge_property' => $aInputLeistungen['menge_property'][$i],
            'menge2_property' => $aInputLeistungen['menge2_property'][$i],
            'menge_mertens' => $aInputLeistungen['menge_mertens'][$i],
            'menge2_mertens' => $aInputLeistungen['menge2_mertens'][$i],
        ];
    }

    $sql = 'SELECT l.leistung_id, l.leistung_ref_id, l.leistungskategorie_id, k.leistungskategorie '
        . ' FROM mm_leistungskatalog AS l'
        . ' JOIN mm_leistungskategorie AS k ON (l.leistungskategorie_id = k.leistungskategorie_id) '
        . ' WHERE leistung_id IN (' . implode(', ', $aLIds) . ') '
        . ' AND l.leistungskategorie_id NOT IN (18, 25) '
        . ' AND k.leistungskategorie NOT IN("Transport", "Rabatt")'
//        . ' AND IFNULL(leistung_ref_id, 0) > 0'
        ;

    $rows = $db->query_rows( $sql );
    $lastQuery = $db->lastQuery;
    $rowsByRefId = [];
    if (count($rows)) {
        foreach ($rows as $_row) {
            $_id = $_row['leistung_id'];
            $_refId = $_row['leistung_ref_id'];
            $_ktgId = (int)$_row['leistungskategorie_id'];
            if ($_ktgId === 21) {
                $aHasPos['Stuhl']++;
            } elseif ($_ktgId === 22) {
                $aHasPos['Schreibtisch']++;
            } elseif ($_ktgId === 23) {
                $aHasPos['Leuchte']++;
            }
            if (!$_refId) {
                continue;
            }
            if (!isset($rowsByRefId[$_refId])) {
                $rowsByRefId[$_refId] = [
                    'aid' => $AID,
                    'leistung_id' => $_refId,
                    'menge_property' => 0,
                    'menge2_property' => 1,
                    'menge_mertens' => 0,
                    'menge2_mertens' => 1,
                ];
            }
            if (isset($rowsById[$_refId])) {
                unset($rowsById[$_refId]);
            }
            $rowsByRefId[$_refId]['menge_property'] += (int)$rowsById[$_id]['menge_property'];
            $rowsByRefId[$_refId]['menge_mertens'] += (int)$rowsById[$_id]['menge_mertens'];
        }

        if ($aHasPos['Stuhl'] > 0 && $aHasPos['Schreibtisch'] > 0 && $aHasPos['Leuchte'] > 0) {
            // Füge Rabatt für Komplettpaket (Stuhl, Schreibtisch und Leutchte) von 25Euro hinzu
            $_lstgId = 237;
            $rowsByRefId[$_lstgId] = [
                'aid' => $AID,
                'leistung_id' => $_lstgId,
                'menge_property' => 1,
                'menge2_property' => 1,
                'menge_mertens' => 1,
                'menge2_mertens' => 1,
            ];
        }
    }
    // echo json_encode(compact('sql', 'rows', 'rowsByRefId', 'aHasPos'));

    $rowsList = array_values($rowsById);
    $rowsRefList = array_values($rowsByRefId);
    $result = array_merge(array_values($rowsById), array_values($rowsByRefId));
    // die(print_r(compact('lastQuery', 'rows', 'rowsList', 'rowsRefList', 'result'), 1));
    return $result;

}

function umzugsleistungen_speichern($AID) {
    //umzugsleistungen_leeren($AID);
    global $db;
    global $user;
    
    $existing_rows = umzugsleistungen_laden($AID);    
    $existing_ids = array_map(function($v){ return $v['leistung_id'];}, $existing_rows);
    //die('<pre>#' . __LINE__ . ' ' . __FILE__ . ' existing_ids: ' . print_r($existing_ids,1).'</pre>');
    $creator = (preg_match('/umzugsteam|admin/', $user['gruppe'] ) ? 'mertens' : 'property' );
    $data = array();
    $lst = getRequest('L', []);
    $aLstDefaults = [
        'menge_mertens' => 1,
        'menge2_mertens' => 1,
        'menge_property' => 1,
        'menge2_property' => 1,
    ];

    $iNumLeistungen = !empty($lst) && !empty($lst['leistung_id']) ? count($lst['leistung_id']) : 0;
    for($i = 0; $i < $iNumLeistungen; $i++) {
        foreach($aLstDefaults as $_k => $_defaultVal) {
            if (!isset($lst[$_k][$i])) {
                $lst[$_k][$i] = $_defaultVal;
            }
        }
    }

    $data = umzugsleistungen_inputWithShipping($AID, $lst);
    $data = array_map(function($item) use($creator) {
        $item['createdby'] = $creator;
        return $item;
        },
        $data
    );
    $edit_ids = array_column($data, 'leistung_id');
    
    $delete_ids = array_diff($existing_ids, $edit_ids);
    //die('<pre>#' . __LINE__ . ' ' . __FILE__ . ' '.print_r($delete_ids,1) . '</pre>');
    
    if (count($data)) {
        $sqlInsert = 'INSERT INTO mm_umzuege_leistungen(aid, leistung_id, createdby,'
             . ' menge_property, menge2_property, menge_mertens, menge2_mertens) '
             . 'VALUES(:aid, :leistung_id, :createdby, '
             . ($creator == 'property' ? ':menge_property ' : ':menge_mertens') . ','
             . ($creator == 'property' ? ':menge2_property ': ':menge2_mertens') . ','
             . ($creator == 'mertens'  ? ':menge_mertens '  : ':menge_property ') . ','
             . ($creator == 'mertens'  ? ':menge2_mertens ' : ':menge2_property ') . ')';
        $sqlUpdate = 
               'Update mm_umzuege_leistungen SET '
             . ($creator == 'property' ? 'menge_property = :menge_property, '  : '')
             . ($creator == 'property' ? 'menge2_property = :menge2_property, ' : '')
             . ($creator == 'mertens'  ? 'menge_mertens = :menge_mertens, '  : 'menge_mertens  = :menge_property, ')
             . ($creator == 'mertens'  ? 'menge2_mertens = :menge2_mertens ' : 'menge2_mertens = :menge2_property ')
             . 'WHERE aid = :aid AND leistung_id = :leistung_id';
        foreach($data as $row) {
            $row['menge_property']  = (isset($row['menge_property'])  ? getFormattedNumber( $row['menge_property']) : 0);
            $row['menge_mertens']   = (isset($row['menge_mertens'])   ? getFormattedNumber( $row['menge_mertens'])  : 0);
            $row['menge2_property'] = (isset($row['menge2_property']) ? getFormattedNumber( $row['menge2_property']) : '' );
            $row['menge2_mertens']  = (isset($row['menge2_mertens'])  ? getFormattedNumber( $row['menge2_mertens'])  : '' );
            
            if (!is_numeric($row['menge2_property'])) $row['menge2_property'] = new DbExpr ('NULL');
            if (!is_numeric($row['menge2_mertens']))  $row['menge2_mertens']  = new DbExpr ('NULL');
            
            if (in_array($row['leistung_id'], $existing_ids)) {
                $db->query($sqlUpdate, $row);
//                die('#' . __LINE__ . ' ' . $db->lastQuery);
            } else {
                $db->query($sqlInsert, $row);
                //die('#' . __LINE__ . ' ' . $db->lastQuery);
            }
            if ($db->error() ) {
                die('#'.__LINE__ . ' ' . $db->error() . '<br>' . PHP_EOL . $db->lastQuery);
            }
        }
    }
    if (count($delete_ids)) {
        $db->query(
             'DELETE FROM mm_umzuege_leistungen '
            .' WHERE aid = :aid AND leistung_id IN (' . implode(',', $delete_ids) . ') '
            .' AND createdby = :creator',
            array( 'aid' => $AID, 'creator' => $creator )
        );
    }
    return '';  
}

function dienstleister_speichern() {
    
}

function umzugsantrag_speichern() {
    global $db;
    global $error;
    global $msg;
    global $_CONF;
    global $MConf;
    global $connid;
    global $user;

    $UpdateToken = "";
    $AID = getRequest("id","");
    $cmd = getRequest("cmd","");
    $name = getRequest("name","");
    $value = getRequest("value","");
    $ASPostItem = getRequest("AS",false);
    $Umzugsarten = array();
    $setStatus = false;
    $addBemerkung = "";
    $enrichedBemerkung = '';

    $cntAS = count(array_diff(array_keys($ASPostItem), array('aid', 'lieferhinweise', 'bemerkungen')));
    if ( ($cntAS > 0 && $cmd !== 'status') && !isset($ASPostItem["name"])) {
        $error.= "Es wurden keine Daten zum Antragsteller übermittelt. Daten konnten nicht gespeichert werden![sp]<br>\n";
        return false;
    }

    if (!empty($ASPostItem["bemerkungen"])) {
        $addBemerkung = $ASPostItem["bemerkungen"];
        $ASPostItem["bemerkungen"] = "";
    }

    $userIsAdmin = (strpos($user["gruppe"], "kunde_report")!==false || strpos($user["gruppe"], "admin")!==false);

    if (!$AID && !empty($ASPostItem["aid"])) {
        $AID = $ASPostItem["aid"];
    }

    if (!$AID) {
        $ASPostItem['personalnr'] = $user['personalnr'];
    }

    $ASConf = $_CONF["umzugsantrag"];
    $USERConf = $_CONF["user"];

    $gruppierungen = getRequest("gruppierteauftraege",null);
    if (!is_null($gruppierungen)) {
        umzugsgruppierungen_speichern($AID, trim($gruppierungen, ','));
    }

    $AS = new ItemEdit($ASConf, $connid, $user, $AID);
    //echo "#".__LINE__. " AID:$AID; AS->arrDbdata:".print_r($AS->arrDbdata,1)."<br>\n";
    $doValidate = true;
    if (!$AS->itemExists || $AS->arrDbdata["antragsstatus"]=="bearbeitung") {
        $setStatus = "temp";
        //$doValidate = false;
        //foreach($ASConf["Fields"] as $field => $fConf) $ASConf["Fields"][$field]["required"] = false;
    }

    $isSpeicher = $cmd === 'speichern';
    $isRueckgabe = ($cmd === 'status' && $name === 'zurueckgeben');
    $isStorno = (strcmp($name, 'abgeschlossen') === 0 && strcmp($value, 'Storniert') === 0);
    $issetStatus = isset($ASPostItem['umzugsstatus']);
    $isNewStatus = !$issetStatus || (0 === strcasecmp($ASPostItem, $AS->arrDbdata['umzugsstatus']));

    if (
        $cmd !== 'speichern' && !$isStorno && !$isRueckgabe
    ) {
        $AS->arrConf["Fields"]["umzugstermin"]["required"] = ($AS->itemExists && $AS->arrDbdata["antragsstatus"]=="gesendet");
        $AS->arrConf["Fields"]["umzugszeit"]["required"] = ($AS->itemExists && $AS->arrDbdata["antragsstatus"]=="gesendet");
    }


    $aDBG = [ '#' . __LINE__ . ' ' . print_r(compact('AID'), 1)];
    if ($AID) {
        $aDBG[] = '#' . __LINE__ . ' ' . print_r(compact('AID'), 1);
        if (!$AS->itemExists) {
            $error.= "Es wurde kein Umzugsantrag mit der übermittelten Antrags-ID gefunden!<br>\n";
            $aDBG[] = '#' . __LINE__ . ' NO ITEM FOUND WITH AID' . $AID ;
            return false;
        }

        if ($AS->arrDbdata["umzugsstatus"] != "temp" && $AS->arrDbdata["umzugsstatus"] != "zurueckgegeben"  && !$userIsAdmin) {
            $error.= "Der Antrag wurde bereits zur Genehmigung und Bearbeitung gesendet!<br>\n";
            $error.= "Wenden Sie sich für Änderungen an die Bearbeitungsstelle!<br>\n";
            return false;
        }


        $_umzugsstatus = $AS->arrDbdata['umzugsstatus'];
        $aDBG[] = '#' . __LINE__ . ' CHECK UMZUGSSTATUS FÜR TOURZUWEISUNG ' . print_r(compact('_umzugsstatus'), 1);
        if (in_array($AS->arrDbdata['umzugsstatus'], ['angeboten', 'beantragt', 'bestaetigt'])) {
            $_tourField = 'tour_kennung';
            $_tk = isset($ASPostItem[$_tourField]) ? $ASPostItem[$_tourField] : 'NO INPUT FOR ' . $_tourField;
            $aDBG[] = '#' . __LINE__ . ' CHECK TOURZUWEISUNG' . print_r(compact('_tk'), 1);
            if (isset($ASPostItem['tour_kennung'])) {
                $_strcmp = strcmp($ASPostItem['tour_kennung'], $AS->arrDbdata['tour_kennung']);
                $aDBG[] = '#' . __LINE__ . ' Tourkennung gefunden: NEU ' . $ASPostItem['tour_kennung'] . ', ALT ' . $AS->arrDbdata['tour_kennung'] . ' strcmp: ' . json_encode($_strcmp);
                if (strcmp($ASPostItem['tour_kennung'], $AS->arrDbdata['tour_kennung']) !== 0) {
                    $ASPostItem['tour_zugewiesen_am'] = date('Y-m-d H:i:s');
                    $ASPostItem['tour_zugewiesen_von'] = $user['user'];
                }
            }
        }
    }
    $aDBG[] = '#' . __LINE__ . ' CHECK ENDE TOURZUWEISUNG';
    $arrInput = $AS->arrInput;
    $arrDbdata = $AS->arrDbdata;
    $_request = $_REQUEST;
    // die(print_r(compact('aDBG', 'arrInput', 'arrDbdata', '_request', 'ASPostItem'), 1));

    $MAPostItems = get_ma_post_items();
    if ($AID && $MConf['min_ma'] && (!is_array($MAPostItems) || !count($MAPostItems)) ) {
        $error.= "Es wurden keine Mitarbeiter für den Auftrag ausgewählt.<br>\n";
        if ($AS->itemsExists) {
            $error.= "Falls Sie den Auftrag stornieren möchten, klicken Sie den 'Stornieren'-Button.<br>\n";
        }
        return false;
    }

    if (!$userIsAdmin) {
        $ASPostItem["antragsstatus"] = "bearbeitung";
    }
    $AS->loadInput($ASPostItem);
    if (($cntAS > 0 && $cmd !== 'status') && !$AS->checkInput()) {
        $error.= "Überprüfen Sie die Angaben zum Antragssteller!<br>\n";
        $error.= $AS->Error;
        foreach($AS->arrErrFlds as $field => $err) $error.= $field.":".$err."<br>\n";
        $error.= $AS->Warning;
        return false;
    } else {
        if ($addBemerkung) {
            $AS->arrInput["bemerkungen"] = "Bemerkung von " . $user["user"]
                . " am " . date("d.m.Y")
                . " um " . date("H:i")."\n";

            if ($cmd === 'status') {
                $AS->arrInput["bemerkungen"].= $name . ' ' . $value . " - Grund:\n";
            }
            $AS->arrInput["bemerkungen"].= $addBemerkung;
            $enrichedBemerkung = str_replace("\n", "\r\n", $AS->arrInput["bemerkungen"]);

            if (!empty($AS->arrDbdata["bemerkungen"]) && trim($AS->arrDbdata["bemerkungen"])) {
                $AS->arrInput["bemerkungen"].= "\n\n".$AS->arrDbdata["bemerkungen"];
            }
        } else {
            $AS->arrInput["bemerkungen"] = (!empty($AS->arrDbdata["bemerkungen"])) ? $AS->arrDbdata["bemerkungen"] : "";
        }

        if (!$AS->save()) {

        }
        if (!$AID) {
            $AID = $AS->id;
            $UpdateToken = $AS->arrInput["token"]; //substr(md5($AID.time()),0,10);
        }
        $error.= $AS->Error;
        $error.= $AS->dbError;
    }

    if (!$AID) {
        $error.= "Systemfehler: Antrag konnte nicht angelegt werden (Antrags->id:".$AS->id.")!<br>\n";
        return false;
    }

    $save_ul_count = 0;
    if ($cntAS > 0 || $cmd !== 'status') {
        umzugsleistungen_speichern($AID);
        $ulrows = umzugsleistungen_laden($AID);
        foreach($ulrows as $row) {
            if ($row['leistungseinheit'] === 'AP') {
                $save_ul_count+= (int)(is_numeric($row['menge_mertens']) ? $row['menge_mertens'] : $row['menge_property']);
            }
        }
    }

    $MAConf = $_CONF["umzugsmitarbeiter"];
    //if (!$doValidate) foreach($MAConf["Fields"] as $field => $fConf) $MAConf["Fields"][$field]["required"] = false;

    $sql = "DELETE FROM `".$MAConf["Table"]."` WHERE aid = \"".$db->escape($AS->id)."`\"";
    $db->query($sql);

    $save_count = 0;
    $save_errors = "";
    foreach($MAPostItems as $i => $MAItem) {
        $MA = new ItemEdit($MAConf, $connid, $user, false);
        //$error.= print_r($MAItem,1)."<br>\n";
        $MAItem["aid"] = $AID;
        $MAItem["raumid"] = get_raumid_byGER($MAItem["gebaeude"], $MAItem["etage"], $MAItem["raumnr"]);
        $MAItem["zraumid"] = get_raumid_byGER($MAItem["zgebaeude"], $MAItem["zetage"], $MAItem["zraumnr"]);
        $MA->loadInput($MAItem);

        $MA->arrInput["name"] = strtoupper($MA->arrInput["name"]);
        $MA->arrInput["vorname"] = strtoupper($MA->arrInput["vorname"]);
        $MA->arrInput["extern_firma"] = strtoupper($MA->arrInput["extern_firma"]);

        if (!$MA->save()) {
            $save_errors.= "Der ".($i+1).". Mitarbeitereintrag ".$MAItem["name"]." ".$MAItem["vorname"]." konnte nicht gespeichert werden!<br>\n";
            $save_errors.= "Error: ".$MA->Error;
            $save_errors.= "#362 dbError: ".$MA->dbError;
        } else {
            $save_count++;
            if ($MAItem["umzugsart"]) {
                if (isset($Umzugsarten[$MAItem["umzugsart"]])) $Umzugsarten[$MAItem["umzugsart"]]++;
                else $Umzugsarten[$MAItem["umzugsart"]] = 1;
            }
        }
    }

    $sNumUmzugsarten = "";
    if (count($Umzugsarten)) {
        foreach($Umzugsarten as $art_name => $art_num) $sNumUmzugsarten.= ($sNumUmzugsarten?", ":"").$art_num."x".$art_name;
    }

//	echo "Düsseldorf => arrInput[ort]: " . $AS->arrInput["ort"]
//        . ' => strcmp: ' . strcmp("Düsseldorf", $AS->arrInput["ort"] );
//	exit;
    $sql = "UPDATE `".$USERConf["Table"]
        . "` SET `fon` = \""  . $db->escape($AS->arrInput["fon"]) . "\",\n"
        . " `standort` = \""  . $db->escape($AS->arrInput["ort"]) . "\",\n" // Düsseldorf\",\n " //
        . " `gebaeude` = \""  . $db->escape($AS->arrInput["gebaeude"] ?? '')."\" \n";
    $sql.= "\n WHERE uid = \"" . $db->escape($AS->arrInput["antragsteller_uid"]) . "\"";
    $db->query($sql);
    $_err = $db->error();
    if ($_err) {
        $row = $db->query_row('show variables like "character_set_database"');
        $save_errors.= '#383 ' . $_err
            . "<br>\n" . $sql . "<br>\n"
            . print_r(compact('_err', 'sql', 'row'), 1);
    }

    $sql = "UPDATE `".$ASConf["Table"]."` SET `mitarbeiter_num` = $save_ul_count,"
        ." `bearbeiter_bemerkung` = \"".$db->escape($sNumUmzugsarten)."\" \n";

    if ($setStatus) {
        $sql.= ", `umzugsstatus`='$setStatus', `umzugsstatus_vom`=NOW()";
    }
    if ($UpdateToken) {
        $sql.= ",\n token = \"".$db->escape($UpdateToken)."\"";
    }
    $sql.= "\n WHERE aid = \"".$db->escape($AS->id)."\"";
    //die('#'.__LINE__ . ' ' . __FILE__ . PHP_EOL . __FUNCTION__ . PHP_EOL . 'Before exec sql' . PHP_EOL . $sql);
    $db->query($sql);
    if ($db->error()) {
        $save_errors.= '#392 ' . $db->error()."<br>\n".$sql."<br>\n";
    }

    if ($save_errors) {
        $error.= "#396 Es konnten nicht alle Mitarbeiterdaten gespeichert werden!<br>\n".$save_errors;
        return false;
    }

    if ($enrichedBemerkung) {
        $authorUser = $user;
        if (umzugsantrag_mailinform($AID, "neuebemerkung", $enrichedBemerkung, $authorUser)) {
            $msg.= "Mail mit neuer Bemerkung wurde gesendet!<br>\n";
        } else {
            $error.= "Fehler beim Mailversand [#421]!<br>\n";
        }
    }
    return $AID;
}

function umzugsantrag_add_bemerkung() {
    global $db;
    global $error;
    global $msg;
    global $_CONF;
    global $MConf;
    global $connid;
    global $user;

    $UpdateToken = "";
    $AID = getRequest("id","");
    $ASPostItem = getRequest("AS",false);
    $addBemerkung = "";
    $enrichedBemerkung = '';
    $NL = "\n";

    if (!empty($ASPostItem["aid"])) {
        $AID = $ASPostItem["aid"];
    }

    if (empty($AID)) {
        return false;
    }

    if (!empty($ASPostItem["bemerkungen"])) {
        $addBemerkung = trim($ASPostItem["bemerkungen"]);
        unset($ASPostItem["bemerkungen"]);
    }

    if (!empty($ASPostItem['add_bemerkungen'])) {
        $addBemerkung = trim($ASPostItem["add_bemerkungen"]);
    }

    if (empty($addBemerkung)) {
        return false;
    }

    $userIsAdmin = (strpos($user["gruppe"], "kunde_report")!==false || strpos($user["gruppe"], "admin")!==false);


    $ASConf = $_CONF["umzugsantrag"];

    $AS = new ItemEdit($ASConf, $connid, $user, $AID);

    if (!$AS->itemExists) {
        $error.= "Es wurde kein Umzugsantrag mit der übermittelten Antrags-ID gefunden!<br>\n";
        $aDBG[] = '#' . __LINE__ . ' NO ITEM FOUND WITH AID' . $AID ;
        return false;
    }

    $AS->arrConf["Fields"]["umzugstermin"]["required"] = ($AS->itemExists && $AS->arrDbdata["antragsstatus"]=="gesendet");
    $AS->arrConf["Fields"]["umzugszeit"]["required"] = ($AS->itemExists && $AS->arrDbdata["antragsstatus"]=="gesendet");

    $db = dbconn::getInstance();

    $AS->loadInput($ASPostItem);
    if ($addBemerkung) {
        $AS->arrInput["bemerkungen"] = "Bemerkung von " . $user["user"]
            . " am " . date("d.m.Y")
            . " um " . date("H:i")."\n";
        $kunde_uid = $AS->arrDbdata['antragsteller_uid'];

        $AS->arrInput["bemerkungen"].= $addBemerkung;
        $enrichedBemerkung = str_replace("\n", "\r\n", $AS->arrInput["bemerkungen"]);

        $aSet = [];
        if ($kunde_uid == $user['uid'] || $user['gruppe'] !== 'admin') {
            $aSet[] = 'neue_bemerkungen_fuer_admin = (neue_bemerkungen_fuer_admin + 1)';
        }

        if ($kunde_uid != $user['uid'] || $user['gruppe'] === 'admin') {
            $aSet[] = 'neue_bemerkungen_fuer_kunde = (neue_bemerkungen_fuer_kunde + 1)';
        }

        $sql = 'UPDATE mm_umzuege SET ' . $NL
            . ' bemerkungen = TRIM(CONCAT(:bemerkung, "\n\n", bemerkungen))'
            . (count($aSet) > 0 ? ', ' . $NL . implode(",\n", $aSet) : '') . $NL
            . ' WHERE aid = :aid LIMIT 1';

        $db->query($sql, [ 'bemerkung' => $enrichedBemerkung, 'aid' => $AID]);

        if ($db->error()) {
            $error.= $db->error();
            return false;
        }
    }

    $error.= $AS->Error;
    $error.= $AS->dbError;

    if ($enrichedBemerkung) {
        $authorUser = $user;
        if (umzugsantrag_mailinform($AID, "neuebemerkung", $enrichedBemerkung, $authorUser)) {
            $iNumMails = umzugsantrag_mailinform_get_numMails();
            if ($iNumMails > 0) {
                if ($user['gruppe'] === 'admin') {
                    $msg .= "Mail mit neuer Bemerkung wurde gesendet [Anzahl: $iNumMails]!<br>\n";
                } else {
                    $msg .= "Ihre Daten wurden weitergeleiter!<br>\n";
                }
            }
        } else {
            if ($user['gruppe'] === 'admin') {
                $error.= "Fehler beim Mailversand [#421]!<br>\n";
            }
        }
    }
    return $AID;
}
