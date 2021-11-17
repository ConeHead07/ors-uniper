<?php 
require_once("header.php");
require_once("bestandsaufnahme_data.class.php");
require_once($InclBaseDir."php_json.php");
require_once("sites/umzugsantrag_stdlib.php");
require_once("sites/umzugsantrag_speichern.php");
require_once("sites/umzugsantrag_pruefen.php");
require_once("sites/umzugsantrag_senden.php");
require_once("sites/umzugsantrag_antrag_stornieren.php");
require_once("sites/umzugsantrag_laden.php");
require_once("sites/umzugsantrag_status.php");
require_once("sites/umzugsantrag_status_laden.php");
require_once("sites/umzugsantrag_sendmail.php");
require_once("sites/umzugsantrag_datenuebernehmen.php");
//require_once("sites/mapflege_speichern.php");

require_once($InclBaseDir."umzugsantrag.inc.php");
require_once($InclBaseDir."umzugsmitarbeiter.inc.php");
if (function_exists("activity_log")) register_shutdown_function("activity_log");

//ob_start();
$XDBG = true;
$formHtml = "";
$LoadScript = "";
$boxid = getRequest("boxid", "frmEditData");
$cmd = getRequest("cmd", "senden");
$id = getRequest("id", "");
$name = getRequest("name","");
$value = getRequest("value","");
$SELF = basename($_SERVER["PHP_SELF"]);

switch($cmd) {
	case "mapflege_speichern":
	$ua_errors = umzugsantrag_fehler();
	if (!$ua_errors) {
		$reID = umzugsantrag_speichern();
		if ($reID) {
			umzugsantrag_datenuebernehmen($reID);
			if (!$error) {
				umzugsantrag_status($reID, "abgeschlossen", "Ja");
			}
			$msg.= "MA-Daten wurden in die Bestandsdaten übernommen!<br>\n";
			$LoadScript.= "if (typeof(umzugsantrag_auto_reload)==\"function\") umzugsantrag_auto_reload(\"$id\");\n";
		}
	} else {
		$error.= $ua_errors;
	}
	break;
	
	case "speichern_ohne_status":
	$ua_errors = umzugsantrag_fehler();
	if (!$ua_errors) {
		$reID = umzugsantrag_speichern();
		if ($reID) {
			$msg.= "Ihre Umzugsdaten wurden gespeichert!<br>\n";
			$msg.= "Sie können sie zu einem späteren Zeitpunkt über das Menü 'Umzugsantrag->Meine Bestellungen' öffnen und bearbeiten!<br>\n";
			if ($reID!=$id) $LoadScript.= "if (typeof(umzugsformular_set_id)==\"function\") umzugsformular_set_id('".$reID."'); else alert(\"Fehler beim Setzen der Antrags-ID!\");\n";
			$LoadScript.= "if (typeof(umzugsantrag_auto_reload)==\"function\") umzugsantrag_auto_reload('".$reID."');\n";
		}
	} else {
		$error.= $ua_errors;
	}
	break;
	
	case "speichern":
	$ua_errors = umzugsantrag_fehler();
	if (!$ua_errors) {
		$reID = umzugsantrag_speichern();
		if ($reID) {
			$msg.= "Die Daten wurden aktualisiert!<br>\n";
			if ($reID!=$id) {
			    $LoadScript.= "if (typeof(umzugsformular_set_id)==\"function\") umzugsformular_set_id('".$reID."'); else alert(\"Fehler beim Setzen der Auftrags-ID!\");\n";
            }
			$LoadScript.= "if (typeof(umzugsantrag_auto_reload)==\"function\") umzugsantrag_auto_reload('".$reID."');\n";
		}
	} else {
		$error.= $ua_errors;
	}
	break;
	
	case "senden":
	$ua_errors = umzugsantrag_fehler();
	if (!$ua_errors) {
		//die('#'.__LINE__ . ' ' . __FILE__ . PHP_EOL . ' No-Errors ' . PHP_EOL . print_r($_REQUEST,1));
        // umzugsantrag_senden.php --> umzugsantrag_senden()
        $reID = umzugsantrag_senden();

		if ($reID) {
			$msg.= "<strong>Ihre Bestellung wurde erfolgreich gesendet und wird von uns weiter bearbeitet.</strong><br>\n";
			$msg.= "Den Status Ihres Auftrags können Sie unter <a href='/?s=kantraege'>Meine Bestellungen</a> einsehen!<br>\n";
			$msg.= "<br>\n";
			$msg.= "<strong><a href=\"?s=Umzug\">Zur&uuml;ck zur &Uuml;bersicht</a></strong><br>\n";
			$LoadScript.= "if (typeof(umzugsantrag_close)==\"function\") umzugsantrag_close(\"".json_escape($msg)."\");\n";
			$msg = "";
		}
	} else {
		$hdErr = "<h4 class=\"hdErr\" style=\"color:#f00;\">Achtung - Ihre Bestellung konnte nicht abgesendet werden!</h4>\n";
		$error.= $hdErr.$ua_errors;
	}
	break;
	
	case "stornieren":
	if (umzugsantrag_antrag_stornieren()) {
		$msg.= "Ihre Umzugsdaten wurden storniert!<br>\n";
		$msg.= "Der Status wurde gespeichert, der Antrag selbst kann nicht mehr aufgerufen werden!<br>\n";
		$LoadScript.= "if (typeof(umzugsantrag_clear)==\"function\") umzugsantrag_clear();\n";
	}
	break;
	
	case "laden":
	case "autoreload":
	$formHtml = umzugsantrag_laden();
	if ($formHtml) {
		$msg.= "Ihre Umzugsdaten wurden neu geladen!<br>\n";
	}
	break;
	
	case "status_laden":
	$JsonData = umzugsantrag_status_laden($id);
	if ($JsonData) {
		$LoadScript.= $JsonData;
		$LoadScript.= "alert(\"#".__LINE__." ".basename(__FILE__).");\n";
		$LoadScript.= "if (UmzugsdatenAS && typeof(umzugsantrag_load_status)==\"function\") umzugsantrag_load_status(UmzugsdatenAS); else alert('Fehler beim Laden der Daten');\n";
	}
	break;
	
	case "status":
//	echo "#".__LINE__." ".basename(__FILE__)." status: $id, $name, $value<br>\n";
	$ua_errors = umzugsantrag_fehler();
	if (!$ua_errors) {
		$reID = umzugsantrag_speichern();
		if ($reID) {
			if ($name == "abgeschlossen" && $value == "Ja") {
				umzugsantrag_datenuebernehmen($id);
				if (!$error) {
					umzugsantrag_status($id, $name, $value);
				}
				$msg.= "Aauftrag wurde abgeschlossen!<br>\n";
				$LoadScript.= "if (typeof(umzugsantrag_auto_reload)==\"function\") umzugsantrag_auto_reload(\"$id\");\n";
			} else {
				if (umzugsantrag_status($id, $name, $value)) {
					$msg.= "Der Auftragsstatus wurde aktualisiert: $name -> $value!<br>\n";
					$JsonData = umzugsantrag_status_laden($id);
					if ($JsonData) {
						$LoadScript.= $JsonData;
						$LoadScript.= "if (UmzugsdatenAS && typeof(umzugsantrag_load_status)) umzugsantrag_load_status(UmzugsdatenAS); else alert('Fehler beim Laden der Daten');\n";
					}
				}
			}
		}
	} else {
        $request = $_REQUEST;
        $error.= $ua_errors;
	}
	break;
	
	default:
	$error.= "Ungültiger Seitenaufruf!<br>\n";
}


