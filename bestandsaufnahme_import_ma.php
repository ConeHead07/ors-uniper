<?php 
set_time_limit(240);
require("header.php"); 
require("sites/umzugsantrag_stdlib.php");
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Untitled</title>
	<link rel="STYLESHEET" type="text/css" href="css/tablelisting.css?%assetsRefreshId%">
	<script>
	function checkCheckbox(inputName, check, filterValue) {
		var aElements = document.getElementsByName(inputName);
		for (var i = 0; i < aElements.length; i++) {
			if (filterValue && filterValue != aElements[i].value) continue;
			aElements[i].checked = (check != -1 ? check : !aElements[i].checked);
		}
	}
	</script>
	<style>
	.jsLink {
		color:#00f;
		cursor:pointer;
	}
	.jsLink:hover,
	.jsLink:active {
		text-decoration:underline;
	}
	</style>
</head>
<body>
<h3>Import Mitarbeiterdaten</h3>
<pre>
<?php 

$dstDB_Request = "";
$dstDB = getRequest("dstDB", $MConf["DB_Name"]);
$dstDB = $MConf["DB_Name"];
$onlySysDB = true;
$runimport = getRequest("runimport", "");
$runImport = getRequest("runImport", "");
$clearTbl = getRequest("clearTbl", false);
$GebFilter = (array)getRequest("GebFilter", "");
$FieldLineIdentifier = "##FIELDS:";

if ($dstDB) {
	$sql = "SHOW DATABASES LIKE \"".$db->escape($dstDB)."\"";
	$row = $db->query_singlerow($sql);
	if (empty($row)) {
		$dstDB_Request = $dstDB;
		$dstDB = "";
	}
}

if (!$dstDB) {
	echo "Es wurde ".(!$dstDB_Request?"keine Datenbank":"eine ung�ltige Datenbank $dstDB_Request")." angegeben!<br>\n";
}
$db->query("USE `$dstDB`");

$importDir = "import_csv/";
$importFile = getRequest("importFile","");
$sep = ";";
$quot = '"';
$fileAppend = false;
$aImportFiles = array();
$aImportFiles["mitarbeiter"]["file"] = $importDir."Datenbank MA NW_W_ZV 20100722.csv";
$aImportFiles["mitarbeiter"]["file"] = $importDir.$importFile;

//$dstDB = $MConf["DB_Name"];

$sql = "SHOW DATABASES LIKE \"mt\_move%\"";
$aDBs = $db->query_rows($sql);
//print_r($aDBs);

$selectDB = "<select onchange=\"document.getElementById('chgDB').submit()\" name=\"dstDB\">\n";
$selectDB.= "<option value=\"\">Import-DB Auswahl</option>\n";
foreach($aDBs as $v) { $k = key($v); if (!empty($v[$k])) 
	$selectDB.= "<option ".($v[$k] == $dstDB ? "selected=\"true\"":"")." value=\"".fb_htmlEntities($v[$k])."\">".$v[$k]."</option>\n"; }
$selectDB.= "</select>\n";

$i =0;
$wz=0;
if (empty($onlySysDB)) echo "<form id=\"chgDB\" style=\"display:inline;margin:0;\">Quell-DB: ".$selectDB."<input type=submit value=\"Zu DB wechseln\"></form>\n";
else echo "Database: $dstDB\n";
echo "<form id=\"upDB\" style=\"display:inline;margin:0;\" enctype=\"multipart/form-data\" xenctype=\"application/x-www-form-urlencoded\" method=\"post\">Import-File: "."<input type=\"file\" name=\"upload\"><input type=\"hidden\" name=\"dstDB\" value=\"".urlencode($dstDB)."\"><input type=submit value=\"CSV-Datei hochladen\"></form>\n\n";

if (!empty($_FILES["upload"])) {
	if (!$_FILES["upload"]["error"]) {
		if (strtolower(substr($_FILES["upload"]["name"],-4))==".csv") {
			if (move_uploaded_file($_FILES["upload"]["tmp_name"], $importDir.$_FILES["upload"]["name"])) {
				echo "Datei ".$_FILES["upload"]["name"]." wurde gespeichert!\n";
			} else {
				echo "Fehler beim Speichern der Datei!\n";
			}
		} else echo "Fehler: Datei endet nicht auf .csv!\n";
	} else echo "Fehler beim Dateiupload!\n";
}

