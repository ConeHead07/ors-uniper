<?php 
require("header.php"); ?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Untitled</title>
	<link rel="STYLESHEET" type="text/css" href="css/tablelisting.css">
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
<pre>
<?php /*
include("include/conf.php");
include("include/conn.php");
include("class/dbconn.class.php");*/

$aGebaeudeFilter = array('NL_NO_AT', 'ZV_SEE_5');

$srcDB_Request = "";
$srcDB = getRequest("srcDB", "");
$runExport = getRequest("runExport", "");
$runImport = getRequest("runImport", "");
$GebFilter = (array)getRequest("GebFilter", "");

if ($srcDB) {
	$sql = "SHOW DATABASES LIKE \"".$db->escape($srcDB)."\"";
	$row = $db->query_singlerow($sql);
	if (empty($row)) {
		$srcDB_Request = $srcDB;
		$srcDB = "";
	}
}

if (!$srcDB) {
	echo "Es wurde ".(!$srcDB_Request?"keine Datenbank":"eine ungültige Datenbank $srcDB_Request")." angegeben!<br>\n";
}

$sep = ";";
$quot = '"';
$fileAppend = false;
$aExportFiles = array();
//$srcDB = $MConf["DB_Name"];
$dstDB = "mt_move_vodafone2";
$exportDir = "export_csv/";

if ($srcDB) {
	$sql = "SELECT * FROM `$srcDB`.`mm_stamm_gebaeude` order by stadtname, gebaeude";
	$aGebaeude = $db->query_rows($sql);
	$aGebaeudeStat = array();
	$aGebKuerzel = array();
	for($i = 0; $i < count($aGebaeude); $i++) {
		$aGebKuerzel[$i] = $aGebaeude[$i]["gebaeude"];
		$g = $aGebaeude[$i]["gebaeude"];
		$aGebaeudeStat[$g] = array("Stadt"=>$aGebaeude[$i]["stadt"], "Etagen"=>"", "Räume"=>"", "Alle Mitarbeiter"=>"", "Neue Mitarbeiter"=>"", "Gelöschte Räume"=>"", "Gelöschte Mitarbeiter"=>"", "Adresse"=>$aGebaeude[$i]["adresse"]);
	}
	
	$sql = "SELECT gebaeude, count(distinct(etage)) etagen, count(raumnr) raeume FROM `$srcDB`.`mm_stamm_immobilien` WHERE gebaeude IN(\"".implode("\",\"", $aGebKuerzel)."\") group by gebaeude order by gebaeude";
	$rows = $db->query_rows($sql);
	if ($db->error()) echo $db->error()."\n".$sql."\n";
	for($i = 0; $i < count($rows); $i++) {
		if (empty($rows[$i]["gebaeude"])) continue;
		$e = $rows[$i];
		$g = $e["gebaeude"];
		$aGebaeudeStat[$g]["Etagen"] = $e["etagen"];
		$aGebaeudeStat[$g]["Räume"] = $e["raeume"];
	}
	
	$sql = "SELECT gebaeude, count(raumnr) raeume_geloescht FROM `$srcDB`.`mm_stamm_immobilien_geloescht` WHERE gebaeude IN(\"".implode("\",\"", $aGebKuerzel)."\")";
	$rows = $db->query_rows($sql);
	if ($db->error()) echo $db->error()."\n".$sql."\n";
	for($i = 0; $i < count($rows); $i++) {
		if (empty($rows[$i]["gebaeude"])) continue;
		$e = $rows[$i];
		$g = $e["gebaeude"];
		$aGebaeudeStat[$g]["Gelöschte Räume"] = $e["raeume_geloescht"];
	}
	
	for($i = 0; $i < count($aGebaeude); $i++) {
		$e = $aGebaeude[$i];
		$g = $e["gebaeude"];
		/*
		$aGebaeudeStat[$g] = array("Etagen"=>"", "Räume"=>"", "Alle Mitarbeiter"=>"", "Neue Mitarbeiter"=>"", "Gelöschte Räume"=>"", "Gelöschte Mitarbeiter"=>"");
		
		$sql = "SELECT gebaeude, count(distinct(etage)) etagen, count(distinct(raumnr)) raeume FROM `mm_stamm_immobilien` WHERE gebaeude = \"".$g."\"";
		$row = $db->query_singlerow($sql);
		if ($db->error()) echo $db->error()."\n".$sql."\n";
		//else echo "row: ".print_r($row, 1)."\n";
		$aGebaeudeStat[$g]["Etagen"] = $row["etagen"];
		$aGebaeudeStat[$g]["Räume"]  = $row["raeume"];
		
		$sql = "SELECT count(distinct(raumnr)) raeume_geloescht FROM `mm_stamm_immobilien_geloescht` WHERE gebaeude = \"".$g."\"";
		$row = $db->query_singlerow($sql);
		if ($db->error()) echo $db->error()."\n".$sql."\n";
		//else echo "row: ".print_r($row, 1)."\n";
		$aGebaeudeStat[$g]["Gelöschte Räume"]  = $row["raeume_geloescht"];
		*/
		$sql = "SELECT count(*) mitarbeiter FROM `$srcDB`.`mm_stamm_mitarbeiter` WHERE immobilien_raum_id IN (SELECT id FROM `$srcDB`.`mm_stamm_immobilien` WHERE gebaeude = \"".$g."\")";
		$row = $db->query_singlerow($sql);
		//echo $sql."\n";
		if ($db->error()) echo $db->error()."\n".$sql."\n";
		//else echo "row: ".print_r($row, 1)."\n";
		$aGebaeudeStat[$g]["Alle Mitarbeiter"]  = $row["mitarbeiter"];
		
		$sql = "SELECT count(*) mitarbeiter_neu FROM `$srcDB`.`mm_stamm_mitarbeiter` WHERE (gebaeude like '' OR gebaeude is null) AND immobilien_raum_id IN (SELECT id FROM `$srcDB`.`mm_stamm_immobilien` WHERE gebaeude = \"".$g."\")";
		$row = $db->query_singlerow($sql);
		if ($db->error()) echo $db->error()."\n".$sql."\n";
		//else echo "row: ".print_r($row, 1)."\n";
		$aGebaeudeStat[$g]["Neue Mitarbeiter"]  = $row["mitarbeiter_neu"];
		
		$sql = "SELECT count(*) mitarbeiter_geloescht FROM `$srcDB`.`mm_stamm_mitarbeiter_geloescht` WHERE immobilien_raum_id IN (SELECT id FROM `$srcDB`.`mm_stamm_immobilien` WHERE gebaeude = \"".$g."\")";
		$row = $db->query_singlerow($sql);
		if ($db->error()) echo $db->error()."\n".$sql."\n";
		//else echo "row: ".print_r($row, 1)."\n";
		$aGebaeudeStat[$g]["Gelöschte Mitarbeiter"]  = $row["mitarbeiter_geloescht"];
	}
}

