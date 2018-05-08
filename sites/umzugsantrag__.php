<?php 
require_once(dirname(__FILE__)."/../header.php");
if (empty($InclBaseDir)) $InclBaseDir = $MConf["AppRoot"].$MConf["Inc_Dir"];

require_once($InclBaseDir."registered_data.inc.php");
if (!empty($ConfRegData['umzugsantrag']) && file_exists($InclBaseDir.$ConfRegData['umzugsantrag'])) require_once($InclBaseDir.$ConfRegData['umzugsantrag']);
if (!empty($ConfRegData['umzugsmitarbeiter']) && file_exists($InclBaseDir.$ConfRegData['umzugsmitarbeiter'])) require_once($InclBaseDir.$ConfRegData['umzugsmitarbeiter']);

if (empty($_CONF["umzugsmitarbeiter"])) die("FEHLER: Conf-Datei für umzugsmitarbeiter wurde nicht gefunden!<br>\n");
if (empty($_CONF["umzugsantrag"]))      die("FEHLER: Conf-Datei für umzugsantrag wurde nicht gefunden!<br>\n");

// Get ID, falls Antrag bereits vorhanden
$EditAID = (!empty($_POST["id"]) ? $_POST["id"] : (!empty($_GET["id"]) ? $_GET["id"] : ''));
if (empty($EditAID)) $EditAID = (!empty($_POST["AS"]["id"]) ? $_POST["AS"]["id"] : (!empty($_GET["AS"]["id"]) ? $_GET["AS"]["id"] : ''));


$inputAS = new ItemEdit($_CONF["umzugsantrag"], $connid, $user, $EditAID);
$inputMA = new ItemEdit($_CONF["umzugsmitarbeiter"], $connid, $user, false);
$Tpl = new myTplEngine();

function get_ma_post_items() {
	$aMaItems = array();
	for($i = 0; $i < count($_POST["MA"]["vorname"]); $i++) {
		$aMaItems[$i]["ID"] = $i+1;
		foreach($_POST["MA"] as $fld => $aTmp) {
			$aMaItems[$i][$fld] = $_POST["MA"][$fld][$i];
		}
	}
	return $aMaItems;
}

if (isset($_POST["AS"])) {
	// Get Input
	$inputAS->arrInput = $_POST["AS"];
	$aMaItems = get_ma_post_items();
	
	// Eingabe prüfen
	$isOk = $inputAS->checkInput();
	if ($isOk) {
		if ($inputAS->save()) {
			$EditAID = $inputAS->id;
			
			if (!$inputAS->Error) for($i = 0; $i < count($aMaItems); $i++) {
				$aMaItems[$i]["aid"] = $inputAS->id;
				$EditMID = (empty($aMaItems[$i]["mid"])) ? false : $aMaItems[$i]["mid"];
				$inputMA = new ItemEdit($_CONF["umzugsmitarbeiter"], $connid, $user, $EditMID);
				$inputMA->arrInput = $aMaItems[$i];
				$isOk = $inputMA->checkInput();
				if ($isOk) {
					$inputMA->save();
					
				}
				$error.= $inputMA->Error;
			}
		}
	}
	$error.= $inputAS->Error;
	
} else {
	// If EditAID: Bearbeitungsformular mit DB-Daten
	if ($EditAID) {
		$inputAS->loadDbdata();
		$inputAS->dbdataToInput();
		
		$sql = "SELECT * FROM `".$_CONF["umzugsmitarbeiter"]." WHERE aid = ".intval($EditAID);
		$aMaListe = $db->query_rows($sql);
		$Tpl->assign("Mitarbeiterliste", $aMaListe);
	} else {
	// else: lade Eingabeformular
		$inputAS->loadInput(array(), false);
		$inputMA->loadInput(array(), false);
		$TplMaListItem = array("ID"=>1, "nachname"=>"", "vorname"=>"", "ort"=>"", "gebaeude"=>"", "raumnr"=>"");
		$Tpl->assign("AS", array($inputAS->arrInput));
		$Tpl->assign("Mitarbeiterliste", array($inputMA->arrInput));
	}
}

