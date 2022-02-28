<?php 

function getLeistungsAuswahl(array $opts = []) {

    global $db;

    $AID = !empty($opts['AID']) ? (int)$opts['AID'] : 0;
    $mitNeuenAngeboten = !empty($opts['mitNeuenAngeboten']);

    $NL = "\n";

    $sqlSelect = 'SELECT l.leistung_id, l.leistung_ref_id, ' . $NL
        . ' l.leistung_ref_id2, l.leistung_ref_id3, ' . $NL
        . ' l.Bezeichnung, ' . $NL
        . ' l.Beschreibung, ' . $NL
        . ' l.Farbe, ' . $NL
        . ' l.Groesse, ' . $NL
        . ' l.produkt_link, ' . $NL
        . ' CONCAT(' . $NL
        . '   l.Bezeichnung, ' . $NL
        . '   IF(IFNULL(l.Farbe, "")="", "", CONCAT(", ", l.Farbe)), ' . $NL
        . '   IF(IFNULL(l.Groesse, "")="", "", CONCAT(", ", l.Groesse)) ' . $NL
        . ' ) leistung, ' . $NL
        . ' leistungseinheit, leistungseinheit2, ' . $NL
        . ' k.leistungskategorie AS kategorie, ' . $NL
        . ' k.leistungsart, ' . $NL
        . ' IFNULL(k.leistungskategorie_id, l.leistungskategorie_id) AS kategorie_id, ' . $NL
        . ' l.aktiv, l.verfuegbar, ' . $NL
        . ' preis_pro_einheit, image, ' . $NL
        . ' m.preis mx_preis, m.preiseinheit mx_preiseinheit, m.mengen_von mx_von, m.mengen_bis mx_bis' . $NL
        ;
    $sqlFrom = ' FROM mm_leistungskatalog l ' . $NL
        . ' LEFT JOIN mm_leistungskategorie k ON l.leistungskategorie_id = k.leistungskategorie_id ' . $NL
        . ' LEFT JOIN mm_leistungspreismatrix m ON l.leistung_id = m.leistung_id ' . $NL
        ;
    $sqlWhere = ' WHERE l.verfuegbar = "Ja" AND k.leistungsart != "Angebot" ' . $NL
        . ($AID
            ? ' AND (IFNULL(l.angebots_aid, "") = "" OR l.angebots_aid = ' . $db::quote($AID) . ') ' . $NL
            : ' AND IFNULL(l.angebots_aid, "") = "" ' . $NL
        );
    $sqlOrder = ' ORDER BY kategorie, Bezeichnung, mx_von' . $NL;
    $sql = $sqlSelect . $sqlFrom . $sqlWhere . $sqlOrder;

    $rows = $db->query_rows($sql, 0);

    if ($mitNeuenAngeboten) {
        $sqlA = $sqlSelect
            . ' FROM mm_leistungskategorie k ' . $NL
            . ' LEFT JOIN ( ' . $NL
            . '   SELECT * ' . $NL
            . '   FROM mm_leistungskatalog t ' . $NL
            . '   WHERE t.aktiv = "Ja"' . $NL
            . ($AID && 1
                ? ' AND (IFNULL(t.angebots_aid, "") = "" OR t.angebots_aid = ' . $db::quote($AID) . ') ' . $NL
                : ' AND IFNULL(t.angebots_aid, "") = "" ' . $NL
              )
            . ' ) l ON k.leistungskategorie_id = l.leistungskategorie_id' . $NL
            . ' LEFT JOIN mm_leistungspreismatrix m ON l.leistung_id = m.leistung_id ' . $NL
            . ' WHERE k.leistungsart = "Angebot" ' . $NL
            ;
        $rowsA = $db->query_rows($sqlA, 0);
//        echo '<pre>' . json_encode(compact('sqlA', 'rowsA', JSON_PRETTY_PRINT)) . '</pre>';
//        exit;

        foreach($rowsA as $_row) {
            $rows[] = $_row;
        }
    }
    return $rows;
}

