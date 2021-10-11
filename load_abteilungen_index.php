<?php 
require("header.php");


$ai = 0;

$aGfByBereich = array();
$sql = "SELECT bereich, organisationseinheit FROM `".$_TABLE["hauptabteilungen"]."`";
$rows = $db->query_rows($sql);
$rowsByBe = array();
foreach($rows as $i => $v) {
	$aGfByBereich[$v["bereich"]] = $v["organisationseinheit"];
}

$sql = "SELECT bereich, abteilung, abteilungsname FROM `".$_TABLE["abteilungen"]."` ORDER BY bereich, abteilung";
$rows = $db->query_rows($sql);
foreach($rows as $v) $rowsByBe[$v["bereich"]][] = $v;

$bi = 0;
$JsonAb = "{";
foreach($rowsByBe as $be => $rows) {
	$JsonAb.= ($bi?",\n":"\n")."\"$be\":[";
	foreach($rows as $i => $v) {
		$a_gf = (!empty($aGfByBereich[$v["bereich"]])) ? $aGfByBereich[$v["bereich"]] : "-";
		$JsonAb.= ($i?",\n":"\n")."{
			Ebene:'A',
			Abteilung:\"".$v["abteilung"]."\",
			Abteilungsname:\"".$v["abteilungsname"]."\",
			value:\"".$v["abteilung"]."\",
			content:\"<strong class='AbtKuerzel'>".$v["abteilung"]."</strong> &nbsp; <em class='AbtName'>".$v["abteilungsname"]."</em>\"
		}";
	}
	$JsonAb.= "]";
	$bi++;
}
$JsonAb.= "}";

$resultFormat = getRequest("resultFormat", "JS");

switch($resultFormat) {
	case "XML":
	header("Content-Type: text/xml; charset=UTF-8");
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
	echo "<Result type=\"success\">\n";
	echo "<LoadScript language=\"javascript\" src=\"cdata\"><![CDATA[\n";
	echo "AbteilungenByBe = ".$JsonAb.";\n";
	//echo 'alert(Orte["D�sseldorf"]["ZV_SEE_1"]+"\n"+Gebaeude["ZV_SEE_1"]["Etagen"].join(", "));'."\n";
	echo "]]></LoadScript>\n";
	echo "</Result>";
	break;
	
	case "JS":
	default:
	header("Content-Type: text/javascript; charset=UTF-8");
	echo "AbteilungenByBe = ".$JsonAb.";\n";
}
