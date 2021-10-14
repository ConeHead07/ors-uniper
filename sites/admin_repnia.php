<?php 
require(dirname(__FILE__)."/../header.php");
//require(dirname(__FILE__)."/umzugsantrag_stdlib.php");
$conf_etagen_flaechen_file = dirname(__FILE__)."/../textfiles/conf.etagen_flaechen.cnf";
/*
# Kommawerte in Punktnoation angeben Bsp: 105.5 statt 105,5

[NO_BER_ATT_E]
OG03 = 1337
OG04 = 1236

[NO_BER_ATT_M]
EG00 = 847
OG01 = 1234
OG02 = 1279
OG03 = 1287
OG04 = 1230
 */
$conf_etagen_ausblenden_file = dirname(__FILE__)."/../textfiles/conf.etagen_ausblenden_nia.cnf";
/*
# Etagen, die nicht im NIA-Report aufgenommen werden sollen, muessen zeilenweise
# unter dem in eckigen Klammen gesetzten [Gebäudenamen] aufgeführt werden
# Mit Raute beginnende Zeilen werden werden beim Auslesen der Konfiguration ignoriert

[NO_BER_ATT_E]
#OG03

[NO_BER_ATT_M]

[NO_STA_RUH_5]

[NO_STA_RUH_5A]

 */

$conf_gebaeude_file = dirname(__FILE__)."/../textfiles/conf.gebaeude.cnf";
/*
# Reports für Gebäude aktivieren: J für Ja, N für Nein

NO_BER_ATT_E = J
NO_BER_ATT_M = J
NO_STA_RUH_5 = J
NO_STA_RUH_5A = J
NW_DOR_KAM = J
NW_KAS_KOE = N
N__BRE_STR = J
 */
$CNF_GP = array();
if (file_exists($conf_gebaeude_file)) {
	$CNF_GP = conf_load($conf_gebaeude_file);
}

if (file_exists($conf_etagen_ausblenden_file)) {
  $CNF_ETG_HIDE = conf_load($conf_etagen_ausblenden_file);
}
//die("<pre>".print_r($CNF_ETG_HIDE,1)."</pre>");

if (file_exists($conf_etagen_flaechen_file)) {
  $CNF_EF = conf_load($conf_etagen_flaechen_file);
}
$aValidGP = array();
foreach($CNF_GP as $k => $v) {
	if ($v == "J") $aValidGP[] = $k;
}
$sFilter = "";
$filter = getRequest("filter","");
$sFilter = (substr($filter,0,2)=="s:") ? substr($filter,2) : "";
$gFilter = (substr($filter,0,2)=="g:") ? substr($filter,2) : "";

//$msg.= "<pre>".print_r($aValidGP,1)."</pre>\n";

$sql = "SELECT g.`gebaeude`, g.`stadtname`, g.`adresse`
FROM `mm_stamm_gebaeude` g LEFT JOIN `mm_stamm_immobilien` i USING(`gebaeude`)
WHERE LENGTH(i.`etage`) > 0 AND g.`gebaeude` IN ('".implode("','", $aValidGP)."')
GROUP BY g.`gebaeude`
ORDER BY g.`gebaeude` ASC";

$Gebaeude = array();
$GEFlaechen = array();
$gebaeude = "";
$etage = "";
$options = "";
$lastS = "";
$pageTitle = "NIA-Report: ";

//$msg.= "#".__LINE__." $sql<br/>\n";
$rows = $db->query_rows($sql);
if (!$db->error()) {
	foreach($rows as $v) {
	  $stadt = $v["stadtname"];
	  $gebaeude = $v["gebaeude"];
	  if ($lastS != $stadt) {
	   	$selected = (!empty($sFilter) && $sFilter == $stadt) ? "selected=\"true\"" : "";
	   	if ($lastS) $options.="</optgroup>";
	   	$options.= "<optgroup label=\"$stadt\">\n";
	   	$options.= "<option value=\"s:$stadt\" $selected >Alle Gebaeude von $stadt</option>\n";
	   	if ($selected) $pageTitle.= $stadt;
	  }
	  $selected = (!empty($gFilter) && $gFilter == $Gebaeude) ? "selected=\"true\"" : "";
	  $options.= "<option value=\"g:$gebaeude\" $selected >".$gebaeude. ": ".$v["adresse"]."</option>\n";
	  if ($selected) $pageTitle.= $v["adresse"];
	   
	   
	  $lastS = $stadt;
	}
	if ($lastS) $options.="</optgroup>";
} else {
	$error.= $db->error()."<br/>\n";
}

$sql = "SELECT g.`gebaeude`, g.`stadtname`, i.`etage`, g.`adresse`
FROM `mm_stamm_gebaeude` g LEFT JOIN `mm_stamm_immobilien` i USING(`gebaeude`)
WHERE LENGTH(i.`etage`) > 0 AND g.`gebaeude` IN ('".implode("','", $aValidGP)."')\n";
if ($sFilter) $sql.= "AND g.stadtname LIKE \"".$sFilter."\"\n";
elseif ($gFilter) $sql.= "AND g.gebaeude LIKE \"".$gFilter."\"\n";
$sql.= "GROUP BY i.`gebaeude`, i.`etage`
ORDER BY i.`gebaeude` ASC , i.`etage` ASC";

