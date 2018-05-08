<?php 
require("header.php");

$gebaeude = getRequest("gebaeude","");
$etage = getRequest("etage","");
$SBBoxId = getRequest("SBBoxId","");

$sql = "SELECT gebaeude, etage, raumnr, raum_typ FROM ".$_TABLE["immobilien"]."
WHERE gebaeude = \"".$db->escape($gebaeude)."\" ";
if ($etage) $sql.= " AND etage LIKE \"".$db->escape($etage)."\" ";
$sql.= " AND raum_typ IN ('BUE','GBUE','CAL') \n";
$sql.= " ORDER BY etage, raumnr";

$rows = $db->query_rows($sql);
$error = $db->error();
$aRaeume = array();
$JsonRaeume = "[";
for($i = 0; $i < count($rows); $i++) {
	$e = $rows[$i];
	$JsonRaeume.= ($i?",\n":"\n")."\t {\n";
	$JsonRaeume.= "\t\t\"value\" : \"".$e["raumnr"]."\",\n";
	$JsonRaeume.= "\t\t\"content\" : \"".$e["raumnr"]." ".$e["raum_typ"]."\",\n";
	$JsonRaeume.= "\t\t\"RaumTyp\" : \"".$e["raum_typ"]."\"\n";
	$JsonRaeume.= "\t}";
}
$JsonRaeume.= "]";

//echo "<pre>\nvar Raeume = ".$JsonRaeume.";\n</pre>";

$resultFormat = getRequest("resultFormat", "JS");

switch($resultFormat) {
	case "XML":
	header("Content-Type: text/xml; charset=ISO-8859-1");
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n";
	echo "<Result type=\"success\">\n";
	echo "<LoadScript language=\"javascript\" src=\"cdata\"><![CDATA[\n";
	echo "/* Err:".$error." */\n";
	echo "/* sql:".$sql." */\n";
	echo "optionsRaeume = ".$JsonRaeume.";\r\n";
	echo "if (typeof(optionsRaeumeByGebEtg)!=\"object\") optionsRaeumeByGebEtg = new Array();\r\n";
	echo "if (typeof(optionsRaeumeByGebEtg[\"$gebaeude\"])!=\"object\") optionsRaeumeByGebEtg[\"$gebaeude\"] = new Array();\n";
	echo "if (typeof(optionsRaeumeByGebEtg[\"$gebaeude\"][\"$etage\"])!=\"object\") optionsRaeumeByGebEtg[\"$gebaeude\"][\"$etage\"] = new Array();\n";
	echo "optionsRaeumeByGebEtg[\"$gebaeude\"][\"$etage\"] = getCopyOfArray(optionsRaeume)\n";
	echo "//alert('$SBBoxId: '+optionsRaeume);\n";
	echo "//m=\"\"; for(i in optionsRaeume) m+=i+\":\"+optionsRaeume[i]+\"\\n\"; alert(m);\n";
	echo "SelBox_loadData('".$SBBoxId."', optionsRaeume);\n";
	echo "]]></LoadScript>\n";
	echo "</Result>";
	break;
	
	case "JS":
	default:
	header("Content-Type: text/javascript; charset=ISO-8859-1");
	echo "Raeume = ".$JsonRaeume.";\n";
}
?>
