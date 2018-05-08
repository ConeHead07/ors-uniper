<?php 
require_once($InclBaseDir."nebenleistungen.inc.php");

$showConfData = false;
$showErros = false;
$showMsg = false;

$s = getRequest("s","");
if ($s == "klantrag") $editCmd = "Edit";

$confName = "nebenleistungen";
$_CONF[$confName]["FormInput"] = "html/nebenleistungen_antrag_eingabe.html";
include($ModulBaseDir."editdatabyconf/edit_data.inc.php");

?>