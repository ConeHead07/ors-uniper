<?php 
if (basename(__FILE__)==basename($_SERVER["PHP_SELF"])) require_once("../header.php");

require_once($InclBaseDir."umzugsantrag.inc.php");
require_once($InclBaseDir."umzugsmitarbeiter.inc.php");

function umzugsantrag_status($AID, $name, $value) {
    /// die('#'.__LINE__ . ' ' . __FILE__ . ' ' . __FUNCTION__ . '(' . print_r(func_get_args(),1) . ')');
	global $db;
	global $error;
	global $msg;
	global $_CONF;
    global $MConf;
	global $connid;
	global $user;
	
	if(!$AID) {
        $error.= "Fehlende Antrags-ID für Storniervorgang!<br>\n";
        return false;
	}
	
	$sql_set = "";
	$ASConf = $_CONF["umzugsantrag"];
	$MAConf = $_CONF["umzugsmitarbeiter"];
    $userIsAdmin = preg_match('/admin|umzugsteam/', $user["gruppe"]);
	
	$ASError = "";
	$AS = new ItemEdit($ASConf, $connid, $user, $AID);
	$AS->loadDbdata();
	$AS->dbdataToInput();

	if (!$AS->arrInput["umzugstermin"]) {
		$ASError.= "Der Umzugstermin wurde noch nicht festgesetzt!<br>\n";
	}

	if (!$AS->arrInput["fon"]) {
        $ASError.= "Telefon: fehlende Eingabe!<br>\n";
    }

	if (!$AS->arrInput["email"]) {
		$ASError.= "Email: fehlende Eingabe!<br>\n";
	}

	if($ASError) {
		$error.= "Es wurden noch nicht alle erforderlichen Felder ausgefüllt!<br>\n";
		$error.= $ASError;
		//$error.= $AS->Warning;
		return false;
	}
	
	$MAPostItems = get_ma_post_items();
	if ($MConf['min_ma'] && (!is_array($MAPostItems) || !count($MAPostItems))) {
		$error.= "Es wurden keine Mitarbeiter fär den Auftrag ausgewählt.<br>\n";
		if ($AS->itemExists) {
			$error.= "Falls Sie den Auftrag stornieren möchten, klicken Sie den 'Stornieren'-Button.<br>\n";
		}
		return false;
	}
	
	$MAError = false;
	foreach($MAPostItems as $i => $MAItem) {
		$MA = new ItemEdit($MAConf, $connid, $user, false);
		$MAItem["aid"] = $AID;
		$MA->loadInput($MAItem);
		if (!$MA->checkInput()) {
			$error.= "Fehlerhafte Angaben beim ".($i+1).". Mitarbeiter ".$MAItem["name"]."!<br>\n";
			$MAError = true;
		}
	}
	if ($MAError) {
		return false;
	}
	
	
	$errIstAbgeschlossen = (!$AS->arrDbdata["abgeschlossen"] || $AS->arrDbdata["abgeschlossen"]=="Init")
        ? ""
        : "Der Auftrag wurde bereits abgeschlossen (".$AS->arrDbdata["abgeschlossen"].")!";

	$errIstGenehmigt     = ($AS->arrDbdata["genehmigt"]=="Init")
        ? ""
        : "Der Auftrag wurde bereits " . ($AS->arrDbdata["genehmigt"]=="Ja" ? "genehmigt" : "abgelehnt") . "!";

	$errIstBestaetigt    = ($AS->arrDbdata["bestaetigt"] !== "Ja")
        ? ""
        : "Der Auftrag wurde bereits bestätigt!";
	
	$sendmail_newstatus = "";
	$new_status = "";
	if ($AID) {
		if (!$AS->itemExists) {
			$error.= "Es wurde kein Umzugsantrag mit der übermittelten Antrags-ID gefunden!<br>\n";
			return false;
		}
		
		switch($name) {
			case "gesendet":
			break;
                    
            case "erneutpruefen":
                $sendmail_newstatus = "erneutpruefen";
                $newstatus = "erneutpruefen";
            break;
			
			case "geprueft":
			if (!$errIstAbgeschlossen && $AS->arrDbdata["antragsstatus"]!="bearbeitung") {
                $sql_set = "`geprueft` = \"".$db->escape($value)."\",\n geprueft_am=NOW(),\ngeprueft_von=\"".$db->escape($user["user"])."\"";
                $sendmail_newstatus = "geprueft";
                $newstatus = "geprueft";

                if ($value === 'Ja' && $AS->arrDbdata['umzug'] === 'Nein' ) {
                    $newstatus = "genehmigt";
                    $sql_set.= ",`genehmigt_br` = \"".$db->escape($value)."\",\n `genehmigt_br_am`=NOW(),\n`genehmigt_br_von`=\"".$db->escape($user["user"])."\"";
                    $sendmail_newstatus = "genehmigt";
                }
			} else {
                if ($errIstAbgeschlossen) {
                    $error.= $errIstAbgeschlossen."<br>\n";
                }
                if ($AS->arrDbdata["antragsstatus"]=="bearbeitung") {
                    $error.= "Antrag wurde vom Antragsteller noch nicht gesendet!<br>\n";
                }
			}
			break;
			
			case "zurueckgegeben":
			case "zurueckgeben":
                $sql_set = " `antragsstatus`=\"bearbeitung\", `zurueckgegeben` = \"".$db->escape($value)."\",\n zurueckgegeben_am=NOW(),\nzurueckgegeben_von=\"".$db->escape($user["user"])."\"";
                $sendmail_newstatus = "zurueckgegeben";
                $newstatus = "zurueckgegeben";
			break;
			
			case "genehmigt":
			if (!$errIstAbgeschlossen) { // && !$errIstBestaetigt && $AS->arrDbdata["geprueft"] == "Ja") {
                $newstatus = ($value == "Ja") ? "genehmigt" :"abgelehnt";
                $sql_set = "`genehmigt_br` = \"".$db->escape($value)."\",\n `genehmigt_br_am`=NOW(),\n`genehmigt_br_von`=\"".$db->escape($user["user"])."\"";
                $sendmail_newstatus = "genehmigt";

                if ($userIsAdmin && $value=='Ja' && $AS->arrInput['geprueft']!=='Ja') {
                    $sql_set.= ",`geprueft` = \"".$db->escape($value)."\",\n geprueft_am=NOW(),\ngeprueft_von=\"".$db->escape($user["user"])."\"";
                }
			} else {
                if ($errIstAbgeschlossen) {
                    $error.= $errIstAbgeschlossen."<br>\n";
                }
                if (0 && $errIstBestaetigt) {
                    $error.= $errIstBestaetigt."<br>\n";
                }
                if ($MConf['genehmigung_requires_pruefung'] && $AS->arrDbdata["geprueft"] != "Ja") {
                    $error.= "Auftrag kann erst nach Prüfung genehmigt werden!";
                }
                return false;
			}
			break;
			
			case "bestaetigt":
			if ($AS->arrDbdata["abgeschlossen"]=="Init" && (!$MConf['bestaetigung_requires_genehmigung'] || $AS->arrDbdata["genehmigt_br"] === "Ja")) {
				$sql_set = "`bestaetigt` = \"".$db->escape($value)."\",\n `bestaetigt_am`=NOW(),\n`bestaetigt_von`=\"".$db->escape($user["user"])."\"";
				$sendmail_newstatus = "bestaetigt";
				$newstatus = "bestaetigt";
			} else {
				if ($errIstAbgeschlossen) {
				    $error.= $errIstAbgeschlossen."<br>\n";
                }
				if ($AS->arrDbdata["genehmigt_br"] != "Ja") {
				    $error.= "Auftrag kann erst nach Genehmigung bestätigt werden!";
                }
				return false;
			}
			break;
			
			case "abgeschlossen":
			$sql_set = "`abgeschlossen` = \"".$db->escape($value)."\",\n `abgeschlossen_am`=NOW(),\n`abgeschlossen_von`=\"".$db->escape($user["user"])."\"";
			$sendmail_newstatus = "abgeschlossen";
			$newstatus = "abgeschlossen";
			break;
			
			case "storniert":
			$sql_set = "`abgeschlossen` = \"Storniert\",\n `abgeschlossen_am`=NOW(),\n`abgeschlossen_von`=\"".$db->escape($user["user"])."\"";
			$newstatus = "storniert";
			break;
			
			default:
			$error.= "Ungültiger Statusaufruf $name!";
			return false;
		}
		
		if ($newstatus) {
			$sql_set.= ($sql_set?",\n":"")."`umzugsstatus`=\"$newstatus\", `umzugsstatus_vom` = NOW()";
		}
		
		if ($sql_set) {
			$sql = "UPDATE `".$ASConf["Table"]."` SET ".$sql_set."\n WHERE aid = \"".$db->escape($AID)."\"";
			$db->query($sql);
			if ($db->error()) {
				$error.= "Beim Setzen des Umzugsstatus ist ein Fehler aufgetreten!<br>\n";
			}
		}
	}
	
	if (getRequest("umzugsart") != "Datenpflege") {
		if ($sendmail_newstatus) {
			if (umzugsantrag_mailinform($AID, $sendmail_newstatus, $value)) {
                if ($user['gruppe'] === 'admin') {
                    $msg .= "Mail wurde gesendet!<br>\n";
                } else {
                    $msg.= "Ihre Daten wurden weitergeleiter!<br>\n";
                }
			} else {
                if ($user['gruppe'] === 'admin') {
                    $error.= "Fehler beim Mailversand [#213]!<br>\n";
                } else {
                    $error.= "Fehler im Nachrichtensystem [#215]!<br>\n";
                }
			}
		}
	}
	return true;
}
