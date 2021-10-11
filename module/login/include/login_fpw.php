<?php 
$fpwSuccess = false;

// Freischaltcode anfordern
if (isset($_POST["send_fpwmail"]) ) {
	if (!empty($_POST["email"])) {
		$SQL = "SELECT * FROM `".$_TABLE["user"]."` \n";
		$SQL.= "WHERE email LIKE \"".MyDB::escape_string($_POST["email"])."\" \n";
		$SQL.= "LIMIT 1";
		$row = $db->query_row($SQL, $ConnUserDB["connid"]);
		if ($row) {
			if ($row) {
				srand ( (double)microtime () * 1000000 );
				$authentcode = substr(md5(rand().$user["email"].$user["pw"]), 0, 10);
				$user = $row;
				$SQL = "REPLACE `".$_TABLE["newpw"]."` SET \n";
				$SQL.= " uid = \"".$user["uid"]."\", \n";
				$SQL.= " code = \"".$authentcode."\", \n";
				$SQL.= " date = NOW() \n";
				$db->query($SQL);
				if (!$db->error()) {
					$vorlage = implode("", file($_CONF["fpw_mail_text"]));
					if (send_fpw_mail($vorlage, $_POST["email"], $authentcode)) {
						$msg = "E-Mail mit Freischaltcode wurde verschickt!";
					} else {
						$error.= "Interner Fehler: E-Mail mit Freischaltcode konnte nicht versendet werden!<br>\n";
					}
				} else {
					$error.= "Interner Fehler: Freischaltcode konnte nicht erstellt werden!<br>\n";
					// echo "<pre>".MyDB::error()."\n".$SQL."</pre>";
				}
			} else {
				$error.= "Es existiert kein Account mit der E-Mail: ".$_POST["email"]."!<br>\n";
			}
		} else {
			$error.= "Interner Fehler bei Accountabfrage mittel E-Mail!<br>\n";
		}
	} else {
        $error.= "Bitte geben Sie Ihre Mailadresse an!<br>\n";
    }
}

// Neues Passwort mit Freischaltcode anlegen
if (isset($_POST["create_fpw"]) ) {
	if (isset($_POST["email"])) {
		$SQL = "SELECT u.* FROM `".$_TABLE["newpw"]."` AS n \n";
		$SQL.= " LEFT JOIN `".$_CONF["user"]["Table"]."` AS u USING(uid) \n";
		$SQL.= "WHERE n.code LIKE \"".MyDB::escape_string($_POST["code"])."\" \n";
		$SQL.= " AND u.email LIKE \"".MyDB::escape_string($_POST["email"])."\" \n";
		$SQL.= "LIMIT 1";
		$r = MyDB::query($SQL, $ConnUserDB["connid"]);
		if ($r) {
			if (MyDB::num_rows($r)) {
				$user = MyDB::fetch_array($r, MYSQL_ASSOC);
				if ($_POST["pw"] && $_POST["pw"] == $_POST["pwc"]) {
                                        if (!preg_match('/\W/', $_GET["pw"]) ) {
						if (strlen($_POST["pw"]) >= 5) {
							$SQL = "UPDATE `".$_CONF["user"]["Table"]."` SET \n";
							$SQL.= " pw = \"".md5(stripslashes($_POST["pw"]))."\" \n";
							$SQL.= " WHERE uid = \"".$user["uid"]."\" ";
							MyDB::query($SQL);
							if (!MyDB::error()) {
								$SQL = "DELETE FROM `".$_TABLE["newpw"]."` WHERE uid = \"".$user["uid"]."\" ";
								@MyDB::query($SQL, $ConnUserDB["connid"]);
								$msg = "Das Passwort wurde erfolgreich aktualisiert.<br>\n";
								$msg.= "Sie können sich ab sofort einloggen!<br>\n";
								$fpwSuccess = true;
							}
						} else {
							$error.= "Das Passwort muss mind. 5 Zeichen lang sein!<br>\n";
						}
					} else {
						$error.= "Das Passwort darf nur aus Zahlen und Buchstaben bestehen!<br>\n";
					}
				} else {
					$error.= "Passwortwiederholung stimmt nicht überein!<br>\n";
				}
			} else {
				$error.= "Freischaltcode oder E-Mail sind ungültig!<br>\n";
			}
		} else {
			$syserr.= "<pre>#".__LINE__." ".basename(__FILE__)." MYSQL:".MyDB::error()."\nQUERY:".$SQL."</pre>";
		}
	}
}

if (!isset($redirect)) {
	if (isset($_GET["redirect"])) $redirect = $_GET["redirect"];
	elseif (isset($_POST["redirect"])) $redirect = $_POST["redirect"];
	else $redirect = "";
}

$_rpl = array();
$_rpl["{code}"] = (isset($_POST["code"]) ? fb_htmlEntities(stripslashes($_POST["code"])) : "");
$_rpl["{email}"] = (isset($_POST["username"]) ? fb_htmlEntities(stripslashes($_POST["email"])) : "");
$_rpl["{username}"] = $_rpl["{email}"];
$_rpl["{redirect}"] = (isset($redirect)) ? fb_htmlEntities($redirect) : "";
$_rpl["{HomepageTitle}"] = $_CONF["HomepageTitle"];
$_rpl["{action}"] = basename($_SERVER["PHP_SELF"])."?".$_SERVER["QUERY_STRING"];


if (!$fpwSuccess) {
	$content = implode("", file($_CONF["HTML"]["forget_pw"]));
} else {
	$content = implode("", file($_CONF["HTML"]["login"]));
}

