<?php 
require(dirname(__FILE__)."/../header.php");
//require(dirname(__FILE__)."/umzugsantrag_stdlib.php");

/*
Umzug innerhalb eines Gebäudes
Pos. Umzugart Einheit Preis zzgl. MwSt
2.1 Typ A € /AP 115,63 €
2.2 Typ B € /AP 212,51 €

Umzug zwischen Gebäuden innerhalb eines Standortes/Campus
Pos. Umzugart Einheit Preis zzgl. MwSt
2.3 Typ A € /AP 130,52 €
2.4 Typ B € /AP 237,12 €

Umzug zwischen Gebäuden innerhalb einer Stadt
Pos. Umzugart Einheit Preis zzgl. MwSt
2.5 Typ A € /AP 148,96 €
2.6 Typ B € /AP 255,91 €

Umzug zwischen Städten
Pos. Umzugart Einheit Preis zzgl. MwSt
2.7 Typ A € /AP 180,36 €
2.8 Typ B € /AP 342,66 €
*/

$aUmzugsPreise = array(
	"BOX_Gebaeude"=>115.00,
	"BOX_Campus"=>130.00, 
	"BOX_Stadt"=>148.00, 
	"BOX_Städte"=>180.00, 
	"MOEBEL_Gebaeude"=>212.00, 
	"MOEBEL_Campus"=>237.00, 
	"MOEBEL_Stadt"=>255.00, 
	"MOEBEL_Städte"=>342.00 );


$statListe = "";
$aGebCampus = array();
function isSameCampus($geb1, $geb2) {
	global $aGebCampus;
	
	if (empty($aGebCampus[$geb1]) || empty($aGebCampus[$geb2])) return false;
	return ($aGebCampus[$geb1] == $aGebCampus[$geb2]);
}

$statMonat = getRequest("statMonat", date("Y-m"));
$statMonat = substr($statMonat, 0, 7);
$aMonate = array();

$aGeb2Ort = array();
$sql = "SELECT gebaeude, stadtname FROM `".$_TABLE["gebaeude"]."`";
$rows = $db->query_rows($sql);
foreach($rows as $v) $aGeb2Ort[$v["gebaeude"]] = $v["stadtname"];


$sql = "SELECT distinct(date_format(umzugstermin, \"%Y-%m\")) monat FROM `".$_TABLE["umzugsantrag"]."` WHERE abgeschlossen = \"Ja\" AND length(umzugstermin)>1 ORDER BY umzugstermin DESC LIMIT 24";
$rows = $db->query_rows($sql);
if ($db->error()) $statListe.=  $db->error()."<br>\n".$sql."<br>\n";
//else $statListe.=  $sql."<br>\n";

foreach($rows as $row) $aMonate[] = $row["monat"];

$sql = "SELECT gebaeude, campus FROM `".$_TABLE["gebaeude"]."` WHERE length( campus ) >=1";
$rows = $db->query_rows($sql);
if ($db->error()) $statListe.=  $db->error()."<br>\n".$sql."<br>\n";
//else $statListe.=  $sql."<br>\n";

foreach($rows as $row) $aGebCampus[$row["gebaeude"]] = $row["campus"];
//$statListe.=  print_r($aGebCampus,1)."<br>\n";

$sql = "SELECT SUM(sonderleistung_preis) slkosten, SUM(time_to_sec( `zeit` ) /3600 *21) nlzeitkosten, standort, gebaeude\n";
$sql.= "FROM `".$_TABLE["nebenleistungen"]."` \n";
$sql.= "WHERE status = \"Abgeschlossen\" AND DATE_FORMAT(datum, \"%Y-%m\") LIKE \"".$db->escape($statMonat)."\"\n";
$sql.= "GROUP BY gebaeude\n";
$sql.= "ORDER BY gebaeude\n";
$rows = $db->query_rows($sql);
foreach($rows as $v) {
	$Region = array_shift(explode("_", $v["gebaeude"]));
	$statOrt = ($Region == "ZV") ? $Region : $v["standort"];
	$nlKosten[$statOrt][$v["gebaeude"]] = 
	($v["slkosten"]?round(floatval($v["slkosten"]),2):0.00)+
	($v["nlzeitkosten"]?round(floatval($v["nlzeitkosten"]),2):0.00);
}

