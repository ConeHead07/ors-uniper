<?php 

if (strpos($user['gruppe'], 'admin') === false && $user['gruppe'] !== 'umzugsteam') {
    die("UNERLAUBTER ZUGRIFF!");
}


$batchCmd = getRequest('batchCmd', '');
$aids = getRequest('aids', []);
if ($batchCmd = 'erinnerungsmail') {
    if (is_array($aids) && count($aids) > 0) {
        $batchErinnern = new \module\Auftragsbearbeitung\BatchErinnerungsmails();
        $batchErinnern->setAuftragsIds($aids);
        $batchErinnern->run();
    }
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
$istAdmin = $userGruppe === 'admin';
$istSuperAdmin = $istAdmin && $user['adminmode'] === 'superadmin';

$offset = getRequest('offset', 0);
$limit = getRequest('limit', 100);
$ofld = getRequest('ofld', '');
$odir = getRequest('odir', '');
$cat = getRequest('cat', '');
$top = getRequest('top', '');
$exportFormat = getRequest('format', 'html');
$query = (!empty($_REQUEST['q']))   ? $_REQUEST['q'] : [];
$allusers = (int)getRequest('allusers', 1);
// die(print_r(compact('query'), 1));

$datumvon = !empty($_REQUEST['datumvon'])   ? $_REQUEST['datumvon'] : '';
$datumbis = !empty($_REQUEST['datumbis'])  ? $_REQUEST['datumbis'] : '';
$datumfeld = !empty($_REQUEST['datumfeld']) ? $_REQUEST['datumfeld'] : 'antragsdatum';

$aValidRangeDateFields = [
    'angeboten_am',
    'antragsdatum', 'temp_erinnerungsmail_am', 'umzugstermin', 'bestaetigt_am',
    'abgeschlossen_am', 'berechnet_am',
];

if ($datumfeld && !in_array($datumfeld, $aValidRangeDateFields)) {
    $datumfeld = '';
}

if ($datumvon & strtotime($datumvon)) {
    $datumvon = date('Y-m-d', strtotime($datumvon));
}
if ($datumbis & strtotime($datumbis)) {
    $datumbis = date('Y-m-d', strtotime($datumbis));
}

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
    'service' => array('field'=>'service', 'defaultOrder'=>'ASC'),
	'mitarbeiter' => array('field'=>'mitarbeiter_num', 'defaultOrder'=>'ASC'),
	'antragsdatum' => array('field'=>'U.antragsdatum', 'defaultOrder'=>'ASC'),
	'geprueft' => array('field'=>'U.geprueft_am', 'defaultOrder'=>'ASC'),
	'genehmigt' => array('field'=>'genehmigt_am', 'defaultOrder'=>'ASC'),
	'bestaetigt' => array('field'=>'bestaetigt_am', 'defaultOrder'=>'ASC'),
	'abgeschlossen' => array('field'=>'U.abgeschlossen_am', 'defaultOrder'=>'ASC'),
    'tour_kennung' =>  array('field'=>'tour_kennung', 'defaultOrder'=>'ASC'),
    'Leistungen' =>  array('field'=>'Leistungen', 'defaultOrder'=>'ASC'),
    'neue_bemerkungen_fuer_admin' => array('field' => 'neue_bemerkungen_fuer_admin', 'defaultOrder'=>'DESC'),
    'summe' =>  array('field'=>'Summe', 'defaultOrder'=>'ASC'),
    'numAuftraege' =>  array('field'=>'numAuftraege', 'defaultOrder'=>'DESC'),
);

$validFields = array_keys($orderFields);

