<?php 
require "include/conf.php";
require "include/conn.php";
require "class/dbconn.class.php";
$LSName = (!empty($_REQUEST["LSName"])) ? $_REQUEST["LSName"] : "";
$LSQuery = (!empty($_REQUEST["q"])) ? $_REQUEST["q"] : false;
//print_r($LSQuery);
$LSOffset = (!empty($_REQUEST["LSOffset"])) ? $_REQUEST["LSOffset"] : 0;
$LSBoxId = (!empty($_REQUEST["LSBoxId"])) ? $_REQUEST["LSBoxId"] : "SelBoxItems";
$LSMultiple = (isset($_REQUEST["LSMultiple"])) ? (bool) $_REQUEST["LSMultiple"] : false;
$LSLimit = 30;
$LSTrackQuery = "";

$sSelect = "*";
$sFrom = "";
$sGroup = "";
$sOrder = "";
$sWhere = "";



$aQueryFields = array(
	array("qf"=>"ort", "sf"=>"ort"),
	array("sf"=>"unzugstermin", "qf"=>"unzugstermin"),
	array("sf"=>"terminwunsch", "qf"=>"terminwunsch")
);

$aResultFields = array(
	array("sf"=>"unzugstermin", "qf"=>"unzugstermin"),
	array("sf"=>"terminwunsch", "qf"=>"terminwunsch")
);

foreach($aQueryFields as $k => $v) {
	$qf = $v["qf"];
	$sf = $v["sf"];
	//echo "qf:$qf; sf:$sf; \$LSQuery[$qf]:".$LSQuery[$qf]."<br>\n";
	$LSQueryLen[$v["qf"]] = 0;
	if (isset($LSQuery[$qf]) && is_scalar($LSQuery[$qf]) && trim($LSQuery[$qf])) {
		$LSQuery[$qf] = trim($LSQuery[$qf]);
		$LSQueryLen[$qf] = strlen($LSQuery[$qf]);
		if ($LSQuery[$qf]) {
			$sWhere.= ($sWhere ? " AND " : "")." ".$sf." LIKE \"".MyDB::escape_string($LSQuery[$qf])."%\"";
			$LSTrackQuery.= "&q[".$qf."]=".$LSQuery[$qf];
		}
	}
}


$sFrom = " mm_umzuege \n";
$sSelect = "umzugstermin, terminwunsch \n";
$sGroup = "umzugstermin, terminwunsch \n";
$sOrder = "umzugstermin, terminwunsch \n";


$sSelect = "SELECT $sSelect \n";
if ($sWhere) $sWhere= "WHERE $sWhere \n";
if ($sGroup) $sGroup = "GROUP BY $sGroup \n";
if ($sOrder) $sOrder = "ORDER BY $sOrder \n";
$NumAll = $db->query_count($sFrom.$sWhere.$sGroup);

$sql= $sSelect." FROM ".$sFrom.$sWhere.$sGroup.$sOrder;
//echo $sql."<br>\n".MyDB::error()."<br>\n";
$sql.= "LIMIT ".intval($LSOffset).", $LSLimit";
$rows = $db->query_rows($sql);
$Num = count($rows);
//echo "#".__LINE__." ".MyDB::error()."<br>sql:".$sql."; rows:".$rows."<br>\n";
header("Content-Type: text/xml; charset=UTF-8");
echo '<?xml version="1.0" encoding="UTF-8" ?>
<Result type="success">
	<Attribute id="'.$LSBoxId.'" name="LSResultNum" value="'.$Num.'"></Attribute>
	<Attribute id="'.$LSBoxId.'" name="LSResultNumAll" value="'.$NumAll.'"></Attribute>
	<Attribute id="'.$LSBoxId.'" name="LSResultOffset" value="'.intval($LSOffset).'"></Attribute>
	<Attribute id="'.$LSBoxId.'" name="LSResultNextOffset" value="'.intval($LSOffset+$Num<$NumAll?$LSOffset+$Num:0).'"></Attribute>
	<Attribute id="'.$LSBoxId.'" name="LSResultLimit" value="'.$LSLimit.'"></Attribute>
	<Attribute id="'.$LSBoxId.'" name="LSResultName" value="'.$LSName.'"></Attribute>
	<Attribute id="'.$LSBoxId.'" name="LSResultQuery" value=""><![CDATA['.$LSTrackQuery.']]></Attribute>';
	
	if (!$LSOffset) echo '<Update id="'.$LSBoxId.'"><![CDATA[';
	else echo '<Update id="'.$LSBoxId.'" options="Append"><![CDATA[';
	
	$numResultFields = count($aResultFields);
	$aDates = array();
	for($i = 0; $i < count($rows); $i++) {
		$Rslt = "";
		$RsltDisplay = "";
		
		if ($rows[$i]["umzugstermin"]) $Rslt = $RsltDisplay = $rows[$i]["umzugstermin"];
		elseif ($rows[$i]["terminwunsch"]) $Rslt = $RsltDisplay = $rows[$i]["terminwunsch"];
		else continue;
		if (isset($aDates[$Rslt])) continue;
		$aDates[$Rslt] = 1;
		$RsltDisplay = trim($RsltDisplay, " \r\n\t;");
		echo '<div class="SelItem"><input xtype="checkbox" type="'.(!$LSMultiple?"radio":"checkbox").'" name="mabox" value="'.$Rslt.'"> '.$RsltDisplay.'</div>'."\n";
}
echo '<span onclick=alert(this.innerHTML)>Info<span style="display:;">'.$_SERVER["QUERY_STRING"].'<br>\n'.$sql.'<br>\n'.MyDB::error().'</span></span>';
echo ']]>';
if (!$LSOffset) echo '</Update>';
else echo '</Update>';
echo '
<LoadScript language="javascript" src="cdata"><![CDATA[
SelBox_initChilds("'.$LSBoxId.'");
//alert(document.getElementById("'.$LSBoxId.'"));
]]></LoadScript>
</Result>';
