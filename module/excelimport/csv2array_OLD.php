<?php 

function fit_colnames4mysql($colname) {
	$colname = str_replace(" - ", "_", $colname);
	$colname = str_replace(" + ", "_", $colname);
	$colname = str_replace("-", "_", $colname);
	$colname = str_replace("+", "_", $colname);
	$colname = str_replace(" ", "_", $colname);
	$a = array(
		":" => "",
		";" => "",
		"." => "",
		"," => "",
		"#" => "",
		"�" => "ae",
		"�" => "ae",
		"�" => "oe",
		"�" => "oe",
		"�" => "ue",
		"�" => "ue",
		"�" => "ss"
	);
	$colname = ucfirst(strtolower(strtr($colname, $a)));
	return $colname;
}

function get_max_lfd_nr() {
	global $_TABLE;
	global $connid;
	
	$max_lfd_nr = 0;
	$SQL = "SELECT MAX(lfd_nr) FROM `".$_TABLE["projects"]."`";
	$r = MyDB::query($SQL, $connid);
	if ($r) {
		list($max_lfd_nr) = MyDB::fetch_array($r, MYSQL_NUM);
		MyDB::free_result($r);
	} else echo MyDB::error()."<br>\n".$SQL."; \$max_lfd_nr:$max_lfd_nr<br>\n";
	return $max_lfd_nr;
}

function get_mysqlFields($table) {
	global $_TABLE;
	global $connid;
	
	$reFields = array();
	
	$SQL = "SHOW FIELDS FROM `".$table."`";
	$r = MyDB::query($SQL, $connid);
	if ($r) {
		$n = MyDB::num_rows($r);
		for ($i = 0; $i < $n; $i++) {
			$_e = MyDB::fetch_assoc($r);
			// [Field] => pid [Type] => int(11) [Null] => NO [Key] => PRI [Default] => [Extra] => auto_increment
			$reFields[$_e["Field"]] = $_e;
		}
		MyDB::free_result($r);
	}
	return $reFields;
}
$aProjMysqlFields = get_mysqlFields($_TABLE["projects"]);
$aZeMysqlFields = get_mysqlFields($_TABLE["p_entries"]);
//echo "#".__LINE__." ".__FILE__." \$aProjMysqlFields:".print_r($aProjMysqlFields, true)."<br>\n";

function get_tableOfField($fld) {
	//lfd.-Nr.:
	//A-ge�ndert von:	A-Std.	A-Datum	
	//B-ge�ndert von:	B-Std.	B-Datum	
	//C-ge�ndert von:	C-Std.	C-Datum	
	//D-ge�ndert von:	D-Std.	D-Datum	
	//E-ge�ndert von:	E-Std.
	//
	if (substr($fld, 1, strlen("-ge�ndert von:")) == "-ge�ndert von:"
	 || substr($fld, 1, strlen("-Std.")) == "-Std."
	 || substr($fld, 1, strlen("-Datum")) == "-Datum") {
		return "p_entries";
	} else {
		return "projects";
	}
}

function pentry_colname2MyDB::fldname($colname) {
	switch(substr($colname, 1)) {
		case "-ge�ndert von:":
		return "Mitarbeiter";
		break;
		
		case "-Std.":
		return "Dauer";
		break;
		
		case "-Datum":
		return "Datum";
		break;
		
		default:
		return "";
	}
}

function date_de2mysql($de) {
	$sValidChars = ".0123456789";
	$newDe = "";
	for($i = 0; $i < strlen($de); $i++) {
		if (is_int(strpos($sValidChars, $de[$i]))) $newDe.= $de[$i];
	}
	// echo "#".__LINE__." de:$de; newDe:$newDe<br>\n";
	$de = $newDe;
	$t = explode(".", $de);
	if (count($t) != 3 || !is_numeric(implode("",$t))) return $de;
	
	return substr("20".$t[2], -4)."-".substr("0".$t[1],-2)."-".substr("0".$t[0],-2);
}

function float_de2mysql($de) {
	$de = str_replace(".", "", $de);
	$de = str_replace(",", ".", $de);
	return $de;
}

