<?php 

$_FitTags = array();
$_FitTags[0] = array(
	"tag" => "div",
	"find" => array("x_div_attr" => true),
	"attr" => array("onclick" => "hello()")
);

function run_menu_creator(&$tpl, &$Item) {
	if ($Item["create_menu_script"]) {
		 if (file_exists($Item["create_menu_script"])) include_once($Item["create_menu_script"]); 
		 else {
		 	return false;
		 }
	}
	if ($Item["create_menu_function"]) {
		if (function_exists($Item["create_menu_function"])) call_user_func($Item["create_menu_function"], $tpl, $Item);
		else {
			return false;
		}
	}
	return true;
}
$cnt_loop = 0;
$max_loop = 100;
function get_navelements(&$user, $parentid = 0, $deep = 0, $menuGroupName="main") {
	global $_TABLE;
	global $_ConfMenu;
	global $error;
	global $cnt_loop;
	global $max_loop;
	
	$aNavElements = array();
	
	if ($cnt_loop++ > $max_loop) {
		$error.= "#".__LINE__." Max.Loops:$max_loop überschritten!<br>\n";
		$cnt_loop = 0;
		return false;
	}
	
	$_Items = array();
	$SQL = "SELECT *\n";
	$SQL.= " FROM ".$_TABLE["cms_bereiche"]." \n";
	$SQL.= " WHERE \n";
	$SQL.= " parentid = \"".addslashes($parentid)."\" \n";
	if ($menuGroupName != "%") $SQL.= " AND menu_groupname = \"".MyDB::escape_string($menuGroupName)."\" ";
	$SQL.= " AND webfreigabe = \"Ja\" \n";
	$SQL.= " AND (\n";
	$SQL.= " ( (visibility_iflogin IS NULL OR visibility_iflogin ='none') AND visibility = \"visible\")\n";
	
	if (!is_array($user) || !count($user)) {
		$SQL.= " OR visibility_iflogin = 'logout' \n";
	} else {
		$SQL.= " OR visibility_iflogin = 'login' \n";
		
		if ($user["gruppe"] == "user") {
			$SQL.= " OR visibility_iflogin = 'loginas:user' \n";
		}
		if ($user["gruppe"] == "admin") {
			$SQL.= " OR visibility_iflogin = 'loginas:admin' \n";
		}
	}
	$SQL.= " )\n";
	
	$SQL.= " ORDER BY ordnungszahl, name";
	// echo "<pre>#".__LINE__." user:".print_r($user, true)."</pre>\n";
	//echo "<pre>#".__LINE__." SQL:".$SQL."</pre>\n";
	$r = MyDB::query($SQL);
	
	
	if ($r) {
		
		$n = MyDB::num_rows($r);
		if ($n) {
			for ($i = 0; $i < $n; $i++) {
				$j = count($aNavElements);
				$aNavElements[$j] = MyDB::fetch_array($r, MYSQL_ASSOC);
				// echo "#".__LINE__." i:$i, srv:".$_Item["srv"]."<br>\n";
				$aNavElements[$j]["deep"] = $deep;
				
				$aSubElements = get_navelements($user, $aNavElements[$j]["id"], $deep+1, $menuGroupName);
				if (is_array($aSubElements) && count($aSubElements)) {
					$aNavElements[$j]["hasChilds"] = true;
					$aNavElements[$j]["childs"] = $aSubElements;
				} else {
					$aNavElements[$j]["hasChilds"] = false;
				}
			}
			MyDB::free_result($r);
			
		}
		if ($aNavElements) { 
			return $aNavElements;
		}
	} else {
		// echo "<pre>#".__LINE__." ".MyDB::error()."\nSQL:".fb_htmlEntities($SQL)."</pre>\n";
	}
	
	return false;
}

