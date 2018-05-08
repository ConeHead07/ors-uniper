<?php 

function check_parentid(&$eingabe, &$lesen) {
	$re["Error"] = "";
	$lesen["parents"] = "";
	if (isset($eingabe["parentid"])) {
		$Menu = get_menu_byId($eingabe["parentid"]);
		if ($Menu != false) {
			$_Parent = get_menu_parentItems($eingabe["parentid"]);
			for ($i = 0; $i < count($_Parent); $i++) {
                            if ($lesen["parents"]) $lesen["parents"].= " ";
                            $lesen["parents"].= "&raquo;".print_r($_Parent[$i],1);
			}
		} else {
			$re["Error"] = "Un�ltige ParentID: ".$eingabe["parentid"]."!<br>\n";
		}
	}
	return $re;
}

function check_srv(&$eingabe, &$lesen) {
	$validChars = "abcdefghijklmnopqrstuvwxyz-_0123456789";
	$re["Error"] = "";
	$lesen["parents"] = "";
	if (isset($eingabe["srv"])) {
		$ckStr = strtolower($eingabe["srv"]);
		for ($i = 0; $i < strlen($ckStr); $i++) {
			if (!is_int(strpos($validChars, $ckStr[$i]))) {
				$re["Error"] = "Unzul�ssiges Zeichen: <span style=\"font-style:italic;\">".$ckStr[$i]."</span>.  Zul�ssig sind: $validChars!<br>\n";
			}
		}
	}
	return $re;
}

function get_tplFormVars(&$eingabe, &$lesen, &$_TplVars, &$_FLDCONF) {
	reset($eingabe);
	reset($lesen);
	reset($_FLDCONF);
	
	while(list($k, $v) = each($_FLDCONF)) {
		
		$posAttrStart = strpos($v["type"], "(");
		$posAttrEnd = strrpos($v["type"], ")");
		
		if ($posAttrStart === false && $posAttrEnd === false) {
			 $checkType = $v["type"];
			 $checkTypeAttr = "";
		} else {
			 $checkType = substr($v["type"], 0, $posAttrStart);
			 $checkAttr = substr($v["type"], $posAttrStart, $posAttrStart-$posAttrStart);
		}
		
		$_TplVars["%eingabe[".$k."]%"] = "";
		$_TplVars["%lesen[".$k."]%"] = "";
		if (isset($eingabe[$k])) $_TplVars["%eingabe[".$k."]%"] =&$eingabe[$k];
		if (isset($lesen[$k])) $_TplVars["%lesen[".$k."]%"] =&$lesen[$k];
		
		
		if (isset($eingabe[$k])) {
			switch($checkType) {
				// enum(),set(),char(),int(),float(),date,datetime,time,email,text,html,created,modified
				
				case "enum":
				if (is_int(strpos($v["htmlinputtype"], "option"))) {
					$_TplVars["chck_".$k."=\"".$eingabe[$k]."\""] = "checked selected";
				}
				break;
				
				case "set":
				if (is_int(strpos($v["htmlinputtype"], "option"))) {
					$encloser = (strpos($checkAttr, "'") === false) ? "" : "'";
					$_Werte = (is_array($eingabe[$k])) ? $eingabe[$k] : explode(",", $eingabe[$k]);
					for ($wi = 0; $wi < count($_Werte); $wi++) {
						if (strpos(",".$checkAttr.",", ",".$encloser.$_Werte[$wi].$encloser.",") === false) {
							$_TplVars["chck_".$k."=\"".$_Werte[$wi]."\""] = "checked selected";
						}
					}
				}
				break;
				
				default:
				// Nothing
			}
		}
	}
	
	reset($eingabe);
	reset($lesen);
	reset($_FLDCONF);
	return true;
}


function init_menu_input(&$eingabe, &$lesen, &$_FLDCONF) {
	global $_TABLE;
	global $_TBLKEY;
	global $conn;
		
	$err = "";
	$_eFields = array();
	while(list($k, $v) = each($_FLDCONF)) {
		$eingabe[$k] = $v["default"];
		$lesen[$k] = &$eingabe[$k];
	}
}

