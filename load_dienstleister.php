<?php 
require("header.php");
require_once($InclBaseDir."dienstleister.inc.php");
require_once($MConf["AppRoot"].$MConf["Inc_Dir"]."php_json.php");

$query = getRequest("query","");
$limit = (int)getRequest("limit", 15);
$SBBoxId = getRequest("SBBoxId","");

$where = '';
foreach(explode(' ', $query) as $_q) {
    $where.= ' Ort LIKE "'.$db->escape($_q).'%" ' 
           . ' OR Firmenname LIKE "'.$db->escape($_q).'%" '
           . ' OR Ansprechpartner LIKE "' . $db->escape($_q) . '%"';
}

$sqlNumAll = "SELECT COUNT(1) count FROM mm_dienstleister ";
if ($where) $sqlNumAll.= ' WHERE ' . $where;
$NumAll = $db->query_one($sqlNumAll);


$sql = "SELECT * FROM mm_dienstleister";
if ($where) $sql.= ' WHERE ' . $where;
$sql.= " ORDER BY Ort, Firmenname LIMIT " . $limit;

$rows = $db->query_rows($sql);
$num = count($rows);
$error = $db->error();
$jsonData = "[";
for($i = 0; $i < count($rows); $i++) {
	$e = $rows[$i];
	$jsonData.= ($i?",\n":"\n")."\t {\n";
	$jsonData.= "\t\t\"id\" : \"".$e["dienstleister_id"]."\",\n";
        
        foreach($e as $k => $v) {
            $jsonData.= "\t\t\"$k\" : \"".json_escape($v)."\",\n";
        }	
	
	$jsonData.= "\t\t\"value\" : \"".json_escape($e["dienstleister_id"])."\",\n";
	$jsonData.= "\t\t\"content\" : \"".json_escape($e["Ort"]." ".$e["Firmenname"]." ".$e["Ansprechpartner"])."\"\n";
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
	echo "optionsDienstleister = {\n";
	echo "\tQuery:\"".json_escape($query)."\",\n";
	echo "\tNumAll:$NumAll,\n";
	echo "\tSize:$num,\n";
	echo "\tData:$jsonData\n";
	echo "};\n";
	echo "SelBox_loadData('".$SBBoxId."', optionsDienstleister[\"Data\"]);\n";
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