if (!function_exists("format_fstat")) {
function format_fstat($file) {
	$d = date("Y-m-d H:i", filemtime($file));
	$d = (substr($d, 0, 10) != date("Y-m-d")) ? array_shift(explode(" ",$d)) : array_pop(explode(" ",$d));
	$s = filesize($file);
	if ($s < 1024) {
		$s = $s."B";
	} elseif ($s < 1024*1024) {
		$s = round($s/1024,1)."KB";
	} elseif ($s < 1024*1024*1024) {
		$s = round($s/(1024*1024),1)."MB";
	} else {
		$s = round($s/(1024*1024*1024),1)."GB";
	}
	return $d."|".$s;
}}

$XD = false; // Debug-Schalter
$file_liste = "";
$trackVars = "s=".(!empty($_GET["s"]) ? urldecode($_GET["s"]) : "csvimport");
$db_import = (isset($_POST["db_import"]));
$fuploadname = "csvfile";
$csvToTbl = "";
$data_cnt = 0;
$line_cnt = 0;
$last_first_TiWord = "";
$serialized_titles_file = dirname(__FILE__)."/serialized_fitTitles.phs";
$csv_dir = "./import_csv/";
$csv_file = (isset($_POST["csv_file"])) ? $_POST["csv_file"] : (isset($_GET["csv_file"]) ? $_GET["csv_file"] : "");
$del_file = (isset($_POST["del_file"])) ? $_POST["del_file"] : (isset($_GET["del_file"]) ? $_GET["del_file"] : "");
$aTitles = array();
$aEntries = array();
$aSubEntries = array();
$aFldMaxLength = array();
$aFldMinLength = array();
$max_lfdnr = get_max_lfd_nr();
$lfdnr = $max_lfdnr;

if (isset($_FILES[$fuploadname])) {

	if (!$_FILES[$fuploadname]["error"]) {
	
		$saveas = $_FILES[$fuploadname]["name"];
		$tmp_saveas = $csv_dir.$saveas;
		$t = explode(".", $saveas);
		$upload_is_ok = false;
		switch(strtolower(implode(".",array_slice($t,-1)))) {
			case "txt":
			case "csv":
			case "xls":
			$upload_is_ok = true;
			if (is_uploaded_file ($_FILES[$fuploadname]["tmp_name"])) {
				$upload_is_ok = true;
				// die("#".__LINE__." IS-Uploaded-File: ".$_FILES[$fuploadname]["name"]."!");
			} else {
				// die("#".__LINE__." No-Uploaded-File: ".$_FILES[$fuploadname]["name"]."!");
			}
			break;
			
			default:
			$msg.= "#".__LINE__." Ung�ltige Dateierweiterung: ".strtolower(implode(".",array_slice($t,-1)))."<br>\n";
		}
		$tmp_nr = 2;
		$max_nr = 100;
		while (file_exists($tmp_saveas)) {
			$tmp_saveas = $csv_dir.implode("",array_slice($t, 0, -1))."_".date("Y.m.d")."($tmp_nr)".".".implode("",array_slice($t, -1));
			if ($max_nr <= ++$tmp_nr) { $upload_is_ok = false; break; }
		}
		if ($upload_is_ok && move_uploaded_file ($_FILES[$fuploadname]["tmp_name"] , $tmp_saveas )) {
			$msg.= "Neue CSV-Datei wurde unter <a href=\"$tmp_saveas\">".basename($tmp_saveas). "</a> abgespeichert!<br>\n";
			$csv_file = basename($tmp_saveas);
		}
	}
}

$show_csv_files = (!empty($_GET["show_csv_files"]) || !$csv_file && !file_exists($csv_dir.$csv_file)) ? true : false;
if ($del_file) {
	if (file_exists($csv_dir."/".$del_file)) {
		if (unlink($csv_dir."/".$del_file)) $msg.= "Datei <strong>$del_file</strong> wurde gel�scht!<br>\n";
		else $msg.= "Datei <strong>$del_file</strong> konnte <strong>nicht</strong> gel�scht werden!<br>\n";
	} else  $msg.= "Datei <strong>$del_file</strong> existiert <strong>nicht</strong>!<br>\n";
}

