<?php 
require_once($InclBaseDir."nebenleistungen.inc.php");

$showConfData = false;
$confName = "nebenleistungen";
$_CONF[$confName]["FormInput"] = "html/nebenleistungen_antrag_eingabe.html";
include($ModulBaseDir."editdatabyconf/edit_data.inc.php");

?>