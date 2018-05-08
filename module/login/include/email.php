<?php 
$mailSuccess = false;

// Neues Passwort erstellen
if (isset($_POST["email"]) ) {
	$email = stripslashes($_POST["email"]);
	$emailc = (isset($_POST["emailc"])) ? stripslashes($_POST["emailc"]) : "";
	if ($email && $email == $emailc) {
		if (check_email($_POST["email"])) {
			if (unique_email($ConnUserDB["connid"], $email, $user["uid"]) ) {
				srand ( (double)microtime () * 1000000 );
				$authentcode = substr(md5(rand().$user["email"]), 0, 10);
				
				$SQL = "REPLACE `".$_TABLE["newemail"]."` SET \n";
				$SQL.= " uid = \"".$user["uid"]."\", \n";
				$SQL.= " email = \"".MyDB::escape_string($email)."\", \n";
				$SQL.= " code = \"".MyDB::escape_string($authentcode)."\", \n";
				$SQL.= " date = NOW() \n";
				MyDB::query($SQL);
				if (!MyDB::error()) {
					$arrFormVars = $user;
					$arrFormVars["email"] = $email;
					$vorlage = implode("", file($_CONF["mailc_change_text"]));
					if (send_mailcmail($vorlage, $arrFormVars, $authentcode)) {
						$msg = "Die neue E-Mail wurde gespeichert ";
						$msg.= "und muss von Ihnen noch bestätigt werden!<br>\n";
						$msg.= "Zu diesem Zweck erhalten Sie eine E-Mail mit einem Freischaltlink ";
						$msg.= "an die neue E-Mail-Adresse.<br>\n";
						$mailSuccess = true;
					} else {
						$error.= "Interner Fehler: Bitte probieren Sie es zu einem späteren Zeitpunkt noch einmal!<br>\n";
					}
				} else {
					$error.= "Interner Fehler: Freischaltcode konnte nicht erstellt werden!<br>\n";
					echo "<pre>".MyDB::error()."\n".$SQL."</pre>";
				}

			} else {
				$error.= "Die E-Mail ist bereits von einem anderen Mitglied besetzt!<br>\n";
			}
		} else {
			$error.= "Ungültige E-Mail!<br>\n";
		}
	} else {
		$error.= "E-Mail-Wiederholung stimmt nicht überein!<br>\n";
	}
}
/**/

if (!$mailSuccess) {
	$content.= implode("", file(PATH_TO_LOGIN_MODUL."html/email_aendern.html"));
}

?>