<?php 
require("header.php");
require_once($MConf["AppRoot"].$MConf["Inc_Dir"]."php_json.php");

$SBBoxId = getRequest("SBBoxId","");
$orte = getRequest("orte","");
$gebaeude = getRequest("gebaeude","");
$etagen = getRequest("etagen","");
$aPreselectOrte = explode(",", $orte);
$aPreselectGebaeude = explode(",", $gebaeude);
$aPreselectEtagen = explode(",", $etagen);
foreach($aPreselectOrte as $k => $v) $aPreselectOrte[$k] = trim($v);
foreach($aPreselectGebaeude as $k => $v) $aPreselectGebaeude[$k] = trim($v);

$sql = "SELECT raumnr FROM ".$_TABLE["immobilien"]." i\nWHERE 1\n";
if ($etagen) $sql.= "AND etage IN (\"".implode("\",\"", $aPreselectEtagen)."\")\n";
if ($gebaeude) $sql.= "AND gebaeude IN (\"".implode("\",\"", $aPreselectGebaeude)."\")\n";
elseif ($orte) $sql.= "AND gebaeude IN (SELECT gebaeude FROM `".$_TABLE["gebaeude"]."` WHERE stadtname IN (\"".implode("\",\"", $aPreselectOrte)."\"))\n";
$sql.= "GROUP BY raumnr
ORDER BY raumnr";

$rows = $db->query_rows($sql);
//echo $db->error()."\n".$sql;

$JsonData = "[";
for($i = 0; $i < count($rows); $i++) {
	$JsonData.= ($i?",\n":"\n")."\t\"".json_escape($rows[$i]["raumnr"])."\"";
}
$JsonData.= "]";


$resultFormat = getRequest("resultFormat", "JS");

switch($resultFormat) {
	case "XML":
	header("Content-Type: text/xml; charset=UTF-8");
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
	echo "<Result type=\"success\">\n";
	echo "<LoadScript language=\"javascript\" src=\"cdata\"><![CDATA[\n";
	echo "RaumnrIndex = ".$JsonData.";\n";
	echo "if (typeof(SelBox_loadData)==\"function\") SelBox_loadData('".$SBBoxId."', RaumnrIndex);\n";
	echo "]]></LoadScript>\n";
	echo "</Result>";
	break;
	
	case "JS":
	default:
	header("Content-Type: text/javascript; charset=UTF-8");
	echo "RaumnrIndex = ".$JsonData.";\n";
	echo "if (typeof(SelBox_loadData)==\"function\") SelBox_loadData('".$SBBoxId."', RaumnrIndex);\n";
}
