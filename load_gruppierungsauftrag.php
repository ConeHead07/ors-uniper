<?php 
require("header.php");
$log = function($l) {
	return;	
	echo "#$l<br>\n";
	if (func_num_args() > 1) echo print_r( array_slice(func_get_args(), 1),1) . "<br>\n";
};

require_once($InclBaseDir."umzugsantrag.inc.php");
require_once($InclBaseDir."umzugsmitarbeiter.inc.php");
require_once($InclBaseDir."stdlib.php");
require_once($InclBaseDir."umzugsantrag_stdlib.php");

require_once($MConf["AppRoot"].$MConf["Inc_Dir"]."php_json.php");
$CUA = &$_CONF['umzugsantrag'];
$CUM = &$_CONF["umzugsmitarbeiter"];
$log(__LINE__);
$query = trim(getRequest("query",""));
$limit = (int)getRequest("limit", 15);
$SBBoxId = getRequest("SBBoxId","");
$log(__LINE__);

$sqlSelect = 'U.*' 
            .', CONCAT(vg.stadtname, " ", vg.adresse) von ' 
            .', CONCAT(ng.stadtname, " ", ng.adresse) nach '
			.', if(length(umzugstermin > 1), umzugstermin, terminwunsch) termin';
if (is_numeric($query)) {
	$_cmp = (int)$query;
	$sqlSelect.= ', if(POSITION("' . $query . '" in aid) > 0, 
	                   if (' . $query . ' = aid, 
					      3,
					      IF(POSITION("' . $query . '" in aid) = 1, 2, 1)
                       ),
						0
				    ) rank';
	$orderby = 'aid';
}
elseif (preg_match('/^([0-9]{1,2}\.[0-9]{1,2}(\.[0-9]{2,4}?))$/', $query, $m)) {
	// die(print_r($m,1));
	$d = explode('.', $m[1]);
	if (strlen($d[0]) < 2) $d[0] = '0'. $d[0];
	if (strlen($d[1]) < 2) $d[1] = '0'. $d[1];
	if (count($d)>2 && strlen($d[2])===2) $d[2] = '20' . $d[2];
	$_cmp = implode('.', $d);
	$sqlSelect.= ', if(POSITION("' . $_cmp . '" in DATE_FORMAT(U.antragsdatum, "d.m.Y")) > 0, 
                      IF(POSITION("' . $_cmp . '" in DATE_FORMAT(U.antragsdatum, "d.m.Y")) = 1, 2, 1), 
                      if(umzugstermin, 
						if(POSITION("' . $_cmp . '" in DATE_FORMAT(U.umzugstermin, "d.m.Y")) > 0, 
                           IF(POSITION("' . $_cmp . '" in DATE_FORMAT(U.umzugstermin, "d.m.Y")) = 1, 2, 1),0), 
                        if(POSITION("' . $_cmp . '" in U.terminwunsch) > 0, 
                           IF(POSITION("' . $_cmp . '" in U.terminwunsch) = 1, 2, 1),
                           0)								  
                      )
                   ) rank';
} else {
	$sqlSelect.= ', 0 rank';
}
$log(__LINE__);

$where = '';
//aid, name, gebaeude, zielgebaeude, umzug, etage, antragsdatum, umzugstermin, terminwunsch, raumnr, bemerkungen
foreach(explode(' ', $query) as $_q) {
    $where.= ' aid LIKE "'.$db->escape($_q).'%" ' 
           . ' OR date_format(U.antragsdatum, "d.m.Y") LIKE "'.$db->escape($_q).'%" '
           . ' OR if(U.umzugstermin, date_format(U.umzugstermin, "d.m.Y"), U.terminwunsch) LIKE "'.$db->escape($_q).'%" '
           . ' OR U.etage LIKE "' . $db->escape($_q) . '%"'
           . ' OR U.raumnr LIKE "' . $db->escape($_q) . '%"'
           . ' OR U.name LIKE "' . $db->escape($_q) . '%"'
           . ' OR U.bemerkungen LIKE "%' . $db->escape($_q) . '%"'
           . ' OR U.umzug LIKE "' . $db->escape($_q) . '"';
}
$log(__LINE__);

