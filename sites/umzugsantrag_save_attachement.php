<?php 
require_once("../header.php");

$aid = getRequest("aid","");
if (!isset($int)) $int = getRequest("internal","");
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

function drop_attachement($token, $dokid) {
	global $_TABLE;
	global $db;
	global $user;
	global $MConf;
	global $dropError;
	$dropError = "";
	
	$dir = $MConf["AppRoot"]."attachements".DS;
	
	$sql = "SELECT * FROM `".$_TABLE["umzugsanlagen"]."` WHERE `dokid` LIKE \"".$db->escape($dokid)."\"";
	$row = $db->query_singlerow($sql);
	
	if (!empty($row["dokid"])) {
		if ($row["token"] != $token) {
			$dropError = "Ausgewähltes Attachement ist nicht dem Antrag zugeordnet!<br>\n";
			return false;
		}
		if (!file_exists($dir.$row["dok_datei"])) {
			$dropError = "Datei ".$row["dok_datei"]." existiert nicht!<br>\n";
		}
		@unlink($dir.$row["dok_datei"]);

		$sql = "DELETE FROM `".$_TABLE["umzugsanlagen"]."` WHERE `dokid` LIKE \"".$db->escape($dokid)."\"";
		$db->query($sql);
	
		return true;
	} else {
		$dropError.= "Dateianhang mit der ID:".$dokid." wurde nicht gefunden!<br>\n";
	}
	return false;	
}

function save_attachement($aid, $token, $file, $size, $title, $int = 0) {
	global $_TABLE;
	global $db;
	global $user;
	
	$aFileInfo = pathinfo($file);
	
	$sql = "DELETE FROM `".$_TABLE["umzugsanlagen"]."` WHERE `dok_datei` LIKE \"".$db->escape($file)."\"";
	$db->query($sql);
	
	$sql = "INSERT `".$_TABLE["umzugsanlagen"]."` SET \n";
	if ($aid) {
            $sql.= " `aid` = " . intval($db->escape($aid)) . ",\n";
        }
	$sql.= " `token` = \"".$db->escape($token)."\",\n";
	$sql.= " `oeffentlich` = \"Ja\", \n";
	$sql.= " `typ` = \"Datei\", \n";
	$sql.= " `dok_datei` = \"".$db->escape($file)."\", \n";
	$sql.= " `titel` = \"".$db->escape($title)."\", \n";
	$sql.= " `dok_groesse` = \"".$db->escape($size)."\", \n";
	$sql.= " `dok_type` = \"".$db->escape($aFileInfo["extension"])."\", \n";
	$sql.= " `internal` = \"".$db->escape($int)."\", \n";
	$sql.= " `created` = NOW(), \n";
	$sql.= " `createdby` = \"".$db->escape($user["uid"])."\"";
	$db->query($sql);
	if (!$db->error()) {
		return $db->insert_id();
	}
	echo "#".__LINE__." ".basename(__FILE__)." db->error: ".$db->error()."<br>\nsql:".$sql."<br>\n";
	return false;
}

function fitFileName($file) {
    $p = pathinfo($file);
    $rpl = array(
        'ä' => 'ae',
        'Ä' => 'Ae',
        'ö' => 'oe',
        'Ö' => 'Oe',
        'ü' => 'ue',
        'Ü' => 'Ue',
        'ß' => 'ss',
    );
    return substr(preg_replace('/[^a-zA-Z0-9\._]/', '', strtr($p['filename'],$rpl)), 0, 50).'_'.time().'.'.$p['extension'];
}

function save_upload($aid, $token, $allowOverwrite, $internal = 0) {
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
	$aUploadErrCodes[2] = 'Datei ist zu groß. MAX_FILE_SIZE ('.(getRequest('MAX_FILE_SIZE','') ? format_file_size(getRequest('MAX_FILE_SIZE','')): '').') wurde überschritten!';
	$aUploadErrCodes[3] = 'Datei wurde unvollständig übertragen!';
	$aUploadErrCodes[4] = 'Es wurde keine Datei hochgeladen!';
	
	if ($token) {
		if (isset($_FILES["uploadfile"]) && !$_FILES["uploadfile"]["error"]) {
			$Im = &$_FILES["uploadfile"];
			if ($Im["size"] <= $max_size) {
				$saveas = $dst_dir."token_".$token."_". fitFileName($_FILES['uploadfile']['name']);
                                $title  = $_FILES['uploadfile']['name'];
				if (file_exists($saveas) && !$allowOverwrite) {
					$uploadError = "Fehler: Eine gleichnamige Datei existiert bereits. Nur Administratoren d&uuml;rfen Dateien &uuml;berschreiben!";
					return false;
				}
				@unlink($saveas);
	   			move_uploaded_file($_FILES['uploadfile']['tmp_name'], $saveas);
				
				$dokid = save_attachement($aid, $token, basename($saveas), filesize($saveas), $title, $internal);
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
	return false;
}
