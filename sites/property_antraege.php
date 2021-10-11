<?php 

if (strpos($user["gruppe"], "kunde_report") === false && strpos($user["adminmode"], "superadmin") === false)
	die("UNERLAUBTER ZUGRIFF!");


require_once($InclBaseDir."umzugsantrag.inc.php");
require_once($InclBaseDir."umzugsmitarbeiter.inc.php");
$CUA = &$_CONF["umzugsantrag"];
$CUM = &$_CONF["umzugsmitarbeiter"];
$Tpl = new myTplEngine();
$Umzuege = array();

if (empty($s)) $s = getRequest("s", "");

$offset = getRequest("offset", 0);
$limit = getRequest("limit", 50);
$ofld = getRequest("ofld", "");
$odir = getRequest("odir", "");
$cat = getRequest("cat", "neue");
$allusers = (int)  getRequest('allusers', 1);

if (empty($s)) $s = getRequest("s", "");
if (!in_array($cat, array("neue", "bearbeitung", "gepruefte", "genehmigte", "aktive", "abgelehnte", "abgeschlossene", "stornierte"))) $cat = "neue";
//if ($cat == 'bearbeitung') $cat = 'neue';

$defaultOrder = "ORDER BY antragsdatum ASC";
$orderFields = array(
	"id" => array("field"=>"U.aid", "defaultOrder"=>"ASC"),
	"termin" => array("field"=>"umzugstermin", "defaultOrder"=>"ASC"),
	"von" => array("field"=>"M.gebaeude", "defaultOrder"=>"ASC"),
	"nach" => array("field"=>"M.ziel_gebaeude", "defaultOrder"=>"ASC"),
	"ort" => array("field"=>"ort", "defaultOrder"=>"ASC"),
	"umzug" => array("field"=>"umzug", "defaultOrder"=>"ASC"),
	"mitarbeiter" => array("field"=>"mitarbeiter_num", "defaultOrder"=>"ASC"),
	"antragsdatum" => array("field"=>"antragsdatum", "defaultOrder"=>"ASC"),
	"geprueft" => array("field"=>"geprueft", "defaultOrder"=>"ASC"),
	"genehmigt" => array("field"=>"genehmigt_br", "defaultOrder"=>"ASC"),
	"bestaetigt" => array("field"=>"bestaetigt", "defaultOrder"=>"ASC"),
	"abgeschlossen" => array("field"=>"abgeschlossen", "defaultOrder"=>"ASC"),
);
if ($ofld && isset($orderFields[$ofld])) {
	$orderBy = "ORDER BY ".$orderFields[$ofld]["field"]." ";
	$orderBy.= ($odir) ? ($odir!="DESC" ? "ASC" : "DESC") : $orderFields[$ofld]["defaultOrder"];
} else {
	$orderBy = $defaultOrder;
}
$ListBaseLink = "?s=".urlencode($s)."&cat=".urlencode($cat).($allusers ? '&allusers=1' : '');

$user["uid"];


$sqlFrom= "FROM `".$CUA["Table"]."` U LEFT JOIN `".$CUM["Table"]."` M USING(aid)\n"
           ." LEFT JOIN mm_stamm_gebaeude g  ON U.gebaeude = g.id \n"
           ." LEFT JOIN mm_stamm_gebaeude vg ON U.von_gebaeude_id = vg.id \n"
           ." LEFT JOIN mm_stamm_gebaeude ng ON U.nach_gebaeude_id = ng.id \n"
           ." LEFT JOIN mm_user usr ON U.antragsteller_uid = usr.uid \n";
// 'temp', 
//'angeboten', 
//'beantragt', 
//'zurueckgegeben', 
//'geprueft', 
//'genehmigt', 
//'abgelehnt', 
//'bestaetigt', 
//'storniert', 
//'abgeschlossen'
 
switch($cat) {
    case 'neue':
        $sqlWhere= "WHERE (umzugsstatus = 'beantragt')\n";
        // $sqlWhere= "WHERE ((umzugsstatus = 'beantrag' and usr.gruppe NOT LIKE \"admin%\")\n";
        break;

	case "zurgenehmigung":
        case 'neue':
	$sqlWhere= "WHERE ((umzugsstatus = 'angeboten' OR "
                  ." (umzugsstatus = 'geprueft' AND umzug ='Ja')) "
                  ." and usr.gruppe NOT LIKE \"admin%\")\n";
	break;
        
	case "neue":
	if (0) $sqlWhere= "WHERE umzugsstatus IN ('geprueft','beantragt')\n";
	else $sqlWhere= "WHERE umzugsstatus = 'angeboten' OR (umzugsstatus = 'geprueft' AND umzug ='Ja')\n";
	break;
    
	case "gepruefte":
	$sqlWhere= "WHERE umzugsstatus = 'geprueft'\n";
	break;
    
	case "bearbeitungmertens":
        case 'bearbeitung':
	$sqlWhere= "WHERE ((umzugsstatus = 'angeboten' OR "
                  ." (umzugsstatus = 'geprueft' AND umzug ='Ja')) "
                  ." and usr.gruppe LIKE \"admin%\")\n";
	break;
        
	case "bearbeitungmertens":
        case 'bearbeitung':
	$sqlWhere= "WHERE (umzugsstatus IN ('beantragt','zurueckgegeben','erneutpruefen')"
                  ." and usr.gruppe LIKE \"admin_%\")\n";
	break;
    
	case "bearbeitung":
	$sqlWhere= "WHERE umzugsstatus = 'angeboten' AND umzug ='Ja'\n";
	break;
    
	case "bearbeitung":
	$sqlWhere= "WHERE umzugsstatus IN ('angeboten')\n";
	break;
	
	case "genehmigte":
	$sqlWhere= "WHERE umzugsstatus = 'genehmigt'\n";
	break;
    
	case "abgelehnte":
	$sqlWhere= "WHERE umzugsstatus = 'abgelehnt'\n";
	break;
    
	case "stornierte":
	$sqlWhere= "WHERE (abgeschlossen = 'Storniert' OR umzugsstatus = 'storniert')\n";
	break;
	
	case "aktive":
	$sqlWhere= "WHERE (umzugsstatus IN ('bestaetigt','genehmigt','geprueft') OR (umzug='Nein' AND umzugsstatus='angeboten'))\n";
	break;
	
	case "abgeschlossene":
	$sqlWhere= "WHERE (umzugsstatus = 'abgeschlossen' AND abgeschlossen = 'Ja')\n";
	break;
    
        default:
            echo 'cat. ' . $cat . '<br>' . PHP_EOL;
}

