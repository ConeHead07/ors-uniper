<?php 

function check_email($email) {
	$email = trim($email);
	$b = strpos($email," ");
	$k = strpos($email,",");
	$l=strlen($email);
	if ($l >= 7) {
		if  (!is_int($b) && !is_int($k)) {
			$a=strrpos($email,"@");
			$p=strrpos($email,".");
			if (is_int($a) && is_int($p)) {
				if (($p+3)<=$l && ($a+3)<=$p) {
					return true;
				}
			}
		}
	}
	return false;
}

function unique_email($user_connid, $email, $uid = "") {
	global $_TABLE;
	global $error;
	global $sys_error;
	
	$SQL = "SELECT COUNT(*) \n";
	$SQL.= " FROM `".$_TABLE["user"]."` \n";
	$SQL.= " WHERE email LIKE \"".MyDB::escape_string($email)."\" \n";
	if ($uid) {
		$SQL.= " AND uid != \"".MyDB::escape_string($uid)."\" \n";
	}
	$r = MyDB::query($SQL, $user_connid);
	if ($r) {
		list($cnt) = MyDB::fetch_array($r, MyDB::NUM);
		MyDB::free_result($r);
		return ($cnt == 0) ? true : false;
	} else {
		$sys_error.= "<pre>#".__LINE__." ".__FILE__."\n";
		$sys_error.= "Funktion: ".__FUNCTION__."($email)\n";
		$sys_error.= "connid: $user_connid\n";
		$sys_error.= "r: $r\n";
		$sys_error.= "MYSQL: ".MyDB::error()."\n";
		$sys_error.= "QUERY: ".$SQL."</pre>\n";
		//echo $sys_error;
	}
	return false;
}

function unique_fldval($user_connid, $fld, $val, $uid = "") {
	global $_TABLE;
	global $error;
	global $sys_error;
	$SQL = "SELECT COUNT(*) \n";
	$SQL.= " FROM `".$_TABLE["user"]."` \n";
	$SQL.= " WHERE `$fld` LIKE \"".MyDB::escape_string($val)."\" \n";
	if ($uid) {
		$SQL.= " AND uid != \"".MyDB::escape_string($uid)."\" \n";
	}
	$r = MyDB::query($SQL, $user_connid);
	if ($r) {
		list($cnt) = MyDB::fetch_array($r, MyDB::NUM);
		MyDB::free_result($r);
		return ($cnt == 0) ? true : false;
	} else {
		$sys_error.= "<pre>#" . __LINE__ . " " . __FILE__ . "\n";
		$sys_error.= "Funktion: " . __FUNCTION__ . "(connid, '$fld', '$val', '$uid')\n";
		$sys_error.= 'connid: ' . gettype($user_connid) . "\n";
		$sys_error.= "r: $r\n";
		$sys_error.= "MYSQL: " . MyDB::error() . "\n";
		$sys_error.= "QUERY: " . $SQL . "</pre>\n";
	}
	return false;
}

