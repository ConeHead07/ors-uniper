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
		echo "#".__LINE__." antragsOrt:$antragsOrt; antragsGebaeude:$antragsGebaeude; Region:$Region; verteilerFile:$verteilerFile<br>\n";
		return "";
	}
	
	$TextBaseDir = $MConf["AppRoot"].$MConf["Texte_Dir"];
	$verteiler = file_get_contents($TextBaseDir.$verteilerFile);
	$verteiler = strtr($verteiler, array("\r\n"=>"\n","\r"=>"\n"," "=>"\n",","=>"\n", ";"=>"\n"));
	while(strpos($verteiler, "\n\n")!==false) $verteiler = str_replace("\n\n", "\n", $verteiler);
	//echo "#".__LINE__." <br>\nOrt:$antragsOrt; <br>\nGeb:$antragsGebaeude; <br>\nReg:$Region; <br>\nFil:$verteilerFile<br>\nVer:<br>\n".$verteiler."<br>\n<br>\n";
	return $verteiler;
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
			$tmpVerteiler = explode("\n", get_umzugsblatt_verteiler($ort, $gebaeude));
			$tmpVerteiler = get_standort_property_mail($ort, $gebaeude);
			//echo "#".__LINE__." ".basename(__FILE__)." tmpVerteiler:".print_r($tmpVerteiler,1)."<br>\n";
			
			if (is_array($tmpVerteiler)) foreach($tmpVerteiler as $v_user) {
				if (!$addedEmails[$v_user["to"]]) {
					$verteiler[]=$v_user;
					$addedEmails[$v_user["to"]] = 1;
				}
			}
		}
	}
	//echo "#".__LINE__." ".basename(__FILE__)." verteiler:".print_r($verteiler,1)."<br>\n";
	return $verteiler;
}

function send_umzugsblatt($AID, $antragsOrt, $antragsGebaeude, $aData) {
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
	
	for($i = 0; $i < count($aMailTo); $i++) {
		if (!trim($aMailTo[$i])) continue;
		$name = str_replace(".", " ", array_shift(explode("@", $aMailTo[$i])));
		$mailTo = array(array("email"=>$aMailTo[$i], "Name"=>$name, "Vorname"=>""));
		//echo "<pre>#".__LINE__." send_umzugsblatt() mailTo: ".print_r($mailTo,1)."</pre>\n";
		$sendmail=send_multipart_mail($mailTo,$subject,$htmltext,$plaintext,$attachement,$header,$specs);
	}
	
	if (!send_multipart_mail("frank.barthold@googlemail.com",$subject,$htmltext,$plaintext,$attachement,$header,$specs)) $success = false;
	if (!send_multipart_mail("ors@mertens-henk.de",$subject,$htmltext,$plaintext,$attachement,$header,$specs)) $success = false;
}


