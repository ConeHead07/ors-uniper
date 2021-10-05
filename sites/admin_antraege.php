<?php 

if (strpos($user["gruppe"], "admin") === false) die("UNERLAUBTER ZUGRIFF!");

require_once($InclBaseDir."umzugsantrag.inc.php");
require_once($InclBaseDir."umzugsmitarbeiter.inc.php");
$CUA = &$_CONF["umzugsantrag"];
$CUM = &$_CONF["umzugsmitarbeiter"];
$Tpl = new myTplEngine();
$Umzuege = array();

$offset = getRequest("offset", 0);
$limit = getRequest("limit", 100);
$ofld = getRequest("ofld", "");
$odir = getRequest("odir", "");
$cat = getRequest("cat", "neue");
$allusers = (int)getRequest("allusers", 1);

if (empty($s)) $s = getRequest("s", "");
if (!in_array($cat, array('temp','zurueckgegeben','angeboten','abgelehnte','neue', 'gepruefte', 'genehmigte', 'aktive', 'abgeschlossene', 'stornierte'))) $cat = 'neue';

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

$sqlFrom  = "FROM `".$CUA["Table"]."` U LEFT JOIN `".$CUM["Table"]."` M USING(aid)\n" 
           ." LEFT JOIN mm_stamm_gebaeude g  ON U.gebaeude = g.id \n"
           ." LEFT JOIN mm_stamm_gebaeude vg ON U.von_gebaeude_id = vg.id \n"
           ." LEFT JOIN mm_stamm_gebaeude ng ON U.nach_gebaeude_id = ng.id \n";
$sqlWhere = "WHERE 1\n";


if (!$allusers) {
    if ($user["gruppe"]=="admin_standort") {
        $sqlWhere.= "AND (";
        $sqlWhere.= "    U.ort IN (\"".str_replace(",", "\",\"", $user["standortverwaltung"])."\")\n";
        $sqlWhere.= '    OR g.mertenshenk_uid = ' . (int)$user['uid'] . ' ';
        $sqlWhere.= '    OR U.antragsteller_uid = ' . (int)$user['uid'] . ' ';
        $sqlWhere.= ' )';
    } else {
        $sqlWhere.= 'AND (g.mertenshenk_uid = ' . (int)$user['uid'] . ' '
                   .'  OR U.antragsteller_uid = ' . (int)$user['uid'] . ') ';
    }
}

switch($cat) {
	case "neue":
	$sqlWhere.= "AND umzugsstatus IN ('beantragt', 'erneutpruefen')\n";
	break;
    
	case "angeboten":
	$sqlWhere.= "AND (umzugsstatus = 'angeboten' or umzugsstatus='geprueft' AND umzug='Ja')\n";
	break;
	
	case "gepruefte":
	$sqlWhere.= "AND umzugsstatus = 'geprueft'\n";
	break;
	
	case "genehmigte":
	$sqlWhere.= "AND umzugsstatus = 'genehmigt'\n";
	break;
	
	case "aktive":
	$sqlWhere.= "AND (umzugsstatus IN ('geprueft', 'bestaetigt','genehmigt') OR (umzug='Nein' AND umzugsstatus='angeboten'))\n";
	break;
	
	case "abgeschlossene":
	$sqlWhere.= "AND (umzugsstatus = 'abgeschlossen' AND abgeschlossen = 'Ja')\n";
	//$sqlWhere.= "OR (abgeschlossen !=  'Init' AND  abgeschlossen IS NOT NULL)) \n";
	break;
	
	case "abgelehnte":
	$sqlWhere.= "AND (umzugsstatus = 'abgelehnt')\n";
	break;
	
	case "temp":
	$sqlWhere.= "AND umzugsstatus IN ('temp','zurueckgegeben')\n";
	break;
	
	case "zurueckgegeben":
	$sqlWhere.= "AND umzugsstatus = 'zurueckgegeben'\n";
	break;
	
	case "stornierte":
	$sqlWhere.= "AND (abgeschlossen = 'Storniert' OR umzugsstatus = 'storniert')\n";
	break;
}

$sql = "SELECT COUNT(distinct(U.aid)) count \n";
$sql.= $sqlFrom.$sqlWhere;
//$sql.= "GROUP BY aid\n";
$row = $db->query_singlerow($sql);
//echo $db->error()."<br>\nsql: $sql <br>\n";
$num_all = $row["count"];

$sql = 'SELECT U.*, CONCAT(vg.stadtname, " ", vg.adresse) gebaeude, CONCAT(ng.stadtname, " ", ng.adresse) ziel_gebaeude ' . PHP_EOL;
$sql.= $sqlFrom.$sqlWhere;
$sql.= "GROUP BY aid\n";
$sql.= $orderBy."\n";
$sql.= "LIMIT $offset, $limit";
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
	$ListBrowsing = $rlist_nav->get_nav("all")." num:$num num_all:$num_all<br>\n";
} else {
	$ListBrowsing = ""; 
}
$ListBrowsing = "<div style='border:1px solid gray;border-radius: 5px;padding:.8rem;'>
<pre style='background-color: #c9c9c9;color: #626262;padding:.8rem;'>" . $sql . "</pre>Num-Result: " . count($all) . "</div>" . $ListBrowsing;

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


//echo MyDB::error()."<br>$sql<br> num_rows:".count($all).":".print_r($all,1)."\n";
if (is_array($all)) foreach($all as $i => $item) {
	$Umzuege[$i] = $item;
	
	$Umzuege[$i]["LinkOpen"] = "?s=aantrag"."&id=".$item["aid"];
	$Umzuege[$i]["Mitarbeiter"] = $item["mitarbeiter_num"];
    $Umzuege[$i]["plz"] = $item["plz"]."&nbsp;";
	$Umzuege[$i]["Von"] = $item["gebaeude"]."&nbsp;";
	$Umzuege[$i]["Nach"] = $item["ziel_gebaeude"]."&nbsp;";
	$Umzuege[$i]["Antragsstatus"] =  $item["antragsstatus"];
	$Umzuege[$i]["Termin"] = ($item["umzugstermin"]?$item["umzugstermin"]:$item["terminwunsch"]);
	$Umzuege[$i]["Antragsdatum"] = $item["antragsdatum"];
	
	$Umzuege[$i]["Geprueft"]   = (!$item["geprueft_am"]) ? get_iconStatus($item["geprueft"], $item["geprueft_am"]) : '';
	$Umzuege[$i]["Geprueft"]   = get_iconStatus($item["geprueft"], $item["geprueft_am"], $item["geprueft_von"], 'Geprueft');
	$Umzuege[$i]["Genehmigt"]  = ($item['umzug'] == 'Ja') ? get_iconStatus($item["genehmigt_br"], $item["genehmigt_br_am"]) : '';
	$Umzuege[$i]["Genehmigt"]  = get_iconStatus($item["genehmigt_br"], $item["genehmigt_br_am"], $item["genehmigt_br_von"]);
	$Umzuege[$i]["Bestaetigt"] = (empty($item['angeboten_am']) ? get_iconStatus($item["genehmigt_br"], $item["genehmigt_br_am"]) : '');
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

$Tpl->assign("Umzuege", $Umzuege);

//echo '<pre>#' . __LINE__ . ' '; // . print_r( filestat('html/antraege_liste.html'),1);
try {
	$body_content.= $Tpl->fetch("admin_antraege_liste.html");
} catch(Exception $e) {
	echo $e->getMessage();
}
//$body_content.= "<pre>".print_r($all,1)."</pre>\n";


