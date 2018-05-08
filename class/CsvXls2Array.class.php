<?php 
class CsvXls2Array {
	
	function CsvXls2Array() {
		$this->boundary = ";";
		$this->auto_set_boundary = true;
		$this->encloser = "\"";
		$this->encloserLen = strlen($this->encloser);
		$this->encloserXlsMask = "\"\"";
		$this->encloserXlsMaskLen = strlen($this->encloserXlsMask);
		$this->encloserMask = "{".substr(md5("\""), 0, 8)."}"; //{b15835f1}# "&quot;";
		$this->lineIsOpen = false;
		$this->DATA = array();
	}
	
	function chck_boundary($sCSV) {
		$p = 0;
		while($sCSV[0] == " " || $sCSV[0] == "\r" || $sCSV[0] == "\n") $p++;
		
		// Falls erstes Feld mit Zahlen gefüllt, muss erste Nicht-Zahl der Feldtrenner sein
		if (strval(intval($sCSV[$p])) == $sCSV[$p]) {
			while(strval(intval($sCSV[++$p])) == $sCSV[$p]) { }
			$bnd = substr(trim(substr($sCSV, $p+1)), 0, 1);
			if ($bnd) {
				$this->boundary = $bnd;
				return $this->boundary;
			}
		} else {
			switch($sCSV[$p]) {
				case '"':
				case "'":
				// Finde Zeichen nach abschließendem Texttrenner
				$txtTrenner = $sCSV[$p];
				$numLoop = 0;
				$maxLoop = strlen($sCSV);
				do {
					if ($numLoop++ > $maxLoop) { echo "Strukturfehler, Kein gültiges CSV-Format!!"; break; }
					$p = strpos($sCSV, $txtTrenner, $p+1);
					if (is_int($p)) {
						// Prüfe, ob gefundener Texttrenner nur als Escape genutzt wurde, analog zum Backslash
						if ($sCSV[$p+1] == $txtTrenner) $p++;
						else $bnd = $sCSV[$p+1];
					} // else Fehler, da abschließendem Texttrenner gesucht wird
				} while (!$bnd && is_int($p));
				if ($bnd) {
					$this->boundary = $bnd;
					return $this->boundary;
				}
				break;
				
				default:
					$this->boundary = $sCSV[$p];
					return $this->boundary;
				break;
			}
		}
		return false;
	}
	
	function trim_empty_rows() {
		$checkNextLine = (count($this->DATA)) ? true : false;
		while($checkNextLine) {
			$r_i = count($this->DATA)-1;
			if ($r_i >= 0 && trim(implode("", $this->DATA[$r_i])) == "") {
				array_pop($this->DATA);
			} else {
				$checkNextLine = false;
			}
		}
	}
	
	function encode_encloserMask($string) {
		return str_replace($this->encloserXlsMask, $this->encloserMask, $string);
	}
	
	function xdecode_encloserMask($string) {
		return str_replace($this->encloserMask, $this->encloser, $string);
	}
	
	function decode_encloserMask($string) {
		return str_replace($this->encloserXlsMask, $this->encloser, $string);
	}
	
	function pos_of_closingEncloser($line) {
		$read_next = true;
		$offset = 0;
		while($read_next) {
			$p1 = strpos($line, $this->encloser, $offset);
			$p2 = strpos($line, $this->encloserXlsMask, $offset);
			if (is_int($p1)) {
				if (is_int($p2)) {
					$offset = $p2+$this->encloserXlsMaskLen;
				} else {
					$offset = $p1+$this->encloserLen;
				}
				if (!is_int($p2) || $p1 < $p2) return $p1;
				
			} else {
				return false;
			}
		}
		return false;
	}
	
	function parse_enclosed_line($line) {
		
		$read_next = true;
		$_Data = array();
		$inPhrase = ($this->lineIsOpen) ? true : false;
		
		while($read_next) {
			if (!$inPhrase) {
				if (substr(trim($line), 0, 1) == $this->encloser) {
					$inPhrase = true;
					$line = substr(ltrim($line), 1);
				}
			}
			
			switch($inPhrase) {
				case true:
				$quoteEnd  = strpos($line, $this->encloser);
				$quoteEnd  = $this->pos_of_closingEncloser($line);
				
				if (is_int($quoteEnd)) {
					$_Data[count($_Data)] = $this->decode_encloserMask(substr($line, 0, $quoteEnd));
					//echo "#".__LINE__." ".$_Data[count($_Data)-1]."<br>\n";
					$line = substr($line, $quoteEnd+1);
					$inPhrase = false;
					$this->lineIsOpen = false;
				} else {
					$_Data[count($_Data)] = $this->decode_encloserMask($line);
					//echo "#".__LINE__." ".$_Data[count($_Data)-1]."<br>\n";
					$read_next = false;
					$this->lineIsOpen = true;
				}
				
				break;
				
				case false:
				$pBnd = strpos($line, $this->boundary);
				if (is_int($pBnd)) {
					$_Data[count($_Data)] = $this->decode_encloserMask(substr($line, 0, $pBnd));
					//echo "#".__LINE__." ".$_Data[count($_Data)-1]."<br>\n";
					$line = substr($line, $pBnd);
					$read_next = true;
				} else {
					$_Data[count($_Data)] = $this->decode_encloserMask($line);
					//echo "#".__LINE__." ".$_Data[count($_Data)-1]."<br>\n";
					$read_next = false;
				}
				break;
			}
			
			if ($read_next) {
				$pBnd = strpos($line, $this->boundary);
				if (is_int($pBnd)) {
					$line = substr($line, $pBnd+1);
					//echo "#".__LINE__." line:".$line."<br>\n";
				} else {
					$read_next = false;
				}
			}
		}
		return $_Data;
	}
	
