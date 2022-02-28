<?php 
require_once($InclBaseDir."php_json.php");
require_once("sites/umzugsantrag_stdlib.php");
require_once($InclBaseDir."umzugsgruppierungen.lib.php");


// Get ID, falls Antrag bereits vorhanden
$AID = getRequest("id",'');

if (strpos($user["gruppe"], "admin") === false) {
    if ($user['gruppe'] === 'kunde_report' && strpos($_SERVER['REQUEST_URI'], '=aantrag')) {
        $_alt = str_replace('=aantrag', '=pantrag', $_SERVER['REQUEST_URI']);
        Header('Location: ' . $_alt);
        exit;
    }
    if ($user['gruppe'] === 'umzugsteam') {

    } else {
        if ($AID) {
            $row = $db->query_row('SELECT * FROM mm_umzuege WHERE aid = :aid', [ 'aid' => $AID]);
            if ($row && $row['antragsteller_uid'] == $user['uid']) {
                $_alt = str_replace('=aantrag', '=kantrag', $_SERVER['REQUEST_URI']);
                Header('Location: ' . $_alt);
                exit;
            }
        }
        die("UNERLAUBTER ZUGRIFF! Zugriff nur für Administratoren");
    }
}

require_once($InclBaseDir . "umzugsantrag.inc.php");
require_once($InclBaseDir . "umzugsmitarbeiter.inc.php");
require_once($InclBaseDir . "umzugsanlagen.inc.php");
require_once($InclBaseDir . "leistungskatalog.inc.php");
require_once($InclBaseDir . "dienstleister.inc.php");


$ATConf = &$_CONF["umzugsanlagen"];
$ASConf = &$_CONF["umzugsantrag"];
$MAConf = &$_CONF["umzugsmitarbeiter"];
$LKConf = &$_CONF["leistungskatalog"];

$topService = getRequest('top', '');

$userGruppe = $user['gruppe'];
$istUmzugsteam = $userGruppe === 'umzugsteam' || $topService === 'auslieferung';
$istAdmin = $userGruppe === 'admin';
$istSuperAdmin = $istAdmin && $user['adminmode'] === 'superadmin';

if (empty($AID)) $AID = (!empty($_POST["AS"]["aid"]) ? $_POST["AS"]["aid"] : (!empty($_GET["AS"]["aid"]) ? $_GET["AS"]["aid"] : ''));

$AS = new ItemEdit($ASConf, $connid, $user, $AID);
$MA = new ItemEdit($MAConf, $connid, $user, false);
$Tpl = new myTplEngine();
$TplMaListItem = array("ID"=>1, "nachname"=>"", "vorname"=>"", "ort"=>"", "gebaeude"=>"", "raumnr"=>"");
$PreiseAnzeigen = ($user['darf_preise_sehen'] === 'Ja') ? 1 : 0;
// die('<pre>' . print_r($user,1));
$Tpl->assign('PreiseAnzeigen', $PreiseAnzeigen);

$sql = 'SELECT l.leistung_id, l.leistung_ref_id, '
    . ' l.leistung_ref_id2, l.leistung_ref_id3, '
    . ' l.Bezeichnung, '
    . ' l.Beschreibung, '
    . ' l.Farbe, '
    . ' l.Groesse, '
    . ' l.produkt_link, '
    . ' CONCAT('  . "\n"
    . '   l.Bezeichnung, '
    . '   IF(IFNULL(l.Farbe, "")="", "", CONCAT(", ", l.Farbe)), ' . "\n"
    . '   IF(IFNULL(l.Groesse, "")="", "", CONCAT(", ", l.Groesse)) ' . "\n"
    . ' ) leistung, ' . "\n"
    . ' leistungseinheit, leistungseinheit2, '
    . ' k.leistungskategorie AS kategorie, '
    . ' k.leistungsart, '
    . ' l.leistungskategorie_id AS kategorie_id, '
    . ' l.aktiv, l.verfuegbar, '
    . ' preis_pro_einheit, image, '
    . ' m.preis mx_preis, m.preiseinheit mx_preiseinheit, m.mengen_von mx_von, m.mengen_bis mx_bis'
    . ' FROM mm_leistungskatalog l '
    . ' LEFT JOIN mm_leistungskategorie k ON l.leistungskategorie_id = k.leistungskategorie_id '
    . '  LEFT JOIN mm_leistungspreismatrix m ON l.leistung_id = m.leistung_id '
    . ' WHERE l.aktiv = "Ja" '
    . ($AID
        ? ' AND (IFNULL(l.angebots_aid, "") = "" OR l.angebots_aid = ' . $db::quote($AID) . ') '
        : ' AND IFNULL(l.angebots_aid, "") = "" '
    )
    . ' ORDER BY kategorie, Bezeichnung, mx_von';