if (!$allusers) {
    $sqlWhere.= 'AND ('
             .'        g.regionalmanager_uid = ' . (int)$user['uid'] 
             .'     OR g.standortmanager_uid = ' . (int)$user['uid']
             .'     OR U.antragsteller_uid   = ' . (int)$user['uid'] . ') ';
}

$sql = "SELECT COUNT(DISTINCT(U.aid)) count \n";
$sql.= $sqlFrom.$sqlWhere;
//$sql.= "GROUP BY aid\n";
$row = $db->query_singlerow($sql);
// echo $sql . '<br>' . PHP_EOL . $db->error();
$num_all = $row["count"];



$sql = 'SELECT U.*, CONCAT(vg.stadtname, " ", vg.adresse) gebaeude, CONCAT(ng.stadtname, " ", ng.adresse) ziel_gebaeude' ."\n";
$sql.= $sqlFrom.$sqlWhere;
$sql.= "GROUP BY aid\n";
$sql.= $orderBy."\n";
$sql.= "LIMIT $offset, $limit";
$all = $db->query_rows($sql);
//echo $db->error()."<br>\nsql: $sql <br>\n";
$num = count($all);
// die('<pre>' . print_r(compact('sql', 'num' ), 1));

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
if(0) {
    $ListBrowsing = "<div style='border:1px solid gray;border-radius: 5px;padding:.8rem;'>
<pre style='background-color: #c9c9c9;color: #626262;padding:.8rem;'>" . $sql . "</pre>Num-Result: " . count($all) . "</div>" . $ListBrowsing;
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
		case "WARNUNG": return "<img src=\"images/warning_triangle.png\" width=\"16\" height=\"16\" alt=\"".fb_htmlEntities($alt)."\">";
	}
	return "<span class=\"status".$statVal."\" title=\"".fb_htmlEntities($alt)."\">$statVal</span>";
}}

if ( is_array($all) ) foreach($all as $i => $item) {
	$Umzuege[$i] = $item;
	
	$Umzuege[$i]["LinkOpen"] = "?s=pantrag&id=".$item["aid"];
	$Umzuege[$i]["Mitarbeiter"] = $item["mitarbeiter_num"];
	$Umzuege[$i]["Von"] = ($item["gebaeude"]?$item["gebaeude"]:"&nbsp;");
	$Umzuege[$i]["Nach"] = ($item["ziel_gebaeude"]?$item["ziel_gebaeude"]:"&nbsp;");
	$Umzuege[$i]["Antragsstatus"] =  $item["antragsstatus"];
	$Umzuege[$i]["Termin"] = ($item["umzugstermin"]?$item["umzugstermin"]:$item["terminwunsch"]);
	$Umzuege[$i]["Antragsdatum"] = $item["antragsdatum"];
	
	
	$Umzuege[$i]["Geprueft"]      = get_iconStatus($item["geprueft"], $item["geprueft_am"], $item["geprueft_von"], 'Geprueft');
	$Umzuege[$i]["Genehmigt"]     = get_iconStatus($item["genehmigt_br"], $item["genehmigt_br_am"], $item["genehmigt_br_von"]);
	$Umzuege[$i]["Bestaetigt"]    = get_iconStatus($item["bestaetigt"], $item["bestaetigt_am"], $item["bestaetigt_von"]);
	$Umzuege[$i]["Abgeschlossen"] = get_iconStatus($item["abgeschlossen"], $item["abgeschlossen_am"], $item["abgeschlossen_von"]);
}

$Tpl->assign("s", $s);
$Tpl->assign("cat", $cat);
$Tpl->assign("allusers", $allusers);
$Tpl->assign("ListBrowsing", $ListBrowsing);
$Tpl->assign("ListBaseLink", $ListBaseLink);
$Tpl->assign("ofld", $ofld);
$Tpl->assign("odir", $odir);
$Tpl->assign("num_all", $num_all);
$Tpl->assign("propertyName", $propertyName);

$Tpl->assign("Umzuege", $Umzuege);
$body_content.= $Tpl->fetch("property_antraege_liste.html");
//$body_content.= "<pre>".print_r($all,1)."</pre>\n";
