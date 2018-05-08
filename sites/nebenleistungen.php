<?php 
require_once($InclBaseDir."nebenleistungen.inc.php");
require_once($InclBaseDir."nebenleistungsanlagen.inc.php");
$NATConf = &$_CONF["nebenleistungsanlagen"];

$cat = getRequest("cat");
$id = getRequest("id");

if (!in_array($cat, array("Neu","Beauftragt","Abgelehnt","Abgeschlossen"))) $cat = "Neu";
$Tpl = new myTplEngine();
$Tpl->assign("s", $s);
$Tpl->assign("cat", $cat);
$body_content.= $Tpl->fetch("admin_nebenleistungen_listenregister.html");
$sTrackGetData = "&cat=$cat";
$_CONF["nebenleistungen"]["Lists"][0]["where"] = " status = \"".$db->escape($cat)."\"";

if (!empty($_POST["eingabe"]) && $id) {
	$sql = "SELECT aufgabe FROM `".$_TABLE["nebenleistungen"]."` WHERE id = \"".$db->escape($id)."\" LIMIT 1";
	$row = $db->query_singlerow($sql);
	if ($row["aufgabe"]) {
		$fieldVals["aufgabe"] = "";
		if ($_POST["eingabe"]["aufgabe"]) {
			$fieldVals["aufgabe"] = "Bemerkung von ".$user["user"]." am ".date("d.m.Y")." um ".date("H:i").":\n";
			$fieldVals["aufgabe"].= $_POST["eingabe"]["aufgabe"]."\n****\n";
		}
		$fieldVals["aufgabe"].= $row["aufgabe"];
	}
}

$showConfData = false;
$confName = "nebenleistungen";
$_CONF[$confName]["FormInput"] = "html/admin_nebenleistungen_eingabe.html";
include($ModulBaseDir."editdatabyconf/edit_data.inc.php");
$viewForm = ($inputItem->id && $editCmd && $editCmd == "Edit");

if ($viewForm) {
	$sql = "SELECT dokid FROM `".$NATConf["Table"]."` WHERE nid = ".intval($inputItem->id);
	$aATs = $db->query_rows($sql);
	$NLAnlagenListe = "";
	
	if (is_array($aATs) && count($aATs)) {
		$NLAnlagenListe.= "<ul class=\"ulAttachements\">\n<strong>Dateianhänge</strong><br>\n";
		for($i = 0; $i < count($aATs); $i++) {
			$DOKID = $aATs[$i]["dokid"];
			$NAT = new ItemEdit($NATConf, $connid, $user, $DOKID);
			$NAT->dbdataToInput();
			$aNAtItems[$i] = $NAT->arrInput;
			$NLAnlagenListe.= "<li><a href=\"attachements/".$NAT->arrInput["dok_datei"]."\" target=\"_blank\">".$NAT->arrInput["dok_datei"]."</a> ".format_file_size($NAT->arrInput["dok_groesse"])."</li>\n";
		}
		$NLAnlagenListe.= "</ul>\n";
	} else {
		$NLAnlagenListe = "<strong>Dateianhänge: </strong><em>keine</em><br>\n";
	}
	$body_content = str_replace("{NLAnlagenListe}", $NLAnlagenListe, $body_content);
} else {
	
}
?>