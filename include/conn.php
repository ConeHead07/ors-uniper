<?php 

// DB-Verbindungsdaten

$ConnDB = array(
	"Host" => $MConf["DB_Host"],
	"User" => $MConf["DB_User"],
	"Pass" => $MConf["DB_Pass"],
    "Database" => $MConf["DB_Name"],
    "Port" => $MConf["DB_PORT"] ?? null,
    "Charset" => $MConf["DB_CHARSET"] ?? null,
	"connid" => "",
	"conndbid" => "",
	"connobj" => "",
	"error_Abfrage" => "<b>Abfragefehler</b>",
	"error_Connect" => "<b>Verbindungsfehler</b>",
	"error_Server"  => "<b>DB-Server ist nicht bereit[!]</b>",
	"error_insert"  => "<b>Es wurde kein neuer Eintrag aufgnommen</b>",
	"error_update"  => "<b>Eintrag konnte nicht ge&auml;ndert werden</b>",
	"error_beitraege" => "<b>Zur Zeit sind keine Eintr&auml;ge abrufbar</b>"
);

if (!isset($autoconnect)) $autoconnect = true;

function db_connect(&$ConnAccessData) {
    $db = dbconn::getInstance();
	if ($db!== null && !$db->connect_error()) {
        return $db;
    }
	
	$ConnData = &$ConnAccessData;

	$port = $ConnData['Port'] ?? '3306';
	$aOptions = [];
	if (!empty($ConnData['Charset'])) {
	    $aOptions['charset'] = $ConnData['Charset'];
    }

    $db = dbconn($ConnData["Host"], $ConnData["Database"], $ConnData["User"], $ConnData["Pass"], $port, $aOptions);
	if (!$db->connect_error()) {
		// echo "<pre>#".__LINE__." ".basename(__FILE__)." conn:$conn, connid:$connid, ConnDB:".print_r($ConnDB, true)."</pre>\n";
		$ConnData["conndbid"]= $db->select_db($ConnData["Database"], $ConnData["connid"]);
		if (!$ConnData["conndbid"]) {
			die( "#".__LINE__." ".$db->error()." // ".$ConnData["error_Connect"]);
		}
	} else {
		die( $ConnData["error_Server"]);
		echo "#" . __LINE__ . " new mysqli({$ConnData[Host]} , {$ConnData[User]}, {$ConnData[Pass]}); <br>\n";
	}
	return $db;
}

function db_auto_connect() {
	global $ConnDB;
	global $connid;
	global $conn;
	$connid = db_connect($ConnDB);
	$conn = $connid;
	return $conn;
}

if ((!isset($conn) || !dbconn::getInstance()) && $autoconnect) {
	$connid = db_auto_connect();
}

function db_close() {
    if (dbconn::getInstance()) {
        dbconn::getInstance()->close();
    }
}

// global $dbUsertbl, $grouptb, $dbUser_data_tbl;
$tbl_prefix = "mm_";
$tblPrefix  = $tbl_prefix;

$_TABLE["user"] = $tbl_prefix."user";
$_TABLE["cms_bereiche"] = $tbl_prefix."cms_bereiche";
$_TABLE["cms_texte"] = $tbl_prefix."cms_bereiche";
$_TABLE["immobilien"] = $tblPrefix."stamm_immobilien";
$_TABLE["gebaeude"] = $tblPrefix."stamm_gebaeude";
$_TABLE["abteilungen"] = $tblPrefix."stamm_abteilungen";
$_TABLE["hauptabteilungen"] = $tblPrefix."stamm_hauptabteilungen";
$_TABLE["abteilungen_v"] = $tblPrefix."stamm_abteilungen";
$_TABLE["gf"] = $tblPrefix."stamm_gf";
$_TABLE["mitarbeiter"] = $tblPrefix."stamm_mitarbeiter";
$_TABLE["raumkategorien"] = $tblPrefix."stamm_raumkategorien";
$_TABLE["raumtypen"] = $tblPrefix."stamm_raumtypen";
$_TABLE["umzugsantrag"] = $tblPrefix."umzuege";
$_TABLE["umzugsmitarbeiter"] = $tblPrefix."umzuege_arbeitsplaetze";
$_TABLE["umzugsanlagen"] = $tblPrefix."umzuege_anlagen";
$_TABLE["nebenleistungen"] = $tblPrefix."nebenleistungen";
$_TABLE["nebenleistungsanlagen"] = $tblPrefix."nebenleistungen_anlagen";
$_TABLE["activity_log"] = $tblPrefix."activity_log";
$_TABLE["dienstleister"] = $tblPrefix."dienstleister";
$_TABLE["leistungskatalog"] = $tblPrefix."leistungskatalog";
$_TABLE["leistungskatalog2"] = $tblPrefix."leistungskatalog2";
$_TABLE["leistungskategorie"] = $tblPrefix."leistungskategorie";
$_TABLE["leistungsmatrix"] = $tblPrefix."leistungspreismatrix";

