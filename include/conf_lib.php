<?php 

function conf_load($ConfFile, $ConfGroup = "", $ConfVar = "") {
	$CNF = array();
	if (file_exists($ConfFile)) {
		$CNF_KEY = "";
		$fp = fopen($ConfFile, "r");
		if ($fp) {
			$i = 0;
			while($line = fgets($fp, 1500)) {
				$line = trim($line);
				
				if (!$line || $line[0] == "#" || $line[0] == ";") continue;
				
				if ($line[0] == "[" && substr($line, -1) == "]") {
					if ($line[1] != "/") {
						$CNF_KEY = substr($line, 1, -1);
						$CNF[$CNF_KEY] = array();
					} else {
						$CNF_KEY = "";
					}
					continue;
				}
				
				if ($ConfGroup && $ConfGroup != $CNF_KEY) continue;
				
				$t = explode("=", $line);
				$k = trim($t[0]);
				$v = (count($t) ? trim(implode("=", array_slice($t, 1))) : "");
				
				if ($ConfGroup == $CNF_KEY && $k == $ConfVar) return $v;
				
				if ($CNF_KEY) {
					$CNF[$CNF_KEY][$k] = $v;
				} else {
					$CNF[$k] = $v;
				}
			}
			fclose($fp);
		}
		if ($ConfGroup && !empty($CNF[$ConfGroup])) return $CNF[$ConfGroup];
	}
	return $CNF;
}

function conf_serialize($ConfArray) {
	$ConfCode = "";
	foreach($ConfArray as $k => $v) {
		if (is_array($v)) {
  		$ConfCode.= "[{$k}]\n";
  		foreach($v as $k2 => $v2) {
  			$ConfCode.= "$k2 = ".trim($v2)."\n";
  		}
      $ConfCode.= "\n";
    } else {
      $ConfCode.= "$k = ".trim($v)."\n";
    }
	}
	return $ConfCode;
}

function conf_deserialize($ConfCode, $ConfGroup = "") {
	$CNF = "";
	$CNF_KEY = "";
	$i = 0;
	$offset = 0;
	$lines = explode("\n", $ConfCode);
	foreach($lines as $line) {
		$line = trim($line);
		
		if (!$line || $line[0] == "#" || $line[0] == ";") continue;
		
		if ($line[0] == "[" && substr($line, -1) == "]") {
			if ($line[1] != "/") {
				$CNF_KEY = substr($line, 1, -1);
				$CNF[$CNF_KEY] = array();
			} else {
				$CNF_KEY = "";
			}
			continue;
		}
		
		if ($ConfGroup && $ConfGroup != $CNF_KEY) continue;
		
		$t = explode("=", $line);
		$k = trim($t[0]);
		$v = (count($t) ? trim(implode("=", array_slice($t, 1))) : "");
		
		if ($CNF_KEY) {
			$CNF[$CNF_KEY][$k] = $v;
		} else {
			$CNF[$k] = $v;
		}
	}
	if ($ConfGroup && !empty($CNF[$ConfGroup])) return $CNF[$ConfGroup];
	return $CNF;
}

function conf_write_array($ConfFile, $ConfArray) {
	$cnfCode = "";
	foreach($ConfArray as $k => $v) {
		$cnfCode.= "[{$k}]\n";
		foreach($v as $k2 => $v2) {
			$cnfCode.= "$k2 = ".trim($v2)."\n";
		}
		$cnfCode.= "\n";
	}
	file_put_contents($ConfFile, $cnfCode);
}

function conf_write_code($ConfFile, $ConfCode) {
	file_put_contents($ConfFile, $ConfCode);
}

?>