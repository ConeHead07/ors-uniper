<?php
require_once(dirname(__FILE__)."/../../header.php");
$default_db = $MConf["DB_Name"];
$db_only = $MConf["DB_Name"];
$db_host = $MConf["DB_Host"];
$db_user = $MConf["DB_User"];
$db_pass = $MConf["DB_Pass"];
$includeDir = $MConf["AppRoot"].$MConf["Inc_Dir"];
$templateDir = $MConf["AppRoot"].$MConf["Tpl_Dir"];
$modul_dir = basename(dirname(__FILE__));
$modulWebDir = $MConf["WebRoot"].str_replace(DS,"/",$MConf["Modul_Dir"]).$modul_dir;

if (@empty($_rplAusgabe[0]["<!-- {Headers} -->"])) $_rplAusgabe[0]["<!-- {Headers} -->"] = "";
if (@empty($body_content)) $body_content = "";
$_rplAusgabe[0]["<!-- {Headers} -->"].= "<!-- Create Forms -->\n
<link rel=\"stylesheet\" href=\"".$modulWebDir."/css/default.css\" media=\"screen\" title=\"CreateForms\">
<script src=\"".$modulWebDir."/js/GetObjectDisplay.js\"></script>\n";

//echo "#".__LINE__."";
include($includeDir."registered_data.inc.php");
include(dirname(__FILE__).DS."formbuilder_presets.php");
$body_content.= $formbuilder_presets;

$body_content.= $error;

$ConfCreate = false;
if (isset($_POST["create"])) {
	if ($newConfVars["NewConfDb"] && $newConfVars["NewConfTbl"] && $newConfVars["NewConfName"]) {
		$conf_db = $newConfVars["NewConfDb"];
		$conf_tbl= $newConfVars["NewConfTbl"];
		$conf_name = $newConfVars["NewConfName"];
		$ConfCreate = true;
	}
}

if ($ConfCreate) {
	$conf_file = $includeDir.$conf_name.".inc.php";
	// Start: Erstellen und Speichern der Default-Conf aus Tabellenstruktur
	$f = new formcreator($conf_name, $includeDir, $templateDir);
	$f->create_default_conf_fromdb($conf_db, $conf_tbl, true);
	
	$CE = new ConfEditor($conf_file, $conf_name);
	$CE->load_struct();
	$CE->load_conf($f->arrConf);
	$CE->formAction = basename($_SERVER["PHP_SELF"])."?s=$s&ConfEdit=$conf_name";
	$CE->autorun();
	$CE->write_conf();
	// Ende: Erstellen und Speichern der Default-Conf aus Tabellenstruktur
	
	$body_content.= $f->Error;
	$body_content.= $CE->sCnfForm;
	$f = null;
	$CE = null;
	
	// Neue Datenstruktur registrieren
	include($includeDir."registered_data.inc.php");
	$ConfRegData[$conf_name] = basename($conf_file);
	$fp = fopen($includeDir."registered_data.inc.php", "w+");
	if ($fp) {
		fputs($fp, "<?php \r\n\r\n");
		foreach($ConfRegData as $k => $v) fputs($fp, "\$ConfRegData['".addslashes($k)."'] = '".addslashes($v)."';\r\n");
		fputs($fp, "\r\n"."?".">");
	}
}

if (!@empty($_GET["ConfEdit"])) {
	$conf_name = $_GET["ConfEdit"];
	$conf_file = $includeDir.$conf_name.".inc.php";
	if (file_exists($conf_file)) {
		// Start: Bestehende Conf laden
		$CE = new ConfEditor($conf_file, $conf_name);
		$CE->formAction = basename($_SERVER["PHP_SELF"])."?s=$s&ConfEdit=$conf_name";
		$CE->load_conf();
		
		$f = new formcreator($conf_name, $includeDir, $templateDir);
		$f->load_tblconf_from_db($CE->aCnfData["Db"], $CE->aCnfData["Table"]);
		$f->arrConf = $CE->aCnfData;
		$f->compare_tbl_and_conf();
		$CE->aCnfPathAlerts = $f->getFieldAlerts();
		
		$CE->autorun();
		if ($CE->saved) $f->write_templates();
		
		$body_content.= $f->Error;
		$body_content.= $CE->sCnfForm;
		// Ende: Bestehende Conf laden
		
		$f = null;
		$CE = null;
	} else {
		echo "#".__LINE__." ".basename(__FILE__)."<br>\n";
	}
}

if (basename($_SERVER["PHP_SELF"]) == basename(__FILE__)) {
	echo $_rplAusgabe[0]["<!-- {Headers} -->"];
	echo $body_content;
	echo "</body>\n</html>";
}
/**/
?>