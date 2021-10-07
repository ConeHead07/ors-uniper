<?php 
require_once("../header.php");

echo "<pre>\n";
$csvEtagenUpdate = <<<HereDocCsvEtagenUpdate
etage alt	etage neu
1	OG01
1. OG	OG01
1.OG	OG01
1.UG	UG01
10. OG	OG10
10OG	OG01
11. OG
11OG	OG11
12. OG	OG12
12OG	OG12
13. OG	OG13
13OG	OG13
14. OG	OG14
15. OG	OG15
16. OG	OG16
17. OG	OG17
18. OG	OG18
19. OG	OG19
2. OG	OG02
2.OG	OG02
2.UG	UG02
20. OG	OG20
21. OG	OG21
22. OG	OG22
23.OG	OG23
24.OG	OG24
3. OG	OG03
3.OG	OG03
3.UG	UG03
4. OG	OG04
4.OG	OG04
4.UG	UG04
5. OG	OG05
5.OG	OG05
6. OG	OG06
6.OG	OG06
7. OG	OG07
7.OG	OG07
8. OG	OG08
8.OG	OG08
9. OG	OG09
9.OG	OG09
E1	OG01
E2	OG02
E3	OG03
E4	OG04
E5	OG05
EG	EG
KG	UG01
U1	UG01
U2	UG02
U3	UG03
HereDocCsvEtagenUpdate;

$lines = explode("\n", $csvEtagenUpdate);
$fields = array_flip(explode("\t", trim($lines[0])));
$keys = explode("\t", trim($lines[0]));

$aFldVals = array();
$sqlList = "";

for($i = 1; $i < count($lines); $i++) {
	$values = explode("\t", $lines[$i]);
	$aFldVals = array_combine  ($keys  ,$values  );
	foreach($aFldVals as $k => $v) $aFldVals[$k] = trim($v);
	
	$sql = "";
	if ($aFldVals["etage neu"]) {
		
		$sql = "#\n";
		$sql.= "UPDATE `".$_TABLE["mitarbeiter"]."` SET \n";
		$sql.= "etage = \"".$db->escape($aFldVals["etage neu"])."\" \n";
		$sql.= "WHERE etage LIKE \"".$db->escape($aFldVals["etage alt"])."\"\n";
		$db->query($sql); if ($db->error()) { echo $db->error()."\n$sql\n"; break; }
		$sqlList.= $sql;
		
		$sql = "#\n";
		$sql.= "UPDATE `".$_TABLE["immobilien"]."` SET \n";
		$sql.= "etage = \"".$db->escape($aFldVals["etage neu"])."\" \n";
		$sql.= "WHERE etage LIKE \"".$db->escape($aFldVals["etage alt"])."\"\n";
		$db->query($sql); if ($db->error()) { echo $db->error()."\n$sql\n"; break; }
		$sqlList.= $sql;
		
	}
}
echo $sqlList;


print_r($aFldVals);
echo $csvEtagenUpdate;

echo "</pre>\n";
