<?php 

class checkType 
{
	function get_baseType($type) {
		if (preg_match("/int/", $type)) {
			$baseType = "int";
		} elseif(preg_match("/float|decimal/", $type)) {
			$baseType = "float";
		}  elseif(preg_match("/char/", $type)) {
			$baseType = "char";
		} elseif(preg_match("/text/", $type)) {
			$baseType = "text";
		} elseif(preg_match("/blob/", $type)) {
			$baseType = "blob";
		} else {
			$baseType = $type;
		}
		return $baseType;
	}
	
	function formatDataToRead($val, $type, $size) {
		$baseType = $this->get_baseType($type);
		switch($baseType) {
			case "int":
			if (is_numeric($val)) {
				return number_format(intval($val), 0, "", ".");
			}
			break;
			
			case "float":
			case "decimal":
			case "double":
			if (is_numeric($val)) {
				list(, $nachkommastellen) = explode(",", $size);
				$float = (double) $val; //100.55;
				$n = (int) $nachkommastellen; // 2;
				$komma = ",";
				$tsd = ".";
				// $re = number_format($float, $n, $komma, $tsd);
				return number_format($float, $n, $komma, $tsd);
			}
			break;
			
			case "email":
			return "<a href=\"mailto:$val\">$val</a>";
			break;
			
			case "date":
			if (count(explode("-", $val)) == 3) {
				list($y, $m, $d) = explode("-", $val);
				return "$d.$m.$y";
			} 
			break;
			
			case "time":
			return $val;
			break;
			
			case "datetime":
			$re = "";
			if (count(explode(" ", $val)) == 2) {
				list($date, $time) = explode(" ", $val);
				if (count(explode("-", $date)) == 3) {
					list($y, $m, $d) = explode("-", $date);
					return "$d.$m.$y $time";
				}
			}
			break;
			
			case "set":
			if (is_array($val)) return implode(", ", $val);
			break;
			
			default:
			// echo "#".__LINE__." baseType:".$baseType."<br>\n";
		}
		return nl2br($val);
	}
	
	// ( $this->arrInput[$fN], $fC["type"], $fC["size"], $fC["min"], $fC["max"], $err);
	function isValidType(&$val, &$type, &$size, &$min, &$max, &$err) {
			// echo "isValidType(val:|$val|, type:|$type|, size:|$size|, min:|$min|, max:|$max|, err:|$err) <br>\n";
			$baseType = $this->get_baseType($type);
			
			switch($baseType) {
				case "char":
				case "char":
				case "text":
				return $this->isValidStr($val, $size, $err);
				break;
				
				case "int":
				return $this->isInt($val, $size, $min, $max, $err);
				break;
				
				case "double":
				case "float":
				case "decimal":
				return $this->isFloat($val, $size, $min, $max, $err);
				break;
				
				case "datetime":
				return $re = $this->isDateTime($val, $size, $min, $max, $err);
				break;
				
				case "date":
				return $this->isDate($val, $size, $min, $max, $err);
				break;
				
				case "time":
				return $this->isTime($val, $size, $min, $max, $err);
				break;
				
				case "set":
				return $this->isInSet($val, $size, $min, $max, $err);
				break;
				
				case "enum":
				return $this->isInEnum($val, $size, $min, $max, $err);
				break;

                case "blob":
                case "file":
                    return true;
                    break;
			}
	}
	
	function isValidStr(&$val, &$size, &$err) {
		if (is_numeric($size) && strlen($val)>intval($size) ) {
			$err = "#".__LINE__.": Eingabe ist zu lang. Max. Zeichenlänge: $size!<br>\n";
		} else $err = "";
		return ($err === "");
	}
	