function insert_reguser($user_connid, $tbl, &$arrFormVars, &$authentcode) {
	global $error;
	$SQL = "INSERT INTO `".$tbl."` SET \n";
	$SQL.= " user = \"".MyDB::escape_string($arrFormVars["user"])."\",\n";
	$SQL.= " email = \"".MyDB::escape_string($arrFormVars["email"])."\",\n";
	$SQL.= " pw = \"".MyDB::escape_string(md5($arrFormVars["pw"]))."\",\n";
	$SQL.= " gruppe = \"user\",\n";
	$SQL.= " rechte = \"1\",\n";
	$SQL.= " freigegeben = \"Nein\",\n";
	$SQL.= " anrede = \"".MyDB::escape_string($arrFormVars["anrede"])."\",\n";
	$SQL.= " vorname = \"".MyDB::escape_string($arrFormVars["vorname"])."\",\n";
	$SQL.= " nachname = \"".MyDB::escape_string($arrFormVars["nachname"])."\",\n";
	$SQL.= " personalnr = \"".MyDB::escape_string($arrFormVars["personalnr"])."\",\n";
	$SQL.= " strasse = \"".MyDB::escape_string($arrFormVars["strasse"])."\",\n";
	$SQL.= " plz = \"".MyDB::escape_string($arrFormVars["plz"])."\",\n";
	$SQL.= " ort = \"".MyDB::escape_string($arrFormVars["ort"])."\",\n";
	
	$SQL.= " fon = \"".MyDB::escape_string($arrFormVars["fon"])."\",\n";
	$SQL.= " standort = \"".MyDB::escape_string($arrFormVars["standort"])."\",\n";
	$SQL.= " gebaeude = \"".MyDB::escape_string($arrFormVars["gebaeude"])."\",\n";
	
	$SQL.= " authentcode = \"".MyDB::escape_string($authentcode)."\",\n";
	$SQL.= " registerdate = NOW(),\n";
	$SQL.= " agb_confirm = \"".MyDB::escape_string($arrFormVars["agb_confirm"])."\",\n";
	$SQL.= " datenschutz_confirm = \"".MyDB::escape_string($arrFormVars["datenschutz_confirm"])."\",\n";
	$SQL.= " confirmdate = NULL,\n";
	$SQL.= " lastlogin = NULL,\n";
	$SQL.= " created = NOW()\n";
	MyDB::query($SQL, $user_connid);
	if (!MyDB::error()) {
		return MyDB::insert_id($user_connid);
	} else {
		$error.= MyDB::error()."<br>\n";
		// echo "<pre>".MyDB::error()."\n".$SQL."</pre>";
		return false;
	}
}

function send_regcmail(&$vorlage, &$arrUserVars, $authentcode = "") { // Send Registry-Confirm-Mail
	global $_CONF;
	global $error;
	$mail_text = $vorlage;
	
	foreach($arrUserVars as $k => $v) {
		$mail_text = str_replace("{".$k."}", $v, $mail_text);
	}
	$authentlink = str_replace("{authentcode}", $authentcode, $_CONF["regc_authentlink"]);
	$mail_text = str_replace("{AktivierungsLink}", $authentlink, $mail_text);
	$mail_text = str_replace("{HomepageTitle}", $_CONF["HomepageTitle"], $mail_text);
	$mail_to   = $arrUserVars["email"];

	$mail_su   = $_CONF["regc_subject"];
	$mail_hd   = "\nReply-To: ".$_CONF["email"]["webmaster"];

    if (!SMTP_MAILER_DEBUG) {
        $mail_hd.= "\nBCC: ".$_CONF["email"]["webmaster"];
    }

	$sent = fbmail($mail_to, $mail_su, $mail_text, $mail_hd);
	return $sent;
}

function send_mailcmail(&$vorlage, &$arrUserVars, $authentcode = "") { // Send Mail-Confirm-Mail
	global $_CONF;
	global $error;
	$mail_text = $vorlage;
	foreach($arrUserVars as $k => $v) {
		$mail_text = str_replace("{".$k."}", $v, $mail_text);
	}
	$authentlink = str_replace("{authentcode}", $authentcode, $_CONF["mailc_authentlink"]);
	$mail_text = str_replace("{AktivierungsLink}", $authentlink, $mail_text);
	$mail_text = str_replace("{HomepageTitle}", $_CONF["HomepageTitle"], $mail_text);
	$mail_to   = $arrUserVars["email"];
	$mail_su   = $_CONF["mailc_subject"];
	$mail_hd   = "From: ".$_CONF["email"]["webmaster"]."\nReply-To: ".$_CONF["email"]["webmaster"];
	$sent = fbmail($mail_to, $mail_su, $mail_text, $mail_hd);
	// echo "<pre>#".__LINE__." $sent = fbmail($mail_to, $mail_su, $mail_text, $mail_hd)</pre>\n";
	return $sent;
}

