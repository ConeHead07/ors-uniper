<?php 

if (!function_exists('fb_htmlEntities')) {
    function fb_htmlEntities($str, $flags = null, $charset = null)
	{
        $f = (null === $flags) ? ENT_COMPAT : $flags;
        $c = (null === $charset) ? 'UTF-8' : $charset;

        // echo "/* htmlentities($str, $f, $c): " . htmlentities($str, $f, $c) . "<br>\n */";

        return htmlentities($str, $f, $c);
    }
}


if (!function_exists("get_iconStatus")) { function get_iconStatus($statVal, $date, $von ='', $statKey ='') {
        $alt = '';
        $alt.= (strtotime($date) ? date('d.m H:i', strtotime($date)) : $date);
        if ($statKey) $alt.= ' ' . $statKey . '(' . $statVal . ')';
        if ($von) $alt.= ' von ' . $von;
	switch(strtoupper($statVal)) {
		case "JA": return "<img src=\"images/status_ja.png\" width=\"16\" height=\"16\" title=\"".fb_htmlEntities($alt)."\">";
		case "NEIN": return "<img src=\"images/status_nein.png\" width=\"16\" height=\"16\" title=\"".fb_htmlEntities($alt)."\">";
		case "INIT": return "<img src=\"images/status_init.png\" width=\"16\" height=\"16\" title=\"".fb_htmlEntities($statVal)."\">";
		case "STORNIERT": return "<img src=\"images/status_storniert.png\" width=\"16\" height=\"16\" title=\"".fb_htmlEntities($alt)."\">";
		case "WARNUNG": return "<img src=\"images/warning_triangle.png\" width=\"16\" height=\"16\" alt=\"".fb_htmlEntities($alt)."\">";
	}
	return "<span class=\"status".$statVal."\" title=\"".fb_htmlEntities($alt)."\">$statVal</span>";
}}

if (!function_exists('getFormattedNumber')) {
function getFormattedNumber($nr) {
    if (is_numeric($nr)) {
        return $nr;
    } elseif (preg_match('/^([0-9.]+),([0-9]+)$/', $nr, $m) || preg_match('/^([0-9,]+).([0-9]+)$/', $nr, $m)) {
        return strtr($m[1], array('.'=>'',','=>'')) . '.' . $m[2];
    }
    return $nr;
}}

if (!function_exists("format_dbDate")) {
function format_dbDate($dbDate, $format) {
	$d = array_slice(explode("-", strtr($dbDate," :","--")), 0, 6);
	for($i = 0; $i <6; $i++) $d[$i] = (isset($d[$i])) ? (int)$d[$i] : 0;
	$t = mktime($d[3], $d[4], $d[5], $d[1], $d[2], $d[0]);
	return date($format, $t);
}}

if (!function_exists("dateToTime")) {
function dateToTime($date) {
	$d = array_slice(explode("-", strtr($date," :.","---")), 0, 6);
	for($i = 0; $i <6; $i++) $d[$i] = (isset($d[$i])) ? (int)$d[$i] : 0;
	if (strpos($date, "-")!==false) return mktime($d[3], $d[4], $d[5], $d[1], $d[2], $d[0]);
	elseif (strpos($date, ".")!==false) return mktime($d[3], $d[4], $d[5], $d[1], $d[0], $d[2]);
	else return false;
}}

if (!function_exists("format_file_size")) {
function format_file_size($bytes) {
	if ($bytes > (1024*1024*1024)) {
		return round($bytes / (1024*1024*1024), 1)." GB";
	} elseif ($bytes > (1024*1024)) {
		return round($bytes / (1024*1024), 1)." MB";
	} elseif ($bytes > (1024)) {
		return round($bytes / (1024), 1)." KB";
	} else return $bytes." Bytes";
}}

