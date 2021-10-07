<?php 

$mod_sb_dir = realpath(dirname(__FILE__)."/../")."/";
$mod_sb_url = $_CONF["WebRoot"]."/module/seitenbereiche/";

$_ConfMenu["main"]["msbBaselink"] = "index.php?s={srv}";
$_ConfMenu["main"]["MenuDeep"] = 3;
$_ConfMenu["main"]["MenuNeedle"] = "{navbar}";
$_ConfMenu["main"]["Tpl"]["BaseFile"] = $ModulBaseDir."seitenbereiche/html/vorlage_seitennavigation.html";
$_ConfMenu["main"]["Tpl"]["MenuFile"] = $ModulBaseDir."seitenbereiche/html/vorlage_navigation_links.html";
$_ConfMenu["main"]["Tpl"]["DynLevel"][0]["WithSub"] = "";
$_ConfMenu["main"]["Tpl"]["DynLevel"][0]["NoSub"]   = "";
$_ConfMenu["main"]["Tpl"]["DynLevel"][1]["WithSub"] = "";
$_ConfMenu["main"]["Tpl"]["DynLevel"][1]["NoSub"] = "";
$_ConfMenu["main"]["Tpl"]["DynLevel"][2]["NoSub"] = "";

$_ConfMenu["top"]["msbBaselink"] = "index.php?s={srv}";
$_ConfMenu["top"]["MenuDeep"] = 1;
$_ConfMenu["top"]["MenuNeedle"] = "{navbar}";
$_ConfMenu["top"]["Tpl"]["BaseFile"] = $ModulBaseDir."seitenbereiche/html/vorlage_nav_items_top.html";
$_ConfMenu["top"]["Tpl"]["MenuFile"] = $ModulBaseDir."seitenbereiche/html/vorlage_nav_container_blank.html";
$_ConfMenu["top"]["Tpl"]["DynLevel"][0]["NoSub"]   = "";


$_ConfMenu["list"]["msbBaselink"] = "index.php?s={srv}";
$_ConfMenu["list"]["MenuDeep"] = 0;
$_ConfMenu["list"]["MenuNeedle"] = "{navbar}";
$_ConfMenu["list"]["Tpl"]["BaseFile"] = $ModulBaseDir."seitenbereiche/html/vorlage_nav_listitems.html";
$_ConfMenu["list"]["Tpl"]["MenuFile"] = $ModulBaseDir."seitenbereiche/html/vorlage_nav_container_blank.html";
$_ConfMenu["list"]["Tpl"]["DynLevel"][0]["NoSub"]   = "";

$_ConfMenu["header"] = $_ConfMenu["list"];
$_ConfMenu["footer"] = $_ConfMenu["list"];

$_ConfMenu["default"] = $_ConfMenu["list"];

$_ConfMenu["partnerlinks"] = $_ConfMenu["main"];
function check_menucreator() {
	return true;
}