$sql = "SELECT ua.aid, ua.ort, ua.gebaeude, ua.umzugstermin, um.umzugsart, vg.stadtname vort, um.gebaeude vgebaeude, zg.stadtname zort, um.ziel_gebaeude zgebaeude \n";
$sql.= "FROM `".$_TABLE["umzugsantrag"]."` ua LEFT JOIN `".$_TABLE["umzugsmitarbeiter"]."` um USING(aid) LEFT JOIN `".$_TABLE["gebaeude"]."` vg ON um.gebaeude=vg.gebaeude LEFT JOIN `".$_TABLE["gebaeude"]."` zg ON um.ziel_gebaeude=zg.gebaeude\n";
$sql.= "WHERE um.umzugsart != \"Datenpflege\" AND ua.abgeschlossen = \"Ja\" AND DATE_FORMAT(ua.umzugstermin, \"%Y-%m\") LIKE \"".$db->escape($statMonat)."\"\n";
$sql.= "ORDER BY zgebaeude\n";

$r = $db->query($sql);
if ($db->error()) $statListe.=  $db->error()."<br>\n".$sql."<br>\n";
//else $statListe.=  $sql."<br>\n";
$n = $db->num_rows($r);

$aStatUmzuege = array();

for ($i = 0; $i < $n; $i++) {
	$e = $db->fetch_assoc($r);
	$Standort = $e["ort"];
	$zielOrt = $aGeb2Ort[$e["zgebaeude"]];
	$Region = array_shift(explode("_", $e["zgebaeude"]));
	$statOrt = ($Region == "ZV") ? $Region : $zielOrt;
	$statAID = $e["aid"];
	$zielGebaeude = $statOrt."/".$e["zgebaeude"];
	
	if (!isset($aStatUmzuege[$zielGebaeude])) $aStatUmzuege[$zielGebaeude] = array();
	if (!isset($aStatUmzuege[$zielGebaeude][$statAID])) {
		$aStatUmzuege[$zielGebaeude][$statAID] = array(
			"aid" => $statAID,
			"umzugstermin" => $e["umzugstermin"],
			"BOX_Gebaeude"=>0,
			"BOX_Campus"=>0,
			"BOX_Stadt"=>0,
			"BOX_Städte"=>0,
			
			"MOEBEL_Gebaeude"=>0,
			"MOEBEL_Campus"=>0,
			"MOEBEL_Stadt"=>0,
			"MOEBEL_Städte"=>0
		);
	}
	
	$aStatUmzuege[$zielGebaeude][$statAID]["umzugstermin"] = $e["umzugstermin"];
	switch($e["umzugsart"]) {
		case "BOX":
		case "MOEBEL":
		$uart = $e["umzugsart"];
		break;
		
		default:
		$uart = $e["undefiniert"];
	}
	
	//$e["zort"] = get_ort_byGeb($e["zgebaeude"]);
	//$e["vort"] = get_ort_byGeb($e["vgebaeude"]);
	
	//echo "#".__LINE__." AID:".$e["aid"]."; ".$e["vgebaeude"].":".$e["zgebaeude"]." // ".isSameCampus($e["vgebaeude"], $e["zgebaeude"])." // ".$e["vort"].":".$e["zort"]."<br>\n";
	
	if ($e["vgebaeude"]==$e["zgebaeude"]) {
		$uweg = "Gebaeude"; //echo "#".__LINE__." <br>\n";
	} elseif(isSameCampus($e["vgebaeude"], $e["zgebaeude"])) {
		$uweg = "Campus";   //echo "#".__LINE__." <br>\n";
	} elseif($e["vort"]==$e["zort"]) {
		$uweg = "Stadt";    //echo "#".__LINE__." <br>\n";
	} else {
		$uweg = "Städte";   //echo "#".__LINE__." <br>\n";
	}
	
	$aStatUmzuege[$zielGebaeude][$statAID][$uart."_".$uweg]++;
}


