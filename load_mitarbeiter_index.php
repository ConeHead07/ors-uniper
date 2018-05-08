<?php 
require("header.php");
require_once($InclBaseDir."umzugsantrag.inc.php");
require_once($InclBaseDir."umzugsmitarbeiter.inc.php");
require_once($InclBaseDir."..".DS."sites".DS."umzugsantrag_stdlib.php");

$gebaeude = getRequest("gebaeude","");
$etage = getRequest("etage","");
$raumnr = getRequest("raumnr","");
$SBBoxId = getRequest("SBBoxId","");

$sql = "SELECT m.id, m.name, m.vorname, m.arbeitsplatznr, m.extern, m.extern_firma, m.gf, m.bereich, m.abteilung, i.gebaeude, i.etage, i.raumnr, i.raum_typ FROM ".$_TABLE["immobilien"]." i LEFT JOIN ".$_TABLE["mitarbeiter"]." m ON(m.immobilien_raum_id=i.id)
WHERE i.gebaeude = \"".$db->escape($gebaeude)."\" ";
if ($etage) $sql.= " AND i.etage LIKE \"".$db->escape($etage)."\" ";
if ($raumnr) $sql.= " AND i.raumnr LIKE \"".$db->escape($raumnr)."\" \n";
$sql.= " ORDER BY name, vorname";

$rows = $db->query_rows($sql);
$error = $db->error();
$aRaeume = array();
$JsonMa = "[";
for($i = 0; $i < count($rows); $i++) {
	$e = $rows[$i];
	$abt = ($e["abteilung"]?$e["abteilung"]:($e["bereich"]?$e["bereich"]:($e["gf"]?$e["gf"]:"")));
	$JsonMa.= ($i?",\n":"\n")."\t {\n";
	$JsonMa.= "\t\t\"ma_id\" : \"".$e["id"]."\",\n";
	$JsonMa.= "\t\t\"ma_n\" : \"".$e["name"]."\",\n";
	$JsonMa.= "\t\t\"ma_v\" : \"".$e["vorname"]."\",\n";
	$JsonMa.= "\t\t\"ma_ap\" : \"".$e["arbeitsplatznr"]."\",\n";
	$JsonMa.= "\t\t\"ma_nu\" : \"".$e["extern"]."\",\n";
	$JsonMa.= "\t\t\"ma_xf\" : \"".$e["extern_firma"]."\",\n";
	$JsonMa.= "\t\t\"ma_gf\" : \"".$e["gf"]."\",\n";
	$JsonMa.= "\t\t\"ma_b\" : \"".$e["bereich"]."\",\n";
	$JsonMa.= "\t\t\"ma_a\" : \"".$e["abteilung"]."\",\n";
	$JsonMa.= "\t\t\"ma_gb\" : \"".$e["gebaeude"]."\",\n";
	$JsonMa.= "\t\t\"ma_e\" : \"".$e["etage"]."\",\n";
	$JsonMa.= "\t\t\"ma_r\" : \"".$e["raumnr"]."\",\n";
	$JsonMa.= "\t\t\"ma_rt\" : \"".$e["raum_typ"]."\",\n";
	
	
	$JsonMa.= "\t\t\"value\" : \"".$e["name"]." ".$e["vorname"]."\",\n";
	$JsonMa.= "\t\t\"content\" : \"".$e["name"]." ".$e["vorname"]." ".($e["arbeitsplatznr"]?"(".$e["arbeitsplatznr"].")":"")." $abt ".$e["extern_firma"]."\",\n";
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
	echo "if (typeof(optionsMaByGER)!=\"object\") optionsMaByGER = new Array();\r\n";
	echo "if (typeof(optionsMaByGER[\"$gebaeude\"])!=\"object\") optionsMaByGER[\"$gebaeude\"] = new Array();\n";
	echo "if (typeof(optionsMaByGER[\"$gebaeude\"][\"$etage\"])!=\"object\") optionsMaByGER[\"$gebaeude\"][\"$etage\"] = new Array();\n";
	echo "if (typeof(optionsMaByGER[\"$gebaeude\"][\"$etage\"][\"$raumnr\"])!=\"object\") optionsMaByGER[\"$gebaeude\"][\"$etage\"][\"$raumnr\"] = new Array();\n";
	echo "optionsMaByGER[\"$gebaeude\"][\"$etage\"][\"$raumnr\"] = ".$JsonMa.";\r\n";
	echo "//alert('$SBBoxId: '+optionsRaeume);\n";
	echo "//m=\"\"; for(i in optionsRaeume) m+=i+\":\"+optionsMaByGER[\"$gebaeude\"][\"$etage\"][\"$raumnr\"][i]+\"\\n\"; alert(m);\n";
	echo "SelBox_loadData('".$SBBoxId."', optionsMaByGER[\"$gebaeude\"][\"$etage\"][\"$raumnr\"]);\n";
	echo "]]></LoadScript>\n";
	echo "</Result>";
	break;
	
	case "JS":
	default:
	header("Content-Type: text/javascript; charset=ISO-8859-1");
	echo "/* Err:".$error." */\n";
	echo "/* sql:".$sql." */\n";
	echo "if (typeof(optionsMaByGER)!=\"object\") optionsMaByGER = new Array();\r\n";
	echo "if (typeof(optionsMaByGER[\"$gebaeude\"])!=\"object\") optionsMaByGER[\"$gebaeude\"] = new Array();\n";
	echo "if (typeof(optionsMaByGER[\"$gebaeude\"][\"$etage\"])!=\"object\") optionsMaByGER[\"$gebaeude\"][\"$etage\"] = new Array();\n";
	echo "if (typeof(optionsMaByGER[\"$gebaeude\"][\"$etage\"][\"$raumnr\"])!=\"object\") optionsMaByGER[\"$gebaeude\"][\"$etage\"][\"$raumnr\"] = new Array();\n";
	echo "optionsMaByGER[\"$gebaeude\"][\"$etage\"][\"$raumnr\"] = ".$JsonMa.";\r\n";
	echo "//alert('$SBBoxId: '+optionsRaeume);\n";
	echo "//m=\"\"; for(i in optionsRaeume) m+=i+\":\"+optionsMaByGER[\"$gebaeude\"][\"$etage\"][\"$raumnr\"][i]+\"\\n\"; alert(m);\n";
	echo "SelBox_loadData('".$SBBoxId."', optionsMaByGER[\"$gebaeude\"][\"$etage\"][\"$raumnr\"]);\n";
}
?>
