<?php 
require("header.php");
require_once($MConf["AppRoot"].$MConf["Inc_Dir"]."php_json.php");

$maxSize = 200;
$nachname = getRequest("nachname","");
$input = getRequest("input","");
$limit = (int)getRequest("limit",$maxSize);
if (!$limit) $limit = $maxSize;
$raumnr = getRequest("raumnr","");
$SBBoxId = getRequest("SBBoxId","");

$sqlNumAll = "SELECT COUNT(*) count FROM ".$_TABLE["mitarbeiter"]." m
WHERE m.name LIKE \"".$db->escape($nachname)."\" ";
if ($input) $sql.= " AND i.vorname LIKE \"".$db->escape($input)."%\" ";
$row = $db->query_singlerow($sqlNumAll);
$NumAll = $row["count"];

$sql = "SELECT m.id, m.name, m.vorname, m.arbeitsplatznr, m.extern, m.extern_firma, m.gf, m.bereich, m.abteilung, i.gebaeude, i.etage, i.raumnr, i.raum_typ FROM ".$_TABLE["immobilien"]." i LEFT JOIN ".$_TABLE["mitarbeiter"]." m ON(m.immobilien_raum_id=i.id)
WHERE m.name LIKE \"".$db->escape($nachname)."\" ";
if ($input) $sql.= " AND m.vorname LIKE \"".$db->escape($input)."%\" ";
$sql.= " ORDER BY name, vorname";

$rows = $db->query_rows($sql);
$num = count($rows);
$error = $db->error();
$aRaeume = array();
$JsonMa = "[";
for($i = 0; $i < count($rows); $i++) {
	$e = $rows[$i];
	$abt = ($e["abteilung"]?$e["abteilung"]:($e["bereich"]?$e["bereich"]:($e["gf"]?$e["gf"]:"")));
	$JsonMa.= ($i?",\n":"\n")."\t {\n";
	$JsonMa.= "\t\t\"ma_id\" : \"".json_escape($e["id"])."\",\n";
	$JsonMa.= "\t\t\"ma_n\" : \"".json_escape($e["name"])."\",\n";
	$JsonMa.= "\t\t\"ma_v\" : \"".json_escape($e["vorname"])."\",\n";
	$JsonMa.= "\t\t\"ma_ap\" : \"".json_escape($e["arbeitsplatznr"])."\",\n";
	$JsonMa.= "\t\t\"ma_nu\" : \"".json_escape($e["extern"])."\",\n";
	$JsonMa.= "\t\t\"ma_xf\" : \"".json_escape($e["extern_firma"])."\",\n";
	$JsonMa.= "\t\t\"ma_gf\" : \"".json_escape($e["gf"])."\",\n";
	$JsonMa.= "\t\t\"ma_b\" : \"".json_escape($e["bereich"])."\",\n";
	$JsonMa.= "\t\t\"ma_a\" : \"".json_escape($e["abteilung"])."\",\n";
	$JsonMa.= "\t\t\"ma_g\" : \"".json_escape($e["gebaeude"])."\",\n";
	$JsonMa.= "\t\t\"ma_e\" : \"".json_escape($e["etage"])."\",\n";
	$JsonMa.= "\t\t\"ma_r\" : \"".json_escape($e["raumnr"])."\",\n";
	$JsonMa.= "\t\t\"ma_rt\" : \"".json_escape($e["raum_typ"])."\",\n";
	
	$JsonMa.= "\t\t\"value\" : \"".json_escape($e["name"]." ".$e["vorname"])."\",\n";
	$JsonMa.= "\t\t\"content\" : \"".json_escape($e["name"]." ".$e["vorname"]." &middot;".$e["gebaeude"]." &middot;".$e["etage"]." &middot;".$e["raumnr"]."".($e["arbeitsplatznr"]?"(".$e["arbeitsplatznr"].")":"")." &middot;$abt ".($e["extern_firma"]?" &middot;Fa:".$e["extern_firma"]:""))."\"\n";
	$JsonMa.= "\t}";
}
$JsonMa.= "]";

//echo "<pre>\nvar Raeume = ".$JsonMa.";\n</pre>";

$resultFormat = getRequest("resultFormat", "JS");

switch($resultFormat) {
	case "XML":
	default:
	header("Content-Type: text/xml; charset=ISO-8859-1");
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n";
	echo "<Result type=\"success\">\n";
	echo "<LoadScript language=\"javascript\" src=\"cdata\"><![CDATA[\n";
	echo "/* Err:".$error." */\n";
	echo "/* sql:".$sql." */\n";
	echo "optionsMaByName = {\n";
	echo "\tQuery:\"".json_escape($nachname)."\",\n";
	echo "\tNumAll:$NumAll,\n";
	echo "\tSize:$num,\n";
	echo "\tData:$JsonMa\n";
	echo "};\n";
	echo "mitarbeiter_loadDataByName(\"".$SBBoxId."\", optionsMaByName[\"Data\"]);\n";
	echo "]]></LoadScript>\n";
	echo "</Result>";
	break;
	
	case "JS":
	default:
	header("Content-Type: text/javascript; charset=ISO-8859-1");
	echo "/* Err:".$error." */\n";
	echo "/* sql:".$sql." */\n";
	echo "optionsMaByName = {\n";
	echo "\tQuery:\"".addslashes($nachname)."\",\n";
	echo "\tNumAll:$NumAll,\n";
	echo "\tSize:$num,\n";
	echo "\tData:$JsonMa\n";
	echo "};\n";
	echo "mitarbeiter_loadDataByName('".$SBBoxId."', optionsMaByName[\"Data\"]);\n";
}
?>