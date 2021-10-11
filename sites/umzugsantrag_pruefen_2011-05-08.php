<?php 
if (basename(__FILE__)==basename($_SERVER["PHP_SELF"])) require_once("../header.php");
require_once($InclBaseDir."umzugsantrag.inc.php");
require_once($InclBaseDir."umzugsantrag.fnc.php");
require_once($InclBaseDir."umzugsmitarbeiter.inc.php");
require_once($InclBaseDir."umzugsmitarbeiter.fnc.php");

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


function abteilung_exists($a) {
	global $_TABLE;
	global $db;
	$r = sql_match_rows("SELECT organisationseinheit FROM `".$_TABLE["gf"]."` WHERE `organisationseinheit` LIKE \"$a\" LIMIT 1");
	if (!$r) $r = sql_match_rows("SELECT bereich FROM `".$_TABLE["hauptabteilungen"]."` WHERE `bereich` LIKE \"$a\" LIMIT 1");
	if (!$r) $r = sql_match_rows("SELECT abteilung FROM `".$_TABLE["abteilungen"]."` WHERE `abteilung` LIKE \"$a\" LIMIT 1");
	return $r;
}

function ort_exists($o) {
	global $_TABLE;
	global $db;
	return sql_match_rows("SELECT stadtname FROM `".$_TABLE["gebaeude"]."` WHERE `stadtname` LIKE \"".$db->escape($o)."\" LIMIT 1");
}

function gebaeude_exists($g) {
	global $_TABLE;
	global $db;
	return sql_match_rows("SELECT gebaeude FROM `".$_TABLE["gebaeude"]."` WHERE `gebaeude` LIKE \"".$db->escape($g)."\" LIMIT 1");
}

function etage_exists($g, $e) {
	global $_TABLE;
	global $db;
	return sql_match_rows("SELECT etage FROM `".$_TABLE["immobilien"]."` WHERE `gebaeude` LIKE \"".$db->escape($g)."\" AND etage LIKE \"".$db->escape($e)."\" LIMIT 1");
}

function get_existing_antraegeByMaId($maid, $aid=false) {
	global $_TABLE;
	global $db;
	global $_CONF;
	global $error;
	$ASConf = $_CONF["umzugsantrag"];
	$MAConf = $_CONF["umzugsmitarbeiter"];
	
	$sql = "SELECT um.aid, um.mid, a.`antragsdatum`, a.`umzugsstatus`, a.`umzugsstatus_vom` \n";
	$sql.= "FROM `".$MAConf["Table"]."` um LEFT JOIN `".$ASConf["Table"]."` a USING(aid) \n";
	$sql.= "WHERE um.`maid` = \"".$db->escape($maid)."\" \n";
	if ($aid) $sql.= "AND a.`aid` !=\"".$db->escape($aid)."\" \n";
	$sql.= "AND a.`umzugsstatus` IN ('beantragt','geprueft','genehmigt','bestaetigt')";
	$rows = $db->query_rows($sql);
	//if ($db->error()) $error.= $db->error()."<br>\n".$sql."<br>\n";
	return $rows;
}

function raum_exists($g, $e, $r) {
	global $_TABLE;
	global $db;
	return sql_match_rows("SELECT raumnr FROM `".$_TABLE["immobilien"]."` WHERE `gebaeude` LIKE \"".$db->escape($g)."\" AND etage LIKE \"".$db->escape($e)."\" AND raumnr LIKE \"".$db->escape($r)."\" LIMIT 1");
}

