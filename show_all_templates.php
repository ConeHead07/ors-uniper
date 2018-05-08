<?php 

$html = implode("", file("webdump_smallsite.tpl.html"));

$tplDir = "vorlagen";
$dp = opendir($tplDir);
if ($dp) {
  while($f = readdir($dp)) {
    if (filetype($tplDir."/".$f) == "file") {
      $tplContent = implode("", file($tplDir."/".$f));
      $html = str_replace("<!-- $f -->", $f."<br>\n".$tplContent, $html);
    }
  }
  closedir($dp);
}
echo $html;
?>