echo "<strong>Hinweis für den Import:</strong>
Import-Daten werden hinzugef&uuml;gt. Bestehende Daten werden nicht gelöscht!
Nicht vorhandene Abteilungen und R&auml;ume werden automatisch angelegt.
Nach dem Import sehen Sie eine Liste neu angelegter R&auml;me und Abteilungen.\n\n";
if ($dstDB) {
	$dp = opendir($importDir);
	while($file = readdir($dp)) 
		if (strpos("..",$file)===false)
			echo "<li>import <a href=\"?dstDB=$dstDB&importFile=".urlencode($file)."&runImport=1\">Daten hinzufügen</a> <a href=\"?dstDB=$dstDB&importFile=".urlencode($file)."&runImport=1&clearTbl=1\">Daten ersetzen</a> $file</li>";
}

function csv_getFields($line, $sep = ";", $quot = '"') {
	$qLen = strlen($quot);
	if ($quot) {
		if (substr(trim($line), 0, 10+$qLen ) == "##FIELDS: $quot") {
			return explode($quot.$sep.$quot, substr(trim($line),10+$qLen,-$qLen));
		} elseif (substr(trim($line), 0, $qLen) == $quot) {
			return explode($quot.$sep.$quot, substr(trim($line),$qLen,-$qLen));
		}
	}
	return false;
}

function csv_getValues($line, $sep = ";", $quot = '"') {
	$line = trim($line);
	$lastChar = substr($line, -1, 1);
	$aValues = array();
	//return explode($sep, str_replace($quot, "", $line));
	if (!$quot) return explode($sep, $line);
	else {
		//echo "#".__LINE__." CSV-Line:".$line."<br>\n";
		for ($i = 0; $i < strlen($line); $i++) {
			//echo "#".__LINE__." i:$i; line[$i]:".$line[$i]." => ".substr($line, $i)."<br>\n";
			
			if ($line[$i] == $quot) {
				$p = strpos($line, $quot.$sep, $i+1);
				if (is_int($p)) {
					$aValues[] = substr($line, $i+1, $p-($i+1));
					$i = $p+1;
					continue;
				}
				
				$p = strpos($line, $quot, $i+1);
				if ($p==strlen($line)-1) {
					$aValues[] = substr($line, $i+1, $p-$i-1);
					return $aValues;
				}
			}
			
			if ($line[$i] == $sep) {
				$aValues[] = "";
				continue;
			}
			
			$p = strpos($line, $sep, $i+1);
			if (is_int($p)) {
				$aValues[] = substr($line, $i, $p-$i);
				$i = $p+1;
				continue;
			}
			$aValues[] = substr($line, $i);
			return $aValues;
		}
		if ($lastChar == $sep) $aValues[] = "";
	}
	return $aValues;
}

function check_create_raum_byMaData($MA) {
	global $db;
	global $_TABLE;
	global $aNeueRaeume;
	
	$raumid = get_raumid_byGER($MA["gebaeude"], $MA["etage"], $MA["raumnr"]);
	if ($raumid) return $raumid;
	
	$sql = "INSERT INTO `".$_TABLE["immobilien"]."` (ort,gebaeude,etage,raumnr,raum_flaeche,raum_kategorie,raum_typ,aufgenommen_am)\n";
	$sql.= "VALUES(\"".$db->escape($MA["ort"])."\", \"".$db->escape($MA["gebaeude"])."\", \"".$db->escape($MA["etage"])."\", \"".$db->escape($MA["raumnr"])."\", 0, \"AF\", \"BUE\",NOW())";
	$db->query($sql);
	//echo $db->error()."<br>\n".$sql."<br>\n";
	if (!$db->error() && $db->insert_id()) {
		$raumid = $db->insert_id();
		$aNeueRaeume[] = "RaumID:$raumid Values(\"".$MA["ort"]."\", \"".$MA["gebaeude"]."\", \"".$MA["etage"]."\", \"".$MA["raumnr"]."\", 0, \"AF\", \"BUE\")";
		return $raumid;
	}
	return false;
}

