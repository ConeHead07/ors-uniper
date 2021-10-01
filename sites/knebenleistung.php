<?php 

$Tpl = new myTplEngine();
require_once($InclBaseDir."nebenleistungen.inc.php");
require_once($InclBaseDir."user.inc.php");
require_once($InclBaseDir."..".DS."sites/umzugsantrag_sendmail.php");
$NLConf = $_CONF["nebenleistungen"];
$USERConf = $_CONF["user"];
$NID = getRequest("id",'');
$NLInput = getRequest("NL");
$nebenleistung_form = "knebenleistung_eingabe.html";

if (empty($NID)) $NID = (!empty($_POST["NL"]["id"]) ? $_POST["NL"]["id"] : (!empty($_GET["NL"]["id"]) ? $_GET["NL"]["id"] : ''));

$NL = new ItemEdit($NLConf, $connid, $user, $NID);

$save_success = false;

if ($NLInput) {
	if (!$NID) {
		$NL->loadInput($NLInput, true);
		
		switch(date("w")) {
			case 0: $vorlaufTage = 3; break;
			case 1:case 2:case 3: $vorlaufTage = 2; break;
			case 4:case 5:case 6: $vorlaufTage = 4; break;
			default:$vorlaufTage=2;
		}
		if (empty($NL->arrInput["datum"]) || date("Y-m-d", dateToTime($NL->arrInput["datum"])) < date("Y-m-d", time()+($vorlaufTage*24*60*60))) {
			$NL->Error.= "Fehlender oder unzul&auml;ssiger Terminwunsch '".$NL->arrInput["datum"]."'. Nebenleistungen m&uuml;ssen mind. zwei Werktage im Voraus beantragt werden!<br>\n";
		}
		if (!$NL->Error) {
			$NL->save();
			if (!$NL->Error) $NID = $NL->id;
			echo $db->error()."<br>\n";
			
			$NL->loadDbdata();
			$NL->dbdataToInput();
			$sql = "UPDATE `".$USERConf["Table"]."` SET `fon` = \"".$db->escape($NL->arrInput["fon"])."\", `standort`=\"".$db->escape($NL->arrInput["standort"])."\", `gebaeude` = \"".$db->escape($NL->arrInput["gebaeude"])."\" \n";
			$sql.= "\n WHERE uid = \"".$db->escape($NL->arrInput["createduid"])."\"";
			$db->query($sql);
			
			$aNlVerteiler = get_standort_admin_mail($NL->arrInput["standort"], $NL->arrInput["gebaeude"]);
			$aNlVerteiler[] = array("to"=>"Frank.Barthold@gmail.com", "Name"=>"Barthold", "Vorname"=>"Frank");
			//echo '#' . __LINE__ . ' aNlVerteiler: ' . print_r($aNlVerteiler,1) . '<br/>' . PHP_EOL;
			if ($aNlVerteiler) {
				
				$hd = "Reply-To:".(!empty($rplVars["Reply-To"])?$rplVars["Reply-To"]:"service-uniper@mertens.ag")."";
				$suTpl = "Es wurde eine neue Nebenleistung beauftrag. ID #".$NID;
				$nlStatusTxtTpl = trim(file_get_contents("textfiles/statusmail_nebenleistung_neu.txt"));
				
				if (substr($nlStatusTxtTpl,0, 8) == "Betreff=") {
					$lines = explode("\n", $nlStatusTxtTpl);
					$suTpl = trim(substr($lines[0], 8));
					$nlStatusTxtTpl = implode("\n", array_slice($lines, 1));
				}
				
				$rplNL["{StatusLink}"] = $MConf["WebRoot"]."?s=nebenleistungen&id=".$NID;
				$rplNL["{HomepageTitle}"] = $MConf["AppTitle"];
				$rplNL["{NID}"] = $NID;
				
				foreach($aNlVerteiler as $r_to) {					
					if (empty($r_to["to"])) $r_to["to"] = $r_to["email"];
					$rplNL["{Name}"] = $r_to["Name"];
					$rplNL["{Vorname}"] = $r_to["Vorname"];
					$su = strtr($suTpl, $rplNL);
					$nlStatusTxt = strtr($nlStatusTxtTpl, $rplNL);
					//echo '#'.__LINE__ . ' fbmail('.$r_to["to"].')' . '<br/>' . PHP_EOL;
					fbmail($r_to["to"], $su, $nlStatusTxt, $hd);
					//fbmail($r_to["to"], $su, $nlStatusTxt."\n\nEmpfänger:\n".print_r($aNlVerteiler,1), $hd);
					//echo "#".__LINE__." fbmail(".print_r($r_to["to"],1).", $su, $nlStatusTxt.\"\n\nEmpfänger:\n\"".print_r($aNlVerteiler,1).", $hd);";
					
				}
			}
			//if ($db->error()) 
			//echo $db->error()."<br>\n".$sql."<br>\n";
			
		}
	} else {
		$nebenleistung_form = "knebenleistung_lesen.html";
		$addBemerkung = $NLInput["aufgabe"];
		$NL->loadDbdata();
		$NL->dbdataToInput();
		$NL->arrInput["aufgabe"] = "Bemerkung von ".$user["user"]." am ".date("d.m.Y")." um ".date("H:i").":\n";
		$NL->arrInput["aufgabe"].= trim($addBemerkung)."\n\n";
		$NL->arrInput["aufgabe"].= $NL->arrDbdata["aufgabe"];
		$NL->save();
		if (!$NL->Error) $NID = $NL->id;
	}
	if (!$NL->Error) {
		$body_content.= "Eintrag wurde gespeichert!<br>\n";
		$save_success = true;
	} else {
		$body_content.= $NL->Error;
	}
} else {
	// If AID: Bearbeitungsformular mit DB-Daten
	if ($NID) {
		$NL->loadDbdata();
		$NL->dbdataToInput();
		
		if ($user["gruppe"]=="admin_standort") {
			if (strpos(",".$user["standortverwaltung"].",", ",".$NL->arrInput["ort"].",")===false)
				die("UNERLAUBTER ZUGRIFF! Standort-Administratoren dürfen nur auf Anträge zugreifen, die in Ihrer Standortverwaltung eingetragen sind!");
		}
		$nebenleistung_form = "knebenleistung_lesen.html";
	} else {
		// else: lade Eingabeformular
		$NL->loadInput(array(), false);
		$NL->arrInput["name"] = $user["nachname"];
		$NL->arrInput["vorname"] = $user["vorname"];
		$NL->arrInput["fon"] = $user["fon"];
		$NL->arrInput["email"] = $user["email"];
		$NL->arrInput["standort"] = $user["standort"];
		$NL->arrInput["gebaeude"] = $user["gebaeude"];
	}
}

if (!$save_success) {
	$Tpl->assign("NL", $NL->arrInput);
	$Tpl->assign("s", $s);
	$body_content.= $Tpl->fetch($nebenleistung_form);
} else {
	include($MConf["AppRoot"]."sites".DS."knebenleistungen.php");
}
?>
