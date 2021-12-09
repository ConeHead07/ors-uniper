<?php

if (false && date("Y-m-d H:i") > "2016-01-30 13:00" && date("Y-m-d H:i") < "2016-01-30 15:00") {
    die("Das Auftragsportal ist vorübergehend bis voraussichtlich 13.30 Uhr wegen Wartungsarbeiten gesperrt!");
}

// Delegate static file requests back to the PHP built-in webserver
if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
    return false;
}

error_reporting(E_ALL);
require_once("header.php");

$topmenu = "";
$bodby_content = "";
$assetsRefreshId = '202112092100';

// Seitenbereiche
require_once $MConf["AppRoot"].$MConf["Inc_Dir"]."lib_admin_cms.php";
require_once $MConf["AppRoot"].$MConf["Modul_Dir"]."seitenbereiche/include/seitenbereich_conf.php";
require_once $MConf["AppRoot"].$MConf["Modul_Dir"]."seitenbereiche/include/seitenbereiche_lib.php";
require_once $MConf["AppRoot"].$MConf["Modul_Dir"]."seitenbereiche/include/lib_menutree.php";
require_once $MConf["AppRoot"].$MConf["Modul_Dir"]."seitenbereiche/include/lib_menues_render.php";
require_once $MConf["AppRoot"].$MConf["Inc_Dir"]."/lib_menu_embed_functions.php";

$_rplAusgabe[0]['%assetsRefreshId%'] = $assetsRefreshId;
$_rplAusgabe[0]["{theme}"] = $MConf["theme"];
$_rplAusgabe[0]['<!-- {Headers} -->'] =
    "<link rel=\"stylesheet\" href=\"css/"
    . (!empty($userlogin["uid"]) ? "ulogin.css" : "ulogout.css?$assetsRefreshId")."\">\n";

$_rplAusgabe[0]["{pageTitle}"]  = $MConf["AppTitle"];
$_rplAusgabe[0]["{user.uid}"]  = $user['uid'];
$_rplAusgabe[0]["{user.rechte}"]  = $user['rechte'];
$_rplAusgabe[0]["{user.name}"]  = $user['user'];
$_rplAusgabe[0]["{user.gruppe}"] = ($user["adminmode"]==="superadmin" ? $user["adminmode"] : $user['gruppe']);
$_rplAusgabe[0]["{user.anrede}"] = $user['anrede'];
$_rplAusgabe[0]["{user.vorname}"] = $user['vorname'];
$_rplAusgabe[0]["{user.nachname}"] = $user['nachname'];
$body_content = '';

$pview = $_GET['pview'] ?? $_POST['pview'] ?? '';

if (!isset($pview) || $pview != "body") {
    $ausgabe = implode("", file($_CONF["HTML"]["ausgabe"]));
} else {
    $ausgabe = implode("", file($_CONF["HTML"]["body"]));
    $aTrackVars["pview"] = $pview;
}

$s = $s = getRequest("s","start");
$srv = $s;

include($MConf["AppRoot"].$MConf["Modul_Dir"]."seitenbereiche/public_get_seitenbereiche.php");

// START: Load Dynamic Menu
$ActiveMenu = get_menu_bySrv($srv);

// echo "<pre>#".__LINE__." print_r(ActiveMenu):".print_r($ActiveMenu, true)."</pre>\n";
$srv_error = false;

$MenuAccess = "User Rechte:".$user["rechte"]." Gruppe:".$user["gruppe"]." Freigegeben:".$user["freigegeben"]." Adminmode:".$user["adminmode"]."<br>\n";
if (is_array($ActiveMenu)
    && isset($ActiveMenu["webfreigabe"])
    && $ActiveMenu["webfreigabe"] == "Ja") {
    $_ParentItems = get_menu_parentItems($ActiveMenu["id"]);

    $iNumParentItems = count($_ParentItems);

    for ($i = 0; $i < $iNumParentItems; $i++)
    {
        $M = $_ParentItems[$i];

        $MenuAccess.= "Menü {$M['srv']} Rechte:{$M['rechte']} Gruppen:{$M['gruppen']} Geschützt:{$M['geschuetzt']}<br>\n";

        if ($_ParentItems[$i]["webfreigabe"] == "Nein") {
            $srv_error = true;
        }
        $r_psrv = $_ParentItems[$i]["srv"];
        if ($_ParentItems[$i]["geschuetzt"] == "Ja") {
            if (empty($user)) {
                $srv_error = true;
                $error.= "Zugang nur für Mitglieder!<br>\n";
                $show_login = true;
            } else {

                switch($_ParentItems[$i]["gruppen"]) {
                    case "admin":
                        if ($user["gruppe"] != "admin") {
                            $error.= "Unzulässiger Seitenaufruf!<br>\n";
                            $srv_error = true;
                        }
                        break;


                    case "user":
                        // Sollte alles Ok sein, da es nur admin oder user als gruppe gibt
                        break;

                    default:
                        //echo "#".__LINE__." ".basename(__FILE__)." [gruppen]".$_ParentItems[$i]["gruppen"]."<br>\n";
                        // Komisch, print_r geht der Sache auf den Grund
                        // print_r($_ParentItems[$i]);
                }
            }
        }

        if (!$srv_error) {
            $_rplAusgabe[1][" active=\"".$r_psrv."\" class=\""] = " active=\"".$r_psrv."\" class=\"liActive ";
        }
        if (!$srv_error && $ActiveMenu["redirect"]) {
            header("Location:".$ActiveMenu["redirect"]);
            echo "<a href=\"".$ActiveMenu["redirect"]."\">Weiterleitung</a><br>\n";
            echo "<script>self.location.href=\"".$ActiveMenu["redirect"]."\";</script>\n";
        }
        $_rplAusgabe[1][" active=\"".$srv."\" class=\""] = " active=\"".$srv."\" class=\"liActive ";
    }
    if (!$srv_error) {
        $_rplAusgabe[0]["{pageTitle}"] = $ActiveMenu["name"];
    }
} else {
    $srv_error = true;
}
/**/