$sql = "SHOW DATABASES LIKE \"mt\_move%\"";
$aDBs = $db->query_rows($sql);
//print_r($aDBs);

$selectDB = "<select onchange=\"document.getElementById('chgDB').submit()\" name=\"srcDB\">\n";
foreach($aDBs as $v) { $k = key($v); if (!empty($v[$k])) $selectDB.= "<option ".($v[$k] == $srcDB ? "selected=\"true\"":"")." value=\"".fb_htmlEntities($v[$k])."\">".$v[$k]."</option>\n"; }
$selectDB.= "</select>\n";

$i =0;
$wz=0;
echo "<form id=\"chgDB\">Quell-DB: ".$selectDB."<noscript><input type=submit value=\"DB wechseln\"></noscript></form>\n";
echo "<form action=\"?\" method=\"post\">";
echo "<table class=\"tblList\"><thead><tr><td>#</td><td>Gebäude</td>";
if (!empty($aGebaeudeStat)) {
	$g = key($aGebaeudeStat);
	foreach($aGebaeudeStat[$g] as $k => $v) echo "<td>$k</td>";
	echo "</tr><thead><tbody>\n";
	foreach($aGebaeudeStat as $g => $aV) {
		$wz = ($wz!=1?1:2);
		echo "<tr class=\"wz{$wz}\"><td class=\"int\">".(++$i)."</td><td><input name=\"GebFilter[]\" ".(!in_array($g,$GebFilter)?"":"checked=\"true\"")." type=\"checkbox\" value=\"".$g."\">".$g."</td>";
		foreach($aV as $k => $v) echo "<td>$v</td>";
		echo "</tr>\n";
	}
	echo "</tbody></table>\n";
	echo "<span class=\"jsLink\" onclick=\"checkCheckbox('GebFilter[]', 1, '')\">Alle markieren</span> ";
	echo "<span class=\"jsLink\" onclick=\"checkCheckbox('GebFilter[]', 0, '')\">Alle demarkieren</span> ";
	echo "<span class=\"jsLink\" onclick=\"checkCheckbox('GebFilter[]', -1, '')\">Alle umkehren</span>\n";
	
	echo "<input type=\"checkbox\" name=\"runImport\">Ausgespielte Daten von `$srcDB` nach `$dstDB` importieren\n";
	echo "<input type=\"hidden\" name=\"srcDB\" value=\"".fb_htmlEntities($srcDB)."\">\n";
	echo "<input type=\"submit\" name=\"runExport\" value=\"Daten ausspielen\">\n";
	echo "(Gebäude, Räume, Mitarbeiter, Bereiche, Abteilungen, Gelöschte Räume u. Mitarbeiter)\n";
}
echo "</form>\n";
//echo print_r($aGebaeudeStat,1)."\n";

