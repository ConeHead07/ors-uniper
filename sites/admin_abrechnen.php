<?php
$Tpl = new myTplEngine();

$kwvon = (!empty($_REQUEST['kwvon']))   ? $_REQUEST['kwvon'] : '';
$kwbis = (!empty($_REQUEST['kwbis']))   ? $_REQUEST['kwbis'] : '';
$order = (!empty($_REQUEST['order']))   ? $_REQUEST['order'] : '';
$queriedorder = (!empty($_REQUEST['queriedorder'])) ? $_REQUEST['queriedorder'] : '';
$queriedodir  = (!empty($_REQUEST['queriedodir']))  ? $_REQUEST['queriedodir']  : '';
$query = (!empty($_REQUEST['q']))   ? $_REQUEST['q'] : array();

$finish = (!empty($_REQUEST['finish'])) ? (int)(bool)$_REQUEST['finish'] : 0;
$aids = (!empty($_REQUEST['aids']))     ? $_REQUEST['aids'] : array();
$wwsnr = (!empty($_REQUEST['wwsnr']))   ? $_REQUEST['wwsnr'] : '';
$all   = (!empty($_REQUEST['all']))     ? $_REQUEST['all'] : '';

if ($finish && $wwsnr && count($aids)) {
    $sql = 'UPDATE mm_umzuege SET berechnet_am = NOW(), vorgangsnummer = :wwsnr WHERE aid IN('.implode(',', $aids).')';
    $db->query($sql, array('wwsnr' => $wwsnr));    
    //    echo $db->error() . '<br>' . $db->lastQuery . '<br>';
}

$timeVon = (preg_match('/^(\d{4})W(\d{2})$/', $kwvon, $m)) ? strtotime($kwvon) : strtotime(date('Y').'W'.substr('0'.date('W'),-2) );
if ($kwbis && preg_match('/^(\d{4})W(\d{2})$/', $kwbis, $m)) {
    $timeBis = strtotime($kwbis);
} else {
    $timeBis = strtotime('+7 days', $timeVon);
}

$kwvon = date('Y\WW', $timeVon);
$kwbis = date('Y\WW', $timeBis);
//echo 'Von: ' . $kwvon . ' => '. date( 'r', $timeVon) . '<br/>';
//echo 'Bis: ' . $kwbis . ' => '. date( 'r', $timeBis) . '<br/>';

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
);

$having = array();
$w = array();
foreach($validFields as $_f) {
    $sqlQueryField = $_f;
    if ($_f == 'Wirtschaftseinheit') $sqlQueryField = 'g.id';
    elseif ($_f == 'aid') $sqlQueryField = 'a.aid';
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

$sql = 'SELECT a.*, user.personalnr, user.personalnr AS kid, '
      .' g.id Wirtschaftseinheit, g.bundesland, g.stadtname, g.adresse, '
      .' u.nachname, u.nachname stom, '
      .' SUM(if(lm.preis, lm.preis, preis_pro_einheit) * ul.menge_mertens * IFNULL(ul.menge2_mertens,1)) AS summe'
      .' FROM mm_umzuege a '
      .' LEFT JOIN mm_user user ON (a.antragsteller_uid = user.uid) '
      .' LEFT JOIN mm_stamm_gebaeude g ON a.gebaeude = g.id '
      .' LEFT JOIN mm_user u ON g.standortmanager_uid = u.uid '
      .' LEFT JOIN mm_umzuege_leistungen ul ON (a.aid = ul.aid) '
      .' LEFT JOIN mm_leistungskatalog l ON(ul.leistung_id = l.leistung_id) ' . "\n"
      .' LEFT JOIN mm_leistungspreismatrix lm ON('
      .'    l.leistung_id = lm.leistung_id '
      .'    AND lm.mengen_von <= (ul.menge_mertens * IFNULL(ul.menge2_mertens,1)) '
      .'    AND (lm.mengen_bis >= ( ul.menge_mertens * IFNULL(ul.menge2_mertens,1)))'
      .' ) '
      .' WHERE '
     // .' abgeschlossen_am IS NOT NULL AND '
      .' abgeschlossen = "Ja" AND abgeschlossen_am IS NOT NULL AND abgeschlossen_am BETWEEN :von AND :bis '
      .(!$all ? 'AND berechnet_am IS NULL' : '')
      .( count($w) ? ' AND ('  . implode(' AND ', $w) . ') ' : '')
      .' GROUP BY a.aid'
      .( count($having) ? ' HAVING (' . implode(' AND ', $having) . ')' : '')
      .' ORDER BY ' . $sqlOrderFld. ' ' . $odir;
$rows = $db->query_rows($sql, 0, array('von'=>date('Y-m-d', $timeVon), 'bis'=>date('Y-m-d',$timeBis)));

if (0) {
    echo $db->error() . '<br>' . $db->lastQuery . '<br>' . PHP_EOL;
}

$Tpl->assign('s', $s);
$Tpl->assign('all', $all);
$Tpl->assign('wwsnr', $wwsnr);
$Tpl->assign('Auftraege', $rows);
$Tpl->assign('kw_options', $kw_options);
$Tpl->assign('kwvon', $kwvon);
$Tpl->assign('kwbis', $kwbis);
$Tpl->assign('order', $order);
$Tpl->assign('odir', $odir);
$Tpl->assign('s', $s);
$Tpl->assign('q', $query);
$body_content = $Tpl->fetch("auswertung_form.html");

