<?php


$monatStrArr = array("",
	"Jan",
	"Feb",
	"M&auml;rz",
	"April",
	"Mai",
	"Juni",
	"Juli",
	"Aug",
	"Sep",
	"Okt",
	"Nov",
	"Dez");

$monatStrLongArr = array("",
	"Januar",
	"Februar",
	"M&auml;rz",
	"April",
	"Mai",
	"Juni",
	"Juli",
	"August",
	"September",
	"Oktober",
	"November",
	"Dezember");

function classids2Names($csvClassIds) {
	global $_TABLE;
	global $error;
	global $clError;

	if (!$csvClassIds) return false;

	$_Classids2Name = array();
	$SQL = "SELECT classid, printname, parentclassid FROM ".$_TABLE["rubriken"]." \n";
	$SQL.= " WHERE classid IN (".$csvClassIds.")";
	$r = MyDB::query($SQL);
	if ($r) {
		$n = MyDB::num_rows($r);
		for ($i = 0; $i < $n; $i++) {
			$_e = MyDB::fetch_array($r, MYSQL_ASSOC);
			$_Classids2Name["cid:".$_e["classid"]] = $_e;
		}
		MyDB::free_result($r);
		return $_Classids2Name;
	} else {
		echo "<pre>#".__LINE__." ERR:".MyDB::error()."\n";
		echo "QUERY: ".fb_htmlEntities($SQL)."</pre>\n";
	}
	return false;
}

function get_pathsByClassid($ClassidPath) {
	global $error;
	global $clError;

	$clError = "";
	$_CP = (is_array($ClassidPath)) ? $ClassidPath : explode(";", $ClassidPath);
	for($i = count($_CP)-1; $i >= 0; $i--) if (!trim($_CP[$i])) unset($_CP[$i]);
	ksort($_CP);
	$_CP_CID = array();
	$_CP2NP = array();
	$csvClassIds = "";
	for ($i = 0; $i < count($_CP); $i++) {
		$_CP[$i] = trim($_CP[$i]);
		if ($_CP[$i]) {
			if (substr($_CP[$i], 0, 1) == "»") {
				$_CP[$i] = substr($_CP[$i], 1);
			}
			if (substr($_CP[$i], -1) == ";") {
				$_CP[$i] = substr($_CP[$i], 0, -1);
			}
			if ($_CP[$i]) {
				$j = count($_CP_CID);
				if ($csvClassIds) $csvClassIds.= ",";
				$_CP_CID[$j] = explode(" ->", $_CP[$i]);
				$csvClassIds.= implode(",", $_CP_CID[$j]);
			}
		}
	}

	$_Classids2Name = classids2Names($csvClassIds);
	if (!$_Classids2Name) {
		return false;
	}

	for ($i = 0; $i < count($_CP_CID); $i++) {
		$_CP2NP[$i] = ""; // Pfad mit Namen
		$_CP[$i] = "";    // Pfad mit Classids
		$pcid = 0;        // ParenClassId
		$pcname = 0;        // ParenClassName
		for ($j = 0; $j < count($_CP_CID[$i]); $j++) {
			$cid = $_CP_CID[$i][$j];

			if (isset($_Classids2Name["cid:".$cid])
				&& $_Classids2Name["cid:".$cid]["parentclassid"] == $pcid) {

				$cname = $_Classids2Name["cid:".$cid]["printname"];
				if ($j == 0) {
					$_CP2NP[$i] = "»";
					$_CP[$i] = "»";
				} else {
					$_CP2NP[$i].= " ->";
					$_CP[$i].= " ->";
				}

				$_CP2NP[$i].= $cname;
				$_CP[$i].= $cid;

				$pcid = $cid;
				$pcname = $cname;
			} else {
				echo "#".__LINE__." ".basename(__FILE__)." : Der Deskriptor $cname[$cid] ist kein Kindelement von $pcname[$pcid]<br>\n";
				if ($j) {
					$_CP2NP[$i].= ";";
					$_CP[$i].= ";";
				}
				break;
			}

			if ($j + 1 == count($_CP_CID[$i]) ) {
				$_CP2NP[$i].= ";";
				$_CP[$i].= ";";
			}
		}
	}
	return array(
		"NP" => $_CP2NP,
		"CP" => $_CP
	);
}

