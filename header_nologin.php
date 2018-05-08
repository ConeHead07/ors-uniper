<?php
header("Content-Type: text/html; charset=ISO-8859-1");

require_once(dirname(__FILE__)."/include/conf.php");
require_once($MConf["AppRoot"].$MConf["Class_Dir"]."dbconn.class.php");
require_once($MConf["AppRoot"].$MConf["Class_Dir"]."SmtpMailer.class.php");
require_once($MConf["AppRoot"].$MConf["Inc_Dir"]."conn.php");
require_once($MConf["AppRoot"].$MConf["Inc_Dir"]."user.inc.php");

require_once $MConf["AppRoot"].$MConf["Inc_Dir"]."stdlib.php";
require_once $MConf["AppRoot"].$MConf["Inc_Dir"]."strip_magic_quoted_gpc.php";
require_once $MConf["AppRoot"].$MConf["Inc_Dir"]."form_request.php";
require_once $MConf["AppRoot"].$MConf["Inc_Dir"]."fbmail.php";
require_once $MConf["AppRoot"].$MConf["Inc_Dir"]."lib_mail.php";
$userlogin = false; //
//require_once $MConf["AppRoot"].$MConf["Modul_Dir"]."login/login.php";
require_once $MConf["AppRoot"]."smarty/Smarty.class.php";
require_once($MConf["AppRoot"].$MConf["Class_Dir"]."myTplEngine.class.php");

?>
