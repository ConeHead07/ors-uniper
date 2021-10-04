<?php 
require_once($InclBaseDir."php_json.php");
require_once("sites/umzugsantrag_stdlib.php");

if (strpos($user["gruppe"], "admin") === false) die("UNERLAUBTER ZUGRIFF! Zugriff nur für Administratoren");

require_once($InclBaseDir."umzugsantrag.inc.php");
require_once($InclBaseDir."umzugsmitarbeiter.inc.php");
require_once($InclBaseDir."umzugsanlagen.inc.php");

$ATConf = &$_CONF["umzugsanlagen"];
$ASConf = &$_CONF["umzugsantrag"];
$MAConf = &$_CONF["umzugsmitarbeiter"];

// Get ID, falls Antrag bereits vorhanden
$AID = getRequest("id",'');
if (empty($AID)) $AID = (!empty($_POST["AS"]["aid"]) ? $_POST["AS"]["aid"] : (!empty($_GET["AS"]["aid"]) ? $_GET["AS"]["aid"] : ''));

$AS = new ItemEdit($ASConf, $connid, $user, $AID);
$MA = new ItemEdit($MAConf, $connid, $user, false);
$Tpl = new myTplEngine();
$TplMaListItem = array("ID"=>1, "nachname"=>"", "vorname"=>"", "ort"=>"", "gebaeude"=>"", "raumnr"=>"");

// If AID: Bearbeitungsformular mit DB-Daten
if ($AID) {
	$AS->loadDbdata();
	$AS->dbdataToInput();
	
	if ($user["gruppe"]=="admin_standort") {
		if (strpos(",".$user["standortverwaltung"].",", ",".$AS->arrInput["ort"].",")===false)
			die("UNERLAUBTER ZUGRIFF! Standort-Administratoren dürfen nur auf Anträge zugreifen, die in Ihrer Standortverwaltung eingetragen sind!");
	}
	
	$sql = "SELECT mid FROM `".$MAConf["Table"]."` WHERE aid = ".intval($AID);
	$aMIDs = $db->query_rows($sql);
	
	for($i = 0; $i < count($aMIDs); $i++) {
		$MID = $aMIDs[$i]["mid"];
		$MA = new ItemEdit($MAConf, $connid, $user, $MID);
		$MA->dbdataToInput();
		$aMaItems[$i] = $MA->arrInput;
		$MAItems[$i] = &$aMaItems[$i];
			
			
			$raumdaten = get_raumdaten_byGER($MAItems[$i]["zgebaeude"], $MAItems[$i]["zetage"], $MAItems[$i]["zraumnr"]);
			$raum_ma_fix = get_arbeitsplatz_belegung($raumdaten["id"], $apnr=false);
			$raum_ma_hin = get_arbeitsplatz_hinzuege($raumdaten["id"], $apnr=false);
			
			$count_ma_fix = (is_array($raum_ma_fix) && count($raum_ma_fix)) ? count($raum_ma_fix) : 0;
			$count_ma_hin = (is_array($raum_ma_hin) && count($raum_ma_hin)?count($raum_ma_hin):0);
			$count_ma_all = $count_ma_fix+$count_ma_hin;
			
			if ($count_ma_all) {
				$isCritical = ($raumdaten["raum_flaeche"] / 10) < $count_ma_all;
			} else $isCritical = false;
			
			$MAItems[$i]["critical_status_index"] = ($isCritical ? 1 : 0);
			$MAItems[$i]["critical_status_info"] = intval($raumdaten["raum_flaeche"])."qm: ".$count_ma_fix."Fix + ".$count_ma_hin."Hin";
			$MAItems[$i]["critical_status_img"] = ($isCritical ? "warning_triangle.png" : "thumb_up.png");
			/**/
	}
	
	//die("#".__LINE__." ".basename(__FILE__)."<br>\n");
	$sql = "SELECT dokid FROM `".$ATConf["Table"]."` WHERE aid = ".intval($AID);
	$aATs = $db->query_rows($sql);
	echo $db->error();
	
	for($i = 0; $i < count($aATs); $i++) {
		$DOKID = $aATs[$i]["dokid"];
		$AT = new ItemEdit($_CONF["umzugsanlagen"], $connid, $user, $DOKID);
		$AT->dbdataToInput();
		$aAtItems[$i] = $AT->arrInput;
		$aAtItems[$i]["datei_link"] = $MConf["WebRoot"]."attachements/".$AT->arrInput["dok_datei"];
		$aAtItems[$i]["datei_groesse"] = format_file_size($AT->arrInput["dok_groesse"]);
		
	}
} else {
	// else: lade Eingabeformular
	$defaultAS = array(
		"vorname" => $user["vorname"],
		"name" => $user["nachname"],
		"fon" => $user["fon"],
		"email"=> $user["email"],
		"ort" => $user["standort"],
		"gebaeude" => $user["gebaeude"]
	);
	$AS->loadInput($defaultAS, false);
	$MA->loadInput(array(), false);
	$Tpl->assign("AS", array($AS->arrInput));
}

$Tpl->assign("AS", $AS->arrInput);

if (!empty($aMaItems) && count($aMaItems)) $Tpl->assign("Mitarbeiterliste", $aMaItems);
if (!empty($aAtItems) && count($aAtItems)) $Tpl->assign("UmzugsAnlagen", $aAtItems);

// Erzeuge GeraeteListe (Array) für Smarty-Template
$CsvLines = explode("\n", $AS->arrInput["geraete_csv"]);
$aGItems = array();
$aGCols = array();
for ($i = 0; $i < count($CsvLines); $i++) {
    $aGCols = explode("\t", $CsvLines[$i]);
    if (count($aGCols) != 4) continue;
    $aGItems[$i] = array(
        "Art" => $aGCols[0],
        "Nr" => $aGCols[1],
        "Von" => $aGCols[2],
        "Nach" => $aGCols[3]
    );
}
if (!empty($aGItems) && count($aGItems)) $Tpl->assign("Geraeteliste", $aGItems);

//die("#".__LINE__." aAtItems: ".print_r($aAtItems,1)."<br>\n");

$mainmenu = "Class-Active-Umzug";
$topmenu = implode("", file($MConf["AppRoot"]."/sites/mitarbeiter_topmenu.tpl.html"));

$AS->loadDbdata();
$body_content = $Tpl->fetch("admin_mapflege.tpl.html");


//$body_content = implode("", file($MConf["AppRoot"].$MConf["Tpl_Dir"]."umzugsformular.tpl.html"));
if (DEBUG && basename(__FILE__) == basename($_SERVER["PHP_SELF"])) {
	echo $body_content;
}
?>