function getAllOrderedLeistungenByUid(int $uid, array $opts = []) {
	global $db;
	$aParams = [ 'uid' => $uid ];
	$aAndWhere = [];

	if (!empty($opts['WithStatus'])) {
		$aStati = (array)$opts['WithStatus'];
		$aQuotedStati = array_map(function($v) use($db) { return $db::quote($v); }, $aStati);
		$aAndWhere[] = 'a.umzugsstatus IN ('
			. implode(', ', $aQuotedStati )
			. ')';
	} elseif (!empty($opts['WithoutStatus'])) {
		$aStati = (array)$opts['WithoutStatus'];
		$aQuotedStati = array_map(function($v) use($db) { return $db::quote($v); }, $aStati);
		$aAndWhere[] = 'a.umzugsstatus NOT IN ('
			. implode(', ', $aQuotedStati )
			. ')';
	} else {
		$aAndWhere = 'a.umzugsstatus != "storniert"';
	}

	if (!empty($opts['WithKategorieId'])) {
		$aKtgId = (array)$opts['WithKategorieId'];
		$aQuotedKtgId = array_map(function($v) use($db) { return $db::quote($v); }, $aKtgId);
		$aAndWhere[] = 'ktg.leistungskategorie_id IN ('
			. implode(', ', $aQuotedKtgId )
			. ')';
	}

	if (!empty($opts['WithLeistungsart'])) {
		$aArt = (array)$opts['WithLeistungsart'];
		$aQuotedArt = array_map(function($v) use($db) { return $db::quote($v); }, $aArt);
		$aAndWhere[] = 'ktg.leistungsart IN ('
			. implode(', ', $aQuotedArt )
			. ')';
	} elseif (!empty($opts['WithoutLeistungsart'])) {
		$aArt = (array)$opts['WithoutLeistungsart'];
		$aQuotedArt = array_map(function($v) use($db) { return $db::quote($v); }, $aArt);
		$aAndWhere[] = 'ktg.leistungsart NOT IN ('
			. implode(', ', $aQuotedArt )
			. ')';
	}

	$sql = 'SELECT 
    a.aid,
    al.id AS l_id,
    al.*,
    ktg.leistungskategorie_id,
    a.service,
    a.antragsdatum,
    a.umzugsstatus,
    al.menge_mertens,
    ktg.leistungsart AS Art,
    ktg.leistungskategorie AS Kategorie,
    ktg.leistungskategorie AS kategorie,
    klg.Bezeichnung,
    klg.Bezeichnung AS leistung,
    klg.Farbe,
    klg.Groesse,
    klg.preis_pro_einheit,
    klg.preis_pro_einheit AS Preis,
    (al.menge_mertens * klg.preis_pro_einheit) AS gesamtpreis,
    (al.menge_mertens * klg.preis_pro_einheit) AS Summe
   FROM mm_umzuege AS a
   JOIN mm_umzuege_leistungen AS al ON (a.aid = al.aid) 
   JOIN mm_leistungskatalog AS klg ON (al.leistung_id = klg.leistung_id) 
   JOIN mm_leistungskategorie AS ktg ON (klg.leistungskategorie_id = ktg.leistungskategorie_id)
   WHERE 
      a.antragsteller_uid = :uid
';
	if (count($aAndWhere)) {
		$sql .= ' AND ' . implode(' AND ', $aAndWhere);
	}

	$sql.= '
    ORDER BY a.aid, ktg.leistungskategorie, klg.Bezeichnung, klg.Farbe, klg.Groesse
';
	return $db->query_rows($sql, 0, $aParams);
}


