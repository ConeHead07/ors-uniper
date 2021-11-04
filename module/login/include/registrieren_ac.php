<?php


$reqIsPost = ($_SERVER['REQUEST_METHOD'] === 'POST');
if ($reqIsPost) {
    $authentCode = $_POST['authentcode'] ?? '';
} else {
    $authentCode = $_GET['ac'] ?? '';
}
$error = '';
$content = '';
$userRow = '';
$r = 'x';
$SQL = '';
$n = 0;
if ($authentCode) {

    $SQL = "SELECT uid, confirmdate, freigegeben FROM `" . $_TABLE["user"] . "`\n";
    $SQL .= "WHERE \n";
    $SQL .= " authentcode = \"" . MyDB::real_escape_string($authentCode) . "\"
     AND IFNULL(confirmdate, '') = ''";
    $r = MyDB::query($SQL, $ConnUserDB["connid"]);
    if ($r) {
        $n = MyDB::num_rows($r);
        if ($n == 1) {
            $userRow = MyDB::fetch_array($r, MYSQL_ASSOC);
            if ($reqIsPost) {
                if (!$userRow["confirmdate"]) {
                    $SQL2 = "UPDATE `" . $_TABLE["user"] . "` SET \n";
                    $SQL2 .= " confirmdate = NOW(), \n";
                    $SQL2 .= " freigegeben = 'Ja' \n";
                    $SQL2 .= "WHERE \n";
                    $SQL2 .= " authentcode = \"" . MyDB::real_escape_string($authentCode) . "\"";
                    MyDB::query($SQL2, $ConnUserDB["connid"]);
                    if (!MyDB::error()) {
                        //$msg.= "Die automatische Freischaltung wurde vorübergehend deaktiviert!<br>\n";
                        //$msg.= "Ihre E-Mail wurde bestätigt. Die Freigabe wird in Kürze von einem Mitarbeiter bearbeitet!";
                        $msg .= "Ihr Account wurde angelegt und freigeschaltet.<br>\nSie können sich ab sofort am System anmelden.";
                    } else {
                        $error .= "Ihr Account konnte leider nicht angelegt werden.<br>\nBitte probieren Sie es zu einem späteren Zeitpunkt nocheinmal!<br>\n";
                    }
                } else {
                    if ($userRow["freigegeben"] == "Nein") {
                        $msg .= "Der Account wurde bereits aktiviert, ist aber zur Zeit gesperrt!<br>\n";
                    } else {
                        $msg .= "Der Account wurde bereits aktiviert. Sie können sich einloggen!<br>\n";
                    }
                }
            } else {
                $content = implode("", file($_CONF["HTML"]["authentcode_form"]));
                $content = str_replace('{/*authentcodeJson*/}', json_encode($authentCode), $content);
            }
        } else {
            $error .= "Ungültiger oder abgelaufener Freischaltlink!<br>\n";
        }
    } else {
        $error .= "Ungültiger oder abgelaufener Freischaltlink!<br>\n";
    }
}
// die('#' . __LINE__ . ' ' . __FILE__ . ' ' . $SQL . ' n:' . $n . ' ' . print_r(compact('authentCode', 'SQL', 'error', 'content', 'r', 'userRow'),1));