$db->free_result($r);
$statListe.=  '<link rel="STYLESHEET" type="text/css" href="../css/tablelisting.css">'."\n";
//$statListe.=  "<pre>\$aStatUmzuege: ".print_r($aStatUmzuege,1)."</pre>\n";

$optionsMonate = "";
for ($i = 0; $i < count($aMonate); $i++) {
	$selected = ($statMonat != $aMonate[$i]) ? "" : " selected=\"true\"";
	$optionsMonate.= "<option value=\"".$aMonate[$i]."\" $selected>".$aMonate[$i]."</option>\n";
}
$statListe.= "
<form action=\"?\" method=get name=\"frmStat\" id=\"frmStat\">
<span style=\"border:0;font-weight:bold;font-size:14px;\">Auswertungsmonat <select style=\"border:0;font-weight:bold;font-size:14px;background:none;\" name=\"statMonat\" style=\"width:auto;\" onchange=\"document.forms['frmStat'].submit()\">
<option value=\"\">auswählen</option>
$optionsMonate
</select>
<input type=\"hidden\" name=\"s\" value=\"$s\">
<noscript><input type=\"submit\" value=\"Auswertung starten\"></noscript>
</span>
</form>
<table class=\"tblList\" border=1>
	<thead>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td colspan=4 align=\"center\">BOX-Move</td>
		
		<td colspan=4 align=\"center\">BOX-Move &amp; M&ouml;bel</td>
		<td>&nbsp;</td>
		
	</tr>
	<tr>
		<td>Ziel-Geb&auml;ude</td>
		<td>ID</td>
		<td>Datum</td>
		<td>Gebaeude<br>".number_format($aUmzugsPreise["BOX_Gebaeude"],2,",",".")." &euro;</td>
		<td>Campus<br>".number_format($aUmzugsPreise["BOX_Campus"],2,",",".")." &euro;</td>
		<td>Stadt<br>".number_format($aUmzugsPreise["BOX_Stadt"],2,",",".")." &euro;</td>
		<td>Zw. Städten<br>".number_format($aUmzugsPreise["BOX_Städte"],2,",",".")." &euro;</td>
		
		<td>Gebaeude<br>".number_format($aUmzugsPreise["MOEBEL_Gebaeude"],2,",",".")." &euro;</td>
		<td>Campus<br>".number_format($aUmzugsPreise["MOEBEL_Campus"],2,",",".")." &euro;</td>
		<td>Stadt<br>".number_format($aUmzugsPreise["MOEBEL_Stadt"],2,",",".")." &euro;</td>
		<td>Zw.Städten<br>".number_format($aUmzugsPreise["MOEBEL_Städte"],2,",",".")." &euro;</td>
		<td>Summen</td>
		
	</tr>
	</thead>
	<tbody>
";

$tplLinkNL = "<a href=\"./sites/admin_auswertung_details_nl.php?monat=$statMonat&gebaeude=%gebaeude%\" target=\"winNL\" onclick=\"window.open(this.href,'winNL');return false;\">%text%</a>";
$sumAllPreise = 0;
$sumAllNL = 0;
$aSumAll = array("BOX_Gebaeude"=>0,"BOX_Campus"=>0, "BOX_Stadt"=>0, "BOX_Städte"=>0, "MOEBEL_Gebaeude"=>0, "MOEBEL_Campus"=>0, "MOEBEL_Stadt"=>0, "MOEBEL_Städte"=>0 );
$aSumAllPreis = array("BOX_Gebaeude"=>0,"BOX_Campus"=>0, "BOX_Stadt"=>0, "BOX_Städte"=>0, "MOEBEL_Gebaeude"=>0, "MOEBEL_Campus"=>0, "MOEBEL_Stadt"=>0, "MOEBEL_Städte"=>0 );
$lastZielOrt = "";
foreach($nlKosten as $stadt => $v) {
	foreach($v as $geb => $kosten) {
		if ($kosten > 0) $aStatUmzuege[$stadt."/".$geb]["NL"] = $kosten;
	}
}
ksort($aStatUmzuege);

