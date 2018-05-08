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

addEvent(window, "load", init_nebenleistungsantrag);
function init_nebenleistungsantrag() {
	if (!document.forms["frmUmzugsantrag"]) return false;
	document.forms["frmUmzugsantrag"].elements["NL[standort]"].setAttribute("autocomplete","off");
	document.forms["frmUmzugsantrag"].elements["NL[gebaeude]"].setAttribute("autocomplete","off");
}

function umzugsantrag_loadingBar(msg) {
	if (typeof(O("LoadingBar"))=="object") O("LoadingBar").parentNode.removeChild(O("LoadingBar"));
	if (!msg) msg = "Daten werden übertragen ...";
	var LoadingBar = "<div id=\"LoadingBar\" style=\"text-align:center;\">"+msg+"<br>\n<img src=\"images/loading.gif\"></div>";
	var IBox = InfoBox(LoadingBar);
	return IBox;
}

function getNamedArrayOfForm(frm) {
	if (typeof(frm)!="object") {
		if (document.forms[frm]) frm = document.forms[frm];
		else if (document.getElementById(frm) && document.getElementById(frm).tagName.toUpperCase()=="FORM") frm = document.getElementById(frm);
	}
	if (typeof(frm)!="object" || !frm.elements) return false;
	var namedArray = new Array();
	var name = "";
	for(var i = 0; i < frm.elements.length; i++) {
		
		if (!frm.elements[i].name) continue;
		if (frm.elements[i].name.lastIndexOf("[]")==frm.elements[i].name.length-2) {
			
			name = frm.elements[i].name.substr(0, frm.elements[i].name.lastIndexOf("[]"));
			//alert("#83 "+frm.elements[i].name+" => "+name);
			if (typeof(namedArray[name])!="object") namedArray[name] = new Array();
			namedArray[name][namedArray[name].length] = frm.elements[i];
		} else {
			namedArray[frm.elements[i].name] = frm.elements[i];
		}
		
	}
	return namedArray;
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

function getOffBox(obj) {
	var OffBoxId = "OffBox";
	var OffBox = O(OffBoxId);
	if (!OffBox || typeof(OffBox)!="object") {
		OffBox = document.createElement("div");
		OffBox.id = OffBoxId;
		with(OffBox.style) { position = "absolute"; left=0; top=0; width="100%"; height="100%"; }
	}
	OffBox.style.top = PageInfo.getElementTop(obj)+"px";
	if (obj.style.index > 1) OffBox.style.index = obj.style.index-1;
	else {
		obj.style.index = 100;
		OffBox.style.index = 90;
	}
	return OffBox;
}

function getcreateDivBoxById(DivBoxId, DivClassName) {
	if (typeof(DivBoxId)!="string") return false;
	if (typeof(O(DivBoxId))!="object") {
		var SelBox = document.createElement("div");
		SelBox.id = DivBoxId;
		document.body.appendChild(SelBox);
	}
	if (O(DivBoxId) && typeof(DivClassName)=="string") O(DivBoxId).className = DivClassName;
	return O(DivBoxId);
}

function ort_getSelection(parentbox) {
	if (!parentbox || typeof(O(parentbox))!="object") return false;
	SubItem = SelBox_getSelectedItem(parentbox);
	if (typeof(SubItem)=="object") {
		parentbox.SBConf["InputField"].value = SubItem.content;
		SelBox_release(parentbox, true);
		if (document.getElementsByName("NL[gebaeude]")) {
			//alert("#125");
			document.getElementsByName("NL[gebaeude]")[0].value = "";
			//document.getElementsByName("NL[gebaeude]")[0].click();
			get_fieldindex_gebaeude(document.getElementsByName("NL[gebaeude]")[0]);
		}
		return false;
	}
	return true
}
function get_fieldindex_ort(obj) {
	if (typeof(O(obj))!="object") return false;
	obj.hasFocus = true;
	
	obj = O(obj);
	var SBConfXF = getCopyOfArray(SBConfDefault);
	SBConfXF["InputField"] = obj;
	SBConfXF["OnSelect"] = ort_getSelection;
	
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
	
	var SBBoxId = "SBItems";
	var SBBox = getcreateDivBoxById(SBBoxId, "SelBoxEasy SelBoxEtagen");
	//alert(typeof(SBBox)+" "+SBBox);
	if (typeof(SBBox)=="object" && typeof(SBBox.SBConf)=="object" && SBBox.SBConf["InputField"]==obj && SBBox.captureEvents) return true;
	var Placeholder = new Array();
	if (typeof(SBBox.SBConf)=="object" && SBBox.captureEvents) SelBox_release(SBBox);
	
	
	SelBox_capture(SBBox, SBConfXF, Placeholder);
	dockBox(obj, SBBox);
	
	var ortePreselect = document.forms["frmUmzugsantrag"].elements["NL[standort]"].value;
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

function load_optionsOrte() {
	if (typeof(Orte)!="object") return false;
	optionsOrte = new Array();
	var i;
	var j=0;
	for(i in Orte) {
		optionsOrte[j++] = {value:i, content:i};
	}
	return true;
}

function load_optionsAllegebaeude() {
	if (typeof(Gebaeude)!="object") return false;
	optionsAlleGebaeude = new Array();
	var i;
	var j=0;
	for(i in Gebaeude) {
		optionsAlleGebaeude[j++] = {value:i, content:i+" "+Gebaeude[i]["Adresse"]};
	}
	return true;
}

function load_optionsGebaeudeByOrt(ort) {
	if (typeof(Orte)!="object" || !Orte[ort]) return false;
	optionsGebaeudeByOrt = new Array();
	var i;
	var j=0;
	for(i in Orte[ort]) {
		optionsGebaeudeByOrt[j++] = {value:i, content:i+" "+Gebaeude[i]["Adresse"]};
	}
	return true;
}

function load_optionsEtagenByGebaeude(gebaeude) {
	if (typeof(Orte)!="object" || !Orte[ort]) return false;
	optionsGebaeudeByOrt = new Array();
	var i;
	var j=0;
	for(i in Orte[ort]) {
		optionsGebaeudeByOrt[j++] = {value:i, content:i+" "+Gebaeude[i]["Adresse"]};
	}
	return true;
}