function check_create_abteilung_byMaData($MA) {
	global $db;
	global $_TABLE;
	global $aNeueAbteilungen;
	
	$a = $MA["abteilung"];
	$b = $MA["bereich"];
	$g = $MA["gf"];
	
	if (!$g && !$b && !$a) return false;
	
	if (!$g) die("CanNot Find GF! Unvollständige Angaben -> GF:".($g?$g:"?")."; B:".($b?$b:"?")."; A:".($a?$a:"?")."<br>\n");
	if ($a && !$b) die("CanNot Create Abteilung! Unvollständige Angaben -> GF:".($g?$g:"?")."; B:".($b?$b:"?")."; A:".($a?$a:"?")."<br>\n");
	if ($b && !$g) die("CanNot Create Bereich! Unvollständige Angaben -> GF:".($g?$g:"?")."; B:".($b?$b:"?")."<br>\n");
	
	if ($g && !$db->query_count("`".$_TABLE["gf"]."` WHERE `organisationseinheit` LIKE \"$g\" LIMIT 1")) {
		$db->query("INSERT `".$_TABLE["gf"]."` SET organisationseinheit=\"".$db->escape($g)."\", name=\"\", personalbelegschaft=0, verrechenbare_flaeche=0");
		$aNeueAbteilungen["GF"][] = $g;
		if ($db->error()) { die($db->error()."\n".$sql); return false; }
	}
	
	if ($b && !$db->query_count("`".$_TABLE["hauptabteilungen"]."` WHERE `bereich` LIKE \"$b\" AND `organisationseinheit` LIKE \"$g\" LIMIT 1")) {
		$db->query("INSERT `".$_TABLE["hauptabteilungen"]."` SET bereich=\"".$db->escape($b)."\", bereichsname=\"\", bereichsleiter=\"\", organisationseinheit=\"".$db->escape($g)."\", aufgenommen_am=NOW()");
		$aNeueAbteilungen["Bereich"][] = "$b -> GF $g";
		if ($db->error()) { die($db->error()."\n".$sql); return false; }
	}
	
	if ($a && !$db->query_count("`".$_TABLE["abteilungen"]."` WHERE `abteilung` LIKE \"$a\" LIMIT 1")) {
		$db->query("INSERT `".$_TABLE["abteilungen"]."` SET bereich=\"".$db->escape($b)."\", abteilung=\"".$db->escape($a)."\", abteilungsname=\"\", abteilungsleiter=\"\", autocad=\"\", aufgenommen_am=NOW()");
		$aNeueAbteilungen["Abteilung"][] = "$a -> Bereich $b";
		if ($db->error()) { die($db->error()."\n".$sql); return false; }
	}
	return true;
}

$MaLinkLog = array("NewLinked"=>"", "MaNotFound"=>"", "MultipleMas"=>"");
function check_umzugsmitarbeiter_links() {
	global $db;
	global $_TABLE;
	global $MaLinkLog;
	
	$sql = "SELECT ma.aid, ma.mid, ma.maid, ma.name, ma.vorname, a.umzugsstatus FROM `".$_TABLE["umzugsmitarbeiter"]."` ma \n";
	$sql.= "LEFT JOIN `".$_TABLE["umzugsantrag"]."` a USING(aid) \n";
	$rows = $db->query_rows($sql);
	
	foreach($rows as $ma) {
		$sql = "SELECT id FROM `".$_TABLE["mitarbeiter"]."` \n";
		$sql.= "WHERE name LIKE \"".$db->escape($ma["name"])."\" \n";
		$sql.= " AND vorname LIKE \"".$db->escape($ma["vorname"])."\"";
		$ma_ids = $db->query_rows($sql);
		
		//echo print_r($ma,1)."<br>\n";
		//echo print_r($ma_ids, 1)."<hr>\n";
		if ($ma["maid"]) {
			
			switch (count($ma_ids)) {
				case 1:
				if ($ma_ids[0]["id"] != $ma["maid"]) {
					$sql = "UPDATE `".$_TABLE["umzugsmitarbeiter"]."` \n";
					$sql.= "SET maid = \"".$db->escape($ma_ids[0]["id"])."\"\n";
					$sql.= "WHERE mid = \"".$db->escape($ma["mid"])."\"";
					$db->query($sql);
					if ($db->error()) die($db->error()."\n".$sql);
					
					$MaLinkLog["NewLinked"].= "Link New MA-ID: ".$sql."\n";
				} else {
					$MaLinkLog["NewLinked"].= "No Update required: ma_ids[0][id] != ma[maid] : ".$ma_ids[0]["id"]." == ".$ma["maid"]."\n";
				}
				break;
				
				case 0:
				$MaLinkLog["MaNotFound"].= "<span style=\"color:#f00;\">MA Not Found ".$ma["name"].", ".$ma["vorname"]." (aid:".$ma["aid"].", maid:".$ma["maid"].", mid:".$ma["mid"].")</span>\n";
				break;
				
				default:
				$MaLinkLog["MultipleMas"].= "<span style=\"color:#f00;\">Es wurden mehrere (".count($ma_ids).") MAs f�r die Suche  ".$ma["name"].", ".$ma["vorname"]." (aid:".$ma["aid"].", maid:".$ma["maid"].", mid:".$ma["mid"].")</span>\n";
			}
		}
	}
}