function get_standort_property_mail($antragsOrt, $antragsGebaeude) {
	
	if ($antragsOrt == "Ratingen" || strpos($antragsGebaeude,"_KAI_")) {
		$aPropertyMailTo[] = array("to"=>"Arno.Pieper@demo.com", "Name"=>"Pieper", "Vorname"=>"Arno");
		$aPropertyMailTo[] = array("to"=>"Herbert.Kranepoth@demo.com", "Name"=>"Kranepoth", "Vorname"=>"Herbert");
		
	} elseif ($antragsOrt == "Essen" || $antragsOrt == "Dortmund") {
		$aPropertyMailTo[] = array("to"=>"Stefan.De-Hoogd@demo.com", "Name"=>"De Hoogd", "Vorname"=>"Stefan");
		$aPropertyMailTo[] = array("to"=>"Benjamin.Ferfers01@demo.com", "Name"=>"Ferfers", "Vorname"=>"Benjamin");
		$aPropertyMailTo[] = array("to"=>"Kirsten.Reinke@demo.com", "Name"=>"Kirsten", "Vorname"=>"Reinke");
			
	} elseif ($antragsOrt == "Dresden" || $antragsOrt == "Radebeul" || $antragsOrt == "Bautzen") {
		$aPropertyMailTo[] = array("to"=>"Gabriele.Leppchen@demo.com", "Name"=>"Leppchen", "Vorname"=>"Gabriele");
		
	} elseif ($antragsOrt == "Hannover") {
		$aPropertyMailTo[] = array("to"=>"Sabine.Geipel@demo.com", "Name"=>"Geipel", "Vorname"=>"Sabine");
		
	} elseif ($antragsOrt == "Eschborn" || $antragsOrt == "Sulzbach") {
		$aPropertyMailTo[] = array("to"=>"Walburga.Becker@demo.com", "Name"=>"Becker", "Vorname"=>"Walburga");
		$aPropertyMailTo[] = array("to"=>"Carola.Otterbein@demo.com", "Name"=>"Otterbein", "Vorname"=>"Carola");
		$aPropertyMailTo[] = array("to"=>"Bernd.Groeber@demo.com", "Name"=>"Groeber", "Vorname"=>"Bernd");
		$aPropertyMailTo[] = array("to"=>"Sigrid.Hilbig@demo.com", "Name"=>"Hilbig", "Vorname"=>"Sigrid");
		
	} elseif ($antragsOrt == "Hamburg") {
		$aPropertyMailTo[] = array("to"=>"Barbara.Schoening@demo.com", "Name"=>"Sch�ning", "Vorname"=>"Barbara");
		
	} elseif ($antragsOrt == "Berlin") {
		$aPropertyMailTo[] = array("to"=>"Alexander.Voigt@demo.com", "Name"=>"Voigt", "Vorname"=>"Alexander");
		
	} elseif ($antragsOrt == "Stuttgart" || $antragsOrt == "Mannheim" || $antragsOrt == "Freiburg" || $antragsOrt == "Saarbrücken") {
		$aPropertyMailTo[] = array("to"=>"Katja.Rabe@demo.com", "Name"=>"Rabe", "Vorname"=>"Katja");
		$aPropertyMailTo[] = array("to"=>"Veronica.Strobel@demo.com", "Name"=>"Strobel", "Vorname"=>"Veronica");
		
	} elseif ($antragsOrt == "Nürnberg" || $antragsOrt == "München") {
		$aPropertyMailTo[] = array("to"=>"Jutta.Jaquet@demo.com", "Name"=>"Jaquet", "Vorname"=>"Jutta");
		$aPropertyMailTo[] = array("to"=>"Helmut.Brandstetter@demo.com", "Name"=>"Brandstetter", "Vorname"=>"Helmut");
		
	} else { //if (substr($antragsGebaeude, 0, 3) == "ZV_") {
		// ZV + Kassel
		$aPropertyMailTo[] = array("to"=>"Michael.Effertz@demo.com", "Name"=>"Effertz", "Vorname"=>"Michael");
		$aPropertyMailTo[] = array("to"=>"Hans-Martin.Kutscher@demo.com", "Name"=>"Kutscher", "Vorname"=>"Hans-Martin");
	}
	return $aPropertyMailTo;
}

function get_standort_admin_mail($antragsOrt, $antragsGebaeude) {
	
	$aAdminMailTo[] = array("to"=>"ors@mertens-henk.de", "Name"=>"Movemanagement", "Vorname"=>"");
	switch($antragsOrt) {
		case "Hannover":
		case "Hamburg":
		case "Bremen":
		case "Berlin":
		case "Dresden":
		case "Radebeuel":
		case "Bautzen":
		case "Kassel":
		$aAdminMailTo[] = array("to"=>"u.kelber@mertens.ag", "Name"=>"Kelber", "Vorname"=>"Ulf");
		break;
		
		case "München":
		case "Nürnberg":
		case "Stuttgart":
		case "Mannheim":
		case "Sulzbach":
		case "Eschborn":
		case "Freiburg":
		case "Saarbrücken":
		$aAdminMailTo[] = array("to"=>"j.ickstadt@mertens.ag", "Name"=>"Ickstadt", "Vorname"=>"Jens");
		$aAdminMailTo[] = array("to"=>"m.schiller@mertens.ag", "Name"=>"Schiller", "Vorname"=>"Markus");
		break;
		
		case "Düsseldorf":
		case "Ratingen":
		case "Essen":
		case "Dortmund":
		default:
		//$mailTo = "ors@mertens-henk.de";
	}
	return $aAdminMailTo;
}

