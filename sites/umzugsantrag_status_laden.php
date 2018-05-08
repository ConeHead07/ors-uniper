<?php 
if (basename(__FILE__)==basename($_SERVER["PHP_SELF"])) require_once("../header.php");
require_once($MConf["AppRoot"].$MConf["Inc_Dir"]."php_json.php");

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

function umzugsantrag_status_laden($AID) {
	global $db;
	global $error;
	global $msg;
	global $_CONF;
	global $connid;
	global $user;
	
	$ASJson = "";
	
	if (!$AID) {
		$error.= "Statusdaten können erst nach erstmaligem Speichern geladen werden!<br>\n";
		return false;
	}
	$AS = new ItemEdit($_CONF["umzugsantrag"], $connid, $user, $AID);
	
	// If AID: Bearbeitungsformular mit DB-Daten
	if ($AID) {
		$AS->loadDbdata();
		$AS->dbdataToInput();
		$ASJson = "{";
		$i=0;
		foreach($AS->arrInput as $field => $value) {
			$ASJson.= ($i?",\n":"")."\t\"$field\":\"".json_escape($value)."\"";
			$i++;
		}
		$ASJson.= ($i?"\n":"")."}";
		
		$JsonData = "UmzugsdatenAS = $ASJson;\n";
		return $JsonData;
	}
	
	
}

?>