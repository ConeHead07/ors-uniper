<?php 

$GroupField = "Status";
function orderLnk($f, $of, $od) {
	global $GroupField;
	if ($f != $of && $f == $GroupField) $r_od = "ASC";
	else $r_od = ($f != $of || $od == "ASC") ? "DESC" : "ASC" ;
	return "of=".rawurlencode($f)."&od=".rawurlencode($r_od);
}

function formatPSumResult(&$aRslt) {
	foreach($aRslt as $k => $v) {
		switch($k) {
			case "Status":
			case "Projekte":
			break;
			
			case "Sum-Sollstd":
			case "Sum-Iststd":
			$aRslt[$k] = number_format($v, 2, ",", ".");
			break;
			
			case "P_Prz":
			case "V_Prz":
			$aRslt[$k] = ($v != "--") ? number_format($v, 1, ",", ".")."%" : "--";
			break;
			
			case "Sum-Volumen":
			$aRslt[$k] = number_format($v, 2, ",", ".")."&euro;";
			break;
			
			// ( float $number , int $decimals , string $dec_point , string $thousands_sep )
		}
	}
}

switch($s) {
	case "projects_stat_plg":
	$select_piab = "";
	$piab = "`Planung_IAB` LIKE \"%P%\"";
	break;
	
	case "projects_stat_iab":
	$select_piab = "";
	$piab = "`Planung_IAB` LIKE \"%IAB%\"";
	break;
	
	default:
	$select_piab = "`Planung_IAB` AS `P_I`,";
	$piab = "";
	break;
}

$aGesamt["Projekte"] = 0;
$SQL = "SELECT
\"Gesamt\" AS `Status`,
COUNT(DISTINCT(P.pid)) AS `Projekte`,
100 AS `P_Prz`,
SUM(`Auftragsvolumen`) AS `Sum-Volumen`,
100 AS `V_Prz`,
SUM(`Sollstunden`) AS `Sum-Sollstd`,
SUM(`Dauer`) AS `Sum-Iststd`
FROM `".$_TABLE["projects"]."` AS `P`";
$SQL.= " LEFT JOIN `".$_TABLE["p_entries"]."` AS `Z` USING(pid)\n";

if ($sWhere || $piab) {
	$SQL.= "WHERE ";
	if ($sWhere && $piab) $SQL.= $piab." AND ".$sWhere;
	else $SQL.= $piab.$sWhere;
}

$r = MyDB::query($SQL, $connid);
if ($r) {
	if (MyDB::num_rows($r)) {
		$aGesamt = MyDB::fetch_array($r, MYSQL_ASSOC);
	}
	MyDB::free_result($r);
} else {
	echo MyDB::error()."<br>\n";
	echo "<pre>".fb_htmlEntities($SQL)."</pre>\n";
}

if ($aGesamt["Projekte"]) {
	// echo "#".__LINE__." <pre>aGesamt[Projekte]: ".print_r($aGesamt["Projekte"],true)."</pre>\n";
	$SQL = "SELECT
	`Status`,
	COUNT(DISTINCT(P.pid)) AS `Projekte`,
	ROUND(100/".$aGesamt["Projekte"]."*COUNT(DISTINCT(P.pid)),1) AS `P_Prz`,
	SUM(`Auftragsvolumen`) AS `Sum-Volumen`,
	".($aGesamt["Sum-Volumen"]?("ROUND(100/".$aGesamt["Sum-Volumen"]."*SUM(`Auftragsvolumen`),1)"):"'--'")." AS `V_Prz`,
	SUM(`Sollstunden`) AS `Sum-Sollstd`,
	SUM(`Dauer`) AS `Sum-Iststd`
	FROM `".$_TABLE["projects"]."` AS `P`";
	$SQL.= " LEFT JOIN `".$_TABLE["p_entries"]."` AS `Z` USING(pid)\n";
	if ($sWhere || $piab) {
		$SQL.= "WHERE ";
		if ($sWhere && $piab) $SQL.= $piab." AND ".$sWhere;
		else $SQL.= $piab.$sWhere;
	}
	
	$SQL.= "\nGROUP BY `Status`";
	$of = (strchr($SQL, "`".$_GET["of"]."`") && isset($_GET["of"])) ? $_GET["of"] : "Status";
	$od = (strchr($SQL, "`".$_GET["of"]."`") && isset($_GET["od"]) && in_array($_GET["od"], array("ASC","DESC"))) ? $_GET["od"] : "ASC";
	$SQL.= "ORDER BY `$of` $od";
	// echo "#".__LINE__." <pre>SQL: ".print_r($SQL,true)."</pre>\n";
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
			formatPSumResult($_e);
			$tableRows.= "<tr onclick=\"self.location.href='#".$_e[$GroupField]."'\" class=\"wz".($i%2?1:2)."\"><td>".implode("</td><td class=\"int\">", $_e)."</td></tr>\n";
		}
		formatPSumResult($aGesamt);
		$tableRows.= "<tr class=\"wz".($i%2?1:2)."\"><td>".implode("</td><td class=\"int\">", $aGesamt)."</td></tr>\n";
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
}