if (empty($runExport) || empty($GebFilter))
	exit;
else
	$aGebaeudeFilter = $GebFilter;

function exportSql2CsvFile($sql, $exportFile, $sep = "", $quot = false, $fileAppend = false) {
	if (!$sep) $sep = ";";
	if (gettype($quot) != "string") $quot = "\"";
	if (file_exists($exportFile) && !is_writeable($exportFile)) die("Can't write to file: $exportFile!");
	$fp = fopen($exportFile, (!$fileAppend?"w+":"a+"));
	if (!is_resource($fp)) die("No Access to file: $exportFile!");
	$aRpl = array("\r"=> "\\r", "\n"=>"\\n", "\""=>"\\\"", $quot=>"\\".$quot);
	$r = MyDB::query($sql);
	$n = false;
	if ($r) {
		$n = MyDB::num_rows($r);
		$nf = MyDB::num_fields($r);
		fputs($fp, "##FIELDS: ");
		for($i = 0; $i < $nf; $i++) {
			fputs($fp, ($i?$sep:"").$quot.MyDB::field_name($r, $i).$quot);
		}
		fputs($fp, "\r\n");
		
		for($i = 0; $i < $n; $i++) {
			$e = MyDB::fetch_array($r, MYSQL_NUM);
			for($j = 0; $j < count($e); $j++) fputs($fp, ($j?$sep:"").$quot.strtr(trim($e[$j]), $aRpl).$quot);
			
			fputs($fp, "\r\n");
		}
	} else echo "#".__LINE__." ".basename(__FILE__)." SQL-ERR:".MyDB::error()."\nSQL-CMD:".$sql."\n";
	fclose($fp);
	return $n;
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

$row = $db->query_singlerow("SHOW DATABASES LIKE \"$dstDB\"");
if (empty($row)) {
	die("Ziel-DB '$dstDB' existiert nicht oder es fehlen Zugriffsrechte!\n".$db->error()."\nSHOW DATABASES LIKE \"x$dstDB\"\n".print_r($row,1));
}

if ($runExport) {
	$sql = "SELECT a.*, count(*) anzahl FROM `$srcDB`.`mm_stamm_abteilungen` a, `$srcDB`.`mm_stamm_mitarbeiter` m where a.abteilung = m.abteilung group by a.abteilung ORDER BY `a`.`abteilung` ASC";
	$exportFile = $exportDir."bestandsaufnahme_export_abteilungen_".substr(implode(",", $aGebaeudeFilter),0,25).".csv";
	$num_rows = exportSql2CsvFile($sql, $exportFile, $sep, $quot, $fileAppend);
	$aExportFiles["abteilungen"]["file"] = $exportFile;
	$aExportFiles["abteilungen"]["rows"] = $num_rows;
	
	$sql = "SELECT b.*, count(*) anzahl FROM `$srcDB`.mm_stamm_hauptabteilungen b, `$srcDB`.mm_stamm_mitarbeiter m where b.bereich = m.bereich group by b.bereich ORDER BY `b`.`bereich` ASC";
	$exportFile = $exportDir."bestandsaufnahme_export_hauptabteilungen_".substr(implode(",", $aGebaeudeFilter),0,25).".csv";
	$num_rows = exportSql2CsvFile($sql, $exportFile, $sep, $quot, $fileAppend);
	$aExportFiles["hauptabteilungen"]["file"] = $exportFile;
	$aExportFiles["hauptabteilungen"]["rows"] = $num_rows;
	
	$sql = "SELECT * FROM `$srcDB`.`mm_stamm_gebaeude` WHERE gebaeude IN ('".implode("','", $aGebaeudeFilter)."')";
	$exportFile = $exportDir."bestandsaufnahme_export_gebaeude_".substr(implode(",", $aGebaeudeFilter),0,25).".csv";
	$num_rows = exportSql2CsvFile($sql, $exportFile, $sep, $quot, $fileAppend);
	$aExportFiles["gebaeude"]["file"] = $exportFile;
	$aExportFiles["gebaeude"]["rows"] = $num_rows;
	
	$sql = "SELECT * FROM `$srcDB`.`mm_stamm_immobilien` WHERE gebaeude IN ('".implode("','", $aGebaeudeFilter)."')";
	$exportFile = $exportDir."bestandsaufnahme_export_raeume_".substr(implode(",", $aGebaeudeFilter),0,25).".csv";
	$num_rows = exportSql2CsvFile($sql, $exportFile, $sep, $quot, $fileAppend);
	$aExportFiles["raeume"]["file"] = $exportFile;
	$aExportFiles["raeume"]["rows"] = $num_rows;
	
	$sql = "SELECT * FROM `$srcDB`.`mm_stamm_immobilien_geloescht` WHERE gebaeude IN ('".implode("','", $aGebaeudeFilter)."')";
	$exportFile = $exportDir."bestandsaufnahme_export_raeume_geloescht_".substr(implode(",", $aGebaeudeFilter),0,25).".csv";
	$num_rows = exportSql2CsvFile($sql, $exportFile, $sep, $quot, $fileAppend);
	$aExportFiles["raeume_geloescht"]["file"] = $exportFile;
	$aExportFiles["raeume_geloescht"]["rows"] = $num_rows;
	
	$sql = "SELECT i.id, i.ort, i.gebaeude, i.etage, i.raumnr, m.arbeitsplatznr, m.name, m.vorname, m.extern, m.extern_firma, m.ersthelfer, m.raeumungsbeauftragter, m.anmerkung, m.anrede, m.mitarbeiter, m.gf, m.bereich, m.abteilung
	FROM `$srcDB`.`mm_stamm_mitarbeiter_geloescht` m
	LEFT JOIN `$srcDB`.`mm_stamm_immobilien` i ON ( m.immobilien_raum_id = i.id )
	WHERE i.gebaeude IN ('".implode("','", $aGebaeudeFilter)."')";
	$exportFile = $exportDir."bestandsaufnahme_export_ma_geloescht_".substr(implode(",", $aGebaeudeFilter),0,25).".csv";
	$num_rows = exportSql2CsvFile($sql, $exportFile, $sep, $quot, $fileAppend);
	$aExportFiles["mitarbeiter_geloescht"]["file"] = $exportFile;
	$aExportFiles["mitarbeiter_geloescht"]["rows"] = $num_rows;
	
	$sql = "SELECT i.id, i.ort, i.gebaeude, i.etage, i.raumnr, m.arbeitsplatznr, m.name, m.vorname, m.extern, m.extern_firma, m.ersthelfer, m.raeumungsbeauftragter, m.anmerkung, m.anrede, m.mitarbeiter, m.gf, m.bereich, m.abteilung
	FROM `$srcDB`.`mm_stamm_mitarbeiter` m
	LEFT JOIN `$srcDB`.`mm_stamm_immobilien` i ON ( m.immobilien_raum_id = i.id )
	WHERE i.gebaeude IN ('".implode("','", $aGebaeudeFilter)."')";
	$exportFile = $exportDir."bestandsaufnahme_export_ma_".substr(implode(",", $aGebaeudeFilter),0,25).".csv";
	$num_rows = exportSql2CsvFile($sql, $exportFile, $sep, $quot, $fileAppend);
	$aExportFiles["mitarbeiter"]["file"] = $exportFile;
	$aExportFiles["mitarbeiter"]["rows"] = $num_rows;
	// Export-Ende
	
	foreach($aExportFiles as $key => $aV) echo "\n<div><a href=\"".$aV["file"]."\">$key ".$aV["rows"].": ".$aV["file"]."</a></div>\n";
}
// Start-Import

if ($runImport) {
	// Wichtige Löschreihenfolge: Erst MA in Abängigkeit der Räume (Sub-Query), dann die Räume
	// Leeren der Mitarbeiterdaten
	$sql = "DELETE FROM `$dstDB`.`mm_stamm_mitarbeiter`
	where immobilien_raum_id IN (SELECT id FROM `$dstDB`.mm_stamm_immobilien where gebaeude IN ('".implode("','", $aGebaeudeFilter)."') )";
	$db->query($sql);
	if ($db->error()) echo "#".__LINE__." DB-ERR: ".$db->error()."\n".$sql."\n";
	
	// Leeren der Raumdaten
	$sql = "DELETE  FROM `$dstDB`.mm_stamm_immobilien where gebaeude IN ('".implode("','", $aGebaeudeFilter)."')";
	$db->query($sql);
	if ($db->error()) echo $db->error()."\n".$sql."\n";
	
	// Import-Abteilungen
	$num_import = 0;
	$i = 0;
	$fp = fopen($aExportFiles["abteilungen"]["file"], "r");
	$aImportFields = explode(",", "bereich,abteilung,abteilungsname,abteilungsleiter");
	if ($fp) {
		while($line = fgets($fp, 2000)) {
			if ($line[0] == "#") {
				$aChckFields = csv_getFields($line);
				if (!empty($aChckFields)) $aFields = $aChckFields;
				continue;
			}
			$aValues = explode($quot.$sep.$quot, substr(trim($line),1,-1));
			if (count($aValues) == count($aFields)) {
				$e = array_combine($aFields, $aValues);
				$eInsert = array();
				foreach($aImportFields as $fld) $eInsert[$fld] = (!empty($e[$fld])) ? $e[$fld] : "";
				
				$sql = "SELECT id, bereich FROM `$dstDB`.`mm_stamm_abteilungen` WHERE abteilung = \"".$e["abteilung"]."\"";
				$row = $db->query_singlerow($sql);
				if (empty($row["id"])) {
					$sql = "INSERT INTO `$dstDB`.mm_stamm_abteilungen (".implode(",", $aImportFields).") VALUES ";
					$sql.= "(\"".implode("\",\"", $eInsert)."\")";
					$db->query($sql);
					if ($db->error()) echo "#".__LINE__." DB-ERR: ".$db->error()."\n".$sql."\n";
					else {
						$num_import++;
						echo "Neue Abteilung ".$e["abteilung"]." mit ".$e["anzahl"]." MA wurde importiert!\n";
					}
				} elseif ($row["bereich"] != $e["bereich"]) {
					echo "Abteilung ".$e["abteilung"]." (Bereich ".$e["bereich"].") existiert bereits unter anderer Hauptabteilung ".$row["bereich"]."!\n";
				}
			}
		}
	}
	echo "Es wurden $num_import neue Abteilungen importiert!\n";
	fclose($fp);
	
	
	// Import-Hauptabteilungen
	$i = 0;
	$num_import = 0;
	$fp = fopen($aExportFiles["hauptabteilungen"]["file"], "r");
	$aImportFields = explode(",", "bereich,bereichsname,bereichsleiter,organisationseinheit");
	if ($fp) {
		while($line = fgets($fp, 2000)) {
			if ($line[0] == "#") {
				$aChckFields = csv_getFields($line);
				if (!empty($aChckFields)) $aFields = $aChckFields;
				continue;
			}
			$aValues = explode($quot.$sep.$quot, substr(trim($line),1,-1));
			if (count($aValues) == count($aFields)) {
				$e = array_combine($aFields, $aValues);
				$eInsert = array();
				foreach($aImportFields as $fld) $eInsert[$fld] = (!empty($e[$fld])) ? $e[$fld] : "";
				
				$sql = "SELECT id, organisationseinheit FROM `$dstDB`.`mm_stamm_hauptabteilungen` WHERE bereich = \"".$e["bereich"]."\"";
				$row = $db->query_singlerow($sql);
				if (empty($row["id"])) {
					$sql = "INSERT INTO `$dstDB`.mm_stamm_hauptabteilungen (".implode(",", $aImportFields).") VALUES ";
					$sql.= "(\"".implode("\",\"", $eInsert)."\")";
					$db->query($sql);
					if ($db->error()) echo "#".__LINE__." DB-ERR: ".$db->error()."\n";
					else {
						$num_import++;
						echo "Neuer Bereich ".$e["bereich"]." mit ".$e["anzahl"]." MA wurde importiert!\n";
					}
				} elseif ($row["organisationseinheit"] != $e["organisationseinheit"]) {
					echo "Bereich ".$e["bereich"]." (GF ".$e["organisationseinheit"].") existiert bereits unter anderem GF ".$row["organisationseinheit"]."!\n";
				}
			}
		}
	}
	echo "Es wurden $num_import neue Bereiche importiert!\n";
	fclose($fp);
	
	
	// Import-Gebäude
	$sql = "DELETE  FROM `$dstDB`.mm_stamm_gebaeude where gebaeude IN ('".implode("','", $aGebaeudeFilter)."')";
	$db->query($sql);
	if ($db->error()) echo $db->error()."\n".$sql."\n";
	
	$i = 0;
	$num_import = 0;
	$fp = fopen($aExportFiles["gebaeude"]["file"], "r");
	$aImportFields = explode(",", "gebaeude,gebaeudename,nutzflaeche,belegschaft,nutzflaeche_pro_ma,flaeche_pro_ma,stadt,stadtname,adresse");
	if ($fp) {
		while($line = fgets($fp, 2000)) {
			if ($line[0] == "#") {
				$aChckFields = csv_getFields($line);
				if (!empty($aChckFields)) $aFields = $aChckFields;
				continue;
			}
			$aValues = explode($quot.$sep.$quot, substr(trim($line),1,-1));
			if (count($aValues) == count($aFields)) {
				$e = array_combine($aFields, $aValues);
				$eInsert = array();
				foreach($aImportFields as $fld) $eInsert[$fld] = (!empty($e[$fld])) ? $e[$fld] : "";
				
				$sql = "SELECT id FROM `$dstDB`.mm_stamm_gebaeude WHERE gebaeude =\"".$e["gebaeude"]."\" LIMIT 1 ";
				$row = $db->query_singlerow($sql);
				
				if (empty($row["id"])) {
					$sql = "INSERT INTO `$dstDB`.mm_stamm_gebaeude (".implode(",", $aImportFields).") VALUES ";
					$sql.= "(\"".implode("\",\"", $eInsert)."\")";
					$db->query($sql);
					if ($db->error()) echo "#".__LINE__." DB-ERR: ".$db->error()."\n";
					else $num_import++;
				} else {
					echo "Allready Exists: Gebäude".$e["gebaeude"]."\n";
				}
				if ($i++ < 10) echo $sql."\n";
			}
		}
	}
	echo "Es wurden $num_import Gebäudedaten angelegt bzw. überschrieben falls schon vorhanden!\n";
	fclose($fp);
	
	
	// Import-Räume
	$i = 0;
	$num_import = 0;
	$fp = fopen($aExportFiles["raeume"]["file"], "r");
	$aImportFields = explode(",", "ort,gebaeude,etage,raumnr,raum_flaeche,raum_kategorie,raum_typ");
	if ($fp) {
		while($line = fgets($fp, 2000)) {
			if ($line[0] == "#") {
				$aChckFields = csv_getFields($line);
				if (!empty($aChckFields)) $aFields = $aChckFields;
				continue;
			}
			$aValues = explode($quot.$sep.$quot, substr(trim($line),1,-1));
			if (count($aValues) == count($aFields)) {
				$e = array_combine($aFields, $aValues);
				$eInsert = array();
				foreach($aImportFields as $fld) $eInsert[$fld] = (!empty($e[$fld])) ? $e[$fld] : "";
				
				$sql = "INSERT INTO `$dstDB`.mm_stamm_immobilien (".implode(",", $aImportFields).") VALUES ";
				$sql.= "(\"".implode("\",\"", $eInsert)."\")";
				$db->query($sql);
				if ($db->error()) echo "#".__LINE__." DB-ERR: ".$db->error()."\n";
				else $num_import++;
				if ($i++ < 10) echo $sql."\n";
			}
		}
	}
	echo "Es wurden $num_import Räume importiert!\n";
	fclose($fp);
	
	
	
	// Import - Mitarbeiter
	$i = 0;
	$num_import = 0;
	$fp = fopen($aExportFiles["mitarbeiter"]["file"], "r");
	$aImportFields = explode(",", "abteilungen_id,immobilien_raum_id,arbeitsplatznr,name,vorname,extern,extern_firma,ersthelfer,raeumungsbeauftragter,anmerkung,anrede,mitarbeiter,gebaeude,etage,raumnr,gf,bereich,abteilung");
	if ($fp) {
		while($line = fgets($fp, 2000)) {
			if ($line[0] == "#") {
				$aChckFields = csv_getFields($line);
				if (!empty($aChckFields)) $aFields = $aChckFields;
				continue;
			}
			$aValues = explode($quot.$sep.$quot, substr(trim($line),1,-1));
			if (count($aValues) == count($aFields)) {
				/*for($k = 0; $k < count($aValues); $k++) {
					$e[$aFields[$k]] = $aValues[$k];
					if ($i < 50) echo "combine k:$k; aFields[$k]:".$aFields[$k]."; aValues[$k]:".$aValues[$k]." e[".$aFields[$k]."]:".$e[$aFields[$k]]."\n";
					else die();
				}
				if ($i < 50) echo "e:".print_r($e,1)."\n\n";
				*/
				$i++;
				$e = array_combine($aFields, $aValues);
					//if ($i++ < 50) echo "CanNot Link to Raum: ".$db->error()."\nSQL:".$sql."\ne:".print_r($e,1)."\naFields:".print_r($aFields,1)."\naValues:".print_r($aValues,1)."\nLINE: ".$line."\n\n";
					//else die();
				
				$sql = "SELECT id FROM `$dstDB`.`mm_stamm_immobilien` WHERE gebaeude = \"".$e["gebaeude"]."\" AND etage=\"".$e["etage"]."\" AND raumnr=\"".$e["raumnr"]."\"";
				$row = $db->query_singlerow($sql);
				if (!empty($row) && $row["id"]) {
					$e["immobilien_raum_id"] = $row["id"];
				} else {
					die("CanNot Link to Raum: ".$db->error()."\nSQL:".$sql."\n");
				}
				if ($e["abteilung"]) {
					$sql = "SELECT id FROM `$dstDB`.`mm_stamm_abteilungen` WHERE abteilung = \"".$e["abteilung"]."\"";
					$row = $db->query_singlerow($sql);
					$e["abteilungen_id"] = $row["id"];
				} else $e["abteilungen_id"] = 0;
				
				$eInsert = array();
				foreach($aImportFields as $fld) $eInsert[$fld] = (!empty($e[$fld])) ? $e[$fld] : "";
				
				$sql = "INSERT INTO `$dstDB`.mm_stamm_mitarbeiter (".implode(",", $aImportFields).") VALUES ";
				$sql.= "(\"".implode("\",\"", $eInsert)."\")";
				$db->query($sql);
				if ($db->error()) echo "#".__LINE__." DB-ERR: ".$db->error()."\n";
				else $num_import++;
				//if ($i++ < 10) { echo $sql."\n"; print_r($e); }
			}
		}
	}
	echo "Es wurden $num_import Mitarbeiter importiert!\n";
	fclose($fp);
}
?>
</pre>
</body>
</html>