$aGroupItems = [];
$lkTreeItems = [];
$lkTreeItemsJson = [];
$lkmById = [];
// $lkItems = $db->query_rows($sql);
$lkItems = getLeistungsAuswahl([ 'AID' => $AID, 'mitNeuenAngeboten' => true ]);
//echo '<pre>' . json_encode($lkItems, JSON_PRETTY_PRINT) . '</pre>';
//exit;
foreach($lkItems as $k => $v) {
    $ktg1 = (empty($v['kategorie'])) ? 'Einsatz' : $v['kategorie'];
    $lkTreeItems[$ktg1][$v['leistung']][] = $v;
    
    $jvals = [];
    foreach($v as $jk => $jv) $jvals[$jk] = $jv;
    if (!isset($lkTreeItemsJson[$ktg1][$v['leistung']])) {
        $lkTreeItemsJson[$ktg1][$v['leistung']] = $jvals;
        $lkmById[$v['leistung_id']] = [];
    }
    
    if ($v['mx_preis']) $lkmById[$v['leistung_id']][] = array(
        'preis' => $v['mx_preis'],
        'von'   => $v['mx_von'],
        'bis'   => $v['mx_bis'],
    );
}

$Tpl->assign('lktreeItems', $lkTreeItems);
$Tpl->assign('lkTreeItemsJson', json_encode($lkTreeItemsJson) );
$Tpl->assign('lkmByIdJson', json_encode($lkmById) );

// If AID: Bearbeitungsformular mit DB-Daten
if ($AID) {
	$AS->loadDbdata();
	$AS->dbdataToInput();
        
        $gebRow = $db->query_row('SELECT * FROM mm_stamm_gebaeude WHERE id = :gebaeude ', array('gebaeude'=>$AS->arrDbdata['gebaeude']) );
//	echo '<pre>' . print_r($user,1) . '</pre>';
//      echo '<pre>' . print_r($gebRow,1) . '</pre>';
	if ($user["gruppe"]=="admin_standort") {
		if ($user['uid'] != $gebRow['mertenshenk_uid'] && strpos(",".$user["standortverwaltung"].",", ",".$AS->arrInput["ort"].",")===false)
			die("UNERLAUBTER ZUGRIFF! Standort-Administratoren dürfen nur auf Anträge zugreifen, "
                           ."die in Ihrer Standortverwaltung eingetragen sind oder für die Sie in der Gebääudeliste zugeordnet sind!");
	}
	
	$sql = "SELECT mid FROM `".$MAConf["Table"]."` WHERE aid = ".intval($AID);
	$aMIDs = $db->query_rows($sql);

	$iNumMIDs = count($aMIDs);
	for($i = 0; $i < $iNumMIDs; $i++) {
            $MID = $aMIDs[$i]["mid"];
            $MA = new ItemEdit($MAConf, $connid, $user, $MID);
            $MA->dbdataToInput();
            $aMaItems[$i] = $MA->arrInput;
            $MAItems[$i] = &$aMaItems[$i];

            $raumdaten = get_raumdaten_byGER($MAItems[$i]["zgebaeude"], $MAItems[$i]["zetage"], $MAItems[$i]["zraumnr"]);
            $raum_ma_fix = get_arbeitsplatz_belegung($raumdaten["id"], $apnr=false);
            $raum_ma_hin = get_arbeitsplatz_hinzuege($raumdaten["id"], $apnr=false);

            $count_ma_fix = (is_array($raum_ma_fix) && count($raum_ma_fix)) ? count($raum_ma_fix) : 0;
            $count_ma_hin = (is_array($raum_ma_hin) && count($raum_ma_hin)?count($raum_ma_hin):0);
            $count_ma_all = $count_ma_fix+$count_ma_hin;

            if ($count_ma_all) {
                    $isCritical = ($raumdaten["raum_flaeche"] / 10) < $count_ma_all;
            } else $isCritical = false;

            $MAItems[$i]["critical_status_index"] = ($isCritical ? 1 : 0);
            $MAItems[$i]["critical_status_info"] = intval($raumdaten["raum_flaeche"])."qm: ".$count_ma_fix."Fix + ".$count_ma_hin."Hin";
            $MAItems[$i]["critical_status_img"] = ($isCritical ? "warning_triangle.png" : "thumb_up.png");
            /**/
	}

	$baseAID = $AID; // $AS->arrDbdata['ref_aid'] ?: $AID;
	
	$aAtItems = getAttachements($AS->arrDbdata, 0);
	$aAtIntItems = getAttachements($AS->arrDbdata, 1);
	$aGroupItems = getGruppierungen($baseAID);
    $aReklas = getReklamationenByAid($baseAID);
    $aTeillieferungen = getTeillieferungenByAid($baseAID);

    $aLSItems = getLieferscheineByAid($AID, ['onlySigned' => true]);
    $aOrderedRHItems = getOrderedRueckholLeistungen($AID);
	
} else {
	// else: lade Eingabeformular
	$defaultAS = array(
		"vorname" => $user["vorname"],
		"name" => $user["nachname"],
		"fon" => $user["fon"],
		"email"=> $user["email"],
		"ort" => $user["standort"],
		"gebaeude" => $user["gebaeude"]
	);
	$AS->loadInput($defaultAS, false);
	$MA->loadInput(array(), false);
}