function __autoload($class_name) {
    global $MConf;
	$loadfile = $MConf["AppRoot"].$MConf["Class_Dir"].$class_name . '.class.php';
	if (!file_exists($loadfile)) {
		if ($class_name == "Smarty") {
		    $loadfile = $MConf["AppRoot"]."smarty3/Smarty.class.php";
        }
		elseif ($class_name == 'TCPDF') {
            $loadfile = $MConf["AppRoot"]."/vendor/TCPDF/tcpdf.php";
        }
		else {
            $moduleClassFile = $MConf["AppRoot"] . str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . '.php';
            $moduleClassFileExists = file_exists($moduleClassFile);
            if ($moduleClassFileExists) {
                require_once $moduleClassFile;
                $loadfile = $moduleClassFile;
            } else {
                return false;
            }
//            die('#'. __LINE__ . ' ' . __FILE__ . ' '
//                . json_encode(compact('class_name', 'moduleClassFile', 'moduleClassFileExists'))
//            );
//			echo "CanNotAutoLoad: ".$loadfile."<br>\n";
			return false;
		}
	}
	require_once $loadfile;
}

function getTplEngine($tplDir = "", $cnfDir = "") {
	global $MConf;
	if (!class_exists("Smarty")) require_once $MConf["AppRoot"]."smarty3/Smarty.class.php";
	$myTpl = new Smarty();
	$myTpl->debugging = false; // true; // 
	$myTpl->compile_check = true;
	$myTpl->template_dir = ($tplDir) ? $tplDir : $MConf["AppRoot"]."html";
	$myTpl->config_dir = ($cnfDir) ? $cnfDir : $MConf["AppRoot"]."smarty_lab/configs";
	$myTpl->compile_dir = $MConf["AppRoot"]."smarty_lab/templates_c";
	$myTpl->cache_dir = $MConf["AppRoot"]."smarty_lab/cache";
	return $myTpl;
}

if (!function_exists("set_cms")) {
function set_cms($Str, $strStart, $strEnde, $strWert) {
	$p1 = strpos($Str, $strStart);
	if (is_int($p1)) {
		$tmp = substr($Str, $p1+strlen($strStart));
		
		$p2 = strpos($tmp, $strEnde);
		if (is_int($p2)) {
			return substr($Str, 0, $p1+strlen($strStart)).$strWert.substr($tmp,$p2);
		}
	}
	return $Str;
}}

if (!function_exists("get_cms")) {
function get_cms($startTag, $closeTag, $str) {
	$p1 = strpos($str, $startTag);
	if (is_int($p1)) {
		$p1co = $p1+strlen($startTag); // Pos1 of ContentStart
		$p2 = strpos($str, $closeTag, $p1co);
		if (is_int($p2)) {
			return substr($str, $p1co, $p2-$p1co);
		}
	}
	return "";
}}

function scriptTimeInit($time = "") {
	global $timeIn;
	if ($time) $timeIn = $timeIn;
	else $timeIn = time();
}

function scriptTime($time, $__LINE__ = "", $__FILE__ = "") {
	global $timeIn;
	global $LogTimeProtokoll;
	$debugTime = "";
	$debugTime.= "<div style=\"background:#ffff80;width:100%;margin:1px;font-family:Arial;font-size:12px;color:#191970;\">";
	$debugTime.= "&nbsp;<b>&gt; Sek: ".strval($time-$timeIn)."</b> ";
	if ($__LINE__ || $__FILE__) {
		$debugTime.= " <font style=\"font-size:11px;\">";
		if ($__LINE__) $debugTime.= "#".$__LINE__." ";
		if ($__FILE__) $debugTime.= basename($__FILE__)." ";
		$debugTime.= "</font>";
	}
	$debugTime.= "</div>\n";
	$LogTimeProtokoll.= $debugTime;
	return $debugTime;
}

