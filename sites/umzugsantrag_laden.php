<?php 
if (basename(__FILE__)==basename($_SERVER["PHP_SELF"])) require_once("../header.php");
require_once($MConf["AppRoot"].$MConf["Inc_Dir"]."php_json.php");

require_once($InclBaseDir."umzugsantrag.inc.php");
require_once($InclBaseDir."umzugsmitarbeiter.inc.php");

if (!function_exists("get_ma_post_items")){ function get_ma_post_items() {
	global $_POST;
	//echo "<pre>#".__LINE__." ".basename(__FILE__)." _POST:".print_r($_POST,1)."</pre><br>\n";
	
	$aMaItems = array();
	if (!empty($_POST["MA"])) for($i = 0; $i < count($_POST["MA"]["vorname"]); $i++) {
		$aMaItems[$i]["ID"] = $i+1;
		foreach($_POST["MA"] as $fld => $aTmp) {
			$aMaItems[$i][$fld] = $_POST["MA"][$fld][$i];
		}
	}
	return $aMaItems;
}}

function umzugsantrag_laden() {
	global $db;
	global $error;
	global $msg;
	global $LoadScript;
	global $_CONF;
	global $connid;
	global $user;
	
	$ASJson = "";
	$MAJson = "";
	
	$AID = getRequest("id","");
	if (!$AID) {
		$error.= "Daten können erst nach erstmaligem Speichern neu geladen werden!<br>\n";
		return false;
	}

    $creator = ( preg_match('/user|kunde|property/', $user['gruppe'] ) ? 'property' : 'mertens');
	$ASConf = $_CONF["umzugsantrag"];
	$AS = new ItemEdit($ASConf, $connid, $user, $AID);
	
	$Tpl = new myTplEngine();
    $Tpl->assign('user', $user);
	$TplMaListItem = array("ID"=>1, "nachname"=>"", "vorname"=>"", "ort"=>"", "gebaeude"=>"", "raumnr"=>"");

	$MAConf = $_CONF["umzugsmitarbeiter"];
	// If AID: Bearbeitungsformular mit DB-Daten
	if ($AID) {
		$AS->loadDbdata();
		$AS->dbdataToInput();
		//$msg.= "#".__LINE__." AS->arrDbdata: ".print_r($AS->arrDbdata,1)."<br>\n";
		//$msg.= "#".__LINE__." AS->arrInput: ".print_r($AS->arrInput,1)."<br>\n";
		$ASJson = "{";
		$i=0;
		foreach($AS->arrInput as $field => $value) {
			$ASJson.= ($i?",\n":"")."\t\"$field\":\"".json_escape($value)."\"";
			$i++;
		}
		$ASJson.= ($i?"\n":"")."}";
		
		$sql = "SELECT `mid` FROM `".$MAConf["Table"]."` WHERE `aid` = \"".$db->escape($AID)."\"";
		$MAIDs = $db->query_rows($sql);
		//$msg.= "sql: ".$sql."\n";
		//$msg.= print_r($MAIDs,1);
		$MAJson = "[";
		for($i = 0; $i < count($MAIDs); $i++) {
			$MAID = $MAIDs[$i]["mid"];
			$MA = new ItemEdit($MAConf, $connid, $user, $MAID);
			$MA->dbdataToInput();
			$MAItems[$i] = $MA->arrInput;
			
			
			$raumdaten = get_raumdaten_byGER($MAItems[$i]["zgebaeude"], $MAItems[$i]["zetage"], $MAItems[$i]["zraumnr"]);
			$raum_ma_fix = get_arbeitsplatz_belegung($raumdaten["id"], $apnr=false);
			$raum_ma_hin = get_arbeitsplatz_hinzuege($raumdaten["id"], $apnr=false);
			
			$count_ma_fix = (is_array($raum_ma_fix) && count($raum_ma_fix)) ? count($raum_ma_fix) : 0;
			$count_ma_hin = (is_array($raum_ma_hin) && count($raum_ma_hin)?count($raum_ma_hin):0);
			$count_ma_all = $count_ma_fix+$count_ma_hin;
			
			if ($count_ma_all) {
				$isCritical = ($raumdaten["raum_flaeche"] / 10 < $count_ma_all);
			}
			$MAItems[$i]["critical_status_index"] = ($isCritical ? 1 : 0);
			$MAItems[$i]["critical_status_info"] = ($raumdaten["raum_flaeche"]."qm: ".$count_ma_fix."Fix + ".$count_ma_hin."Hin");
			$MAItems[$i]["critical_status_img"] = ($isCritical ? "warning_triangle.png" : "thumb_up.png");
			//$MAItems[$i] = null;
			$MAJson.= ($i?",\n":"\n")."\t{";	$j=0;
			foreach($MAItems[$i] as $field => $value) {
				$MAJson.= ($j?",\n":"")."\t\t\"$field\":\"".json_escape($value)."\"";
				$j++;
			}
			$MAJson.= ($j?"\n":"")."\t}";
		}
		$MAJson.= ($i?"\n":"")."]";
		
		$LoadScript.= "UmzugsdatenAS = $ASJson;\n";
		$LoadScript.= "UmzugsdatenMA = $MAJson;\n";
		$LoadScript.= "if (typeof(umzugsantrag_load)) umzugsantrag_load(UmzugsdatenAS, UmzugsdatenMA); else alert('Fehler beim Laden der Daten');\n";
		
		$i=0;
		foreach($AS->arrInput as $field => $value) {
			$MAJson.= ($i?",\n":"")."\t\"$field\":\"".addslashes($value)."\"";
			$i++;
		}
		
	} else {
	// else: lade Eingabeformular
		$defaultAS = array(
                        "token" => md5(print_r($user,1).':'.time().':'.rand(1,999999)),
			"vorname" => $user["vorname"],
			"name" => $user["nachname"],
			"fon" => $user["fon"],
			"email"=> $user["email"],
			"ort" => $user["standort"],
			"gebaeude" => $user["gebaeude"]
		);
                print_r($defaultAS);
		$AS->loadInput($defaultAS, false);
		// $MA->loadInput(array(), false);
		//$MAItems = array($MA->arrInput);
	}
    $umzug_optionvals = explode("','", trim($_CONF["umzugsantrag"]['Fields']['umzug']['size'], "'"));

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
	
	$Tpl->assign("AS", $AS->arrInput);
    $Tpl->assign("s", 'aantrag');
    $Tpl->assign('AID', $AID);
    $Tpl->assign('AIDJson', json_encode($AID));
    $Tpl->assign("ASConf", $ASConf['Fields']);$Tpl->assign("AS", $AS->arrInput);
    $Tpl->assign("ASJson", json_encode($AS->arrInput));
    $Tpl->assign("umzugsstatus", $AS->arrInput['umzugsstatus']);
    $Tpl->assign("umzugsstatusJson", json_encode($AS->arrInput['umzugsstatus']));
    $Tpl->assign("antragsstatus", $AS->arrInput['antragsstatus']);
    $Tpl->assign("antragsstatusJson", json_encode($AS->arrInput['antragsstatus']));
    $Tpl->assign("umzug_options", array_combine($umzug_optionvals, $umzug_optionvals));
    $Tpl->assign("creator", $creator);
    $Tpl->assign("creatorJson", json_encode($creator));
    $Tpl->assign("user", $user);
    $Tpl->assign('lkTreeItems', []);
    $Tpl->assign('lkTreeItemsJson', '[]');
    $Tpl->assign('lkmById', []);
    $Tpl->assign('lkmByIdJson', '[]');
    $Tpl->assign('Umzugsleistungen', []);
    $Tpl->assign('UmzugsleistungenJson', '[]');

	if (!empty($MAItems) && count($MAItems)) {
	    $Tpl->assign("Mitarbeiterliste", $MAItems);
    }
	
	return $Tpl->fetch("umzugsformular.tpl.html");
}

