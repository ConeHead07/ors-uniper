<?php 


class raumdata {
	var $error = "";
	var $aOrteGebaeude = array();
	var $aBueroTypen = array("buero", "grossraum", "null");
	var $aRaumTypen = array();
	
	function __construct() {
		global $_TABLE;
		global $db;
		$this->aOrteGebaeude = array();
		$sql = "SELECT stadtname ort, gebaeude\n";
		$sql.= " FROM `".$_TABLE["gebaeude"]."`\n";
		$sql.= " GROUP BY ort, gebaeude\n";
		$sql.= " ORDER BY ort, gebaeude\n";
		$rows = $db->query_rows($sql);
		foreach($rows as $row) $this->aOrteGebaeude[$row["ort"]][] = $row["gebaeude"];
		
		$sql = "SELECT t.id, t.raumtyp, t.beschreibung tb, k.raumkategorie, k.beschreibung kb \n";
		$sql.= " FROM `".$_TABLE["raumtypen"]."` t LEFT JOIN `".$_TABLE["raumkategorien"]."` k USING(raumkategorie)\n";
		$sql.= " ORDER BY raumkategorie, raumtyp\n";
		$rows = $db->query_rows($sql);
		//die(MyDB::error()."\n".$sql);
		foreach($rows as $k => $v) $this->aRaumTypen[$v["raumkategorie"]." ".$v["kb"]][$v["id"]] = $v["raumtyp"]." ".$v["tb"];
	}
	
	function get() {
		$e["ort"] = getRequest("ort", false);
		$e["gebaeude"] = getRequest("gebaeude", false);
		$e["etage"] = getRequest("etage", false);
		$e["raumnr"] = getRequest("raumnr", false);
		$e["raum_flaeche"] = getRequest("raum_flaeche", false);
		$e["raum_typ"] = getRequest("raum_typ", false);
		
		$raumtypdaten = ($e["raum_typ"]) ? $this->getRaumTypDaten($e["raum_typ"]) : array();
		$e["raumkategorie_key"] = ($raumtypdaten["raumkategorie"] ? $raumtypdaten["raumkategorie"] : "");
		$e["raumtyp_key"] = ($raumtypdaten["raumtyp"] ? $raumtypdaten["raumtyp"] : "");
		return $e;
	}
	
	function updateRaumstatusById($raumid) {
		global $_TABLE;
		global $db;
		
		$sql = "UPDATE `".$_TABLE["immobilien"]."` SET\n";
		$sql.= " `aufgenommen_am` = NOW() \n";
		$sql.= " WHERE id = ".(int)$raumid." \n";
		$row = $db->query($sql);
		if ($db->error()) $this->error.= $db->error();
		return $row;
	}
	
	function getRaumById($raumid) {
		global $_TABLE;
		global $db;
		
		$sql = "SELECT * FROM `".$_TABLE["immobilien"]."`\n";
		$sql.= " WHERE id = ".(int)$raumid." \n";
		$sql.= " LIMIT 1";
		$row = $db->query_singlerow($sql);
		if ($db->error()) $this->error.= $db->error();
		return $row;
	}
	
	function getRaumTypDaten($raumtyp_id) {
		global $_TABLE;
		global $db;
		
		$sql = "SELECT raumtyp, raumkategorie \n";
		$sql.= " FROM `".$_TABLE["raumtypen"]."`\n";
		$sql.= " WHERE id = ".(int)$raumtyp_id."\n";
		$sql.= " LIMIT 1";
		$row = $db->query_singlerow($sql);
		if ($db->error()) $this->error.= $db->error();
		return $row;
	}
	
