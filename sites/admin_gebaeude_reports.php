<?php
//include dirname(__FILE__)."/../include/conf_lib.php";
$op = "";

$conf_gebaeude_file = dirname(__FILE__)."/../textfiles/conf.gebaeude.cnf";
$conf_gebaeude_list = "# Reports für Gebäude aktivieren: J für Ja, N für Nein\n\n";

$SentGP = getRequest("GP");

$CNF_GP = array();

$sql = "SELECT `gebaeude`, `adresse`
FROM `mm_stamm_gebaeude`
ORDER BY `gebaeude` ASC";

if ($SentGP) {
  $rows = $db->query_rows($sql);
  
  foreach($rows as $v) {
     $k = $v["gebaeude"];
     $v = (!empty($SentGP[$k])) ? "J" : "N";
     $CNF_GP[$k] = $v;
  }
	$conf_gebaeude_list.= conf_serialize($CNF_GP);
	conf_write_code($conf_gebaeude_file, $conf_gebaeude_list);
}

if (file_exists($conf_gebaeude_file)) {
	$CNF_GP = conf_load($conf_gebaeude_file);
}

$wz = "";

$op.= "<form action=\"".basename($_SERVER["PHP_SELF"])."?s=$s\" method=\"post\">
<input type=\"submit\" value=\"speichern\">
<table class=\"tblList\">
<thead>
<tr>
  <td>Geb&auml;ude aktivieren</td>
  <td>Adresse</td>
</tr>
</thead>
<tbody>\n";
$rows = $db->query_rows($sql);
foreach($rows as $v) {
	$wz = ($wz != "wz1") ? "wz1" : "wz2";
	$checked = (!empty($CNF_GP[$v["gebaeude"]]) && $CNF_GP[$v["gebaeude"]]=='J') ? "checked=\"true\"" : "";
	$op.= "<tr class=\"$wz\">
	<td><input type=\"checkbox\" name=\"GP[".$v["gebaeude"]."]\" value=\"J\" $checked> ".$v["gebaeude"]."</td>
	<td style=\"padding-left:80px;text-align:right;\">".$v["adresse"]."</td>
	</tr>\n";
}

$op.= "</tbody>
</table>
<input type=\"submit\" value=\"speichern\">
</form>";
//$op.= "<pre>CNF_GP:\n".print_r($CNF_GP, 1)."</pre>\n";

$body_content.= "
<link rel=\"STYLESHEET\" type=\"text/css\" href=\"css/umzugsformular.css\"/>
<div class=\"divModuleBasic padding6px width5Col heightAuto colorContentMain\"> 
<h1><span class=\"spanTitle\">Geb&auml;udeauswahl f&uuml;r NIA-Report</span></h1> 
<p>
<div id=\"Umzugsantrag\" class=\"divInlay\">\n";
$body_content.= $op;
$body_content.= "</div>\n";
$body_content.= "</div>\n";
?>