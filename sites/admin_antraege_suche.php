<?php

if (!function_exists('getLaenderKuerzelByLand')) {
    function getLaenderKuerzelByLand($land) {
        switch($land) {
            case 'Belgien':
                return 'BE';
            case 'Deutschland':
                return 'D';
            case 'England':
                return 'EN';
            case 'Niederlande':
                return 'NL';
                break;
            case 'Ungarn':
                return 'HU';

            default:
                return $land;
        }
    }
}

$op = ''; // Ausgabebuffer
if (basename($_SERVER['PHP_SELF'])==basename(__FILE__)) {
	require_once('../header.php');
}

if (empty($s)) {
	$s = getRequest('s', 'umzugssuche');
}
$top = getRequest('top', '');
$queryForm = file_get_contents($HtmlBaseDir.'admin_antraege_suche.html');
$queryForm = str_replace("{s}", $s, $queryForm);
require_once($InclBaseDir.'parse_userquery.php');

$Tpl = new myTplEngine();
$Tpl->assign('cat', 'suche');
$Tpl->assign('allusers', 1);
$Tpl->assign('top', $top);

$searchFields = array(
	'a.aid' => '',
    'a.vorname' => '',
    'a.name' => '',
	'a.ort' => '',
    'a.plz' => '',
    'a.strasse' => '',
    'a.land' => '',
	'a.gebaeude' => '',
	'a.umzugstermin' => '',
	'a.umzugsstatus' => '',
	'a.tour_kennung' => '',
	'kk.leistungskategorie' => [],
	'u.kid' => '',
	'm.name' => '',
	'm.vorname' => '',
	'm.nutzung' => '',
	'm.extern_firma' => '',
    'm.ort' => '',
	'm.gebaeude' => '',
	'm.etage' => '',
	'm.raumnr' => '',
	'm.gf' => '',
	'm.bereich' => '',
	'm.abteilung' => '',
	'm.ziel_ort' => '',
	'm.ziel_gebaeude' => '',
	'm.ziel_etage' => '',
	'm.ziel_raumnr' => '',
	'm.ziel_gf' => '',
	'm.ziel_bereich' => '',
	'm.ziel_abteilung' => '',
    'm.umzugsart' => '',
    'a.bemerkungsstatus' => '',
    'a.service' => '',
    'lk.leistung' => '',
);

$defaultOrder = "ORDER BY a.umzugstermin";
$q = getRequest('q');
$sendquery = getRequest('sendquery');
$offset = getRequest('offset', 0);
$limit = getRequest('limit', 50);
$ofld = getRequest('ofld', '');
$odir = getRequest('odir', 'ASC');
$View = getRequest('View', 'Antraege');
$tabs = getRequest('tabs', 'antraege');
$trackGetQuery = '&sendquery=1';

$originOFld = $ofld;
//echo "<pre>".print_r($q,1)."</pre>\n";
//if (is_array($q)) foreach($q as $k => $v) echo "case \"".$k."\":<br>\n";
if ($ofld === 'TicketID') {
    $ofld = 'a.aid';
}

if (in_array($ofld, ['Strasse', 'Ort', 'PLZ', 'Land', 'Vorname', 'Name'])) {
	$ofld = 'a.' . strtolower($ofld);
} elseif($ofld === 'Liefertermin') {
	$ofld = 'a.umzugstermin';
} elseif($ofld === 'Status') {
	$ofld = 'a.umzugsstatus';
} elseif($ofld === 'kid') {
	$ofld = 'u.personalnr';
}

if ($ofld && isset($searchFields[$ofld])) {
	$orderBy = 'ORDER BY ' . $ofld . ' ' . ($odir != 'DESC' ? 'ASC' : 'DESC');
} elseif($ofld === 'Leistungen') {
    $orderBy = 'ORDER BY GROUP_CONCAT(
            CONCAT(
			 	kategorie_abk, 
				IF( IFNULL(leistung_abk, "") != "", CONCAT(":", leistung_abk, "|"), ""),
				""
			)ORDER BY leistungskategorie)' . ($odir != 'DESC' ? 'ASC' : 'DESC');
} else {
	$orderBy = $defaultOrder;
}

