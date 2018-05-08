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

var Orte = false;
var Gebaeude = false;
var optionsOrte = false;
var optionsGebaeude = false;

var OrteIndex = false;
var GebaeudeIndex = false;

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

function nebenleistung_add_attachement(nid) {
	if (typeof(winUp)=="object" && typeof(winUp.closed)!="undefined" && !winUp.closed) winUp.close();
	winUp = window.open("./sites/nebenleistung_add_attachement.php?nid="+escape(nid),"winUp","width=400,height=400,scrollbars=yes,status=yes");
	winUp.focus();
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
	SBConfXF["Multiple"] = false;
	
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
	SBConfXF["Multiple"] = false;
	
	var SBBoxId = "SBItems";
	var SBBox = getcreateDivBoxById(SBBoxId, "SelBoxEasy SelBoxEtagen");
	//alert(typeof(SBBox)+" "+SBBox);
	if (typeof(SBBox)=="object" && typeof(SBBox.SBConf)=="object" && SBBox.SBConf["InputField"]==obj && SBBox.captureEvents) return true;
	var Placeholder = new Array();
	if (typeof(SBBox.SBConf)=="object" && SBBox.captureEvents) SelBox_release(SBBox);
	
	
	SelBox_capture(SBBox, SBConfXF, Placeholder);
	dockBox(obj, SBBox);
	
	//var ortePreselect = document.forms["frmSuche"].elements["q[ort]"].value;
	var ortePreselect = getFormElementValue("eingabe[standort]", "frmInput");
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
