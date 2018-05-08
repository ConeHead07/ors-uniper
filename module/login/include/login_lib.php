<?php 

function get_sql_create_usertbl($table) {
	$SQL = "CREATE TABLE IF NOT EXISTS `".MyDB::escape_string($table)."` (
 `uid` int(11) NOT NULL AUTO_INCREMENT,
 `user` varchar(50) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
 `email` varchar(50) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
 `pw` varchar(32) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
 `gruppe` enum('user','admin') COLLATE latin1_german1_ci NOT NULL DEFAULT 'user',
 `freigegeben` enum('init','Nein','Ja') COLLATE latin1_german1_ci NOT NULL DEFAULT 'Nein',
 `anrede` enum('Frau','Herr') COLLATE latin1_german1_ci NOT NULL DEFAULT 'Frau',
 `vorname` varchar(50) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
 `nachname` varchar(50) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
 `firma` char(50) COLLATE latin1_german1_ci DEFAULT NULL,
 `standort` char(50) COLLATE latin1_german1_ci DEFAULT NULL,
 `authentcode` varchar(10) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
 `registerdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 `confirmdate` datetime DEFAULT NULL,
 `lastlogin` datetime DEFAULT NULL,
 `lastvisit` datetime DEFAULT NULL,
 `lastvisitbefore` datetime DEFAULT NULL,
 `onlinestatus` enum('online','loggedout','timeout') COLLATE latin1_german1_ci NOT NULL DEFAULT 'loggedout',
 PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci";
	return $SQL;
}

/*
CREATE TABLE IF NOT EXISTS `mm_user_newemail` (
`uid` int( 11 ) NOT NULL DEFAULT '0',
`email` varchar( 50 ) NOT NULL DEFAULT '',
`code` varchar( 10 ) NOT NULL DEFAULT '',
`date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY ( `uid` )
) ENGINE = MYISAM ;