	function isInt(&$val, &$size, &$min, &$max, &$err) {
		$err = "";
		if ($val !== "") {
			if (is_numeric($val) && !preg_match("/[^0-9]/", $val)) {
				$int = intval($val);
				if (is_numeric($min) && intval($min) > $int ) {
					$err = "#".__LINE__.": Zahl ist kleiner als min. zulässig: $min!<br>\n";
				}
				if (is_numeric($max) && intval($max) < $int ) {
					$err = "#".__LINE__.": Zahl ist groesser als max. zulässig: $max!<br>\n";
				}
				if (!$err) $val = $int;
			} else $err = "#".__LINE__.": Das ist keine gültige Ganzzahl: $val!<br>\n";
		} else $err = "#".__LINE__.": Es wurde kein Wert für Int-Prüfung angegeben!<br>\n";
		return ($err === "");
	}
	
	function isFloat(&$val, &$size, &$min, &$max, &$err) {
		$err = "";
		if ($val !== "") {
			$strFloat = $val;
			// Entferne führende Nullen
			while(strlen($strFloat)> 1 && $strFloat[0] == "0" && preg_match("/\d/", $strFloat[1])) $strFloat = substr($strFloat, 1);
			
			// Teste auf float, wenn false führe Syntaxkorrektur für deutsche Schreibweise durch
			if (!is_numeric($val)) {
				$strFloat = strtr($strFloat, array("."=>"", ","=>"."));
			}
			
			// Test auf float inkl. evtll. korrigierte Schreibweise
			if (is_numeric($strFloat) ) {
				if (is_numeric($min) && floatval($min) > floatval($strFloat) ) {
					$err = "Zahl ist kleiner als min. zulässig: $min!<br>\n";
				}
				if (is_numeric($max) && floatval($max) < floatval($strFloat) ) {
					$err = "Zahl ist groesser als max. zulässig: $max!<br>\n";
				}
				$arrS = explode(",", $size);
				$arrN = explode(".", $strFloat);
				if (is_numeric($arrS[0]) && strlen($arrN[0]) > intval($arrS[0]) ) {
					$err = "Der Vorkommawert hat mehr als {$arrS[0]} size:$size implode(',', arrS):".implode(",", $arrS)." Stellen!<br>\n";
				}
				
				if (isset($arrS[1]) && isset($arrN[1]) && is_numeric($arrS[1]) && strlen($arrN[1]) > intval($arrS[1]) ) {
					$err = "Der Nachkommawert hat mehr als {$arrS[0]} Stellen!<br>\n";
				}
				if (!$err) {
					$val = floatval($strFloat);
				}
			}
		} else $err = "#".__LINE__.": Es wurde kein Wert für Fliesszahl-Prüfung angegeben!<br>\n";
		return ($err === "");
	}
	
	function date2mysqlform($val, &$mysqldate, &$err) {
		if ($val == "0000-00-00") { $mysqldate = $val; return true; }
		if ( count(explode(".", $val)) == 3 || count(explode("-", $val)) == 3) {
			if (strpos($val, ".")) list($d, $m, $y) = explode(".", $val);
			else list($y, $m, $d) = explode("-", $val);
			if (is_numeric($m) && is_numeric($d) && is_numeric($y) && checkdate($m, $d, $y)) {
				if (intval($d) < 10) $d = "0".intval($d);
				if (intval($m) < 10) $m = "0".intval($m);
				$mysqldate = "$y-$m-$d";
				return true;
			} else {
				$err.= "Ungültige Datumsangabe: $val (Erwartetes Format: JJJJ-MM-TT oder TT.MM.JJJJ)<br>\n<br>\n";
			}
		} else {
			$err.= "Ungültiges Datumsformat: $val (Erwartetes Format: JJJJ-MM-TT oder TT.MM.JJJJ)<br>\n";
		}
		return false;
	}
	