$_TABLE["seitenbereich"] = $tbl_prefix."cms_bereiche";
$_CONF["seitenbereich"] = array(
	
	"id" => array(
		"label" => "BereichID",
		"type" => "key", // enum(),set(),char(),int(),float(),date,datetime,time,email,text,html,created,modified,file
		"default" => "",
		"required" => false,
		"unique" => true,
		"htmlinputtype" => "text", // text,html,option
		"checkByFunction" => ""
	),
	
	"lang" => array(
		"label" => "Sprache",
		"type" => "enum('DE','EN')", // enum(),set(),char(),int(),float(),date,datetime,time,email,text,html,created,modified,file
		"default" => "DE",
		"required" => true,
		"unique" => false,
		"htmlinputtype" => "option", // text,html,option
		"checkByFunction" => ""
	),
	
	"geschuetzt" => array(
		"label" => "Passwortbereich",
		"type" => "enum('Ja','Nein')",
		"default" => "Nein",
		"required" => true,
		"unique" => false,
		"htmlinputtype" => "option", // text,html,option
		"checkByFunction" => ""
	),
	
	"content" => array(
		"label" => "Content-Menü (CMS)",
		"type" => "enum('Ja','Nein')",
		"default" => "Ja",
		"required" => true,
		"unique" => false,
		"htmlinputtype" => "option", // text,html,option
		"checkByFunction" => ""
	),
	
	"gruppen" => array(
		"label" => "Zugang für Gruppen",
		"type" => "text",
		"default" => "",
		"required" => false,
		"unique" => false,
		"htmlinputtype" => "text", // text,html,option
		"checkByFunction" => ""
	),
	
	"rechte" => array(
		"label" => "Berechtigungsstufe",
		"type" => "enum('0','1','2','3','4','5','6','7')",
		"default" => "0",
		"required" => true,
		"unique" => false,
		"htmlinputtype" => "option", // text,html,option
		"checkByFunction" => ""
	),
	
	"webfreigabe" => array(
		"label" => "Webfreigabe",
		"type" => "enum('Ja','Nein')",
		"default" => "Nein",
		"required" => true,
		"unique" => false,
		"htmlinputtype" => "option", // text,html,option
		"checkByFunction" => ""
	),
	
	"visibility" => array(
		"label" => "Sichtbar",
		"type" => "enum('allways','never','conditional')",
		"default" => "allways",
		"required" => true,
		"unique" => false,
		"htmlinputtype" => "option", // text,html,option
		"checkByFunction" => ""
	),
	
	"visibility_condition" => array(
		"label" => "Anzeige abhängig von Login",
		"type" => "set('login','logout','loginas:gruppe','loginas:gleicherechte','loginas:mindestrechte','loginas:admin')",
		"default" => "none",
		"required" => true,
		"unique" => false,
		"htmlinputtype" => "option", // text,html,option
		"checkByFunction" => ""
	),
	
	"parentid" => array(
		"label" => "ParentID",
		"type" => "int",
		"default" => "0",
		"required" => false,
		"unique" => false,
		"htmlinputtype" => "text,option", // text,html,option
		"checkByFunction" => "check_parentid"
	),
	
	"ordnungszahl" => array(
		"label" => "Ordnungszahl",
		"type"  => "int",
		"default"  => "0",
		"required" => false,
		"unique"   => false,
		"htmlinputtype" => "text,option", // text,html,option
		"checkByFunction" => ""
	),
	
	"create_menu_function" => array(
		"label" => "Php-Funtion für Menüausgabe",
		"type"  => "char(200)",
		"default"  => "",
		"required" => false,
		"unique"   => false,
		"htmlinputtype" => "text", // text,html,option
		"checkByFunction" => "check_menucreator"
	),
	
	"create_menu_script" => array(
		"label" => "Php-Script für Menüausgabe",
		"type"  => "char(200)",
		"default"  => "",
		"required" => false,
		"unique"   => false,
		"htmlinputtype" => "text", // text,html,option
		"checkByFunction" => ""
	),
	
	"srv" => array(
		"label" => "Bereich (interne Bezeichnung)",
		"type"  => "char(32)",
		"default"  => "",
		"required" => true,
		"unique"   => false,
		"htmlinputtype" => "text", // text,html,option
		"checkByFunction" => "check_srv"
	),
	
	"cmd" => array(
		"label" => "cmd (Ausgabesteuerung)",
		"type"  => "char(32)",
		"default"  => "",
		"required" => false,
		"unique"   => false,
		"htmlinputtype" => "text", // text,html,option
		"checkByFunction" => ""
	),
	
	"script" => array(
		"label" => "cmd (Ausgabesteuerung)",
		"type"  => "char(50)",
		"default"  => "",
		"required" => false,
		"unique"   => false,
		"htmlinputtype" => "text", // text,html,option
		"checkByFunction" => ""
	),
	
	"name" => array(
		"label" => "Bereichsname",
		"type"  => "char(100)",
		"default"  => "",
		"required" => true,
		"unique"   => false,
		"htmlinputtype" => "text", // text,html,option
		"checkByFunction" => ""
	),
	
	"name_translations" => array(
		"label" => "Übersetzungen (Derzeit nur nach englisch) z.B. für Start \"EN=Home\" ",
		"type"  => "char(100)",
		"default"  => "EN=",
		"required" => false,
		"unique"   => false,
		"htmlinputtype" => "text", // text,html,option
		"checkByFunction" => ""
	),
	
	"text" => array(
		"label" => "Infotext",
		"type"  => "html",
		"default"  => "",
		"required" => false,
		"unique"   => false,
		"htmlinputtype" => "html", // text,html,option
		"checkByFunction" => ""
	),
	
	"kommentar" => array(
		"label" => "Interner Kommentar",
		"type"  => "text",
		"default"  => "",
		"required" => false,
		"unique"   => false,
		"htmlinputtype" => "text", // text,html,option
		"checkByFunction" => ""
	),
	
	"logo" => array(
		"label" => "Logo",
		"type"  => "file",
		"default"  => "",
		"required" => false,
		"unique"   => false,
		"htmlinputtype" => "text", // text,html,option
		"checkByFunction" => ""
	),
	
	"redirect" => array(
		"label" => "Alternativer Umleitungs-URL (Falls der Seitenbereich deaktiviert wurde)",
		"type"  => "char(150)",
		"default"  => "",
		"required" => false,
		"unique"   => false,
		"htmlinputtype" => "text", // text,html,option
		"checkByFunction" => ""
	),
	
	"menu_groupname" => array(
		"label" => "Menügruppe",
		"type"  => "char(32)",
		"default"  => "main",
		"required" => true,
		"unique"   => false,
		"htmlinputtype" => "text", // text,html,option
		"checkByFunction" => "check_srv"
	),
	
	"menu_icon" => array(
		"label" => "Menü-Icon",
		"type"  => "char(50)",
		"default"  => "",
		"required" => false,
		"unique"   => false,
		"htmlinputtype" => "text", // text,html,option
		"checkByFunction" => ""
	),
	
	"menu_title" => array(
		"label" => "Menü-Titel",
		"type" => "char(50)",
		"default"  => "",
		"required" => false,
		"unique"   => false,
		"htmlinputtype" => "text", // text,html,option
		"checkByFunction" => ""
	),
	
	"menu_behaviour" => array(
		"label" => "Menü-Verhalten",
		"type" => "enum('openSubMenu','openLink')",
		"default"  => "openSubMenu",
		"required" => true,
		"unique"   => false,
		"htmlinputtype" => "option", // text,html,option
		"checkByFunction" => ""
	),
	
	"submenu_behaviour" => array(
		"label" => "Menü-Verhalten",
		"type" => "enum('visible','hidden')",
		"default"  => "visible",
		"required" => true,
		"unique"   => false,
		"htmlinputtype" => "option", // text,html,option
		"checkByFunction" => ""
	),
	
	"menu_target" => array(
		"label" => "Menü-Target",
		"type" => "char(15)",
		"default"  => "_self",
		"required" => false,
		"unique"   => false,
		"htmlinputtype" => "text", // text,html,option
		"checkByFunction" => ""
	),
	
	"menu_class" => array(
		"label" => "Class (Style)",
		"type" => "char(15)",
		"default" => "",
		"required" => false,
		"unique" => false,
		"htmlinputtype" => "text", // text,html,option
		"checkByFunction" => ""
	),
	
	"menu_style" => array(
		"label" => "Style",
		"type" => "char(100)",
		"default" => "",
		"required" => false,
		"unique" => false,
		"htmlinputtype" => "text", // text,html,option
		"checkByFunction" => ""
	),
	
	"menu_onclick" => array(
		"label" => "Menü-OnClick",
		"type" => "char(100)",
		"default" => "",
		"required" => false,
		"unique" => false,
		"htmlinputtype" => "text", // text,html,option
		"checkByFunction" => ""
	),
	
	"menu_link" => array(
		"label" => "Abweichende Verlinkung",
		"type" => "char(100)",
		"default" => "",
		"required" => false,
		"unique" => true,
		"htmlinputtype" => "text", // text,html,option
		"checkByFunction" => ""
	),
	
	"menu_html" => array(
		"label" => "Menü-HTML",
		"type" => "text",
		"default" => "",
		"required" => false,
		"unique" => false,
		"htmlinputtype" => "text", // text,html,option
		"checkByFunction" => ""
	),
	
	"menu_div_attr" => array(
		"label" => "DIV-Attribute(Menü-Umgebung)",
		"type" => "text",
		"default" => "",
		"required" => false,
		"unique" => false,
		"htmlinputtype" => "text", // text,html,option
		"checkByFunction" => ""
	),
	
	"menu_ahref_attr" => array(
		"label" => "Link-Attribute",
		"type" => "text",
		"default" => "",
		"required" => false,
		"unique" => false,
		"htmlinputtype" => "text", // text,html,option
		"checkByFunction" => ""
	),
	
	"menu_sub_attr" => array(
		"label" => "Attribute für Container für Untermenüs",
		"type" => "text",
		"default" => "",
		"required" => false,
		"unique" => false,
		"htmlinputtype" => "text", // text,html,option
		"checkByFunction" => ""
	),
	
	"menu_code_before" => array(
		"label" => "Menü: HtmlCode-Before",
		"type" => "text",
		"default" => "",
		"required" => false,
		"unique" => false,
		"htmlinputtype" => "text", // text,html,option
		"checkByFunction" => ""
	),
	
	"menu_code_behind" => array(
		"label" => "Menü: HTMLCode",
		"type" => "text",
		"default" => "",
		"required" => false,
		"unique" => false,
		"htmlinputtype" => "text", // text,html,option
		"checkByFunction" => ""
	),
	
	"created" => array(
		"label" => "Erstellt am",
		"type" => "created",
		"default" => date("Y-m-d H:i:s"),
		"required" => false,
		"unique" => false,
		"htmlinputtype" => "text", // text,html,option
		"checkByFunction" => ""
	),
	
	"modified" => array(
		"label" => "Letzte Änderung",
		"type" => "modified",
		"default" => date("Y-m-d H:i:s"),
		"required" => false,
		"unique" => false,
		"htmlinputtype" => "text", // text,html,option
		"checkByFunction" => ""
	)
);


