<?php
$Tpl = new myTplEngine();

$datumvon = (!empty($_REQUEST['datumvon']))   ? $_REQUEST['datumvon'] : '';
$datumbis = (!empty($_REQUEST['datumbis']))   ? $_REQUEST['datumbis'] : '';
$datumfeld = (!empty($_REQUEST['datumfeld'])) ? $_REQUEST['datumfeld'] : 'antragsdatum';

$aAuftragsstatus = (!empty($_REQUEST['auftragsstatus'])) ? $_REQUEST['auftragsstatus'] : ['beauftragt'];

$aValidDatumfelder = [
    'umzugstermin', 'antragsdatum', 'geprueft_am',
    'bestaetigt_am', 'abgeschlossen_am', 'berechnet_am',
    'tour_disponiert_am'
];
if (!in_array($datumfeld, $aValidDatumfelder)) {
    echo "$datumfeld is not " . json_encode($aValidDatumfelder) . "!<br>\n";
    $datumfeld = current($aValidDatumfelder);
}
/*
<label style="margin-right:2rem"><input type="checkbox" name="auftragsstatus[]" id="filter_beauftragt" value="beauftragt"> Beauftragt</label>
        <label style="margin-right:2rem"><input type="checkbox" name="auftragsstatus[]" id="filter_avisiert" value="avisiert"> Avisiert</label>
        <label style="margin-right:2rem"><input type="checkbox" name="auftragsstatus[]" id="filter_disponiert" value="disponiert"> Disponiert</label>
        <label style="margin-right:2rem"><input type="checkbox" name="auftragsstatus[]" id="filter_abgeschlossen" value="abgeschlossen"> Abgeschlossen</label>
*/
$aWhereStatusAnyOf = [];
if (in_array('beauftragt', $aAuftragsstatus)) {
    $aWhereStatusAnyOf[] = ' (umzugsstatus = "beauftragt" OR IFNULL(antragsdatum, "") != "") ' . "\n";
}
if (in_array( 'avisiert', $aAuftragsstatus)) {
    $aWhereStatusAnyOf[] = ' (umzugsstatus = "bestaetigt" or bestaetigt="Ja" or IFNULL(umzugstermin, "") != "") ' . "\n";
}
if (in_array('abgeschlossen', $aAuftragsstatus)) {
    $aWhereStatusAnyOf[] = ' (umzugsstatus = "abgeschlossen" or abgeschlossen="Ja") ' . "\n";
}
if (in_array('disponiert', $aAuftragsstatus)) {
    $aWhereStatusAnyOf[] = ' (IFNULL(berechnet_am, "") != "") ' . "\n";
}

if (empty($datumvon)) {
//    $timeMin1Month = strtotime('-1 month');
//    $datumvon = date('Y-m-01', $timeMin1Month);

    $givendate = $_REQUEST['givendate'] ?? date('Y-m-d');
    $prevMonday = strtotime('previous thursday', strtotime('-2 days', strtotime($givendate)));
    $datumvon = date('Y-m-d', $prevMonday);
}

if (empty($datumbis) || $datumbis < $datumvon) {
    $lastDay = date('t');
    if (strlen($lastDay) < 2) {
        $lastDay = '0' . $lastDay;
    }
    // $datumbis = date('Y-m-t');

    $datumbis = date('Y-m-d', strtotime('next wednesday', strtotime($datumvon)));
}


$order = (!empty($_REQUEST['order']))   ? $_REQUEST['order'] : '';
$queriedorder = (!empty($_REQUEST['queriedorder'])) ? $_REQUEST['queriedorder'] : '';
$queriedodir  = (!empty($_REQUEST['queriedodir']))  ? $_REQUEST['queriedodir']  : '';
$query = (!empty($_REQUEST['q']))   ? $_REQUEST['q'] : array();

$finish = (!empty($_REQUEST['finish'])) ? (int)(bool)$_REQUEST['finish'] : 0;
$aids = (!empty($_REQUEST['aids']))     ? $_REQUEST['aids'] : array();
$tourkennung = (!empty($_REQUEST['tourkennung']))   ? $_REQUEST['tourkennung'] : '';
$all   = (!empty($_REQUEST['all']))     ? $_REQUEST['all'] : '';

if ($finish && $tourkennung && count($aids)) {
    $sql = 'UPDATE mm_umzuege SET '
        . ' tour_disponiert_am = NOW(), '
        . ' tour_disponiert_von = :username, '
        . ' tour_kennung = :tour_kennung '
        . ' WHERE aid IN('.implode(',', $aids).')'
        . ' AND IFNUL(tour_kennung, "") NOT LIKE :tour_kennung2';
    $db->query($sql, array('tour_kennung' => $tourkennung, 'tour_kennung2' => $tourkennung, 'username' => $user['user']));
    // echo $db->error() . '<br>' . $db->lastQuery . '<br>';
}

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
    'umzugstermin',
    'tour_kennung',
    'Leistungen',
    'summe',

    'vorgangsnummer',
    'nachname',
    'bundesland',
    'stadtname',
    'Wirtschaftseinheit',
    'kostenstelle',
    'planonnr',
    'abgeschlossen_am',
    'abgerechnet_am',
);

