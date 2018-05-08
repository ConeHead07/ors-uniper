<?php 

function createVideoOjectTag_Factory(&$src = "", &$arrParams = array()) {
	$fname = basename($src);
	list($fext) = array_reverse(explode(".", $fname));
	
	if (isset($arrParams["type"])) $type = strtolower($arrParams["type"]);
	else {
		switch(strtolower($fext)) {
			case "wmv":
			case "avi":
			$type = "wmv";
			break;
			
			default:
			$type = "";
			break;
		}
	}
	
	switch($type) {
		case "wmv":
		default:
		return createVideoObject_Wmv($src, $arrParams);
	}
}

function createVideoObject_Wmv(&$src, &$arrParams) {
	$maxWidth = 500;
	$maxHeight = 500;
	$defaultWidth  = 500;
	$defaultHeight = 460;
	$defaultClass  = "CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6";
	$defaultPluginspage  = "http://www.microsoft.com/windows/windowsmedia/download/";
	$defaultAutostart    = "1";
	$defaultStretchToFit = "1";
	$defaultUiMode = "mini"; // User Interface
	
	$width = (isset($arrParams["width"]))     ? min($maxWidth,$arrParams["width"]) : $defaultWidth;
	$height = (isset($arrParams["height"]))   ? min($maxHeight,$arrParams["height"]) : $defaultHeight;
	$classid = (isset($arrParams["classid"])) ? $arrParams["classid"] : $defaultClass;
	$autostart = (isset($arrParams["autostart"])) ? $arrParams["autostart"] : $defaultAutostart;
	$pluginspage = (isset($arrParams["pluginspage"])) ? $arrParams["pluginspage"] : $defaultPluginspage;
	$stretchToFit = (isset($arrParams["stretchtofit"])) ? $arrParams["stretchtofit"] : $defaultStretchToFit;
	$uimode = (isset($arrParams["uimode"])) ? $arrParams["uimode"] : $defaultUiMode;
	
	$param_list = "";
	$embed_list = "";
	
	foreach($arrParams as $key => $val) {
		switch(strtolower($key)) {
			case "width":
			case "height":
			case "classid":
			case "autostart":
			case "pluginspage":
			case "stretchtofit":
			break;
			
			default:
			$param_list.= "<param name=\"".$key."\" value=\"".$val."\">\n";
			$embed_list.= " ".$key."=\"".$val."\"";
		}
	}
	
	$html = "<object type=application/x-oleobject standby=\"Lade Komponenten...\" \n";
	$html.= " classid=".$classid." \n";
	$html.= " width=\"".$width."\" height=\"".$height."\">\n";
	$html.= " <param name=\"URL\" value=\"".$src."\">\n";
	$html.= " <param name=\"Filename\" value=\"".$src."\">\n";
	$html.= " <param name=\"Autostart\" value=\"".$autostart."\">\n";
	$html.= " <param name=\"StretchToFit\" value=\"".$stretchToFit."\">\n";
	$html.= " <PARAM NAME=\"uiMode\" VALUE=\"$uimode\">\n";
	$html.= $param_list;
	$html.= " <embed type=\"application/x-mplayer2\" \n";
	$html.= " pluginspage=\"".$pluginspage."\" autostart=\"".$autostart."\" \n";
	$html.= " src=\"".$src."\" width=\"".$width."\" ";
	$html.= " height=\"".$height."\"";
	$html.= " uimode=\"".$uimode."\"";
	$html.= $embed_list;
	$html.= "></embed>\n";
	$html.= "</object>";
	return $html;
}

?>