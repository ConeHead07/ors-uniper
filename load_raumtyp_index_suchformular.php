<?php 
require("header.php");
require_once($MConf["AppRoot"].$MConf["Inc_Dir"]."php_json.php");

$SBBoxId = getRequest("SBBoxId","");
$ktg = getRequest("ktg","");
$aPreselectKtg = explode(",", $ktg);
foreach($aPreselectKtg as $k => $v) $aPreselectKtg[$k] = trim($v);

$sql = "SELECT raumtyp, beschreibung FROM ".$_TABLE["raumtypen"]." i\nWHERE 1\n";
if ($ktg) $sql.= "AND raumkategorie IN (\"".implode("\",\"", $aPreselectKtg)."\")\n";
$sql.= "ORDER BY raumtyp";

$rows = $db->query_rows($sql);
//echo $db->error()."\n".$sql;

$JsonData = "[";
for($i = 0; $i < count($rows); $i++) {
	$JsonData.= ($i?",\n":"\n")."\t{value:\"".json_escape($rows[$i]["raumtyp"])."\",content:\"".json_escape($rows[$i]["raumtyp"]." ".$rows[$i]["beschreibung"])."\"}";
}
$JsonData.= "]";


$resultFormat = getRequest("resultFormat", "JS");

switch($resultFormat) {
	case "XML":
	header("Content-Type: text/xml; charset=ISO-8859-1");
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n";
	echo "<Result type=\"success\">\n";
	echo "<LoadScript language=\"javascript\" src=\"cdata\"><![CDATA[\n";
	echo "RaumtypIndex = ".$JsonData.";\n";
	echo "if (typeof(SelBox_loadData)==\"function\") SelBox_loadData('".$SBBoxId."', RaumtypIndex);\n";
	echo "]]></LoadScript>\n";
	echo "</Result>";
	break;
	
	case "JS":
	default:
	header("Content-Type: text/javascript; charset=ISO-8859-1");
	echo "RaumnrIndex = ".$JsonData.";\n";
	echo "if (typeof(SelBox_loadData)==\"function\") SelBox_loadData('".$SBBoxId."', RaumnrIndex);\n";
	//echo 'alert(Orte["Düsseldorf"]["ZV_SEE_1"]+"\n"+Gebaeude["ZV_SEE_1"]["Etagen"].join(", "));'."\n";
}
?>
