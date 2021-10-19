<?php 

if (basename($_SERVER["PHP_SELF"]) == basename(__FILE__)) {
	require_once("../header.php");
	require_once($InclBaseDir."umzugsantrag.inc.php");
	require_once($InclBaseDir."umzugsmitarbeiter.inc.php");
	require_once($InclBaseDir."umzugsanlagen.inc.php");
}

require($MConf["AppRoot"]."sites".DS."umzugsantrag_datenblatt.php");
if (empty($_CONF["umzugsanlagen"])) {
    require_once($InclBaseDir."umzugsanlagen.inc.php");
}

function get_umzugsblatt_verteiler($antragsOrt, $antragsGebaeude) {
    die('#'.__LINE__ . ' ' . __FUNCTION__ . '(' . print_r(func_get_args(),1) . ')');
	global $MConf;
	
	$Region = "";
	$verteilerFile = "";
	
	if (!$verteilerFile) {
		//echo "#".__LINE__." antragsOrt:$antragsOrt; antragsGebaeude:$antragsGebaeude; Region:$Region; verteilerFile:$verteilerFile<br>\n";
		return "";
	}
	
	$TextBaseDir = $MConf["AppRoot"].$MConf["Texte_Dir"];
	$verteiler = file_get_contents($TextBaseDir.$verteilerFile);
	$verteiler = strtr($verteiler, array("\r\n"=>"\n","\r"=>"\n"," "=>"\n",","=>"\n", ";"=>"\n"));
	while(strpos($verteiler, "\n\n")!==false) $verteiler = str_replace("\n\n", "\n", $verteiler);
	//echo "#".__LINE__." <br>\nOrt:$antragsOrt; <br>\nGeb:$antragsGebaeude; <br>\nReg:$Region; <br>\nFil:$verteilerFile<br>\nVer:<br>\n".$verteiler."<br>\n<br>\n";
	return $verteiler;
}

function get_usersByAid($AID) {
//        die('#'.__LINE__ . ' ' . __FUNCTION__ . '(' . print_r(func_get_args(),1) . ')');
	global $MConf;
	global $_CONF;
	global $aHeader;
	global $db;
	global $connid;
	global $user;
	global $_TABLE;

    $row = $db->query_row(
        'SELECT gebaeude, antragsteller_uid '
        . ' FROM ' . $_TABLE['umzugsantrag'] . ' ' . PHP_EOL
        . ' WHERE aid = ' . (int)$AID

    );
    if (!$row) {
        return null;
    }
    $gebaeudeId = $row['gebaeude'];
    $antrag_uid = $row['antragsteller_uid'];

	$checkUsersByGebaeude = false;

	if (!$checkUsersByGebaeude) {
        $users = array(
            'antragsteller' => $db->query_row(
                'SELECT uid, user, email, fon, emails_cc, gruppe, adminmode, freigegeben, anrede, vorname, nachname, '
                . ' personalnr, strasse, plz, ort, standort, standortverwaltung, gebaeude, darf_preise_sehen, '
                . ' email AS `to` '
                . ' FROM  mm_user ' . PHP_EOL
                . ' WHERE freigegeben = "Ja" AND uid = ' . (int)$antrag_uid
            ),
            'admins' => $db->query_rows(
                'SELECT uid, user, email, fon, emails_cc, gruppe, adminmode, freigegeben, anrede, vorname, nachname, '
                . ' personalnr, strasse, plz, ort, standort, standortverwaltung, gebaeude, darf_preise_sehen, '
                . ' email AS `to` '
                . ' FROM  mm_user ' . PHP_EOL
                . ' WHERE freigegeben = "Ja" AND gruppe IN ("admin") '
            ),
            'properties' => $db->query_rows(
                'SELECT uid, user, email, fon, emails_cc, gruppe, adminmode, freigegeben, anrede, vorname, nachname, '
                . ' personalnr, strasse, plz, ort, standort, standortverwaltung, gebaeude, darf_preise_sehen, '
                . ' email AS `to` '
                . ' FROM  mm_user ' . PHP_EOL
                . ' WHERE freigegeben = "Ja" AND gruppe IN ("kunde-report", "v-property", "property") '
            ),
        );
    } else {
//        die($db->lastQuery . PHP_EOL . '<br>' . print_r($row, 1));

        // user.gruppe enum('user','umzugsteam','kunde_report','admin_standort','admin_gesamt','admin','v-mitarbeiter','v-property','mertens')
        $users = array(
            'antragsteller' => $db->query_row(
                'SELECT uid, user, email, fon, emails_cc, gruppe, adminmode, freigegeben, anrede, vorname, nachname, '
                . ' personalnr, strasse, plz, ort, standort, standortverwaltung, gebaeude, darf_preise_sehen, '
                . ' email AS to'
                . ' FROM  mm_user ' . PHP_EOL
                . ' WHERE uid = ' . (int)$antrag_uid
            ),
            'admins' => $db->query_rows(
                'SELECT uid, user, email, fon, emails_cc, gruppe, adminmode, freigegeben, anrede, vorname, nachname, '
                . ' personalnr, strasse, plz, ort, standort, standortverwaltung, gebaeude, darf_preise_sehen, '
                . ' email AS to'
                . ' FROM  mm_user ' . PHP_EOL
                . ' WHERE gruppe IN ("admin") '
            ),
            'properties' => $db->query_rows(
                'SELECT uid, user, email, fon, emails_cc, gruppe, adminmode, freigegeben, anrede, vorname, nachname, '
                . ' personalnr, strasse, plz, ort, standort, standortverwaltung, gebaeude, darf_preise_sehen, '
                . ' email AS to'
                . ' FROM  mm_user ' . PHP_EOL
                . ' WHERE gruppe IN ("v-property", "property") '
            ),
            'regionalmanager' => $db->query_row(
                'SELECT u.* FROM mm_stamm_gebaeude g ' . PHP_EOL
                . ' LEFT JOIN mm_user u ON (g.regionalmanager_uid = u.uid)' . PHP_EOL
                . ' WHERE id = ' . (int)$gebaeudeId
            ),
            'standortmanager' => $db->query_row(
                'SELECT u.* FROM mm_stamm_gebaeude g ' . PHP_EOL
                . ' LEFT JOIN mm_user u ON (g.standortmanager_uid = u.uid)' . PHP_EOL
                . ' WHERE id = ' . (int)$gebaeudeId
            ),
            'objektleiter' => $db->query_row(
                'SELECT u.* FROM mm_stamm_gebaeude g ' . PHP_EOL
                . ' LEFT JOIN mm_user u ON (g.objektleiter_uid = u.uid)' . PHP_EOL
                . ' WHERE id = ' . (int)$gebaeudeId
            ),
            'mertenshenk' => $db->query_row(
                'SELECT u.* FROM mm_stamm_gebaeude g ' . PHP_EOL
                . ' LEFT JOIN mm_user u ON (g.mertenshenk_uid = u.uid)' . PHP_EOL
                . ' WHERE id = ' . (int)$gebaeudeId
            ),
        );
    }

    return $users;
}