function send_status_mail($aUserTo, $tplMail, $rplVars, $aAttachements = false) {
	
	$lines = explode("\n", $tplMail);
	$tplSu = trim((strpos($lines[0], "Betreff=") === 0) ? substr($lines[0],8) : $lines[0]);
	$tplBody = trim(implode("\n",array_slice($lines, 1)));
	
	$success = true;
	
	for ($i = 0; $i < count($aUserTo); $i++) {
		$to = $aUserTo[$i]["to"];
		$body = $tplBody;
		$su = $tplSu;
		$body = str_replace("{Vorname}", $aUserTo[$i]["Vorname"], $body);
		$body = str_replace("{Name}", $aUserTo[$i]["Name"], $body);
		$su = str_replace("{Vorname}", $aUserTo[$i]["Vorname"], $su);
		$su = str_replace("{Name}", $aUserTo[$i]["Name"], $su);
		foreach($rplVars as $k => $v) $body = str_replace("{".$k."}", $v, $body);
		foreach($rplVars as $k => $v) $su = str_replace("{".$k."}", $v, $su);
		
		$hd = "Reply-To:".(!empty($rplVars["Reply-To"])?$rplVars["Reply-To"]:"service-uniper@mertens.ag")."\n";
		$hd.= "BCC: frank.barthold@googlemail.com";
		
		if (!fbmail($to, $su, $body, $hd)) $success = false;
		//if (!fbmail("frank.barthold@googlemail.com", $su, $body."\nTo: ".$to, $hd)) $success = false;
		//if (!fbmail("ors@mertens-henk.de", $su, $body."\n".$to, $hd)) $success = false;
		
		//if (basename($_SERVER["PHP_SELF"]) == basename(__FILE__))
			//echo "#".__LINE__." fbmail(\"frank.barthold@googlemail.com\", $su, $body\nTo:.$to, $hd);\n\n";
	}
	return $success;
}

function umzugsantrag_mailinform($AID, $status="neu", $value) {
	global $db;
	global $error;
	global $msg;
	global $LoadScript;
	global $_CONF;
	global $MConf;
	global $connid;
	global $user;
	
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
		"StatusLink" => "",
		"HomepageTitle" => $MConf["AppTitle"]
	);
	
	$userMailTo[] = array("to"=>$AS->arrInput["email"], "Name"=>$AS->arrInput["name"], "Vorname"=>$AS->arrInput["vorname"]);
	$aPropertyMailTo = get_standort_property_mail($AS->arrInput["ort"], $AS->arrInput["gebaeude"]);
	$aAdminMailTo = get_standort_admin_mail($AS->arrInput["ort"], $AS->arrInput["gebaeude"]);
	
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
			return send_status_mail($aPropertyMailTo, $tplMail, $rplVars);
		break;
		
		case "genehmigt":
			$rplVars["StatusLink"] = $MConf["WebRoot"]."?s=aantrag&id=".$AID;
			switch($value) {
				case "Nein":
				$tplMail = file_get_contents($TextBaseDir."statusmail_umzug_abgelehnt.txt");
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
			//echo "#".__LINE__." "; print_r($AS->arrInput)."<br>\n";
			send_umzugsblatt($AID, $AS->arrInput["ort"], $AS->arrInput["gebaeude"], $AS->arrInput);
			
			$tplMail = file_get_contents($TextBaseDir."statusmail_umzug_bestaetigt.txt");
			$rplVars["StatusLink"] = $MConf["WebRoot"]."?s=kantrag&id=".$AID;
			$sentToUser = send_status_mail($userMailTo, $tplMail, $rplVars);
			
			$tplMail = file_get_contents($TextBaseDir."statusmail_umzug_bestaetigt.txt");
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
					$aMailTo = explode("\n", $verteiler);
					for($i = 0; $i < count($aMailTo); $i++) {
						$name = str_replace(".", " ", array_shift(explode("@", $aMailTo[$i])));
						$userMailTo[] = array("to"=>$aMailTo[$i], "Name"=>$name, "Vorname"=>"");
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
	$AID = 93;
	
	$verteiler = get_umzugsverteilerById($AID);
	//echo "<pre>#".__LINE__." verteiler: ".print_r($verteiler,1)."</pre>\n";
	
	$AS = new ItemEdit($_CONF["umzugsantrag"], $connid, $user, $AID);
	$AS->loadDbdata();
	$AS->dbdataToInput();
	
	send_umzugsblatt($AID, "Ratingen", "", $AS->arrInput);
	
	$aStatus = array(
		array("status"=> "neu", "wert"=>""),
		array("status"=> "beantragt", "wert"=>""),
		array("status"=> "geprueft", "wert"=>""),
		array("status"=> "genehmigt", "wert"=>"Ja"),
		array("status"=> "genehmigt", "wert"=>"Nein"),
		array("status"=> "bestaetigt", "wert"=>""),
		array("status"=> "zurueckgegeben", "wert"=>""),
		array("status"=> "abgeschlossen", "wert"=>"Ja"),
		array("status"=> "abgeschlossen", "wert"=>"Storniert")
	);
	
	echo "<pre>";
	for ($i = 0; $i < count($aStatus); $i++) {
		umzugsantrag_mailinform($AID,$aStatus[$i]["status"], $aStatus[$i]["wert"]);
		echo "#".__LINE__." umzugsantrag_mailinform($AID, ".$aStatus[$i]["status"].", ".$aStatus[$i]["wert"].")<br>\n";
	}
	echo "</pre>";
}
?>
