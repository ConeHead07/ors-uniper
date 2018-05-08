<?php


$delimitedTags="div,u,b,i,ul,ol,li";
$hauptvorlage_file="../html/vorlage_edit.html";
$editareavorlage_file="../html/vorlage_editarea.html";
$jsbuttonsvorlage_file="../html/vorlage_editjsbtns.txt";
//$scriptspecifics_header_file="../html/scriptspecifics_editbar.html";
if (!@empty($userrole) && $userrole == 500) {
	$jsbuttonsvorlage_file="../html/vorlage_editjsbtns500.txt";
	$editareavorlage_file="../html/vorlage_editarea500.html";
}

function parse_vorlage($vorlage) {
	$cms_partsArr=array();
	$tplNeedle1="<!-- cms_";
	$tplFinal=" -->";
	$nLen1=strlen($tplNeedle1);
	$tplNeedle2="<!-- /cms_";
	$nLen2=strlen($tplNeedle2);
	$tmp=$vorlage;

	while(strchr($tmp,$tplNeedle1)) {
		$tmp=substr($tmp,strpos($tmp,$tplNeedle1));
		$cms_partsName=substr($tmp,$nLen1);
		$cms_partsName=substr($cms_partsName,0,strpos($cms_partsName," "));

		$props=substr($tmp,($nLen1+strlen($cms_partsName)));
		$props=substr($props,0,strpos($props,$tplFinal));

		$tmpN1=$tplNeedle1.$cms_partsName.$props.$tplFinal;
		$tmpN2=$tplNeedle2.$cms_partsName.$tplFinal;
		list($cms_partsTxt,$tmp) = explode($tmpN2,$tmp);
		list($kill,$cms_partsTxt)= explode($tmpN1,$cms_partsTxt);

		$cms_partsArr[$cms_partsName]=$cms_partsTxt;
		parse_str($props,$cms_partPropsArr[$cms_partsName]);
	}
	return array($cms_partsArr,$cms_partPropsArr);
}

function pfad_info($link) {
	if (is_int(strpos($link, "?"))) {
		$_pfadInfo = pathinfo(substr($link, 0, strpos($link,"?")));
		$_pfadInfo["query"] = substr($link, strpos($link,"?")+1);
	} else {
		$_pfadInfo = pathinfo($link);
		$_pfadInfo["query"] = "";
	}
	$_pfadInfo["filepath"] = $_pfadInfo["dirname"]."/".$_pfadInfo["basename"];
	$_pfadInfo["pathIsAbsolute"] = (strchr(strtolower($_pfadInfo["dirname"]), "://")) ? true : false;
	return $_pfadInfo;
}

if (!function_exists("zzz_parseTag")) {
function zzz_parseTag($tname, $tag, &$_CFT) {
	$a=parse_htmlTag($tag);
	$astyle = (isset($a["style"]))?parse_tagStyle($a["style"]):array();
	$_NewTagAttr = array();
	$fitStyle = ";";
	
	// Filtern gültiger Attribut-Eigenschafts-NAMEN
	while(list($ak, $av) = each($a)) {
		if (in_array($ak, $_CFT["AT"])) {
			$_NewTagAttr[$ak] = $av;
		} 
	}
	
	// Ausfiltern ungültiger Attribut-WERTE
	if (isset($_CFT["ATCK"])) {
		reset($_NewTagAttr);
		while (list($r_attr, $r_wert) = each($_NewTagAttr)) {
			if (isset($_CFT["ATCK"][$r_attr])) {
				if (!in_array($r_wert, $_CFT["ATCK"][$r_attr])) {
					unset($_NewTagAttr[$r_attr]);
				}
			}
		}
	}
	
	// Setze Standard-Attribut-WERTE 
	if (isset($_CFT["ATDEF"])) {
		reset($_CFT["ATDEF"]);
		while(list($r_attr, $r_default) = each($_CFT["ATDEF"])) {
			if (!isset($_NewTagAttr[$r_attr])) $_NewTagAttr[$r_attr] = $r_default;
		}
	}
	
	// Filtern gültiger Style-Eigenschafts-NAMEN 
	if (isset($astyle) && is_array($astyle) && count($astyle)) {
		reset($astyle);
		while(list($k_astyle, $v_astyle) = each($astyle)) {
			if (in_array($k_astyle, $_CFT["ST"])) {
				if (!isset($_CFT["STCK"][$k_astyle])
					|| in_array($v_astyle, $_CFT["STCK"][$k_astyle])) {
					$fitStyle.= "$k_astyle:$v_astyle;";
				}
			}
		}
	}
	
	// Setze Standard-Style-WERTE 
	if (isset($_CFT["STDEF"])) {
		reset($_CFT["STDEF"]);
		while(list($r_k, $r_default) = each($_CFT["STDEF"])) {
			if (!is_int(strpos($fitStyle, ";$r_k:"))) {
				$fitStyle.= $r_k.":".$r_default.";";
			}
		}
	}
	
	if ($fitStyle != ";") {
		$_NewTagAttr["style"] = $fitStyle;
	} else {
		if(isset($_NewTagAttr["style"])) {
			unset($_NewTagAttr["style"]);
		}
	}
	
	$tag = buildTag($tname, $_NewTagAttr);
	return $tag;
}}


