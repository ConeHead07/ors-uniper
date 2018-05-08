<?php 

$max_loop = 500;
$cnt_loop = 0;

function get_menu_groupnames($parentid = 0) {
	global $_TABLE;
	global $connid;
	$aReGroupNames = array();
	$SQL = "SELECT DISTINCT(menu_groupname) \n";
	$SQL.= " FROM ".$_TABLE["cms_bereiche"]." \n";
	$SQL.= " WHERE parentid = \"".addslashes($parentid)."\" \n";
	$SQL.= " ORDER BY menu_groupname";
	// echo "<pre>#".__LINE__." SQL:".fb_htmlEntities($SQL)." </pre>\n";
	$r = MyDB::query($SQL, $connid);
	
	if ($r) {
		$n = MyDB::num_rows($r);
		for ($i = 0; $i < $n; $i++) {
			$e = MyDB::fetch_array($r, MYSQL_ASSOC);
			$aReGroupNames[] = $e["menu_groupname"];
		}
		MyDB::free_result($r);
	} else {
		echo "<pre>#".__LINE__." ".MyDB::error()."\nSQL:".fb_htmlEntities($SQL)."</pre>\n";
	}
	return $aReGroupNames;
}



function get_menu_tree($onlyContentMenu = false, $parentid = 0, $deep = 0) {
	global $_TABLE;
	global $connid;
	global $IsInitMenuTree;
	global $cnt_loop;
	global $max_loop;
	// echo "#".__LINE__." ".__FILE__." ".__FUNCTION__."($parentid)<br>\n";
	
	if ($deep == 0) $cnt_loop = 0;
	if ($cnt_loop++ > $max_loop) {
		echo "#".__LINE__." Max.Loops:$max_loop �berschritten!<br>\n";
		$cnt_loop = 0;
		return false;
	}
	
	$_Items = array();
	$SQL = "SELECT id, parentid, srv, name, webfreigabe, geschuetzt, visibility, menu_groupname, menu_link, ordnungszahl \n";
	$SQL.= " FROM ".$_TABLE["cms_bereiche"]." \n";
	$SQL.= " WHERE parentid = \"".addslashes($parentid)."\" \n";
	if ($onlyContentMenu) $SQL.= " AND content = \"Ja\" \n";
	$SQL.= " ORDER BY menu_groupname, ordnungszahl, name";
	// echo "<pre>#".__LINE__." SQL:".fb_htmlEntities($SQL)." </pre>\n";
	$r = MyDB::query($SQL, $connid);
	
	if ($r) {
		$n = MyDB::num_rows($r);
		
		for ($i = 0; $i < $n; $i++) {
			$j = count($_Items);
			$_Items[$j] = MyDB::fetch_array($r, MYSQL_ASSOC);
			$_Items[$j]["deep"] = $deep;
			
			$_ChildItems = get_menu_tree($onlyContentMenu, $_Items[$j]["id"], $deep+1);
			if (is_array($_ChildItems) && count($_ChildItems)) {
				$_Items[$j]["hasChilds"] = true;
				$_Items = array_merge($_Items, $_ChildItems);
			} else {
				$_Items[$j]["hasChilds"] = false;
			}
		}
		MyDB::free_result($r);
		if (count($_Items)) {
			$IsInitMenuTree = true;
			return $_Items;
		}
	} else {
		echo "<pre>#".__LINE__." ".MyDB::error()."\nSQL:".fb_htmlEntities($SQL)."</pre>\n";
	}
	return false;
}

function get_menu_tree_options(&$_MenuTree, $checkId = 0) {
	global $IsInitMenuTree;
	$menu_options = "";
	$intend = "";
	
	if (is_array($_MenuTree)) {
		for ($i = 0; $i < count($_MenuTree); $i++) {
			//echo "#".__LINE__."<br>\n";
			$intend = "";
			if ($_MenuTree[$i]["deep"]) {
				for ($j = 0; $j < count($_MenuTree[$i]["deep"]); $j++) {
					$intend.= "...";
				}
			}
			$selected = ($_MenuTree[$i]["id"] == $checkId) ? "selected" : "";
			$menu_options.= "<option value=\"".$_MenuTree[$i]["id"]."\" $selected>".$intend.$_MenuTree[$i]["name"]."</option>\n";
		}
	}
	return $menu_options;
}

function get_numberOfAllMenus() {
	global $_TABLE;
	global $connid;
	
	$SQL = "SELECT COUNT(*) FROM ".$_TABLE["cms_bereiche"]." \n";
	$r = MyDB::query($SQL, $connid);
	if ($r) {
		list($count) = MyDB::fetch_array($r, MyDB::NUM);
		MyDB::free_result($r);
		return $count;
	}
	return false;	
}

function get_numberOfChilds($parentid) {
	global $_TABLE;
	global $connid;
	
	$SQL = "SELECT COUNT(*) FROM ".$_TABLE["cms_bereiche"]." \n";
	$SQL.= " WHERE parentid = \"".addslashes($parentid)."\" \n";
	$r = MyDB::query($SQL, $connid);
	if ($r) {
		list($count) = MyDB::fetch_array($r, MyDB::NUM);
		MyDB::free_result($r);
		return $count;
	}
	return false;	
}