function set_formVars($cmscontent, &$_VF, $_Fehlerfelder = array()) {
	global $pflichtfeldMarke;
	reset($_VF);
	while(list($k, $v) = each ($_VF)) {
		// Setze Feldlabel
		$cmscontent = str_replace($v[5], $v[0], $cmscontent);

		// Setze Pflichteingabemarken
		if ($v[2]) $cmscontent = str_replace($v[6], $pflichtfeldMarke, $cmscontent);

		// Setze Marken bei Feldern mit Eingabefehlern
		if (isset($_Fehlerfelder[$k])) {
			$cmscontent = str_replace($v[7], "!", $cmscontent);
			$cmscontent = str_replace("class=\"$k\"", "class=\"inputErr\"", $cmscontent);
		}
	}
	reset($_VF);
	return $cmscontent;
}

// <!-- {options_classid2deskriptor} -->
function get_options_cid2dsk($_CP, $_NP) {
	$options_cid2dsk = "";
	if (count($_NP) == count($_CP)) {
		for ($i = 0; $i < count($_CP); $i++) {
			$options_cid2dsk.= "<option value=\"".$_CP[$i]."\">".$_NP[$i]."</option>\n";
		}
	} else {
		$error.= "Unstimmige Wertepaare Classids(".count($_CP).") und Classnamen(".count($_NP).")<br>\n";
	}
	return $options_cid2dsk;
}


function get_tplvars(&$eingabe, &$lesen, &$_TplVars) {
	reset($eingabe);
	reset($lesen);

	while(list($k, $v) = each($eingabe)) {
		//echo "#".__LINE__." _TplVars eingabe[$k] = $v<br>\n";
		switch($k) {

			case "timer_wochentage":
				if (is_array($v)) $v = implode(",", $v);
				$_tmp = explode(",", $v);
				for ($ti = 0; $ti < count($_tmp); $ti++) {
					$_TplVars["chck_".$k."=\"".$_tmp[$ti]."\""] = "checked=\"true\" selected=\"true\"";
				}
				$_TplVars["%eingabe[".$k."]%"] = $v;
				break;

			case "webfreigabe":
			case "antrailern":
			case "linkzumvolltext":
			case "lang":
				$_TplVars["chck_".$k."=\"".$v."\""] = "checked=\"true\" selected=\"true\"";
				$_TplVars["%eingabe[".$k."]%"] = &$eingabe[$k];
				break;

			case "options_classid2deskriptor":
				$_TplVars["<!-- {options_classid2deskriptor} -->"] = &$eingabe[$k];
				break;

			default:
				$_TplVars["%eingabe[".$k."]%"] = fb_htmlEntities(stripslashes($eingabe[$k]));
		}
	}

	while(list($k, $v) = each($lesen)) {
		// echo "#".__LINE__." _TplVars lesen[$k] = $v<br>\n";
		$_TplVars["%lesen[".$k."]%"] = &$lesen[$k];
	}

	reset($eingabe);
	reset($lesen);
}

function eingabe2lesen($eingabe,$validFields) {
	$eingabe2lesen = $eingabe;
	/*
	case "datum":
	case "titel":
	case "listentitel":
	case "text1":
	case "webfreigabe":
	*/

	while(list($k,$v) = each($validFields)) {
		$eingabewert = $eingabe[$k];
		// Test auf Pflichtfeld, wenn Ja prüfe ob eine Eingabe getätigt wurde
		if (trim($eingabewert)) {
			switch($k) {
				// Pruefe Datumsfelder
				case "datum":
					$datums_auswertung = check_date($eingabewert);
					$eingabe2lesen[$k] = $datums_auswertung["Deutsch"];
					break;

				case "timer_datumvon":
				case "timer_datumbis":
					$datums_auswertung = check_date($eingabewert);
					$eingabe2lesen[$k] = $datums_auswertung["Deutsch"];
					break;

				case "timer_zeitvon":
				case "timer_zeitbis":
					$datums_auswertung = check_time($eingabewert);
					$eingabe2lesen[$k] = $datums_auswertung["Deutsch"];
					break;

				case "timer_wochentage":
					if (is_array($eingabewert)) {
						$eingabe2lesen[$k] = implode(",", $eingabewert);
					}

				// Pruefe Zahlenfelder
				case "zahlenfeld":
					$mt_auswertung = check_integer($eingabewert,1,36);
					$eingabe2lesen[$k] = $mt_auswertung["Deutsch"];
					break;

				// Sonstige Felder
				case "titel":
				case "listentitel":
				case "text":
				case "webfreigabe":
					break;

				default:
					break;
			}
		}
	}
	return $eingabe2lesen;
}