function fit_htmlTags($htmlTags, $_FitTags = array()) {
	//echo "#".__LINE__." ".basename(__FILE__)." htmlTags:".fb_htmlEntities(print_r($htmlTags,1))."<br>\n";
	
	$x = 0;
	$parsed_str = "";
	$v = &$htmlTags;
	// echo "#".__LINE__." ".fb_htmlEntities($htmlTags)." <br>\n";
	while ( strlen($v) ) {
		$char=substr($v, 0, 1);
		if ($char == "<" && substr($v, 1, 1) != "/") {
			$tagLen = strpos($v, ">") + 1;
			$tag    = substr($v, 0, $tagLen);
			$tag    = str_replace(">", " >", $tag);
			$tagname= trim(substr($tag, 1, strpos($tag, " ", 1) ));
			$tname  = trim(strtolower($tagname));
			
			switch(trim(strtolower($tagname))) {
				case "a":
				case "div":
				// break;
				
				default:
				$arrAttr = parse_htmlTag($tag);
				
				for ($i = 0; $i < count($_FitTags); $i++) {
					if ($_FitTags[$i]["tag"] == $tname || $_FitTags[$i]["tag"] == "") {
						$found = false;
						reset($_FitTags[$i]["find"]);
						while (list($ta, $tv) = each($_FitTags[$i]["find"])) { 
							// ta:tag-attribut-key, tv:tag-attribut-val
							if (isset($arrAttr[$ta]) && $arrAttr[$ta] == $tv) {
								$found = true;
							} else {
								$found = false;
								break;
							}/**/
						}
						if ($found) {
							$arrAttr = array_merge($arrAttr, $_FitTags[$i]["attr"]);
						}
						reset($_FitTags[$i]["find"]);
					}
				}
				reset($arrAttr);
				
				$tag = buildTag($tagname, $arrAttr);
				//echo "#".__LINE__." ".basename(__FILE__)." tag:".fb_htmlEntities(print_r($tag,1))."<br>\n";
				
				// $tag = "<$tagname>";
				break;
			}
			
			$parsed_str.= $tag;
			$v=substr($v, $tagLen);
			
		} else {
			$parsed_str.=$char;
			$v=substr($v,1);
		}
	}
	
	// echo "<!-- #".__LINE__." ".basename(__FILE__)." ".fb_htmlEntities($parsed_str)." -->\n";
    if ($x) echo "#2 parsed_str: ".fb_htmlEntities($parsed_str)."<br>\n";
	//exit;
	return $parsed_str;
}