if (!empty($q['kk.leistungskategorie'])) {
    $queryForm = str_replace('[/*aCheckedKategorienJson*/]', json_encode($q['kk.leistungskategorie']), $queryForm);
} else {
    $queryForm = str_replace('[/*aCheckedKategorienJson*/]', '[]', $queryForm);
}

if (!empty($q['a.service'])) {
    $queryForm = str_replace('[/*aCheckedServiceJson*/]', json_encode($q['a.service']), $queryForm);
} else {
    $queryForm = str_replace('[/*aCheckedServiceJson*/]', '[]', $queryForm);
}

foreach($searchFields as $f => $v) {
	if (isset($q[$f])) {
	    $searchFields[$f] = $q[$f];
    }

	if (empty($searchFields[$f])) {
        $queryFormReplacePart = '';
    } elseif (is_scalar($searchFields[$f])) {
        $queryFormReplacePart = fb_htmlEntities($searchFields[$f]);
    }
	$queryForm = str_replace('{'."q[$f]".'}', $queryFormReplacePart, $queryForm);

	if ($searchFields[$f]) {
	    if (is_array($searchFields[$f])) {
	        foreach($searchFields[$f] as $fkey => $fval) {
                $trackGetQuery .= "&q[$f][" . (is_numeric($fkey) ? '' : $fkey) . "]=" . urlencode($fval);
            }
        } else {
            $trackGetQuery .= "&q[$f]=" . urlencode($searchFields[$f]);
        }
    }
}
$queryForm = str_replace("chck_View=\"".$View."\"", "checked=\"true\"", $queryForm);
$ListBaseLink = "?s=$s" . $trackGetQuery . '&View=' . urlencode($View);

