<?php 

$content_dir = $MConf["AppRoot"]."textfiles".DS;
$body_content.= <<<EOT
    <div class="divModuleBasic padding6px width5Col heightAuto colorContentMain">
    <h1><span class="spanTitle">{pageTitle}</span></h1>
    <p>
    <div id="Umzugsantrag" class="divInlay">
EOT;
if (0 > 1) {
    echo '<pre>' . htmlentities(json_encode($_rplAusgabe, JSON_PRETTY_PRINT)) . '</pre>';
}

if (file_exists($content_dir.$s."_".HP_LANG.".html")) {
    $body_content .= file_get_contents($content_dir . $s . "_" . HP_LANG . ".html");
} elseif (file_exists($content_dir.$s."_DE.html")) {
    $body_content .= file_get_contents($content_dir . $s . "_DE.html");
} elseif (file_exists($content_dir.$s.".html")) {
    $body_content .= file_get_contents($content_dir . $s . ".html");
} else {
    $body_content .= "Inhalt konnte nicht geladen werden!<br>\n";
}

$body_content.= '</div></p></div>';


