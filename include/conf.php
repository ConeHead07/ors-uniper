<?php
@session_start();
session_id();

if (file_exists(__DIR__ . '/../etc/sysdef.local.php')) {
    require_once __DIR__ . '/../etc/sysdef.local.php';
} else {
    require_once __DIR__ . '/../etc/sysdef.php';
}

$charset = 'UTF-8';
// use UTF-8 as character encoding
if (function_exists('mb_internal_charset')) {
    mb_internal_charset($charset);
}
define('SMARTY_RESOURCE_CHAR_SET', $charset);

$msg = "";
$error = "";
$sys_error = "";
/**/

// TEST-Wert
// if (!defined('TOTAL_SHOP_LIMIT')) define('TOTAL_SHOP_LIMIT', 1650000.00);
if (!defined('TOTAL_SHOP_LIMIT')) define('TOTAL_SHOP_LIMIT', 1850000.00);

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
        $Port = (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT']!="80")?':'.$_SERVER['SERVER_PORT']:'';
        if (!empty($_SERVER["HTTP_HOST"])) {
            $H = $PR."://".$_SERVER["HTTP_HOST"]; //.$Port;
        } else {
            $H = "http://localhost" . $Port;
        }

	$P = realpath($FileAbsPath)."/";
	//echo "#".__LINE__." ".basename(__FILE__)." FileAbsPath:".$FileAbsPath."; R:$R; H:$H; P:$P<br>\n";
    if (!empty($_SERVER["DOCUMENT_ROOT"])) {
        $s = str_replace("\\", "/", substr($P, strlen($_SERVER["DOCUMENT_ROOT"])));
    } else {
        $s = __LINE__;
    }
	return ($WithDomain?$H:"")."/".ltrim($s,'/');
        
}}

$postmaster = '';
$propertyName = 'Dussman';
$MConf = array(
	"AppTitle" => "Uniper NewNormal Homeoffice",
	"WebRoot" => abs2WebPath(dirname(__FILE__).DS."..".DS),
	"AppRoot" => realpath(dirname(__FILE__).DS."..".DS).DS,
	"Tpl_Dir" => "html".DS,
	"Inc_Dir" => "include".DS,
	"Class_Dir" => "class".DS,
	"Modul_Dir" => "module".DS,
    "Texte_Dir" => "textfiles".DS,
    "Sites_Dir" => "sites".DS,
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
	"DB_CHARSET" => "utf8", // Previous latin1
	"DB_TblPrefix" => "mm_",
	"Html_Ausgabe" => "index.html",
	"Html_Body" => "index_body.html",
	"Html_MsgBox" => "msgbox.html",
	"Html_ErrBox" => "errorbox.html",
	"theme" => "zurich",

	"activity_log_max_days" => 30,

	"propertyName" => $propertyName,
//	"smtp_server"    => "mail.mertens.ag",
//	"smtp_port"      => "25",
//	'smtp_from_name' => 'Order Request System',
//	'smtp_from_addr' => 'uniperors@mertens.ag',
//	'smtp_auth_user' => 'mag\unipermove',
//	'smtp_auth_pass' => 'merTens47877',
//	'smtp_client_host' => gethostbyname(gethostname()),
    "smtp_server"    => "mail.mertens.ag",
    "smtp_port"      => "25",
    'smtp_from_name' => 'Order Request System',
    // ors-service@mertens.ag
    // service-uniper@mertens.ag
    'smtp_from_addr' => 'service-zurich@mertens.ag',
    'smtp_auth_user' => 'mag\ors-service',
    'smtp_auth_pass' => 'merTens47877',
    'smtp_client_host' => gethostbyname(gethostname()),
	'min_ma' => 0,
	'webmaster' => 'service-zurich@mertens.ag',
	'minWerktageVorlauf' => 0,
    'validateAntragOrt' => false,
    'validateAntragGebaeude' => false,

    'STATUSMAIL_ADD_STEUERINFOS' => false,

    'genehmigung_requires_pruefung' => false,
    'bestaetigung_requires_genehmigung' => false,

    'notify_user_angeboten' => true,
    'notify_user_angeboten_tpl' => 'statusmail_user_angeboten.txt',

    'notify_user_beantragt' => true,
    'notify_user_beantragt_tpl' => 'statusmail_user_beantragt.txt',

    'notify_user_temp_remember' => true,
    'notify_user_temp_remember_tpl' => 'statusmail_user_temp_remember.txt',

    'notify_user_bemerkung' => true,
    'notify_user_bemerkung_tpl' => 'statusmail_umzug_bemerkung.txt',

    'notify_user_bemerkung_selfcreated' => false,
    'notify_user_bemerkung_selfcreated_tpl' => 'statusmail_umzug_bemerkung.txt',

    'notify_user_genehmigt_Ja' => false,
    'notify_user_genehmigt_Ja_tpl' => 'statusmail_umzug_aktiv.txt',

    'notify_user_genehmigt_Nein' => false,  // Ist aktuell nicht implementiert, siehe notify_property_genehmigt_Nein
    'notify_user_genehmigt_Nein_tpl' => 'statusmail_umzug_kabgelehnt.txt',  // Ist aktuell nicht implementiert, siehe notify_property_genehmigt_Nein

    'notify_user_bestaetigt_Ja' => true,
    'notify_user_bestaetigt_Ja_tpl' => 'statusmail_umzug_bestaetigt.txt',

    'notify_user_bestaetigt_Nein' => true,
    'notify_user_bestaetigt_Nein_tpl' => 'statusmail_umzug_aufhebung.txt',

    'notify_user_zurueckgegeben' => true,
    'notify_user_zurueckgegeben_tpl' => 'statusmail_umzug_zurueckgegeben.txt',

    'notify_user_abgeschlossen' => true,
    'notify_user_abgeschlossen_tpl' => 'statusmail_umzug_durchgefuehrt.txt',

    'notify_user_storniert' => true,
    'notify_user_storniert_tpl' => 'statusmail_umzug_storniert.txt',


    'notify_mertens_bemerkung' => true,
    'notify_mertens_bemerkung_tpl' => 'statusmail_umzug_bemerkung.txt',

    'notify_mertens_bemerkung_selfcreated' => false,
    'notify_mertens_bemerkung_selfcreated_tpl' => 'statusmail_umzug_bemerkung.txt',

    'notify_mertens_beantragt' => false,
    'notify_mertens_beantragt_tpl' => 'statusmail_umzug_zurpruefung.txt',

    'notify_mertens_erneutpruefen' => true,
    'notify_mertens_erneutpruefen_tpl' => 'statusmail_umzug_zurerneutenpruefung.txt',

    'notify_mertens_genehmigt_Ja' => false,
    'notify_mertens_genehmigt_Ja_tpl' => 'statusmail_umzug_genehmigt.txt',

    'notify_mertens_genehmigt_Nein' => false,
    'notify_mertens_genehmigt_Nein_tpl' => 'statusmail_umzug_abgelehnt.txt',

    'notify_property_beantragt' => false,
    'notify_property_beantragt_tpl' => 'statusmail_umzug_neu.txt',

    'notify_property_angeboten' => true,
    'notify_property_angeboten_tpl' => 'statusmail_property_angeboten.txt',
//    'notify_property_angeboten_tpl' => 'statusmail_umzug_zurgenehmigung.txt',

    'notify_property_geprueft' => false,
    'notify_property_geprueft_tpl' => 'statusmail_umzug_zurgenehmigung.txt',

    'notify_property_bestaetigt_Ja' => false,
    'notify_property_bestaetigt_Ja_tpl' => 'statusmail_umzug_bestaetigt.txt',

    'notify_property_bestaetigt_Nein' => false,
    'notify_property_bestaetigt_Nein_tpl' => 'statusmail_umzug_aufhebung.txt',

    'notify_property_genehmigt_Nein' => false, // Sieht nach einem logischen Fehler, Property muss über eigene Aktion nicht benachrichtigt werden
    'notify_property_genehmigt_Nein_tpl' => '',

    'notify_property_genehmigt_Ja' => false,
    'notify_property_genehmigt_Ja_tpl' => 'statusmail_umzug_aktiv.txt',

    'notify_property_abgeschlossen' => false,
    'notify_property_abgeschlossen_tpl' => 'statusmail_umzug_durchgefuehrt.txt',

    'notify_property_storniert' => false,
    'notify_property_storniert_tpl' => 'statusmail_umzug_storniert.txt',

    'notify_user_reklamation' => true,
    'notify_user_reklamation_tpl' => 'statusmail_user_reklamation.txt',

    'notify_user_reklamation_selfcreated' => true,
    'notify_user_reklamation_selfcreated_tpl' => 'statusmail_user_reklamation.txt',

    'notify_mertens_reklamation' => true,
    'notify_mertens_reklamation_tpl' => 'statusmail_admin_reklamation.txt',

    'notify_mertens_reklamation_selfcreated' => true,
    'notify_mertens_reklamation_selfcreated_tpl' => 'statusmail_admin_reklamation.txt',
);

