<?php 
require("header.php");

ob_start();

$SELF  = basename($_SERVER["PHP_SELF"]);
$cat   = getRequest("cat", "mitarbeiter", "PG");
$id    = getRequest("id", "", "PG");
$boxid = getRequest("boxid", "frmEditData", "PG");
$rowid = getRequest("rowId", "", "PG");

$AjaxUpdates = "";

switch($cat) {
	case "mitarbeiter":
	$sql = "DELETE FROM `".$_TABLE["mitarbeiter"]."` WHERE id = ".(int)$id;
	$db->query($sql);
	if (!$db->error()) {
		$AjaxUpdates.= "<Update id=\"$boxid\"><![CDATA[Der Mitarbeiterdatensatz wurde gelöscht! ]]></Update>\n";
		$AjaxUpdates.= "<Delete id=\"{$rowid}i1\"></Delete>\n";
		$AjaxUpdates.= "<Delete id=\"{$rowid}i2\"></Delete>\n";
	} else {
		$AjaxUpdates.= "<Update id=\"$boxid\"><![CDATA[Fehler beim Löschen! ]]></Update>\n";
	}
	break;
	
	default:
		$AjaxUpdates.= "<Update id=\"$boxid\"><![CDATA[Ungültiger Aufruf! (cat:$cat) ]]></Update>\n";
	
}

ob_get_contents();
ob_end_clean();

	header("Content-Type: text/xml; charset=ISO-8859-1");
	echo '<?xml version="1.0" encoding="ISO-8859-1" ?>
	<Result type="success">'."\n";
	echo $AjaxUpdates;
	echo '</Result>';
