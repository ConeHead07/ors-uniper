<?php 

if (strpos($user["gruppe"], "kunde_report") === false && strpos($user["adminmode"], "superadmin") === false)
	die('UNERLAUBTER ZUGRIFF! Zugriff nur für ' . $MConf['propertyName'] . ' Property');

$confName = "nebenleistungen";
require_once($InclBaseDir.$confName.".inc.php");
$NLConf = $_CONF[$confName];
$Tpl = new myTplEngine();
$Umzuege = array();

$offset = getRequest("offset", 0);
$limit = getRequest("limit", 50);
$ofld = getRequest("ofld", "");
$odir = getRequest("odir", "");
$cat = getRequest("cat", "bearbeitung");
if (empty($s)) $s = getRequest("s", "");
if (!in_array($cat, array("Neu", "Beauftragt","Abgelehnt","Abgeschlossen"))) $cat = "Neu";

$defaultOrder = "ORDER BY created ASC";
$orderFields = array(
	"id" => array("field"=>"id", "defaultOrder"=>"ASC"),
	"created" => array("field"=>"created", "defaultOrder"=>"ASC"),
	"datum" => array("field"=>"datum", "defaultOrder"=>"ASC"),
	"uhrzeit" => array("field"=>"uhrzeit", "defaultOrder"=>"ASC"),
	"kostenstelle" => array("field"=>"kostenstelle", "defaultOrder"=>"ASC"),
	"aufgabe" => array("field"=>"aufgabe", "defaultOrder"=>"ASC")
);
if ($ofld && isset($orderFields[$ofld])) {
	$orderBy = "ORDER BY ".$orderFields[$ofld]["field"]." ";
	$orderBy.= ($odir) ? ($odir!="DESC" ? "ASC" : "DESC") : $orderFields[$ofld]["defaultOrder"];
} else {
	$orderBy = $defaultOrder;
}
$ListBaseLink = "?s=".urlencode($s)."&cat=".urlencode($cat);
$user["uid"];

$sql = "SELECT * \n";
$sqlFrom= "FROM `".$NLConf["Table"]."`\n";
$sqlWhere = "WHERE 1 \n";
switch($cat) {
	case "Neu":
	$sqlWhere.= " AND status=\"$cat\" \n";
	$catTitle = "gesendete";
	break;
	
	case "Beauftragt":
	$sqlWhere.= " AND status=\"$cat\" \n";
	$catTitle = "beauftragte";
	break;
	
	case "Abgelehnt":
	$sqlWhere.= " AND status=\"$cat\" \n";
	$catTitle = "abgelehnte";
	break;
	
	case "Abgeschlossen":
	$sqlWhere.= " AND status=\"$cat\" \n";
	$catTitle = "abgeschlossene";
	break;
	
	default:
	$catTitle = "";
}

$sql = "SELECT COUNT(*) count \n";
$sql.= $sqlFrom.$sqlWhere;
$row = $db->query_singlerow($sql);
$num_all = $row["count"];
//echo $db->error()."<br>\nsql: $sql <br>\n";


$sql = "SELECT * \n";
$sql.= $sqlFrom.$sqlWhere;
$sql.= $orderBy;
$all = $db->query_rows($sql);
//echo $db->error()."<br>\nsql: $sql <br>\n";
$num = count($all);

if ($num_all > $num) {
	$rlist_nav = new listbrowser(array(
		"offset"     => $offset,
		"limit"      => $limit,
		"num_result" => $num,
		"num_all"    => $num_all,
		"baselink"   => $ListBaseLink."&offset={offset}&limit={limit}&ofld=$ofld&odir=$odir"));
	$rlist_nav->render_browser();
	$ListBrowsing = $rlist_nav->get_nav("all")."<br>\n";
} else {
	$ListBrowsing = ""; 
}


if (!function_exists("get_iconStatusfunction")) { function get_iconStatus($stat, $alt) {
	switch(strtoupper($stat)) {
		case "JA": return "<img src=\"images/status_ja.png\" width=\"16\" height=\"16\" alt=\"".fb_htmlEntities($alt)."\">";
		case "NEIN": return "<img src=\"images/status_nein.png\" width=\"16\" height=\"16\" alt=\"".fb_htmlEntities($alt)."\">";
		case "INIT": return "<img src=\"images/status_init.png\" width=\"16\" height=\"16\" alt=\"".fb_htmlEntities($alt)."\">";
		case "STORNIERT": return "<img src=\"images/status_storniert.png\" width=\"16\" height=\"16\" alt=\"".fb_htmlEntities($alt)."\">";
	}
	return "<span class=\"status".$stat."\" title=\"".fb_htmlEntities($alt)."\">$stat</span>";
}}

if (is_array($all)) foreach($all as $i => $item) {
	$Umzuege[$i] = $item;
	
	$Umzuege[$i]["LinkOpen"] = "?s=pnebenleistung&id=".$item["id"];
	
	$Umzuege[$i]["Erledigt"] = get_iconStatus($item["erledigt"], $item["erledigt_am"]);
}

$Tpl->assign("s", $s);
$Tpl->assign("cat", $cat);
$Tpl->assign("catTitle", $catTitle);
$Tpl->assign("ListBrowsing", $ListBrowsing);
$Tpl->assign("ListBaseLink", $ListBaseLink);
$Tpl->assign("ofld", $ofld);
$Tpl->assign("odir", $odir);
$Tpl->assign("Umzuege", $Umzuege);
$body_content.= $Tpl->fetch("property_nebenleisungen_liste.html");
//$body_content.= "<pre>".print_r($all,1)."</pre>\n";

?>