function check_menu_input(&$eingabe, &$lesen, &$_FLDCONF) {
	global $_TABLE;
	global $_TBLKEY;
	global $conn;
		
	$err = "";
	$_eFields = array();
	foreach($_FLDCONF as $k => $v) {
		if (isset($eingabe[$k])) {
			$eingabewert = $eingabe[$k];
			$lesen[$k] = $eingabe[$k];
		} else {
			$eingabewert = "";
			$eingabe[$k] = "";
			$lesen[$k] = "";
		}
		// Check: Pflichteingabe
		$ok = 1;
		if ($v["required"]) {
			if (!isset($eingabe[$k]) || $eingabe[$k] === "") {	
				$_eFields[$k] = "Fehlende Angabe!";
				$err = "Fehlende Angaben!<br>";
				$ok = 0;
			}
		}
	}
	
	foreach($_eFields as $k => $v) {
		$err.= " <li>".$_FLDCONF[$k]["label"]."</li>";
	}
	if ($err) return array($err, $_eFields);
	
	foreach($_FLDCONF as $k => $v) {
		
		$posAttrStart = strpos($v["type"], "(");
		$posAttrEnd = strrpos($v["type"], ")");
		
		if ($posAttrStart === false && $posAttrEnd === false) {
			 $checkType = $v["type"];
			 $checkTypeAttr = "";
		} else {
			 $checkType = substr($v["type"], 0, $posAttrStart);
			 $checkAttr = substr($v["type"], $posAttrStart+1, $posAttrEnd-($posAttrStart+1));
		}
		
		if ($eingabe[$k]) {
			switch($checkType) {
				case "enum":
				$encloser = (strpos($checkAttr, "'") === false) ? "" : "'";
				if (strpos(",".$checkAttr.",", ",".$encloser.$eingabe[$k].$encloser.",") === false) {
					$err.= "Ung�ltiger Auswahlwert in ".$v["label"].": ".$eingabe[$k]."!<br>\n";
					$_eFields[$k] = "Ung�ltiger Auswahlwert!";
					$er = 1;
				} else $er = 0;
				break;
				
				case "set":
				// echo "#".__LINE__." checkAttr:$checkAttr, print_($k):".print_r($eingabe[$k], true)." <br>\n";
				$encloser = (strpos($checkAttr, "'") === false) ? "" : "'";
				$_Werte = (is_array($eingabe[$k])) ? $eingabe[$k] : explode(",", $eingabe[$k]);
				for ($wi = 0; $wi < count($_Werte); $wi++) {
					// echo "#".__LINE__." strpos(\"{$checkAttr}\", \"{$encloser}{$_Werte[$wi]}{$encloser}\" <br>\n";
					if (strpos(",".$checkAttr.",", ",".$encloser.$_Werte[$wi].$encloser.",") === false) {
						$err.= "Ung�ltiger Auswahlwert in ".$v["label"].": ".$_Werte[$wi]."!<br>\n";
						$_eFields[$k] = "Ung�ltiger Auswahlwert!";
					}
				}
				$lesen[$k] = implode(",\n", $_Werte);
				break;
				
				case "int":
				if (strval(intval($eingabe[$k])) != $eingabe[$k]) {
					$err.= $v["label"]." erwartet positive Ganzzahl: ".$eingabe[$k]."!<br>\n";
					$_eFields[$k] = "Eingabe ist keine positive Ganzzahl!";
				}
				break;
				
				case "char":
				$maxlen = ($checkAttr) ? $checkAttr : 200;
				if (strlen($eingabe[$k]) > $maxlen) {
					$err.= "Eingabe ".$v["label"]." ist zu lang!<br>\n";
					$_eFields[$k] = "Eingabe ist zu langl!";
				}
				break;
				
				case "email":
				$re = check_email($eingabe[$k]);
				if ($re["Error"]) {
					$err.= $re["Error"];
					if (!isset($_eFields[$k])) $_eFields[$k] = "";
					$_eFields[$k].= $re["Error"];
				}
				break;
				
				case "date":
				$re = check_date($eingabe[$k]);
				$eingabe[$k] = $re["Datum"];
				$lesen[$k] = $re["Deutsch"];
				if ($re["Error"]) {
					$err.= $re["Error"];
					if (!isset($_eFields[$k])) $_eFields[$k] = "";
					$_eFields[$k].= $re["Error"];
				}
				// check_date
				break;
				
				case "file":
				if (!file_exists($eingabe[$k])) {
					if (!isset($_eFields[$k])) $_eFields[$k] = "";
					$_eFields[$k].= "Die Datei \"$eingabe[$k]\" wurde nicht gefunden!<br>\n";
				}
				break;
				
				case "datetime":
				$datums_auswertung = check_datetime($eingabe[$k]);
				$eingabe[$k] = $datums_auswertung["Datumzeit"];
				$lesen[$k] = $datums_auswertung["Deutsch"];
				if ($re["Error"]) {
					$err.= $re["Error"];
					if (!isset($_eFields[$k])) $_eFields[$k] = "";
					$_eFields[$k].= $re["Error"];
				}
				break;
				
				case "time":
				$datums_auswertung = check_time($eingabe[$k]);
				$eingabe[$k] = $datums_auswertung["Zeit"];
				$lesen[$k] = $datums_auswertung["Deutsch"];
				if ($re["Error"]) {
					$err.= $re["Error"];
					if (!isset($_eFields[$k])) $_eFields[$k] = "";
					$_eFields[$k].= $re["Error"];
				}
				break;
				
				case "text":
				break;
				
				case "html":
				break;
				
				case "created":
				case "modified":
				break;
				
				case "key":
				break;
			}
			
			if ($v["unique"]) {
				$keyname = $_TBLKEY["cms_bereiche"];
				$keyval = (isset($eingabe[$keyname]) && $eingabe[$keyname]) ? $eingabe[$keyname] : "";
				if (!check_unique($conn, $_TABLE["cms_bereiche"], $k, $eingabe[$k], $keyname, $keyval)) {
					$err.= "Die Angabe in ".$v["label"]." existiert bereits:".$eingabe[$k]."!<br>\n";
					if (!isset($_eFields[$k])) $_eFields[$k] = "";
					$_eFields[$k].= "Eingabe existiert bereits!";
				}
			}
			
			if ($v["checkByFunction"]) {
				$re = call_user_func($v["checkByFunction"],$eingabe, $lesen);
				if ($re["Error"]) {
					$err.= $re["Error"];
					if (!isset($_eFields[$k])) $_eFields[$k] = "";
					$_eFields[$k].= $re["Error"];
				}
			}
		}
	}
	return array($err, $_eFields);
}

