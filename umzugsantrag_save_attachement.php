<?php 
require_once("../header.php");
require_once($MConf["AppRoot"].$MConf["Inc_Dir"]."php_json.php");

$aid = getRequest("aid","");
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

function save_attachement($aid, $file, $size, $title) {
	global $_TABLE;
	global $db;
	global $user;
	
	$aFileInfo = pathinfo($file);
	
	$sql = "DELETE FROM `".$_TABLE["umzugsanlagen"]."` WHERE `dok_datei` LIKE \"".$db->escape($file)."\"";
	$db->query($sql);
	
	$sql = "INSERT `".$_TABLE["umzugsanlagen"]."` SET \n";
	$sql.= " `aid` = \"".$db->escape($aid)."\",\n";
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
	}
	return false;
}

function save_upload($aid) {
	global $uploadError;
	$uploadError = "";
	$max_size = 1024*1024*25; // 25MB
	$dst_dir = $MConf["AppRoot"]."attachements/";
	$msg = "";
	$saved = false;
	
	$aUploadErrCodes[0] = "";
	$aUploadErrCodes[1] = 'Dateigroesse überschreitet Servervorgaben!';
	$aUploadErrCodes[2] = 'Datei ist zu groß. MAX_FILE_SIZE ('.format_file_size($_POST["MAX_FILE_SIZE"]).') wurde überschritten!';
	$aUploadErrCodes[3] = 'Datei wurde unvollständig übertragen!';
	$aUploadErrCodes[4] = 'Es wurde keine Datei hochgeladen!';
	
	if ($aid) {
		if (isset($_FILES["uploadfile"]) && !$_FILES["uploadfile"]["error"]) {
			$Im = &$_FILES["uploadfile"];
			if ($Im["size"] <= $max_size) {
				$saveas = $dst_dir."aid_".$aid."_".$_FILES['uploadfile']['name'];
				unlink($saveas);
	   			move_uploaded_file($_FILES['uploadfile']['tmp_name'], $saveas);
				
				$dokid = save_attachement($aid, basename($file), $size, $title);
				if (!$dokid) {
					$uploadError = "DB-Fehler: Hochgeladene Datei konnte nicht gespeichert werden!";
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
