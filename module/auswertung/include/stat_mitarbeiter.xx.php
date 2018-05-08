<?php 

$last_adm = "";
$aSumByADM = array();

$SQL = "SELECT 
`pid`,
`Vorgangsnr`,
`Projekt`,
`Status`,
`Eingangsdatum`,
`Sollstunden`,
`Auftragsvolumen`,
`".$_TABLE["projects"]."`.`Mitarbeiter` AS Projekt_Mitarbeiter,
`ADM`,
`Kunde`,
SUM(`Dauer`) AS Projekt_Ist_Stunden
FROM `".$_TABLE["projects"]."`";
$SQL.= " LEFT JOIN `".$_TABLE["p_entries"]."` USING(pid)
GROUP BY `".$_TABLE["projects"]."`.`pid`
ORDER BY `".$_TABLE["projects"]."`.`ADM`";
$r = MyDB::query($SQL, $connid);
if ($r) {
	$n = MyDB::num_rows($r);
	for ($i = 0; $i < $n; $i++) {
		$_e = MyDB::fetch_array($r, MYSQL_ASSOC);
		$adm = $_e["pid"];
		$stat= $_e["Status"];
		
		if (!isset($aSumByADM[$adm]["Liste"])) $aSumByADM[$adm]["Liste"] = "";
		
		$aSumByADM[$adm]["Liste"].= "<div>
		".$_e["Kunde"]."<br>
		".$_e["Projekt"]."<br>
		<span class=\"".$_e["Status"]."\" >".$_e["Status"].": ".$_e["Auftragsvolumen"]."</span><br>
		Eingang: ".$_e["Eingangsdatum"]."
		</div>\n";
		
		if (isset($aSumByADM[$adm]["Gesamt"]["Anzahl_Projekte"])) $aSumByADM[$adm]["Gesamt"]["Anzahl_Projekte"]++;
		else $aSumByADM[$adm]["Gesamt"]["Anzahl_Projekte"] = 0;
		
		if (isset($aSumByADM[$adm]["Gesamt"]["Auftragsvolumen"])) $aSumByADM[$adm]["Gesamt"]["Auftragsvolumen"]+= $_e["Auftragsvolumen"];
		else $aSumByADM[$adm]["Gesamt"]["Auftragsvolumen"] = $_e["Auftragsvolumen"];
		
		if (isset($aSumByADM[$adm]["Gesamt"]["Sollstunden"])) $aSumByADM[$adm]["Gesamt"]["Sollstunden"]+= $_e["Sollstunden"];
		else $aSumByADM[$adm]["Gesamt"]["Sollstunden"] = $_e["Sollstunden"];
		
		if (isset($aSumByADM[$adm]["Gesamt"]["Projekt_Ist_Stunden"])) $aSumByADM[$adm]["Gesamt"]["Projekt_Ist_Stunden"]+= $_e["Projekt_Ist_Stunden"];
		else $aSumByADM[$adm]["Gesamt"]["Projekt_Ist_Stunden"] = $_e["Projekt_Ist_Stunden"];
		
		if (isset($aSumByADM[$adm][$stat]["Sollstunden"])) $aSumByADM[$adm][$stat]["Sollstunden"]+= $_e["Sollstunden"];
		else $aSumByADM[$adm][$stat]["Sollstunden"] = $_e["Sollstunden"];
		
		if (isset($aSumByADM[$adm][$stat]["Projekt_Ist_Stunden"])) $aSumByADM[$adm][$stat]["Projekt_Ist_Stunden"]+= $_e["Projekt_Ist_Stunden"];
		else $aSumByADM[$adm][$stat]["Projekt_Ist_Stunden"] = $_e["Projekt_Ist_Stunden"];
		
		if (isset($aSumByADM[$adm][$stat]["Summe"])) $aSumByADM[$adm][$stat]["Summe"]+= $_e["Auftragsvolumen"];
		else $aSumByADM[$adm][$stat]["Summe"] = $_e["Auftragsvolumen"];
		
		if (isset($aSumByADM[$adm][$stat]["Projekte"])) $aSumByADM[$adm][$stat]["Projekte"]++;
		else $aSumByADM[$adm][$stat]["Projekte"] = 0;
	}
	MyDB::free_result($r);
} else {
	echo MyDB::error()."<br>\n";
	echo "<pre>".fb_htmlEntities($SQL)."</pre>\n";
	
}
foreach($aSumByADM as $r_adm => $aStats) {
	$body_content.= "<strong>ADM: $adm</strong><br>\n<table>";
	foreach($aStats as $stat => $aV) {
		if ($stat == "Liste") continue;
		$body_content.= "\n\t<tr><td>".$stat."</td>\n";
		foreach($aV as $k => $v) {
			$body_content.= "<td>".$stat."</td>\n";
		}
		$body_content.= "\t</tr>\n";
		
	}
	$body_content.= "</table>\n";
	$body_content.= $aStats["Liste"];
}
?>