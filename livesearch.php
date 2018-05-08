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


switch($LSName) {
	case "Dienstleister":
	$aQueryFields = array(
		array("qf"=>"Firmenname", "sf"=>"Firmenname"),
		array("qf"=>"Ort", "sf"=>"Ort"),
		array("qf"=>"Ansprechpartner", "sf"=>"Ansprechpartner"),
	);
	
	$aResultFields = array(
		array("sf"=>"dienstleister_id", "qf"=>false),
		array("sf"=>"Firmenname", "qf"=>"Firmenname"),
		array("sf"=>"Ansprechpartner", "qf"=>"Ansprechpartner"),
		array("sf"=>"Ort", "qf"=>"ort"),
		array("sf"=>"Strasse", "qf"=>"Strasse"),
		array("sf"=>"PLZ", "qf"=>"PLZ"),
		array("sf"=>"Handy", "qf"=>"Handy"),
		array("sf"=>"Festnetz", "qf"=>"Festnetz"),
		array("sf"=>"Email", "qf"=>"Email"),
	);
	break;
    
	case "Mitarbeiter":
	$aQueryFields = array(
		array("qf"=>"name", "sf"=>"name"),
		array("qf"=>"vorname", "sf"=>"vorname"),
		array("qf"=>"ort", "sf"=>"ort"),
		array("qf"=>"gebaeude", "sf"=>"gebaeude"),
		array("qf"=>"raumnr", "sf"=>"raumnr"),
		array("qf"=>"abteilung", "sf"=>"abteilung")
	);
	
	$aResultFields = array(
		array("sf"=>"id", "qf"=>false),
		array("sf"=>"name", "qf"=>"name"),
		array("sf"=>"vorname", "qf"=>"vorname"),
		array("sf"=>"ort", "qf"=>"ort"),
		array("sf"=>"gebaeude", "qf"=>"gebaeude"),
		array("sf"=>"raumnr", "qf"=>"raumnr"),
		array("sf"=>"abteilung", "qf"=>"abteilung")
	);
	break;
	
	case "Ziel":
	$aQueryFields = array(
		array("qf"=>"ort", "sf"=>"ort"),
		array("qf"=>"gebaeude", "sf"=>"gebaeude"),
		array("qf"=>"raumnr", "sf"=>"raumnr"),
		array("qf"=>"abteilung", "sf"=>"abteilung")
	);
	
	$aResultFields = array(
		array("sf"=>"ort", "qf"=>"ort"),
		array("sf"=>"gebaeude", "qf"=>"gebaeude"),
		array("sf"=>"raumnr", "qf"=>"raumnr"),
		array("sf"=>"abteilung", "qf"=>"abteilung")
	);
	break;
	
	case "Terminvorschlaege":
	case "Terminvorschläge":
	$aQueryFields = array(
		array("qf"=>"ort", "sf"=>"ort"),
		array("sf"=>"unzugstermin", "qf"=>"unzugstermin"),
		array("sf"=>"terminwunsch", "qf"=>"terminwunsch")
	);
	
	$aResultFields = array(
		array("sf"=>"unzugstermin", "qf"=>"unzugstermin"),
		array("sf"=>"terminwunsch", "qf"=>"terminwunsch")
	);
	break;
	
	case "ort":
	$sFrom = " mm_stamm_immobilien \n";
	$sSelect = $sGroup = $sOrder = "ort";
	$aQueryFields = array(array("qf"=>"ort", "sf"=>"ort"));
	$aResultFields = array(array("sf"=>"ort", "qf"=>"ort"));
	break;
	
	case "gebaeude":
	$sFrom = " mm_stamm_immobilien \n";
	$sSelect = $sGroup = $sOrder = "gebaeude";
	$aQueryFields = array(array("qf"=>"ort", "sf"=>"ort"),array("qf"=>"gebaeude", "sf"=>"gebaeude"));
	$aResultFields = array(array("sf"=>"gebaeude", "qf"=>"gebaeude"));
	break;
	
	case "etage":
	$sFrom = " mm_stamm_immobilien \n";
	$sSelect = $sGroup = $sOrder = "etage";
	$aQueryFields = array(array("qf"=>"ort", "sf"=>"ort"),array("qf"=>"gebaeude", "sf"=>"gebaeude"),array("qf"=>"etage", "sf"=>"etage"));
	$aResultFields = array(array("sf"=>"etage", "qf"=>"etage"));
	break;
	
	default:
	die("\Ungültiger Aufruf: \$LSName:".fb_htmlEntities($LSName)."!");
}

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

