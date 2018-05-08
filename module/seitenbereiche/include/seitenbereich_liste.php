<?php 

$msbBaselink = $baseLnk; //"index.php?area=admin&a=sb";
$msbBaselinkListe = $baseLnk."&ansicht=liste";
$activeMenuId = (isset($id) && $id) ? $id : 0;

$numberOfAllMenus = get_numberOfAllMenus();

// Anzeige Hierarchische Menüliste
$body_content.= "<b>Untermenüs</b><br>\n";
$_MenuItems = get_menu_tree(0);
if (is_array($_MenuItems) && count($_MenuItems)) {
	$body_content.= "<br>Menüelemente auf dieser Ebene: ".count($_MenuItems)." | Alle: $numberOfAllMenus<br>\n";
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
	$body_content.= "<form action=\"".$msbBaselinkListe."\" method=\"post\" style=\"display:inline;margin:0px;\">\n";
	$body_content.= "<table class=\"tblSB\">\n";
	$body_content.= "<tr>\n";
	$body_content.= "<td>Menü/Ebene</td>";
	// $body_content.= "<td>Edit</td>";
	$body_content.= "<td>Aktiviert</td>";
	$body_content.= "<td>Menüanzeige</td>";
	//print_r($_CONF);
	$body_content.= "<td><img src=\"".$_CONF["WebRoot"]."/images/usr_icon.png\" width=\"15\" height=\"14\" alt=\"Status, ob Frei zugänglich\">Passwortschutz</td>";
	$body_content.= "<td>Pos</td>";
	$body_content.= "<td>Kill</td>";
	$body_content.= "</tr>";
	$last_groupname = "";
	for ($i = 0; $i < count($_MenuItems); $i++) {
		$strLenNumCount = strlen(count($_MenuItems));
		$r_id = $_MenuItems[$i]["id"];
		$r_pid = $_MenuItems[$i]["parentid"];
		
		$intend = "";
		if ($_MenuItems[$i]["deep"]) {
			for ($j = 0; $j < $_MenuItems[$i]["deep"]; $j++) {
				$intend.= "... ";
			}
		}
		if ($last_groupname != $_MenuItems[$i]["menu_groupname"]) {
			$body_content.= "<tr>\n";
			$body_content.= "<td><strong>".$_MenuItems[$i]["menu_groupname"]."</strong></td>\n";
			$body_content.= "<td></td>\n<td></td>\n<td></td>\n<td></td>\n<td></td>\n";
			$body_content.= "</tr>\n";
		}
		
		$body_content.= "<tr>\n";
		$body_content.= "<td>\n"; // Edit
		$body_content.= $intend;
		$body_content.= "&raquo; ";
		$body_content.= " <a href=\"".$msbBaselink."&ansicht=edit&id=".$r_id."\">".$_MenuItems[$i]["name"]." "; // (L:".$_MenuItems[$i]["deep"].")
		$body_content.= "</a></td>\n";
		
		$r_free = &$_MenuItems[$i]["webfreigabe"];
		$r_visi = &$_MenuItems[$i]["visibility"];
		$r_lock = &$_MenuItems[$i]["geschuetzt"];
		
		$r_free_bg = ($r_free == "Ja" ? "#c6fabe" : "#f0c8cb"); // BG: Green || Red
		$r_visi_bg = ($r_visi == "allways" ? "#c6fabe" : "#f0c8cb"); // BG: Green || Red
		$r_lock_bg = ($r_lock == "Nein" ? "#c6fabe" : "#f0c8cb"); // BG: Green || Red
		
		$invert_free = ($r_free != "Ja" ? "Ja" : "Nein");
		$invert_lock = ($r_lock != "Ja" ? "Ja" : "Nein");
		$invert_visi = ($r_visi != "allways" ? "allways" : "never");
		
		// Edit
		// $body_content.= "<td><a href=\"".$msbBaselink."&ansicht=edit&id=".$r_id."&parentid=\">Edit</a></td>\n";
		
		// Webfreigabe
		$body_content.= "<td style=\"background:$r_free_bg;\" title=\"Editierbarer Status: Web-Freigabe\">";
		$body_content.= "".$_GetFlag["webfreigabe"][$r_free]."";
		$body_content.= " &raquo; <a href=\"".$msbBaselinkListe."&cmd=setfreigabe&wert=$invert_free&id=".$r_id."&pid=$r_pid\">";
		$body_content.= "".$_SetFlag["webfreigabe"][$invert_free]."</a></td>\n";
		
		// Menüanzeige
		$body_content.= "<td style=\"background:$r_visi_bg;\" title=\"Editierbarer Status: Menüanzeige\">";
		$body_content.= $_GetFlag["visibility"][$r_visi];
		$body_content.= " &raquo; <a href=\"".$msbBaselinkListe."&cmd=setvisibility&wert=$invert_visi&id=".$r_id."&pid=$r_pid\">";
		$body_content.= "".$_SetFlag["visibility"][$invert_visi]."</a></td>\n";
		
		// Passwortschutz
		$body_content.= "<td style=\"background:$r_lock_bg;\" title=\"Editierbarer Status: Passwortschutz\">";
		$body_content.= $_GetFlag["geschuetzt"][$r_lock];
		$body_content.= " &raquo; <a href=\"".$msbBaselinkListe."&cmd=setgeschuetzt&wert=$invert_lock&id=".$r_id."&pid=$r_pid\">";
		$body_content.= "".$_SetFlag["geschuetzt"][$invert_lock]."</a></td>\n";
		
		// Positionierung
		$body_content.= "<td>";
		$body_content.= "<a href=\"".$msbBaselinkListe."&cmd=pos&wert=first&id=".$r_id."&pid=$r_pid\">";
		$body_content.= "<img src=\"{$webPathBaseUrl}/images/pfeil_nachganzoben.png\" width=\"10\" height=\"10\" title=\"An Anfang\"></a>";
		$body_content.= "<a href=\"".$msbBaselinkListe."&cmd=pos&wert=higher&id=".$r_id."&pid=$r_pid\">";
		$body_content.= "<img src=\"{$webPathBaseUrl}/images/pfeil_nachoben.png\" width=\"10\" height=\"10\" title=\"Eins höher\"></a>";
		$body_content.= "<a href=\"".$msbBaselinkListe."&cmd=pos&wert=lower&id=".$r_id."&pid=$r_pid\">";
		$body_content.= "<img src=\"{$webPathBaseUrl}/images/pfeil_nachunten.png\" width=\"10\" height=\"10\" title=\"Eins tiefer\"></a>";
		$body_content.= "<a href=\"".$msbBaselinkListe."&cmd=pos&wert=last&id=".$r_id."&pid=$r_pid\">";
		$body_content.= "<img src=\"{$webPathBaseUrl}/images/pfeil_nachganzunten.png\" width=\"10\" height=\"10\" title=\"Ans Ende\"></a>";
		$body_content.= "<input type=\"text\" size=\"$strLenNumCount\" name=\"poslist[$r_id:".$_MenuItems[$i]["ordnungszahl"]."]\" value=\"".$_MenuItems[$i]["ordnungszahl"]."\">";
		
		$body_content.= "<input type=\"submit\" value=\"&raquo;\">";
		$body_content.= "</td>\n";
		
		// Löschen
		$body_content.= "<td title=\"Editierbarer Status: Sichtbarkeit im Menü\">";
		$body_content.= "<a href=\"".$msbBaselinkListe."&cmd=loeschen&wert=1&id=".$r_id."&pid=$r_pid\">";
		$body_content.= "<img src=\"{$webPathBaseUrl}/images/kill_item.png\" width=\"10\" height=\"10\" alt=\"Menü löschen\"></a></td>\n";
		
		// Zeilenende
		$body_content.= "</tr>\n";
		$last_groupname = $_MenuItems[$i]["menu_groupname"];
	}
	
	$body_content.= "</table>\n";
	$body_content.= "</form>\n";
} else {
	$body_content.= "Es existieren keine Untermenüs!<br>\n";
}
$body_content.= "<br>\n";
/**/
?>