// Lade Dienstleister
$dl_id = $AS->arrInput['dienstleister_id'];
$DL = new ItemEdit($_CONF["dienstleister"], $connid, $user, $dl_id);
$DL->loadDbdata();
$DL->dbdataToInput();
$Tpl->assign("DL", $DL->arrInput);
$Tpl->assign("MAConf", $MAConf['Fields']);

$AS->arrInput['gebaeude_text'] = '';
$AS->arrInput['von_gebaeude_text'] = '';
$AS->arrInput['nach_gebaeude_text'] = '';
if ((int)$AS->arrInput['gebaeude']) {
    $AS->arrInput['gebaeude_text'] = $db->query_one(
        'SELECT CONCAT(adresse, ", ", stadtname, " [", id, "]") adr '
       .'FROM mm_stamm_gebaeude WHERE id = ' . (int)$AS->arrInput['gebaeude']);
}
if ((int)$AS->arrInput['von_gebaeude_id']) {
    $AS->arrInput['von_gebaeude_text'] = $db->query_one(
        'SELECT CONCAT(adresse, ", ", stadtname) adr '
       .'FROM mm_stamm_gebaeude WHERE id = ' . (int)$AS->arrInput['von_gebaeude_id']);
}
if ((int)$AS->arrInput['nach_gebaeude_id']) {
    $AS->arrInput['nach_gebaeude_text'] = $db->query_one(
        'SELECT CONCAT(adresse, ", ", stadtname) adr '
       .'FROM mm_stamm_gebaeude WHERE id = ' . (int)$AS->arrInput['nach_gebaeude_id']);
}
if ((int)$AS->arrInput['antragsteller_uid']) {
    $_kid = $db->query_one(
        'SELECT personalnr '
        .'FROM mm_user WHERE uid = ' . (int)$AS->arrInput['antragsteller_uid']);
    $AS->arrInput['personalnr'] = $_kid;
    $AS->arrInput['kid'] = $_kid;
} else {
    $AS->arrInput['personalnr'] = '';
}

$Tpl->assign("s", $s);
$Tpl->assign('AID', $AID);
$Tpl->assign('AIDJson', json_encode($AID));
$Tpl->assign("AS", $AS->arrInput);
$Tpl->assign("ASJson", json_encode($AS->arrInput));
$Tpl->assign("ASConf", $ASConf['Fields']);$Tpl->assign("AS", $AS->arrInput);
$Tpl->assign('creator', 'mertens');
$Tpl->assign("creatorJson", json_encode('mertens'));
$Tpl->assign("user", $user);
$userForJson = $user;
unset($userForJson['pw']);
unset($userForJson['authentcode']);
unset($userForJson['authentcode']);
$Tpl->assign('userJson', json_encode($userForJson));
$Tpl->assign("umzugsstatus", $AS->arrInput['umzugsstatus']);
$Tpl->assign("umzugsstatusJson", json_encode($AS->arrInput['umzugsstatus']));
$Tpl->assign("antragsstatus", $AS->arrInput['antragsstatus']);
$Tpl->assign("antragsstatusJson", json_encode($AS->arrInput['antragsstatus']));

