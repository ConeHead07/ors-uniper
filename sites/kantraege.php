<?php 

require_once($InclBaseDir."umzugsantrag.inc.php");
require_once($InclBaseDir."umzugsmitarbeiter.inc.php");
$ASConf = &$_CONF["umzugsantrag"];
$MAConf = &$_CONF["umzugsmitarbeiter"];
$Tpl = new myTplEngine();
$Umzuege = array();

$offset = getRequest("offset", 0);
$limit = getRequest("limit", 50);
$ofld = getRequest("ofld", "");
$odir = getRequest("odir", "");
$cat = getRequest("cat", "bearbeitung");
$allusers = (int)getRequest('allusers', 0);

$isMH = (preg_match('/admin|umzugsteam/', $user["gruppe"]));

if (empty($s)) $s = getRequest("s", "");
if (!in_array($cat, array("bearbeitung", "zurueckgegeben", "gesendet", "genehmigt", "aktiv", "geschlossen"))) $cat = "bearbeitung";

$defaultOrder = "ORDER BY antragsdatum ASC";
$orderFields = array(
	"id" => array("field"=>"U.aid", "defaultOrder"=>"ASC"),
	"termin" => array("field"=>"umzugstermin", "defaultOrder"=>"ASC"),
	"von" => array("field"=>"M.gebaeude", "defaultOrder"=>"ASC"),
	"nach" => array("field"=>"M.ziel_gebaeude", "defaultOrder"=>"ASC"),
	"ort" => array("field"=>"ort", "defaultOrder"=>"ASC"),
	"umzug" => array("field"=>"umzug", "defaultOrder"=>"ASC"),
	"mitarbeiter" => array("field"=>"umzug", "defaultOrder"=>"ASC"),
	"umzugsarten" => array("field"=>"U.bearbeiter_bemerkung", "defaultOrder"=>"ASC"),
	"antragsdatum" => array("field"=>"antragsdatum", "defaultOrder"=>"ASC"),
	"geprueft" => array("field"=>"geprueft", "defaultOrder"=>"ASC"),
	"genehmigt" => array("field"=>"genehmigt_br", "defaultOrder"=>"ASC"),
	"bestaetigt" => array("field"=>"genehmigt_br", "defaultOrder"=>"ASC"),
	"abgeschlossen" => array("field"=>"abgeschlossen", "defaultOrder"=>"ASC"),
);
if ($ofld && isset($orderFields[$ofld])) {
	$orderBy = "ORDER BY ".$orderFields[$ofld]["field"]." ";
	$orderBy.= ($odir) ? ($odir!="DESC" ? "ASC" : "DESC") : $orderFields[$ofld]["defaultOrder"];
} else {
	$orderBy = $defaultOrder;
}
$ListBaseLink = "?s=".urlencode($s)."&cat=".urlencode($cat);
$user["uid"];

$sql = 'SELECT U.*, CONCAT(vg.stadtname, " ", vg.adresse) gebaeude, CONCAT(ng.stadtname, " ", ng.adresse) ziel_gebaeude' . "\n";
$sqlFrom = "FROM `".$ASConf["Table"]."` U LEFT JOIN `".$MAConf["Table"]."` M USING(aid)\n" 
           ." LEFT JOIN mm_stamm_gebaeude g  ON U.gebaeude = g.id \n"
           ." LEFT JOIN mm_stamm_gebaeude vg ON U.von_gebaeude_id = vg.id \n"
           ." LEFT JOIN mm_stamm_gebaeude ng ON U.nach_gebaeude_id = ng.id \n";
$sqlWhere= "WHERE 1 \n";

if (!$allusers) {
    $sqlWhere.= 'AND ('
//             .'        g.regionalmanager_uid = ' . (int)$user['uid'] 
//             .'     OR g.standortmanager_uid = ' . (int)$user['uid'] OR 
             .'     U.antragsteller_uid   = ' . (int)$user['uid'] . ') ';
}

switch($cat) {
	case "bearbeitung":
	$sqlWhere.= " AND umzugsstatus IN (\"temp\", \"zurueckgegeben\")\n";
	$catTitle = "noch nicht gesendete";
	break;
	
	case "zurueckgegeben":
	$sqlWhere.= " AND umzugsstatus=\"".$db->escape($cat)."\" \n";
	$catTitle = "noch nicht gesendete";
	break;
	
	case "gesendet":
	$sqlWhere.= " AND umzugsstatus IN (\"beantragt\",\"angeboten\") \n";
	$catTitle = "gesendete";
	break;
	
	case "genehmigt":
	$sqlWhere.= " AND umzugsstatus IN (\"bestaetigt\",\"genehmigt\") AND (abgeschlossen IS NULL OR abgeschlossen=\"Init\")\n";
	$catTitle = "genehmigte";
	break;
	
	
	case "aktiv":
	$sqlWhere.= " AND umzugsstatus=\"genehmigt\" AND (abgeschlossen IS NULL OR abgeschlossen=\"Init\")\n";
	$catTitle = "genehmigte";
	break;
	
	case "geschlossen":
	$sqlWhere.= " AND umzugsstatus IN (\"abgeschlossen\",\"abgelehnt\",\"storniert\")\n";
	$catTitle = "abgeschlossene";
	break;
	
	default:
	$catTitle = "";
}

