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

function umzugsleistungen_speichern($AID) {
    //umzugsleistungen_leeren($AID);
    global $db;
    global $user;
    
    $existing_rows = umzugsleistungen_laden($AID);    
    $existing_ids = array_map(function($v){ return $v['leistung_id'];}, $existing_rows);
    //die('<pre>#' . __LINE__ . ' ' . __FILE__ . ' existing_ids: ' . print_r($existing_ids,1).'</pre>');
    $creator = (preg_match('/umzugsteam|admin/', $user['gruppe'] ) ? 'mertens' : 'property' );
    $data = array();
    $lst = getRequest('L');
    
    
//    if (empty($lst['leistung_id']) || !is_array($lst['leistung_id'])) {
//        die('<pre>#' . __LINE__ . ' ' . __FILE__ . ' return true</pre>');
//        return true;
//    }
    
    $edit_ids = array();
    if (!empty($lst['leistung_id'])) for($i = 0; $i < count($lst['leistung_id']); ++$i) {
        if (!intval($lst['leistung_id'][$i])) continue;
        $edit_ids[] = $lst['leistung_id'][$i];
        $data[] = array(
            'aid' => $AID,
            'leistung_id' => $lst['leistung_id'][$i],
            'menge_property' => $lst['menge_property'][$i],
            'menge2_property' => $lst['menge2_property'][$i],
            'menge_mertens' => $lst['menge_mertens'][$i],
            'menge2_mertens' => $lst['menge2_mertens'][$i],
            'createdby' => $creator,
        );
    }
    //die('#'.__LINE__ . ' ' . __FILE__ . ' ' . print_r($lst,1) . '<br>' . PHP_EOL . 'data: ' . print_r($data,1) );
    
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
	
        $cntAS = count(array_diff(array_keys($ASPostItem), array('aid', 'bemerkungen')));
	if ( ($cntAS > 0 && $cmd !== 'status') && !isset($ASPostItem["name"])) {
		$error.= "Es wurden keine Daten zum Antragsteller übermittelt. Daten konnten nicht gespeichert werden![sp]<br>\n";
		return false;
	}
	
	if (!empty($ASPostItem["bemerkungen"])) {
		$addBemerkung = $ASPostItem["bemerkungen"];
		$ASPostItem["bemerkungen"] = "";
	}
	
	$userIsAdmin = (strpos($user["gruppe"], "kunde_report")!==false || strpos($user["gruppe"], "admin")!==false);
	
	if (!$AID && !empty($ASPostItem["aid"])) $AID = $ASPostItem["aid"];
	
	$ASConf = $_CONF["umzugsantrag"];
	$USERConf = $_CONF["user"];
	
	$gruppierungen = getRequest("gruppierteauftraege",null);
	if (!is_null($gruppierungen)) umzugsgruppierungen_speichern($AID, trim($gruppierungen, ','));
	
	$AS = new ItemEdit($ASConf, $connid, $user, $AID);
	//echo "#".__LINE__. " AID:$AID; AS->arrDbdata:".print_r($AS->arrDbdata,1)."<br>\n";
	$doValidate = true;
	if (!$AS->itemExists || $AS->arrDbdata["antragsstatus"]=="bearbeitung") {
		$setStatus = "temp";
		//$doValidate = false;
		//foreach($ASConf["Fields"] as $field => $fConf) $ASConf["Fields"][$field]["required"] = false;
	}
	
        $AS->arrConf["Fields"]["umzugstermin"]["required"] = ($AS->itemExists && $AS->arrDbdata["antragsstatus"]=="gesendet");
        $AS->arrConf["Fields"]["umzugszeit"]["required"] = ($AS->itemExists && $AS->arrDbdata["antragsstatus"]=="gesendet");
	
	if ($AID) {
		if (!$AS->itemExists) {
			$error.= "Es wurde kein Umzugsantrag mit der übermittelten Antrags-ID gefunden!<br>\n";
			return false;
		}
		
		if ($AS->arrDbdata["umzugsstatus"] != "temp" && $AS->arrDbdata["umzugsstatus"] != "zurueckgegeben"  && !$userIsAdmin) {
			$error.= "Der Antrag wurde bereits zur Genehmigung und Bearbeitung gesendet!<br>\n";
			$error.= "Wenden Sie sich für Änderungen an die Bearbeitungsstelle!<br>\n";
			return false;
		}
	}
	
	$MAPostItems = get_ma_post_items();
	if ($AID && $MConf['min_ma'] && (!is_array($MAPostItems) || !count($MAPostItems)) ) {
		$error.= "Es wurden keine Mitarbeiter für den Umzug ausgewählt.<br>\n";
		if (!empty($AS->itemsExists)) {
			$error.= "Falls Sie den Auftrag stornieren möchten, klicken Sie den 'Stornieren'-Button.<br>\n";
		}
		return false;
	}
	
	if (!$userIsAdmin) $ASPostItem["antragsstatus"] = "bearbeitung";
	$AS->loadInput($ASPostItem);
	if (($cntAS > 0 && $cmd !== 'status') && !$AS->checkInput()) {
            $error.= "Überprüfen Sie die Angaben zum Antragssteller!<br>\n";
            $error.= $AS->Error;
            foreach($AS->arrErrFlds as $field => $err) $error.= $field.":".$err."<br>\n";
            $error.= $AS->Warning;
            return false;
	} else {
            if ($addBemerkung) {
                $AS->arrInput["bemerkungen"] = "Bemerkung von ".$user["user"]." am ".date("d.m.Y")." um ".date("H:i")."\n";
                if ($cmd === 'status') $AS->arrInput["bemerkungen"].= $name . ' ' . $value . " - Grund:\n";
                $AS->arrInput["bemerkungen"].= trim($addBemerkung);
                if (!empty($AS->arrDbdata["bemerkungen"])) $AS->arrInput["bemerkungen"].= "\n\n".$AS->arrDbdata["bemerkungen"];
            } else {
                $AS->arrInput["bemerkungen"] = (!empty($AS->arrDbdata["bemerkungen"])) ? $AS->arrDbdata["bemerkungen"] : "";
            }
            // @ob_end_flush();
            // echo '#' . __LINE__ . ' ' . __FILE__ . "\n" . var_export($AS->arrInput, 1);
            // exit;
            $AS->save();
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
                $save_errors.= "dbError: ".$MA->dbError;
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
	
	$sql = "UPDATE `".$USERConf["Table"]."` SET `fon` = \"".$db->escape($AS->arrInput["fon"])
                ."\", `standort`=\"".$db->escape($AS->arrInput["ort"])
                ."\", `gebaeude` = \"".$db->escape($AS->arrInput["gebaeude"] ?? '')."\" \n";
	$sql.= "\n WHERE uid = \"".$db->escape($AS->arrInput["antragsteller_uid"])."\"";
	$db->query($sql);
	if ($db->error()) $save_errors.= $db->error()."<br>\n".$sql."<br>\n";
	
	$sql = "UPDATE `".$ASConf["Table"]."` SET `mitarbeiter_num` = $save_ul_count,"
              ." `bearbeiter_bemerkung` = \"".$db->escape($sNumUmzugsarten)."\" \n";
	if ($setStatus) $sql.= ", `umzugsstatus`='$setStatus', `umzugsstatus_vom`=NOW()";
	if ($UpdateToken) $sql.= ",\n token = \"".$db->escape($UpdateToken)."\"";
	$sql.= "\n WHERE aid = \"".$db->escape($AS->id)."\"";
        //die('#'.__LINE__ . ' ' . __FILE__ . PHP_EOL . __FUNCTION__ . PHP_EOL . 'Before exec sql' . PHP_EOL . $sql);
        $db->query($sql);
	if ($db->error()) $save_errors.= $db->error()."<br>\n".$sql."<br>\n";
	
	if ($save_errors) {
            $error.= "Es konnten nicht alle Mitarbeiterdaten gespeichert werden!<br>\n".$save_errors;
            return false;
	}
	return $AID;
}
