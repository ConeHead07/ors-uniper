<?php 

if (strpos($user['gruppe'], 'admin') === false && $user['gruppe'] !== 'umzugsteam') {
    die("UNERLAUBTER ZUGRIFF!");
}


require_once( $InclBaseDir . "umzugsantrag.inc.php");
require_once( $InclBaseDir . "umzugsmitarbeiter.inc.php");
require_once( $ModulBaseDir . 'excelexport/helper_functions.php');

$CUA = &$_CONF['umzugsantrag'];
$CUM = &$_CONF['umzugsmitarbeiter'];
$Tpl = new myTplEngine();
$Auftraege = [];
$NL = "\n";

$exportFormat = 'html';


if (empty($s)) {
    $s = getRequest('s', "");
}

$userGruppe = $user['gruppe'];
$istUmzugsteam = $userGruppe === 'umzugsteam' || $s === 'auslieferung';

$offset = getRequest('offset', 0);
$limit = getRequest('limit', 100);
$ofld = getRequest('ofld', '');
$odir = getRequest('odir', '');
$cat = getRequest('cat', '');
$exportFormat = getRequest('format', 'html');
$query = (!empty($_REQUEST['q']))   ? $_REQUEST['q'] : [];
$allusers = (int)getRequest('allusers', 1);
// die(print_r(compact('query'), 1));

if (!$istUmzugsteam) {
    if (empty($cat) || !in_array($cat,
        [
            'temp', 'zurueckgegeben', 'angeboten', 'abgelehnte', 'neue', 'disponierte',
            'gepruefte', 'genehmigte', 'heute', 'aktive', 'abgeschlossene',
            'stornierte'
        ])) {
        $cat = 'neue';
    }
} else {
    if (empty($cat) || !in_array($cat,
        [ 'heute', 'aktive', 'abgeschlossene', ])) {
        $cat = 'heute';
    }
}

$defaultOrder = 'ORDER BY antragsdatum ASC';
$orderFields = array(
    'id' => array('field'=>"U.aid", 'defaultOrder'=>'ASC'),
    'aid' => array('field'=>"U.aid", 'defaultOrder'=>'ASC'),
    'kid' => array('field'=>"user.personalnr", 'defaultOrder'=>'ASC'),
	'termin' => array('field'=>'U.umzugstermin', 'defaultOrder'=>'ASC'),
	'von' => array('field'=>"M.gebaeude", 'defaultOrder'=>'ASC'),
	'nach' => array('field'=>"M.ziel_gebaeude", 'defaultOrder'=>'ASC'),
    'strasse' => array('field'=>'U.strasse', 'defaultOrder'=>'ASC'),
    'plz' => array('field'=>'U.plz', 'defaultOrder'=>'ASC'),
    'ort' => array('field'=>'U.ort', 'defaultOrder'=>'ASC'),
    'land' => array('field'=>'U.land', 'defaultOrder'=>'ASC'),
	'umzug' => array('field'=>'umzug', 'defaultOrder'=>'ASC'),
	'mitarbeiter' => array('field'=>'mitarbeiter_num', 'defaultOrder'=>'ASC'),
	'antragsdatum' => array('field'=>'U.antragsdatum', 'defaultOrder'=>'ASC'),
	'geprueft' => array('field'=>'U.geprueft_am', 'defaultOrder'=>'ASC'),
	'genehmigt' => array('field'=>'genehmigt_am', 'defaultOrder'=>'ASC'),
	'bestaetigt' => array('field'=>'bestaetigt_am', 'defaultOrder'=>'ASC'),
	'abgeschlossen' => array('field'=>'U.abgeschlossen_am', 'defaultOrder'=>'ASC'),
    'tour_kennung' =>  array('field'=>'tour_kennung', 'defaultOrder'=>'ASC'),
    'Leistungen' =>  array('field'=>'Leistungen', 'defaultOrder'=>'ASC'),
    'summe' =>  array('field'=>'Summe', 'defaultOrder'=>'ASC'),
);

$validFields = array_keys($orderFields);

