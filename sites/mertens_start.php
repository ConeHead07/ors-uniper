<?php 

$mainmenu = "Class-Active-Mertens";
$body_content = implode("", file("sites/mertens_admin_start.tpl.html"));

$_rplAusgabe[0][$mainmenu] = "liActive";
$_rplAusgabe[0]["<!-- {topmenu} -->"] = $topmenu;
