<?php 

$wws_upd_mid_logfile = dirname(__FILE__)."/cache/upd_mid.log.phs";
$mysql_conn_script = dirname(__FILE__)."/../../include/conn.php";
$mssql_conn_script = dirname(__FILE__)."/class/wwssync.class.php";
require($mysql_conn_script);
require($mssql_conn_script);
$DbgMsg = "<strong>Start WWS-Mandanten-Synchronisation: ".date("Y-m-d H:i:s")."</strong><br><br>\n";
$ShowDbgMsg = true; //(!empty($_GET["DBG"])) ? $_GET["DBG"] : false; // 
$num_updates_soll = 0;
$num_updates_done = 0;
$num_unique_ids = 0;
$num_notunique_ids = 0;
$num_no_wwsentries = 0;
$sUneindeutigeWwsIds = "";
$sEindeutigeWwsIds = "";
$sProjekteOhneWwsGegenPart = "";
$GroupUpdateByIds = array();

$DoUpdConfirm = (isset($_GET["DoUpdConfirm"]) && !empty($_GET["DoUpdConfirm"]));

if (!$connid) {
	$sLogErr.= date("Y-m-d H:i:s")." Fehler bei Synchronisation mit WWS: Fehlender Mysql-DB-Connect!\n";
}

WWW_DB::check_mssql($errno);

if ($errno) {
	$sLogErr.= date("Y-m-d H:i:s")." ".$aErrno2Txt[$errno]." ".$enableNotice."\n";
}

if ($sLogErr) {
	file_put_contents("system_alerts.log", $sLogErr, FILE_APPEND);
	$DbgMsg.= "UPDATE-ABBRUCH:<br>\n".$sLogErr."<br>\n";
}

