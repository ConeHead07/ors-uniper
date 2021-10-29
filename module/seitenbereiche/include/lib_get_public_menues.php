<?php 

function menu_get_parentElements($childMenu, &$aNavElementsById, &$aParentElements) {
	$aParentElements[] = $childMenu;
	if (!empty($childMenu["parentid"]) && $childMenu["id"] != $childMenu["parentid"]) {
		if ($aNavElementsById[$childMenu["parentid"]]) {
			menu_get_parentElements($aNavElementsById[$pid], $aNavElementsById, $aParentElements);
		}
	}
}

function menu_group_sort($a, $b) {
	return ($a["ordnungszahl"] > $b["ordnungszahl"]);
}

function menu_get_elementIdBySrv($srv) {
	global $_TABLE;
	global $db;
	$sql = "SELECT id FROM `".$_TABLE["cms_bereiche"]."` WHERE srv = \"".$db->escape($srv)."\" LIMIT 1";
	$row = $db->query_singlerow($sql);
	return ($row ? $row["id"] : false);
}

function menu_map_treeElements(&$tree, &$menusByGroupname, &$menusByElementsId, &$srv, $deep = 0) {
	if ($deep > 50) return false;
	foreach($tree as $i => $item) {
		if ($item["srv"] == $srv)  $activeMenu2 = &$tree[$i];
		$menusByGroupname[$item["menu_groupname"]][] = &$tree[$i];
		$menusByElementsId[$item["id"]] = &$tree[$i];
		if (count($item["childs"])) {
			menu_map_treeElements($item["childs"], $menusByGroupname, $menusByElementsId, $srv, $deep+1);
		}
	}
	return count($menusByElementsId);
}

function menu_isVisibleByCond(&$user, &$menu) {
	
	if (!is_array($menu) || empty($menu)) return false;
	
	if ($menu["visibility"] != 'conditional') return true;
	
	if (!$menu["visibility_condition"] || $menu["visibility_condition"] == "none") return true;
	
	if ($menu["visibility_condition"] == "logout") return empty($user);
	
	if (strpos($menu["visibility_condition"], "login") === false) return true;
	
	if (empty($user)) return false;
	
	if (strpos($menu["visibility_condition"], "loginas:mindestrechte") !== false) 
		if ($menu["rechte"] > $user["rechte"]) return false;
	
	if (strpos($menu["visibility_condition"], "loginas:gleicherechte") !== false)
		if ($menu["rechte"] != $user["rechte"]) $vi = "hidden";
	
	if (strpos($menu["visibility_condition"], "loginas:admin") !== false)
		if (strpos(",".$user["gruppe"].",", ",admin,") === false) $vi = "hidden";
	
	if (strpos($menu["visibility_condition"], "loginas:gruppe") !== false && strpos(",".$user["gruppe"].",",",admin,") === false && $user["rechte"]<7) {
		$aInGruppe = array_intersect(explode(",", str_replace(" ","",$menu["gruppen"])), explode(",",$user["gruppe"]));
		if (empty($aInGruppe) || !count($aInGruppe)) return false;
	}
	return true;
}

function menu_get_public_tree(&$user, $max_deep = 0, $parent_id = 0, $deep = 0, $parentElement = false) {
	global $_TABLE;
	global $db;
	
	$aUGruppen = explode(",", $user["gruppe"]);
	if ($max_deep && $deep >= $max_deep) return false;
	$sql = "SELECT * FROM ".$_TABLE["cms_bereiche"]." \n";
	$sql.= "WHERE parentid = ".intval($parent_id)."\n";
	$sql.= "AND webfreigabe = \"Ja\"\n";
	if ($user["adminmode"] !== 'superadmin') {
            $sql.= "AND \n";
            $sql.= "(geschuetzt = \"Nein\" \n";
            $sql.= " OR (\n";
            $sql.= " 		(gruppen = '' or gruppen is null ";

            foreach($aUGruppen as $g) {
                $sql.= " OR CONCAT(',',gruppen,',') LIKE \"%,".$db->escape($g).",%\"";
            }
            $sql.= " 		)\n";
            //$sql.= " 		AND \n";
            //$sql.= "		rechte <= ".intval($user["rechte"])."\n";
            $sql.= " 	)\n";
            $sql.= ")\n";
	}
	$sql.= " ORDER BY ordnungszahl";
	// echo "#".__LINE__." ".basename(__FILE__)." sql:$sql<br>\n";
	
	$tree = $db->query_rows($sql);
        echo $db->error();
	if (is_array($tree)) {
		foreach($tree as $i => $item) {
			$tree[$i]["IsVisibleByCond"] = menu_isVisibleByCond($user, $item);
			$tree[$i]["childs"] = menu_get_public_tree($user, $max_deep, $item["id"], $deep+1, $item);
			$tree[$i]["hasChilds"] = count($tree[$i]["childs"]);
		}
	}
	return $tree;
}

function getActiveParentItems($childId, $menusByElementsId) {
	if (!isset($menusByElementsId[$childId])) return array();
	
	$array = array($menusByElementsId[$childId]);
	if ($menusByElementsId[$childId] && $menusByElementsId[$childId]["parentid"]) {
		$pid = $menusByElementsId[$childId]["parentid"];
		$array = array_merge(getActiveParentItems($menusByElementsId[$pid]["id"], $menusByElementsId), $array);
	}
	return $array;
}

function menu_isVisible(&$menuElement, &$activeMenuElements, &$menusByElementsId) {
	
	// Check Visible If Login
	//echo "#".__LINE__." menu_isVisible: ".$menuElement["srv"]." <br>\n";
	if ($menuElement["visibility"] == "never") {
	    return false;
    }
	$id = $menuElement["id"];
	$name = $menuElement['name'];
	$parentid = $menuElement["id"];
	while($parentid != 0) {
		if (!empty($menusByElementsId[$parentid])) {
			$parentItem = $menusByElementsId[$parentid];
			if (!$parentItem["IsVisibleByCond"]) {
			    return false;
            }
			
			if ($id != $parentid) {
				if ($parentItem["submenu_behaviour"] == "hidden") {
					$isActive = false;
					foreach($activeMenuElements as $activeItem) 
						if ($activeItem["id"] == $parentItem["id"]) {
						    $isActive = true;
                        }
					
					if (!$isActive) {
                        return false;
                    }
				}
			}
			
			$parentid = $parentItem["parentid"];
			//echo "#".__LINE__." parentid: ".$parentItem["parentid"]." <br>\n";
		} else {
		    $parentid = 0;
        }
	}

    return true;
	// Check Parent Is Visible
}

function menu_isProtected(&$menuElement, &$user, &$tree, &$activeMenuElements, &$menusByElementsId) {
	return true;
	
	// Check Visible If Login
	if ($menuElement["geschuetzt"] == "Ja" && empty($user)) return true;
	
	$id = $menuElement["id"];
	$parentid = $menuElement["id"];
	while($parentid != 0) {
		if (!empty($menusByElementsId[$parentid])) {
			$mParent = $menusByElementsId[$parentid];
			if ($mParent["webfreigabe"] != "Ja") return true;
			if ($mParent["geschuetzt"] == "Ja" && empty($user)) return true;
			
			if ($mParent["rechte"] > $user["rechte"]) return true;
			
			$parentid = $mParent["parentid"];
		} else $parentid = 0;
	}
	return false;
}

