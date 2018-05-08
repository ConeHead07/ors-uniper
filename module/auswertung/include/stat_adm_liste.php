<?php 

function orderLnk($f, $of, $od) {
	if ($f != $of && $f == "AD_Mitarbeiter") $r_od = "ASC";
	else $r_od = ($f != $of || $od == "ASC") ? "DESC" : "ASC" ;
	return "of=".rawurlencode($f)."&od=".rawurlencode($r_od);
}

$wz = "";
$last_adm = "";
$aSumByADM = array();
$tableHead = "";
$tableRows = "";

$SQL = "SELECT  `ADM`,
`Status`, 
`Auftragsvolumen`
FROM `".$_TABLE["projects"]."` AS `P`";
if ($aSearch["MA"]) $SQL.= "\nLEFT JOIN `".$_TABLE["p_entries"]."` AS `Z` USING(PID)\n";
if ($sWhere) $SQL.= "WHERE ".$sWhere;

$SQL.= "GROUP BY `ADM`, `Status`
ORDER BY `ADM` ASC";
$r = MyDB::query($SQL, $connid);
if ($r) {
	$n = MyDB::num_rows($r);
	for ($i = 0; $i < $n; $i++) {
		$_e = MyDB::fetch_array($r, MYSQL_ASSOC);
		$adm = $gf = array_shift(explode(",", $_e["ADM"]));
		$stat= $_e["Status"];
		
		if (empty($aSumByADM[$adm])) {
			$aSumByADM[$adm] = array(
				"Num_Gesamt" => 0,
				"Sum_Gesamt" => 0.00,
				
				"Num_Angebot" => 0,
				"Sum_Angebot" => 0.00,
				
				"Num_Beauftragt"=>0,
				"Sum_Beauftragt"=>0.00,
				
				"Num_Abgeschlossen"=>0,
				"Sum_Abgeschlossen"=>0.00,
				
				"Num_Gewonnen"=>0,
				"Sum_Gewonnen"=>0.00,
				"Pzt_Gewonnen"=>0.00,
				
				"Num_Verloren"=>0,
				"Sum_Verloren"=>0.00,
				"Pzt_Verloren"=>0.00,
			);
		}
		$aSumByADM[$adm]["Num_".$stat]++;
		$aSumByADM[$adm]["Sum_".$stat]+= $_e["Auftragsvolumen"];
		
		$aSumByADM[$adm]["Num_Gesamt"]++;
		$aSumByADM[$adm]["Sum_Gesamt"]+= $_e["Auftragsvolumen"];
		
		if ($stat == "Beauftragt" || $stat == "Abgeschlossen") {
			$aSumByADM[$adm]["Sum_Gewonnen"]++;
			$aSumByADM[$adm]["Sum_Gewonnen"]+= $_e["Auftragsvolumen"];
		}
		/**/
	}
	MyDB::free_result($r);
	
	$SQL = "CREATE TEMPORARY TABLE  `TmpAdmStat` (
`ADM` VARCHAR( 120 ) NOT NULL ,
`Gesamt` INT( 6 ) NOT NULL ,
`Summe` FLOAT( 7, 2 ) NOT NULL ,
`Angebote` INT( 6 ) NOT NULL ,
`A_Summe` FLOAT( 7, 2 ) NOT NULL ,
`Beauftragt` INT( 6 ) NOT NULL ,
`B_Summe` FLOAT( 7, 2 ) NOT NULL ,
`Abgeschlossen` INT( 6 ) NOT NULL ,
`Abg_Summe` FLOAT( 7, 2 ) NOT NULL ,
`Gewonnen` INT( 6 ) NOT NULL ,
`G_Summe` FLOAT( 7, 2 ) NOT NULL ,
`G_Prozent` FLOAT( 7, 2 ) NOT NULL ,
`Verloren` INT( 6 ) NOT NULL ,
`V_Summe` FLOAT( 7, 2 ) NOT NULL,
`V_Prozent` FLOAT( 7, 2 ) NOT NULL
) ENGINE = MYISAM\n";
	MyDB::query($SQL, $connid);
	// echo MyDB::error()."<br>$SQL<br>\n";
	
	$inserts = "";
	foreach($aSumByADM as $r_adm => $r_aStat) {
		$aADM[] = $r_adm;
		$r_aStat["Pzt_Gewonnen"] = round(100/$r_aStat["Num_Gesamt"]*$r_aStat["Num_Gewonnen"],1);
		$r_aStat["Pzt_Verloren"] = round(100/$r_aStat["Num_Gesamt"]*$r_aStat["Num_Verloren"],1);
		$inserts.= (isset($inserts[0])?",":"")."\n(\"".MyDB::escape_string($r_adm)."\",";
		$inserts.= "'".implode("','", $r_aStat)."')";
		// echo "<pre>".print_r($r_aStat, true)."</pre>\n";
	}
	$SQL = "INSERT INTO `TmpAdmStat` VALUES ".$inserts;
	MyDB::query($SQL, $connid);
} else {
	echo MyDB::error()."<br>\n";
	// echo "<pre>".fb_htmlEntities($SQL)."</pre>\n";
}

$SQL = "SELECT 
`ADM`,
`Gesamt`,
`Summe`,/*
`Angebote`,
`A_Summe`,
`Beauftragt`,
`B_Summe`,
`Abgeschlossen`,
`Abg_Summe`,*/
`Gewonnen`,
`G_Summe`,
`G_Prozent`,
`Verloren`,
`V_Summe`,
`V_Prozent`
FROM `TmpAdmStat`\n";

$of = (isset($_GET["of"]) && strchr($SQL, "`".$_GET["of"]."`")) ? $_GET["of"] : "ADM";
$od = (isset($_GET["od"]) && in_array($_GET["od"], array("ASC","DESC"))) ? $_GET["od"] : "ASC";
$SQL.= "ORDER BY `$of` $od";
$r = MyDB::query($SQL, $connid);
if ($r) {
	$n = MyDB::num_rows($r);
	$fn = MyDB::num_fields($r);
	for ($fi = 0; $fi < $fn; $fi++) {
		$f = MyDB::field_name($r, $fi);
		$tableHead.= "<td><a href=\"?".$trackVars."&".orderLnk($f, $of, $od)."\">".$f."</a></td>\n";
	}
	for ($i = 0; $i < $n; $i++) {
		$_e = MyDB::fetch_array($r, MYSQL_ASSOC);
		
		$tableRows.= "<tr onclick=\"self.location.href='#".$_e["ADM"]."'\" class=\"wz".($i%2?1:2)."\"><td>".implode("</td><td class=\"int\">", $_e)."</td></tr>\n";
	}
	MyDB::free_result($r);
} else {
	echo MyDB::error()."<br>\n";
	echo "<pre>".fb_htmlEntities($SQL)."</pre>\n";
}

$body_content.= "<table class=\"tblList\">
<thead>
	<tr>".$tableHead."</tr>
</thead>
<tbody>\n".$tableRows."</tbody>
</table>\n";

?>