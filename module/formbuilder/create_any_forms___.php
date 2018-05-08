<?php
$default_db = $MConf["DB_Name"];
$db_only = $MConf["DB_Name"];
$db_host = $MConf["DB_Host"];
$db_user = $MConf["DB_User"];
$db_pass = $MConf["DB_Pass"];
$dirLib = "./include/";
$dirTpl = "./html/";
$dirBase = "./";
$dir_only = $dirBase;
if (!isset($tblPrefix)) $tblPrefix = "";
$modul = "formbuilder";

$modul_dir = basename(dirname(__FILE__));
$_rplAusgabe[0]["<!-- {Headers} -->"].= "<!-- Create Forms -->\n
<link rel=\"stylesheet\" href=\"".$_CONF["WebRoot"].$_CONF["Modul_Dir"].$modul_dir."/css/default.css\" media=\"screen\" title=\"CreateForms\">
<script src=\"".$_CONF["WebRoot"].$_CONF["Modul_Dir"].$modul_dir."/js/GetObjectDisplay.js\"></script>\n";

if (basename($_SERVER["PHP_SELF"]) == basename(__FILE__)) {
//include_once($InclBaseDir."conn.php");
//include_once($ClassBaseDir."formcreator.class.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Create Form</title>
	<style>
	body, * { font-family:Arial,sans-serif; font-size:12px; }
	h1 { font-size:15px; margin-bottom:10px; }
	</style>
</head>
<body>
<h1>Create Form</h1>
<?php // ><style>/*
} else {
	// include_once($ClassBaseDir."formcreator.class.php");
}
$output_caf = "";
$db_conn = $connid;
$addVars = "s=$s&";
$formAction = basename($_SERVER["PHP_SELF"])."?".$addVars;

function dbconn($db_host, $db_user, $db_pass, &$db_conn) {
	
	if (!is_resource($db_conn)) $db_conn = MyDB::connect($db_host, $db_user, $db_pass);
	return is_resource($db_conn);
}

function queryResultFirstColToArr($SQL, $db_conn, &$err) {
	global $error;
	// echo "#".__LINE__." r:$r, SQL:".fb_htmlEntities($SQL)."<br>\n";
	if (!is_resource($db_conn)) {
		$error.= "Ungültiger DB-Conn-Handler \$db_conn:$db_conn!<br>\n";
		return false;
	}
	$reArr = array();
	$r = MyDB::query($SQL, $db_conn);
	// echo "#".__LINE__." r:$r, SQL:".fb_htmlEntities($SQL)."<br>\n";
	if ($r) {
		$n = MyDB::num_rows($r);
		for ($i = 0; $i < $n; $i++) {
			$e = MyDB::fetch_array($r, MYSQL_NUM);
			$reArr[] = $e[0];
		}
		MyDB::free_result($r);
	} else {
		$err = "<pre>#".__LINE__." ERROR: ".MyDB::error()."\nQUERY:".fb_htmlEntities($SQL)."\n</pre>\n";
		$error.= $err;
	}
	return $reArr;
}

function getFormSelectByArr($inputName, $inputArray, $optionValByKey = false, $multiple = false, $default = "", $emptyTitle = "Auswahl") {
	$html = "<select name=\"{$inputName}\" ".($multiple ? "multiple=\"true\"" : "").">\n";
	$html.= "<option>{$emptyTitle}</option>\n";
	
	foreach($inputArray as $k => $v) {
		if ($optionValByKey) {
			$html.= "<option value=\"".fb_htmlEntities($k)."\" ".($default == $k ? "selected=\"true\"":"").">".fb_htmlEntities($v)."</option>\n";
		} else {
			$html.= "<option value=\"".fb_htmlEntities($v)."\" ".($default == $v ? "selected=\"true\"":"").">".fb_htmlEntities($v)."</option>\n";
		}
	}
	$html.= "</select>\n";
	return $html;
}

function getFormSelectBySQL($inputName, $SQL, $db_conn, $optionValByKey = false, $multiple = false, $default = "", $emptyTitle = "Auswahl") {
	global $error;
	$inputArray = queryResultFirstColToArr($SQL, $db_conn, $err);
	// echo "#".__LINE__." print_r(\$inputArray):".print_r($inputArray, true)." <br>\n";
	if ($inputArray) return getFormSelectByArr($inputName, $inputArray, $optionValByKey, $multiple, $default, $emptyTitle);
	else { $error.= $err; return ""; }
}