$_TBLKEY["user"] = "uid";
$_TBLKEY["cms_bereiche"] = "id";

$usertbl = $_TABLE["user"];


function single_resultquery($SQL, $connid = "", $fetch_mod = MYSQL_ASSOC) {
        $db = dbconn::getInstance();
        return $db->query_row($SQL);
        echo '#'.__LINE__ . ' ' . __FILE__ . ' db:' . print_r($db,1) . '<br>' . PHP_EOL;
	global $single_result;
	$x=0;
	$single_ergebnis=array();
        echo '#'.__LINE__ . ' ' . __FILE__ . '<br>' . PHP_EOL;
	if ($db!== null && !$db->connect_error() ) {
            echo '#'.__LINE__ . ' ' . __FILE__ . 'SQL: ' . $SQL . '<br>' . PHP_EOL;
		$single_result=$db->query($SQL);
                echo '#'.__LINE__ . ' ' . __FILE__ . ' single_result:' .print_r($single_result,1) . '<br>' . PHP_EOL;
		if ($db->error()) echo db_chckError($SQL,"single_resultquery");
		echo '#'.__LINE__ . ' ' . __FILE__ . '<br>' . PHP_EOL;
                $single_num=$single_result->num_rows;
                echo '#'.__LINE__ . ' ' . __FILE__ . '<br>' . PHP_EOL;
		if ($db->error() && $x) echo db_chckError($SQL,"single_resultquery");
		if ($single_num) {
                    echo '#'.__LINE__ . ' ' . __FILE__ . '<br>' . PHP_EOL;
			$single_ergebnis=$single_result->fetch_assoc();
		}
                echo '#'.__LINE__ . ' ' . __FILE__ . '<br>' . PHP_EOL;
	}
	return $single_ergebnis;
}

function onerowcol_resultquery($SQL, $connid = "") {
        $db = dbconn::getInstance();
	$re="";
	if ($db!== null && !$db->connect_error()) {
		$onerowcol_result_ergebnis=array();
		$onerowcol_result_result=$db->query($SQL);
		$onerowcol_result_num=$db->num_rows($onerowcol_result_result);
		if ($onerowcol_result_num) {
			$onerowcol_result_ergebnis=$db->fetch_array($onerowcol_result_result);
			$re=$onerowcol_result_ergebnis[0];
		}
		$db->free_result($onerowcol_result_result);
	}
	return $re;
}

function count_query($SQL, $connid = "") {
        $db = dbconn::getInstance();
	$x=0;
	$re=0;
	if ($db!== null && !$db->connect_error()) {
            $r=$db->query($SQL);
            if ($r) {
                if ($x) {
                    echo '<pre>#'.__LINE__ . ' ' . __FUNCTION__ . ' SQL: ' . $SQL . PHP_EOL;
                    echo db_chckError($SQL, "count_query");
                    echo 'result: ' . print_r($r,1) . PHP_EOL;
                    echo '</pre>';
                }
                //$_e=$r->fetch_array();
                $_e=$db->fetch_array($r);
                $re = current($_e);
                $db->free_result($r);
            } else {
                if ($x) echo db_chckError($SQL, "count_query");
            }
	}
	return $re;
}

function allRows2Str($SQL, $trenner, $connid = "") {
        $db = dbconn::getInstance();
	$x=0;
	$str="";
	if ($db!== null && !$db->connect_error()) {
		$result=$db->query($SQL);
		if ($x) echo db_chckError($SQL,"allRows2Str, trenner:$trenner");
		if (!$db->error() && $db->num_rows($result)) {
			for ($i=0; $i<$db->num_rows($result); $i++) {
				if ($str) $str.=$trenner;
				$str.=implode($trenner,$db->fetch_array($result,MyDB::NUM));
			}
		}
	}
	return $str;
}

