<?php 

// Angebotsstatus 
// [Bearbeitungsstatus] => 1
// [AbschlussStatus] => 0

// Bestätigt: 
// [Bearbeitungsstatus] => 2
// [AbschlussStatus] => 0

// Geliefert: 
// [Bearbeitungsstatus] => 4
// [AbschlussStatus] => 0

// Teilberechnet: 
// [Bearbeitungsstatus] => 5
// [AbschlussStatus] => 0

// Erledigt: 
// [Bearbeitungsstatus] => 9
// [AbschlussStatus] => 1

$aErrno2Txt[0] = "MS-SQL-Connector wurde geladen!";
$aErrno2Txt[1] = "MS-SQL-Connector wurde nicht geladen: die Funktion dl() zum Nachladen ist deaktiviert!";
$aErrno2Txt[2] = "MS-SQL-Connector wurde nicht geladen: die Erweiterung php_mssql wurde nicht gefunden!";
$enableNotice = "Aktivieren Sie die Erweiterung extension=php_mssql in xampp/php/php.ini indem Sie das Semikolon am Zeilenanfang entfernen. Danach muss der Webserver neu gestartet werden.";

class WWW_DB {
	var $connid;
	var $Servername = "SQLSRV"; // "SQLSRV"
	var $Benutzername = "qe2"; // string 
	var $Passwort = "vielsichtig"; // string
	var $NeueVerbindung = true; // bool
	var $DB = "scoffice";
	
	function __construct() {
		if (!$this->check_mssql(&$errno)) {
			return null;
		}
		$this->connid = @mssql_connect($this->Servername, $this->Benutzername, $this->Passwort, $this->NeueVerbindung);
		// echo "#".__LINE__." MS_Conn: $this->connid <br>\n";
		
		$this->Result = @mssql_query("USE scoffice", $this->connid);
		// echo "#".__LINE__." MS_Result: $this->Result <br>\n";
		
		$this->Result = @mssql_query("USE scoffice", $this->connid);
		// echo "#".__LINE__." MS_Result: $this->Result <br>\n";
	}
	
	function check_mssql(&$errno) {
		
		if (function_exists("mssql_connect")) return true;
		
		if (!function_exists("dl")) {
			$errno = 1;
			return false;
		}
		
		if (!dl("php_mssql.dll") && !dl("php_mssql.so")) {
			$errno = 2;
			return false;
		}
		return true;
	}
	
	function __destruct() {
		if (is_resource($this->connid)) mssql_close($this->connid);
	}
	
	function get_RowById($WwsId, $mid) {
		$row = array();
		
		if (!is_resource($this->connid)) return $row;
		if (!function_exists("mssql_connect") && !@dl("php_mssql.dll")) {
			return false;
		}
		
		$mandant = "";
		$int1 = $WwsId; // "192161";
		$int2 = $mid; // "10" merTens;
		
		// [Bearbeitungsstatus] 
		// [UnterBearbeitungsstatus]
		// [AbschlussStatus]
		$Query = "SELECT 
				A.Mandant,
				A.Bearbeitungsstatus,
				A.UnterBearbeitungsstatus,
				A.AbschlussStatus,
				A.Auftragsnummer as vorgangsnr,
				A.RechnungName as firmenname,
				A.RechnungPostleitzahl as firmenplz,
				A.RechnungOrt as firmenort,
				A.RechnungPostleitzahl + ' ' + A.RechnungOrt as firmenplzort,
				A.RechnungStrassePostfach as firmenstr
		  		FROM  AuftragsKoepfe AS A
		        WHERE A.Auftragsnummer=\"".addslashes($int1)."\" AND A.Mandant=\"$int2\"";
		        // WHERE A.Bearbeitungsstatus!=\"9\"";
		
		$this->Result = mssql_query($Query, $this->connid);
		// echo "#".__LINE__." MS_Result: $this->Result <br>\n";
		
		if ($this->Result)  {
			$n = mssql_num_rows($this->Result);
			if  ($n) {
				$row = mssql_fetch_assoc($this->Result);
				// echo "#".__LINE__." row: ".print_r($row, true)."<br>\n";
			}
			mssql_free_result($this->Result);
		}
		
		return $row;
	}
	