function getConfDataByCFile($cfile) {
	global $error;
	global $InclBaseDir;
	global $db_name;
	global $tbl_prefix;
	global $_TABLE;
	
	if (file_exists($InclBaseDir.$cfile)) {
		if (file_exists($InclBaseDir.str_replace(".inc.php",".fnc.php",$cfile))) {
			include_once($InclBaseDir.str_replace(".inc.php",".fnc.php",$cfile));
		}
		include($InclBaseDir.$cfile);
		$cname = key($_CONF);
		
		return array(
			"Title" => $_CONF[$cname]["Title"],
			"Description" => $_CONF[$cname]["Description"],
			"Db" => $_CONF[$cname]["Db"],
			"Table" => $_CONF[$cname]["Table"],
			"Fields"=> count($_CONF[$cname]["Table"])
		);
	} else {
		$error.= "Conf-Datei wurde nicht gefunden: $InclBaseDir.$cfile!<br>\n";
	}
	return false;
}


$aCnfForm = array("Database" => $db_only, "Table" => "", "DirName" => $dir_only, "ConfName" => "");

if (!is_resource($db_conn)) {
	die("Ungültige Zugangsdaten für DB-Server!<br>\n");
}

foreach($aCnfForm as $k => $v) if (!empty($_GET["aCnfForm"][$k])) $aCnfForm[$k] = $_GET["aCnfForm"][$k];

if (!isset($_POST["resetSrc"]) && !isset($_GET["new"])) {
	if (!empty($_POST["SetDb"]) && !$db_only)  $aCnfForm["Database"] = stripslashes($_POST["SetDb"]);
	if (!empty($_POST["SetTbl"])) $aCnfForm["Table"] = stripslashes($_POST["SetTbl"]);
	if (!empty($_POST["SetDir"]) && !$dir_only) $aCnfForm["DirName"] = stripslashes($_POST["SetDir"]);
	if (!empty($_POST["SetCnfKey"])) $aCnfForm["ConfName"] = stripslashes($_POST["SetCnfKey"]);
}

$dbAccessForm = "";
//echo "#".__LINE__."";
include($InclBaseDir."registered_data.inc.php");
// $ConfRegData[$conf_name] = $conf_file;

$dbAccessForm.= "<table style=\"border:0px;border-left:1px solid gray;border-top:1px solid gray;\">
<tr>
<td style=\"border-right:1px solid gray;border-bottom:1px solid gray;padding:5px;\" valign=top>
<strong>Bestehende Datenkonfigurationen</strong><br>\n";
foreach($ConfRegData as $k => $v) {
	$aCData[$k] = getConfDataByCFile($v);
	if (isset($aCData[$k]["Title"]))
	$dbAccessForm.= "<a href=\"{$formAction}&loadConf=".$k."\">".$aCData[$k]["Title"]."</a><br>\n";
}
$dbAccessForm.= "</td>\n";
$dbAccessForm.= "<td valign=top style=\"border-right:1px solid gray;border-bottom:1px solid gray;padding:5px;\">
<strong>Neue Datenkonfigurationen</strong><br>\n";

if (!empty($_GET["loadConf"])) {
	$loadConf = $_GET["loadConf"];
	if (isset($aCData[$loadConf]["Title"])) {
		$aCnfForm["Database"] = $aCData[$loadConf]["Db"];
		$aCnfForm["Table"] = $aCData[$loadConf]["Table"];
		$aCnfForm["DirName"] = "./";
		$aCnfForm["ConfName"] = $loadConf;
		// print_r($aCnfForm);
	}
}

if (!isset($_POST["resetSrc"]) && !empty($aCnfForm["Database"])) {
	$dbAccessForm.= "Datenbank: ".$aCnfForm["Database"]." ".($db_only? "(<strong>DbOnly:</strong>".$db_only.")" : "")."<br>\n";
	$dbAccessForm.= "<input type=\"hidden\" name=\"SetDb\" value=\"".fb_htmlEntities($aCnfForm["Database"])."\">\n";
	if (isset($_POST["resetSrc"]) || !empty($aCnfForm["Table"])) {
		$dbAccessForm.= "Tabelle: ".$aCnfForm["Table"]."<br>\n";
		$dbAccessForm.= "<input type=\"hidden\" name=\"SetTbl\" value=\"".fb_htmlEntities($aCnfForm["Table"])."\">\n";
	} else {
		$dbAccessForm.= "Tabelle: <br>\n";
		$dbAccessForm.= getFormSelectBySQL("SetTbl", "SHOW Tables FROM `".$aCnfForm["Database"]."`", $db_conn, false, false, "", "Tbl auswählen")."<br>\n";
	}
} else {
	$dbAccessForm.= "Datenbank: ".($db_only? "(<strong>DbOnly:</strong>".$db_only.")" : "")."<br>\n";
	$dbAccessForm.= getFormSelectBySQL("SetDb", "SHOW DATABASES", $db_conn, false, false, $default_db, "Db auswählen")."<br>\n";
}

