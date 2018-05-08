<?php
require(dirname(__FILE__)."/../../header.php");
$default_db = $MConf["DB_Name"];
$db_only = $MConf["DB_Name"];
$db_host = $MConf["DB_Host"];
$db_user = $MConf["DB_User"];
$db_pass = $MConf["DB_Pass"];
$dirLib = "./include/";
$dirTpl = "./html/";
$dirBase = "./";
$s = "cf";
$dir_only = $dirBase;
if (!isset($tblPrefix)) $tblPrefix = "";
$modul = "formbuilder";

$modul_dir = basename(dirname(__FILE__));

function queryResultFirstColToArr($SQL, $connid, &$err) {
	global $error;
	// echo "#".__LINE__." r:$r, SQL:".fb_htmlEntities($SQL)."<br>\n";
	if (!is_resource($connid)) {
		$error.= "Ungültiger DB-Conn-Handler \$connid:$connid!<br>\n";
		return false;
	}
	$reArr = array();
	$r = MyDB::query($SQL, $connid);
	// echo "#".__LINE__." r:$r, SQL:".fb_htmlEntities($SQL)."<br>\n";
	if ($r) {
		$n = MyDB::num_rows($r);
		for ($i = 0; $i < $n; $i++) {
			$e = MyDB::fetch_array($r, MYSQL_NUM);
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

$chckDb = (isset($_REQUEST["SetDb"]) ? $_REQUEST["SetDb"] : ($default_db ? $default_db : ""));
$options_set_db = getFormOptionsBySQL("SHOW DATABASES", $connid, false, $chckDb)."<br>\n";
$options_set_tbl = ($chckDb) ? getFormOptionsBySQL("SHOW Tables FROM `".$chckDb."`", $connid, false, (isset($_REQUEST["SetTbl"])?$_REQUEST["SetTbl"]:"")) : "";
if (!empty($_REQUEST["SetCnfKey"])) $chckKey = $_REQUEST["SetCnfKey"];
elseif (isset($_REQUEST["SetTbl"]) && $_REQUEST["SetTbl"] == $_REQUEST["lastSelTbl"] && $_REQUEST["SetDb"] == $_REQUEST["lastSelDb"]) { 
	$t = explode("_", $_REQUEST["SetTbl"]);
	$chckKey = (count($t) > 1) ? implode("_", array_slice($t, 1)) : $_REQUEST["SetTbl"];
} else $chckKey = "";
$chckDir = (!empty($_REQUEST["SetDir"])) ? $_REQUEST["SetDir"] : $dirBase;

$cnfItemList = "";
$formbuilder_presets = file_get_contents(dirname(__FILE__).DS."/html/formbuilder_presets.html");
$tplCnfItem = get_cms("{ItemConfig}", "{/ItemConfig}", $formbuilder_presets);
echo $MConf["Inc_Dir"]."registered_data.inc.php"."<br>\n";
include($MConf["AppRoot"].$MConf["Inc_Dir"]."registered_data.inc.php");
if (!empty($ConfRegData)) {
	foreach($ConfRegData as $cnfKey => $cnfFile) {
		$cnfItemList.= strtr($tplCnfItem, array('{cnfKey}'=>$cnfKey, '{cnfFile}'=>$cnfFile));
	}
}

$formbuilder_presets = set_cms($formbuilder_presets, "{ItemConfig}", "{/ItemConfig}", $cnfItemList);
$formbuilder_presets = str_replace("<!-- {options_set_db} -->", $options_set_db, $formbuilder_presets);
$formbuilder_presets = str_replace("<!-- {options_set_tbl} -->", $options_set_tbl, $formbuilder_presets);
$formbuilder_presets = str_replace("{SetDir}", $chckDir, $formbuilder_presets);
$formbuilder_presets = str_replace("{SetCnfKey}", $chckKey, $formbuilder_presets);
$formbuilder_presets = str_replace("{lastSelDb}", (isset($_REQUEST["SetDb"])?$_REQUEST["SetDb"]:""), $formbuilder_presets);
$formbuilder_presets = str_replace("{lastSelTbl}", (isset($_REQUEST["SetTbl"])?$_REQUEST["SetTbl"]:""), $formbuilder_presets);
$formbuilder_presets = str_replace("{s}", $s, $formbuilder_presets);
$formbuilder_presets = strtr($formbuilder_presets, array("{ItemConfig}"=>"", "{/ItemConfig}"=>""));

echo $formbuilder_presets;
?>
