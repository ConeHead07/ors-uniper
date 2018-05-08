<?php 
require("header_nologin.php");
require_once($MConf["AppRoot"].$MConf["Inc_Dir"]."php_json.php");

$SBBoxId = getRequest("SBBoxId","");

$sql = "SELECT g.stadtname FROM ".$_TABLE["gebaeude"]." g
GROUP BY g.stadtname
ORDER BY g.stadtname";

$rows = $db->query_rows($sql);

$JsonOrte = "[";
for($i = 0; $i < count($rows); $i++) {
	$JsonOrte.= ($i?",\n":"\n")."\t\"".json_escape($rows[$i]["stadtname"])."\"";
}
$JsonOrte.= "]";


$resultFormat = getRequest("resultFormat", "JS");

switch($resultFormat) {
	case "XML":
	header("Content-Type: text/xml; charset=ISO-8859-1");
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n";
	echo "<Result type=\"success\">\n";
	echo "<LoadScript language=\"javascript\" src=\"cdata\"><![CDATA[\n";
	echo "OrteIndex = ".$JsonOrte.";\n";
	echo "if (typeof(SelBox_loadData)==\"function\") SelBox_loadData('".$SBBoxId."', OrteIndex);\n";
	echo "]]></LoadScript>\n";
	echo "</Result>";
	break;
	
	case "JS":
	default:
	header("Content-Type: text/javascript; charset=ISO-8859-1");
	echo "OrteIndex = ".$JsonOrte.";\n";
	echo "if (typeof(SelBox_loadData)==\"function\") SelBox_loadData('".$SBBoxId."', OrteIndex);\n";
	//echo 'alert(Orte["Düsseldorf"]["ZV_SEE_1"]+"\n"+Gebaeude["ZV_SEE_1"]["Etagen"].join(", "));'."\n";
}
?>
