<?php 

$Tpl = new myTplEngine();
require_once($InclBaseDir."nebenleistungen.inc.php");
require_once($InclBaseDir."user.inc.php");
$NLConf = $_CONF["nebenleistungen"];
$USERConf = $_CONF["user"];
$NID = getRequest("id",'');
$NLInput = getRequest("NL");
$nebenleistung_form = "knebenleistung_eingabe.html";

if (empty($NID)) $NID = (!empty($_POST["NL"]["id"]) ? $_POST["NL"]["id"] : (!empty($_GET["NL"]["id"]) ? $_GET["NL"]["id"] : ''));

$NL = new ItemEdit($NLConf, $connid, $user, $NID);

$save_success = false;

if ($NLInput) {
	if (!$NID) {
		$NL->loadInput($NLInput, true);
		if (!$NL->Error) {
			$NL->save();
			if (!$NL->Error) $NID = $NL->id;
			echo $db->error()."<br>\n";
			
			$NL->loadDbdata();
			$NL->dbdataToInput();
			$sql = "UPDATE `".$USERConf["Table"]."` SET `fon` = \"".$db->escape($NL->arrInput["fon"])."\", `standort`=\"".$db->escape($NL->arrInput["standort"])."\", `gebaeude` = \"".$db->escape($NL->arrInput["gebaeude"])."\" \n";
			$sql.= "\n WHERE uid = \"".$db->escape($NL->arrInput["createduid"])."\"";
			$db->query($sql);
			//if ($db->error()) 
			//echo $db->error()."<br>\n".$sql."<br>\n";
			
		}
	} else {
		$nebenleistung_form = "knebenleistung_lesen.html";
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
	}
} else {
	// If AID: Bearbeitungsformular mit DB-Daten
	if ($NID) {
		$NL->loadDbdata();
		$NL->dbdataToInput();
		
		if ($user["gruppe"]=="admin_standort") {
			if (strpos(",".$user["standortverwaltung"].",", ",".$NL->arrInput["ort"].",")===false)
				die("UNERLAUBTER ZUGRIFF! Standort-Administratoren dürfen nur auf Anträge zugreifen, die in Ihrer Standortverwaltung eingetragen sind!");
		}
		$nebenleistung_form = "knebenleistung_lesen.html";
	} else {
		// else: lade Eingabeformular
		$NL->loadInput(array(), false);
		$NL->arrInput["name"] = $user["nachname"];
		$NL->arrInput["vorname"] = $user["vorname"];
		$NL->arrInput["fon"] = $user["fon"];
		$NL->arrInput["email"] = $user["email"];
		$NL->arrInput["standort"] = $user["standort"];
		$NL->arrInput["gebaeude"] = $user["gebaeude"];
	}
}

if (!$save_success) {
	$Tpl->assign("NL", $NL->arrInput);
	$Tpl->assign("s", $s);
	$body_content.= $Tpl->fetch($nebenleistung_form);
} else {
	include($MConf["AppRoot"]."sites".DS."knebenleistungen.php");
}
?>
