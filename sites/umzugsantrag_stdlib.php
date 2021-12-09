<?php 

function getLieferscheineByAid(int $aid, array $opts = []) {

    global $db, $_CONF, $MConf, $user, $connid, $InclBaseDir;
    require_once($InclBaseDir."umzugsanlagen.inc.php");

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
            WHERE aid = ' . $aid . '
            ORDER BY lid DESC';

    $aRows = $db->query_rows($sql);
    echo $db->error();

    for($i = 0; $i < count($aRows); $i++) {

        $_row = $aRows[$i];
        $_aid = (int)$_row['aid'];
        $_lid = (int)$_row['lid'];
        $_lsidx = strrev(base64_encode(json_encode(['aid' => $_aid, 'lid' => $_lid])));
        $aRows[$i]["datei_link"] = $MConf['WebRoot'] . 'sites/lieferschein.php?idx=' . $_lsidx;
        $aRows[$i]["datei_groesse"] = format_file_size($_row["PdfSize"]);
    }

    return $aRows;
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
	
	for($i = 0; $i < count($aATs); $i++) {
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
	
	$aMaItems = array();
	if (!empty($_POST["MA"])) for($i = 0; $i < count($_POST["MA"]["vorname"]); $i++) {
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
	for($i = 0; $i < count($rows); $i++) {
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
	for($i = 0; $i < count($rows); $i++) {
		$row = $rows[$i];
		$raumid = get_raumid_byGER($row["gebaeude"], $row["etage"], $row["raumnr"]);
		$zraumid = get_raumid_byGER($row["ziel_gebaeude"], $row["ziel_etage"], $row["ziel_raumnr"]);
		$sql = "update `".$MAConf["Table"]."` SET raumid = \"$raumid\", ziel_raumid=\"$zraumid\" WHERE mid=\"".$row["mid"]."\"";
		$db->query($sql);
		if ($db->error()) die($db->error()."<br>\n<br>\n".$sql);
	}
}

function update_umzuege_status() {
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
?>