if (!empty($aMaItems) && count($aMaItems)) {
    $Tpl->assign("Mitarbeiterliste", $aMaItems);
}
if (!empty($aAtItems) && count($aAtItems)) {
    $Tpl->assign("UmzugsAnlagen", $aAtItems);
}

if (!empty($aAtIntItems) && count($aAtIntItems)) {
    $Tpl->assign("UmzugsAnlagenIntern", $aAtIntItems);
} else {
    $Tpl->assign("UmzugsAnlagenIntern", []);
}

if (!empty($aLSItems) && count($aLSItems)) {
    $Tpl->assign("UmzugLieferscheine", $aLSItems);
} else {
    $Tpl->assign("UmzugLieferscheine", []);
}

if (!empty($aGroupItems) && count($aGroupItems)) {
    $Tpl->assign("UmzugsGruppierungen", $aGroupItems);
}

$aids = array_map(function($a) { return $a['aid']; }, $aGroupItems);
if (!empty($aids) && count($aids)) {
    $Tpl->assign("UmzugsGruppierungsIds", implode(',', $aids));
}

if (!empty($aReklas) && count($aReklas)) {
    $Tpl->assign("Reklamationen", $aReklas);
} else {
    $Tpl->assign("Reklamationen", []);
}

if (!empty($aTeillieferungen) && count($aTeillieferungen)) {
    $Tpl->assign("Teillieferungen", $aTeillieferungen);
} else {
    $Tpl->assign("Teillieferungen", []);
}

if (!empty($aOrderedRHItems)) {
    $Tpl->assign("aOrderedRHItems", $aOrderedRHItems);
} else {
    $Tpl->assign("aOrderedRHItems", []);
}


// Erzeuge GeraeteListe (Array) für Smarty-Template
$CsvLines = explode("\n", $AS->arrInput["geraete_csv"]);
$aGItems = array();
$aGCols = array();
$iNumCsvLines = count($CsvLines);
for ($i = 0; $i < $iNumCsvLines; $i++) {
    $aGCols = explode("\t", $CsvLines[$i]);
    if (count($aGCols) != 4) continue;
    $aGItems[$i] = array(
        "Art" => $aGCols[0],
        "Nr" => $aGCols[1],
        "Von" => $aGCols[2],
        "Nach" => $aGCols[3]
    );
}
if (!empty($aGItems) && count($aGItems)) {
    $Tpl->assign("Geraeteliste", $aGItems);
}

$SumBase = 'MH';
$sql = 'SELECT ul.leistung_id, ul.leistung_id lid, ul.menge_property, ul.menge2_property, ' . "\n"
      . ' ul.menge_mertens, ul.menge2_mertens, ' . "\n"
      . ' ul.menge_rekla, ul.menge2_rekla, ' . "\n"
      . ' ul.menge_geliefert, ul.menge2_geliefert, ' . "\n"
      . ' CONCAT('  . "\n"
      . '   l.Bezeichnung, '
      . '   IF(IFNULL(l.Farbe, "")="", "", CONCAT(", ", l.Farbe)), ' . "\n"
      . '   IF(IFNULL(l.Groesse, "")="", "", CONCAT(", ", l.Groesse)) ' . "\n"
      . ' ) leistung, ' . "\n"
      . ' lk.leistungskategorie kategorie, lk.leistungskategorie_id kategorie_id, lk.leistungsart, ' . "\n"
      . ' l.image, l.Beschreibung, l.produkt_link, l.Farbe, l.Groesse, ' . "\n"
      . ' l.leistungseinheit, l.leistungseinheit2, if(lm.preis, lm.preis, preis_pro_einheit) preis_pro_einheit ' . "\n"
      . ' FROM mm_umzuege_leistungen ul ' . "\n"
      . ' LEFT JOIN mm_leistungskatalog l ON(ul.leistung_id = l.leistung_id) ' . "\n"
      . ' LEFT JOIN mm_leistungskategorie lk ON(l.leistungskategorie_id = lk.leistungskategorie_id) ' . "\n"
      . ' LEFT JOIN mm_leistungspreismatrix lm ON(' . "\n"
      . '    l.leistung_id = lm.leistung_id ';
      if ($SumBase == 'MH') {
            $sql.= '    AND lm.mengen_von <= (ul.menge_mertens * IFNULL(ul.menge2_mertens,1)) ' . "\n"
                  .'    AND (lm.mengen_bis >= ( ul.menge_mertens * IFNULL(ul.menge2_mertens,1)))';
      } else {
//      $sql.= '      AND lm.mengen_von <= if(ul.menge_mertens * IFNULL(ul.menge2_mertens,1) < 0.01, ul.menge_property * IFNULL(ul.menge2_property,1), ul.menge_mertens * IFNULL(ul.menge2_mertens,1)) '
//            .'      AND (lm.mengen_bis < 0.01 '
//            .'           || lm.mengen_bis >= if(ul.menge_mertens * IFNULL(ul.menge2_mertens,1) < 0.01, ul.menge_property * IFNULL(ul.menge2_property,1), ul.menge_mertens * IFNULL(ul.menge2_mertens,1))'
//            .'      )'  
      }
      $sql.= ' )' . PHP_EOL;
      $sql.= ' WHERE ul.aid = :aid';
