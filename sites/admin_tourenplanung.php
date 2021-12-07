<?php
$Tpl = new myTplEngine();

$NL = "\n";
$error = '';
$debugInfos = [];
$datumvon = (!empty($_REQUEST['datumvon']))   ? $_REQUEST['datumvon'] : '';
$datumbis = (!empty($_REQUEST['datumbis']))   ? $_REQUEST['datumbis'] : '';
$datumfeld = (!empty($_REQUEST['datumfeld'])) ? $_REQUEST['datumfeld'] : 'antragsdatum';
$statByTour = (!empty($_REQUEST['t']))   ? $_REQUEST['t'] : '';
$statByDatum = (!empty($_REQUEST['d']))   ? $_REQUEST['d'] : '';
$exportFormat = getRequest('format', 'html');

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
$tourkennung = (!empty($_REQUEST['tourkennung']))   ? trim($_REQUEST['tourkennung']) : '';
$tourdatum = (!empty($_REQUEST['tourdatum']))   ? trim($_REQUEST['tourdatum']) : '';
$all   = (!empty($_REQUEST['all']))     ? (int)$_REQUEST['all'] : 1;

if ($statByTour) {
    $query['tour_kennung'] = $statByTour;
    $aAuftragsstatus = [ 'beauftragt', 'disponiert', ];
    $sql = 'SELECT aid, count(1) numAuftraege, '
        . ' MIN(antragsdatum) min_at, MAX(antragsdatum) max_at, '
        . ' MIN(umzugstermin) min_lt, MAX(umzugstermin) max_lt, '
        . ' GROUP_CONCAT(DISTINCT(umzugsstatus)) csv_status, '
        . ' SUM( IF(IFNULL(umzugstermin, "") = "", 1, 0)) numOhneTermin'
        . ' FROM mm_umzuege '
        . ' WHERE tour_kennung LIKE :tour_kennung';
    $row = $db->query_row($sql, [ 'tour_kennung' => $statByTour ]);

    if ((int)$row['numAuftraege'] > 0) {
        if ( (int)$row['numOhneTermin'] > 0) {
            $datumfeld = 'antragsdatum';
            if ($row['min_at'] < $datumvon) {
                $datumvon = $row['min_at'];
            }
            if ($row['max_at'] > $datumbis) {
                $datumbis = $row['max_at'];
            }
        } else {
            $datumfeld = 'umzugstermin';
            if ($row['min_lt'] < $datumvon) {
                $datumvon = $row['min_lt'];
            }
            if ($row['max_lt'] > $datumbis) {
                $datumbis = $row['max_lt'];
            }
        }
        $aStatus = array_map('trim', explode(',', $row['csv_status']));
        $aAuftragsstatus = array_merge($aAuftragsstatus, $aStatus);
    }
} elseif ($statByDatum) {
    $testTimeOfDatum = strtotime($statByDatum);
    if ($testTimeOfDatum) {
        $aAuftragsstatus = [ 'beauftragt', 'disponiert', ];

        $statsByDatum = date('Y-m-d', $testTimeOfDatum);

        $datumvon = $statsByDatum;
        $datumbis = $statsByDatum;
        $datumfeld = 'umzugstermin';
    }
}


if (in_array('beauftragt', $aAuftragsstatus)) {
    $aWhereStatusAnyOf[] = ' (umzugsstatus = "beauftragt" OR IFNULL(antragsdatum, "") != "") ' . "\n";
}
if (in_array( 'avisiert', $aAuftragsstatus)) {
    $aWhereStatusAnyOf[] = ' (umzugsstatus = "bestaetigt") ' . "\n";
}
if (in_array('abgeschlossen', $aAuftragsstatus)) {
    $aWhereStatusAnyOf[] = ' (umzugsstatus = "abgeschlossen" or abgeschlossen="Ja") ' . "\n";
}
if (in_array('disponiert', $aAuftragsstatus)) {
    $aWhereStatusAnyOf[] = ' (IFNULL(tour_kennung, "") != "") ' . "\n";
}