function cms_strip_tags($v, $allowableTags = "/default", $_CONF_CTAGS = "") {
	global $default_allowableTags;
	global $interneLinks;
	global $webPath;
	global $_SERVER;
	global $HTTP_SERVER_VARS;
	global $username;
	global $_interneAbsolutePfade;
	global $_interneRelativePfade;
	global $_interneTestPfade;
	global $_internerPfadAbs2Rel;
	if (!$_CONF_CTAGS) {
		global $_CTAGS;
		$CONF_CTAGS = &$_CTAGS;
	}
	$fbd = (strtolower($username) == "Xadministrator") ? 1 : 0;
	$x=0;
	$v=str_replace("<wbr/>","",$v);
    if ($x) echo "#1 cms_strip_tags(v): ".fb_htmlEntities($v)."\n<br>*************Ende*****<br>\n";
	if ($allowableTags=="/default") $allowableTags=$default_allowableTags;
	$allowableTags=strtolower($allowableTags);
	if (strchr($allowableTags,"<br>") && !strchr($allowableTags,"<p>")) {
		$v=str_replace("</p>","</p><br><br>",$v);
		$v=str_replace("</P>","</P><br><br>",$v);
	}
	while(strtolower(substr($v, -4)) == "<br>") $v = substr($v, 0, -4);
	
	$stripped_str="";
	if ($x) echo "#2 cms_strip_tags(v): ".fb_htmlEntities($v)."\n<br>*************Ende*****<br>\n";
	while (strlen($v)) {
		$char=substr($v,0,1);
		if ($char=="<") {
			$tagLen=strpos($v,">")+1;
			$tag=substr($v,0,$tagLen);
			$tag=str_replace(">"," >",$tag);
			$tagname=trim(substr($tag, 1, strpos($tag, " ",1)));
			$tname = trim(strtolower($tagname));
			switch(trim(strtolower($tagname))) {
				case "a":
				$a = parse_htmlTag($tag);
				$tag="";
				$_pfadInfo = (isset($a["href"])) ? pfad_info($a["href"]) : "";
				$linkIsIntern = false;
				$linkIsAbsolute = false;
				
				if (isset($a["href"])) {
					if (in_array(strtolower($_pfadInfo["filepath"]), $_interneTestPfade)) {
						$linkIsIntern = true;
						$linkIsAbsolute = true;
						$a["href"] = basename($a["href"]);
					} elseif (in_array(strtolower($_pfadInfo["filepath"]), $_interneAbsolutePfade)) {
						$linkIsIntern = true;
						$linkIsAbsolute = true;
					} elseif (in_array(strtolower($_pfadInfo["filepath"]), $_interneRelativePfade)) {
						$linkIsIntern = true;
						$a["href"] = basename($a["href"]);
					}
				}
				
				if (!isset($a["target"])) {
					if ($fbd) echo "#1159 linkIsIntern: $linkIsIntern|";
					$a["target"] = ($linkIsIntern) ? "_self" : "_blank" ;
				}
				$tag = buildTag($tname, $a);
				//echo "#".__LINE__." tag:".fb_htmlEntities($tag)."<br>\n";
				$tag = zzz_parseTag($tname, $tag, $CONF_CTAGS[$tname]);
				//echo "#".__LINE__." tag:".fb_htmlEntities($tag)."<br>\n";
				break;
				
				case "img":
				$a=parse_htmlTag($tag);
				$astyle=(isset($a["style"]))?parse_tagStyle($a["style"]):array();
				
				while(list($r_abs, $r_rel) = each($_internerPfadAbs2Rel)) {
					if (isset($a["src"]) && substr(strtolower($a["src"]), 0, strlen($r_abs)) == $r_abs) {
						$a["src"] = $r_rel.substr($a["src"], strlen($r_abs));
					}
				}
				
				//$tag = buildTag($tname,$_NewTagAttr);
				$tag = zzz_parseTag($tname, $tag, $CONF_CTAGS[$tname]);
				break;
				
				case "table":
				case "td":
				//echo "#".__LINE__." tname:$tname, tag:".fb_htmlEntities($tag)."<br>\n";
				$a = parse_htmlTag($tag);
				$astyle=(isset($a["style"]))?parse_tagStyle($a["style"]):array();
				if (isset($astyle["text-align"])) $a["align"] = $astyle["text-align"];
				if (isset($astyle["vertical-align"])) $a["valign"] = $astyle["vertical-align"];
				
				$tag = buildTag($tagname, $a);
				$tag = zzz_parseTag($tname, $tag,$$CONF_CTAGS[$tname]);
				break;
				
				case "h3":
				if (isset($CONF_CTAGS[$tname])) {
					$tag = zzz_parseTag($tname, $tag,$$CONF_CTAGS[$tname]);
				} elseif (strchr(strtolower($allowableTags),"<$tname>")) {
					$tag="<$tagname>";
				} else {
					$tag="";
				}
				break;
				
				case "div":
				default:
				if (isset($CONF_CTAGS[$tname])) {
					$tag = zzz_parseTag($tname, $tag, $CONF_CTAGS[$tname]);
				} elseif (strchr(strtolower($allowableTags),"<$tname>")) {
					$tag="<$tagname>";
				} else {
					$tag="";
				}
				break;
			}
			$stripped_str.=$tag;
			$v=substr($v, $tagLen);
			
			// Und was passiert hier
			// Hier werden ausgeschriebene Internetadressen so präpariert,
			// dass der Browser bei langen Adressen automatisch bei Slash selbst
			// den nötigen Zeilenumbruch vornehmen kann, damit die Seite nicht auseinanderplatzt
			/**/
			if ($tname == "a") {
				$posOfClosingTag = strpos($v,"</$tagname>");
				$anchorText = substr($v, 0, $posOfClosingTag);
				$anchorText = str_replace('<WBR>', '', $anchorText);
				$anchorText = str_replace('<wbr>', '', $anchorText);
				if ( !is_int(strpos($anchorText,"<"))) {
					$anchorText = str_replace('/', '/<WBR>', $anchorText);
					$anchorText = str_replace('&', '<WBR>&', $anchorText);
					$v = substr($v, $posOfClosingTag);
					$stripped_str.= $anchorText;
				}
			}
			
			//echo "<pre style=\"margin:0px;\">#".__LINE__." ".fb_htmlEntities($stripped_str)."</pre>\n";
		} else {
			$stripped_str.=$char;
			$v=substr($v,1);
		}
	}
	
	$v=strip_tags($stripped_str,$allowableTags);
	$stripped_str=fit4js($stripped_str);
	
    if ($x) echo "#2 v:stripped_str: ".fb_htmlEntities($stripped_str)."<br>\n";
	return $stripped_str;
}

