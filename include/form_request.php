<?php 
function getRequest($key, $default = "", $src = "PGSR") {
	//echo "#".__LINE__." ".basename(__FILE__)." $key; $default; $src, ".$_GET["ort"]." <br>\n";
	for ($i = 0; $i < strlen($src); $i++) {
		switch(strtoupper($src[$i])) {
			case "P": if (isset($_POST[$key])) 		return $_POST[$key]; 	break;
			case "G": if (isset($_GET[$key]))		return $_GET[$key]; 	break;
			case "C": if (isset($_COOKIE[$key])) 	return $_COOKIE[$key]; 	break;
			case "S": if (isset($_SESSION[$key])) 	return $_SESSION[$key];	break;
			case "R": if (isset($_REQUEST[$key])) 	return $_REQUEST[$key];	break;
			case "E": if (isset($_SERVER[$key])) 	return $_SERVER[$key];	break;
			default: die("#".__LINE__." ?? ".$src[$i]."<br>\n");
		}
	}
	return $default;
}

function get_SelectBox($name, $value, $options, $IsAssoc=false, $attr="") {
	$input = "<select name=\"$name\" $attr>";
	$input.= "<option value=\"\" ".($value?"":"selected='true'").">...</option>\n";
	foreach($options as $k => $v) {
		$selected = ($IsAssoc ? $k == $value : $v == $value) ? "selected=\"true\"" : "";
		$input.= "<option value=\"".fb_htmlEntities($IsAssoc?$k:$v)."\" $selected>$v</option>\n";
	}
	$input.= "</select>\n";
	return $input;
}
function get_InputRadio($name, $value, $options, $IsAssoc=true, $attr="", $settings = array()) {
	$input = "";
	$lbl = "";
	if (!isset($settings["HideLabel"])) $settings["HideLabel"] = false;
	foreach($options as $k => $v) {
		$checked = ($IsAssoc ? $k == $value : $v == $value) ? "checked=\"true\"" : "";
		if (!$settings["HideLabel"]) $lbl = "$v ";
		$input.= "<input type=\"radio\" name=\"$name\" value=\"".fb_htmlEntities($IsAssoc?$k:$v)."\" $checked $attr>$lbl";
	}
	return $input;
}
function get_InputCheckBox($name, $value, $options, $IsAssoc=true, $attr="", $settings = array()) {
	$input = "";
	$lbl = "";
	if (!isset($settings["ShowLabel"])) $settings["ShowLabel"] = true;
	foreach($options as $k => $v) {
		$checked = ($IsAssoc ? $k == $value : $v == $value) ? "checked=\"true\"" : "";
		if ($settings["ShowLabel"]) $lbl = "$v ";
		$input.= "<input type=\"checkbox\" name=\"$name\" value=\"".fb_htmlEntities($IsAssoc?$k:$v)."\" $checked $attr>$lbl";
	}
	return $input;
}
function get_InputText($name, $value, $attr="") {
	return "<input type=\"text\" name=\"$name\" default=\"".fb_htmlEntities($value)."\" value=\"".fb_htmlEntities($value)."\" $attr>";
}
function get_InputHidden($name, $value, $attr="") {
	return "<input type=\"hidden\" name=\"$name\" default=\"".fb_htmlEntities($value)."\" value=\"".fb_htmlEntities($value)."\" $attr>";
}
function get_TextArea($name, $value, $attr="") {
	return "<textarea name=\"$name\" style=\"width:100%;\" $attr>".fb_htmlEntities($value)."</textarea>";
}
function get_InputRead($name, $value, $attr="") {
	return "<input readonly=\"1\" type=\"text\" name=\"$name\" default=\"".fb_htmlEntities($value)."\" value=\"".fb_htmlEntities($value)."\" $attr>";
}
?>