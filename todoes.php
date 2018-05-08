<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Untitled</title>
	<script>
	function fb_ajax_submit(frm) {
		if ((typeof frm) != "object") {
			if (document.forms[frm]) frm = document.forms[frm];
			else if (document.getElementById(frm) && document.getElementById(frm).tagName.toLowerCase()=="form") frm = document.forms[frm];
		}
		if (
		(typeof frm) != "object" 
		|| !frm.tagName 
		|| frm.tagName.toLowerCase() != "form") return false;
		
		var frmVars = "";
		for (var i = 0; i < frm.elements.length; i++) {
			switch(frm.elements[i].type.toLowerCase()) {
				case "text":
				case "hidden":
				case "textarea":
				frmVars+= "&"+frm.elements[i].name+"="+escape(frm.elements[i].value);
				break;
				
				case "radio":
				case "checkbox":
				if (frm.elements[i].checked) {
					frmVars+= "&"+frm.elements[i].name+"="+escape(frm.elements[i].value);
				}
				break;
				
				case "select":
				case "select-one":
				alert(frm.elements[i].selectedIndex);
				if (frm.elements[i].selectedIndex != -1) {
					frmVars+= "&"+frm.elements[i].name+"="+escape(frm.elements[i].options[frm.elements[i].selectedIndex].value);
				}
				break;
				
				case "select-multiple":
				for(j = 0; j < frm.elements[i].options.length; j++) {
					if (frm.elements[i].options[j].selected) {
						frmVars+= "&"+frm.elements[i].name+"="+escape(frm.elements[i].options[j].value);
					}
				}
				break;
				
				default:
				alert(frm.elements[i].type);
			}
		}
		alert(frmVars);
		return false;
	}
	</script>
</head>

<body>
<form action="<?php echo basename($_SERVER["PHP_SELF"]); ?>" onsubmit="return fb_ajax_submit(this)" method=post>
	<select name="kategorie">
		<option value="Fehler">Fehler</option>
		<option value="Aufgabe">Aufgabe</option>
		<option value="Hinweis">Hinweis</option>
	</select>
	<input type="text" name="bis" value=""><br>
	Text / Beschreibung
	<textarea name="text"></textarea>
	<input type="submit" value="speichern" name="send">
</form>
<?php
require("include/conf.php");
require("include/conn.php");

$TABLE["todoes"] = "mm_todoes";
$liste = "";

$sql = "SELECT * FROM `".$TABLE["todoes"]."`";
$r = MyDB::query($sql, $conn);
if ($r) {
	$n = MyDB::num_rows($r);
	$nf = MyDB::num_fields($r);
	$liste.= "<tr>\n";
	for($i = 0; $i < $nf; $i++) {
		$f = MyDB::field_name($r, $i);
		$liste.= "<td>".$f."</td>\n";
	}
	$liste.= "</tr>\n";
	for($i = 0; $i < $n; $i++) {
		$e = MyDB::fetch_assoc($r);
		$liste.= "<tr>\n";
		foreach($e as $fld => $val) {
			$liste.= "<td>".$val."</td>\n";
		}
		$liste.= "<td style=\"color:#f00;\">x</td>\n";
		$liste.= "</tr>\n";
	}
	free_result($r);
}
?>


</body>
</html>
