<?php

define("SET_NEW_VISIT", true);
if (function_exists("db_auto_connect")) db_auto_connect();
// REDIRECT
if (!isset($msgID)) $msgID=0;
if (empty($redirect)) {
    if (!empty($_POST["redirect"])   && trim($_POST["redirect"]) ) $redirect = $_POST["redirect"];
    elseif (!empty($_GET["redirect"]) && trim($_GET["redirect"]) ) $redirect = $_GET["redirect"];
    else $redirect = $_CONF["redirectAfterLogin"];
}

$m = __LINE__;
$l = "";
$content = "";
$show_form = true;


// PRUEFE ZUGANGSDATEN
if (isset($_POST["username"])  && !empty($_POST["username"]) && 
isset($_POST["password"]) && !empty($_POST["password"]))
{
	// die("#".__LINE__." _SESSION:".print_r($_SESSION, true)."<br>\n");
	$m = __LINE__;
	$l = "n:".$_POST["username"].", p:".$_POST["password"]."; md5(p):".md5($_POST["password"]);
	$user = get_userByLogin($_POST["username"], md5($_POST["password"]), $m);
	
	if (isset($user["uid"])) {
		
		$m = __LINE__;
		if ($user["freigegeben"] == "Ja") {
			$m = __LINE__;
			$test = "test";
	        if (function_exists("session_start")) {
				$m = __LINE__;
				$SESS_CName = $_CONF["CAuthSessionName"];
	            
				$_SESSION[$_CONF["CAuthSessionName"]]["username"] = $_POST["username"];
	            $_SESSION[$_CONF["CAuthSessionName"]]["password"] = md5($_POST["password"]);
	            $_SESSION[$_CONF["CAuthSessionName"]]["uid"] = $user["uid"];
	            $_SESSION[$_CONF["CAuthSessionName"]]["remote_addr"]  = $_SERVER["REMOTE_ADDR"];
	            $_SESSION[$_CONF["CAuthSessionName"]]["session_name"] = session_name();
	            $_SESSION[$_CONF["CAuthSessionName"]]["user_agent"]   = $_SERVER["HTTP_USER_AGENT"];
	            if (!isset($_SESSION["REMOTE_ADDR"])) $_SESSION["REMOTE_ADDR"] = $_SERVER["REMOTE_ADDR"];
	            if (!isset($_SESSION["counter"])) $_SESSION["counter"] = 0;
	            $_SESSION["counter"]++;
				// die("#".__LINE__." _SESSION:".print_r($_SESSION, true)."<br>\n");
				if (isset($user["uid"]) && !empty($user["uid"])) {
					$m = __LINE__;
					// SETZE ONLINE-STATUS => LOGIN
					set_lastlogin($ConnUserDB["connid"], $_TABLE["user"], $user["uid"], SET_NEW_VISIT);
				}
				
				// COOKIES UEBER LOGIN-BRUECKE INITIALISIEREN
				if (!strchr($redirect,"?")) $redirect.="?";
				$ausgabe=implode("",file(PATH_TO_LOGIN_MODUL."html/login_bridge.html"));
				$ausgabe=str_replace("%redirect%", $redirect, $ausgabe);
				$ausgabe=str_replace("%name%", $user["vorname"]." ".$user["nachname"], $ausgabe);
				db_close();
				clearstatcache();
				$show_form = false;
	        } else {
	            $error.= "Session konnte nicht gestartet werden!<br>\n";
	        }

		} else {
			$m = __LINE__;
			$error.= "Der Zugang zu diesem Account ist zur Zeit nicht freigegeben!<br>\n";
		}
	} else {
		$m = __LINE__;
		$error.= "Ungültige Zugangsdaten!<br>\n";
	}
} else {
	$m = __LINE__;
	$error.= "Geben Sie E-Mail und Passwort an!<br>\n";
}
