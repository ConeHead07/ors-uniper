<?php
require_once($InclBaseDir."leistungskategorie.inc.php");
//die(print_r($_REQUEST,1));
$showConfData = false;
$confName = "leistungskategorie";
include($ModulBaseDir."editdatabyconf/edit_data.inc.php");

$body_content = str_replace("id=\"InputLblrechte\"", "class=\"jtooltip\" id=\"InputLblrechte\" rel=\"./hilfetexte/user_rechte.php\"", $body_content);

?>