CREATE TABLE IF NOT EXISTS `mm_user_newpw` (
`uid` int( 11 ) NOT NULL DEFAULT '0',
`code` char( 10 ) NOT NULL DEFAULT '',
`date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY ( `uid` )
) ENGINE = MYISAM ;
*/

function create_user_table($table, $SQL = "") {
	if (!$SQL) $SQL = get_sql_create_usertbl($table);
	MyDB::query($SQL);
}

function kill_sessionsByTimeout($user_connid, $tbl, $minutes) {
	$SQL = "UPDATE $tbl SET \n";
	$SQL.= " onlinestatus = 'timeout' \n";
	$SQL.= " WHERE lastlogin < DATE_SUB(NOW(),INTERVAL ".$minutes." MINUTE) ";
	MyDB::query($SQL, $user_connid);
}

function get_username() {
	global $_COOKIE;
    global $_CONF;
    global $_SESSION;
    global $_SERVER;
    
    if (@isset($_SESSION[$_CONF["CAuthSessionName"]]["username"])) {
        return $_SESSION[$_CONF["CAuthSessionName"]]["username"];
	} elseif (isset($_COOKIE["azbcu"])) {
		return $_COOKIE["azbcu"];
	}
    return "";
}

function get_userid() {
    global $_CONF;
    global $_SESSION;
    
    if (@isset($_SESSION[$_CONF["CAuthSessionName"]]["uid"])) {
        return $_SESSION[$_CONF["CAuthSessionName"]]["uid"];
	}
    return "";
}

function get_userpass() {
	global $_COOKIE;
    global $_CONF;
    global $_SESSION;
    global $_SERVER;
    
	if (@isset($_SESSION[$_CONF["CAuthSessionName"]]["password"])) {
        //echo "session_name:".$_SESSION[$_CONF["CAuthSessionName"]]["session_name"]."<br>\n";
        if (!@isset($_SESSION[$_CONF["CAuthSessionName"]]["session_name"])) {
            return "";
        }
        $SessCookieName = $_SESSION[$_CONF["CAuthSessionName"]]["session_name"];
        if (!isset($_COOKIE[$SessCookieName])) {
            return "";
        }
		
        if ($_SESSION[$_CONF["CAuthSessionName"]]["remote_addr"] != $_SERVER["REMOTE_ADDR"]) {
            return "";
        }
        if ($_SESSION[$_CONF["CAuthSessionName"]]["user_agent"]  != $_SERVER["HTTP_USER_AGENT"]) {
            return "";
        }
        return $_SESSION[$_CONF["CAuthSessionName"]]["password"];
        
	} elseif(isset($_COOKIE["azbcp"])) {
		return $_COOKIE["azbcp"];
	}
    return "";
}

function get_userByLogin($uname, $upass, $m = "") {
        $db = dbconn::getInstance();
	global $_TABLE;
	global $ConnUserDB;
	$SQL = "SELECT * FROM `".$_TABLE["user"]."` \n";
	$SQL.= " WHERE user LIKE \"".$db->escape($uname)."\" \n";
	$SQL.= " AND pw = \"".$db->escape($upass)."\" \n";
	$SQL.= " LIMIT 1";
        $result = $db->query($SQL);
        if (!$result || !is_object($result) || !$result->num_rows) {
            return null;
        }
        
	$user= single_resultquery($SQL);
	// echo "<pre>#".__LINE__." m:$m, ConnDB[connid]:{$ConnUserDB['connid']} uname:$uname, upass:$upass, ".MyDB::error()."\n".fb_htmlEntities($SQL)."</pre>\n";
	return $user;
}

function get_userById($uid) {
        $db = dbconn::getInstance();
	global $_TABLE;
	global $ConnUserDB;
	$SQL = "SELECT * FROM `".$_TABLE["user"]."` \n";
	$SQL.= " WHERE uid like \"".$db->escape($uid)."\" \n";
	$SQL.= " LIMIT 1";
	$user=single_resultquery($SQL);
	if (MyDB::error()) echo "<pre>#".__LINE__." DB-ERROR:".MyDB::error()."\nDB-QUERY(connid:$user_connid):".fb_htmlEntities($SQL)."</pre>\n";
	return $user;
}

function set_navigation(&$user, &$ausgabe, &$navtitle) {
	switch($user["gruppe"]) {
		case "user":
		$nav_file = "../html/nav_kunde.html";
		break;
		
		case "admin":
		$nav_file = "../html/nav_admin.html";
		break;
		
		default:
		$nav_file = "";
	}
	
	//echo "#110 nav_file: $nav_file<br>\n";
	if ($nav_file) {
		$navigation = implode("",file($nav_file));
		$navigation = str_replace("%navtitle%",$navtitle,$navigation);
		if (strchr($ausgabe,"%navigation%")) {
			$ausgabe = str_replace("%navigation%",$navigation,$ausgabe);
			return $ausgabe;
		} else {
			return $navigation;
		}
	}
	return $ausgabe;
}

function get_userBySession() {
	$sess_userid  = get_userid();
	if ($sess_userid) {
		$user = get_userById($sess_userid);
	}
	if (!isset($user) || !count($user)) {
		$sess_username= get_username();
		$sess_userpass= get_userpass();
		$user = get_userByLogin($sess_username, $sess_userpass);
	}
	if (!@empty($user["freigegeben"]) && $user["freigegeben"] == "Ja" && $user["onlinestatus"] == "online") {
		return $user;
	}
	return false;
}

function set_lastlogin($user_connid, $tbl, $uid, $setNewVisit = false) {
	$SQL = "UPDATE $tbl SET \n";
	if ($setNewVisit) {
		$SQL.= " lastvisit = NOW(), \n";
		$SQL.= " lastvisitbefore = lastvisit, \n";
		$SQL.= " onlinestatus = 'online', \n";
	}
	$SQL.= " lastlogin = NOW() \n";
	$SQL.= " WHERE uid = '".$uid."'";
	MyDB::query($SQL, $user_connid);
}
//echo "#".__LINE__." ".basename(dirname(__FILE__))."/".basename(__FILE__)." ".date("Y-m-d H:i:s")." <br>\n";