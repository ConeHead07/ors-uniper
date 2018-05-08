var SBConfDefault = new Array();
SBConfDefault["InputField"] = "",
SBConfDefault["InputFrontTrunc"] = false,
SBConfDefault["OnInput"] = ""; // function(parentbox)
SBConfDefault["OnSelect"] = ""; // function(parentbox)
SBConfDefault["OnRelease"] = ""; // function(parentbox)
SBConfDefault["OnSelectClass"] = "IsHoverItem",
SBConfDefault["OnEnterClose"] = true;
SBConfDefault["MaxHeight"] = 200;
SBConfDefault["InputType"] = "";

var optionsUmzugsarten = [
	{value:"BOX", content:"Box-Move"},
	{value:"MOEBEL", content:"Mit Möbel"}
];

var optionsNachnamen = false;
var optionsMaByName = false;
var optionsMaByGER = false; // Version 1, veraltet
var Orte = false;
var Gebaeude = false;
var optionsOrte = false;
var optionsGebaeude = false;
var optionsRaeume = false;
var GF = false;
var BereicheByGF = false;
var AbteilungenByBe = false;
var optionsExterneFirmen = false;

var KtgIndex = false;
var OrteIndex = false;
var GebaeudeIndex = false;
var EtagenIndex = false;
var RaumnrIndex = false;
var GFIndex = false;
var BereicheIndex = false;
var AbteilungIndex = false;
var optionsExterneFirmen = false;

addEvent(window, "load", init_suchformular);

function init_suchformular() {
	removeAutocompleteAll();
}

function qFelderLeeren() {
	if (!document.forms["frmSuche"]) return false;
	for (var i = 0; i < document.forms["frmSuche"].elements.length; i++) {
		if (document.forms["frmSuche"].elements[i].type == "text")
			document.forms["frmSuche"].elements[i].value = "";
	}
}

function removeAutocomplete() {
	var InputFields = document.getElementsByTagName("input");
	for(var i = 0; InputFields.length; i++) {
		if (InputFields[i].type=="text") InputFields[i].setAttribute("autocomplete", "off");
	}
}

function getCopyOfArray(a) {
	if (typeof(a)!="object") return false;
	var b = new Array();
	var i, j;
	for(i in a) {
		if (typeof(a[i])!="object") b[i] = a[i];
		else { b[i] = new Array(); for(j in a[i]) b[i][j] = a[i][j]; }
	}
	return b;
	
}

function getcreateDivBoxById(DivBoxId, DivClassName) {
	if (typeof(DivBoxId)!="string") return false;
	if (typeof(O(DivBoxId))!="object" || O(DivBoxId) == null) {
		var SelBox = document.createElement("div");
		SelBox.id = DivBoxId;
		document.body.appendChild(SelBox);
	}
	if (O(DivBoxId) && typeof(DivClassName)=="string") O(DivBoxId).className = DivClassName;
	return O(DivBoxId);
}

function get_fieldindex_ort(obj) {
	if (typeof(O(obj))!="object") return false;
	obj.hasFocus = true;
	
	obj = O(obj);
	var SBConfXF = getCopyOfArray(SBConfDefault);
	SBConfXF["InputField"] = obj;
	SBConfXF["Multiple"] = true;
	SBConfXF["MultipleSeparator"] = ",";
	
	var SBBoxId = "SBItems";
	var SBBox = getcreateDivBoxById(SBBoxId, "SelBoxEasy SelBoxEtagen");
	//alert(typeof(SBBox)+" "+SBBox);
	if (typeof(SBBox)=="object" && typeof(SBBox.SBConf)=="object" && SBBox.SBConf["InputField"]==obj && SBBox.captureEvents) return true;
	//var Placeholder = new Array();
	var Placeholder = (typeof(OrteIndex)=="object") ? OrteIndex : new Array();
	if (typeof(SBBox.SBConf)=="object" && SBBox.captureEvents) SelBox_release(SBBox);
	
	
	SelBox_capture(SBBox, SBConfXF, Placeholder);
	dockBox(obj, SBBox);
	if (!Placeholder.length) request_fieldindex_ort(SBBoxId);
	
	//alert(InputFields["gebaeude"].value+" "+InputFields["etage"].value);
}

