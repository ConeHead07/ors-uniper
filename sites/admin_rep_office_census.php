<?php 
require(dirname(__FILE__)."/../header.php");
//require(dirname(__FILE__)."/umzugsantrag_stdlib.php");

$conf_gebaeude_file = dirname(__FILE__)."/../textfiles/conf.gebaeude_oc.cnf";
$CNF_GP = array();
if (file_exists($conf_gebaeude_file)) {
	$CNF_GP = conf_load($conf_gebaeude_file);
}

$aValidGP = array();
foreach($CNF_GP as $k => $v) {
	if ($v == "J") $aValidGP[] = $k;
}

$sql = "SELECT `id` , `organisationseinheit` gf, `name`
FROM `mm_stamm_gf`
ORDER BY id ASC";
$aGF = $db->query_rows($sql);
if ($db->error()) $error.= "#".__LINE__." ".$db->error()."<br/>$sql<br/>\n";

$sFilter = "";
$filter = getRequest("filter","");
$sFilter = (substr($filter,0,2)=="s:") ? substr($filter,2) : "";
$gFilter = (substr($filter,0,2)=="g:") ? substr($filter,2) : "";
$ShowAll = 0;
if($sFilter == "") {
	if ($gFilter=="") {
		$ShowAll = 1;
	}
}

//$msg.= "<pre>".print_r($aValidGP,1)."</pre>\n";

$sql = "SELECT `gebaeude`, `stadtname`, `adresse`
FROM `mm_stamm_gebaeude`
WHERE `gebaeude` IN ('".implode("','", $aValidGP)."')
GROUP BY `gebaeude`
ORDER BY `gebaeude` ASC";


$Nutzung = array(
	"Staff" => "Interne MA", 
	"Extern" => "Externe MA", 
	"Flex-Position" => "Flex-AP", 
	"Spare" => "Spare-AP"
);

$gf_nutzung_count = array();
foreach($Nutzung as $k => $v) $gf_nutzung_count[$k] = 0;

$nutzung_sum = array();
foreach($Nutzung as $k => $v) $nutzung_sum[$k] = 0;

$nutzung_sum_all = array();
foreach($Nutzung as $k => $v) $nutzung_sum_all[$k] = 0;

$gf_nutzung_sum_all = array();
for($i = 0; $i < count($aGF); $i++)
{
	foreach($Nutzung as $k => $v)
		$gf_nutzung_sum_all[$aGF[$i]["gf"]][$k] = 0;
}
$geb_nutzung_count_all = array();
foreach($Nutzung as $k => $v) $geb_nutzung_count_all[$k] = 0;

$Gebaeude = array();
$APCount = array();
$gebaeude = "";
$options = "";
$lastS = "";
$pageTitle = "Office Census Deutschland";
$sGSumTbl = "";

//$msg.= "#".__LINE__." $sql<br/>\n";
$rows = $db->query_rows($sql);
if ($db->error()) $error.= "#".__LINE__." ".$db->error()."<br/>$sql<br/>\n";
if (!$db->error()) {
	foreach($rows as $v) {
	  $stadt = $v["stadtname"];
	  $gebaeude = $v["gebaeude"];
	  $Gebaeude[$gebaeude] = array("stadt" => $v["stadtname"], "adresse" => $v["adresse"]);
	  if ($lastS != $stadt) {
	   	$selected = (!empty($sFilter) && $sFilter == $stadt) ? "selected=\"true\"" : "";
	   	if ($lastS) $options.="</optgroup>";
	   	$options.= "<optgroup label=\"$stadt\">\n";
	   	$options.= "<option value=\"s:$stadt\" $selected >Alle Gebaeude von $stadt</option>\n";
	   	if ($selected) $pageTitle.= ": ".$stadt;
	  }
	  $selected = (!empty($gFilter) && $gFilter == $Gebaeude) ? "selected=\"true\"" : "";
	  $options.= "<option value=\"g:$gebaeude\" $selected >".$gebaeude. ": ".$v["adresse"]."</option>\n";
	  if ($selected) $pageTitle.= ": ".$gebaeude;
	   
	   
	  $lastS = $stadt;
	}
	if ($lastS) $options.="</optgroup>";
} else {
	$error.= $db->error()."<br/>\n";
}


