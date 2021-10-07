<?php 
require("header.php");
require_once($MConf["AppRoot"].$MConf["Inc_Dir"]."php_json.php");

$SBBoxId = getRequest("SBBoxId","");
$gf = getRequest("gf","");
$bereiche = getRequest("bereiche","");
$aPreselectGF = explode(",", $gf);
$aPreselectBereiche = explode(",", $bereiche);
foreach($aPreselectGF as $k => $v) $aPreselectGF[$k] = trim($v);
foreach($aPreselectBereiche as $k => $v) $aPreselectBereiche[$k] = trim($v);

$sql = "SELECT abteilung FROM ".$_TABLE["abteilungen"]."\nWHERE 1\n";
if ($bereiche) $sql.= "AND bereich IN (\"".implode("\",\"", $aPreselectBereiche)."\")\n";
elseif ($gf) $sql.= "AND bereich IN (SELECT bereich FROM ".$_TABLE["hauptabteilungen"]." WHERE organisationseinheit IN (\"".implode("\",\"", $aPreselectGF)."\"))\n";
$sql.= "GROUP BY abteilung
ORDER BY abteilung";

$rows = $db->query_rows($sql);
//echo $db->error()."\n".$sql;

$JsonData = "[";
for($i = 0; $i < count($rows); $i++) {
	$JsonData.= ($i?",\n":"\n")."\t\"".json_escape($rows[$i]["abteilung"])."\"";
}
$JsonData.= "]";


$resultFormat = getRequest("resultFormat", "JS");

switch($resultFormat) {
	case "XML":
	default:
	header("Content-Type: text/xml; charset=ISO-8859-1");
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n";
	echo "<Result type=\"success\">\n";
	echo "<LoadScript language=\"javascript\" src=\"cdata\"><![CDATA[\n";
	echo "AbteilungIndex = ".$JsonData.";\n";
	echo "if (typeof(SelBox_loadData)==\"function\") SelBox_loadData('".$SBBoxId."', AbteilungIndex);\n";
	echo "]]></LoadScript>\n";
	echo "</Result>";
	break;
	
	case "JS":
	default:
	header("Content-Type: text/javascript; charset=ISO-8859-1");
	echo "AbteilungIndex = ".$JsonData.";\n";
	echo "if (typeof(SelBox_loadData)==\"function\") SelBox_loadData('".$SBBoxId."', AbteilungIndex);\n";
	//echo 'alert(Orte["Düsseldorf"]["ZV_SEE_1"]+"\n"+Gebaeude["ZV_SEE_1"]["Etagen"].join(", "));'."\n";
}