function get_umzugsverteilerById($AID) {
	global $MConf;
	global $_CONF;
	global $aHeader;
	global $db;
	global $connid;
	global $user;
	global $_TABLE;
	
	$aFilterOrtGeb = array();
	$verteiler = array();
	$tmpVerteiler = array();
	
	$MAConf = $_CONF["umzugsmitarbeiter"];
	$UAConf = $_CONF["umzugsantrag"];
	
	$sql = "SELECT ort, gebaeude FROM `".$UAConf["Table"]."` a\n";
	$sql.= "WHERE aid = \"".$db->escape($AID)."\"\n";
	$row = $db->query_singlerow($sql);
	$aFilterOrtGeb[$row["ort"]][$row["gebaeude"]] = 1;
	
	$sql = "SELECT g.stadtname ort, a.gebaeude, zg.stadtname ziel_ort, a.ziel_gebaeude FROM `".$MAConf["Table"]."` a\n";
	$sql.= "LEFT JOIN `".$_TABLE["gebaeude"]."` zg ON ( zg.gebaeude = a.ziel_gebaeude )\n";
	$sql.= "LEFT JOIN `".$_TABLE["gebaeude"]."` g ON ( g.gebaeude = a.gebaeude )\n";
	$sql.= "WHERE aid = \"".$db->escape($AID)."\"\n";
	$sql.= "GROUP BY g.stadtname, g.gebaeude, zg.stadtname, ziel_gebaeude";
	
	$rows = $db->query_rows($sql);
        if (!is_array($rows) || count($rows) === 0) return $verteiler;
        
	//echo "<pre>#".__LINE__." sql:$sql; \nerror:".$db->error()."\n rows:".print_r($rows,1)."</pre>\n";
	
	foreach($rows as $row) {
		$aFilterOrtGeb[$row["ort"]][$row["gebaeude"]] = 1;
		$aFilterOrtGeb[$row["ziel_ort"]][$row["ziel_gebaeude"]] = 1;
	}
	foreach($aFilterOrtGeb as $ort => $aGeb) {
		foreach($aGeb as $gebaeude => $tmp) {
			//echo "#".__LINE__." ".basename(__FILE__)." ort:$ort; gebaeude:$gebaeude<br>\n";
			$tmpVerteiler = explode("\n", get_umzugsblatt_verteiler($ort, $gebaeude));
			//echo "#".__LINE__." ".basename(__FILE__)." tmpVerteiler:".print_r($tmpVerteiler,1)."<br>\n";
			if (is_array($tmpVerteiler)) foreach($tmpVerteiler as $email) if (!in_array($email, $verteiler)) $verteiler[]=$email;
		}
	}
	//echo "#".__LINE__." ".basename(__FILE__)." verteiler:".print_r($verteiler,1)."<br>\n";
	return $verteiler;
}