$having = array();
$w = array();
foreach($validFields as $_f) {
    if (empty($query[$_f]) || trim($query[$_f]) === '') {
        continue;
    }
    $sqlQueryField = $orderFields[$_f]['field'];
    $query[$_f] = trim($query[$_f] ?? '');
    $_qf = $query[$_f];

    if (strcmp($_f, 'Leistungen') === 0 && strlen($_qf)) {
        if (preg_match('[ ,]', $_qf)) {
            $chars = preg_split('[ ,]', $_qf);
        } else {
            $chars = str_split($_qf);
        }
        $chars = array_filter($chars, function($v) { return strlen(trim($v)) > 0; });

        foreach($chars as $_chr) {
            $having[] = 'GROUP_CONCAT(CONCAT(kategorie_abk, IF(IFNULL(leistung_abk,"")="", "", CONCAT("", leistung_abk, "")))) LIKE "%' . $_chr . '%"';
        }
        continue;
    }
    elseif (strcmp($_f, 'land') === 0 && !empty($query[$_f]) && strcmp(trim($query[$_f]), 'NL') === 0 ) {
        $w[] = $sqlQueryField . ' LIKE "Niederlande"';
        continue;
    }
    elseif (strcmp($_f, 'land') === 0 && !empty($query[$_f]) && strcmp(trim($query[$_f]), 'EN') === 0 ) {
        $w[] = $sqlQueryField . ' LIKE "England"';
        continue;
    }
    elseif (strcmp($_f, 'land') === 0 && !empty($query[$_f]) && strcmp(trim($query[$_f]), 'DE') === 0 ) {
        $w[] = $sqlQueryField . ' LIKE "Deutschland"';
        continue;
    }
    elseif (strcmp($_f, 'land') === 0 && !empty($query[$_f]) && strcmp(trim($query[$_f]), 'BE') === 0 ) {
        $w[] = $sqlQueryField . ' LIKE "Belgien"';
        continue;
    }

    if (!empty($query[$_f])) {
//        if (preg_match('/^([<>=]{1,2})(.+)$/', trim($query[$_f]), $m)) {
//            $_q = $sqlQueryField . ' ' . $m[1] . $db->quote($m[2]);
//        } else {
//            $_q = $sqlQueryField . ' ' . ' LIKE ' . $db->quote( str_replace('*','%', $query[$_f]) . '%');
//        }

        if (preg_match('#^(' . preg_quote('!=', '#') .')(.*)$#', trim($query[$_f]), $m)) {
            $_q = 'IFNULL(' . $sqlQueryField . ',"") ' . $m[1] . $db->quote($m[2]);
        }
        elseif (preg_match('/^(!)(.+)$/', trim($query[$_f]), $m)) {
            $_q = 'IFNULL(' . $sqlQueryField . ',"") NOT LIKE ' . $db->quote( str_replace('*','%', $m[2]) . '%');
        }
        elseif (preg_match('/^([<>=]{1,2})(.*)$/', trim($query[$_f]), $m)) {
            $_q = 'IFNULL(' . $sqlQueryField . ',"") ' . $m[1] . $db->quote($m[2]);
        } else {
            $_q = 'IFNULL(' . $sqlQueryField . ',"") LIKE ' . $db->quote( str_replace('*','%', $query[$_f]) . '%');
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
    if ($cat === 'heute') {
        $orderBy = 'ORDER BY umzugszeit ASC';
    } elseif($cat === 'disponierte') {
        $orderBy = 'ORDER BY umzugstermin ASC, umzugszeit ASC';
    } elseif($cat === 'aktive') {
        $orderBy = 'ORDER BY umzugstermin DESC, umzugszeit ASC';
    } elseif($cat === 'abgeschlossene') {
        $orderBy = 'ORDER BY abgeschlossen_am DESC';
    } else {
        $orderBy = $defaultOrder;
    }
}

$ListBaseLink = '?s='.urlencode($s).'&cat='.urlencode($cat).($allusers ? '&allusers=1' : '');

'SELECT 
  a.aid,
  usr.personalnr kid,
  DATE_FORMAT(a.antragsdatum, "%d.%m.%Y") beantragt,
  DATE_FORMAT(a.bestaetigt_am, "%d.%m.%Y") bestaetigt,
  a.umzugsstatus, 
  DATE_FORMAT(a.umzugsstatus_vom, "%d.%m.%Y") statusdatum, 
  GROUP_CONCAT(ktg.kategorie_abk SEPARATOR "") Lstg, 
  ROUND(SUM(klg.preis_pro_einheit * al.menge_mertens),2) Summe  
  FROM mm_umzuege a 
  LEFT JOIN mm_umzuege_leistungen al ON (a.aid = al.aid)
  LEFT JOIN mm_user usr ON (a.antragsteller_uid = usr.uid)
  LEFT JOIN mm_leistungskatalog klg ON (al.leistung_id = klg.leistung_id)
  LEFT JOIN mm_leistungskategorie ktg ON (klg.leistungskategorie_id = ktg.leistungskategorie_id)
  GROUP BY a.aid, a.antragsdatum, a.bestaetigt_am, a.umzugsstatus_vom;
';

'SELECT 
  a.antragsteller_uid, usr.personalnr kid,
  COUNT(DISTINCT(a.aid)) AS numAuftraege,
  GROUP_CONCAT( CONCAT_WS(" ", CONCAT("#", a.aid), umzugsstatus, "am", DATE_FORMAT(umzugsstatus_vom, "%d.%m.%Y")) SEPARATOR " \n") AS Auftraege
  FROM mm_umzuege a 
  left JOIN mm_user usr ON (a.antragsteller_uid = usr.uid)
  GROUP BY a.antragsteller_uid, usr.personalnr
';

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
    . ' ) ' . $NL
    . ' LEFT JOIN ('
    . 'SELECT 
      a.antragsteller_uid,
      COUNT(DISTINCT(a.aid)) AS numAuftraege,
      GROUP_CONCAT( CONCAT_WS(" ", CONCAT("#", a.aid), umzugsstatus, "am", DATE_FORMAT(umzugsstatus_vom, "%d.%m.%Y")) SEPARATOR " \n") AS Auftraege
      FROM mm_umzuege a 
      GROUP BY a.antragsteller_uid
    '
    . ') UStat ON (U.antragsteller_uid = UStat.antragsteller_uid) ' . $NL;
$sqlWhere = "WHERE 1\n"
. ' AND ul.menge_mertens > 0 ' . $NL
;

if ($datumfeld && $datumvon && $datumbis && $datumvon <= $datumbis) {
    if ($datumvon && $datumbis) {
        if ($datumvon > $datumbis) {
            $error = '';
        } else {
            $sqlWhere.= 'AND (DATE_FORMAT(' . $datumfeld . ', "%Y-%m-%d") BETWEEN ' . $db::quote($datumvon) . ' AND ' . $db::quote($datumbis) . ')';
        }
    }
    elseif ($datumvon) {
        $sqlWhere.= 'AND (DATE_FORMAT(' . $datumfeld . ', "%Y-%m-%d") >= ' . $db::quote($datumvon) . ')';
    } else {
        $sqlWhere.= 'AND (DATE_FORMAT(' . $datumfeld . ', "%Y-%m-%d") <= ' . $db::quote($datumbis) . ')';
    }

}

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

$sqlWhereStatus = '';
switch($cat) {
	case 'neue':
	$sqlWhereStatus.= 'AND umzugsstatus IN ("beantragt", "genehmigt", "erneutpruefen") AND IFNULL(tour_kennung, "") = ""' . $NL;
	break;
    
	case 'angeboten':
	$sqlWhereStatus.= 'AND (umzugsstatus = "angeboten" or umzugsstatus="geprueft" AND umzug="Ja")' . $NL;
	break;
	
	case 'gepruefte':
	$sqlWhereStatus.= 'AND umzugsstatus = "geprueft"' . $NL;
	break;
	
	case 'genehmigte':
	$sqlWhere.= 'AND umzugsstatus = "genehmigt"' . $NL;
	break;

    case 'heute':
        $sqlWhereStatus.= 'AND DATE_FORMAT(umzugstermin, "%Y-%m-%d") = "' . date('Y-m-d') . '"';
        $sqlWhereStatus.= 'AND (umzugsstatus IN ("geprueft", "bestaetigt","genehmigt") OR (umzug="Nein" AND umzugsstatus="angeboten"))' . $NL;
        break;

    case 'disponierte':
        $sqlWhereStatus.= 'AND (umzugsstatus IN ("beantragt", "disponiert") AND IFNULL(tour_kennung, "") LIKE "_%")' . $NL;
        break;

    case 'aktive':
        $sqlWhereStatus.= 'AND (umzugsstatus IN ("geprueft", "bestaetigt", "genehmigt") OR (umzug="Nein" AND umzugsstatus="angeboten"))' . $NL;
        break;
	
	case 'abgeschlossene':
	$sqlWhereStatus.= 'AND (umzugsstatus = "abgeschlossen" AND abgeschlossen = "Ja")' . $NL;
	//$sqlWhere.= "OR (abgeschlossen !=  'Init' AND  abgeschlossen IS NOT NULL)) \n";
	break;
	
	case 'abgelehnte':
	$sqlWhereStatus.= 'AND (umzugsstatus = "abgelehnt")' . $NL;
	break;
	
	case 'temp':
	$sqlWhereStatus.= 'AND umzugsstatus IN ("temp", "zurueckgegeben")' . $NL;
	break;
	
	case 'zurueckgegeben':
	$sqlWhereStatus.= 'AND umzugsstatus = "zurueckgegeben"' . $NL;
	break;
	
	case 'stornierte':
	$sqlWhereStatus.= 'AND (abgeschlossen = "Storniert" OR umzugsstatus = "storniert")' . $NL;
	break;
}
if ($sqlWhereStatus) {
    $sqlWhere.= $sqlWhereStatus;
}
if (count($w)) {
    $sqlWhere.= ' AND (' . implode(' AND ', $w) . ') ' . $NL;
}

$sqlLimit = "LIMIT $offset, $limit" . $NL;
$sqlGroup = ' GROUP BY U.aid' . $NL;
$sqlHaving = ( count($having) ? ' HAVING (' . implode(' AND ', $having) . ')' . $NL : '');


$sqlSelect = 'SELECT U.*, ' . $NL
    . ' U.umzugstermin AS Lieferdatum, ' . $NL
    . ' user.personalnr AS kid, ' . $NL
    . ' user.user, ' . $NL
    . ' CONCAT(vg.stadtname, " ", vg.adresse) von_gebaeude, ' . $NL
    . ' CONCAT(ng.stadtname, " ", ng.adresse) ziel_gebaeude, ' . $NL
    . ' UStat.numAuftraege, ' . $NL
    . ' UStat.Auftraege, ' . $NL
    . ' REPLACE(REPLACE(GROUP_CONCAT( CONCAT(
			 	kategorie_abk, 
				IF( IFNULL(leistung_abk, "") != "", CONCAT("", leistung_abk, ""), ""),
				""
			) ORDER BY leistungskategorie SEPARATOR ""), "P", ""), "R", "") AS Leistungen, ' . $NL
    . ' GROUP_CONCAT(lk.leistungskategorie ORDER BY leistungskategorie SEPARATOR ", ") AS LeistungenFull, ' . $NL
    . ' SUM(if(lm.preis, lm.preis, preis_pro_einheit) * ul.menge_mertens * IFNULL(ul.menge2_mertens,1)) AS Summe, ' . $NL
    . ' (ul.menge_mertens * IFNULL(ul.menge2_mertens,1)) AS Menge ' . $NL;

