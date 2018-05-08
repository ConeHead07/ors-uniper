<?php 
// ?area=admin&task=modul&modul=seitenbereiche&ansicht=liste&cmd=setvisibility&wert=hidden&id=53&pid=0
// ?area=admin&task=modul&modul=seitenbereiche&ansicht=edit&id=108&parentid=
// START: Register Vars
$_V = array(
	//array(varname, Reihenfolge , setDefault, default
	array(    "area", "pg", false,  ""),
	array(      "id", "pg", false,  ""),
	array(     "pid", "pg", false,  ""),
	array(    "wert", "pg", false,  ""),
	array(     "cmd", "pg", false,  ""),
	array( "ansicht", "pg", false,  ""),
	array(   "modul", "pg", false,  ""),
	array(    "task", "pg", false,  ""),
	array("parentid", "pg", false,  "")
);

foreach($_V as $varProps) {
	$varName = $varProps[0];
	// echo "#".__LINE__." varName:$varName <br>\n";
	for ($k = 0; $k < strlen($varProps[1]); $k++) {
		switch($varProps[1][$k]) {
			case "p": if (isset($_POST[$varName]))   { $$varName = $_POST[$varName];   continue 3;} break;
			case "g": if (isset($_GET[$varName]))    { $$varName = $_GET[$varName];    continue 3;} break;
			case "c": if (isset($_COOKIE[$varName])) { $$varName = $_COOKIE[$varName]; continue 3;} break;
		}
	} if ($varProps[2]) $$varName = $varProps[3];
	// echo "#".__LINE__." varName:$varName, \$\$varName:".$$varName."<br>\n";
}

// echo "#".__LINE__." ".basename(__FILE__)." ".strval(time()-$timeIn)." Sek.<br>\n";
$mod_sb_dir = dirname(__FILE__)."/";
$mod_sb_url = $_CONF["WebRoot"].$_CONF["Modul_Dir"]."seitenbereiche/";

include_once($mod_sb_dir."/include/seitenbereiche_lib.php");
include_once($mod_sb_dir."/include/seitenbereich_conf.php");
include_once($mod_sb_dir."/include/lib_menutree.php");
include_once($InclBaseDir."check_lib.php");
$nav_modul = implode("", file($mod_sb_dir."html/seitenbereiche_nav_ansichten.html"));
$nav_modul = str_replace("{s}", $s, $nav_modul);
$body_content.= $nav_modul;
$modul = $s;

$_rpl = array();
if (!isset($_MenuTree) || !isset($_IsInitMenuTree) || $_IsInitMenuTree == false) {
	$_MenuTree = get_menu_tree(0);
}

if (isset($_GET["cmd"])) $cmd = $_GET["cmd"];
if (isset($_POST["cmd"])) $cmd = $_POST["cmd"];

if (isset($_GET["wert"])) $wert = $_GET["wert"];
if (isset($_POST["wert"])) $wert = $_POST["wert"];

if (isset($_GET["id"])) $id = $_GET["id"];
elseif (isset($_POST["id"])) $id = $_POST["id"];

// echo "#".__LINE__." ".basename(__FILE__)." ".strval(time()-$timeIn)." Sek.<br>\n";
// $body_content.= "<pre>#".__LINE__." MenuTree:<br>\n".fb_htmlEntities(arraytoVarString("_MenuTree", $_MenuTree))."</pre>";

if (isset($id)) {
	$_SavedData = get_menu($id);
	$check_parent_id = $_SavedData["parentid"];
}
if (!isset($ofld)) $ofld = "eingetragenam";;
if (!isset($oby)) $oby = "DESC";

$eingabe = array();
$lesen = array();
$keyname = $_TBLKEY["cms_bereiche"];
//$baseLnk = "index.php?area=admin&a=$a&s=$s&modul=$modul";
$baseLnk = "index.php?&s=$s";
$msbBaselinkListOrd = $baseLnk."&ofld=$ofld&oby=$oby";
$adminTitel = $modul."-Management";
$nav_body = "/".$modul;
if (!isset($body_content)) $body_content = "";
// echo "#".__LINE__." ansicht:$ansicht<br>\n";

if (isset($_GET["ansicht"])) $ansicht = $_GET["ansicht"];
if (isset($_POST["ansicht"])) $ansicht = $_POST["ansicht"];
if (isset($_POST["speichern"])) $ansicht = "speichern";
if (!isset($ansicht) || !$ansicht) $ansicht = "liste";
//echo "#".__LINE__." ".basename(__FILE__)." ansicht:$ansicht<br>\n";

if (isset($_POST["poslist"])) {
	$countPosUpdates = 0;
	foreach($_POST["poslist"] as $k => $newpos) {
		if (!is_int(strpos($k,":"))) continue;
		list($r_id, $oldpos) = explode(":", $k);
		
		if ($oldpos != $newpos) {
			$_SavedData = get_menu($r_id);
			$posUpdate = set_position($_SavedData, $newpos);
			if ($posUpdate) {
				$countPosUpdates++;
			} else {
				break;
			}
		}
	}
	$msg.= "Es wurden $countPosUpdates Menüpunkte neu positioniert!<br>\n";
}