	function parse_xls_file($file, $max_rows = 0) {
		$fp = fopen($file, "r");
		$this->DATA = array();
		
		$txtStart = false;
		if ($fp) {
			while(!feof($fp)) {
				$line = fgets($fp, 1500);
				
				if ($this->auto_set_boundary && !$txtStart && trim($line)) {
					$this->chck_boundary($line);
					$txtStart = true;
				}
				
				if (!$this->lineIsOpen) {
					$di = count($this->DATA);
					if (!is_int(strpos($line, $this->encloser))) {
						$this->DATA[$di] = explode($this->boundary, $line);
					} else {
						$this->DATA[$di] = $this->parse_enclosed_line($line);
					}
				} else {
					$_Tmp = $this->parse_enclosed_line($line);
					$dij = count($this->DATA[$di])-1;
					$this->DATA[$di][$dij] = $this->DATA[$di][$dij].$_Tmp[0];
					
					for ($ti = 1; $ti < count($_Tmp); $ti++) {
						$dij = count($this->DATA[$di]);
						$this->DATA[$di][$dij] = $_Tmp[$ti];
					}
				}
				if ($max_rows && count($this->DATA) >= $max_rows) break;
			}
			fclose($fp);
		}
		$this->trim_empty_rows();
	}
	
	function parse_xls_string($string) {
		$_LINES = explode("\n", $string);
		$cntLines = count($_LINES);
		$txtStart = false;
		
		for ($i = 0; $i < $cntLines; $i++) {
			$line = $_LINES[$i];
			
			if ($this->auto_set_boundary && !$txtStart && trim($line)) {
				$this->chck_boundary($line);
				$txtStart = true;
			}
			
			if ($i+1 < $cntLines) $line.= "\n"; 
			if (!$this->lineIsOpen) {
				$di = count($this->DATA);
				if (!is_int(strpos($line, $this->encloser))) {
					$this->DATA[$di] = explode($this->boundary, $line);
				} else {
					$this->DATA[$di] = $this->parse_enclosed_line($line);
				}
			} else {
				$_Tmp = $this->parse_enclosed_line($line);
				$dij = count($this->DATA[$di])-1;
				$this->DATA[$di][$dij] = $this->DATA[$di][$dij].$_Tmp[0];
				for ($ti = 1; $ti < count($_Tmp); $ti++) {
					$dij = count($this->DATA[$di]);
					$this->DATA[$di][$dij] = $_Tmp[$ti];
				}
			}
		}
		$this->trim_empty_rows();
	}
	
	function show_csv_table($re = true, $offset = 0, $max_rows = 0) {
		$this->table = "<style>table.xlsTbl { border-collapse:collapse;border-spacing:0px; border-left:1px solid gray;border-top:1px solid gray;}
		table.xlsTbl td { font-family:Arial; font-size:12px; }
		</style>";
		$this->table.= "<table class=\"xlsTbl\" border=1 cellspacing=0 cellpadding=3>\n";
		$nr = 0;
		foreach($this->DATA as $k => $v) {
			if ($nr++ < $offset) continue;
			$this->table.= "<tr>\n";
			$this->table.= "<td>$nr</td>\n";
			if (is_array($v)) {
			while(list($k2, $v2) = each($v)) {
				$this->table.= "<td>\n";
				$this->table.= nl2br($v2);
				$this->table.= "&nbsp;</td>\n";
			}} else {
				$this->table.= "<td>\n";
				$this->table.= "dump DATA[$k] = ".nl2br($v)."\n";
				$this->table.= "&nbsp;</td>\n";
			}
			$this->table.= "</tr>\n";
			if ($max_rows && $nr >= $max_rows) break;
		}
		$this->table.= "</table>\n";
		if ($re) return $this->table;
		else echo $this->table;
	}
}

if (basename($_SERVER["PHP_SELF"]) == basename(__FILE__)) {
	echo "#".__LINE__. " time:".time()."<br> \n";
	$xls_file = "../sendezeiten_csv_31_03_2006.csv";
	$xls_string = implode("", file($xls_file));
	$xls_csv = new csv_xls2array;
	//$xls_csv->parse_xls_file($xls_file);
	$xls_csv->parse_xls_string($xls_string);
	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html><head><title>Untitled</title></head>	
	<body>
	<?php
	$xls_csv->show_csv_table(false);
	echo "#".__LINE__. " time:".time()."<br> \n";
	?>
	</body>
	</html>
<?php
}
?>