<?php 
//require("../header.php");
if (basename(__FILE__)==basename($_SERVER["PHP_SELF"])) require_once("../header.php");

require_once($InclBaseDir."umzugsantrag.inc.php");
require_once($InclBaseDir."umzugsmitarbeiter.inc.php");

/*
Es sind Fehler aufgetreten!
Es konnten nicht alle Umzugsdaten übernommen werden!
1054: Unknown column 'id' in 'where clause'
UPDATE `mm_umzuege_arbeitsplaetze` SET maid="9565" WHERE id=""
Mail wurde gesendet!
Umzugsauftrag wurde abgeschlossen und in die Bestandsdaten übernommen!
OK

*/

function get_path_byAbt($a) {
	global $_TABLE;
	global $db;
	$FDBG = false;
	$aPath = array(
		"id"=>0,
		"abteilung"=>"",
		"bereich"=>"",
		"gf"=>""
	);
	
	$sql = "SELECT id, bereich  FROM `".$_TABLE["abteilungen"]."` WHERE `abteilung` LIKE \"$a\" LIMIT 1";
	$row = $db->query_singlerow($sql);
	if ($FDBG) echo "#".__LINE__." ".$db->error()."; ".$sql."<br>\n";
	if (!empty($row["id"])) {
		$aPath["abteilung"] = $a;
		$aPath["bereich"] = $row["bereich"];
		$aPath["id"] = $row["id"];
	}
	if ($FDBG) echo "#".__LINE__." a $a: ".print_r($aPath,1)."<br>\n";
	
	$checkB = ($aPath["bereich"] ? $aPath["bereich"] : $a);
	$sql = "SELECT organisationseinheit FROM `".$_TABLE["hauptabteilungen"]."` WHERE `bereich` LIKE \"$checkB\" LIMIT 1";
	$row = $db->query_singlerow($sql);
	//echo $db->error()."<br>\n".$sql."<br>\n";
	if (!empty($row["organisationseinheit"])) {
		if (empty($aPath["bereich"])) $aPath["bereich"] = $a;
		$aPath["gf"] = $row["organisationseinheit"];
	}
	if ($FDBG) echo "#".__LINE__." a $a: ".print_r($aPath,1)."<br>\n";
	
	if (empty($aPath["gf"])) {
		$sql = "SELECT organisationseinheit FROM `".$_TABLE["hauptabteilungen"]."` WHERE `organisationseinheit` LIKE \"".$a."\" LIMIT 1";
		$row = $db->query_singlerow($sql);
		//echo $db->error()."<br>\n".$sql."<br>\n";
		if (!empty($row["organisationseinheit"])) $aPath["gf"] = $row["organisationseinheit"];
	}
	return $aPath;
}

function ma_row_exists($maid) {
	global $_TABLE;
	global $db;
	
	$sql = "SELECT id FROM `".$_TABLE["mitarbeiter"]."` WHERE id = \"".$db->escape($maid)."\" LIMIT 1";
	$row = $db->query_singlerow($sql);
	return (!empty($row["id"]));
}

