<?php 
$SQL = "SELECT uid, confirmdate, freigegeben FROM `".$_TABLE["user"]."`\n";
$SQL.= "WHERE \n";
$SQL.= " authentcode = \"". MyDB::real_escape_string($_GET["ac"])."\"";
$r = MyDB::query($SQL, $ConnUserDB["connid"]);
if ($r) {
	$n = MyDB::num_rows($r);
	if ($n == 1) {
		$e = MyDB::fetch_array($r, MYSQL_ASSOC);
		if (!$e["confirmdate"]) {
                    $SQL = "UPDATE `".$_TABLE["user"]."` SET \n";
                    $SQL.= " confirmdate = NOW(), \n";
                    $SQL.= " freigegeben = 'Ja' \n";
                    $SQL.= "WHERE \n";
                    $SQL.= " authentcode = \"".MyDB::real_escape_string($_GET["ac"])."\"";
                    MyDB::query($SQL, $ConnUserDB["connid"]);
                    if (!MyDB::error()) {
                            //$msg.= "Die automatische Freischaltung wurde vorübergehend deaktiviert!<br>\n";
                            //$msg.= "Ihre E-Mail wurde bestätigt. Die Freigabe wird in Kürze von einem Mitarbeiter bearbeitet!";
                            $msg.= "Ihr Account wurde angelegt und freigeschaltet. Sie können sich ab sofort am System anmelden.";
                    } else {
                            $error.= "Ihr Account konnte leider nicht angelegt werden. Bitte probieren Sie es zu einem späteren Zeitpunkt nocheinmal!<br>\n";
                    }
		} else {
                    if ($e["freigegeben"] == "Nein") {
                            $msg.= "Der Account wurde bereits aktiviert, ist aber zur Zeit gesperrt!<br>\n";
                    } else {
                            $msg.= "Der Account wurde bereits aktiviert. Sie können sich einloggen!<br>\n";
                    }
		}
	}
} else {
	$error.= "Ungültiger oder abgelaufener Freischaltlink!<br>\n";
}