function buildTag($tagName, $_Attribute) {
	$strAttribute = "";
	$closeTag = "";
	while (list($k,$v) = each ($_Attribute)) {
		// $v = trim( strtr($v, array('"'=>'', "'"=>"") ) );
		if ($v  != "") {
			switch(strtolower($k)) {
				case "style":
				case "src":
				case "href":
				case "onclick":
				$strAttribute.= " ".$k.'="'.$v.'"';
				break;
				
				case "/":
				// 
				$closeTag = $k;
				break;
				
				default:
				if (is_string($_Attribute[$k]) || is_int($_Attribute[$k])) {
					$strAttribute.= " ".$k."=\"".$v."\"";
				} elseif ($_Attribute[$k] === true) {
					$strAttribute.= " ".$k;
				}
				break;
			}
			
		}
	}
	
	$tag = "<".$tagName.$strAttribute.$closeTag.">";
	return $tag;
}

function set_tag_attribut($tag, $tagName, $name, $value) {
	$imgAttributeArr = parse_htmlTag($tag);
	$imgAttributeArr[$name] = $value;
	reset($imgAttributeArr);
	$newAttribute="";
	while (list($k,$v) = each ($imgAttributeArr)) {
		$v = trim( strtr($v, array('"'=>'', "'"=>"") ) );
		if ($v  != "") {
			/*$newAttribute.=" ".$k."='".$v."'";
			*/
			switch(strtolower($k)) {
				case "style":
				case "src":
				$newAttribute.=" ".$k.'="'.$v.'"';
				break;
				
				default:
				$newAttribute.=" ".$k."=".$v;
				break;
			}
			
		}
	}
	$tag="<".$tagName.$newAttribute.">";
	return $tag;
}

function parse_tagStyle($attributeStr) {
	$re=array();
	$attributeStr=trim(strtolower($attributeStr));
	if ($attributeStr) {
		if (strchr($attributeStr,";")) {
			$attributeArr=explode(";",$attributeStr);
		} else {
			$attributeArr[0]=$attributeStr;
		}
	
		for ($i=0; $i<count($attributeArr); $i++) {
			if (strchr($attributeArr[$i],":")) {
				list($key,$val) = explode(":",$attributeArr[$i]);
				$re[trim($key)]=trim($val);
			}
		}
	}
	return $re;
}