/* statusmails:
    neuebemerkung  - von User    statusmail_umzug_bemerkung.txt                      AN USER  ADMINS
    neuebemerkung  - von Admin   statusmail_umzug_bemerkung.txt                      AN USER  ADMINS
    neu                          statusmail_umzug_neu.txt                            AN       ADMINS  PROPERTY
    beantragt                    statusmail_umzug_zurpruefung.txt                    AN       ADMINS  PROPERTY
    erneutpruefen                statusmail_umzug_zurerneutenpruefung.txt            AN       ADMINS
    angeboten                    statusmail_umzug_zurgenehmigung.txt                 AN               PROPERTY
    geprueft                     statusmail_umzug_zurgenehmigung.txt                 AN               PROPERTY
    genehmigt      - Nein        statusmail_umzug_kabgelehnt.txt                     AN USER
    genehmigt      - Nein        statusmail_umzug_abgelehnt.txt                      AN       ADMINS
    genehmigt      - Ja          statusmail_umzug_aktiv.txt                          AN               PROPERTY
    genehmigt      - Ja          statusmail_umzug_genehmigt.txt                      AN       ADMINS
    bestaetigt     - Ja          statusmail_umzug_bestaetigt.txt                     AN USER          PROPERTY
    bestaetigt     - Nein        statusmail_umzug_aufhebung.txt                      AN USER          PROPERTY
    zurueckgegeben               statusmail_umzug_zurueckgegeben.txt                 AN USER
    abgeschlossen  - Ja          statusmail_umzug_durchgefuehrt.txt                  AN USER          PROPERTY
    abgeschlossen  - Storniert   statusmail_umzug_storniert.txt                      AN USER          PROPERTY
*/

//die('<pre>'.print_r($MConf,1));



$SiteVars["AppTitle"]     = $MConf["AppTitle"];
$SiteVars["WebRoot"]      = $MConf["WebRoot"];
$SiteVars["propertyName"] = $MConf["propertyName"];

// Kompatibilitäts-Variablen
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
$SitesBaseDir = $MConf["AppRoot"].$MConf["Sites_Dir"];

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

if (file_exists( __DIR__ . '/conf.local.php')) {
    include __DIR__ . '/conf.local.php';
}

if (!function_exists('getAppConfigProperty')) {
    function getAppConfigProperty(string $key, $default = null) {
        global $MConf;
        if (array_key_exists($key, $MConf)) {
            return $MConf[$key];
        }
        return $default;
    }
}

