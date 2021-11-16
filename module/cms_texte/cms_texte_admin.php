<?php error_reporting(E_ALL); ?>
<?php

$ModSelfPath = $MConf["AppRoot"].$MConf["Modul_Dir"]."cms_texte".DS;

include_once($ModSelfPath."include".DS."conf_cms_texte.php");
include_once($ModSelfPath."include".DS."check_cms_texte.php");
include_once($ModSelfPath."include".DS."lib_video.php");
include_once($ModSelfPath."include".DS."check_lib.php");

// Start Block: Standard-Formular-Vorlagen
$vorlage_eingabe_file = $ModSelfPath."html".DS."vorlage_cms_texte_eingabe.html";
$vorlage_vorschau_file = $ModSelfPath."html".DS."vorlage_cms_texte_vorschau.html";
$vorlage_lesen_file = $ModSelfPath."html".DS."vorlage_cms_texte_lesen.html";
$public_item_vorlage_file = $ModSelfPath."html".DS."public_beitraege_items.html";
$unpublic_item_vorlage_file = $ModSelfPath."html".DS."unpublic_beitraege_items.html";
$cmstasks_public_item_file = $ModSelfPath."html".DS."cmstasks_public_item.html";
$cmstasks_unpublic_item_file = $ModSelfPath."html".DS."cmstasks_unpublic_item.html";

$ModBaseLink = "?s=$s";
$benutzerdaten = $user;
if (isset($_POST["eingabe"])) $eingabe = $_POST["eingabe"];

$cx = 0;
if ($cx) echo "#".__LINE__." ".basename(__FILE__)."<br>\n";
if (!isset($cmstask)) $cmstask = "";

//echo "#3 task:$task, cmstask:$cmstask, ansicht:$ansicht<br>\r\n";

$fehlertext = "";
$fehlerfelder = array();
$nav_body = "";
$listentext = "";
$volltext = "";
$editTableOrd = $tbl_defaultOrderFeld;

$ansicht = (!empty($_REQUEST["ansicht"])) ? $_REQUEST["ansicht"] :  "liste";
$id = (!empty($_REQUEST["id"])) ? $_REQUEST["id"] :  "liste";
$srv = (!empty($_REQUEST["srv"])) ? $_REQUEST["srv"] :  "";
$task = (!empty($_REQUEST["task"])) ? $_REQUEST["task"] :  "";
$area = "";

if ($cx) echo "#".__LINE__." ansicht:$ansicht, id:$id<br>\n";

$buffer_liste = true;

$cmscontent_nav = "";
$cmscontent_nav.= '<table cellpadding="1" cellspacing="0" border="0" width="100%" align="center">
	<tr>
		<td bgcolor=navy><strong style="color:#FFF;">&nbsp; &gt;&gt; '.ucfirst($srv).'</strong></td>
		<td bgcolor=navy><strong>';
$cmscontent_nav.= " <a style='color:lightblue;' href='$ModBaseLink&task=$task&srv=".$srv."&ansicht=eingabe'>[ Erstellen ]</a> ";
$cmscontent_nav.= "&nbsp; <a style='color:lightblue;' href='$ModBaseLink&task=$task&srv=".$srv."&ansicht=liste'>[ Bearbeiten / Liste ]</a> ";
$cmscontent_nav.= '</strong></td>
	</tr>
</table>';


if (!isset($id)) {
	if (isset($_GET["id"])) $id = $_GET["id"];
	elseif (isset($_POST["id"])) $id = $_POST["id"];
	else $id = "";
}

$editTable = $_TABLE["cms_texte"];
$editTableKey = "id";
$_VF = &$validFields[$editTable];

// Start Block: Setze Werte für set_cms_editbar
$groupid_counter =0;
$delimitedTags ="div,u,b,i,ul,ol,li";
$editareavorlage_file = $ModSelfPath."html".DS."vorlage_editarea.html";
$jsbuttonsvorlage_file = $ModSelfPath."html".DS."vorlage_editjsbtns.txt";
$scriptspecifics_header_file = $ModSelfPath."html".DS."scriptspecifics_editbar.html";

