<?php
$Tpl = new myTplEngine();

$datumvon = (!empty($_REQUEST['datumvon']))   ? $_REQUEST['datumvon'] : '';
$datumbis = (!empty($_REQUEST['datumbis']))   ? $_REQUEST['datumbis'] : '';
$datumfeld = (!empty($_REQUEST['datumfeld'])) ? $_REQUEST['datumfeld'] : 'abgeschlossen_am';

$NL = "\n";
$aValidDatumfelder = ['umzugstermin', 'antragsdatum', 'geprueft_am', 'bestaetigt_am', 'abgeschlossen_am', 'berechnet_am'];
if (!in_array($datumfeld, $aValidDatumfelder)) {
    echo "$datumfeld is not " . json_encode($aValidDatumfelder) . "!<br>\n";
    $datumfeld = current($aValidDatumfelder);
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

$site_antrag = 'aantrag';

$order = (!empty($_REQUEST['order']))   ? $_REQUEST['order'] : '';
$queriedorder = (!empty($_REQUEST['queriedorder'])) ? $_REQUEST['queriedorder'] : '';
$queriedodir  = (!empty($_REQUEST['queriedodir']))  ? $_REQUEST['queriedodir']  : '';
$query = (!empty($_REQUEST['q']))   ? $_REQUEST['q'] : array();

$finish = (!empty($_REQUEST['finish'])) ? (int)(bool)$_REQUEST['finish'] : 0;
$wwsnr = (!empty($_REQUEST['wwsnr']))   ? $_REQUEST['wwsnr'] : '';
$all   = (!empty($_REQUEST['all']))     ? $_REQUEST['all'] : '';

$aAids = [];
$aUlids = [];

if ($finish && $wwsnr && count($aids)) {
    $sql = 'UPDATE mm_umzuege SET berechnet_am = NOW(), vorgangsnummer = :wwsnr WHERE aid IN('.implode(',', $aids).')';
    $db->query($sql, array('wwsnr' => $wwsnr));
    //    echo $db->error() . '<br>' . $db->lastQuery . '<br>';
}
if ($finish && $wwsnr && count($ulids)) {
    $sql = 'UPDATE mm_umzuege SET berechnet_am = NOW(), vorgangsnummer = :wwsnr WHERE aid IN('.implode(',', $ulids).')';
    $db->query($sql, array('wwsnr' => $wwsnr));
    //    echo $db->error() . '<br>' . $db->lastQuery . '<br>';
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
    'vorgangsnummer',
    'nachname',
    'bundesland',
    'stadtname',
    'Wirtschaftseinheit',
    'kostenstelle',
    'planonnr',
    'umzugstermin',
    'abgeschlossen_am',
    'abgerechnet_am',
    'summe',
    'aids',
    'ulids'
);

$having = array();
$w = array();
$wTL = [];
foreach($validFields as $_f) {
    $sqlQueryField = $_f;
    if (strcmp($_f, 'Wirtschaftseinheit') === 0) {
        $sqlQueryField = 'g.id';
    }
    elseif (strcmp($_f,'aid') === 0) {
        $sqlQueryField = 'a.aid';
    }
    elseif (!empty($query[$_f]) && strcmp($_f,'aids') === 0) {
        $_aids = explode(',', $query[$_f]);
        $aAids = array_map('intval', array_map('trim', $_aids));
        $w[] = ' a.aid IN (' . implode(',', $_aids) . ')';
        continue;
    }
    elseif (!empty($query[$_f]) && strcmp($_f,'ulids') === 0) {
        $_ulids = explode(',', $query[$_f]);
        $aUlids = array_map('intval', array_map('trim', $_ulids));
        $wTL[] = ' ul.id IN (' . implode(',', $_ulids) . ')';
        continue;
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
}

$sqlSelect = 'SELECT a.*, user.personalnr, user.personalnr AS kid, '
    .' g.id Wirtschaftseinheit, g.bundesland, g.stadtname, g.adresse, '
    .' u.nachname, u.nachname stom, '
    .' SUM(if(lm.preis, lm.preis, preis_pro_einheit) * ul.menge_mertens * IFNULL(ul.menge2_mertens,1)) AS summe';
$sqlFrom = ' FROM mm_umzuege a '
    .' LEFT JOIN mm_user AS `user` ON (a.antragsteller_uid = user.uid) '
    .' LEFT JOIN mm_stamm_gebaeude g ON a.gebaeude = g.id '
    .' LEFT JOIN mm_user u ON g.standortmanager_uid = u.uid '
    .' LEFT JOIN mm_umzuege_leistungen ul ON (a.aid = ul.aid) '
    .' LEFT JOIN mm_leistungskatalog l ON(ul.leistung_id = l.leistung_id) ' . "\n"
    .' LEFT JOIN mm_leistungspreismatrix lm ON('
    .'    l.leistung_id = lm.leistung_id '
    .'    AND lm.mengen_von <= (ul.menge_mertens * IFNULL(ul.menge2_mertens,1)) '
    .'    AND (lm.mengen_bis >= ( ul.menge_mertens * IFNULL(ul.menge2_mertens,1)))'
    .' ) ';
$sqlWhere = ' WHERE '
    // .' abgeschlossen_am IS NOT NULL AND '
    .' abgeschlossen = "Ja" AND abgeschlossen_am IS NOT NULL AND DATE_FORMAT(' . $datumfeld . ', "%Y-%m-%d") BETWEEN :von AND :bis '
    .(!$all ? 'AND berechnet_am IS NULL' : '')
    .( count($w) ? ' AND ('  . implode(' AND ', $w) . ') ' : '')
    ;
$sqlGroup = ' GROUP BY a.aid';
$sqlHaving = ( count($having) ? ' HAVING (' . implode(' AND ', $having) . ')' : '');
$sqlOrder = ' ORDER BY ' . $sqlOrderFld. ' ' . $odir;
$sqlLimit = '';
$aParams = array('von'=> $datumvon, 'bis'=> $datumbis);
$sql = $sqlSelect . $sqlFrom . $sqlWhere . $sqlGroup . $sqlHaving . $sqlOrder . $sqlLimit;
$rows = $db->query_rows($sql, 0, $aParams);

$sqlArtikel = 'SELECT lk.leistungskategorie AS Kategorie, ul.leistung_id, l.Bezeichnung, l.Farbe, l.Groesse, ' . $NL
    . ' COUNT(distinct(ul.aid)) count, ' . $NL
    . ' MAX(l.preis_pro_einheit) Preis, ' . $NL
    . ' (l.preis_pro_einheit * COUNT(distinct(ul.aid))) AS Summe, ' . $NL
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
$artikelStat = $db->query_rows($sqlArtikel, 0, $aParams);


$sqlForStat = $sqlSelect . $sqlFrom . $sqlWhere . $sqlGroup . $sqlHaving;
$sqlStat = 'SELECT COUNT(1) numAll, SUM(summe) sumAll FROM (' . $sqlForStat . ') AS t';
$stat = $db->query_row($sqlStat, $aParams);

if (false && empty($wTL)) {
    $sqlTL = 'SELECT ul.id AS ulid, a.*, `user`.personalnr, `user`.personalnr AS kid, '
        .' g.id Wirtschaftseinheit, g.bundesland, g.stadtname, g.adresse, '
        .' u.nachname, u.nachname stom, '
        .' (if(lm.preis, lm.preis, preis_pro_einheit) * ul.menge_mertens * IFNULL(ul.menge2_mertens,1)) AS summe'
        .' FROM mm_umzuege a '
        .' LEFT JOIN mm_user AS `user` ON (a.antragsteller_uid = user.uid) '
        .' LEFT JOIN mm_umzuege_leistungen ul ON (a.aid = ul.aid) '
        .' LEFT JOIN mm_leistungskatalog l ON(ul.leistung_id = l.leistung_id) ' . "\n"
        .' LEFT JOIN mm_leistungspreismatrix lm ON('
        .'    l.leistung_id = lm.leistung_id '
        .'    AND lm.mengen_von <= (ul.menge_mertens * IFNULL(ul.menge2_mertens,1)) '
        .'    AND (lm.mengen_bis >= ( ul.menge_mertens * IFNULL(ul.menge2_mertens,1)))'
        .' ) '
        .' WHERE '
        . ' abgeschlossen = "Init" AND (ul.rechnungsnr IS NULL OR ul.rechnungsnr = "") '
        . (count($wTL) ? ' AND ' . implode(' AND ', $wTL) : '')
        . ' AND ul.abgeschlossen_am BETWEEN ' . $db::quote($datumvon) . ' AND ' . $db::quote($datumbis) . ' '
        .' ORDER BY ' . $sqlOrderFld. ' ' . $odir;
    $rowsTL = $db->query_rows($sql, 0, array('von'=> $datumvon, 'bis'=> $datumbis));
} else {
    $rowsTL = [];
}

if (0) {
    echo $db->error() . '<br>' . $db->lastQuery . '<br>' . PHP_EOL;
}
$kwvon = date('Y\WW', strtotime($datumvon));
$kwbis = date('Y\WW', strtotime($datumbis));

$Tpl->assign('numAll', $stat['numAll']);
$Tpl->assign('sumAll', $stat['sumAll']);
$Tpl->assign('s', $s);
$Tpl->assign('all', $all);
$Tpl->assign('wwsnr', $wwsnr);
$Tpl->assign('site_antrag', $site_antrag);
$Tpl->assign('Auftraege', $rows);
$Tpl->assign('TeilLieferungen', $rowsTL);
$Tpl->assign('artikelStat', $artikelStat);
$Tpl->assign('kw_options', $kw_options);
$Tpl->assign('kwvon', $kwvon);
$Tpl->assign('kwbis', $kwbis);
$Tpl->assign('datumfeld', $datumfeld);
$Tpl->assign('datumvon', $datumvon);
$Tpl->assign('datumbis', $datumbis);
$Tpl->assign('datumfeld', $datumfeld);
$Tpl->assign('order', $order);
$Tpl->assign('odir', $odir);
$Tpl->assign('s', $s);
$Tpl->assign('aAids', $aAids);
$Tpl->assign('aUlids', $aUlids);
$body_content = $Tpl->fetch("auswertung_form.html");