function error_src($_file_ = "", $_line_ = "") {
	$err_src = "";
	$err_src.= ($_line_ !== "") ? "#{$_line_} " : "Zeile unbekannt";
	$err_src.= ($_file_ !== "") ? " in Script {$_file_} " : " in unbekanntem Script!";
	return $err_src;
}

function sql_err(&$SQL, $_f_ = "", $_l_ = "") {
	$err_src = "<pre>\n";
	$err_src.= error_src($_f_, $_l_);
	$err_src.= "QUERY: ".fb_htmlEntities($SQL)."\n";
	$err_src.= "ERROR: ".$db->error()."\n";
	$err_src.= "ErrID: ".$db->errno()."\n";
	$err_src = "</pre>";
}

function result2array($SQL, $connid, $_file_ = "", $_line_ = "") {
        $db = dbconn::getInstance();
	global $syserr;
	if ($_line_ !== "") $syserr.= "#{$_line_} ";
	if ($_file_ !== "") $syserr.= " in Script {$_file_} ";
	$r = $db->query($SQL);
	if (!$r) {
		$syserr.= sql_err($SQL, $_file_, $_line_);
		// echo "#".__LINE__." ".basename(__FILE__)." => ".$syserr."\n";
		return false;
	}
	
	$aRslt = array();
	if ($r) {
		$nf = $db->num_fields($r);
		$n = $db->num_rows($r);
		for ($i = 0; $i < $n; $i++) {
			$e = $db->fetch_array($r, MYSQL_ASSOC);
			if ($nf == 1) {
				$aRslt[$i] = $e[key($e)];
			} else {
				$aRslt[$i] = $e;
			}
		}
		$db->free_result($r);
		return $aRslt;
	} else echo "#".__LINE__." Interner Fehler!";
	return false;
}

function db_chckError($SQL, $rem, $connid = "") {
        $db = dbconn::getInstance();
	$re="";
	if ($db!== null && !$db->connect_error()) {
		$error=$db->error();
		if ($rem) $re.=$rem."<br>\n";
		$re.="<b>MYSQL-Fehler :</b>";
		$re.=($error)?"Ja":"Nein";
		$re.="<br>\n";
		if ($error) {
			$re.="<b>Fehlertext:</b> $error <br>\n";
			$re.="<b>Fehler-ID :</b> ".$db->errno()."<br>\n";
		}
		$re.="<b>SQL-Befehl:</b> $SQL <br>";
	}
	return $re;
}

function get_enum_array($enumTypeDef, $connid = "") {
        $db = dbconn::getInstance();
        
	if ($db!== null && !$db->connect_error()) {
		//echo "get_enum_array($enumTypeDef)<br>";
		$enumTypeDef=substr($enumTypeDef,strpos($enumTypeDef,"(")+1);
		$enumTypeDef=substr($enumTypeDef,0,strrpos($enumTypeDef,")"));
		$enumTypeDef=strtr($enumTypeDef,"\"'","'");
		$enumTypeDef=str_replace("'","",$enumTypeDef);
		if (strchr($enumTypeDef,","))
			return explode(",",$enumTypeDef);
		else 
		{
			$enumTypeDef[0]=$enumTypeDef;
			return $enumTypeDef[0];
		}
	}
	return array();
}


if (!function_exists("db_auswahlwerte")) {
function db_auswahlwerte($tbl, $fld, $connid = "") {
        $db = dbconn::getInstance();

        $SQL="SHOW FIELDS FROM $tbl LIKE '$fld'";
	$result=$db->query($SQL);
	if ($db->error()) echo $db->error()."<br><b>SQL:</b> $SQL";
	$erg=$db->fetch_array($result);
	$moegliche_werte=$erg["Type"];
	if (strchr($moegliche_werte,"("))
            $feldtyp=substr($moegliche_werte,0,strpos($moegliche_werte,"("));
	else
            $feldtyp=$moegliche_werte;
        
	$moegliche_werte=substr($moegliche_werte,strpos($moegliche_werte,"('")+2);
	$moegliche_werte=substr($moegliche_werte,0,strrpos($moegliche_werte,"')"));
	$moegl_werte_arr=explode("','",$moegliche_werte);
	return array($feldtyp,$moegl_werte_arr);
}
}
//mail2logfile("errors","frank.barthold@azmedia.de","subject","body","header","testmail");