$SQL = "SELECT 
`pid`,
`Vorgangsnr`,
`Status`,
$select_piab
`ADM`,
`Kunde`,
`Projekt`,
`Auftragsvolumen` AS `Auftr-Vol`,
`Sollstunden` AS `Soll-Std`,
SUM(`Dauer`) AS `Ist-Std`
FROM `".$_TABLE["projects"]."` AS `P`";
$SQL.= " LEFT JOIN `".$_TABLE["p_entries"]."` AS `Z` USING(pid)\n";
if ($sWhere || $piab) {
	$SQL.= "WHERE ";
	if ($sWhere && $piab) $SQL.= $piab." AND ".$sWhere;
	else $SQL.= $piab.$sWhere;
}
//$SQL.= " GROUP BY `pid`, `Z`.`Mitarbeiter`";

$SQL.= " GROUP BY `pid`";
$of = (strchr($SQL, "`".$_GET["of"]."`") && isset($_GET["of"])) ? $_GET["of"] : "Status";
$od = (strchr($SQL, "`".$_GET["of"]."`") && isset($_GET["od"]) && in_array($_GET["od"], array("ASC","DESC"))) ? $_GET["od"] : "ASC";
$SQL.= "ORDER BY `$of` $od";
$r = MyDB::query($SQL, $connid);
if ($r) {
	$n = MyDB::num_rows($r);
	$fn = MyDB::num_fields($r);
	$body_content.= "<table class=\"tblList\" style=\"margin-bottom:5px;\">\n";
	$body_content.= "<thead><tr>\n";
	for ($fi = 0; $fi < $fn; $fi++) {
		$f = MyDB::field_name($r, $fi);
		if ($f == "pid") continue;
		$body_content.= "<td><a href=\"?".$trackVars."&".orderLnk($f, $of, $od)."\">".$f."</a></td>\n";
	}
	$body_content.= "</tr></thead>\n";
	$body_content.= "<tbody>\n";
	for ($i = 0; $i < $n; $i++) {
		$_e = MyDB::fetch_array($r, MYSQL_ASSOC);
		
		$body_content.= "<tr>\n";
		foreach($_e as $f => $v) {
			switch($f) {
				case "pid": break;
				
				case "Status":
				$body_content.= "<td class=\"".$_e["Status"]."\"><strong>$v</strong></td>\n";
				break;
				
				case "Projekt":
				$lnkHref = " href='index.php?area=admin&modul=&s=p_entries&pid=".$_e["pid"]."&view=read' target=_blank onclick=\"addLightBox(this,this.href+'&pview=body');return false;\"";
				$body_content.= "<td nowrap><div style=\"position:relative;width:120px;overflow:hidden;\" ".(strlen($_e["Projekt"])>20 ? "title=\"".fb_htmlEntities($_e["Projekt"])."\"":"")."><a $lnkHref> ".substr($_e["Projekt"],0,520)."</a></div></td>\n";
				break;
				
				case "P_I":
				$body_content.= "<td>".$_e["P_I"][0]."</td>\n";
				break;
				
				default:
				$body_content.= "<td>$v</td>\n";
			}
		}
		$body_content.= "</tr>\n";
		// $body_content.= "<pre>".print_r($_e,true)."</pre>\n";
		if ($last_pid != $_e["pid"]) {
			if ($i) {
				//$body_content.= "</td></tr></tbody>\n";
				//$body_content.= "</table>";
			}
			/*$body_content.= "<h3> ".$_e["Projekt"]."</strong> (ID".$_e["pid"].")</h3>\n";
			$body_content.= "<table class=\"tblList\" style=\"margin-bottom:5px;\">\n";
			$body_content.= "<thead><tr>\n";
			$body_content.= "
	<td>WWS-Nr</td>
	<td>Status</td>
	<td>ADM</td>
	<td>Kunde</td>
	<td>Eingang</td>
	<td>Volumen</td>
	<td>Sollstd</td>
	<td>Iststd</td>\n";

			$body_content.= "</tr></thead>\n";
			$body_content.= "<tbody><tr>\n";
			
			$body_content.= "<tr>
	<td>".$_e["Vorgangsnr"]."</td>
	<td class=\"".$_e["Status"]."\"><strong>".$_e["Status"]."</strong></td>
	<td>".$_e["ADM"]."</td>
	<td>".$_e["Kunde"]."</td>
	<td>".$_e["Eingangsdatum"]."</td>
	<td>".$_e["Auftragsvolumen"]."</td>
	<td>".$_e["Sollstunden"]."</td>
	<td>".$_e["Iststunden"]."</td>\n";
			$body_content.= "</tr>\n";
			*/
			///$body_content.= "<tr><td colspan=7>\n";
			//$body_content.= "<strong>Projektmitarbeiter:</strong> ".$_e["Projekt_Mitarbeiter"]."<br>\n";
		}
		//if ($_e["Dauer_Pro_Mitarbeiter"]) $body_content.= "<strong> => ".$_e["Mitarbeiter"]."</strong> : ".$_e["Dauer_Pro_Mitarbeiter"]." Std<br>\n";
		//$_e["ADM"]
		//$_e["Mitarbeiter"]
		// break;
		$last_pid = $_e["pid"];
	}
	//$body_content.= "</td></tr></tbody>\n";
	$body_content.= "</tbody>\n";
	$body_content.= "</table>";
	MyDB::free_result($r);
} else echo MyDB::error()."<br>\n".$SQL."<br>\n";

?>