<?php 
require("header.php");

ob_start();

$SELF = basename($_SERVER["PHP_SELF"]);
$cat = getRequest("cat", "mitarbeiter", "PG");

$formHtml = "";
$formScript = "";
$boxid = getRequest("boxid", "frmEditData");

class raumdata {
	var $error = "";
	var $aOrteGebaeude = array();
	var $aBueroTypen = array("buero", "grossraum");
	
	function __construct() {
		global $_TABLE;
		global $db;
		$this->aOrteGebaeude = array();
		$sql = "SELECT ort, gebaeude\n";
		$sql.= " FROM `".$_TABLE["immobilien"]."`\n";
		$sql.= " GROUP BY ort, gebaeude\n";
		$sql.= " ORDER BY ort, gebaeude\n";
		$rows = $db->query_rows($sql);
		foreach($rows as $row) $this->aOrteGebaeude[$row["ort"]][] = $row["gebaeude"];
	}
	
	function get() {
		$e["ort"] = getRequest("ort", false);
		$e["gebaeude"] = getRequest("gebaeude", false);
		$e["etage"] = getRequest("etage", false);
		$e["raumnr"] = getRequest("raumnr", false);
		$e["raum_flaeche"] = getRequest("raum_flaeche", false);
		$e["raum_kategorie"] = getRequest("raum_kategorie", false);
		return $e;
	}
	
	function check($data) {
		if (empty($data["ort"])) $this->error.= "- Ortsangabe!<br>\n";
		if (empty($data["gebaeude"])) $this->error.= "- Geb&auml;deangabe!<br>\n";
		if (empty($data["etage"])) $this->error.= "- Etage!<br>\n";
		if (empty($data["raumnr"])) $this->error.= "- Raumnr!<br>\n";
		if (empty($data["raum_kategorie"])) $this->error.= "- B&uuml;rotyp!<br>\n";
		
		if (!$this->error) {
			if (!$this->isOrt($data["ort"])) $this->error.= "- Ung&uuml;ltige Ortsangabe!<br>\n";
			if (!$this->isGebaeude($data["gebaeude"], $data["ort"])) $this->error.= "- Ung&uuml;ltige Geb&auml;udeangabe!<br>\n";
			if (!$this->isBueroTyp($data["raum_kategorie"])) $this->error.= "- Ung&uuml;ltiger Bürotyp!<br>\n";
			if ($data["raum_flaeche"] && !$this->isFlaeche($data["raum_flaeche"])) $this->error.= "- Ung&uuml;ltige Flächenangabe (Ganz- oder Kommazahl)!<br>\n";
		}
		return ($this->error == "") ? true : false;
	}
	
	function isOrt($v) {
		return (isset($this->aOrteGebaeude[$v]));
	}
	
	function isGebaeude($v, $ort) {
		return (isset($this->aOrteGebaeude[$ort]) && in_array($v, $this->aOrteGebaeude[$ort]));
	}
	
	function isFlaeche($v) {
		return (preg_match("(^[0-9]+,?[0-9]*$)", $v));
	}
	
	function isBueroTyp($v) {
		return (in_array($v, $this->aBueroTypen));
	}
	