$rows = $db->query_rows($sql);
if (!$db->error()) {
	foreach($rows as $v) {
	  $stadt = $v["stadtname"];
	  $gebaeude = $v["gebaeude"];
	  $etage = $v["etage"];
	  $Gebaeude[$gebaeude]["adresse"] = $v["adresse"];
	  
	  if (is_array($CNF_ETG_HIDE)
	  && array_key_exists($gebaeude, $CNF_ETG_HIDE) 
	  && array_key_exists($etage,    $CNF_ETG_HIDE[$gebaeude]))
	  	continue;
	  
	  $GEFlaechen[$gebaeude][$etage] = array(
	    "flaeche" => (!empty($CNF_EF[$gebaeude][$etage]))?$CNF_EF[$gebaeude][$etage]:0,
	    "ma" => 0
	   );
	}
} else {
	$error.= $db->error()."<br/>\n";
}

$sql = "SELECT i.`gebaeude`, i.`etage`, COUNT(*) AS Anzahl
FROM `mm_stamm_mitarbeiter` m LEFT JOIN `mm_stamm_immobilien` i ON m.`immobilien_raum_id` = i.`id`
WHERE m.extern IN ('Staff', 'Extern', 'Flex') AND LENGTH(i.`gebaeude`) > 0 AND i.`gebaeude` IN ('".implode("','", $aValidGP)."')\n";
if ($sFilter) $sql.= "AND i.stadtname LIKE \"".$sFilter."\"\n";
elseif ($gFilter) $sql.= "AND i.gebaeude LIKE \"".$gFilter."\"\n";

$sql.= "GROUP BY i.`gebaeude`, i.`etage` 
ORDER BY i.`gebaeude` ASC , i.`etage` ASC";
//$msg.= $sql."<br>\n";
$rows = $db->query_rows($sql);
if (!$db->error()) {
  foreach($rows as $v) {
    $gebaeude = $v["gebaeude"];
    $etage = $v["etage"];
    	  
	if (is_array($CNF_ETG_HIDE)
	&& array_key_exists($gebaeude, $CNF_ETG_HIDE) 
	&& array_key_exists($etage,    $CNF_ETG_HIDE[$gebaeude]))
		continue;
	
    $GEFlaechen[$gebaeude][$etage]["ma"] = $v["Anzahl"];
  }
}

$op = "<div class=\"divModuleBasic padding6px width5Col heightAuto colorContentMain\"> 
<h1><span class=\"spanTitle\">$pageTitle</span></h1> 
<p>
<div id=\"Umzugsantrag\" class=\"divInlay\">\n";

$op.= "<form action=\"".basename($_SERVER["PHP_SELF"])."\" method=\"get\">\n";
$op.= "<input name=\"s\" value=\"$s\" type=\"hidden\"/>\n";
$op.= "<select onchange=\"this.parentNode.submit()\" name=\"filter\" style=\"width:auto;\">\n";
$op.= "<option value=\":\">Gebaeudeauswahl</option>\n";
$op.= "<option>Alle Gebaeude</option>\n";
$op.= $options."\n";
$op.= "</select><noscript><input type=\"submit\" value=\"go\"></noscript></form>\n";
$op.= "<table class=\"tblList\" style=\"width:600px;\">\n";
$op.= "<thead>\n";
$op.= "<tr><td>Gebaeude:Etage</td><td>Fl&auml;che</td><td>AP</td><td>qm/AP</td></tr>\n";
$op.= "</thead>\n";
$op.= "<tbody>\n";
foreach($GEFlaechen as $gebaeude => $Etg) {
  if (empty($Etg)) continue;
  //echo "#".__LINE__." $gebaeude: ".print_r($Etg,1)."<br/>\n";
  $op.= "<tr class=\"wz2\"><td colspan=4 style=\"padding-top:15px;font-size:12px;font-weight:bold;\">".$gebaeude."<br>\n".$Gebaeude[$gebaeude]["adresse"]."</td></tr>\n";
  $wz = "";
  $sum_flaeche = 0.0;
  $sum_ma = 0;
  foreach($Etg as $etage => $v) {
    $wz = ($wz != "wz1") ? "wz1" : "wz2";
    $qm_per_ma = ($v["ma"]>0) ? number_format($v["flaeche"]/$v["ma"],2,",", ".") : "-,--";  
    $op.= "<tr class=\"$wz\"><td>$etage</td><td class=\"float\">".number_format($v["flaeche"],2,",",".")."</td><td class=\"int\">".$v["ma"]."</td><td class=\"float\">".$qm_per_ma."</td></tr>\n";
    $sum_flaeche+= (float) $v["flaeche"];
    $sum_ma+= (int) $v["ma"];
  }
  $wz = ($wz != "wz1") ? "wz1" : "wz2";
  $sum_qm_per_ma = ($sum_ma>0) ? number_format($sum_flaeche/$sum_ma,2,",", ".") : "-,--";  
  $op.= "<tr class=\"$wz\"><td class=\"sum\">Sum</td><td class=\"sum float\">".number_format($sum_flaeche,2,",",".")."</td><td class=\"sum int\">".$sum_ma."</td><td class=\"sum float\">".$sum_qm_per_ma."</td></tr>\n";
}

$op.= "</tbody>\n";
$op.= "</table>\n";

$op.= "</div>\n";
$op.= "</div>\n";

$body_content.= $op;
//$body_content.= "<pre>".print_r($GEFlaechen, 1)."</pre>\n";

?>