function check_minWerktage($datum, $minWerktage) {
	
	if (strpos($datum, ".") && count(explode(".", $datum))==3) { 
		list($d, $m, $y) = explode(".", $datum);
	} elseif (strpos($datum, "-") && count(explode("-", $datum))==3) { 
		list($y, $m, $d) = explode("-", $datum);
	} else return "Invalid Date!";
	
	$heute= time();
	$current_date = date("Y-m-d");
	$check_date = date("Y-m-d", mktime(3, 0, 0, $m, $d, $y));
	
	$count_werktage = 0;
	$i=0;
	while($current_date < $check_date && $minWerktage > $count_werktage) {
		
		if ($current_date > $check_date) break;
		
		switch(date("w", $heute+($i*24*60*60))) {
			case 0:
			case 6:
			break;
			
			default:
			$count_werktage++;
		}
		if ($count_werktage >= $minWerktage) return true;
		$current_date = date("Y-m-d", $heute+($i*24*60*60));
		$i++;
	}
	
	return false;
}

function umzugsantrag_fehler() {
	global $db;
	global $_CONF;
	global $connid;
	global $user;
	
	$error = "";
	$ASConf = $_CONF["umzugsantrag"];
	$MAConf = $_CONF["umzugsmitarbeiter"];
	
	$AID = getRequest("id","");
	$ASPostItem = getRequest("AS",false);
	if (empty($ASPostItem) || !isset($ASPostItem["name"])) {
		$error.= "Es wurden keine Daten zum Antragsteller übermittelt [P2]!<br>\n";
		return $error;
	}
	
	if (!$AID && !empty($ASPostItem["aid"])) $AID = $ASPostItem["aid"];
	
	$AS = new ItemEdit($ASConf, $connid, $user, $AID);
	
	if ($AID) {
		if (!$AS->itemExists) {
			$error.= "Es wurde kein Umzugsantrag mit der übermittelten Antrags-ID gefunden!<br>\n";
			return $error;
		} else {
			$AS->loadDbdata();
		}
	}
	
	$MAPostItems = get_ma_post_items();
	if (!is_array($MAPostItems) || !count($MAPostItems)) {
		$error.= "Es wurden keine Mitarbeiter für den Auftrag ausgewählt.<br>\n";
		if ($AS->itemExists) {
			$error.= "Falls Sie den Auftrag stornieren möchten, klicken Sie den 'Stornieren'-Button.<br>\n";
		}
		return $error;
	}
	
	$MAError = false;
	foreach($MAPostItems as $i => $MAItem) {
		$MA = new ItemEdit($MAConf, $connid, $user, false);
		$MAItem["aid"] = $AID;
		if ($MAItem["maid"]) $rows_dubletten = get_existing_antraegeByMaId($MAItem["maid"], $AID);
		
		$errDubletten = "";
		if (!empty($rows_dubletten) && is_array($rows_dubletten) && count($rows_dubletten)) {
			foreach($rows_dubletten as $dub) {
				$lnkDub = (strpos($user["gruppe"], "admin") === false) ? $dub["antragsdatum"] : "<a href=\"?s=aantrag&id=".urlencode($dub["aid"])."\" target=\"winDub\">".$dub["antragsdatum"]." ".($dub["umzugsstatus"]!="beantragt"?$dub["umzugsstatus"]." am ".format_dbDate($dub["umzugsstatus_vom"],"d.m"):"")." (ID:".$dub["aid"].")</a>";
				$errDubletten.= "Für den Mitarbeiter existiert bereits ein Antrag vom ".$lnkDub."!<br>\n";
				
			}
		}
		$MA->loadInput($MAItem);
		if (!$MA->checkInput() || $errDubletten) {
			$error.= "Fehlerhafte Angaben beim ".($i+1).". Mitarbeiter ".$MAItem["name"]."!<br>\n";
			$error.= $errDubletten.$MA->Error;
			//if (count($MA->arrErrFlds)) $error.= print_r($MA->arrErrFlds, 1);
			$MAError = true;
		}
	}
	if ($MAError) {
		return $error;
	}
	
	$AS->loadInput($ASPostItem);
	$AS->Error = "";
	if (!$AS->checkInput()) {
		$error.= "Überprüfen Sie die Basis-Angaben zum Antragssteller!<br>\n";
		$error.= $AS->Error;
		return $error;
	}
	
	if (getRequest("umzugsart") != "Datenpflege") {
		if ($_CONF['minWerktageVorlauf'] > 0 && ($AID || $AS->arrDbdata["umzugsstatus"] == "temp")) {
			if (!check_minWerktage($ASPostItem["terminwunsch"], $_CONF['minWerktageVorlauf'] )) {
				$error.= "Umzugstermin ist zu kurzfristig. Planen Sie eine Vorlaufzeit von mind. 8 Arbeitstagen ein!<br>\n";
			}
		}
	}
	
	$error.= umzugsantrag_get_zuordnungs_fehler($ASPostItem, $MAPostItems);
	return $error;
}

