<?php 
require_once($InclBaseDir . 'umzugsantrag.inc.php');
require_once($InclBaseDir . 'umzugsmitarbeiter.inc.php');
require_once($InclBaseDir . 'umzugsanlagen.inc.php');
require_once($InclBaseDir . 'leistungskatalog.inc.php');
require_once($SitesBaseDir . 'umzugsantrag_sendmail.php');

$ASConf = &$_CONF['umzugsantrag'];
$MAConf = &$_CONF['umzugsmitarbeiter'];
$ATConf = &$_CONF['umzugsanlagen'];
$LKConf = &$_CONF['leistungskatalog'];
$ASConf['Fields']['token']['default'] = substr(md5(print_r($user,1).time().rand(1,999999)),0,10);

$creator = ( preg_match('/user|kunde|property/', $user['gruppe'] ) ? 'property' : 'mertens');


if ('property' == $creator) {
    // PSP-Element
    // $ASConf['Fields']['kostenstelle']['required'] = true;
    // Planon-Nr
    // $ASConf['Fields']['planonnr']['required'] = true;
}

/** @var $db dbconn $db */
if (empty($db)) {
    $db = new dbconn($MConf['DB_Host'], $MConf['DB_Name'], $MConf['DB_User'], $MConf['DB_Pass']);
}

//  preismatrix_id 	leistung_id 	preis 	preiseinheit 	mengen_von 	mengen_bis
$NL = "\n";
$sql = 'SELECT ' . $NL
    . ' l.leistung_id, ' . $NL
    . ' l.leistung_ref_id, ' . $NL
    . ' l.leistung_ref_id2, ' . $NL
    . ' l.leistung_ref_id3, ' . $NL
    . ' IFNULL(l.leistung_stamm_id, StammIds.leistung_stamm_id) AS leistung_stamm_id, ' . $NL
    . ' IFNULL(StammIds.numVarianten, 0) AS NumVarianten, ' . $NL
    . ' l.Bezeichnung, ' . $NL
    . ' l.Bezeichnung leistung, ' . $NL
    . ' l.Beschreibung, ' . $NL
    . ' l.Farbe, ' . $NL
    . ' l.Groesse, ' . $NL
    . ' produkt_link, ' . $NL
    . ' leistungseinheit, ' . $NL
    . ' leistungseinheit2, ' . $NL
    . ' leistungskategorie AS kategorie, ' . $NL
    . ' l.leistungskategorie_id AS kategorie_id, ' . $NL
    . ' l.aktiv, l.verfuegbar, ' . $NL
    . ' preis_pro_einheit, image, ' . $NL
    . ' m.preis mx_preis, ' . $NL
    . ' m.preiseinheit mx_preiseinheit, ' . $NL
    . ' m.mengen_von mx_von, ' . $NL
    . ' m.mengen_bis mx_bis' . $NL
    . ' FROM mm_leistungskatalog l LEFT JOIN mm_leistungskategorie k ' . $NL
    . '  ON l.leistungskategorie_id = k.leistungskategorie_id ' . $NL
    . ' LEFT JOIN mm_leistungspreismatrix m ' . $NL
    . '  ON l.leistung_id = m.leistung_id ' . $NL
    . ' LEFT JOIN (' . $NL
    . '     SELECT l.leistung_stamm_id, COUNT(1) numVarianten ' . $NL
    . '     FROM mm_leistungskatalog l' . $NL
    . '     WHERE l.aktiv="Ja" AND IFNULL(l.leistung_stamm_id, 0) != 0 AND l.leistung_id != l.leistung_stamm_id' . $NL
    . ' GROUP BY leistung_stamm_id' . $NL
    . ') StammIds ' . $NL
    . ' ON (l.leistung_id = StammIds.leistung_stamm_id OR l.leistung_stamm_id = StammIds.leistung_stamm_id)' . $NL
    . ' WHERE l.aktiv = "Ja" ' . $NL
    . ' ORDER BY kategorie, Bezeichnung, mx_von' . $NL;


