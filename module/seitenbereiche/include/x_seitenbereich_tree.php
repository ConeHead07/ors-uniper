<?php 

$msbBaselink = $baseLnk."&task=modul&modul=seitenbereiche";
$msbBaselinkTree = $baseLnk."&modul&ansicht=tree";
$activeMenuId = (isset($id) && $id) ? $id : 0;

$numberOfAllMenus = get_numberOfAllMenus();

$numberOfChilds = get_numberOfChilds($activeMenuId);
$body_content.= "#".__LINE__."Menüs auf aktueller Ebene: $numberOfChilds<br>\n";

// Hole MenuDaten
if (!empty($activeId)) {
	$Menu = get_menu($activeMenuId);
}

// Anzeige MenuPath
$body_content.= "<b>Menü-Pfad</b><br>\n";
$body_content.= "<div><a href=\"".$msbBaselinkTree."\"><b>Top</b></a></div>\n";
$_ParentItems = get_menu_parentItems($activeMenuId);
$intendWidth = "20"; // px:Pixel
for ($i = 0; $i < count($_ParentItems); $i++) {
	$body_content.= "<div>\n";
	$body_content.= "<div style=\"float:left;width:".(($i+1)*$intendWidth)."px;text-align:right;\">||=></div>\n";
	$body_content.= "<div>\n";
	$body_content.= "<a href=\"".$msbBaselinkTree."&id=".$_ParentItems[$i]["id"]."\">";
	$body_content.= "".$_ParentItems[$i]["name"]."";
	$body_content.= "</a></div>\n";
	$body_content.= "</div>\n";
	$body_content.= "<br>\n";
}
$body_content.= "<br>\n";