$having = array();
$w = array();
foreach($validFields as $_f) {
    if (empty($query[$_f]) || trim($query[$_f]) === '') {
        continue;
    }
    $sqlQueryField = $orderFields[$_f]['field'];

    if (strcmp($_f, 'Leistungen') === 0 && !empty($query[$_f])) {
        $chars = str_split( trim($query[$_f]));
        $chars = preg_replace('#[^A-Z]#', '', $chars);

        foreach($chars as $_chr) {
            $having[] = 'GROUP_CONCAT(kategorie_abk) LIKE "%' . $_chr . '%"';
        }
        continue;
    }
    elseif (strcmp($_f, 'land') === 0 && !empty($query[$_f]) && strcmp(trim($query[$_f]), 'NL') === 0 ) {
        $w[] = $sqlQueryField . ' LIKE "Niederlande"';
        continue;
    }
    elseif (strcmp($_f, 'land') === 0 && !empty($query[$_f]) && strcmp(trim($query[$_f]), 'DE') === 0 ) {
        $w[] = $sqlQueryField . ' LIKE "Deutschland"';
        continue;
    }

    if (!empty($query[$_f])) {
        if (preg_match('/^([<>=]{1,2})(.+)$/', trim($query[$_f]), $m)) {
            $_q = $sqlQueryField . ' ' . $m[1] . $db->quote($m[2]);
        } else {
            $_q = $sqlQueryField . ' ' . ' LIKE ' . $db->quote( str_replace('*','%', $query[$_f]) . '%');
        }

        if (strcmp($_f, 'summe') !== 0) {
            $w[] = $_q;
        }
        else {
            $having[] = $_q;
        }
    }
}

if ($ofld && isset($orderFields[$ofld])) {
	$orderBy = 'ORDER BY ' . $orderFields[$ofld]['field'] . ' ';
	$orderBy.= ($odir) ? ($odir!='DESC' ? 'ASC' : 'DESC') : $orderFields[$ofld]['defaultOrder'];
} else {
	$orderBy = $defaultOrder;
}

$ListBaseLink = '?s='.urlencode($s).'&cat='.urlencode($cat).($allusers ? '&allusers=1' : '');

$sqlFrom  = 'FROM `' . $CUA['Table'] . '` U LEFT JOIN `' . $CUM['Table'] . '` M USING(aid)' . $NL
    . ' LEFT JOIN mm_user user ON U.antragsteller_uid = user.uid ' . $NL
    . ' LEFT JOIN mm_stamm_gebaeude g  ON U.gebaeude = g.id ' . $NL
    . ' LEFT JOIN mm_stamm_gebaeude vg ON U.von_gebaeude_id = vg.id ' . $NL
    . ' LEFT JOIN mm_stamm_gebaeude ng ON U.nach_gebaeude_id = ng.id ' . $NL
    . ' LEFT JOIN mm_umzuege_leistungen ul ON (U.aid = ul.aid) ' . $NL
    . ' LEFT JOIN mm_leistungskatalog l ON(ul.leistung_id = l.leistung_id) ' . $NL
    . ' LEFT JOIN mm_leistungskategorie lk ON(l.leistungskategorie_id = lk.leistungskategorie_id) ' . $NL
    . ' LEFT JOIN mm_leistungspreismatrix lm ON('  . $NL
    . '    l.leistung_id = lm.leistung_id '  . $NL
    . '    AND lm.mengen_von <= (ul.menge_mertens * IFNULL(ul.menge2_mertens,1)) ' . $NL
    . '    AND (lm.mengen_bis >= ( ul.menge_mertens * IFNULL(ul.menge2_mertens,1)))' . $NL
    . ' ) '  . $NL;
$sqlWhere = "WHERE 1\n";

if (!$allusers) {
    if ($user['gruppe']=='admin_standort') {
        $sqlWhere.= "AND (";
        $sqlWhere.= "    U.ort IN (\"".str_replace(",", "\",\"", $user['standortverwaltung'])."\")\n";
        $sqlWhere.= '    OR g.mertenshenk_uid = ' . (int)$user['uid'] . ' ';
        $sqlWhere.= '    OR U.antragsteller_uid = ' . (int)$user['uid'] . ' ';
        $sqlWhere.= ' )';
    } else {
        $sqlWhere.= 'AND (g.mertenshenk_uid = ' . (int)$user['uid'] . ' '
                   .'  OR U.antragsteller_uid = ' . (int)$user['uid'] . ') ';
    }
}


