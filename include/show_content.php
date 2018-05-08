<?php 

$content_dir = $MConf["AppRoot"]."textfiles".DS;

if (file_exists($content_dir.$s."_".HP_LANG.".html")) 
	$body_content.= file_get_contents($content_dir.$s."_".HP_LANG.".html");
elseif (file_exists($content_dir.$s."_DE.html")) 
	$body_content.= file_get_contents($content_dir.$s."_DE.html");
elseif (file_exists($content_dir.$s.".html")) 
	$body_content.= file_get_contents($content_dir.$s.".html");
else
	$body_content.= "Inhalt konnte nicht geladen werden!<br>\n";

?>
