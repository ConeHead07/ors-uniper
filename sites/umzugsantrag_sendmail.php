<?php 

if (basename($_SERVER["PHP_SELF"]) == basename(__FILE__)) {
	require_once("../header.php");
	require_once($InclBaseDir."umzugsantrag.inc.php");
	require_once($InclBaseDir."umzugsmitarbeiter.inc.php");
	require_once($InclBaseDir."umzugsanlagen.inc.php");
}

require($MConf["AppRoot"]."sites".DS."umzugsantrag_datenblatt.php");
if (empty($_CONF["umzugsanlagen"])) require_once($InclBaseDir."umzugsanlagen.inc.php");

function get_umzugsblatt_verteiler($antragsOrt, $antragsGebaeude) {
        die('#'.__LINE__ . ' ' . __FUNCTION__ . '(' . print_r(func_get_args(),1) . ')');
	global $MConf;
	
	$Region = "";
	$verteilerFile = "";
	switch($antragsOrt) {
		case "Ratingen":
		$verteilerFile = "email_verteiler_umzugsblatt_Ratingen.txt";
		break;
		
		case "Essen":
		case "Dortmund":
		$verteilerFile = "email_verteiler_umzugsblatt_Essen_Dortmund.txt";
		break;
	}
	if (!$verteilerFile) {
		list($Region) = explode("_", $antragsGebaeude);
		
		switch($Region) {
			case "ZV":
			case "N":
			case "NO":
			case "NW":
			case "O":
			case "RM":
			case "S":
			case "SW":
			case "W":
			$verteilerFile = "email_verteiler_umzugsblatt_".$Region.".txt";
			break;
		}
	}
	
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
               .' FROM ' . $_TABLE['umzugsantrag'] . ' ' . PHP_EOL
               .' WHERE aid = ' . (int)$AID
                
        );
        if (!$row) return null;
        $gebaeudeId = $row['gebaeude'];
        $antrag_uid = $row['antragsteller_uid'];
//        die($db->lastQuery . PHP_EOL . '<br>' . print_r($row, 1));
        
        $users = array(
            'antragsteller' => $db->query_row(
                    'SELECT * FROM  mm_user ' . PHP_EOL
                   .' WHERE uid = ' . (int)$antrag_uid
            ),
            'regionalmanager' => $db->query_row(
                    'SELECT u.* FROM mm_stamm_gebaeude g ' . PHP_EOL
                   .' LEFT JOIN mm_user u ON (g.regionalmanager_uid = u.uid)' . PHP_EOL 
                   .' WHERE id = ' . (int)$gebaeudeId
            ),
            'standortmanager' => $db->query_row(
                    'SELECT u.* FROM mm_stamm_gebaeude g ' . PHP_EOL
                   .' LEFT JOIN mm_user u ON (g.standortmanager_uid = u.uid)' . PHP_EOL 
                   .' WHERE id = ' . (int)$gebaeudeId
            ),
            'objektleiter' => $db->query_row(
                    'SELECT u.* FROM mm_stamm_gebaeude g ' . PHP_EOL
                   .' LEFT JOIN mm_user u ON (g.objektleiter_uid = u.uid)' . PHP_EOL 
                   .' WHERE id = ' . (int)$gebaeudeId
            ),
            'mertenshenk' => $db->query_row(
                    'SELECT u.* FROM mm_stamm_gebaeude g ' . PHP_EOL
                   .' LEFT JOIN mm_user u ON (g.mertenshenk_uid = u.uid)' . PHP_EOL 
                   .' WHERE id = ' . (int)$gebaeudeId
            )
        );
        
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