	function isDate(&$val, &$size, &$min, &$max, &$err) {
		$err = "";
		$errMin = "";
		$errMax = "";
		$mysqldate = "";
		
		// Konvertiere Datum in Standard-Mysql-Format
		if ($this->date2mysqlform($val, $mysqldate, $err)) $val = $mysqldate;
		if ($min && $this->date2mysqlform($min, $mysqldate, $errMin)) $min = $mysqldate;
		if ($max && $this->date2mysqlform($max, $mysqldate, $errMax)) $max = $mysqldate;
		
		if ($errMin) $err.= "Fehler in Bereichsangabe für kleinstmögliches Datum: $min -> $errMin<br>\n";
		if ($errMax) $err.= "Fehler in Bereichsangabe für grösstmögliches Datum: $max -> $errMax<br>\n";
		if ($err) return false;
		
		if ($min && $val < $min) $err.= "Datum '$val' ist zu klein. Minimal zulässig ist $min!<br>\n";
		if ($max && $val > $max) $err.= "Datum '$val' ist zu gross. Maximal zulässig ist $max!<br>\n";
		if ($err) return false;
		
		return true;
	}
	
	function isTime(&$val, &$size, &$min, &$max, &$err) {
		$err = "";
		$pName = array("Stunde", "Minute", "Sekunde");
		$parts = explode(":", $val);
		if ($val !== "" && count($parts) < 4) {
			for ($i = 0; $i < 3; $i++) {
				if (!isset($parts[$i])) {
					$parts[$i] = "00";
					continue;
				}
				if (!is_numeric($parts[$i])) {
					$err = "#".__LINE__." Zeitangabe enthält ungültige Zeichen. Formatbeispiel: 00:02:30!<br>\n";
					break;
				}
				switch($i) {
					case 0:
					if (intval($parts[$i]) > 23) {
						$err = "#".__LINE__." Unzulässige Zeitangabe. Die Stunde {$parts[$i]} überschreitet 23!<br>\n";
					}
					break;
					
					default:
					if (intval($parts[$i]) > 59) {
						$err = "#".__LINE__." Unzulässige Zeitangabe. Die {$pName[$i]} {$parts[$i]} überschreitet 59!<br>\n";
					}
				}
				if (strlen($parts[$i]) == 1) $parts[$i] = "0".$parts[$i];
			}
			if (!$err) {
				$val = implode(":", $parts);
				if (strlen($min) && $min > $val) {
					$err = "Die Zeitangabe $val ist kleiner als min. zulässig: $min!<br>\n";
				}
				if (strlen($max) && $max < $val) {
					$err = "Die Zeitangabe $val ist größer als min. zulässig: $max!<br>\n";
				}
			}
		} else {
			$err = "Unzulässiges Zeitformat: hh:mm:ss !<br>\n";
		}
		return ($err === "");
	}
	
	function isDateTime(&$val, &$size, &$min, &$max, &$err) {
		$err = "";
		$stdErr = "Ungültiges Datetime-Format! Geben Sie den Wert so JJJJ-MM-TT ss:mm:ss oder si TT.MM.JJJJ ss:mm:ss an!<br>\n";
		if ($val !== "") {
			$parts = explode(" ", $val);
			if (count(explode(" ", $val))) {
				list($date, $time) = explode(" ", $val);
				$tmp1 = "";
				$tmp2 = "";
				//         isDate(&$val,    $size, $min, $max, $err)
				if ($this->isDate($parts[0], $tmp1, $tmp1, $tmp1, $err) && $this->isTime($parts[1], $tmp1, $tmp1, $tmp1, $err) ) {
					if (strlen($min) && $min > $date." ".$time) {
						$err = "#".__LINE__.": Die Zeitangabe $val ist kleiner als min. zulässig: $min!<br>\n";
					}
					if (strlen($max) && $max < $date." ".$time) {
						$err = "#".__LINE__.": Die Zeitangabe $val ist groesser als max. zulässig: $max!<br>\n";
					}
				}
			} else $err = $stdErr;
		} else $err = "#".__LINE__." Es wurde kein Wert für Datum-Zeit-Prüfung angegeben!<br>\n";
		echo $err;
		return ($err === "");
	}
	
