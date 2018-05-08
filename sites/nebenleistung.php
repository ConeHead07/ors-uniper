<?php 
if (strpos($user["gruppe"], "admin") === false) die("UNERLAUBTER ZUGRIFF! Zugriff nur für Administratoren");

$Tpl = new myTplEngine();
require_once($InclBaseDir."nebenleistungen.inc.php");
$NLConf = $_CONF["nebenleistungen"];
$NID = getRequest("id",'');
$NLInput = getRequest("NL");

if (empty($NID)) $NID = (!empty($_POST["NL"]["id"]) ? $_POST["NL"]["id"] : (!empty($_GET["NL"]["id"]) ? $_GET["NL"]["id"] : ''));

$NL = new ItemEdit($NLConf, $connid, $user, $NID);

$save_success = false;
echo "#".__LINE__." ".basename(__FILE__)."<br>\n";
if ($NLInput) {
	echo "#".__LINE__." ".basename(__FILE__)."<br>\n";
	$NL->loadInput($NLInput, true);
	if (!$NL->Error) {
		$NL->save();
		if (!$NL->Error) $NID = $NL->id;
	}
	if (!$NL->Error) {
		$body_content.= "Eintrag wurde gespeichert!<br>\n";
		$save_success = true;
	} else {
		$body_content.= $NL->Error;
	}
} else {
	echo "#".__LINE__." ".basename(__FILE__)."<br>\n";
	// If AID: Bearbeitungsformular mit DB-Daten
	if ($NID) {
		$NL->loadDbdata();
		$NL->dbdataToInput();
		
		if ($user["gruppe"]=="admin_standort") {
			if (strpos(",".$user["standortverwaltung"].",", ",".$NL->arrInput["ort"].",")===false)
				die("UNERLAUBTER ZUGRIFF! Standort-Administratoren dürfen nur auf Anträge zugreifen, die in Ihrer Standortverwaltung eingetragen sind!");
		}
		
	} else {
		// else: lade Eingabeformular
		$NL->loadInput(array(), false);
	}
}

if (!$save_success) {
	$Tpl->assign("NL", $NL->arrInput);
	$Tpl->assign("s", $s);
	$body_content.= $Tpl->fetch("knebenleistung_eingabe.html");
} else {
	$body_content.= $NL->autorun_itemlist();
}
?>
