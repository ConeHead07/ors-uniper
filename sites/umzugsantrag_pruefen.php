<?php 
if (basename(__FILE__)==basename($_SERVER["PHP_SELF"])) require_once("../header.php");
require_once($InclBaseDir."umzugsantrag.inc.php");
require_once($InclBaseDir."umzugsantrag.fnc.php");
require_once($InclBaseDir."umzugsmitarbeiter.inc.php");
require_once($InclBaseDir."umzugsmitarbeiter.fnc.php");
require_once($InclBaseDir."leistungskatalog.fnc.php");

if (!function_exists("get_ma_post_items")){ function get_ma_post_items() {
	global $_POST;
	//echo "<pre>#".__LINE__." ".basename(__FILE__)." _POST:".print_r($_POST,1)."</pre><br>\n";
	
	$aMaItems = array();
	if (!empty($_POST["MA"])){
	    // Sorry, Context nicht mehr klar
	    $countMAVorname = count($_POST["MA"]["vorname"]);
	    for($i = 0; $i < $countMAVorname; $i++) {
            $aMaItems[$i]["ID"] = $i+1;
            foreach($_POST["MA"] as $fld => $aTmp) {
                $aMaItems[$i][$fld] = $_POST["MA"][$fld][$i];
            }
        }
    }
	return $aMaItems;
}}


function abteilung_exists($a) {
	global $_TABLE;
	global $db;
	$r = sql_match_rows("SELECT organisationseinheit FROM `".$_TABLE["gf"]."` WHERE `organisationseinheit` LIKE \"$a\" LIMIT 1");
	if (!$r) $r = sql_match_rows("SELECT bereich FROM `".$_TABLE["hauptabteilungen"]."` WHERE `bereich` LIKE \"$a\" LIMIT 1");
	if (!$r) $r = sql_match_rows("SELECT abteilung FROM `".$_TABLE["abteilungen"]."` WHERE `abteilung` LIKE \"$a\" LIMIT 1");
	return $r;
}

function ort_exists($o) {
	global $_TABLE;
	global $db;
	return sql_match_rows("SELECT stadtname FROM `".$_TABLE["gebaeude"]."` WHERE `stadtname` LIKE \"".$db->escape($o)."\" LIMIT 1");
}

function gebaeude_exists($g) {
	global $_TABLE;
	global $db;
	return sql_match_rows("SELECT gebaeude FROM `".$_TABLE["gebaeude"]."` WHERE `gebaeude` LIKE \"".$db->escape($g)."\" LIMIT 1");
}

function etage_exists($g, $e) {
	global $_TABLE;
	global $db;
	return sql_match_rows("SELECT etage FROM `".$_TABLE["immobilien"]."` WHERE `gebaeude` LIKE \"".$db->escape($g)."\" AND etage LIKE \"".$db->escape($e)."\" LIMIT 1");
}

function get_existing_antraegeByMaId($maid, $aid=false) {
	global $_TABLE;
	global $db;
	global $_CONF;
	global $error;
	$ASConf = $_CONF["umzugsantrag"];
	$MAConf = $_CONF["umzugsmitarbeiter"];
	
	$sql = "SELECT um.aid, um.mid, a.`antragsdatum`, a.`umzugsstatus`, a.`umzugsstatus_vom` \n";
	$sql.= "FROM `".$MAConf["Table"]."` um LEFT JOIN `".$ASConf["Table"]."` a USING(aid) \n";
	$sql.= "WHERE um.`maid` = \"".$db->escape($maid)."\" \n";
	if ($aid) $sql.= "AND a.`aid` !=\"".$db->escape($aid)."\" \n";
	$sql.= "AND a.`umzugsstatus` IN ('beantragt','geprueft','genehmigt','bestaetigt')";
	$rows = $db->query_rows($sql);
	//if ($db->error()) $error.= $db->error()."<br>\n".$sql."<br>\n";
	return $rows;
}

function raum_exists($g, $e, $r) {
	global $_TABLE;
	global $db;
	return sql_match_rows("SELECT raumnr FROM `".$_TABLE["immobilien"]."` WHERE `gebaeude` LIKE \"".$db->escape($g)."\" AND etage LIKE \"".$db->escape($e)."\" AND raumnr LIKE \"".$db->escape($r)."\" LIMIT 1");
}

