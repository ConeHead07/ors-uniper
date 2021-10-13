<?php 
require_once($InclBaseDir."php_json.php");
require_once("sites/umzugsantrag_stdlib.php");

if (strpos($user["gruppe"], "kunde_report") === false && strpos($user["adminmode"], "superadmin") === false) {
    if ($user['gruppe'] === 'admin' && strpos($_SERVER['REQUEST_URI'], '=pantrag')) {
        $_alt = str_replace('=pantrag', '=aantrag', $_SERVER['REQUEST_URI']);
        Header('Location: ' . $_alt);
        exit;
    }
    die("UNERLAUBTER ZUGRIFF!<br>$_lnk");
}

require_once($InclBaseDir."umzugsantrag.inc.php");
require_once($InclBaseDir."umzugsmitarbeiter.inc.php");
require_once($InclBaseDir."umzugsanlagen.inc.php");
require_once($InclBaseDir."leistungskatalog.inc.php");
require_once($InclBaseDir."dienstleister.inc.php");

$ATConf = &$_CONF["umzugsanlagen"];
$ASConf = &$_CONF["umzugsantrag"];
$MAConf = &$_CONF["umzugsmitarbeiter"];
$LKConf = &$_CONF["leistungskatalog"];

$Tpl = new myTplEngine();

$sql = 'SELECT leistung_id, l.leistung_ref_id, Bezeichnung leistung, leistungseinheit, leistungseinheit2, '
      .' leistungskategorie AS kategorie, l.leistungskategorie_id AS kategorie_id, preis_pro_einheit, image '
      .' FROM mm_leistungskatalog l LEFT JOIN mm_leistungskategorie k ON l.leistungskategorie_id = k.leistungskategorie_id '
      .' WHERE l.aktiv = 1 '
      .' ORDER BY kategorie, Bezeichnung';

$lkTreeItems = array();
$lkTreeItemsJson = array();
$lkmById = array();
$lkItems = $db->query_rows($sql);
foreach($lkItems as $k => $v) {
    $ktg1 = (empty($v['kategorie'])) ? 'Einsatz' : $v['kategorie'];
    $lkTreeItems[$ktg1][$v['leistung']][] = $v;
    
    $jvals = array();
    foreach($v as $jk => $jv) $jvals[utf8_encode ($jk)] = utf8_encode($jv);
    $lkTreeItemsJson[utf8_encode($ktg1)][utf8_encode($v['leistung'])] = $jvals;
}

$Tpl->assign('lktreeItems', $lkTreeItems);
$Tpl->assign('lkTreeItemsJson', json_encode($lkTreeItemsJson) );
$Tpl->assign('lkmByIdJson', json_encode($lkmById) );

// Get ID, falls Antrag bereits vorhanden
$creator = 'property';
$AID = getRequest("id",'');
$export = getRequest("export",'');
if (empty($AID)) $AID = (!empty($_POST["AS"]["aid"]) ? $_POST["AS"]["aid"] : (!empty($_GET["AS"]["aid"]) ? $_GET["AS"]["aid"] : ''));

