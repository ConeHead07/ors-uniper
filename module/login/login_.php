<?php 
/*
define('PATH_TO_LOGIN_MODUL', realpath(dirname(__FILE__))."/");
if (empty($connid) && !empty($conn)) $connid = $conn;
if (empty($connid) && function_exists("db_connect")) db_connect();
if (@empty($ConnDB["connid"]) && !empty($connid)) $ConnDB["connid"] = $connid;

$ConnUserDB = $ConnDB;
$user_connid = $connid;

$UserConnScript = dirname(__FILE__)."/include/conn_userdb.php";
if (file_exists($UserConnScript)) {
	include($UserConnScript);
	if ( $ConnUserDB["Host"] != $ConnUserDB["Host"]
	 || $ConnUserDB["Database"] != $ConnUserDB["Database"]) {
		
		$user_connid = db_connect($ConnUserDB);
	}
	
}

// require_once(dirname(__FILE__)."/user.inc.php");
require_once(PATH_TO_LOGIN_MODUL."include/conf.php");
if (!isset($pruefe_login)) $pruefe_login = $_CONF["defaultPruefeLogin"];

require_once(PATH_TO_LOGIN_MODUL."include/login_lib.php");
require_once(PATH_TO_LOGIN_MODUL."include/user_lib.php");

if (empty($msg)) $msg = "";
if (empty($error)) $error = "";
if (empty($syserr)) $syserr = "";

$_rpl = array();
if (!isset($show_form)) $show_form = false;

$user = get_userBySession();

// Logout
if (isset($_GET["logout"]) || isset($_POST["logout"])) {
	include(PATH_TO_LOGIN_MODUL."include/logout.php");
	$show_form = true;
}

// PRUEFE ZUGANGSDATEN
if (isset($_POST["username"])  && !empty($_POST["username"]) && 
isset($_POST["password"]) && !empty($_POST["password"]))
{
	include(PATH_TO_LOGIN_MODUL."include/login_bridge.php");
}

// Registrierung 
if (isset($_GET["rg"]) && ($_CONF["allow_user_register"] || $user["gruppe"] == "admin")) {
	// echo "#".__LINE__." ".basename(__FILE__)."<br>\n";
	include(PATH_TO_LOGIN_MODUL."include/registrieren.php");
	$show_form = true;
}

// Registrierung abschliessen
if (isset($_GET["ac"])) {
	// echo "#".__LINE__." ".basename(__FILE__)."<br>\n";
	include(PATH_TO_LOGIN_MODUL."include/registrieren_ac.php");
	$show_form = true;
}

// E-Mail
if (isset($_GET["mc"])) {
	// echo "#".__LINE__." ".basename(__FILE__)."<br>\n";
	include(PATH_TO_LOGIN_MODUL."include/email_mc.php");
	$show_form = true;
}

// Change PassWord
if (isset($_GET["cpw"])) {
	// echo "#".__LINE__." ".basename(__FILE__)."<br>\n";
	include(PATH_TO_LOGIN_MODUL."include/password.php");
	$show_form = true;
}

// Change Email
if (isset($_GET["cm"])) {
	include(PATH_TO_LOGIN_MODUL."include/email.php");
	$show_form = true;
}

// Forget PassWord
if (isset($_GET["fpw"])) {
	include(PATH_TO_LOGIN_MODUL."include/login_fpw.php");
	$show_form = true;
}

if ($pruefe_login && (empty($user["uid"]) || empty($user["freigegeben"]) != "Ja")) {
	if (empty($user["uid"])) $show_form = true;
}

if (!isset($redirect)) {
	if (isset($_GET["redirect"])) $redirect = $_GET["redirect"];
	elseif (isset($_POST["redirect"])) $redirect = $_POST["redirect"];
	else $redirect = "";
}

$_rpl["{username}"] = (isset($_POST["username"]) ? fb_htmlEntities(stripslashes($_POST["username"])) : "");
$_rpl["<!-- {msg} -->"] = (!empty($msg)) ? "<div class=\"msg\">".$msg."</div>" : "";
$_rpl["<!-- {error} -->"] = (!empty($error)) ? "<div class=\"err\">".$error."</div>" : "";
$_rpl["{redirect}"] = (isset($redirect)) ? fb_htmlEntities($redirect) : "";
$_rpl["{email}"] = (isset($_POST["username"]) ? fb_htmlEntities(stripslashes($_POST["email"])) : "");
$_rpl["{HomepageTitle}"] = $_CONF["HomepageTitle"];
$_rpl["{UrlToHomepage}"] = $_CONF["UrlToHomepage"];
$_rpl["{UrlToLoginModul}"] = $_CONF["UrlToLoginModul"];

$_rpl["{LnkRegister}"] = ($_CONF["allow_user_register"]) ? $_CONF["LnkRegister"] : "";
$_rpl["{LnkForgetPw}"] = ($_CONF["allow_user_forgetpass"]) ? $_CONF["LnkForgetPw"] : "";
$_rpl["{LnkChgEmail}"] = ($_CONF["allow_user_changemail"]) ? $_CONF["LnkChgEmail"] : "";

$_rpl["{action}"] = basename($_SERVER["PHP_SELF"])."?".$_SERVER["QUERY_STRING"];


if (!empty($content)) $content = str_replace("{webPath}", $_CONF["LOGIN_WEBPATH_MODUL"], $content);
if ($show_form) {
	// echo "#".__LINE__." _rpl: ".print_r($_rpl,true)."<br>\n";
	// echo "#".__LINE__." _CONF: ".print_r($_CONF,true)."<br>\n";
	
	if (!isset($content)) $content = implode("", file(PATH_TO_LOGIN_MODUL."html/login.html"));
	
	if (empty($ausgabe)) $ausgabe = implode("", file(PATH_TO_LOGIN_MODUL."html/index.html"));
	$ausgabe = str_replace("<!-- {content} -->", $content, $ausgabe);
	$ausgabe = str_replace("{content}", $content, $ausgabe);
	$ausgabe = strtr($ausgabe, $_rpl);
	$ausgabe = str_replace("{webPath}", $_CONF["LOGIN_WEBPATH_MODUL"], $ausgabe);
	die($ausgabe);
}
*/
?>