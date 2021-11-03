<?php 
if (strpos($user["gruppe"], "admin") === false) die("UNERLAUBTER ZUGRIFF! Zugriff nur f�r Administratoren");

$Tpl = new myTplEngine();
require_once($InclBaseDir."nebenleistungen.inc.php");
$NLConf = $_CONF["nebenleistungen"];

require_once($InclBaseDir."nebenleistungsanlagen.inc.php");
$NATConf = &$_CONF["nebenleistungsanlagen"];

$NID = getRequest("id",'');
$NLInput = getRequest("NL");

if (empty($NID)) $NID = (!empty($_POST["NL"]["id"]) ? $_POST["NL"]["id"] : (!empty($_GET["NL"]["id"]) ? $_GET["NL"]["id"] : ''));

$NL = new ItemEdit($NLConf, $connid, $user, $NID);

if ($NID) {
	$NL->loadDbdata();
	$NL->dbdataToInput();
	
	$sql = "SELECT dokid FROM `".$NATConf["Table"]."` WHERE nid = ".intval($NID);
	$aATs = $db->query_rows($sql);
	
	for($i = 0; $i < count($aATs); $i++) {
		$DOKID = $aATs[$i]["dokid"];
		$NAT = new ItemEdit($NATConf, $connid, $user, $DOKID);
		$NAT->dbdataToInput();
		$aNAtItems[$i] = $NAT->arrInput;
	}
	
	$Tpl->assign("NL", $NL->arrInput);
	$Tpl->assign("AppTitle", $MConf["AppTitle"]);
	$Tpl->assign("WebRoot", $MConf["WebRoot"]);
	if (!empty($aNAtItems) && count($aNAtItems)) $Tpl->assign("UmzugsAnlagen", $aNAtItems);
	$body_content.= $Tpl->fetch("nebenleistung_druckansicht.html");
} else {
	// else: lade Eingabeformular
	$body_content.= "Es existiert kein Eintrag mit der ID $id!<br>\n";
}



?>