function menu_eingabe2lesen($eingabe, &$_FLDCONF) {
	global $maintbl;
	global $tbl_userID;
	global $monatStrLongArr;
	
	$eingabe2lesen = $eingabe;
	// 
	// case "datum":
	// case "titel":
	// case "listentitel":
	// case "text1":
	// case "webfreigabe":
	
	while(list($k,$v) = each($_FLDCONF)) {
		$eingabewert = (isset($eingabe[$k])) ? $eingabe[$k] : "";
		// Test auf Pflichtfeld, wenn Ja pr�fe ob eine Eingabe get�tigt wurde
		if (trim($eingabewert)) {
			switch($v["type"]) {
				
				// Pruefe Datumsfelder
				case "date":
				$datums_auswertung = check_date($eingabewert);
				$eingabe2lesen[$k] = $datums_auswertung["Deutsch"];
				break;
				
				// Pruefe Datums mit Uhrzeit
				case "datetime":
				$datums_auswertung = check_datetime($eingabewert);
				$eingabe2lesen[$k] = $datums_auswertung["Deutsch"];
				break;
				
				// Pruefe Zeitfelder
				case "time":
				$datums_auswertung = check_time($eingabewert);
				$eingabe2lesen[$k] = $datums_auswertung["Deutsch"];
				break;
				
				// Pruefe Datumsfelder
				case "float":
				$datums_auswertung = check_float($eingabewert);
				$eingabe2lesen[$k] = $datums_auswertung["Deutsch"];
				break;
				
				// Pruefe Zahlenfelder
				case "zahlenfeld":
				$mt_auswertung = check_integer($eingabewert, 1, 36);
				$eingabe2lesen[$k] = $mt_auswertung["Deutsch"];
				break;
				
				// Sonstige Felder
				case "titel":
				case "listentitel":
				case "text1":
				case "webfreigabe":
				break;
				
				default:
				break;
			}
		}
	}
	return $eingabe2lesen;
}

if (!function_exists("get_seitenbereichsdaten")) {
function get_seitenbereichsdaten($srv, $fld = "") {
	global $_TABLE;
	global $_TBLKEY;
	global $error;
	global $connid;
	$re = false;
	if (!$fld) $fld = $_TBLKEY["cms_bereiche"];
	
	$SQL = "SELECT * FROM ".$_TABLE["cms_bereiche"]." \n";
	$SQL.= " WHERE $fld = \"".$srv."\" \n";
	$SQL.= " LIMIT 1";
	$r = MyDB::query($SQL, $connid);
	if ($r) {
		if (MyDB::num_rows($r)) {
			$re = MyDB::fetch_array($r, MYSQL_ASSOC);
		}
		MyDB::free_result($r);
	}
	return $re;
}}


