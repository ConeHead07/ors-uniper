<?php 
@session_start();
session_id();
if (!empty($user_connid)) $ConnUserDB["connid"] = $user_connid;
if (!empty($conn)) $ConnUserDB["connid"] = $conn;
$RegBaseDir = "./login/";
$LoginBaseDir = "./login/";
if (!defined("PATH_TO_LOGIN_MODUL")) define("PATH_TO_LOGIN_MODUL", realpath(dirname(__FILE__)."./"));

// if (!isset(PATH_TO_LOGIN_MODUL)) define('PATH_TO_LOGIN_MODUL', realpath(dirname(__FILE__)."/../")."/");
if (empty($_CONF["email"]["webmaster"])) $_CONF["email"]["webmaster"] = "service-unipers@mertens.ag";
if (empty($MConf["WebRoot"])) $MConf["WebRoot"] = "https://".$_SERVER["HTTP_HOST"]."/".dirname($_SERVER["PHP_SELF"])."/";
$_CONF["WebRoot"] = $MConf["WebRoot"];
$_CONF["user_tbl_prefix"] = $MConf["DB_TblPrefix"];
$_CONF["HomepageTitle"] = $MConf["AppTitle"];
$_CONF["UrlToLoginModul"] = $MConf["WebRoot"]."module/login/";
$_CONF["PATH_TO_LOGIN_MODUL"] = PATH_TO_LOGIN_MODUL;
$_CONF["LOGIN_WEBPATH_MODUL"] = $MConf["WebRoot"]."module/login/";

if (!isset($_CONF["defaultPruefeLogin"])) $_CONF["defaultPruefeLogin"] = true;
$_CONF["redirectAfterLogin"] = "index.php?area=admin";
$_CONF["regc_subject"] = "Aktivieren Sie Ihren Account auf ".$MConf["WebRoot"];
$_CONF["regc_authentlink"] = $MConf["WebRoot"]."login.php?ac={authentcode}";
$_CONF["regc_mail_tld_only"] = '@gmail.com'; // "@mertens.ag"; // "@mertens.ag"; // "@googlemail.com"; //
$_CONF["regc_mail_tld_check"] = true; //false;
$_CONF["forget_pw_link"] = $MConf["WebRoot"]."login_fpw.php";
$_CONF["mailc_subject"]  = "Aktivieren Sie Ihre E-Mail-Änderung";
$_CONF["mailc_authentlink"] = $MConf["WebRoot"]."login.php?mc={authentcode}";

$_CONF["pw_min_length"]   = 5;
$_CONF["allow_user_register"]   = true;
$_CONF["allow_user_forgetpass"] = true;
$_CONF["allow_user_changemail"] = true;

$_CONF["LnkStartseite"] = "<a class=\"menuLnk\" id=\"astart\" href=\"{$_CONF['WebRoot']}index.php\" target=\"_self\">Startseite</a>";
$_CONF["LnkLogin"] = "<a href=\"{$_CONF['WebRoot']}login.php\">Anmelden</a> "; //Registrieren //
$_CONF["LnkRegister"] = "<a href=\"{$_CONF['WebRoot']}login.php?rg=1\">Registrieren</a> "; //Registrieren //
$_CONF["LnkForgetPw"] = "<a href=\"{$_CONF['WebRoot']}login.php?fpw=1\">Passwort vergessen?</a> ";
$_CONF["LnkChgEmail"] = "<a href=\"{$_CONF['WebRoot']}login.php?cm=1\">Email ändern</a> ";

$_CONF["forget_pw_link"] = $MConf["WebRoot"]."login.php?fpw=1";
$_CONF["regc_mail_type"] = "Content-Type: text/plain";
$_CONF["regc_mail_text"] = PATH_TO_LOGIN_MODUL."mailtexte/reg_confirm_mail.txt";
$_CONF["mailc_change_text"] = PATH_TO_LOGIN_MODUL."mailtexte/confirm_mail_change.txt";
$_CONF["fpw_mail_text"]    = PATH_TO_LOGIN_MODUL."mailtexte/forget_password.txt"; // Passwort vergessen: Sende Mail mit neuem Freischaltcode
$_CONF["fpw_mail_subject"] = "Passwort vergessen?";
$_CONF["HTML"]["login"] = PATH_TO_LOGIN_MODUL."html/login.html";
$_CONF["HTML"]["forget_pw"] = PATH_TO_LOGIN_MODUL."html/login_forgetpw.html";
$_CONF["HTML"]["registrieren_eingabe"] = PATH_TO_LOGIN_MODUL."html/registrieren_eingabe.html";
$_CONF["HTML"]["registrieren_saved"]   = PATH_TO_LOGIN_MODUL."html/registrieren_saved.html";
$_CONF["HTML"]["registrieren_syserror"]=PATH_TO_LOGIN_MODUL."html/registrieren_syserror.html";
$_CONF["HTML"]["errorbox"] = PATH_TO_LOGIN_MODUL."html/errorbox.html";
$_CONF["HTML"]["msgbox"]   = PATH_TO_LOGIN_MODUL."html/msgbox.html";

$_TABLE["user"] = $_CONF["user_tbl_prefix"]."user";
$_TABLE["newpw"] = $_CONF["user_tbl_prefix"]."user_newpw";
$_TABLE["newemail"] = $_CONF["user_tbl_prefix"]."user_newemail";