function getRegionalmanager(){
    
}

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
	for($i = 0; $i < count($aATs); $i++) {
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
	
	$subject = "Umzug ID ".$AID." - Umzugsdatenblatt";
	$plaintext = "Guten Tag,\n\nanbei erhalten Sie das Umzugsdatenblatt f?r den Umzug mit der ID ".$AID.".\n\nMit freundlichen Gr??en\n\nIhr\n".$MConf["AppTitle"];
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
		$htmltext = "Guten Tag,<br>\n<br>\nanbei erhalten Sie das Umzugsdatenblatt f?r den Umzug mit der ID ".$AID.".<br>\n<br>\n";
		$htmltext.= "Dem Auftrag wurden ".count($aAtItems)." Dateianh&auml;nge zum Download beigef&uuml;gt:<br>\n";
		$htmltext.= "<ol>".$AtList."</ol>\n";
		$htmltext.= "<br>\n<br>\nMit freundlichen Gr&uuml;&szlig;en<br>\n<br>\nIhr<br>\n".$MConf["AppTitle"];
		$plaintext = "";
	}
	
	$specs="";
	
	for($i = 0; $i < count($aMailTo); $i++) {
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
        
	$aAdminMailTo[] = array("email"=>"bayerors@mertens.ag", "Name"=>"ORS", "Vorname"=>"");
	foreach($aAdminMailTo as $k => $v) 
            $aAdminMailTo[$k]['to'] = $aAdminMailTo[$k]['email'];
        
	return $aAdminMailTo;
}

function send_status_mail($aUserTo, $tplMail, $rplVars, $aAttachements = false) {
//	aUserTo[] = array("email"=>"", "Vorname"=>"", "Name"=>"")
	if (!isset($aUserTo[0]["email"]) 
            && !isset($aUserTo[0]["Vorname"]) 
            && !isset($aUserTo[0]["Name"])) {
            die("F?r die Statusmail konnten keine E-Mail-Empf?nger ermittelt werden!");
            // try { throw new Exception("aUserTo-Check: "); } catch(Exception $e) { die($e->getTraceAsString() . '<br>' . print_r($aUserTo,1)); }
            // die("#".__LINE__." Ung?ltige ArrayStruktur f?r aUserTo. Erwartet: [i][email], [i][Vorname], [i][Name] !<br />\nErhalten: ".print_r($aUserTo,1));
	}
	$lines = explode("\n", $tplMail);
	$tplSu = trim((strpos($lines[0], "Betreff=") === 0) ? substr($lines[0],8) : $lines[0]);
	$tplBody = trim(implode("\n",array_slice($lines, 1)));
	
	$success = true;
	
	for ($i = 0; $i < count($aUserTo); $i++) {
            $to = $aUserTo[$i]["email"];
            $body = $tplBody;
            $su = $tplSu;
            if (!isset($aUserTo[$i]["vorname"]) && !isset($aUserTo[$i]["Vorname"])) {
                die('#'.__LINE__ . ' !isset $aUserTo['.$i.']["vorname"]<br>aUserTo :' . PHP_EOL . print_r($aUserTo,1) );
            }
//          echo '#'.__LINE__ . ' $aUserTo[$i] ' . print_r($aUserTo[$i],1);
            $_vorname  = (isset($aUserTo[$i]["vorname"])  ? $aUserTo[$i]["vorname"]  : (isset($aUserTo[$i]["Vorname"]) ? $aUserTo[$i]["Vorname"] : die(print_r($aUserTo[$i],1))) );
            $_nachname = (isset($aUserTo[$i]["nachname"]) ? $aUserTo[$i]["nachname"] : (isset($aUserTo[$i]["Name"]) ? $aUserTo[$i]["Name"] : die(print_r($aUserTo[$i],1))) );

            $body = str_replace("{Vorname}", $_vorname, $body);
            $body = str_replace("{Name}", $_nachname, $body);
			if ($rplVars['umzugszeit']) {
				$body = str_replace('{ausgefuehrtam}', date('d.m.Y H:i', strtotime($rplVars['umzugstermin'] . ' ' . $rplVars['umzugszeit']) ), $body);
			} else {
				$body = str_replace('{ausgefuehrtam}', date('d.m.Y', strtotime($rplVars['umzugstermin'] ) ), $body);				
			}
			
            $body = str_replace("{Vorname}", $_vorname, $body);
            $body = str_replace("{Name}", $_nachname, $body);
            foreach($rplVars as $k => $v) $body = str_replace("{".$k."}", (is_scalar($v) ? $v : ''), $body);
            foreach($rplVars as $k => $v) $su = str_replace("{".$k."}", (is_scalar($v) ? $v : ''), $su);

            $hd = "Reply-To:".(!empty($rplVars["Reply-To"])?$rplVars["Reply-To"]:"bayerors@mertens.ag")."";

            if (!fbmail($to, $su, $body, $hd)) $success = false;
	}
	return $success;
}