function check_umzugsmitarbeiter($name, $vorname) {
	global $db;
	global $_TABLE;
	global $UM_DATA;
	
	$chckStr = strtoupper($name.", ".$vorname);
	$sql = "SELECT aid, mid, maid, name, vorname FROM `".$_TABLE["umzugsmitarbeiter"]."` \n";
	$sql.= "WHERE `name` LIKE \"".$db->escape($name)."\" AND `vorname` LIKE \"".$db->escape($vorname)."\"";
	$UM_DATA[$chckStr][] = $db->query_rows($sql);
	return $UM_DATA[$chckStr];
}

$UM_DATA = array();
$MA_CACHE = array();
$MA_DUBS = array();
function check_dubletten($name, $vorname) {
	global $MA_CACHE;
	global $MA_DUBS;
	
	$chckStr = strtoupper($name.", ".$vorname);
	if (!isset($MA_CACHE[$chckStr])) {
		$MA_CACHE[$chckStr] = 1;
		$re = false;
	} else {
		if (!isset($MA_DUBS[$chckStr])) $MA_DUBS[$chckStr] = $name.", ".$vorname;
		else $MA_DUBS[$chckStr].= "; ".$name.", ".$vorname;
		$re = true;
	}
	return $re;
}

$row = $db->query_singlerow("SHOW DATABASES LIKE \"$dstDB\"");
if (empty($row)) {
	die("Ziel-DB '$dstDB' existiert nicht oder es fehlen Zugriffsrechte!\n".$db->error()."\nSHOW DATABASES LIKE \"x$dstDB\"\n".print_r($row,1));
}