switch($cat) {
	case 'neue':
	$sqlWhere.= 'AND umzugsstatus IN ("angeboten", "beantragt", "erneutpruefen")' . $NL;
	break;
    
	case 'angeboten':
	$sqlWhere.= 'AND (umzugsstatus = "angeboten" or umzugsstatus="geprueft" AND umzug="Ja")' . $NL;
	break;
	
	case 'gepruefte':
	$sqlWhere.= 'AND umzugsstatus = "geprueft"' . $NL;
	break;
	
	case 'genehmigte':
	$sqlWhere.= 'AND umzugsstatus = "genehmigt"' . $NL;
	break;

    case 'heute':
        $sqlWhere.= 'AND DATE_FORMAT(umzugstermin, "%Y-%m-%d") = "' . date('Y-m-d') . '"';
        $sqlWhere.= 'AND (umzugsstatus IN ("geprueft", "bestaetigt","genehmigt") OR (umzug="Nein" AND umzugsstatus="angeboten"))' . $NL;
        break;

    case 'disponierte':
        $sqlWhere.= 'AND (umzugsstatus IN ("beantragt", "disponiert") AND IFNULL(tour_kennung, "") LIKE "_%")' . $NL;
        break;

    case 'aktive':
        $sqlWhere.= 'AND (umzugsstatus IN ("geprueft", "bestaetigt", "genehmigt") OR (umzug="Nein" AND umzugsstatus="angeboten"))' . $NL;
        break;
	
	case 'abgeschlossene':
	$sqlWhere.= 'AND (umzugsstatus = "abgeschlossen" AND abgeschlossen = "Ja")' . $NL;
	//$sqlWhere.= "OR (abgeschlossen !=  'Init' AND  abgeschlossen IS NOT NULL)) \n";
	break;
	
	case 'abgelehnte':
	$sqlWhere.= 'AND (umzugsstatus = "abgelehnt")' . $NL;
	break;
	
	case 'temp':
	$sqlWhere.= 'AND umzugsstatus IN ("temp", "zurueckgegeben")' . $NL;
	break;
	
	case 'zurueckgegeben':
	$sqlWhere.= 'AND umzugsstatus = "zurueckgegeben"' . $NL;
	break;
	
	case 'stornierte':
	$sqlWhere.= 'AND (abgeschlossen = "Storniert" OR umzugsstatus = "storniert")' . $NL;
	break;
}
if (count($w)) {
    $sqlWhere.= ' AND (' . implode(' AND ', $w) . ') ' . $NL;
}

$sqlLimit = "LIMIT $offset, $limit" . $NL;
$sqlGroup = ' GROUP BY U.aid' . $NL;
$sqlHaving = ( count($having) ? ' HAVING (' . implode(' AND ', $having) . ')' . $NL : '');


$sqlSelect = 'SELECT U.*, U.umzugstermin AS Lieferdatum, ' . $NL
    . ' user.personalnr AS kid, ' . $NL
    . ' CONCAT(vg.stadtname, " ", vg.adresse) von_gebaeude, ' . $NL
    . ' CONCAT(ng.stadtname, " ", ng.adresse) ziel_gebaeude, ' . $NL
    . ' REPLACE(REPLACE(GROUP_CONCAT(lk.kategorie_abk ORDER BY leistungskategorie SEPARATOR ""), "P", ""), "R", "") AS Leistungen, ' . $NL
    . ' GROUP_CONCAT(lk.leistungskategorie ORDER BY leistungskategorie SEPARATOR ", ") AS LeistungenFull, ' . $NL
    . ' SUM(if(lm.preis, lm.preis, preis_pro_einheit) * ul.menge_mertens * IFNULL(ul.menge2_mertens,1)) AS Summe' . $NL;

$sql = 'SELECT COUNT(1) AS `count` FROM (' . $sqlSelect . $sqlFrom . $sqlWhere . $sqlGroup . $sqlHaving . ') AS t';
$row = $db->query_singlerow($sql);
$num_all = $row['count'];

$sql = $sqlSelect . $sqlFrom . $sqlWhere;
$sql.= $sqlGroup . $NL;
$sql.= $sqlHaving . $NL;
$sql.= $orderBy . $NL;
if ($exportFormat !== 'html') {
    $sql .= $sqlLimit . $NL;
}

$all = $db->query_rows($sql);
// echo $db->error()."<pre>\nsql: $sql </pre>\n";
$num = count($all);

$sqlSummeTotal = 'SELECT SUM(summe) FROM ( ' . $sqlSelect . $sqlFrom . $sqlWhere . $sqlGroup . $sqlHaving . ') AS t';
$summeTotal = $db->query_one($sqlSummeTotal);

$sqlArtikel = 'SELECT lk.leistungskategorie AS Kategorie, l.leistung_id, l.Bezeichnung, l.Farbe, l.Groesse, '
    . ' COUNT(distinct(U.aid)) count, '
    . ' MAX(l.preis_pro_einheit) Preis, '
    . ' (l.preis_pro_einheit * COUNT(distinct(U.aid))) AS Summe, '
    . 'group_concat(ul.aid) aids' . $NL;
