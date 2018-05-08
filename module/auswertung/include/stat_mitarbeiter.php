<?php 


$GroupField = "Mitarbeiter";
function orderLnk($f, $of, $od) {
	global $GroupField;
	if ($f != $of && ($f == "Mitarbeiter" || $f == $GroupField)) $r_od = "ASC";
	else $r_od = ($f != $of || $od == "ASC") ? "DESC" : "ASC" ;
	return "of=".rawurlencode($f)."&od=".rawurlencode($r_od);
}


$wz = "";
$last_adm = "";
$aSumByADM = array();
$tableHead = "";
$tableRows = "";

$SQL = "SELECT 
`Z`.`$GroupField`,
SUM(`Z`.`Dauer`) AS `Dauer`,
COUNT(`Z`.`id`) AS `Zeiterfassungen`,
`P`.`pid`, 
`P`.`Status`,
`P`.`Auftragsvolumen`,
`P`.`Projekt`,
`P`.`Projekt`,
`P`.`Kunde`,
`P`.`Status`,
`P`.`Sollstunden`
FROM `".$_TABLE["p_entries"]."` AS `Z` LEFT JOIN
`".$_TABLE["projects"]."` AS `P` USING(pid)
WHERE 
`Status` IN ('Beauftragt','Abgeschlossen')\n";
if ($sWhere) $SQL.= " AND ".$sWhere."\n";

$SQL.= "GROUP BY `Z`.`$GroupField`
ORDER BY `Z`.`Mitarbeiter`";

$r = MyDB::query($SQL, $connid);

if ($r) {
	$n = MyDB::num_rows($r);
	$NUM_RESULTS = $n;
	if ($NUM_RESULTS) {
		for ($i = 0; $i < $n; $i++) {
			$_e = MyDB::fetch_array($r, MYSQL_ASSOC);
			$gf = $_e[$GroupField];
			$stat= $_e["Status"];
			//echo "#".__LINE__." ".print_r($_e, true)."<br>\n";
			
			if (empty($aSumByADM[$gf])) {
				$aSumByADM[$gf] = array(
					"Num_Gesamt" => 0,
					"Std_Gesamt" => 0.00,
					"Soll_Gesamt" => 0.00,
					"Sum_Gesamt" => 0.00,
					
					"Num_Beauftragt"=>0,
					"Sum_Beauftragt"=>0.00,
					"Std_Beauftragt"=>0.00,
					"Soll_Beauftragt"=>0.00,
					
					"Num_Abgeschlossen"=>0,
					"Sum_Abgeschlossen"=>0.00,
					"Std_Abgeschlossen"=>0.00,
					"Soll_Abgeschlossen"=>0.00
				);
			}
			$aSumByADM[$gf]["Num_Gesamt"]++;
			$aSumByADM[$gf]["Std_Gesamt"]+= $_e["Dauer"];
			$aSumByADM[$gf]["Soll_Gesamt"]+= $_e["Sollstunden"];
			$aSumByADM[$gf]["Sum_Gesamt"]+= $_e["Auftragsvolumen"];
			
			$aSumByADM[$gf]["Num_".$stat]++;
			$aSumByADM[$gf]["Std_".$stat]+= $_e["Dauer"];
			$aSumByADM[$gf]["Soll_".$stat]+= $_e["Sollstunden"];
			$aSumByADM[$gf]["Sum_".$stat]+= $_e["Auftragsvolumen"];
			
			
			/**/
		}
		MyDB::free_result($r);
		
		$SQL = "CREATE TEMPORARY TABLE  `TmpAdmStat` (
	`$GroupField` VARCHAR( 120 ) NOT NULL ,
	`Gesamt` INT( 6 ) NOT NULL ,
	`G_Stunden` FLOAT( 7, 2 ) NOT NULL ,
	`G_Soll` FLOAT( 7, 2 ) NOT NULL ,
	`G_Summe` FLOAT( 7, 2 ) NOT NULL ,
	`Beauftragt` INT( 6 ) NOT NULL ,
	`B_Summe` FLOAT( 7, 2 ) NOT NULL ,
	`B_Dauer` FLOAT( 7, 2 ) NOT NULL ,
	`B_Sollstd` FLOAT( 7, 2 ) NOT NULL ,
	`Abgeschlossen` INT( 6 ) NOT NULL,
	`Abg_Summe` FLOAT( 7, 2 ) NOT NULL ,
	`Abg_Dauer` FLOAT( 7, 2 ) NOT NULL ,
	`Abg_Sollstd` FLOAT( 7, 2 ) NOT NULL  
	) ENGINE = MYISAM\n";
		MyDB::query($SQL, $connid);
		
		$inserts = "";
		foreach($aSumByADM as $r_ma => $r_aStat) {
			//$r_aStat["Pzt_Gewonnen"] = round(100/$r_aStat["Num_Gesamt"]*$r_aStat["Num_Gewonnen"],1);
			//$r_aStat["Pzt_Verloren"] = round(100/$r_aStat["Num_Gesamt"]*$r_aStat["Num_Verloren"],1);
			$inserts.= (isset($inserts[0])?",":"")."\n(\"".MyDB::escape_string($r_ma)."\",";
			$inserts.= "'".implode("','", $r_aStat)."')";
			//
		}
		
		$SQL = "INSERT INTO `TmpAdmStat` VALUES ".$inserts;
		MyDB::query($SQL, $connid);
		if (MyDB::errno()) {
			echo "#".__LINE__." ".MyDB::error()."<br>\n";
			echo "<pre>".fb_htmlEntities($SQL)."</pre>\n";
		}
	}
} else {
	echo "#".__LINE__." ".MyDB::error()."<br>\n";
	echo "<pre>".fb_htmlEntities($SQL)."</pre>\n";
}

