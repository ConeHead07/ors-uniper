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

function split_type_attr($type) {
	
	$posAttrStart = strpos($type, "(");
	$posAttrEnd = strrpos($type, ")");
	$re = array("type" => "", "attr" => "");
	if ($posAttrStart === false || $posAttrEnd === false) {
		$re["type"] = substr($type, 0, $posAttrStart);
		$re["attr"] = substr($type, $posAttrStart, $posAttrStart-$posAttrStart);
	} else {
		$re["type"] = $type;
		$re["attr"] = "";
	}
	return $re;
}

if (!function_exists("check_unique")) {
function check_unique($connid, $tbl, $fld, $val, $keyname = "", $keyval = "") {
	$SQL = "SELECT COUNT(*) FROM $tbl";
	$SQL.= " WHERE ".$fld." LIKE \"$val\"";
	if ($keyname && $keyval) {
		$SQL.= " AND NOT ".$keyname." = ".$keyval;
	}
	// echo "SQL: $SQL <br>\n";
	if (count_query($SQL, $connid)) {
		return false;
	}
	return true;
}}

if (!function_exists("IsEmail")) {
function IsEmail($email) {
	$email = trim($email);
	$b = strpos($email," ");
	$k = strpos($email,",");
	$l=strlen($email);
	if ($l >= 7) {
		if  (!is_int($b) && !is_int($k)) {
			$a=strrpos($email,"@");
			$p=strrpos($email,".");
			if (is_int($a) && is_int($p)) {
				if (($p+3)<=$l && ($a+3)<=$p) {
					return true;
				}
			}
		}
	}
	return false;
}}

if (!function_exists("check_email")) {
function check_email($email) {
	$email = trim($email);
	$b = strrpos($email," ");
	$k = strrpos($email,",");
	$a=strrpos($email,"@");
	$p=strrpos($email,".");
	$l=strlen($email);
	if (!$k && !$b && $l>6 && ($p+3)<=$l && ($a+3)<=$p) {
		$return["Error"] = "";
	} else {
		$return["Error"] = "�berpr�fen Sie Ihre E-Mail-Angabe";
	}
	return $return["Error"];
}}

if (!function_exists("check_date")) {
function check_date($inputDatum) {
	$return["Datum"] = $inputDatum;
	$return["Deutsch"] = $inputDatum;
	$return["Error"] = "";
	$datumsTeile = explode("-",$inputDatum);
	$jahr = 0;
	$monat = 0;
	$tag = 0;
	// Ermitteln der Datumsbestandteile: Tag, Monat u. Jahr
	if (count($datumsTeile) == 3) {
		list($jahr,$monat,$tag) = $datumsTeile;
	} else {
		$datumsTeile = explode(".",$inputDatum);
		if (count($datumsTeile) == 3) {
			list($tag,$monat,$jahr) = $datumsTeile;
		}
	}
	
	// Formatanpassung der Datumsbestandteile
	if ($jahr && $monat && $tag) {
		// echo "#1 tag: $tag<br>monat: $monat<br>jahr: $jahr<br>";
		$jahr = intval($jahr);
		$monat = intval($monat);
		$tag = intval($tag);
		$tag = substr("00".$tag,-2);
		$monat = substr("00".$monat,-2);
		if (strlen($jahr) == 2) {
			$jahr = ($jahr >=70) ?"19".$jahr:"20".$jahr;
		}
		$minJahr = 1970;
		$maxJahr = 2037;
		if ($jahr >= $minJahr && $jahr <=$maxJahr) {
			// echo "#2 tag: $tag<br>monat: $monat<br>jahr: $jahr<br>";
			// Pr�fen ob es sich um ein g�ltiges Datum handelt
			$date2Time = mktime(1,1,1,$monat,$tag,$jahr);
			$time2Date = date("Y-m-d", $date2Time);
			if (checkdate($monat, $tag, $jahr)) {
				$return["Datum"] = $time2Date;
				$return["Deutsch"] = $tag.".".$monat.".".$jahr;
			} else {
				$return["Error"].="Ung�ltiges Datum<br>";
			}
		} else {
			$return["Error"].="Die Jahresangabe '$jahr' liegt ausserhalb";
			$return["Error"].=" des Bereichs $minJahr - $maxJahr<br>";
		}
	} else {
		$return["Error"].="Ung�ltiges Datumsformat<br>";
	}
	return $return;
}}