function parse_htmlTag($tag) {
	$OriginTag = $tag;
	// $tag = stripslashes(trim($tag));
	$lastAttributeName  = "";
	$lastAttributeValue = "";
	$phrasenOpener = "";
	$y = "";
	$attribArr = array();
	$tagClosed = 0;
	
	if (substr($tag, 0, 1) == "<") {
		$tag = substr($tag, 1);
		$p = strpos($tag, " ");
		if ($p) {
			$tagname = substr($tag, 0, $p);
			$tag = substr($tag, $p);
		}
	}
	
	// echo "#".__LINE__." tag: ".fb_htmlEntities($tag)."<br>\n";
	for ($i=0; $i<strlen($tag); $i++) {
		$z = substr($tag, $i, 1);
		if ($z == ">" && $phrasenOpener) $tagClosed = 1;
		// echo "#".__LINE__." po: $phrasenOpener, z:$z<br>\n";
		switch($phrasenOpener) {
			
			case "=":
			
			if (trim($lastAttributeValue) && ($z == " " || $z==">")) {
				$attribArr[strtolower($lastAttributeName)] = $lastAttributeValue;
				$lastAttributeName="";
				$lastAttributeValue="";
				$phrasenOpener="";
			} else {
				$lastAttributeValue.=$z;
			}
			break;
			
			case "'":
			case '"':
			if ($phrasenOpener == $z && ($z == "'" || $z == '"') && $y != "\\") {
				$attribArr[$lastAttributeName] = $lastAttributeValue;
				$lastAttributeName = "";
				$lastAttributeValue =" ";
				$phrasenOpener = "";
			} else {
				$lastAttributeValue.= $z;
			}
			break;
			
			// Hier wird der PhrasenOpener erkannt, der folgenden Wert haben kann
			// => '
			// => "
			// => =		Attributwert ist nicht in Anführungszeichen
			// Und hier werden alleinstehende Attribute ohne Wertzuweisung erkannt
			case "":
			if ($z == "=") {
				$temp = substr($tag, $i+1);
				$nextNonBlindChar = substr(trim($temp), 0, 1);
				if ($nextNonBlindChar == "'" || $nextNonBlindChar=="\"") {
					$z = $nextNonBlindChar;
					$i+= strpos($temp, $nextNonBlindChar) + 1;
				}
				$phrasenOpener = $z;
			}
			
			switch($z) {
				case "=": // Wertepaar key="value"
				$temp = substr($tag,$i+1);
				$nextNonBlindChar = substr(trim($temp), 0, 1);
				if ($nextNonBlindChar == "'" || $nextNonBlindChar=="\"") {
					$z = $nextNonBlindChar;
					$i+=strpos($temp, $nextNonBlindChar) + 1;
				}
				$phrasenOpener=$z;
				break;
				
				case " ": // Prüfe, ob Attribut einzeln im Tag steht => ohne Wertzuweisung
				$temp = substr($tag,$i+1);
				$nextNonBlindChar = substr(trim($temp), 0, 1);
				
				if ($nextNonBlindChar != "=") { // Attribut ist standalone
					if (trim($lastAttributeName)) {
						$attribArr[$lastAttributeName] = true;
					}
					$lastAttributeName  = "";
					$lastAttributeValue = "";
				}
				
				$z = $nextNonBlindChar;
				$i+= strpos($temp,$nextNonBlindChar)+1;
				// $phrasenOpener=$z;
				break;
			}
			
			if (!$phrasenOpener) {
				$lastAttributeName = ($z != " ") ? $lastAttributeName.$z : "";
			}
			break;
		}
		if ($tagClosed) break;
		$y=$z;
	}
	
	reset($attribArr);
	while(list($tagk,$tagv) = each ($attribArr)) { 
		$attribArr[strtolower($tagk)]=$tagv; 
		// echo "#".__LINE__." $tagk = ".fb_htmlEntities($tagv)."<br>\n";
	}
	reset($attribArr);
	
	/*echo "#".__LINE__." ".basename(__FILE__)." OriginTag: ".fb_htmlEntities($OriginTag)."<br>\n";
	echo "#".__LINE__." ".basename(__FILE__)." attribArr: ".print_r($attribArr,1)."<br>\n";
	exit;*/
	return $attribArr;
}