//echo "#".__LINE__." ".basename(__FILE__)." ansicht:$ansicht<br>\n";
if (!empty($id) && !empty($cmd) && !empty($wert)) {
	switch($cmd) {
		case "pos":
		// echo "#".__LINE__." cmd:$cmd, wert:".$_GET["wert"]."<br>\n";
		$posUpdate = set_position($_SavedData, $wert);
		$_SavedData = get_menu($id);
		
		// while(list($k, $v) = each($_SavedData)) echo "#".__LINE__." $k : $v <br>\n";reset($_SavedData);
		
		$check_parent_id = $_SavedData["parentid"];
		if ($posUpdate) {
			$msg = $_SavedData["name"]." wurde an ";
			$msg.= $_SavedData["ordnungszahl"].". Stelle positioniert!<br>\n";
		}
		break;
		
		case "setfreigabe":
		if (isset($_SetFlag["webfreigabe"][$wert])) {
			edit_flag($id, "webfreigabe", $wert);
		} else {
			$error.= "Ungültige Eigenschaftswert für Webfreigabe: ".$wert."<br>\n";
		}
		break;
		
		case "setvisibility":
		if (isset($_SetFlag["visibility"][$wert])) {
			edit_flag($id, "visibility", $wert);
		} else {
			$error.= "Ungültige Eigenschaftswert für Menüanzeige: ".$wert."<br>\n";
		}
		break;
		
		case "setgeschuetzt":
		if (isset($_SetFlag["geschuetzt"][$wert])) {
			edit_flag($id, "geschuetzt", $wert);
		} else {
			$error.= "Ungültige Eigenschaftswert für Passwortschutz: ".$wert."<br>\n";
		}
		break;
		
		case "loeschen":
		$num_childs = get_menu_itemsByParentid($id);
		if (!$num_childs) {
			$SQL = "SELECT COUNT(*) FROM ".$_TABLE["cms_texte"]." \n";
			$SQL.= " WHERE seitenbereich = \"".addslashes($_SavedData["srv"])."\"";
			$num_stories = count_query($SQL, $conn);
			if (!$num_stories) {
				// kill_menu_byId($id, $_SavedData);
				$SQL = "DELETE FROM ".$_TABLE["seitenbereich"]." \n";
				$SQL.= " WHERE srv = \"".addslashes($_SavedData["srv"])."\"";
				MyDB::query($SQL);
				if (!MyDB::error()) {
					$msg.= "Der Seitenbereich '".$_SavedData["srv"]."' wurde gelöscht!<br>\n";
				} else {
					$error.= "Fehler beim Löschen: ".$_SavedData["srv"]."<br>\n";
				}
			} else {
				$error.= "Der Seitenbereich konnte nicht gelöscht werden, da ihm noch $num_stories Beiträge zugeordnet sind!<br>\n";
				$error.= "Alternativ können Sie den Bereich sperren oder ausblenden!<br>\n";
			}
		} else {
			$error.= "Der Seitenbereich konnte nicht gelöscht werden, da er noch $num_childs Untermenüs hat!<br>\n";
		}
		break;
		
		default:
		// Nothing
	}
} else {
	// echo "#".__LINE__." NoCmd!<br>\n";
}

if (isset($_POST) && isset($_POST["eingabe"])) {
	$eingabe = $_POST["eingabe"];
	foreach($eingabe as $k => $v) if (gettype($eingabe[$k]) == "string") $eingabe[$k] = stripslashes($v);
	
	//echo "#".__LINE__." ".basename(__FILE__)." ansicht:$ansicht<br>\n";
	list($err, $_eFields) = check_menu_input($eingabe, $lesen, $_CONF["seitenbereich"]);
	
	if ($err) {
		$error.= $err;
		reset($_eFields);
		while(list($k, $v) = each($_eFields)) { 
			if (!isset($_rpl["/"."*dynStyle_".$k."*"."/"])) $_rpl["/"."*dynStyle_".$k."*"."/"] = "";
			$_rpl["/"."*"."dynStyle_".$k."*"."/"].= "color:red;";
		}
		reset($_eFields);
		$ansicht = "edit";
		
	}
	//echo "#".__LINE__." ".basename(__FILE__)." ansicht:$ansicht, err:$err<br>\n";
} else {
	if ($ansicht == "edit") {
		if (isset($id) && $id) {
			$eingabe = $_SavedData;
			list($err, $_eFields) = check_menu_input(
				$eingabe, 
				$lesen, 
				$_CONF["seitenbereich"]);
		} else {
			init_menu_input($eingabe, $lesen,  $_CONF["seitenbereich"]);
		}
	}
}

