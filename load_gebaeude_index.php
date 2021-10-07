<?php 
require("header.php");
$sqlMitRaeumen = 0;

$sql = "SELECT g.id, g.stadtname, gebaeudename, gebaeude, g.adresse, etagenliste, '' etage FROM ".$_TABLE["gebaeude"]." g";
if ($sqlMitRaeumen) $sql.= "
LEFT JOIN ".$_TABLE["immobilien"]." r USING(gebaeude)
WHERE raum_typ IN ('BUE','GBUE')
GROUP BY g.gebaeude, r.etage";
$sql.= "
ORDER BY g.stadtname, g.adresse";
if ($sqlMitRaeumen) $sql.= ", r.etage";

$rows = $db->query_rows($sql);
//echo '<pre>' . print_r($rows,1).'</pre>' . PHP_EOL;
//exit;
$aStrukturArray = array();
$aOrte = array();
$aGebaeude = array();
for($i = 0; $i < count($rows); $i++) {
	$e = $rows[$i];
        $id = $e['id'];
	$a = $e["adresse"];
	$s = $e["stadtname"];
	$g = trim($e["gebaeudename"]);
	if (!isset($aOrte[$s]) || !isset($aOrte[$s][$id])) $aOrte[$s][$id] = $a . ', ' . $s;
//        $aOrte[$s][$a] = $a;
	if (!isset($aGebaeude[$id])) {
            $aGebaeude[$id]["Adresse"] = ($g ? "$g, " : '') . $a . ', ' . $s;
            $aGebaeude[$id]["Etagenliste"] = $e['etagenliste'];
        }
//        $aGebaeude[$g]["Adresse"] = $a;
	$aGebaeude[$id]["Etagen"][] = $e["etage"];
}

$i=0;
$JsonOrte = "{";
foreach($aOrte as $stadt => $v) {
	$JsonOrte.= ($i?",\n":"\n")."\t\"$stadt\" : {";
	$j=0;
	foreach($v as $geb => $adr) { $JsonOrte.= ($j?",\n":"\n")."\t\t\"$geb\":\"$adr\""; $j++; }
	$JsonOrte.= "\n\t}";
	$i++;
}
$JsonOrte.= "}";

$i=0;
$JsonGebaeude = "{";
foreach($aGebaeude as $geb => $v) {
	$JsonGebaeude.= ($i?",\n":"\n")."\t\"$geb\" : {\n";
	$JsonGebaeude.= "\t\t\"Adresse\" : \"".$v["Adresse"]."\",\n";
	$JsonGebaeude.= "\t\t\"Etagen\" : [\"".implode("\",\"", $v["Etagen"])."\"],\n";
	$JsonGebaeude.= "\t\t\"Etagenliste\" : [\"".implode("\",\"", explode(',',$v["Etagenliste"]))."\"]\n";
	$JsonGebaeude.= "\t}";
	$i++;
}
$JsonGebaeude.= "}";

//echo "<pre>\nvar Orte = ".$JsonOrte.";\n</pre>";
//echo "<pre>\nvar Gebaeude = ".$JsonGebaeude.";\n</pre>";
//echo "<pre>\n \$aOrte = ".print_r($aOrte,1)."</pre>\n";
//echo "<pre>\n \$aGebaeude = ".print_r($aGebaeude,1)."</pre>\n";

$resultFormat = getRequest("resultFormat", "JS");

switch($resultFormat) {
	case "XML":
	header("Content-Type: text/xml; charset=ISO-8859-1");
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n";
	echo "<Result type=\"success\">\n";
	echo "<LoadScript language=\"javascript\" src=\"cdata\"><![CDATA[\n";
	echo "Orte = ".$JsonOrte.";\n";
	echo "Gebaeude = ".$JsonGebaeude.";\n";
	//echo 'alert(Orte["Düsseldorf"]["ZV_SEE_1"]+"\n"+Gebaeude["ZV_SEE_1"]["Etagen"].join(", "));'."\n";
	echo "]]></LoadScript>\n";
	echo "</Result>";
	break;
	
	case "JS":
	default:
	header("Content-Type: text/javascript; charset=ISO-8859-1");
	echo "Orte = ".$JsonOrte.";\n";
	echo "Gebaeude = ".$JsonGebaeude.";\n";
	//echo 'alert(Orte["Düsseldorf"]["ZV_SEE_1"]+"\n"+Gebaeude["ZV_SEE_1"]["Etagen"].join(", "));'."\n";
}
