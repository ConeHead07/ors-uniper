<?php 
require("../header.php");

$statMonat = getRequest("monat", "2010-10");
$statGebaeude = getRequest("gebaeude", "%");

$sql = "SELECT id, gebaeude, name, datum, uhrzeit, aufgabe , sonderleistung, zeit, sonderleistung_preis `SL-Kosten`, `zeit`, (time_to_sec( `zeit` ) /3600 *21) `NL-Kosten`\n";
$sql.= "FROM `".$_TABLE["nebenleistungen"]."` \n";
$sql.= "WHERE status = \"Abgeschlossen\" AND DATE_FORMAT(datum, \"%Y-%m\") LIKE \"".$db->escape($statMonat)."\"\n";
$sql.= " AND `gebaeude` LIKE \"".$db->escape($statGebaeude)."\"\n";
$sql.= "ORDER BY datum";
$rows = $db->query_rows($sql);

$SUM["SL-Kosten"] = 0;
$SUM["NL-Kosten"] = 0;
$SUM["Gesamt-Kosten"] = 0;
foreach($rows as $i => $v) {
	$rows[$i]["SL-Kosten"] = round(floatval($v["SL-Kosten"]),2);
	$rows[$i]["NL-Kosten"] = round(floatval($v["NL-Kosten"]),2);
	$rows[$i]["Gest-Kosten"] = $rows[$i]["SL-Kosten"]+$rows[$i]["NL-Kosten"];
	
	$SUM["SL-Kosten"]+= $rows[$i]["SL-Kosten"];
	$SUM["NL-Kosten"]+= $rows[$i]["NL-Kosten"];
	$SUM["Gesamt-Kosten"]+= $rows[$i]["Gest-Kosten"];
}
echo '<html><head>
<link rel="STYLESHEET" type="text/css" href="'.$MConf["WebRoot"].'themes/' . $MConf['theme'] . '/css/cms.css">
<link rel="STYLESHEET" type="text/css" href="".$MConf["WebRoot"]."css/tablelisting.css">
</head><body>
<div><a href="javascript:self.close()" style="font-size:11px;font-family:Arial,sans-serif;color:#ff3300;text-decoration:none;">Schliessen [x]</a><br>\n</div>
<h1>Detailansicht Nebenleistungen zum Geb&auml;de $statGebaeude im Monat $statMonat</h1>
';
echo "<div style=\"font-size:12px;font-family:Arial;\">";
foreach($SUM as $k => $v) echo "<strong>$k:</strong> ".number_format($v, 2, ",", ".")."&euro; &nbsp; ";
echo "</div>\n";
echo "<table class=\"tblList\">\n";
echo "<thead><tr><td>".implode("</td><td>", array_keys($rows[0]))."</td></tr></thead>\n";
echo "<tbody>\n";
for($i = 0; $i < count($rows); $i++) {
	echo "<tr class=\"wz".($i%2==0?1:2)."\">\n";
	foreach($rows[$i] as $k => $v) {
		switch($k) {
			case "SL-Kosten":
			case "NL-Kosten":
			echo "<td style=\"text-align:right;\">".number_format($v, 2, ",", ".")."</td>\n";
			break;
			
			case "Gest-Kosten":
			echo "<td style=\"text-align:right;font-weight:bold;\">".number_format($v, 2, ",", ".")."</td>\n";
			break;
			
			case "zeit":
			echo "<td>".implode(":", array_slice(explode(":",$v),0,2))."</td>\n";
			break;
			
			case "aufgabe":
			case "sonderleistung":
			echo "<td ".(strlen($v)>50?" title=\"".fb_htmlEntities($v)."\"":"").">".fb_htmlEntities(substr($v, 0, 50))."</td>\n";
			break;
			
			default:
			echo "<td>".fb_htmlEntities($v)."</td>\n";
		}
	}
}
echo "</tbody>\n</table>\n";
echo "</body></html>\n";
?>