$sqlFrom  = "`".$CUA["Table"]."` U LEFT JOIN `".$CUM["Table"]."` M USING(aid)\n" 
           ." LEFT JOIN mm_stamm_gebaeude g  ON U.gebaeude = g.id \n"
           ." LEFT JOIN mm_stamm_gebaeude vg ON U.von_gebaeude_id = vg.id \n"
           ." LEFT JOIN mm_stamm_gebaeude ng ON U.nach_gebaeude_id = ng.id \n";

$sqlNumAll = sprintf("SELECT COUNT(1) count FROM %s", $sqlFrom);
if ($where) $sqlNumAll.= ' WHERE ' . $where;
$NumAll = $db->query_one($sqlNumAll);

$sql = sprintf("SELECT %s FROM %s", $sqlSelect, $sqlFrom);
if ($where) $sql.= ' WHERE ' . $where;
$sql.= " ORDER BY rank desc, antragsdatum LIMIT " . $limit;
$log(__LINE__, $sql);

$rows = $db->query_rows($sql);
$log(__LINE__);

$num = count($rows);
$error = $db->error();
$jsonData = "[";
$log(__LINE__, $rows);

for($i = 0; $i < count($rows); $i++) {
	$e = $rows[$i];
	
	$jsonData.= ($i?",\n":"\n")."\t {\n";
	$jsonData.= "\t\t\"id\" : \"".$e["aid"]."\",\n";
    
	$e["Geprueft"]   = get_iconStatus($e["geprueft"], $e["geprueft_am"], $e["geprueft_von"], 'Geprueft');
	$e["Genehmigt"]  = get_iconStatus($e["genehmigt_br"], $e["genehmigt_br_am"], $e["genehmigt_br_von"]);
	$e["Abgeschlossen"] = get_iconStatus($e["abgeschlossen"], $e["abgeschlossen_am"], $e["abgeschlossen_von"]);
	
	foreach($e as $k => $v) {
		$jsonData.= "\t\t\"$k\" : \"".json_escape($v)."\",\n";
	}
	
	$jsonData.= "\t\t\"value\" : \"".json_escape($e["aid"])."\",\n";
	$jsonData.= "\t\t\"content\" : \"".json_escape(
					$e["aid"] . " " 
				  . $e["termin"] . " "
				  . $e["ort"] . " "
				  . $e["etage"] . " " 
				  . $e["raumnr"]." " 
				  . $e["antragsdatum"] . " "
				  . $e["umzugsstatus"]/**/)."\"\n";
	
	$jsonData.= "\t}";
	
}
$jsonData.= "]";

//echo "<pre>\nvar Raeume = ".$jsonData.";\n</pre>";

$resultFormat = getRequest("resultFormat", "JS");

switch($resultFormat) {
	case "XML":
	header("Content-Type: text/xml; charset=ISO-8859-1");
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n";
	echo "<Result type=\"success\">\n";
	echo "<LoadScript language=\"javascript\" src=\"cdata\"><![CDATA[\n";
	echo "/* Err:".$error." */\n";
	echo "/* sql:".$sql." */\n";
	echo "optionsGruppierungsauftrag = {\n";
	echo "\tQuery:\"".json_escape($query)."\",\n";
	echo "\tNumAll:$NumAll,\n";
	echo "\tSize:$num,\n";
	echo "\tData:$jsonData\n";
	echo "};\n";
	echo "SelBox_loadData('".$SBBoxId."', optionsGruppierungsauftrag[\"Data\"]);\n";
	echo "]]></LoadScript>\n";
	echo "</Result>";
	break;
	
	case "JS":
	default:
	header("Content-Type: text/javascript; charset=ISO-8859-1");
	echo "/* Err:".$error." */\n";
	echo "/* sql:".$sql." */\n";
	echo "optionsDienstleister = {\n";
	echo "\tQuery:\"".json_escape($input)."\",\n";
	echo "\tNumAll:$NumAll,\n";
	echo "\tSize:$num,\n";
	echo "\tData:$jsonData\n";
	echo "};\n";
	echo "SelBox_loadData('".$SBBoxId."', optionsDienstleister[\"Data\"]);\n";
}

$log(__LINE__);
