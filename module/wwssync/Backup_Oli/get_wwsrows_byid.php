<?php 

require_once(dirname(__FILE__)."/class/wwssync2.class.php");

WWW_DB::check_mssql($errno);

$TplAjaxXmlResult = "<"."?xml version=\"1.0\" encoding=\"UTF-8\" ?".">\n";
$TplAjaxXmlResult.= "<result type=\"{ResultType}\">\n";
$TplAjaxXmlResult.= "	<Msg/>\n";
$TplAjaxXmlResult.= "	<Err/>\n";
$TplAjaxXmlResult.= "	<JScript/>\n";
$TplAjaxXmlResult.= "	<MoreFields/>\n";
$TplAjaxXmlResult.= "</result>\n";
$aXmlValues["ResultType"] = "error";

$aFitTxt4Xml = array(
	"&" => "&#".ord("&").";",
	"\r" => "&#".ord("\r").";",
	"\n" => "&#".ord("\n").";",
	"\"" => "&#".ord("\"").";",
	"<" => "&#".ord("<").";",
	">" => "&#".ord(">").";"
);

function encodeXmlTxt($XmlText) {
	global $aFitTxt4Xml;
	$XmlText = strtr(decodeXmlTxt($XmlText), $aFitTxt4Xml);
	return $XmlText;
}
function decodeXmlTxt($XmlText) {
	global $aFitTxt4Xml;
	$XmlText = strtr($XmlText, array_flip($aFitTxt4Xml));
	return $XmlText;
}

$wid = (!empty($_GET["wid"])) ? $_GET["wid"] : "";
$wid_int = strval(intval($wid));
if ($wid_int == $wid && strlen($wid) <= 7 && strlen($wid) >= 5) { 
	if (!$errno) {
		if ($wid) {
			$wws = new WWW_DB();
			if ($wws && $wws->connid) {
				$aRows = $wws->get_RowsById($wid);
				if (count($aRows)) {
					// echo "#".__LINE__." Angebotsstatus <pre>".print_r($aData,true)."</pre>\n";
					$aXmlValues["MoreFields"] = "";
          for($i = 0; $i < count($aRows); $i++) {
            
  					foreach($aRows[$i] as $k => $v) {
  						$aXmlValues["MoreFields"].= "\t<Row{$i}_".$k.">".encodeXmlTxt($v)."</Row{$i}_".$k.">\n";
  					}
          }
					$aXmlValues["ResultType"] = "success";
				} else {
					$aXmlValues["Err"] = "Es wurde kein Datensatz mit der Vorgangsnr gefunden!";
				}
			} else {
				$aXmlValues["Err"] = "Es konnte keine DB-Verbindung hergestellt werden!";
			}
		} else {
			$aXmlValues["Err"] = "Fehlende WWS-ID für Anfrage";
		}
	} else {
		$aXmlValues["Err"] = $aErrno2Txt[$errno]." ".$enableNotice;
	}
} else {
	$aXmlValues["Err"] = "Ungültige oder fehlende WWS-ID (5-6 Numerische Zeichen): ".intval($wid).":".$wid;
}

$AjaxXmlResult = $TplAjaxXmlResult;
foreach($aXmlValues as $k => $v) {
	switch($k) {
		case "ResultType":
		$AjaxXmlResult = str_replace("{".$k."}", $v, $AjaxXmlResult);
		break;
		
		case "MoreFields":
		$AjaxXmlResult = str_replace("<".$k."/>", $v, $AjaxXmlResult);
		break;
		
		default:
		$AjaxXmlResult = str_replace("<".$k."/>", "<".$k.">".$v."</".$k.">", $AjaxXmlResult);
	}
}

header("Content-Type: text/xml; charset=utf-8");
echo utf8_encode($AjaxXmlResult);

?>