foreach($aStatUmzuege as $rOrt => $v) {
	$aZiel = explode("/", $rOrt);
	$statListe.=  "<tr><td colspan=11><strong>".($lastZielOrt!=$aZiel[0]?"<span style=\"font-size:12px;font-weight:bold;\">".$aZiel[0]."</span><br>":"").$aZiel[1]."</strong>&nbsp;</td></tr>\n";
	$wz = "";
	
	$sumGebPreise = 0;
	$aSumGeb = array("BOX_Gebaeude"=>0,"BOX_Campus"=>0, "BOX_Stadt"=>0, "BOX_Städte"=>0, "MOEBEL_Gebaeude"=>0, "MOEBEL_Campus"=>0, "MOEBEL_Stadt"=>0, "MOEBEL_Städte"=>0 );
	$aSumGebPreis = array("BOX_Gebaeude"=>0,"BOX_Campus"=>0, "BOX_Stadt"=>0, "BOX_Städte"=>0, "MOEBEL_Gebaeude"=>0, "MOEBEL_Campus"=>0, "MOEBEL_Stadt"=>0, "MOEBEL_Städte"=>0 );
	foreach($v as $rAid => $v2) {
		if ($rAid != "NL") {
			$wz = ($wz!=1?1:2);
			if ($s=="vauswertung" || $user["gruppe"]=="kunde_report") $link = "?s=pantrag&id=".$rAid;
			elseif ($s=="auswertung" || strpos($user["gruppe"], "admin")!==false) $link = "?s=aantrag&id=".$rAid;
			else $link = "#";
			$statListe.=  "<tr class=\"wz".$wz."\"><td>&nbsp;</td>
			
			<td style=\"padding-left:15px;padding-right:15px;\"><a href=\"$link\">".$v2["aid"]."</a></td>
			<td style=\"padding-left:15px;padding-right:15px;\"><a href=\"$link\">".format_dbDate($v2["umzugstermin"], "d.m.Y")."</a></td>
			
			<td class=\"int\">".$v2["BOX_Gebaeude"]."</td>
			<td class=\"int\">".$v2["BOX_Campus"]."</td>
			<td class=\"int\">".$v2["BOX_Stadt"]."</td>
			<td class=\"int\">".$v2["BOX_Städte"]."</td>
			
			<td class=\"int\">".$v2["MOEBEL_Gebaeude"]."</td>
			<td class=\"int\">".$v2["MOEBEL_Campus"]."</td>
			<td class=\"int\">".$v2["MOEBEL_Stadt"]."</td>
			<td class=\"int\">".$v2["MOEBEL_Städte"]."</td>
			<td class=\"int\">&nbsp;</td>
			
			</tr>\n";
			$aSumGeb["BOX_Gebaeude"]+= $v2["BOX_Gebaeude"];
			$aSumGeb["BOX_Campus"]+= $v2["BOX_Campus"];
			$aSumGeb["BOX_Stadt"]+= $v2["BOX_Stadt"];
			$aSumGeb["BOX_Städte"]+= $v2["BOX_Städte"];
			$aSumGeb["MOEBEL_Gebaeude"]+= $v2["MOEBEL_Gebaeude"];
			$aSumGeb["MOEBEL_Campus"]+= $v2["MOEBEL_Campus"];
			$aSumGeb["MOEBEL_Stadt"]+= $v2["MOEBEL_Stadt"];
			$aSumGeb["MOEBEL_Städte"]+= $v2["MOEBEL_Städte"];
			
			$aSumAll["BOX_Gebaeude"]+= $v2["BOX_Gebaeude"];
			$aSumAll["BOX_Campus"]+= $v2["BOX_Campus"];
			$aSumAll["BOX_Stadt"]+= $v2["BOX_Stadt"];
			$aSumAll["BOX_Städte"]+= $v2["BOX_Städte"];
			$aSumAll["MOEBEL_Gebaeude"]+= $v2["MOEBEL_Gebaeude"];
			$aSumAll["MOEBEL_Campus"]+= $v2["MOEBEL_Campus"];
			$aSumAll["MOEBEL_Stadt"]+= $v2["MOEBEL_Stadt"];
			$aSumAll["MOEBEL_Städte"]+= $v2["MOEBEL_Städte"];
			
		}
	}
	
	$statListe.=  "
	<tr class=\"wz".$wz."\"><td>&nbsp;</td>
		
		<td style=\"padding-left:15px;padding-right:15px;\">&nbsp;</td>
		<td style=\"padding-left:15px;padding-right:15px;\">SUMME</td>
		
		<td class=\"int\">".$aSumGeb["BOX_Gebaeude"]."</td>
		<td class=\"int\">".$aSumGeb["BOX_Campus"]."</td>
		<td class=\"int\">".$aSumGeb["BOX_Stadt"]."</td>
		<td class=\"int\">".$aSumGeb["BOX_Städte"]."</td>
		
		<td class=\"int\">".$aSumGeb["MOEBEL_Gebaeude"]."</td>
		<td class=\"int\">".$aSumGeb["MOEBEL_Campus"]."</td>
		<td class=\"int\">".$aSumGeb["MOEBEL_Stadt"]."</td>
		<td class=\"int\">".$aSumGeb["MOEBEL_Städte"]."</td>
		<td class=\"int\">&nbsp;</td>
		
	</tr>\n";
	
	foreach($aSumGeb as $sk => $sv) {
		$aSumGebPreis[$sk] = round($sv*$aUmzugsPreise[$sk],2);
		$aSumAllPreis[$sk]+= $aSumGebPreis[$sk];
		$sumGebPreise+= $aSumGebPreis[$sk];
	}
	$sumAllPreise+= $sumGebPreise;
	$statListe.=  "<tr class=\"wz".$wz."\"><td>&nbsp;</td>
		
		<td style=\"padding-left:15px;padding-right:15px;\">&nbsp;</td>
		<td style=\"padding-left:15px;padding-right:15px;\">Kosten</td>
		
		
		<td class=\"int\">".number_format($aSumGebPreis["BOX_Gebaeude"], 2, ",", ".")." &euro;</td>
		<td class=\"int\">".number_format($aSumGebPreis["BOX_Campus"], 2, ",", ".")." &euro;</td>
		<td class=\"int\">".number_format($aSumGebPreis["BOX_Stadt"], 2, ",", ".")." &euro;</td>
		<td class=\"int\">".number_format($aSumGebPreis["BOX_Städte"], 2, ",", ".")." &euro;</td>
		
		<td class=\"int\">".number_format($aSumGebPreis["MOEBEL_Gebaeude"], 2, ",", ".")." &euro;</td>
		<td class=\"int\">".number_format($aSumGebPreis["MOEBEL_Campus"], 2, ",", ".")." &euro;</td>
		<td class=\"int\">".number_format($aSumGebPreis["MOEBEL_Stadt"], 2, ",", ".")." &euro;</td>
		<td class=\"int\">".number_format($aSumGebPreis["MOEBEL_Städte"], 2, ",", ".")." &euro;</td>
		<td class=\"int\">".number_format($sumGebPreise, 2, ",", ".")." &euro;</td>
		
	</tr>\n";
	
	if (isset($nlKosten[$aZiel[0]]) && isset($nlKosten[$aZiel[0]][$aZiel[1]])) {
		$sumNL[$aZiel[1]] = $nlKosten[$aZiel[0]][$aZiel[1]];
		$sumAllNL+= $nlKosten[$aZiel[0]][$aZiel[1]];
		unset($nlKosten[$aZiel[0]][$aZiel[1]]);
	} else {
		$sumNL[$aZiel[1]] = 0;
	}
	
	if ($sumNL[$aZiel[1]]) {
		$statListe.=  "
		<tr class=\"wz".$wz."\"><td>&nbsp;</td>
			
			<td style=\"padding-left:15px;padding-right:15px;\">&nbsp;</td>
			<td style=\"padding-left:15px;padding-right:15px;\">".strtr($tplLinkNL, array("%gebaeude%"=>urlencode($aZiel[1]), "%text%"=>"Summe N-Leistungen"))."</td>
			
			<td class=\"int\">&nbsp;</td>
			<td class=\"int\">&nbsp;</td>
			<td class=\"int\">&nbsp;</td>
			<td class=\"int\">&nbsp;</td>
			
			<td class=\"int\">&nbsp;</td>
			<td class=\"int\">&nbsp;</td>
			<td class=\"int\">&nbsp;</td>
			<td class=\"int\">&nbsp;</td>
			<td class=\"int\">".number_format($sumNL[$aZiel[1]], 2, ",", ".")." &euro;</td>
			
		</tr>\n";
		
		$statListe.=  "
		<tr class=\"wz".$wz."\"><td>&nbsp;</td>
			
			<td style=\"padding-left:15px;padding-right:15px;\">&nbsp;</td>
			<td style=\"padding-left:15px;padding-right:15px;\">Total</td>
			
			<td class=\"int\">&nbsp;</td>
			<td class=\"int\">&nbsp;</td>
			<td class=\"int\">&nbsp;</td>
			<td class=\"int\">&nbsp;</td>
			
			<td class=\"int\">&nbsp;</td>
			<td class=\"int\">&nbsp;</td>
			<td class=\"int\">&nbsp;</td>
			<td class=\"int\">&nbsp;</td>
			<td class=\"int\">".number_format($sumGebPreise+$sumNL[$aZiel[1]], 2, ",", ".")." &euro;</td>
			
		</tr>\n";
	}
	$lastZielOrt = $aZiel[0];
}
foreach($nlKosten as $stadt => $v) {
	foreach($v as $gebaeude => $kosten) {
		if (!($kosten > 0)) continue;
		$sumAllNL+= $kosten;
		$statListe.=  "
		<tr class=\"wz".$wz."\"><td><strong>$stadt/$gebaeude</strong></td>
			
			<td style=\"padding-left:15px;padding-right:15px;\">&nbsp;</td>
			<td style=\"padding-left:15px;padding-right:15px;\">Summe N-Leistungen</td>
			
			<td class=\"int\">&nbsp;</td>
			<td class=\"int\">&nbsp;</td>
			<td class=\"int\">&nbsp;</td>
			<td class=\"int\">&nbsp;</td>
			
			<td class=\"int\">&nbsp;</td>
			<td class=\"int\">&nbsp;</td>
			<td class=\"int\">&nbsp;</td>
			<td class=\"int\">&nbsp;</td>
			<td class=\"int\">".number_format($kosten, 2, ",", ".")." &euro;</td>
			
		</tr>\n";
	}
}
$statListe.=  "<tr class=\"wz".$wz."\" style=\"border-top:2px solid #939393;\"><td><strong>Summe Alle Orte</strong></td>
	
	<td style=\"padding-left:15px;padding-right:15px;\"></td>
	<td style=\"padding-left:15px;padding-right:15px;\">SUMME</td>
	
	<td class=\"int\">".$aSumAll["BOX_Gebaeude"]."</td>
	<td class=\"int\">".$aSumAll["BOX_Campus"]."</td>
	<td class=\"int\">".$aSumAll["BOX_Stadt"]."</td>
	<td class=\"int\">".$aSumAll["BOX_Städte"]."</td>
	
	<td class=\"int\">".$aSumAll["MOEBEL_Gebaeude"]."</td>
	<td class=\"int\">".$aSumAll["MOEBEL_Campus"]."</td>
	<td class=\"int\">".$aSumAll["MOEBEL_Stadt"]."</td>
	<td class=\"int\">".$aSumAll["MOEBEL_Städte"]."</td>
	
