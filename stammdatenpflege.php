<?php require("header.php"); 
ob_start();
$s = getRequest("s");
if (basename(__FILE__)==basename($_SERVER["PHP_SELF"])) {
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<?php } ?>
	<title>Untitled</title>
	<link rel="STYLESHEET" type="text/css" href="css/tablelisting.css?%assetsRefreshId%">
	<link rel="stylesheet" type="text/css" href="css/umzugsformular.css?%assetsRefreshId%">
	<style>
	/*, body *, html * { font-family:Arial,Helvetica,sans-serif; font-size:12px; }*/
	.jLink { cursor:pointer; color:#00f; }
	.rowHide { display:none; }
	.tblList td input, .tblList td textarea { border:0; }
	.tblList .rowInfoLine td { border-bottom:2px solid #b4b4b4; }
	</style>
	
	<script src="js/GetObjectDisplay.js?%assetsRefreshId%" type="text/javascript"></script>
	<script src="js/jquery.js?%assetsRefreshId%" type="text/javascript"></script>
	<script src="js/EventHandler.js?%assetsRefreshId%" type="text/javascript"></script>
	<script src="js/FbAjaxUpdater.js?%assetsRefreshId%" type="text/javascript"></script>
	<script src="js/PageInfo.js?%assetsRefreshId%" type="text/javascript"></script>
<link rel="STYLESHEET" type="text/css" href="css/stammdatenpflege.css?%assetsRefreshId%">
<script src="js/stammdatenpflege.js?%assetsRefreshId%" type="text/javascript"></script>
<?php if (basename(__FILE__)==basename($_SERVER["PHP_SELF"])) { ?>
</head>

<body><?php } ?><div id="Monitor"></div>
<!-- <form name="frmMAFilterSelBox">
<label for="ort">Ort</label><input onclick="get_SearchInputListeOrte(this)" id="ortsFilter" type="text" name="ort">
<label for="gebaeude">Geb&auml;ude</label><input onfocus="get_SearchInputListeGebaeude(this)" type="text" id="gebaeudeFilter" name="gebaeude">
<label for="etage">Etage</label><input onfocus="get_SearchInputListeEtage(this)" type="text" id="etageFilter" name="etage"></select>
<input type="submit" value="ok">
</form> -->
<?php 
$frmImoFilter = <<<FrmImoFilter
<div class="boxMenu">
<div class="boxFrmFilter">
<form action="{action}" name="frmMAFilter" style="display:inline;">
<label for="ort" style="width:auto;">Ort</label><select name="ort" onchange="refreshImoFilter(this)">{optionsOrte}</select>
<label for="gebaeude" style="width:auto;">Geb&auml;ude</label><select name="gebaeude" onchange="refreshImoFilter(this)">{optionsGebaeude}</select>
<label for="etage" style="width:auto;">Etage</label><select id="etageFilter" name="etage" onchange="refreshImoFilter(this)">{optionsEtage}</select>
<label for="etage" style="width:auto;">Raum</label><select id="raumFilter" name="raum" onchange="refreshImoFilter(this)">{optionsRaeume}</select>
<input type="hidden" name="s" value="{s}">
<input type="submit" value="ok">
</form></div>
<div class="boxLnkNewData"><a href="#" onclick="getInlineForm('close')">X</a>
<a href="#" onclick="getInlineForm('NewEmployer');return false;">Neuer Mitarbeiter</a> 
<a href="#" onclick="getInlineForm('NewRoom');return false;">Neuer Raum</a> 
<a href="#" onclick="getInlineForm('NewDepart');return false;">Neue Abteilung</a> 
<a href="#" onclick="getInlineForm('NewMainDepart'); return false;">Neuen Bereich (H-Abt)</a>
</div>
<div id="frmEditData"><!-- {Msg} --></div>
</div>
FrmImoFilter;
?>
<?php 
include("bestandsaufnahme_speichern.php");
// UPDATE-Start einmalige UPDATE mit einfacher Abfrage, ob erforderlich
$rows = $db->query_rows("SELECT id FROM `".$_TABLE["immobilien"]."` WHERE `ort` = \"N\" LIMIT 1");
if (is_array($rows) && count($rows)) include("bestandsaufnahme_sqlupdate.php");
// UPDATE-Ende

include("bestandsaufnahme_sqlupdate_aufgenommen_am.php");

if (!isset($error)) $error = "";
if (!isset($msg)) $msg = "";

$BereichData = new bereich();
$AbtlgData = new abteilung();
$RaumData = new raumdata();

// RaumTyp �ndern
$editRaumtyp = getRequest("editRaumtyp", "");
if ($editRaumtyp) {
	$del_raum_id = getRequest("raum", "");
	$editRaum["raum_typ_id"] = getRequest("raum_typ_id", "");
	
	if ($RaumData->setRaumtypById($del_raum_id, $editRaum["raum_typ_id"])) {
		$msg.= "Raumtyp wurde ge�ndert!<br>\n";
	} else {
		$error.= "Raumtyp konnte nicht ge�ndert werden!<br>\n";
		$error.= "Fehler: ".$RaumData->error."<br>\n";
	}
}

// RaumNr �ndern
$editRaumnr = getRequest("editRaumnr", "");
if ($editRaumnr) {
	$del_raum_id = getRequest("raum", "");
	$editRaum["raumnr"] = getRequest("raumnr", "");
	
	if ($RaumData->setRaumnrById($del_raum_id, $editRaum["raumnr"])) {
		$msg.= "Raumnr wurde ge�ndert!<br>\n";
	} else {
		$error.= "Raumnr konnte nicht ge�ndert werden!<br>\n";
		$error.= "Fehler: ".$RaumData->error."<br>\n";
	}
}

// RaumFl�che �ndern
$editRaumflaeche = getRequest("editRaumflaeche", "");
if ($editRaumflaeche) {
	$edit_raum_id = getRequest("raum", "");
	$editRaum["raumflaeche"] = getRequest("raumflaeche", "");
	
	if ($RaumData->setRaumflaecheById($edit_raum_id, $editRaum["raumflaeche"])) {
		$msg.= "Raumfl�che wurde ge�ndert!<br>\n";
	} else {
		$error.= "Raumfl�che konnte nicht ge�ndert werden!<br>\n";
		$error.= "Fehler: ".$RaumData->error."<br>\n";
	}
}

// Raum l�schen
$deleteRaum = getRequest("deleteRaum", "");
if ($deleteRaum) {
	$backUpDir = $MConf["AppRoot"]."geloescht/";
	$del_raum_id = getRequest("raum", "");
	if ($del_raum_id) {
		$RaumData = new raumdata();
		$RaumData->error = "";
		if ($RaumData->exists($del_raum_id)) {
			if ($RaumData->isEmpty($del_raum_id)) {
				$sql = "SELECT * FROM `".$_TABLE["immobilien"]."` WHERE id = ".(int)$del_raum_id;
				$db->query("INSERT INTO `mm_stamm_immobilien_geloescht` ".$sql);
				$db->query_export_csv($sql, $backUpDir."geloeschte_raeume.csv", ";", "\"", "\"\"", true);
				if ($RaumData->delete($del_raum_id)) {
					$msg.= "Der Raumdatensatz wurde gel�scht!<br>\n";
				} else {
					$error.= "Fehler beim L�schen! ".$RaumData->error."<br>\n";
				}
			} else {
				$error.= "Raum kann nicht gel�scht werden! Dem Raum sind noch ".$RaumData->numMitarbeiter($del_raum_id)." Mitarbeiter zugeordnet!<br>\n";
			}
		} else {
			$error.= "Es existiert kein Raum mit der ID:".$del_raum_id."!<br>\n";
		}
	} else {
		$error.= "Fehlende RaumId f�r L�schvorgang!<br>\n";
	}
}

// RaumStatus aktualisieren
$editRaumstatus = getRequest("editRaumstatus", "");
if ($editRaumstatus) {
	$edit_raum_id = getRequest("raum", "");
	
	if ($RaumData->updateRaumstatusById($edit_raum_id)) {
		$msg.= "Raumstatus wurde ge�ndert!<br>\n";
	} else {
		$error.= "Raumstatus konnte nicht aktualisiert werden!<br>\n";
		$error.= "Fehler: ".$RaumData->error."<br>\n";
	}
}


$first = true;
$formScript = "aBereiche = {";
foreach($BereichData->aListe as $v) {
	$formScript.= (!$first?",\n":"")."\t\"".$v["bereich"]."\" : { \"oe\":\"".$v["organisationseinheit"]."\", \"b\":\"".$v["bereich"]."\", \"bname\": \"".$v["bereichsname"]."\" }";
	if ($first) $first = false;
}
$formScript.= "};\n";

$first = true;
$formScript.= "aAbteilungen = {";
foreach($AbtlgData->aAbteilungen as $k => $v) {
	$formScript.= (!$first?",\n":"")."\t\"".$v["id"]."\" : { \"b\":\"".$v["bereich"]."\", \"a\": \"".$v["abteilung"]."\", \"aname\": \"".$v["abteilungsname"]."\" }";
	if ($first) $first = false;
}
$formScript.= "};\n";
echo "<script>\n".$formScript."</script>\n";

function getOptionsAbteilungen(&$aAbt, $bereich, $aid, $abk = "") {
	//id, bereich, abteilung, abteilungsname\n";
	$options = "";
	foreach($aAbt as $a) {
		if ($bereich && $a["bereich"] != $bereich) continue;
		$selected = ($a["id"] != $aid && $abk != $a["abteilung"]) ? "" : "selected=\"true\"";
		$options.= "<option value=\"".$a["id"]."\" $selected>".$a["abteilung"]."</option>\n";
	}
	//$options.= "<option>$aid=$abk</option>\n";
	return $options;
}

function getOptionsBereiche(&$aBereiche, $oe, $bereich) {
	//id 	bereich 	bereichsname 	bereichsleiter 	organisationseinheit
	$options = "";
	foreach($aBereiche as $b) {
		if (!isset($b["organisationseinheit"])) echo "b: <pre>".print_r($b,1)."</pre>";
		if ($oe && $b["organisationseinheit"] != $oe) continue;
		$selected = ($b["bereich"] != $bereich) ? "" : "selected=\"true\"";
		$options.= "<option value=\"".$b["bereich"]."\" $selected>".$b["bereich"]."</option>\n";
	}
	
	return $options;
}

function getBereichByAbteilung(&$aAbt, $abt) {
	foreach($aAbt as $a) if ($a["abteilung"] == $abt) return $a["bereich"];
	return "";
}

function getGFByBereich(&$aBereiche, $bereich) {
	foreach($aBereiche as $b) if ($b["bereich"] == $bereich) return $b["organisationseinheit"];
	return "";
}

$thisRaum = array();
$optionsOrte = "";
$optionsGebaeude = "";
$optionsEtagen = "";
$optionsRaeume = "";
$JSON_Gebaeude = "";
$aOrte = array();
$aGebaeude = array();
$aEtagen = array();
$aRaeume = array();
$defaultEtage = "";
$defaultRaum = "";
$sql = "SELECT stadtname, gebaeude, gebaeudename\n";
$sql.= " FROM `".$_TABLE["gebaeude"]."`\n";
$sql.= " ORDER BY stadtname, gebaeudename\n";
$rows = $db->query_rows($sql);
foreach($rows as $row) $aOrteGebaeude[$row["stadtname"]][$row["gebaeude"]] = $row["gebaeudename"];

$ort = "";
$gebaeude = "";
$etage = "";
$raum = getRequest("raum", "");
if ($raum) {
	$sql = "SELECT ort, gebaeude, etage, raumnr\n";
	$sql.= " FROM `".$_TABLE["immobilien"]."`\n";
	$sql.= " WHERE id = \"".MyDB::escape_string($raum)."\" \n";
	$sql.= " LIMIT 1\n";
	$row = $db->query_singlerow($sql);
	if (!empty($row) && !empty($row["ort"])) {
		$ort = $row["ort"];
		$gebaeude = $row["gebaeude"];
		$etage = $row["etage"];
	}
}
if (!$ort) $ort = getRequest("ort", key($aOrteGebaeude));

if ($ort && !empty($aOrteGebaeude[$ort])) list($defGebaeude, $defGebaeudename) = each($aOrteGebaeude[$ort]);
else $defGebaeude = "";
if (!$gebaeude) $gebaeude = getRequest("gebaeude", $defGebaeude);
if ($ort && $gebaeude) {
	$sql = "SELECT etage\n";
	$sql.= " FROM `".$_TABLE["immobilien"]."`\n";
	$sql.= " WHERE gebaeude LIKE \"".MyDB::escape_string($gebaeude)."\"\n";
	$sql.= " GROUP BY etage\n";
	$sql.= " ORDER BY etage\n";
	$aEtagen = $db->query_rows($sql);
	if (count($aEtagen)) $defaultEtage = $aEtagen[0]["etage"];
}
if (!$etage) $etage = getRequest("etage", $defaultEtage);

if ($ort && $gebaeude && $etage) {
	$sql = "SELECT id, raumnr\n";
	$sql.= " FROM `".$_TABLE["immobilien"]."`\n";
	$sql.= " WHERE gebaeude LIKE \"".MyDB::escape_string($gebaeude)."\" AND etage =\"".MyDB::escape_string($etage)."\"\n";
	$sql.= " GROUP BY id\n";
	$sql.= " ORDER BY raumnr\n";
	$aRaeume = $db->query_rows($sql);
	if (count($aRaeume)) $defaultRaum = $aRaeume[0]["id"];
	//echo "#".__LINE__." ".basename(__FILE__)." ".MyDB::error()."; sql:$sql <pre>".print_r($aRaeume,1)."</pre><br>\n";
}
$raum = getRequest("raum", $defaultRaum);

if ($raum) {
	$sql = "SELECT *\n";
	$sql.= " FROM `".$_TABLE["immobilien"]."`\n";
	$sql.= " WHERE id =\"".(int)$raum."\"\n";
	$thisRaum = $db->query_singlerow($sql);
	
	if (!empty($thisRaum)) {
		$thisRaum["raum_typ_id"] = "";
		if ($thisRaum["raum_typ"]) {
			$sql = "SELECT id FROM `".$_TABLE["raumtypen"]."` WHERE `raumtyp` = \"".$db->escape($thisRaum["raum_typ"])."\" LIMIT 1";
			$row = $db->query_singlerow($sql);
			if (!empty($row["id"])) $thisRaum["raum_typ_id"] = $row["id"];
		}
	}
}

//echo "#".__LINE__." ".basename(__FILE__)." ort:$ort, gebaeude:$gebaeude, etage:$etage<br>\n";

foreach($aOrteGebaeude as $rOrt => $aGebaeude) {
	$selected = ($ort == $rOrt) ? "selected=\"true\"" : "";
	$optionsOrte.= "<option value=\"$rOrt\" $selected>$rOrt</option>\n";
	$JSON_Gebaeude.= ($JSON_Gebaeude?",\n":"")."\t\"".$rOrt."\":[";
	$first = true;
	foreach($aGebaeude as $k => $v) {
		$JSON_Gebaeude.= (!$first?",":"")."\"".addslashes($k)."\"";
		if ($first) $first = false;
		
		if ($rOrt == $ort) {
			$selected = ($k == $gebaeude) ? "selected=\"true\"" : "";
			$optionsGebaeude.= "<option value=\"".$k."\" $selected>".$v."</option>\n";
		}
	}
	$JSON_Gebaeude.= "]";
}

//echo print_r($aEtagen,1);
for($i = 0; $i < count($aEtagen); $i++) {
	$selected = ($etage == $aEtagen[$i]["etage"]) ? "selected=\"true\"" : "";
	$optionsEtagen.= "<option value=\"".$aEtagen[$i]["etage"]."\" $selected>".$aEtagen[$i]["etage"]."</option>\n";
}

//echo print_r($aRaeume,1);
for($i = 0; $i < count($aRaeume); $i++) {
	$selected = ($raum == $aRaeume[$i]["id"]) ? "selected=\"true\"" : "";
	$optionsRaeume.= "<option value=\"".$aRaeume[$i]["id"]."\" $selected>".$aRaeume[$i]["raumnr"]."</option>\n";
}

$MsgBox = "";
if ($error) $MsgBox.= "<div class=\"err\">".$error."</div>\n";
if ($msg) $MsgBox.= "<div class=\"msg\">".$msg."</div>\n";

echo "<script>var GebaeudeListe = {\n $JSON_Gebaeude };\n</script>";
$frmImoFilter = str_replace("{action}", "".basename($_SERVER["PHP_SELF"]), $frmImoFilter);
$frmImoFilter = str_replace("{s}", $s, $frmImoFilter);
$frmImoFilter = str_replace("{optionsOrte}", $optionsOrte, $frmImoFilter);
$frmImoFilter = str_replace("{optionsGebaeude}", $optionsGebaeude, $frmImoFilter);
$frmImoFilter = str_replace("{optionsEtage}", $optionsEtagen, $frmImoFilter);
$frmImoFilter = str_replace("{optionsRaeume}", $optionsRaeume, $frmImoFilter);
$frmImoFilter = str_replace("<!-- {Msg} -->", $MsgBox, $frmImoFilter);

echo $frmImoFilter;
$liste = "";

$mTbl = $_TABLE["mitarbeiter"];
$iTbl = $_TABLE["immobilien"];
$aTbl = $_TABLE["abteilungen"];
$bTbl = $_TABLE["hauptabteilungen"];

$sql = "select m.id, m.immobilien_raum_id, m.abteilungen_id, 
m.name, m.vorname, m.extern, m.extern_firma, m.ersthelfer, m.raeumungsbeauftragter, m.anmerkung, m.gf, m.bereich, m.abteilung,
i.ort, i.gebaeude, i.etage, i.raumnr,  m.arbeitsplatznr, DATE_FORMAT(m.aufgenommen_am, \"%d.%m.%Y\") aufgenommen_am
FROM `$mTbl` m
LEFT JOIN `$iTbl` i ON (m.immobilien_raum_id = i.id)
LEFT JOIN `$aTbl` a ON (m.abteilungen_id = a.id)
LEFT JOIN `$bTbl` b ON (b.bereich = a.bereich)
WHERE i.id = \"".$db->escape($raum)."\" 
ORDER BY i.ort, i.etage, i.raumnr, m.name, m.vorname";
// Alte Where-Abfrage:  i.gebaeude = \"".$db->escape($gebaeude)."\" AND i.etage = \"".$db->escape($etage)."\" AND i.raum = \"".$db->escape($raum)."\"

$rows = $db->query_rows($sql);
if (!$db->error()) {
//echo "<pre>".print_r($rows[0],1)." rows:$rows; count(rows):".count($rows)." gettype(rows):".gettype($rows)."</pre>\n";

if (!empty($RowsError)) foreach($RowsError as $k => $vError) echo $vError."<hr/>\n";
if (count($rows)) {
	
	$GFAbkSelect = array();
	$GFData = new gf();
	foreach($GFData->aSelect as $k => $v) $GFAbkSelect[$k] = $k;
	$theadColTitles = "<tr><td>#</td><td><img src=\"images/text.png\" width=\"16\" height=\"16\" alt=\"\"></td><td><img src=\"images/loeschen_off.png\" width=\"14\" height=\"14\" alt=\"\"></td><td>Name</td><td>Vorname</td><td>AP-Nutzung</td><td>Firma (wenn ext.)</td><td>A-Nr.</td><td>GF</td><td>Bereich</td><td>Abtlg</td><td title=\"Erst-Helfer\">EH</td><td title=\"R�umgsbeauftragter\">RBA</td><td>Aufg. am</td></tr>\n";
	if (!empty($rows)) for ($i = 0; $i < count($rows); $i++) {
		$e = $rows[$i];
		if ($e["abteilung"] && !$e["bereich"]) $e["bereich"] = getBereichByAbteilung($AbtlgData->aAbteilungen, $e["abteilung"]);
		if ($e["bereich"] && !$e["gf"]) $e["gf"] = getGFByBereich($BereichData->aListe, $e["bereich"]);
		
		$rowId = "row".$i;
		if ($i % 10 == 0) $liste.= "<thead>".$theadColTitles."</thead>\n";
		$liste.= "<tr class=\"".($i%2?'wz2':'wz1')." rowFirstInGroup\" id=\"{$rowId}i1\">\n";
			$RowNrStyle = (isset($RowsError[$e["id"]]) ? "color:#f00;" : (isset($RowsSaved[$e["id"]]) ? "color:#008000;" : ""));
			$liste.= "<td class=\"cellInput\" style=\"text-align:right;font-weight:bold;$RowNrStyle\">$i</td>\n";
			$liste.= "<td class=\"cellInput\"><div onclick=\"TC('{$rowId}i2', 'rowHide', '')\" xonmouseover=\"alert(O+'; '+'anmerkung[$i]: '+O('anmerkung[$i]'))\" class=\"jLink\"><img src=\"images/text.png\" width=\"16\" height=\"16\" alt=\"Anmerkung\" title=\"Anmerkung\"></div></td>\n";
			$liste.= "<td class=\"cellInput\"><div onclick=\"dropMa('".$e["id"]."', '{$rowId}'); \" class=\"jLink\"><img src=\"images/loeschen_off.png\" width=\"14\" height=\"14\" alt=\"Datensatz l�schen\" title=\"Datensatz l�schen\"></div></td>\n";
			$liste.= "<td class=\"cellInput\">".get_InputText("name[$i]", $e["name"])."</td>\n";
			$liste.= "<td class=\"cellInput\">".get_InputText("vorname[$i]", $e["vorname"])."</td>\n";
			$liste.= "<td class=\"cellInput\">".get_SelectBox("extern[$i]", $e["extern"], array("Staff", "Extern", "Funktionsarbeitsplatz", "Flex-Position", "Spare"), false, "onchange=\"checkListExternFirma($i)\" default=\"".$e["extern"]."\"")."</td>\n";
			$liste.= "<td class=\"cellInput\">".get_InputText("extern_firma[$i]", $e["extern_firma"])."</td>\n";
			$liste.= "<td class=\"cellInput\">".get_InputText("arbeitsplatznr[$i]", $e["arbeitsplatznr"], "maxlength=3 size=4")."</td>\n";
			
			//$liste.= "<td>".get_SelectBox("imo_raum_id[$i]", $e["immobilien_raum_id"], array($e["immobilien_raum_id"]=>$e["ort"]." | Etg:".$e["etage"]." | R:".$e["raumnr"]." | m�:".$e["groesse_qm"]), true, "")."</td>\n";
			$liste.= get_InputHidden("imo_raum_id[$i]", $e["immobilien_raum_id"]);
			
			//$liste.= "<td class=\"cellInput\">".get_SelectBox("abteilungs_kategorie[$i]", $e["abteilungs_kategorie"], array('GF'=>'GF','Bereich'=>'Bereich','Abteilung'=>'Abtlg',''=>'N.N.'), true, "onchange=\"checkListAbteilungsAuswahl($i)\"")."</td>\n";
			$liste.= "<td class=\"cellInput\">".get_SelectBox("gf[$i]", $e["gf"], $GFAbkSelect, true, " style=\"width:auto;\" onchange=\"reloadListSelectBereiche($i)\"")."</td>\n";
			
			$liste.= "<td class=\"cellInput\"><select name=\"bereich[$i]\" style=\"width:auto;\" onchange=\"reloadListSelectAbteilungen($i)\"><option></option>\n".getOptionsBereiche($BereichData->aListe, $e["gf"], $e["bereich"])."</select></td>\n";
			$liste.= "<td class=\"cellInput\"><select name=\"abteilungen_id[$i]\" style=\"width:auto;\"><option></option>\n".getOptionsAbteilungen($AbtlgData->aAbteilungen, $e["bereich"], $e["abteilungen_id"], $e["abteilung"])."</select></td>\n";
			//$liste.= "<td class=\"cellInput\">".get_SelectBox("abteilungen_id[$i]", $e["abteilungen_id"], array($e["abteilungen_id"]=>$e["abteilung"]), true)."</td>\n";
			
			$liste.= "<td class=\"cellInput\">".get_InputCheckBox("ersthelfer[$i]", $e["ersthelfer"], array("Ja"=>"E.H."), true, "", array("ShowLabel"=>false))."</td>\n";
			$liste.= "<td class=\"cellInput\">".get_InputCheckBox("raeumungsbeauftragter[$i]", $e["raeumungsbeauftragter"], array("Ja"=>"R.B."), true, "", array("ShowLabel"=>false))."</td>\n";
			$liste.= "<td class=\"cellInput\">".$e["aufgenommen_am"]."</td>\n";
			
			$liste.= "</tr>\n";
		$liste.= "<tr class=\"".($i%2?'wz2':'wz1')." rowInfoLine rowHide\" id=\"{$rowId}i2\"><td colspan=15 class=\"cellInputLast\">".get_TextArea("anmerkung[$i]", $e["anmerkung"])."</td></tr>\n";
		$liste.= "<input type=\"hidden\" name=\"id[$i]\" value=\"".fb_htmlEntities($e["id"])."\">\n";
		$liste.= "<input type=\"hidden\" name=\"rownr[$i]\" value=\"".fb_htmlEntities($rowId)."\">\n";
	}
	echo "<form action=\"".basename($_SERVER["PHP_SELF"])."\" name=\"frmListe\" method=\"post\" style=\"display:inline;\">\n";
	echo "<input class=\"iSave\" type=\"submit\" name=\"save\" value=\"Arbeitsplatzdaten aktualisieren\" style=\"margin:15px 0 0 0;\">\n";
	echo "<table class=tblList style=\"margin-top:0;\">$liste</table>\n";
	if (count($rows) > 20) echo "<input class=\"iSave\" type=\"submit\" name=\"save\" value=\"Speichern\">\n";
	echo "<input type=\"hidden\" name=\"s\" value=\"".fb_htmlEntities($s)."\">\n";
	echo "<input type=\"hidden\" name=\"raum\" value=\"".fb_htmlEntities($raum)."\">\n";
	echo "<input type=\"hidden\" name=\"etage\" value=\"".fb_htmlEntities($etage)."\">\n";
	echo "<input type=\"hidden\" name=\"gebaeude\" value=\"".fb_htmlEntities($gebaeude)."\">\n";
	echo "<input type=\"hidden\" name=\"ort\" value=\"".fb_htmlEntities($ort)."\">\n";
	echo "</form>";
	}
} else {
	echo $db->error();
}

if (!empty($thisRaum)) {
	if ($gebaeude) {
		$sql = "SELECT * FROM `".$_TABLE["gebaeude"]."` WHERE gebaeude LIKE \"".$gebaeude."\" LIMIT 1";
		$thisGebaeude = $db->query_singlerow($sql);
	}
	if (empty($thisGebaeude)) $thisGebaeude["adresse"] = "";
	
	echo "<table class=tblList cellpadding=0 cellspacing=0 border=1 style=\"width:350px;margin-top:20px;\">\n";
	echo "<thead><tr><td colspan=2>Raum Detailinfos:</td></tr></thead>\n";
	echo "<tbody>\n";
	echo "<tr class=\"wz1\"><td style=\"padding-right:10px;text-align:right;font-size:11px;font-family:Arial,sans-serif;\">Adresse</td><td style=\"padding-left:5px;font-size:11px;font-family:Arial,sans-serif;\">".$thisGebaeude["adresse"]."</td></tr>\n";
	$wz = 1;
	foreach($thisRaum as $k => $v) {
		if ($k == "raumart" || $k == "groesse_qm" || $k == "raum_typ_id") continue;
		$wz = ($wz!=1)?1:2;
		echo "<tr class=\"wz{$wz}\"><td style=\"padding-right:10px;text-align:right;font-size:11px;font-family:Arial,sans-serif;\">$k</td><td style=\"padding-left:5px;font-size:11px;font-family:Arial,sans-serif;\">$v</td></tr>\n";
	}
	echo "</tbody></table></div>\n";
	
	echo "<div><form method=\"get\" action=\"".basename($_SERVER["PHP_SELF"])."\" style=\"display:inline;margin:0;\">\n";
	echo "<input type=\"submit\" name=\"editRaumstatus\" value=\"Status aktualisieren: Aufgenommen am\" style=\"border-style: groove;width:350px;font-size:11px;color:#008000;\">\n";
	echo "<input type=\"hidden\" name=\"s\" value=\"".fb_htmlEntities($s)."\">\n";
	echo "<input type=\"hidden\" name=\"raum\" value=\"".fb_htmlEntities($thisRaum["id"])."\">\n";
	echo "<input type=\"hidden\" name=\"etage\" value=\"".fb_htmlEntities($thisRaum["etage"])."\">\n";
	echo "<input type=\"hidden\" name=\"gebaeude\" value=\"".fb_htmlEntities($thisRaum["gebaeude"])."\">\n";
	echo "<input type=\"hidden\" name=\"ort\" value=\"".fb_htmlEntities($thisRaum["ort"])."\">\n";
	echo "</form></div>\n";
	$raumTypOptions = '';
	echo "<div><form method=\"post\" action=\"".basename($_SERVER["PHP_SELF"])."\" style=\"display:inline;margin:0;\"><select name=\"raum_typ_id\" style=\"font-size:12px;width:200px;\">\n";
		foreach($RaumData->aRaumTypen as $optgroup => $childs) {
			$raumTypOptions.= "<optgroup label=\"$optgroup\">\n";
			foreach($childs as $chid => $chtxt) {
				$selected = ($thisRaum["raum_typ_id"] == $chid) ? "selected=\"true\"" : "";
				$raumTypOptions.= "<option value=\"$chid\" $selected thisRaumTyp=\"".$thisRaum["raum_typ"]."\">$chtxt</option>\n";
			}
			$raumTypOptions.= "</optgroup>\n";
		}
	echo $raumTypOptions;
	echo "</select>";
	echo "<input type=\"submit\" name=\"editRaumtyp\" value=\"Raumtyp �ndern\" style=\"border-style: groove;width:150px;font-size:11px;\">\n";
	echo "<input type=\"hidden\" name=\"s\" value=\"".fb_htmlEntities($s)."\">\n";
	echo "<input type=\"hidden\" name=\"raum\" value=\"".fb_htmlEntities($thisRaum["id"])."\">\n";
	echo "<input type=\"hidden\" name=\"etage\" value=\"".fb_htmlEntities($thisRaum["etage"])."\">\n";
	echo "<input type=\"hidden\" name=\"gebaeude\" value=\"".fb_htmlEntities($thisRaum["gebaeude"])."\">\n";
	echo "<input type=\"hidden\" name=\"ort\" value=\"".fb_htmlEntities($thisRaum["ort"])."\">\n";
	echo "</form></div>\n";
	
	echo "<div><form method=\"post\" action=\"".basename($_SERVER["PHP_SELF"])."\" style=\"display:inline;margin:0;\">\n";
	echo "<input type=\"text\" name=\"raumnr\" value=\"".fb_htmlEntities($thisRaum["raumnr"])."\" style=\"width:200px;font-size:11px;\">";
	echo "<input type=\"submit\" name=\"editRaumnr\" value=\"Raumnr �ndern\" style=\"border-style: groove;width:150px;font-size:11px;\">\n";
	echo "<input type=\"hidden\" name=\"s\" value=\"".fb_htmlEntities($s)."\">\n";
	echo "<input type=\"hidden\" name=\"raum\" value=\"".fb_htmlEntities($thisRaum["id"])."\">\n";
	echo "<input type=\"hidden\" name=\"etage\" value=\"".fb_htmlEntities($thisRaum["etage"])."\">\n";
	echo "<input type=\"hidden\" name=\"gebaeude\" value=\"".fb_htmlEntities($thisRaum["gebaeude"])."\">\n";
	echo "<input type=\"hidden\" name=\"ort\" value=\"".fb_htmlEntities($thisRaum["ort"])."\">\n";
	echo "</form></div>\n";
	
	echo "<div><form method=\"post\" action=\"".basename($_SERVER["PHP_SELF"])."\" style=\"display:inline;margin:0;\">\n";
	echo "<input type=\"text\" name=\"raumflaeche\" value=\"".fb_htmlEntities(str_replace(".", ",",$thisRaum["raum_flaeche"]))."\" style=\"width:200px;font-size:11px;\">";
	echo "<input type=\"submit\" name=\"editRaumflaeche\" value=\"Raumfl�che �ndern\" style=\"border-style: groove;width:150px;font-size:11px;\">\n";
	echo "<input type=\"hidden\" name=\"s\" value=\"".fb_htmlEntities($s)."\">\n";
	echo "<input type=\"hidden\" name=\"raum\" value=\"".fb_htmlEntities($thisRaum["id"])."\">\n";
	echo "<input type=\"hidden\" name=\"etage\" value=\"".fb_htmlEntities($thisRaum["etage"])."\">\n";
	echo "<input type=\"hidden\" name=\"gebaeude\" value=\"".fb_htmlEntities($thisRaum["gebaeude"])."\">\n";
	echo "<input type=\"hidden\" name=\"ort\" value=\"".fb_htmlEntities($thisRaum["ort"])."\">\n";
	echo "</form></div>\n";
	
	echo "<div><form method=\"get\" action=\"".basename($_SERVER["PHP_SELF"])."\" style=\"display:inline;margin:0;\">\n";
	echo "<input type=\"submit\" name=\"deleteRaum\" value=\"Raum l�schen\" style=\"border-style: groove;width:350px;font-size:11px;color:#f00;\">\n";
	echo "<input type=\"hidden\" name=\"s\" value=\"".fb_htmlEntities($s)."\">\n";
	echo "<input type=\"hidden\" name=\"raum\" value=\"".fb_htmlEntities($thisRaum["id"])."\">\n";
	echo "<input type=\"hidden\" name=\"etage\" value=\"".fb_htmlEntities($thisRaum["etage"])."\">\n";
	echo "<input type=\"hidden\" name=\"gebaeude\" value=\"".fb_htmlEntities($thisRaum["gebaeude"])."\">\n";
	echo "<input type=\"hidden\" name=\"ort\" value=\"".fb_htmlEntities($thisRaum["ort"])."\">\n";
	echo "</form></div>\n";
}
$body_content = ob_get_contents();
ob_end_clean();
if (basename(__FILE__)==basename($_SERVER["PHP_SELF"])) {
	echo $body_content;
} else {
?>
<!-- 
<div onclick="reloadAllListSelectAbteilungen()">reloadAllListSelectAbteilungen</div>
<div onclick="reloadAllListSelectBereiche()">reloadAllListSelectBereiche</div> -->
</body>
</html>
<?php } ?>