function request_fieldindex_ort(SBBoxId) {
	igWShowLoadingBar(1, "Auswahl wird geladen!", SBBoxId);
	AjaxRequestUrl = "load_orte_index.php?";
	AjaxRequestUrl+= '&SBBoxId='+escape(SBBoxId);
	AjaxRequestUrl+= '&resultFormat=XML';
	//alert(AjaxRequestUrl);
	fb_AjaxRequest(AjaxRequestUrl, 'get', "fb_AjaxXmlUpdate(%req%)");
}

function get_fieldindex_gebaeude(obj) {
	if (typeof(O(obj))!="object") return false;
	obj.hasFocus = true;
	
	obj = O(obj);
	var SBConfXF = getCopyOfArray(SBConfDefault);
	SBConfXF["InputField"] = obj;
	SBConfXF["Multiple"] = true;
	SBConfXF["MultipleSeparator"] = ",";
	
	var SBBoxId = "SBItems";
	var SBBox = getcreateDivBoxById(SBBoxId, "SelBoxEasy SelBoxEtagen");
	//alert(typeof(SBBox)+" "+SBBox);
	if (typeof(SBBox)=="object" && typeof(SBBox.SBConf)=="object" && SBBox.SBConf["InputField"]==obj && SBBox.captureEvents) return true;
	var Placeholder = new Array();
	if (typeof(SBBox.SBConf)=="object" && SBBox.captureEvents) SelBox_release(SBBox);
	
	
	SelBox_capture(SBBox, SBConfXF, Placeholder);
	dockBox(obj, SBBox);
	
	var ortePreselect = document.forms["frmSuche"].elements["q[ort]"].value;
	request_fieldindex_gebaeude(SBBoxId, ortePreselect);
	
	//alert(InputFields["gebaeude"].value+" "+InputFields["etage"].value);
}

function request_fieldindex_gebaeude(SBBoxId, ortePreselect) {
	igWShowLoadingBar(1, "Auswahl wird geladen!", SBBoxId);
	AjaxRequestUrl = "load_gebaeude_index_suchformular.php?";
	AjaxRequestUrl+= '&orte='+escape(ortePreselect);
	AjaxRequestUrl+= '&SBBoxId='+escape(SBBoxId);
	AjaxRequestUrl+= '&resultFormat=XML';
	//alert(AjaxRequestUrl);
	fb_AjaxRequest(AjaxRequestUrl, 'get', "fb_AjaxXmlUpdate(%req%)");
}

function get_fieldindex_etage(obj) {
	if (typeof(O(obj))!="object") return false;
	obj.hasFocus = true;
	
	obj = O(obj);
	var SBConfXF = getCopyOfArray(SBConfDefault);
	SBConfXF["InputField"] = obj;
	SBConfXF["Multiple"] = true;
	SBConfXF["MultipleSeparator"] = ",";
	
	var SBBoxId = "SBItems";
	var SBBox = getcreateDivBoxById(SBBoxId, "SelBoxEasy SelBoxEtagen");
	//alert(typeof(SBBox)+" "+SBBox);
	if (typeof(SBBox)=="object" && typeof(SBBox.SBConf)=="object" && SBBox.SBConf["InputField"]==obj && SBBox.captureEvents) return true;
	var Placeholder = new Array();
	if (typeof(SBBox.SBConf)=="object" && SBBox.captureEvents) SelBox_release(SBBox);
	
	
	SelBox_capture(SBBox, SBConfXF, Placeholder);
	dockBox(obj, SBBox);
	
	
	var ortePreselect = document.forms["frmSuche"].elements["q[ort]"].value;
	var gebaeudePreselect = document.forms["frmSuche"].elements["q[gebaeude]"].value;
	request_fieldindex_etage(SBBoxId, ortePreselect, gebaeudePreselect);
	
	//alert(InputFields["gebaeude"].value+" "+InputFields["etage"].value);
}
function request_fieldindex_etage(SBBoxId, ortePreselect, gebaeudePreselect) {
	igWShowLoadingBar(1, "Auswahl wird geladen!", SBBoxId);
	AjaxRequestUrl = "load_etage_index_suchformular.php?";
	AjaxRequestUrl+= '&orte='+escape(ortePreselect);
	AjaxRequestUrl+= '&gebaeude='+escape(gebaeudePreselect);
	AjaxRequestUrl+= '&SBBoxId='+escape(SBBoxId);
	AjaxRequestUrl+= '&resultFormat=XML';
	//alert(AjaxRequestUrl);
	fb_AjaxRequest(AjaxRequestUrl, 'get', "fb_AjaxXmlUpdate(%req%)");
}