switch($LSName) {
    case "Mitarbeiter":
    $sFrom = " mm_stamm_mitarbeiter \n";
    $sFrom.= " LEFT JOIN mm_stamm_immobilien ON (mm_stamm_mitarbeiter.immobilien_raum_id = mm_stamm_immobilien.id) \n";
    $sFrom.= " LEFT JOIN mm_stamm_abteilungen ON (mm_stamm_mitarbeiter.abteilungen_id = mm_stamm_abteilungen.id) \n";

    if ($sWhere && ($LSQuery["name"] || $LSQuery["vorname"] || ($LSQuery["ort"] && $LSQuery["gebaeude"] && ($LSQuery["abteilung"] || $LSQuery["raumnr"])))) {
        $sOrder = "name, vorname, ort, gebaeude, abteilung, raumnr \n";
    } else {
        $sFrom = " mm_stamm_immobilien \n";
        $sPreselectFields = "ort, gebaeude, raumnr";
        if (!$sWhere) $sPreselectFields = "ort";
        elseif (!$LSQuery["gebaeude"] && !$LSQuery["raumnr"]) $sPreselectFields = "ort, gebaeude";
        $sSelect = $sGroup = $sOrder = $sPreselectFields;
    }
    break;

    case "Dienstleister":
    $sSelect = '*';
    $sFrom = " mm_dienstleister \n";
    $sOrder = "Ort, Firmenname\n";	
    break;

    case "Ziel":	
    $sFrom = " mm_stamm_immobilien \n";
    $sPreselectFields = "ort, gebaeude, raumnr";
    if (!$sWhere) $sPreselectFields = "ort";
    elseif (!$LSQuery["gebaeude"] && !$LSQuery["raumnr"]) $sPreselectFields = "ort, gebaeude";
    $sSelect = $sGroup = $sOrder = $sPreselectFields;
    break;

    case "Terminvorschlaege":
    case "Terminvorschläge":
    $sFrom = " mm_umzuege \n";
    $sSelect = "umzugstermin, terminwunsch \n";
    $sGroup = "umzugstermin, terminwunsch \n";
    $sOrder = "umzugstermin, terminwunsch \n";
    break;
}

if ($sSelect && $sFrom) {
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
	header("Content-Type: text/xml; charset=ISO-8859-1");
	echo '<?xml version="1.0" encoding="ISO-8859-1" ?>
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
        for($i = 0; $i < count($rows); $i++) {
            $Rslt = "";
            $RsltDisplay = "";
            for ($if = 0; $if < $numResultFields; $if++) {
                    $sf = $aResultFields[$if]["sf"];
                    $qf = $aResultFields[$if]["qf"];
                    $Rslt.= ($if?";":"").(!empty($rows[$i][$sf]) ? $rows[$i][$sf] : ""); //.";"; //
                    if ($qf && !empty($rows[$i][$qf])) $RsltDisplay.= ($LSQueryLen[$qf] ? "<b>".substr($rows[$i][$qf],0,$LSQueryLen[$qf])."</b>".substr($rows[$i][$qf], $LSQueryLen[$qf])."</b>" : $rows[$i][$qf]).";";
            }
            $RsltDisplay = trim($RsltDisplay, " \r\n\t;");
            echo '<div class="SelItem"><input xtype="checkbox" type="'.(!$LSMultiple?"hidden":"checkbox").'" name="mabox" value="'.$Rslt.'"> '.$RsltDisplay.'</div>'."\n";
	}
	echo '<span onclick=alert(this.innerHTML)>Info<span style="display:none;">'.$_SERVER["QUERY_STRING"].'<br>\n'.$sql.'<br>\n'.MyDB::error().'</span></span>';
	echo ']]>';
	if (!$LSOffset) echo '</Update>';
	else echo '</Update>';
	echo '
	<LoadScript language="javascript" src="cdata"><![CDATA[
	SelBox_initChilds("'.$LSBoxId.'");
	//alert(document.getElementById("'.$LSBoxId.'"));
	]]></LoadScript>
	</Result>';
}
?>