$sql = 'SELECT COUNT(1) AS `count` FROM (' . $sqlSelect . $sqlFrom . $sqlWhere . $sqlGroup . $sqlHaving . ') AS t';

$row = $db->query_singlerow($sql);
$num_all = $row['count'];

if ($cat === 'heute' || $cat === 'aktive' || $cat === 'disponierte' ) {
    $sTourBaseLink = "/?s=$s&cat=$cat&q%5Btour_kennung%5D=";
    $sqlTourNrs = 'SELECT tour_kennung,
    count(1) AS num_auftraege,
    CONCAT(' . $db::quote($sTourBaseLink) . ', tour_kennung) AS url 
    FROM mm_umzuege 
    WHERE 1 > 0 AND IFNULL(tour_kennung, "") != ""
    ' . $sqlWhereStatus . '
    GROUP BY tour_kennung
    ORDER BY MIN(umzugstermin) ASC, MIN(umzugszeit) ASC';
    $aTourNrs = $db->query_rows($sqlTourNrs);
} else {
    $aTourNrs = [];
}

$sql = $sqlSelect . $sqlFrom . $sqlWhere;
$sql.= $sqlGroup . $NL;
$sql.= $sqlHaving . $NL;
$sql.= $orderBy . $NL;
if ($exportFormat === 'html') {
    $sql .= $sqlLimit . $NL;
}

