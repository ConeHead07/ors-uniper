<?php
$Tpl = new myTplEngine();

$NL = "\n";
$request = $_REQUEST;
$datumvon = (!empty($_REQUEST['datumvon']))   ? $_REQUEST['datumvon'] : '';
$datumbis = (!empty($_REQUEST['datumbis']))   ? $_REQUEST['datumbis'] : '';
$datumfeld = (!empty($_REQUEST['datumfeld'])) ? $_REQUEST['datumfeld'] : 'antragsdatum';
$exportFormat = getRequest('format', 'html');
$all   = (!empty($_REQUEST['all']))     ? (int)$_REQUEST['all'] : 1;


$aAuftragsstatus = (!empty($_REQUEST['auftragsstatus'])) ? $_REQUEST['auftragsstatus'] : ['beauftragt'];

$aValidDatumfelder = ['umzugstermin', 'abgeschlossen_am', 'antragsdatum', 'geprueft_am', 'berechnet_am'];
if (!in_array($datumfeld, $aValidDatumfelder)) {
    $datumfeld = current($aValidDatumfelder);
}

if (empty($datumvon)) {
    $timeMin1Month = strtotime('-1 month');
    $datumvon = date('Y-m-01', $timeMin1Month);
}
if (empty($datumbis)) {
    $datumbis = date('Y-m-01', strtotime('next month', strtotime($datumvon)));
}


$order = (!empty($_REQUEST['order']))   ? $_REQUEST['order'] : '';
$queriedorder = (!empty($_REQUEST['queriedorder'])) ? $_REQUEST['queriedorder'] : '';
$queriedodir  = (!empty($_REQUEST['queriedodir']))  ? $_REQUEST['queriedodir']  : '';
$query = (!empty($_REQUEST['q']))   ? $_REQUEST['q'] : array();
$s = $_REQUEST['s'];

if (!empty($_REQUEST['kwvon'])) {
    $kwvon = (!empty($_REQUEST['kwvon'])) ? $_REQUEST['kwvon'] : '';
    $kwbis = (!empty($_REQUEST['kwbis'])) ? $_REQUEST['kwbis'] : '';
    $timeVon = (preg_match('/^(\d{4})W(\d{2})$/', $kwvon, $m)) ? strtotime($kwvon) : strtotime(date('Y') . 'W' . substr('0' . date('W'), -2));
    $timeBis = (preg_match('/^(\d{4})W(\d{2})$/', $kwbis, $m)) ? strtotime($kwbis) : $timeVon + (7 * 24 * 3600);
} else {
    $timeVon = (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $datumvon, $m)) ? strtotime($datumvon) : strtotime(date('Y-m-01'));
    $timeBis = (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $datumbis, $m)) ? strtotime($datumbis) : $timeVon + (7 * 24 * 3600);
}

$kwvon = date('Y\WW', $timeVon);
$kwbis = date('Y\WW', $timeBis);



$sql = 'SELECT date_format(umzugstermin, "%Y\W%u") kw '
      .' FROM mm_umzuege '
      .' GROUP BY date_format(umzugstermin, "%Y\W%u")'
      .' ORDER BY umzugstermin ASC';
$kws = $db->query_rows($sql);
$kw_options = array();
foreach($kws as $i => $_kw) {
    $kws[$i]['kw'].= date(' d.m.', strtotime($_kw['kw']));
    $kw_options[ $_kw['kw'] ] = 'KW ' . date('W (d.m.Y)', strtotime($_kw['kw'])) ;
}

$validFields = array(
    'aid',
    'kid',
    'service',
    'umzug',
    'land',
    'ort',
    'plz',
    'strasse',
    'antragsdatum',
    'berechnet_am',
    'vorgangsnummer',
    'service',
    'umzugstermin',
    'umzugsstatus',
    'nachname',
    'bundesland',
    'stadtname',
    'Wirtschaftseinheit',
    'kostenstelle',
    'planonnr',
    'abgeschlossen_am',
    'Leistungen',
    'summe',
);