function check_input($eingabe, $validFields) {
	global $maintbl;
	global $editTableKey;
	global $srv;
	global $tbl_mainID;

	$check_input = array("eingabe" => $eingabe,
		"lesen" => $eingabe,
		"Fehlerfelder" => array(),
		"Error" => "");

	$setLinkZumVolltext = false;

	/*
	if ($eingabe["linkzumvolltext"] == "Ja") {
		$setLinkZumVolltext = true;
	} elseif($eingabe["linkzumvolltext"] == "Auto") {
		if ($eingabe["volltext"] != "" && $eingabe["volltext"] != $_VF["volltext"[1]) {
			$setLinkZumVolltext = true;
		}
	}*/

	/*
	case "datum":
	case "titel":
	case "listentitel":
	case "text1":
	case "webfreigabe":
	*/

	$leereFelder = "";
	$pruefFelder = "";
	$uniqueFelder = "";
	while(list($k,$v) = each($validFields)) {
		if (isset($eingabe[$k])) {
			$eingabewert = $eingabe[$k];
		} else {
			$eingabewert = "";
			$check_input["eingabe"][$k] = "";
			$check_input["lesen"][$k] = "";
		}

		// echo "#278 $k : $eingabewert <br>\n";
		// Test auf Pflichtfeld, wenn Ja prüfe ob eine Eingabe getätigt wurde
		if ($v[2] && trim($eingabewert) == "") {
			$leereFelder.="<li>".$v[0]."</li>";
			$check_input["Fehlerfelder"][$k] = "Fehlende Eingabe";
		}

		// Eingabe prüfen, wenn erforderlich
		// echo "#".__LINE__." ".__FILE__." k:$k gettype(eingabewert):".gettype($eingabewert)." gettype(v[11]):".gettype($v[11])."<br>n";
		if (!empty($eingabewert) && $v[11]) {
			switch($k) {
				// Pruefe Datumsfelder
				case "datum":
				case "timer_datumvon":
				case "timer_datumbis":
					$datums_auswertung = check_date($eingabewert);
					if ($datums_auswertung["Error"]) {
						$check_input["Fehlerfelder"][$k]=$datums_auswertung["Error"];
						$pruefFelder.="<li>".$v[0].": ".$datums_auswertung["Error"]."</li>\n";
						// echo "<br>************<br>\npruefFelder: $pruefFelder <br>\n";
					}
					$eingabe[$k] = $datums_auswertung["Datum"];
					$check_input["lesen"][$k] = $datums_auswertung["Deutsch"];
					break;

				// Pruefe Zeitfelder (Tageszeit / Uhrzeit)
				case "timer_zeitvon":
				case "timer_zeitbis":
					$datums_auswertung = check_time($eingabewert);
					if ($datums_auswertung["Error"]) {
						$check_input["Fehlerfelder"][$k]=$datums_auswertung["Error"];
						$pruefFelder.="<li>".$v[0].": ".$datums_auswertung["Error"]."</li>\n";
						// echo "<br>************<br>\npruefFelder: $pruefFelder <br>\n";
					}
					$eingabe[$k] = $datums_auswertung["Zeit"];
					$check_input["lesen"][$k] = $datums_auswertung["Deutsch"];
					break;

				// Pruefe Emailfeld
				case "email":
					$email_auswertung = fcheck_email($eingabewert);
					if ($email_auswertung["Error"]) {
						$check_input["Fehlerfelder"][$k]=$email_auswertung["Error"];
						$pruefFelder.="<li>".$v[0].": ".$email_auswertung["Error"]."</li>\n";
						// echo "<br>************<br>\npruefFelder: $pruefFelder <br>\n";
					}
					break;

				case "media_src":
					$check_input["lesen"][$k] = "<a href=\"".$eingabewert."\" target=_blank>$eingabewert</a>";
					break;

				case "media_params":
					break;

				// Pruefe Zahlenfelder
				case "Pruefe Zahlenfelder Wertebereich":
					$mt_auswertung = check_integer($eingabewert,1,36);
					if ($mt_auswertung["Error"]!="") {
						$check_input["Fehlerfelder"][$k]=$mt_auswertung["Error"];
						$pruefFelder.="<li>".$v[0].": ".$mt_auswertung["Error"]."</li>";
					}
					$check_input["lesen"][$k] = $mt_auswertung["Deutsch"];
					break;

				case "timer_wochentage":
					if (is_array($eingabewert)) {
						$check_input["eingabe"][$k] = implode(",", $eingabewert);
						$check_input["lesen"][$k] = $check_input["eingabe"][$k];
					}
					break;

				case "classid":
					$_Paths = get_pathsByClassid($eingabewert);
					if (isset($_Paths["CP"]) && isset($_Paths["NP"])) {
						// <!-- {options_classid2deskriptor} -->
						$options_classid2deskriptor = get_options_cid2dsk($_Paths["CP"], $_Paths["NP"]);
						//echo "#".__LINE__." classid:$eingabewert<br>\n";
						//echo "#".__LINE__." options_classid2deskriptor:".fb_htmlEntities($options_classid2deskriptor)."<br>\n";
						$check_input["eingabe"]["classid"] = implode("\n", $_Paths["CP"]);
						$check_input["eingabe"]["deskriptor"] = implode("\n", $_Paths["NP"]);
						$check_input["lesen"]["classid"] = nl2br($check_input["eingabe"]["classid"]);
						$check_input["lesen"]["deskriptor"] = nl2br($check_input["eingabe"]["deskriptor"]);
						$check_input["eingabe"]["options_classid2deskriptor"] = $options_classid2deskriptor;
					}
					break;

				case "deskriptor":
					break;

				case "listentitel":
					if ($setLinkZumVolltext) {
						$check_input["lesen"]["listentitel"].= " ";
						$check_input["lesen"]["listentitel"].="<a href='index.php?";
						$check_input["lesen"]["listentitel"].="&area=ctv";
						$check_input["lesen"]["listentitel"].="&$editTableKey=".$eingabe[$editTableKey];
						$check_input["lesen"]["listentitel"].="&srv=".$srv."'>".$eingabe["listentitel"]."</a>";
					}
					break;

				case "listentext":
					if ($setLinkZumVolltext) {
						if (!isset($check_input["lesen"]["listentext"]))
							$check_input["lesen"]["listentext"]="";
						$check_input["lesen"]["listentext"].= " ";
						$check_input["lesen"]["listentext"].="<a href='index.php?";
						$check_input["lesen"]["listentext"].="&area=ctv";
						$check_input["lesen"]["listentext"].="&$editTableKey=".$eingabe[$editTableKey];
						$check_input["lesen"]["listentext"].="&srv=".$srv."'>... mehr</a>";
					}
					break;

				// Sonstige Felder
				case "datum":
				case "titel":
				case "listentitel":
				case "text1":
				case "webfreigabe":
					break;

			}
		}

		// Eingabe auf Eindeutigkeit prüfen, wenn erforderlich
		if (!empty($eingabewert) && $v[10] == true && $v[5]!=$tbl_mainID) {
			$SQL = "SELECT COUNT(*) FROM $maintbl";
			$SQL.= " WHERE ".$v[3]." LIKE \"$eingabewert\"";
			if (isset($eingabe[$tbl_mainID]) && $eingabe[$tbl_mainID]) {
				$SQL.= " AND NOT ".$tbl_mainID." = ".$eingabe[$tbl_mainID];
			}
			// echo "SQL: $SQL <br>\n";
			if (count_query($SQL)) {
				$check_input["Fehlerfelder"][$k]="Geben Sie eindeutigen Wert ein!";
				$uniqueFelder.= "<li>".$v[0]."</li>";
			}
		}
	}

	$check_input["Error"] = "";
	if ($leereFelder) {
		$check_input["Error"].= "<font color='#FF0000'><b>Es fehlen noch Angaben in folgenden Pflichteingabefeldern:</b></font><br>\n";
		$check_input["Error"].="<ul>".$leereFelder."</ul>";
	}

	if ($pruefFelder) {
		$check_input["Error"].= "Es wurden ungültige Werte in folgenden Normfeldern eingegeben:<br>\n";
		$check_input["Error"].=$pruefFelder."<br>";
	}

	if ($uniqueFelder) {
		$check_input["Error"].= "Bitte geben Sie andere eindeutige Werte ein:<br>\n";
		$check_input["Error"].=$uniqueFelder."<br>";
	}

	return array($check_input["eingabe"],
		$check_input["lesen"],
		$check_input["Fehlerfelder"],
		$check_input["Error"]);
}