if ($srv_error && empty($show_login)) {
    $body_content.= "<h3>Die angeforderte Seite wurde nicht gefunden.</h3>\n";
    $body_content.= "Falls Sie die Adresse von Hand eingetippt haben, prüfen Sie bitte die korrekte Schreibweise!\n";
    $body_content.= "Möglicherweise befindet sich die Seite derzeit in Bearbeitung und wurde gesperrt!<br>\n";
    $body_content.= "<br>\n";
    $body_content.= "Bei Fragen zur Erreichbarkeit der gewünschten Informationen können Sie uns gerne eine E-Mail schreiben an <a href=\"mailto:$postmaster?subject=Seitenfehler:$srv\">$postmaster</a><br>\n";
} else {

    if ($ActiveMenu["cmd"]) {
        if (is_int(strpos($ActiveMenu["cmd"], "&"))) {
            $cmdPreFix = (substr($ActiveMenu["cmd"], 0, 4) != "cmd=") ? "cmd=" : "";
            $sCmd = $cmdPreFix.$ActiveMenu["cmd"];
            parse_str($sCmd);
            // echo $sCmd;
        } else {
            $cmd = $ActiveMenu["cmd"];
        }
    }
    if (!isset($_rplAusgabe[0]["<!-- {Headers} -->"])) {
        $_rplAusgabe[0]["<!-- {Headers} -->"] = "";
    }

    if ($ActiveMenu["script"]) {
        if (file_exists($ActiveMenu["script"])) {
            require($ActiveMenu["script"]);
        } else {
            $error.= "Interner Fehler:".$ActiveMenu["script"]." (id:".$ActiveMenu["id"].")<br>\n";
            $error.= "Das Ausgabemodul für die Seite \"".$ActiveMenu["name"]."\" konnte nicht geladen werden!<br>\n";
        }
    }
}
//$body_content.= $MenuAccess."<br>\n";

if (!empty($aTrackVars)) {
    $_rplAusgabe[1]['%assetsRefreshId%'] = $assetsRefreshId;
    $_rplAusgabe[1]["{trackVars}"] = "";
    $_rplAusgabe[1]["<!-- {trackPostVars} -->"] = "";

    foreach($aTrackVars as $k => $v) {
        $_rplAusgabe[1]["{trackVars}"].= "&$k=".rawurlencode($v);
        $_rplAusgabe[1]["<!-- {trackPostVars} -->"].=
            "<input type=\"hidden\" name=\"$k\" value=\"".fb_htmlEntities($v)."\">\n";
    }
}

$sysbox = "";
if ($error) {
    if (file_exists($HtmlBaseDir."errorbox.html"))
        $sysbox.= str_replace("{txt}", $error, file_get_contents($HtmlBaseDir."errorbox.html"));
    else
        $sysbox.= "<div class=\"errBox\">$error</div>\n";
}
if ($msg) {
    if (file_exists($HtmlBaseDir."msgbox.html"))
        $sysbox.= str_replace("{txt}", $msg, file_get_contents($HtmlBaseDir."msgbox.html"));
    else
        $sysbox.= "<div class=\"msgBox\">$error</div>\n";
}


$ausgabe = str_replace("{content}", $sysbox.$body_content, $ausgabe);
$iNumRplAusgabe = count($_rplAusgabe);
for ($i = 0; $i < $iNumRplAusgabe; $i++) {
    $ausgabe = strtr($ausgabe, $_rplAusgabe[$i]);
}
// die(print_r(compact('_rplAusgabe'), 1));

if (!empty($SiteVars)) foreach($SiteVars as $k => $v) {
    $ausgabe=str_replace("{".$k."}", $v, str_replace("%".$k."%", $v,$ausgabe));
}
echo $ausgabe;