function get_fieldindex_raumnr(obj) {
	if (typeof(O(obj))!="object") return false;
	obj.hasFocus = true;
	
	obj = O(obj);
	var SBConfXF = getCopyOfArray(SBConfDefault);
	SBConfXF["InputField"] = obj;
	SBConfXF["Multiple"] = true;
	SBConfXF["MultipleSeparator"] = ",";
	
	var SBBoxId = "SBItems";
	var SBBox = getcreateDivBoxById(SBBoxId, "SelBoxEasy SelBoxEtagen");
	//alert(typeof(SBBox)+" "+SBBox);
	if (typeof(SBBox)=="object" && typeof(SBBox.SBConf)=="object" && SBBox.SBConf["InputField"]==obj && SBBox.captureEvents) return true;
	var Placeholder = new Array();
	if (typeof(SBBox.SBConf)=="object" && SBBox.captureEvents) SelBox_release(SBBox);
	
	
	SelBox_capture(SBBox, SBConfXF, Placeholder);
	dockBox(obj, SBBox);
	
	var ortePreselect = document.forms["frmSuche"].elements["q[ort]"].value;
	var gebaeudePreselect = document.forms["frmSuche"].elements["q[gebaeude]"].value;
	var etagenPreselect = document.forms["frmSuche"].elements["q[etage]"].value;
	request_fieldindex_raumnr(SBBoxId, ortePreselect, gebaeudePreselect, etagenPreselect);
	
	//alert(InputFields["gebaeude"].value+" "+InputFields["etage"].value);
}
function request_fieldindex_raumnr(SBBoxId, ortePreselect, gebaeudePreselect, etagenPreselect) {
	igWShowLoadingBar(1, "Auswahl wird geladen!", SBBoxId);
	AjaxRequestUrl = "load_raumnr_index_suchformular.php?";
	AjaxRequestUrl+= '&orte='+escape(ortePreselect);
	AjaxRequestUrl+= '&gebaeude='+escape(gebaeudePreselect);
	AjaxRequestUrl+= '&etagen='+escape(etagenPreselect);
	AjaxRequestUrl+= '&SBBoxId='+escape(SBBoxId);
	AjaxRequestUrl+= '&resultFormat=XML';
	//alert(AjaxRequestUrl);
	fb_AjaxRequest(AjaxRequestUrl, 'get', "fb_AjaxXmlUpdate(%req%)");
}

function get_fieldindex_gf(obj) {
	if (typeof(O(obj))!="object") return false;
	obj.hasFocus = true;
	
	obj = O(obj);
	var SBConfXF = getCopyOfArray(SBConfDefault);
	SBConfXF["InputField"] = obj;
	SBConfXF["Multiple"] = true;
	SBConfXF["MultipleSeparator"] = ",";
	
	var SBBoxId = "SBItems";
	var SBBox = getcreateDivBoxById(SBBoxId, "SelBoxEasy SelBoxEtagen");
	//alert(typeof(SBBox)+" "+SBBox);
	if (typeof(SBBox)=="object" && typeof(SBBox.SBConf)=="object" && SBBox.SBConf["InputField"]==obj && SBBox.captureEvents) return true;
	var Placeholder = (typeof(GFIndex)=="object") ? GFIndex : new Array();
	if (typeof(SBBox.SBConf)=="object" && SBBox.captureEvents) SelBox_release(SBBox);
	if (typeof(optionsExterneFirmen)=="object" && optionsExterneFirmen.length) Placeholder = optionsExterneFirmen;
	
	
	SelBox_capture(SBBox, SBConfXF, Placeholder);
	dockBox(obj, SBBox);
	if (!Placeholder.length) request_fieldindex_gf(SBBoxId);
	
	//alert(InputFields["gebaeude"].value+" "+InputFields["etage"].value);
}
function request_fieldindex_gf(SBBoxId) {
	igWShowLoadingBar(1, "Auswahl wird geladen!", SBBoxId);
	AjaxRequestUrl = "load_gf_index_suchformular.php?";
	AjaxRequestUrl+= '&SBBoxId='+escape(SBBoxId);
	AjaxRequestUrl+= '&resultFormat=XML';
	//alert(AjaxRequestUrl);
	fb_AjaxRequest(AjaxRequestUrl, 'get', "fb_AjaxXmlUpdate(%req%)");
}