function get_propertyverteilerById($AID) {
        return array();
	global $MConf;
	global $_CONF;
	global $aHeader;
	global $db;
	global $connid;
	global $user;
	global $_TABLE;
	
	$aFilterOrtGeb = array();
	$verteiler = array();
	$tmpVerteiler = array();

	$addedEmails = array();
	
	$MAConf = $_CONF["umzugsmitarbeiter"];
	$UAConf = $_CONF["umzugsantrag"];
	
	$sql = "SELECT ort, gebaeude FROM `".$UAConf["Table"]."` a\n";
	$sql.= "WHERE aid = \"".$db->escape($AID)."\"\n";
	$row = $db->query_singlerow($sql);
	$aFilterOrtGeb[$row["ort"]][$row["gebaeude"]] = 1;
	
	$sql = "SELECT g.stadtname ort, a.gebaeude, zg.stadtname ziel_ort, a.ziel_gebaeude FROM `".$MAConf["Table"]."` a\n";
	$sql.= "LEFT JOIN `".$_TABLE["gebaeude"]."` zg ON ( zg.gebaeude = a.ziel_gebaeude )\n";
	$sql.= "LEFT JOIN `".$_TABLE["gebaeude"]."` g ON ( g.gebaeude = a.gebaeude )\n";
	$sql.= "WHERE aid = \"".$db->escape($AID)."\"\n";
	$sql.= "GROUP BY g.stadtname, g.gebaeude, zg.stadtname, ziel_gebaeude";
	
	$rows = $db->query_rows($sql);
	//echo "<pre>#".__LINE__." sql:$sql; \nerror:".$db->error()."\n rows:".print_r($rows,1)."</pre>\n";
	
	foreach($rows as $row) {
		$aFilterOrtGeb[$row["ort"]][$row["gebaeude"]] = 1;
		$aFilterOrtGeb[$row["ziel_ort"]][$row["ziel_gebaeude"]] = 1;
	}
	foreach($aFilterOrtGeb as $ort => $aGeb) {
		foreach($aGeb as $gebaeude => $tmp) {
			//echo "#".__LINE__." ".basename(__FILE__)." ort:$ort; gebaeude:$gebaeude<br>\n";
			$tmpVerteiler = get_standort_property_mail($ort, $gebaeude);
			//echo "#".__LINE__." ".basename(__FILE__)." tmpVerteiler:".print_r($tmpVerteiler,1)."<br>\n";
			
			if (is_array($tmpVerteiler)) foreach($tmpVerteiler as $v_user) {
				if (!array_key_exists($v_user["email"], $addedEmails)) {
					$v_user["admin_ort"] = $ort.": ".$gebaeude;
					$verteiler[]=$v_user;
					$addedEmails[$v_user["email"]] = 1;
				}
			}
		}
	}
	//echo "#".__LINE__." ".basename(__FILE__)." verteiler:".print_r($verteiler,1)."<br>\n";
	return $verteiler;
}

function getRegionalmanager(){}

function send_umzugsblatt($AID, $antragsOrt, $antragsGebaeude, $aData) {
        die('#'.__LINE__ . ' ' . __FUNCTION__ . '(' . print_r(func_get_args(),1) . ')');
	//echo "#".__LINE__." "; print_r($aData)."<br>\n";
	global $MConf;
	global $_CONF;
	global $aHeader;
	global $db;
	global $connid;
	global $user;
	
	$ATConf = &$_CONF["umzugsanlagen"];
	//echo "#".__LINE__." ".basename(__FILE__)." send_umzugsblatt($AID, $antragsOrt, $antragsGebaeude)<br>\n";
	
	$sql = "SELECT dokid FROM `".$ATConf["Table"]."` WHERE aid = ".intval($AID);
	$aATs = $db->query_rows($sql);
	
	$AtList = "";
	$iNumAnlangen = count($aATs);
	for($i = 0; $i < $iNumAnlangen; $i++) {
		$DOKID = $aATs[$i]["dokid"];
		$AT = new ItemEdit($ATConf, $connid, $user, $DOKID);
		$AT->dbdataToInput();
		$aAtItems[$i] = $AT->arrInput;
		$aAtItems[$i]["datei_link"] = $MConf["WebRoot"]."attachements/".$AT->arrInput["dok_datei"];
		$aAtItems[$i]["datei_groesse"] = format_file_size($AT->arrInput["dok_groesse"]);
		
		$AtList.= "<li><a href=\"".$aAtItems[$i]["datei_link"]."\"><strong>".$AT->arrInput["dok_datei"]." (".$aAtItems[$i]["datei_groesse"].")</strong></a><br>\n";
		//$AtList.= "<a href=\"".$aAtItems[$i]["datei_link"]."\">".$aAtItems[$i]["datei_link"]."</a>";
		$AtList.= "</li>\n\n";
	}
	
	$verteiler = get_umzugsverteilerById($AID);
	
	$aMailTo = $verteiler;
	//echo "verteiler: ".file_get_contents($TextBaseDir.$verteilerFile)."\n\n";
	//echo "aMailTo: ".print_r($aMailTo,1)."\n\n";
	$umzugsblatt = get_umzugsblatt($AID);
	
	$subject = "Auftrag ID ".$AID." - Lieferschein";
	$plaintext = "Guten Tag,\n\nanbei erhalten Sie den Lieferschein für den Auftrag mit der ID ".$AID.".\n\nMit freundlichen Grüßen\n\nIhr\n".$MConf["AppTitle"];
	$htmltext = "";
	$header = "";
	$specs = "";
	foreach($aHeader as $k => $v) {
		if ($v) $header.= ($header?"\n":"").$k.": ".$v;
	}
	
	$attachement[0]["quelle"]="data";
	$attachement[0]["file"]=$umzugsblatt;
	$attachement[0]["fname"]="umzugsblatt_".$AID.".html";
	$attachement[0]["fsize"]=strlen($umzugsblatt);
	$attachement[0]["fmime"]="text/html";
	
	if ($AtList) {
		$htmltext = "Guten Tag,<br>\n<br>\nanbei erhalten Sie den Lieferschein für den Auftrag mit der ID ".$AID.".<br>\n<br>\n";
		$htmltext.= "Dem Auftrag wurden ".count($aAtItems)." Dateianh&auml;nge zum Download beigef&uuml;gt:<br>\n";
		$htmltext.= "<ol>".$AtList."</ol>\n";
		$htmltext.= "<br>\n<br>\nMit freundlichen Gr&uuml;&szlig;en<br>\n<br>\nIhr<br>\n".$MConf["AppTitle"];
		$plaintext = "";
	}
	
	$specs="";

	$iNumMailTos = count($aMailTo);
	for($i = 0; $i < $iNumMailTos; $i++) {
		if (!trim($aMailTo[$i])) continue;
		$name = str_replace(".", " ", current(explode("@", $aMailTo[$i])));
		$mailTo = array(array("email"=>$aMailTo[$i], "Name"=>$name, "Vorname"=>"", 'anrede' => $name));
		//echo "<pre>#".__LINE__." send_umzugsblatt() mailTo: ".print_r($mailTo,1)."</pre>\n";
		$numRecipients = SmtpMailer::getNewInstance()->sendMultiMail($mailTo, $subject, $htmltext, $plaintext, $attachement, $aHeader);
	}
	
}

