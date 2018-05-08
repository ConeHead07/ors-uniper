<?php 

function menu_select_lang(&$tpl, &$Item) {
	global $s;
	$SwitchLang = (HP_LANG=="DE") ? "EN" : "DE";
	$Item["menu_html"] = str_replace("{lang}", $SwitchLang, $Item["menu_html"]);
	$Item["menu_html"] = str_replace("{s}", $s, $Item["menu_html"]);
	$Item["strMenu"] = $Item["menu_html"];
	return true;
}

?>