//$scriptspecifics_header_file= $ModSelfPath."html".DS."scriptspecifics_editbar2.html";
if ($user["gruppe"] == "admin") {
	$jsbuttonsvorlage_file= $ModSelfPath."html".DS."vorlage_editjsbtns500.txt";
	$editareavorlage_file= $ModSelfPath."html".DS."vorlage_editarea500.html";
}
// End Block: Setze Werte für set_cms_editbar

if (empty($ActiveMenu["kommentar"])) $ActiveMenu = get_menu_bySrv($srv);
if ($ActiveMenu["kommentar"]) {
	$hintsandtipps = "<div style=\"border:1px solid green;padding:4px;font-size:12px;\">".$ActiveMenu["kommentar"]."</div>\n";
} else {
	$hintsandtipps = "";
}

$cmscontent = implode("",file($vorlage_eingabe_file));
if (!isset($ansicht)) $ansicht = "liste";
if (isset($_POST["vorschau"])) $ansicht = "vorschau";
if (isset($_POST["speichern"])) $ansicht = "speichern";
if (isset($_POST["korrigieren"])) $ansicht = "korrigieren";
//echo "#25 ansicht:$ansicht<br>";

if ($cmstask == "translate") {
	include($ModSelfPath."include".DS."lib_check_translate.php");
	$clk = get_clkById($id);
	if ($clk) {
		$from_lang = array_shift(explode("-", $clk));
		$aTranslatedItems = get_translatedItemIdsByCLK($clk, $from_lang);
		
		switch(count($aTranslatedItems)) {
			case 0: 
			// Keine Übersetzung vorhanden, neu anlegen
			$validFields[$editTable]["lang"][1] = ($from_lang == "DE") ? "EN" : "";
			$validFields[$editTable]["common_lang_key"][1] = $clk;
			$ansicht = "eingabe";
			break;
			
			case 1: 
			// Übersetzung vorhanden, zur Bearbeitung öffnen
			$id = $aTranslatedItems[0][$editTableKey];
			$cmstask = "edit";
			$ansicht = "bearbeiten";
			break;
			
			default: 
			// Es existieren Übersetzungen in mehr als nur einer Sprache
			// Zur Zeit nicht möglich, da nur deutsch und englisch konfiguriert sind
			echo "#".__LINE__." ".basename(__FILE__)."<br>\n";
			break;
		}
	}
	/**/
}
if (!isset($msg)) $msg = "";

if ($cx) echo "#".__LINE__." ".basename(__FILE__)." ansicht:$ansicht<br>\n";

function recount_public_srv_items($srv) {
	global $editTable;
	global $editTableKey;
	global $editTableOrd;
	$key2Ord = array();
	$SQL = "SELECT $editTableKey, $editTableOrd \n";
	$SQL.= " FROM $editTable \n";
	$SQL.= " WHERE webfreigabe LIKE 'Ja'";
	$SQL.= " AND seitenbereich = \"".addslashes($srv)."\" ";
	$SQL.= " ORDER BY $editTableOrd ASC \n";
	
	$r = MyDB::query($SQL);
	if ($r) {
		$n = MyDB::num_rows($r);
		for ($i = 0; $i < $n; $i++) {
			$_e = MyDB::fetch_array($r, MYSQL_NUM);
			$key2Ord[$i] = $_e[0];
		}
		MyDB::free_result($r);
		for ($i = 0; $i < $n; $i++) {
			$SQL = "UPDATE $editTable SET \n";
			$SQL.= " $editTableOrd = '". (string)($i+1)."' \n";
			$SQL.= " WHERE $editTableKey = '".$key2Ord[$i]."'";
			$SQL.= " AND seitenbereich = \"".addslashes($srv)."\" ";
			MyDB::query($SQL);
			if (MyDB::error()) {
				echo "<pre>#".__LINE__." MYSQL:".MyDB::error()." <br> \n";
				echo "#".__LINE__." QUERY:".fb_htmlEntities($SQL)." </pre> \n";
				return false;
			}
			// echo "#".__LINE__." key:".$key2Ord[$i].", ord:".strval($i+1)."<br>\n";
		}
	} else {
		echo "<pre>#".__LINE__." MYSQL:".MyDB::error()." <br> \n";
		echo "#".__LINE__." QUERY:".fb_htmlEntities($SQL)." </pre> \n";
		return false;
	}
	return true;
}
if (isset($_GET["cmstask"]) && $_GET["cmstask"] == "sortieren" && isset($_GET["srv"])) {
	recount_public_srv_items($_GET["srv"]);
}

