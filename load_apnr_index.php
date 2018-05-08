<?php 
require("header.php");
require_once($MConf["AppRoot"].$MConf["Inc_Dir"]."php_json.php");

$gebaeude = getRequest("gebaeude","");
$etage = getRequest("etage","");
$raumnr = getRequest("raumnr","");
$SBBoxId = getRequest("SBBoxId","");

$sql = "SELECT arbeitsplatznr, name, vorname, extern FROM `".$_TABLE["mitarbeiter"]."` \n";
$sql.= " WHERE immobilien_raum_id IN (SELECT id FROM `mm_stamm_immobilien` WHERE gebaeude = \"".$db->escape($gebaeude)."\" AND etage = \"".$db->escape($etage)."\" AND raumnr = \"".$db->escape($raumnr)."\")";
$sql.= " AND arbeitsplatznr != \"0\" AND arbeitsplatznr IS NOT NULL ";
$sql.= " ORDER BY arbeitsplatznr";

$rows = $db->query_rows($sql);
$error = $db->error();
$aRaeume = array();
$JsonData = "[";
for($i = 0; $i < count($rows); $i++) {
	$e = $rows[$i];
	$JsonData.= ($i?",\n":"\n")."\t {\n";
	$JsonData.= "\t\t\"value\" : \"".$e["arbeitsplatznr"]."\",\n";
	$JsonData.= "\t\t\"content\" : \"".$e["arbeitsplatznr"]." ".json_escape($e["name"])."\",\n";
	$JsonData.= "\t\t\"Nutzung\" : \"".json_escape($e["extern"])."\"\n";
	$JsonData.= "\t}";
}
$JsonData.= "]";

//echo "<pre>\nvar Raeume = ".$JsonData.";\n</pre>";

$resultFormat = getRequest("resultFormat", "JS");

switch($resultFormat) {
	case "XML":
	header("Content-Type: text/xml; charset=ISO-8859-1");
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n";
	echo "<Result type=\"success\">\n";
	echo "<LoadScript language=\"javascript\" src=\"cdata\"><![CDATA[\n";
	echo "/* Err:".$error." */\n";
	echo "/* sql:".$sql." */\n";
	echo "optionsApnr = ".$JsonData.";\r\n";
	echo "if (typeof(optionsApnrByGER)!=\"object\") optionsApnrByGER = new Array();\r\n";
	echo "if (typeof(optionsApnrByGER[\"$gebaeude\"])!=\"object\") optionsApnrByGER[\"$gebaeude\"] = new Array();\n";
	echo "if (typeof(optionsApnrByGER[\"$gebaeude\"][\"$etage\"])!=\"object\") optionsApnrByGER[\"$gebaeude\"][\"$etage\"] = new Array();\n";
	echo "if (typeof(optionsApnrByGER[\"$gebaeude\"][\"$etage\"][\"$raumnr\"])!=\"object\") optionsApnrByGER[\"$gebaeude\"][\"$etage\"][\"$raumnr\"] = new Array();\n";
	echo "optionsApnrByGER[\"$gebaeude\"][\"$etage\"][\"$raumnr\"] = getCopyOfArray(optionsApnr)\n";
	echo "SelBox_loadData('".$SBBoxId."', optionsApnrByGER[\"$gebaeude\"][\"$etage\"][\"$raumnr\"]);\n";
	echo "]]></LoadScript>\n";
	echo "</Result>";
	break;
	
	case "JS":
	default:
	header("Content-Type: text/javascript; charset=ISO-8859-1");
	echo "optionsApnr = ".$JsonData.";\n";
}
?>