if ($show_csv_files) {
	$dp = opendir($csv_dir);
	if ($dp) {
		$file_liste.= "<table cellpadding=1 cellspacing=0>\n";
		while($file = readdir($dp)) {
			if (!is_dir($csv_dir.$file) && is_int(strpos(".txt/.csv/.xls", substr($file,-4)))) {
				list($fdt,$fs) = explode("|", format_fstat($csv_dir.$file));
				$file_liste.= "
				<tr>
					<td>".$fdt."</td>
					<td align=right>".$fs."</td>
					<td>"."<a href=\"?".$trackVars."&csv_file=".urlencode($file)."\">".$file." </a> </td>
					<td>"."<a href=\"".$csv_dir.$file."\" target=_blank style=\"color:#00f;\">Anzeigen </a> </td>
					<td>"."<a href=\"?".$trackVars."&del_file=".urlencode($file)."\" style=\"color:#f00;\">L�schen </a> </td>
				</tr>\n";
				$aCsvFiles[] = $file;
			}
		}
		$file_liste.= "</table>";
		closedir($dp);
	}
} elseif ($csv_file && file_exists($csv_dir.$csv_file)) {
	$fp = fopen($csv_dir.$csv_file, "r");
	
	if ($fp) {
		while ($data = fgetcsv($fp, 2000, ";")) {
			if (trim(implode(";", $data)) == "") continue;
			$j = count($aEntries);
			$aEntries[$j] = $data;
			
			if (count($aTitles) == 0) {
				$aTitles = array_pop($aEntries);
				$j = count($aEntries);
				foreach($aTitles as $k => $v) {
					if ($v == "Std.") $aTitles[$k] = $last_first_TiWord."-".$v;
					elseif ($v == "Datum") $aTitles[$k] = $last_first_TiWord."-".$v;
					if (is_int(strpos($v, "-"))) list($last_first_TiWord) = explode("-", $v);
					
				}
				continue;
			}
			if ($j > 0 && count($aEntries[$j]) != count($aEntries[$j-1])) {
				$csvToTbl.= "Dateninkonsistenz in Zeile $line_cnt. Datensatz in der Zeile:".count($aEntries[$j]).", statt $data_cnt<br>\n";
				break;
			}
			
			foreach($aEntries[$j] as $k => $v) {
				if (isset($aFldMaxLength[$k])) {
					$aFldMaxLength[$k] = max($aFldMaxLength[$k],strlen($v));
					$aFldMinLength[$k] = min($aFldMinLength[$k],strlen($v));
				} else {
					$aFldMaxLength[$k] = strlen($v);
					$aFldMinLength[$k] = strlen($v);
				}
			}
		}
		fclose($fp);
	}
	if (isset($_POST["cleanProjektTables"])) {
		$SQL = "DELETE FROM `".$_TABLE["projects"]."`";
		MyDB::query($SQL, $connid);
		if (!MyDB::error()) $msg.= "Die Tabelle `".$_TABLE["projects"]."` (".MyDB::affected_rows()."Einträge) wurde geleert!<br>\n";
		$SQL = "DELETE FROM `".$_TABLE["p_entries"]."`";
		MyDB::query($SQL, $connid);
		if (!MyDB::error())$msg.= "Die Tabelle `".$_TABLE["p_entries"]."` (".MyDB::affected_rows()."Einträge) wurde geleert!<br>\n";
	}
	
	if (!isset($_POST["ft"])) {
		if (file_exists($serialized_titles_file)) {
			$aFitTitles = unserialize(file_get_contents($serialized_titles_file));
		} else {
			foreach($aTitles as $k => $v) $aFitTitles[$k] = fit_colnames4mysql($aTitles[$k]);
		}
		
	} else {
		foreach($aTitles as $k => $v) $aFitTitles[$k] =  (!empty($_POST["ft"][$k])) ? $_POST["ft"][$k] : fit_colnames4mysql($aTitles[$k]);
		file_put_contents($serialized_titles_file, serialize($aFitTitles));
	}
	
	
	$csvToTbl.= "Anzahl Spalten: ".count($aEntries[0])."<br>\n";
	$csvToTbl.= "<form action=\"?".$trackVars."\" method=post><table border=1>
		<thead><tr>";
	foreach($aTitles as $k => $v) {
		$csvToTbl.= "<td nowrap=true>Feld: ".($k+1)." (".$aFldMinLength[$k]."-".$aFldMaxLength[$k].")"."
		<br><input type='text' name='ft[".($k)."]' id='ft[".($k)."]' value='".fb_htmlEntities($aFitTitles[$k])."'
		   autocomplete='off' onclick=\"showComboBox(this, 'csvimport','single')\"
		>
		</td>";
	}
	$csvToTbl.= "</tr><tr>\n";
	foreach($aTitles as $k => $v) {
		$csvToTbl.= "<td nowrap=true>".$v."</td>";
	}
	$csvToTbl.= "</tr></thead>
		<tbody>
	";
	
	
	$SQL = "INSERT INTO `".$_TABLE["projects"]."` \n(";
	for($i = 0; $i<count($aFitTitles); $i++) {
		$r_tbl = get_tableOfField($aTitles[$i]);
		$isColOfProject[$i] = ($r_tbl == "projects");
		if ($XD) $csvToTbl.= "#".__LINE__." ".$aTitles[$i]." gehört zu Tabelle $r_tbl<br>\n";
		if ($isColOfProject[$i]) {
			$SQL.= ($i?",":"")."`".MyDB::escape_string($aFitTitles[$i])."`";
		}
	}
	$SQL.= ")\nVALUES\n";
	
	$num_inserts = 0;
	$num_entries = 0;
	foreach($aEntries as $k => $tmp) {
		if (!is_numeric($tmp[0]) || !implode("",array_slice($tmp, 2))) {
			$msg.= "#".__LINE__." Ausgefilterte Zeile $k:".implode(";",$tmp)."<br>\n";
			continue;
		}
		$csvToTbl.= "<tr>";
		$SQL.= ($num_inserts?",\n":"")."(";
		$num_f = 0;
		foreach($aEntries[$k] as  $i => $v) {
			if ($aFitTitles[$i] == "lfd_nr") {
				$v = ($lfdnr++);
				// echo "v:$v, lfdnr:$lfdnr, max_lfdnr:$max_lfdnr <br>\n";
			}
			$csvToTbl.= "<td>".$v."</td>";
			if ($aFitTitles[$i] == "ADM") {
				$v = strtr($v, array(" + "=>", ", "+ "=>", ", "+"=>", ", " / "=> ", ", "/ "=>", ", "/"=> ", "));
			}
			
			if ($isColOfProject[$i]) {
				
				switch($aFitTitles[$i]) {
					case "lfd_nr":
					$aSubEntries[$k]["lfd_nr"] = $v;
					break;
					
					case "Eingangsdatum":
					case "Angebotsabgabetermin":
					case "Angebot_weitergeleitet_am":
					case "Angebot_zurueck_am":
					case "Angebot_erstellt_am":
					case "Auftrag_erteilt_am":
					case "Fertigstellungstermin":
					$oldv = $v;
					$v = date_de2mysql($v);
					// echo "#".__LINE__." ".$aFitTitles[$i].": <strong>$oldv</strong> -> <strong style=\"color:#00f;\">".$v."</strong><br>\n";
					break;
					
					case "Angebotssumme":
					$v = float_de2mysql($v);
					break;
				}
				if (trim($v) === "") {
					$SQL.= ($num_f++?",":"")."NULL";
				} else {
					$SQL.= ($num_f++?",":"")."\"".MyDB::escape_string(trim($v))."\"";
				}
			} else {
				$t = $aTitles[$i][0]; // Gruppierungs-Prefix: A_, B_, C_, 
				$pe_fld = pentry_colname2MyDB::fldname($aTitles[$i]);
				if ($pe_fld == "Mitarbeiter" || $pe_fld == "ADM") {
					$v = str_replace("+ ", "/ ", $v);
					if (!is_int(strpos($v, "("))) $v = str_replace("/", "/ ", $v);
				}
				$aSubEntries[$k]["values"][$t][$pe_fld] = $v;
				
				
				if ($pe_fld == "Mitarbeiter" && trim($v)) {
					$aV = explode("/ ", $v);
					// $aV = array($v);
					foreach($aV as $mv) {
						$mv = array_shift(explode(" ", trim($mv)));
						$mv = (trim(strtr($mv, array("("=>"",")"=>""," "=>""))));
						if (strlen($mv) < 2) continue;
						if (!isset($aMitarbeiter[$mv])) $aMitarbeiter[$mv] = 1;
						else $aMitarbeiter[$mv]++;
					}
				}
			}
		}
		
		$SQL.= ")";
		$csvToTbl.= "</tr>\n";
		$num_inserts++;
		
		/*
		if (!$aSubEntries[$k]["lfd_nr"]) {
			$csvToTbl.= "<pre>#".__LINE__." aSubEntries: ".print_r($aSubEntries[$k], true)."</pre>\n";
			$csvToTbl.= "<pre>#".__LINE__." aFitTitles: ".print_r($aFitTitles, true)."</pre>\n";
			die("#".__LINE__." UNDEFINED: \$aSubEntries[$k][lfd_nr]!!<br>\n");
		}
		*/
	}
	
	$csvToTbl.= "</tbody>
	</table>
	<input type=\"checkbox\" name=\"cleanProjektTables\" value=\"1\">Projekt- u. Zeiterfassungstabellen vor Import leeren<br>
	<input type='hidden' name='csv_file' value=\"".fb_htmlEntities($csv_file)."\">
	<input type='submit' name='SaveFitNames' value='Feldnamen korrigieren'>
	<input type='submit' name='db_import' value='Daten importieren'>
	</form>";
}

