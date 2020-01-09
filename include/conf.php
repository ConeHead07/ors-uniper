<?php
@session_start();
session_id();

if (file_exists(__DIR__ . '/../etc/sysdef.local.php')) {
    require_once __DIR__ . '/../etc/sysdef.local.php';
} else {
    require_once __DIR__ . '/../etc/sysdef.php';
}

$msg = "";
$error = "";
$sys_error = "";
/**/

if (!defined("HP_LANG")) {
	if (isset($_REQUEST["SetLang"])) $SetLang = $_REQUEST["SetLang"];
	elseif (isset($_SESSION["HP_LANG"])) $SetLang = $_SESSION["HP_LANG"];
	if (!empty($SetLang) && in_array($SetLang, array("DE","EN"))) define("HP_LANG", $SetLang);
}


if (!defined('MYSQL_ASSOC')) define('MYSQL_ASSOC', MYSQLI_ASSOC);
if (!defined('MYSQL_NUM'))   define('MYSQL_NUM', MYSQLI_NUM);
if (!defined('MYSQL_BOTH'))  define('MYSQL_BOTH', MYSQLI_BOTH);

if (!defined('DEBUG')) define('DEBUG', true);
if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
if (!defined('HP_LANG')) define("HP_LANG", "DE");

$_SESSION["HP_LANG"] = HP_LANG;

if (!function_exists("abs2WebPath")) {
function abs2WebPath($FileAbsPath, $WithDomain = true) {
	global $_SERVER;
	$R = $_SERVER["DOCUMENT_ROOT"];
	$PR = (isset($_SERVER["HTTPS"]) && strtoupper($_SERVER["HTTPS"])=="ON") ? "https" : "http";
        $Port = $_SERVER['SERVER_PORT']!="80"?':'.$_SERVER['SERVER_PORT']:'';
	$H = $PR."://".$_SERVER["HTTP_HOST"]; //.$Port;
	$P = realpath($FileAbsPath)."/";
	//echo "#".__LINE__." ".basename(__FILE__)." FileAbsPath:".$FileAbsPath."; R:$R; H:$H; P:$P<br>\n";
        $s = str_replace("\\","/",substr($P, strlen($_SERVER["DOCUMENT_ROOT"])));
	return ($WithDomain?$H:"")."/".ltrim($s,'/');
        
}}

$postmaster = '';
$propertyName = 'Dussman';
$MConf = array(
	"AppTitle" => "ORS Order Request System",
	"WebRoot" => abs2WebPath(dirname(__FILE__).DS."..".DS),
	"AppRoot" => realpath(dirname(__FILE__).DS."..".DS).DS,
	"Tpl_Dir" => "html".DS,
	"Inc_Dir" => "include".DS,
	"Class_Dir" => "class".DS,
	"Modul_Dir" => "module".DS,
	"Texte_Dir" => "textfiles".DS,
	"CAuthSessionName" => "MMAGSID",
	"CAuthEncodingKey" => "mermoma",
	"SiteIsOnline" => true,
	"OfflineModul" => "offline".DS."offline.php",
	"defaultPruefeLogin" => true,
	"DB_Name" => DB_DUSS,
	"DB_Host" => DB_DUSS_HOST,
	"DB_User" => DB_DUSS_USER,
    "DB_Pass" => DB_DUSS_PASS,
    "DB_Port" => DB_DUSS_PORT,
	"DB_TblPrefix" => "mm_",
	"Html_Ausgabe" => "index.html",
	"Html_Body" => "index_body.html",
	"Html_MsgBox" => "msgbox.html",
	"Html_ErrBox" => "errorbox.html",
	"theme" => "property_old",
	"propertyName" => $propertyName,
	"smtp_server"    => "mail.mertens.ag",
	"smtp_port"      => "25",
	'smtp_from_name' => 'Order Request System',
	'smtp_from_addr' => 'bayerors@mertens.ag',        
	'smtp_auth_user' => 'mag\bayermove',
	'smtp_auth_pass' => 'merTens47877',
	'smtp_client_host' => gethostbyname(gethostname()),
	'min_ma' => 0,
	'webmaster' => 'bayerors@mertens.ag',
	'minWerktageVorlauf' => 0,
);
//die('<pre>'.print_r($MConf,1));

/* $MConf['module_login_hint'] = <<<EOT
        admin3=geheim3, test_vma(vodafone-user)=test, test_vproperty=test, test_mertens=test
EOT; */


$SiteVars["AppTitle"]     = $MConf["AppTitle"];
$SiteVars["WebRoot"]      = $MConf["WebRoot"];
$SiteVars["propertyName"] = $MConf["propertyName"];

// Kompatibilit�ts-Variablen
$_CONF = $MConf;
$_CONF["HTML"]["ausgabe"]  = $MConf["AppRoot"].$MConf["Tpl_Dir"].$MConf["Html_Ausgabe"];
$_CONF["HTML"]["body"]     = $MConf["AppRoot"].$MConf["Tpl_Dir"].$MConf["Html_Body"];
$_CONF["HTML"]["msgbox"]   = $MConf["AppRoot"].$MConf["Tpl_Dir"].$MConf["Html_MsgBox"];
$_CONF["HTML"]["errorbox"] = $MConf["AppRoot"].$MConf["Tpl_Dir"].$MConf["Html_ErrBox"];
$_CONF["email"]["webmaster"] = $MConf['webmaster'];

$webPathBaseUrl = $MConf["WebRoot"];
$AppBaseDir = $MConf["AppRoot"];
$RegBaseDir = $MConf["AppRoot"];
$LoginBaseDir = $MConf["AppRoot"];
$HtmlBaseDir = $MConf["AppRoot"].$MConf["Tpl_Dir"];
$InclBaseDir = $MConf["AppRoot"].$MConf["Inc_Dir"];
$ModulBaseDir = $MConf["AppRoot"].$MConf["Modul_Dir"];
$AdminModulBaseDir = $AppBaseDir."adminmod/";
$ClassBaseDir = $MConf["AppRoot"].$MConf["Class_Dir"];
$ProjBaseDir = $MConf["AppRoot"].$MConf["Class_Dir"];
$TextBaseDir = $MConf["AppRoot"].$MConf["Texte_Dir"];

/* ADMIN-MODULE: START */
$_ADMIN_MODUL["kontakt"] = $AdminModulBaseDir."admin_mod_kontakt.php";
$_ADMIN_MODUL["webstatistik"] = $AdminModulBaseDir."admin_mod_webstatistik.php";
$_ADMIN_MODUL["seitenbereiche"] = $AdminModulBaseDir."admin_mod_seitenbereiche.php";
$_ADMIN_MODUL["siteconf"] = $AdminModulBaseDir."admin_mod_siteconf.php";
$_ADMIN_MODUL["webuser"] = $AdminModulBaseDir."admin_mod_webuser.php";
$_ADMIN_MODUL["formcreator"] = $ModulBaseDir."formbuilder/create_any_forms.php";
$_ADMIN_MODUL["editbyconf"] = $ModulBaseDir."editdatabyconf/edit_data.inc.php";
$_ADMIN_MODUL["offline"] = $ModulBaseDir."offline/offline.php";
/* ADMIN-MODULE: ENDE */