function insert_tpl_value($k,$v,$confirm_form) {
	$cmsFinalMarke="-->";
	$cmsOpenMarke="<!-- ".$k." ";
	$cmsCloseMarke="<!-- /".$k." ".$cmsFinalMarke;

    $offset1=strpos($confirm_form,$cmsOpenMarke);
	$offset2=strpos($confirm_form,$cmsFinalMarke,$offset1);
	$offset3=strpos($confirm_form,$cmsCloseMarke);

	$strBefore=substr($confirm_form,0,$offset2+strlen($cmsFinalMarke));
	$strBehind=substr($confirm_form,$offset3);

	return $strBefore.$v.$strBehind;
}

function new_file_name($directory,$stamm_name) {
	$dp=opendir($directory);
	$needle=$stamm_name;
	$max=0;

	while($file = readdir($dp)) {
		if (strchr($file,$needle)) {
			list($tmp,$counter) = explode($needle,$file);
			$counter=intval($counter);
			$max=max($counter,$max);
		}
	}

	$max+=1;
	$new_file_name=$stamm_name.$max.".html";
	return $new_file_name;
}

function cms_extract_part($vorlage,$cms_part_name) {
	$tagOpener="<!-- ".$cms_part_name;
	$tagClose="<!-- /".$cms_part_name;
	$tplFinal="-->";
	$re="";
	if (strchr($vorlage,$tagOpener)) {
		list($tmp,$re) = explode($tagOpener,$vorlage);
		$pos=strpos($re,$tplFinal)+strlen($tplFinal);
		$re=substr($re,$pos);
	}
	if (strchr($re,$tagClose)) {
		list($re,$tmp) = explode($tagClose,$re);
	}
	return $re;
}

// Funktion erledigt diverse Vorarbeiten für die Ausgabe
// einer komfortablen Editier-Umgebung
function get_cms_items($str,$tagStart,$tagEnd,$setUniqueNeedle) {
	$xbug=1;
	$teLen=strlen($tagEnd);
	$cms_items=array();
	$ausgabe=$str;
	while(strchr($ausgabe,$tagStart) && (strpos($ausgabe,$tagStart)<strrpos($ausgabe,$tagEnd))) {
		$tVar=count($cms_items);

		// Blockbeginn: Extrahieren der gesamten Kommentarzeile 'als cmstag'
		$a=strpos($ausgabe,$tagStart);
		$cmstag=substr($ausgabe,$a);
		$cmstag=substr($cmstag,0,strpos($cmstag,$tagEnd)+$teLen);
		// Blockende: Extrahieren der gesamten Kommentarzeile 'als cmstag'

		// Blockbeginn: Extrahieren der getWertepaare aus 'cmstag'
		if (isset($vararr)) unset($vararr);
		$vararr=array();
		$varstr=trim(substr($cmstag,1+strpos($cmstag,"?"),-$teLen));
		parse_str($varstr,$vararr);
		// Blockbeginn: Extrahieren der getWertepaare aus 'cmstag'

		// Blockbeginn: CMS-Tag durch eindeutigen String ersetzen (erledigt)
		$needle="<!-- ".md5($tVar).md5((time())*rand())." -->";
		$ausgabe=substr($ausgabe,0,$a).$needle.substr($ausgabe,$a+strlen($cmstag));
		// Blockbeginn: CMS-Tag durch eindeutigen String ersetzen (erledigt)

		// Blockbeginn Buffern der Rückgabewerte
		$cms_items[$tVar]["cmstag"]=$cmstag;
		$cms_items[$tVar]["varstr"]=$varstr;
		$cms_items[$tVar]["vararr"]=$vararr;
		$cms_items[$tVar]["needle"]=($setUniqueNeedle)?$needle:"";
		// Blockende Buffern der Rückgabewerte

	}
	if ($setUniqueNeedle) {
		return array($cms_items,$ausgabe);
	} else  {
		return array($cms_items);
	}
}

function tag_replace($str,$delimitedTags) {
	$tags_arr=explode(",",unique_items($delimitedTags,","));
	for ($i=0; $i<count($tags_arr); $i++) {
		$tag=$tags_arr[$i];
		$str=eregi_replace("\[".$tag."\]","<".strtoupper($tag).">",$str);
		$str=eregi_replace("\[/".$tag."\]","</".strtoupper($tag).">",$str);
	}
	return $str;
}

