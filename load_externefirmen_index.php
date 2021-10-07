<?php 
require("header.php");

$SBBoxId = getRequest("SBBoxId","");

$sql = "SELECT m.extern_firma FROM ".$_TABLE["mitarbeiter"]." m 
WHERE m.extern IN('Ja','Extern') 
GROUP BY extern_firma
ORDER BY extern_firma";

$rows = $db->query_rows($sql);
$error = $db->error();
$aRaeume = array();
$JsonXF = "[";
for($i = 0; $i < count($rows); $i++) {
	$e = $rows[$i];
	$JsonXF.= ($i?",\n":"\n")."\t {\n";
	$JsonXF.= "\t\t\"value\" : \"".$e["extern_firma"]."\",\n";
	$JsonXF.= "\t\t\"content\" : \"".$e["extern_firma"]."\"\n";
	$JsonXF.= "\t}";
}
$JsonXF.= "]";

//echo "<pre>\nvar Raeume = ".$JsonXF.";\n</pre>";

$resultFormat = getRequest("resultFormat", "JS");

switch($resultFormat) {
	case "XML":
	header("Content-Type: text/xml; charset=ISO-8859-1");
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n";
	echo "<Result type=\"success\">\n";
	echo "<LoadScript language=\"javascript\" src=\"cdata\"><![CDATA[\n";
	echo "/* Err:".$error." */\n";
	echo "/* sql:".$sql." */\n";
	echo "optionsExterneFirmen = ".$JsonXF.";\r\n";
	echo "//alert('$SBBoxId: '+optionsRaeume);\n";
	echo "//m=\"\"; for(i in optionsExterneFirmen) m+=i+\":\"+optionsExterneFirmen[i].value+\"\\n\"; alert(m);\n";
	if ($SBBoxId) echo "SelBox_loadData('".$SBBoxId."', optionsExterneFirmen);\n";
	echo "]]></LoadScript>\n";
	echo "</Result>";
	break;
	
	case "JS":
	default:
	header("Content-Type: text/javascript; charset=ISO-8859-1");
	echo "/* Err:".$error." */\n";
	echo "/* sql:".$sql." */\n";
	echo "optionsExterneFirmen = ".$JsonXF.";\r\n";
	echo "//alert('$SBBoxId: '+optionsRaeume);\n";
	echo "//m=\"\"; for(i in optionsExterneFirmen) m+=i+\":\"+optionsExterneFirmen[i].value+\"\\n\"; alert(m);\n";
	if ($SBBoxId) echo "SelBox_loadData('".$SBBoxId."', optionsExterneFirmen);\n";
}