	function check($data) {
		if (empty($data["ort"])) $this->error.= "- Ortsangabe!<br>\n";
		if (empty($data["gebaeude"])) $this->error.= "- Geb&auml;deangabe!<br>\n";
		if (empty($data["etage"])) $this->error.= "- Etage!<br>\n";
		if (empty($data["raumnr"])) $this->error.= "- Raumnr!<br>\n";
		if (empty($data["raum_typ"])) $this->error.= "- Raumtyp (".$data["raum_typ"].")!<br>\n";
		
		if ($data["gebaeude"] && $data["etage"] && $data["raumnr"]) {
			if ($this->raumnrExists($data["gebaeude"], $data["etage"], $data["raumnr"]))
				$this->error.= "Die Raumnr ".$data["raumnr"]." existiert bereits in ".$data["gebaeude"]." ".$data["etage"]."!<br>\n";
		}
		
		if (!$this->error) {
			if (!$this->isOrt($data["ort"])) $this->error.= "- Ung&uuml;ltige Ortsangabe!<br>\n";
			if (!$this->isGebaeude($data["gebaeude"], $data["ort"])) $this->error.= "- Ung&uuml;ltige Geb&auml;udeangabe!<br>\n";
			if ($data["raum_flaeche"] && !$this->isFlaeche($data["raum_flaeche"])) $this->error.= "- Ung&uuml;ltige Flächenangabe (Ganz- oder Kommazahl)!<br>\n";
		}
		return (empty($this->error));
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
	
	function isEmpty($raumid) {
		$numMitarbeiter = $this->numMitarbeiter($raumid);
		return ($numMitarbeiter === "0");
	}
	
	function isRaumTyp($v) {
		global $_TABLE;
		global $db;
		$raumtypdaten = $this->getRaumTypDaten($v);
		return (is_array($raumtypdaten) && count($raumtypdaten));
	}
	
	function exists($raumid) {
		global $_TABLE;
		global $db;
		
		$sql = "SELECT COUNT(*) Anzahl FROM `".$_TABLE["immobilien"]."` WHERE id = ".(int)$raumid;
		$row = $db->query_singlerow($sql);
		if ($db->error()) { $this->error.= "SQL-Fehler! Raum konnte nicht abgefragt werden!<br>\n".$db->error()."<br>\n".$sql."<br>\n"; return false; }
		return ($row["Anzahl"]);
	}
	
	function delete($raumid) {
		global $_TABLE;
		global $db;
		
		if ($this->isEmpty($raumid)) {
			$sql = "DELETE FROM `".$_TABLE["immobilien"]."` WHERE id = ".(int)$raumid;
			$db->query($sql);
			if ($db->error()) { $this->error.= "SQL-Fehler! Raum konnte nicht geloescht werden!<br>\n".$db->error()."<br>\n".$sql."<br>\n"; return false; }
			return true;
		}
		$this->error.= "Raum kann nicht gelöscht werden, da noch Mitarbeiter zugeordnet sind!<br>\n";
		return false;
	}
	
	function numMitarbeiter($raumid) {
		global $_TABLE;
		global $db;
		
		$sql = "SELECT COUNT(*) Anzahl FROM `".$_TABLE["mitarbeiter"]."` \n";
		$sql.= "WHERE `immobilien_raum_id` = ".(int)$raumid;
		$row = $db->query_singlerow($sql);
		if ($db->error()) { $this->error.= $db->error()."<br>\n"; return false; }
		return $row["Anzahl"];
	}
	
	function setRaumtypById($raumid, $raum_typ_id) {
		global $_TABLE;
		global $db;
		
		$raumtypdaten = $this->getRaumTypDaten($raum_typ_id);
		if ($raumtypdaten && isset($raumtypdaten["raumtyp"])) {
			//raumkategorie" 	raumtyp
			
			$sql = "UPDATE `".$_TABLE["immobilien"]."` SET \n";
			$sql.= " `raum_kategorie` = \"".$db->escape($raumtypdaten["raumkategorie"])."\",\n";
			$sql.= " `raum_typ` = \"".$db->escape($raumtypdaten["raumtyp"])."\"\n";
			$sql.= " WHERE id = ".(int)$raumid;
			$db->query($sql);
			if ($db->error()) $this->error.= $db->error();
			return (!$db->error()) ? true : false;
		}
		return false;
	}
	
	function raumnrExists($gebaeude, $etage, $raumnr) {
		global $_TABLE;
		global $db;
		
		$sql = "SELECT count(*) Anzahl FROM `".$_TABLE["immobilien"]."`\n";
		$sql.= " WHERE gebaeude LIKE \"".$db->escape($gebaeude)."\" \n";
		$sql.= " AND etage LIKE \"".$db->escape($etage)."\" \n";
		$sql.= " AND TRIM(raumnr) LIKE TRIM(\"".$db->escape($raumnr)."\")";
		$row = $db->query_singlerow($sql);
		if ($db->error()) $this->error.= $db->error();
		return $row["Anzahl"];
	}
	
	function setRaumnrById($raumid, $raumnr) {
		global $_TABLE;
		global $db;
		
		if (!(int)$raumid || !trim($raumnr)) {
			$this->error.= "Fehlende Raumid ($raumid) oder Raumnr ($raumnr)!<br>\n";
			return false;
		}
		
		$raumdaten = $this->getRaumById($raumid);
		if (!$raumdaten || !$raumdaten["id"]) {
			$this->error.= "Fehler: Es wurde kein Raum mit der ID $raumid gefunden!<br>\n";
			return false;
		}
		
		if (!$this->raumnrExists($raumdaten["gebaeude"], $raumdaten["etage"], $raumnr)) {
			$sql = "UPDATE `".$_TABLE["immobilien"]."`\n";
			$sql.= " SET raumnr = TRIM(\"".$db->escape($raumnr)."\")\n";
			$sql.= " WHERE id = ".(int)$raumid;
			$db->query($sql);
			if (!$db->error()) return true;
			
			$this->error.= $db->error()."<br>\n";
		} else {
			$this->error.= "Es existiert bereits eine Raumnr $raumnr in Gebäude ".$raumdaten["gebaeude"]." in Etage ".$raumdaten["etage"]."!<br>\n";
		}
		return false;
	}
	
	function setRaumflaecheById($raumid, $v) {
		global $_TABLE;
		global $db;
		
		
		if (!(int)$raumid || !trim($v)) {
			$this->error.= "Fehlende Raumid ($raumid) oder Raumfläche ($v)!<br>\n";
			return false;
		}
		
		$raumdaten = $this->getRaumById($raumid);
		if (!$raumdaten || !$raumdaten["id"]) {
			$this->error.= "Fehler: Es wurde kein Raum mit der ID $raumid gefunden!<br>\n";
			return false;
		}
		
		if ($this->isFlaeche($v)) {
			$sql = "UPDATE `".$_TABLE["immobilien"]."`\n";
			$sql.= " SET ".$db->setFieldValue("raum_flaeche", str_replace(",", ".", $v), "float", true)."\n";
			$sql.= " WHERE id = ".(int)$raumid;
			$db->query($sql);
			if (!$db->error()) return true;
			
			$this->error.= $db->error()."<br>\n";
		} else {
			$this->error.= "Ungültige Flaechenangabe: ".$v."! Eingabe erwartet Ganz- oder Kommazahl mit bis zu 2 Dezimalstellen!<br>\n";
		}
		return false;
	
	}
	
	function save($data) {
		global $_TABLE;
		global $db;
		$CanBeNull = false;
		
		$sql = "INSERT INTO `".$_TABLE["immobilien"]."` SET\n";
		$sql.= $db->setFieldValue("ort", $data["ort"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("gebaeude", $data["gebaeude"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("etage", $data["etage"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("raumnr", $data["raumnr"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("raum_flaeche", str_replace(",", ".", $data["raum_flaeche"]), "float", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("raum_kategorie", $data["raumkategorie_key"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("raum_typ", $data["raumtyp_key"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("aufgenommen_am", date("Y-m-d H:i:s"), "string", $CanBeNull)."\n";
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
		
		$this->aBereiche = array();
		$sql = "SELECT bereich, bereichsname\n";
		$sql.= " FROM `".$_TABLE["hauptabteilungen"]."`\n";
		$sql.= " ORDER BY bereich\n";
		$rows = $db->query_rows($sql);
		foreach($rows as $row) $this->aBereiche[$row["bereich"]] = $row["bereich"]." (".$row["bereichsname"].")";
		
		$this->aAbteilungen = array();
		$sql = "SELECT id, bereich, abteilung, abteilungsname\n";
		$sql.= " FROM `".$_TABLE["abteilungen_v"]."`\n";
		$sql.= " ORDER BY abteilung\n";
		$this->aListe = $db->query_rows($sql);
		
		$this->aAbteilungen = &$this->aListe;
		
		foreach($this->aListe as $k => $v) {
			$this->aSelect[($v["abteilung"]?$v["abteilung"]:$v["id"])] = $v["abteilung"]." (".$v["abteilungsname"].")";
		}
	}
	
	function get() {
		$e["bereich"] = getRequest("bereich", "");
		$e["abteilung"] = getRequest("abteilung", "");
		$e["abteilungsname"] = getRequest("abteilungsname", "");
		$e["abteilungsleiter"] = getRequest("abteilungsleiter", "");
		return $e;
	}
	
	function check($e) {
		if (empty($e["bereich"])) $this->error.= "- Bereich (Hauptabteilung)!<br>\n";
		
		if (empty($e["abteilung"])) $this->error.= "- Abteilung (Abk.)!<br>\n";
		elseif ($this->exists($e["abteilung"])) $this->error.= "Die Abteilung ".$e["abteilung"]." existiert bereits unter dem Bereich ".$this->getBereichByAbteilung($e["abteilung"])."!<br>\n";
		
		return (empty($this->error));
	}
	
	function exists($v) {
		global $_TABLE;
		global $db;
		
		$sql = "SELECT count(*) Anzahl\n";
		$sql.= " FROM `".$_TABLE["abteilungen"]."`\n";
		$sql.= " WHERE abteilung = \"".$db->escape($v)."\"";
		$row = $db->query_singlerow($sql);
		if ($db->error()) { $this->error.= $db->error(); return true; }
		return ($row["Anzahl"]);
	}
	
	function getBereichByAbteilung($v) {
		global $_TABLE;
		global $db;
		
		$sql = "SELECT bereich\n";
		$sql.= " FROM `".$_TABLE["abteilungen"]."`\n";
		$sql.= " WHERE abteilung = \"".$db->escape($v)."\" LIMIT 1";
		$row = $db->query_singlerow($sql);
		if ($db->error()) { $this->error.= $db->error(); return true; }
		return ($row["bereich"]);
	}
	
	function save($data) {
		global $_TABLE;
		global $db;
		$CanBeNull = false;
		$this->aOrteGebaeude = array();
		$sql = "INSERT INTO `".$_TABLE["abteilungen_v"]."` SET\n";
		$sql.= $db->setFieldValue("bereich", $data["bereich"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("abteilung", $data["abteilung"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("abteilungsname", $data["abteilungsname"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("abteilungsleiter", $data["abteilungsleiter"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("aufgenommen_am", date("Y-m-d H:i:s"), "string", $CanBeNull)."\n";
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
		$sql.= " ORDER BY bereich\n";
		$this->aListe = $db->query_rows($sql);
		foreach($this->aListe as $k => $v) {
			$this->aSelect[($v["bereich"]?$v["bereich"]:$v["id"])] = $v["bereichsname"]." (".$v["bereich"].")";
		}
	}
	
	function get() {
		$e["gf"] = getRequest("gf", "");
		$e["bereich"] = getRequest("bereich", "");
		$e["bereichsname"] = getRequest("bereichsname", "");
		$e["bereichsleiter"] = getRequest("bereichsleiter", "");
		return $e;
	}
	
	function check($e) {
		if (empty($e["gf"])) $this->error.= "- GF!<br>\n";
		
		if (empty($e["bereich"])) $this->error.= "- Bereich (Abk.)!<br>\n";
		elseif ($this->exists($e["bereich"])) $this->error.= "Der Bereich ".$e["bereich"]." existiert bereits!<br>\n";
		
		return (empty($this->error));
	}
	
	function exists($v) {
		global $_TABLE;
		global $db;
		
		$sql = "SELECT count(*) Anzahl\n";
		$sql.= " FROM `".$_TABLE["hauptabteilungen"]."`\n";
		$sql.= " WHERE bereich = \"".$db->escape($v)."\"";
		$row = $db->query_singlerow($sql);
		if ($db->error()) { $this->error.= $db->error(); return true; }
		return ($row["Anzahl"]);
	}
	
	function save($data) {
		global $_TABLE;
		global $db;
		$CanBeNull = false;
		$sql = "INSERT INTO `".$_TABLE["hauptabteilungen"]."` SET\n";
		$sql.= $db->setFieldValue("organisationseinheit", $data["gf"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("bereich", $data["bereich"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("bereichsname", $data["bereichsname"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("bereichsleiter", $data["bereichsleiter"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("aufgenommen_am", date("Y-m-d H:i:s"), "string", $CanBeNull)."\n";
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
	var $aListe = array();
	var $aSelect = array();
	
	function __construct() {
		global $_TABLE;
		global $db;
		
		$sql = "SELECT id, organisationseinheit, name\n";
		$sql.= " FROM `".$_TABLE["gf"]."`\n";
		$sql.= " ORDER BY organisationseinheit\n";
		$aListe = $db->query_rows($sql);
		foreach($aListe as $row) {
			$this->aGF[$row["organisationseinheit"]] = $row["organisationseinheit"]." (".$row["name"].")";
			$this->aSelect[($row["organisationseinheit"]?$row["organisationseinheit"]:$row["id"])] = $row["name"]." (".$row["organisationseinheit"].")";
		}
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
		elseif ($this->exists($e["organisationseinheit"])) $this->error.= "Der GF ".$e["organisationseinheit"]." existiert bereits!<br>\n";
		
		if (empty($e["name"])) $this->error.= "- Name!<br>\n";
		return (empty($this->error));
	}
	
	function exists($v) {
		global $_TABLE;
		global $db;
		
		$sql = "SELECT count(*) Anzahl\n";
		$sql.= " FROM `".$_TABLE["gf"]."`\n";
		$sql.= " WHERE organisationseinheit = \"".$db->escape($v)."\"";
		$row = $db->query_singlerow($sql);
		if ($db->error()) { $this->error.= $db->error(); return true; }
		return ($row["Anzahl"]);
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
		$e["abteilungen_id"] = getRequest("abteilungen_id", "");
		$e["arbeitsplatznr"] = getRequest("arbeitsplatznr", "");
		$e["name"] = getRequest("name", "");
		$e["vorname"] = getRequest("vorname", "");
		$e["extern"] = getRequest("extern", "");
		$e["extern_firma"] = getRequest("extern_firma", "");
		$e["ersthelfer"] = getRequest("ersthelfer", "Nein");
		$e["raeumungsbeauftragter"] = getRequest("raeumungsbeauftragter", "Nein");
		$e["raum"] = getRequest("raum", "");
		$e["gf"] = getRequest("gf", "");
		$e["bereich"] = getRequest("bereich", "");
		$e["abteilung"] = getRequest("abteilung", "");
		$e["abteilungen_id"] = getRequest("abteilungen_id", "");
		$e["anmerkung"] = getRequest("anmerkung", "");
		
		if (strtoupper($e["ersthelfer"])!="JA") $e["ersthelfer"] = "Nein";
		if (strtoupper($e["raeumungsbeauftragter"])!="JA") $e["raeumungsbeauftragter"] = "Nein";
		
		$raumdaten = ($e["raum"]) ? $this->getRaum($e["raum"]) : array();
		//die("\$raumdaten: ".print_r($raumdaten,1));
		$e["ort"] = (!empty($raumdaten["ort"]) ? $raumdaten["ort"] : "");
		$e["gebaeude"] = (!empty($raumdaten["gebaeude"]) ? $raumdaten["gebaeude"] : "");
		$e["etage"] = (!empty($raumdaten["etage"]) ? $raumdaten["etage"] : "");
		$e["raumnr"] = (!empty($raumdaten["raumnr"]) ? $raumdaten["raumnr"] : "");
		$e["raum_typ"] = (!empty($raumdaten["raum_typ"]) ? $raumdaten["raum_typ"] : "");
		return $e;
	}
	
	function getRaum($raum_id) {
		global $_TABLE;
		global $db;
		$sql = "SELECT ort, gebaeude, etage, raumnr, raum_typ FROM `".$_TABLE["immobilien"]."` WHERE `id` = ".(int)$raum_id;
		$row = $db->query_singlerow($sql);
		if ($db->error()) $this->error.= $db->error();
		return $row;
	}
	
	function getMitarbeiter($id) {
		global $_TABLE;
		global $db;
		$sql = "SELECT * FROM `".$_TABLE["mitarbeiter"]."` WHERE `id` = ".(int)$id." LIMIT 1";
		$row = $db->query_singlerow($sql);
		if ($db->error()) $this->error.= $db->error();
		return $row;
	}
	
	function check($e) {
		switch($e["extern"]) {
			case "Staff":
			case "Extern":
			case "Funktionsarbeitsplatz":
			if (empty($e["abteilungen_id"]) && empty($e["bereich"]) && empty($e["gf"])) {
				$this->error.= "- Bei Staff, Extern u. Funktionsarbeitsplatz muss eine Abteilung oder Bereich angegeben werden!<br>\n";
			}
		}
		
		if (empty($e["extern"])) $this->error.= "- Arbeitsplatznutzug!<br>\n";
		elseif ($e["extern"] == "Extern" && empty($e["extern_firma"])) $this->error.= "- Externe Firma!<br>\n";
		
		//if (empty($e["buerotyp"])) $this->error.= "- B&uuml;rotyp!<br>\n";
		if (empty($e["raum_typ"])) $this->error.= "- Fehlender Raumtyp, Ordnen Sie dem Raum einen Raumtyp zu!<br>\n";
		if ($e["raum_typ"] == "GBUE" && empty($e["arbeitsplatznr"])) $this->error.= "- Arbeitsplatnr!<br>\n";
		
		if (!empty($e["extern"]) && $e["extern"] != "Flex-Position" && $e["extern"] != "Spare" && $e["extern"] != "Funktionsarbeitsplatz") {
			if (empty($e["name"])) $this->error.= "- Name!<br>\n";
			if (empty($e["vorname"])) $this->error.= "- Vorname!<br>\n";
		}
		if (empty($e["raum"])) $this->error.= "- Raum!<br>\n";
		return (empty($this->error));
	}
	
	function OLD_getNewFlexPosition() {
		global $_TABLE;
		global $db;
		$sql = "SELECT MAX(name) MaxNumber, MAX(LENGTH(name)) MaxLength FROM `".$_TABLE["mitarbeiter"]."` WHERE name LIKE \"%FLEX\"";
		$row = $db->query_singlerow($sql);
		if (strlen($row["MaxNumber"]) == $row["MaxLength"]) {
			$oldMaxNumber = substr($row["MaxNumber"], 0, -4);
			$newMaxNumber = intval($oldMaxNumber)+1;
			if (strlen($newMaxNumber)<strlen($oldMaxNumber)) {
				return substr("0000".$newMaxNumber, -max(5,strlen($oldMaxNumber)))."FLEX";
			} return $newMaxNumber."FLEX";
		}
		$this->error.= "Der lexikalisch gr&ouml;szlig;te Wert '".$row["MaxNumber"]."FLEX' in der DB hat nicht die l&auml;gste Zeichenfolge (".$row["MaxLength"].")!<br>\n";
		return false;
	}
	function getNewFlexNr() {
		global $_TABLE;
		global $db;
		$sql = "SELECT MAX(vorname) MaxNumber FROM `".$_TABLE["mitarbeiter"]."` WHERE name LIKE \"FLEX\"";
		$row = $db->query_singlerow($sql);
		return intval($row["MaxNumber"])+1;
	}
	
	function getNewSpareNr() {
		global $_TABLE;
		global $db;
		$sql = "SELECT MAX(vorname) MaxNumber FROM `".$_TABLE["mitarbeiter"]."` WHERE name = \"SPARE\"";
		$row = $db->query_singlerow($sql);
		//$oldMaxNumber = substr($row["MaxNumber"], 0, -5);
		$oldMaxNumber = intval($row["MaxNumber"]);
		return intval($oldMaxNumber)+1;
	}
	
	function getNewFunctionNr() {
		global $_TABLE;
		global $db;
		$sql = "SELECT MAX(vorname) MaxNumber FROM `".$_TABLE["mitarbeiter"]."` WHERE name = \"FUNCTION\"";
		$row = $db->query_singlerow($sql);
		return intval($row["MaxNumber"])+1;
	}
	
	function getAbteilungById($id) {
		global $_TABLE;
		global $db;
		
		$sql = "SELECT * FROM `".$_TABLE["abteilungen"]."` WHERE id = ".(int)$id;
		return $db->query_singlerow($sql);
	}
	
	function save($data, $id = "") {
		global $_TABLE;
		global $db;
		$CanBeNull = false;
		
		if ($id) $mitarbeiterdaten = $this->getMitarbeiter($id);
		if (!isset($mitarbeiterdaten) || !is_array($mitarbeiterdaten)) $mitarbeiterdaten = array();
		
		switch($data["extern"]) {
			case "Flex-Position":
			$data["name"] = "FLEX";
			if (!$id || (isset($mitarbeiterdaten["extern"]) && $mitarbeiterdaten["extern"] != $data["extern"])) {
				$data["vorname"] = $this->getNewFlexNr();
 			} elseif (!@empty($mitarbeiterdaten["vorname"]))  	$data["vorname"] = $mitarbeiterdaten["vorname"];
			break;
			
			case "Spare":
			$data["name"] = "SPARE";
			if (!$id || (isset($mitarbeiterdaten["extern"]) && $mitarbeiterdaten["extern"] != $data["extern"])) {
				$data["vorname"] = $this->getNewSpareNr();
			} elseif (!@empty($mitarbeiterdaten["vorname"])) 	$data["vorname"] = $mitarbeiterdaten["vorname"];
			break;
			
			case "Funktionsarbeitsplatz":
			$data["name"] = "FUNCTION";
			if (!$id || (isset($mitarbeiterdaten["extern"]) && $mitarbeiterdaten["extern"] != $data["extern"])) {
				$data["vorname"] = $this->getNewFunctionNr();
			} elseif (!@empty($mitarbeiterdaten["vorname"])) 	$data["vorname"] = $mitarbeiterdaten["vorname"];
			break;
		}
		
		
		if (!empty($data["abteilungen_id"])) {
			$abteilungsdaten = $this->getAbteilungById($data["abteilungen_id"]);
			$data["abteilung"] = $abteilungsdaten["abteilung"];
		} else {
			$data["abteilungen_id"] = "";
		}

		if (!isset($data["abteilung"])) $data["abteilung"] = "";
		if (!isset($data["extern_firma"])) $data["extern_firma"] = "";
		
		$SaveMode = empty($id) ? "INSERT" : "UPDATE";
		$sql = $SaveMode." `".$_TABLE["mitarbeiter"]."` SET\n";
		
		$sql.= $db->setFieldValue("abteilungen_id", $data["abteilungen_id"], "int", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("immobilien_raum_id", $data["raum"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("arbeitsplatznr", $data["arbeitsplatznr"], "int", true).",\n";
		$sql.= $db->setFieldValue("name", $data["name"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("vorname", $data["vorname"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("extern", $data["extern"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("extern_firma", $data["extern_firma"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("ersthelfer", $data["ersthelfer"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("raeumungsbeauftragter", $data["raeumungsbeauftragter"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("gf", $data["gf"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("bereich", $data["bereich"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("abteilung", $data["abteilung"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("anmerkung", $data["anmerkung"], "string", $CanBeNull).",\n";
		$sql.= $db->setFieldValue("aufgenommen_am", date("Y-m-d H:i:s"), "string", $CanBeNull)."\n";
		if ($SaveMode == "UPDATE") $sql.= "WHERE id = ".(int)$id;
		$db->query($sql);
		//die("#".__LINE__." sql:".$sql."<br>\n".$db->error());
		if (!$db->error()) return $db->insert_id(); 
		
		$this->error.= $db->error()."<br>\n".$sql;
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