if (!function_exists("get_menu_bySrv")) {
function get_menu_bySrv($srv) {
	return get_seitenbereichsdaten($srv, "srv");
}}

function get_seitenbereichsdaten_bySrv($srv) {
	return get_seitenbereichsdaten($srv, "srv");
}

function get_seitenbereichsdaten_byId($id) {
	global $_TBLKEY;
	return get_seitenbereichsdaten($id, $_TBLKEY["cms_bereiche"]);
}

function save_menu($_DATEN, &$_FLDCONF, &$SAVE_ERROR) {
	global $_TABLE;
	global $_TBLKEY;
	global $SAVE_ERROR;
	$keyname = $_TBLKEY["cms_bereiche"];
	
	//echo "<pre>".print_r($_DATEN,1)."</pre>\n";
	
	$SAVE_MODE = (isset($_DATEN[$keyname]) && $_DATEN[$keyname]) ? "UPDATE" : "INSERT";
	$FLD_SETS = array();
	
	if ($SAVE_MODE == "UPDATE") {
		$_SavedData = get_menu($_DATEN[$keyname]);
		if (!empty($_SavedData) && $_SavedData["ordnungszahl"] != $_DATEN["ordnungszahl"]) {
			$posUpdate = set_position($_SavedData, $_DATEN["ordnungszahl"]);
		}
	}
	
	foreach($_FLDCONF as $k =>$v) {
        
        $ftyp = split_type_attr($v["type"]); // returns array: "type" => type, "attr" =>attribute/Laenge
        
        if ($SAVE_MODE == "INSERT") {
            if ((!isset($_DATEN[$k]) || empty($_DATEN[$k])) && strlen($v["default"])) {
                $_DATEN[$k] = $v["default"];
            }
        }
		// echo "#".__LINE__." $k type:".$v["type"]." ftyp:".$ftyp["type"]." v[default]:".$v["default"]." _DATEN[$k]:".$_DATEN[$k]." <br>\n";
        
		switch($ftyp["type"]) {
			
            case "created":
			if ($SAVE_MODE == "INSERT") {
				$FLD_SETS[count($FLD_SETS)] = "`".$k."` = NOW()";
			}
			break;
			
			case "modified":
			$FLD_SETS[count($FLD_SETS)] = "`".$k."` = NOW()";
			break;
			
			case "key":
			break;
			
			case "int":
			if (isset($_DATEN[$k]) && strlen($_DATEN[$k])) {
				$FLD_SETS[count($FLD_SETS)] = "`".$k."` = ".intval($_DATEN[$k]);
			} else {
				$FLD_SETS[count($FLD_SETS)] = "`".$k."` = NULL";
			}
			break;
			
			case "set":
			// echo "#".__LINE__." ".basename(__FILE__)." ".$ftyp["type"]." _DATEN[$k]:".print_r($_DATEN[$k],1)."<br>\n";
			if (isset($_DATEN[$k]) && is_array($_DATEN[$k])) $_DATEN[$k] = implode(",",$_DATEN[$k]);
			if (isset($_DATEN[$k]) && strlen($_DATEN[$k])) {
				$FLD_SETS[count($FLD_SETS)] = "`".$k."` = \"".MyDB::escape_string($_DATEN[$k])."\"";
			} else {
				$FLD_SETS[count($FLD_SETS)] = "`".$k."` = NULL";
			}
			break;
			
			default:
			//echo "#".__LINE__." ".basename(__FILE__)." set _DATEN[$k]:".print_r($_DATEN[$k],1)."<br>\n";
			if (isset($_DATEN[$k]) && strlen($_DATEN[$k])) {
				switch(gettype($_DATEN[$k])) {
					case "array":
					$FLD_SETS[count($FLD_SETS)] = " `".$k."` = \"".addslashes(implode(",",$_DATEN[$k]))."\"";
					break;
					
					default:
					if (is_scalar($_DATEN[$k])) {
						$FLD_SETS[count($FLD_SETS)] = " `".$k."` = \"".addslashes($_DATEN[$k])."\"";
					}
				}
			} else {
				$FLD_SETS[count($FLD_SETS)] = "`".$k."` = NULL";
			}
			break;
		}
	}
	
	$SQL = "$SAVE_MODE `".$_TABLE["cms_bereiche"]."` SET \n";
	$SQL.= implode(",\n", $FLD_SETS)."\n";
	if ($SAVE_MODE == "UPDATE") {
		$SQL.= "WHERE `$keyname` = ".$_DATEN[$keyname]."";
	}
	MyDB::query($SQL);
	//echo "<pre>#".__LINE__." ".basename(__FILE__)." QUERY:".fb_htmlEntities($SQL)."</pre>\n";
	if (!MyDB::error()) {
		$id = ($SAVE_MODE == "INSERT") ? MyDB::insert_id() : $_DATEN[$keyname];
		return $id;
	} else {
		$SAVE_ERROR = "<pre>#".__LINE__." ".basename(__FILE__)." MYSQL: ".MyDB::error()."\n";
		$SAVE_ERROR.= "QUERY: ".fb_htmlEntities($SQL)."</pre>\n";
	}
	return false;
}