function MyDB::save_input($eingabe, $validFields, $table, $tblkey, $modus, $benutzerdaten) {

$MyDB::save_input = array("insertID" => 0, "Fehlertext" => "");
	if (!$modus) {
		if (isset($eingabe[$tblKey]) && $eingabe[$tblKey]) $modus = "UPDATE";
		else $modus = "INSERT";
	}
	$modus = strtoupper($modus);
	$setSQL = "";

	$author = $benutzerdaten["vorname"]." ".$benutzerdaten["name"]."<uid:".$benutzerdaten["uid"]."/>";

	while(list($k,$v) = each($validFields)) {
		if ($v[3]) {
			if ($v[3] == $tblkey) {
				$configKey = $k;
				// echo "#".__LINE__." is_key:$k<br>\n";
			}
			if (isset($eingabe[$k])) {

				switch($k) {
					case "timer_datumvon":
					case "timer_datumbis":
					case "timer_zeitvon":
					case "timer_zeitbis":
					case "timer_wochentage":
						if ($eingabe[$k]) {
							if ($setSQL!="") $setSQL.=",\n";
							$setSQL.= $v[3]." = \"".addslashes(stripslashes($eingabe[$k]))."\"";
						} else {
							if ($setSQL!="") $setSQL.=",\n";
							$setSQL.= $v[3]." = NULL";
						}
						break;

					case "erstelltam":
					case "erstelltvon":
					case "bearbeitetam":
					case "bearbeitetvon":
					case "textplain":
						break;

					default:
						$isNull = false;
						if ($k == $tblkey) {
							if (!$eingabe[$k]) $isNull = true;
						}
						if ($setSQL!="") $setSQL.=",\n";
						$eingabe[$k] = stripslashes($eingabe[$k]);
						$setSQL.= $v[3]." = ".(!$isNull ? "\"".addslashes($eingabe[$k])."\"" : "NULL");
				}
			}
		}
	}

	if ($modus == "UPDATE" && (!isset($eingabe[$configKey]) || !$eingabe[$configKey])) {
		$MyDB::save_input["fehlertext"] = "Änderung kann ohne Primärschlüssel nicht";
		$MyDB::save_input["fehlertext"].= " abgespeichert werden!<br>";
	}

	if ($MyDB::save_input["fehlertext"] == "") {
		$plain_text = $eingabe["listentitel"]."\n";
		$plain_text.= $eingabe["listentext"]."\n";
		$plain_text.= $eingabe["titel"]."\n";
		$plain_text.= $eingabe["volltext"]."\n";
		$plain_text.= $eingabe["deskriptoren"]."";
		$setSQL.= ",\n text_plain = \"".addslashes(strip_tags(str_replace("<br>","\n",$plain_text)))."\", \n";

		if ($modus == "INSERT") {
			$setSQL.= "erstelltam = NOW(), \n";
			$setSQL.= "erstelltvon =\"".addslashes(stripslashes($author))."\", \n";
		}
		$setSQL.= "bearbeitetam = NOW(), \n";
		$setSQL.= "bearbeitetvon =\"".addslashes(stripslashes($author))."\" ";

		Switch($modus) {
			case "INSERT":
				$SQL = "INSERT INTO $table SET $setSQL";
				MyDB::query($SQL);
				if (!MyDB::error()) {
					$MyDB::save_input["insertID"] = MyDB::insert_id();
				} else {
					$MyDB::save_input["fehlertext"].=MyDB::error();
					$MyDB::save_input["fehlertext"].="<b>SQL</b>:\n$SQL<br>";
				}
				break;

			case "UPDATE":
				$SQL = "UPDATE $table SET $setSQL";
				$SQL.= " WHERE $tblkey = '".$eingabe[$configKey]."'";
				MyDB::query($SQL);
				if (MyDB::error()) {
					$MyDB::save_input["fehlertext"].=MyDB::error();
					$MyDB::save_input["fehlertext"].="<b>SQL</b>:\n$SQL<br>";
				}
				$MyDB::save_input["insertID"] = $eingabe[$configKey];
				break;
		}
		if (MyDB::error()) echo "#409 SQL:".fb_htmlEntities($SQL)."<br>\n";
	}

	return array($MyDB::save_input["insertID"],$MyDB::save_input["fehlertext"]);
}