$img_schloss_rot = "<img src=\"{$mod_sb_url}/images/schloss_rot_aktiv_kl.gif\" width=\"10\" height=\"10\" alt=\"{alt}\">";
$img_schloss_gruen = "<img src=\"{$mod_sb_url}/images/schloss_gruen_aktiv_kl.gif\" width=\"10\" height=\"10\" alt=\"{alt}\">";

$color_on = "green";
$color_off = "red";
$color_cond = "#ffa500";

$color_set_on = "black";
$color_set_off = "black";
$color_set_cond = "black";

function set_img_alt($img, $alt) {
	return str_replace("{alt}", addslashes($alt), $img);
}

$_SetFlag["webfreigabe"]["Ja"]   = "<span style=\"color:$color_set_on;\">Aktivieren</span>";
$_SetFlag["webfreigabe"]["Nein"] = "<span style=\"color:$color_set_off;\">Deaktivieren</span>";

$_SetFlag["visibility"]["never"] = "<span style=\"color:$color_set_on;\">Anzeigen</span>";
$_SetFlag["visibility"]["allways"]  = "<span style=\"color:$color_set_off;\">Verstecken</span>";
$_SetFlag["visibility"]["conditional"]  = "<span style=\"color:$color_set_cond;\">Immer Anzeigen</span>";