function get_fieldindex_bereich(obj) {
	if (typeof(O(obj))!="object") return false;
	obj.hasFocus = true;
	
	obj = O(obj);
	var SBConfXF = getCopyOfArray(SBConfDefault);
	SBConfXF["InputField"] = obj;
	SBConfXF["Multiple"] = true;
	SBConfXF["MultipleSeparator"] = ",";
	
	var SBBoxId = "SBItems";
	var SBBox = getcreateDivBoxById(SBBoxId, "SelBoxEasy SelBoxEtagen");
	//alert(typeof(SBBox)+" "+SBBox);
	if (typeof(SBBox)=="object" && typeof(SBBox.SBConf)=="object" && SBBox.SBConf["InputField"]==obj && SBBox.captureEvents) return true;
	var Placeholder = new Array();
	if (typeof(SBBox.SBConf)=="object" && SBBox.captureEvents) SelBox_release(SBBox);
	if (typeof(optionsExterneFirmen)=="object" && optionsExterneFirmen.length) Placeholder = optionsExterneFirmen;
	
	
	SelBox_capture(SBBox, SBConfXF, Placeholder);
	dockBox(obj, SBBox);
	var gfPreselect = document.forms["frmSuche"].elements["q[gf]"].value;
	request_fieldindex_bereich(SBBoxId, gfPreselect);
	
	//alert(InputFields["gebaeude"].value+" "+InputFields["etage"].value);
}
function request_fieldindex_bereich(SBBoxId, gfPreselect) {
	igWShowLoadingBar(1, "Auswahl wird geladen!", SBBoxId);
	AjaxRequestUrl = "load_bereich_index_suchformular.php?";
	AjaxRequestUrl+= '&gf='+escape(gfPreselect);
	AjaxRequestUrl+= '&SBBoxId='+escape(SBBoxId);
	AjaxRequestUrl+= '&resultFormat=XML';
	//alert(AjaxRequestUrl);
	fb_AjaxRequest(AjaxRequestUrl, 'get', "fb_AjaxXmlUpdate(%req%)");
}

