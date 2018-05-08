<?php 
require_once("../header.php");

$nid = getRequest("nid","");
$titel = getRequest("titel","");

if (!function_exists("format_file_size")) {
function format_file_size($bytes) {
	if ($bytes > (1024*1024*1024)) {
		return round($bytes / (1024*1024*1024), 1)." GB";
	} elseif ($bytes > (1024*1024)) {
		return round($bytes / (1024*1024), 1)." MB";
	} elseif ($bytes > (1024)) {
		return round($bytes / (1024), 1)." KB";
	} else return $bytes." Bytes";
}}

function nl_drop_attachement($nid, $dokid) {
	global $_TABLE;
	global $db;
	global $user;
	global $MConf;
	global $dropError;
	$dropError = "";
	
	$dir = $MConf["AppRoot"]."attachements".DS;
	
	$sql = "SELECT * FROM `".$_TABLE["nebenleistungsanlagen"]."` WHERE `dokid` LIKE \"".$db->escape($dokid)."\"";
	$row = $db->query_singlerow($sql);
	
	if (!empty($row["dokid"])) {
		if ($row["nid"] != $nid) {
			$dropError = "Ausgewähltes Attachement ist nicht dem Antrag zugeordnet!<br>\n";
			return false;
		}
		if (!file_exists($dir.$row["dok_datei"])) {
			$dropError = "Datei ".$row["dok_datei"]." existiert nicht!<br>\n";
		}
		@unlink($dir.$row["dok_datei"]);

		$sql = "DELETE FROM `".$_TABLE["nebenleistungsanlagen"]."` WHERE `dokid` LIKE \"".$db->escape($dokid)."\"";
		$db->query($sql);
	
		return true;
	} else {
		$dropError.= "Dateianhang mit der ID:".$dokid." wurde nicht gefunden!<br>\n";
	}
	return false;	
}

function nl_save_attachement($nid, $file, $size, $title) {
	global $_TABLE;
	global $db;
	global $user;
	
	$aFileInfo = pathinfo($file);
	
	$sql = "DELETE FROM `".$_TABLE["nebenleistungsanlagen"]."` WHERE `dok_datei` LIKE \"".$db->escape($file)."\"";
	$db->query($sql);
	
	$sql = "INSERT `".$_TABLE["nebenleistungsanlagen"]."` SET \n";
	$sql.= " `nid` = \"".$db->escape($nid)."\",\n";
	$sql.= " `oeffentlich` = \"Ja\", \n";
	$sql.= " `typ` = \"Datei\", \n";
	$sql.= " `dok_datei` = \"".$db->escape($file)."\", \n";
	$sql.= " `titel` = \"".$db->escape($title)."\", \n";
	$sql.= " `dok_groesse` = \"".$db->escape($size)."\", \n";
	$sql.= " `dok_type` = \"".$db->escape($aFileInfo["extension"])."\", \n";
	$sql.= " `created` = NOW(), \n";
	$sql.= " `createdby` = \"".$db->escape($user["uid"])."\"";
	$db->query($sql);
	if (!$db->error()) {
		return $db->insert_id();
	} else {
		//echo "#".__LINE__." ".basename(__FILE__)." db->error:".$db->error()."<br>\nsql:".$sql."<br>\n";
	}
	return false;
}

function nl_save_upload($nid, $allowOverwrite) {
	global $uploadError;
	global $MConf;
	global $db;
	
	$uploadError = "";
	$max_size = 1024*1024*12; // 12MB
	$dst_dir = $MConf["AppRoot"]."attachements".DS;
	$msg = "";
	$saved = false;
	$title = getRequest("titel","");
	
	$aUploadErrCodes[0] = "";
	$aUploadErrCodes[1] = 'Dateigroesse überschreitet Servervorgaben!';
	$aUploadErrCodes[2] = 'Datei ist zu groß. MAX_FILE_SIZE ('.format_file_size($_POST["MAX_FILE_SIZE"]).') wurde überschritten!';
	$aUploadErrCodes[3] = 'Datei wurde unvollständig übertragen!';
	$aUploadErrCodes[4] = 'Es wurde keine Datei hochgeladen!';
	
	if ($nid) {
		if (isset($_FILES["uploadfile"]) && !$_FILES["uploadfile"]["error"]) {
			$Im = &$_FILES["uploadfile"];
			if ($Im["size"] <= $max_size) {
				$saveas = $dst_dir."nid_".$nid."_".$_FILES['uploadfile']['name'];
				if (file_exists($saveas) && !$allowOverwrite) {
					$uploadError = "Fehler: Eine gleichnamige Datei existiert bereits. Nur Administratoren d&uuml;rfen Dateien &uuml;berschreiben!";
					return false;
				}
				@unlink($saveas);
	   			move_uploaded_file($_FILES['uploadfile']['tmp_name'], $saveas);
				
				$dokid = nl_save_attachement($nid, basename($saveas), filesize($saveas), $title);
				if (!$dokid) {
					$uploadError = "DB-Fehler: Hochgeladene Datei konnte nicht gespeichert werden!<br>\n".$db->error()."<br>\n";
				} else {
					return $dokid;
				}
			} else {
				$uploadError = "Hochgeladene Datei ist zu groß!\\nBitte nicht groesser als ".format_file_size($max_size).".";
			}
		} else {
			if ($_FILES["uploadfile"]["error"]) $uploadError.= $aUploadErrCodes[$_FILES["uploadfile"]["error"]];
		}
	} else {
		$uploadError = "Unauthorisierter Upload!";
	}
}
?>