if ($_POST) {
	//if (isset($_POST)) echo " _POST: ".print_r($_POST,1);
	//if (isset($_POST["AS"])) echo " _POST[AS]: ".print_r($_POST["AS"],1);
	//if (isset($_POST["MA"])) echo " _POST[MA]: ".print_r($_POST["MA"],1);
}

$mainmenu = "Class-Active-Umzug"; //Umzug" xclass="liActive
$topmenu = implode("", file($MConf["AppRoot"]."/sites/mitarbeiter_topmenu.tpl.html"));

//$_rplAusgabe[0]["<!-- {topmenu} -->"] = $topmenu;

$rnr = 0;
$MaId =1;
$Tpl = new myTplEngine();

// Neuantrag: Ohne Post-Data
// Neuantrag: Mit Post-Data

// Bearbeiten: Daten laden
// Bearbeiten: Post



$TplMaListItem = array("ID"=>1, "nachname"=>"", "vorname"=>"", "ort"=>"", "gebaeude"=>"", "raumnr"=>"");
if (!isset($_POST["MA"]) || !isset($_POST["MA"]["vorname"]) || !is_array($_POST["MA"]["vorname"])) {
	// Default-Mind. ein MitArbeit-Eintrag als Vorlage
	$Tpl->assign("Mitarbeiterliste", array($TplMaListItem));
} else {
	$aMaListe = array();
	for($i = 0; $i < count($_POST["MA"]["vorname"]); $i++) {
		$aMaListe[$i]["ID"] = $i+1;
		foreach($_POST["MA"] as $fld => $aTmp) {
			$aMaListe[$i][$fld] = $_POST["MA"][$fld][$i];
		}
	}
	$Tpl->assign("Mitarbeiterliste", $aMaListe);
}

$TplASItem = array("nachname"=>"", "vorname"=>"", "ort"=>"", "gebaeude"=>"", "email"=>"");
if (!isset($_POST["AS"]) || !isset($_POST["AS"]["vorname"])) {
	// Default-Mind. ein MitArbeit-Eintrag als Vorlage
	$Tpl->assign("AS", $TplASItem);
} else {
	$Tpl->assign("AS", $_POST["AS"]);
}

/*
	$Tpl->assign("txt", "Frank Barthold");
	$Tpl->assign(array("nachname"=>"Mueller", "vorname"=>"Klaus", "ort"=>"Düsseldorf", "gebaeude"=>"Seestern \"hüi\"", "raumnr"=>"00".(++$rnr)));
	$Tpl->assign("terminwunsch", time());
	$Tpl->assign("Mitarbeiterliste", array(
			array("ID"=>$MaId++, "nachname"=>"Mueller", "vorname"=>"Klaus", "ort"=>"Düsseldorf", "gebaeude"=>"Seestern", "raumnr"=>"00".($rnr)),
			array("ID"=>$MaId++,"nachname"=>"Mueller", "vorname"=>"Klaus", "ort"=>"Düsseldorf", "gebaeude"=>"Seestern", "raumnr"=>"00".(++$rnr)),
			array("ID"=>$MaId++,"nachname"=>"Mueller", "vorname"=>"Klaus", "ort"=>"Düsseldorf", "gebaeude"=>"Seestern", "raumnr"=>"00".(++$rnr)),
			array("ID"=>$MaId++,"nachname"=>"Mueller", "vorname"=>"Klaus", "ort"=>"Düsseldorf", "gebaeude"=>"Seestern", "raumnr"=>"00".(++$rnr))
		)
	);
*/

$body_content = $Tpl->fetch("umzugsformular.tpl.html");
//$body_content = implode("", file($MConf["AppRoot"].$MConf["Tpl_Dir"]."umzugsformular.tpl.html"));
if (DEBUG && basename(__FILE__) == basename($_SERVER["PHP_SELF"])) {
	echo $body_content;
}
?>