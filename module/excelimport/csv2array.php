<?php 
require_once dirname(__FILE__)."/../../include/conf.php";
require_once $MConf["AppRoot"].$MConf["Class_Dir"]."ConfEditor.class.php";
require_once $MConf["AppRoot"].$MConf["Class_Dir"]."dbconn.class.php";
require_once $MConf["AppRoot"].$MConf["Class_Dir"]."CsvXls2Array.class.php";
require_once $MConf["AppRoot"].$MConf["Inc_Dir"]."registered_data.inc.php";

function loadTblConf($tbl_conf_file) {
	$CE = new ConfEditor($tbl_conf_file);
	$CE->load_conf();
	return $CE->aCnfData;
}

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

function get_max_lfd_nr($table, $key) {
	global $_TABLE;
	global $connid;
	
	$max_lfd_nr = 0;
	$SQL = "SELECT MAX($key) FROM `".$table."`";
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
$importTblKey = (!empty($_REQUEST["importTblKey"]) ? $_REQUEST["importTblKey"] : "");
$trackVars = "s=".(!empty($_GET["s"]) ? urldecode($_GET["s"]) : "csvimport");
$db_import = (isset($_POST["db_import"]));
$fuploadname = "csvfile";
$csvToTbl = "";
$data_cnt = 0;
$line_cnt = 0;

$csv_dir = $MConf["AppRoot"]."import_csv/";
$import = (isset($_POST["import"])) ? $_POST["import"] : (isset($_GET["import"]) ? $_GET["import"] : "");

$firstRowIsTitle = (isset($_POST["firstRowIsTitle"])) ? $_POST["firstRowIsTitle"] : (isset($_GET["firstRowIsTitle"]) ? $_GET["firstRowIsTitle"] : "");
$mapCsvDst = (isset($_POST["mapCsvDst"])) ? $_POST["mapCsvDst"] : (isset($_GET["mapCsvDst"]) ? $_GET["mapCsvDst"] : "");
if ($mapCsvDst && $importTblKey) {
	file_put_contents(dirname(__FILE__)."/mapCsvDst.$importTblKey.phs", serialize($mapCsvDst));
}
$csv_file = (isset($_POST["csv_file"])) ? $_POST["csv_file"] : (isset($_GET["csv_file"]) ? $_GET["csv_file"] : "");
$del_file = (isset($_POST["del_file"])) ? $_POST["del_file"] : (isset($_GET["del_file"]) ? $_GET["del_file"] : "");
$cleanImportTable = (isset($_POST["cleanImportTable"])) ? $_POST["cleanImportTable"] : (isset($_GET["cleanImportTable"]) ? $_GET["cleanImportTable"] : "");

$aTitles = array();
$aEntries = array();
$aSubEntries = array();
$aFldMaxLength = array();
$aFldMinLength = array();
$max_lfdnr = 0; //get_max_lfd_nr();
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

$dp = opendir($csv_dir);
if ($dp) {
	$file_liste.= "<table cellpadding=1 cellspacing=0>\n";
	$fi = 0;
	while($file = readdir($dp)) {
		if (!is_dir($csv_dir.$file) && is_int(strpos(".txt/.csv/.xls", substr($file,-4)))) {
			$fi++;
			list($fdt,$fs) = explode("|", format_fstat($csv_dir.$file));
			$file_liste.= "
			<tr>
				<td>".$fdt."</td>
				<td align=right>".$fs."</td>
				<td>"."<input type=\"radio\" id=\"f{$fi}\" name=\"csv_file\" value=\"".fb_htmlEntities($file)."\"><span onclick=\"O('f{$fi}').click()\" style=\"cursor:pointer;\">".$file." </span> </td>
				<td>"."<a href=\"".$csv_dir.$file."\" target=_blank style=\"color:#00f;\">Anzeigen </a> </td>
				<td>"."<a href=\"?".$trackVars."&show_csv_files=1&del_file=".urlencode($file)."\" style=\"color:#f00;\">L�schen </a> </td>
			</tr>\n";
			$aCsvFiles[] = $file;
		}
	}
	$file_liste.= "</table>";
	closedir($dp);
}

//echo "#".__LINE__." ".basename(__FILE__)." csv_file:$csv_file<br>\n";
//echo "#".__LINE__." ".basename(__FILE__)." del_file:$del_file<br>\n";
//echo "#".__LINE__." ".basename(__FILE__)." import: $import<br>\n";
//echo "#".__LINE__." ".basename(__FILE__)." db_import: $db_import<br>\n";
//echo "#".__LINE__." ".basename(__FILE__)." importTblKey: $importTblKey<br>\n";

if (isset($import) && !empty($mapCsvDst)) {
	//echo "#".__LINE__." ".basename(__FILE__)."<br>\n";
	$aChckDstFld = array();
	foreach($mapCsvDst as $k => $v) {
		if ($v) {
			if (isset($aChckDstFld[$v])) {
				$error.= "Fehler! Doppelte Feldzuweisung f�r Feld $v!<br>\n";
				$aChckDstFld[$v]++;
			} else $aChckDstFld[$v] = 1;
		}
	}
	if (!count($aChckDstFld)) $error.= "Es wurden keine Felder f�r den Import zugewiesen!<br>\n";
	
	if (!$error) {
		$Csv = new CsvXls2Array();
		$Csv->parse_xls_file($csv_dir.$csv_file);
		$tbl_conf_file = $MConf["AppRoot"].$MConf["Inc_Dir"].$ConfRegData[$importTblKey];
		$tblConf = loadTblConf($tbl_conf_file);
		//echo "#".__LINE__." ".basename(__FILE__)."<pre>tblConf:\n".print_r($tblConf,1)."</pre><br>\n";
		//echo "#".__LINE__." ".basename(__FILE__)."<pre>mapCsvDst:\n".print_r($mapCsvDst,1)."</pre><br>\n";
		
		$offset = $firstRowIsTitle?1:0;
		
		$insertCols = "";
		$j = 0;
		foreach($mapCsvDst as $k => $fKey) {
			if ($fKey && isset($tblConf["Fields"][$fKey])) {
				$insertCols.= ($j?", ":"")."`".$tblConf["Fields"][$fKey]["dbField"]."`";
				$j++;
			}
		}
		
		$insertRows = "";
		for ($i = $offset; $i < count($Csv->DATA); $i++) {
			$insertRows.= ($insertRows?",\n":"")."(";
			$j = 0;
			foreach($mapCsvDst as $k => $fKey) {
				if ($fKey && isset($tblConf["Fields"][$fKey])) {
					$insertRows.= ($j?", ":"")."\"".$db->escape($Csv->DATA[$i][$k])."\"";
					$j++;
				}
			}
			$insertRows.= ")";
		}
		
		$SQL = "INSERT INTO `".$tblConf["Table"]."` ($insertCols)\n";
		$SQL.= "VALUES\n";
		$SQL.= $insertRows;
		
		if ($cleanImportTable) {
			$table_backup_file = $csv_dir.$tblConf["Table"]."_BACKUP_".date("Ymd").".csv";
			$csvTblBackup = $db->query_export_csv("SELECT * FROM `".$tblConf["Table"]."`", $table_backup_file);
			
			$db->query("TRUNCATE TABLE `".$tblConf["Table"]."`");
			$num_del = $db->affected_rows();
			$msg.= "F�r den Import wurde die Tabelle `".$tblConf["Table"]."` geleert. $num_del Datens�tze!<br>\n";
			if ($csvTblBackup) $msg.= "Zuvor wurde ein Backup, dass �ber das csv-Verzeichnis wieder eingespielt werden kann!<br>\n";
		}
		$db->query($SQL);
		if ($db->error()) $error.= $db->error() . '<pre>' . $SQL . '</pre>' . PHP_EOL;
		// echo "#".__LINE__." ".basename(__FILE__)."<pre>".fb_htmlEntities($SQL)."</pre><br>\n";
	}
}

//echo "#".__LINE__." ".basename(__FILE__)." <strong>import:</strong>$import, <strong>error:</strong>$error, <strong>importTblKey:</strong>$importTblKey, <strong>csv_dir:</strong>$csv_dir, <strong>csv_file:</strong>$csv_file, <strong>file_exists():</strong>".file_exists($csv_dir.$csv_file)."<br>\n";
if ((empty($import) || $error) && $importTblKey && $csv_file && file_exists($csv_dir.$csv_file)) {
	//echo "#".__LINE__." ".basename(__FILE__)." csv_file:$csv_file<br>\n";
	if (empty($mapCsvDst) && file_exists(dirname(__FILE__)."/mapCsvDst.$importTblKey.phs")) {
		$mapCsvDst = unserialize(file_get_contents(dirname(__FILE__)."/mapCsvDst.$importTblKey.phs"));
	}
	$max_rows = 10;
	$return_rows = true;
	$Csv = new CsvXls2Array();
	$Csv->parse_xls_file($csv_dir.$csv_file, $max_rows);
	$csvToTbl = $Csv->show_csv_table($return_rows, 0, $max_rows);
	$tbl_conf_file = $MConf["AppRoot"].$MConf["Inc_Dir"].$ConfRegData[$importTblKey];
	$tblConf = loadTblConf($tbl_conf_file);
	//echo "#".__LINE__." ".basename(__FILE__)."<pre>$tbl_conf_file:\n".print_r($tblConf,1)."</pre><br>\n";
	$options_dst_flds = "<option value=\"\">Nicht importieren</option>";
	foreach($tblConf["Fields"] as $fKey => $fProps) {
		$options_dst_flds.= "<option value=\"$fKey\" chck=\"$fKey\">".$fProps["label"]."</option>\n";
	}
	
	$firstRowIsTitle = 1;
	$sMapTable = "Feldzuordnung:<br>\n";
	$sMapTable.= "<form action=\"".basename($_SERVER["PHP_SELF"])."?s=$s\" method=\"post\">\n";
	$sMapTable.= "<table>\n";
	$sMapTable.= "<input type=\"hidden\" name=\"s\" value=\"".fb_htmlEntities($s)."\">\n";
	$sMapTable.= "<input type=\"hidden\" name=\"importTblKey\" value=\"".fb_htmlEntities($importTblKey)."\">\n";
	$sMapTable.= "<input type=\"hidden\" name=\"csv_file\" value=\"".fb_htmlEntities($csv_file)."\">\n";
	$sMapTable.= "<input type=\"hidden\" name=\"firstRowIsTitle\" value=\"".($firstRowIsTitle?1:0)."\">\n";
	$sMapTable.= "<thead><tr><td>Csv-Feld</td><td>Importieren nach</td></tr></thead>\n";
	
	//echo "#".__LINE__." ".basename(__FILE__)."<pre>mapCsvDst:\n".print_r($mapCsvDst,1)."</pre><br>\n";
	// Formular: Feldzuordnungs-Auswahl
	for($i = 0; $i < count($Csv->DATA[0]); $i++) {
		
		$sMapTable.= "<tr><td>";
		$sMapTable.= ($i+1).". ".($firstRowIsTitle ? $Csv->DATA[0][$i] : "Feld");
		$sMapTable.= "</td>\n<td>";
		$sMapTable.= "<select name=\"mapCsvDst[$i]\">\n";
		$sMapTable.= (empty($mapCsvDst[$i]) ? $options_dst_flds : str_replace("chck=\"".$mapCsvDst[$i]."\"", "selected=\"true\"", $options_dst_flds));
		$sMapTable.= "</select>\n";
		$sMapTable.= "</td></tr>\n";
	}
	$sMapTable.= "</table>\n";
	$sMapTable.= "<input type=\"checkbox\" name=\"saveMapTable\">Zuordnung f�r Tabelle $importTblKey speichern<br>\n";
	$sMapTable.= "<input type=\"checkbox\" name=\"cleanImportTable\">Tabelle vor Import leeren<br>\n";
	$sMapTable.= "<input type=\"submit\" name=\"import\" value=\"importieren\"><br>\n";
	$sMapTable.= "</form>\n";
}

if ($db_import) {

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
	<script src="./js/PageInfo.js?%assetsRefreshId%" type="text/javascript"></script>
	<script src="./js/GetObjectDisplay.js?%assetsRefreshId%" type="text/javascript"></script>
	<script src="./module/datepicker/DatePicker.js?%assetsRefreshId%"></script>
	<link  href="./module/datepicker/DatePicker.css?%assetsRefreshId%" rel="stylesheet" media="screen">
	<link  href="./module/ComboBox/ComboBox.css?%assetsRefreshId%" rel="stylesheet" media="screen">
	<script src="./module/ComboBox/ComboBox.js?%assetsRefreshId%"></script>
</head>

<body>
<?php
if ($error) echo "<div style=\"border:1px solid #f00;padding:5px;color:#000080;\">".$error."</div>\n";
$msg.= "<form enctype=\"multipart/form-data\" method=\"post\" style=\"margin:0px;display:inline;\">\n";
$msg.= "<a href=\"?".$trackVars."&show_csv_files=1\" onclick=\"ChgD('csvList'); return false;\">Vorhandene CSV-Datei aus Liste ausw�hlen ...</a><br>\n";
if ($file_liste) $msg.= "<div id=\"csvList\" style=\"display:".($show_csv_files?"":"none")."\">$file_liste</div><br>\n";
$msg.= "Neue Datei f�r Import hochladen<br>\n";
$msg.= "<input type=\"file\" name=\"$fuploadname\"><br>\n";
$msg.= "Vor dem Import m�ssen Excel-Dateien als CSV-Dateien exportiert und mit der Dateiendung .csv versehen worden sein!<br>\n";
$msg.= "<br>\n";
$msg.= "Importieren in:<br>\n";
$msg.= "<select name=\"importTblKey\">\n";
foreach($ConfRegData as $k => $f) {
	$msg.= "<option value=\"$k\">$k</option>\n";
}
$msg.= "</select><br>\n";
$msg.= "<input type=\"hidden\" name=\"ximport\" value=\"1\">";
$msg.= "<input type=\"submit\" value=\"Weiter\">\n";
$msg.= "</form>\n";
/**/
echo "<div style=\"border:1px solid gray;padding:5px;color:#000080;\">".$msg."</div>\n";
if (isset($sMapTable)) echo $sMapTable;
echo $csvToTbl;
?>


</body>
</html>
<?php exit; ?>
