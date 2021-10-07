<?php 
require("header.php");

$input = getRequest("input","");
$limit = min((int)getRequest("limit","100"),100);
$SBBoxId = getRequest("SBBoxId","");

$sqlNumAll = "SELECT COUNT(DISTINCT(UPPER(name))) count FROM ".$_TABLE["mitarbeiter"]." 
WHERE name LIKE \"".strtoupper($db->escape($input))."%\"";
$row = $db->query_singlerow($sqlNumAll);
$NumAll = $row["count"];

$sql = "SELECT DISTINCT(UPPER(name)) name FROM ".$_TABLE["mitarbeiter"]." 
WHERE name LIKE \"".strtoupper($db->escape($input))."%\"
GROUP BY name
ORDER BY name
LIMIT 0, $limit";

$rows = $db->query_rows($sql);
$num = count($rows);
$error = $db->error();
$JsonMa = "[";
for($i = 0; $i < count($rows); $i++) {
	$e = $rows[$i];
	$JsonMa.= ($i?",\n":"\n")."\t{\n";
	$JsonMa.= "\t\t\"value\" : \"".$e["name"]."\",\n";
	$JsonMa.= "\t\t\"content\" : \"".$e["name"]."\"\n";
	$JsonMa.= "\t}";
}
$JsonMa.= "]";

//echo "<pre>\nvar Raeume = ".$JsonMa.";\n</pre>";

$resultFormat = getRequest("resultFormat", "JS");

switch($resultFormat) {
	case "XML":
	header("Content-Type: text/xml; charset=ISO-8859-1");
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n";
	echo "<Result type=\"success\">\n";
	echo "<LoadScript language=\"javascript\" src=\"cdata\"><![CDATA[\n";
	echo "/* Err:".$error." */\n";
	echo "/* sql:".$sql." */\n";
	echo "optionsNachnamen = {\n";
	echo "\tQuery:\"".addslashes($input)."\",\n";
	echo "\tNumAll:$NumAll,\n";
	echo "\tSize:$num,\n";
	echo "\tData:$JsonMa\n";
	echo "};\n";
	echo "SelBox_loadData('".$SBBoxId."', optionsNachnamen[\"Data\"]);\n";
	echo "]]></LoadScript>\n";
	echo "</Result>";
	break;
	
	case "JS":
	default:
	header("Content-Type: text/javascript; charset=ISO-8859-1");
	echo "/* Err:".$error." */\n";
	echo "/* sql:".$sql." */\n";
	echo "optionsNachnamen = {\n";
	echo "\tQuery:\"".addslashes($input)."\",\n";
	echo "\tNumAll:$NumAll,\n";
	echo "\tSize:$num,\n";
	echo "\tData:$JsonMa\n";
	echo "};\n";
	echo "SelBox_loadData('".$SBBoxId."', optionsNachnamen[\"Data\"]);\n";
}