// Start-Import
if ($runImport) {
	// Wichtige L�schreihenfolge: Erst MA in Ab�ngigkeit der R�ume (Sub-Query), dann die R�ume
	// Leeren der Mitarbeiterdaten
	if ($clearTbl) {
		$sql = "DELETE FROM `$dstDB`.`mm_stamm_mitarbeiter` \n";
		if (!empty($aGebaeudeFilter)) $sql.= "WHERE immobilien_raum_id IN (SELECT id FROM `$dstDB`.mm_stamm_immobilienwhere gebaeude IN ('".implode("','", $aGebaeudeFilter)."') )";
		$db->query($sql);
		//if ($db->error()) 
		echo "#".__LINE__." DB-ERR: ".$db->error()."\n".$sql."\n";
	}
	$aFields = false;
	// Import - Mitarbeiter
	if (@isset($aImportFiles["mitarbeiter"]) && isset($aImportFiles["mitarbeiter"]["file"])) {
		$i = 0;
		$num_import = 0;
		$fp = fopen($aImportFiles["mitarbeiter"]["file"], "r");
		$aImportFields = explode(",", "abteilungen_id,immobilien_raum_id,arbeitsplatznr,name,vorname,extern,extern_firma,ersthelfer,raeumungsbeauftragter,anmerkung,anrede,mitarbeiter,gebaeude,etage,raumnr,gf,bereich,abteilung");
		if ($fp) {
			while($line = fgets($fp, 2000)) {
				//echo "#".__LINE__." ".basename(__FILE__)." substr(\$line,0,".strlen($FieldLineIdentifier).")".substr($line,0,strlen($FieldLineIdentifier))."<br>\n";
				if (empty($aFields)|| substr($line,0,strlen($FieldLineIdentifier)) == $FieldLineIdentifier) {
					echo "#".__LINE__." ".basename(__FILE__)."<br>\n";
					$aChckFields = csv_getFields($line);
					if (!empty($aChckFields)) $aFields = $aChckFields;
					continue;
				}
				//echo "#".__LINE__." ".basename(__FILE__)." line:".$line."<br>\n";
				if (empty($aFields)) die("Fehlende Felddefinitionen; FieldLineIdentifier: `$FieldLineIdentifier` NOT FOUND!<br>\n");
				//die("#".__LINE__." ".print_r($aFields,1));
				//$aValues = explode($quot.$sep.$quot, substr(trim($line),1,-1));
				$aValues = csv_getValues(trim($line), $sep, $quot);
				//echo "#".__LINE__." $line<br>\n".print_r($aValues,1)."<br>\n";
				//exit;
				
				if (count($aValues) == count($aFields)) {
					$i++;
					
					$e = array_combine($aFields, $aValues);
					$IS_Dub = check_dubletten($e["name"], $e["vorname"]);
					
					if ($e["name"] != "SPARE" && $e["name"] != "FUNCTION" && $e["name"] != "FLEX") {
						$UM_Data = check_umzugsmitarbeiter($e["name"], $e["vorname"]);
						//if ($IS_Dub && $UM_Data) echo "#".__LINE__." ".$e["name"].", ".$e["vorname"].": ".strtr(print_r($UM_Data,1),array("\r\n"=>" ","\r"=>" ", "\n"=>" "))."<br>\n";
					}
					
					$raumid = check_create_raum_byMaData($e);
					check_create_abteilung_byMaData($e);
					
					if ($raumid) {
						$e["immobilien_raum_id"] = $raumid;
					} else {
						die("CanNot Link to Raum: Gebaeude ".$MA["gebaeude"]."; Etage ".$MA["etage"]."; Raum ".$MA["raumnr"]."!");
					}
					if ($e["abteilung"]) {
						$sql = "SELECT id FROM `$dstDB`.`mm_stamm_abteilungen` WHERE abteilung = \"".$e["abteilung"]."\"";
						$row = $db->query_singlerow($sql);
						$e["abteilungen_id"] = $row["id"];
					} else $e["abteilungen_id"] = 0;
					
					$eInsert = array();
					foreach($aImportFields as $fld) $eInsert[$fld] = (!empty($e[$fld])) ? $e[$fld] : "";
					
					$sql = "INSERT INTO `$dstDB`.mm_stamm_mitarbeiter "; 
					//$sql.= " (".implode(",", $aImportFields).") VALUES (\"".implode("\",\"", $eInsert)."\")";
					$sql.= " SET 
					abteilungen_id = ".($eInsert["abteilungen_id"]?$eInsert["abteilungen_id"]:0).",
					immobilien_raum_id = ".$eInsert["immobilien_raum_id"].",
					arbeitsplatznr = ".($eInsert["arbeitsplatznr"]?$eInsert["arbeitsplatznr"]:"NULL").",
					name = \"".$eInsert["name"]."\",
					vorname = \"".$eInsert["vorname"]."\",
					extern = \"".$eInsert["extern"]."\",
					extern_firma = \"".$eInsert["extern_firma"]."\",
					ersthelfer = \"".($eInsert["ersthelfer"]?"".$eInsert["ersthelfer"]."":"Nein")."\",
					raeumungsbeauftragter = \"".($eInsert["raeumungsbeauftragter"]?"".$eInsert["raeumungsbeauftragter"]."":"Nein")."\",
					anmerkung = \"".$eInsert["anmerkung"]."\",
					anrede = \"".$eInsert["anrede"]."\",
					mitarbeiter = \"".$eInsert["mitarbeiter"]."\",
					gebaeude = \"".$eInsert["gebaeude"]."\",
					etage = \"".$eInsert["etage"]."\",
					raumnr = \"".$eInsert["raumnr"]."\",
					gf = \"".$eInsert["gf"]."\",
					bereich = \"".$eInsert["bereich"]."\",
					abteilung = \"".$eInsert["abteilung"]."\",
					aufgenommen_am = NOW()";
					
					$db->query($sql);
					if ($db->error()) die("#".__LINE__." DB-ERR: ".$db->error()."\n".$sql."\n\n");
					else $num_import++;
					//if ($i++ < 10) { echo $sql."\n"; print_r($e); }
				} else {
					die("Anzahl Spalten ".count($aValues)." stimmt nicht mit Felddefinition in erster Zeile (".count($aFields)." überein!\nSpalten:\n$line\n".print_r($aValues,1)."\n Importvorgabe:".implode(";",$aFields)."<br>\n");
				}
			}
			fclose($fp);
		}
		echo "Es wurden $num_import Mitarbeiter importiert!\n";
		
		echo "<strong>Neue Räume:</strong>\n".print_r($aNeueRaeume,1)."\n\n";
		echo "<strong>Neue Abteilungen:</strong>\n".print_r($aNeueAbteilungen,1)."\n\n";
	}
}
check_umzugsmitarbeiter_links();
print_r($MaLinkLog);

$DubsInUM = array_intersect_key($UM_DATA, $MA_DUBS);
foreach($DubsInUM as $k => $v) $DubsInUM[$k] = true;
echo "array_intersect_key: ".print_r($DubsInUM,1)."<br>\n";

echo "MA_DUBS:\n";
echo print_r($MA_DUBS,1);
?>
</pre>
</body>
</html>