$chckSelect = "CHECKED SELECTED";
$pflichtfeldMarke = "<font color='#FF0000'>*</font>";
$warnMarke = "<b><font color='#FF0000'>!!!</font> </b>";
if ($cx) echo "#".__LINE__." ansicht:$ansicht<br>\n";
switch ($ansicht) {
	case "liste":
	$cmscontent = "";
	//echo "#48 task:$task, cmstask:$cmstask, ansicht:$ansicht<br>\r\n";
	switch($cmstask) {
		case "sperren":
		$SQL = "UPDATE $editTable set";
		$SQL.= " webfreigabe = 'Nein',";
		if (isset($mysql_tbl_fields[$editTable]["bearbeitetam"])) {
			$SQL.= " bearbeitetam = NOW()";
		}
		$SQL.= " WHERE $editTableKey = '$id'";
		MyDB::query($SQL);
		break;

		case "freigeben":
		$SQL = "UPDATE $editTable SET $editTableOrd = ($editTableOrd+1)";
		$SQL.=" WHERE $editTableKey <> $id";
		MyDB::query($SQL);
		$SQL = "UPDATE $editTable set";
		$SQL.= " webfreigabe = 'Ja',";
		$SQL.= " $editTableOrd = '1',";
		if (isset($mysql_tbl_fields[$editTable]["bearbeitetam"])) {
			$SQL.= " bearbeitetam = NOW()";
		}
		$SQL.= " WHERE $editTableKey = '$id'";
		MyDB::query($SQL);
		break;
		
		case "loeschen":
		$SQL = "DELETE FROM $editTable";
		$SQL.= " WHERE $editTableKey = '$id'";
		MyDB::query($SQL);
		break;
		
		case "sortieren":
		$SQL = "SELECT $editTableOrd FROM $editTable WHERE $editTableKey = '$id'";
		$r_Ord = onerowcol_resultquery($SQL);
		//echo "#".__LINE__." ".MyDB::error()."<br>\n";
		if (!isset($Ord)) $Ord = 1;
		//echo "#".__LINE__." r_Ord:$r_Ord, Ord:$Ord, $editTableKey = '$id' <br>\n";
		if ($r_Ord > $Ord) {
			//echo "#".__LINE__."<br>\n";
			$SQL = "UPDATE $editTable SET $editTableOrd = ($editTableOrd+1)";
			$SQL.=" WHERE $editTableKey <> $id \n";
			$SQL.= " AND $editTableOrd >=$Ord \n";
			$SQL.= " AND $editTableOrd < $r_Ord \n";
			$SQL.= " AND seitenbereich LIKE \"".$srv."\" \n";
			MyDB::query($SQL);
			//echo "#".__LINE__." ".MyDB::error()."<br>\n";
		} elseif ($r_Ord < $Ord) {
			//echo "#".__LINE__."<br>\n";
			$SQL = "UPDATE $editTable SET $editTableOrd = ($editTableOrd-1)";
			$SQL.=" WHERE $editTableKey <> $id \n";
			$SQL.= " AND $editTableOrd <=$Ord \n";
			$SQL.= " AND $editTableOrd > $r_Ord \n";
			$SQL.= " AND seitenbereich LIKE \"".$srv."\" \n";
			MyDB::query($SQL);
			echo "#".__LINE__." ".MyDB::error()."<br>\n";
		}
		if ($r_Ord != $Ord) {
			//echo "#".__LINE__."<br>\n";
			$SQL = "UPDATE $editTable set";
			$SQL.= " webfreigabe = 'Ja',";
			$SQL.= " $editTableOrd = '$Ord',";
			if (isset($mysql_tbl_fields[$editTable]["bearbeitetam"])) {
				$SQL.= " bearbeitetam = NOW()";
			}
			$SQL.= " WHERE $editTableKey = '$id'";
			MyDB::query($SQL);
			//echo "#".__LINE__." ".MyDB::error()."<br>\n";
		}
		break;
	}
	
	$order_options = "";
	$r_max_Ord = 0;
	$SQL = "SELECT $editTableKey, ".$_SYS2MYSQL[$editTable]["sortierfeld"]." FROM $editTable \n";
	$SQL.= " WHERE webfreigabe LIKE 'Ja' \n";
	$SQL.= " AND srv LIKE \"".$srv."\" \n";
	$SQL.= " ORDER BY $editTableOrd ASC";
	$r = MyDB::query($SQL);
	if ($r) {
		$n = MyDB::num_rows($r);
		if ($n) {
			for ($i = 0; $i < $n; $i++) {
				list($r_id,$r_ord) = MyDB::fetch_array($r);
				$r_max_Ord = max($r_max_Ord,$r_ord);
				$pos = $i+1;
				$order_options.= "<option value='$pos' chck_".$editTableKey."=\"$r_id\">$pos</option>\r\n";
			}
			if ($n > 1) {
				$order_options = "<option value='$r_max_Ord'>Ende</option>\r\n".$order_options;
			}
		}
		MyDB::free_result($r);
	}
	
	$SQL = "SELECT * FROM $editTable \n";
	$SQL.= " WHERE webfreigabe LIKE 'Ja' \n";
	$SQL.= " AND srv LIKE \"".$srv."\" \n";
	$SQL.= " ORDER BY $editTableOrd ASC";
	$result = MyDB::query($SQL);
	$num = MyDB::num_rows($result);
	//echo "#".__LINE__." num:$num, ".MyDB::error()."<br>SQL:$SQL<br>";
	$public_liste = "<table width='100%'><tr><td><b>Auf Portalseite ver&ouml;ffentlichte Beiträge</b></td></tr></table>";
	
	$public_item_vorlage = "";
	if ($num) {
		//echo "#".__LINE__." num:$num<br>\n";
		$public_item_vorlage = implode("",file($public_item_vorlage_file));
		$public_item_vorlage = str_replace("%srv%", $srv, $public_item_vorlage);
		for ($i = 0; $i < $num; $i++) {
			//echo "#64 i:$i<br>";
			$pos = $i+1;
			$_e = MyDB::fetch_array($result);
			$r_id = $_e[$editTableKey];
			//list($id,$$editTableOrd,$antrailern,$listentitel,$listentext,$titel,$volltext) = MyDB::fetch_array($result);
			$item_order_options = str_replace("chck_".$editTableKey."=\"$r_id\"","selected checked",$order_options);
			$_rplItem["%task%"] = $task;
			$_rplItem["{task}"] = $task;
			$_rplItem["%pos%"] = $pos;
			$_rplItem["{pos}"] = $pos;
			$_rplItem["%order_options%"] = $item_order_options;
			
			$_rplItem["%listentitel%"] = "";
			if ($_e["notation"]) {
				$_rplItem["%listentitel%"] = "<b style=\"font-size:12px;\">".$_e["notation"]."</b>";
			}
			
			if ($_e["listentitel"]) {
				if ($_rplItem["%listentitel%"]) $_rplItem["%listentitel%"].= " &raquo; ";
				$_rplItem["%listentitel%"].= $_e["listentitel"];
			}
			
			$_rplItem["%notation%"] = $_e["notation"];
			$_rplItem["%erstelltam%"] = substr($_e[$_SYS2MYSQL[$editTable]["erstelltam"]], 0, 16);
			$_rplItem["%listentext%"] =  $_e[$_SYS2MYSQL[$editTable]["listentext"]];
			
			$_rplItem["%id%"] = $r_id;
			$_rplItem["{id}"] = $r_id;
			$public_liste.= strtr($public_item_vorlage, $_rplItem);
		}
	} else {
		//echo "#74 else<br>";
		$public_liste.="Zur Zeit sind keine Beiträge ver&ouml;ffentlicht!<br>";
	}
	
	$SQL = "SELECT * FROM $editTable \n";
	$SQL.= " WHERE webfreigabe NOT LIKE 'Ja' \n";
	$SQL.= " AND srv LIKE \"".$srv."\" \n";
	$SQL.= " ORDER BY created DESC";
	$result = MyDB::query($SQL);
	$num = MyDB::num_rows($result);
	//echo "#80 num:$num<br>";
	$unpublic_liste = "<br><table width='100%'><tr><td><b>Auf Portalseite gesperrte Beiträge</b></td></tr></table>";
	$unpublic_item_vorlage = "";
	if ($num) {
		$unpublic_item_vorlage = implode("",file($unpublic_item_vorlage_file));
		$unpublic_item_vorlage = str_replace("%srv%", $srv, $unpublic_item_vorlage);
		for ($i = 0; $i < $num; $i++) {
			$_e = MyDB::fetch_array($result);
			$r_id = $_e[$editTableKey];
			$_rplItem["{task}"] = $task;
			$_rplItem["%id%"] = $r_id;
			$_rplItem["{id}"] = $r_id;
			
			$_rplItem["%listentitel%"] = "";
			if ($_e["notation"]) {
				$_rplItem["%listentitel%"] = "<b style=\"font-size:12px;\">".$_e["notation"]."</b>";
			}
			
			if ($_e["listentitel"]) {
				if ($_rplItem["%listentitel%"]) $_rplItem["%listentitel%"].= " &raquo; ";
				$_rplItem["%listentitel%"].= $_e["listentitel"];
			}
			
			$_rplItem["%notation%"] = $_e["notation"];
			$_rplItem["%erstelltam%"] = substr($_e[$_SYS2MYSQL[$editTable]["erstelltam"]], 0, 16);
			$_rplItem["%listentext%"] =  $_e[$_SYS2MYSQL[$editTable]["listentext"]];
			
			$_rplItem["%id%"] = $r_id;
			$_rplItem["{id}"] = $r_id;
			$unpublic_liste.= strtr($unpublic_item_vorlage, $_rplItem);
		}
	} else {
		$unpublic_liste.="Zur Zeit existieren keine unver&ouml;ffentlichten Beiträge!<br>";
	}
	$body_content.=$cmscontent_nav.$public_liste.$unpublic_liste;
	break;
	
	
	case "bearbeiten":
	if (isset($cmstask) && $cmstask == "edit" && isset($id)) {
		$SQL = "SELECT * FROM $editTable WHERE $editTableKey = '$id'";
		$result = MyDB::query($SQL);
		$num = MyDB::num_rows($result);
		//echo "#178 num:$num<br>";
		if ($num) {
			$eingabe = MyDB::fetch_array($result);
			MyDB::free_result($result);
			list($eingabe, $lesen, $fehlerfelder, $fehlertext) = check_input($eingabe, $_VF);
			reset($eingabe);
		}
		
		//while(list($k, $v) = each($eingabe)) echo "#".__LINE__." $k : $v <br>\n";
		//reset($eingabe);
	}
	break;
	
	case "vorschau":
	case "speichern":
	case "korrigieren":
	// Überprüfe Eingabewerte
	if ($cx) echo "#".__LINE__." ansicht:$ansicht<br>\n";
	reset($_POST["eingabe"]);
	//echo "<pre>#".__LINE__." ".arraytoVarString("\$_POST", $_POST)."</pre>\n";
	
	reset($_POST["eingabe"]);
	list($eingabe, $lesen, $fehlerfelder, $fehlertext) = check_input($_POST["eingabe"], $_VF);
	// echo "<pre>#".__LINE__." ".arraytoVarString("\$eingabe", $eingabe)."</pre>\n";
	
	if ($fehlertext) {
		$error.= $fehlertext;
		$ansicht = "korrigieren";
	}
	break;
	
	default:
	break;
}
if ($cx) echo "#".__LINE__." ansicht:$ansicht<br>\n";
//echo "fehlertext: $fehlertext<br>ansicht:$ansicht<br>";