if ($finish && ($tourkennung || $tourdatum) && is_array($aids) && count($aids)) {
    $debugInfos[] = '#' . __LINE__;
    $aids = array_map('intval', $aids);
    $iNumConflicts = 0;

    if ($tourkennung && $tourdatum) {
        $sqlCount = 'mm_umzuege 
          WHERE 
            umzugstermin NOT LIKE ' . $db::quote($tourdatum) . '  
            AND umzugsstatus NOT IN ("beantragt", "angeboten")
            AND aid IN('.implode(',', $aids).')';

        $iNumConflicts = $db->query_count($sqlCount);
        $sConflictErr = '';

        if ($iNumConflicts > 0) {
            $sConflictErr = '<div>Für die geplante Tourenzuweisung bestehen ' . $iNumConflicts . ' Konflikte!<br>' . "\n"
                . 'Für bereits avisierte Touren kann das Lieferdatum mit dieser Funktion nicht geändert werden.<br>' . "\n"
                . 'Wechsel hierzu bitte in den einzelnen Auftrag.</div>';
            $error.= $sConflictErr;
        }
        $debugInfos[] =  $db->lastQuery . ";\ncount \$iNumConflicts: $iNumConflicts\nError:  $sConflictErr\n";
    }
    if (!empty($tourdatum)) {
        if (!preg_match('#^(2\d{3}-\d\d-\d\d|\d\d?.\d\d?.\d\d\d\d)$#', $tourdatum)) {
            $error.= '<div>Ungültiges Datumsformat für das Tourdatum. Zulässig ist TT.MM.YYYY oder YYYY-MM-TT</div>';
        } else {
            $t = strtotime($tourdatum);
            if (!$t) {
                $error.= '<div>Ungültige Datumsangabe für Tourdatum: ' . $tourdatum . '</div>';
            }
        }
        $tourdatum = date('Y-m-d', strtotime($tourdatum));

    }

    if (!$error) {
        if ($tourkennung) {
            $debugInfos[] = '#' . __LINE__;
            $sql = 'UPDATE mm_umzuege SET '
                . ' tour_zugewiesen_am = NOW(), '
                . ' tour_zugewiesen_von = :username, '
                . ' tour_kennung = :tour_kennung '
                . ' WHERE aid IN(' . implode(',', $aids) . ')'
                . ' AND IFNULL(tour_kennung, "") NOT LIKE :tour_kennung2';
            $db->query($sql, array('tour_kennung' => $tourkennung, 'tour_kennung2' => $tourkennung, 'username' => $user['user']));
        }
        if ($tourdatum) {
            $sql = 'UPDATE mm_umzuege SET '
                . ' tour_zugewiesen_am = NOW(), '
                . ' tour_zugewiesen_von = :username, '
                . ' umzugstermin = :tourdatum '
                . ' WHERE aid IN(' . implode(',', $aids) . ')'
                . ' AND IFNULL(umzugstermin, "") NOT LIKE :tourdatum2';
            $db->query($sql, array('tourdatum' => $tourdatum, 'tourdatum2' => $tourdatum, 'username' => $user['user']));
        }
        // echo $db->error() . '<br>' . $db->lastQuery . '<br>';
    }
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
    'aid' => array('field'=> 'a.aid'),
    'kid' => array('field'=> 'user.personalnr'),
    'land' => array('field'=> 'a.land'),
    'ort' => array('field'=> 'a.ort'),
    'plz' => array('field'=> 'a.plz'),
    'strasse' => array('field'=> 'a.strasse'),
    'antragsdatum' => array('field'=> 'a.antragsdatum'),
    'umzugstermin' => array('field'=> 'a.umzugstermin'),
    'tour_kennung' => array('field'=> 'a.tour_kennung'),
    'Leistungen' => array('field'=> 'Leistungen'),
    'summe' => array('field'=> 'summe'),
    'vorgangsnummer' => array('field'=> 'a.vorgangsnummer'),
    'nachname' => array('field'=> 'user.name'),
    'Wirtschaftseinheit' => array('field'=> 'g.id'),
    'abgeschlossen_am' => array('field'=> 'a.abgeschlossen_am'),
    'abgerechnet_am' => array('field'=> 'a.berechnet_am'),
    'berechnet_am' => array('field'=> 'a.berechnet_am'),
);