$sqlArtikel.= $sqlFrom . $NL . $sqlWhere . ' AND l.leistung_id IS NOT NULL' . $NL;
$sqlArtikel.= 'GROUP BY l.leistung_id, l.Bezeichnung, l.Farbe, l.Groesse' . $NL;
$artikelStat = $db->query_rows($sqlArtikel);

if ($exportFormat !== 'html' && count($all)) {

    $iNumItems = count($all);

    $aSelectCols = [
        'Summe', 'aid', 'kid', 'tour_kennung', 'service', 'plz', 'ort', 'strasse',
        'land', 'Leistungen', 'antragsdatum', 'Lieferdatum', 'tour_zugewiesen_am',
        'bestaetigt_am', 'abgeschlossen_am'
    ];

    $writer = new XLSXWriter();
    $writer->setAuthor('Frank Barthold, merTens AG');

    $sheet01Name = $cat . 'Auftraege';
    $sheet01Header = leistungsRowToSheetHeader($aSelectCols);
    // die('<pre>' . print_r(compact('sheet01Header'), 1));
    $writer->writeSheetHeader($sheet01Name , $sheet01Header);
    foreach($all as $_row) {
        $_export = [];
        $_styles = [];
        foreach($aSelectCols as $k) {
            $s = [];
            $v = isset($_row[$k]) ? $_row[$k] : '';
            if ($k === 'aid' && (int)$v > 0) {
                $_link = $MConf["WebRoot"] . "?s=aantrag&id=" . (int)$v;
                $_styles[] = [ 'color' => '#00F', 'font-style' => 'underline' ];
                $_export[] = '=HYPERLINK("' . $_link . '","' . (int)$v . '")';
                continue;
            }
            $_styles[] = $s;
            $_export[] = $v;
        }
        $writer->writeSheetRow($sheet01Name, $_export, $_styles);
    }

    $sheet02Name = 'KumulierteLeistungen';
    $sheet02Header = leistungsRowToSheetHeader($artikelStat[0] );
    $writer->writeSheetHeader($sheet02Name, $sheet02Header);
    foreach($artikelStat as $_row) {
        $writer->writeSheetRow($sheet02Name, $_row);
    }

    header('Content-Type: application/xls');
    header('Content-Disposition: attachment; filename="' . $cat . 'Auftraege_ExportVom' . date('YmdHi') . '.xlsx"');
    $writer->writeToStdOut();

    exit;
}

if ($num_all > $num) {
	$rlist_nav = new listbrowser(array(
		'offset'     => $offset,
		'limit'      => $limit,
		'num_result' => $num,
		'num_all'    => $num_all,
		'baselink'   => $ListBaseLink."&offset={offset}&limit={limit}&ofld=$ofld&odir=$odir"));
	$rlist_nav->render_browser();
	$ListBrowsing = $rlist_nav->get_nav('all')."<br>\n";
} else {
	$ListBrowsing = ""; 
}
$showSQL = false;
if ($showSQL) {
    $ListBrowsing = "<div style='border:1px solid gray;border-radius: 5px;padding:.8rem;'>
<pre style='background-color: #c9c9c9;color: #626262;padding:.8rem;'>" . $sql . "</pre>Num-Result: " . count($all) . "</div>" . $ListBrowsing;
}

if (!function_exists('get_iconStatus')) { 
    function get_iconStatus($statVal, $date, $von ='', $statKey ='') {
        $alt = '';
        $alt.= (strtotime($date) ? date('d.m H:i', strtotime($date)) : $date);
        if ($statKey) $alt.= ' ' . $statKey . '(' . $statVal . ')';
        if ($von) $alt.= ' von ' . $von;

        switch(strtoupper($statVal)) {
            case 'JA': return '"<img src="images/status_ja.png" width="16" height="16" title="' . fb_htmlEntities($alt) . '">';
            case 'NEIN': return '"<img src="images/status_nein.png" width="16" height="16" title="' . fb_htmlEntities($alt) . '">';
            case 'INIT': return '"<img src="images/status_init.png" width="16" height="16" title="' . fb_htmlEntities($statVal) . '">';
            case 'STORNIERT': return '"<img src="images/status_storniert.png" width="16" height="16" title="' . fb_htmlEntities($alt) . '">';
            case 'WARNUNG': return '"<img src="images/warning_triangle.png" width="16" height="16" alt="' . fb_htmlEntities($alt) . '">';
        }
        return '<span class="status' . $statVal.'" title="'.fb_htmlEntities($alt).'">' . $statVal . '</span>';
}}


