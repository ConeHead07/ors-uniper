<?php 
if (basename(__FILE__)==basename($_SERVER["PHP_SELF"])) require_once("../header.php");

require_once($InclBaseDir."umzugsantrag.inc.php");
require_once($InclBaseDir."umzugsmitarbeiter.inc.php");

if (!function_exists("get_ma_post_items")){ function get_ma_post_items() {
	global $_POST;
	//echo "<pre>#".__LINE__." ".basename(__FILE__)." _POST:".print_r($_POST,1)."</pre><br>\n";
	
	$aMaItems = array();
	if (!empty($_POST["MA"])) for($i = 0; $i < count($_POST["MA"]["vorname"]); $i++) {
		$aMaItems[$i]["ID"] = $i+1;
		foreach($_POST["MA"] as $fld => $aTmp) {
			$aMaItems[$i][$fld] = $_POST["MA"][$fld][$i];
		}
	}
	return $aMaItems;
}}

function umzugsantrag_antrag_stornieren() {
	global $db;
	global $error;
	global $msg;
	global $LoadScript;
	global $_CONF;
	global $connid;
	global $user;
	
	$AID = getRequest("id","");
	$ASPostItem = getRequest("AS",false);
	
	if ($AID && !empty($ASPostItem["aid"])) $AID = $ASPostItem["aid"];
	
	if(!$AID) {
		$error.= "Fehlende Antrags-ID für Storniervorgang!<br>\n";
		return false;
	}
	
	$ASConf = $_CONF["umzugsantrag"];
	$AS = new ItemEdit($ASConf, $connid, $user, $AID);
	
	if ($AID) {
		if (!$AS->itemExists) {
			$error.= "Es wurde kein Umzugsantrag mit der übermittelten Antrags-ID gefunden!<br>\n";
			return false;
		}
		if ($AS->arrDbdata["antragsstatus"] == "gesendet") {
			$error.= "Der Antrag wurde bereits zur Genehmigung und Bearbeitung gesendet!<br>\n";
			$error.= "Wenden Sie sich zur Auftragsstornierung an die Bearbeitungsstelle!<br>\n";
			return false;
		}
	}
	$sql = "UPDATE `".$ASConf["Table"]."` SET antragsstatus=\"storniert\", umzugsstatus=\"storniert\" WHERE `aid` = \"".$db->escape($AID)."\"";
	$db->query($sql);
	if ($db->error()) {
		$msg.= "Systemehler beim Stornieren des Auftrag! Bitte versuchen Sie es später noch mal oder wenden Sie sich an die Bearbeitungsstelle!<br>\n";
		$msg.= $db->error()."<br>\n".$sql."<br>\n";
		return false;
	} else {
		$msg.= "Auftrag wurde storniert!<br>\n";
	}
	
	$MAConf = $_CONF["umzugsmitarbeiter"];
	
	$sql = "DELETE FROM `".$MAConf["Table"]."` WHERE aid = \"".$db->escape($AS->id)."`\"";
	$db->query($sql);
	
	$sql = "DELETE FROM `mm_umzuege_leistungen` WHERE aid = \"".$db->escape($AS->id)."`\"";
	$db->query($sql);
	
	if ($db->error()) {
		$msg.= "Fehler beim Löschen der Mitarbeiterdaten!<br>\n";
	}
	return true;
}

?>