//echo "#".__LINE__." ansicht:$ansicht<br>\n";
if ($ansicht == "speichern" && !$error) {
	$SAVE_ERROR = "";
	$SAVE_MODE = (isset($eingabe[$keyname]) && $eingabe[$keyname]) ? "UPDATE" : "INSERT";
	$saveid = save_menu($eingabe,$_CONF["seitenbereich"], $SAVE_ERROR);
	if (!$saveid || $SAVE_ERROR) {
		$ansicht = "edit";
		// echo "#".__LINE__." ansicht:$ansicht<br>\n";
		switch($SAVE_MODE) {
			case "INSERT":
			$error.= "Fehler beim Speichern: Der Seitenbereich konnte nicht angelegt werden!<br>\n";
			$error.= $SAVE_ERROR."<br>\n";
			break;
			
			default:
			$error.= "Fehler beim Speichern: Der Seitenbereich konnte nicht aktualisert werden!<br>\n";
			$error.= $SAVE_ERROR."<br>\n";
			break;
		}
		echo "#".__LINE__." ".basename(__FILE__)." error:$error<br>\n";
	} else {
		switch($SAVE_MODE) {
			case "INSERT":
			$msg.= "Ein neuer Seitenbereich wurde angelegt!<br>\n";
			break;
			
			default:
			$msg.= "Der Seitenbereich wurde aktualisert!<br>\n";
			break;
		}
		$srv = $eingabe["srv"];
		$id = $saveid;
		$ansicht = "liste";
	}
}
// echo "#".__LINE__." ".basename(__FILE__)." ".strval(time()-$timeIn)." Sek.<br>\n";

if ($ansicht == "loeschen") {
	include($mod_sb_dir."include/seitenbereich_loeschen.php");
	$ansicht = "liste";
}

if ($ansicht == "edit") {
	$body_content.= implode("", file($mod_sb_dir."html/seitenbereich_eingabe.html"));
	$check_pid = ($eingabe["parentid"]) ? $eingabe["parentid"] : 0;
	$checkTop = ($check_pid == 0) ? "checked selected" : "";
	$parent_menu_options = "<option value=\"0\" $checkTop>Top</option>\n";
	$parent_menu_options.= get_menu_tree_options($_MenuTree, $check_pid);
	$body_content = str_replace("<!-- {parent_menu_options} -->", $parent_menu_options, $body_content);
	// echo "#".__LINE__." ".basename(__FILE__)." ".strval(time()-$timeIn)." Sek.<br>\n";
}

if (isset($id) && $id && isset($_SavedData) && is_array($_SavedData) && count($_SavedData)) {
	if ($ansicht == "setfreigabe" && isset($wert)) {
		if (isset($_SetFlag["webfreigabe"][$wert])) {
			edit_flag($id, "webfreigabe", $wert);
		} else {
			$error.= "Ungültige Eigenschaftswert für Webfreigabe: ".$wert."<br>\n";
		}
		$ansicht = "liste";
	}
	
	if ($ansicht == "setvisibility" && isset($wert)) {
		if (isset($_SetFlag["visibility"][$wert])) {
			edit_flag($id, "visibility", $wert);
		} else {
			$error.= "Ungültige Eigenschaftswert für Menüanzeige: ".$wert."<br>\n";
		}
		$ansicht = "liste";
	}
	
	if ($ansicht == "setgeschuetzt" && isset($wert)) {
		if (isset($_SetFlag["geschuetzt"][$wert])) {
			edit_flag($id, "geschuetzt", $wert);
		} else {
			$error.= "Ungültige Eigenschaftswert für Passwortschutz: ".$wert."<br>\n";
		}
		$ansicht = "liste";
	}
	
	if ($ansicht == "loeschen") {
		$num_childs = get_menu_itemsByParentid($id);
		if (!$num_childs) {
			$SQL = "SELECT COUNT(*) FROM ".$_TABLE["cms_texte"]." \n";
			$SQL.= " WHERE seitenbereich = \"".addslashes($_SavedData["seitenbereich"])."\"";
			$num_stories = count_query($SQL, $conn);
			if (!$num_stories) {
				kill_menu_byId($id);
			} else {
				$error.= "Der Seitenbereich konnte nicht gelöscht werden, da ihm noch $num_stories Beiträge zugeordnet sind!<br>\n";
				$error.= "Alternativ können Sie den Bereich sperren oder ausblenden!<br>\n";
			}
		} else {
			$error.= "Der Seitenbereich konnte nicht gelöscht werden, da er noch $num_childs Untermenüs hat!<br>\n";
		}
	}
	if (isset($pid)) $id = $pid;
}

if ($ansicht == "liste") {
	include($mod_sb_dir."include/seitenbereich_liste.php");
}

if ($ansicht == "tree") {
	include($mod_sb_dir."include/seitenbereich_tree.php");
}

// Setze Template-Ersetzungen
$_rpl["{s}"] = (isset($s)) ? $s : "sb";
$_rpl["{task}"] = (isset($task)) ? $task : "modul";
$_rpl["{modul}"] = (isset($modul)) ? $modul : "seitenbereich";
$_rpl["{area}"] = (isset($area)) ? $area : "ctv";
$_rpl["{ansicht}"] = (isset($ansicht)) ? $ansicht : "";

if (isset($eingabe) && is_array($eingabe) && count($eingabe)) {
	get_tplFormVars($eingabe, $lesen, $_rpl, $_CONF["seitenbereich"]);
}

foreach($_rpl as $k => $v) {
    if (is_scalar($v)) $body_content = str_replace($k, $v, $body_content);
}