function render_navelements(&$aNavElements, $deep = 0, $menuGroupName="main", $HP_LANG = "DE") {
	if (!is_array($aNavElements) || !count($aNavElements)) {
	    return "";
    }
	global $_TABLE;
	global $_ConfMenu;
	global $error;
	global $cnt_loop;
	global $max_loop;
	
	$dyn_navbar = "";
	
	if ($deep == 0 && !isset($_ConfMenu[$menuGroupName])) {
		if (isset($_ConfMenu["default"])) $menuGroupName = "default";
	}
	
	if ($cnt_loop++ > $max_loop) {
		$error.= "#".__LINE__." Max.Loops:$max_loop überschritten!<br>\n";
		$cnt_loop = 0;
		return false;
	}
	
	$_Items = array();
	
		$n = count($aNavElements);
		// echo "<pre>#".__LINE__." ".fb_htmlEntities($SQL)." n:$n</pre>\n";
		if ($n) {
			foreach($aNavElements as $i => $_Item) { // ($i = 0; $i < $n; $i++) {
				$_Item = $aNavElements[$i];
				
				if ($aNavElements[$i]["hasChilds"] && $deep + 1 < $_ConfMenu[$menuGroupName]["MenuDeep"]) {
					//$sub_navbar = render_navelements($aNavElements[$i]["childs"], $deep+1, $menuGroupName, $HP_LANG);
                    $sub_navbar = '';
				} else {
					$sub_navbar = "";
				}
				
				if ($sub_navbar) {
					$tpl = $_ConfMenu[$menuGroupName]["Tpl"]["DynLevel"][$deep]["WithSub"];
					// echo "#".__LINE__."<br>\n";
				} else {
					$tpl = $_ConfMenu[$menuGroupName]["Tpl"]["DynLevel"][$deep]["NoSub"];
				}
				
				if ($tpl) {
				    $_isVisible = $_Item["IsVisible"];
					$strMenu = $_isVisible ? render_dyn_navitem($tpl, $_Item, $sub_navbar, $HP_LANG) : "";
					$dyn_navbar.= $strMenu;
				} else {
					$error.= "#".__LINE__." ".basename(__FILE__)." ";
					$error.= __FUNCTION__."(deep = $deep)<br>\n";
					$error.= "\$_Item[srv]: $_Item[srv]<br>\n";
					$error.= "\$menuGroupName: $menuGroupName<br>\n";
					$error.= "\$tpl: $tpl<br>\n";
					$error.= "\"".$_Item["srv"]."\": Menüvorlage der Ebene $deep ".($sub_navbar ? "mit" : "ohne")." Untermenüs konnte nicht gefunden werden!<br>\n";
					if (DEBUG) echo $error;
				}
			}
			
			if ($_ConfMenu[$menuGroupName]["MenuNeedle"] && $deep == 0) {
				$nav = implode("", file($_ConfMenu[$menuGroupName]["Tpl"]["MenuFile"]));
				$dyn_navbar = str_replace($_ConfMenu[$menuGroupName]["MenuNeedle"], $dyn_navbar, $nav);
			}
		}
		
		if ($dyn_navbar) { 
			return $dyn_navbar;
		}
	
	/**/
	return false;
}


function load_menu_tpls($menuGroupName = "main") {
	global $_ConfMenu;
	global $error;
	
	// echo "#".__LINE__." ".__FILE__." \$_ConfMenu[$menuGroupName]:".$_ConfMenu[$menuGroupName]."<br>\n";
	//if (empty($_ConfMenu[$menuGroupName])) return false;
	
	if (empty($_ConfMenu[$menuGroupName]) || !file_exists($_ConfMenu[$menuGroupName]["Tpl"]["BaseFile"])) {
		$_ConfMenu[$menuGroupName] = $_ConfMenu["default"];
	}
	$baseTplFile = $_ConfMenu[$menuGroupName]["Tpl"]["BaseFile"];
	$baseTpl = implode("", file($baseTplFile));
	// echo "#".__LINE__." ".$baseTpl."<br>\n";

    $iNumGroupMenues = count($_ConfMenu[$menuGroupName]["Tpl"]["DynLevel"]);
	for ($i = 0; $i < $iNumGroupMenues; $i++) {
		
		foreach($_ConfMenu[$menuGroupName]["Tpl"]["DynLevel"][$i] as $k => $v ) {
			$startTag = "<!-- DynLevel:".strval($i).":".$k." -->";
			$closeTag = "<!-- /DynLevel:".strval($i).":".$k." -->";
			$v = get_cms($startTag, $closeTag, $baseTpl);
			if ($v) {
				$_ConfMenu[$menuGroupName]["Tpl"]["DynLevel"][$i][$k] = $v;
				// echo "<pre>#".__LINE__." \$_ConfMenu[\"Tpl\"][\"DynLevel\"][$i][$k] = \n";
				// echo " ".fb_htmlEntities($_ConfMenu[$menuGroupName]["Tpl"]["DynLevel"][$i][$k])."</pre>\n";
			} else {
				$err = "#".__LINE__." Tpl NotFound:\n";
				$err.= "#".__LINE__." menuGroupName:".$menuGroupName."\n";
				$err.= "#".__LINE__." startTag:".$startTag."\n";
				$err.= "#".__LINE__." closeTag:".$closeTag."\n";
				$err.= "#".__LINE__." baseTplFile:".$baseTplFile."\n";
				$err.= "#".__LINE__." baseTpl:".$baseTpl."\n";
				if (DEBUG) {
					$error.= "<br>\n".$err."<br>\n";
					echo "#".__LINE__." ".__FILE__."<br>\n".nl2br(fb_htmlEntities($err))."<br>\n";
				}
				return false;
			}
		}
	}
	// echo "#".__LINE__." ".__FILE__." <br>\n";
	return true;
}

