<?php 
$pwSuccess = false;

// Neues Passwort erstellen
if (isset($_POST["oldpw"]) ) {
	$SQL = "SELECT uid FROM `".$_TABLE["user"]."` \n";
	$SQL.= "WHERE uid LIKE \"".MyDB::escape_string($user["uid"])."\" \n";
	$SQL.= " AND pw LIKE \"".MyDB::escape_string(md5($_POST["oldpw"]))."\" \n";
	$SQL.= "LIMIT 1";
	$r = MyDB::query($SQL, $ConnUserDB["connid"]);
	if ($r) {
		if (MyDB::num_rows($r)) {
			MyDB::free_result($r);
			if ($_POST["pw"] && $_POST["pw"] == $_POST["pwc"]) {
				if (!preg_match('\W', $_GET["t"]) ) {
					if (strlen($_POST["pw"]) >= 5) {
						$SQL = "UPDATE `".$_TABLE["user"]."` SET \n";
						$SQL.= " pw = \"".md5(stripslashes($_POST["pw"]))."\" \n";
						$SQL.= " WHERE uid = \"".$user["uid"]."\" ";
						MyDB::query($SQL);
						if (!MyDB::error()) {
							$msg.= "Das Passwort wurde erfolgreich geändert<br>\n";
							$msg.= "und ist ab sofort gültig!<br>\n";
							$pwSuccess = true;
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
			$error.= "Altes Passwort ist falsch!<br>\n";
		}
	} else {
		echo "<pre>".MyDB::error()."\n".$SQL."</pre>";
	}
}

if (!$pwSuccess) {
	$form = implode("", file(PATH_TO_LOGIN_MODUL."html/passwort_aendern.html"));
	$form = str_replace("{action}", basename($_SERVER["PHP_SELF"])."?".$_SERVER["QUERY_STRING"], $form);
	$content.= $form;
}

?>