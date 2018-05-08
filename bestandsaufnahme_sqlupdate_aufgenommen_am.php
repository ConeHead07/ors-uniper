<?php 
$chckAddField = "aufgenommen_am";
$aTableCheck = array(
	$_TABLE["mitarbeiter"],
	$_TABLE["mitarbeiter"]."_geloescht",
	$_TABLE["immobilien"],
	$_TABLE["immobilien"]."_geloescht",
	$_TABLE["abteilungen"],
	$_TABLE["hauptabteilungen"]
);

foreach($aTableCheck as $t) {
	$sql = "SHOW FIELDS FROM `".$t."` like \"$chckAddField\"";
	$rows = $db->query_rows($sql);
	if ($db->error()) echo $db->error()."<br>\n".$sql."<br>\n";
	if (empty($rows)) {
		//echo $chckAddField." existiert nicht in Tabelle $t!<br>\n";
		$sql = "ALTER TABLE `".$t."` ADD `$chckAddField` DATETIME NULL";
		$db->query($sql);
		if ($db->error()) echo $db->error()."<br>\n".$sql."<br>\n";
		//else echo $chckAddField." wurde der Tabelle $t hinzugefügt!<br>\n";
	} //else echo $chckAddField. " existiert in Tabelle $t!<br>\n";
}

?> 