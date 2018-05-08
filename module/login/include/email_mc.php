<?php 

$SQL = "SELECT * FROM `".$_TABLE["newemail"]."`\n";
$SQL.= "WHERE \n";
$SQL.= " code = \"".MyDB::escape_string($_GET["mc"])."\"";
$r = MyDB::query($SQL, $ConnUserDB["connid"]);
if ($r) {
	$n = MyDB::num_rows($r);
	if ($n == 1) {
		$e = MyDB::fetch_array($r, MYSQL_ASSOC);
		$SQL = "UPDATE `".$_TABLE["user"]."` SET \n";
		$SQL.= " email = \"".MyDB::escape_string($e["email"])."\" \n";
		$SQL.= "WHERE \n";
		$SQL.= " uid = \"".$e["uid"]."\"";
		MyDB::query($SQL, $ConnUserDB["connid"]);
		if (!MyDB::error()) {
			$SQL = "DELETE FROM `".$_TABLE["newemail"]."` WHERE uid = \"".$e["uid"]."\"";
			MyDB::query($SQL, $ConnUserDB["connid"]); 
			$msg.= "Ihre neue E-Mail ".$e["email"]." wurden soeben in Ihrem Account aktualisiert!<br>\n";
		} else {
			$error.= "Ihre neue E-Mail konnte leider nicht übernommen werden. Bitte probieren Sie es zu einem späteren Zeitpunkt nocheinmal!<br>\n";
			// $error.= "<pre>#".__LINE__." DB-ERROR:".MyDB::error()."\nDB-QUERY:".fb_htmlEntities($SQL)."</pre>\n";
		}
	}
} else {
	$error.= "Ungültiger oder abgelaufener E-Mail-Freischaltlink!<br>\n";
}

?>
