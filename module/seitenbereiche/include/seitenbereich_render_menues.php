<?php 
echo "#".__LINE__." ".__FILE__."<br>\n";
include_once($InclBaseDir."conn.php");
include_once($ModulBaseDir."seitenbereiche/include/lib_menutree.php");
include_once($ModulBaseDir."seitenbereiche/include/seitenbereiche_lib.php");
include_once($ModulBaseDir."seitenbereiche/include/seitenbereich_conf.php");
include_once($InclBaseDir."check_lib.php");
include_once($InclBaseDir."lib_admin_cms.php");
include_once($InclBaseDir."stdlib.php");

$_FitTags = array();
$_FitTags[0] = array(
	"tag" => "div",
	"find" => array("x_div_attr" => true),
	"attr" => array("onclick" => "hello()")
);
$strMenuBar = "";

load_menu_tpls();
$strMenueBar = render_dyn_navbar(0);


$addMsgBox = "<script>alert(\"Diese Ansicht ist nur eine Vorschau :o)\");</script>\n";
$msg = "<span style=\"color:green;\">Vorschauansicht!</span>";
if (isset($_GET["SaveMenu"]) && $_GET["SaveMenu"] == "1") {
	if (save_navbar($strMenueBar)) {
		$addMsgBox = "<script>alert(\"Menüausgabe wurde gespeichert!\");</script>\n";
		$msg = "<span style=\"color:green;\">Menü wurde im Web veröffentlicht!</span>";
	} else {
		$addMsgBox = "<script>alert(\"FEHLER: Menüausgabe konnte nicht gespeichert werden!\");</script>\n";
		$msg = "<span style=\"color:red;\">Menü konnte nicht veröffentlicht werden</span>!";
	}
} elseif(isset($_GET["RefreshMenu"]) && $_GET["RefreshMenu"] == "1") {
	$msg = "<span style=\"color:green;\">Menüvorgaben wurde neu geladen!</span>";
}


$btnMenue = '<DIV style="position:absolute;top:0px;left:0px;padding:0px;z-Index:99;">
<DIV style="background:gray;float:left;">
<DIV style="border:1px solid green;background:silver;padding:2px;margin:2px;color:black;"><a href=../adminmod/seitenbereich_render_menues.php?SaveMenu=1 style="font-size:12px;text-decoration:none;">Speichern</a></DIV>
</DIV>

<DIV style="background:gray;float:left;">
<DIV style="border:1px solid green;background:silver;padding:2px;margin:2px;color:black;"><a href=../adminmod/seitenbereich_render_menues.php?RefreshMenu=1 style="font-size:12px;text-decoration:none;">Aktualisieren</a></DIV>
</DIV>

<DIV style="background:gray;float:left;">
<DIV style="border:1px solid green;background:silver;padding:2px;margin:2px;color:black;" onclick=self.close()><a href=# style="font-size:12px;text-decoration:none;">Schliessen</a></DIV>
</DIV>

<DIV style="float:left;border:1px solid white;background:white;padding:4px;margin:0px;">'.$msg.'</DIV>
</DIV>';

$ausgabe = implode("", file($AppBaseDir."html/index.html"));
$ausgabe = str_replace("<!-- {NAVLEFT} -->", $strMenueBar, $ausgabe);
echo $btnMenue;
echo $ausgabe;
echo $addMsgBox;

?>