function get_standort_property_mail($antragsOrt, $antragsGebaeude) {
        return array(); //$aPropertyMailTo;
}

function get_standort_admin_mail($antragsOrt, $antragsGebaeude) {
        
	$aAdminMailTo[] = array("email"=>"service-uniper@mertens.ag", "Name"=>"ORS", "Vorname"=>"", 'emails_cc' => '');
	foreach($aAdminMailTo as $k => $v) 
            $aAdminMailTo[$k]['to'] = $aAdminMailTo[$k]['email'];
        
	return $aAdminMailTo;
}

function send_status_mail($aUserTo, $tplMail, $rplVars, $aAttachements = false, $authorUser = [])
{
    global $aHeader;
    global $user;
    $arg0 = $aUserTo;

    if (!is_array($authorUser) || count(array_keys($authorUser)) === 0) {
        $authorUser = $user;
    }

	if (!isset($aUserTo[0]['email'])) {
	    $error = 'Für die Statusmail konnten keine E-Mail-Empfänger ermittelt werden!';
	    $stack = [];
	    try {
	        throw new \Exception('Debug-Stack-Trace');
        } catch(\Exception $e) {
	        $stack = $e->getTrace();
        }
        $line = __LINE__;
        $file = __FILE__;
        $debugInfo = print_r(compact('error', 'line', 'file', 'arg0', 'aUserTo', 'tplMail', 'rplVars', 'authorUser', 'stack'), 1);
	    error_log($debugInfo);
        die($debugInfo);
	}

	$lines = explode("\n", $tplMail);

    $aUserHeader = $aHeader;

	$tplSu = trim((strpos($lines[0], 'Betreff=') === 0)
        ? substr($lines[0],8)
        : $lines[0]
    );
	
	$tplBody = trim(implode("\n",array_slice($lines, 1)));
	
	$success = true;
	$iNumRecipients = count($aUserTo);
	$iNumSentTo = 0;

	for ($i = 0; $i < $iNumRecipients; $i++) {
	    if (empty($aUserTo[$i])) {
	        continue;
        }
        $to = $aUserTo[$i]["email"];
        $su = $tplSu;
        $body = $tplBody;
        if (!isset($aUserTo[$i]["emails_cc"])) {
            echo 'Missing field emails_cc in aUserTo: ';
            print_r(compact('aUserTo'));
            exit;
        }
        $cc = trim($aUserTo[$i]["emails_cc"]);

        $rplVars['UserTo'] = json_encode($aUserTo[$i]);
        $rplVars['Vorname'] = $aUserTo[$i]["vorname"] ?? '';
        $rplVars['Name'] =  $aUserTo[$i]["nachname"] ?? '';
        $rplVars['ausgefuehrtam'] = date('d.m.Y', strtotime($rplVars['umzugstermin'] ) );

        if (trim($rplVars['umzugszeit'])) {
            $rplVars['ausgefuehrtam'].= $rplVars['umzugszeit'];
        }

        $aUserHeader['Reply-To'] = $rplVars['Reply-To'] ?? 'service-uniper@mertens.ag';

        $mailer = SmtpMailer::getNewInstance();
        $mailer->setTplVars($rplVars, 'UTF-8');
        $iNumSentTo+= $mailer->sendMultiMail([ ['email' => $to, 'anrede' => ''] ], $su, null, $body, [], $aUserHeader);

        // print_r(compact('iNumRecipients', 'aUserTo', 'iNumSentTo', 'to', 'su', 'body', 'cc', 'rplVars'));

        if ($cc) {
            $aCC = explode(',', $cc);
            foreach($aCC as $_emailAddress) {
                $iNumSentTo+= $mailer->sendMultiMail([ ['email' => $_emailAddress, 'anrede' => ''] ], $su, null, $body, [], $aUserHeader);
            }
        }
	}

	return $success && $iNumSentTo > 0;
}