function getAllOrderedLeistungskagetorienByUid(int $uid, array $opts = []) {
	global $db;
	$aParams = [ 'uid' => $uid ];
	$sql = 'SELECT 
    ktg.leistungskategorie AS Kategorie,
    ktg.leistungskategorie_id,
    COUNT(DISTINCT(a.aid)) AS NumAuftraege,
    SUM(al.menge_mertens) AS SumMenge,
    CONCAT(
    	"[",
		GROUP_CONCAT(
			CONCAT(
				"{",
				CONCAT(\'"Bezeichnung":"\', QUOTE(klg.Bezeichnung), \'", \'),
				CONCAT(\'"Farbe":"\', QUOTE(klg.Farbe), \'", \'),
				CONCAT(\'"Groesse":"\', QUOTE(klg.Groesse), \'", \'),
				CONCAT(\'"Menge":"\', al.menge_mertens, \'", \'),
				CONCAT(\'"aid":"\', a.aid, \'", \'),
				CONCAT(\'"service":"\', a.service, \'", \'),
				CONCAT(\'"antragsdatum":"\', a.antragsdatum, \'", \'),
				CONCAT(\'"umzugsstatus":"\', a.umzugsstatus, \'" \'),
				"}"
			)
			ORDER BY al.aid
			SEPARATOR ",\n"
		),
		"]"
    )
   FROM mm_umzuege AS a
   JOIN mm_umzuege_leistungen AS al ON (a.aid = al.aid) 
   JOIN mm_leistungskatalog AS klg ON (al.leistung_id = klg.leistung_id) 
   JOIN mm_leistungskategorie AS ktg ON (klg.leistungskategorie_id = ktg.leistungskategorie_id)
   WHERE 
      a.antragsteller_uid = :uid
      AND a.umzugsstatus != "storniert"
   GROUP BY ktg.Kategorie, ktg.leistungskategorie_id
';
	return $db->query_rows($sql, $aParams);
}

function getReklamationenByAid(int $aid, array $opts = []) {

	global $db;

	$sql = 'SELECT a.*, stat.LeistungenKtg, stat.Summe, stat.LeistungenBez
            FROM mm_umzuege a 
            JOIN (
				SELECT a.aid,
				 GROUP_CONCAT(
				 	CONCAT(
				 		ktg.kategorie_abk,
				 		IF(IFNULL(lk.leistung_abk,"")="", "", CONCAT("", lk.leistung_abk, ""))
				 	) ORDER BY leistungskategorie SEPARATOR "") AS LeistungenKtg, 
				 GROUP_CONCAT(
				 	CONCAT(
				 		lk.Bezeichnung,
				 		IF (IFNULL(lk.Farbe, "") != "", CONCAT(", ", lk.Farbe), ""),
				 		IF (IFNULL(lk.Groesse, "") != "", CONCAT(", ", lk.Groesse), "")
				 	) ORDER BY leistungskategorie SEPARATOR ";"
				  ) AS LeistungenBez,
				 SUM(IFNULL(lk.preis_pro_einheit,0) * IFNULL(al.menge_rekla, 1) * IFNULL(al.menge2_rekla,1)) AS Summe
				FROM mm_umzuege a
				JOIN mm_umzuege_leistungen al ON (a.aid = al.aid)
				JOIN mm_leistungskatalog lk ON (al.leistung_id = lk.leistung_id)
				JOIN mm_leistungskategorie ktg ON (lk.leistungskategorie_id = ktg.leistungskategorie_id)
				WHERE service="Rekla" AND ref_aid = ' . $aid . '
				GROUP BY a.aid
            ) AS stat ON (a.aid = stat.aid)
            WHERE service="Rekla" AND ref_aid = ' . $aid . '
            ORDER BY aid DESC';

	$aRows = $db->query_rows($sql);

	return $aRows;
}

function getTeillieferungenByAid(int $aid, array $opts = []) {

	global $db;

	$sql = 'SELECT a.*, stat.LeistungenKtg, stat.Summe, stat.LeistungenBez
            FROM mm_umzuege a 
            JOIN (
				SELECT a.aid,
				 GROUP_CONCAT(CONCAT(
						kategorie_abk, 
						IF( IFNULL(leistung_abk, "") != "", CONCAT(":", leistung_abk, "|"), ""),
						""
					) ORDER BY leistungskategorie SEPARATOR ""
				 ) AS LeistungenKtg, 
				 GROUP_CONCAT(
				 	CONCAT(
				 		lk.Bezeichnung,
				 		IF (IFNULL(lk.Farbe, "") != "", CONCAT(", ", lk.Farbe), ""),
				 		IF (IFNULL(lk.Groesse, "") != "", CONCAT(", ", lk.Groesse), "")
				 	)
				 	ORDER BY leistungskategorie SEPARATOR ";"
				  ) AS LeistungenBez,
				 SUM(IFNULL(lk.preis_pro_einheit,0) * IFNULL(al.menge_rekla, 1) * IFNULL(al.menge2_rekla,1)) AS Summe
				FROM mm_umzuege a
				JOIN mm_umzuege_leistungen al ON (a.aid = al.aid)
				JOIN mm_leistungskatalog lk ON (al.leistung_id = lk.leistung_id)
				JOIN mm_leistungskategorie ktg ON (lk.leistungskategorie_id = ktg.leistungskategorie_id)
				WHERE service="Teil" AND ref_aid = ' . $aid . '
				GROUP BY a.aid
            ) AS stat ON (a.aid = stat.aid)
            WHERE service="Teil" AND ref_aid = ' . $aid . '
            ORDER BY aid DESC';

	$aRows = $db->query_rows($sql);

	return $aRows;
}

function getLieferscheineByAid(int $aid, array $opts = []) {

    global $db, $_CONF, $MConf, $user, $connid, $InclBaseDir;
    require_once($InclBaseDir."umzugsanlagen.inc.php");

    $andWhere = '';
    if (!empty($opts['onlySigned'])) {
    	$andWhere.= ' AND (source="fileupload" OR IFNULL(sig_kd_blob, "") != "")';
	}

    $sql = 'SELECT 
              lid, 
              aid, 
              leistungen, 
              lieferdatum, 
              ankunft, 
              abfahrt, 
              LENGTH(IFNULL(lieferschein, "")) AS PdfSize,
              source,
              umzuege_anlagen_dokid,
              sig_mt_size,
              sig_kd_size,
              sig_kd_unterzeichner,
              created_uid,
              created_user,
              created_at,
              modified_uid,
              modified_user,
              modified_at
            FROM mm_lieferscheine 
            WHERE aid = ' . $aid . ' ' . $andWhere . '
            ORDER BY lid DESC';

    $aRows = $db->query_rows($sql);
    echo $db->error();

    $iNumRows = count($aRows);
    for($i = 0; $i < $iNumRows; $i++) {

        $_row = $aRows[$i];
        $_aid = (int)$_row['aid'];
        $_lid = (int)$_row['lid'];
        $_lsidx = strrev(base64_encode(json_encode(['aid' => $_aid, 'lid' => $_lid])));
        $aRows[$i]["datei_link"] = $MConf['WebRoot'] . 'sites/lieferschein.php?idx=' . $_lsidx;
        $aRows[$i]["datei_groesse"] = format_file_size($_row["PdfSize"]);
    }

    return $aRows;
}

function getRueckholLeistungen() {
	global $db;

	$sql = <<<EOT
SELECT 
 ktg.leistungskategorie AS kategorie,
 ktg.leistungskategorie,
 ktg.kategorie_abk,
 klg.leistung_abk,
 klg.leistung_id,
 klg.Bezeichnung,
 klg.Bezeichnung AS leistung,
 klg.Beschreibung,
 klg.preis_pro_einheit,
 klg.preis_pro_einheit AS Preis,
 klg.produkt_link
 FROM mm_leistungskategorie AS ktg   
 JOIN mm_leistungskatalog AS klg ON (ktg.leistungskategorie_id = klg.leistungskategorie_id)
 WHERE 
 	ktg.leistungskategorie LIKE "R%ckholung" 
 	and klg.verfuegbar = "Ja" 
 	AND klg.aktiv = "Ja"
 	ORDER BY klg.Bezeichnung
EOT;

	return $db->query_rows($sql);
}

function getAttachements($data, $internal) {

	global $db, $_CONF, $MConf, $user, $connid, $InclBaseDir;
	require_once($InclBaseDir."umzugsanlagen.inc.php");
	
	$ATConf = &$_CONF["umzugsanlagen"];
	$items = array();
	$aid = (int)$data['aid'];
	
	$sql = 'SELECT dokid FROM `' . $ATConf['Table'] . '` '
          . ' WHERE (aid = '. $aid . ' or token = ' . $db::quote($data['token']) . ')'
          . ' AND IFNULL(target, "") = ""';

	if ($internal) {
			$sql.= " AND internal = 1";
	} else {
			$sql.= " AND (internal is null or internal = 0 )";
	}
	
	$aATs = $db->query_rows($sql);
	echo $db->error();

	$iNumRows = count($aATs);
	for($i = 0; $i < $iNumRows; $i++) {
		$DOKID = $aATs[$i]["dokid"];
		$AT = new ItemEdit($_CONF["umzugsanlagen"], $connid, $user, $DOKID);
		$AT->dbdataToInput();
		$items[$i] = $AT->arrInput;
		$items[$i]["datei_link"] = $MConf["WebRoot"]."attachements/".$AT->arrInput["dok_datei"];
		$items[$i]["datei_groesse"] = format_file_size($AT->arrInput["dok_groesse"]);	
	}
	return $items;
}

function get_ort_byGeb($geb) {
	global $aGebaeudeOrte;
	global $db;
	global $_TABLE;
	
	if (empty($aGebaeudeOrte) || empty($aGebaeudeOrte[$geb])) {
		$sql = "SELECT stadtname, gebaeude FROM `".$_TABLE["gebaeude"]."` \n";
		$sql.= "WHERE gebaeude LIKE \"".$db->escape($geb)."\"";
		$row = $db->query_singlerow($sql);
		$aGebaeudeOrte[$row["gebaeude"]] = $row["stadtname"];
	}
	return (!empty($aGebaeudeOrte[$geb])) ? $aGebaeudeOrte[$geb] : "";
}

function get_ma_post_items() {
	global $_POST;
	//echo "<pre>#".__LINE__." ".basename(__FILE__)." _POST:".print_r($_POST,1)."</pre><br>\n";
    if (empty($_POST["MA"]["vorname"])) {
        return [];
    }

	$aMaItems = array();
	$iNumItems = !empty($_POST["MA"]["vorname"]) ? count($_POST["MA"]["vorname"]) : 0;
	if (!empty($_POST["MA"])) for($i = 0; $i < $iNumItems; $i++) {
		$aMaItems[$i]["ID"] = $i+1;
		foreach($_POST["MA"] as $fld => $aTmp) {
			$aMaItems[$i][$fld] = $_POST["MA"][$fld][$i];
		}
	}
	return $aMaItems;
}

function get_raumdaten_byGER($g, $e, $r) {
	global $db;
	global $_TABLE;
	$sql = "SELECT * FROM `".$_TABLE["immobilien"]."` WHERE \n";
	$sql.= "`gebaeude` LIKE \"".$db->escape($g)."\"\n";
	$sql.= " AND `etage` LIKE \"".$db->escape($e)."\"\n";
	$sql.= " AND `raumnr` LIKE \"".$db->escape($r)."\"\n";
	$sql.= " LIMIT 1";
	$row = $db->query_singlerow($sql);
	if (!empty($row["id"])) return $row;
	return false;
}

function get_raumtyp_byGER($g, $e, $r) {
	global $db;
	global $_TABLE;
	global $error;
	$sql = "SELECT raum_typ FROM `".$_TABLE["immobilien"]."` WHERE \n";
	$sql.= "`gebaeude` LIKE \"".$db->escape($g)."\"\n";
	$sql.= " AND `etage` LIKE \"".$db->escape($e)."\"\n";
	$sql.= " AND `raumnr` LIKE \"".$db->escape($r)."\"\n";
	$sql.= " LIMIT 1";
	$row = $db->query_singlerow($sql);
	if ($db->error()) $error.= $db->error()."<br>\n".$sql."<br>\n";
	if (!empty($row["raum_typ"])) return $row["raum_typ"];
	return false;
}

function get_raumid_byGER($g, $e, $r) {
	global $db;
	global $_TABLE;
	global $error;
	$sql = "SELECT id FROM `".$_TABLE["immobilien"]."` WHERE \n";
	$sql.= "`gebaeude` LIKE \"".$db->escape($g)."\"\n";
	$sql.= " AND `etage` LIKE \"".$db->escape($e)."\"\n";
	$sql.= " AND `raumnr` LIKE \"".$db->escape($r)."\"\n";
	$sql.= " LIMIT 1";
	$row = $db->query_singlerow($sql);
	if ($db->error()) $error.= $db->error()."<br>\n".$sql."<br>\n";
	if (!empty($row["id"])) return $row["id"];
	return false;
}

function get_arbeitsplatz_hinzuege($raumid, $apnr=false) {
	global $db;
	global $_TABLE;
	global $_CONF;
	global $RowsCacheHinzuege;
	
	$CacheId = $raumid."_".intval($apnr);
	$ASConf = $_CONF["umzugsantrag"];
	$MAConf = $_CONF["umzugsmitarbeiter"];
	
	if (isset($RowsCacheHinzuege[$CacheId])) {
		return $RowsCacheHinzuege[$CacheId];
	}
	
	// Hole anstehende Umz�ge zu diesem Arbeitsplatz
	$sql = "SELECT m.*, a.umzugsstatus, a.umzugsstatus_vom \n";
	$sql.= "FROM `".$MAConf["Table"]."` m LEFT JOIN `".$ASConf["Table"]."` a USING(aid) \n";
	$sql.= "WHERE ziel_raumid LIKE \"".$db->escape($raumid)."\" \n";
	if ($apnr) $sql.= "AND ziel_arbeitsplatznr LIKE \"".$db->escape($apnr)."\" \n";
	$sql.= "AND umzugsstatus NOT IN (\"temp\",\"abgeschlossen\", \"storniert\", \"abgelehnt\") \n";
	$rows = $db->query_rows($sql);
	//echo "#".__LINE__." ".$db->error()."\n".$sql."\n";
	$RowsCacheWegzuege[$CacheId] = $rows;
	return $rows;
}

function get_arbeitsplatz_wegzuege($raumid, $apnr=false) {
	global $db;
	global $_TABLE;
	global $_CONF;
	global $RowsCacheWegzuege;
	
	$CacheId = $raumid."_".intval($apnr);
	$ASConf = $_CONF["umzugsantrag"];
	$MAConf = $_CONF["umzugsmitarbeiter"];
	
	if (isset($RowsCacheWegzuege[$CacheId])) {
		return $RowsCacheWegzuege[$CacheId];
	}
	
	// Hole anstehende Umz�ge zu diesem Arbeitsplatz
	$sql = "SELECT m.*, a.umzugsstatus, a.umzugsstatus_vom \n";
	$sql.= "FROM `".$MAConf["Table"]."` m LEFT JOIN `".$ASConf["Table"]."` a USING(aid) \n";
	$sql.= "WHERE raumid LIKE \"".$db->escape($raumid)."\" \n";
	if ($apnr) $sql.= "AND arbeitsplatznr LIKE \"".$db->escape($apnr)."\" \n";
	$sql.= "AND umzugsstatus NOT IN (\"temp\",\"abgeschlossen\", \"storniert\", \"abgelehnt\") \n";
	$rows = $db->query_rows($sql);
	//echo "#".__LINE__." ".$db->error()."\n".$sql."\n";
	$RowsCacheWegzuege[$CacheId] = $rows;
	return $rows;
}


function get_arbeitsplatz_belegung($raumid, $apnr=false) {
	global $db;
	global $_TABLE;
	global $_CONF;
	global $RowsCacheBelegung;
	
	$CacheId = $raumid."_".intval($apnr);
	$ASConf = $_CONF["umzugsantrag"];
	$MAConf = $_CONF["umzugsmitarbeiter"];
	
	if (isset($RowsCacheBelegung[$CacheId])) {
		return $RowsCacheBelegung[$CacheId];
	}
	
	// Hole bestehende Belegung zu diesem Arbeitsplatz
	$sql = "SELECT m.*, um.aid, um.ziel_gebaeude, um.ziel_etage, um.ziel_raumnr, a.umzugsstatus, a.umzugsstatus_vom \n";
	$sql.= "FROM `".$_TABLE["mitarbeiter"]."` m LEFT JOIN `".$MAConf["Table"]."` um ON(m.id=um.maid) \n";
	$sql.= "LEFT JOIN `".$ASConf["Table"]."` a ON(um.aid=a.aid) \n";
	$sql.= "WHERE m.`immobilien_raum_id`=\"".$db->escape($raumid)."\"\n";
	if ($apnr) $sql.= "AND m.arbeitsplatznr LIKE \"".$db->escape($apnr)."\" LIMIT 1";
	$sql.= "AND (a.umzugsstatus IS NULL OR a.umzugsstatus IN('abgeschlossen','storniert'))";
	$rows = $db->query_rows($sql);
	//echo "#".__LINE__." ".basename(__FILE__)." sql:$sql<br>\n";
	
	$rows_clean = array();
	$iNumRows = count($rows);
	for($i = 0; $i < $iNumRows; $i++) {
		$row = $rows[$i];
		$sql = "SELECT aid FROM `".$MAConf["Table"]."` LEFT JOIN `".$ASConf["Table"]."` a USING(aid)\n";
		$sql.= "WHERE maid=\"".$db->escape($row["id"])."\" AND a.`umzugsstatus` IN ('beantragt','geprueft','genehmigt','bestaetigt')\n";
		$sql.= "LIMIT 1";
		$row = $db->query_singlerow($sql);
		if (empty($row["aid"])) $rows_clean[] = $rows[$i];
	}
	$RowsCacheBelegung[$CacheId] = $rows_clean;
	
	return $rows_clean;
}

function create_spare($raumid, $apnr=false) {
	global $db;
	global $_TABLE;
	$sql = "INSERT INTO `".$_TABLE["mitarbeiter"]."` SET \n";
	$sql.= "immobilien_raum_id = \"Spare\",\n";
	$sql.= "arbeitsplatznr = \"".$db->escape($apnr)."\",\n";
	$sql.= "abteilung = \"\",\n";
	$sql.= "extern = \"Spare\",\n";
	$sql.= "name = \"SPARE\"\n";
	$db->query($sql);
}

function drop_spare($raumid, $apnr=false) {
	global $db;
	global $_TABLE;
	$sql = "DELETE FROM `".$_TABLE["mitarbeiter"]."` WHERE \n";
	$sql.= "extern = \"Spare\" AND immobilien_raum_id=\"$raumid\" ";
	if ($apnr) $sql.= "AND arbeitsplatznr LIKE \"".$db->escape($apnr)."\"";
	else $sql.= "LIMIT 1";
	$db->query($sql);
}

function update_umzuege_raumid() {
	global $db;
	global $_CONF;
	$MAConf = $_CONF["umzugsmitarbeiter"];
	
	$sql = "SELECT mid, gebaeude, etage, raumnr, ziel_gebaeude, ziel_etage, ziel_raumnr FROM `".$MAConf["Table"]."`";
	$rows = $db->query_rows($sql);
	if ($db->error()) die($db->error()."<br>\n<br>\n".$sql);
	$iNumRows = count($rows);
	for($i = 0; $i < $iNumRows; $i++) {
		$row = $rows[$i];
		$raumid = get_raumid_byGER($row["gebaeude"], $row["etage"], $row["raumnr"]);
		$zraumid = get_raumid_byGER($row["ziel_gebaeude"], $row["ziel_etage"], $row["ziel_raumnr"]);
		$sql = "update `".$MAConf["Table"]."` SET raumid = \"$raumid\", ziel_raumid=\"$zraumid\" WHERE mid=\"".$row["mid"]."\"";
		$db->query($sql);
		if ($db->error()) die($db->error()."<br>\n<br>\n".$sql);
	}
}

function update_umzuege_status() {
	global $_CONF;
	$ASConf = $_CONF["umzugsantrag"];
	// `"$ASConf["Table"]."`
	
	/*
	update `mm_umzuege` SET umzugsstatus='temp', `umzugsstatus_vom`=IF(`modified` IS NOT NULL,`modified`,`created`);
	update `mm_umzuege` SET umzugsstatus='beantragt', `umzugsstatus_vom`=`antragsdatum` WHERE `antragsstatus`='gesendet';
	update `mm_umzuege` SET umzugsstatus='storniert', `umzugsstatus_vom`=`modified` WHERE `antragsstatus`='storniert';
	update `mm_umzuege` SET umzugsstatus='geprueft', `umzugsstatus_vom`=`geprueft_am` WHERE `geprueft`='Ja';
	update `mm_umzuege` SET umzugsstatus='genehmigt', `umzugsstatus_vom`=`genehmigt_br_am` WHERE `genehmigt_br`='Ja';
	update `mm_umzuege` SET umzugsstatus='abgelehnt', `umzugsstatus_vom`=`genehmigt_br_am` WHERE `genehmigt_br`='Nein';
	update `mm_umzuege` SET umzugsstatus='bestaetigt', `umzugsstatus_vom`=`bestaetigt_am` WHERE `bestaetigt`='Ja';
	update `mm_umzuege` SET umzugsstatus='abgeschlossen', `umzugsstatus_vom`=`abgeschlossen_am` WHERE `abgeschlossen`='Ja';
	update `mm_umzuege` SET umzugsstatus='storniert', `umzugsstatus_vom`=`abgeschlossen_am` WHERE `abgeschlossen`='Storniert';
	*/
}

/*
require("../header.php");
require_once($InclBaseDir."umzugsantrag.inc.php");
require_once($InclBaseDir."umzugsmitarbeiter.inc.php");
echo "<pre>\n";
$raumid = "3345";
echo "Hinzuege raumid $raumid: ".print_r(get_arbeitsplatz_hinzuege($raumid, $apnr=false),1);
$raumid = "3345";
echo "Wegzuege raumid $raumid: ".print_r(get_arbeitsplatz_wegzuege($raumid, $apnr=false),1);
$raumid = "3345";
echo "Belegung raumid $raumid: ".print_r(get_arbeitsplatz_belegung($raumid, $apnr=false),1);
echo "</pre>\n";
*/
