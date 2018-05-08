<?php 

if (strpos($user["gruppe"], "kunde_report") === false && strpos($user["adminmode"], "superadmin") === false)
	die('UNERLAUBTER ZUGRIFF! Zugriff nur für ' . $MConf['propertyName'] . ' Property');

$Tpl = new myTplEngine();
require_once($InclBaseDir."nebenleistungen.inc.php");
require_once($InclBaseDir."nebenleistungsanlagen.inc.php");
$NLConf = $_CONF["nebenleistungen"];
$NATConf = &$_CONF["nebenleistungsanlagen"];
$NID = getRequest("id",'');
$NLInput = getRequest("NL");
$nebenleistung_form = "property_nebenleistung_lesen.html";

if (empty($NID)) $NID = (!empty($_POST["NL"]["id"]) ? $_POST["NL"]["id"] : (!empty($_GET["NL"]["id"]) ? $_GET["NL"]["id"] : ''));

$NL = new ItemEdit($NLConf, $connid, $user, $NID);

$save_success = false;

if ($NID && $NLInput) {
	
	if (trim($NLInput["aufgabe"])) {
		$addBemerkung = $NLInput["aufgabe"];
		$NL->loadDbdata();
		$NL->dbdataToInput();
		$NL->arrInput["aufgabe"] = "Bemerkung von ".$user["user"]." am ".date("d.m.Y")." um ".date("H:i").":\n";
		$NL->arrInput["aufgabe"].= trim($addBemerkung)."\n\n";
		$NL->arrInput["aufgabe"].= $NL->arrDbdata["aufgabe"];
		$NL->save();
		if (!$NL->Error) $NID = $NL->id;
	}
	if (!$NL->Error) {
		$body_content.= "Eintrag wurde gespeichert!<br>\n";
		$save_success = true;
	} else {
		$body_content.= $NL->Error;
		$body_content.= $NL->dbError;
	}
} else {
	// If AID: Bearbeitungsformular mit DB-Daten
	if ($NID) {
		$NL->loadDbdata();
		$NL->dbdataToInput();
		
	} else {
		// else: lade Eingabeformular
		$NL->loadInput(array(), false);
	}
}

$sql = "SELECT dokid FROM `".$NATConf["Table"]."` WHERE nid = ".intval($NID);
$aATs = $db->query_rows($sql);

for($i = 0; $i < count($aATs); $i++) {
	$DOKID = $aATs[$i]["dokid"];
	$NAT = new ItemEdit($NATConf, $connid, $user, $DOKID);
	$NAT->dbdataToInput();
	$aNAtItems[$i] = $NAT->arrInput;
}

if (!$save_success) {
	$Tpl->assign("NL", $NL->arrInput);
	if (!empty($aNAtItems) && count($aNAtItems)) $Tpl->assign("UmzugsAnlagen", $aNAtItems);
	$Tpl->assign("s", $s);
	$Tpl->assign("WebRoot", $MConf["WebRoot"]);
	$body_content.= $Tpl->fetch($nebenleistung_form);
} else {
	include($MConf["AppRoot"]."sites".DS."property_nebenleistungen.php");
}

?>