function umzugsantrag_mailinform($AID, $status="neu", $value, $authorUser = []) {
//  throw new Exception('#'.__LINE__ . ' ' . __FUNCTION__ . '(' . print_r(func_get_args(),1) . ')');
	global $db;
	global $error;
	global $msg;
	global $LoadScript;
	global $_CONF;
	global $MConf;
	global $connid;
	global $user;

	if (!is_array($authorUser) || count(array_keys($authorUser)) === 0) {
	    $authorUser = $user;
    }
    
	$users = get_usersByAid($AID);
        
	$TextBaseDir = $MConf["AppRoot"] . $MConf["Texte_Dir"];
        
	if (!$AID) {
		echo '#' . __LINE__ . ' AID: ' . $AID . '<br>' . PHP_EOL;
		return false;
	}
	$ASInput = getRequest('AS');
	$bemerkung = (!empty($ASInput['bemerkungen'])) ? $ASInput['bemerkungen'] : '';
        
	$AS = new ItemEdit($_CONF["umzugsantrag"], $connid, $user, $AID);
	$MAConf = $_CONF["umzugsmitarbeiter"];
	
	$AS->loadDbdata();
	$AS->dbdataToInput();
	$auftragsDaten = $AS->arrDbdata;
        
	if (empty($AS->arrInput['angeboten_am']) && $status === "genehmigt") {
		$status = 'bestaetigt';
	}
        
	$rplVars = 
		array_merge(
			$AS->arrInput,
			array(
				"AID" => $AID,
				"StatusLink" => $MConf["WebRoot"]."?s=aantrag&id=".$AID,
				"HomepageTitle" => $MConf["AppTitle"],
				"Bemerkung" => $bemerkung,
			)
		);
	$userMailTo[] = $users['antragsteller'];
    // die('#' . __LINE__ . '' . __FILE__ . print_r(compact('AID', 'userMailTo', 'users', 'status', 'value')));
	
    // Leider noch doppelte Abfrage aus evtll. Kompatibilitätsgründen, eigentlich unnötig, Korrektur steht noch an !!!
	$aPropertyMailTo = $users['properties'];

	$aAdminMailTo = $users['admins'];
        
	$aAdminMailTo[] = array(
	    "to"=>"service-uniper@mertens.ag",
        "email"=>"service-uniper@mertens.ag",
        "nachname"=>"ORS Uniper",
        "vorname"=>"NewNormalOffice",
        'emails_cc' => ''
    );

	$dbg = 0;
	$getDebugSteuerinfosAsPlaintext = function($status, $tplFile, $authorUser, $aMailTo, $_configNameEnable) {
	    global $MConf;
        $txt = "\n\nBenachrichtungs-Steuerinfos:\n";
        $txt.= "- $_configNameEnable: " . (isset($MConf[$_configNameEnable]) ? json_encode($MConf[$_configNameEnable]) : 'UNDEFINED') . "\n";
        $txt.= "- Status: $status\n";
        $txt.= "- Template: $tplFile\n";
        $txt.= "- Ersteller: \n";
        $txt.= "- - uid: "    . ($authorUser['uid'] ?? 'UNDEFINED')    . "\n";
        $txt.= "- - user: "   . ($authorUser['user'] ?? 'UNDEFINED')   . "\n";
        $txt.= "- - email: "  . ($authorUser['email'] ?? 'UNDEFINED')  . "\n";
        $txt.= "- - gruppe: " . ($authorUser['gruppe'] ?? 'UNDEFINED') . "\n";
        $txt.= "- Empfänger: \n";
        foreach($aMailTo as $_m) {
            $txt.= "- - "
                . ($_m['uid'] ?? 'UNDEFINED') . ' '
                . ($_m['user'] ?? 'UNDEFINED') . ' '
                . ($_m['email'] ?? 'UNDEFINED') . ' '
                . ($_m['gruppe'] ?? 'UNDEFINED') . ' '
                . "\n";
        }
        return $txt;
    };
    $return = true;
	switch($status) {
        case "neuebemerkung":
            $tplFile = $TextBaseDir."statusmail_umzug_bemerkung.txt";
            $_configNameEnable = 'notify_user_bemerkung';
            if ((int)$authorUser['uid'] !== (int)$auftragsDaten['antragsteller_uid']) {
                $_configNameEnable.= '_selfcreated';
            }
            if ($MConf[$_configNameEnable]) {
                $tplMail = file_get_contents($tplFile);
                $rplVars["StatusLink"] = $MConf["WebRoot"] . "?s=pantrag&id=" . $AID;
                $rplVars["neuebemerkung"] = $value;
                if ($_CONF['STATUSMAIL_ADD_STEUERINFOS']) {
                    $tplMail .= $getDebugSteuerinfosAsPlaintext($status, $tplFile, $authorUser, $userMailTo, $_configNameEnable);
                }
                if ($dbg) {
                    $LINE = __LINE__;
                    $FILE = __FILE__;
                    print_r(compact('LINE', 'FILE', 'tplFile', 'tplMail', 'rplVars', 'userMailTo'));
                }
                $return = send_status_mail($userMailTo, $tplMail, $rplVars);
            }

            $tplFile = $TextBaseDir."statusmail_umzug_bemerkung.txt";
            $_configNameEnable = 'notify_mertens_bemerkung';
            if ($authorUser['gruppe'] !== 'user') {
                $_configNameEnable.= '_selfcreated';
            }
            if ($MConf[$_configNameEnable]) {
                $tplMail = file_get_contents($tplFile);
                $rplVars["StatusLink"] = $MConf["WebRoot"] . "?s=aantrag&id=" . $AID;
                if ($_CONF['STATUSMAIL_ADD_STEUERINFOS']) {
                    $tplMail .= $getDebugSteuerinfosAsPlaintext($status, $tplFile, $authorUser, $aAdminMailTo, $_configNameEnable);
                }
                if ($dbg) {
                    $LINE = __LINE__;
                    $FILE = __FILE__;
                    print_r(compact('LINE', 'FILE', 'tplFile', 'tplMail', 'rplVars', 'aAdminMailTo'));
                }
                return send_status_mail($aAdminMailTo, $tplMail, $rplVars);
            }
            return $return;
            break;

		case "neu":
		case "beantragt":
			$tplFile = $TextBaseDir."statusmail_umzug_neu.txt";
            $_configNameEnable = 'notify_property_beantragt';
			if (count($aPropertyMailTo) && $MConf[$_configNameEnable]) {
                if ($dbg) echo '#' . __LINE__ . ' FILE: ' . $tplFile . '; EXISTS: ' . (file_exists($tplFile) ? 'Ja' : 'Nein') . PHP_EOL;
                $tplMail = file_get_contents($tplFile);
                $rplVars["StatusLink"] = $MConf["WebRoot"] . "?s=pantrag&id=" . $AID;
                if ($_CONF['STATUSMAIL_ADD_STEUERINFOS']) {
                    $tplMail .= $getDebugSteuerinfosAsPlaintext($status, $tplFile, $authorUser, $aPropertyMailTo, $_configNameEnable);
                }
                $return = send_status_mail($aPropertyMailTo, $tplMail, $rplVars);
            }

            $_configNameEnable = 'notify_mertens_beantragt';
			if ($MConf[$_configNameEnable]) {
                $tplFile = $TextBaseDir . "statusmail_umzug_zurpruefung.txt";
                $tplMail = file_get_contents($tplFile);
                $rplVars["StatusLink"] = $MConf["WebRoot"] . "?s=aantrag&id=" . $AID;
                if ($_CONF['STATUSMAIL_ADD_STEUERINFOS']) {
                    $tplMail .= $getDebugSteuerinfosAsPlaintext($status, $tplFile, $authorUser, $aAdminMailTo, $_configNameEnable);
                }
                if ($dbg) {
                    $LINE = __LINE__;
                    $FILE = __FILE__;
                    print_r(compact('LINE', 'FILE', 'tplFile', 'tplMail', 'rplVars', 'aAdminMailTo'));
                }
                return send_status_mail($aAdminMailTo, $tplMail, $rplVars);
            }
            return $return;
			break;
            
		case "erneutpruefen":
            $_configNameEnable = 'notify_mertens_erneutpruefen';
		    if ($MConf[$_configNameEnable]) {
                $tplFile = $TextBaseDir . "statusmail_umzug_zurerneutenpruefung.txt";
                $tplMail = file_get_contents($tplFile);
                $rplVars["StatusLink"] = $MConf["WebRoot"] . "?s=aantrag&id=" . $AID;
                if ($_CONF['STATUSMAIL_ADD_STEUERINFOS']) {
                    $tplMail .= $getDebugSteuerinfosAsPlaintext($status, $tplFile, $authorUser, $aAdminMailTo, $_configNameEnable);
                }
                if ($dbg) {
                    $LINE = __LINE__;
                    $FILE = __FILE__;
                    print_r(compact('LINE', 'FILE', 'tplFile', 'tplMail', 'rplVars', 'aAdminMailTo'));
                }
                return send_status_mail($aAdminMailTo, $tplMail, $rplVars);
            }
            return $return;
		    break;
            
		case 'angeboten':
            $_configNameEnable = 'notify_property_angeboten';
            if ($MConf[$_configNameEnable]) {
                $tplFile = $TextBaseDir . "statusmail_umzug_zurgenehmigung.txt";
                $tplMail = file_get_contents($tplFile);
                $rplVars["StatusLink"] = $MConf["WebRoot"] . "?s=pantrag&id=" . $AID;
                if ($_CONF['STATUSMAIL_ADD_STEUERINFOS']) {
                    $tplMail .= $getDebugSteuerinfosAsPlaintext($status, $tplFile, $authorUser, $aPropertyMailTo, $_configNameEnable);
                }
                if ($dbg) {
                    $LINE = __LINE__;
                    $FILE = __FILE__;
                    print_r(compact('LINE', 'FILE', 'tplFile', 'tplMail', 'rplVars', 'aPropertyMailTo'));
                }
                return send_status_mail($aPropertyMailTo, $tplMail, $rplVars);
            }
            return $return;
            break;
		
		case "geprueft":
            $_configNameEnable = 'notify_property_geprueft';
		    if ($MConf[$_configNameEnable]) {
                $tplFile = $TextBaseDir . "statusmail_umzug_zurgenehmigung.txt";
                $tplMail = file_get_contents($tplFile);
                $rplVars["StatusLink"] = $MConf["WebRoot"] . "?s=pantrag&id=" . $AID;
                if ($_CONF['STATUSMAIL_ADD_STEUERINFOS']) {
                    $tplMail .= $getDebugSteuerinfosAsPlaintext($status, $tplFile, $authorUser, $aPropertyMailTo, $_configNameEnable);
                }
                if ($dbg) {
                    $LINE = __LINE__;
                    $FILE = __FILE__;
                    print_r(compact('LINE', 'FILE', 'tplFile', 'tplMail', 'rplVars', 'aPropertyMailTo'));
                }
                return send_status_mail($aPropertyMailTo, $tplMail, $rplVars);
            }
            return $return;
		    break;
		
		case "genehmigt":
			$rplVars["StatusLink"] = $MConf["WebRoot"]."?s=aantrag&id=".$AID;
			switch($value) {
				case "Nein":
                    $_configNameEnable = 'notify_user_genehmigt_Nein';
				    if ($MConf[$_configNameEnable]) {
                        $tplFile = $TextBaseDir . "statusmail_umzug_kabgelehnt.txt";
                        $tplMail = file_get_contents($tplFile);
                        $rplVars["StatusLink"] = $MConf["WebRoot"] . "?s=kantrag&id=" . $AID;
                        if ($_CONF['STATUSMAIL_ADD_STEUERINFOS']) {
                            $tplMail .= $getDebugSteuerinfosAsPlaintext($status, $tplFile, $authorUser, $userMailTo, $_configNameEnable);
                        }
                        if ($dbg) {
                            $LINE = __LINE__;
                            $FILE = __FILE__;
                            print_r(compact('LINE', 'FILE', 'tplFile', 'tplMail', 'rplVars', 'userMailTo'));
                        }
                        $sentToUser = send_status_mail($userMailTo, $tplMail, $rplVars);
                        $return = $sentToUser;
                    }

                    $_configNameEnable = 'notify_mertens_genehmigt_Nein';
				    if ( $MConf[$_configNameEnable]) {
                        $tplFile = $TextBaseDir . "statusmail_umzug_abgelehnt.txt";
                        $tplMail = file_get_contents($tplFile);
                        $rplVars["StatusLink"] = $MConf["WebRoot"] . "?s=aantrag&id=" . $AID;
                        if ($_CONF['STATUSMAIL_ADD_STEUERINFOS']) {
                            $tplMail .= $getDebugSteuerinfosAsPlaintext($status, $tplFile, $authorUser, $aAdminMailTo, $_configNameEnable);
                        }
                        if ($dbg) {
                            $LINE = __LINE__;
                            $FILE = __FILE__;
                            print_r(compact('LINE', 'FILE', 'tplFile', 'tplMail', 'rplVars', 'aAdminMailTo'));
                        }
                        return send_status_mail($aAdminMailTo, $tplMail, $rplVars);
                    }
				    return $return;
				    break;
				
				case "Ja":
				default:
                    $_configNameEnable = 'notify_property_genehmigt_Ja';
				    if ($MConf[$_configNameEnable]) {
                        $tplFile = $TextBaseDir . "statusmail_umzug_aktiv.txt";
                        $tplMail = file_get_contents($tplFile);
                        if ($_CONF['STATUSMAIL_ADD_STEUERINFOS']) {
                            $tplMail .= $getDebugSteuerinfosAsPlaintext($status, $tplFile, $authorUser, $aPropertyMailTo, $_configNameEnable);
                        }
                        if ($dbg) {
                            $LINE = __LINE__;
                            $FILE = __FILE__;
                            print_r(compact('LINE', 'FILE', 'tplFile', 'tplMail', 'rplVars', 'aPropertyMailTo'));
                        }
                        $return = send_status_mail($aPropertyMailTo, $tplMail, $rplVars);
                    }

                    $_configNameEnable = 'notify_mertens_genehmigt_Ja';
				    if ($MConf[$_configNameEnable]) {
                        $tplFile = $TextBaseDir . "statusmail_umzug_genehmigt.txt";
                        $tplMail = file_get_contents($tplFile);
                        if ($_CONF['STATUSMAIL_ADD_STEUERINFOS']) {
                            $tplMail .= $getDebugSteuerinfosAsPlaintext($status, $tplFile, $authorUser, $aAdminMailTo, $_configNameEnable);
                        }
                        if ($dbg) {
                            $LINE = __LINE__;
                            $FILE = __FILE__;
                            print_r(compact('LINE', 'FILE', 'tplFile', 'tplMail', 'rplVars', 'aAdminMailTo'));
                        }
                        return send_status_mail($aAdminMailTo, $tplMail, $rplVars);
                    }
			}
            return $return;
		    break;
		
		case "bestaetigt": // 
            if ($value == "Ja") {
                $tplFile = $TextBaseDir."statusmail_umzug_bestaetigt.txt";
                $tplMail = file_get_contents($tplFile);
                // Benachrichtige UmzugsTeam über bestätigten Umzugsauftrag
//			       send_umzugsblatt($AID, $AS->arrInput["ort"], $AS->arrInput["gebaeude"], $AS->arrInput);
            } else {
                $tplFile = $TextBaseDir."statusmail_umzug_aufhebung.txt";
                $tplMail = file_get_contents($tplFile);
                // Informiere Umzugsteam über (bis auf weiteres) aufgehobenen Umzugsauftrag
//			       $rplVars["StatusLink"] = $MConf["WebRoot"]."?s=aantrag&id=".$AID;
//			       send_status_mail($aAdminMailTo, $tplMail, $rplVars);
            }

            $_configNameEnable = 'notify_user_bestaetigt_' . ($value === 'Ja' ? 'Ja' : 'Nein');
            if ($MConf[$_configNameEnable]) {
                $rplVars["StatusLink"] = $MConf["WebRoot"] . "?s=kantrag&id=" . $AID;
                if ($_CONF['STATUSMAIL_ADD_STEUERINFOS']) {
                    $tplMail .= $getDebugSteuerinfosAsPlaintext($status, $tplFile, $authorUser, $userMailTo, $_configNameEnable);
                }
                if ($dbg) {
                    $LINE = __LINE__;
                    $FILE = __FILE__;
                    print_r(compact('LINE', 'FILE', 'tplFile', 'tplMail', 'rplVars', 'userMailTo', 'users'));
                }
                $sentToUser = send_status_mail($userMailTo, $tplMail, $rplVars);
                $return  = $sentToUser;
            }

            $_configNameEnable = 'notify_property_bestaetigt_' . ($value === 'Ja' ? 'Ja' : 'Nein');
            if (count($aPropertyMailTo) && ($MConf[$_configNameEnable])) {
                $rplVars["StatusLink"] = $MConf["WebRoot"] . "?s=pantrag&id=" . $AID;
                if ($_CONF['STATUSMAIL_ADD_STEUERINFOS']) {
                    $tplMail .= $getDebugSteuerinfosAsPlaintext($status, $tplFile, $authorUser, $aPropertyMailTo, $_configNameEnable);
                }
                if ($dbg) {
                    $LINE = __LINE__;
                    $FILE = __FILE__;
                    print_r(compact('LINE', 'FILE', 'tplFile', 'tplMail', 'rplVars', 'aPropertyMailTo'));
                }
                return (send_status_mail($aPropertyMailTo, $tplMail, $rplVars) && $sentToUser);
            }
            return $return;
            break;
		
		case "zurueckgegeben":
            $_configNameEnable = 'notify_user_zurueckgegeben';
		    if ($MConf[$_configNameEnable]) {
                $tplFile = $TextBaseDir . "statusmail_umzug_zurueckgegeben.txt";
                $tplMail = file_get_contents($tplFile);

                $rplVars["StatusLink"] = $MConf["WebRoot"] . "?s=kantrag&id=" . $AID;
                if ($_CONF['STATUSMAIL_ADD_STEUERINFOS']) {
                    $tplMail .= $getDebugSteuerinfosAsPlaintext($status, $tplFile, $authorUser, $userMailTo, $_configNameEnable);
                }
                if ($dbg) {
                    $LINE = __LINE__;
                    $FILE = __FILE__;
                    print_r(compact('LINE', 'FILE', 'tplFile', 'tplMail', 'rplVars', 'userMailTo'));
                }
                return send_status_mail($userMailTo, $tplMail, $rplVars);
            }
            return $return;
            break;
		
		case "abgeschlossen":
			switch($value) {
				case "Ja":
                    $_configNameEnable = 'notify_user_abgeschlossen';
				    if ($MConf[$_configNameEnable]) {
                        $tplFile = $TextBaseDir . "statusmail_umzug_durchgefuehrt.txt";
                        $tplMail = file_get_contents($tplFile);
                        $rplVars["StatusLink"] = $MConf["WebRoot"] . "?s=kantrag&id=" . $AID;
                        if ($_CONF['STATUSMAIL_ADD_STEUERINFOS']) {
                            $tplMail .= $getDebugSteuerinfosAsPlaintext($status, $tplFile, $authorUser, $userMailTo, $_configNameEnable);
                        }
                        if ($dbg) {
                            $LINE = __LINE__;
                            $FILE = __FILE__;
                            print_r(compact('LINE', 'FILE', 'tplFile', 'tplMail', 'rplVars', 'userMailTo'));
                        }
                        $return = send_status_mail($userMailTo, $tplMail, $rplVars);
                    }

                    $_configNameEnable = 'notify_property_abgeschlossen';
				    if ($MConf[$_configNameEnable]) {
                        $rplVars["StatusLink"] = $MConf["WebRoot"] . "?s=pantrag&id=" . $AID;
                        if ($_CONF['STATUSMAIL_ADD_STEUERINFOS']) {
                            $tplMail .= $getDebugSteuerinfosAsPlaintext($status, $tplFile, $authorUser, $aPropertyMailTo, $_configNameEnable);
                        }
                        if ($dbg) {
                            $LINE = __LINE__;
                            $FILE = __FILE__;
                            print_r(compact('LINE', 'FILE', 'tplFile', 'tplMail', 'rplVars', 'aPropertyMailTo'));
                        }
                        return send_status_mail($aPropertyMailTo, $tplMail, $rplVars);
                    }
				    return $return;
				    break;

				case "Storniert":
                    $_configNameEnable = 'notify_user_storniert';
				    if ($MConf[$_configNameEnable]) {
                        $tplFile = $TextBaseDir . "statusmail_umzug_storniert.txt";
                        $tplMail = file_get_contents($tplFile);
                        $rplVars["StatusLink"] = $MConf["WebRoot"] . "?s=kantrag&id=" . $AID;
                        if ($_CONF['STATUSMAIL_ADD_STEUERINFOS']) {
                            $tplMail .= $getDebugSteuerinfosAsPlaintext($status, $tplFile, $authorUser, $userMailTo, $_configNameEnable);
                        }
                        if ($dbg) {
                            $LINE = __LINE__;
                            $FILE = __FILE__;
                            print_r(compact('LINE', 'FILE', 'tplFile', 'tplMail', 'rplVars', 'userMailTo'));
                        }
                        $return = send_status_mail($userMailTo, $tplMail, $rplVars);
                    }

				    $_configNameEnable = 'notify_property_storniert';
				    if ($MConf[$_configNameEnable]) {
                        $rplVars["StatusLink"] = $MConf["WebRoot"] . "?s=pantrag&id=" . $AID;
                        if ($_CONF['STATUSMAIL_ADD_STEUERINFOS']) {
                            $tplMail .= $getDebugSteuerinfosAsPlaintext($status, $tplFile, $authorUser, $aPropertyMailTo, $_configNameEnable);
                        }
                        if ($dbg) {
                            $LINE = __LINE__;
                            $FILE = __FILE__;
                            print_r(compact('LINE', 'FILE', 'tplFile', 'tplMail', 'rplVars', 'aPropertyMailTo'));
                        }
                        return send_status_mail($aPropertyMailTo, $tplMail, $rplVars);
                    }
				    return $return;
				    break;
			}
            return $return;
		    break;
		
		default:
		return false;
	}
}