$having = array();
$w = array();
foreach($validFields as $_f) {

    switch($_f) {
        case 'kid':
            $sqlQueryField = 'ua.personalnr';
            break;

        case 'Wirtschaftseinheit':
            $sqlQueryField = 'g.id';
            break;

        case 'aid':
            $sqlQueryField = 'a.aid';
            break;

        case 'plz':
            $sqlQueryField = 'a.plz';
            break;

        default:
            $sqlQueryField = $_f;

    }

    if (isset($query[$_f]) && is_string($query[$_f]) && trim($query[$_f]) !== '') {
        $_q = '';
        $_qf = trim($query[$_f] ?? '');
        if ($_f === 'Leistungen') {
            if (preg_match('[ ,]', $_qf)) {
                $chars = preg_split('[ ,]', $_qf);
            } else {
                $chars = str_split($_qf);
            }
            $chars = array_filter($chars, function($v) { return strlen(trim($v)) > 0; });

            foreach($chars as $_chr) {
                $having[] = 'GROUP_CONCAT(CONCAT(lk.kategorie_abk, IF(IFNULL(l.leistung_abk,"")="", "", CONCAT("", l.leistung_abk, "")))) LIKE "%' . $_chr . '%"';
            }
            break;
        }
        elseif (preg_match('#^(!=)(.*)$#', trim($query[$_f]), $m)) {
            $_q = 'IFNULL(' . $sqlQueryField . ',"") ' . $m[1] . $db->quote($m[2]) . ' /* #' . __LINE__ . ' */';
        }
        elseif (preg_match('/^(!)(.+)$/', trim($query[$_f]), $m)) {
            $_q = 'IFNULL(' . $sqlQueryField . ',"") NOT LIKE ' . $db->quote( str_replace('*','%', $m[2]) . '%') . ' /* #' . __LINE__ . ' */';
        }
        elseif (preg_match('/^([<>=]{1,2})(.*)$/', trim($query[$_f]), $m)) {
            $_q = 'IFNULL(' . $sqlQueryField . ',"") ' . $m[1] . $db->quote($m[2]) . ' /* #' . __LINE__ . ' */';
        } else {
            $_term = str_replace('*','%', trim($query[$_f]));
            if ($_f === 'land' && strcmp($_term,'NL') === 0) {
                $_term = 'Niederlande';
            }
            elseif ($_f === 'land' && strcmp($_term,'EN') === 0) {
                $_term = 'England';
            }
            else {
                $_term.= '%';
            }
            $_q = 'IFNULL(' . $sqlQueryField . ',"") LIKE ' . $db->quote( str_replace('*','%', $_term) . '%') . ' /* #' . __LINE__ . ' */';
        }
        
        if ($sqlQueryField === 'nachname') {
            if ($query[$_f] === '!') {
                $w[] = 'ua.gruppe = "kunde_report"';
            } elseif ( preg_match('/^!(.+)$/', $query[$_f], $m2 )) {
                $w[] = '(ua.gruppe = "kunde_report" AND ua.' . $sqlQueryField . ' LIKE '
                      . $db->quote( str_replace('*','%', $m2[1]) . '%')
                      . ')';
            } else {
                $w[] = '(((ua.gruppe != "kunde_report" OR u.nachname = ua.nachname) AND u.' . $_q . ')'
                      .' OR '
                      .'(ua.gruppe = "kunde_report" AND ua.' . $_q . '))';
            }
        }
        elseif ($_f !== 'summe') {
            $w[] = $_q;
        }
        elseif ($_q) {
            $having[] = $_q;
        }
    }
}

if (!in_array($order, $validFields)) {
    $order = 'umzugstermin';
    $odir = 'ASC';
} else {
    $odir = ($order !== $queriedorder) ? 'ASC' : ($queriedodir === 'DESC' ? 'ASC' : 'DESC');
}
$sqlOrderFld = $order;
if ($order == 'Wirtschaftseinheit') {
    $sqlOrderFld = 'g.id';
}
elseif ($order == 'aid') {
    $sqlOrderFld = 'a.aid';
}
elseif ($order == 'kid') {
    $sqlOrderFld = 'ua.personalnr';
}
elseif ($order == 'plz') {
    $sqlOrderFld = 'a.plz';
}

$aWhereStatusAnyOf = [];
if (in_array('beauftragt', $aAuftragsstatus)) {
    $aWhereStatusAnyOf[] = ' (umzugsstatus = "beantragt") ' . "\n";
}
if (in_array( 'avisiert', $aAuftragsstatus)) {
    $aWhereStatusAnyOf[] = ' (umzugsstatus = "bestaetigt") ' . "\n";
}
if (in_array('abgeschlossen', $aAuftragsstatus)) {
    $aWhereStatusAnyOf[] = ' (umzugsstatus = "abgeschlossen" AND abgeschlossen = "Ja" AND IFNULL(berechnet_am, "") = "") ' . "\n";
}
if (in_array('abgerechnet', $aAuftragsstatus)) {
    $aWhereStatusAnyOf[] = ' (umzugsstatus = "abgeschlossen" AND abgeschlossen = "Ja" AND IFNULL(berechnet_am, "") != "") ' . "\n";
}


