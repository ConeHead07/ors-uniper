<?php 
require("bestandsaufnahme_data.class.php");

$BereichData = new bereich();
$AbtlgData = new abteilung();
$GFData = new gf();
$MaData = new mitarbeiter();

$RowsError = array();
$RowsSaved = array();
if (!isset($error)) $error = "";

$SentRows = array();
if (isset($_POST["id"]) && is_array($_POST["id"])) foreach($_POST as $k => $v) {
	if (is_array($v)) foreach($_POST["id"] as $i => $v_id) if (isset($v[$i])) $SentRows[$i][$k] = $v[$i];
}

foreach($SentRows as $i => $row) if ($row["imo_raum_id"]) {
	$SentRows[$i]["raum"] = $SentRows[$i]["imo_raum_id"];
	if ($SentRows[$i]["imo_raum_id"]) $raumdaten = $MaData->getRaum($SentRows[$i]["imo_raum_id"]);
	if (is_array($raumdaten)) $SentRows[$i] = array_merge($SentRows[$i], $raumdaten);
	
	if (!isset($SentRows[$i]["ersthelfer"])) $SentRows[$i]["ersthelfer"] = "Nein";
	if (!isset($SentRows[$i]["raeumungsbeauftragter"])) $SentRows[$i]["raeumungsbeauftragter"] = "Nein";
}

foreach($SentRows as $e) {
	$MaData->error = "";
	if ($MaData->check($e)) {
		$e["id"] = $MaData->save($e, $e["id"]);
		if (!$MaData->error) $RowsSaved[$e["id"]] = $e["rownr"];
	}
	if ($MaData->error) {
		$RowsError[$e["id"]] = "<div>Fehler beim Speichern der Daten in Zeile ".$e["rownr"].":<br>\n";
		$RowsError[$e["id"]].= $MaData->error."</div>";
	}
}
if (count($RowsError)) $error.= "Beim Speichern sind Fehler in ".count($RowsError)." Zeilen aufgetreten!<br>\n";

?>