$lkTreeItems = array();
$lkTreeItemsJson = array();
$lkmById = array();
$lkItems = $db->query_rows($sql);
// die(print_r(array_column($lkItems, 'leistung_id'), 1));
if ($db->error()) {
    echo $db->error() . '<br>' . PHP_EOL;
    echo $sql . '<br>' . PHP_EOL;
}
foreach($lkItems as $k => $v) {
    $v['leistung_id'] = (int)$v['leistung_id'];
    $v['leistung_ref_id'] = (int)$v['leistung_ref_id'];
    $v['leistung_ref_id2'] = (int)$v['leistung_ref_id2'];
    $v['leistung_ref_id3'] = (int)$v['leistung_ref_id3'];
    $v['leistung_stamm_id'] = (int)$v['leistung_stamm_id'];
    $v['NumVarianten'] = (int)$v['NumVarianten'];
    $v['preis_pro_einheit'] = (float)$v['preis_pro_einheit'];
    $ktg1 = (empty($v['kategorie'])) ? 'Einsatz' : $v['kategorie'];
    $lkTreeItems[$ktg1][$v['leistung']][] = $v;
    
    $jvals = $v;

    $lkTreeItemsJson[$ktg1][$v['leistung_id']] = $jvals;
    $lkmById[$v['leistung_id']] = array();

    if ($v['mx_preis']) {
        $lkmById[$v['leistung_id']][] = array(
            'preis' => $v['mx_preis'],
            'von'   => $v['mx_von'],
            'bis'   => $v['mx_bis'],
        );
    }
}
// die('<pre>' . json_encode(compact('lkItems', 'lkTreeItems', 'lkTreeItemsJson'), JSON_PRETTY_PRINT));

//die('<pre> lkItmes: ' . print_r($lkItems,1) . '</pre>');
$cmd = getRequest('cmd','');
// Get ID, falls Antrag bereits vorhanden
$AID = getRequest('id','');
if (empty($AID)) $AID = (!empty($_POST['AS']['aid']) ? $_POST['AS']['aid'] : (!empty($_GET['AS']['aid']) ? $_GET['AS']['aid'] : ''));

$ASInput = getRequest('AS','');
//die("#".__LINE__." ".__FILE__);
$AS = new ItemEdit($_CONF['umzugsantrag'], $connid, $user, $AID);
$MA = new ItemEdit($_CONF['umzugsmitarbeiter'], $connid, $user, false);

$Tpl = new myTplEngine();
$TplMaListItem = array('ID'=>1, 'nachname'=>'', 'vorname'=>'', 'ort'=>'', 'gebaeude'=>'', 'raumnr'=>'');
$PreiseAnzeigen = ($user['darf_preise_sehen'] === 'Ja') ? 1 : 0;

$Tpl->assign('PreiseAnzeigen', $PreiseAnzeigen);
$Tpl->assign('lktreeItems', $lkTreeItems);
$Tpl->assign('lkTreeItemsJson', json_encode($lkTreeItemsJson) );
$Tpl->assign('lkmByIdJson', json_encode($lkmById) );