function fit_htmlInput($str,$delimitedTags) {
	$str=tag_replace($str,$delimitedTags);
	$allowableTags="<".implode("><",explode(",",$delimitedTags)).">";
	$str=strip_tags($str,$allowableTags);
	return $str;
}

function set_cms_editbar($body_content,$ausgabe) {
	global $delimitedTags;
	global $editareavorlage_file;
	global $jsbuttonsvorlage_file;
	global $scriptspecifics_header_file;
	
	global $username;
	//echo "username: $username";
	if ($username == "administrator") {
		//echo "<pre>Vorher\n".fb_htmlEntities($body_content)."</pre>";
		$bodyPos = strpos($ausgabe,"<body");
		$testAusgabe = substr($ausgabe,0,$bodyPos);
		$testAusgabe = "<pre>TestAusgabe\n".fb_htmlEntities($testAusgabe)."</pre>";
		//echo $testAusgabe;
	}

	$scriptspecifics=implode("",file($scriptspecifics_header_file));
	$needle="<!-- %script_specifics% -->";
	//echo "<pre>".fb_htmlEntities($scriptspecifics)."</pre><br>\n";
	$replaceBy=$scriptspecifics.$needle;
	$ausgabe=str_replace($needle,$replaceBy,$ausgabe);

	$needle="/*js_body_onload*/";
	$replaceBy="init_editbar();".$needle;
	$ausgabe=str_replace($needle,$replaceBy,$ausgabe);

	$editBlockVorlage=implode("",file($editareavorlage_file));
	$editJsVorlage=implode("",file($jsbuttonsvorlage_file));

	$editJsBlock="";
	$jsIFrameNames="";
	$objSourceItems="";

	$setUniqueNeedle=true;

	$tagStart="<!-- txtedit_toolbar";
	$tagEnd="-->";
	list($cmsitems_arr,$body_content) = get_cms_items($body_content,$tagStart,$tagEnd,$setUniqueNeedle);
	for ($i=0; $i<count($cmsitems_arr); $i++) {
		$cmsitems_vars=$cmsitems_arr[$i]["vararr"];
		$groupName=$cmsitems_vars["groupid"];
		$formName=(isset($cmsitems_vars["formname"]))?$cmsitems_vars["formname"]:"0";
		$inputName=$cmsitems_vars["inputname"];
		$inputWidth=(isset($cmsitems_vars["width"]))?$cmsitems_vars["width"]:"400";
		$inputHeight=(isset($cmsitems_vars["height"]))?$cmsitems_vars["height"]:"100";
		$inputClass=(isset($cmsitems_vars["class"]))?$cmsitems_vars["class"]:"editarea";
		$styleSize="";
		$styleWidth=($inputWidth)?"width:$inputWidth;":"";
		$styleSize.=$styleWidth;
		$styleHeight=($inputWidth)?"height:$inputHeight;":"";
		$styleSize.=$styleHeight;
		$objSourceItems.=($objSourceItems)?",":"";
		$objSourceItems.="'$groupName,$formName,$inputName'";
		$groupNeedle=$cmsitems_arr[$i]["needle"];
		$editBlock=str_replace("%GROUPID%",$groupName,$editBlockVorlage);
		// Maße des Eingabefeldes
		$editBlock=str_replace("/*styleSize*/",$styleSize,$editBlock);
		// Breite der Schaltflächenleiste
		$editBlock=str_replace("/*styleWidth*/",$styleWidth,$editBlock);
		$editBlock=str_replace("%class%",$inputClass,$editBlock);
		$editJs=str_replace("%GROUPID%",$groupName,$editJsVorlage);
		$editJs=str_replace("%FORMNAME%",$formName,$editJs);
		$ausgabe=str_replace("%FORMNAME%",$formName,$ausgabe);
		
		$body_content=str_replace($groupNeedle,$editBlock,$body_content);
		$editJsBlock.=($editJsBlock)?",":"";
		$editJsBlock.=$editJs;
		$jsIFrameNames.=($jsIFrameNames)?",":"";
		$jsIFrameNames.="'".$groupName."'";
	}
	//echo $editJsBlock;
	$ausgabe=str_replace("/*jsGroupButtons*/",$editJsBlock."/*jsGroupButtons*/",$ausgabe);
	$ausgabe=str_replace("/*jsIFrameNames*/",$jsIFrameNames."/*jsIFrameNames*/",$ausgabe);
	$ausgabe=str_replace("/*objSourceItems*/",$objSourceItems."/*objSourceItems*/",$ausgabe);

	if (isset($input) && is_array($input)) {
		while(list($k,$v) = each ($input)) {
			$body_content=str_replace("%".$k."%",fb_htmlEntities(stripslashes($v)),$body_content);
			//$str=fit_htmlInput($v,$delimitedTags);
		}
	}
	if ($username == "administrator") {
		//echo "<pre>Nachher\n".fb_htmlEntities($body_content)."</pre>";
		$bodyPos = strpos($ausgabe,"<body");
		$testAusgabe = substr($ausgabe,0,$bodyPos);
		$testAusgabe = "<pre>TestAusgabe\n".fb_htmlEntities($testAusgabe)."</pre>";
		//echo $testAusgabe;
	}
	return array($body_content,$ausgabe);
}

