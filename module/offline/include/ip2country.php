<?php 

if (!function_exists("ip2number")) {
function ip2number($ip_addr) {
	$_ip_parts = explode(".", $ip_addr);
	if (count($_ip_parts) == 4) {
		for ($i = 0; $i < count($_ip_parts); $i++) {
			if (strval(intval($_ip_parts[$i])) == $_ip_parts[$i]) {
				$_ip_parts[$i] = intval($_ip_parts[$i]);
			} else {
				return 0;
			}
		}
		$ip_long = 0;
		$ip_long+= $_ip_parts[0]*(256*256*256);
		$ip_long+= $_ip_parts[1]*(256*256);
		$ip_long+= $_ip_parts[2]*(256);
		$ip_long+= $_ip_parts[3];
		return $ip_long;
	} else {
		return 0;
	}
}}

if (!function_exists("ipn2country")) {
function ipn2country($ipnumber) {
	global $connid;
	// echo "#".__LINE__." connid: $connid; is_resource(connid):".is_resource($connid)."<br>\n";
	$country = "";
	$SQL = "SELECT country_code3 FROM ctv_ip2country \n";
	$SQL.= " WHERE ip_from <= $ipnumber AND ip_to >= $ipnumber \n";
	$r = MyDB::query($SQL, $connid);
	if ($r) {
		$n = MyDB::num_rows($r);
		if ($n) {
			$_e = MyDB::fetch_array($r);
			$country = $_e["country_code3"];
		}
		MyDB::free_result($r);
	} else {
		echo "<pre>#".__LINE__." ".MyDB::error()."\nQUERY: ".fb_htmlEntities($SQL)."</pre>\n";
	}
	//echo "<!-- ".$SQL." -->\n";
	return $country;
}}

?>
