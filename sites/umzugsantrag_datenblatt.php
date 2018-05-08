<?php 

function get_umzugsblatt($AID, $view = '') {
	global $_CONF;
	global $MConf;
	global $user;
	global $connid;
	global $db;
	global $InclBaseDir;
	
	if (empty($_CONF["umzugsantrag"]))      require_once($InclBaseDir."umzugsantrag.inc.php");
	if (empty($_CONF["umzugsmitarbeiter"])) require_once($InclBaseDir."umzugsmitarbeiter.inc.php");
	if (empty($_CONF["gebaeude"]))          require_once($InclBaseDir."gebaeude.inc.php");
	if (empty($_CONF["umzugsanlagen"]))     require_once($InclBaseDir."umzugsanlagen.inc.php");
	
	$ASConf = &$_CONF["umzugsantrag"];
	$MAConf = &$_CONF["umzugsmitarbeiter"];
	$GBConf = &$_CONF["gebaeude"];
	$ATConf = &$_CONF["umzugsanlagen"];
	
	$cmd = getRequest("cmd","");
	// Get ID, falls Antrag bereits vorhanden
	
	
	$AS = new ItemEdit($_CONF["umzugsantrag"], $connid, $user, $AID);
	$MA = new ItemEdit($_CONF["umzugsmitarbeiter"], $connid, $user, false);
	$Tpl = new myTplEngine();
	
	$sql = "SELECT gebaeude, stadtname, gebaeudename FROM `".$GBConf["Table"]."`";
	$rows = $db->query_rows($sql);
	foreach($rows as $i => $v) $GB[$v["gebaeude"]] = $v;
	
	
	// If AID: Bearbeitungsformular mit DB-Daten
	if ($AID) {
		$AS->loadDbdata();
		$AS->dbdataToInput();
		
		$sql = "SELECT M.mid, G.gebaeudename, G.stadtname  FROM `".$MAConf["Table"]."` M \n";
		$sql.= "Left JOIN `".$GBConf["Table"]."` G ON (M.gebaeude=G.gebaeude) \n";
		$sql.= "WHERE aid = ".intval($AID);
		$aMIDs = $db->query_rows($sql);
		
		for($i = 0; $i < count($aMIDs); $i++) {
			$MID = $aMIDs[$i]["mid"];
			$MA = new ItemEdit($_CONF["umzugsmitarbeiter"], $connid, $user, $MID);
			$MA->dbdataToInput();
			$aMaItems[$i] = $MA->arrInput;
			foreach($aMIDs[$i] as $k => $v)$aMaItems[$i][$k] = $v;
			$g = $aMaItems[$i]["zgebaeude"];
			$aMaItems[$i]["zgebaeudename"] = $GB[$g]["gebaeudename"];
			$aMaItems[$i]["zstadtname"] = $GB[$g]["stadtname"];
			
			$aMaItems[$i]["name"] = strtoupper($aMaItems[$i]["name"]);
			$aMaItems[$i]["vorname"] = strtoupper($aMaItems[$i]["vorname"]);
		}
		
		$sql = "SELECT dokid FROM `".$ATConf["Table"]."` WHERE aid = ".intval($AID);
		$aATs = $db->query_rows($sql);
		
		for($i = 0; $i < count($aATs); $i++) {
			$DOKID = $aATs[$i]["dokid"];
			$AT = new ItemEdit($ATConf, $connid, $user, $DOKID);
			$AT->dbdataToInput();
			$aAtItems[$i] = $AT->arrInput;
			$aAtItems[$i]["datei_link"] = $MConf["WebRoot"]."attachements/".$AT->arrInput["dok_datei"];
			$aAtItems[$i]["datei_groesse"] = format_file_size($AT->arrInput["dok_groesse"]);
		}
	}
        if (intval($AS->arrInput['gebaeude'])) {
            $AS->arrInput['gebaeude_text'] = $db->query_one(
                'SELECT CONCAT(adresse, ", ", stadtname) adr '
               .'FROM mm_stamm_gebaeude WHERE id = ' . intval($AS->arrInput['gebaeude']));
        }
        if (intval($AS->arrInput['von_gebaeude_id'])) {
            $AS->arrInput['von_gebaeude_text'] = $db->query_one(
                'SELECT CONCAT(adresse, ", ", stadtname) adr '
               .'FROM mm_stamm_gebaeude WHERE id = ' . intval($AS->arrInput['von_gebaeude_id']));
        }
        if (intval($AS->arrInput['nach_gebaeude_id'])) {
            $AS->arrInput['nach_gebaeude_text'] = $db->query_one(
                'SELECT CONCAT(adresse, ", ", stadtname) adr '
               .'FROM mm_stamm_gebaeude WHERE id = ' . intval($AS->arrInput['nach_gebaeude_id']));
        }
	
	$Tpl->assign("WebRoot", $MConf["WebRoot"]);
        //die('<pre>' . print_r($AS->arrInput,1) . '</pre>');
	$Tpl->assign("AS", $AS->arrInput);
	if (!empty($aMaItems) && count($aMaItems)) $Tpl->assign("Mitarbeiterliste", $aMaItems);
	if (!empty($aAtItems) && count($aAtItems)) $Tpl->assign("UmzugsAnlagen", $aAtItems);
	
	// Erzeuge GeraeteListe (Array) für Smarty-Template
	$CsvLines = explode("\n", $AS->arrInput["geraete_csv"]);
	$aGItems = array();
	$aGCols = array();
	for ($i = 0; $i < count($CsvLines); $i++) {
	    $aGCols = explode("\t", $CsvLines[$i]);
	    if (count($aGCols) != 4) continue;
	    $aGItems[$i] = array(
	        "Art" => $aGCols[0],
	        "Nr" => $aGCols[1],
	        "Von" => $aGCols[2],
	        "Nach" => $aGCols[3]
	    );
	}
	if (!empty($aGItems) && count($aGItems)) $Tpl->assign("Geraeteliste", $aGItems);

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
        $sql.= ' )' . PHP_EOL;
        $sql.= ' WHERE ul.aid = :aid';
        $aLItems = $db->query_rows($sql, 0, array('aid'=>$AID));
        if ($db->error()) die($db->error() . '<br>' . PHP_EOL . $db->lastQuery);
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

        if (!empty($aLItems) && count($aLItems)) $Tpl->assign("Umzugsleistungen", $aLItems);
        $Tpl->assign("Gesamtsumme", $Gesamtsumme);
		$Tpl->assign("ASConf", $ASConf['Fields']);

        if (!empty($aLItems) && count($aLItems)) $Tpl->assign("Umzugsleistungen", $aLItems);
	
        switch($view) {
            case 'kalkulation':
        	return $Tpl->fetch("kostenkalkulation.tpl.read.html");
                
            case 'rechnung':
        	return $Tpl->fetch("rechnungsanlage.tpl.read.html");
                
            default:
        	return $Tpl->fetch("umzugsblatt.tpl.read.html");
        }
}