$sqlSelect = 'SELECT a.*, ' . "\n"
      . ' ua.personalnr AS kid,' . "\n"
      . ' g.id Wirtschaftseinheit, g.bundesland, g.stadtname, g.adresse, ' . "\n"
      . ' u.nachname, u.nachname stom, ua.nachname antragsteller_name, ' . "\n"
      . ' ua.gruppe antragsteller_gruppe, ' . "\n"
      . ' GROUP_CONCAT(CONCAT(lk.kategorie_abk, IF(IFNULL(l.leistung_abk,"")="", "", CONCAT("", l.leistung_abk, ""))) ORDER BY leistungskategorie SEPARATOR "") AS Leistungen, ' . $NL
      . '   GROUP_CONCAT(' . $NL
      . '     IF (l.leistung_id is NULL, "", CONCAT_WS("<|#|>", '  . $NL
      . '      l.leistung_id, lk.kategorie_abk, lk.leistungskategorie, ' . $NL
      . '      l.Bezeichnung, l.Farbe, l.Groesse, "€", l.preis_pro_einheit' . $NL
      . ',     (ul.menge_mertens * IFNULL(ul.menge2_mertens,1))' . $NL
      . '     )) '
      . '     ORDER BY leistungskategorie SEPARATOR ";\n"' . $NL
      . '   ) AS LeistungenFull, ' . $NL
      . ' SUM(if(lm.preis, lm.preis, preis_pro_einheit) * ul.menge_mertens * IFNULL(ul.menge2_mertens,1)) AS summe' . "\n";

$sqlFrom = ' FROM mm_umzuege a ' . "\n"
      . ' JOIN mm_user ua ON a.antragsteller_uid = ua.uid ' . "\n"
      . ' LEFT JOIN mm_stamm_gebaeude g ON a.gebaeude = g.id ' . "\n"
      . ' LEFT JOIN mm_user u ON g.standortmanager_uid = u.uid ' . "\n"
      . ' LEFT JOIN mm_umzuege_leistungen ul ON (a.aid = ul.aid) ' . "\n"
      . ' LEFT JOIN mm_leistungskatalog l ON(ul.leistung_id = l.leistung_id) ' . "\n"
      . ' LEFT JOIN mm_leistungskategorie lk ON(l.leistungskategorie_id = lk.leistungskategorie_id) ' . "\n"
      . ' LEFT JOIN mm_leistungspreismatrix lm ON(' . "\n"
      . '    l.leistung_id = lm.leistung_id ' . "\n"
      . '    AND lm.mengen_von <= (ul.menge_mertens * IFNULL(ul.menge2_mertens,1)) ' . "\n"
      . '    AND (lm.mengen_bis >= ( ul.menge_mertens * IFNULL(ul.menge2_mertens,1)))' . "\n"
      . ' ) ' . "\n";

$sqlWhere = ' WHERE 1 ' . "\n"
    . ' AND service != "Rekla" '
    . ' AND ul.menge_mertens > 0 ' . $NL
    . ' AND DATE_FORMAT(' . $datumfeld . ', "%Y-%m-%d") BETWEEN :von AND :bis ' . "\n";
if (count($aWhereStatusAnyOf)) {
    $sqlWhere.= ' AND ( ' . implode(' OR ', $aWhereStatusAnyOf) . ')' . "\n";
}

$sqlWhere.=  ( count($w) ? ' AND ('  . implode(' AND ', $w) . ') ' : '') . "\n";
$sqlGroup = ' GROUP BY a.aid ' . "\n";
$sqlHaving = ( count($having) ? ' HAVING (' . implode(' AND ', $having) . ') ' : '') . "\n";
$sqlOrder = ' ORDER BY ' . $sqlOrderFld . ' ' . $odir . "\n";
$sqlLimit = '';

$aParams = array('von'=>date('Y-m-d', $timeVon), 'bis'=>date('Y-m-d',$timeBis));

$sql = $sqlSelect . $sqlFrom . $sqlWhere . $sqlGroup . $sqlHaving . $sqlOrder . $sqlLimit;
$rows = $db->query_rows($sql, 0, $aParams);
if (1 && constant('APP_ENVIRONMENT') === 'DEVELOPMENT') {
    echo '<pre>' . $db->lastQuery . '</pre>' . PHP_EOL;
}

