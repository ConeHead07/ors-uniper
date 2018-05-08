<?php 
require("../header.php");
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Aktualisierungsabfrage: APNR</title>
</head>

<body>
<pre>
<?php
$count_updates = 0;

$SQL = "SELECT u.aid, a.umzugsstatus, a.abgeschlossen_am, u.maid, u.name uname, u.vorname uvorname, u.ziel_arbeitsplatznr, m.id, m.name, m.vorname, m.arbeitsplatznr
FROM `mm_umzuege_arbeitsplaetze` u
LEFT JOIN `mm_stamm_mitarbeiter` m ON u.maid = m.id
LEFT JOIN `mm_umzuege` a ON u.aid = a.aid
WHERE 
a.umzugsstatus = \"abgeschlossen\"
AND u.ziel_arbeitsplatznr IS NOT NULL
AND u.ziel_arbeitsplatznr > 0
AND (
  m.arbeitsplatznr is null
  or 
  m.arbeitsplatznr = 0
  or
  m.arbeitsplatznr != u.ziel_arbeitsplatznr
)
ORDER BY a.abgeschlossen_am DESC , u.maid
LIMIT 300";

$rows = $db->query_rows($SQL);

if ($rows && is_array($rows)) {
	foreach($rows as $row) {
		$sql_update = "UPDATE `mm_stamm_mitarbeiter` SET arbeitsplatznr = \"".$db->escape($row["ziel_arbeitsplatznr"])."\" WHERE id = \"".$db->escape($row["maid"])."\"";
		echo $sql_update.";\n";
		
		$db->query($sql_update);
		if (!$db->error()) {
			echo "Update-Befehl wurde erfolgreich ausgeführt!\n\n";
			if ($db->affected_rows()) ++$count_updates;
		} else {
			
			die("Fehler bei Update: ".$db->error()."<br>\nupdate_sql:".$update_sql."<br>\n");
		}
	}
	echo "Von ".count($rows)." Datensätzen wurden ".$count_updates." aktualisiert!<br>\n";
} else {
	echo "Abfrage lieferte keine Treffer!<br>\n";
	echo $db->error()."\n";
	echo $SQL;
}


?>
</pre>
</body>
</html>