function edit_flag($id, $feld, $wert) {
	global $_TABLE;
	global $_TBLKEY;
	global $error;
	$SQL = "UPDATE ".$_TABLE["cms_bereiche"]." SET \n";
	$SQL.= " `$feld` = \"".addslashes($wert)."\" \n";
	$SQL.= " WHERE `".$_TBLKEY["cms_bereiche"]."` = $id";
	MyDB::query($SQL);
	if (!MyDB::error()) {
		return true;
	} else {
		$error.= "#".__LINE__." Der Wert &quot;$wert&quot; f�r $feld konnte nicht gesetzt werden!<br>\n";
		$error.= "DB-Error:".MyDB::error()." <br>\n";
		$error.= "DB-QUERY:".fb_htmlEntities($SQL)." <br>\n";
		echo $error;
		return false;
	}
}

function kill_menu_bySrv($srv) {
	global $_TABLE;
	global $_TBLKEY;
	global $error;
	$SQL = "DELETE FROM ".$_TABLE["cms_bereiche"]."\n";
	$SQL.= " WHERE `seitenbereich` = \"".addslashes($srv)."\" LIMIT 0,1";
	MyDB::query($SQL);
	if (!MyDB::error()) {
		return true;
	} else {
		$error.= "#".__LINE__." Das Men� mit der internen Benennung $srv konnte nicht geloescht werden!<br>\n";
		$error.= "DB-Error:".MyDB::error()." <br>\n";
		return false;
	}
}

function kill_menu_byId($id, $_DATA) {
	global $_TABLE;
	global $_TBLKEY;
	global $error;
	
	$SQL = "UPDATE ".$_TABLE["cms_bereiche"]." SET \n";
	$SQL.= " parentid = ".$_DATA["parentid"]." \n";
	$SQL.= " WHERE parentid = ".$_DATA["id"];
	MyDB::query($SQL);
	
	if (!MyDB::error()) {
		$SQL = "DELETE FROM ".$_TABLE["cms_bereiche"]."\n";
		$SQL.= " WHERE `".$_TBLKEY["cms_bereiche"]."` = $id";
		MyDB::query($SQL);
	}
	
	if (!MyDB::error()) {
		return true;
	} else {
		$error.= "#".__LINE__." Das Men� mit der ID $id konnte nicht geloescht werden!<br>\n";
		$error.= "DB-Error:".MyDB::error()." <br>\n";
		$error.= "DB-QUERY:".fb_htmlEntities($SQL)." <br>\n";
		return false;
	}
}

