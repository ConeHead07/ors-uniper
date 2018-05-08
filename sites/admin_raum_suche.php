<?php 
$op = ""; // Ausgabebuffer
if (basename($_SERVER["PHP_SELF"])==basename(__FILE__)) require_once("../header.php");
$queryForm = file_get_contents($HtmlBaseDir."admin_raum_suche.html");
$queryForm = str_replace("{s}", $s, $queryForm);
require_once($InclBaseDir."parse_userquery.php");

$searchFields = array(
	"ort" =>"Düsseldorf",
	"gebaeude" =>"ZV_SEE1",
	"etage" =>"1.OG",
	"raumnr" =>"1.OG",
	"raum_kategorie" =>"01-01",
	"raum_typ" =>"PV",
	"Belegung" =>"PV"
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
if (!empty($q["leereRaeume"])) $queryForm = str_replace("check_leereRaeume=\"1\"", "checked=\"true\"", $queryForm);
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
				case "raum_typ":
				case "raum_kategorie":
				$dbField = "i.".$qField;
				$aUQueryParts = userquery_parse($userQuery);
				$sqlWhereRaum.= ($sqlWhereRaum?"AND ":"")." (";
				$sqlWhereRaum.= userquery_parts2sql($aUQueryParts, $dbField, "End");
				$sqlWhereRaum.= ")\n";
				break;
				
				default:
				$dbField;
			}
			
		}
	}
}
if (!empty($q["leereRaeume"])) {
	$sqlWhereRaum.= ($sqlWhereRaum?" AND ":"")." m.id IS NULL";
}

$sql = "Select COUNT(DISTINCT(m.id)) count\n";
$sql.= "FROM `".$_TABLE["immobilien"]."` i LEFT JOIN `".$_TABLE["mitarbeiter"]."` m ON (m.immobilien_raum_id=i.id) \n";
$sql.= "WHERE 1\n";
if ($sqlWhereRaum) $sql.= " AND ".$sqlWhereRaum."\n";
$row = $db->query_singlerow($sql);
$sum_belegung = $row["count"];
//echo "#".__LINE__." ".basename(__FILE__)." err:".$db->error()." sql:".$sql."<br>\n";

$sql = "Select SUM(raum_flaeche) count\n";
if (empty($q["leereRaeume"]))
	$sql.= "FROM `".$_TABLE["immobilien"]."` i \n";
else
	$sql.= "FROM `".$_TABLE["immobilien"]."` i LEFT JOIN `".$_TABLE["mitarbeiter"]."` m ON (m.immobilien_raum_id=i.id) \n";
$sql.= "WHERE 1\n";
if ($sqlWhereRaum) $sql.= " AND ".$sqlWhereRaum."\n";
$row = $db->query_singlerow($sql);
$sum_flaeche = $row["count"];
//echo "#".__LINE__." ".basename(__FILE__)." err:".$db->error()." sql:".$sql."<br>\n";

$sql = "Select COUNT(DISTINCT(i.id)) count\n";
$sql.= "FROM `".$_TABLE["immobilien"]."` i LEFT JOIN `".$_TABLE["mitarbeiter"]."` m ON (m.immobilien_raum_id=i.id) \n";
$sql.= "WHERE 1\n";
if ($sqlWhereRaum) $sql.= " AND ".$sqlWhereRaum."\n";
$row = $db->query_singlerow($sql);
//echo "#".__LINE__." ".basename(__FILE__)." err:".$db->error()." sql:".$sql."<br>\n";
$num_all = $row["count"];

$sql = "Select i.*, COUNT(DISTINCT(m.id)) Belegung\n";
$sql.= "FROM `".$_TABLE["immobilien"]."` i LEFT JOIN `".$_TABLE["mitarbeiter"]."` m ON (m.immobilien_raum_id=i.id) \n";
$sql.= "WHERE 1\n";
if ($sqlWhereRaum) $sql.= " AND ".$sqlWhereRaum."\n";
$sql.= "GROUP BY i.id \n";
$sql.= $orderBy."\n";
$sql.= "LIMIT $offset, $limit";
$rows = $db->query_rows($sql);
$num = count($rows);
//echo "#".__LINE__." ".basename(__FILE__)." err:".$db->error()." sql:".$sql."<br>\n";

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
	
	$rows2Tbl = $rlist_nav->get_nav("all")." <strong>SUMME: ".$sum_belegung."MA, ".$sum_flaeche."qm</strong> <br>\n"; 
	//if ($db->error()) 
	//$rows2Tbl.= $db->error()."<br>\nsql:".$sql."<br>\n";
	$wz = "";
	$rows2Tbl.= "<table class=\"tblList\" border=1 cellpadding=1 cellspacing=0>\n";
	$rows2Tbl.= "<thead>\n";
	$rows2Tbl.= "<td>#</td>";
	if ($showEditLink) $rows2Tbl.= "<td colspan=1>Aktion</td>";
	foreach($rows[0] as $fld => $v) {
		if ($fld!="id") $rows2Tbl.= "<td><a href=\"".$ListBaseLink."&ofld=$fld&odir=".listbrowser::get_oDir($fld, $ofld, $odir)."\">".$fld."</a></td>";
	}
	$rows2Tbl.= "</thead>\n";
	$rows2Tbl.= "<tbody>\n";
	
	for($i = 0; $i < count($rows); $i++) {
		$wz = ($wz!=1)?1:2;
		$rows2Tbl.= "<tr class=\"wz$wz\">";
		$rows2Tbl.= "<td>".($offset+$i+1)."</td>";
		if ($showEditLink) $rows2Tbl.= "<td><a href=\"?s=Bestandsaufnahme&raum=".urlencode($rows[$i]["id"])."\">Raum anzeigen</a></td>";
		foreach($rows[$i] as $k => $v) if ($k!="id") $rows2Tbl.= "<td>$v</td>";
		$rows2Tbl.= "</tr>\n";
	}
	$rows2Tbl.= "</tbody>\n";
	$rows2Tbl.= "</table>";
	$op.= "<br>\n".$rows2Tbl;
} else {#
	$op.= "Ihre Suche ergab keine Treffer!<br>\n";
}

$body_content.= "<div class=\"divModuleBasic padding6px width5Col heightAuto colorContentMain\"> 
<h1><span class=\"spanTitle\">Raumsuche</span></h1> 
<p>
<div id=\"Umzugsantrag\" class=\"divInlay\">\n";
$body_content.= $op;
$body_content.= "</div>\n";
$body_content.= "</div>\n";
?>