$dbAccessForm.= "Oberverzeichnis für $dirLib und $dirTpl ".($dir_only? "(<strong>DirOnly:</strong>".$dir_only.")" : "").":<br>\n";
if (isset($_POST["resetSrc"]) || empty($aCnfForm["DirName"])) {
	$dbAccessForm.= "<input name=\"SetDir\" value=\"\"><br>\n";
} else {
	$dbAccessForm.= $aCnfForm["DirName"]."<br>\n<input type=\"hidden\" name=\"SetDir\" value=\"".fb_htmlEntities($aCnfForm["DirName"])."\">\n";
}
$dbAccessForm.= "Eindeutiger Konf. bzw. Var-Name:<br>\n";
if (isset($_POST["resetSrc"]) || empty($aCnfForm["ConfName"])) {
	if (!empty($aCnfForm["Table"])) {
		$defCnfName = $aCnfForm["Table"];
		if (substr($defCnfName,0,strlen($tblPrefix)) == $tblPrefix) {
			$defCnfName = substr($defCnfName, strlen($tblPrefix));
		}
	} else {
		$defCnfName = "";
	}
	$dbAccessForm.= "<input name=\"SetCnfKey\" value=\"".fb_htmlEntities($defCnfName)."\"><br>\n";
} else {
	$dbAccessForm.= $aCnfForm["ConfName"]."<br>\n<input type=\"hidden\" name=\"SetCnfKey\" value=\"".fb_htmlEntities($aCnfForm["ConfName"])."\">\n";
}
$dbAccessForm.= "</tr></table>\n";


if ($dbAccessForm) {
	$output_caf.= "<form action=\"".$formAction."\" method=\"post\">\n";
	$output_caf.= $dbAccessForm;
	$output_caf.= "<input type=\"submit\" vale=\"senden\">\n<input type=\"submit\" name=\"resetSrc\" value=\"Korrigieren\">\n";
	$output_caf.= "</form>\n";
}