function get_fieldindex_abteilung(obj) {
	if (typeof(O(obj))!="object") return false;
	obj.hasFocus = true;
	
	obj = O(obj);
	var SBConfXF = getCopyOfArray(SBConfDefault);
	SBConfXF["InputField"] = obj;
	SBConfXF["Multiple"] = true;
	SBConfXF["MultipleSeparator"] = ",";
	
	var SBBoxId = "SBItems";
	var SBBox = getcreateDivBoxById(SBBoxId, "SelBoxEasy SelBoxEtagen");
	//alert(typeof(SBBox)+" "+SBBox);
	if (typeof(SBBox)=="object" && typeof(SBBox.SBConf)=="object" && SBBox.SBConf["InputField"]==obj && SBBox.captureEvents) return true;
	var Placeholder = new Array();
	if (typeof(SBBox.SBConf)=="object" && SBBox.captureEvents) SelBox_release(SBBox);
	if (typeof(optionsExterneFirmen)=="object" && optionsExterneFirmen.length) Placeholder = optionsExterneFirmen;
	
	
	SelBox_capture(SBBox, SBConfXF, Placeholder);
	dockBox(obj, SBBox);
	var gfPreselect = document.forms["frmSuche"].elements["q[gf]"].value;
	var bereichePreselect = document.forms["frmSuche"].elements["q[bereich]"].value;
	request_fieldindex_abteilung(SBBoxId, gfPreselect, bereichePreselect)
	
	//alert(InputFields["gebaeude"].value+" "+InputFields["etage"].value);
}
function request_fieldindex_abteilung(SBBoxId, gfPreselect, bereichePreselect) {
	igWShowLoadingBar(1, "Auswahl wird geladen!", SBBoxId);
	AjaxRequestUrl = "load_abteilung_index_suchformular.php?";
	AjaxRequestUrl+= '&gf='+escape(gfPreselect);
	AjaxRequestUrl+= '&bereiche='+escape(bereichePreselect);
	AjaxRequestUrl+= '&SBBoxId='+escape(SBBoxId);
	AjaxRequestUrl+= '&resultFormat=XML';
	//alert(AjaxRequestUrl);
	fb_AjaxRequest(AjaxRequestUrl, 'get', "fb_AjaxXmlUpdate(%req%)");
}

	
function get_fieldindex_extern_firma(obj) {
	if (typeof(O(obj))!="object") return false;
	obj.hasFocus = true;
	
	obj = O(obj);
	var SBConfXF = getCopyOfArray(SBConfDefault);
	SBConfXF["InputField"] = obj;
	SBConfXF["Multiple"] = true;
	SBConfXF["MultipleSeparator"] = ",";
	
	var SBBoxId = "SBItems";
	var SBBox = getcreateDivBoxById(SBBoxId, "SelBoxEasy SelBoxEtagen");
	//alert(typeof(SBBox)+" "+SBBox);
	if (typeof(SBBox)=="object" && typeof(SBBox.SBConf)=="object" && SBBox.SBConf["InputField"]==obj && SBBox.captureEvents) return true;
	var Placeholder = new Array();
	if (typeof(SBBox.SBConf)=="object" && SBBox.captureEvents) SelBox_release(SBBox);
	if (typeof(optionsExterneFirmen)=="object" && optionsExterneFirmen.length) Placeholder = optionsExterneFirmen;
	
	
	SelBox_capture(SBBox, SBConfXF, Placeholder);
	dockBox(obj, SBBox);
	if (!Placeholder.length) request_query_xf(SBBoxId);
	
	//alert(InputFields["gebaeude"].value+" "+InputFields["etage"].value);
}

function request_query_xf(SBBoxId) {
	igWShowLoadingBar(1, "Externe Firmen werden geladen!", SBBoxId);
	AjaxRequestUrl = "load_externefirmen_index.php?";
	AjaxRequestUrl+= '&SBBoxId='+escape(SBBoxId);
	AjaxRequestUrl+= '&resultFormat=XML';
	//alert(AjaxRequestUrl);
	fb_AjaxRequest(AjaxRequestUrl, 'get', "fb_AjaxXmlUpdate(%req%)");
}

function get_fieldindex_nutzung(obj) {
	if (typeof(O(obj))!="object") return false;
	obj.hasFocus = true;
	
	obj = O(obj);
	var SBConfXF = getCopyOfArray(SBConfDefault);
	SBConfXF["InputField"] = obj;
	SBConfXF["Multiple"] = true;
	SBConfXF["MultipleSeparator"] = ",";
	
	var optionsData = [
		"Staff",
		"Extern",
		"Funktionsarbeitsplatz",
		"Flex-Position",
		"Spare"
	];
	
	var SBBoxId = "SBItems";
	var SBBox = getcreateDivBoxById(SBBoxId, "SelBoxEasy SelBoxEtagen");
	//alert(typeof(SBBox)+" "+SBBox);
	if (typeof(SBBox)=="object" && typeof(SBBox.SBConf)=="object" && SBBox.SBConf["InputField"]==obj && SBBox.captureEvents) return true;
	if (typeof(SBBox.SBConf)=="object" && SBBox.captureEvents) SelBox_release(SBBox);	
	
	SelBox_capture(SBBox, SBConfXF, optionsData);
	dockBox(obj, SBBox);
	
	//alert(InputFields["gebaeude"].value+" "+InputFields["etage"].value);
}