function check_minWerktage($datum, $minWerktage) {
	
	if (strpos($datum, ".") && count(explode(".", $datum))==3) { 
		list($d, $m, $y) = explode(".", $datum);
	} elseif (strpos($datum, "-") && count(explode("-", $datum))==3) { 
		list($y, $m, $d) = explode("-", $datum);
	} else return "Invalid Date!";
	
	$heute= time();
	$current_date = date("Y-m-d");
	$check_date = date("Y-m-d", mktime(3, 0, 0, $m, $d, $y));
	
	$count_werktage = 0;
	$i=0;
	while($current_date <= $check_date && $minWerktage >= $count_werktage) {
		
		if ($current_date > $check_date) break;

		$_nextDayOffset = $i * 24 * 60 * 60;
		$_nextDayTimestamp = $heute + $_nextDayOffset;
		switch(date("w", $_nextDayTimestamp)) {
			case 0:
			case 6:
			break;
			
			default:
			$count_werktage++;
		}
		if ($count_werktage >= $minWerktage){
		    return true;
        }
		$current_date = date("Y-m-d", $heute + ( $i * 24 * 60 * 60 ));
		$i++;
	}
	
	return false;
}

function getLeistungenError() {
    global $user;
    
    $creator = (preg_match('/admin|umzugsteam/', $user['gruppe']) ? 'mertens' : 'property');
    $menge_key  = 'menge_' . $creator;
    $menge2_key = 'menge2_' . $creator;
    $menge_lbl = ('property' == $creator ? 'Menge 1 DSD' : 'Menge 1 MH');
    $menge2_lbl = ('property' == $creator ? 'Menge 2 DSD' : 'Menge 2 MH');
    
    $positionen = array();
    $lst = getRequest('L');
    if (empty($lst['leistung_id']) || !is_array($lst['leistung_id'])) {
        return true;
    }
    
    for($i = 0; $i < count($lst['leistung_id']); ++$i) {
        $lst[ $menge_key ][$i] = getFormattedNumber( $lst[ $menge_key ][$i] );
        $lst[ $menge2_key][$i] = getFormattedNumber( $lst[ $menge2_key ][$i]);
//        $lst[ $menge_key ][$i] = str_replace(',', '.', $lst[ $menge_key ][$i]);
//        $lst[ $menge2_key ][$i] = str_replace(',', '.', $lst[ $menge2_key ][$i]);
        if (!intval($lst['leistung_id'][$i]) && !floatval($lst['leistung_id'][$i])) continue;
        
        $e2 = leistung_einheit2($lst['leistung_id'][$i]);
        $err = '';
        $err2= '';
        if ('' === trim($lst[ $menge_key ][$i]) || !is_numeric($lst[ $menge_key ][$i]) ) {
            $err = $menge_lbl;
        }
        if ($e2 && !is_numeric($lst[ $menge2_key ][$i]) ) {
            $err2 = $menge2_lbl . ' (' . $e2 . ')';
        }
        if ($err || $err2) $positionen[] = 'Pos' . ($i+1) . ': ' . $err . ($err && $err2 ? ', ' : '') . $err2;
        
    }
    if (count($positionen)) {
        return 'Fehlende oder falsche Angaben ' . $menge_lbl . '/' . $menge2_lbl .' in den Positionen: <br>' 
              . implode(', ', $positionen) . '<br>' . PHP_EOL;
    }
    return '';
    
}