if ($sendquery) {
	$sqlWhereMa = '';
	$sqlWhereUA = '';
    $sqlWhereUsr = '';
    $sqlWhereKtg = '';
    $sqlWhereLst = '';
	if (!empty($q)) {
		foreach($searchFields as $qField => $userQuery) {
			if (!empty($userQuery)) {
				
				switch($qField) {
					case 'a.aid':
					case 'a.name':
                    case 'a.ort':
                    case 'a.plz':
                    case 'a.strasse':
                    case 'a.land':
                    case 'a.vorname':
                    case 'a.name':
                    case 'a.umzugsstatus':
					case 'a.gebaeude':
					case 'a.umzugstermin':
					case 'a.umzugsstatus':
                    case 'a.tour_kennung':
					$dbField = $qField;
					$sqlWhereUA.= ($sqlWhereUA ? 'AND ' : '') . ' ';
					$aUQueryParts = userquery_parse($userQuery, 'Both');
					$sqlWhereUA.= userquery_parts2sql($aUQueryParts, $dbField);
					$sqlWhereUA.= ")\n";
					break;

                    case 'a.service':
                        $dbField = $qField;
                        $sqlWhereUA.= ($sqlWhereUA ? 'AND ' : '') . ' (';

                        if (is_array($userQuery) && count($userQuery)) {
                            $aQuotedVals = array_map(function ($val) use ($db) {
                                return $db::quote($val);
                            }, $userQuery);
                            $sqlWhereUA = $qField . ' in (' . implode(', ', $aQuotedVals) . ')' . "\n";
                        } elseif (is_string($userQuery) && trim($userQuery) !== '') {
                            $sqlWhereUA.= ($sqlWhereUA ? ' AND ' : '');
                            $sqlWhereUA.= $qField . ' LIKE ' . $db::quote($userQuery) . "\n";
                        }

                        $sqlWhereUA.= " \n";
                        break;

                    case 'a.bemerkungsstatus':
                        $mitBmk = ' LENGTH(TRIM(IFNULL(a.bemerkungen, ""))) > 0 ';
                        $ohneBmk = ' LENGTH(TRIM(IFNULL(a.bemerkungen, ""))) = 0 ';
                        switch($userQuery) {
                            case 'allemit':
                                $sqlWhereUA.= ($sqlWhereUA ? ' AND ' : '') . $mitBmk;
                                break;

                            case 'neue':
                                $sqlWhereUA.= ($sqlWhereUA ? ' AND ' : '') . $mitBmk;
                                $sqlWhereUA.= ' AND IFNULL(a.neue_bemerkungen_fuer_admin, 0) > 0';
                                break;

                            case 'gelesene':
                                $sqlWhereUA.= ($sqlWhereUA ? ' AND ' : '') . $mitBmk;
                                $sqlWhereUA.= ' AND IFNULL(a.neue_bemerkungen_fuer_admin, 0) = 0';
                                break;

                            case 'none':
                                $sqlWhereUA.= ($sqlWhereUA ? ' AND ' : '') . $ohneBmk;
                                break;
                        }
                        break;

                    case 'kk.leistungskategorie':
                        if (is_array($userQuery) && count($userQuery)) {

                            $_angebotsQuery = '';
                            $_kategorieQuery = '';
                            if (in_array('Angebot', $userQuery)) {
                                $userQuery = array_slice(array_filter($userQuery, function($v) { return $v !== 'Angebot'; }), 0);
                                $_angebotsQuery = ' IFNULL(a.angeboten_am, "") != "" OR kk.leistungsart = "Angebot"';
                            }

                            $sqlWhereKtg.= ($sqlWhereKtg ? ' AND ' : '');
                            if (count($userQuery)) {
                                $aQuotedVals = array_map(function ($val) use ($db) {
                                    return $db::quote($val);
                                }, $userQuery);
                                $_kategorieQuery = $qField . ' in (' . implode(', ', $aQuotedVals) . ')' . "\n";
                            }

                            if ($_kategorieQuery && $_angebotsQuery) {
                                $sqlWhereKtg.= ' (' . $_kategorieQuery . ' OR ' . $_angebotsQuery . ')';
                            } elseif ($_angebotsQuery) {
                                $sqlWhereKtg.= $_angebotsQuery;
                            } else {
                                $sqlWhereKtg.= $_kategorieQuery;
                            }

                        } elseif (is_string($userQuery) && trim($userQuery) !== '') {
                            $sqlWhereKtg.= ($sqlWhereKtg ? ' AND ' : '');
                            $sqlWhereKtg.= $qField . ' LIKE ' . $db::quote($aQuotedVals) . "\n";
                        }
                        break;
					
					case 'm.name':
					case 'm.vorname':
					case 'm.nutzung':
					case 'm.extern_firma':
					case 'm.ort':
					case 'm.gebaeude':
					case 'm.etage':
					case 'm.raumnr':
					case 'm.gf':
					case 'm.bereich':
					case 'm.abteilung':
					case 'm.ziel_ort':
					case 'm.ziel_gebaeude':
					case 'm.ziel_etage':
					case 'm.ziel_raumnr':
					case 'm.ziel_gf':
					case 'm.ziel_bereich':
					case 'm.ziel_abteilung':
					case 'm.umzugsart':
					$dbField = ($qField!='m.nutzung' ? $qField : 'm.extern');
					$sqlWhereMa.= ($sqlWhereMa?'AND ':'')." (";
					$aUQueryParts = userquery_parse($userQuery, 'Both');
					$sqlWhereMa.= userquery_parts2sql($aUQueryParts, $dbField);
					$sqlWhereMa.= ")\n";
					break;

                    case 'u.kid':
                    case 'u.personalnr':
                        $dbField = 'u.personalnr';
                        $sqlWhereUsr.= ($sqlWhereUsr ? 'AND ' : '') . " (";
                        $aUsrQueryParts = userquery_parse($userQuery, 'Both');
                        $sqlWhereUsr.= userquery_parts2sql($aUsrQueryParts, $dbField);
                        $sqlWhereUsr.= ")\n";
                        break;

                    case 'lk.leistung':
                        $dbField = 'CONCAT_WS(l.Bezeichnung, l.Beschreibung, l.Farbe, l.Groesse)';
                        $aUsrQueryParts = userquery_parse($userQuery, 'Both');
                        $sqlWhereLst = userquery_parts2sql($aUsrQueryParts, $dbField);
                        break;

					default:
					$dbField;
				}
				
			}
		}
	}
	
	if ($View == 'Antraege') {
		
		$sqlSelect = 'Select a.aid, a.aid AS AID, u.personalnr AS kid, '  . "\n"
            . ' a.tour_kennung AS Tour, ' . "\n"
            . ' CONCAT(a.name, ", ", substr(a.vorname, 1, 1), ".") AS Name, a.ort AS Ort, a.plz AS PLZ, a.strasse AS Strasse, ' . "\n"
			. ' a.land AS Land, a.umzugstermin AS Liefertermin, a.umzugsstatus AS Status, ' . "\n"
            . ' GROUP_CONCAT(
                     CONCAT(
                        kategorie_abk, 
                        IF( IFNULL(leistung_abk, "") != "", CONCAT(":", leistung_abk, "|"), ""),
                        ""
                    ) ORDER BY leistungskategorie) AS Leistungen, ' . "\n"
            . ' SUM(if(lm.preis, lm.preis, preis_pro_einheit) * ul.menge_mertens * IFNULL(ul.menge2_mertens,1)) AS summe ' . "\n"
        ;
		$sqlFrom = 'FROM `' . $_TABLE['umzugsantrag'] . '` a ' . "\n"
			. ' LEFT JOIN `' . $_TABLE['user'] . '` u ON (a.antragsteller_uid = u.uid) ' . "\n"
            . ' LEFT JOIN mm_umzuege_leistungen ul ON (a.aid = ul.aid) ' . "\n"
            . ' LEFT JOIN mm_leistungskatalog l ON (ul.leistung_id = l.leistung_id) ' . "\n"
            . ' LEFT JOIN mm_leistungskategorie kk ON (l.leistungskategorie_id = kk.leistungskategorie_id) ' . "\n"
            . ' LEFT JOIN mm_leistungspreismatrix lm ON(' . "\n"
            . '    l.leistung_id = lm.leistung_id ' . "\n"
            . '    AND lm.mengen_von <= (ul.menge_mertens * IFNULL(ul.menge2_mertens,1)) ' . "\n"
            . '    AND (lm.mengen_bis >= ( ul.menge_mertens * IFNULL(ul.menge2_mertens,1)))' . "\n"
            . ' ) ' . "\n"
		;
		$sqlWhere = "WHERE 1\n";
		if ($sqlWhereUA) {
            $sqlWhere.= ' AND ' . $sqlWhereUA . "\n";
        }
		if ($sqlWhereMa) {
            $sqlWhere.= 'AND a.aid IN(SELECT aid FROM `' . $_TABLE['umzugsmitarbeiter'] . "` m WHERE $sqlWhereMa)\n";
        }
        if ($sqlWhereUsr) {
            $sqlWhere.= 'AND a.antragsteller_uid IN(SELECT uid FROM `' . $_TABLE['user'] . "` u WHERE $sqlWhereUsr)\n";
        }
        if ($sqlWhereKtg) {
            $sqlWhere.= 'AND a.aid IN(
                    SELECT distinct(a.aid) 
                    FROM mm_umzuege a 
                    JOIN mm_umzuege_leistungen ul ON(a.aid=ul.aid)
                    JOIN mm_leistungskatalog l ON (ul.leistung_id = l.leistung_id)
                    JOIN mm_leistungskategorie kk ON (l.leistungskategorie_id = kk.leistungskategorie_id)
                    WHERE ' . $sqlWhereKtg . '
                )';
        }
        if ($sqlWhereLst) {
            $sqlWhere.= 'AND a.aid IN(
                    SELECT distinct(ul.aid) 
                    FROM mm_umzuege_leistungen ul
                    JOIN mm_leistungskatalog l ON (ul.leistung_id = l.leistung_id)
                    JOIN mm_leistungskategorie k ON (l.leistungskategorie_id = k.leistungskategorie_id)
                    WHERE ' . $sqlWhereLst . '
                )';
        }
		$sqlGroup = "GROUP BY a.aid\n";
		$sqlOrder = $orderBy . "\n";
		$sqlLimit = "LIMIT $offset, $limit";

		$sqlCount = 'SELECT count(1) AS count FROM (SELECT a.aid ' . $sqlFrom . $sqlWhere . $sqlGroup . ') AS t';