function get_fieldindex_mitarbeiter(obj) {
	if (typeof(O(obj))!="object") return false;
	
	var SBConfMa = getCopyOfArray(SBConfDefault);
	SBConfMa["InputField"] = obj;
	SBConfMa["OnInput"] = mitarbeiter_fieldindex_check_reload;
	SBConfMa["OnEnterClose"] = true;
	SBConfMa["Multiple"] = true;
	SBConfMa["MultipleSeparator"] = ",";
		
	var SBBoxId = "SBItems";
	var Placeholder = new Array();
	var SBBox = getcreateDivBoxById(SBBoxId, "SelBoxEasy SelBoxMitarbeiter");
	if (typeof(SBBox.SBConf)=="object" && SBBox.SBConf["InputField"]==obj && SBBox.captureEvents) return true;
	if (typeof(SBBox.SBConf)=="object" && SBBox.captureEvents) SelBox_release(SBBox);
	
	SelBox_capture(SBBox, SBConfMa, Placeholder);
	dockBox(obj, SBBox);
	
	mitarbeiter_fieldindex_check_reload(SBBox);
}


function request_fieldindex_nachname(SBBoxId, input, limit) {
	igWShowLoadingBar(1, "Mitarbeiter werden geladen!", SBBoxId);
	AjaxRequestUrl = "load_nachnamen_index_suchformular.php?";
	AjaxRequestUrl+= '&input='+escape(input);
	AjaxRequestUrl+= '&limit='+escape(limit);
	AjaxRequestUrl+= '&SBBoxId='+escape(SBBoxId);
	AjaxRequestUrl+= '&resultFormat=XML';
	//alert(AjaxRequestUrl);
	fb_AjaxRequest(AjaxRequestUrl, 'get', "fb_AjaxXmlUpdate(%req%)");
}

function mitarbeiter_fieldindex_check_reload(parentbox) {
	if (!parentbox || typeof(O(parentbox))!="object") return false;
	parentbox = O(parentbox);
	var send_query = false;
	var limit = 10;
	
	//alert("#657 "+parentbox.SBConf["InputType"]);
	var lastQuery = {Input:"",Size:-1,NumAll:-1};
	
	var splittedValues = parentbox.SBConf["InputField"].value.split(parentbox.SBConf["MultipleSeparator"]);
	var lastValue = splittedValues[splittedValues.length-1];
	while(lastValue.substr(0, 1) == " ") lastValue = lastValue.substr(1);
	while(lastValue.substr(lastValue.length-1) == " ") lastValue = lastValue.substr(0,lastValue.length-1);
		
	if (lastValue.length) {
		
		if (typeof(optionsNachnamen)=="object") {
			if (typeof(optionsNachnamen["Query"])=="string") lastQuery["Input"] = optionsNachnamen["Query"];
			if (typeof(optionsNachnamen["NumAll"])=="number") lastQuery["NumAll"] = optionsNachnamen["NumAll"];
			if (typeof(optionsNachnamen["Size"])=="number") lastQuery["Size"] = optionsNachnamen["Size"];
		}
		
		if (!lastQuery["Input"] || lastQuery["NumAll"] > limit) send_query = 1;
		if (typeof(optionsNachnamen)!="object" || !optionsNachnamen["Data"].length) send_query = 2;
		else if(lastQuery["Input"].length > lastValue.length) send_query = 3;
		else if(lastValue.toUpperCase().indexOf(lastQuery["Input"].toUpperCase())!=0) send_query = 4;
		
		////document.getElementsByTagName("textarea")[0].value = "send_query:"+send_query+" Input:"+parentbox.SBConf["InputField"].value+"\n";
		//for(i in lastQuery) //document.getElementsByTagName("textarea")[0].value+= i+": "+lastQuery[i]+"\t";
		
		if (send_query) {
		
			var CBoxLoading = document.createElement("span");
			CBoxLoading.style.display="block";
			CBoxLoading.className = "SelBoxItem";
			CBoxLoading.innerHTML = "<em style=\"italic\">Daten werden geladen</em>";
			CBoxLoading.onclick = function() { mitarbeiter_switchData(parentbox, "Nachname"); }
			CBoxLoading.onmouseover = function() { AC(this, "IsHoverItem"); }
			CBoxLoading.onmouseout = function() { RC(this, "IsHoverItem"); }
			SelBox_addControlBox(parentbox, CBoxLoading);
			
			request_fieldindex_nachname(parentbox.id, lastValue, 10);
		}
	}
}


