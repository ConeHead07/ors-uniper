<?php 
require("header.php");
require_once($MConf["AppRoot"].$MConf["Inc_Dir"]."php_json.php");

$SBBoxId = getRequest("SBBoxId","");
$gf = getRequest("gf","");
$aPreselectGF = explode(",", $gf);
foreach($aPreselectGF as $k => $v) $aPreselectGF[$k] = trim($v);

$sql = "SELECT bereich FROM ".$_TABLE["hauptabteilungen"]."\nWHERE 1\n";
if ($gf) $sql.= "AND organisationseinheit IN (\"".implode("\",\"", $aPreselectGF)."\")\n";
$sql.= "GROUP BY bereich
ORDER BY bereich";

$rows = $db->query_rows($sql);
//echo $db->error()."\n".$sql;

$JsonData = "[";
for($i = 0; $i < count($rows); $i++) {
	$JsonData.= ($i?",\n":"\n")."\t\"".json_escape($rows[$i]["bereich"])."\"";
}
$JsonData.= "]";


$resultFormat = getRequest("resultFormat", "JS");

switch($resultFormat) {
	case "XML":
	header("Content-Type: text/xml; charset=UTF-8");
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
	echo "<Result type=\"success\">\n";
	echo "<LoadScript language=\"javascript\" src=\"cdata\"><![CDATA[\n";
	echo "BereichIndex = ".$JsonData.";\n";
	echo "if (typeof(SelBox_loadData)==\"function\") SelBox_loadData('".$SBBoxId."', BereichIndex);\n";
	echo "]]></LoadScript>\n";
	echo "</Result>";
	break;
	
	case "JS":
	default:
	header("Content-Type: text/javascript; charset=UTF-8");
	echo "BereichIndex = ".$JsonData.";\n";
	echo "if (typeof(SelBox_loadData)==\"function\") SelBox_loadData('".$SBBoxId."', BereichIndex);\n";
}
