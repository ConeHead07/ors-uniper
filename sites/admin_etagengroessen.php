<?php
//include dirname(__FILE__)."/../include/conf_lib.php";
$op = "";

$conf_etagen_flaechen_file = dirname(__FILE__)."/../textfiles/conf.etagen_flaechen.cnf";
$conf_etagen_flaechen_list = "# Kommawerte in Punktnoation angeben Bsp: 105.5 statt 105,5\n\n";

$SentGEGroessen = getRequest("GEGroessen");

$CNF_EF = array();

if ($SentGEGroessen) {
	$conf_etagen_flaechen_list.= conf_serialize($SentGEGroessen);
	conf_write_code($conf_etagen_flaechen_file, $conf_etagen_flaechen_list);
}

if (file_exists($conf_etagen_flaechen_file)) {
	$CNF_EF = conf_load($conf_etagen_flaechen_file);
}

$sql = "SELECT `gebaeude`, `etage`
FROM `mm_stamm_immobilien`
GROUP BY `gebaeude`, `etage`
ORDER BY `gebaeude` ASC , `etage` ASC";

$lastGebaeude = "";

$op.= "<form name=\"frmSuche\" action=\"".basename($_SERVER["PHP_SELF"])."?s=$s\" method=\"post\">
<input type=\"submit\" value=\"speichern\">
<table>\n";
$rows = $db->query_rows($sql);
foreach($rows as $v) {
	if ($lastGebaeude != $v["gebaeude"]) {
		$op.= "<tr><td colspan=2 style=\"padding-top:15px;\"><strong>".$v["gebaeude"]."</strong></td></tr>\n";
		$lastGebaeude = $v["gebaeude"];
	}
	$cnfVal = (!empty($CNF_EF[$v["gebaeude"]]) && !empty($CNF_EF[$v["gebaeude"]][$v["etage"]])) ? $CNF_EF[$v["gebaeude"]][$v["etage"]] : "";
	$op.= "<tr><td>".$v["etage"]."</td>
	<td><input class=\"itxt\" type=\"text\" name=\"GEGroessen[".$v["gebaeude"]."][".$v["etage"]."]\" value=\"".fb_htmlEntities($cnfVal)."\"></td>
	</tr>\n";
}

$op.= "</table>
<input type=\"submit\" value=\"speichern\">
</form>";
//$op.= "<pre>CNF_EF:\n".print_r($CNF_EF, 1)."</pre>\n";

$body_content.= "
<link rel=\"STYLESHEET\" type=\"text/css\" href=\"css/umzugsformular.css\"/>
<div class=\"divModuleBasic padding6px width5Col heightAuto colorContentMain\"> 
<h1><span class=\"spanTitle\">Flächenzuordnung zu Etagen f&uuml;r NIA-Report</span></h1> 
<p>
<div id=\"Umzugsantrag\" class=\"divInlay\">\n";
$body_content.= $op;
$body_content.= "</div>\n";
$body_content.= "</div>\n";
?>