//        echo "<pre>".print_r($sqlCount,1)."</pre>\n";
		$row = $db->query_singlerow($sqlCount);
		$num_all = $row['count'];

		$sql = $sqlSelect . $sqlFrom . $sqlWhere . $sqlGroup . $sqlOrder . $sqlLimit;
//        echo "<pre>".print_r($sql,1)."</pre>\n";
		$rows = $db->query_rows($sql);
		$num = count($rows);
	} else {
		$sql = 'Select ' . "\n"
            . 'a.aid, a.umzugstermin, a.umzugsstatus, m.name, ' . "\n"
            . 'm.vorname, m.ort, m.gebaeude, m.etage, m.raumnr, ' . "\n"
            . 'm.ziel_ort, m.ziel_gebaeude, m.ziel_etage, m.ziel_raumnr ' . "\n";
		$sql.= 'FROM `' . $_TABLE['umzugsmitarbeiter'] . '` AS m' . "\n"
			. '` LEFT JOIN `' . $_TABLE['umzugsantrag'] . '` a USING (aid) ' . "\n"
		//	. '` LEFT JOIN `' . $_TABLE['user']."` a ON (a.antragsteller_uid = user.uid) \n"
		;
		$sql.= "WHERE 1\n";
		if ($sqlWhereMa) {
		    $sql.= ' AND ' . $sqlWhereMa . "\n";
        }
		if ($sqlWhereUA) {
		    $sql.= ' AND ' . $sqlWhereUA . "\n";
        }
        $sqlOrder = $orderBy . "\n";
        $sqlLimit = "LIMIT $offset, $limit";

        $sqlCount = 'SELECT count(1) FROM (' . $sql . ') AS t';
        $row = $db->query_singlerow($sql);
        $num_all = $row['count'];

        $sql.= $sqlOrder . $sqlLimit;
		$rows = $db->query_rows($sql);
		$num = count($rows);
		//echo "<pre>".print_r($rows,1)."</pre>\n";
	}
}
$op.= $queryForm;

