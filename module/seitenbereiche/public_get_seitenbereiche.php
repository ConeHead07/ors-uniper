<?php 
require_once(dirname(__FILE__)."/../../header.php");
include($MConf["AppRoot"].$MConf["Modul_Dir"]."seitenbereiche/include/lib_get_public_menues.php"); //public_get_menu.php

$tree = menu_get_public_tree($user);
//echo "<pre>#".__LINE__." tree: "; print_r($tree); echo "</pre>";

$menusByGroupname = array();
$menusByElementsId = array();
$activeMenu2 = false;
if (empty($srv)) $srv = "";

$activeMenuID = menu_get_elementIdBySrv($srv); 
menu_map_treeElements($tree, $menusByGroupname, $menusByElementsId, $srv);

foreach($menusByGroupname as $gName => $aItems) 
	uasort($menusByGroupname[$gName], "menu_group_sort");

if (isset($menusByElementsId[$activeMenuID]))
	$activeMenu2 = $menusByElementsId[$activeMenuID];

$activeMenuElements = getActiveParentItems($activeMenuID, $menusByElementsId);


//echo "<pre>#".__LINE__." \$menusByElementsId: ".print_r($menusByElementsId,1)."</pre>\n";
/*
echo "<pre>#".__LINE__." ".print_r($activeMenuElements,1)."</pre>\n";
echo "#".__LINE__." ".basename(__FILE__)." srv:$srv, activeMenuID:$activeMenuID<br>\n";
echo "<pre>#".__LINE__." \$menusByGroupname: "; print_r($menusByGroupname); echo "</pre>";

echo "<pre>#".__LINE__." \$menusByElementsId: "; print_r($menusByElementsId); echo "</pre>";
echo "<pre>#".__LINE__." \$activeMenu2: "; print_r($activeMenu2); echo "</pre>";
*/

foreach($menusByElementsId as $menuId => $menuItem) {
	$menusByElementsId[$menuId]["IsVisible"] = menu_isVisible($menusByElementsId[$menuId], $activeMenuElements, $menusByElementsId);
	//echo "#".__LINE__." ".basename(__FILE__)." ".$menusByElementsId[$menuId]["name"]." IsVisible:".$menusByElementsId[$menuId]["IsVisible"]."<br>\n";
}

//menu_get_parentElements($id, $aNavElementsById);
foreach($activeMenuElements as $activeItem) {
	$_rplAusgabe[1][" active=\"".$activeItem["srv"]."\" class=\""] = " active=\"".$activeItem["srv"]."\" class=\"liActive ";
}

//die("<pre>#".__LINE__." DIE():".fb_htmlEntities(print_r($menusByGroupname,1))."</pre>");

foreach($menusByGroupname as $gName => $gNavElements) {
	if (!empty($gNavElements) && load_menu_tpls($gName)) {
		//echo "#".__LINE__." ".basename(__FILE__)." TemplateRegion für gName:$gName gefunden!<br>\n";
		$_rplAusgabe[0]["<!-- {MenuGroup:".$gName."} -->"] = render_navelements($gNavElements, 0, $gName, HP_LANG);
		//echo "#".__LINE__." ".basename(__FILE__)." gName:$gName <br>\n";
		//die("#".__LINE__." DIE():".fb_htmlEntities(print_r($_rplAusgabe[0]["<!-- {MenuGroup:".$gName."} -->"],1)));
	} else {
		echo "#".__LINE__." ".basename(__FILE__)." TemplateRegion für gName:$gName NICHT gefunden!<br>\n";
	}
}
