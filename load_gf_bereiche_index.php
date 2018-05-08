<?php 
require("header.php");

$JsonGF = "";
$JsonBe = "";

$sql = "SELECT organisationseinheit, name FROM `".$_TABLE["gf"]."` ORDER BY organisationseinheit";
$rows = $db->query_rows($sql);

$JsonGF = "[";
foreach($rows as $i => $v) {
	$JsonGF.= ($i?",\n":"\n")."\t{
		Ebene:'GF',
		Abteilung:\"".$v["organisationseinheit"]."\",
		Abteilungsname:\"".$v["name"]."\",
		value:\"".$v["organisationseinheit"]."\",
		content:\"<strong class='AbtKuerzel'>".$v["organisationseinheit"]."</strong> &nbsp; <em class='AbtName'>".$v["name"]."</em>\"
	}";
}
$JsonGF.= "]";

$rowsByGF = array();
$aGfByBereich = array();
$sql = "SELECT bereich, bereichsname, organisationseinheit FROM `".$_TABLE["hauptabteilungen"]."` ORDER BY organisationseinheit, bereich";
$rows = $db->query_rows($sql);
foreach($rows as $i => $v) $rowsByGF[$v["organisationseinheit"]][] = $v;

$gi = 0;
$JsonBe = "{";
foreach($rowsByGF as $gf => $rows) {
	$JsonBe.= ($gi?",\n":"\n")."\"".$gf."\":\t[\n";
	foreach($rows as $i => $v) {
		$JsonBe.= ($i?",\n":"\n")."\t\t{
			Ebene:'B',
			Abteilung:\"".$v["bereich"]."\",
			Abteilungsname:\"".$v["bereichsname"]."\",
			value:\"".$v["bereich"]."\",
			content:\""."<strong class='AbtKuerzel'>".$v["bereich"]."</strong> &nbsp; <em class='AbtName'>".$v["bereichsname"]."</em>\"
		}";
	}
	$JsonBe.= "\t]";
	$gi++;
}
$JsonBe.= "}";

$resultFormat = getRequest("resultFormat", "JS");

switch($resultFormat) {
	case "XML":
	header("Content-Type: text/xml; charset=ISO-8859-1");
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n";
	echo "<Result type=\"success\">\n";
	echo "<LoadScript language=\"javascript\" src=\"cdata\"><![CDATA[\n";
	echo "GF = ".$JsonGF.";\n";
	echo "BereicheByGF = ".$JsonBe.";\n";
	//echo "alert(\"".basename(__FILE__)." GF:\"+GF);\n";
	//echo 'alert(Orte["Düsseldorf"]["ZV_SEE_1"]+"\n"+Gebaeude["ZV_SEE_1"]["Etagen"].join(", "));'."\n";
	echo "]]></LoadScript>\n";
	echo "</Result>";
	break;
	
	case "JS":
	default:
	header("Content-Type: text/javascript; charset=ISO-8859-1");
	echo "GF = ".$JsonGF.";\n";
	echo "BereicheByGF = ".$JsonBe.";\n";
	//echo 'alert(Orte["Düsseldorf"]["ZV_SEE_1"]+"\n"+Gebaeude["ZV_SEE_1"]["Etagen"].join(", "));'."\n";
}
?>
