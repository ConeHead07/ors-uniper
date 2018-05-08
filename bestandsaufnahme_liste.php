<?php 
include("header.php");
$showConfData = false;
$body_content = "";
$s="editdata";
$confName="stamm_mitarbeiter";
include($MConf["AppRoot"].$MConf["Modul_Dir"]."editdatabyconf".DS."edit_data.inc.php");
echo $body_content;
?>
