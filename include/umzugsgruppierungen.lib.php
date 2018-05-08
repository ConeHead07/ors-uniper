<?php 

function getGruppierungen($aid) {
	global $db;
	
	$sql = 'SELECT "from" lnk, u.* FROM mm_umzuege_gruppierungen g ' . PHP_EOL
		  .' LEFT JOIN mm_umzuege u ON (g.refid = u.aid) ' . PHP_EOL
		  .' WHERE g.aid = ' . (int)$aid . PHP_EOL
		  .' UNION SELECT "back" lnk,  u.* FROM mm_umzuege_gruppierungen g ' . PHP_EOL
		  .' LEFT JOIN mm_umzuege u ON (g.aid = u.aid) ' . PHP_EOL
		  .' WHERE g.refid = ' . (int)$aid;
		  
	$rows = $db->query_rows($sql);
	foreach($rows as &$row) {
		$row["Geprueft"]   = get_iconStatus($row["geprueft"], $row["geprueft_am"], $row["geprueft_von"], 'Geprueft');
		$row["Genehmigt"]  = get_iconStatus($row["genehmigt_br"], $row["genehmigt_br_am"], $row["genehmigt_br_von"]);
		$row["Abgeschlossen"] = get_iconStatus($row["abgeschlossen"], $row["abgeschlossen_am"], $row["abgeschlossen_von"]);
	}
	return $rows;
}

function umzugsgruppierungen_speichern($AID, $aids) {
	global $db;
	
	if (is_scalar($aids)) {
		$aids = (empty($aids) ? array() : explode(",", $aids));
	}
	if (is_array($aids)) {
		$aids = array_unique($aids);
		sort($aids);
	} else {
		return;
	}
	
	$SQL = sprintf('DELETE FROM mm_umzuege_gruppierungen WHERE aid=%s', (int)$AID );
	$db->query( $SQL );
	
	
	if (!count($aids)) {
		return;
	}
	
	$inserts = array();
	foreach($aids as $_id) $inserts[] = "($AID, $_id)";
	
	$sql = 'INSERT INTO mm_umzuege_gruppierungen(aid,refid) VALUES' . implode(',', $inserts);
	$db->query($sql);
}