// If AID: Bearbeitungsformular mit DB-Daten
if ($AID) {
    $AS->loadDbdata();
    if ($AS->arrDbdata["antragsteller_uid"] != $user['uid'] && strpos($user['gruppe'], 'admin') === false) {
        die("UNERLAUBTER ZUGRIFF!");
    }
    $AS->dbdataToInput();
    
    if ($ASInput) {
        if (!empty($ASInput["add_bemerkungen"])) {
            $enrichedBemerkung = "Bemerkung von ".$user['user']." am ".date('d.m.Y')." um ".date("H:i")." zum Status ".$AS->arrDbdata['umzugsstatus'].":\n";
            $enrichedBemerkung.= trim($ASInput["add_bemerkungen"])."\n\n";
            $AS->arrInput['bemerkungen'] = $enrichedBemerkung .  $AS->arrDbdata['bemerkungen'];
            umzugsantrag_mailinform($AID, 'neuebemerkung', $enrichedBemerkung, $user);
            $AS->save();
        }
    }

    $sql = "SELECT mid FROM `" . $MAConf['Table'] . "` WHERE aid = " . (int)$AID;
    $aMIDs = $db->query_rows($sql);

    $iNumMIDs = count($aMIDs);
    for($i = 0; $i < $iNumMIDs; $i++) {
        $MID = $aMIDs[$i]['mid'];
        $MA = new ItemEdit($MAConf, $connid, $user, $MID);
        $MA->dbdataToInput();
        $aMaItems[$i] = $MA->arrInput;
    }
    
    $sql = "SELECT dokid FROM `" . $ATConf['Table'] . "` WHERE aid = " . ((int)$AID)
          ." or token = " . $db->quote($AS->arrDbdata['token']);
    $aATs = $db->query_rows($sql);

    $iNumAnlagen = count($aATs);
    for($i = 0; $i < $iNumAnlagen; $i++) {
        $DOKID = $aATs[$i]['dokid'];
        $AT = new ItemEdit($ATConf, $connid, $user, $DOKID);
        $AT->dbdataToInput();
        $aAtItems[$i] = $AT->arrInput;
        $aAtItems[$i]["datei_link"] = $MConf['WebRoot']."attachements/".$AT->arrInput["dok_datei"];
        $aAtItems[$i]["datei_groesse"] = format_file_size($AT->arrInput["dok_groesse"]);
    }
} else {
    // else: lade Eingabeformular
    $defaultAS = array(
        'vorname' => $user['vorname'],
        'name' => $user['nachname'],
        'fon' => $user['fon'],
        'email'=> $user['email'],
        'ort' => '',
        'gebaeude' => ''
    );
    $AS->loadInput($defaultAS, false);
    $MA->loadInput(array(), false);
    $as->arrInput['personalnr'] = $user['personalnr'];

    //$aMaItems = array($MA->arrInput);
}
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

// die('<pre>$AS->arrInput: ' . print_r($AS->arrInput,1));

$umzug_optionvals = explode("','", trim($ASConf['Fields']['umzug']['size'], "'"));
$Tpl->assign('s', $s);
$Tpl->assign('AID', $AID);
$Tpl->assign('AIDJson', json_encode($AID));
$Tpl->assign('ASConf', $ASConf['Fields']);
$Tpl->assign('AS', $AS->arrInput);
$Tpl->assign('ASJson', json_encode($AS->arrInput));
$Tpl->assign('umzugsstatus', $AS->arrInput['umzugsstatus']);
$Tpl->assign('umzugsstatusJson', json_encode($AS->arrInput['umzugsstatus']));
$Tpl->assign('antragsstatus', $AS->arrInput['antragsstatus']);
$Tpl->assign('antragsstatusJson', json_encode($AS->arrInput['antragsstatus']));
$Tpl->assign("umzug_options", array_combine($umzug_optionvals, $umzug_optionvals));
$Tpl->assign('creator', $creator);
$Tpl->assign('creatorJson', json_encode($creator));
$Tpl->assign('user', $user);
$userForJson = $user;
unset($userForJson['pw']);
unset($userForJson['authentcode']);
unset($userForJson['authentcode']);
$Tpl->assign('userJson', json_encode($userForJson));

if ('property' == $creator
    && (
        !$AID 
        || !$AS->arrDbdata['umzugsstatus']
        || $AS->arrDbdata['umzugsstatus'] == 'zuruckgegeben'
        || $AS->arrDbdata['umzugsstatus'] == 'zurueckgegeben'
        || $AS->arrDbdata['umzugsstatus'] == 'angeboten'
        || $AS->arrDbdata['umzugsstatus'] == 'temp'
    )
) {
    // Kann nachtraeglich gesetzt werden, da conf als Referenz uebergeben wird
    $ASConf['Fields']['kostenstelle']['required'] = true;
    $ASConf['Fields']['planonnr']['required'] = true;
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
        'Art' => $aGCols[0],
        'Nr' => $aGCols[1],
        'Von' => $aGCols[2],
        'Nach' => $aGCols[3]
    );
}

