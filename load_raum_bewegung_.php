<?php 
require("header.php");
require_once($InclBaseDir."php_json.php");
require_once($InclBaseDir."umzugsantrag.inc.php");
require_once($InclBaseDir."umzugsmitarbeiter.inc.php");
require_once($InclBaseDir."..".DS."sites".DS."umzugsantrag_stdlib.php");

$gebaeude = getRequest("gebaeude","");
$etage = getRequest("etage","");
$raumnr = getRequest("raumnr","");
$apnr = getRequest("apnr","");
$SBBoxId = getRequest("SBBoxId","");

$raumdaten = get_raumdaten_byGER($gebaeude, $etage, $raumnr);
$raumid = $raumdaten["id"];
//$raumid = "116";
$rows_ma["Hin"] = get_arbeitsplatz_hinzuege($raumid, $apnr);
$rows_ma["Weg"] = get_arbeitsplatz_wegzuege($raumid, $apnr);
$rows_ma["Fix"] = get_arbeitsplatz_belegung($raumid, $apnr);

$rauminfo = $raumdaten["raumnr"]." ".$raumdaten["raum_typ"]." ".$raumdaten["raum_flaeche"]."qm<br>\n";
$rauminfo.= "MA: ".count($rows_ma["Fix"])." Hin:".count($rows_ma["Hin"])." Weg:".count($rows_ma["Weg"]);

$JsonMa = "[";
$JsonMa.= "\n\t{\n";
$JsonMa.= "\t\t\"value\":\"".json_escape($rauminfo)."\",\n";
$JsonMa.= "\t\t\"content\":\"".json_escape("<b>".$rauminfo."</b>")."\"\n\t}";
$j=0;
foreach($rows_ma as $k => $rows) {
	
	for($i = 0; $i < count($rows); $i++, $j++) {
		$e = $rows[$i];
		$abt = ($e["abteilung"]?$e["abteilung"]:($e["bereich"]?$e["bereich"]:($e["gf"]?$e["gf"]:"")));
		$extra = (($k=="Hin" || $k=="Weg") && !empty($e["umzugsstatus"])) ? $e["umzugsstatus"]." ".format_dbDate($e["umzugsstatus_vom"],"d.m") : "";
		//$JsonMa.= ($j?",\n":"\n")."\t {\n";
		$JsonMa.= ",\n"."\t {\n";
		$JsonMa.= "\t\t\"ma_aid\" : \"".(isset($e["aid"])?$e["aid"]:"")."\",\n";
		$JsonMa.= "\t\t\"ma_id\" : \"".(isset($e["id"])?$e["id"]:"")."\",\n";
		$JsonMa.= "\t\t\"ma_n\" : \"".json_escape($e["name"])."\",\n";
		$JsonMa.= "\t\t\"ma_v\" : \"".json_escape($e["vorname"])."\",\n";
		$JsonMa.= "\t\t\"ma_ap\" : \"".json_escape($e["arbeitsplatznr"])."\",\n";
		$JsonMa.= "\t\t\"ma_xf\" : \"".json_escape($e["extern_firma"])."\",\n";
		$JsonMa.= "\t\t\"ma_a\" : \"".json_escape($e["abteilung"])."\",\n";
		$JsonMa.= "\t\t\"setClassName\" : \"Ma".$k."\",\n";
		
		$JsonMa.= "\t\t\"value\" : \"".$e["name"]." ".$e["vorname"]."\",\n";
		$JsonMa.= "\t\t\"content\" : \"$k: ".$e["name"]." ".$e["vorname"]." ".($e["arbeitsplatznr"]?"(".$e["arbeitsplatznr"].")":"")." $abt ".$e["extern_firma"].$extra."\",\n";
		$JsonMa.= "\t}";
	}
	//echo "j:$j<br>\n";
}
$JsonMa.= "]";

//echo "<pre>\nvar Raeume = ".$JsonMa.";\n</pre>";

$resultFormat = getRequest("resultFormat", "JS");

switch($resultFormat) {
	case "XML":
	default:
	header("Content-Type: text/xml; charset=ISO-8859-1");
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n";
	echo "<Result type=\"success\">\n";
	echo "<LoadScript language=\"javascript\" src=\"cdata\"><![CDATA[\n";
	echo "/* Err:".$error." */\n";
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
	echo "if (typeof(optionsMaByGER)!=\"object\") optionsMaByGER = new Array();\r\n";
	echo "if (typeof(optionsMaByGER[\"$gebaeude\"])!=\"object\") optionsMaByGER[\"$gebaeude\"] = new Array();\n";
	echo "if (typeof(optionsMaByGER[\"$gebaeude\"][\"$etage\"])!=\"object\") optionsMaByGER[\"$gebaeude\"][\"$etage\"] = new Array();\n";
	echo "if (typeof(optionsMaByGER[\"$gebaeude\"][\"$etage\"][\"$raumnr\"])!=\"object\") optionsMaByGER[\"$gebaeude\"][\"$etage\"][\"$raumnr\"] = new Array();\n";
	echo "optionsMaByGER[\"$gebaeude\"][\"$etage\"][\"$raumnr\"] = ".$JsonMa.";\r\n";
	echo "//alert('$SBBoxId: '+optionsRaeume);\n";
	echo "//m=\"\"; for(i in optionsRaeume) m+=i+\":\"+optionsMaByGER[\"$gebaeude\"][\"$etage\"][\"$raumnr\"][i]+\"\\n\"; alert(m);\n";
	echo "SelBox_loadData('".$SBBoxId."', optionsMaByGER[\"$gebaeude\"][\"$etage\"][\"$raumnr\"]);\n";
}