function get_fieldindex_raumkategorie(obj) {
	if (typeof(O(obj))!="object") return false;
	obj.hasFocus = true;
	
	obj = O(obj);
	var SBConfXF = getCopyOfArray(SBConfDefault);
	SBConfXF["InputField"] = obj;
	SBConfXF["Multiple"] = true;
	SBConfXF["MultipleSeparator"] = ",";
	SBConfXF["InputSrc"] = "value";
	
	var SBBoxId = "SBItems";
	var SBBox = getcreateDivBoxById(SBBoxId, "SelBoxEasy SelBoxEtagen");
	//alert(typeof(SBBox)+" "+SBBox);
	if (typeof(SBBox)=="object" && typeof(SBBox.SBConf)=="object" && SBBox.SBConf["InputField"]==obj && SBBox.captureEvents) return true;
	//var Placeholder = new Array();
	var Placeholder = (typeof(KtgIndex)=="object") ? KtgIndex : new Array();
	if (typeof(SBBox.SBConf)=="object" && SBBox.captureEvents) SelBox_release(SBBox);
	
	
	SelBox_capture(SBBox, SBConfXF, Placeholder);
	dockBox(obj, SBBox);
	if (!Placeholder.length) request_fieldindex_raumkategorie(SBBoxId);
	
	//alert(InputFields["gebaeude"].value+" "+InputFields["etage"].value);
}

function request_fieldindex_raumkategorie(SBBoxId) {
	igWShowLoadingBar(1, "Auswahl wird geladen!", SBBoxId);
	AjaxRequestUrl = "load_raumkategorie_index.php?";
	AjaxRequestUrl+= '&SBBoxId='+escape(SBBoxId);
	AjaxRequestUrl+= '&resultFormat=XML';
	//alert(AjaxRequestUrl);
	fb_AjaxRequest(AjaxRequestUrl, 'get', "fb_AjaxXmlUpdate(%req%)");
}

function get_fieldindex_raumtyp(obj) {
	if (typeof(O(obj))!="object") return false;
	obj.hasFocus = true;
	
	obj = O(obj);
	var SBConfXF = getCopyOfArray(SBConfDefault);
	SBConfXF["InputField"] = obj;
	SBConfXF["Multiple"] = true;
	SBConfXF["MultipleSeparator"] = ",";
	SBConfXF["InputSrc"] = "value";
	
	var SBBoxId = "SBItems";
	var SBBox = getcreateDivBoxById(SBBoxId, "SelBoxEasy SelBoxEtagen");
	//alert(typeof(SBBox)+" "+SBBox);
	if (typeof(SBBox)=="object" && typeof(SBBox.SBConf)=="object" && SBBox.SBConf["InputField"]==obj && SBBox.captureEvents) return true;
	var Placeholder = new Array();
	if (typeof(SBBox.SBConf)=="object" && SBBox.captureEvents) SelBox_release(SBBox);
	
	
	SelBox_capture(SBBox, SBConfXF, Placeholder);
	dockBox(obj, SBBox);
	
	var ktgPreselect = document.forms["frmSuche"].elements["q[raum_kategorie]"].value;
	request_fieldindex_raumtyp(SBBoxId, ktgPreselect);
	
	//alert(InputFields["gebaeude"].value+" "+InputFields["etage"].value);
}

function request_fieldindex_raumtyp(SBBoxId, ktgPreselect) {
	igWShowLoadingBar(1, "Auswahl wird geladen!", SBBoxId);
	AjaxRequestUrl = "load_raumtyp_index_suchformular.php?";
	AjaxRequestUrl+= '&ktg='+escape(ktgPreselect);
	AjaxRequestUrl+= '&SBBoxId='+escape(SBBoxId);
	AjaxRequestUrl+= '&resultFormat=XML';
	//alert(AjaxRequestUrl);
	fb_AjaxRequest(AjaxRequestUrl, 'get', "fb_AjaxXmlUpdate(%req%)");
}