function umzugsantrag_fehler() {
    global $db;
    global $_CONF;
    global $MConf;
    global $connid;
    global $user;
    
    $error = "";
    $ASConf = $_CONF["umzugsantrag"];
    $MAConf = $_CONF["umzugsmitarbeiter"];
    
    $creator = ( preg_match('/user|kunde|property/', $user['gruppe'] ) ? 'property' : 'mertens');

    $AID = getRequest("id","");
    $cmd = getRequest("cmd","");
    $name = getRequest("name","");
    $value = getRequest("value","");
    $ASPostItem = getRequest("AS",false);
    
    $checkKundenInput = false;

    $cntAS = count(array_diff(array_keys($ASPostItem), array('aid', 'bemerkungen', 'kostenstelle', 'planonnr')));
    if ( $cntAS > 0 && $cmd !== 'status' && empty($ASPostItem['name'])) {
            $error.= "Es wurden keine Daten zum Antragsteller übermittelt [P]!<br>\n";
            return $error;
    }
	
    if (!$AID && !empty($ASPostItem["aid"])) $AID = $ASPostItem["aid"];

    $AS = new ItemEdit($ASConf, $connid, $user, $AID);

    if ($AID) {
		if (!$AS->itemExists) {
			$error.= "Es wurde kein Umzugsantrag mit der übermittelten Antrags-ID gefunden!<br>\n";
			return $error;
		} else {
			$AS->loadDbdata();
			if (!$AS->arrDbdata["umzugsstatus"] 
				|| $AS->arrDbdata["umzugsstatus"] == "zuruckgegeben"
				|| $AS->arrDbdata["umzugsstatus"] == "zurueckgegeben"
				|| $AS->arrDbdata["umzugsstatus"] == "angeboten"
				|| $AS->arrDbdata["umzugsstatus"] == "temp") {
				$checkKundenInput = true;
			}
			
			if ($name === 'genehmigt' && $value === 'Ja') {                    
                $pspError = '';
                $plnError = '';
                if (!empty($ASPostItem['kostenstelle']) 
                && !$AS->checkFieldInput('kostenstelle', trim($ASPostItem['kostenstelle']), $pspError) ) {
                    $error.= '#'.__LINE__ . ' PSP-Element: ' . $pspError . "\n";
                }
                if (!empty($ASPostItem['planonnr']) 
                && !$AS->checkFieldInput('planonnr', trim($ASPostItem['planonnr']), $plnError) ) {
                    $error.= '#'.__LINE__ . ' Planon-Nr: ' . $plnError . "\n";
                }                    
                if ($error) {
                    return $error;
                }                    
            }   
		}
    } else {
        $checkKundenInput = true;
    }
    
    // PSP-Element
    if (true === $checkKundenInput && 'property' == $creator) {
        // Kann nachtraeglich gesetzt werden, da conf als Referenz uebergeben wird
        $ASConf['Fields']['kostenstelle']['required'] = true;
        $ASConf['Fields']['planonnr']['required'] = true;
    }

    $MAPostItems = get_ma_post_items();
    if ($MConf['min_ma'] && (!is_array($MAPostItems) || !count($MAPostItems))) {
            $error.= "Es wurden keine Mitarbeiter für den Auftrag ausgewählt.<br>\n";
            if ($AS->itemExists) {
                    $error.= "Falls Sie den Auftrag stornieren möchten, klicken Sie den 'Stornieren'-Button.<br>\n";
            }
            return $error;
    }

    $errLst = getLeistungenError();
    if ($errLst && $errLst != 1) $error.= $errLst;
	
    $MAError = false;
    foreach($MAPostItems as $i => $MAItem) {
            $MA = new ItemEdit($MAConf, $connid, $user, false);
            $MAItem["aid"] = $AID;
            if ($MAItem["maid"]) $rows_dubletten = get_existing_antraegeByMaId($MAItem["maid"], $AID);

            $errDubletten = "";
            if (!empty($rows_dubletten) && is_array($rows_dubletten) && count($rows_dubletten)) {
                    foreach($rows_dubletten as $dub) {
                            $lnkDub = (strpos($user["gruppe"], "admin") === false) ? $dub["antragsdatum"] : "<a href=\"?s=aantrag&id=".urlencode($dub["aid"])."\" target=\"winDub\">".$dub["antragsdatum"]." ".($dub["umzugsstatus"]!="beantragt"?$dub["umzugsstatus"]." am ".format_dbDate($dub["umzugsstatus_vom"],"d.m"):"")." (ID:".$dub["aid"].")</a>";
                            $errDubletten.= "Für den Mitarbeiter existiert bereits ein Antrag vom ".$lnkDub."!<br>\n";

                    }
            }
            $MA->loadInput($MAItem);
            if (!$MA->checkInput() || $errDubletten) {
                    $error.= "Fehlerhafte Angaben beim ".($i+1).". Mitarbeiter ".$MAItem["name"]."!<br>\n";
                    $error.= $errDubletten.$MA->Error;
                    //if (count($MA->arrErrFlds)) $error.= print_r($MA->arrErrFlds, 1);
                    $MAError = true;
            } else {			
                    $raumtyp = get_raumtyp_byGER($MAItem["gebaeude"], $MAItem["etage"], $MAItem["raumnr"]);
                    $zraumtyp = get_raumtyp_byGER($MAItem["zgebaeude"], $MAItem["zetage"], $MAItem["zraumnr"]);

                    if ($raumtyp && $raumtyp == "GBUE" && !preg_match('/^\d*$/', $MAItem["apnr"])) {
                            $error.= "Fehlende oder ungültige Ist-Arbeitsplatznr (Ganzzahl) beim ".($i+1).". Mitarbeiter ".$MAItem["name"]."!<br>\n";
                            $MAError = true;
                    }
                    if ($zraumtyp && $zraumtyp == "GBUE" && !preg_match('/^\d*$/', $MAItem["zapnr"])) {
                            $error.= "Fehlende oder ungültige Ziel-Arbeitsplatznr (Ganzzahl) beim ".($i+1).". Mitarbeiter ".$MAItem["name"]."!<br>\n";
                            $MAError = true;
                    }
            }
    }
    if ($MAError) {
            return $error;
    }
	
    $AS->loadInput($ASPostItem);
    $AS->Error = "";
    if ( ($cntAS > 0 || $cmd !== 'status') && !$AS->checkInput()) {
            $error.= "Überprüfen Sie die Basis-Angaben zum Antragssteller!<br>\n";
            $error.= $AS->Error;
            return $error;
    }

    if ( ($cntAS > 0 || $cmd !== 'status') && getRequest("umzugsart") != "Datenpflege") {
            if (!$AID || $AS->arrDbdata["umzugsstatus"] == "temp") {
                    if (!check_minWerktage($ASPostItem["terminwunsch"], $MConf['minWerktageVorlauf'] )) {
						$error.= "Umzugstermin ist zu kurzfristig. Planen Sie eine Vorlaufzeit von mind. {$MConf[$minWerktageVorlauf]} Arbeitstagen ein!<br>\n";
                    }
            }
    }

    if ( $cntAS > 0 || $cmd !== 'status' ) {
        //die('<pre>' . print_r($ASPostItem,1).'</pre>');
        $error.= umzugsantrag_get_zuordnungs_fehler($ASPostItem, $MAPostItems);
    }
    return $error;
}

