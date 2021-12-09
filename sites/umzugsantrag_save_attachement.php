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

function save_attachement($aid, $token, $file, $size, $title, $internal = 0, $target = '') {
	global $_TABLE;
	global $db;
	global $user;
	
	$aFileInfo = pathinfo($file);
	
	$sql = 'DELETE FROM `' . $_TABLE['umzugsanlagen'] . '` WHERE `dok_datei` LIKE ' . $db::quote($file) . ' ';
	$db->query($sql);
	
	$sql = "INSERT `".$_TABLE['umzugsanlagen']."` SET \n";
	if ($aid) {
            $sql.= ' `aid` = ' . (int)$aid . ",\n";
        }
	$sql.= ' `token` = ' . $db::quote($token) . ",\n";
	$sql.= " `oeffentlich` = \"Ja\", \n";
	$sql.= " `typ` = \"Datei\", \n";
	$sql.= ' `dok_datei` = ' . $db::quote($file) . ", \n";
    $sql.= ' `titel` = ' . $db::quote($title) . ", \n";
    $sql.= ' `target` = ' . $db::quote($target) . ", \n";
	$sql.= ' `dok_groesse` = ' . $db::quote($size) . ", \n";
	$sql.= ' `dok_type` = ' . $db::quote($aFileInfo['extension']) . ", \n";
	$sql.= ' `internal` = ' . $db::quote($internal) . ", \n";
	$sql.= " `created` = NOW(), \n";
	$sql.= ' `createdby` = ' . $db::quote($user['uid']) . '';
	$db->query($sql);
	if (!$db->error()) {
		return $db->insert_id();
	}
	echo '#' . __LINE__ . ' ' . basename(__FILE__) . ' db->error: '.$db->error() . "<br>\nsql:".$sql."<br>\n";
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

function save_lieferschein($aid, $file, array $opts = []) {

    $re = [
        'success' => false,
        'lid' => 0,
        'error' => '',
        'msg' => ''
    ];

    $lsmodel = new LS_Model((int)$aid);
    $auftrag = $lsmodel->getAuftragsdaten();

    $input = [
        'aid' => $aid,
        'lieferdatum' => $opts['lieferdatum'] ?? $auftrag['umzugstermin'],
        'source' => 'fileupload',
        'umzuege_anlagen_dokid' => $opts['dokid'] ?? 0,
        'leistungen' => !empty($opts['leistungen']) ? json_encode($opts['leistungen']) : null,
        'uebergeben_an' => $opts['uebergeben_an'] ?? '',
        'tracking_id' => $opts['tracking_id'] ?? '',
        'tracking_link' => $opts['tracking_link'] ?? '',
    ];
    $re['lid'] = $lsmodel->save($input);

    $lsPdf = file_get_contents($file);

    if (empty($lsPdf) || strlen($lsPdf) < 10) {
        $re['error'] = 'Lieferschein enthält keine Daten!';
    }
    elseif (!$lsmodel->updateLieferscheinPdf($lsPdf)) {
        $re['error'] = 'Lieferschein konnte nicht in Datenbank gespeichert werden!';
    } else {
        $re['success'] = true;
    }

    return $re;
}

function check_upload($name = 'uploadfile', array $opts = []) {
    global $uploadError;

    $re = [
        'success' => false,
        'file' => '',
        'error' => '',
        'upload' => null
    ];

    $uploadError = '';
    if (!empty($opts['max_file_size']) && is_numberic($opts['max_file_size'])) {
        $max_size = (int)$opts['max_file_size'];
    } else {
        $max_size_default = 1024 * 1024 * 12; // 12MB
        $max_size = getRequest('MAX_FILE_SIZE', $max_size_default); // 12MB
    }

    $upload = $_FILES[$name] ?? null;

    $aUploadErrCodes[0] = '';
    $aUploadErrCodes[1] = 'Dateigroesse überschreitet Servervorgaben!';
    $aUploadErrCodes[2] = 'Datei ist zu groß. MAX_FILE_SIZE (' . format_file_size($max_size) . ') wurde überschritten!';
    $aUploadErrCodes[3] = 'Datei wurde unvollständig übertragen!';
    $aUploadErrCodes[4] = 'Es wurde keine Datei hochgeladen!';

    if (!empty($upload) && empty($upload["error"])) {
        if (empty($upload['size'])) {
            $re['error'] = 'Hochgeladene Datei ist leer: ' . format_file_size($max_size) . '!';
        }
        elseif ($upload['size'] > $max_size) {
            $re['error'] = 'Hochgeladene Datei ist zu groß! Max. Dateigröße beträgt ' . format_file_size($max_size) . '.';
        } else {
            $re['success'] = true;
            $re['file'] = $upload['tmp_name'];
            $re['upload'] = $upload;
        }
    } elseif (!empty($upload["error"])) {
        $re['error'] = $aUploadErrCodes[$upload['error']];
    } else {
        $re['error'] = 'Es wurde keine Datei hochgeladen!';
    }
    $uploadError = $re['error'];
    return $re;
}

function save_checkedUploadFile($aid, $uploadCheck, $token, $allowOverwrite, $internal = 0, $title = '') {
    global $MConf;

    $re = [
        'success' => false,
        'error' => '',
        'savedas' => '',
        'dokid' => 0,
    ];

    if (empty($token)) {
        $re['error'] = 'Unauthorisierter Upload!';
        return false;
    }

    $dst_dir = $MConf['AppRoot'] . 'attachements' . DS;
    $title = $title ?: getRequest('titel',"");
    $target = getRequest('target','');
    $Im = $uploadCheck['upload'];
    $file = $uploadCheck['file'];

    $saveas = $dst_dir . 'token_' . $token . '_' . fitFileName($Im['name']);
    $title  = $title ?: $Im['name'];

    if (!$allowOverwrite && file_exists($saveas) ) {
        $re['error'] = 'Fehler: Eine gleichnamige Datei existiert bereits. Nur Administratoren d&uuml;rfen Dateien &uuml;berschreiben!';
        return $re;
    }

    @unlink($saveas);
    if (move_uploaded_file($file, $saveas)) {
        $re['savedas'] = $saveas;
    } else {
        $re['error'] = 'Upload-Datei konnte nicht importiert werden!';
        return $re;
    }

    $re['dokid'] = save_attachement($aid, $token, basename($saveas), filesize($saveas), $title, $internal, $target);

    if (!$re['dokid']) {
        $re['error'] = 'DB-Fehler: Hochgeladene Datei konnte nicht gespeichert werden!';
        @unlink($saveas);
    } else {
        $re['success'] = true;
    }

    return $re;
}

function save_upload($aid, $token, $allowOverwrite, $internal = 0, $title = '') {
	global $uploadError;
	global $MConf;

	if (empty($token)) {
        $uploadError = 'Unauthorisierter Upload!';
        return false;
    }

	$check = check_upload('uploadfile');
	if (!$check['success']) {
	    $uploadError = $check['error'];
	    return false;
    }
	
	$uploadError = '';
	$dst_dir = $MConf['AppRoot'] . 'attachements' . DS;
    $title = $title ?: getRequest('titel',"");
    $target = getRequest('target',"");

    $Im = &$check['upload'];

    $saveas = $dst_dir . 'token_' . $token . '_' . fitFileName($Im['name']);
    $title  = $title ?: $Im['name'];

    if (!$allowOverwrite && file_exists($saveas) ) {
        $uploadError = 'Fehler: Eine gleichnamige Datei existiert bereits. Nur Administratoren d&uuml;rfen Dateien &uuml;berschreiben!';
        return false;
    }
    @unlink($saveas);
    move_uploaded_file($Im['tmp_name'], $saveas);

    $dokid = save_attachement($aid, $token, basename($saveas), filesize($saveas), $title, $internal, $target);

    if (!$dokid) {
        $uploadError = 'DB-Fehler: Hochgeladene Datei konnte nicht gespeichert werden!';
        return false;
    } else {
        return $dokid;
    }
}
