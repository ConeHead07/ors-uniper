<?php 
$op = ""; // Ausgabepuffer
if (basename($_SERVER["PHP_SELF"])==basename(__FILE__)) require_once("../header.php");
$queryForm = file_get_contents($HtmlBaseDir."admin_ma_suche.html");
$queryForm = str_replace("{s}", $s, $queryForm);
require_once($InclBaseDir."parse_userquery.php");

$searchFields = array(
	"ort" =>"Düsseldorf",
	"gebaeude" =>"ZV_SEE1",
	"etage" =>"1.OG",
	"raumnr" =>"01-01",
	"gf" =>"PV",
	"bereich" =>"PV",
	"abteilung" =>"PV",
	"name" =>"Barthold",
	"vorname" =>"Frank",
	"nutzung" =>"Extern",
	"extern_firma" =>"mertens",
	"ersthelfer" =>"",
	"raeumungsbeauftragter" =>""
);
foreach($searchFields as $k => $v) $searchFields[$k] = "";

$defaultOrder = "ORDER BY gebaeude, etage, raumnr, name, vorname";
$q = getRequest("q");
$offset = getRequest("offset", 0);
$limit = getRequest("limit", 50);
$ofld = getRequest("ofld", "");
$odir = getRequest("odir", "ASC");
$trackGetQuery = "";

if (!empty($q["raumnr"])) {
	$t = explode(",", $q["raumnr"]);
	foreach($t as $k => $v) if (trim($t[$k]) && $t[$k][0]!=="\"") $t[$k] = "\"".trim($t[$k])."\"";
	$q["raumnr"] = implode(",",$t);
}

if ($ofld && isset($searchFields[$ofld])) {
	$orderBy = "ORDER BY ".$ofld." ".($odir!="DESC" ? "ASC" : "DESC");
} else {
	$orderBy = $defaultOrder;
}

foreach($searchFields as $f => $v) {
	if (isset($q[$f])) $searchFields[$f] = $q[$f];
	$queryForm = str_replace("{"."q[$f]"."}", fb_htmlEntities($searchFields[$f]), $queryForm);
	if ($searchFields[$f]) $trackGetQuery.= "&q[$f]=".urlencode($searchFields[$f]);
}
$ListBaseLink = "?s=$s".$trackGetQuery;


$sqlWhereMa = "";
$sqlWhereRaum = "";
if (!empty($q)) {
	foreach($searchFields as $qField => $userQuery) {
		if ($userQuery) {
			
			switch($qField) {
				case "ort":
				case "gebaeude":
				case "etage":
				case "raumnr":
				$dbField = "im.".$qField;
				$aUQueryParts = userquery_parse($userQuery);
				$sqlWhereRaum.= ($sqlWhereRaum?"AND ":"")." (";
				$sqlWhereRaum.= userquery_parts2sql($aUQueryParts, $dbField, "End");
				$sqlWhereRaum.= ")\n";
				break;
				
				case "nutzung":
				case "name":
				case "vorname":
				case "extern_firma":
				case "gf":
				case "bereich":
				case "abteilung":
				$dbField = "m.".($qField!="nutzung" ? $qField : "extern");
				$sqlWhereMa.= ($sqlWhereMa?"AND ":"")." (";
				$aUQueryParts = userquery_parse($userQuery);
				$sqlWhereMa.= userquery_parts2sql($aUQueryParts, $dbField, "End");
				$sqlWhereMa.= ")\n";
				break;
				
				case "abteilung":
				$dbField = $qField;
				break;
				
				default:
				$dbField;
			}
			
		}
	}
}

$sql = "Select COUNT(*) count\n";
$sql.= "FROM `".$_TABLE["mitarbeiter"]."` m LEFT JOIN `".$_TABLE["immobilien"]."` i ON (m.immobilien_raum_id=i.id) \n";
$sql.= "WHERE 1\n";
if ($sqlWhereMa) $sql.= " AND ".$sqlWhereMa."\n";
if ($sqlWhereRaum) $sql.= "AND m.immobilien_raum_id IN(SELECT id FROM `".$_TABLE["immobilien"]."` im WHERE $sqlWhereRaum)\n";
$row = $db->query_singlerow($sql);
$num_all = $row["count"];

