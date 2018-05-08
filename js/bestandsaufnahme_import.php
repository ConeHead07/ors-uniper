<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Untitled</title>
</head>

<body>
<?php 

include("include/conf.php");
include("include/conn.php");

$sql = "SELECT i.ort, i.gebaeude, i.etage, i.raumnr, m.arbeitsplatznr, m.name, m.vorname, m.extern, m.extern_firma, m.ersthelfer, m.raeumungsbeauftragter, m.anmerkung, m.anrede, m.mitarbeiter, m.gebaeude, m.etage, m.raumnr, m.gf, m.bereich, m.abteilung
FROM `mm_stamm_mitarbeiter` m
LEFT JOIN `mm_stamm_immobilien` i ON ( m.immobilien_raum_id = i.id )
WHERE i.gebaeude
IN (
'NL_NO_AT', 'ZV_SEE_5'
)";

$aRpl = array("\r"=> "\\r", "\n"=>"\\n", "\""=>"\\\"");
$r = MyDB::query($sql);
if ($r) {
	$n = MyDB::num_rows($r);
	for($i = 0; $i < $n; $i++) {
		$e = MyDB::fetch_array($r, MyDB::NUM);
		for($j = 0; $j < count($e); $j++)
		echo ($j?",":"")."\"".strtr($e[$j], $aRpl)."\"";
	}
	echo "\n";
}

?>
</body>
</html>
