<?php
if (basename(__FILE__) == basename($_SERVER["PHP_SELF"])) {
	require_once(dirname(__FILE__)."/../include/conf.php");
	require_once(dirname(__FILE__)."/../include/stdlib.php"); // __autoload
	if (!class_exists("Smarty")) require_once $MConf["AppRoot"]."smarty/Smarty.class.php";
}


class myTplEngine extends Smarty {
	
    function __construct($tplDir = "", $cnfDir = "") {
        global $MConf;
        parent::__construct();

        $this->debugging = false; // true; // 
        $this->compile_check = true;
        $this->template_dir = ($tplDir) ? $tplDir : $MConf["AppRoot"]."html";
        $this->config_dir = ($cnfDir) ? $cnfDir : $MConf["AppRoot"]."smarty_lab/configs";
        $this->compile_dir = $MConf["AppRoot"]."smarty_lab/templates_c";
        $this->cache_dir = $MConf["AppRoot"]."smarty_lab/cache";
        
        $this->assign('MConf', $MConf);
    }
}

if (basename(__FILE__) == basename($_SERVER["PHP_SELF"])) {
	$Tpl = new myTplEngine();
	$Tpl->assign("txt", "Frank Barthold");
	$html = $Tpl->fetch("errorbox.html");
	echo $html;
}
?>