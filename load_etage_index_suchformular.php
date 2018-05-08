<?php 
require("header.php");
require_once($MConf["AppRoot"].$MConf["Inc_Dir"]."php_json.php");

$SBBoxId = getRequest("SBBoxId","");
$orte = getRequest("orte","");
$gebaeude = getRequest("gebaeude","");
$aPreselectOrte = explode(",", $orte);
$aPreselectGebaeude = explode(",", $gebaeude);
foreach($aPreselectOrte as $k => $v) $aPreselectOrte[$k] = trim($v);
foreach($aPreselectGebaeude as $k => $v) $aPreselectGebaeude[$k] = trim($v);

$sql = "SELECT etage FROM ".$_TABLE["immobilien"]." i\nWHERE 1\n";
if ($gebaeude) $sql.= "AND gebaeude IN (\"".implode("\",\"", $aPreselectGebaeude)."\")\n";
elseif ($orte) $sql.= "AND gebaeude IN (SELECT gebaeude FROM `".$_TABLE["gebaeude"]."` WHERE stadtname IN (\"".implode("\",\"", $aPreselectOrte)."\"))\n";
$sql.= "GROUP BY etage
ORDER BY etage";
//echo $sql;

$rows = $db->query_rows($sql);

$JsonData = "[";
for($i = 0; $i < count($rows); $i++) {
	$JsonData.= ($i?",\n":"\n")."\t\"".json_escape($rows[$i]["etage"])."\"";
}
$JsonData.= "]";


$resultFormat = getRequest("resultFormat", "JS");

switch($resultFormat) {
	case "XML":
	header("Content-Type: text/xml; charset=ISO-8859-1");
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n";
	echo "<Result type=\"success\">\n";
	echo "<LoadScript language=\"javascript\" src=\"cdata\"><![CDATA[\n";
	echo "EtagenIndex = ".$JsonData.";\n";
	echo "if (typeof(SelBox_loadData)==\"function\") SelBox_loadData('".$SBBoxId."', EtagenIndex);\n";
	echo "]]></LoadScript>\n";
	echo "</Result>";
	break;
	
	case "JS":
	default:
	header("Content-Type: text/javascript; charset=ISO-8859-1");
	echo "EtagenIndex = ".$JsonData.";\n";
	echo "if (typeof(SelBox_loadData)==\"function\") SelBox_loadData('".$SBBoxId."', EtagenIndex);\n";
	//echo 'alert(Orte["Düsseldorf"]["ZV_SEE_1"]+"\n"+Gebaeude["ZV_SEE_1"]["Etagen"].join(", "));'."\n";
}
?>
