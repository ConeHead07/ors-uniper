<?php
require($InclBaseDir."leistungsmatrix.inc.php");
//die(print_r($_REQUEST,1));
$showConfData = false;
$confName = "leistungsmatrix";
include($ModulBaseDir."editdatabyconf/edit_data.inc.php");

if (isset($_REQUEST['lid'])) {
//$_CONF[$confName]['Lists'][0]['where'] = 'leistung_id = ' . (int)$_REQUEST['lid'];
}

$body_content = str_replace("id=\"InputLblrechte\"", "class=\"jtooltip\" id=\"InputLblrechte\" rel=\"./hilfetexte/user_rechte.php\"", $body_content);

?>