$all = $db->query_rows($sql);
// echo '<pre style="color:#0ba1b5;font-size:11px;border:1px solid #0ba1b5;padding:1rem;border-radius:5px;background-color: #dedede;margin:5px;">' . $sql . '</pre>' . $NL;
$num = count($all);

$sqlSummeTotal = 'SELECT SUM(summe) FROM ( ' . $sqlSelect . $sqlFrom . $sqlWhere . $sqlGroup . $sqlHaving . ') AS t';
$summeTotal = $db->query_one($sqlSummeTotal);


$sqlArtikel = 'SELECT lk.leistungskategorie AS Kategorie, ul.leistung_id, l.Bezeichnung, l.Farbe, l.Groesse, ' . $NL
    . ' SUM(ul.menge_mertens * IFNULL(ul.menge2_mertens,1)) count, ' . $NL
    . ' MAX(l.preis_pro_einheit) Preis, ' . $NL
    . ' SUM(l.preis_pro_einheit * ul.menge_mertens * IFNULL(ul.menge2_mertens,1)) AS Summe, ' . $NL
    . ' group_concat(ul.aid) aids' . $NL
    . ' FROM (' . $NL . $sqlSelect . $sqlFrom . $sqlWhere . $sqlGroup . $sqlHaving . ') AS t '
    . ' JOIN mm_umzuege_leistungen ul ON (t.aid = ul.aid) ' . $NL
    . ' JOIN mm_leistungskatalog l ON (ul.leistung_id = l.leistung_id) '
    . ' JOIN mm_leistungskategorie lk ON (l.leistungskategorie_id = lk.leistungskategorie_id) '
    . ' LEFT JOIN mm_leistungspreismatrix lm ON('  . $NL
    . '    l.leistung_id = lm.leistung_id '  . $NL
    . '    AND lm.mengen_von <= (ul.menge_mertens * IFNULL(ul.menge2_mertens,1)) ' . $NL
    . '    AND (lm.mengen_bis >= ( ul.menge_mertens * IFNULL(ul.menge2_mertens,1)))' . $NL
    . ' ) '  . $NL;