$having = array();
$w = array();
foreach($validFields as $_f => $_fOpts) {
    $sqlQueryField = $_fOpts['field'];
    if ($_f === 'Leistungen' && !empty($query[$_f])) {
        $chars = str_split( trim($query[$_f]));
        $chars = preg_replace('#[^A-Z]#', '', $chars);

        foreach($chars as $_chr) {
            $having[] = 'GROUP_CONCAT(kategorie_abk) LIKE "%' . $_chr . '%"';
        }
        break;
    }
    elseif ($_f === 'land' && !empty($query[$_f]) && strcmp(trim($query[$_f]), 'NL') === 0 ) {
        $w[] = 'a.land LIKE "Niederlande"';
    }
    if (!empty($query[$_f])) {
        if (preg_match('#^(' . preg_quote('!=', '#') .')(.*)$#', trim($query[$_f]), $m)) {
            $_q = 'IFNULL(' . $sqlQueryField . ',"") ' . $m[1] . $db->quote($m[2]);
        }
        elseif (preg_match('/^(!)(.+)$/', trim($query[$_f]), $m)) {
            $_q = 'IFNULL(' . $sqlQueryField . ',"") NOT LIKE ' . $db->quote( str_replace('*','%', $m[2]) . '%');
        }
        elseif (preg_match('/^([<>=]{1,2})(.+)$/', trim($query[$_f]), $m)) {
            $_q = 'IFNULL(' . $sqlQueryField . ',"") ' . $m[1] . $db->quote($m[2]);
        } else {
            $_q = 'IFNULL(' . $sqlQueryField . ',"") LIKE ' . $db->quote( str_replace('*','%', $query[$_f]) . '%');
        }

        if ($_f !== 'summe') {
            $w[] = $_q;
        }
        else {
            $having[] = $_q;
        }
    }
}
$debugInfos[] = json_encode(compact('validFields', 'query', 'w', 'having', 'aWhereStatusAnyOf'), JSON_PRETTY_PRINT);

