<?php  
require("../header.php");
$body_content = "";
require($MConf["AppRoot"]."sites".DS."nebenleistung_druckansicht.php");

$umzugsblatt = $body_content;
//$body_content = implode("", file($MConf["AppRoot"].$MConf["Tpl_Dir"]."umzugsformular.tpl.html"));
if (basename(__FILE__) == basename($_SERVER["PHP_SELF"])) {
	echo $umzugsblatt;
}
?>