switch ($ansicht) {
	case "liste":
	break;
	
	case "vorschau":
	$cmscontent = implode("",file($vorlage_vorschau_file));
	break;
	
	case "speichern":
	case "lesen":
	$cmscontent = implode("",file($vorlage_lesen_file));
	break;
	
	case "korrigieren":
	case "eingabe":
	case "bearbeiten":
	$cmscontent = implode("",file($vorlage_eingabe_file));
	break;
	
	default:
	$cmscontent = implode("",file($vorlage_eingabe_file));
	break;
}

$_TplVars = array();
$_TplBaseVars = array();
$_TplBaseVars["%area%"] = $area;
$_TplBaseVars["{area}"] = $area;
$_TplBaseVars["%srv%"]  = $srv;
$_TplBaseVars["{srv}"]  = $srv;
$_TplBaseVars["%task%"] = $task;
$_TplBaseVars["{task}"] = $task;
$_TplBaseVars["%id%"]   = $id;
$_TplBaseVars["{id}"]   = $id;
$_TplVars = $_TplBaseVars;

if ($cx) echo "#".__LINE__." ansicht:$ansicht <br>\n";
switch($ansicht) {
	case "liste":
	break;
	
	case "eingabe":
	reset($_VF);
	while(list($k, $v) = each ($_VF)) {	
		// Setze Standard-Eingabewerte
		$eingabe[$k] = $v[1];
	}
	$eingabe["%".$editTableKey."%"] = "";
	
	list($eingabe, $lesen, $fehlerfelderNO, $fehlertextNO) = check_input($eingabe, $_VF);
	get_tplvars($eingabe, $lesen, $_TplVars);
	$cmscontent = strtr($cmscontent, $_TplVars);
	$cmscontent = set_formVars($cmscontent, $_VF);
	list($cmscontent, $ausgabe) = set_cms_editbar($cmscontent,$ausgabe);
	break;
	
	case "bearbeiten":
	get_tplvars($eingabe, $lesen, $_TplVars);
	$cmscontent = strtr($cmscontent, $_TplVars);
	$cmscontent = set_formVars($cmscontent, $_VF, $fehlerfelder);
	list($cmscontent, $ausgabe) = set_cms_editbar($cmscontent, $ausgabe);
	break;
	
	case "lesen":
	if ($eingabe["webfreigabe"] == "Ja") {
		$cmsTasks = implode("", file($cmstasks_public_item_file));
	} else {
		$cmsTasks = implode("", file($cmstasks_unpublic_item_file));
	}
	$cnt_public = count_public_items();
	$pos_options = render_pos_options($cnt_public, $eingabe[$editTableOrd]);
	get_tplvars($eingabe, $lesen, $_TplVars);
	$cmscontent = strtr($cmscontent, $_TplVars);
	break;
	
	case "vorschau":
	$_TplVars["%".$editTableKey."%"] = $id;
	get_tplvars($eingabe, $lesen, $_TplVars);
	$cmscontent = strtr($cmscontent, $_TplVars);
	$cmscontent = set_formVars($cmscontent, $_VF);
	break;
	
	case "korrigieren":
	$cmscontent = str_replace("%id%", $id, $cmscontent);
	get_tplvars($eingabe, $lesen, $_TplVars);
	$cmscontent = strtr($cmscontent, $_TplVars);
	$cmscontent = set_formVars($cmscontent,$_VF, $fehlerfelder);
	list($cmscontent,$ausgabe) = set_cms_editbar($cmscontent,$ausgabe);
	break;

	case "speichern":
	$mysql_save_mode = ((int)($eingabe[$editTableKey]) > 0) ? "UPDATE" : "INSERT" ;
	list($insertID,$fehlertext) = MyDB::save_input(
										$eingabe,
										$_VF,
										$editTable,
										$editTableKey,
										$mysql_save_mode,
										$benutzerdaten);
	$id = $insertID;
	
	if (!$eingabe["common_lang_key"] && !$fehlertext) {
		echo "#".__LINE__." <br>\n";
		$SQL = "UPDATE $editTable SET common_lang_key = CONCAT(CONCAT(`lang`,\"-\"),`$editTableKey`)\n";
		$SQL.= "WHERE `$editTableKey` = \"".MyDB::escape_string($insertID)."\"";
		MyDB::query($SQL, $connid)."<br>\nSQL:".$SQL."<br>\n";;
		echo "#".__LINE__." ".MyDB::error();
	} else {
		echo "#".__LINE__." <br>\n";
	}
	
	$SQL = "SELECT * FROM $editTable WHERE $editTableKey = $insertID LIMIT 1";
	$r = MyDB::query($SQL);
	$_e = MyDB::fetch_array($r, MYSQL_ASSOC);
	
	// echo "#".__LINE__." eingabe[volltext]: ".$eingabe["volltext"]."<br>\n";
	list($eingabe, $lesen, $fehlerfelderSave, $fehlertextSave) = check_input(
															$_e,
															$_VF);
	// echo "#".__LINE__." eingabe[volltext]: ".$eingabe["volltext"]."<br>\n";
	
	get_tplvars($eingabe, $lesen, $_TplVars);
	$cmscontent = strtr($cmscontent, $_TplVars);
	$cmscontent = set_formVars($cmscontent, $_VF);
	
	if (!$fehlertext) {
		$cmscontent_nav.='
		<table cellpadding="1" cellspacing="0" border="0" width="440" align="center">
	<tr>
		<td bgcolor=green><strong><font color="#FFFFFF">&nbsp; : : Der Beitrag wurde im System gespeichert!</font></strong></td>
	</tr>
</table><br>';
	} else {
		//echo "Fehler beim Speichern der Daten!<br>\r\n$fehlertext<br>\n";
		$error.= $fehlertext;
	}
	
	if ($_e["webfreigabe"] == "Ja") {
		$cmsTasks = implode("", file($cmstasks_public_item_file));
	} else {
		$cmsTasks = implode("", file($cmstasks_unpublic_item_file));
	}
	$cnt_public = count_public_items();
	$pos_options = render_pos_options($cnt_public, $_e[$editTableOrd]);  
	$cmscontent = str_replace("<!-- cmsTasks -->", $cmsTasks, $cmscontent);
	break;
}

if (!empty($eingabe) && $eingabe["media_src"]) {
	$arrParams = array();
	parse_str($eingabe["media_params"], $arrParams);
	$cmscontent = str_replace("<!-- {media_object} -->",
							createVideoOjectTag_Factory($eingabe["media_src"], $arrParams),
							$cmscontent);
}

$_TplBaseVars["%id%"]   = $id;
$_TplBaseVars["{id}"]   = $id;
$cmscontent_nav = strtr($cmscontent_nav, $_TplBaseVars);
$cmscontent = strtr($cmscontent, $_TplBaseVars);
$body_content.= $cmscontent_nav.$hintsandtipps.$cmscontent;