if (!in_array($order, array_keys($validFields))) {
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

$sqlSelect = 'SELECT a.*, user.personalnr, user.personalnr AS kid, ' . $NL
    . ' g.id Wirtschaftseinheit, g.bundesland, g.stadtname, g.adresse, ' . $NL
    . ' u.nachname, u.nachname stom, ' . $NL
    . ' GROUP_CONCAT(lk.kategorie_abk ORDER BY leistungskategorie SEPARATOR "") AS Leistungen, ' . $NL
    . '   GROUP_CONCAT(' . $NL
    . '     IF (l.leistung_id is NULL, "", CONCAT_WS("<|#|>", '  . $NL
    . '      l.leistung_id, lk.kategorie_abk, lk.leistungskategorie, ' . $NL
    . '      l.Bezeichnung, l.Farbe, l.Groesse, "€", l.preis_pro_einheit' . $NL
    . '     )) '
    . '     ORDER BY leistungskategorie SEPARATOR ";\n"' . $NL
    . '   ) AS LeistungenFull, ' . $NL
    . ' SUM(if(lm.preis, lm.preis, preis_pro_einheit) * ul.menge_mertens * IFNULL(ul.menge2_mertens,1)) AS summe' . $NL;

$sqlFrom = ' FROM mm_umzuege a ' . $NL
    . ' LEFT JOIN mm_user user ON (a.antragsteller_uid = user.uid) ' . $NL
    . ' LEFT JOIN mm_stamm_gebaeude g ON a.gebaeude = g.id ' . $NL
    . ' LEFT JOIN mm_user u ON g.standortmanager_uid = u.uid ' . $NL
    . ' LEFT JOIN mm_umzuege_leistungen ul ON (a.aid = ul.aid) ' . $NL
    . ' LEFT JOIN mm_leistungskatalog l ON(ul.leistung_id = l.leistung_id) ' . $NL
    . ' LEFT JOIN mm_leistungskategorie lk ON(l.leistungskategorie_id = lk.leistungskategorie_id) ' . $NL
    . ' LEFT JOIN mm_leistungspreismatrix lm ON(' . $NL
    . '    l.leistung_id = lm.leistung_id ' . $NL
    . '    AND lm.mengen_von <= (ul.menge_mertens * IFNULL(ul.menge2_mertens,1)) ' . $NL
    . '    AND (lm.mengen_bis >= ( ul.menge_mertens * IFNULL(ul.menge2_mertens,1)))' . $NL
    . ' ) ';

$sqlWhere = ' WHERE ' . $NL
    . ' ' . $datumfeld . ' BETWEEN :von AND :bis ' . $NL
    . (!$all ? 'AND IFNULL(tour_kennung, "") = ""' . $NL : '')
    . ( count($w) ? ' AND ('  . implode(' AND ', $w) . ') ' . $NL : '');

    if (count($aWhereStatusAnyOf)) {
        $sqlWhere.= ' AND ( ' . implode(' OR ', $aWhereStatusAnyOf) . ')' . $NL;
    }

$sqlGroup = ' GROUP BY a.aid' . $NL;
$sqlHaving = ( count($having) ? ' HAVING (' . implode(' AND ', $having) . ')' : '');
$sqlOrder = ' ORDER BY ' . $sqlOrderFld. ' ' . $odir . $NL;
$sqlLimit = '';

$sql = $sqlSelect . $sqlFrom . $sqlWhere . $sqlGroup . $sqlHaving . $sqlOrder . $sqlLimit;
$rows = $db->query_rows($sql, 0, array('von'=> $datumvon, 'bis'=> $datumbis));
$debugInfos[] =  $db->lastQuery;

if ($exportFormat !== 'html' && is_array($rows) && count($rows)) {
    require_once( $ModulBaseDir . 'excelexport/helper_functions.php');

    $iNumItems = count($all);

    $aSelectCols = [
        'summe', 'aid', 'kid', 'tour_kennung', 'service', 'plz', 'ort', 'strasse',
        'land', 'Leistungen', 'antragsdatum', 'Lieferdatum', 'tour_zugewiesen_am',
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

    /*
    $sheet02Name = 'KumulierteLeistungen';
    $sheet02Header = $assocLeistungsRowToSheetHeader($artikelStat[0] );
    $writer->writeSheetHeader($sheet02Name, $sheet02Header);
    foreach($artikelStat as $_row) {
        $writer->writeSheetRow($sheet02Name, $_row);
    }
    */

    header('Content-Type: application/xls');
    header('Content-Disposition: attachment; filename="UniperTourenplanungVom' . date('YmdHi') . '.xlsx"');
    $writer->writeToStdOut();

    exit;
}

if (0) {
    echo $db->error() . '<br>' . $db->lastQuery . '<br>' . PHP_EOL;
}
$kwvon = date('Y\WW', strtotime($datumvon));
$kwbis = date('Y\WW', strtotime($datumbis));

$Tpl->assign('s', $s);
$Tpl->assign('error', $error);
$Tpl->assign('all', $all);
$Tpl->assign('tourkennung', $tourkennung);
$Tpl->assign('tourdatum', $tourdatum);
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

$body_content.= "<div id='toggleDebugInfos' style='margin-top:2rem;opacity:0.5;display:inline-block'>...</div>";
$body_content.= '<div id="debugInfosContainer" class="debug-infos-container" style="display:none;overflow: scroll;height: calc(100vh - 20px);width: 100%;">' . $NL;
foreach($debugInfos as $_dbg) {
    $body_content.= '<pre style="color:#0ba1b5;font-size:11px;border:1px solid #0ba1b5;padding:1rem;border-radius:5px;background-color: #dedede;margin:5px;">'
        . $_dbg . '</pre>' . $NL;
}
$body_content.= '</div>' . $NL;