	function isInSet(&$val, &$size, &$min, &$max, &$err) {
		$err = "";
                if ($size[0] == "'") {
                    $opt = explode("','", substr($size, 1, -1)); 
                } elseif(strpos($size, "=,'") === 0) {
                    $opt = explode("','", "='" . substr($size, 1, -1));
                } else {
                    $opt = explode(",", $size);
                }
		$sel = (is_array($val)) ? $val : explode(",", $val);
		
		if (is_int(strpos($size, "="))) {
			for ($i = 0; $i < count($opt); $i++) list($opt[$i]) = explode("=", $opt[$i]);
		}
		for ($i = 0; $i < count($sel); $i++) {
			if (!in_array($sel[$i], $opt)) {
				$err = "#".__LINE__.": Ungültige Auswahl {$sel[$i]}! Mögliche Werte: " . print_r($opt,1) . "<br>\n";
				return false;
			}
		}
		if (is_numeric($min) && $min > count($sel)) {
			$err = "#".__LINE__." Es wurden zu wenig Werte (".count($sel).") ausgewählt. Wählen Sie mind. $min aus!<br>\n";
		}
		if (is_numeric($max) && $max < count($sel)) {
			$err = "#".__LINE__." Es wurden zu viele Werte ".count($sel)." ausgewählt. Wählen Sie höchstens. $max aus!<br>\n";
		}
		return ($err === "");
	}
	
	function isInEnum(&$val, &$size, &$min, &$max, &$err) {
		$err = "";
                if ($size[0] == "'") {
                    $opt = explode("','", substr($size, 1, -1)); 
                } elseif(strpos($size, "=,'") === 0) {
                    $opt = explode("','", "='" . substr($size, 1, -1));
                } else {
                    $opt = explode(",", $size);
                }
		if (!is_int(strpos($size, "="))) {
			if (in_array($val, $opt)) return true;
		} else {
			for ($i = 0; $i < count($opt); $i++) {
				list($r_key) = explode("=", $opt[$i]);
				if ($r_key == $val) return true;
			}
		}
		$err = "#".__LINE__.": Ungültige Auswahl: {$val}! Mögliche Werte: $size<br>\n";
		return false;
	}
	
	function isEmail(&$val, $size, &$err) {
		$err = "";
		if ($val !== "") {
			$email = trim($val);
			$s = preg_match("/\s/", trim($email));
			$k = strrpos($email,",");
			if (!$s && !$k) {
				$a=strrpos($email,"@");
				$p=strrpos($email,".");
				$l=strlen($email);
				if (!$k && !$s && $l>6 && ($p+2)<$l && ($a+2)<$p) {
					return true;
				} else $err = "#".__LINE__.": Überprüfen Sie Ihre E-Mail-Angabe!";
			} else $err = "#".__LINE__.": Ungültige Zeichen (Leerzeichen, Komma) in E-Mail: $val<br>\n";
		} $err = "#".__LINE__.": Es wurde kein Wert für E-Mail-Prüfung angegeben!";
		return ($err === "");
	}
}

if (basename(__FILE__) == basename($_SERVER["PHP_SELF"]) ) {
	$val = "12.3.2008";
	$size = false;
	$min = "13.3.2008";
	$max = "11.3.2008";
	$err = "";
	echo "#".__LINE__." val (before Check): $val <br>\n";
	echo "#".__LINE__." min (before Check): $min <br>\n";
	echo "#".__LINE__." max (before Check): $max <br>\n";
	$ct = new checkType();
	$ct->isDate($val, $size, $min, $max, $err);
	echo "#".__LINE__." val (after Check): $val <br>\n";
	echo "#".__LINE__." min (after Check): $min <br>\n";
	echo "#".__LINE__." max (after Check): $max <br>\n";
	echo "#".__LINE__." err: $err <br>\n";
}