$sqlArtikel.= 'GROUP BY ul.leistung_id, l.Bezeichnung, l.Farbe, l.Groesse' . $NL;
$artikelStat = $db->query_rows($sqlArtikel);

//$sqlArtikel = 'SELECT lk.leistungskategorie AS Kategorie, l.leistung_id, l.Bezeichnung, l.Farbe, l.Groesse, ' . $NL
//    . ' COUNT(distinct(U.aid)) count, ' . $NL
//    . ' MAX(l.preis_pro_einheit) Preis, ' . $NL
//    . ' (l.preis_pro_einheit * COUNT(distinct(U.aid))) AS Summe, ' . $NL
//    . 'group_concat(ul.aid) aids' . $NL;
//$sqlArtikel.= $sqlFrom . $NL . $sqlWhere . ' AND l.leistung_id IS NOT NULL' . $NL;
//$sqlArtikel.= 'GROUP BY l.leistung_id, l.Bezeichnung, l.Farbe, l.Groesse' . $NL;
//$sqlArtikel.= $sqlHaving . $NL;
//$artikelStat = $db->query_rows($sqlArtikel);
// echo '<pre style="color:#0ba1b5;font-size:11px;border:1px solid #0ba1b5;padding:1rem;border-radius:5px;background-color: #dedede;margin:5px;">' . $sqlArtikel . '</pre>' . $NL;

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
        $_link = $MConf["WebRoot"] . "?s=aantrag&id=" . (int)$_row['aid'];

        foreach($aSelectCols as $k) {
            $s = [];
            $v = isset($_row[$k]) ? $_row[$k] : '';

            if ($k === 'aid' && (int)$v > 0) {
                $_styles[] = $s; // [ 'color' => '#00F', 'font-style' => 'underline' ];
                $_export[] = (int)$v; // '=HYPERLINK("' . $_link . '","' . (int)$v . '")';
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

$showDateRangeFilter = false;
$selectable = false;
$selectableActionTemplate = '';
$rangeDateFields = [];

if ($cat === 'temp' & $istSuperAdmin) {
    $selectable = true;
    $selectableActionTemplate = 'admin_antraege_temp_action.html';
    $showDateRangeFilter = true;
    $rangeDateFields = [
        [
            'value' => 'antragsdatum',
            'label' => 'Auftragsdatum',
            'checked' => $datumfeld === 'antragsdatum'
        ],
        [
            'value' => 'temp_erinnerugsmail_am',
            'label' => 'Erinnerungsdatum',
            'checked' => $datumfeld === 'temp_erinnerugsmail_am'
        ]
    ];
}

$Tpl->assign('s', $s);
$Tpl->assign('top', $top);
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
$Tpl->assign('aTourNrs', $aTourNrs);

$Tpl->assign('selectable', $selectable);
$Tpl->assign('selectableActionTemplate', $selectableActionTemplate);
$Tpl->assign('showDateRangeFilter', $showDateRangeFilter);
$Tpl->assign('rangeDateFields', $rangeDateFields);
$Tpl->assign('rangeDatumvon', $datumvon);
$Tpl->assign('rangeDatumbis', $datumbis);

$Tpl->assign('Umzuege', $Auftraege);

//echo '<pre>#' . __LINE__ . ' '; // . print_r( filestat('html/antraege_liste.html'),1);
try {
    if (!$istUmzugsteam || true) {
        $body_content .= $Tpl->fetch("admin_antraege_liste.html");
    } else {
        $body_content .= $Tpl->fetch("umzugsteam_antraege_liste.html");
    }
} catch(Exception $e) {
	echo $e->getMessage();
}
//$body_content.= "<pre>".print_r($all,1)."</pre>\n";