if (!$sLogErr) {
	$SQL = "SELECT vorgangsnr FROM `".$_TABLE["projects"]."`\n";
	$SQL.= "WHERE mid IS NULL OR LENGTH(mid) = 0 OR mid = 0";
	$r = MyDB::query($SQL, $connid);
	if ($r) {
		$n = MyDB::num_rows($r);
		for ($i = 0; $i < $n; $i++) {
			$e = MyDB::fetch_array($r, MYSQL_ASSOC);
			$aWwsIds[]= $e["vorgangsnr"];
		}
		MyDB::free_result($r);
	} else echo MyDB::error()." || $SQL <br>\n";
	
	$DbgMsg.= "#".__LINE__." Mandanten-Updatesuche f�r ".count($aWwsIds)." Projekteintr�ge!<br>\n";
	
	if (!$errno) {
		$wws = new WWW_DB();
    
		$aWwsStatien = $wws->get_projectsStatus($aWwsIds);
    if (empty($aWwsStatien) ) $aWwsStatien = array();
		file_put_contents($wws_upd_mid_logfile, serialize($aWwsStatien));
		
    $DbgMsg.= "#".__LINE__." Es wurden ".count($aWwsIds)." WWS-Einträge gefunden!<br>\n";
	  
    $aUniqueChckWwsids = array();
    
		for ($i = 0; $i < count($aWwsStatien); $i++) {
      $r_wwsid = $aWwsStatien[$i]["vorgangsnr"];
      if (!isset($aUniqueChckWwsids[$r_wwsid])) {
        $aUniqueChckWwsids[$r_wwsid] = 1;
      } else {
        $aUniqueChckWwsids[$r_wwsid]++;
      }
		}
		
		for ($i = 0; $i < count($aWwsStatien); $i++) {
	    
      $r_wwsid = $aWwsStatien[$i]["vorgangsnr"];
			$r_mid = $aWwsStatien[$i]["Mandant"];
			$r_fa = $aWwsStatien[$i]["firmenname"];
      
      if ($aUniqueChckWwsids[$r_wwsid] == 1) {
        if (empty($GroupUpdateByIds[$r_mid])) $GroupUpdateByIds[$r_mid] = "\"".MyDB::escape_string($r_wwsid)."\"";
        else $GroupUpdateByIds[$r_mid].= ",\"".MyDB::escape_string($r_wwsid)."\"";
        
        // F�rs Log-Protokoll
        $num_updates_soll++;
        $num_unique_ids++;
        $sEindeutigeWwsIds.= "# WWSID=$r_wwsid: MandantenID=$r_mid, Firma=$r_fa<br>\n";
      } else {
        
        // F�rs Log-Protokoll
        $num_notunique_ids++;
        $sUneindeutigeWwsIds.= "# WWSID=$r_wwsid: MandantenID=$r_mid, Firma=$r_fa, WWS-Eintr&auml;ge mit dieser ID:".$aUniqueChckWwsids[$r_wwsid]."<br>\n";
      }
    }
    
    for($i = 0; $i < count($aWwsIds); $i++) {
      $r_wwsid = $aWwsIds[$i];
      if (!isset($aUniqueChckWwsids[$r_wwsid])) {
        $num_no_wwsentries++;
        $sProjekteOhneWwsGegenPart.= "# WWSID=$r_wwsid &nbsp; //<span style=\"color:#c9c9c9;\">Not Found in WWS!</span><br>\n";
      }
    }
    
    $DbgMsg.= "#".__LINE__." <strong>Auswertung: Abgleich der Projekt-DB-Einträge ohne Mandanten-ID mit WWS-Einträge</strong><br>\n";
    $DbgMsg.= "#".__LINE__." <strong>Zusammenfassung:</strong> <br>\n<br>\n";
    $DbgMsg.= "#".__LINE__." Es liegen $num_notunique_ids Einträge mit uneindeutiger WwsId vor:<br>\n";
    $DbgMsg.= "#".__LINE__." Es liegen $num_unique_ids Einträge mit eindeutiger WwsId vor:<br>\n";
    $DbgMsg.= "#".__LINE__." Es liegen $num_no_wwsentries Projekt-Einträge Ohne WWS-Eintrag vor<br>\n";
    
    $DbgMsg.= "#<br>\n";
    $DbgMsg.= "#".__LINE__." <strong>Details:</strong> <br>\n<br>\n";
    $DbgMsg.= "#".__LINE__." Es liegen $num_notunique_ids Einträge mit uneindeutiger WwsId vor:<br>\n";
    $DbgMsg.= $sUneindeutigeWwsIds;
    
    $DbgMsg.= "#<br>\n";
    $DbgMsg.= "#".__LINE__." Es liegen $num_unique_ids Einträge mit eindeutiger WwsId vor:<br>\n";
    $DbgMsg.= $sEindeutigeWwsIds;
    
    $DbgMsg.= "#<br>\n";
    $DbgMsg.= "#<br>\n";
    $DbgMsg.= "#".__LINE__." Es liegen $num_no_wwsentries Projekt-Einträge Ohne WWS-Eintrag vor<br>\n";
    $DbgMsg.= $sProjekteOhneWwsGegenPart;
    
		$DbgMsg.= "#".__LINE__." GroupUpdateByIds:<pre>".print_r($GroupUpdateByIds,true)."</pre>\n";
		$DbgMsg.= "#".__LINE__." aWwsStatien:<pre>".print_r($aWwsStatien,true)."</pre>\n";
    
		foreach($GroupUpdateByIds as $r_mid => $r_in_wwsids) {
      $num_found = 0;
      $SQL = "SELECT COUNT(*) AS Num FROM `".$_TABLE["projects"]."`\n";
			$SQL.= "WHERE vorgangsnr IN (".$r_in_wwsids.") AND (mid IS NULL OR mid = NULL OR length(mid) = 0 OR mid = 0)\n";
      $r = MyDB::query($SQL, $connid);
      if ($r) {
        list($num_found) = MyDB::fetch_assoc($r);
        MyDB::free_result($r);
      }
      $DbgMsg.= "#".__LINE__." Update-Check - Gefundene Datensätze: $num_found!<br>\n";
      $DbgMsg.= "#".__LINE__." Update-Check - ".(MyDB::error()?"ERROR:".MyDB::error():" No-Error!")."<br>\n";
      $DbgMsg.= "#".__LINE__." Update-Check - SQL:<pre>".fb_htmlEntities($SQL)."</pre>\n";
      
      $SQL = "UPDATE `".$_TABLE["projects"]."`\n";
			$SQL.= "SET mid = \"".MyDB::escape_string($r_mid)."\"\n";
			$SQL.= "WHERE vorgangsnr IN (".$r_in_wwsids.") AND (mid IS NULL OR mid = NULL OR length(mid) = 0 OR mid = 0)\n";
      
      if ($DoUpdConfirm) {
        // Update-SQL-Ausführung
  			$r = MyDB::query($SQL, $connid);
  			$n = MyDB::affected_rows();
  			$num_updates_done+= $n;
        $DbgMsg.= "#".__LINE__." Update-Ausführung - aktualisierte Datensätze: $n!<br>\n";
        $DbgMsg.= "#".__LINE__." Update-Ausführung - ".(MyDB::error()?"ERROR:".MyDB::error():" No-Error!")."<br>\n";
      } else {
        $DbgMsg.= "#".__LINE__." Update-Simulation - UPDATE wurde NICHT ausgeführt!<br>\n";
        $DbgMsg.= "#".__LINE__." Update-Simulation - Zur Update-Ausführung, klicke bitte <a href=\"".$_SERVER["PHP_SELF"]."?DoUpdConfirm=1\">hier</a>!<br>\n";
      }
      $DbgMsg.= "#".__LINE__." SQL Update-MID:<pre>".fb_htmlEntities($SQL)."</pre>\n";
		}
	}
}

$DbgMsg.= "#".__LINE__." num_updates_soll: ".$num_updates_soll."<br>\n";
$DbgMsg.= "#".__LINE__." num_updates_done: ".$num_updates_done."<br>\n";
if ($ShowDbgMsg) echo $DbgMsg;
?>