function umzugsantrag_get_zuordnungs_fehler($ASItem, $MAItems) {
	$as_error = "";
	$ma_error = "";

    if (empty($ASItem["ort"])) {
        $as_error.= "Die Ortsangabe darf nicht leer sein!\n";
    }

    if (getAppConfigProperty('validateAntragOrt', true) && !ort_exists($ASItem["ort"])) {
        $as_error.= "Ungültige Ortsauswahl ".$ASItem["ort"]."!<br>\n";
    }

    if (getAppConfigProperty('validateAntragGebaeude', false)) {


        if ( empty($ASItem["gebaeude"])) {
            $as_error .= "Gebäudeangabe darf nicht leer sein!<br>\n";
        }

        if ( !gebaeude_exists($ASItem["gebaeude"])) {
            $as_error .= "Ungültige Gebäudeauswahl " . $ASItem["gebaeude"] . "!<br>\n";
        }
    }
	
	if ($as_error) {
	    $as_error = "<strong>Fehlerhafte Angaben beim Antragsteller:</strong><br>\n".$as_error."<br>\n";
    }

	$numMAItems = count($MAItems);
	for($i = 0; $i < $numMAItems; $i++) {
		$MA = $MAItems[$i];
		$n = $i+1;
		
		if (empty($MA["abteilung"]) || !abteilung_exists($MA["abteilung"])){
		    $ma_error.= "[MA $n] Ungültige Abteilungsauswahl: von ".$ASItem["abteilung"]."!<br>\n";
        }
		if (empty($MA["gebaeude"]) || !gebaeude_exists($MA["gebaeude"])){
		    $ma_error.= "[MA $n] Ungültige Gebäudeauswahl: von ".$ASItem["gebaeude"]."!<br>\n";
        }
		elseif (empty($MA["gebaeude"]) || !etage_exists($MA["gebaeude"], $MA["etage"])){
		    $ma_error.= "[MA $n] Ungültige Etagenauswahl: von ".$MA["etage"]." in ".$MA["gebaeude"]."!<br>\n";
        }
		elseif (empty($MA["raumnr"]) || !etage_exists($MA["gebaeude"], $MA["etage"], $MA["raumnr"])){
		    $ma_error.= "[MA $n] Ungültige Raumauswahl: von ".$MA["raumnr"]." in ".$MA["gebaeude"]." ".$MA["etage"]."!<br>\n";
        }
		
		
		if (empty($MA["zabteilung"]) || !abteilung_exists($MA["zabteilung"])){
		    $ma_error.= "[MA $n] Ungültige Abteilungsauswahl: nach ".$ASItem["zabteilung"]."!<br>\n";
        }
		
		if (empty($MA["zgebaeude"]) || !gebaeude_exists($MA["zgebaeude"])){
		    $ma_error.= "[MA $n] Ungültige Gebäudeangabe: nach ".$ASItem["zgebaeude"]."!<br>\n";
        }
		elseif (empty($MA["zgebaeude"]) || !etage_exists($MA["zgebaeude"], $MA["zetage"])){
		    $ma_error.= "[MA $n] Ungültige Etagenauswahl: nach ".$MA["zetage"]." in ".$MA["zgebaeude"]."!<br>\n";
        }
		elseif (empty($MA["zraumnr"]) || !etage_exists($MA["zgebaeude"], $MA["zetage"], $MA["zraumnr"])){
		    $ma_error.= "[MA $n] Ungültige Raumauswahl: nach ".$MA["zraumnr"]." in ".$MA["zgebaeude"]." ".$MA["zetage"]."!<br>\n";
        }
		
	}
	if ($ma_error){
	    $ma_error = "<strong>Fehlerhafte Angaben in der Mitarbeiterliste:</strong><br>\n".$ma_error."<br>\n";
    }
	
	return $as_error.$ma_error;
}

?>