$withRowNumbers = false;
$withActionColumn = false;

if ($sendquery) {
	if (is_array($rows) && count($rows)) {
		$rlist_nav = new listbrowser(array(
			'offset'     => $offset,
			'limit'      => $limit,
			'num_result' => $num,
			'num_all'    => $num_all,
			'baselink'   => $ListBaseLink."&offset={offset}&limit={limit}&ofld=$ofld&odir=$odir"));
		$rlist_nav->render_browser();

        $rows2Tbl = <<<EOT
        <style>
        #legendKategorien {
            float:right;
            display: inline-block;
            border:1px solid #ededed;
            border-radius: 4px;
            background: #f7f7f7;
            font-size:x-small;
            color: #484848;            
        }
        #legendKategorien::after {
           clear: both; 
        }
        #legendKategorien legend {
            font-size: x-small;
            line-height: 130%;
            font-weight: bold;
            margin-left:0.6rem;
            border:1px solid #ededed;
            border-radius: 4px;
            background: #ffffff;
        }
        #legendKategorien .content {
            font-size: x-small;
            margin: .2rem 0.6rem;
            line-height: 120%;
        }
        </style>
        <fieldset class="legend" id="legendKategorien" style="margin-top:-1rem">
            <legend>Kategorien</legend>
            <div class="content">T=Schreibtisch, S=Stuhl, R=Rabatt, P=Transportpostion, A=Angebot, H=Rückholung</div>
        </fieldset>