$_SetFlag["geschuetzt"]["Ja"]   = "<span style=\"color:$color_set_off;\">Schützen</span>";
$_SetFlag["geschuetzt"]["Nein"] = "<span style=\"color:$color_set_on;\">Schutz aufheben</span>";

$_GetFlag["webfreigabe"]["Ja"]   = "<span style=\"color:$color_on;\">".set_img_alt($img_schloss_gruen, "Freigegeben")."</span>";
$_GetFlag["webfreigabe"]["Nein"] = "<span style=\"color:$color_off;\">".set_img_alt($img_schloss_rot, "Gesperrt")."</span>";

$_GetFlag["visibility"]["allways"] = "<span style=\"color:$color_on;\">".set_img_alt($img_schloss_gruen, "Sichtbar")."</span>";
$_GetFlag["visibility"]["never"]  = "<span style=\"color:$color_off;\">".set_img_alt($img_schloss_rot, "Versteckt")."</span>";
$_GetFlag["visibility"]["conditional"]  = "<span style=\"color:$color_cond;\">".set_img_alt($img_schloss_rot, "Bedingt")."</span>";

$_GetFlag["geschuetzt"]["Ja"]   = "<span style=\"color:$color_off;\">".set_img_alt($img_schloss_rot, "Geschuetzt")."</span>";
$_GetFlag["geschuetzt"]["Nein"] = "<span style=\"color:$color_on;\">".set_img_alt($img_schloss_gruen, "�ffentlich")."</span>";

?>