$aLItems = $db->query_rows($sql, 0, array('aid'=>$AID));
if ($db->error()) {
    die($db->error() . '<br>' . PHP_EOL . $db->lastQuery);
}

$hideDusMengen = 0; // $AS->arrInput['umzugsstatus'] == 'angeboten';

$Gesamtsumme = 0.0;
foreach($aLItems as &$_it) {
    if (false !== stripos($_it['leistungseinheit'], 'prozent')) {
        $_it['gesamtpreis'] = '';
        continue;
    }
    
    $_it['gesamtpreis'] = $_it['preis_pro_einheit'];
    if ($SumBase == 'MH') { //$_it['menge_mertens']) {
        $_it['gesamtpreis']*= $_it['menge_mertens'];
        if ($_it['menge2_mertens']) $_it['gesamtpreis']*= $_it['menge2_mertens'];
    } else {
        $_it['gesamtpreis']*= $_it['menge_property'];
        if ($_it['menge2_property']) $_it['gesamtpreis']*= $_it['menge2_property'];
    }
    if ($hideDusMengen) {
        $_it['menge_property'] = $_it['menge2_property'] = null;
    }
    $Gesamtsumme+= $_it['gesamtpreis'];
}

if (!empty($aLItems) && count($aLItems)) {
    $Tpl->assign("Umzugsleistungen", $aLItems);
} else {
    $Tpl->assign("Umzugsleistungen", []);
}
$Tpl->assign("Gesamtsumme", $Gesamtsumme);
$Tpl->assign('enableLeistungCheckbox', false);
//die("#".__LINE__." aAtItems: ".print_r($aAtItems,1)."<br>\n");

$mainmenu = "Class-Active-Umzug"; //Umzug" xclass="liActive
$topmenu = implode("", file($MConf["AppRoot"]."/sites/mitarbeiter_topmenu.tpl.html"));

$istEditierbar = empty($AS->arrDbdata["umzugsstatus"])
    || in_array($AS->arrDbdata["umzugsstatus"],[ 'angeboten', 'beantragt', 'bestaetigt', 'geprueft', 'geprueft' ]);

$istAktiv = !empty($AS->arrDbdata["umzugsstatus"])
    && in_array($AS->arrDbdata["umzugsstatus"],[ 'bestaetigt', 'geprueft' ]);

$AS->loadDbdata();

if ($istUmzugsteam && $istAktiv) {
    $body_content = $Tpl->fetch("umzugsteam_umzugsformular.tpl.read.html");
} elseif($istEditierbar || $istSuperAdmin) {
    $body_content = $Tpl->fetch("admin_umzugsformular.tpl.html");
} else {
    $body_content = $Tpl->fetch("admin_umzugsformular.tpl.read.html");
}

//$body_content = implode("", file($MConf["AppRoot"].$MConf["Tpl_Dir"]."umzugsformular.tpl.html"));
if (DEBUG && basename(__FILE__) == basename($_SERVER["PHP_SELF"])) {
	echo $body_content;
}