$output_caf.= $error;
/**/
if (!isset($_POST["resetSrc"]) && $aCnfForm["Database"] && $aCnfForm["Table"] && $aCnfForm["DirName"] && $aCnfForm["ConfName"]) {
	$addVars2 = "";
	foreach($aCnfForm as $k => $v) if ($v) $addVars2.= "&aCnfForm[$k]=".rawurlencode($v);
	
	// DB-Basisdaten für Conf-Erstellung
	$conf_db = $aCnfForm["Database"];
	$conf_tbl= $aCnfForm["Table"];
	$conf_name = $aCnfForm["ConfName"];
	$dirBase = $aCnfForm["DirName"];
	
	// Arbeitsschritte
	$default = "view";
	$conf_file = $conf_name.".inc.php";
	
	if (!file_exists($dirBase) || filetype($dirBase) != "dir") {
		mkdir($conf_name);
	}
	if (!file_exists($dirBase."/html") || filetype($dirBase."/html") != "dir") {
		mkdir($dirBase."/html");
	}
	if (!file_exists($dirBase."/include") || filetype($dirBase."/include") != "dir") {
		mkdir($dirBase."/include");
	}
	
	echo "#".__LINE__." ".basename(__FILE__)." f = new formcreator($conf_name, $dirBase, $dirLib, $dirTpl)<br>\n";
	// Anlegen
	// Aus DB-Tabelle eine Conf-Datei erstellen
	$formAction = basename($formAction)."?".$addVars2;
	$f = new formcreator($conf_name, $dirBase, $dirLib, $dirTpl);
	if (!$db_conn) {
		$f->db_connect($db_host, $db_user, $db_pass);
	} else {
		$f->db_connid = $db_conn;
	}
	$f->load_tblconf_from_db($conf_db, $conf_tbl);
	$f->create_default_conf_fromdb($conf_db, $conf_tbl);
	
	
	$CE = new ConfEditor($f->dirLib."/".$f->confName.".inc.php");
	$CE->load_struct();
	//echo "#".__LINE__." ".basename(__FILE__)."<pre>arrConf:\n".print_r($f->arrConf,1)."</pre><br>\n";
	
	$CE->load_conf();
	//echo "#".__LINE__." ".basename(__FILE__)."<pre>CE->aCnfData:\n".print_r($CE->aCnfData,1)."</pre><br>\n";
	//$CE->write_conf();
	$CE->sCnfPostVar = "conf";
	/*$CE->aCnfData = $f->arrConf;
	$CE->aCnfPathAlerts = $aCnfPathAlerts;
	$CE->autorun();
	return $CE->sCnfForm;
	*/
	$CE->load_conf();
	
	if (empty($CE->aCnfData)) {
		$f->create_default_conf_fromdb($conf_db, $conf_tbl);
		$CE->load_conf($f->arrConf);
	} else {
		$f->arrConf = $CE->aCnfData;
	}
	$f->compare_tbl_and_conf();
	foreach($f->aChanges as $k => $v) {
		if ($v["FeldStatus"] == "NEW") {
			// if (isset($f->db_tblconf[$conf_tbl][$k])) echo "#".__LINE__." \$f->db_tblconf[$conf_tbl][$k]: ".print_r($f->db_tblconf[$conf_tbl][$k],true)."<br>\n";
		}
	}
	$CE->aCnfPathAlerts = $f->getFieldAlerts();
	$CE->autorun();
	if ($CE->saved) {
		$f->write_templates();
	}
	
	/*
	if (isset($_POST["conf"]) && is_array($_POST["conf"]) ) {
		
		$f->load_conf_byArray($_POST["conf"], true);
		if ($f->write_conf()) {
			$f->load_conf_file($conf_file);
			$f->write_templates();
			if ($f->write_conf()) $f->load_conf_file($conf_file);
		}
	}
	// if (0 && $f->load_conf_file($conf_file)) {
	if (!$f->load_conf_file($conf_file)) {
		$output_caf.= "#".__LINE__." ".basename(__FILE__)." <br>\n";
		$f->load_tblconf_from_db($conf_db, $conf_tbl);
		$f->create_default_conf_fromdb($conf_db, $conf_tbl);
		$f->write_conf();
		$f->write_templates();
	}
	
	$sConfEditForm = "";
	if ($f->load_conf_file($conf_file)) {
		$f->compare_tbl_and_conf();
		$f->create_default_conf_fromdb($conf_db, $conf_tbl);
		$aCnfPathAlerts = $f->getFieldAlerts();
		$sConfEditForm = $f->getConfEditForm($formAction);
	}*/
	if ($f->Error) $output_caf.= "<div class=\"errBox\">".$f->Error."</div>\n";
	if ($f->Msg) $output_caf.= "<div class=\"msgBox\">".$f->Msg."</div>\n";
	// $output_caf.= $sConfEditForm;
	
	$output_caf.= $CE->sCnfForm;
	
	//echo "<pre>";
	//print_r($f->db_tblconf);
	//print_r($f->arrConf);
	//print_r($f->arrConfProperties);
	//echo "</pre>";
	
	// Konfiguration laden
	$output_caf.= '<link rel="stylesheet" type="text/css" media="screen" href="http://www.center.tv/online/css/fb_table.css"/>';
	$output_caf.= <<<MyCssDoc
	<style>
		td {vertical-align:top;}
	</style>
MyCssDoc;
	// Konfiguration speichern
	
	$f->__destruct(); // = null;
	$output_caf.= $f->Error;
		
	// */</style>
	
	
	// Neue Datenstruktur registrieren
	include($InclBaseDir."registered_data.inc.php");
	$ConfRegData[$conf_name] = $conf_file;
	$fp = fopen($InclBaseDir."registered_data.inc.php", "w+");
	if ($fp) {
		fputs($fp, "<?php \r\n\r\n");
		foreach($ConfRegData as $k => $v) fputs($fp, "\$ConfRegData['".addslashes($k)."'] = '".addslashes($v)."';\r\n");
		fputs($fp, "\r\n?>");
	}
}

if (basename($_SERVER["PHP_SELF"]) != basename(__FILE__)) {
	$body_content.= $output_caf;
} else {
	echo $output_caf;
	echo "</body>\n</html>";
}
?>