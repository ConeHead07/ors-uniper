<?php 
require("header.php");
require_once($MConf["AppRoot"].$MConf["Inc_Dir"]."php_json.php");

$SBBoxId = getRequest("SBBoxId","");

$sql = "SELECT raumkategorie, beschreibung FROM ".$_TABLE["raumkategorien"]." 
ORDER BY raumkategorie";

$rows = $db->query_rows($sql);

$JsonData = "[";
for($i = 0; $i < count($rows); $i++) {
	$JsonData.= ($i?",\n":"\n")."\t{value:\"".json_escape($rows[$i]["raumkategorie"])."\",content:\"".json_escape($rows[$i]["raumkategorie"]." ".$rows[$i]["beschreibung"])."\"}";
}
$JsonData.= "]";
//echo $JsonData;


$resultFormat = getRequest("resultFormat", "JS");

switch($resultFormat) {
	case "XML":
	header("Content-Type: text/xml; charset=UTF-8");
	echo "<?xml version=\"1.0\" encoding=\"IUTF-8\" ?>\n";
	echo "<Result type=\"success\">\n";
	echo "<LoadScript language=\"javascript\" src=\"cdata\"><![CDATA[\n";
	echo "KtgIndex = ".$JsonData.";\n";
	echo "if (typeof(SelBox_loadData)==\"function\") SelBox_loadData('".$SBBoxId."', KtgIndex);\n";
	echo "]]></LoadScript>\n";
	echo "</Result>";
	break;
	
	case "JS":
	default:
	header("Content-Type: text/javascript; charset=UTF-8");
	echo "OrteIndex = ".$JsonOrte.";\n";
	echo "if (typeof(SelBox_loadData)==\"function\") SelBox_loadData('".$SBBoxId."', OrteIndex);\n";
}