function render_dyn_navitem($tpl, $Item, $submenu = "") {
	global $_ConfMenu;
	$_AttrDiv   = array();
	$_AttrAhref = array();
	$_AttrSub   = array();
	$_RplTpl    = array();
	$_FitTags   = array();
	$strMenu = "";
	if (!$Item) return "";
	
	$menuGroupName = $Item["menu_groupname"];
	// echo "#".__LINE__." ".__FILE__." Item:".print_r($Item, true)."<br>\n";
	$_RplTpl["/*showContent*/"] = "";
	
	if (HP_LANG != $Item["lang"]) {
		// echo "#".__LINE__." ".basename(__FILE__)."; (".$Item["name_translations"].") <br>\n";
		parse_str(str_replace("\n", "&", str_replace("\r","\n",$Item["name_translations"])), $t);
		foreach($t as $k => $v) {
			if (trim($k) == HP_LANG) {
				$Item["menu_title"] = trim($v);
				break;
			}
		}
	}
	
	if ($Item["create_menu_script"] || $Item["create_menu_function"]) {
		if (!run_menu_creator($tpl, $Item)) return "";
		if (!empty($Item["strMenu"])) return $Item["strMenu"];
	}
	
	// Menü-srv
	$_RplTpl["{srv}"] = $Item["srv"];
	// echo "#".__LINE__." srv:".$Item["srv"]."<br>\n";
	// Menü-Benennung
	$m_ti = ($Item["menu_title"] ? $Item["menu_title"] : $Item["name"]);
	if ($Item["menu_icon"]) {
		if (substr(trim($Item["menu_icon"]), 0, 1) == "<") {
			$m_ic = $Item["menu_icon"];
			$m_ti = $Item["menu_icon"].$m_ti;
		} else {
			$m_ic = "<img class=\"menicon\" id=\"ico".$Item["srv"]."\" ";
			$m_ic.= " src=\"".$Item["menu_icon"]."\" border=\"0\">";
			$m_ti = $m_ic.$m_ti;
		}
	}
	// echo "#".__LINE__." m_ti: ".fb_htmlEntities($m_ti)."<br>\n";
	$_RplTpl["{menu_title}"] = $m_ti;
	
	// SubMenüs
	$_RplTpl["{submenu}"] = $submenu;
	
	// Special-Html ersetzt komplette Vorlagenstruktur
	if ($Item["menu_html"]) {
		$strMenu = $Item["menu_html"];
		$strMenu = strtr($strMenu, $_RplTpl); // "{submenu}", $submenu, $strMenu);
		$strMenu = str_replace("{menu_link}", $Item["menu_link"], $strMenu);
		return $strMenu;
	} else {
		$strMenu = $tpl;
	}
	
	// Menü-Link-URL
	// 
	if ($Item["menu_link"]) {
		$m_li = $Item["menu_link"];
	} else {
		$m_li = $_ConfMenu[$menuGroupName]["msbBaselink"];
		// echo "#".__LINE__." ".__FILE__." _ConfMenu[$menuGroupName][msbBaselink]".$_ConfMenu[$menuGroupName]["msbBaselink"]."<br>\n";
		$m_li = str_replace("{srv}", $Item["srv"], $m_li);
		$m_li = str_replace("{cid}", $Item["id"],  $m_li);
	}
	
	if (is_int(strpos($strMenu, "{menu_link}"))) {
		$_RplTpl["{menu_link}"] = $m_li;
	} else {
		$_AttrAhref["href"] = $m_li;
	}
	
	// Menü-Link-Target
	$m_tg = ($Item["menu_target"] ? $Item["menu_target"] : "_self");
	if (is_int(strpos($strMenu, "{menu_target}"))) {
		$_RplTpl["{menu_target}"] = $m_tg;
	} else {
		$_AttrAhref["target"] = $m_tg;
	}
	//
	// Menü-Class
	if ($Item["menu_class"]) {
		$_AttrDiv["class"] = $Item["menu_class"];
	}
	
	// Menü-Style
	$m_st = ($Item["menu_style"] ? $Item["menu_style"] : "");
	$_RplTpl["/*menu_style*/"] = $m_st;
	if (is_int(strpos($strMenu, "/*menu_style*/"))) {
		$_RplTpl["/*menu_style*/"] = $m_st;
	} else {
		$_AttrDiv["style"] = $m_st;
	}
	
	// Menü-OnClick
	if ($Item["menu_onclick"]) {
		$_AttrDiv["onclick"] = $Item["menu_onclick"];
	}
	
	if ($Item["hasChilds"]) {
		switch($Item["menu_behaviour"]) {
			case "openLink":
			$_RplTpl["/*showContent*/"] = "gotoMenuLink(this);";
			break;
			
			case "openSubMenu":
			$_RplTpl["/*showContent*/"] = "showMenu('Sub'+this.id, this.id);";
			break;
			
			default:
			break;
		}
		
		switch($Item["submenu_behaviour"]) {
			case "visible":
			$_RplTpl["display:none;/*Display{srv}*/"] = "display:;/*Display{srv}*/";
			$_RplTpl["display:none;/*Display".$Item["srv"]."*/"] = "display:;/*Display".$Item["srv"]."*/";
			break;
			
			case "hidden":
			break;
		}
	}
	/*
	if ($Item["menu_div_attr"])   {
		$_FitTags[count($_FitTags)] = array(
			"tag" => "div",
			"find" => array("x_div_attr" => true),
			"attr" => parse_htmlTag($Item["menu_div_attr"])
		);
	}
	
	
	if ($Item["menu_ahref_attr"]) {
		$_FitTags[count($_FitTags)] = array(
			"tag" => "a",
			"find" => array("x_ahref_attr" => true),
			"attr" => parse_htmlTag($Item["menu_div_attr"])
		);
	}
	
	if ($Item["menu_sub_attr"])   {
		$_FitTags[count($_FitTags)] = array(
			"tag" => "div",
			"find" => array("x_sub_attr" => true),
			"attr" => parse_htmlTag($Item["menu_sub_attr"])
		);
	}
	*/
	$strMenu = strtr($strMenu, $_RplTpl);
	$strMenu = fit_htmlTags($strMenu, $_FitTags);
	
	if ($Item["menu_code_before"]) {
		$strMenu = $Item["menu_code_before"].$strMenu;
	}
	
	if ($Item["menu_code_behind"]) {
		$strMenu = $strMenu.$Item["menu_code_behind"];
	}
	
	// echo "#".__LINE__." <b>Menu:</b> ".fb_htmlEntities($strMenu)."<br>\n";
	/**/
	return $strMenu;
}