$sqlForStat = $sqlSelect . $sqlFrom . $sqlWhere . $sqlGroup . $sqlHaving;
$sqlStat = 'SELECT COUNT(1) numAll, SUM(summe) sumAll FROM (' . $sqlForStat . ') AS t';
$stat = $db->query_row($sqlStat, $aParams);

$sqlArtikel = 'SELECT 
    ul.leistung_id AS ID,
    CONCAT(lk.kategorie_abk, IF(IFNULL(l.leistung_abk,"")="", "", CONCAT("", l.leistung_abk, ""))) AS Ktg_Abk,
    lk.leistungskategorie AS Kategorie, 
    l.Bezeichnung, 
    l.Farbe, 
    l.Groesse, ' . $NL
    . ' COUNT(distinct(ul.aid)) numAuftraege, ' . $NL
    . ' SUM( ul.menge_mertens * IFNULL(ul.menge2_mertens,1)) AS Menge, ' . $NL
    . ' MAX(l.preis_pro_einheit) Preis, ' . $NL
    . ' SUM(l.preis_pro_einheit * ul.menge_mertens) AS Summe, ' . $NL
    . ( $exportFormat !== 'html' ? ' group_concat(ul.aid) aids' : '"" AS aids') . $NL
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
$artikelStat = $db->query_rows($sqlArtikel, 0, $aParams);

if ($exportFormat !== 'html' && is_array($rows) && count($rows)) {
    require_once( $ModulBaseDir . 'excelexport/helper_functions.php');

    $aSelectCols = [
        'summe', 'aid', 'kid', 'tour_kennung', 'service', 'plz', 'ort', 'strasse',
        'land', 'Leistungen', 'antragsdatum', 'umzugstermin', 'umzugsstatus', 'tour_zugewiesen_am',
        'bestaetigt_am', 'abgeschlossen_am'
    ];

    $writer = new XLSXWriter();
    $writer->setAuthor('Frank Barthold, merTens AG');

    $sheet01Name = 'Auftraege';
    $sheet01Header = leistungsRowToSheetHeader($aSelectCols);
    // die('<pre>' . print_r(compact('sheet01Header'), 1));
    $writer->writeSheetHeader($sheet01Name , $sheet01Header);
    foreach($rows as $_row) {
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

    $aSelectStat = [
        'ID', 'Ktg_Abk', 'Kategorie', 'Bezeichnung', 'Farbe', 'Größe', 'Menge', 'Preis', 'Summe', 'numAuftraege', 'aids'
    ];
    $sheet02Name = 'KumulierteLeistungen';
    $sheet02Header = leistungsRowToSheetHeader($aSelectStat );
    $writer->writeSheetHeader($sheet02Name, $sheet02Header);
    foreach($artikelStat as $_row) {
        $_export = [];
        $_styles = [];
        foreach($aSelectStat as $k) {
            $s = [];
            $v = isset($_row[$k]) ? $_row[$k] : '';
            $_styles[] = $s;
            $_export[] = $v;
        }
        $writer->writeSheetRow($sheet02Name, $_export, $_styles);
    }

    header('Content-Type: application/xls');
    header('Content-Disposition: attachment; filename="ZurichAuswertungVom' . date('YmdHi') . '.xlsx"');
    $writer->writeToStdOut();

    exit;
}

if ($s === 'vauswertung') $site_antrag = 'pantrag';

$Tpl->assign('numAll', $stat['numAll']);
$Tpl->assign('sumAll', $stat['sumAll']);
$Tpl->assign('Auftraege', $rows);
$Tpl->assign('artikelStat', $artikelStat);
$Tpl->assign('kw_options', $kw_options);
$Tpl->assign('kwvon', $kwvon);
$Tpl->assign('kwbis', $kwbis);
$Tpl->assign('datumfeld', $datumfeld);
$Tpl->assign('datumvon', $datumvon);
$Tpl->assign('datumbis', $datumbis);
$Tpl->assign('order', $order);
$Tpl->assign('odir', $odir);
$Tpl->assign('s', $s);
$Tpl->assign('q', $query);
$Tpl->assign('site_antrag', (!empty($site_antrag)?$site_antrag:'aantrag'));
$Tpl->assign('aAuftragsstatus', json_encode($aAuftragsstatus));

$body_content = $Tpl->fetch("auswertung_filter.html");