$having = array();
$w = array();
foreach($validFields as $_f) {
    $sqlQueryField = $_f;
    if ($_f == 'Wirtschaftseinheit') {
        $sqlQueryField = 'g.id';
    }
    elseif ($_f == 'aid') {
        $sqlQueryField = 'a.aid';
    }
    elseif ($_f === 'Leistungen' && !empty($query[$_f])) {
        $chars = str_split( trim($query[$_f]));
        $chars = preg_replace('#[^A-Z]#', '', $chars);

        foreach($chars as $_chr) {
            $having[] = 'GROUP_CONCAT(kategorie_abk) LIKE "%' . $_chr . '%"';
        }
        break;
    }
    elseif ($_f === 'land' && !empty($query[$_f]) && strcmp(trim($query[$_f]), 'NL') ) {
        $w[] = 'a.land LIKE "Niederlande"';
    }
    if (!empty($query[$_f])) {
        if (preg_match('/^([<>=]{1,2})(.+)$/', trim($query[$_f]), $m)) {
            $_q = $sqlQueryField . ' ' . $m[1] . $db->quote($m[2]);
        } else {
            $_q = $sqlQueryField . ' ' . ' LIKE ' . $db->quote( str_replace('*','%', $query[$_f]) . '%');
        }

        if ($_f !== 'summe') {
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
}elseif ($order == 'Leistungen') {
    $sqlOrderFld = 'GROUP_CONCAT(lk.kategorie_abk ORDER BY leistungskategorie SEPARATOR "")';
}

$sqlSelect = 'SELECT a.*, user.personalnr, user.personalnr AS kid, '
    . ' g.id Wirtschaftseinheit, g.bundesland, g.stadtname, g.adresse, '
    . ' u.nachname, u.nachname stom, '
    . ' GROUP_CONCAT(lk.kategorie_abk ORDER BY leistungskategorie SEPARATOR "") AS Leistungen, ' . "\n"
    . ' GROUP_CONCAT(lk.leistungskategorie ORDER BY leistungskategorie SEPARATOR ", ") AS LeistungenFull, ' . "\n"
    . ' SUM(if(lm.preis, lm.preis, preis_pro_einheit) * ul.menge_mertens * IFNULL(ul.menge2_mertens,1)) AS summe' . "\n";

$sqlFrom = ' FROM mm_umzuege a '
    . ' LEFT JOIN mm_user user ON (a.antragsteller_uid = user.uid) '
    . ' LEFT JOIN mm_stamm_gebaeude g ON a.gebaeude = g.id '
    . ' LEFT JOIN mm_user u ON g.standortmanager_uid = u.uid '
    . ' LEFT JOIN mm_umzuege_leistungen ul ON (a.aid = ul.aid) '
    . ' LEFT JOIN mm_leistungskatalog l ON(ul.leistung_id = l.leistung_id) ' . "\n"
    . ' LEFT JOIN mm_leistungskategorie lk ON(l.leistungskategorie_id = lk.leistungskategorie_id) ' . "\n"
    . ' LEFT JOIN mm_leistungspreismatrix lm ON('
    . '    l.leistung_id = lm.leistung_id '
    . '    AND lm.mengen_von <= (ul.menge_mertens * IFNULL(ul.menge2_mertens,1)) '
    . '    AND (lm.mengen_bis >= ( ul.menge_mertens * IFNULL(ul.menge2_mertens,1)))'
    . ' ) ';

$sqlWhere = ' WHERE '
    . ' ' . $datumfeld . ' BETWEEN :von AND :bis '
    . (!$all ? 'AND tour_kennung IS NULL' : '')
    . ( count($w) ? ' AND ('  . implode(' AND ', $w) . ') ' : '');

    if (count($aWhereStatusAnyOf)) {
        $sql.= ' AND ( ' . implode(' OR ', $aWhereStatusAnyOf) . ')' . "\n";
    }

$sqlGroup = ' GROUP BY a.aid';
$sqlHaving = ( count($having) ? ' HAVING (' . implode(' AND ', $having) . ')' : '');
$sqlOrder = ' ORDER BY ' . $sqlOrderFld. ' ' . $odir;
$sqlLimit = '';

$sql = $sqlSelect . $sqlFrom . $sqlGroup . $sqlHaving . $sqlOrder . $sqlLimit;
$rows = $db->query_rows($sql, 0, array('von'=> $datumvon, 'bis'=> $datumbis));

if (0) {
    echo $db->error() . '<br>' . $db->lastQuery . '<br>' . PHP_EOL;
}
$kwvon = date('Y\WW', strtotime($datumvon));
$kwbis = date('Y\WW', strtotime($datumbis));

$Tpl->assign('s', $s);
$Tpl->assign('all', $all);
$Tpl->assign('tourkennung', $tourkennung);
$Tpl->assign('Auftraege', $rows);
$Tpl->assign('kw_options', $kw_options);
$Tpl->assign('kwvon', $kwvon);
$Tpl->assign('kwbis', $kwbis);
$Tpl->assign('datumfeld', $datumfeld);
$Tpl->assign('datumvon', $datumvon);
$Tpl->assign('datumbis', $datumbis);
$Tpl->assign('datumfeld', $datumfeld);
$Tpl->assign('site_antrag', (!empty($site_antrag)?$site_antrag:'aantrag'));
$Tpl->assign('aAuftragsstatus', json_encode($aAuftragsstatus));
$Tpl->assign('order', $order);
$Tpl->assign('odir', $odir);
$Tpl->assign('s', $s);
$Tpl->assign('q', $query);
$body_content = $Tpl->fetch("auswertung_tourenplanung.html");