if ($db_import) {
	// Importiere Projektdaten
	MyDB::query($SQL, $connid);
	$msg.= "Es wurden ".MyDB::affected_rows()." Projekt-Datensätze eingefügt!<br>\n";
	if (MyDB::error() || $XD) {
		if (MyDB::error()) $msg.= MyDB::error()."<br>\n";
		$msg.= "<pre>".fb_htmlEntities($SQL)."</pre>\n";
	} else {
		$SQL = "UPDATE `".$_TABLE["projects"]."` SET STATUS = \"Beauftragt\" WHERE `Auftrag_erteilt_am` > \"0000-00-00\"";
		MyDB::query($SQL, $connid);
	}
	
	for ($i = 0; $i < count($aSubEntries); $i++) {
		$IN_LFDNR.= ($i?",":"")."\"".MyDB::escape_string($aSubEntries[$i]["lfd_nr"])."\"";
	}
	
	$SQL = "SELECT pid, lfd_nr FROM `".$_TABLE["projects"]."` WHERE lfd_nr IN ($IN_LFDNR)";
	$r = MyDB::query($SQL, $connid);
	if ($r) {
		$n = MyDB::num_rows($r);
		for ($i = 0; $i < $n; $i++) {
			$_e = MyDB::fetch_array($r, MYSQL_ASSOC);
			$aLfdnr2Pid[$_e["lfd_nr"]] = $_e["pid"];
		}
	} else $msg.= "#".__LINE__." ERROR: ".MyDB::error()."<br>\n";
	
	// Importiere Zeiterfassungen
	$aSubKeys = array("Mitarbeiter","Dauer","Datum");
	$SQL_SUB = "INSERT INTO `".$_TABLE["p_entries"]."`\n";
	$SQL_SUB.= "(pid";
	foreach($aSubKeys as $k) $SQL_SUB.= ", `".$k."`";
	$SQL_SUB.= ")\nVALUES\n";
	$num_loops = 0;
	
	foreach($aSubEntries as $aSub) {
		$r_nr = $aSub["lfd_nr"];
		if (!$aSub["values"]) continue;
		foreach($aSub["values"] as $aVal) {
			if ($aVal["Mitarbeiter"]) {
				$SQL_SUB.= ($num_loops?",\n":"")."(\"".MyDB::escape_string($aLfdnr2Pid[$r_nr])."\"";
				foreach($aSubKeys as $k) {
					$v = (isset($aVal[$k])) ? $aVal[$k] : "";
					if ($XD && $num_loops == 0) $msg.= "SUB: $k : $v <br>\n";
					
					switch($k) {
						case "Dauer":
						$SQL_SUB.= ", \"".MyDB::escape_string(float_de2mysql($v))."\"";
						break;
						
						case "Datum":
						$oldv = $v;
						$newv = date_de2mysql($newv);
						// echo "#".__LINE__." ".$k." <strong>$oldv</strong> -> <strong style=\"color:#00f;\">$newv</strong><br>\n";
						$SQL_SUB.= ", \"".MyDB::escape_string(date_de2mysql($v))."\"";
						break;
						
						default:
						$SQL_SUB.= ", \"".MyDB::escape_string($v)."\"";
					}
				}
				$SQL_SUB.= ")";
				$num_loops++;
			}
		}
	}
	
	if ($num_loops) {
		MyDB::query($SQL_SUB, $connid);
		
		$msg.= "Es wurden ".MyDB::affected_rows()." Zeiterfassungen eingefügt!<br>\n";
		if (MyDB::error() || $XD) {
			if (MyDB::error()) $msg.= MyDB::error()."<br>\n";
			$msg.= "<pre>".fb_htmlEntities($SQL_SUB)."</pre>\n";
		}
	} else {
		$msg.= "Es wurden keine Zeiterfassungen importiert, da keine vorlagen!<br>\n";
	}
	// Importiere Mitarbeiter
	$loopNr = 0;
	$SQL = "INSERT IGNORE INTO `".$_TABLE["user"]."`\n";
	$SQL.= "(
		`user`, 
		`email`, 
		`pw`, 
		`gruppe`, 
		`gid`, 
		`freigegeben`, 
		`anrede`, 
		`nachname`, 
		`authentcode`, 
		`registerdate`, 
		`onlinestatus`, 
		`created`, `modified`)\n";
	$SQL.= "VALUES \n";
	foreach($aMitarbeiter as $mitarbeiter => $count) {
		$mitarbeiter = trim($mitarbeiter);
		if ($loopNr++) $SQL.= ",\n";
		$SQL.= "(\"".MyDB::escape_string($mitarbeiter)."\",";
		$SQL.= "\"".MyDB::escape_string($mitarbeiter."@mertens.ag")."\",";
		$SQL.= "\"".MyDB::escape_string(md5("init"))."\",";
		$SQL.= "\"user\",";
		$SQL.= "\"500\",";
		$SQL.= "\"Ja\",";
		$SQL.= "\"Herr\",";
		$SQL.= "\"".MyDB::escape_string($mitarbeiter)."\",";
		$SQL.= "\"".MyDB::escape_string(md5($mitarbeiter))."\",";
		$SQL.= "NOW(),";
		$SQL.= "\"loggedout\",";
		$SQL.= "NOW(),";
		$SQL.= "NOW())";
	}
	
	if ($loopNr) {
		MyDB::query($SQL, $connid);
		$msg.= "Es wurden ".MyDB::affected_rows()." Mitarbeiter eingefügt!<br>\n";
		if (MyDB::error() || $XD) {
			if (MyDB::error()) $msg.= MyDB::error()."<br>\n";
			$msg.= "<pre>".fb_htmlEntities($SQL_SUB)."</pre>\n";
		}
	} else {
		$msg.= "Es wurden keine Mitarbeiter importiert, da keine Daten vorlagen!<br>\n";
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Untitled</title>
	<style>
	
	table {
		border-collapse:collapse;
		border-color:gray;
		border-left:1px solid gray;
		border-top:1px solid gray;
	}
	table td {
		border-right:1px solid gray;
		border-bottom:1px solid gray;
		padding:2px;
	}
	* {
		font-size:12px;
		font-family:Arial,sans-serif;
	}
	thead td {
		font-weight:bold;
	}
	</style>
	<script src="./js/PageInfo.js" type="text/javascript"></script> 
	<script src="./module/datepicker/DatePicker.js"></script> 
	<link  href="./module/datepicker/DatePicker.css" rel="stylesheet" media="screen"> 
	<link  href="./module/ComboBox/ComboBox.css" rel="stylesheet" media="screen">
	<script src="./module/ComboBox/ComboBox.js"></script> 
</head>

<body>
<?php
if (!empty($aMitarbeiter)) $msg.= "<pre> Mitarbeiter: ".print_r($aMitarbeiter, true)."</pre>\n";
$msg.= "<a href=\"?".$trackVars."&show_csv_files=1\">CSV-Datei auswählen ...</a> oder Neue Datei für Import hochladen<br>\n";
if ($file_liste) $msg.= $file_liste."<br>\n";
$msg.= "Vor dem Import müssen Excel-Dateien als CSV-Dateien exportiert und mit der Dateiendung .csv versehen worden sein!<br>\n";
$msg.= "<form enctype=\"multipart/form-data\" method=\"post\" style=\"margin:0px;display:inline;\">\n";
$msg.= "<input type=\"file\" name=\"$fuploadname\"><input type=\"submit\" value=\"Senden\"><br>\n";
$msg.= "</form>\n";
/**/
echo "<div style=\"border:1px solid gray;padding:5px;color:#000080;\">".$msg."</div>\n";
echo $csvToTbl;
?>


</body>
</html>
<?php exit; ?>
