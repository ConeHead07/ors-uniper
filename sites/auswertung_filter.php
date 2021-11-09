<?php
$Tpl = new myTplEngine();

$request = $_REQUEST;
$datumvon = (!empty($_REQUEST['datumvon']))   ? $_REQUEST['datumvon'] : '';
$datumbis = (!empty($_REQUEST['datumbis']))   ? $_REQUEST['datumbis'] : '';
$datumfeld = (!empty($_REQUEST['datumfeld'])) ? $_REQUEST['datumfeld'] : 'umzugstermin';

$aValidDatumfelder = ['umzugstermin', 'antragsdatum', 'geprueft_am', 'berechnet_am'];
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
    'land',
    'ort',
    'plz',
    'strasse',
    'antragsdatum',
    'berechnet_am',
    'vorgangsnummer',
    'service',
    'umzugstermin',
    'nachname',
    'bundesland',
    'stadtname',
    'Wirtschaftseinheit',
    'kostenstelle',
    'planonnr',
    'abgeschlossen_am',
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

    if (!empty($query[$_f])) {
        if (preg_match('/^([<>=]{1,2})(.+)$/', trim($query[$_f]), $m)) {
            $_q = $sqlQueryField . ' ' . $m[1] . $db->quote($m[2]);
        } else {
            $_q = $sqlQueryField . ' ' . ' LIKE ' . $db->quote( str_replace('*','%', $query[$_f]) . '%');
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
        else {
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


$sql = 'SELECT a.*, '
      . ' ua.personalnr AS kid,'
      . ' g.id Wirtschaftseinheit, g.bundesland, g.stadtname, g.adresse, '
      . ' u.nachname, u.nachname stom, ua.nachname antragsteller_name, '
      . ' ua.gruppe antragsteller_gruppe, '
      . ' SUM(if(lm.preis, lm.preis, preis_pro_einheit) * ul.menge_mertens * IFNULL(ul.menge2_mertens,1)) AS summe'
      . ' FROM mm_umzuege a '
      . ' JOIN mm_user ua ON a.antragsteller_uid = ua.uid '
      . ' LEFT JOIN mm_stamm_gebaeude g ON a.gebaeude = g.id '
      . ' LEFT JOIN mm_user u ON g.standortmanager_uid = u.uid '
      . ' LEFT JOIN mm_umzuege_leistungen ul ON (a.aid = ul.aid) '
      . ' LEFT JOIN mm_leistungskatalog l ON(ul.leistung_id = l.leistung_id) ' . "\n"
      . ' LEFT JOIN mm_leistungspreismatrix lm ON('
      . '    l.leistung_id = lm.leistung_id '
      . '    AND lm.mengen_von <= (ul.menge_mertens * IFNULL(ul.menge2_mertens,1)) '
      . '    AND (lm.mengen_bis >= ( ul.menge_mertens * IFNULL(ul.menge2_mertens,1)))'
      . ' ) '
      . ' WHERE 1 '
      . ' AND ' . $datumfeld . ' BETWEEN :von AND :bis '
      . ' AND ('
//      . '  umzugsstatus IN ("genehmigt", "bestaetigt") '
//      . '   OR '
      . '(umzugsstatus = "abgeschlossen" AND abgeschlossen = "Ja") '
      . '  )'
      . ( count($w) ? ' AND ('  . implode(' AND ', $w) . ') ' : '')
      . ' GROUP BY a.aid '
      . ( count($having) ? ' HAVING (' . implode(' AND ', $having) . ') ' : '')
      . ' ORDER BY ' . $sqlOrderFld . ' ' . $odir;
$rows = $db->query_rows($sql, 0, array('von'=>date('Y-m-d', $timeVon), 'bis'=>date('Y-m-d',$timeBis)));
// echo $db->error() . '<br>' . $db->lastQuery . '<br>' . PHP_EOL;

if ($s === 'vauswertung') $site_antrag = 'pantrag';

$Tpl->assign('Auftraege', $rows);
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

$body_content = $Tpl->fetch("auswertung_filter.html");