$sql = "SELECT COUNT(DISTINCT(U.aid)) count \n";
$sql.= $sqlFrom.$sqlWhere;
$sql.= "GROUP BY aid\n";
$row = $db->query_singlerow($sql);
if ($db->error()) {
    die($db->error() . '<br>' . $db->lastQuery);
}
$num_all = $row["count"];


$sql = 'SELECT U.*, CONCAT(vg.stadtname, " ", vg.adresse) gebaeude, CONCAT(ng.stadtname, " ", ng.adresse) ziel_gebaeude ' ."\n";
$sql.= $sqlFrom.$sqlWhere;
$sql.= "GROUP BY aid\n";
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

if (!function_exists("get_iconStatus")) { function get_iconStatus($statVal, $date, $von ='', $statKey ='') {
        $alt = '';
        $alt.= (strtotime($date) ? date('d.m H:i', strtotime($date)) : $date);
        if ($statKey) $alt.= ' ' . $statKey . '(' . $statVal . ')';
        if ($von) $alt.= ' von ' . $von;
	switch(strtoupper($statVal)) {
		case "JA": return "<img src=\"images/status_ja.png\" width=\"16\" height=\"16\" title=\"".fb_htmlEntities($alt)."\">";
		case "NEIN": return "<img src=\"images/status_nein.png\" width=\"16\" height=\"16\" title=\"".fb_htmlEntities($alt)."\">";
		case "INIT": return "<img src=\"images/status_init.png\" width=\"16\" height=\"16\" title=\"".fb_htmlEntities($statVal)."\">";
		case "STORNIERT": return "<img src=\"images/status_storniert.png\" width=\"16\" height=\"16\" title=\"".fb_htmlEntities($alt)."\">";
		case "WARNUNG": return "<img src=\"images/warning_triangle.png\" width=\"16\" height=\"16\" title=\"".fb_htmlEntities($alt)."\">";
	}
	return "<span class=\"status".$statVal."\" title=\"".fb_htmlEntities($alt)."\">$statVal</span>";
}}

if (is_array($all)) foreach($all as $i => $item) {
	$Umzuege[$i] = $item;
	
	$Umzuege[$i]["LinkOpen"] = "?s=kantrag&id=".$item["aid"];
	$Umzuege[$i]["Mitarbeiter"] = $item["mitarbeiter_num"];
    $Umzuege[$i]["Von"] = ($item["gebaeude"]?$item["gebaeude"]:"&nbsp;");
    $Umzuege[$i]["Von"] = ($item["gebaeude"]?$item["gebaeude"]:"&nbsp;");
	$Umzuege[$i]["Von"] = ($item["gebaeude"]?$item["gebaeude"]:"&nbsp;");
	$Umzuege[$i]["Nach"] = ($item["ziel_gebaeude"]?$item["ziel_gebaeude"]:"&nbsp;");
	$Umzuege[$i]["Antragsstatus"] =  $item["antragsstatus"];
	$Umzuege[$i]["Termin"] = ($item["umzugstermin"]?$item["umzugstermin"]:$item["terminwunsch"]);
	$Umzuege[$i]["Antragsdatum"] = $item["antragsdatum"];
	
        if ($Umzuege[$i]["umzugsstatus"]=="zurueckgegeben") {
            $Umzuege[$i]["Genehmigt"] = get_iconStatus("WARNUNG", $item["zurueckgegeben_am"], $item["zurueckgegeben_von"]);
        } else {
            $Umzuege[$i]["Genehmigt"]  = get_iconStatus($item["genehmigt_br"], $item["genehmigt_br_am"], $item["genehmigt_br_von"]);
        }
	
	$Umzuege[$i]["Abgeschlossen"] = get_iconStatus($item["abgeschlossen"], $item["abgeschlossen_am"], $item["abgeschlossen_von"]);
        
	$Umzuege[$i]["Geprueft"] = get_iconStatus($item["geprueft"], $item["geprueft_am"], $item["geprueft_von"]);
}
//die('<pre>' . print_r($Umzuege,1) . '</pre>');

$Tpl->assign("s", $s);
$Tpl->assign("cat", $cat);
$Tpl->assign("catTitle", $catTitle);
$Tpl->assign("ListBrowsing", $ListBrowsing);
$Tpl->assign("ListBaseLink", $ListBaseLink);
$Tpl->assign("ofld", $ofld);
$Tpl->assign("odir", $odir);
$Tpl->assign("Umzuege", $Umzuege);
$Tpl->assign("propertyName", $propertyName);
$Tpl->assign("isMH", $isMH);
$body_content.= $Tpl->fetch("kantraege_liste.html");
//$body_content.= "<pre>".print_r($all,1)."</pre>\n";