</tr>\n";

$statListe.=  "<tr class=\"wz".$wz."\"><td>&nbsp;</td>
	
	<td style=\"padding-left:15px;padding-right:15px;\">&nbsp;</td>
	<td style=\"padding-left:15px;padding-right:15px;\">Kosten</td>
	
	
	<td class=\"int\">".number_format($aSumAllPreis["BOX_Gebaeude"], 2, ",", ".")." &euro;</td>
	<td class=\"int\">".number_format($aSumAllPreis["BOX_Campus"], 2, ",", ".")." &euro;</td>
	<td class=\"int\">".number_format($aSumAllPreis["BOX_Stadt"], 2, ",", ".")." &euro;</td>
	<td class=\"int\">".number_format($aSumAllPreis["BOX_Städte"], 2, ",", ".")." &euro;</td>
	
	<td class=\"int\">".number_format($aSumAllPreis["MOEBEL_Gebaeude"], 2, ",", ".")." &euro;</td>
	<td class=\"int\">".number_format($aSumAllPreis["MOEBEL_Campus"], 2, ",", ".")." &euro;</td>
	<td class=\"int\">".number_format($aSumAllPreis["MOEBEL_Stadt"], 2, ",", ".")." &euro;</td>
	<td class=\"int\">".number_format($aSumAllPreis["MOEBEL_Städte"], 2, ",", ".")." &euro;</td>
	<td class=\"int\">".number_format($sumAllPreise, 2, ",", ".")." &euro;</td>
	
