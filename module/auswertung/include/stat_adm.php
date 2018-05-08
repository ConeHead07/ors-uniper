<?php 

$GroupField = "ADM";
$wz = "";
$last_adm = "";
$aSumByGF = array();

$SQL = "SELECT 
`pid`,
`Vorgangsnr`,
`Projekt`,
`Status`,
`Eingangsdatum`,
`Sollstunden`,
`Auftragsvolumen`,
`P`.`Mitarbeiter` AS Projekt_Mitarbeiter,
`ADM`,
`Kunde`,
SUM(`Dauer`) AS Projekt_Ist_Stunden
FROM `".$_TABLE["projects"]."` AS `P`";
$SQL.= " LEFT JOIN `".$_TABLE["p_entries"]."` AS `Z` USING(pid)\n";
if ($sWhere) $SQL.= "WHERE ".$sWhere."\n";

$SQL.= "
GROUP BY `pid`
ORDER BY `$GroupField`";
$r = MyDB::query($SQL, $connid);
if ($r) {
	$n = MyDB::num_rows($r);
	for ($i = 0; $i < $n; $i++) {
		$_e = MyDB::fetch_array($r, MYSQL_ASSOC);
		$gf = $_e[$GroupField];
		
		$stat= $_e["Status"];
		
		// GESAMT
		if (isset($aSumByGF[$gf]["Gesamt"]["Projekte"])) $aSumByGF[$gf]["Gesamt"]["Projekte"]++;
		else $aSumByGF[$gf]["Gesamt"]["Projekte"] = 1;
		
		if (isset($aSumByGF[$gf]["Gesamt"]["Auftragsvolumen"])) $aSumByGF[$gf]["Gesamt"]["Auftragsvolumen"]+= $_e["Auftragsvolumen"];
		else $aSumByGF[$gf]["Gesamt"]["Auftragsvolumen"] = $_e["Auftragsvolumen"];
		
		if (isset($aSumByGF[$gf]["Gesamt"]["Sollstunden"])) $aSumByGF[$gf]["Gesamt"]["Sollstunden"]+= $_e["Sollstunden"];
		else $aSumByGF[$gf]["Gesamt"]["Sollstunden"] = $_e["Sollstunden"];
		
		if (isset($aSumByGF[$gf]["Gesamt"]["Projekt_Ist_Stunden"])) $aSumByGF[$gf]["Gesamt"]["Projekt_Ist_Stunden"]+= $_e["Projekt_Ist_Stunden"];
		else $aSumByGF[$gf]["Gesamt"]["Projekt_Ist_Stunden"] = $_e["Projekt_Ist_Stunden"];
		
		// NACH STATUS
		if (isset($aSumByGF[$gf][$stat]["Projekte"])) $aSumByGF[$gf][$stat]["Projekte"]++;
		else $aSumByGF[$gf][$stat]["Projekte"] = 1;
		
		if (isset($aSumByGF[$gf][$stat]["Auftragsvolumen"])) $aSumByGF[$gf][$stat]["Auftragsvolumen"]+= $_e["Auftragsvolumen"];
		else $aSumByGF[$gf][$stat]["Auftragsvolumen"] = $_e["Auftragsvolumen"];
		
		if (isset($aSumByGF[$gf][$stat]["Sollstunden"])) $aSumByGF[$gf][$stat]["Sollstunden"]+= $_e["Sollstunden"];
		else $aSumByGF[$gf][$stat]["Sollstunden"] = $_e["Sollstunden"];
		
		if (isset($aSumByGF[$gf][$stat]["Projekt_Ist_Stunden"])) $aSumByGF[$gf][$stat]["Projekt_Ist_Stunden"]+= $_e["Projekt_Ist_Stunden"];
		else $aSumByGF[$gf][$stat]["Projekt_Ist_Stunden"] = $_e["Projekt_Ist_Stunden"];
		
		// GEWONNEN / VERLOREN
		switch($stat) {
			case "Angebot": break;
			case "Beauftragt":
			case "Abgeschlossen":
			case "Verloren":
		}
		
		// LISTE PROJEKTE
		
		if (!isset($aSumByGF[$gf]["Liste"])) $aSumByGF[$gf]["Liste"] = "";
		$aSumByGF[$gf]["Liste"].= "
		<tr class=\"wz1 ".$_e["Status"]."\">
		<td>".$_e["Status"]."</td>
		<td><strong>".$_e["Kunde"]."</strong></td>
		<td>".$_e["Projekt"]."</td>
		<td>".($_e["Auftragsvolumen"]?$_e["Auftragsvolumen"]:"-,--")."€</td>
		</tr>\n";
		
	}
	MyDB::free_result($r);
} else {
	echo MyDB::error()."<br>\n";
	echo "<pre>".fb_htmlEntities($SQL)."</pre>\n";
	
}


foreach($aSumByGF as $r_adm => $aStats) {
	
	$body_content.= "<div class=\"listItemBox\">\n";
	$body_content.= "<h3 style=\"color:#0000ff;font-size:13px;\"><a name=\"{$r_adm}\">$r_adm</a></h3>\n<h3>&Uuml;bersicht</h4>\n";
	$body_content.= "<table class=\"tblList\" border=1>\n";
	$rowLbl = "<td>Status</td>";
	$rowSum = "<td>Gesamt</td>";
	foreach($aStats["Gesamt"] as $r_lbl => $r_sum) {
		$rowLbl.= "<td>".$r_lbl."</td>";
		$rowSum.= "<td class=\"int sum\">".$r_sum."</td>";
		$aSumByGF[$gf]["Gewonnen"][$r_lbl] = 0;
		$aSumByGF[$gf]["Verloren"][$r_lbl] = 0;
	}
	$body_content.= "\t<thead><tr>".$rowLbl."</tr></thead>\n";
	$body_content.= "\t<tbody>\n";
	foreach($aStats as $r_stat => $aV) {
		if ($r_stat == "Liste" || $r_stat == "Gesamt") continue;
		$rowStat = "<td>".$r_stat."</td>\n";
		
		foreach($aStats["Gesamt"] as $r_lbl => $r_tmp) {
			$rowStat.= "<td class=\"int\">".$aV[$r_lbl]."</td>";
			switch($r_stat) {
				case "Beauftragt":
				case "Abgeschlossen":
				$aSumByGF[$gf]["Gewonnen"][$r_lbl]+= $aV[$r_lbl];
				break;
				
				case "Verloren":
				$aSumByGF[$gf]["Verloren"][$r_lbl]+= $aV[$r_lbl];
				
			}
		}
		
		$body_content.= "\t<tr class=\"wz1 $r_stat\">".$rowStat."</tr>\n";
		
	}
	$body_content.= "\t<tr class=\"wz1\">".$rowSum."</tr>\n";
	$body_content.= "\t</tbody>\n";
	$body_content.= "</table>\n";
	$body_content.= "<h3>Projekte</h3>
	<table class=\"tblList tblProjekte\">\n<thead>\n<tr>
			<td class=\"colstat\">Status</td><td class=\"colkunde\">Kunde</td><td class=\"colproj\">Projekt</td><td class=\"colasum\">A.-Summe</td>
		</tr>\n</thead>
		<tbody>\n".$aStats["Liste"]."</tbody></table>\n";
	$body_content.= "</div>";
}
?>