if ($msg || $error) {
	if ($msg) {
	    $formHtml.= $msg;
    }
	if ($error) {
	    $formHtml.= $error;
    }
	if ($error && $XDBG) {
	    $formHtml.= "<div style=\"border:1px solid #f00;\">"
            . htmlentities(print_r($_POST,1))
            . "</div>\n";
    }
	
	if ($error || $msg) {
		if ($error) $LoadScript.= "\nif (typeof(ErrorBox)==\"function\") ErrorBox(\"".json_escape($error.($msg&&$error?"<br>\n":"").$msg)."\");\n";
		elseif ($cmd!="autoreload" && $msg) $LoadScript.= "\nif (typeof(InfoBox)==\"function\") InfoBox(\"".json_escape($msg)."\");\n";
	}
}

if (!defined("NEWLINE")) define("NEWLINE", "\n");
if (!defined("TAB")) define("TAB", "\n");
header("Content-Type: text/xml; charset=UTF-8");
echo '<?xml version="1.0" encoding="UTF-8" ?>';
echo '<Result type="success">'.NEWLINE;
echo TAB.'<Success>'.(!$error?"TRUE":"FALSE").'</Success>'.NEWLINE;
echo TAB.'<Error><![CDATA['.$error.']]></Error>'.NEWLINE;
echo TAB.'<Msg><![CDATA['.$msg.']]></Msg>'.NEWLINE;
echo TAB.'<Update id="'.(!empty($boxid)?$boxid:"frmEditData").'"><![CDATA['."-".$msg.":".$error."H:".$formHtml.']]></Update>'.NEWLINE;
if (!empty($LoadScript)) echo TAB.'<LoadScript language="javascript" src="cdata"><![CDATA['.NEWLINE.$LoadScript.']]></LoadScript>'.NEWLINE;
//echo TAB.'<POST><![CDATA['.print_r($_POST,1).']]></POST>'.NEWLINE;
//echo TAB.'<GET><![CDATA['.print_r($_GET,1).']]></GET>'.NEWLINE;
echo '</Result>';