if (!function_exists("insert_db_auswahlwerte")) {
function insert_db_auswahlwerte($tbl, $fld, $new, $connid = "") {
        $db = dbconn::getInstance();
        
	list($auswahltyp,$auswahlwerte_arr) = db_auswahlwerte($tbl,$fld);
	$auswahlwerte_str="'".implode("','",$auswahlwerte_arr)."'";
	$SQL="SHOW FIELDS FROM $tbl LIKE '$fld'";
	$fld_props=single_resultquery($SQL);
	if ($db->error()) echo $db->error()."<br><b>SQL:</b> $SQL";
	$newIsOk=(!strchr( strtolower($auswahlwerte_str), "'".strtolower($new)."'" ))?1:0;
	while(list($k,$v) = each ($fld_props)) $fld_props[strtolower($v)]=$k;
	if (isset($fld_props["type"]) && $newIsOk) {
		$SQL="ALTER TABLE `$tbl` CHANGE `$fld` `$fld`";
		$SQL.=" $auswahltyp( $auswahlwerte_str,'$new' )";
		if ($fld_props["null"]!="YES") $SQL.=" NOT NULL ";
		if ($fld_props["default"]) $SQL.=" DEFAULT ".$fld_props["default"];
		if ($fld_props["extra"]) $SQL.=" ".$fld_props["extra"];
		$db->query($SQL);
		if ($db->error()) echo $db->error()."<br>SQL: $SQL <br>\n";
		else return true;
	}
	return false;
}
}

if (!function_exists("delete_db_auswahlwert")) {
function delete_db_auswahlwert($tbl, $fld, $val, $connid = "") {
        $db = dbconn::getInstance();
        
	list($auswahltyp,$auswahlwerte_arr) = db_auswahlwerte($tbl,$fld);
	$delIsOk=0;
	for ($i=0; $i<count($auswahlwerte_arr); $i++) {
		if ($auswahlwerte_arr[$i]==$val) {
			$delIsOk=1;
			array_splice($auswahlwerte_arr, $i, 1);
			break;
		}
	}
	$auswahlwerte_str="'".implode("','",$auswahlwerte_arr)."'";
	$SQL="SHOW FIELDS FROM $tbl LIKE '$fld'";
	$fld_props=single_resultquery($SQL);
	if ($db->error()) echo $db->error()."<br><b>SQL:</b> $SQL";
	while(list($k,$v) = each ($fld_props)) $fld_props[strtolower($v)]=$k;
	
	if (isset($fld_props["type"]) && $delIsOk) {
		$SQL="ALTER TABLE `$tbl` CHANGE `$fld` `$fld`";
		$SQL.=" $auswahltyp( $auswahlwerte_str )";
		if ($fld_props["null"]!="YES") $SQL.=" NOT NULL ";
		if ($fld_props["default"]) $SQL.=" DEFAULT ".$fld_props["default"];
		if ($fld_props["extra"]) $SQL.=" ".$fld_props["extra"];
		$db->query($SQL);
		if ($db->error()) echo $db->error()."<br>SQL: $SQL <br>\n";
		else return true;
	}
	return false;
}
}

if (!function_exists("db_show_fields")){
function db_show_fields($tbl, $connid = "") {
        $db = dbconn::getInstance();
        
	$SQL = "SHOW FIELDS FROM $tbl";
	$result= $db->query($SQL);
	if ($db->error()) echo $db->error()."<br><b>SQL:</b> $SQL";
	$num = $db->num_rows($result);
	
	$f = array();
	$$db->tbl_flds = array();
	for ($i=0; $i<$num; $i++) {
		$erg = $db->fetch_array($result);
		list($f["field"],$f["type"],$f["null"],$f["key"],$f["default"],$f["extra"]) = $erg;
		$$db->tbl_flds[$f["field"]] = $f;
	}
	return $$db->tbl_flds;
}
}

if (!function_exists("optionsFelderAusMysqlAuswahlfeld")) {
function optionsFelderAusMysqlAuswahlfeld($table, $field, $connid = "") {
	$optionsFelder = "";
	list($typ,$auswahlwerte) = db_auswahlwerte($table, $field);
	if (is_array($auswahlwerte) && count($auswahlwerte)) {
		for ($i=0; $i<count($auswahlwerte); $i++) {
			$wert = $auswahlwerte[$i];
			$optionsFelder.="<option value=\"$wert\"";
			$optionsFelder.=" check_".$field."=\"".$wert."\">$wert</option>\n";
		}
	}
	return $optionsFelder;
}
}