$AS = new ItemEdit($ASConf, $connid, $user, $AID);
$MA = new ItemEdit($MAConf, $connid, $user, false);
$TplMaListItem = array("ID"=>1, "nachname"=>"", "vorname"=>"", "ort"=>"", "gebaeude"=>"", "raumnr"=>"");
$PreiseAnzeigen = ($user['darf_preise_sehen'] === 'Ja') ? 1 : 0;
$Tpl->assign('PreiseAnzeigen', $PreiseAnzeigen);
// If AID: Bearbeitungsformular mit DB-Daten
if ($AID) {
	$AS->loadDbdata();
	// print_r($AS->arrDbdata);
	$AS->dbdataToInput();
	$sql = "SELECT mid FROM `".$MAConf["Table"]."` WHERE aid = ".intval($AID);
	$aMIDs = $db->query_rows($sql);

	$iNumMIDs = count($aMIDs);
	for($i = 0; $i < $iNumMIDs; $i++) {
            $MID = $aMIDs[$i]["mid"];
            $MA = new ItemEdit($MAConf, $connid, $user, $MID);
            $MA->dbdataToInput();
            $MAItems[$i] = $MA->arrInput;

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
	}
        $sql = "SELECT dokid FROM `".$ATConf["Table"]."` WHERE aid = ".intval($AID)
              ." or token = " . $db->quote($AS->arrDbdata['token']);
        $aATs = $db->query_rows($sql);
	echo $db->error();

	$iNumAnlagen = count($aATs);
	for($i = 0; $i < $iNumAnlagen; $i++) {
		$DOKID = $aATs[$i]["dokid"];
		$AT = new ItemEdit($_CONF["umzugsanlagen"], $connid, $user, $DOKID);
		$AT->dbdataToInput();
		$aAtItems[$i] = $AT->arrInput;
		$aAtItems[$i]["datei_link"] = $MConf["WebRoot"]."attachements/".$AT->arrInput["dok_datei"];
		$aAtItems[$i]["datei_groesse"] = format_file_size($AT->arrInput["dok_groesse"]);
		
	}
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
$Tpl->assign("AS", array($AS->arrInput));

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
        'SELECT CONCAT(adresse, ", ", stadtname ) adr '
       .'FROM mm_stamm_gebaeude WHERE id = ' . (int)$AS->arrInput['von_gebaeude_id']);
}
if ((int)$AS->arrInput['nach_gebaeude_id']) {
    $AS->arrInput['nach_gebaeude_text'] = $db->query_one(
        'SELECT CONCAT(adresse, ", ", stadtname ) adr '
       .'FROM mm_stamm_gebaeude WHERE id = ' . (int)$AS->arrInput['nach_gebaeude_id']);
}
if ((int)$AS->arrInput['antragsteller_uid']) {
    $_kid = $db->query_one(
        'SELECT personalnr '
        .'FROM mm_user WHERE uid = ' . (int)$AS->arrInput['antragsteller_uid']);
    $AS->arrInput['personalnr'] = $_kid;
    $AS->arrInput['kid'] = $_kid;
}

$Tpl->assign("s", $s);
$Tpl->assign('AID', $AID);
$Tpl->assign('AIDJson', json_encode($AID));
$Tpl->assign("ASConf", $ASConf['Fields']);$Tpl->assign("AS", $AS->arrInput);
$Tpl->assign("ASJson", json_encode($AS->arrInput));
$Tpl->assign("umzugsstatus", $AS->arrInput['umzugsstatus']);
$Tpl->assign("umzugsstatusJson", json_encode($AS->arrInput['umzugsstatus']));
$Tpl->assign("antragsstatus", $AS->arrInput['antragsstatus']);
$Tpl->assign("antragsstatusJson", json_encode($AS->arrInput['antragsstatus']));
$Tpl->assign("creator", $creator);
$Tpl->assign("creatorJson", json_encode($creator));
$Tpl->assign("user", $user);
$userForJson = $user;
unset($userForJson['pw']);
unset($userForJson['authentcode']);
unset($userForJson['authentcode']);
$Tpl->assign('userJson', json_encode($userForJson));

//die('<pre>' . print_r($AS->arrInput,1) . '</pre>');
$Tpl->assign("propertyName", $propertyName);

