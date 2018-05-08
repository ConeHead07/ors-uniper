<?php 
require_once("../../header.php");

$conf_name = "cms_bereiche";
$conf_file = $MConf["AppRoot"].$MConf["Inc_Dir"].$conf_name.".inc.php";
echo "#".__LINE__." var_file:".$MConf["AppRoot"].$MConf["Inc_Dir"].$conf_name.".inc.php";

$dirBase = "./";
$dirLib = "./include/";
$dirTpl = "./html/";
$dbase = $MConf["DB_Name"];
$tbl = "mm_cms_bereiche";

if (!file_exists($conf_file)) {
	// Start: Erstellen und Speichern der Default-Conf aus Tabellenstruktur
	$f = new formcreator($conf_name, $dirBase, $dirLib, $dirTpl);
	$f->create_default_conf_fromdb($dbase, $tbl, true);
	
	$CE = new ConfEditor($conf_file, $conf_name);
	$CE->load_struct();
	$CE->load_conf($f->arrConf);
	$CE->autorun();
	$CE->write_conf();
	echo $CE->sCnfForm;
	// Ende: Erstellen und Speichern der Default-Conf aus Tabellenstruktur
} 

elseif (file_exists($conf_file)) {
	// Start: Bestehende Conf laden
	$CE = new ConfEditor($conf_file, $conf_name);
	$CE->autorun();
	echo $CE->sCnfForm;
	// Ende: Bestehende Conf laden
}

$CE_Reflection = new ReflectionClass('ConfEditor');

echo "<pre>ConfEditor Methodes:".print_r($CE_Reflection->getMethods(),1)."</pre>";

//$CE_Reflection = new ReflectionExtension($CE);
//print_r($CE_Reflection->getFunctions());
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Untitled</title>
</head>

<body>



</body>
</html>