function wordbr($string,$abst) {
	$wordbr = "";
	for ($i=0; $i<strlen($string); $i++) {
		if ($i && $i%$abst == 0) $wordbr.="<br>";
		$wordbr.=substr($string,$i,1);
	}
	return $wordbr;
}

function sort_multiArr($a,$b)
{
	global $multiArr_orderKey, $multiArr_orderDir, $multiArr_orderMethod;
	$x=0;
	$ak=$a[$multiArr_orderKey];
	$bk=$b[$multiArr_orderKey];
	if ($x) echo "multiArr_orderKey: $multiArr_orderKey, multiArr_orderDir: $multiArr_orderDir, multiArr_orderMethod: $multiArr_orderMethod <br>";
	if ($x) echo "ak: $ak <br>";
	if ($x) echo "bk: $bk <br>";
	switch(strtolower($multiArr_orderMethod))
	{
		case 'string':
			settype($ak,"String");
			settype($bk,"String");
			$ak=strtolower($ak);
			$bk=strtolower($bk);
			break;

		case 'numeric':
			settype($ak,"Double");
			settype($bk,"Double");
			break;
	}

	if ($ak==$bk) {
		if ($x) echo "vergleich: $ak == $bk <br>\n";
		return 0;
	}
	if (strtolower($multiArr_orderDir)=="desc") {
		$vergleich = ($ak>$bk)? -1:1;
		if ($x) echo "vergleich: $ak &gt; $bk = $vergleich <br>\n";
		return $vergleich;
	} else {
		$vergleich = ($ak<$bk)?-1:1;
		if ($x) echo "vergleich: $ak &lt; $bk = $vergleich <br>\n";
		return $vergleich;
	}
	if ($x) echo "&nbsp;<br>";
}

