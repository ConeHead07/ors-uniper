<?php 
require("header.php");
require("bestandsaufnahme_data.class.php");


ob_start();

$SELF = basename($_SERVER["PHP_SELF"]);
$cat = getRequest("cat", "mitarbeiter", "PG");
$moreInsert = getRequest("moreInsert", "", "PG");

$formHtml = "";
$formScript = "";
$boxid = getRequest("boxid", "frmEditData");

switch($cat) {
	case "raum":
	$formHtml = '';
	$RaumData = new raumdata();
	$e = $RaumData->get();
	$DataSent = (isset($_POST["raumnr"]));
	if ($DataSent) {
		if ($RaumData->check($e)) {
			$e["id"] = $RaumData->save($e);
		}
	}
	if (!$DataSent || $RaumData->error) {
		if ($RaumData->error) $formHtml.= $RaumData->error."<br>\n";
		//$formHtml.= "#".__LINE__." DataSent:".print_r($DataSent,1)."; POST:".print_r($_POST,1)."; GET:".print_r($_GET,1)."; RaumData->error:".print_r($RaumData->error,1)."<br>\n";
		$raumTypOptions = "<options value=\"\">...</option>\n";
		foreach($RaumData->aRaumTypen as $optgroup => $childs) {
			$raumTypOptions.= "<optgroup label=\"$optgroup\">\n";
			foreach($childs as $chid => $chtxt) {
				$selected = ($e["raum_typ"] == $chid) ? "selected=\"true\"" : "";
				$raumTypOptions.= "<option value=\"$chid\" $selected>$chtxt</option>\n";
			}
			$raumTypOptions.= "</optgroup>\n";
		}
		$formHtml.= '
	<form class="frmNewEntry" name="frmNewEntry" action="'.$SELF.'" method="post" onsubmit="return (typeof(AjaxFormSend)==\'function\' && AjaxFormSend(this,\'frmEditData\'))?false:true;">
	<div class="h1Form">Neue Raumdaten</div>
	<label>Ort, Geb&auml;ude</label> '.$e["ort"].', '.$e["gebaeude"].'<br>
	<label for="etage">Etage</label>'.get_InputText("etage", $e["etage"]).'<br>
	<label for="raumnr">Raum</label>'.get_InputText("raumnr", $e["raumnr"]).'<br>
	<label for="raum_flaeche">Fl&auml;che</label>'.get_InputText("raum_flaeche", $e["raum_flaeche"]).'<br>
	<label for="raum_kategorie">Raumtyp</label> <select name="raum_typ">'.$raumTypOptions.'</select><br>
	<input type="hidden" name=cat value="'.fb_htmlEntities($cat).'">
	<input type="hidden" name=ort value="'.fb_htmlEntities($e["ort"]).'">
	<input type="hidden" name=gebaeude value="'.fb_htmlEntities($e["gebaeude"]).'">
	<input type="submit" name=NeuerEintrag value="Speichern" class="frmBtn frmSubmit">
	</form>';
	} else {
		$formHtml.= "Raum wurde gespeichert!<br>\n";
		$formScript.= "setTimeout(\"refreshImoFilter('', '&etage=".urlencode($e["etage"])."&raum=".$e["id"]."')\", 500)";
	}
	break;
	
	case "abteilung":
	$formHtml = '';
	$AbtlgData = new abteilung();
	$e = $AbtlgData->get();
	$DataSent = (isset($_POST["abteilungsname"]));
	if ($DataSent) {
		if ($AbtlgData->check($e)) {
			$e["id"] = $AbtlgData->save($e);
		}
	}
	if (!$DataSent || $AbtlgData->error) {
		if ($AbtlgData->error) $formHtml.= $AbtlgData->error."<br>\n";
		//$formHtml.= "#".__LINE__." DataSent:".print_r($DataSent,1)."; POST:".print_r($_POST,1)."; GET:".print_r($_GET,1)."; RaumData->error:".print_r($AbtlgData->error,1)."<br>\n";
		$formHtml.= '
	<form class="frmNewEntry" name="frmNewEntry" action="'.$SELF.'" method="post" onsubmit="return (typeof(AjaxFormSend)==\'function\' && AjaxFormSend(this,\'frmEditData\'))?false:true;">
	<div class="h1Form">Neue Abteilung</div>
	<label for="etage">Bereich (Haupt-Abt)</label>'.get_SelectBox("bereich", $e["bereich"], $AbtlgData->aBereiche, true).'<br>
	<label for="raumnr">Abteilung (Abk.)</label>'.get_InputText("abteilung", $e["abteilung"]).'<br>
	<label for="raumnr">Abteilungsname</label>'.get_InputText("abteilungsname", $e["abteilungsname"]).'<br>
	<label for="raumnr">Abteilungsleiter</label>'.get_InputText("abteilungsleiter", $e["abteilungsleiter"]).'<br>
	<input type="hidden" name=cat value="'.fb_htmlEntities($cat).'">
	<input type="hidden" name=ort value="'.fb_htmlEntities($ort).'">
	<input type="hidden" name=gebaeude value="'.fb_htmlEntities($gebaeude).'">
	<input type="hidden" name=etage value="'.fb_htmlEntities($etage).'">
	<input type="hidden" name=raum value="'.fb_htmlEntities($raum).'">
	<input type="submit" name=NeuerEintrag value="Speichern" class="frmBtn frmSubmit">
	</form>';
	} else {
		$formHtml.= "Abteilung wurde gespeichert!<br>\n";
		$formHtml.= "Gegebenenfalls muss die Seite <a href=# onclick=\"self.location.reload()\">neue geladen</a> werden, damit der Wert zur Verfügung steht!<br>\n";
		$formScript.= "aAbteilungen[".$e["id"]."] = { \"b\":\"".addslashes($e["bereich"])."\", \"a\": \"".addslashes($e["abteilung"])."\", \"aname\": \"".addslashes($e["abteilungsname"])."\" };";
		$formScript.= "reloadAllListSelectAbteilungen(\"".$e["bereich"]."\");\n";
	}
	break;
	
	case "bereich":
	$formHtml = '';
	$GFData = new gf();
	$BereichData = new bereich();
	$e = $BereichData->get();
	$DataSent = (isset($_POST["bereichsname"]));
	if ($DataSent) {
		if ($BereichData->check($e)) {
			$e["id"] = $BereichData->save($e);
		}
	}
	if (!$DataSent || $BereichData->error) {
		if ($BereichData->error) $formHtml.= $BereichData->error."<br>\n";
		//$formHtml.= "#".__LINE__." DataSent:".print_r($DataSent,1)."; POST:".print_r($_POST,1)."; GET:".print_r($_GET,1)."; RaumData->error:".print_r($BereichData->error,1)."<br>\n";
		$formHtml.= '
	<form class="frmNewEntry" name="frmNewEntry" action="'.$SELF.'" method="post" onsubmit="return (typeof(AjaxFormSend)==\'function\' && AjaxFormSend(this,\'frmEditData\'))?false:true;">
	<div class="h1Form">Neuer Bereich (Hauptabteilung)</div>
	<label for="etage">GF</label>'.get_SelectBox("gf", $e["gf"], $GFData->aSelect, true).'<br>
	<label for="etage">Bereich</label>'.get_InputText("bereich", $e["bereich"]).'<br>
	<label for="raumnr">Bereichsname</label>'.get_InputText("bereichsname", $e["bereichsname"]).'<br>
	<label for="raumnr">Bereichsleiter</label>'.get_InputText("bereichsleiter", $e["bereichsleiter"]).'<br>
	<input type="hidden" name=cat value="'.fb_htmlEntities($cat).'">
	<input type="hidden" name=ort value="'.fb_htmlEntities($ort).'">
	<input type="hidden" name=gebaeude value="'.fb_htmlEntities($gebaeude).'">
	<input type="hidden" name=etage value="'.fb_htmlEntities($etage).'">
	<input type="hidden" name=raum value="'.fb_htmlEntities($raum).'">
	<input type="submit" name=NeuerEintrag value="Speichern" class="frmBtn frmSubmit">
	</form>';
	} else {
		$formHtml.= "Bereich wurde gespeichert!<br>\n";
		$formHtml.= "Gegebenenfalls muss die Seite <a href=# onclick=\"self.location.reload()\">neue geladen</a> werden, damit der Wert zur Verfügung steht!<br>\n";
		// aBereiche = {	"" : { "oe":"E", "b":"", "bname": "VF - Office" },
		$formScript.= "aBereiche[".$e["id"]."] = { \"oe\":\"".addslashes($e["gf"])."\", \"b\": \"".addslashes($e["bereich"])."\", \"bname\": \"".addslashes($e["bereichsname"])."\" };";
		$formScript.= "reloadAllListSelectBereiche(\"".$e["gf"]."\");\n";
	}
	break;
	
	case "gf":
	$formHtml = '';
	$GFData = new gf();
	$e = $GFData->get();
	$DataSent = (isset($_POST["bereichsname"]));
	if ($DataSent) {
		if ($GFData->check($e)) {
			$e["id"] = $GFData->save($e);
		}
	}
	if (!$DataSent || $GFData->error) {
		if ($GFData->error) $formHtml.= $GFData->error."<br>\n";
		//$formHtml.= "#".__LINE__." DataSent:".print_r($DataSent,1)."; POST:".print_r($_POST,1)."; GET:".print_r($_GET,1)."; RaumData->error:".print_r($GFData->error,1)."<br>\n";
		$formHtml.= '
	<form class="frmNewEntry" name="frmNewEntry" action="'.$SELF.'" method="post" onsubmit="return (typeof(AjaxFormSend)==\'function\' && AjaxFormSend(this,\'frmEditData\'))?false:true;">
	<div class="h1Form">Neue GF</div>
	<label for="etage">Organisationseinheit</label>'.get_InputText("organisationseinheit", $e["organisationseinheit"]).'<br>
	<label for="raumnr">Name</label>'.get_InputText("name", $e["name"]).'<br>
	<label for="raumnr">Personalbelegschaft</label>'.get_InputText("personalbelegschaft", $e["personalbelegschaft"]).'<br>
	<label for="raumnr">Verrechenbare Fläche</label>'.get_InputText("verrechenbare_flaeche", $e["verrechenbare_flaeche"]).'<br>
	<input type="hidden" name=cat value="'.fb_htmlEntities($cat).'">
	<input type="hidden" name=ort value="'.fb_htmlEntities($ort).'">
	<input type="hidden" name=gebaeude value="'.fb_htmlEntities($gebaeude).'">
	<input type="hidden" name=etage value="'.fb_htmlEntities($etage).'">
	<input type="hidden" name=raum value="'.fb_htmlEntities($raum).'">
	<input type="submit" name=NeuerEintrag value="Speichern" class="frmBtn frmSubmit">
	</form>';
	} else {
		$formHtml.= "GF wurde gespeichert!<br>\n";
	}
	break;
	
	case "mitarbeiter":
	$BereichData = new bereich();
	$AbtlgData = new abteilung();
	$GFData = new gf();
	$MaData = new mitarbeiter();
	$e = $MaData->get();
	$DataSent = (isset($_POST["name"]));
	if ($DataSent) {
		if ($MaData->check($e)) {
			$e["id"] = $MaData->save($e);
		}
	}
	if (!$DataSent || $MaData->error) {
		if ($MaData->error) $formHtml.= $MaData->error."<br>\n";
		//$formHtml.= "#".__LINE__." DataSent:".print_r($DataSent,1)."; POST:".print_r($_POST,1)."; GET:".print_r($_GET,1)."; RaumData->error:".print_r($MaData->error,1)."<br>\n";
		
		$formHtml.= '
	<form class="frmNewEntry" name="frmNewEntry" action="'.$SELF.'" method="post" onsubmit="return (typeof(AjaxFormSend)==\'function\' && AjaxFormSend(this,\'frmEditData\'))?false:true;">
	<div class="h1Form">Neuer Arbeitsplatz</div>
	<label for="name">Name</label>'.get_InputText("name", $e["name"]).'<br>
	<label for="vorname">Vorname</label>'.get_InputText("vorname", $e["vorname"]).'<br>
	<label for="extern">Arbeitsplatznutzug</label>'.get_SelectBox("extern", $e["extern"], array("Staff", "Extern", "Funktionsarbeitsplatz", "Flex-Position", "Spare"), false, "onchange=\"checkExternFirma('frmNewEntry');checkArbeitsplatzNr('frmNewEntry');\"").'<br>
	<label for="extern_firma">Externe Firma</label>'.get_InputText("extern_firma", $e["extern_firma"]).'<br>
	<label for="arbeitsplatznr">Arbeitsplatznr</label>'.get_InputText("arbeitsplatznr", $e["arbeitsplatznr"]).'<br>
	<label for="raum">Raum</label>'.$e["ort"]." ".$e["gebaeude"]." ".$e["etage"]." Raum:".$e["raumnr"]." | m²:".$e["groesse_qm"].'<br>
	<label for="gf">GF</label>'.get_SelectBox("gf", $e["gf"], $GFData->aGF, true, " id=gf onchange=\"reloadSelectBereiche(this, 'frmNewEntry')\"").'<br>
	<label for="bereich">Bereich</label>'.get_SelectBox("bereich", $e["bereich"], $AbtlgData->aBereiche, true, "onchange=\"reloadSelectAbteilungen(this, 'frmNewEntry')\"").'<br>
	<label for="abteilung">Abteilung</label>'.get_SelectBox("abteilungen_id", $e["abteilungen_id"], $AbtlgData->aSelect, true, "").'<br>
	';
	
	$formHtml.= '<label for="ersthelfer">Ersthelfer</label>'.get_InputCheckBox("ersthelfer", $e["ersthelfer"], array("Ja"=>"Ja")).'<br>
	<label for="raeumungsbeauftragter">R&auml;umungsbeauftragter</label>'.get_InputCheckBox("raeumungsbeauftragter", $e["raeumungsbeauftragter"], array("Ja"=>"Ja")).'<br>
	<label for="anmerkung">Anmerkung</label>'.get_TextArea("anmerkung", $e["anmerkung"]).'<br>
	<input type="hidden" name=cat value="'.fb_htmlEntities($cat).'">
	<input type="hidden" name=ort value="'.fb_htmlEntities($e["ort"]).'">
	<input type="hidden" name=gebaeude value="'.fb_htmlEntities($e["gebaeude"]).'">
	<input type="hidden" name=etage value="'.fb_htmlEntities($e["etage"]).'">
	<input type="hidden" name=raum value="'.fb_htmlEntities($e["raum"]).'">
	<input type="hidden" name=raum_typ value="'.fb_htmlEntities($e["raum_typ"]).'">
	<input type="hidden" name=buerotyp value="'.fb_htmlEntities($e["buerotyp"]).'">
	<input type="checkbox" name="moreInsert" value="1" '.($moreInsert?"checked=\"true\"":"").'>Nach Speichern weiteren Eintrag vornehmen<br>
	<input type="submit" name=NeuerEintrag value="Speichern" class="frmBtn frmSubmit">
	</form>';
		
		$first = true;
		$formScript.= "aBereiche = {";
		foreach($BereichData->aListe as $v) {
			$formScript.= (!$first?",\n":"")."\t\"".$v["bereich"]."\" : { \"oe\":\"".$v["organisationseinheit"]."\", \"b\":\"".$v["bereich"]."\", \"bname\": \"".$v["bereichsname"]."\" }";
			if ($first) $first = false;
		}
		$formScript.= "};\n";
		
		$first = true;
		$formScript.= "aAbteilungen = {";
		foreach($AbtlgData->aAbteilungen as $k => $v) {
			$formScript.= (!$first?",\n":"")."\t\"".$v["id"]."\" : { \"b\":\"".$v["bereich"]."\", \"a\": \"".$v["abteilung"]."\", \"aname\": \"".$v["abteilungsname"]."\" }";
			if ($first) $first = false;
		}
		$formScript.= "};\n";
		
		$formScript.= "checkExternFirma('frmNewEntry');\n";
		$formScript.= "reloadSelectBereiche('gf', 'frmNewEntry');\n";
		$formScript.= "checkArbeitsplatzNr('frmNewEntry');\n";
		//$formHtml.= "<pre>".$formScript."</pre>\n";
	} else {
		$formHtml.= "Mitarbeiter wurde gespeichert!<br>\n";
		if ($moreInsert) $formScript.= "setTimeout(\"refreshImoFilter('', '&getForm=NewEmployer')\", 500);\n";
		else $formScript.= "setTimeout(\"refreshImoFilter()\", 500);\n";
	}
	break;

	default:?>
	Ungültige Kategorie!<?php
}
define("NEWLINE", "\n");

if (empty($formHtml)) $formHtml.= ob_get_contents();
ob_end_clean();

	header("Content-Type: text/xml; charset=ISO-8859-1");
	echo '<?xml version="1.0" encoding="ISO-8859-1" ?>
	<Result type="success">'."\n";
	
	echo '<Update id="'.(!empty($boxid)?$boxid:"frmEditData").'"><![CDATA['.$formHtml.']]></Update>'.NEWLINE;
	if (!empty($formScript)) echo '<LoadScript language="javascript" src="cdata"><![CDATA['.$formScript.']]></LoadScript>'.NEWLINE;
	echo '</Result>';
?>