function umzugsantrag_mailinform_dussmann($AID, $status="neu", $value) {
//        throw new Exception('#'.__LINE__ . ' ' . __FUNCTION__ . '(' . print_r(func_get_args(),1) . ')');
	global $db;
	global $error;
	global $msg;
	global $LoadScript;
	global $_CONF;
	global $MConf;
	global $connid;
	global $user;
    
	$users = get_usersByAid($AID);
        
	$TextBaseDir = $MConf["AppRoot"].$MConf["Texte_Dir"];
        
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
        
	$aPropertyMailTo = array(
            $users['antragsteller'],
//            $users['regionalmanager'],
//            $users['standortmanager'],
//            $users['objektleiter'],
	);
	
    // Leider noch doppelte Abfrage aus evtll. Kompatibilit?tsgr?nden, eigentlich unn?tig, Korrektur steht noch an !!!
	if ($users['antragsteller']['gruppe'] === 'kunde_report') {
		$aPropertyMailTo[] = $users['antragsteller'];
	} else {
		$aPropertyMailTo[] = $users['standortmanager'];
	}
        
	$aAdminMailTo[] = array("to"=>"bayerors@mertens.ag", "email"=>"bayerors@mertens.ag", "Name"=>"ORS", "Vorname"=>"Order Request System");
	if ($users['mertenshenk'] && $users['mertenshenk']['uid']) {
		$aAdminMailTo[] = $users['mertenshenk'];
	}
	$dbg = 0;	
	switch($status) {
		case "neu":
		case "beantragt":
			$tplFile = $TextBaseDir."statusmail_umzug_neu.txt";
			if ($dbg) echo '#'. __LINE__ . ' FILE: ' . $tplFile . '; EXISTS: ' . (file_exists($tplFile) ?'Ja':'Nein') . PHP_EOL;
			$tplMail = file_get_contents($tplFile);
			$rplVars["StatusLink"] = $MConf["WebRoot"]."?s=pantrag&id=".$AID;
			send_status_mail($aPropertyMailTo, $tplMail, $rplVars);
            
			$tplFile = $TextBaseDir."statusmail_umzug_zurpruefung.txt";
			if ($dbg) echo '#'. __LINE__ . ' FILE: ' . $tplFile . '; EXISTS: ' . (file_exists($tplFile) ?'Ja':'Nein') . PHP_EOL;
			$tplMail = file_get_contents($tplFile);
			$rplVars["StatusLink"] = $MConf["WebRoot"]."?s=aantrag&id=".$AID;
			return send_status_mail($aAdminMailTo, $tplMail, $rplVars);
            
		case "erneutpruefen":
			$tplFile = $TextBaseDir."statusmail_umzug_zurerneutenpruefung.txt";
			if ($dbg) echo '#'. __LINE__ . ' FILE: ' . $tplFile . '; EXISTS: ' . (file_exists($tplFile) ?'Ja':'Nein') . PHP_EOL;
			$tplMail = file_get_contents($tplFile);
			$rplVars["StatusLink"] = $MConf["WebRoot"]."?s=aantrag&id=".$AID;
			return send_status_mail($aAdminMailTo, $tplMail, $rplVars);
            
		case 'angeboten':
			$tplFile = $TextBaseDir."statusmail_umzug_zurgenehmigung.txt";
			if ($dbg) echo '#'. __LINE__ . ' FILE: ' . $tplFile . '; EXISTS: ' . (file_exists($tplFile) ?'Ja':'Nein') . PHP_EOL;
			$tplMail = file_get_contents($tplFile);
			$rplVars["StatusLink"] = $MConf["WebRoot"]."?s=pantrag&id=".$AID;
			return send_status_mail($aPropertyMailTo, $tplMail, $rplVars);
		
		case "geprueft":
			$tplFile = $TextBaseDir."statusmail_umzug_zurgenehmigung.txt";
			if ($dbg) echo '#'. __LINE__ . ' FILE: ' . $tplFile . '; EXISTS: ' . (file_exists($tplFile) ?'Ja':'Nein') . PHP_EOL;
			$tplMail = file_get_contents($tplFile);
			$rplVars["StatusLink"] = $MConf["WebRoot"]."?s=pantrag&id=".$AID;
			return send_status_mail($aPropertyMailTo, $tplMail, $rplVars);
		
		case "genehmigt":
			$rplVars["StatusLink"] = $MConf["WebRoot"]."?s=aantrag&id=".$AID;
			switch($value) {
				case "Nein":
			$tplFile = $TextBaseDir."statusmail_umzug_kabgelehnt.txt";
			if ($dbg) echo '#'. __LINE__ . ' FILE: ' . $tplFile . '; EXISTS: ' . (file_exists($tplFile) ?'Ja':'Nein') . PHP_EOL;
			$tplMail = file_get_contents($tplFile);
				$rplVars["StatusLink"] = $MConf["WebRoot"]."?s=kantrag&id=".$AID;
				$sentToUser = send_status_mail($userMailTo, $tplMail, $rplVars);
				
				$tplFile = $TextBaseDir."statusmail_umzug_abgelehnt.txt";
				if ($dbg) echo '#'. __LINE__ . ' FILE: ' . $tplFile . '; EXISTS: ' . (file_exists($tplFile) ?'Ja':'Nein') . PHP_EOL;
				$tplMail = file_get_contents($tplFile);
				$rplVars["StatusLink"] = $MConf["WebRoot"]."?s=aantrag&id=".$AID;
				return send_status_mail($aAdminMailTo, $tplMail, $rplVars);
				
				case "Ja":
				default:
				$tplFile = $TextBaseDir."statusmail_umzug_aktiv.txt";
				if ($dbg) echo '#'. __LINE__ . ' FILE: ' . $tplFile . '; EXISTS: ' . (file_exists($tplFile) ?'Ja':'Nein') . PHP_EOL;
				$tplMail = file_get_contents($tplFile);
				if ($dbg) echo '#' . __LINE__ . ' tplMail: ' . $tplMail . PHP_EOL;
				send_status_mail($aPropertyMailTo, $tplMail, $rplVars);
            
				$tplFile = $TextBaseDir."statusmail_umzug_genehmigt.txt";
				if ($dbg) echo '#'. __LINE__ . ' FILE: ' . $tplFile . '; EXISTS: ' . (file_exists($tplFile) ?'Ja':'Nein') . PHP_EOL;
				$tplMail = file_get_contents($tplFile);
				if ($dbg) echo '#' . __LINE__ . ' tplMail: ' . $tplMail . PHP_EOL;
				return send_status_mail($aAdminMailTo, $tplMail, $rplVars);
			}
		break;
		
		case "bestaetigt": // 
                    if ($value == "Ja") {
						$tplFile = $TextBaseDir."statusmail_umzug_bestaetigt.txt";
						if ($dbg) echo '#'. __LINE__ . ' FILE: ' . $tplFile . '; EXISTS: ' . (file_exists($tplFile) ?'Ja':'Nein') . PHP_EOL;
						$tplMail = file_get_contents($tplFile);
                        // Benachrichtige UmzugsTeam ?ber best?tigten Umzugsauftrag
//			       send_umzugsblatt($AID, $AS->arrInput["ort"], $AS->arrInput["gebaeude"], $AS->arrInput);
                    } else {
						$tplFile = $TextBaseDir."statusmail_umzug_aufhebung.txt";
						if ($dbg) echo '#'. __LINE__ . ' FILE: ' . $tplFile . '; EXISTS: ' . (file_exists($tplFile) ?'Ja':'Nein') . PHP_EOL;
						$tplMail = file_get_contents($tplFile);
                        // Informiere Umzugsteam ?ber (bis auf weiteres) aufgehobenen Umzugsauftrag
//			       $rplVars["StatusLink"] = $MConf["WebRoot"]."?s=aantrag&id=".$AID;
//			       send_status_mail($aAdminMailTo, $tplMail, $rplVars);
                    }

                    $rplVars["StatusLink"] = $MConf["WebRoot"]."?s=kantrag&id=".$AID;
                    $sentToUser = send_status_mail($userMailTo, $tplMail, $rplVars);

                    $rplVars["StatusLink"] = $MConf["WebRoot"]."?s=pantrag&id=".$AID;
                    //die('#'.__LINE__ . ' ' . __LINE__ . ' ' . print_r($aPropertyMailTo,1));
                    return (send_status_mail($aPropertyMailTo, $tplMail, $rplVars) && $sentToUser);
		
		case "zurueckgegeben":
			$tplFile = $TextBaseDir."statusmail_umzug_zurueckgegeben.txt";
			if ($dbg) echo '#'. __LINE__ . ' FILE: ' . $tplFile . '; EXISTS: ' . (file_exists($tplFile) ?'Ja':'Nein') . PHP_EOL;
			$tplMail = file_get_contents($tplFile);
			
			$rplVars["StatusLink"] = $MConf["WebRoot"]."?s=kantrag&id=".$AID;
			return send_status_mail($userMailTo, $tplMail, $rplVars);
		
		case "abgeschlossen":
			switch($value) {
				case "Ja":
				$tplFile = $TextBaseDir."statusmail_umzug_durchgefuehrt.txt";
				if ($dbg) echo '#'. __LINE__ . ' FILE: ' . $tplFile . '; EXISTS: ' . (file_exists($tplFile) ?'Ja':'Nein') . PHP_EOL;
				$tplMail = file_get_contents($tplFile);
				$rplVars["StatusLink"] = $MConf["WebRoot"]."?s=kantrag&id=".$AID;
				send_status_mail($userMailTo, $tplMail, $rplVars);

				$rplVars["StatusLink"] = $MConf["WebRoot"]."?s=pantrag&id=".$AID;
				return send_status_mail($aPropertyMailTo, $tplMail, $rplVars);

				case "Storniert":
				$tplFile = $TextBaseDir."statusmail_umzug_storniert.txt";
				if ($dbg) echo '#'. __LINE__ . ' FILE: ' . $tplFile . '; EXISTS: ' . (file_exists($tplFile) ?'Ja':'Nein') . PHP_EOL;
				$tplMail = file_get_contents($tplFile);
				$rplVars["StatusLink"] = $MConf["WebRoot"]."?s=kantrag&id=".$AID;
				send_status_mail($userMailTo, $tplMail, $rplVars);

				$rplVars["StatusLink"] = $MConf["WebRoot"]."?s=pantrag&id=".$AID;
				return send_status_mail($aPropertyMailTo, $tplMail, $rplVars);
			}
		break;
		
		default:
		return false;
	}
}