// Anzeige MenuChilds
$body_content.= "<b>Untermenüs</b><br>\n";
$_ItemsByParentid = get_menu_itemsByParentid($activeMenuId);
if (is_array($_ItemsByParentid) && count($_ItemsByParentid)) {
	
	$body_content.= "<br>Menüelemente auf dieser Ebene: ".count($_ItemsByParentid)." | Alle: $numberOfAllMenus<br>\n";
	$body_content.= "<style>\n";
	$body_content.= ".tblSB * { \n";
	$body_content.= "   font-size:12px;\n";
	$body_content.= "}";
	$body_content.= ".tblSB { \n";
	$body_content.= "   width:100%;\n";
	$body_content.= "   border:0px;\n";
	$body_content.= "   border-collapse:collapse;\n";
	$body_content.= "   border-spacing:0px;\n";
	$body_content.= "   border-top:1px solid gray;\n";
	$body_content.= "   border-left:1px solid gray;\n";
	$body_content.= "}";
	$body_content.= ".tblSB td {  \n";
	$body_content.= "   padding:3px; \n";
	$body_content.= "   border-bottom:1px solid gray;\n";
	$body_content.= "   border-right:1px solid gray;\n";
	$body_content.= "} \n";
	$body_content.= ".tblSB img {  \n";
	$body_content.= "   border:0px;\n";
	$body_content.= "} \n";
	$body_content.= ".tblSB * a {  \n";
	$body_content.= "   text-decoration:none;\n";
	$body_content.= "   color:blue;\n";
	$body_content.= "} \n";
	$body_content.= "</style>\n";
	
	$body_content.= "<table class=\"tblSB\">\n";
	$body_content.= "<tr>\n";
	$body_content.= "<td>Menü/Ebene</td>";
	$body_content.= "<td>Edit</td>";
	$body_content.= "<td>Aktiviert</td>";
	$body_content.= "<td>Menüanzeige</td>";
	$body_content.= "<td><img src=\"../images/usr_icon.gif\" width=\"15\" height=\"14\" alt=\"Status, ob Frei zugänglich\">Freier Zugang</td>";
	$body_content.= "<td>Pos</td>";
	$body_content.= "<td>Kill</td>";
	$body_content.= "</tr>";
	for ($i = 0; $i < count($_ItemsByParentid); $i++) {
		$r_id = $_ItemsByParentid[$i]["id"];
		$r_pid = $_ItemsByParentid[$i]["parentid"];
		$body_content.= "<tr>\n";
		
		$body_content.= "<td>\n";
		$body_content.= "<a href=\"".$msbBaselinkTree."&id=".$r_id."\">";
		$body_content.= "&raquo;".$_ItemsByParentid[$i]["name"]."</a></td>\n";
		
		$r_free = &$_ItemsByParentid[$i]["webfreigabe"];
		$r_visi = &$_ItemsByParentid[$i]["visibility"];
		$r_lock = &$_ItemsByParentid[$i]["geschuetzt"];
		
		$r_free_bg = ($r_free == "Ja" ? "#c6fabe" : "#f0c8cb"); // BG: Green || Red
		$r_visi_bg = ($r_visi == "visible" ? "#c6fabe" : "#f0c8cb"); // BG: Green || Red
		$r_lock_bg = ($r_lock == "Nein" ? "#c6fabe" : "#f0c8cb"); // BG: Green || Red
		
		$invert_free = ($r_free != "Ja" ? "Ja" : "Nein");
		$invert_lock = ($r_lock != "Ja" ? "Ja" : "Nein");
		$invert_visi = ($r_visi != "visible" ? "visible" : "hidden");
		
		// Edit
		$body_content.= "<td><a href=\"".$msbBaselinkTree."&ansicht=edit&id=".$r_id."&parentid=\">Edit</a></td>\n";
		
		// Webfreigabe
		$body_content.= "<td style=\"background:$r_free_bg;\" title=\"Editierbarer Status: Web-Freigabe\">";
		$body_content.= "".$_GetFlag["webfreigabe"][$r_free]."";
		$body_content.= " &raquo; <a href=\"".$msbBaselinkTree."&cmd=setfreigabe&wert=$invert_free&id=".$r_id."&pid=$r_pid\">";
		$body_content.= "".$_SetFlag["webfreigabe"][$invert_free]."</a></td>\n";
		
		// Men�anzeige
		$body_content.= "<td style=\"background:$r_visi_bg;\" title=\"Editierbarer Status: Menüanzeige\">";
		$body_content.= $_GetFlag["visibility"][$r_visi];
		$body_content.= " &raquo; <a href=\"".$msbBaselinkTree."&cmd=setvisibility&wert=$invert_visi&id=".$r_id."&pid=$r_pid\">";
		$body_content.= "".$_SetFlag["visibility"][$invert_visi]."</a></td>\n";
		
		// Passwortschutz
		$body_content.= "<td style=\"background:$r_lock_bg;\" title=\"Editierbarer Status: Passwortschutz\">";
		$body_content.= $_GetFlag["geschuetzt"][$r_lock];
		$body_content.= " &raquo; <a href=\"".$msbBaselinkTree."&cmd=setgeschuetzt&wert=$invert_lock&id=".$r_id."&pid=$r_pid\">";
		$body_content.= "".$_SetFlag["geschuetzt"][$invert_lock]."</a></td>\n";
		
		// Positionierung
		$body_content.= "<td title=\"Positionierung\">";
		$body_content.= " <a href=\"".$msbBaselinkTree."&cmd=pos&wert=first&id=".$r_id."&pid=$r_pid\">";
		$body_content.= "<img src=\"../images/pfeil_nachganzoben.gif\" width=\"10\" height=\"10\" alt=\"\"></a>";
		$body_content.= " <a href=\"".$msbBaselinkTree."&cmd=pos&wert=higher&id=".$r_id."&pid=$r_pid\">";
		$body_content.= "<img src=\"../images/pfeil_nachoben.gif\" width=\"10\" height=\"10\" alt=\"\"></a>";
		$body_content.= " <a href=\"".$msbBaselinkTree."&cmd=pos&wert=lower&id=".$r_id."&pid=$r_pid\">";
		$body_content.= "<img src=\"../images/pfeil_nachunten.gif\" width=\"10\" height=\"10\" alt=\"\"></a>";
		$body_content.= " <a href=\"".$msbBaselinkTree."&cmd=pos&wert=last&id=".$r_id."&pid=$r_pid\">";
		$body_content.= "<img src=\"../images/pfeil_nachganzunten.gif\" width=\"10\" height=\"10\" alt=\"\"></a>";
		$body_content.= "</td>\n";
		
		// L�schen
		$body_content.= "<td title=\"Editierbarer Status: Sichtbarkeit im Menü\">";
		$body_content.= "<a href=\"".$msbBaselinkTree."&cmd=loeschen&wert=1&id=".$r_id."&pid=$r_pid\"><img src=\"../images/kill_item.gif\" width=\"10\" height=\"10\" alt=\"Men� löschen\"></a></td>\n";
		
		$body_content.= "</tr>\n";
	}/**/
	
	$body_content.= "</table>\n";
	
} else {
	$body_content.= "Es existieren keine Untermenüs!<br>\n";
}

$body_content.= "<br>\n";
/**/
?>