</tr>\n";

$statListe.=  "<tr class=\"wz".$wz."\"><td>&nbsp;</td>
	
	<td style=\"padding-left:15px;padding-right:15px;\">&nbsp;</td>
	<td style=\"padding-left:15px;padding-right:15px;\">Gest.Sum. NL-Kosten</td>
	
	
	<td class=\"int\">&nbsp;</td>
	<td class=\"int\">&nbsp;</td>
	<td class=\"int\">&nbsp;</td>
	<td class=\"int\">&nbsp;</td>
	
	<td class=\"int\">&nbsp;</td>
	<td class=\"int\">&nbsp;</td>
	<td class=\"int\">&nbsp;</td>
	<td class=\"int\">&nbsp;</td>
	<td class=\"int\">".number_format($sumAllNL, 2, ",", ".")." &euro;</td>
	
</tr>\n";

$statListe.=  "<tr class=\"wz".$wz."\"><td>&nbsp;</td>
	
	<td style=\"padding-left:15px;padding-right:15px;\">&nbsp;</td>
	<td style=\"padding-left:15px;padding-right:15px;\">Gst.Sum. Kosten</td>
	
	
	<td class=\"int\">&nbsp;</td>
	<td class=\"int\">&nbsp;</td>
	<td class=\"int\">&nbsp;</td>
	<td class=\"int\">&nbsp;</td>
	
	<td class=\"int\">&nbsp;</td>
	<td class=\"int\">&nbsp;</td>
	<td class=\"int\">&nbsp;</td>
	<td class=\"int\">&nbsp;</td>
	<td class=\"int\">".number_format($sumAllNL+$sumAllPreise, 2, ",", ".")." &euro;</td>
	
</tr>\n";
$statListe.=  "</tbody></table>\n";

$body_content.= "<div class=\"divModuleBasic padding6px width5Col heightAuto colorContentMain\"> 
<h1><span class=\"spanTitle\">Auswertung durchegef&uuml;hrter Umz&uuml;ge</span></h1> 
<p>
<div id=\"Umzugsantrag\" class=\"divInlay\">\n";
$body_content.= $statListe;
$body_content.= "</div>\n";
$body_content.= "</div>\n";
?>