function xdate_diff($intervall,$datumBasis,$datumAbzug) {

	$test1 = check_date($datumBasis);
	$test2 = check_date($datumAbzug);
	if ($test1["Error"] || $test2["Error"]) {
		return false;
	}
	// Analyse datumBasis, Umwandlung in reinen Sekundenwert
	list($stunde,$minute,$sekunde) = array(0,0,0);
	list($datum,$zeit) = explode(" ",$datumBasis);
	list($jahr,$monat,$tag) = explode("-",$datum);
	if ($zeit) list($stunde,$minute,$sekunde) = explode(":",$zeit);
	$timeBasis = mktime($stunde,$minute,$sekunde,$monat,$tag,$jahr);
	// echo "$timeBasis = mktime($stunde,$minute,$sekunde,$monat,$tag,$jahr);<br>\n";

	// Analyse datumAbzug, Umwandlung in reinen Sekundenwert
	list($stunde,$minute,$sekunde) = array(0,0,0);
	list($datum,$zeit) = explode(" ",$datumAbzug);
	list($jahr,$monat,$tag) = explode("-",$datum);
	if ($zeit) list($stunde,$minute,$sekunde) = explode(":",$zeit);
	$timeAbzug = mktime($stunde,$minute,$sekunde,$monat,$tag,$jahr);
	// echo "$timeAbzug = mktime($stunde,$minute,$sekunde,$monat,$tag,$jahr);<br>\n";

	$timeDiff = $timeBasis - $timeAbzug;
	$vorzeichen = ($timeDiff>=0)?1:-1;

	switch(strtolower($intervall)) {
		case "Year":
		case "jahr":
			$jahre = $vorzeichen * abs(intval(100*$timeDiff/(3600*24*365))/100);
			return $jahre;
			break;

		case "month":
		case "monat":
			$monate = abs(intval(100*$timeDiff/(3600*24*30.5))/100);
			$monate*= $vorzeichen;
			return $monate;
			break;

		case "week":
		case "woche":
			$wochen = $vorzeichen * abs(intval(100*$timeDiff/(3600*24*7))/100);
			return $wochen;
			break;

		case "day":
		case "tag":
			$tage = $vorzeichen *  abs(intval(100*$timeDiff/(3600*24))/100);
			return $tage;
			break;

		case "hour":
		case "stunde":
			$stunden = $vorzeichen * abs(intval(100*$timeDiff/3600)/100);
			return $stunden;
			break;

		case "minute":
			$minuten = $vorzeichen * abs(intval(100*$timeDiff/60)/100);
			return $minuten;
			break;

		default:
			return $vorzeichen*$timeDiff;
			break;
	}
}