if (!empty($MAItems) && count($MAItems)) {
    $Tpl->assign("Mitarbeiterliste", $MAItems);
}
if (!empty($aAtItems) && count($aAtItems)) {
    $Tpl->assign("UmzugsAnlagen", $aAtItems);
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
$sql = 'SELECT ul.leistung_id, ul.leistung_id lid, ul.menge_property, ul.menge2_property, '
      .' ul.menge_mertens, ul.menge2_mertens, '
      .' l.Bezeichnung leistung, lk.leistungskategorie kategorie, '
      .' l.leistungseinheit, l.leistungseinheit2, if(lm.preis, lm.preis, preis_pro_einheit) preis_pro_einheit ' . "\n"
      .' FROM mm_umzuege_leistungen ul ' . "\n"
      .' LEFT JOIN mm_leistungskatalog l ON(ul.leistung_id = l.leistung_id) ' . "\n"
      .' LEFT JOIN mm_leistungskategorie lk ON(l.leistungskategorie_id = lk.leistungskategorie_id) ' . "\n"
      .' LEFT JOIN mm_leistungspreismatrix lm ON('
      .'    l.leistung_id = lm.leistung_id ';
      if ($SumBase == 'MH') {
            $sql.= '    AND lm.mengen_von <= (ul.menge_mertens * IFNULL(ul.menge2_mertens,1)) '
                  .'    AND (lm.mengen_bis >= ( ul.menge_mertens * IFNULL(ul.menge2_mertens,1)))';
      } else {
//      $sql.= '      AND lm.mengen_von <= if(ul.menge_mertens * IFNULL(ul.menge2_mertens,1) < 0.01, ul.menge_property * IFNULL(ul.menge2_property,1), ul.menge_mertens * IFNULL(ul.menge2_mertens,1)) '
//            .'      AND (lm.mengen_bis < 0.01 '
//            .'           || lm.mengen_bis >= if(ul.menge_mertens * IFNULL(ul.menge2_mertens,1) < 0.01, ul.menge_property * IFNULL(ul.menge2_property,1), ul.menge_mertens * IFNULL(ul.menge2_mertens,1))'
//            .'      )'  
      }
      $sql.= ' ) ' . "\n";
      $sql.= ' WHERE ul.aid = :aid';
$aLItems = $db->query_rows($sql, 0, array('aid'=>$AID));
if ($db->error()) die($db->error() . '<br>' . PHP_EOL . $db->lastQuery);
$hideMHMengen  = in_array($AS->arrInput['umzugsstatus'], array('temp','beantragt'));

$Gesamtsumme = 0.0;
foreach($aLItems as &$_it) {
    if (false !== stripos($_it['leistungseinheit'], 'prozent')) {
        $_it['gesamtpreis'] = '';
        continue;
    }
    $_it['gesamtpreis'] = $_it['preis_pro_einheit'];
    if ($SumBase == 'MH') { // $_it['menge_mertens']) {
        $_it['gesamtpreis']*= $_it['menge_mertens'];
        if ($_it['menge2_mertens']) $_it['gesamtpreis']*= $_it['menge2_mertens'];
    } else {
        $_it['gesamtpreis']*= $_it['menge_property'];
        if ($_it['menge2_property']) $_it['gesamtpreis']*= $_it['menge2_property'];
    }
    if ($hideMHMengen) {
        $_it['menge_mertens'] = $_it['menge2_mertens'] = null;
    }
    $Gesamtsumme+= $_it['gesamtpreis'];
}
//echo '#'.__LINE__ . ' SumBase: ' . $SumBasee . '<br>';
//echo '#'.__LINE__ . ' Gesamtsumme: ' . $Gesamtsumme . '<br>';
//die('<pre>' . print_r($aLItems,1) . '</pre>');

if (!empty($aLItems) && count($aLItems)) $Tpl->assign("Umzugsleistungen", $aLItems);
$Tpl->assign("Gesamtsumme", $Gesamtsumme);

$mainmenu = "Class-Active-Umzug"; //Umzug" xclass="liActive
$topmenu = implode("", file($MConf["AppRoot"]."/sites/mitarbeiter_topmenu.tpl.html"));

$AS->loadDbdata();

if ($export === 'csv') {
    header('Content-Type: text/csv; charset="' . $charset . '"');
    header('Content-Disposition: attachment; filename="Auftrag_' . $AID . '.csv"');
    $Tpl->display('umzugsformular.tpl.csv');
    exit;
}

if (in_array($AS->arrDbdata['umzugsstatus'], array('temp', 'angeboten', 'zurueckgegeben', 'geprueft') )) {
	$body_content = $Tpl->fetch('property_umzugsformular.tpl.html');
} else {
	$body_content = $Tpl->fetch('property_umzugsformular.tpl.read.html');
}

//$body_content = implode("", file($MConf["AppRoot"].$MConf["Tpl_Dir"]."umzugsformular.tpl.html"));
if (DEBUG && basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
	echo $body_content;
}