function set_position($MenueData, $setPos) {
	// echo "#".__LINE__." ".__FUNCTION__."(\$MenueData:".$MenueData.", setPos:$setPos)<br>\n";
	global $_TABLE;
	global $_TBLKEY;
	global $error;
	
	$keyName = $_TBLKEY["cms_bereiche"];
	$arrPos  = array();
	$arrIds  = array();
	$recount = false;
	$errText = "";
	
	$SQL = "SELECT ".$keyName.", ordnungszahl FROM ".$_TABLE["cms_bereiche"]." \n";
	$SQL.= " WHERE parentid = \"".$MenueData["parentid"]."\" \n";
	$SQL.= " ORDER BY ordnungszahl, name \n";
	$r = MyDB::query($SQL);
	if ($r) {
		$n = MyDB::num_rows($r);
		$lastPos = $n;
		for ($i = 0; $i < $n; $i++) {
			$_e = MyDB::fetch_array($r);
			$arrIds[$i] = $_e[$keyName];
			$arrPos[$i] = $_e["ordnungszahl"];
			if ($recount == false && strval($i+1) != $_e["ordnungszahl"]) {
				$recount = true;
			}
		}
		MyDB::free_result($r);
		
		if ($recount == true) {
			// echo "#".__LINE__." ".__FUNCTION__." recount:true<br>\n";
			for ($i = 0; $i < count($arrIds); $i++) {
				$SQL = "UPDATE ".$_TABLE["cms_bereiche"]." SET \n";
				$SQL.= " ordnungszahl = \"".strval($i+1)."\" \n";
				$SQL.= " WHERE ".$keyName." = \"".addslashes($arrIds[$i])."\"";
				MyDB::query($SQL);
				
				if (MyDB::error()) {
					$errText = "<pre>#".__LINE__." MYSQL:".fb_htmlEntities(MyDB::error())."\n";
					$errText.= "QUERY:".fb_htmlEntities($SQL)."</pre>\n";
					$error.= $errText;
				} else {
					// echo "#".__LINE__." ".__FUNCTION__." UPDATE id:".$arrIds[$i].", pos:".($i+1)." <br>\n";
					$arrPos[$i] = $i+1;
				}
			}
		} else {
			// echo "#".__LINE__." ".__FUNCTION__." recount:false<br>\n";
		}
		
		if ($errText == "") {
			
			$r_t = array_search($MenueData[$keyName], $arrIds);
			$r_id = $arrIds[$r_t];
			$r_pos = $arrPos[$r_t];
			// echo "#".__LINE__." ".__FUNCTION__." array_search(".$MenueData[$keyName].", $arrIds), r_id:$r_id, r_pos:$r_pos <br>\n";
			
			
			if (is_int($r_t)) $body_content.= $_ArtikelTxt[$r_t];
			if (is_int($r_t)) {
				$oldPos = intval($arrPos[$r_t]);
				
				switch($setPos) {
					case "first":
					$newPos = 1;
					break;
					
					case "higher":
					$newPos = ($oldPos > 1 ? $oldPos - 1 : 1);
					break;
					
					case "lower":
					$newPos = ($oldPos < $lastPos ? $oldPos + 1 : $lastPos);
					break;
					
					case "last":
					$newPos = $lastPos;
					break;
					
					default:
					if (strval(intval($setPos)) == $setPos) {
						$newPos = intval($setPos);
					} else {
						$error.= "Ung�ltige Positionsangabe: $setPos!<br>\n";
						return false;
					}
				}
				
				// echo "#".__LINE__." ".__FUNCTION__." oldPos:$oldPos, newPos:$newPos <br>\n";
				if ($newPos == $oldPos) {
					// echo "#".__LINE__." ".__FUNCTION__." oldPos:$oldPos, newPos:$newPos <br>\n";
					return true;
				} else {
					$SQL = "UPDATE ".$_TABLE["cms_bereiche"]." SET \n";
					$SQL.= " ordnungszahl = \"".addslashes($newPos)."\" \n";
					$SQL.= " WHERE ".$keyName." = \"".addslashes($MenueData[$keyName])."\"";
					MyDB::query($SQL);
					// echo "<pre>#".__LINE__." Affected:".MyDB::affected_rows()." QUERY:".fb_htmlEntities($SQL)."</pre>\n";
					if (!MyDB::error()) {
						if ($newPos > $oldPos) {
							$SQL = "UPDATE ".$_TABLE["cms_bereiche"]." SET \n";
							$SQL.= " ordnungszahl = ordnungszahl-1 \n";
							$SQL.= " WHERE ordnungszahl > $oldPos AND ordnungszahl <= $newPos \n";
						} else {
							$SQL = "UPDATE ".$_TABLE["cms_bereiche"]." SET \n";
							$SQL.= " ordnungszahl = ordnungszahl+1 \n";
							$SQL.= " WHERE ordnungszahl < $oldPos AND ordnungszahl >= $newPos \n";
						}
						$SQL.= " AND $keyName != \"".addslashes($MenueData[$keyName])."\" \n";
						$SQL.= " AND parentid = \"".addslashes($MenueData["parentid"])."\" ";
						MyDB::query($SQL);
						
						if (!MyDB::error()) {
							return true;
						} else {
							$errText = "<pre>#".__LINE__." MYSQL:".fb_htmlEntities(MyDB::error())."\n";
							$errText.= "QUERY:".fb_htmlEntities($SQL)."</pre>\n";
							$error.= $errText;
						}
						
					} else {
						$errText = "<pre>#".__LINE__." MYSQL:".fb_htmlEntities(MyDB::error())."\n";
						$errText.= "QUERY:".fb_htmlEntities($SQL)."</pre>\n";
						$error.= $errText;
					}
				}
			}
		}
	}
	return false;
}