function umzugsantrag_mailinform($AID, $status="neu", $value) {
        return umzugsantrag_mailinform_dussmann($AID, $status, $value);
        
        die('#'.__LINE__ . ' ' . __FUNCTION__ . '(' . print_r(func_get_args(),1) . ')');
	global $db;
	global $error;
	global $msg;
	global $LoadScript;
	global $_CONF;
	global $MConf;
	global $connid;
	global $user;
	
        
        $users = get_usersByAid($AID);
	$TextBaseDir = $MConf["AppRoot"].$MConf["Texte_Dir"];
	
	if (!$AID) {
		return false;
	}
	$AS = new ItemEdit($_CONF["umzugsantrag"], $connid, $user, $AID);
	$MAConf = $_CONF["umzugsmitarbeiter"];
	
	$AS->loadDbdata();
	$AS->dbdataToInput();
	
	$sql = "SELECT * FROM `".$MAConf["Table"]."` WHERE `aid` = \"".$db->escape($AID)."\"";
	$rows = $db->query_rows($sql);
	
	$rplVars = array(
		"AID" => $AID,
		"StatusLink" => $MConf["WebRoot"]."?s=aantrag&id=".$AID,
		"HomepageTitle" => $MConf["AppTitle"]
	);
	
	$userMailTo[] = array("email"=>$AS->arrInput["email"], "Name"=>$AS->arrInput["name"], "Vorname"=>$AS->arrInput["vorname"]);
	
	// Alte Abfrage des Verteiler an der Basisdaten des Umzugsantrags
	$aPropertyMailTo = get_standort_property_mail($AS->arrInput["ort"], $AS->arrInput["gebaeude"]);
        $aPropertyMailTo = array(
            $users['antragsteller'],
//            $users['regionalmanager'],
//            $users['standortmanager'],
//            $users['objektleiter'],
        );
        
	$aAdminMailTo = get_standort_admin_mail($AS->arrInput["ort"], $AS->arrInput["gebaeude"]);
	
	// Neue Abfrage des Verteilers unter Ber?cksichtigung aller enthaltenen umzuziehenden MA-Daten
	// Leider noch doppelte Abfrage aus evtll. Kompatibilit?tsgr?nden, eigentlich unn?tig, Korrektur steht noch an !!!
	$adminVerteiler = get_umzugsverteilerById($AID);
	$propertyVerteiler = get_propertyverteilerById($AID);
	
	$aAdminVerteilerTo = array();
	for($i = 0; $i < count($adminVerteiler); $i++) {
		if (!trim($adminVerteiler[$i])) continue;
		$name = str_replace(".", " ", array_shift(explode("@", $adminVerteiler[$i])));
		$aAdminVerteilerTo[] = array("email"=>$adminVerteiler[$i], "Name"=>$name, "Vorname"=>"");
	}
	
	switch($status) {
		case "neu":
		case "beantragt":
			$tplMail = file_get_contents($TextBaseDir."statusmail_umzug_zurpruefung.txt");
			$rplVars["StatusLink"] = $MConf["WebRoot"]."?s=aantrag&id=".$AID;
			return send_status_mail($aAdminMailTo, $tplMail, $rplVars);
		break;
		
		case "geprueft":
			$tplMail = file_get_contents($TextBaseDir."statusmail_umzug_zurgenehmigung.txt");
			$rplVars["StatusLink"] = $MConf["WebRoot"]."?s=pantrag&id=".$AID;
			if ($propertyVerteiler && @isset($propertyVerteiler[0]["email"])) $aPropertyMailTo = $propertyVerteiler;
			return send_status_mail($aPropertyMailTo, $tplMail, $rplVars);
		break;
		
		case "genehmigt":
			$rplVars["StatusLink"] = $MConf["WebRoot"]."?s=aantrag&id=".$AID;
			switch($value) {
				case "Nein":
				$tplMail = file_get_contents($TextBaseDir."statusmail_umzug_kabgelehnt.txt");
				$rplVars["StatusLink"] = $MConf["WebRoot"]."?s=kantrag&id=".$AID;
				$sentToUser = send_status_mail($userMailTo, $tplMail, $rplVars);
				
				$tplMail = file_get_contents($TextBaseDir."statusmail_umzug_abgelehnt.txt");
				$rplVars["StatusLink"] = $MConf["WebRoot"]."?s=aantrag&id=".$AID;
				return send_status_mail($aAdminMailTo, $tplMail, $rplVars);
				break;
				
				case "Ja":
				default:
				$tplMail = file_get_contents($TextBaseDir."statusmail_umzug_genehmigt.txt");
				return send_status_mail($aAdminMailTo, $tplMail, $rplVars);
				break;
			}
		break;
		
		case "bestaetigt":
//			echo "#".__LINE__." bestaetigt ".$value."; aAdminVerteilerTo:".print_r($aAdminVerteilerTo,1)."<br />\n";
//			echo "#".__LINE__." bestaetigt ".$value."; propertyVerteiler:".print_r($propertyVerteiler,1)."<br />\n";
//			echo "#".__LINE__." bestaetigt ".$value."; userMailTo:".print_r($userMailTo,1)."<br />\n";
//			echo "#".__LINE__." bestaetigt ".$value."; aPropertyMailTo:".print_r($aPropertyMailTo,1)."<br />\n";
//			//echo "#".__LINE__." "; print_r($AS->arrInput)."<br>\n";
//			echo "#".__LINE__." bestaetigt Ja<br />\n";
			if ($value == "Ja") {
				$tplMail = file_get_contents($TextBaseDir."statusmail_umzug_bestaetigt.txt");
				// Benachrichtige UmzugsTeam ?ber best?tigten Umzugsauftrag
				send_umzugsblatt($AID, $AS->arrInput["ort"], $AS->arrInput["gebaeude"], $AS->arrInput);
			} else {
				$tplMail = file_get_contents($TextBaseDir."statusmail_umzug_aufhebung.txt");
				// Informiere Umzugsteam ?ber (bis auf weiteres) aufgehobenen Umzugsauftrag
				$rplVars["StatusLink"] = $MConf["WebRoot"]."?s=aantrag&id=".$AID;
				//echo "#".__LINE__." bestaetigt ".$value."; aAdminVerteilerTo:".print_r($aAdminVerteilerTo,1)."<br />\n";
				send_status_mail($aAdminVerteilerTo, $tplMail, $rplVars);
			}
			
			$rplVars["StatusLink"] = $MConf["WebRoot"]."?s=kantrag&id=".$AID;
			$sentToUser = send_status_mail($userMailTo, $tplMail, $rplVars);
			
			$rplVars["StatusLink"] = $MConf["WebRoot"]."?s=pantrag&id=".$AID;
			return (send_status_mail($aPropertyMailTo, $tplMail, $rplVars) && $sentToUser);
		break;
		
		case "zurueckgegeben":
			$tplMail = file_get_contents($TextBaseDir."statusmail_umzug_zurueckgegeben.txt");
			$rplVars["StatusLink"] = $MConf["WebRoot"]."?s=kantrag&id=".$AID;
			return send_status_mail($userMailTo, $tplMail, $rplVars);
		break;
		
		case "abgeschlossen":
			switch($value) {
				case "Ja":
				$tplMail = file_get_contents($TextBaseDir."statusmail_umzug_durchgefuehrt.txt");
				$rplVars["StatusLink"] = $MConf["WebRoot"]."?s=kantrag&id=".$AID;
				return send_status_mail($userMailTo, $tplMail, $rplVars);
				break;
				
				case "Storniert":
				//echo "#".__LINE__." ".basename(__FILE__)."<br>\n";
				if ($AS->arrInput["bestaetigt"]=="Ja") {
					$verteiler = get_umzugsverteilerById($AID);
					$aMailTo = (is_array($verteiler)) ? $verteiler : explode("\n", $verteiler);
					for($i = 0; $i < count($aMailTo); $i++) {
						$name = str_replace(".", " ", array_shift(explode("@", $aMailTo[$i])));
						$userMailTo[] = array("email"=>$aMailTo[$i], "Name"=>$name, "Vorname"=>"");
					}
				}
				$tplMail = file_get_contents($TextBaseDir."statusmail_umzug_storniert.txt");
				$rplVars["StatusLink"] = $MConf["WebRoot"]."?s=pantrag&id=".$AID;
				//echo "#".__LINE__." ".basename(__FILE__)." ".$AS->arrInput["ort"].", ".$AS->arrInput["gebaeude"].": ".print_r($userMailTo,1)."<br>\n";
				return send_status_mail($userMailTo, $tplMail, $rplVars);
				break;
			}
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
    for ($i = 0; $i < count($aStatus); $i++) {
            echo "#".__LINE__." umzugsantrag_mailinform($AID, ".$aStatus[$i]["status"].", ".$aStatus[$i]["wert"].")<br>\n";
            umzugsantrag_mailinform($AID,$aStatus[$i]["status"], $aStatus[$i]["wert"]);
            exit;
    }
    echo "</pre>";
}