	function get_RowsById($WwsId, $mid, $AllFlds = false) {
		$row = array();
		
		if (!is_resource($this->connid)) return $row;
		if (!function_exists("mssql_connect") && !@dl("php_mssql.dll")) {
			return false;
		}
		
    $rows = array();
		$mandant = "";
		$int1 = $WwsId; // "192161";
		$int2 = $mid; // "10" merTens;
		
		// [Bearbeitungsstatus] 
		// [UnterBearbeitungsstatus]
		// [AbschlussStatus]
		if (!$AllFlds) {
      $Query = "SELECT 
				A.Mandant,
				A.Bearbeitungsstatus,
				A.UnterBearbeitungsstatus,
				A.AbschlussStatus,
				A.Auftragsnummer as vorgangsnr,
				A.RechnungName as firmenname,
				A.RechnungPostleitzahl as firmenplz,
				A.RechnungOrt as firmenort,
				A.RechnungPostleitzahl + ' ' + A.RechnungOrt as firmenplzort,
				A.RechnungStrassePostfach as firmenstr";
     } else {
      $Query = "SELECT * ";
     }
     $Query.= "
		  		FROM  AuftragsKoepfe AS A
		        WHERE A.Auftragsnummer=\"".addslashes($int1)."\" AND Mandant=\"$int2\"";
		        // WHERE A.Bearbeitungsstatus!=\"9\"";
		
		$this->Result = mssql_query($Query, $this->connid);
		// echo "#".__LINE__." MS_Result: $this->Result <br>\n";
		
		if ($this->Result)  {
			$n = mssql_num_rows($this->Result);
			if  ($n) {
				for($i = 0; $i < $n; $i++) {
          $rows[] = mssql_fetch_assoc($this->Result);
        }
				// echo "#".__LINE__." row: ".print_r($row, true)."<br>\n";
			}
			mssql_free_result($this->Result);
		}
		
		return $rows;
	}
	
	function get_projectsStatus($aWwsIds) {
    if (!is_resource($this->connid)) {
      return false;
    }
    
		if (!function_exists("mssql_connect") && !@dl("php_mssql.dll")) {
			return false;
		}
		
		if (!is_array($aWwsIds) || !count($aWwsIds)) {
			return false;
		}
    
    
		
		$mandant = "";
		$int1 = $WwsId; // "192161";
		// [Bearbeitungsstatus] 
		// [UnterBearbeitungsstatus]
		// [AbschlussStatus]
		$Query = "SELECT 
				A.Mandant,
        A.RechnungName as firmenname,
				A.Bearbeitungsstatus,
				A.AbschlussStatus,
				A.Auftragsnummer as vorgangsnr
		  		FROM  AuftragsKoepfe AS A
		        WHERE \n";
		
		for ($i = 0; $i < count($aWwsIds); $i++) {
			$Query.= ($i ? "\n OR " : "")." A.Auftragsnummer=\"".addslashes($aWwsIds[$i])."\"";
		}
		
		$this->Result = mssql_query($Query, $this->connid);
		// echo "#".__LINE__." MS_Result: $this->Result<pre>".fb_htmlEntities($Query)."</pre>\n";
		
		$rows = array();
		if ($this->Result)  {
			$n = mssql_num_rows($this->Result);
			if  ($n) {
				for($i = 0; $i < $n; $i++) {
					$rows[] = mssql_fetch_assoc($this->Result);
				}
			}
			mssql_free_result($this->Result);
		}
		return $rows;
	}
}

if (basename(__FILE__) == basename($_SERVER["PHP_SELF"])) {
	$wws = new WWW_DB();
	echo "#".__LINE__." Angebotsstatus <pre>".print_r($wws->get_RowById("193401"),true)."</pre>\n";
	
	
	
	$wws_cache_file = dirname(__FILE__)."/../cache/lastsync.cache.phs";
	$aWwsIds = array("193401","193155","190590","186327","191254");
	$aWwsStatien = $wws->get_projectsStatus($aWwsIds);
	file_put_contents($wws_cache_file, serialize($aWwsStatien));
	echo "#".__LINE__." Angebotsstatus <pre>".print_r($aWwsStatien,true)."</pre>\n";
}
// $wws->get_projectsStatus($aWwsIds);

//file_put_contents(serialize(
?>