$SumBase = 'MH';
$sql = 'SELECT ul.leistung_id, ul.leistung_id lid, ul.menge_property, ul.menge2_property, ' . "\n"
    . ' ul.menge_mertens, ul.menge2_mertens, ' . "\n"
    . ' ul.menge_rekla, ul.menge2_rekla, ' . "\n"
    . ' ul.menge_geliefert, ul.menge2_geliefert, ' . "\n"
    . ' l.Bezeichnung leistung, lk.leistungskategorie kategorie, lk.leistungskategorie_id kategorie_id, '  . "\n"
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
$lastQuery = $db->lastQuery;
if (0 && $AID && !$aLItems) {
    echo json_encode(compact('lastQuery', 'aLItems'), JSON_PRETTY_PRINT);
    exit;
}

if ($db->error()) {
    die($db->error() . '<br>' . PHP_EOL . $db->lastQuery);
}

$hideMHMengen  = false; ($creator !== 'mertens' && in_array($AS->arrInput['umzugsstatus'], array('temp','beantragt')));
$hideDusMengen = $AS->arrInput['umzugsstatus'] == 'angeboten';

$Gesamtsumme =  0.0;
$aLItemsForJson = [];
$iNumLItems = count($aLItems);
for($i = 0; $i < $iNumLItems; $i++) {  // as $i => &$_it) {
    $_it = &$aLItems[$i];
    $aLItemsForJson[$i] = $_it;
    $_utf8EncL = utf8_encode($_it['leistung']);
    $aLItemsForJson[$i]['leistung'] = ($_utf8EncL) ? $_utf8EncL : '???';

    $_utf8EncK = utf8_encode($_it['kategorie']);
    $aLItemsForJson[$i]['kategorie'] = ($_utf8EncK) ? $_utf8EncK : '???';

    $_utf8EncLE2 = utf8_encode($_it['leistungseinheit2']);
    $aLItemsForJson[$i]['leistungseinheit2'] = ($_utf8EncLE2) ? $_utf8EncLE2 : 'Stck';

    $_utf8EncLE = utf8_encode($_it['leistungseinheit']);
    $aLItemsForJson[$i]['leistungseinheit'] = ($_utf8EncLE) ? $_utf8EncLE : 'Stck';
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
    if ($hideDusMengen) {
        $_it['menge_property'] = $_it['menge2_property'] = null;
    }
    if ($hideMHMengen) {
        $_it['menge_mertens'] = $_it['menge2_mertens'] = null;
    }

    $Gesamtsumme+= $_it['gesamtpreis'];
}

if (!empty($aLItems) && count($aLItems)) {
    $Tpl->assign('Umzugsleistungen', $aLItems);
    $Tpl->assign('UmzugsleistungenJson',     json_encode($aLItemsForJson, JSON_PRETTY_PRINT) );
} else {
    $Tpl->assign('Umzugsleistungen', []);
    $Tpl->assign('UmzugsleistungenJson',     json_encode([]));
}
$Tpl->assign('Gesamtsumme', $Gesamtsumme);
if (!empty($aGItems) && count($aGItems)) $Tpl->assign('Geraeteliste', $aGItems);
if (!empty($aMaItems) && count($aMaItems)) $Tpl->assign('Mitarbeiterliste', $aMaItems);
if (!empty($aAtItems) && count($aAtItems)) $Tpl->assign('UmzugsAnlagen', $aAtItems);

$mainmenu = "Class-Active-Umzug"; //Umzug" xclass="liActive
$topmenu = implode('', file($MConf['AppRoot']."/sites/mitarbeiter_topmenu.tpl.html"));

if ($AS->arrInput['umzugsstatus']=='temp' || $AS->arrInput['umzugsstatus']=='zurueckgegeben') {
    $template = 'umzugsformular.tpl.html';
} else {
    $template = 'umzugsformular.tpl.read.html';
}
$body_content = $Tpl->fetch($template);

// echo "#".__LINE__." ".basename(__FILE__)." \$aAtItems:".print_r($aAtItems,1)."<br>\n";
//$body_content = implode('', file($MConf['AppRoot'].$MConf["Tpl_Dir"].'umzugsformular.tpl.html'));
if (DEBUG && basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    echo $body_content;
}