function render_dyn_navbar($parentid = 0, $deep = 0, $menuGroupName = "main") {
	global $_TABLE;
	global $_ConfMenu;
	global $error;
	
	$dyn_navbar = "";
	
	if ($cnt_loop++ > $max_loop) {
		echo "#".__LINE__." Max.Loops:$max_loop überschritten!<br>\n";
		$cnt_loop = 0;
		return false;
	}
	
	$_Items = array();
	$SQL = "SELECT *\n";
	$SQL.= " FROM ".$_TABLE["cms_bereiche"]." \n";
	$SQL.= " WHERE \n";
	$SQL.= " `parentid` = \"".addslashes($parentid)."\" \n";
	$SQL.= " AND `webfreigabe` = \"Ja\" \n";
	$SQL.= " AND `visibility` = \"visible\" \n";
	$SQL.= " AND `menu_groupname` = \"".MyDB::escape_string($menuGroupName)."\" ";
	$SQL.= " ORDER BY `ordnungszahl`, `name`";
	$r = MyDB::query($SQL);
	
	if ($r) {
		
		$n = MyDB::num_rows($r);
		// echo "<pre>#".__LINE__." ".fb_htmlEntities($SQL)." n:$n</pre>\n";
		if ($n) {
			for ($i = 0; $i < $n; $i++) {
				$_Item = MyDB::fetch_array($r, MYSQL_ASSOC);
				// echo "#".__LINE__." i:$i, srv:".$_Item["srv"]."<br>\n";
				$_Item["deep"] = $deep;
				
				if ($deep + 1 < $_ConfMenu[$menuGroupName]["MenuDeep"]) {
					$sub_navbar = render_dyn_navbar($_Item["id"], $deep+1);
				} else {
					$sub_navbar = "";
				}
				
				if ($sub_navbar) {
					$_Item["hasChilds"] = true;
					$tpl = $_ConfMenu[$menuGroupName]["Tpl"]["DynLevel"][$deep]["WithSub"];
					// echo "#".__LINE__."<br>\n";
				} else {
					$tpl = $_ConfMenu[$menuGroupName]["Tpl"]["DynLevel"][$deep]["NoSub"];
					$_Item["hasChilds"] = false;
					// echo "#".__LINE__."<br>\n";
				}
				// echo "#".__LINE__." <b>tpl</b> fuer ".$_Item["srv"]." : ".fb_htmlEntities($tpl)."  <br>\n";
				// echo "#".__LINE__." <b>sub_navbar</b> fuer ".$_Item["srv"]." : ".fb_htmlEntities($sub_navbar)."  <br>\n";
				
				if ($tpl) {
					$strMenu = render_dyn_navitem($tpl, $_Item, $sub_navbar);
					// echo "#".__LINE__." <b>Menu:</b> ".fb_htmlEntities($strMenu)."<br>\n";
					$dyn_navbar.= $strMenu;
				} else {
					$error.= "#".__LINE__." ".basename(__FILE__)." ";
					$error.= __FUNCTION__."(parentid, $parentid, deep = $deep)<br>\n";
					$error.= "\"".$_Item["srv"]."\": Menüvorlage der Ebene $deep ".($sub_navbar ? "mit" : "ohne")." Untermenüs konnte nicht gefunden werden!<br>\n";
				}
			}
			
			// echo "<br>#".__LINE__." <b>$dyn_navbar:</b> ".fb_htmlEntities($dyn_navbar)."<br>\n";
			MyDB::free_result($r);
			
			if ($_ConfMenu[$menuGroupName]["MenuNeedle"] && $deep == 0) {
				$nav = implode("", file($_ConfMenu[$menuGroupName]["Tpl"]["MenuFile"]));
				$dyn_navbar = str_replace($_ConfMenu[$menuGroupName]["MenuNeedle"], $dyn_navbar, $nav);
			}
		}
		if ($dyn_navbar) { 
			return $dyn_navbar;
		}
	} else {
		echo "<pre>#".__LINE__." ".MyDB::error()."\nSQL:".fb_htmlEntities($SQL)."</pre>\n";
	}
	
	/**/
	return false;
}

function save_navbar($strMenueBar, $menuGroupName = "main") {
	global $_ConfMenu;
	global $error;
	
	$fp = fopen($_ConfMenu[$menuGroupName]["MenuFile"], "w+");
	if ($fp) {
		fputs($fp, $strMenueBar);
		fclose($fp);
		return true;
	}
	return false;
}


//load_menu_tpls();
//$strMenueBar = render_dyn_navbar(0);
