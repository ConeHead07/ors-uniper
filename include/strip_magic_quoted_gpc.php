<?php 
if (!function_exists("check_mquotes_is_on")) {
	function check_mquotes_is_on() {
		if (gettype(ini_get("magic_quotes_runtime")) == "string") $mquotes_is_on = (ini_get("magic_quotes_runtime") ? true : false);
		if (function_exists("get_magic_quotes_gpc")) $mquotes_is_on = (get_magic_quotes_gpc() ? true : false);
		return $mquotes_is_on;
	}
	
	function array_stripslashes(&$a) {
		foreach($a as $k => $v) {
			if (gettype($v) == "string") $a[$k] = stripslashes($v);
			elseif (is_array($v)) array_stripslashes($a[$k]);
		}
	}
	
	if (check_mquotes_is_on()) {
		array_stripslashes($_GET);
		array_stripslashes($_POST);
		array_stripslashes($_REQUEST);
		array_stripslashes($_COOKIE);
	}
}
?>