if (!function_exists("format_fstat")) {
function format_fstat($file) {
	$d = date("Y-m-d H:i", filemtime($file));
        $_a = explode(" ",$d);
	$d = (substr($d, 0, 10) != date("Y-m-d")) ? array_shift($_a) : array_pop($_a);
	$s = filesize($file);
	if ($s < 1024) {
		$s = $s."B";
	} elseif ($s < 1024*1024) {
		$s = round($s/1024,1)."KB";
	} elseif ($s < 1024*1024*1024) {
		$s = round($s/(1024*1024),1)."MB";
	} else {
		$s = round($s/(1024*1024*1024),1)."GB";
	}
	return $d . " | " . $s;
}}

if (!function_exists("format_file_size")) {
function format_file_size($bytes) {
	if ($bytes > (1024*1024*1024)) {
		return round($bytes / (1024*1024*1024), 1)." GB";
	} elseif ($bytes > (1024*1024)) {
		return round($bytes / (1024*1024), 1)." MB";
	} elseif ($bytes > (1024)) {
		return round($bytes / (1024), 1)." KB";
	} else return $bytes." Bytes";
}}

if (!function_exists("arraytoVarString")) {
$inv_max = 1000;
function arraytoVarString($strStemm, $_Arr, $trenner = "\n", $cnt_loop = 0) {
	global $inv_max;
	if ($cnt_loop > $inv_max) {
		echo "#".__LINE__." Zu viele Inverse-Aufrufe: More than $inv_max<br>\n";
		return "";
	}
	
	$querystring = "";
	if (is_array($_Arr)) {
		reset($_Arr);
		while(list($k, $v) = each($_Arr)) {
			if ($strStemm) {
				$r_stemm = $strStemm."[\"".$k."\"]";
			} else {
				$r_stemm = $k;
			}
			if (!is_array($v)) {
				if ($querystring || $cnt_loop > 0) $querystring.= $trenner;
				if (gettype($v) == "string") {
					$querystring.= $r_stemm."= \"".addslashes($v)."\";";
				} else {
					$querystring.= $r_stemm."=".strval($v).";";
				}
			} else {
				$querystring.= arraytoVarString($r_stemm, $v, $trenner, $cnt_loop+1);
			}
		}
	} else {
		$querystring = $strStemm." = ";
		$querystring.= (gettype($v) == "string") ? $v : "\"".addslashes($_Arr)."\"" ;
	}
	return $querystring;
}}

if (!function_exists("arraytoQueryString")) {
$inv_max = 1000;
function arraytoQueryString($strStemm, $_Arr, $trenner = "&", $cnt_loop = 0) {
	global $inv_max;
	if ($cnt_loop > $inv_max) {
		echo "#".__LINE__." Zu viele Inverse-Aufrufe: More than $inv_max<br>\n";
		return "";
	}
	
	$querystring = "";
	if (is_array($_Arr)) {
		reset($_Arr);
		while(list($k, $v) = each($_Arr)) {
			if ($strStemm) {
				$r_stemm = $strStemm."[".$k."]";
			} else {
				$r_stemm = $k;
			}
			if (!is_array($v)) {
				$querystring.= $trenner.$r_stemm."=".rawurlencode(strval($v));
			} else {
				$querystring.= arraytoQueryString($r_stemm, $v, $trenner, $cnt_loop+1);
			}
		}
	} else {
		$querystring = $strStemm."=".rawurlencode($_Arr);
	}
	return $querystring;
}}

if (!function_exists("domainOfUrl")) {
function domainOfUrl($strUrl) {
	$refDomain = "";
	if (isset($strUrl)) {
		$t = $strUrl;
		$p1 = strpos($t, "://");
		if (is_int($p1)) {
			$p2 = strpos($t, "/", $p1+3);
			$p3 = strpos($t, "?", $p1+3);
			if (is_int($p2)) {
				$refDomain = substr($t, 0, $p2);
			} elseif (is_int($p3)) {
				$refDomain = substr($t, 0, $p3);
			} else {
				$refDomain = $strUrl;
			}
		}
	}
	return $refDomain;
}}
