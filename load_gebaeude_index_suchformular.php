<?php 
require("header.php");
require_once($MConf["AppRoot"].$MConf["Inc_Dir"]."php_json.php");

$SBBoxId = getRequest("SBBoxId","");
$orte = getRequest("orte","");
$aPreselect = explode(",", $orte);
foreach($aPreselect as $k => $v) $aPreselect[$k] = trim($v);

$sql = "SELECT g.gebaeude FROM ".$_TABLE["gebaeude"]." g\n";
if ($orte) $sql.= "WHERE stadtname IN (\"".implode("\",\"", $aPreselect)."\")\n";
$sql.= "GROUP BY g.gebaeude
ORDER BY g.gebaeude";

$rows = $db->query_rows($sql);

$JsonData = "[";
for($i = 0; $i < count($rows); $i++) {
	$JsonData.= ($i?",\n":"\n")."\t\"".json_escape($rows[$i]["gebaeude"])."\"";
}
$JsonData.= "]";


$resultFormat = getRequest("resultFormat", "JS");

switch($resultFormat) {
	case "XML":
	header("Content-Type: text/xml; charset=UTF-8);
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
	echo "<Result type=\"success\">\n";
	echo "<LoadScript language=\"javascript\" src=\"cdata\"><![CDATA[\n";
	echo "GebaeudeIndex = ".$JsonData.";\n";
	echo "if (typeof(SelBox_loadData)==\"function\") SelBox_loadData('".$SBBoxId."', GebaeudeIndex);\n";
	echo "]]></LoadScript>\n";
	echo "</Result>";
	break;
	
	case "JS":
	default:
	header("Content-Type: text/javascript; charset=UTF-8");
	echo "GebaeudeIndex = ".$JsonData.";\n";
	echo "if (typeof(SelBox_loadData)==\"function\") SelBox_loadData('".$SBBoxId."', GebaeudeIndex);\n";
}