if ($NUM_RESULTS) {
	
	$SQL = "SELECT 
	`$GroupField`,
	`Gesamt` AS Projekte,
	`G_Stunden` AS `Gest-Std`,
	/*`G_Soll`,
	`G_Summe`,*/
	`Beauftragt` AS `Aktuelle P`,
	`B_Dauer` AS `Akt-Sum-Std`,
	/*`B_Summe`,
	`B_Sollstd`,*/
	`Abgeschlossen` AS `Abgeschl P`,
	`Abg_Dauer` AS `Abg-Sum-Std`/*,
	`Abg_Summe`,
	`Abg_Sollstd`*/
	FROM `TmpAdmStat`\n";
	
	$of = (isset($_GET["of"]) && strchr($SQL, "`".$_GET["of"]."`")) ? $_GET["of"] : $GroupField;
	$od = (isset($_GET["od"]) && in_array($_GET["od"], array("ASC","DESC"))) ? $_GET["od"] : "ASC";
	$SQL.= "ORDER BY `$of` $od";
	$r = MyDB::query($SQL, $connid);
	if ($r) {
		$n = MyDB::num_rows($r);
		$fn = MyDB::num_fields($r);
		for ($fi = 0; $fi < $fn; $fi++) {
			$f = MyDB::field_name($r, $fi);
			$tableHead.= "<td>xx<a href=\"?".$trackVars."&".orderLnk($f, $of, $od)."\">".$f."</a></td>\n";
		}
		for ($i = 0; $i < $n; $i++) {
			$_e = MyDB::fetch_array($r, MYSQL_ASSOC);
			
			$tableRows.= "<tr onclick=\"self.location.href='#".$_e["ADM"]."'\" class=\"wz".($i%2?1:2)."\"><td>".implode("</td><td class=\"int\">", $_e)."</td></tr>\n";
		}
		MyDB::free_result($r);
	} else {
		echo "#".__LINE__." ".MyDB::error()."<br>\n";
		echo "<pre>".fb_htmlEntities($SQL)."</pre>\n";
	}
	
	$body_content.= "<table class=\"tblList\">
	<thead>
		<tr>".$tableHead."</tr>
	</thead>
	<tbody>\n".$tableRows."</tbody>
	</table>\n";
} else {
	$body_content.= "
	<div style=\"color:#f00;font-size:13px;border:1px solid gray;padding:5px;margin-top:15px;\">
	Es liegen keine beauftragten oder abgeschlossenen Projekte für Ihre Anfrage vor!
	</div>\n";
}
?>