	function save($data) {
		global $_TABLE;
		global $db;
		$CanBeNull = false;
		$this->aOrteGebaeude = array();
		$sql = "INSERT INTO `".$_TABLE["immobilien"]."` SET\n";
		$sql.= $db->setFieldValue("ort", $data["ort"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("gebaeude", $data["gebaeude"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("etage", $data["etage"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("raumnr", $data["raumnr"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("raum_flaeche", str_replace(",", ".", $data["raum_flaeche"]), "float", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("raum_kategorie", $data["raum_kategorie"], "string", $CanBeNull)."\n";
		$db->query($sql);
		if (!$db->error()) return $db->insert_id(); 
		
		$this->error.= $db->error();
		return false;
	}
}

class abteilung {
	var $aBereiche = array();
	var $aAbteilungen = array();
	var $aSelect = array();
	var $aListe = array();
	
	function __construct() {
		global $_TABLE;
		global $db;
		global $formHtml;
		
		$this->aBereiche = array();
		$sql = "SELECT bereich, bereichsname\n";
		$sql.= " FROM `".$_TABLE["hauptabteilungen"]."`\n";
		$sql.= " ORDER BY bereichsname\n";
		$rows = $db->query_rows($sql);
		foreach($rows as $row) $this->aBereiche[$row["bereich"]] = $row["bereichsname"]." (".$row["bereich"].")";
		
		$this->aAbteilungen = array();
		$sql = "SELECT id, bereich, abteilung, abteilungsname\n";
		$sql.= " FROM `".$_TABLE["abteilungen_v"]."`\n";
		$sql.= " ORDER BY abteilungsname\n";
		$this->aListe = $db->query_rows($sql);
		
		$this->aAbteilungen = &$this->aListe;
		
		foreach($this->aListe as $k => $v) {
			$this->aSelect[$v["abteilung"]] = $v["abteilungsname"]." (".$v["abteilung"].")";
		}
	}
	
	function get() {
		$e["bereich"] = getRequest("bereich", "");
		$e["abteilung"] = getRequest("abteilung", "");
		$e["abteilungsname"] = getRequest("abteilungsname", "");
		$e["abteilungsleiter"] = getRequest("abteilungsleiter", "");
	}
	
	function check($e) {
		if (empty($e["bereich"])) $this->error.= "Wählen Sie einen Bereich (Hauptabteilung) aus!<br>\n";
		if (empty($e["abteilungsname"]) && empty($e["abteilung"]) && empty($e["abteilungsleiter"])) {
			$this->error.= "Geben Sie für die Abteilung eine Abk&uuml;rzung, einen Namen od. einen Leiter an!<br>\n";
		}
		return (!empty($this->error));
	}
	
	function save() {
		global $_TABLE;
		global $db;
		$CanBeNull = false;
		$this->aOrteGebaeude = array();
		$sql = "INSERT INTO `".$_TABLE["abteilungen_v"]."` SET\n";
		$sql.= $db->setFieldValue("bereich", $data["bereich"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("abteilung", $data["abteilung"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("abteilungsname", $data["abteilungsname"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("abteilungsleiter", $data["abteilungsleiter"], "string", $CanBeNull)."\n";
		$db->query($sql);
		if (!$db->error()) return $db->insert_id(); 
		
		$this->error.= $db->error();
		return false;
	}
	// 	Abteilung:
	// - Geschäftsbereich (Abk)
	// - Hauptabteilung (Abk)
	// - Abteilungsname (Klartext z.B. Grafik)
	// - Abteilungsleiter
	// Keine Pflichtfelder bei Bestandaufnahme
	// Flag: Was wurde angelegt: Abteilung, Hauptabteilung, Geschäftsbereich
}


class bereich {
	var $error = "";
	var $aListe = array();
	var $aSelect = array();
	
	function __construct() {
		global $_TABLE;
		global $db;
		
		$sql = "SELECT id, bereich, bereichsname, bereichsleiter, organisationseinheit\n";
		$sql.= " FROM `".$_TABLE["hauptabteilungen"]."`\n";
		$sql.= " ORDER BY bereichsname\n";
		$this->aListe = $db->query_rows($sql);
		foreach($this->aListe as $k => $v) {
			$this->aSelect[$v["id"]] = $v["bereichsname"]." (".$v["bereich"].")";
		}
	}
	
	function get() {
		$e["bereich"] = getRequest("bereich", "");
		$e["bereichsname"] = getRequest("bereichsname", "");
		$e["bereichsleiter"] = getRequest("bereichsleiter", "");
	}
	
	function check($e) {
		if (empty($e["bereichsname"]) && empty($e["bereich"]) && empty($e["bereichsleiter"])) {
			$this->error.= "Geben Sie für den Bereich eine Abk&uuml;rzung, einen Namen od. einen Leiter an!<br>\n";
		}
		return (!empty($this->error));
	}
	
	function save() {
		global $_TABLE;
		global $db;
		$CanBeNull = false;
		$sql = "INSERT INTO `".$_TABLE["hauptabteilungen"]."` SET\n";
		$sql.= $db->setFieldValue("bereich", $data["bereich"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("bereichsname", $data["bereichsname"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("bereichsleiter", $data["bereichsleiter"], "string", $CanBeNull)."\n";
		$db->query($sql);
		if (!$db->error()) return $db->insert_id(); 
		
		$this->error.= $db->error();
		return false;
	}
	// 	Abteilung:
	// - Geschäftsbereich (Abk)
	// - Hauptabteilung (Abk)
	// - Abteilungsname (Klartext z.B. Grafik)
	// - Abteilungsleiter
	// Keine Pflichtfelder bei Bestandaufnahme
	// Flag: Was wurde angelegt: Abteilung, Hauptabteilung, Geschäftsbereich
}

class gf {
	var $error = "";
	var $aGF = array();
	
	function __construct() {
		global $_TABLE;
		global $db;
		$this->aBereiche = array();
		$sql = "SELECT id, organisationseinheit, name\n";
		$sql.= " FROM `".$_TABLE["gf"]."`\n";
		$sql.= " ORDER BY name\n";
		$rows = $db->query_rows($sql);
		foreach($rows as $row) $this->aGF[$row["organisationseinheit"]] = $row["name"]." (".$row["organisationseinheit"].")";
	}
	
	function get() {
		//id 	
		//organisationseinheit 	
		//name 	
		//personalbelegschaft 	
		//verrechenbare_flaeche
		$e["id"] = getRequest("id", "");
		$e["organisationseinheit"] = getRequest("organisationseinheit", "");
		$e["name"] = getRequest("name", "");
		$e["personalbelegschaft"] = getRequest("personalbelegschaft", "");
		$e["verrechenbare_flaeche"] = getRequest("verrechenbare_flaeche", "");
		return $e;
	}
	
	function check($e) {
		if (empty($e["organisationseinheit"])) $this->error.= "- Organisationseinheit!<br>\n";
		if (empty($e["name"])) $this->error.= "- Name!<br>\n";
	}
	
	function save($data) {
		global $_TABLE;
		global $db;
		$CanBeNull = false;
		$this->aOrteGebaeude = array();
		$sql = "INSERT INTO `".$_TABLE["gf"]."` SET\n";
		$sql.= $db->setFieldValue("organisationseinheit", $data["organisationseinheit"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("name", $data["name"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("personalbelegschaft", $data["personalbelegschaft"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("verrechenbare_flaeche", $data["verrechenbare_flaeche"], "string", $CanBeNull)."\n";
		$db->query($sql);
		if (!$db->error()) return $db->insert_id(); 
		
		$this->error.= $db->error();
		return false;
	}
	
	function __destruct() {
		
	}
}


class mitarbeiter {
	var $error = "";
	
	function __construct() {
	}
	
	function get() {
		$e["name"] = getRequest("name", "");
		$e["vorname"] = getRequest("vorname", "");
		$e["extern"] = getRequest("extern", "");
		$e["extern_firma"] = getRequest("extern_firma", "");
		$e["ersthelfer"] = getRequest("ersthelfer", "");
		$e["raeumungsbeauftragter"] = getRequest("raeumungsbeauftragter", "");
		$e["raum"] = getRequest("raum", "");
		$e["abteilung"] = getRequest("abteilung", "");
		$e["anmerkung"] = getRequest("anmerkung", "");
	}
	
	function check($e) {
		if (empty($e["extern"])) $this->error.= "- Extern!<br>\n";
		elseif ($e["extern"] == "Ja" && empty($e["extern_firma"])) $this->error.= "- Externe Firma!<br>\n";
		
		if (!empty($e["extern"]) && $e["extern"] != "Flex-Position" && $e["extern"] != "Spare" && $e["extern"] != "Funktionsarbeitsplatz") {
			if (empty($e["name"])) $this->error.= "- Name!<br>\n";
			if (empty($e["vorname"])) $this->error.= "- Vorname!<br>\n";
			if (empty($e["name"])) $this->error.= "- Name!<br>\n";
		}
		if (empty($e["ersthelfer"])) $this->error.= "- Ersthelfer (Ja/Nein)!<br>\n";
		if (empty($e["raeumungsbeauftragter"])) $this->error.= "- R&auml;umungsbeauftragter (Ja/Nein)!<br>\n";
		if (empty($e["raum"])) $this->error.= "- Raum!<br>\n";
		if (empty($e["abteilung"])) $this->error.= "- Abteilung!<br>\n";
		return (!empty($this->error));
	}
	
	function getNewSpare() {
		global $_TABLE;
		global $db;
		$sql = "SELECT MAX(name) MaxNumber, MAX(LENGTH(name)) MaxLength FROM `".$_TABLE["mitarbeiter"]."` WHERE name LIKE \"%FLEX\"";
		$row = $db->query_singlerow($sql);
		if (strlen($row["MaxNumber"]) == $row["MaxLength"]) {
			$oldMaxNumber = substr($row["MaxNumber"], 0, -4);
			$newMaxNumber = intval($oldMaxNumber)+1;
			if (strlen($newMaxNumber)<strlen($oldMaxNumber)) {
				return substr("0000".$newMaxNumber, -strlen($oldMaxNumber))."FLEX";
			} return $newMaxNumber."FLEX";
		}
		$this->error.= "Der lexikalisch gr&ouml;szlig;te Wert '".$row["MaxNumber"]."FLEX' in der DB hat nicht die l&auml;gste Zeichenfolge (".$row["MaxLength"].")!<br>\n";
		return false;
	}
	
	function getNewFlexPosition() {
		global $_TABLE;
		global $db;
		$sql = "SELECT MAX(name) MaxNumber, MAX(LENGTH(name)) MaxLength FROM `".$_TABLE["mitarbeiter"]."` WHERE name LIKE \"%SPARE\"";
		$row = $db->query_singlerow($sql);
		if (strlen($row["MaxNumber"]) == $row["MaxLength"]) {
			$oldMaxNumber = substr($row["MaxNumber"], 0, -5);
			$newMaxNumber = intval($oldMaxNumber)+1;
			if (strlen($newMaxNumber)<strlen($oldMaxNumber)) {
				return substr("0000".$newMaxNumber, -strlen($oldMaxNumber))."SPARE";
			} return $newMaxNumber."SPARE";
		}
		$this->error.= "Der lexikalisch gr&ouml;szlig;te Wert '".$row["MaxNumber"]."SPARE' in der DB hat nicht die l&auml;gste Zeichenfolge (".$row["MaxLength"].")!<br>\n";
		return false;
	}
	
	function save($data) {
		global $_TABLE;
		global $db;
		$CanBeNull = false;
		$this->aOrteGebaeude = array();
		if ($data["extern"] == "Flex-Position") {
			$data["name"] = getNewFlexPosition();
			$data["vorname"] = "POSITION";
		} elseif ($data["extern"] == "Spare") {
			$data["name"] = getNewSpare();
			$data["vorname"] = "";
		}
		$sql = "INSERT INTO `".$_TABLE["mitarbeiter"]."` SET\n";
		$sql.= $db->setFieldValue("raum", $data["raum"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("abteilung", $data["abteilung"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("name", $data["name"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("vorname", $data["vorname"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("extern", $data["extern"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("extern_firma", $data["extern_firma"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("ersthelfer", $data["ersthelfer"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("raeumungsbeauftragter", $data["raeumungsbeauftragter"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("anmerkung", $data["anmerkung"], "string", $CanBeNull).",\n";
		$db->query($sql);
		if (!$db->error()) return $db->insert_id(); 
		
		$this->error.= $db->error();
		return false;
	}
	// 	Abteilung:
	// - Geschäftsbereich (Abk)
	// - Hauptabteilung (Abk)
	// - Abteilungsname (Klartext z.B. Grafik)
	// - Abteilungsleiter
	// Keine Pflichtfelder bei Bestandaufnahme
	// Flag: Was wurde angelegt: Abteilung, Hauptabteilung, Geschäftsbereich
}

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
		$formHtml.= '
	<form name="frmNewEntry" action="'.$SELF.'" method="post" onsubmit="return (typeof(AjaxFormSend)==\'function\' && AjaxFormSend(this,\'frmEditData\'))?false:true;">
	<div class="h1Form">Neue Raumdaten</div>
	<label>Ort, Geb&auml;ude</label> '.$e["ort"].', '.$e["gebaeude"].'<br>
	<label for="etage">Etage</label>'.get_InputText("etage", $e["etage"]).'<br>
	<label for="raumnr">Raum</label>'.get_InputText("raumnr", $e["raumnr"]).'<br>
	<label for="raum_flaeche">Fl&auml;che</label>'.get_InputText("raum_flaeche", $e["raum_flaeche"]).'<br>
	<label for="raum_kategorie">B&uuml;rotyp</label>'.get_InputRadio("raum_kategorie", $e["raum_kategorie"], array('buero'=>'B&uuml;ro','grossraum'=>'Gro&szlig;raum')).'<br>
	<input type="hidden" name=cat value="'.fb_htmlEntities($cat).'">
	<input type="hidden" name=ort value="'.fb_htmlEntities($e["ort"]).'">
	<input type="hidden" name=gebaeude value="'.fb_htmlEntities($e["gebaeude"]).'">
	<input type="submit" name=NeuerEintrag value="Speichern">
	</form>';
	} else {
		$formHtml.= "Raum wurde gespeichert!<br>\n";
		$formScript.= "alert('raum=".$e["id"]."'); setTimeout(\"refreshImoFilter('', 'raum=".$e["id"]."')\", 500)";
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
	<form name="frmNewEntry" action="'.$SELF.'" method="post" onsubmit="return (typeof(AjaxFormSend)==\'function\' && AjaxFormSend(this,\'frmEditData\'))?false:true;">
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
	<input type="submit" name=NeuerEintrag value="Speichern">
	</form>';
	} else {
		$formHtml.= "Abteilung wurde gespeichert!<br>\n";
	}
	break;
	
	case "bereich":
	$formHtml = '';
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
	<form name="frmNewEntry" action="'.$SELF.'" method="post" onsubmit="return (typeof(AjaxFormSend)==\'function\' && AjaxFormSend(this,\'frmEditData\'))?false:true;">
	<div class="h1Form">Neuer Bereich (Hauptabteilung)</div>
	<label for="etage">Bereich</label>'.get_InputText("bereich", $e["bereich"]).'<br>
	<label for="raumnr">Bereichsname</label>'.get_InputText("bereichsname", $e["bereichsname"]).'<br>
	<label for="raumnr">Bereichsleiter</label>'.get_InputText("bereichsleiter", $e["bereichsleiter"]).'<br>
	<input type="hidden" name=cat value="'.fb_htmlEntities($cat).'">
	<input type="hidden" name=ort value="'.fb_htmlEntities($ort).'">
	<input type="hidden" name=gebaeude value="'.fb_htmlEntities($gebaeude).'">
	<input type="hidden" name=etage value="'.fb_htmlEntities($etage).'">
	<input type="hidden" name=raum value="'.fb_htmlEntities($raum).'">
	<input type="submit" name=NeuerEintrag value="Speichern">
	</form>';
	} else {
		$formHtml.= "Bereich wurde gespeichert!<br>\n";
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
	<form name="frmNewEntry" action="'.$SELF.'" method="post" onsubmit="return (typeof(AjaxFormSend)==\'function\' && AjaxFormSend(this,\'frmEditData\'))?false:true;">
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
	<input type="submit" name=NeuerEintrag value="Speichern">
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
	$DataSent = (isset($_POST["bereichsname"]));
	if ($DataSent) {
		if ($MaData->check($e)) {
			$e["id"] = $MaData->save($e);
		}
	}
	if (!$DataSent || $MaData->error) {
		if ($MaData->error) $formHtml.= $MaData->error."<br>\n";
		//$formHtml.= "#".__LINE__." DataSent:".print_r($DataSent,1)."; POST:".print_r($_POST,1)."; GET:".print_r($_GET,1)."; RaumData->error:".print_r($MaData->error,1)."<br>\n";
		
		$formHtml.= '
	<form name="frmNewEntry" action="'.$SELF.'" method="post" onsubmit="return (typeof(AjaxFormSend)==\'function\' && AjaxFormSend(this,\'frmEditData\'))?false:true;">
	<div class="h1Form">Neuer Arbeitsplatz</div>
	<label for="name">Name</label>'.get_InputText("name", $e["name"]).'<br>
	<label for="vorname">Vorname</label>'.get_InputText("vorname", $e["vorname"]).'<br>
	<label for="extern">Extern</label>'.get_SelectBox("extern", $e["extern"], array("Ja","Intern", "Extern", "Funktionsarbeitsplatz", "Flex-Position", "Spare"), false).'<br>
	<label for="extern">Externe Firma</label>'.get_InputText("extern_firma", $e["extern_firma"]).'<br>
	<label for="raum">Raum</label>'.get_InputRead("raum", $e["ort"]." | Etg:".$e["etage"]." | R:".$e["raumnr"]." | m²:".$e["groesse_qm"], "id=\"".$e["immobilien_raum_id"]."\"").'<br>
	<label for="abteilung">GF</label>'.get_SelectBox("gf", $e["gf"], $GFData->aGF, true, "xonchange=reloadSelectBereiche(this,  'frmNewEntry')").'<br>
	<label for="abteilung">Bereich</label>'.get_SelectBox("bereich", $e["bereich"], $AbtlgData->aBereiche, true, "xonchange=reloadSelectAbteilungen(this,  'frmNewEntry')").'<br>
	';
	
		die(print_r($AbtlgData->aSelect,1));
	$formHtml.= '<label for="abteilung">Abteilung</label>'.get_SelectBox("abteilung", $e["abteilung"], $AbtlgData->aSelect, true, "").'<br>
	<label for="ersthelfer">Ersthelfer</label>'.get_SelectBox("ersthelfer", $e["ersthelfer"], array("Ja","Nein")).'<br>
	<label for="raeumungsbeauftragter">R&auml;umungsbeauftragter</label>'.get_SelectBox("raeumungsbeauftragter", $e["raeumungsbeauftragter"], array("Ja","Nein")).'<br>
	<label for="anmerkung">Anmerkung</label>'.get_TextArea("anmerkung", $e["anmerkung"]).'<br>
	<input type="hidden" name=cat value="'.fb_htmlEntities($cat).'">
	<input type="hidden" name=ort value="'.fb_htmlEntities($ort).'">
	<input type="hidden" name=gebaeude value="'.fb_htmlEntities($gebaeude).'">
	<input type="hidden" name=etage value="'.fb_htmlEntities($etage).'">
	<input type="hidden" name=raum value="'.fb_htmlEntities($raum).'">
	<input type="submit" name=NeuerEintrag value="Speichern">
	</form>';
		$formHtml.= "#".__LINE__." aSelect: ".print_r($AbtlgData->aSelect,1)."<br>\n";
		
		$formScript.= "aBereiche = {";
		foreach($BereichData->aListe as $v) {
			$formScript.= "\t\"".$v["bereich"]."\" : { \"oe\":\"".$v["organisationseinheit"]."\", \"b\":\"".$v["bereich"]."\", \"bname\": \"".$v["bereichsname"]."\" },\n";
		}
		$formScript.= "};\n";
		
		$formScript.= "aAbteilungen = {";
		foreach($AbtlgData->aAbteilungen as $k => $v) {
			$formScript.= "\t\"".$v["id"]."\" : { \"b\":\"".$v["bereich"]."\", \"a\": \"".$v["abteilung"]."\", \"aname\": \"".$v["abteilungsname"]."\" },\n";
		}
		$formScript.= "};";
		
	} else {
		$formHtml.= "Bereich wurde gespeichert!<br>\n";
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