EOT;
		// $rows2Tbl.= '<pre>' . htmlentities($sql) . '</pre>';
        $currYear = date('Y');
		$rows2Tbl.= $rlist_nav->get_nav('all')."\n";
		//if ($db->error()) 
		//$rows2Tbl.= $db->error()."<br>\nsql:".$sql."<br>\n";
		$wz = '';
		$rows2Tbl.= '<table id="auftragsSuchResult" class="tblList" width="100%" border=1 cellpadding=1 cellspacing=0>' . "\n";
		$rows2Tbl.= "<thead>\n";
		if ($withRowNumbers) {
            $rows2Tbl .= '<td>#</td>';
        }
		if ($withActionColumn) {
            $rows2Tbl .= '<td colspan=1>Aktion</td>';
        }
		foreach($rows[0] as $fld => $v) {
		    $_lnk = $ListBaseLink."&ofld=$fld&odir=".listbrowser::get_oDir($fld, $originOFld, $odir);
		    $_tdTitle = '';
		    switch($fld) {
                case 'Tour':
                case 'tour_kennung':
                    $colLabel = 'Tour';
                    $_tdTitle = 'Zuordnungs-ID aus der Tourenplanung';
                    break;

                case 'Liefertermin':
                case 'umzugstermin':
                    $colLabel = 'LiefDat';
                    $_tdTitle = 'Liefertermin';
                    break;

                case 'Leistungen':
                case 'Lstg':
                    $colLabel = 'Lstg.';
                    $_tdTitle = 'Enthaltene Leistungen in abgekürzter Schreibweise';
                    break;

                default:
                    $colLabel = $fld;
            }

			if ($fld !=='aid' && $fld !== 'Land') {
			    $rows2Tbl.= "<td title='$_tdTitle'><a href=\"" . $_lnk . '">' . $colLabel . '</a></td>';
            }
		}
		$rows2Tbl.= "</thead>\n";
		$rows2Tbl.= "<tbody>\n";

		$iNumRows = count($rows);
		for($i = 0; $i < $iNumRows; $i++) {
			$wz = ($wz!=1)?1:2;
			$lnk = '?s=aantrag&id=' . urlencode($rows[$i]['aid']);
			$rows2Tbl.= "<tr class=\"wz$wz data-href\" data-href='$lnk'>";
			if ($withRowNumbers) {
                $rows2Tbl .= '<td>' . ($offset + $i + 1) . '</td>';
            }
			if ($withActionColumn) {
			    $rows2Tbl.= '<td><a href="?s=aantrag&id=' . urlencode($rows[$i]['aid']) . '">anzeigen</a></td>';
            }
			foreach($rows[$i] as $k => $v) {
			    switch($k) {
                    case 'aid':
                        // Nothing do not show
                        break;

                    case 'summe':
                        $rows2Tbl.= "<td class='menge'>" . number_format($v, 2, ',', '.') . "</td>\n";
                        break;

                    case 'Land':
                        // $rows2Tbl.= "<td>" . getLaenderKuerzelByLand($v) . "</td>\n";
                        break;

                    case 'PLZ':
                        $laenderKuerzel = !empty($rows[$i]['Land']) ? getLaenderKuerzelByLand($rows[$i]['Land']).' ' : '';
                        $rows2Tbl.= '<td>' . $laenderKuerzel . $v . "</td>\n";
                        break;

                    case 'Liefertermin':
                        if ($v) {
                            $_t = strtotime($v);
                            $y = date('Y', $_t);
                            if ($y == $currYear) {
                                $v = date('d.m.', $_t);
                            } else {
                                $v = date('d.m.y');
                            }
                        }
                        $rows2Tbl.= '<td>' . $v . "</td>\n";
                        break;

                    case 'Kategorien':
                    case 'Leistungen':
                        $ktgAbk = str_replace(',', '', $v); // strtr($v, $aKtgAbk);
                        $rows2Tbl.= '<td>' . $ktgAbk . "</td>\n";
                        break;
                    default:
                        $rows2Tbl.= "<td>$v</td>";
                }
            }
			$rows2Tbl.= "</tr>\n";
		}
		$rows2Tbl.= "</tbody>\n";
		$rows2Tbl.= '</table>';
		$rows2Tbl.= <<<EOT
<script>
	$(function() {
		$("table#auftragsSuchResult tr[data-href]").on("click", function() {
			self.location.href = $(this).data("href");
		});
	});
</script>
EOT;

		$op.= "<br>\n".$rows2Tbl;
	} else {#
		$op.= "Ihre Suche ergab keine Treffer!<br>\n";
		if ($db->error()) {
            $op .= $db->error() . "<br>\nsql:" . $sql . "<br>\n";
        }
	}
}
if (basename($_SERVER['PHP_SELF'])==basename(__FILE__)) {
    echo $op;
}

$body_content.= '<div id="ID128585" class="divTabbedNavigation" style="width:100%;">' . "\n";

$Tpl->assign('s', 'aantraege');
if ($top === 'auslieferung') {
    $body_content .= $Tpl->fetch('umzugsteam_antraege_tabs.html');
} elseif ($tabs !== 'auswertung') {
    $body_content .= $Tpl->fetch('admin_antraege_tabs.html');
} else {
    $body_content .= $Tpl->fetch('admin_auswertung_tabs.html');
}

$body_content.= "<div class=\"divModuleBasic padding12px width5Col heightAuto colorContentMain\">
<div class=\"divInlay noMarginBottom borderTop\"></div> 
<div id=\"Umzugsantrag\" class=\"divInlay borderTop\">
<h2>Auftragssuche</h2>\n";

$body_content.= $op;
$body_content.= "</div>\n";
$body_content.= "</div>\n";
$body_content.= "</div>\n";

