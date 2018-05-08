<?php 
require("../header.php");

$name = getRequest("name");
$vorname = getRequest("vorname");
$gebaeude = getRequest("gebaeude");
$etage = getRequest("etage");
$raumnr = getRequest("raum");

$sql = "SELECT m.id, m.name, m.vorname, i.id raumid, i.gebaeude, i.etage, i.raumnr * \n";
$sql.= "FROM `".$_TABLE["mitarbeiter"]."` m LEFT JOIN `".$_TABLE["immobilien"]."` i ON (m.immobilien_raum_id=i.id)\n";
$sql.= "WHERE \n";
$sql.= "(m.name SOUNDS LIKE \"".$db->escape($name)."\" OR m.vorname SOUNDS LIKE \"".$vorname."\") \n";
if ($gebaeude) $sql.= " AND i.gebaeude = \"".$db->escape($gebaeude)."\" \n";
if ($etage) $sql.= " AND i.etage = \"".$db->escape($etage)."\" \n";
if ($raumnr) $sql.= " AND i.raumnr = \"".$db->escape($raumnr)."\" \n";
?>
<div>
<div><input name="name" value="{name}" type="text"><input name="vorname" value="{vorname}" type="text"></div>
<div>Gebäude | Etage | Raum</div>
<div>Liste</div>
</div>