function set_mysql_orderid($tbl,$orderFld,$Ord,$idFld,$ID) {
	$ox = false;
	$error = "";
	$r_Ord = "";
	$r_min_Ord = "";
	$r_max_Ord = "";
	$msg = "";
	if ($ox) echo "#1840 set_mysql_orderid:";
	if (trim($Ord)) {
		if ($ox) echo "#1842|";
		$SQL = "SELECT $orderFld FROM $tbl WHERE $idFld = '$ID'";
		$r_Ord = onerowcol_resultquery($SQL);
		if (intval($Ord) == trim($Ord)) {
			if ($ox) echo "#1846|";
			if ($Ord > 0) {
				if ($ox) echo "#1848|";
				if (strlen(strval($Ord)) <= 4) {
					if ($ox) echo "#1850|";
					if ($Ord != $r_Ord) {
						if ($ox) echo "#1852|";
						$SQL = "SELECT MIN($orderFld),MAX($orderFld) FROM $tbl";
						$r = MyDB::query($SQL);
						if ($r) {
							if ($ox) echo "#1856|";
							list($r_min_Ord,$r_max_Ord) = MyDB::fetch_array($r);
							MyDB::free_result($r);
							if ($Ord > ($r_max_Ord+1)) $Ord = $r_max_Ord+1;
							if ($r_Ord < $Ord) {
								if ($ox) echo "#1861|";
								$SQL = "UPDATE $tbl SET";
								$SQL.= " $orderFld = ($orderFld-1)";
								$SQL.= " WHERE $orderFld <= $Ord";
								$SQL.= " AND $orderFld > $r_Ord";
							} else {
								if ($ox) echo "#1867|";
								$SQL = "UPDATE $tbl SET";
								$SQL.= " $orderFld = ($orderFld+1)";
								$SQL.= " WHERE $orderFld >= $Ord";
								$SQL.= " AND $orderFld < $r_Ord";
							}
							MyDB::query($SQL);
							
							if (MyDB::error()) { $error.=""; }
							$msg.="Die Reihenfolge für ".MyDB::affected_rows()." weitere Einträge wurde korrigiert!<br>\n";
							$SQL = "UPDATE $tbl SET $orderFld = $Ord WHERE $idFld = '$ID'";
							MyDB::query($SQL);
							if (MyDB::error()) $error.="#104 ERR:".MyDB::error()."<br>SQL:$SQL<br>\n";
						}
					} else {
						$error.="Die angegebene Sortiernummer ist mit der alten identisch!<br>\n";
					}
				} else {
					$error.="Die Sortiernummer darf h&ouml;chstens vierstellig sein!<br>\n";
				}
			} else {
				$error.="Die Sortiernummer muss eine Ganzzahl >= 1 sein!<br>\n";
			}
		} else {
			$error.="Sie haben keine Ganzzahl eingegeben!<br>\n";
		}
	} else {
		$error.="Sie haben keine Sortiernummer angegeben!<br>\n";
	}
	if ($ox) echo "<br>\r\n";
	return $error;
}
?>