function send_fpw_mail(&$vorlage, &$email, $authentcode = "") { // Send Freischaltcode f. neues Passwort
	global $_CONF;
	global $error;
	$mail_text = $vorlage;
	$mail_text = str_replace("{HomepageTitle}", $_CONF["HomepageTitle"], $mail_text);
	$mail_text = str_replace("{ForgetPWLink}", $_CONF["forget_pw_link"], $mail_text);
	$mail_text = str_replace("{authentcode}", $authentcode, $mail_text);
	$mail_to   = $email;
	$mail_su   = $_CONF["fpw_mail_subject"];
	$mail_hd   = "From: ".$_CONF["email"]["webmaster"]."\nReply-To: ".$_CONF["email"]["webmaster"];
	$sent = fbmail($mail_to, $mail_su, $mail_text, $mail_hd);
	// echo "<pre>#".__LINE__." $sent = fbmail($mail_to, $mail_su, $mail_text, $mail_hd)</pre>\n";
	return $sent;
}

function sendVideoImportStatusMail(&$user, &$videoinfos, $importSuccess) {
	global $_CONF;
	global $error;
	// echo "#".__LINE__." <br>\n";
	
	if ($importSuccess) {
		$mail_text = implode("", file($_CONF["mailc_vimport_text"]));
		$mail_su = $_CONF["mailc_vimport_subject"];
	} else {
		$mail_text = implode("", file($_CONF["maile_vimport_text"]));
		$mail_su = $_CONF["maile_vimport_subject"];
	}
	$mail_text = str_replace("{src_originalname}", $videoinfos["src_originalname"], $mail_text);
	$mail_text = str_replace("{UrlToHomepage}", $_CONF["UrlToHomepage"], $mail_text);
	$mail_text = str_replace("{vorname}", $user["vorname"], $mail_text);
	$mail_text = str_replace("{nachname}", $user["nachname"], $mail_text);
	$mail_to   = $user["email"];
	$mail_hd   = "From: ".$_CONF["email"]["webmaster"]."\nReply-To: ".$_CONF["email"]["webmaster"];
	$sent = fbmail($mail_to, $mail_su, $mail_text, $mail_hd);
	// echo "<pre>#".__LINE__." $sent = fbmail($mail_to, $mail_su, $mail_text, $mail_hd)</pre>\n";
	return $sent;
	/*return true;*/
}


function freeUser($user_connid, $uid) {
	global $_TABLE;
	global $error;
	$SQL = "UPDATE `".$_TABLE["user"]."` SET freigegeben = \"Ja\" \n";
	$SQL.= " WHERE uid = \"".MyDB::escape_string($uid)."\" ";
	MyDB::query($SQL, $user_connid);
	if (!MyDB::error()) return true;
	else $error.= "<pre>#".__LINE__." DB-ERROR:".MyDB::error()."\nDB-QUERY(connid:$user_connid):".fb_htmlEntities($SQL)."</pre>\n";
	return false;
}

function unfreeUser($user_connid, $uid) {
	global $_TABLE;
	global $error;
	$SQL = "UPDATE `".$_TABLE["user"]."` SET freigegeben = \"Nein\" \n";
	$SQL.= " WHERE uid = \"".MyDB::escape_string($uid)."\" ";
	MyDB::query($SQL, $user_connid);
	if (!MyDB::error()) return true;
	else $error.= "<pre>#".__LINE__." DB-ERROR:".MyDB::error()."\nDB-QUERY(connid:$user_connid):".fb_htmlEntities($SQL)."</pre>\n";
	return false;
}

function killUser($user_connid, $uid) {
	global $_TABLE;
	global $error;
	$SQL = "DELETE FROM `".$_TABLE["user"]."` \n";
	$SQL.= " WHERE uid = \"".MyDB::escape_string($uid)."\" ";
	MyDB::query($SQL, $user_connid);
	if (!MyDB::error()) return true;
	else $error.= "<pre>#".__LINE__." DB-ERROR:".MyDB::error()."\nDB-QUERY(connid:$user_connid):".fb_htmlEntities($SQL)."</pre>\n";
	return false;
}
/**/
?>
