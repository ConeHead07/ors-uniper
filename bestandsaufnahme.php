<?php require("header.php"); 
ob_start();
if (basename(__FILE__)==basename($_SERVER["PHP_SELF"])) {
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<?php } ?>
	<title>Untitled</title>
	<link rel="STYLESHEET" type="text/css" href="css/tablelisting.css">
	<link rel="stylesheet" type="text/css" href="css/umzugsformular.css">
	<style>
	*, body *, html * { font-family:Arial,Helvetica,sans-serif; font-size:12px; }
	.jLink { cursor:pointer; color:#00f; }
	.rowHide { display:none; }
	.tblList td input, .tblList td textarea { border:0; }
	.tblList .rowInfoLine td { border-bottom:2px solid #b4b4b4; }
	</style>
	
	<script src="js/GetObjectDisplay.js" type="text/javascript"></script>
	<script src="js/jquery.js" type="text/javascript"></script>
	<script src="js/EventHandler.js" type="text/javascript"></script>
	<script src="js/FbAjaxUpdater.js" type="text/javascript"></script>
	<script src="js/PageInfo.js" type="text/javascript"></script>
	<xscript src="js/SelBox_LiveSearch.js" type="text/javascript"></xscript>
	<script>
	
	function AjaxFormSend(frm, selector, sConfirm) {
		frm = getFormObj(frm);
		if (typeof(frm) != "object" || typeof(frm.tagName) != "string" || frm.tagName.toUpperCase() != "FORM") return false;
		if (typeof(sConfirm) == "string" && sConfirm.length) if (!confirm(sConfirm)) return false;
		
		var sPostData = "";
		var sAjaxAdds = "&AjaxRequest=1&boxid="+selector+"&refresh="+(new Date()).getTime();
		
		if (frm.method.toUpperCase() == "POST") {
			sPostData = frmSerialize(frm);
			sPostData+= sAjaxAdds;
		} else {
			frm.action+(frm.action.indexOf("?") == -1 ? "?":"&")+frmSerialize(frm);
			frm.action+= sAjaxAdds;
		}
		//alert(sPostData);
		if (fb_AjaxRequest(frm.action, frm.method, 'fb_AjaxXmlUpdate(%req%, "'+selector+'")', sPostData)) {
			if (frm.preventDefault) frm.preventDefault();
		}
		return true;
	}
	
	function refreshImoFilter(obj, sAddQuery) {
		//alert("refreshImoFilter("+obj+", "+sAddQuery+")");
		var ImoFilter = (typeof(obj) == "object" && obj.name) ? obj.name : (typeof(obj)=="string" ? obj : "");
		var rHref = "";
		var frm = document.forms["frmMAFilter"];
		var ort = (frm.ort.selectedIndex != -1) ? frm.ort.options[frm.ort.selectedIndex].value : "";
		var gebaeude = (frm.gebaeude.selectedIndex != -1) ? frm.gebaeude.options[frm.gebaeude.selectedIndex].value : "";
		var etage = (frm.etage.selectedIndex != -1) ? frm.etage.options[frm.etage.selectedIndex].value : "";
		var raum = (frm.raum.selectedIndex != -1) ? frm.raum.options[frm.raum.selectedIndex].value : "";
		switch(ImoFilter) {
			case "ort": rHref = "?ort="+escape(ort); break;
			
			case "gebaeude":
			rHref = "?ort="+escape(ort);
			rHref+= "&gebaeude="+escape(gebaeude);
			break;
			
			case "etage":
			rHref = "?ort="+escape(ort);
			rHref+= "&gebaeude="+escape(gebaeude);
			rHref+= "&etage="+escape(etage);
			break;
			
			default:
			rHref = "?ort="+escape(ort);
			rHref+= "&gebaeude="+escape(gebaeude);
			rHref+= "&etage="+escape(etage);
			rHref+= "&raum="+escape(raum);
			break;
		}	
		if (sAddQuery) rHref+= (rHref.indexOf("?") == -1 ? "?" : "&")+sAddQuery;
		rHref+= "&refresh="+(new Date()).getTime();
		//alert(sAddQuery);	
		//alert(rHref);
		if (rHref) self.location.href= rHref;
	}
	
	function getInlineForm($cat, addQuery) {
		var sUrl = "";
		var sGet = "";
		var frm = document.forms["frmMAFilter"];
		
		if (frm.ort.selectedIndex!=-1) sGet+= "&ort="+escape(frm.ort.options[frm.ort.selectedIndex].value);
		else sGet+= "&ort=";
		
		if (frm.gebaeude.selectedIndex!=-1) sGet+= "&gebaeude="+escape(frm.gebaeude.options[frm.gebaeude.selectedIndex].value);
		else sGet+= "&gebaeude=";
		
		if (frm.etage.selectedIndex!=-1) sGet+= "&etage="+escape(frm.etage.options[frm.etage.selectedIndex].value);
		else sGet+= "&etage=";
		
		if (frm.raum.selectedIndex!=-1) sGet+= "&raum="+escape(frm.raum.options[frm.raum.selectedIndex].value);
		else sGet+= "&raum=";
		/**/
		switch($cat) {
			case 'NewRoom':
			sUrl = "bestandsaufnahme_anlegen.php?cat=raum"+sGet;
			break;
			case 'NewDepart':
			sUrl = "bestandsaufnahme_anlegen.php?cat=abteilung"+sGet;
			break;
			case 'NewMainDepart':
			sUrl = "bestandsaufnahme_anlegen.php?cat=bereich"+sGet;
			break;
			case 'NewGF':
			sUrl = "bestandsaufnahme_anlegen.php?cat=gf"+sGet;
			break;
			case 'NewEmployer':
			sUrl = "bestandsaufnahme_anlegen.php?cat=mitarbeiter"+sGet;
			break;
			
			case 'close':
			document.getElementById('frmEditData').innerHTML = "";
			return true;
		}
		//alert(sUrl);
		if (typeof(addQuery) == "string") sUrl+= "&"+addQuery;
		if (sUrl) SendRequest(sUrl, 'frmEditData');
	}
	
	function reloadSelectBereiche(el, frm) {
		if (!document.forms[frm] || !document.forms[frm].elements["bereich"]) return false;
		var v = getFormElementValue(el, frm);
		var el = document.forms[frm].elements["bereich"];
		var j;
		el.options.length = 1;
		el.options[0].value = ""; el.options[0].text = "...";
		for(var i in aBereiche) if (aBereiche[i]["oe"] == v) {
			j = el.options.length;
			el.options.length = j+1;
			el.options[j].value = i;
			el.options[j].text = aBereiche[i]["b"]+" ("+aBereiche[i]["bname"]+")";
		}
		reloadSelectAbteilungen(el, frm)
	}
	
	function reloadSelectAbteilungen(el, frm) {
		if (!document.forms[frm] || !document.forms[frm].elements["abteilungen_id"]) return false;
		var v = getFormElementValue(el, frm);
		//alert("Abteilungen des Bereichs "+v+" ("+bObj.options[bObj.selectedIndex].value+"=>"+bObj.options[bObj.selectedIndex].value+")");
		var el = document.forms[frm].elements["abteilungen_id"];
		var j;
		el.options.length = 1;
		el.options[0].value = ""; el.options[0].text = "...";
		for(var i in aAbteilungen) if (aAbteilungen[i]["b"] == v) {
			j = el.options.length;
			el.options.length = j+1;
			el.options[j].value = i;
			el.options[j].text = aAbteilungen[i]["a"]+" ("+aAbteilungen[i]["aname"]+")";
		}
	}
	
	function reloadListSelectBereiche(k) {
		var frm = "frmListe";
		if (!document.forms[frm] || !document.forms[frm].elements["bereich["+k+"]"]) return false;
		var v = getFormElementValue("gf["+k+"]", frm);
		var el = document.forms[frm].elements["bereich["+k+"]"];
		var j;
		var selectedValue = (el.selectedIndex >= 0) ? el.options[el.selectedIndex].value : "";
		var selectIndex = -1;
		el.options.length = 1;
		el.options[0].value = ""; el.options[0].text = "...";
		for(var i in aBereiche) if (aBereiche[i]["oe"] == v) {
			j = el.options.length;
			el.options.length = j+1;
			el.options[j].value = i;
			el.options[j].text = aBereiche[i]["b"];
			if (el.options[j].value == selectedValue) selectIndex = j;
		}
		if (selectIndex > -1) el.options[selectIndex].selected = true;
		else reloadSelectAbteilungen(k)
	}
	
	function reloadListSelectAbteilungen(k) {
		var frm = "frmListe";
		if (!document.forms[frm] || !document.forms[frm].elements["abteilungen_id["+k+"]"]) return false;
		var v = getFormElementValue("bereich["+k+"]", frm);
		//alert("Abteilungen des Bereichs "+v+" ("+bObj.options[bObj.selectedIndex].value+"=>"+bObj.options[bObj.selectedIndex].value+")");
		var el = document.forms[frm].elements["abteilungen_id["+k+"]"];
		var j;
		var selectedValue = (el.selectedIndex >= 0) ? el.options[el.selectedIndex].value : "";
		var selectIndex = -1;
		el.options.length = 1;
		el.options[0].value = ""; el.options[0].text = "...";
		for(var i in aAbteilungen) if (aAbteilungen[i]["b"] == v) {
			j = el.options.length;
			el.options.length = j+1;
			el.options[j].value = i;
			el.options[j].text = aAbteilungen[i]["a"];
			if (el.options[j].value == selectedValue) selectIndex = j;
			//alert("i:"+i+" "+el.options[j].value+"=>"+el.options[j].text);
		}
		if (selectIndex > -1) el.options[selectIndex].selected = true;
	}
	
	function reloadAllListSelectBereiche(oe) {
		if (typeof(oe) == "undefined") oe = "";
		var frm = 'frmListe';
		var f = document.forms['frmListe'];
		if (!f) return false;
		var elms = f.getElementsByTagName("select");
		var k;
		var v;
		if (!elms.length) elms[0] = elms;
		for (var i = 0; i < elms.length; i++) {
			if (elms[i].name.indexOf("bereich[") != -1) {
				k = parseInt(elms[i].name.substr(8));
				v = getFormElementValue(f.elements["gf["+k+"]"], frm);
				//alert("reload "+elms[i].name+"; k:"+k+"; oe:"+oe+"; v:"+v);
				if (!v || (oe && oe != v)) continue;
				reloadListSelectBereiche(k);
			}
		}
	}
	
	function reloadAllListSelectAbteilungen(b) {
		if (typeof(b) == "undefined") b = "";
		var frm = 'frmListe';
		var f = document.forms['frmListe'];
		if (!f) return false;
		var elms = f.getElementsByTagName("select");
		var k;
		if (!elms.length) elms[0] = elms;
		for (var i = 0; i < elms.length; i++) {
			if (elms[i].name.indexOf("abteilungen_id[") != -1) {
				k = parseInt(elms[i].name.substr(15));
				v = getFormElementValue(f.elements["bereich["+k+"]"], frm);
				if (!b || b == v) {
					//alert("reload "+elms[i].name+"; k:"+k+"; b:"+b+"; v:"+v);
					reloadListSelectAbteilungen(k);
				}
			}
		}
	}
	
	function checkExternFirma(frm) {
		var v = getFormElementValue('extern', frm);
		document.forms[frm].elements['extern_firma'].disabled = (v != "Ja" && v != "Extern");
	}
	
	function checkListExternFirma(k) {
		var frm = "frmListe";
		var v = getFormElementValue('extern['+k+']', frm);
		document.forms[frm].elements['extern_firma['+k+']'].disabled = (v != "Ja" && v != "Extern");
	}
	
	function reloadAllListExternFirma() {
		var frm = 'frmListe';
		var f = document.forms['frmListe'];
		if (!f) return false;
		var elms = f.getElementsByTagName("select");
		var k;
		if (!elms.length) elms[0] = elms;
		for (var i = 0; i < elms.length; i++) {
			k = "?";
			if (elms[i].name.indexOf("extern[") != -1) {
				k = parseInt(elms[i].name.substr(7));
				checkListExternFirma(k);
			}/**/
			//alert("reloadAllListExternFirma() #242 i:"+i+"/"+elms.length+"; k:"+k+"; elms[i].name:"+elms[i].name);
		}
	}
	
	function checkArbeitsplatzNr(frm) {
		var vex = getFormElementValue('extern', frm);
		var vrt = getFormElementValue('raum_typ', frm);
		var vbt = getFormElementValue('buerotyp', frm);
		document.forms[frm].elements['arbeitsplatznr'].disabled = (vex != "Spare" && vrt != "GBUE");
	}
	
	function checkAbteilungsAuswahl(frm) {
		var v = getFormElementValue('abteilungs_kategorie', frm);
		document.forms[frm].elements['gf'].disabled = (v == "");
		document.forms[frm].elements['bereich'].disabled = (v == "GF" || v == "");
		document.forms[frm].elements['abteilung'].disabled = (v != "Abteilung");
	}
	
	function checkListAbteilungsAuswahl(k) {
		var frm = "frmListe";
		var v = getFormElementValue('abteilungs_kategorie['+k+']', frm);
		document.forms[frm].elements['gf['+k+']'].disabled = (v == "");
		document.forms[frm].elements['bereich['+k+']'].disabled = (v == "GF" || v == "");
		document.forms[frm].elements['abteilungen_id['+k+']'].disabled = (v != "Abteilung");
	}
	
	$(document).ready(function() { reloadAllListExternFirma();});
	</script>
	
	<script>
	// LiveSearch
	function get_SearchInputListeOrte(obj) {
		SelBox_Simple(obj, {
			qUrl:'livesearch.php',
			qName:'Ort',
			onBeforeInsert:function(d) {
				O("gebaeudeFilter").value="";
				O("etageFilter").value="";
				return true;
			},
			onAfterInsert:function(d) {
				O("gebaeudeFilter").focus();
				//get_SearchInputListeGebaeude();
				return true;
			} 
		});
	}
	
	function get_SearchInputListeGebaeude(obj) {
		if (!O('ortsFilter').value) {
			alert("Wählen Sie erst einenStandort aus!");
			O("ortsFilter").focus();
			//get_SearchInputListeOrte(O('ortsFilter'));
			return false;
		}
		SelBox_Simple(obj, {
			qUrl:'livesearch.php',
			qName:'Gebaeude',
			qAdd: "&ort="+escape(O('ortsFilter').value),
			onBeforeInsert:function(d) { 
				//alert('onBeforeInsert:'+d);
				O("etageFilter").value="";
				return true;
			},
			onAfterInsert:function(d) { 
				//alert('onAfterInsert:'+d);
				O("etageFilter").click();
				//get_SearchInputListeEtage();
				return true;
			}
		});
	}
	
	function get_SearchInputListeEtage(obj) {
		if (!document.frmMAFilter.elements['ort'].value) {
			alert("Wählen Sie erst einen Standort aus!");
			//O("ortsFilter").focus();
			//get_SearchInputListeOrte();
			return false;
		}
		if (!document.frmMAFilter.elements['gebaeude'].value) {
			alert("Wählen Sie erst ein Gebaeude aus!");
			//O("gebaeudeFilter").focus();
			//get_SearchInputListeGebaeude();
			return false;
		}
		SelBox_Simple(obj, {
			qUrl:'livesearch.php',
			qName:'Etage',
			qAdd: "&ort="+escape(O('ortsFilter').value)+"&gebaeude="+escape(O('gebaeudeFilter').value),
			onBeforeInsert:function(d) { 
				//alert('onBeforeInsert:'+d); 
				return true;
			},
			onAfterInsert:function(d) { 
				//alert('onAfterInsert:'+d); 
				return true;
			} 
		});
	}
	
	function dropMa(MaId, rowId) {
		if (!confirm("Arbeitsplatzdaten löschen?")) return false;
		
		var sUrl = "bestandsaufnahme_loeschen.php?cat=mitarbeiter&id="+MaId+"&rowId="+rowId;
		SendRequest(sUrl, 'frmEditData');
	}
	
	</script>
	<?php 
		$getForm = getRequest("getForm", "");
		switch($getForm) {
			case "NewEmployer":
			case "NewRoom":
			case "NewMainDepart":
			case "NewDepart":
			echo "<script>addEvent(window,'load', function(){getInlineForm('".$getForm."', '&moreInsert=1')});</script>\n"; 
			break;
		}
	?>
	<style>
	.boxMenu { border:#778899 1px solid; background:#f5f5dc; }
	.boxFrmFilter {  }
	.boxLnkNewData { margin:4px; }
	.boxLnkNewData a { border:#778899 1px solid; background:#f5f5dc; padding:0 10px 0 10px; margin:4px 10px 4px 4px; }
	</style>
<?php if (basename(__FILE__)==basename($_SERVER["PHP_SELF"])) { ?>
</head>

<body><?php } ?><div id="Monitor"></div>
<!-- <form name="frmMAFilterSelBox">
<label for="ort">Ort</label><input onclick="get_SearchInputListeOrte(this)" id="ortsFilter" type="text" name="ort">
<label for="gebaeude">Geb&auml;ude</label><input onfocus="get_SearchInputListeGebaeude(this)" type="text" id="gebaeudeFilter" name="gebaeude">
<label for="etage">Etage</label><input onfocus="get_SearchInputListeEtage(this)" type="text" id="etageFilter" name="etage"></select>
<input type="submit" value="ok">
</form> -->
<?php 
$frmImoFilter = <<<FrmImoFilter
<div class="boxMenu">
<div class="boxFrmFilter">
<form name="frmMAFilter" style="display:inline;">
<label for="ort" style="width:65px;">Ort</label><select name="ort" onchange="refreshImoFilter(this)">{optionsOrte}</select>
<label for="gebaeude" style="width:65px;">Geb&auml;ude</label><select name="gebaeude" onchange="refreshImoFilter(this)">{optionsGebaeude}</select>
<label for="etage" style="width:65px;">Etage</label><select id="etageFilter" name="etage" onchange="refreshImoFilter(this)">{optionsEtage}</select>
<label for="etage" style="width:65px;">Raum</label><select id="raumFilter" name="raum" onchange="refreshImoFilter(this)">{optionsRaeume}</select>
<input type="submit" value="ok">
</form></div>
<div class="boxLnkNewData"><a href="#" onclick="getInlineForm('close')">X</a>
<a href="#" onclick="getInlineForm('NewEmployer');return false;">Neuen Mitarbeiter anlegen</a> 
<a href="#" onclick="getInlineForm('NewRoom');return false;">Neuen Raum anlegen</a> 
<a href="#" onclick="getInlineForm('NewDepart');return false;">Neue Abteilung anlegen</a> 
<a href="#" onclick="getInlineForm('NewMainDepart'); return false;">Neuen Bereich (H-Abt) anlegen</a>
<a href="index.php?logout=1" style="color:#f00;">Abmelden</a></div>
<div id="frmEditData"><!-- {Msg} --></div>
</div>
FrmImoFilter;
?>
<?php 
include("bestandsaufnahme_speichern.php");
// UPDATE-Start einmalige UPDATE mit einfacher Abfrage, ob erforderlich
$rows = $db->query_rows("SELECT id FROM `".$_TABLE["immobilien"]."` WHERE `ort` = \"N\" LIMIT 1");
if (is_array($rows) && count($rows)) include("bestandsaufnahme_sqlupdate.php");
// UPDATE-Ende

include("bestandsaufnahme_sqlupdate_aufgenommen_am.php");

if (!isset($error)) $error = "";
if (!isset($msg)) $msg = "";

$BereichData = new bereich();
$AbtlgData = new abteilung();
$RaumData = new raumdata();

// RaumTyp ändern
$editRaumtyp = getRequest("editRaumtyp", "");
if ($editRaumtyp) {
	$del_raum_id = getRequest("raum", "");
	$editRaum["raum_typ_id"] = getRequest("raum_typ_id", "");
	
	if ($RaumData->setRaumtypById($del_raum_id, $editRaum["raum_typ_id"])) {
		$msg.= "Raumtyp wurde geändert!<br>\n";
	} else {
		$error.= "Raumtyp konnte nicht geändert werden!<br>\n";
		$error.= "Fehler: ".$RaumData->error."<br>\n";
	}
}

// RaumNr ändern
$editRaumnr = getRequest("editRaumnr", "");
if ($editRaumnr) {
	$del_raum_id = getRequest("raum", "");
	$editRaum["raumnr"] = getRequest("raumnr", "");
	
	if ($RaumData->setRaumnrById($del_raum_id, $editRaum["raumnr"])) {
		$msg.= "Raumnr wurde geändert!<br>\n";
	} else {
		$error.= "Raumnr konnte nicht geändert werden!<br>\n";
		$error.= "Fehler: ".$RaumData->error."<br>\n";
	}
}

// RaumFläche ändern
$editRaumflaeche = getRequest("editRaumflaeche", "");
if ($editRaumflaeche) {
	$edit_raum_id = getRequest("raum", "");
	$editRaum["raumflaeche"] = getRequest("raumflaeche", "");
	
	if ($RaumData->setRaumflaecheById($edit_raum_id, $editRaum["raumflaeche"])) {
		$msg.= "Raumfläche wurde geändert!<br>\n";
	} else {
		$error.= "Raumfläche konnte nicht geändert werden!<br>\n";
		$error.= "Fehler: ".$RaumData->error."<br>\n";
	}
}

// Raum löschen
$deleteRaum = getRequest("deleteRaum", "");
if ($deleteRaum) {
	$backUpDir = $MConf["AppRoot"]."geloescht/";
	$del_raum_id = getRequest("raum", "");
	if ($del_raum_id) {
		$RaumData = new raumdata();
		$RaumData->error = "";
		if ($RaumData->exists($del_raum_id)) {
			if ($RaumData->isEmpty($del_raum_id)) {
				$sql = "SELECT * FROM `".$_TABLE["immobilien"]."` WHERE id = ".(int)$del_raum_id;
				$db->query("INSERT INTO `mm_stamm_immobilien_geloescht` ".$sql);
				$db->query_export_csv($sql, $backUpDir."geloeschte_raeume.csv", ";", "\"", "\"\"", true);
				if ($RaumData->delete($del_raum_id)) {
					$msg.= "Der Raumdatensatz wurde gelöscht!<br>\n";
				} else {
					$error.= "Fehler beim Löschen! ".$RaumData->error."<br>\n";
				}
			} else {
				$error.= "Raum kann nicht gelöscht werden! Dem Raum sind noch ".$RaumData->numMitarbeiter($del_raum_id)." Mitarbeiter zugeordnet!<br>\n";
			}
		} else {
			$error.= "Es existiert kein Raum mit der ID:".$del_raum_id."!<br>\n";
		}
	} else {
		$error.= "Fehlende RaumId für Löschvorgang!<br>\n";
	}
}

// RaumStatus aktualisieren
$editRaumstatus = getRequest("editRaumstatus", "");
if ($editRaumstatus) {
	$edit_raum_id = getRequest("raum", "");
	
	if ($RaumData->updateRaumstatusById($edit_raum_id)) {
		$msg.= "Raumstatus wurde geändert!<br>\n";
	} else {
		$error.= "Raumstatus konnte nicht aktualisiert werden!<br>\n";
		$error.= "Fehler: ".$RaumData->error."<br>\n";
	}
}


$first = true;
$formScript = "aBereiche = {";
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
echo "<script>\n".$formScript."</script>\n";

function getOptionsAbteilungen(&$aAbt, $bereich, $aid, $abk = "") {
	//id, bereich, abteilung, abteilungsname\n";
	$options = "";
	foreach($aAbt as $a) {
		if ($bereich && $a["bereich"] != $bereich) continue;
		$selected = ($a["id"] != $aid && $abk != $a["abteilung"]) ? "" : "selected=\"true\"";
		$options.= "<option value=\"".$a["id"]."\" $selected>".$a["abteilung"]."</option>\n";
	}
	//$options.= "<option>$aid=$abk</option>\n";
	return $options;
}

function getOptionsBereiche(&$aBereiche, $oe, $bereich) {
	//id 	bereich 	bereichsname 	bereichsleiter 	organisationseinheit
	$options = "";
	foreach($aBereiche as $b) {
		if (!isset($b["organisationseinheit"])) echo "b: <pre>".print_r($b,1)."</pre>";
		if ($oe && $b["organisationseinheit"] != $oe) continue;
		$selected = ($b["bereich"] != $bereich) ? "" : "selected=\"true\"";
		$options.= "<option value=\"".$b["bereich"]."\" $selected>".$b["bereich"]."</option>\n";
	}
	
	return $options;
}

function getBereichByAbteilung(&$aAbt, $abt) {
	foreach($aAbt as $a) if ($a["abteilung"] == $abt) return $a["bereich"];
	return "";
}

function getGFByBereich(&$aBereiche, $bereich) {
	foreach($aBereiche as $b) if ($b["bereich"] == $bereich) return $b["organisationseinheit"];
	return "";
}

$thisRaum = array();
$optionsOrte = "";
$optionsGebaeude = "";
$optionsEtagen = "";
$optionsRaeume = "";
$JSON_Gebaeude = "";
$aOrte = array();
$aGebaeude = array();
$aEtagen = array();
$aRaeume = array();
$defaultEtage = "";
$defaultRaum = "";
$sql = "SELECT stadtname, gebaeude, gebaeudename\n";
$sql.= " FROM `".$_TABLE["gebaeude"]."`\n";
$sql.= " ORDER BY stadtname, gebaeudename\n";
$rows = $db->query_rows($sql);
foreach($rows as $row) $aOrteGebaeude[$row["stadtname"]][$row["gebaeude"]] = $row["gebaeudename"];

$ort = "";
$gebaeude = "";
$etage = "";
$raum = getRequest("raum", "");
if ($raum) {
	$sql = "SELECT ort, gebaeude, etage, raumnr\n";
	$sql.= " FROM `".$_TABLE["immobilien"]."`\n";
	$sql.= " WHERE id = \"".MyDB::escape_string($raum)."\" \n";
	$sql.= " LIMIT 1\n";
	$row = $db->query_singlerow($sql);
	if (!empty($row) && !empty($row["ort"])) {
		$ort = $row["ort"];
		$gebaeude = $row["gebaeude"];
		$etage = $row["etage"];
	}
}
if (!$ort) $ort = getRequest("ort", key($aOrteGebaeude));

if ($ort && !empty($aOrteGebaeude[$ort])) list($defGebaeude, $defGebaeudename) = each($aOrteGebaeude[$ort]);
else $defGebaeude = "";
if (!$gebaeude) $gebaeude = getRequest("gebaeude", $defGebaeude);
if ($ort && $gebaeude) {
	$sql = "SELECT etage\n";
	$sql.= " FROM `".$_TABLE["immobilien"]."`\n";
	$sql.= " WHERE gebaeude LIKE \"".MyDB::escape_string($gebaeude)."\"\n";
	$sql.= " GROUP BY etage\n";
	$sql.= " ORDER BY etage\n";
	$aEtagen = $db->query_rows($sql);
	if (count($aEtagen)) $defaultEtage = $aEtagen[0]["etage"];
}
if (!$etage) $etage = getRequest("etage", $defaultEtage);

if ($ort && $gebaeude && $etage) {
	$sql = "SELECT id, raumnr\n";
	$sql.= " FROM `".$_TABLE["immobilien"]."`\n";
	$sql.= " WHERE gebaeude LIKE \"".MyDB::escape_string($gebaeude)."\" AND etage =\"".MyDB::escape_string($etage)."\"\n";
	$sql.= " GROUP BY id\n";
	$sql.= " ORDER BY raumnr\n";
	$aRaeume = $db->query_rows($sql);
	if (count($aRaeume)) $defaultRaum = $aRaeume[0]["id"];
	//echo "#".__LINE__." ".basename(__FILE__)." ".MyDB::error()."; sql:$sql <pre>".print_r($aRaeume,1)."</pre><br>\n";
}
if (!$raum) $raum = getRequest("raum", $defaultRaum);

if ($raum) {
	$sql = "SELECT *\n";
	$sql.= " FROM `".$_TABLE["immobilien"]."`\n";
	$sql.= " WHERE id =\"".(int)$raum."\"\n";
	$thisRaum = $db->query_singlerow($sql);
	
	if (!empty($thisRaum)) {
		$thisRaum["raum_typ_id"] = "";
		if ($thisRaum["raum_typ"]) {
			$sql = "SELECT id FROM `".$_TABLE["raumtypen"]."` WHERE `raumtyp` = \"".$db->escape($thisRaum["raum_typ"])."\" LIMIT 1";
			$row = $db->query_singlerow($sql);
			if (!empty($row["id"])) $thisRaum["raum_typ_id"] = $row["id"];
		}
	}
}

//echo "#".__LINE__." ".basename(__FILE__)." ort:$ort, gebaeude:$gebaeude, etage:$etage<br>\n";

foreach($aOrteGebaeude as $rOrt => $aGebaeude) {
	$selected = ($ort == $rOrt) ? "selected=\"true\"" : "";
	$optionsOrte.= "<option value=\"$rOrt\" $selected>$rOrt</option>\n";
	$JSON_Gebaeude.= ($JSON_Gebaeude?",\n":"")."\t\"".$rOrt."\":[";
	$first = true;
	foreach($aGebaeude as $k => $v) {
		$JSON_Gebaeude.= (!$first?",":"")."\"".addslashes($k)."\"";
		if ($first) $first = false;
		
		if ($rOrt == $ort) {
			$selected = ($k == $gebaeude) ? "selected=\"true\"" : "";
			$optionsGebaeude.= "<option value=\"".$k."\" $selected>".$v."</option>\n";
		}
	}
	$JSON_Gebaeude.= "]";
}

//echo print_r($aEtagen,1);
for($i = 0; $i < count($aEtagen); $i++) {
	$selected = ($etage == $aEtagen[$i]["etage"]) ? "selected=\"true\"" : "";
	$optionsEtagen.= "<option value=\"".$aEtagen[$i]["etage"]."\" $selected>".$aEtagen[$i]["etage"]."</option>\n";
}

//echo print_r($aRaeume,1);
for($i = 0; $i < count($aRaeume); $i++) {
	$selected = ($raum == $aRaeume[$i]["id"]) ? "selected=\"true\"" : "";
	$optionsRaeume.= "<option value=\"".$aRaeume[$i]["id"]."\" $selected>".$aRaeume[$i]["raumnr"]."</option>\n";
}

$MsgBox = "";
if ($error) $MsgBox.= "<div class=\"err\">".$error."</div>\n";
if ($msg) $MsgBox.= "<div class=\"msg\">".$msg."</div>\n";

echo "<script>var GebaeudeListe = {\n $JSON_Gebaeude };\n</script>";
$frmImoFilter = str_replace("{optionsOrte}", $optionsOrte, $frmImoFilter);
$frmImoFilter = str_replace("{optionsGebaeude}", $optionsGebaeude, $frmImoFilter);
$frmImoFilter = str_replace("{optionsEtage}", $optionsEtagen, $frmImoFilter);
$frmImoFilter = str_replace("{optionsRaeume}", $optionsRaeume, $frmImoFilter);
$frmImoFilter = str_replace("<!-- {Msg} -->", $MsgBox, $frmImoFilter);

echo $frmImoFilter;
$liste = "";

$mTbl = $_TABLE["mitarbeiter"];
$iTbl = $_TABLE["immobilien"];
$aTbl = $_TABLE["abteilungen"];
$bTbl = $_TABLE["hauptabteilungen"];

$sql = "select m.id, m.immobilien_raum_id, m.abteilungen_id, 
m.name, m.vorname, m.extern, m.extern_firma, m.ersthelfer, m.raeumungsbeauftragter, m.anmerkung, m.gf, m.bereich, m.abteilung,
i.ort, i.gebaeude, i.etage, i.raumnr,  m.arbeitsplatznr
FROM `$mTbl` m
LEFT JOIN `$iTbl` i ON (m.immobilien_raum_id = i.id)
LEFT JOIN `$aTbl` a ON (m.abteilungen_id = a.id)
LEFT JOIN `$bTbl` b ON (b.bereich = a.bereich)
WHERE i.id = \"".$db->escape($raum)."\" 
ORDER BY i.ort, i.etage, i.raumnr, m.name, m.vorname";
// Alte Where-Abfrage:  i.gebaeude = \"".$db->escape($gebaeude)."\" AND i.etage = \"".$db->escape($etage)."\" AND i.raum = \"".$db->escape($raum)."\"

$rows = $db->query_rows($sql);
if (!$db->error()) {
//echo "<pre>".print_r($rows[0],1)." rows:$rows; count(rows):".count($rows)." gettype(rows):".gettype($rows)."</pre>\n";

if (!empty($RowsError)) foreach($RowsError as $k => $vError) echo $vError."<hr/>\n";
if (count($rows)) {
	
	$GFAbkSelect = array();
	$GFData = new gf();
	foreach($GFData->aSelect as $k => $v) $GFAbkSelect[$k] = $k;
	$theadColTitles = "<tr><td>#</td><td>Anmkg</td><td>Löschen</td><td>Name</td><td>Vorname</td><td>AP-Nutzung</td><td>Firma (wenn ext.)</td><td>A-Nr.</td><td>GF</td><td>Bereich</td><td>Abtlg</td><td>E-Helfer</td><td>Räumgs-BA</td></tr>\n";
	if (!empty($rows)) for ($i = 0; $i < count($rows); $i++) {
		$e = $rows[$i];
		if ($e["abteilung"] && !$e["bereich"]) $e["bereich"] = getBereichByAbteilung($AbtlgData->aAbteilungen, $e["abteilung"]);
		if ($e["bereich"] && !$e["gf"]) $e["gf"] = getGFByBereich($BereichData->aListe, $e["bereich"]);
		
		$rowId = "row".$i;
		if ($i % 10 == 0) $liste.= "<thead>".$theadColTitles."</thead>\n";
		$liste.= "<tr class=\"".($i%2?'wz2':'wz1')." rowFirstInGroup\" id=\"{$rowId}i1\">\n";
			$RowNrStyle = (isset($RowsError[$e["id"]]) ? "color:#f00;" : (isset($RowsSaved[$e["id"]]) ? "color:#008000;" : ""));
			$liste.= "<td class=\"cellInput\" style=\"text-align:right;font-weight:bold;$RowNrStyle\">$i</td>\n";
			$liste.= "<td class=\"cellInput\"><div onclick=\"TC('{$rowId}i2', 'rowHide', '')\" xonmouseover=\"alert(O+'; '+'anmerkung[$i]: '+O('anmerkung[$i]'))\" class=\"jLink\">Anmkg</div></td>\n";
			$liste.= "<td class=\"cellInput\"><div onclick=\"dropMa('".$e["id"]."', '{$rowId}'); \" class=\"jLink\">Löschen</div></td>\n";
			$liste.= "<td class=\"cellInput\">".get_InputText("name[$i]", $e["name"])."</td>\n";
			$liste.= "<td class=\"cellInput\">".get_InputText("vorname[$i]", $e["vorname"])."</td>\n";
			$liste.= "<td class=\"cellInput\">".get_SelectBox("extern[$i]", $e["extern"], array("Staff", "Extern", "Funktionsarbeitsplatz", "Flex-Position", "Spare"), false, "onchange=\"checkListExternFirma($i)\" default=\"".$e["extern"]."\"")."</td>\n";
			$liste.= "<td class=\"cellInput\">".get_InputText("extern_firma[$i]", $e["extern_firma"])."</td>\n";
			$liste.= "<td class=\"cellInput\">".get_InputText("arbeitsplatznr[$i]", $e["arbeitsplatznr"], "maxlength=3 size=4")."</td>\n";
			
			//$liste.= "<td>".get_SelectBox("imo_raum_id[$i]", $e["immobilien_raum_id"], array($e["immobilien_raum_id"]=>$e["ort"]." | Etg:".$e["etage"]." | R:".$e["raumnr"]." | m²:".$e["groesse_qm"]), true, "")."</td>\n";
			$liste.= get_InputHidden("imo_raum_id[$i]", $e["immobilien_raum_id"]);
			
			//$liste.= "<td class=\"cellInput\">".get_SelectBox("abteilungs_kategorie[$i]", $e["abteilungs_kategorie"], array('GF'=>'GF','Bereich'=>'Bereich','Abteilung'=>'Abtlg',''=>'N.N.'), true, "onchange=\"checkListAbteilungsAuswahl($i)\"")."</td>\n";
			$liste.= "<td class=\"cellInput\">".get_SelectBox("gf[$i]", $e["gf"], $GFAbkSelect, true, "onchange=\"reloadListSelectBereiche($i)\"")."</td>\n";
			
			$liste.= "<td class=\"cellInput\"><select name=\"bereich[$i]\" onchange=\"reloadListSelectAbteilungen($i)\"><option></option>\n".getOptionsBereiche($BereichData->aListe, $e["gf"], $e["bereich"])."</select></td>\n";
			$liste.= "<td class=\"cellInput\"><select name=\"abteilungen_id[$i]\"><option></option>\n".getOptionsAbteilungen($AbtlgData->aAbteilungen, $e["bereich"], $e["abteilungen_id"], $e["abteilung"])."</select></td>\n";
			//$liste.= "<td class=\"cellInput\">".get_SelectBox("abteilungen_id[$i]", $e["abteilungen_id"], array($e["abteilungen_id"]=>$e["abteilung"]), true)."</td>\n";
			
			$liste.= "<td class=\"cellInput\">".get_InputCheckBox("ersthelfer[$i]", $e["ersthelfer"], array("Ja"=>"E.H."), true)."</td>\n";
			$liste.= "<td class=\"cellInput\">".get_InputCheckBox("raeumungsbeauftragter[$i]", $e["raeumungsbeauftragter"], array("Ja"=>"R.B."), true)."</td>\n";
			$liste.= "</tr>\n";
		$liste.= "<tr class=\"".($i%2?'wz2':'wz1')." rowInfoLine rowHide\" id=\"{$rowId}i2\"><td colspan=15 class=\"cellInputLast\">".get_TextArea("anmerkung[$i]", $e["anmerkung"])."</td></tr>\n";
		$liste.= "<input type=\"hidden\" name=\"id[$i]\" value=\"".fb_htmlEntities($e["id"])."\">\n";
		$liste.= "<input type=\"hidden\" name=\"rownr[$i]\" value=\"".fb_htmlEntities($rowId)."\">\n";
	}
	echo "<form action=\"bestandsaufnahme.php\" name=\"frmListe\" method=\"post\" style=\"display:inline;\">\n";
	echo "<input class=\"iSave\" type=\"submit\" name=\"save\" value=\"Arbeitsplatzdaten aktualisieren\" style=\"margin:15px 0 0 0;\">\n";
	echo "<table class=tblList style=\"margin-top:0;\">$liste</table>\n";
	if (count($rows) > 20) echo "<input class=\"iSave\" type=\"submit\" name=\"save\" value=\"Speichern\">\n";
	echo "<input type=\"hidden\" name=\"raum\" value=\"".fb_htmlEntities($raum)."\">\n";
	echo "<input type=\"hidden\" name=\"etage\" value=\"".fb_htmlEntities($etage)."\">\n";
	echo "<input type=\"hidden\" name=\"gebaeude\" value=\"".fb_htmlEntities($gebaeude)."\">\n";
	echo "<input type=\"hidden\" name=\"ort\" value=\"".fb_htmlEntities($ort)."\">\n";
	echo "</form>";
	}
} else {
	echo $db->error();
}

if (!empty($thisRaum)) {
	if ($gebaeude) {
		$sql = "SELECT * FROM `".$_TABLE["gebaeude"]."` WHERE gebaeude LIKE \"".$gebaeude."\" LIMIT 1";
		$thisGebaeude = $db->query_singlerow($sql);
	}
	if (empty($thisGebaeude)) $thisGebaeude["adresse"] = "";
	
	echo "<table class=tblList cellpadding=0 cellspacing=0 border=1 style=\"width:350px;margin-top:20px;\">\n";
	echo "<thead><tr><td colspan=2>Raum Detailinfos:</td></tr></thead>\n";
	echo "<tbody>\n";
	echo "<tr class=\"wz1\"><td style=\"padding-right:10px;text-align:right;font-size:11px;font-family:Arial,sans-serif;\">Adresse</td><td style=\"padding-left:5px;font-size:11px;font-family:Arial,sans-serif;\">".$thisGebaeude["adresse"]."</td></tr>\n";
	$wz = 1;
	foreach($thisRaum as $k => $v) {
		if ($k == "raumart" || $k == "groesse_qm" || $k == "raum_typ_id") continue;
		$wz = ($wz!=1)?1:2;
		echo "<tr class=\"wz{$wz}\"><td style=\"padding-right:10px;text-align:right;font-size:11px;font-family:Arial,sans-serif;\">$k</td><td style=\"padding-left:5px;font-size:11px;font-family:Arial,sans-serif;\">$v</td></tr>\n";
	}
	echo "</tbody></table></div>\n";
	
	echo "<div><form method=\"get\" action=\"".basename($_SERVER["PHP_SELF"])."\" style=\"display:inline;margin:0;\">\n";
	echo "<input type=\"submit\" name=\"editRaumstatus\" value=\"Status aktualisieren: Aufgenommen am\" style=\"width:350px;font-size:11px;color:#008000;\">\n";
	echo "<input type=\"hidden\" name=\"raum\" value=\"".fb_htmlEntities($thisRaum["id"])."\">\n";
	echo "<input type=\"hidden\" name=\"etage\" value=\"".fb_htmlEntities($thisRaum["etage"])."\">\n";
	echo "<input type=\"hidden\" name=\"gebaeude\" value=\"".fb_htmlEntities($thisRaum["gebaeude"])."\">\n";
	echo "<input type=\"hidden\" name=\"ort\" value=\"".fb_htmlEntities($thisRaum["ort"])."\">\n";
	echo "</form></div>\n";
	
	echo "<div><form method=\"post\" action=\"".basename($_SERVER["PHP_SELF"])."\" style=\"display:inline;margin:0;\"><select name=\"raum_typ_id\" style=\"font-size:12px;width:200px;\">\n";
		foreach($RaumData->aRaumTypen as $optgroup => $childs) {
			$raumTypOptions.= "<optgroup label=\"$optgroup\">\n";
			foreach($childs as $chid => $chtxt) {
				$selected = ($thisRaum["raum_typ_id"] == $chid) ? "selected=\"true\"" : "";
				$raumTypOptions.= "<option value=\"$chid\" $selected thisRaumTyp=\"".$thisRaum["raum_typ"]."\">$chtxt</option>\n";
			}
			$raumTypOptions.= "</optgroup>\n";
		}
	echo $raumTypOptions;
	echo "</select>";
	echo "<input type=\"submit\" name=\"editRaumtyp\" value=\"Raumtyp ändern\" style=\"width:150px;font-size:11px;\">\n";
	echo "<input type=\"hidden\" name=\"raum\" value=\"".fb_htmlEntities($thisRaum["id"])."\">\n";
	echo "<input type=\"hidden\" name=\"etage\" value=\"".fb_htmlEntities($thisRaum["etage"])."\">\n";
	echo "<input type=\"hidden\" name=\"gebaeude\" value=\"".fb_htmlEntities($thisRaum["gebaeude"])."\">\n";
	echo "<input type=\"hidden\" name=\"ort\" value=\"".fb_htmlEntities($thisRaum["ort"])."\">\n";
	echo "</form></div>\n";
	
	echo "<div><form method=\"post\" action=\"".basename($_SERVER["PHP_SELF"])."\" style=\"display:inline;margin:0;\">\n";
	echo "<input type=\"text\" name=\"raumnr\" value=\"".fb_htmlEntities($thisRaum["raumnr"])."\" style=\"width:200px;font-size:11px;\">";
	echo "<input type=\"submit\" name=\"editRaumnr\" value=\"Raumnr ändern\" style=\"width:150px;font-size:11px;\">\n";
	echo "<input type=\"hidden\" name=\"raum\" value=\"".fb_htmlEntities($thisRaum["id"])."\">\n";
	echo "<input type=\"hidden\" name=\"etage\" value=\"".fb_htmlEntities($thisRaum["etage"])."\">\n";
	echo "<input type=\"hidden\" name=\"gebaeude\" value=\"".fb_htmlEntities($thisRaum["gebaeude"])."\">\n";
	echo "<input type=\"hidden\" name=\"ort\" value=\"".fb_htmlEntities($thisRaum["ort"])."\">\n";
	echo "</form></div>\n";
	
	echo "<div><form method=\"post\" action=\"".basename($_SERVER["PHP_SELF"])."\" style=\"display:inline;margin:0;\">\n";
	echo "<input type=\"text\" name=\"raumflaeche\" value=\"".fb_htmlEntities(str_replace(".", ",",$thisRaum["raum_flaeche"]))."\" style=\"width:200px;font-size:11px;\">";
	echo "<input type=\"submit\" name=\"editRaumflaeche\" value=\"Raumfläche ändern\" style=\"width:150px;font-size:11px;\">\n";
	echo "<input type=\"hidden\" name=\"raum\" value=\"".fb_htmlEntities($thisRaum["id"])."\">\n";
	echo "<input type=\"hidden\" name=\"etage\" value=\"".fb_htmlEntities($thisRaum["etage"])."\">\n";
	echo "<input type=\"hidden\" name=\"gebaeude\" value=\"".fb_htmlEntities($thisRaum["gebaeude"])."\">\n";
	echo "<input type=\"hidden\" name=\"ort\" value=\"".fb_htmlEntities($thisRaum["ort"])."\">\n";
	echo "</form></div>\n";
	
	echo "<div><form method=\"get\" action=\"".basename($_SERVER["PHP_SELF"])."\" style=\"display:inline;margin:0;\">\n";
	echo "<input type=\"submit\" name=\"deleteRaum\" value=\"Raum löschen\" style=\"width:350px;font-size:11px;color:#f00;\">\n";
	echo "<input type=\"hidden\" name=\"raum\" value=\"".fb_htmlEntities($thisRaum["id"])."\">\n";
	echo "<input type=\"hidden\" name=\"etage\" value=\"".fb_htmlEntities($thisRaum["etage"])."\">\n";
	echo "<input type=\"hidden\" name=\"gebaeude\" value=\"".fb_htmlEntities($thisRaum["gebaeude"])."\">\n";
	echo "<input type=\"hidden\" name=\"ort\" value=\"".fb_htmlEntities($thisRaum["ort"])."\">\n";
	echo "</form></div>\n";
}
$body_content = ob_get_contents();
ob_end_clean();
if (basename(__FILE__)==basename($_SERVER["PHP_SELF"])) {
	echo $body_content;
} else {
?>
<!-- 
<div onclick="reloadAllListSelectAbteilungen()">reloadAllListSelectAbteilungen</div>
<div onclick="reloadAllListSelectBereiche()">reloadAllListSelectBereiche</div> -->
</body>
</html>
<?php } ?>
