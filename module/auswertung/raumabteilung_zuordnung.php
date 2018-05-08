<?php
ignore_user_abort(true);
set_time_limit(300);
$timeIn = time();
require(dirname(__FILE__)."/../../include/conf.php");
require(dirname(__FILE__)."/../../include/conn.php");
require(dirname(__FILE__)."/../../class/dbconn.class.php");
$SHOWDEBUG = (!empty($_GET["ShowDebug"]));

$log_exec_stat_file = dirname(__FILE__)."/raumzuordnung_lastupdate.log";
if (file_exists($log_exec_stat_file) && (time()-filemtime($log_exec_stat_file)<5)) {
	die("Die letzte Ausführung wurde eben erst gestartet und läuft evtll. noch.<br>Bitte warten Sie bis zur nächsten Ausführung mind. 5 Minuten!<br>\n");
}
$stat = date("Y-m-d H:i:s")." starting update";
file_put_contents($log_exec_stat_file, $stat);
if ($SHOWDEBUG) echo file_get_contents($log_exec_stat_file)."<br>\n";

#Abfrage Mitarbeiter mit Angaben zu GF, Bereich oder Abteilung
$aSQL["MA"] = "SELECT `immobilien_raum_id`, `gf`, `bereich`, `abteilung`, COUNT(*) Anzahl
FROM `".$_TABLE["mitarbeiter"]."`
WHERE (gf IS NOT NULL AND LENGTH(gf)>0)
OR (bereich IS NOT NULL AND LENGTH(bereich)>0)
OR (abteilung IS NOT NULL AND LENGTH(abteilung)>0)
GROUP BY `immobilien_raum_id`, `gf`, `bereich`, `abteilung`
ORDER BY `immobilien_raum_id`, Anzahl DESC, abteilung DESC, bereich DESC";

/*
#Abfrage Büroräume des Typs BUE, GBUE oder CAL, denen Arbeitsplätze zugewiesen sind
$aSQL["RaumAbt"] = "SELECT i.id, m.immobilien_raum_id FROM `mm_stamm_immobilien` i
LEFT JOIN mm_stamm_mitarbeiter m ON i.id = m.immobilien_raum_id
WHERE i.raum_typ IN ('BUE','GBUE','CAL') AND m.immobilien_raum_id IS NOT NULL
GROUP BY i.id
ORDER BY m.immobilien_raum_id";

#Abfrage Büroräume des Typs BUE, GBUE oder CAL, denen KEINE Arbeitsplätze zugewiesen sind
$aSQL["RaumNoAbt"] = "SELECT i.id, m.immobilien_raum_id FROM `mm_stamm_immobilien` i
LEFT JOIN mm_stamm_mitarbeiter m ON i.id = m.immobilien_raum_id
WHERE i.raum_typ IN ('BUE','GBUE','CAL') AND m.immobilien_raum_id IS NULL
GROUP BY i.id
ORDER BY m.immobilien_raum_id";
*/#

$sql = "UPDATE `".$_TABLE["immobilien"]."` SET stat = '0'";
$db->query($sql);

$last_raumid = "";
$r = $db->query($aSQL["MA"]);
$n = $db->num_rows($r);
$Group = array();
for ($i = 0; $i < $n; $i++) {

	$e = $db->fetch_assoc($r);
	$rid = $e["immobilien_raum_id"];

	$Group[] = $e;
	if (($last_raumid && $last_raumid!=$rid) || $i+1==$n) {
		$upd = false;
                foreach($Group as $v) if ($v["immobilien_raum_id"] == $rid) { $upd = $v; break; }
                if (!$upd) continue;
                
                $upd["stat"] = "";
		foreach($Group as $v) {
			$upd["stat"].= ($upd["stat"]?", ":"").$v["Anzahl"]."*".($v["abteilung"]?$v["abteilung"]:($v["bereich"]?$v["bereich"]:$v["gf"]));
		}

		//echo "<pre>".print_r($Group, 1)."</pre><br>\n";
		//echo "<pre>".print_r($upd, 1)."</pre><br>\n";
		//flush();
		$Group = array();
		$sql = "UPDATE `".$_TABLE["immobilien"]."` SET \n";
		$sql.= " `gf` = \"".$db->escape($upd["gf"])."\",\n";
		$sql.= " `bereich`=\"".$db->escape($upd["bereich"])."\",\n";
		$sql.= " `abteilung`=\"".$db->escape($upd["abteilung"])."\",\n";
		$sql.= " `stat`=\"".$db->escape($upd["stat"])."\"";
		$sql.= " WHERE `id` = \"".$db->escape($upd["immobilien_raum_id"])."\"";
		$db->query($sql);
		//if ($upd["immobilien_raum_id"] == "4035") {
		//	echo $sql; break;
		//}
	}

	if (($i+1) % 100 == 0) {
		$stat = date("Y-m-d H:i:s")." running. Executed Updates: ".($i+1)." ".intval($i*100/$n)."%. Dauer: ".(time()-$timeIn)."Sek";
		file_put_contents($log_exec_stat_file, $stat);
		if ($SHOWDEBUG) echo file_get_contents($log_exec_stat_file)."<br>\n";
		//sleep(100);
	}
	$last_raumid = $rid;
}

$stat = date("Y-m-d H:i:s")." finished. Executed Updates: ".($i).". Dauer: ".(time()-$timeIn)."Sek";
file_put_contents($log_exec_stat_file, $stat);
if ($SHOWDEBUG) echo file_get_contents($log_exec_stat_file)."<br>\n";
?>