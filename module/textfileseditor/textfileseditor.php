<?php 
include_once("./include/stdlib.php");

$TFE_CNF["Dir"] = array("", "./textfiles"); // , "./html");
if (count($TFE_CNF["Dir"]) > 2) {
	$body_content.= "Verzeichnis wechseln: ";
	for($i = 1; $i < count($TFE_CNF["Dir"]); $i++) {
		$body_content.= "<a href=\"?s=$s&d=$i\">".$TFE_CNF["Dir"][$i]."</a> ";
	}
	$body_content.= "<br><br>\n";
}

$d = (isset($_POST["d"]) ? intval($_POST["d"]) : (isset($_GET["d"]) ? intval($_GET["d"]) : null));
$f = (!empty($_POST["f"]) ? $_POST["f"] : (!empty($_GET["f"]) ? $_GET["f"] : null));
$fs = !empty($_POST["fs"]);
$fc = (!empty($_POST["fc"]) ? $_POST["fc"] : null);
$fn = 0;
$fliste = "";

	
	if (!intval($d)) $d = 1;
	$dp = opendir($TFE_CNF["Dir"][$d]);
	
	if ($dp) {
		while($r_f = readdir($dp)) {
			if (!is_dir($TFE_CNF["Dir"][$d]."/".$r_f)) {
				$fn++;
				$last_f = $r_f;
				$fliste.= "<option value=\"".fb_htmlEntities($r_f)."\">".$r_f." ".format_fstat($TFE_CNF["Dir"][$d]."/".$r_f)."<br>\n";
			}
		}
		if (!$f && $fn == 1) $f = $last_f;
		closedir($dp);
	} else $body_content.= "CanNotOpenDir:".$TFE_CNF["Dir"][$d]."<br>\n";


if (!$d || !$TFE_CNF["Dir"][$d] || !$f || !file_exists($TFE_CNF["Dir"][$d]."/".$f)) {
	$body_content.= "<form action=\"?s=$s\" method=post><select name=\"f\" style=\"width:auto;\">".$fliste."</select>";
	$body_content.= "<input type=hidden name=d value=$d>";
	$body_content.= "<input type=\"submit\" value=\"&ouml;ffnen\"></form>";
} else {
	$file = $TFE_CNF["Dir"][$d]."/".$f;

	if ($fs) { // File-Action : save
		$fc = stripslashes($_POST["fc"]);
		file_put_contents($file, $fc);
		$body_content.= "<div style=\"color:#008000;\">Datei wurde gespeichert. Letzte Änderung: ".format_fstat($file)."!</div><br>\n";
	}

	if ( substr(basename($file), 0, 11) === 'statusmail_' ) {
	    $acceptCharset = 'UTF-8';
    } else {
	    $acceptCharset = 'ISO-8859-1';
    }

	$content = file_get_contents($file);
	
	$aFileInfo = pathinfo($file);
	
	//$body_content.= print_r($aFileInfo,1)."<br>\n";
	if ($aFileInfo["extension"] == "html") {
		$body_content.= '<!-- TinyMCE -->
<script type="text/javascript" src="'.$MConf["WebRoot"].'module/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="'.$MConf["WebRoot"].'module/tinymce/jscripts/tiny_mce/tiny_mce_custom_menu.js"></script>'."\n";
	}
	
	$body_content.= "<strong>Vorlage: ".basename($file)." &nbsp; ".format_fstat($file)."</strong> ".($fn>1?'[<a href="?s=' . str_replace('"','',$s).'">Andere Datei</a>]':"")."<br>
	<form action=\"?s=$s\" method=\"post\" accept-charset=\"$acceptCharset\">
	<textarea name=\"fc\" style=\"width:100%;height:415px;\">".fb_htmlEntities($content, 0, $acceptCharset)."</textarea>
	<input type=\"submit\" name=\"fs\" style=\"padding:0 0 9px 0;background:url(images/BtnBlue_160.png) bottom left no-repeat;border:0;width:160px;height:24px;font-size:12px;color:#fff;font-weight:bold;\" value=\"Datei speichern\">
	<input type=\"hidden\" name=\"d\" value=\"$d\">
	<input type=\"hidden\" name=\"f\" value=\"".fb_htmlEntities($f)."\">
	</form>\n";
}
?>
