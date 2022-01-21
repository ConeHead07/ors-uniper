<?php 
if (basename(__FILE__)==basename($_SERVER["PHP_SELF"])) require_once("../header.php");

require_once($InclBaseDir."umzugsantrag.inc.php");
require_once($InclBaseDir."umzugsmitarbeiter.inc.php");

function umzugsantrag_senden() {
    //die('#'.__LINE__ . ' ' . __FILE__ . PHP_EOL . __FUNCTION__ . PHP_EOL  . print_r($_REQUEST,1));
    global $db;
    global $error;
    global $msg;
    global $_CONF;
    global $MConf;
    global $connid;
    global $user;
    
    $userIsAdmin = preg_match('/admin|umzugsteam/', $user["gruppe"]);

    $addBemerkung = "";
    $UpdateToken = "";
    $Umzugsarten = array();
    $cmd = getRequest('cmd', '');
    $AID = getRequest("id","");
    $ASPostItem = getRequest("AS",false);
    if (empty($ASPostItem) || !isset($ASPostItem["name"])) {
        $error.= "Es wurden keine Daten zum Antragsteller übermittelt. Daten konnten nicht gespeichert werden! [sd]<br>\n";
        return false;
    }

    if (!empty($ASPostItem["bemerkungen"])) {
        $addBemerkung = $ASPostItem["bemerkungen"];
        $ASPostItem["bemerkungen"] = "";
    }

    if (!$AID && !empty($ASPostItem["aid"])) $AID = $ASPostItem["aid"];

    $MAConf = $_CONF["umzugsmitarbeiter"];
    
    $ASConf = $_CONF["umzugsantrag"];
    //die('<pre>#'.__LINE__.' ' . __FILE__ . ' f:'.__FUNCTION__ . "\nASConf\n" . print_r($ASConf,1));
    
    $AS = new ItemEdit($ASConf, $connid, $user, $AID);

    if ($AID) {
        if (!$AS->itemExists) {
            $error.= "Es wurde kein Umzugsantrag mit der übermittelten Antrags-ID gefunden!<br>\n";
            return false;
        }
        if (!in_array($AS->arrDbdata["umzugsstatus"], array('temp','angeboten','erneutpruefen','zurueckgegeben')) && !$userIsAdmin) {
            $error.= "Der Antrag wurde bereits zur Genehmigung und Bearbeitung gesendet!<br>\n";
            $error.= "Wenden Sie sich für Änderungen an die Bearbeitungsstelle!<br>\n";
            return false;
        }
    }
    // die('#'.__LINE__ . ' ' . __FILE__ . PHP_EOL . __FUNCTION__ . PHP_EOL  . print_r($_REQUEST,1));
    $MAPostItems = get_ma_post_items();
    
    if ($MConf['min_ma'] && (!is_array($MAPostItems) || !count($MAPostItems))) {
        $error.= "Es wurden keine Mitarbeiter für den Auftrag ausgewählt.<br>\n";
        if ($AS->itemExists) {
            $error.= "Falls Sie den Auftrag stornieren möchten, klicken Sie den 'Stornieren'-Button.<br>\n";
        }
        if ($MConf['min_ma']) return false;
    }
    
    $MAError = false;
    $MAConf["Fields"]["fon"]["required"] = true;
    $MAConf["Fields"]["pcnr"]["required"] = true;
    
    foreach($MAPostItems as $i => $MAItem) {
        $MA = new ItemEdit($MAConf, $connid, $user, false);
        $MAItem["aid"] = $AID;
        $MA->loadInput($MAItem);
        if (!$MA->checkInput()) {
            $error.= "Fehlerhafte Angaben beim ".($i+1).". Mitarbeiter ".$MAItem["name"]."!<br>\n";
            $error.= $MA->Error;
            $MAError = true;
        }
    }
    if ($MAError) {
        return false;
    }
    
    $AS->loadInput($ASPostItem);
    $AS->Error = "";
    if (!$AS->checkInput()) {
        $error.= "Überprüfen Sie Ihre Basis-Angaben als Antragssteller!<br>\n";
        $error.= $AS->Error;
        return false;
    } else {
        if ($addBemerkung) {
            $AS->arrInput["bemerkungen"] = "Bemerkung von ".$user["user"]." am ".date("d.m.Y")." um ".date("H:i")."\n";
            $AS->arrInput["bemerkungen"].= trim($addBemerkung);
            if (!empty($AS->arrDbdata["bemerkungen"])) $AS->arrInput["bemerkungen"].= "\n\n".$AS->arrDbdata["bemerkungen"];
        } else {
            $AS->arrInput["bemerkungen"] = (!empty($AS->arrDbdata["bemerkungen"])) ? $AS->arrDbdata["bemerkungen"] : "";
        }
        //die('<pre>#'.__LINE__.' ' . __FILE__ . ' f:'.__FUNCTION__ . "\nAS->arrInput\n" . print_r($AS->arrInput,1));
        $AS->save();
        if (!$AID) {
            $AID = $AS->id;
            $UpdateToken = $AS->arrInput["token"]; //substr(md5($AID.time()),0,10);
        }
        $error.= $AS->Error;
        $error.= $AS->dbError;
    }
    
    if (!$AID) {
        $error.= "Systemfehler: Antrag konnte nicht angelegt werden!<br>\n";
        //$error.= $AS->Error;
        //$error.= $AS->dbError;
        return false;
    }
    
    $save_ul_count = 0;
    $cntAS = count(array_diff(array_keys($ASPostItem), array('aid', 'bemerkungen')));
    if ($cntAS > 0 || $cmd !== 'status') {
        if (isset($AS->arrDbdata['autocalc_ref_mengen'])) {
            $autocalc_ref_mengen = (bool)$AS->arrDbdata['autocalc_ref_mengen'];
        } else {
            $autocalc_ref_mengen = true;
        }
        umzugsleistungen_speichern($AID, $autocalc_ref_mengen);
        $ulrows = umzugsleistungen_laden($AID);
        foreach($ulrows as $row) {
            if ($row['leistungseinheit'] === 'AP') {
                $save_ul_count+= (int)(is_numeric($row['menge_mertens']) ? $row['menge_mertens'] : $row['menge_property']);                    
            }
        }
    }
    
    $MAConf = $_CONF["umzugsmitarbeiter"];

    $sql = "DELETE FROM `".$MAConf["Table"]."` WHERE aid = \"".$db->escape($AS->id)."`\"";
    $db->query($sql);

    $save_count = 0;
    $save_errors = "";
    foreach($MAPostItems as $i => $MAItem) {
        $MA = new ItemEdit($MAConf, $connid, $user, false);
        $MAItem["aid"] = $AID;
        $MAItem["raumid"] = get_raumid_byGER($MAItem["gebaeude"], $MAItem["etage"], $MAItem["raumnr"]);
        $MAItem["zraumid"] = get_raumid_byGER($MAItem["zgebaeude"], $MAItem["zetage"], $MAItem["zraumnr"]);
        $MA->loadInput($MAItem);

        $MA->arrInput["name"] = strtoupper($MA->arrInput["name"]);
        $MA->arrInput["vorname"] = strtoupper($MA->arrInput["vorname"]);
        $MA->arrInput["extern_firma"] = strtoupper($MA->arrInput["extern_firma"]);

        if ($MA->checkInput()) {
            if (!$MA->save()) {
                $save_errors.= "Der ".($i+1).". Mitarbeitereintrag ".$MAItem["name"]." ".$MAItem["vorname"]." konnte nicht gespeichert werden!<br>\n";
                $save_errors.= $MA->Errors;
            } else {
                $save_count++;
                if ($MAItem["umzugsart"]) {
                    if (isset($Umzugsarten[$MAItem["umzugsart"]])) $Umzugsarten[$MAItem["umzugsart"]]++;
                    else $Umzugsarten[$MAItem["umzugsart"]] = 1;
                }
            }
        } else {
                //
        }
    }
    
    $sNumUmzugsarten = "";
    if (count($Umzugsarten)) {
        foreach($Umzugsarten as $art_name => $art_num) $sNumUmzugsarten.= ($sNumUmzugsarten?", ":"").$art_num."x".$art_name;
    }
    
    $umzugsstatus   = ($userIsAdmin ? ($AS->arrInput["umzug"] === 'Nein' ? 'genehmigt' : 'angeboten') : 'beantragt');
    $antragsstatus  = ($userIsAdmin ? ($AS->arrInput["umzug"] === 'Nein' ? 'genehmigt' : 'angeboten') : 'gesendet');
    $sendmailstatus = ($userIsAdmin ? ($AS->arrInput["umzug"] === 'Nein' ? 'genehmigt' : 'angeboten') : 'neu');
    
    $sql = "UPDATE `".$ASConf["Table"]."` ";
    $sql.= " SET `mitarbeiter_num` = $save_ul_count, ";
    $sql.= " `antragsdatum`=NOW(),";
    $sql.= " `bearbeiter_bemerkung` = \"".$db->escape($sNumUmzugsarten)."\", \n";
    $sql.= " `umzugsstatus`='$umzugsstatus', `umzugsstatus_vom`=NOW()";
    if ($userIsAdmin) {
        $sql.= ",\n geprueft = " . $db->quote("Ja") . "\n";
        $sql.= ",\n geprueft_am = NOW()\n";
        $sql.= ", geprueft_von = " . $db->quote($user["user"]) . "\n";
        $sql.= ",\n angeboten_am = NOW()\n";
        $sql.= ", angeboten_von = " . $db->quote($user["user"]) . "\n";
    }
    if ($userIsAdmin && $AS->arrInput["umzug"] === 'Nein') {
        $sql.= ",\n genehmigt = " . $db->quote('Ja') . "\n";
        $sql.= ",\n genehmigt_br = " . $db->quote('Ja') . "\n";
        $sql.= ",\n genehmigt_br_am = NOW()\n";
        $sql.= ", genehmigt_br_von = " . $db->quote($user["user"]) . "\n";
    }
    if ($UpdateToken) $sql.= ",\n token = \"".$db->escape($UpdateToken)."\"";
    $sql.= "\n WHERE aid = \"".$db->escape($AS->id)."\"";
    /// die('#'.__LINE__ . ' ' . __FILE__ . PHP_EOL . __FUNCTION__ . PHP_EOL . ' next step exec sql: ' . $sql . PHP_EOL . print_r($_REQUEST,1));
    $db->query($sql);
    if ($db->error()) {
        $msg.= '#192 ' . $db->error()."<br>\n".$sql."<br>\n";
    }
    
    if ($save_errors) {
        $error.= "Es konnten nicht alle Mitarbeiterdaten gespeichert werden!<br>\n".$save_errors;
        return false;
    }
    
    $sql = "UPDATE `".$ASConf["Table"]."` SET antragsstatus=\"$antragsstatus\", `umzugstermin`=`terminwunsch` WHERE `aid` = \"".$db->escape($AID)."\"";
    $db->query($sql);
    if (!$db->error()) {
        $msg.= "Daten wurden aktualisert!<br>\n";
    } else {
        $error.= "Fehler beim Aktualisieren der Daten!<br>\n";
    }
    if (umzugsantrag_mailinform($AID, $sendmailstatus, "")) {
        $iNumMails = umzugsantrag_mailinform_get_numMails();
        if ($iNumMails > 0) {
            if ($user['gruppe'] === 'admin') {
                $msg .= "Mail wurde gesendet [Anzahl: $iNumMails]!<br>\n";
            } else {
                $msg .= "Ihre Daten wurden weitergeleiter!<br>\n";
            }
        }
    } else {
        if ($user['gruppe'] === 'admin') {
            $error.= "Fehler beim Mailversand [#213]!<br>\n";
        } else {
            $error.= "Fehler im Nachrichtensystem [#215]!<br>\n";
        }
    }
    return $AS->id;
}