//echo MyDB::error()."<br>$sql<br> num_rows:".count($all).":".print_r($all,1)."\n";
if (is_array($all)) {
    foreach($all as $i => $item) {
        $Auftraege[$i] = $item;

        $Auftraege[$i]['LinkOpen'] = '?s=aantrag' . '&id=' . $item['aid'];
        if ($istUmzugsteam) {
            $Auftraege[$i]['LinkOpen'] = '?s=aantrag' . '&id=' . $item['aid'] . '&top=' . $s;
        }
        $Auftraege[$i]['Mitarbeiter'] = $item['mitarbeiter_num'];
        $Auftraege[$i]['plz'] = $item['plz'];
        $Auftraege[$i]['Von'] = $item['gebaeude'];
        $Auftraege[$i]['Nach'] = $item['ziel_gebaeude'];
        $Auftraege[$i]['Antragsstatus'] =  $item['antragsstatus'];
        $Auftraege[$i]['Termin'] = ($item['umzugstermin']?$item['umzugstermin']:$item['terminwunsch']);
        $Auftraege[$i]['Antragsdatum'] = $item['antragsdatum'];

        if ($exportFormat === 'html') {
            $Auftraege[$i]['Avisiert'] = get_iconStatus($item['bestaetigt'], $item['geprueft_am'], $item['geprueft_von'], 'Avisiert');
            $Auftraege[$i]['Geprueft'] = get_iconStatus($item['geprueft'], $item['geprueft_am'], $item['geprueft_von'], 'Geprueft');
            $Auftraege[$i]['Genehmigt'] = get_iconStatus($item['genehmigt_br'], $item['genehmigt_br_am'], $item['genehmigt_br_von']);
            $Auftraege[$i]['Bestaetigt'] = get_iconStatus($item['bestaetigt'], $item['bestaetigt_am'], $item['bestaetigt_von']);
            $Auftraege[$i]['Abgeschlossen'] = get_iconStatus($item['abgeschlossen'], $item['abgeschlossen_am'], $item['abgeschlossen_von']);
        }
    }
}

if ($exportFormat === 'csv' && count($Auftraege)) {
    $iNumItems = count($Auftraege);
    $tmpfname = tempnam(sys_get_temp_dir(), "csv");
    $csvSeparator = ';';
    $csvEnclosure = '"';
    $csvEscape = '"';
    $csvEOL = "\n";

    $fh = fopen($tmpfname, 'w');

    $cols = array_keys($Auftraege[0]);

    fwrite($fh, "\xEF\xBB\xBF");
    fputcsv($fh, $cols, $csvSeparator, $csvEnclosure, $csvEscape);
    for($i = 0; $i < $iNumItems; $i++) {
        $Auftraege[$i]['summe'] = round($Auftraege[$i]['summe'], 2);
        $vals = array_map(function($val) {
            $val = trim($val);
            if (strlen($val) > 1) {
                $firstChr = $val[0];
                if (strpos('+-', $firstChr) !== false || preg_match('#^[+-]?[0-9]+[eE][0-9]+#', $val)) {
                    return "'" . $val;
                }
            }
            return $val;
        }, $Auftraege[$i]);
        fputcsv($fh, $vals, $csvSeparator, $csvEnclosure, $csvEscape);
    }
    fclose($fh);

    header('Content-Type: text/csv; charset="' . $charset . '"');
    header('Content-Disposition: attachment; filename="' . $cat . '_' . date('YmdHi'). '.csv"');
    header('Content-Length: ' . filesize($tmpfname));

    readfile($tmpfname);
    unlink($tmpfname);
    exit;
}


$Tpl->assign('s', $s);
$Tpl->assign('cat', $cat);
$Tpl->assign('q', $query);
$Tpl->assign('allusers', $allusers);
$Tpl->assign('ListBrowsing', $ListBrowsing);
$Tpl->assign('ListBaseLink', $ListBaseLink);
$Tpl->assign('ofld', $ofld);
$Tpl->assign('odir', $odir);
$Tpl->assign('num_all', $num_all);
$Tpl->assign('summeTotal', $summeTotal);
$Tpl->assign('artikelStat', $artikelStat);

$Tpl->assign('Umzuege', $Auftraege);

//echo '<pre>#' . __LINE__ . ' '; // . print_r( filestat('html/antraege_liste.html'),1);
try {
    if (!$istUmzugsteam) {
        $body_content .= $Tpl->fetch("admin_antraege_liste.html");
    } else {
        $body_content .= $Tpl->fetch("umzugsteam_antraege_liste.html");
    }
} catch(Exception $e) {
	echo $e->getMessage();
}
//$body_content.= "<pre>".print_r($all,1)."</pre>\n";