if (1 && basename($_SERVER["PHP_SELF"]) == basename(__FILE__)) {
    $AID = 71;

    $AS = new ItemEdit($_CONF["umzugsantrag"], $connid, $user, $AID);
    $AS->loadDbdata();
    $AS->dbdataToInput();

    $aStatus = array(
            array("status"=> "genehmigt", "wert"=>"Ja"),
        
            array("status"=> "neu", "wert"=>""),
            array("status"=> "angeboten", "wert"=>""),
            array("status"=> "beantragt", "wert"=>""),
            array("status"=> "geprueft", "wert"=>""),
            array("status"=> "genehmigt", "wert"=>"Ja"),
            array("status"=> "genehmigt", "wert"=>"Nein"),
            array("status"=> "bestaetigt", "wert"=>"Ja"),
            array("status"=> "bestaetigt", "wert"=>"Init"),
            array("status"=> "zurueckgegeben", "wert"=>""),
            array("status"=> "abgeschlossen", "wert"=>"Ja"),
            array("status"=> "abgeschlossen", "wert"=>"Storniert")
    );

    echo "<pre>";
    $iNumStatus = count($aStatus);
    for ($i = 0; $i < $iNumStatus; $i++) {
            echo "#".__LINE__." umzugsantrag_mailinform($AID, ".$aStatus[$i]["status"].", ".$aStatus[$i]["wert"].")<br>\n";
            umzugsantrag_mailinform($AID,$aStatus[$i]["status"], $aStatus[$i]["wert"]);
            exit;
    }
    echo "</pre>";
}

