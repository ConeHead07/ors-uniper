<?php 
include_once(dirname(__FILE__)."/../../include/conn.php");
include_once(dirname(__FILE__)."/../../include/conf.php");

$report = "";
$SQL = "SELECT 
`pid`,
`Vorgangsnr`,
`Projekt`,
`Status`,
`Eingangsdatum`,
`Sollstunden`,
`Angebotssumme`,
`Mitarbeiter`,
`ADM`,
`Kunde` 
FROM `".$_TABLE["projects"]."`\n";
$SQL.= "WHERE status = \"Angebot\"";
$r = MyDB::query($SQL, $connid);
if ($r) {
	$n = MyDB::num_rows($r);
	echo "#".__LINE__." n:$n <br>\n";
	if ($n) {
		$report.= "<table border=0 bordercolor=#000000 cellpadding=2 cellspacing=0 style=\"border-spacing:0px;border-collapse:collapse;border-left:1px solid #000;border-top:1px solid #000;font-size:12px;font-family:Arial;\">";
		$report.= "<tr bgcolor=#ffd700><td style=\"border-right:1px solid #000;border-bottom:1px solid #000;\" rowspan=2 valign=bottom align=right><strong>#</strong></td><td style=\"border-right:1px solid #000;border-bottom:1px solid #000;\" colspan=4><strong>KUNDE</strong></td></tr>\n";
		$report.= "<tr bgcolor=#ffd700><td style=\"border-right:1px solid #000;border-bottom:1px solid #000;\"><strong>ADM</strong></td><td style=\"border-right:1px solid #000;border-bottom:1px solid #000;\"><strong>Eingangsdatum</strong></td><td style=\"border-right:1px solid #000;border-bottom:1px solid #000;\" align=right><strong>Angebotssumme</strong></td><td style=\"border-right:1px solid #000;border-bottom:1px solid #000;\"><strong>Projekt</strong></td></tr>\n";
		
		for($i = 0; $i < $n; $i++) {
			$bgcolor = ($i%2 ? "#fcf8c9" : "#ffffff");
			$e = MyDB::fetch_assoc($r);
			$url2Project = $_CONF["WebRoot"]."?s=projects&id=".$e["pid"]."&WWSID=".$e["Vorgangsnr"];
			$report.= "<tr bgcolor=$bgcolor><td style=\"border-right:1px solid #000;border-bottom:1px solid #000;\" rowspan=2 valign=bottom align=right><strong>".($i+1)."</strong></td><td style=\"border-right:1px solid #000;border-bottom:1px solid #000;\" colspan=4><strong>".$e["Kunde"]."</strong></td></tr>\n";
			$report.= "<tr bgcolor=$bgcolor>\n";
			$report.= "<td style=\"border-right:1px solid #000;border-bottom:1px solid #000;\">".$e["ADM"]."</td>\n";
			$report.= "<td style=\"border-right:1px solid #000;border-bottom:1px solid #000;\">".$e["Eingangsdatum"]."</td>\n";
			$report.= "<td style=\"border-right:1px solid #000;border-bottom:1px solid #000;\" align=right>".number_format($e["Angebotssumme"], 2, ",",".")."</td>\n";
			$report.= "<td style=\"border-right:1px solid #000;border-bottom:1px solid #000;\"><a href=\"$url2Project\">".$e["Projekt"]." <em>(WWSID:".$e["Vorgangsnr"].")</em></a></td>\n";
			$report.= "</tr>\n";
		}
		$report.= "</table>";
	}
	MyDB::free_result($r);
} else {
	echo MyDB::error()."<br>\n";
}
echo $report;
?>