$sql = "Select m.name, m.vorname, i.id raumid, i.gebaeude, i.etage, i.raumnr, m.gf, m.bereich, m.abteilung, m.extern as nutzung, m.extern_firma,m.ersthelfer, m.raeumungsbeauftragter\n";
$sql.= "FROM `".$_TABLE["mitarbeiter"]."` m LEFT JOIN `".$_TABLE["immobilien"]."` i ON (m.immobilien_raum_id=i.id) \n";
$sql.= "WHERE 1\n";
if ($sqlWhereMa) $sql.= " AND ".$sqlWhereMa."\n";
if ($sqlWhereRaum) $sql.= "AND m.immobilien_raum_id IN(SELECT id FROM `".$_TABLE["immobilien"]."` im WHERE $sqlWhereRaum)\n";
$sql.= $orderBy."\n";
$sql.= "LIMIT $offset, $limit";
//echo "#".__LINE__." ".basename(__FILE__)." sql:".$sql."<br>\n";

$rows = $db->query_rows($sql);
$num = count($rows);
//echo "<pre>".print_r($rows,1)."</pre>\n";

$showEditLink = (strpos($user["gruppe"], "admin")!==false || ($user["gruppe"]=="kunde_report" && $user["rechte"]>=3));

$op.= $queryForm;

if (count($rows)) {
	$rlist_nav = new listbrowser(array(
		"offset"     => $offset,
		"limit"      => $limit,
		"num_result" => $num,
		"num_all"    => $num_all,
		"baselink"   => $ListBaseLink."&offset={offset}&limit={limit}&ofld=$ofld&odir=$odir"));
	$rlist_nav->render_browser();
	
	$rows2Tbl = $rlist_nav->get_nav("all")."<br>\n"; 
	//if ($db->error()) 
	//$rows2Tbl.= $db->error()."<br>\nsql:".$sql."<br>\n";
	$wz = "";
	$rows2Tbl.= "<table class=\"tblList\" border=1 cellpadding=1 cellspacing=0>\n";
	$rows2Tbl.= "<thead>\n<tr>\n";
	$rows2Tbl.= "<td>#</td>";
	if ($showEditLink) $rows2Tbl.= "<td colspan=1>Aktion</td>";
	foreach($rows[0] as $fld => $v) {
		if ($fld!="raumid") $rows2Tbl.= "<td><a href=\"".$ListBaseLink."&ofld=$fld&odir=".listbrowser::get_oDir($fld, $ofld, $odir)."\">".$fld."</a></td>";
	}
	$rows2Tbl.= "</tr>\n</thead>\n";
	$rows2Tbl.= "<tbody>\n";
	
	for($i = 0; $i < count($rows); $i++) {
		$wz = ($wz!=1)?1:2;
		$rows2Tbl.= "<tr class=\"wz$wz\">";
		$rows2Tbl.= "<td>".($offset+$i+1)."</td>";
		if ($showEditLink) $rows2Tbl.= "<td><a href=\"?s=Bestandsaufnahme&raum=".urlencode($rows[$i]["raumid"])."\">Raum anzeigen</a></td>";
		foreach($rows[$i] as $k => $v) if ($k!="raumid") $rows2Tbl.= "<td>$v</td>";
		$rows2Tbl.= "</tr>\n";
	}
	$rows2Tbl.= "</tbody>\n";
	$rows2Tbl.= "</table>";
	$op.= "<br>\n".$rows2Tbl;
} else {#
	$op.= "Ihre Suche ergab keine Treffer!<br>\n";
}

$body_content.= "<div class=\"divModuleBasic padding6px width5Col heightAuto colorContentMain\"> 
<h1><span class=\"spanTitle\">Miarbeitersuche</span></h1> 
<p>
<div id=\"Umzugsantrag\" class=\"divInlay\">\n";
$body_content.= $op;
$body_content.= "</div>\n";
$body_content.= "</div>\n";
?>