function daysOfMonth($monat,$jahr) {
	list($stunde,$minute,$sekunde,$tag) = array(0,0,0,1);

	if  ($monat != 12) {
		$monat++;
	} else {
		$jahr++;
		$monat = 1;
	}
	$daysOfMonth = date("d",mktime($stunde,$minute,$sekunde,$monat,$tag,$jahr)-7200);
	return $daysOfMonth;
}

// LISTENFUNKTIONEN: Sortieren

function recount_public_items() {
	global $srv;
	global $editTable;
	global $editTableKey;
	global $editTableOrd;
	$key2Ord = array();
	$SQL = "SELECT $editTableKey, $editTableOrd \n";
	$SQL.= " FROM $editTable \n";
	$SQL.= " WHERE webfreigabe LIKE 'Ja' \n";
	$SQL.= " AND seitenbereich LIKE \"".$srv."\" \n";
	$SQL.= " ORDER BY $editTableOrd ASC \n";

	$r = MyDB::query($SQL);
	if ($r) {
		$n = MyDB::num_rows($r);
		for ($i = 0; $i < $n; $i++) {
			$_e = MyDB::fetch_array($r, MYSQL_NUM);
			$key2Ord[$i] = $_e[0];
			echo "#".__LINE__." key:".$_e[0].", ord:".$_e[1]."<br>\n";
		}
		MyDB::free_result($r);
		for ($i = 0; $i < $n; $i++) {
			$SQL = "UPDATE $editTable SET \n";
			$SQL.= " $editTableOrd = '".strval($i+1)."' \n";
			$SQL.= " WHERE $editTableKey = '".$key2Ord[$i]."' \n";
			$SQL.= " AND seitenbereich LIKE \"".$srv."\" ";
			MyDB::query($SQL);
			if (MyDB::error()) {
				echo "<pre>#".__LINE__." MYSQL:".MyDB::error()." <br> \n";
				echo "#".__LINE__." QUERY:".fb_htmlEntities($SQL)." </pre> \n";
				return false;
			}
			echo "#".__LINE__." key:".$key2Ord[$i].", ord:".strval($i+1)."<br>\n";
		}
	} else {
		echo "<pre>#".__LINE__." MYSQL:".MyDB::error()." <br> \n";
		echo "#".__LINE__." QUERY:".fb_htmlEntities($SQL)." </pre> \n";
		return false;
	}
	return true;
}
if (isset($_GET["recountItems"]) && $_GET["recountItems"] == "1") {
	recount_public_items();
}

function count_public_items() {
	global $srv;
	global $editTable;
	$cnt = -1;
	$SQL = "SELECT COUNT(*) FROM $editTable";
	$SQL.= " WHERE webfreigabe LIKE 'Ja'";
	$SQL.= " AND seitenbereich LIKE \"".$srv."\" \n";
	$r = MyDB::query($SQL);
	if ($r) {
		list($cnt) = MyDB::fetch_array($r);
		MyDB::free_result($r);
	} else echo "#".__LINE__." ".MyDB::error()." <br> \n";
	return $cnt;
}

function render_pos_options($intAll, $intDefault = "") {
	$options = "";
	$selected = "";
	for($i = 1; $i <= $intAll; $i++) {
		if ($intDefault) {
			$selected = ($i != $intDefault) ? "" : "selected";
		}
		$options.= "\t<option x value=\"".$i."\" chck=\"$i\" $selected>".$i."</option>\n";
	}
	return $options;
}

for ($i=1; $i<=12; $i++) {
	// echo "Der ".$i.". Monat hat ".daysOfMonth($i,date("Y"))." Tage!<br>\n";
}
// echo "#".__LINE__." ".basename(__FILE__)." ".print_r(get_included_files(),1)."<br>\n";
?>
