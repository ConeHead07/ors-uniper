<?php 
require_once dirname(__FILE__)."/../../include/conf.php";
require_once $MConf["AppRoot"].$MConf["Class_Dir"]."ConfEditor.class.php";
require_once $MConf["AppRoot"].$MConf["Class_Dir"]."dbconn.class.php";
require_once $MConf["AppRoot"].$MConf["Class_Dir"]."CsvXls2Array.class.php";
require_once $MConf["AppRoot"].$MConf["Inc_Dir"]."registered_data.inc.php";


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
$importTblKey = '';
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
	file_put_contents(__DIR__ ."/mapCsvDst.$importTblKey.phs", serialize($mapCsvDst));
}
$csv_file = (isset($_POST["csv_file"])) ? $_POST["csv_file"] : (isset($_GET["csv_file"]) ? $_GET["csv_file"] : "");
$del_file = (isset($_POST["del_file"])) ? $_POST["del_file"] : (isset($_GET["del_file"]) ? $_GET["del_file"] : "");

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
			$msg.= "#".__LINE__." Ungültige Dateierweiterung: ".strtolower(implode(".",array_slice($t,-1)))."<br>\n";
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

$show_csv_files = (!empty($_GET["show_csv_files"]) || !$csv_file || !file_exists($csv_dir . $csv_file)) ? true : false;

if ($del_file) {
	if (file_exists($csv_dir."/".$del_file)) {
		if (unlink($csv_dir."/".$del_file)) {
		    $msg.= "Datei <strong>$del_file</strong> wurde gelöscht!<br>\n";
        }
		else {
		    $msg.= "Datei <strong>$del_file</strong> konnte <strong>nicht</strong> gelöscht werden!<br>\n";
        }
	} else {
	    $msg.= "Datei <strong>$del_file</strong> existiert <strong>nicht</strong>!<br>\n";
    }
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

if (isset($import) && $csv_file) {
	
	if (!$error) {
		$Csv = new CsvXls2Array();
		$Csv->parse_xls_file($csv_dir.$csv_file);
		
		$offset = $firstRowIsTitle?1:0;
		$NewCSV = array();
		die( print_r($Csv->DATA, 1));
                $lastTitle = '';
		$insertRows = "";
		$iCountCsvData = count($Csv->DATA);
		for ($i = $offset; $i < $iCountCsvData; $i++) {
			$row = $Csv->DATA[$i];
                        $variante = '';
                        if ( trim($row[0]) && '' == trim($row[1] . $row[2])) {
                            $lastTitle = $row[0];
                            continue;
                        }
                        
                        if ( 0 === strpos($row[0], 'Arbeitsvariante ')) {
                            $variante = substr($row[0], 0, 17);
                            $row[0] = substr($row[0], 17);
                        }
                        
                        $row[3] = $lastTitle;
                        $row[4] = $variante;
                        
                        $NewCSV[] = $row;
		}
		die( print_r($NewCSV, 1));
	}
}

//echo "#".__LINE__." ".basename(__FILE__)." <strong>import:</strong>$import, <strong>error:</strong>$error, <strong>importTblKey:</strong>$importTblKey, <strong>csv_dir:</strong>$csv_dir, <strong>csv_file:</strong>$csv_file, <strong>file_exists():</strong>".file_exists($csv_dir.$csv_file)."<br>\n";
if ((empty($import) || $error) && $importTblKey && $csv_file && file_exists($csv_dir.$csv_file)) {
	//echo "#".__LINE__." ".basename(__FILE__)." csv_file:$csv_file<br>\n";
	if (empty($mapCsvDst) && file_exists(__DIR__ . "/mapCsvDst.$importTblKey.phs")) {
		$mapCsvDst = unserialize(file_get_contents(dirname(__FILE__) . "/mapCsvDst.$importTblKey.phs"));
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
	
	$sMapTable.= "<input type=\"hidden\" name=\"saveMapTable\" value=0>\n";
	$sMapTable.= "<input type=\"hidden\" name=\"cleanImportTable\" value=0>\n";
	$sMapTable.= "<input type=\"submit\" name=\"import\" value=\"prepare\"><br>\n";
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
$msg.= "<a href=\"?".$trackVars."&show_csv_files=1\" onclick=\"ChgD('csvList'); return false;\">Vorhandene CSV-Datei aus Liste auswählen ...</a><br>\n";

if ($file_liste) {
    $msg.= "<div id=\"csvList\" style=\"display:".($show_csv_files?"":"none")."\">$file_liste</div><br>\n";
}

$msg.= "Neue Datei für Import hochladen<br>\n";
$msg.= "<input type=\"file\" name=\"$fuploadname\"><br>\n";
$msg.= "<input type=\"hidden\" name=\"ximport\" value=\"1\">";
$msg.= "<input type=\"submit\" value=\"Weiter\">\n";
$msg.= "</form>\n";

echo "<div style=\"border:1px solid gray;padding:5px;color:#000080;\">".$msg."</div>\n";
if (isset($sMapTable)) {
    echo $sMapTable;
}
echo $csvToTbl;
?>


</body>
</html>
<?php exit;