function umzugsantrag_datenuebernehmen($AID) {
	global $db;
	global $error;
	global $msg;
	global $_CONF;
	global $connid;
	global $user;
	global $_TABLE;
	
	if (!$AID) {
		$error.= "Fehlende AuftragsID für Datenübernahme!<br>\n";
	}
	$userIsAdmin = (strpos($user["gruppe"], "kunde_report")!==false || strpos($user["gruppe"], "admin")!==false);
	
	$ASConf = $_CONF["umzugsantrag"];
	$MAConf = $_CONF["umzugsmitarbeiter"];
	$save_errors = "";
	
	$AS = new ItemEdit($ASConf, $connid, $user, $AID);
	
	if (!$AS->itemExists) {
		$error.= "Es wurde kein Umzugsantrag mit der übermittelten Antrags-ID gefunden!<br>\n";
		return false;
	}
	
	if ($AID) {
		$AS->loadDbdata();
		
		$sql = "SELECT `mid` FROM `".$MAConf["Table"]."` WHERE `aid` = \"".$db->escape($AS->id)."\"";
		$MAIDs = $db->query_rows($sql);
		for($i = 0; $i < count($MAIDs); $i++) {
			$MAID = $MAIDs[$i]["mid"];
			$MA = new ItemEdit($MAConf, $connid, $user, $MAID);
			$MA->dbdataToInput();
			$MAItems[$i] = $MA->arrInput;
			
			$raumdaten_alt = get_raumdaten_byGER($MAItems[$i]["gebaeude"], $MAItems[$i]["etage"], $MAItems[$i]["raumnr"]);
			$raumdaten_neu = get_raumdaten_byGER($MAItems[$i]["zgebaeude"], $MAItems[$i]["zetage"], $MAItems[$i]["zraumnr"]);
			$aAbtInfo = get_path_byAbt($MAItems[$i]["zabteilung"]);
			
			$SAVE_MODE = ($MAItems[$i]["maid"] && ma_row_exists($MAItems[$i]["maid"])) ? "UPDATE" : "INSERT";
			// Mitarbeiterdaten aktualisieren
			// abteilungen_id 	immobilien_raum_id
			$CanBeNull = true;
			$CanNotBeNull = false;
			$sql = "$SAVE_MODE `".$_TABLE["mitarbeiter"]."` SET \n";
			$sql.= " `immobilien_raum_id` = \"".$db->escape($raumdaten_neu["id"])."\",\n";
			$sql.= " `abteilungen_id` = \"".$db->escape($aAbtInfo["id"])."\",\n";
			$sql.= " `name` = \"".$db->escape($MAItems[$i]["name"])."\",\n";
			$sql.= " `vorname` = \"".$db->escape($MAItems[$i]["vorname"])."\",\n";
			$sql.= " `extern` = \"".($MAItems[$i]["extern_firma"]?"Extern":"Staff")."\",\n";
			$sql.= " `extern_firma` = \"".$db->escape($MAItems[$i]["extern_firma"])."\",\n";
			$sql.= " `gf` = \"".$db->escape($aAbtInfo["gf"])."\",\n";
			$sql.= " `bereich` = \"".$db->escape($aAbtInfo["bereich"])."\",\n";
			$sql.= " `abteilung` = \"".$db->escape($aAbtInfo["abteilung"])."\",\n";
			$sql.= " `gebaeude` = \"".$db->escape($MAItems[$i]["zgebaeude"])."\",\n";
			$sql.= " `etage` = \"".$db->escape($MAItems[$i]["zetage"])."\",\n";
			$sql.= " `raumnr` = \"".$db->escape($MAItems[$i]["zraumnr"])."\",\n";
			$sql.= " `arbeitsplatznr` = ".($MAItems[$i]["zarbeitsplatznr"] ? "\"".$db->escape($MAItems[$i]["zarbeitsplatznr"])."\"":"NULL").",\n";
			$sql.= $db->setFieldValue("aufgenommen_am", date("Y-m-d H:i:s"), "string", $CanBeNull)."\n";
			if ($SAVE_MODE == "UPDATE") $sql.= "WHERE id = \"".$db->escape($MAItems[$i]["maid"])."\"";
			$db->query($sql);
			if ($db->error()) {
				$save_errors.= $db->error()."<br>\n".$sql;
			}
			
			if ($SAVE_MODE == "INSERT") {
				$insert_id = $db->insert_id();
				$sql = "UPDATE `".$MAConf["Table"]."` SET maid=\"".$db->escape($insert_id)."\" WHERE mid=\"".$db->escape($MAItems[$i]["mid"])."\"";
				$db->query($sql);
				if ($db->error()) {
					$save_errors.= $db->error()."<br>\n".$sql;
				} else {
					//$save_errors.= "NO-DB-ERROR:<br>\n".$sql;
				}
			}
			
			if ($raumdaten_neu["raum_typ"] == "GBUE") {
				$rows = get_arbeitsplatz_belegung($raumdaten_neu["id"], $MAItems[$i]["zapnr"]);
				if (!empty($rows) && count($rows)) {
					foreach($rows as $row) {
						if ($row["extern"] == "Spare") {
							$db->query("DELETE FROM `".$_TABLE["mitarbeiter"]."` WHERE id = \"".$db->escape($row["id"])."\"");
							break;
						}
					}
				}
			}
			
			if ($raumdaten_alt["raum_typ"] == "GBUE" && $MAItems[$i]["arbeitsplatznr"]) {
				$rows = get_arbeitsplatz_belegung($raumdaten_alt["id"], $MAItems[$i]["apnr"]);
				if (empty($rows) || !count($rows)) {
					create_spare($raumdaten_alt["raum_typ"], $MAItems[$i]["arbeitsplatznr"]);
					if ($db->error()) {
						$save_errors.= $db->error();
					}
				}
			}
		}
	}
	
	if ($save_errors) {
		$error.= "Es konnten nicht alle Umzugsdaten übernommen werden!<br>\n".$save_errors;
		return false;
	}
	return $AID;
}

if (basename(__FILE__) == basename($_SERVER["PHP_SELF"])) {
	$a = "PPF";
	$p = get_path_byAbt($a);
	echo "#".__LINE__." Pfadrückgabe für Abt. $a: ".print_r($p,1)."<hr>\n";
	
	$a = "PP";
	$p = get_path_byAbt($a);
	echo "#".__LINE__." Pfadrückgabe für Abt. $a: ".print_r($p,1)."<hr>\n";
	
	$a = "P";
	$p = get_path_byAbt($a);
	echo "#".__LINE__." Pfadrückgabe für Abt. $a: ".print_r($p,1)."<hr>\n";
}
