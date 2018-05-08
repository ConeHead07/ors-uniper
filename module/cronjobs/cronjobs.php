<?php 
$URL = $_CONF["WebRoot"].$_CONF["Modul_Dir"]."wwssync/cron_wwssync.php";

$body_content.= "<strong>Update: <br>\n<br>Account für Superuser einrichten</strong><br>\n";
$body_content.= "<a target=_blank href=\"".$_CONF["WebRoot"]."updates/\">".$_CONF["WebRoot"]."updates/</a><br>\n";
$body_content.= "<br>\n";
$body_content.= "<br>\n";

$body_content.= "<strong>Status-Update als Cron-Job</strong><br>\n";
$body_content.= "<br>\n";
$body_content.= "<strong>URL für den Cron-Job-Aufruf:</strong><br>\n";
$body_content.= "<a style=\"text-decoration:underline;\" href=\"".$URL."\">".$URL."</a></a><br>";
$body_content.= "<br>\n";

$body_content.= "<strong>Wget für den Aufruf der URL via Shell:</strong><br>\n";
$body_content.= "Hier downloaden: <a style=\"text-decoration:underline;\" href=\"http://download.freenet.de/internet/download-manager/download-wget-for-windows-1.10.1-englisch--6649.xhtml\">http://download.freenet.de/internet/download-manager/download-wget-for-windows-1.10.1-englisch--6649.xhtml</a><br>\n";
$body_content.= "<br>\n";

$body_content.= "<strong>Aufruf der URL mit Wget ohne dass Daten lokal gespeichert werden:</strong><br>\n";
$body_content.= "<span style=\"color:#00f;\">C:\Programme\wget\wget.exe -q --spider ".$URL."</span><br>\n";
$body_content.= "<br>\n";
?>
