<?php
require_once(dirname(__FILE__)."/../../header.php");
require_once($MConf["AppRoot"].$MConf["Inc_Dir"]."registered_data.inc.php");
$default_db = $MConf["DB_Name"];
$db_only = $MConf["DB_Name"];
$db_host = $MConf["DB_Host"];
$db_user = $MConf["DB_User"];
$db_pass = $MConf["DB_Pass"];
$includeDir = $MConf["AppRoot"].$MConf["Inc_Dir"];
$templateDir = $MConf["AppRoot"].$MConf["Tpl_Dir"];
$s = "cf";

function queryResultFirstColToArr($SQL, $connid, &$err) {
	global $error;
	// echo "#".__LINE__." r:$r, SQL:".fb_htmlEntities($SQL)."<br>\n";
        if (is_object($connid)) {
            if ($connid instanceof dbconn && !$connid->connected)  {
		$error.= "Ungültiges dbconn-Object \$connid:<pre>".print_r($connid,1)."</pre>!<br>\n";
                
		return false;
            }
        }
        
	if (is_scalar($connid) && !is_resource($connid) ) {
		$error.= "Ungültiger DB-Conn-Handler \$connid:$connid!<br>\n";
		return false;
	}
	$reArr = array();
	$r = MyDB::query($SQL, $connid);
	// echo "#".__LINE__." r:$r, SQL:".fb_htmlEntities($SQL)."<br>\n";
	if ($r) {
		$n = MyDB::num_rows($r);
		for ($i = 0; $i < $n; $i++) {
			$e = MyDB::fetch_array($r, MyDB::NUM);
			$reArr[] = $e[0];
		}
		MyDB::free_result($r);
	} else {
		$err = "<pre>#".__LINE__." ERROR: ".MyDB::error()."\nQUERY:".fb_htmlEntities($SQL)."\n</pre>\n";
		$error.= $err;
	}
	return $reArr;
}

function getFormOptionsByArr($inputArray, $optionValByKey = false, $default = "") {
	$options = "";
	foreach($inputArray as $k => $v) {
		if ($optionValByKey) {
			$options.= "<option value=\"".fb_htmlEntities($k)."\" ".($default == $k ? "selected=\"true\"":"").">".fb_htmlEntities($v)."</option>\n";
		} else {
			$options.= "<option value=\"".fb_htmlEntities($v)."\" ".($default == $v ? "selected=\"true\"":"").">".fb_htmlEntities($v)."</option>\n";
		}
	}
	return $options;
}
function getFormOptionsBySQL($SQL, $connid, $optionValByKey = false, $default = "") {
	global $error;
	$inputArray = queryResultFirstColToArr($SQL, $connid, $err);
	// echo "#".__LINE__." print_r(\$inputArray):".print_r($inputArray, true)." <br>\n";
	if ($inputArray) return getFormOptionsByArr($inputArray, $optionValByKey, $default);
	else { $error.= $err; return ""; }
}

$aNewConfVars = array("NewConfDb", "NewConfTbl", "NewConfDir", "NewConfName", "lastSelDb", "lastSelTbl");
if (!isset($_REQUEST["reset"])) {
	foreach($aNewConfVars as $k) {
		$newConfVars[$k] = (isset($_REQUEST[$k]) ? $_REQUEST[$k] : "");
	}
}

$chckDb = (isset($newConfVars["NewConfDb"]) ? $newConfVars["NewConfDb"] : ($default_db ? $default_db : ""));
$options_NewConf_db = getFormOptionsBySQL("SHOW DATABASES", $connid, false, $chckDb)."<br>\n";
$options_NewConf_tbl = ($chckDb) ? getFormOptionsBySQL("SHOW Tables FROM `".$chckDb."`", $connid, false, (isset($newConfVars["NewConfTbl"])?$newConfVars["NewConfTbl"]:"")) : "";
if (!empty($newConfVars["NewConfName"])) $chckKey = $newConfVars["NewConfName"];
elseif (isset($newConfVars["NewConfTbl"]) && $newConfVars["NewConfTbl"] == $newConfVars["lastSelTbl"] && $newConfVars["NewConfDb"] == $newConfVars["lastSelDb"]) { 
	$t = explode("_", $newConfVars["NewConfTbl"]);
	$chckKey = (count($t) > 1) ? implode("_", array_slice($t, 1)) : $newConfVars["NewConfTbl"];
} else $chckKey = "";

$cnfItemList = "";
$formbuilder_presets = file_get_contents(dirname(__FILE__).DS."/html/formbuilder_presets.html");
$tplCnfItem = get_cms("{ItemConfig}", "{/ItemConfig}", $formbuilder_presets);

if (!empty($ConfRegData)) {
	foreach($ConfRegData as $cnfKey => $cnfFile) {
		$cnfItemList.= strtr($tplCnfItem, array('{cnfKey}'=>$cnfKey, '{cnfFile}'=>$cnfFile));
	}
}

$formbuilder_presets = set_cms($formbuilder_presets, "{ItemConfig}", "{/ItemConfig}", $cnfItemList);
$formbuilder_presets = str_replace("<!-- {options_NewConf_db} -->", $options_NewConf_db, $formbuilder_presets);
$formbuilder_presets = str_replace("<!-- {options_NewConf_tbl} -->", $options_NewConf_tbl, $formbuilder_presets);
$formbuilder_presets = str_replace("{NewConfName}", $chckKey, $formbuilder_presets);
$formbuilder_presets = str_replace("{lastSelDb}", (isset($newConfVars["NewConfDb"])?$newConfVars["NewConfDb"]:""), $formbuilder_presets);
$formbuilder_presets = str_replace("{lastSelTbl}", (isset($newConfVars["NewConfTbl"])?$newConfVars["NewConfTbl"]:""), $formbuilder_presets);
$formbuilder_presets = str_replace("{s}", $s, $formbuilder_presets);
$formbuilder_presets = strtr($formbuilder_presets, array("{ItemConfig}"=>"", "{/ItemConfig}"=>""));

if (basename(__FILE__) == basename($_SERVER["PHP_SELF"]))
	echo $formbuilder_presets;
?>