$sql = "SELECT i.`gebaeude`, m.`gf`, `extern`, COUNT(*) Anzahl
FROM `mm_stamm_mitarbeiter` m LEFT JOIN `mm_stamm_immobilien` i 
ON(m.immobilien_raum_id = i.id)
WHERE i.`gebaeude` IN ('".implode("','", $aValidGP)."')\n";
if ($sFilter) $sql.= "AND  i.`gebaeude` IN (SELECT gebaeude FROM `mm_stamm_gebaeude` WHERE stadtname LIKE \"".$sFilter."\")\n";
elseif ($gFilter) $sql.= "AND i.gebaeude LIKE \"".$gFilter."\"\n";
$sql.= "GROUP BY i.`gebaeude`, m.`gf`, `extern`
ORDER BY i.`gebaeude`, m.`gf`, `extern`";

//$msg.= $sql."<br>\n";
$rows = $db->query_rows($sql);
if ($db->error()) $error.= "#".__LINE__." ".$db->error()."<br/>$sql<br/>\n";
if (!$db->error()) {
  foreach($rows as $v) {
    $APCount[$v["gebaeude"]][$v["gf"]][$v["extern"]] = $v["Anzahl"];
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

//$APCount[$gebaeude][$gf][$nutzung] = $count;

$page = 0;
$sGList = "";
foreach($APCount as $gebaeude => $GFProps) {
  if (empty($GFProps)) continue;
  $page++;
  
  foreach($gf_nutzung_count as $k => $v) $gf_nutzung_count[$k] = 0;
  foreach($nutzung_sum as $k => $v) $nutzung_sum[$k] = 0;
  
  $wz = "";
  $SumTotal = 0;
  $n = 0;
  $geb_stat_head = "";
  $geb_stat_sum = "";
  $geb_stat_list = "";
  
  for ($i = 0; $i < count($aGF); $i++) {
  	$SumGF = 0;
  	$gf = $aGF[$i]["gf"];
  	$wz = ($wz != "wz1") ? "wz1" : "wz2";
  	
  	$geb_stat_list.= "<tr class=\"$wz\">\n";
  	$geb_stat_list.= "\t<td style=\"padding:10px 3px 10px 3px;\">".$aGF[$i]["name"]."</td>\n";
  	$geb_stat_list.= "<td>".$aGF[$i]["gf"]."</td>\n";
  
  	foreach($Nutzung as $nutzung => $nTitle)  {
  		$n = (!empty($GFProps[$gf]) && !empty($GFProps[$gf][$nutzung])) ? (int)$GFProps[$gf][$nutzung] : 0;
  		$gf_nutzung_count[$nutzung]+= $n;
  		$geb_nutzung_count_all[$nutzung]+= $n;
  		$SumGF+= $n;
  		$geb_stat_list.= "<td class=\"int\">".$n."</td>\n";
  		$gf_nutzung_sum_all[$gf][$nutzung]+= $n;
  		//echo "\$GFProps[".$gf."][".$nutzung."] = ".print_r($GFProps[$gf][$nutzung],1)."</pre>\n";
  	}
  	
  	$SumTotal+= $SumGF;
  	$geb_stat_list.= "<td class=\"int sum\">".$SumGF."</td>\n";
  	$geb_stat_list.= "</tr>\n";
  }
  
  $geb_stat_head = "<tr>\n";
  $geb_stat_head.= "\t<td style=\"padding:10px 3px 10px 3px;\">Gesch&auml;ftsfelder</td>\n";
  $geb_stat_head.= "<td>&nbsp;</td>\n";

  foreach($Nutzung as $nutzung => $nTitle)  {
  	$geb_stat_head.= "<td>".$nTitle."</td>\n";
  }
  $geb_stat_head.= "<td>Alle MA/AP</td>\n";
  $geb_stat_head.= "</tr>\n";
  
  $geb_stat_sum = "<tr class=\"$wz\">\n";
  $geb_stat_sum.= "\t<td style=\"padding:10px 3px 10px 3px;\" class=\"lbl\">Total</td>\n";
  $geb_stat_sum.= "<td>&nbsp;</td>\n";

  foreach($Nutzung as $nutzung => $nTitle)  {
  	$geb_stat_sum.= "<td class=\"int sum\">".$gf_nutzung_count[$nutzung]."</td>\n";
  }
  $geb_stat_sum.= "<td class=\"int sum\">".$SumTotal."</td>\n";
  $geb_stat_sum.= "</tr>\n";
  
  if ($ShowAll || $page>1) $sGList.= "<div style=\"page-break-before:always;\">&nbsp;</div>";
  $sGList.= "<h2 class=\"NoPageBreakAfterThis\" style=\"margin-bottom:0;\">".$gebaeude."</h2>\n".$Gebaeude[$gebaeude]["adresse"]."\n";
  $sGList.= "<table class=\"tblList\" style=\"width:600px;page-break-inside:avoid;margin-bottom:20px;\">\n";
  $sGList.= "<thead>\n";
  $sGList.= $geb_stat_head;
  $sGList.= "</thead>\n";
  $sGList.= "<tbody>\n";
  $sGList.= $geb_stat_sum;
  $sGList.= $geb_stat_list;
  $sGList.= "</tbody>\n";
  $sGList.= "</table>\n";
}

if ($ShowAll) {
  //echo "#".__LINE__." ShowAll<br/>\n";
  $wz = "";
  $SumTotal = 0;
  $n = 0;
  $geb_stat_head = "";
  $geb_stat_sum = "";
  $geb_stat_list = "";
  
  for ($i = 0; $i < count($aGF); $i++) {
  	//echo "#".__LINE__." ShowAll; aGF[$i][gf]:".$aGF[$i]["gf"]."<br/>\n";
  	$SumGF = 0;
  	$gf = $aGF[$i]["gf"];
  	$wz = ($wz != "wz1") ? "wz1" : "wz2";
  	
  	$geb_stat_list.= "<tr class=\"$wz\">\n";
  	$geb_stat_list.= "\t<td style=\"padding:10px 3px 10px 3px;\">".$aGF[$i]["name"]."</td>\n";
  	$geb_stat_list.= "<td>".$aGF[$i]["gf"]."</td>\n";
  
  	foreach($Nutzung as $nutzung => $nTitle)  {
  		//echo "#".__LINE__." ShowAll; gf_nutzung_sum_all[".$gf."][".$nutzung."]:".$gf_nutzung_sum_all[$gf][$nutzung]."<br/>\n";
  		$n = $gf_nutzung_sum_all[$gf][$nutzung];
  		$SumGF+= $n;
  		$geb_stat_list.= "<td class=\"int\">".$n."</td>\n";
  		//echo "\$GFProps[".$gf."][".$nutzung."] = ".print_r($GFProps[$gf][$nutzung],1)."</pre>\n";
  	}
  	
  	$SumTotal+= $SumGF;
  	$geb_stat_list.= "<td class=\"int sum\">".$SumGF."</td>\n";
  	$geb_stat_list.= "</tr>\n";
  }
  
  $geb_stat_head = "<tr>\n";
  $geb_stat_head.= "\t<td style=\"padding:10px 3px 10px 3px;\">Gesch&auml;ftsfelder</td>\n";
  $geb_stat_head.= "<td>&nbsp;</td>\n";

  foreach($Nutzung as $nutzung => $nTitle)  {
  	$geb_stat_head.= "<td class=\"int sum\">".$nTitle."</td>\n";
  }
  $geb_stat_head.= "<td>Alle MA/AP</td>\n";
  $geb_stat_head.= "</tr>\n";
  
  $geb_stat_sum = "<tr class=\"$wz\">\n";
  $geb_stat_sum.= "\t<td style=\"padding:10px 3px 10px 3px;\" class=\"lbl\">Total</td>\n";
  $geb_stat_sum.= "<td>&nbsp;</td>\n";

  foreach($Nutzung as $nutzung => $nTitle)  {
  	$geb_stat_sum.= "<td class=\"int sum\">".$geb_nutzung_count_all[$nutzung]."</td>\n";
  }
  $geb_stat_sum.= "<td class=\"int sum\">".$SumTotal."</td>\n";
  $geb_stat_sum.= "</tr>\n";
  
  $sGSumTbl = "<h2>Alle Geb&auml;ude</h2>\n";
  $sGSumTbl.= "<table class=\"tblList\" style=\"width:600px;\">\n";
  $sGSumTbl.= "<thead>\n";
  $sGSumTbl.= $geb_stat_head;
  $sGSumTbl.= "</thead>\n";
  $sGSumTbl.= "<tbody>\n";
  $sGSumTbl.= $geb_stat_sum;
  $sGSumTbl.= $geb_stat_list;
  $sGSumTbl.= "</tbody>\n";
  $sGSumTbl.= "</table>\n";
}

$op.= $sGSumTbl;
$op.= $sGList;
$op.= "</div>\n";
$op.= "</div>\n";

$body_content.= "<link rel=\"stylesheet\" media=\"print\" href=\"css/tablelisting.print.css\" type=\"text/css\"/>\n";
$body_content.= "<style type=\"text/css\">\n";
$body_content.= "table.tblList * td { padding:60px 0 60px 0; }\n";
//$body_content.= "\t@page { size:portrait; }\n";
$body_content.= "</style>\n";

$body_content.= $op;
//$body_content.= "<pre>".print_r($GEFlaechen, 1)."</pre>\n";

?>