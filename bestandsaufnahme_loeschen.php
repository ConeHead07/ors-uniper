<?php 
require("header.php");
require("bestandsaufnahme_data.class.php");
ob_start();

$SELF  = basename($_SERVER["PHP_SELF"]);
$cat   = getRequest("cat", "mitarbeiter", "PG");
$id    = getRequest("id", "", "PG");
$boxid = getRequest("boxid", "frmEditData", "PG");
$rowid = getRequest("rowId", "", "PG");
$backUpDir = $MConf["AppRoot"]."geloescht/";
$msg_loeschen = "";
$err_loeschen = "";
if (!isset($sendXML)) $sendXML = true;
if (!is_dir($backUpDir)) mkdir($backUpDir);
if (!is_dir($backUpDir)) $backUpDir = $MConf["AppRoot"];

$AjaxUpdates = "";

switch($cat) {
	case "mitarbeiter":
	$sql = "SELECT * FROM `".$_TABLE["mitarbeiter"]."` WHERE id = ".(int)$id;
	$db->query("INSERT INTO `mm_stamm_mitarbeiter_geloescht` ".$sql);
	$db->query_export_csv($sql, $backUpDir."geloeschte_mitarbeiter.csv", ";", "\"", "\"\"", true);
	
	$sql = "DELETE FROM `".$_TABLE["mitarbeiter"]."` WHERE id = ".(int)$id;
	$db->query($sql);
	if (!$db->error()) {
		$msg_loeschen = "Der Mitarbeiterdatensatz wurde gelöscht!<br>\n";
		$AjaxUpdates.= "<Delete id=\"{$rowid}i1\"></Delete>\n";
		$AjaxUpdates.= "<Delete id=\"{$rowid}i2\"></Delete>\n";
	} else {
		$err_loeschen.= "Fehler beim Löschen!<br>\n";
	}
	break;
	
	case "raum":
	$RaumData = new raumdata();
	$RaumData->error = "";
	if ($RaumData->isEmpty($id)) {
		$sql = "SELECT * FROM `".$_TABLE["immobilien"]."` WHERE id = ".(int)$id;
		$db->query("INSERT INTO `mm_stamm_immobilien_geloescht` ".$sql);
		$db->query_export_csv($sql, $backUpDir."geloeschte_raeume.csv", ";", "\"", "\"\"", true);
		if ($RaumData->delete($id)) {
			$msg_loeschen.= "Der Raumdatensatz wurde gelöscht!<br>\n";
		} else {
			$err_loeschen.= "Fehler beim Löschen! ".$RaumData->error."<br>\n";
		}
	} else {
		$err_loeschen.= "Raum kann nicht gelöscht werden! Dem Raum sind noch Mitarbeiter zugeordnet!<br>\n";
	}
	break;
	
	default:
		$err_loeschen.= "Ungültiger Aufruf! (cat:$cat)<br>\n";
	
}

ob_get_contents();
ob_end_clean();

if ($sendXML) {
	$AjaxUpdates.= "<Update id=\"$boxid\"><![CDATA[";
	if ($msg_loeschen) $AjaxUpdates.= "<div class=\"msg\">".$msg_loeschen."</div>\n";
	if ($err_loeschen) $AjaxUpdates.= "<div class=\"err\">".$err_loeschen."</div>\n";
	$AjaxUpdates.= "]]></Update>\n";
	header("Content-Type: text/xml; charset=ISO-8859-1");
	echo '<?xml version="1.0" encoding="ISO-8859-1" ?>
	<Result type="success">'."\n";
	echo $AjaxUpdates;
	echo '</Result>';
}
