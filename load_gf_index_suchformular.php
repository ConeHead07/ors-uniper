<?php 
require("header.php");
require_once($MConf["AppRoot"].$MConf["Inc_Dir"]."php_json.php");

$SBBoxId = getRequest("SBBoxId","");

$sql = "SELECT organisationseinheit FROM ".$_TABLE["gf"]."
GROUP BY organisationseinheit
ORDER BY organisationseinheit";

$rows = $db->query_rows($sql);
//echo $db->error()."\n".$sql;

$JsonData = "[";
for($i = 0; $i < count($rows); $i++) {
	$JsonData.= ($i?",\n":"\n")."\t\"".json_escape($rows[$i]["organisationseinheit"])."\"";
}
$JsonData.= "]";


$resultFormat = getRequest("resultFormat", "JS");

switch($resultFormat) {
	case "XML":
	header("Content-Type: text/xml; charset=ISO-8859-1");
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n";
	echo "<Result type=\"success\">\n";
	echo "<LoadScript language=\"javascript\" src=\"cdata\"><![CDATA[\n";
	echo "GFIndex = ".$JsonData.";\n";
	echo "if (typeof(SelBox_loadData)==\"function\") SelBox_loadData('".$SBBoxId."', GFIndex);\n";
	echo "]]></LoadScript>\n";
	echo "</Result>";
	break;
	
	case "JS":
	default:
	header("Content-Type: text/javascript; charset=ISO-8859-1");
	echo "GFIndex = ".$JsonData.";\n";
	echo "if (typeof(SelBox_loadData)==\"function\") SelBox_loadData('".$SBBoxId."', GFIndex);\n";
	//echo 'alert(Orte["Düsseldorf"]["ZV_SEE_1"]+"\n"+Gebaeude["ZV_SEE_1"]["Etagen"].join(", "));'."\n";
}