function umzugsantrag_get_zuordnungs_fehler($ASItem, $MAItems) {	
	$as_error = "";
	$ma_error = "";
	
	if (empty($ASItem["ort"]) || !ort_exists($ASItem["ort"]))
		$as_error.= "Ung�ltige Ortsauswahl ".$ASItem["ort"]."!<br>\n";
	
	if (empty($ASItem["gebaeude"]) || !gebaeude_exists($ASItem["gebaeude"]))
		$as_error.= "Ung�ltige Geb�udeauswahl ".$ASItem["gebaeude"]."!<br>\n";
	
	if ($as_error) $as_error = "<strong>Fehlerhafte Angaben beim Antragsteller:</strong><br>\n".$as_error."<br>\n";
	
	for($i = 0; $i < count($MAItems); $i++) {
		$MA = $MAItems[$i];
		$n = $i+1;
		
		if (empty($MA["abteilung"]) || !abteilung_exists($MA["abteilung"]))
			$ma_error.= "[MA $n] Ung�ltige Abteilungsauswahl: von ".$ASItem["abteilung"]."!<br>\n";
		
		if (empty($MA["gebaeude"]) || !gebaeude_exists($MA["gebaeude"]))
			$ma_error.= "[MA $n] Ung�ltige Geb�udeauswahl: von ".$ASItem["gebaeude"]."!<br>\n";
		elseif (empty($MA["gebaeude"]) || !etage_exists($MA["gebaeude"], $MA["etage"]))
			$ma_error.= "[MA $n] Ung�ltige Etagenauswahl: von ".$MA["etage"]." in ".$MA["gebaeude"]."!<br>\n";
		elseif (empty($MA["raumnr"]) || !etage_exists($MA["gebaeude"], $MA["etage"], $MA["raumnr"]))
			$ma_error.= "[MA $n] Ung�ltige Raumauswahl: von ".$MA["raumnr"]." in ".$MA["gebaeude"]." ".$MA["etage"]."!<br>\n";
		
		
		if (empty($MA["zabteilung"]) || !abteilung_exists($MA["zabteilung"]))
			$ma_error.= "[MA $n] Ung�ltige Abteilungsauswahl: nach ".$ASItem["zabteilung"]."!<br>\n";
		
		if (empty($MA["zgebaeude"]) || !gebaeude_exists($MA["zgebaeude"]))
			$ma_error.= "[MA $n] Ung�ltige Geb�udeangabe: nach ".$ASItem["zgebaeude"]."!<br>\n";
		elseif (empty($MA["zgebaeude"]) || !etage_exists($MA["zgebaeude"], $MA["zetage"]))
			$ma_error.= "[MA $n] Ung�ltige Etagenauswahl: nach ".$MA["zetage"]." in ".$MA["zgebaeude"]."!<br>\n";
		elseif (empty($MA["zraumnr"]) || !etage_exists($MA["zgebaeude"], $MA["zetage"], $MA["zraumnr"]))
			$ma_error.= "[MA $n] Ung�ltige Raumauswahl: nach ".$MA["zraumnr"]." in ".$MA["zgebaeude"]." ".$MA["zetage"]."!<br>\n";
		
	}
	if ($ma_error) $ma_error = "<strong>Fehlerhafte Angaben in der Mitarbeiterliste:</strong><br>\n".$ma_error."<br>\n";
	
	return $as_error.$ma_error;
}

?>