if (!function_exists("check_datetime")) {
function check_datetime($inputDatumzeit) {
	
	$return["Datumzeit"] = $inputDatumzeit;
	$return["Deutsch"] = $inputDatumzeit;
	$return["Error"] = "";
	
	$inputDatumzeit = trim($inputDatumzeit);
	while(strchr($inputDatumzeit,"  ")) $inputDatumzeit = str_replace("  "," ",$inputDatumzeit);
	list($inputDatum,$inputZeit) = explode(" ",$inputDatumzeit);
	$chckDate = check_date($inputDatum);
	
	if (!$chckDate["Error"]) {
		if (!$chckTime["Error"]) {
			$chckTime = check_time($inputZeit);
			$return["Datumzeit"] = $chckDate["Datum"]." ".$chckTime["Zeit"];
			$return["Deutsch"]   = $chckDate["Deutsch"]." ".$chckTime["Deutsch"];
		}
	}
	return $return;
}}

if (!function_exists("check_time")) {
function check_time($inputZeit) {
	
	$inputZeit = trim($inputZeit);
	$return["Zeit"] = trim($inputZeit);
	$return["Deutsch"] = trim($inputZeit);
	$return["Error"] = "";
	
		$stunde = "00";
		$minute = "00";
		$sekunde = "00";
		if ($inputZeit) {
			$zeitelemente = explode(":",$inputZeit);
			switch(count($zeitelemente)) {
				case 1:
				list($stunde) = $zeitelemente;
				break;
				
				case 2:
				list($stunde,$minute) = $zeitelemente;
				break;
				
				case 3:
				list($stunde,$minute,$sekunde) = $zeitelemente;
				break;
			}
			if (intval($stunde) >23) $return["Error"].="Stundenangabe ist gr&ouml;&szlig;er als 23<br>";
			if (intval($stunde) >59) $return["Error"].="Minutenabgabe ist gr&ouml;&szlig;er als 59<br>";
			if (intval($stunde) >59) $return["Error"].="Sekundenangabe ist gr&ouml;&szlig;er als 59<br>";
			
			if (!$return["Error"]) {
				
				$zeit= substr("0".strval(intval($stunde)),-2);
				$zeit.= ":".substr("0".strval(intval($minute)),-2);
				$zeit.= ":".substr("0".strval(intval($sekunde)),-2);
				$return["Datumzeit"] = $zeit;
				$return["Deutsch"] = $zeit;
				// echo "#124 $return[Datumzeit]: ".$return["Datumzeit"]."<br>\n";
				// echo "#125 $return[Deutsch]: ".$return["Deutsch"]."<br>\n";
			}
		}
	return $return;
}}

if (!function_exists("check_integer")) {
function check_integer($inputZahl, $min = false, $max = false) {
	$auswertung["Zahl"] = strval($inputZahl);
	$auswertung["Error"] = "";
	$auswertung["Deutsch"] = "";
	if (strval(intval($inputZahl)) === strval($inputZahl)) {
		if (!($min === false)) {
            if ($inputZahl < $min) {
                $auswertung["Error"].="Geben Sie eine Ganzzahl gr��er gleich $min ein!<br>";
            }
        }
		if (!($max === false)) {
            if ($inputZahl > $max) {
                $auswertung["Error"].="Geben Sie eine  Ganzzahl kleiner gleich $max ein!<br>";
            }
        }
	 } else {
	 	$auswertung["Error"].= "Geben Sie eine Ganzzahl aus reinen Zahlen ohne Punkt und Komma ein!<br>";
	 }
	 
	 $auswertung["Deutsch"] = number_format($inputZahl, 0, ",", ".");
	 return $auswertung;
}}

if (!function_exists("check_float")) {
function check_float($inputZahl,$min,$max,$nachkommastellen) {
	$auswertung["Zahl"] = "".$inputZahl;
	$auswertung["Error"] = "";
	$auswertung["Deutsch"] = "";
	if (strval(doubleval($inputZahl)) === strval($inputZahl)) {
		if (!($min === false)) {
            if ($inputZahl < $min) {
                $auswertung["Error"].="Geben Sie eine Zahl gr��er gleich $min ein!<br>";
            }
        }
		if (!($max === false)) {
            if ($inputZahl > $max) {
                $auswertung["Error"].="Geben Sie eine  Zahl kleiner gleich $max ein!<br>";
            }
        }
	 } else {
   	 	$auswertung["Error"].="Geben Sie eine Zahl aus reinen Zahlen mit einem Punkt f�r Nachkommastellen an!<br>";
	 }
	 $auswertung["Deutsch"] = number_format($inputZahl, $nachkommastellen, ",", ".");
	 
	 return $auswertung;
}}
/**/
?>