function get_menu_itemsByParentid($parentid = 0) {
	global $_TABLE;
	global $connid;
	
	$_Items = array();
	$SQL = "SELECT id, parentid, srv, name, webfreigabe, geschuetzt, visibility \n";
	$SQL.= " FROM ".$_TABLE["cms_bereiche"]." \n";
	$SQL.= " WHERE parentid = \"".addslashes($parentid)."\" \n";
	$SQL.= " ORDER BY ordnungszahl, name";
	
	$r = MyDB::query($SQL, $connid);
	
	if ($r) {
		$n = MyDB::num_rows($r);
		
		for ($i = 0; $i < $n; $i++) {
			$j = count($_Items);
			$_Items[$j] = MyDB::fetch_array($r, MYSQL_ASSOC);			
			$_Items[$j]["numberOfChilds"] = get_numberOfChilds($_Items[$j]["parentid"]);
		}
		MyDB::free_result($r);
		if (count($_Items)) {
			return $_Items;
		}
	} else {
		echo "<pre>#".__LINE__." ".MyDB::error()."\nSQL:".fb_htmlEntities($SQL)."</pre>\n";
	}
	return false;
}

function get_menu_byId($id) {
	global $_TABLE;
	global $connid;
	
	$Menu = array();
	$SQL = "SELECT * \n";
	$SQL.= " FROM ".$_TABLE["cms_bereiche"]." \n";
	$SQL.= " WHERE id = \"".addslashes($id)."\" \n";
	$r = MyDB::query($SQL, $connid);
	if ($r) {
		if (MyDB::num_rows($r)) {
			$Menu = MyDB::fetch_array($r, MYSQL_ASSOC);
			MyDB::free_result($r);
			return $Menu;
		}
	}
	return false;
}
/*
function get_menu_bySrv($srv) {
	global $_TABLE;
	global $connid;
	
	$Menu = array();
	$SQL = "SELECT * \n";
	$SQL.= " FROM ".$_TABLE["cms_bereiche"]." \n";
	$SQL.= " WHERE srv = \"".addslashes($srv)."\" \n";
	$r = MyDB::query($SQL, $connid);
	if ($r) {
		if (MyDB::num_rows($r)) {
			$Menu = MyDB::fetch_array($r, MYSQL_ASSOC);
			MyDB::free_result($r);
			return $Menu;
		}
	}
	return false;
}*/

function get_menu_parentItems($parentid) {
	global $_TABLE;
	global $connid;
	
	$_ParentItems = array();
	$SQL = "SELECT id, parentid, srv, name, webfreigabe, geschuetzt, gruppen, rechte, visibility \n";
	$SQL.= " FROM ".$_TABLE["cms_bereiche"]." \n";
	$SQL.= " WHERE id = \"".addslashes($parentid)."\" \n";
	$r = MyDB::query($SQL, $connid);
	if ($r) {
		if (MyDB::num_rows($r)) {
			$_ParentItems[0] = MyDB::fetch_array($r, MYSQL_ASSOC);
			if ($_ParentItems[0]["parentid"] > 0) {
				$_Tmp = get_menu_parentItems($_ParentItems[0]["parentid"]);
				if (is_array($_Tmp)) {
					$_ParentItems = array_merge($_Tmp, $_ParentItems);
				}
			}
			MyDB::free_result($r);
			return $_ParentItems;
		}
	}
	return false;
}

function get_menu($id) {
	global $_TABLE;
	global $connid;
	// echo "#".__LINE__." ".__FILE__." ".__FUNCTION__."($i)<br>\n";
	
	$Menu = array();
	$SQL = "SELECT * \n";
	$SQL.= " FROM ".$_TABLE["cms_bereiche"]." \n";
	$SQL.= " WHERE id = \"".addslashes($id)."\" \n";
	$r = MyDB::query($SQL, $connid);
	if ($r) {
		if (MyDB::num_rows($r)) {
			$Menu = MyDB::fetch_array($r, MYSQL_ASSOC);
			MyDB::free_result($r);
			return $Menu;
		}
	}
	return false;
}

function get_menusrv($id) {
	global $_TABLE;
	global $connid;
	// echo "#".__LINE__." ".__FILE__." ".__FUNCTION__."($i)<br>\n";
	
	$Menu = array();
	$SQL = "SELECT srv \n";
	$SQL.= " FROM ".$_TABLE["cms_bereiche"]." \n";
	$SQL.= " WHERE id = \"".MyDB::escape_string($id)."\" \n";
	$r = MyDB::query($SQL, $connid);
	if ($r) {
		if (MyDB::num_rows($r)) {
			list($srv) = MyDB::fetch_assoc($r);
			MyDB::free_result($r);
			return $srv;
		}
	}
	return false;
}

?>