<?php 

// TAG: a
$_CTAGS["a"] = array();
$_CTAGS["a"]["ST"] = array("width","text-decoration;font-weight","font-size","font-style");
$_CTAGS["a"]["AT"] = array("name", "title","target","href","class","style","onclick");

// Default-Wert
$_CTAGS["a"]["STCK"]["font-size"] = array("8px","10px","11px","12px","14px","16px");
//$_CTAGS["a"]["STDEF"]["font-size"] = "12px";

// TAG: a
$_CTAGS["hr"] = array();
$_CTAGS["hr"]["ST"] = array("width", "color", "border", "border-color");
$_CTAGS["hr"]["AT"] = array("class", "width", "height", "color");

// TAG: h3
$_CTAGS["h3"] = array();
$_CTAGS["h3"]["ST"] = array("width","text-decoration;font-weight","font-size","font-style");
$_CTAGS["h3"]["AT"] = array("name", "id", "class", "style");

// TAG: a
$_CTAGS["h4"] = array();
$_CTAGS["h4"]["ST"] = array("width","text-decoration;font-weight","font-size","font-style");
$_CTAGS["h4"]["AT"] = array("name", "id", "class", "style");

// TAG: img
$_CTAGS["img"]["ST"] = array(
	"text-align","vertical-align","width","height","border","border-color",
	"border-left","border-right","border-bottom","border-top",
	"padding","padding-left","padding-top","padding-right","padding-bottom",
	"margin","margin-left","margin-top","margin-right","margin-bottom");
	
$_CTAGS["img"]["AT"] = array("name", "onclick","title","hspace","align","class","style","src","height","width");

// TAG: b
$_CTAGS["b"]["AT"] = array("class");
//$_CTAGS["b"]["ATCK"]["class"] = array("logoSlogan");

// TAG: div
$_CTAGS["div"]["ST"] = array(
	"text-align","vertical-align","width","height",
	"padding","padding-left","padding-top","padding-right","padding-bottom",
	"margin","margin-left","margin-top","margin-right","margin-bottom");
	
$_CTAGS["div"]["AT"] = array("name", "align","class","style","width");
//$_CTAGS["div"]["ATCK"]["class"] = array("contentDivPos", "contentDiv", "h3Content");

// TAG: table
$_CTAGS["table"]["ST"] = array("height","width","background-color",
	"border","border-top","border-bottom","border-left","border-right",
	"margin","margin-top","margin-bottom","margin-left","margin-right",
	"padding","padding-top","padding-bottom","padding-left","padding-right");
	
$_CTAGS["table"]["AT"] = array(
	"name", "align","style","width","valign", "class", "id",
	"border","cellpadding","cellspacing");

// TAG: td
$_CTAGS["td"]["ST"] = array(
	"height","width","text-align","vertical-align","background-color",
	"border","border-top","border-bottom","border-left","border-right",
	"margin","margin-top","margin-bottom","margin-left","margin-right",
	"padding","padding-top","padding-bottom","padding-left","padding-right");
	
$_CTAGS["td"]["AT"] = array(
	"class","id", "name", "align","style","width","height","colspan","rowspan","valign","border","bgcolor");
//$_CTAGS["td"]["ATCK"]["class"] = array("imgCaption");

// TAG: form
$_CTAGS["form"]["ST"] = array();
$_CTAGS["form"]["AT"] = array(
	"name", "action","method","target");
$_CTAGS["form"]["ATCK"]["action"] = array("../forum/login.php","index.php");
$_CTAGS["form"]["ATCK"]["method"] = array("post","get");
$_CTAGS["form"]["ATCK"]["target"] = array("_blank","_top","_self");

// TAG: input
$_CTAGS["input"]["ST"] = array();
$_CTAGS["input"]["AT"] = array(
	"type","name","value","maxlength");

// TAG: object
$_CTAGS["object"]["ST"] = array();
$_CTAGS["object"]["AT"] = array(
	"id","width","height","classid","standby","type");

// TAG: object - param
$_CTAGS["param"]["ST"] = array();
$_CTAGS["param"]["AT"] = array(
	"name","value");

// TAG: object - embed
$_CTAGS["param"]["ST"] = array();
$_CTAGS["param"]["AT"] = array(
	"type", 
	"ID", 
	"pluginspage", 
	"filename", 
	"src", 
	"name", 
	"showcontrols", 
	"showdisplay", 
	"showstatusbar", 
	"width", 
	"height", 
	"Autostart", 
	"showcontrols", 
	"stretchtofit", 
	"enablecontextmenu");

function zzz_specialTagCheck() {
	// Behandlung spezieller Tag-Eigenschaften
	while(list($r_abs, $r_rel) = each($_internerPfadAbs2Rel)) {
		if (isset($a["src"]) && substr(strtolower($a["src"]), 0, strlen($r_abs)) == $r_abs) {
			$a["src"] = $r_rel.substr($a["src"], strlen($r_abs));
		}
	}
	
	while(list($r_abs, $r_rel) = each($_internerPfadAbs2Rel)) {
		if (isset($a["src"]) && substr(strtolower($a["src"]), 0, strlen($r_abs)) == $r_abs) {
			$a["src"] = $r_rel.substr($a["src"], strlen($r_abs));
		}
	}
}

?>