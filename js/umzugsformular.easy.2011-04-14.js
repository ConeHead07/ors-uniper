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

var optionsFon = [
	{"value":"Kein", content:"Kein Telefon"}
];
var optionsPcnr = [
	{"value":"Kein", content:"Kein PC"}
];

var optionsNachnamen = false;
var optionsMaByName = false;
var optionsMaByGER = false; // Version 1, veraltet
var optionsApnrByGER = false;
var Orte = false;
var Gebaeude = false;
var optionsOrte = false;
var optionsGebaeude = false;
var optionsRaeume = false;
var GF = false;
var BereicheByGF = false;
var AbteilungenByBe = false;
var optionsExterneFirmen = false;

addEvent(window, "load", init_umzugsantrag);
addEvent(window, "load", load_index_orte_gebaeude_etagen);
addEvent(window, "load", load_index_gf_bereiche);
addEvent(window, "load", load_index_abteilungen);

function init_umzugsantrag() {
	if (!document.forms["frmUmzugsantrag"]) return false;
	document.forms["frmUmzugsantrag"].onsubmit = function() { return false; };
	//if (typeof(O("terminwunsch"))=="object") addEvent(O("terminwunsch"), "click", function() { showDtPicker(O("terminwunsch")); });
	removeAutocompleteAll();
}

function umzugsantrag_id() {
	if (document.forms["frmUmzugsantrag"].elements["AS[aid]"]) {
		return document.forms["frmUmzugsantrag"].elements["AS[aid]"].value;
	}
	return false;
}
function umzugsformular_set_id(id) {
	if (!document.forms["frmUmzugsantrag"]) return false;
	if (document.forms["frmUmzugsantrag"].elements["AS[aid]"]) {
		document.forms["frmUmzugsantrag"].elements["AS[aid]"].value = id;
		return true;
	}
	return false;
}

function umzugsantrag_errors(AntragSenden) {
	if (!AntragSenden) AntragSenden = false;
        if (!document.forms["frmUmzugsantrag"]) return false;
	var namedArray = getNamedArrayOfForm(document.forms["frmUmzugsantrag"]);
	var error = "";
	var ma = "";
	var InputFields = false;
	var as_err="";
	var ma_err="";
	
	if (namedArray["AS[umzugstermin]"] && namedArray["AS[umzugstermin]"].value=="") 
		error+= "Fehlende Eingabe: "+(as_err?", ":"")+"Umzugstermin<br>\n";
	
	if (namedArray["AS[name]"]) {
		if (namedArray["AS[name]"].value=="") as_err+= ""+(as_err?", ":"")+"Name\n";
		if (!namedArray["AS[vorname]"] || namedArray["AS[vorname]"].value=="") as_err+= ""+(as_err?", ":"")+"Name\n";
		if (!namedArray["AS[fon]"] || namedArray["AS[fon]"].value=="") as_err+= ""+(as_err?", ":"")+"Fon\n";
		if (!namedArray["AS[ort]"] || namedArray["AS[ort]"].value=="") as_err+= ""+(as_err?", ":"")+"Standort\n";
		if (!namedArray["AS[gebaeude]"] || namedArray["AS[gebaeude]"].value=="") as_err+= ""+(as_err?", ":"")+"Gebaeude\n";
		if (!namedArray["AS[email]"] || namedArray["AS[email]"].value=="") as_err+= ""+(as_err?", ":"")+"Email\n";
		if (!namedArray["AS[terminwunsch]"] || namedArray["AS[terminwunsch]"].value=="") as_err+= ""+(as_err?", ":"")+"Terminwunsch\n";
	}

	if (as_err) {
		error+= "<strong>Bitte vervollständigen Sie noch die Angaben zum Antragsteller:</strong><br>\n";
		error+= as_err+"<br>\n";
	}

	var ListMaErrors = "";
	if (namedArray["MA[name]"]) {
		
		for (var i = 0; i < namedArray["MA[name]"].length; i++) {
			InputFields = getUmzugsItemInputFields(namedArray["MA[name]"][i]);
			ma = "Mitarbeiter "+(i+1)+" "+InputFields["name"].value+" "+InputFields["vorname"].value+": ";
			ma_err = "";
			if (InputFields["name"].value=="") ma_err+= "Name";
			if (InputFields["vorname"].value=="") ma_err+= (ma_err?", ":"")+"Name";
			if (InputFields["vorname"].value=="") ma_err+= (ma_err?", ":"")+"vorname";
			
			if (InputFields["gebaeude"].value=="") ma_err+= (ma_err?", ":"")+"Gebaeude";
			if (InputFields["etage"].value=="") ma_err+= (ma_err?", ":"")+"Etage";
			
			if (InputFields["raumnr"].value=="") ma_err+= (ma_err?", ":"")+"Raumnr";
			else if (InputFields["apnr"].value=="" && InputFields["raumnr"].RaumTyp 
				&& InputFields["raumnr"].RaumTyp=="GBUE") ma_err+= (ma_err?", ":"")+"Arbeitsplatznr";
			
			if (InputFields["umzugsart"].value=="") ma_err+= (ma_err?", ":"")+"Umzugsart";
			if (InputFields["zgebaeude"].value=="") ma_err+= (ma_err?", ":"")+"Ziel-Gebaeude";
			if (InputFields["zetage"].value=="") ma_err+= (ma_err?", ":"")+"Ziel-Etage";
			
			if (InputFields["zraumnr"].value=="") ma_err+= (ma_err?", ":"")+"Ziel-Raumnr";
			else if (InputFields["zapnr"].value=="" && InputFields["zraumnr"].RaumTyp && InputFields["zraumnr"].RaumTyp=="GBUE") ma_err+= (ma_err?", ":"")+"Ziel-Arbeitsplatznr";

                        if (AntragSenden) {
                            //if (InputFields["fon"].value=="") ma_err+= (ma_err?", ":"")+"Fon";
                            //if (InputFields["pcnr"].value=="") ma_err+= (ma_err?", ":"")+"PC-Nr";
                        }
			if (ma_err) ListMaErrors+= "<li>"+ma+ma_err+"</li>\n";
		
		}
		if (ListMaErrors) error+= "Bitte vervollständigen Sie die fehlenden Angaben!<br>\n"+ListMaErrors;
	} else {
		error+= "<strong>Es wurden noch keine Mitarbeiter in die Umzugsliste eingetragen!</strong>";
	}
	return error;
}

function umzugsantrag_num_rows() {
	if (!document.forms["frmUmzugsantrag"]) return false;
	var num_rows = 0;
	for (var i = 0; i < document.forms["frmUmzugsantrag"].elements.length; i++) {
		if ("MA[name][]" == document.forms["frmUmzugsantrag"].elements[i].name) num_rows++;
	}
	return num_rows;
}

function umzugsantrag_send() {
	if (!document.forms["frmUmzugsantrag"]) return false;
	var namedArray = getNamedArrayOfForm(document.forms["frmUmzugsantrag"]);
        var AntragSenden = true;
	var error = umzugsantrag_errors(AntragSenden);
	var ma = "";
	var InputFields = false;
	
	if (namedArray["MA[name]"]) {
		for (var i = 0; i < namedArray["MA[name]"].length; i++) {
			InputFields = getUmzugsItemInputFields(namedArray["MA[name]"][i]);		
		}	
	}
	
	if (error) {
		error = "<h4 class=\"hdErr\" style=\"color:#f00;\">Achtung - Antrag konnte nicht gesendet werden!</h4>\n"+error;
		ErrorBox(error);
		return false;
	}
	
	document.forms["frmUmzugsantrag"].action = "umzugsantrag.php";
        frmSerializeGeraete();
	var sData = frmSerialize(document.forms["frmUmzugsantrag"]);
	var selector = "MyInfoBoxTxt";
	umzugsantrag_loadingBar('');
	//alert("Antrag wird zwischengespeichert!\n"+sData);
        frmSerializeGeraete();
	AjaxFormSend(document.forms["frmUmzugsantrag"], selector, "", "cmd=senden", "senden");
}
function umzugsantrag_submit_debug(cmd) {
	if (!document.forms["frmUmzugsantrag"]) return false;
	document.forms["frmUmzugsantrag"].action = "umzugsantrag.php?cmd="+cmd;
	document.forms["frmUmzugsantrag"].target = "_new";
	document.forms["frmUmzugsantrag"].submit();
}
function umzugsantrag_save_notsend() {
	id = umzugsantrag_id();
	//alert("Antrag #"+id+" wird zur Zwischenspeicherung abgeschickt!");
	document.forms["frmUmzugsantrag"].action = "umzugsantrag.php";
	var selector = "MyInfoBoxTxt";
	umzugsantrag_loadingBar('');
        frmSerializeGeraete();
	AjaxFormSend(document.forms["frmUmzugsantrag"], selector, "", "cmd=speichern_ohne_status&id="+id);
}
function umzugsantrag_save() {
	if (!document.forms["frmUmzugsantrag"]) return false;
	var error = umzugsantrag_errors();
	
	if (error) {
		InfoBox(error);
		return false;
	}
	
	id = umzugsantrag_id();
	//alert("Antrag #"+id+" wird zur Zwischenspeicherung abgeschickt!");
	document.forms["frmUmzugsantrag"].action = "umzugsantrag.php";
	var selector = "MyInfoBoxTxt";
	umzugsantrag_loadingBar('');
        frmSerializeGeraete();
	AjaxFormSend(document.forms["frmUmzugsantrag"], selector, "", "cmd=speichern&id="+id);
}

function umzugsantrag_storno() {
	if (!document.forms["frmUmzugsantrag"]) return false;
	id = umzugsantrag_id();
	//alert("Antrag mit der ID "+id+" wird storniert!");
	if (id) {
		document.forms["frmUmzugsantrag"].action = "umzugsantrag.php";
		var selector = "MyInfoBoxTxt";
		umzugsantrag_loadingBar('');
                frmSerializeGeraete();
		AjaxFormSend(document.forms["frmUmzugsantrag"], selector, "", "cmd=stornieren&id="+id);
	} else {
		umzugsantrag_clear();
	}
}
function umzugsantrag_loadingBar(msg) {
	if (typeof(O("LoadingBar"))=="object") O("LoadingBar").parentNode.removeChild(O("LoadingBar"));
	if (!msg) msg = "Daten werden übertragen ...";
	var LoadingBar = "<div id=\"LoadingBar\" style=\"text-align:center;\">"+msg+"<br>\n<img src=\"images/loading.gif\"></div>";
	var IBox = InfoBox(LoadingBar);
	return IBox;
}
function umzugsantrag_auto_reload(id) {
	if (!document.forms["frmUmzugsantrag"]) return false;
	var aid = (!arguments.length) ? umzugsantrag_id() : id;
	if (!aid) { InfoBox("Fehlende Antrags-Id! Wurde der Antrag bereits gespeichert?<br>\n"); return false; }
	//alert("Antrag wird neu geladen AID:"+aid+"!");
	document.forms["frmUmzugsantrag"].action = "umzugsantrag.php";
	var selector = "MyInfoBoxTxt";
        frmSerializeGeraete();
	AjaxFormSend(document.forms["frmUmzugsantrag"], selector, "", "cmd=autoreload&id="+escape(aid));
}
function umzugsantrag_reload(id) {
	if (!document.forms["frmUmzugsantrag"]) return false;
	var aid = (!arguments.length) ? umzugsantrag_id() : id;
	if (!aid) { InfoBox("Fehlende Antrags-Id! Wurde der Antrag bereits gespeichert?<br>\n"); return false; }
	//alert("Antrag wird neu geladen AID:"+aid+"!");
	document.forms["frmUmzugsantrag"].action = "umzugsantrag.php";
	var selector = "MyInfoBoxTxt";
	umzugsantrag_loadingBar('');
        frmSerializeGeraete();
	AjaxFormSend(document.forms["frmUmzugsantrag"], selector, "", "cmd=laden&id="+escape(aid));
}

function umzugsantrag_reload_status(id) {
	var aid = (!arguments.length) ? umzugsantrag_id() : id;
	if (!aid) { InfoBox("Fehlende Antrags-Id! Wurde der Antrag bereits gespeichert?<br>\n"); return false; }
	
	igWShowLoadingBar(1, "Raumnummern werden geladen!", SBBoxId);
	AjaxRequestUrl = "umzugsantrag.php?";
	AjaxRequestUrl+= '&cmd=status_laden'+escape(gebaeude);
	AjaxRequestUrl+= '&id='+escape(aid);
	AjaxRequestUrl+= '&resultFormat=XML';
	//alert(AjaxRequestUrl);
	fb_AjaxRequest(AjaxRequestUrl, 'get', "fb_AjaxXmlUpdate(%req%,'"+selector+"')");
}

function dbdate2de(dbdate) {
	var D = dbdate.split(" ");
	var de = "";
	if (D[0].indexOf("-")!=-1) {
		de = D[0].substr(0,10).split("-").reverse().join(".");
		if (D.length > 1 && D[1].indexOf(":")!=-1) de+= " "+D[1].substr(0, 5);
		return de;
	}
	return dbdate;
}

function umzugsantrag_load_status(ASData) {
	
	//var m = ""; for (i in ASData) m+= i+":"+ASData[i]+"\n"; alert(m);
	if (typeof(ASData)=="object") {
		
		if (typeof(ASData["geprueft"])=="string") {
			var GP = ASData["geprueft"];
			if (typeof(O("imgStatGepr"))=="object") O("imgStatGepr").src="images/status_"+GP.toLowerCase()+".png";
			if (typeof(O("txtStatGepr"))=="object") O("txtStatGepr").innerHTML = (GP!="Init") ? " "+GP+" am "+dbdate2de(ASData["geprueft_am"])+" "+ASData["geprueft_von"] : "";
			
			if (typeof(O("btnStatGeprJa"))=="object") {
				if (GP=="Ja") 	{ AC(O("btnStatGeprJa"), "cssHide"); RC(O("btnStatGeprReset"), "cssHide"); }
				else 			{ RC(O("btnStatGeprJa"), "cssHide"); AC(O("btnStatGeprReset"), "cssHide"); }
			}
		}
		if (typeof(ASData["genehmigt_br"])=="string") {
			var GN = ASData["genehmigt_br"];
			if (typeof(O("imgStatGen"))=="object") O("imgStatGen").src="images/status_"+GN.toLowerCase()+".png";
			if (typeof(O("txtStatGen"))=="object") O("txtStatGen").innerHTML = (GN!="Init") ? " "+GN+" am "+dbdate2de(ASData["genehmigt_br_am"])+" "+ASData["genehmigt_br_von"] : "";;
			
			if (typeof(O("btnStatGenJa"))=="object") {
				if (GN=="Ja") 		{ RC(O("btnStatGenReset"), "cssHide"); 	AC(O("btnStatGenJa"), "cssHide"); 	RC(O("btnStatGenNein"), "cssHide"); }
				else if(GN=="Nein") { RC(O("btnStatGenReset"), "cssHide"); 	RC(O("btnStatGenJa"), "cssHide"); 	AC(O("btnStatGenNein"), "cssHide"); }
				else 				{ RC(O("btnStatGenJa"), "cssHide"); 	AC(O("btnStatGenReset"), "cssHide");RC(O("btnStatGenNein"), "cssHide"); }
			}
		}
		if (typeof(ASData["bestaetigt"])=="string") {
			var BS = ASData["bestaetigt"];
			if (typeof(O("imgStatBest"))=="object") O("imgStatBest").src="images/status_"+BS.toLowerCase()+".png";
			if (typeof(O("txtStatBest"))=="object") O("txtStatBest").innerHTML = (BS!="Init") ? BS+" am "+dbdate2de(ASData["bestaetigt_am"])+" "+ASData["bestaetigt_von"] : "";
			
			if (typeof(O("btnStatBestJa"))=="object") {
				if (BS=="Ja") 	{ AC(O("btnStatBestJa"), "cssHide"); RC(O("btnStatBestReset"), "cssHide"); }
				else 			{ RC(O("btnStatBestJa"), "cssHide"); AC(O("btnStatBestReset"), "cssHide"); }
			}
		}
		
		if (typeof(ASData["abgeschlossen"])=="string") {
			var BS = ASData["abgeschlossen"];
			if (typeof(O("imgStatAbg"))=="object") O("imgStatAbg").src="images/status_"+BS.toLowerCase()+".png";
			if (typeof(O("txtStatAbg"))=="object") O("txtStatAbg").innerHTML = (BS!="Init") ? BS+" am "+dbdate2de(ASData["abgeschlossen_am"])+" "+ASData["abgeschlossen_von"] : "";
			
			if (typeof(O("btnStatAbgJa"))=="object") {
				if (BS=="Ja") 	{ AC(O("btnStatAbgJa"), "cssHide"); RC(O("btnStatAbgReset"), "cssHide"); }
				else 			{ RC(O("btnStatAbgJa"), "cssHide"); AC(O("btnStatAbgReset"), "cssHide"); }
			}
		}
	
	}
}

function umzugsantrag_load(ASData, MAData) {
	if (!document.forms["frmUmzugsantrag"]) return false;
	umzugsantrag_clear();
	//alert("Antrag wurde geleert und wird neu geladen AID:"+ASData["aid"]+"!");
	var i, j, MA, InputFields, maid;
	for(i in ASData) {
		if (document.forms["frmUmzugsantrag"].elements["AS["+i+"]"]) {
			if (i!="bemerkungen") document.forms["frmUmzugsantrag"].elements["AS["+i+"]"].value = ASData[i];
		}
	}
	document.forms["frmUmzugsantrag"].elements["AS[bemerkungen]"].value = ""
	if (typeof(O("BemerkungenHistorie"))=="object") {
		O("BemerkungenHistorie").innerHTML = ASData["bemerkungen"].split("\n").join("<br>");
	}
	//alert("Lade MA-Daten. MAData.length:"+MAData.length);
	for(i = 0; i < MAData.length; i++) {
		maid = (typeof(MAData[i]["maid"])!="undefined" && MAData[i]["maid"]) ? MAData[i]["maid"] : "";
		MA = addMa(maid?"Select":"Input");
		InputFields = getUmzugsItemInputFields(MA.getElementsByTagName("input")[0]);
		for(j in MAData[i]) {
			if (InputFields[j]) InputFields[j].value = MAData[i][j];
		}
		if (typeof(MAData[i]["critical_status_img"])=="string") {
			aImg = MA.getElementsByTagName("img");
			for (iImg = 0; iImg < aImg.length; iImg++) if (aImg[iImg].name=="RaumStatImg") { aImg[iImg].src="images/"+MAData[i]["critical_status_img"];break; }
			
			aTag = MA.getElementsByTagName("span");
			for (iTag = 0; iTag < aTag.length; iTag++) if (aTag[iTag].className=="RaumStatInfo") { aTag[iTag].innerHTML = MAData[i]["critical_status_info"];break; }
		}
	}
	umzugsantrag_load_status(ASData);
	return false;
}

function umzugsantrag_add_attachement() {
	var aid = umzugsantrag_id();
	if (typeof(winUp)=="object" && typeof(winUp.closed)!="undefined" && !winUp.closed) winUp.close();
	winUp = window.open("./sites/umzugsantrag_add_attachement.php?aid="+escape(aid),"winUp","width=400,height=400,scrollbars=yes,status=yes");
	winUp.focus();
}

function umzugsantrag_close(text) {
	if (typeof(O("Umzugsantrag"))=="object") {
		O("Umzugsantrag").innerHTML = "";
		if (text) {
			var objHtmlText = document.createElement("span");
			objHtmlText.innerHTML = text;
			O("Umzugsantrag").appendChild(objHtmlText);
		}
	}
	if (typeof(cluetipClose)=="function") cluetipClose();
	if (typeof(InfoBoxClose)=="function") InfoBoxClose();
}

function umzugsantrag_set_status(name, value) {
	if (!document.forms["frmUmzugsantrag"]) return false;
	var error = umzugsantrag_errors();
		
	if (error) {
		InfoBox(error);
		return false;
	}
	
	var aid = umzugsantrag_id();
	if (!aid) { InfoBox("Fehlende Antrags-Id! Wurde der Antrag bereits gespeichert?<br>\n"); return false; }
	
	//alert("Antrag wird neu geladen AID:"+aid+"!");
	document.forms["frmUmzugsantrag"].action = "umzugsantrag.php";
	var selector = "MyInfoBoxTxt";
	umzugsantrag_loadingBar('');
    frmSerializeGeraete();
	AjaxFormSend(document.forms["frmUmzugsantrag"], selector, "", "cmd=status&name="+escape(name)+"&value="+escape(value)+"&id="+escape(aid));
}

function umzugsantrag_clear() {
	if (typeof(O("mitarbeiterliste"))!="object") return false;
	if (typeof(document.forms["frmUmzugsantrag"])!="object") return false;
	
	O("mitarbeiterliste").innerHTML = "";
	if (document.forms["frmUmzugsantrag"].elements["AS[terminwunsch]"]) document.forms["frmUmzugsantrag"].elements["AS[terminwunsch]"].value="";
	/*
	var el;
	for(var i = 0; i < document.forms["frmUmzugsantrag"].elements.length; i++) {
		el = document.forms["frmUmzugsantrag"].elements[i];
		if (el.name.indexOf("AS[")==0) {
			switch(el.type) {
				case "radio":
				case "checkbox":
				el.checked = false;
				break;
				
				case "select":
				case "select-multiple":
				case "select-one":
				case "select-single":
				el.selectedIndex = -1
				for(k in el.options) el.options[k].selected = false;
				break;
				
				case "text":
				case "hidden":
				case "textarea":
				el.value = "";
			}
		}
	}
	*/
	return true;
}

function getParentNodeByTagName(obj, tagName) {
	if (typeof(O(obj))!="object") return false;
	var parentObj = O(obj).parentNode
	while(parentObj.tagName.toUpperCase() != tagName.toUpperCase() && typeof(parentObj.parentNode)=="object") parentObj = parentObj.parentNode;
	
	return parentObj;
	//return (typeof(parentObj)=="object" && parentObj.tagName.toUpperCase()==tagName.toUpperCase()) ? parentObj : null;
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

function getNextNodeByTagName(obj, tagName) {
	if (typeof(O(obj))!="object") return false;
	nextObj = O(obj).nextSibling;
	while(nextObj && (typeof(nextObj.tagName)!="string" || nextObj.tagName.toUpperCase()!=tagName.toUpperCase())) nextObj = nextObj.nextSibling;
	return nextObj;
	/**/
}

function getPrevNodeByTagName(obj, tagName) {
	if (typeof(O(obj))!="object") return false;
	previousObj = O(obj).previousSibling;
	while(previousObj && (typeof(previousObj.tagName)!="string" || previousObj.tagName.toUpperCase()!=tagName.toUpperCase())) previousObj = previousObj.previousSibling;
	return previousObj;
	/**/
}

function addInputFieldsToArray(dstArray, srcInputFields) {
	if (srcInputFields.name) srcInputFields = [srcInputFields];
	for(var i in srcInputFields) {
		
		if (typeof(srcInputFields[i])!="object" ||typeof(srcInputFields[i].type)!="string" || typeof(srcInputFields[i].name)!="string") continue;
		if (srcInputFields[i].type != "checkbox") dstArray[srcInputFields[i].name] = srcInputFields[i];
		else {
			if (typeof(dstArray[srcInputFields[i].name])!="object") dstArray[srcInputFields[i].name] = new Array();
			dstArray[srcInputFields[i].name][dstArray[srcInputFields[i].name].length] = srcInputFields[i];
		}
	}
	return dstArray;
}

function removeAutocomplete() {
	var InputFields = document.getElementsByTagName("input");
	for(var i = 0; InputFields.length; i++) {
		if (InputFields[i].type=="text") InputFields[i].setAttribute("autocomplete", "off");
	}
}

var MaxMA = 1;
function addMa(InputType) {
	if (InputType=="Select")
		var newMA = document.getElementById("MA_SELECT").cloneNode(true);
	else
		var newMA = document.getElementById("MA_INPUT").cloneNode(true);
	
	newMA.id = "MA"+(++MaxMA);
	var aInputFields = newMA.getElementsByTagName("input");
	//alert("#437 addMa("+InputType+")");
	for (var i = 0; i < aInputFields.length; i++) {
		switch(aInputFields[i].type) {
			case "text":
			case "textarea":
			case "hidden":
			aInputFields[i].value = "";
			if (aInputFields[i].defaultValue)
				aInputFields[i].value = aInputFields[i].defaultValue;
			break;
			
			case "select":
			case "select-one":
			case "select-multiple":
			case "select-single":
			aInputFields[i].selectedIndex=-1;
			break;
			
			case "radio":
			case "checkbox":
			aInputFields[i].checked=false;
		}
	}
	//document.getElementById("mitarbeiterliste").appendChild(document.createElement("br"));
	
	if (document.getElementById("mitarbeiterliste").firstChild && typeof(document.getElementById("mitarbeiterliste").firstChild)=="object")
		document.getElementById("mitarbeiterliste").insertBefore(newMA, document.getElementById("mitarbeiterliste").firstChild);
	else
		document.getElementById("mitarbeiterliste").appendChild(newMA);
	//alert("#464 addMa("+InputType+") newMA:"+newMA);
	return newMA;
}

function addMa_Datenpflege(InputType) {
	//return addMa(InputType);
	var newMA = addMa(InputType);
	var aInputFields = newMA.getElementsByTagName("input");
	
	for (var i = 0; i < aInputFields.length; i++) {
		if(aInputFields[i].name == "MA[umzugsart][]") {
			aInputFields[i].value = "Datenpflege";
			break;
		}
	}
	return newMA;
}

function dropMA(obj) {
	var MaId = "";
	if (obj.id && obj.id.indexOf("MA") == 0) {
		MaId = obj.id;
		obj.parentNode.removeChild(obj);
	} else {
		var checkObj = obj.parentNode;
		while(checkObj) {
			if (checkObj.id && checkObj.id.indexOf("MA") == 0) {
				checkObj.parentNode.removeChild(checkObj);
				return true;
				MaId = checkObj.id;
				break;
			}/**/
			checkObj = checkObj.parentNode;
		}
	}
	//if (MaId) igWNodeDelete(MaId);
}
function getUmzugsItemInputFields(obj) {
	if (typeof(O(obj))!="object") return false;
	var myNode = obj;
	
	
	var parentTbl = getParentNodeByTagName(obj, "TABLE");
	
	var InputFields = new Array();
	InputFields = addInputFieldsToArray(InputFields, parentTbl.getElementsByTagName("input"));
	InputFields = addInputFieldsToArray(InputFields, parentTbl.getElementsByTagName("select"));
	InputFields = addInputFieldsToArray(InputFields, parentTbl.getElementsByTagName("textarea"));
	
	m = "";
	for(i in InputFields) {
		if (i.indexOf("MA[")==0 && i.substr(i.lastIndexOf("][]"))=="][]") {
			shortName = i.substring(3, i.lastIndexOf("][]"));
			InputFields[shortName] = InputFields[i];
			m+= shortName+"\n";
		}
	}
	//alert("InputFields shortName:\n"+m);
	return InputFields;
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

function standort_ort_getSelection(parentbox) {
	if (!parentbox || typeof(O(parentbox))!="object") return false;
	var InputFields = getUmzugsItemInputFields(parentbox.SBConf["InputField"]);
	
	SubItem = SelBox_getSelectedItem(parentbox);
	if (typeof(SubItem)=="object") {
		parentbox.SBConf["InputField"].value = SubItem.value;
		SelBox_release(parentbox, true);
		if (typeof(InputFields["AS[gebaeude]"])=="object") {
			InputFields["AS[gebaeude]"].value = "";
			get_standort_gebaeude(InputFields["AS[gebaeude]"]);
		}
		return false;
	}
}

function get_standort_ort(obj) {
	if (!obj || typeof(O(obj))!="object") return false;
	obj = O(obj);
	obj.hasFocus = true;
	
	if (typeof(optionsOrte)!="object" || !optionsOrte.length) load_optionsOrte();
	
	var SBConfGebaeude = getCopyOfArray(SBConfDefault);
	
	SBConfGebaeude["InputField"] = obj;
	SBConfGebaeude["OnSelect"] = standort_ort_getSelection;
	
	var SBBox = getcreateDivBoxById("SBItems", "SelBoxEasy SelBoxOrte");
	
	if (typeof(SBBox.SBConf)!="object" || SBBox.SBConf["InputField"]!=obj || !SBBox.captureEvents) {
		if (typeof(SBBox.SBConf)=="object") SelBox_release(SBBox);
		SelBox_capture(SBBox, SBConfGebaeude, optionsOrte);
		dockBox(obj, SBBox);
		//addEvent(obj, "blur", function(e) { SBBox.SelBox_checkBlur(e); })
	}
	return true;
}

function standort_gebaeude_getSelection(parentbox) {
	if (!parentbox || typeof(O(parentbox))!="object") return false;
	var InputFields = getUmzugsItemInputFields(parentbox.SBConf["InputField"]);
	
	SubItem = SelBox_getSelectedItem(parentbox);
	if (typeof(SubItem)=="object") {
		parentbox.SBConf["InputField"].value = SubItem.value;
		return true;
	}
}
function get_standort_gebaeude(obj) {
	if (!obj || typeof(O(obj))!="object") return false;
	obj = O(obj);
	obj.hasFocus = true;
	var InputFields = getUmzugsItemInputFields(obj);
	var o = (InputFields && InputFields["AS[ort]"])?InputFields["AS[ort]"].value:"";
	var optionsGebaeude;
	
	if (o && load_optionsGebaeudeByOrt(o)) {
		optionsGebaeude = optionsGebaeudeByOrt;
	} else if (load_optionsAllegebaeude()) {
		optionsGebaeude = optionsAlleGebaeude;
	}
	
	var SBConfGebaeude = getCopyOfArray(SBConfDefault);
	SBConfGebaeude["InputField"] = obj;
	SBConfGebaeude["OnSelect"] = standort_gebaeude_getSelection;
	
	var SBBox = getcreateDivBoxById("SBItems", "SelBoxEasy SelBoxOrte");
	
	if (typeof(SBBox.SBConf)!="object" || SBBox.SBConf["InputField"]!=obj || !SBBox.captureEvents) {
		if (typeof(SBBox.SBConf)=="object") SelBox_release(SBBox);
		SelBox_capture(SBBox, SBConfGebaeude, optionsGebaeude);
		dockBox(obj, SBBox);
	}
	return true;
}

function gebaeude_checkInput(parentbox) {
	if (typeof(O(parentbox))!="object") return false;
	
	if (typeof(parentbox.lastInput)!="string") parentbox.lastInput = null;
	
	if (parentbox.lastInput == null) {
		alert("#404 userdefined_checkInput()");
	} else if (parentbox.lastInput.length > parentbox.SBConf["InputField"].value.length) {
		//alert("#406 userdefined_checkInput()");
	}
	parentbox.lastInput = parentbox.SBConf["InputField"].value;
	return true;
}

function gebaeude_switchData(parentbox, InputType) {
	//alert("#123 gebaeude_switchData!");
	if (typeof(parentbox)!="object" || typeof(parentbox.SBConf)!="object" || typeof(parentbox.SBConf["InputField"])!="object") return false;
	if (typeof(parentbox.SBConf["InputField"].Ort)!="string") InputType = "Orte";
	
	switch(InputType) {
		case "Orte":
		if (load_optionsOrte()) {
			SelBox_loadData(parentbox, optionsOrte);
			parentbox.SBConf["InputType"] = InputType;
		}
		break;
		
		case "Gebaeude":
		var o = parentbox.SBConf["InputField"].Ort;
		load_optionsGebaeudeByOrt(o);
		//alert("#139 gebaeude_switchData!\n"+optionsGebaeudeByOrt);
		parentbox.SBConf["InputType"] = InputType;
		
		var CBoxSwitch = document.createElement("span");
		CBoxSwitch.className = "SelBoxItem";
		CBoxSwitch.onmouseover = function () { AC(this, "IsHoverItem"); }
		CBoxSwitch.onmouseout = function () { RC(this, "IsHoverItem"); }
		CBoxSwitch.onclick = function() { gebaeude_switchData(parentbox, "Orte"); }
		CBoxSwitch.innerHTML = "<strong>"+parentbox.SBConf["InputField"].Ort+"</strong> <em style=\"font-style:italic\">anderer Ort</em>";
		/**/
		SelBox_loadData(parentbox, optionsGebaeudeByOrt);
		SelBox_addControlBox(parentbox, CBoxSwitch);
		break;
	}
}

function gebaeude_getSelection(parentbox) {
	if (typeof(O(parentbox))!="object") return false;
	
	var parentbox = O(parentbox);
	var SubItem = SelBox_getSelectedItem(parentbox);
	var z = (parentbox.SBConf["InputField"].name.indexOf("MA[z")==0)?"z":"";
	
	if (SubItem) {
		
		switch(parentbox.SBConf["InputType"]) {
			case "Orte":
			parentbox.SBConf["InputField"].Ort = SubItem.value;
			parentbox.SBConf["InputField"].finalValue = SubItem.value;
			gebaeude_switchData(parentbox, "Gebaeude");
			return false;
			break;
			
			case "Gebaeude":
			parentbox.SBConf["InputField"].value = SubItem.value;
			parentbox.SBConf["InputField"].finalValue = SubItem.value;
			var InputFields = getUmzugsItemInputFields(parentbox.SBConf["InputField"]);
			SelBox_release(parentbox);
			get_etage(InputFields[z+"etage"]);
			return false;
			break;
			
			default:
			alert("177 InValid InputType: "+InputType); 
		}
	} else {
		alert("180 No SubItem"); 
		parentbox.SBConf["InputField"].value = "";
	}
	return true;
}

function get_gebaeude(obj) {
	if (typeof(O(obj))!="object") return false;
	obj.hasFocus = true;
	
	obj = O(obj);
	load_optionsAllegebaeude();
	var InputFields = getUmzugsItemInputFields(obj);
	var SBConfGebaeude = getCopyOfArray(SBConfDefault);
	
	SBConfGebaeude["InputField"] = obj;
	SBConfGebaeude["OnInput"] = false; //gebaeude_checkInput;
	SBConfGebaeude["OnSelect"] = gebaeude_getSelection;
	SBConfGebaeude["OnHover"] = false; //gebaeude_getHover;
	SBConfGebaeude["OnCapture"] = false; //gebaeude_openSBGBox;
	SBConfGebaeude["OnRelease"] = false; //gebaeude_closeSBGBox;
	SBConfGebaeude["InputType"] = "Gebaeude";
	SBConfGebaeude["OnEnterClose"] = false;
	
	
	if (typeof(obj.Ort)=="undefined") obj.Ort = "";
	
	var listCat = (!obj.Ort) ? "Orte" : "Gebaeude";
	
	var SBBox = getcreateDivBoxById("SBItems", "SelBoxEasy SelBoxGebaeude");
	var Placeholder = new Array();
	
	if (typeof(SBBox.SBConf)!="object" || SBBox.SBConf["InputField"]!=obj || !SBBox.captureEvents) {
		if (typeof(SBBox.SBConf)=="object") SelBox_release(SBBox);
		SelBox_capture(SBBox, SBConfGebaeude, Placeholder);
		dockBox(obj, SBBox);
		gebaeude_switchData(SBBox, listCat);
		//addEvent(obj, "blur", function(e) { SBBox.SelBox_checkBlur(e); })
	}
	return true;
}

function etage_getSelection(parentbox) {
	if (typeof(O(parentbox))!="object") return false;
	//alert("#290 "+parentbox.SBConf["OnSelect"]);
	var parentbox = O(parentbox);
	var SubItem = SelBox_getSelectedItem(parentbox);
	if (SubItem) {
		parentbox.SBConf["InputField"].value = SubItem.value;
		var InputFields = getUmzugsItemInputFields(parentbox.SBConf["InputField"]);
		var z = (parentbox.SBConf["InputField"].name.indexOf("MA[z")==0)?"z":"";
		SelBox_release(parentbox);
		get_raumnr(InputFields[z+"raumnr"]);
		return false;
	} else {
		parentbox.SBConf["InputField"].value = "";
	}
	return true;
}

function get_etage(obj) {
	if (typeof(O(obj))!="object") return false;
	obj.hasFocus = true;
	
	obj = O(obj);
	var InputFields = getUmzugsItemInputFields(obj);
	var SBConfEtagen = getCopyOfArray(SBConfDefault);
	var z = (obj.name.indexOf("MA[z")==0)?"z":"";
	SBConfEtagen["InputField"] = obj;
	SBConfEtagen["OnInput"] = false; //etage_checkInput;
	SBConfEtagen["OnSelect"] = etage_getSelection;
	SBConfEtagen["OnHover"] = false; //etage_getHover;
	SBConfEtagen["InputType"] = "Etagen";
	SBConfEtagen["OnEnterClose"] = true;
	
	if (!InputFields[z+"gebaeude"].value) { 
		alert("Wählen Sie erst ein Gebäude aus!");
		get_gebaeude(InputFields[z+"gebaeude"]);
		return false; 
	}
	
	if (load_optionsEtagenByGebaeude(InputFields[z+"gebaeude"].value)) {
		var SBBoxId = "SBItems";
		var SBBox = getcreateDivBoxById(SBBoxId, "SelBoxEasy SelBoxEtagen");
		if (typeof(SBBox.SBConf)=="object" && SBBox.SBConf["InputField"]==obj && SBBox.captureEvents) return true;
		//addEvent(obj, "blur", function(e) { SBBox.SelBox_checkBlur(e); })
		
		if (typeof(SBBox.SBConf)=="object" && SBBox.captureEvents) SelBox_release(SBBox);
		SelBox_capture(SBBox, SBConfEtagen, optionsEtagenByGebaeude);
		dockBox(obj, SBBox);
	} else {
		EtageInputObj = obj;
		setTimeout("get_etage(EtageInputObj)", 1000);
	}
	//alert(InputFields["gebaeude"].value);
}

function raumnr_getSelection(parentbox) {
	if (typeof(O(parentbox))!="object") return false;
	//alert("#290 "+parentbox.SBConf["OnSelect"]);
	var parentbox = O(parentbox);
	var SubItem = SelBox_getSelectedItem(parentbox);
	var obj = parentbox.SBConf["InputField"];
	if (SubItem) {
		//alert(SubItem.RaumTyp);RaumTyp
    var z = (obj.name.indexOf("MA[z")==0)?"z":"";
		parentbox.SBConf["InputField"].RaumTyp = SubItem.RaumTyp;
		parentbox.SBConf["InputField"].value = SubItem.value;
		var InputFields = getUmzugsItemInputFields(parentbox.SBConf["InputField"]);
		var z = (parentbox.SBConf["InputField"].name.indexOf("MA[z")==0)?"z":"";
		SelBox_release(parentbox);
		
		if (SubItem.RaumTyp == "GBUE") {
			get_apnr(InputFields[z+"apnr"]);
		} else {
		  InputFields[z+"apnr"].value = "";
		}
		return false;
	} else {
		parentbox.SBConf["InputField"].value = "";
	}
	return true;
}

function get_raumnr(obj) {
	if (typeof(O(obj))!="object") return false;
	obj.hasFocus = true;
	
	obj = O(obj);
	var InputFields = getUmzugsItemInputFields(obj);
	var SBConfRaumnr = getCopyOfArray(SBConfDefault);
	var z = (obj.name.indexOf("MA[z")==0)?"z":"";
	SBConfRaumnr["InputField"] = obj;
	SBConfRaumnr["OnInput"] = false; //etage_checkInput;
	SBConfRaumnr["OnSelect"] = raumnr_getSelection;
	SBConfRaumnr["OnHover"] = false; //etage_getHover;
	SBConfRaumnr["InputType"] = "Raumnr";
	SBConfRaumnr["OnEnterClose"] = true;
	
	if (!InputFields[z+"gebaeude"].value) { alert("Wählen Sie erst ein Gebäude aus!"); get_gebaeude(InputFields[z+"gebaeude"]); return false; }
	if (!InputFields[z+"etage"].value) { alert("Wählen Sie erst eine Etage aus!"); get_gebaeude(InputFields[z+"etage"]); return false; }
	
	var g = InputFields[z+"gebaeude"].value;
	var e = InputFields[z+"etage"].value;
	var SBBoxId = "SBItems";
	var SBBox = getcreateDivBoxById(SBBoxId, "SelBoxEasy SelBoxRaumnr");
	if (typeof(SBBox.SBConf)=="object" && SBBox.SBConf["InputField"]==obj && SBBox.captureEvents) return true;
	var PlaceHolder = new Array();
	//addEvent(obj, "blur", function(e) { SBBox.SelBox_checkBlur(e); })
	
	if (typeof(SBBox.SBConf)=="object" && SBBox.captureEvents) SelBox_release(SBBox);
	if (typeof(optionsRaeumeByGebEtg)=="object" 
	 && typeof(optionsRaeumeByGebEtg[g])=="object"
	 && typeof(optionsRaeumeByGebEtg[g][e]) == "object"
	 && optionsRaeumeByGebEtg[g][e].length > 20) {
		SelBox_capture(SBBox, SBConfRaumnr, optionsRaeumeByGebEtg[g][e]);
	} else {
		SelBox_capture(SBBox, SBConfRaumnr, PlaceHolder);
		request_query_raeume(SBBoxId, g, e);
	}
	dockBox(obj, SBBox);
	
	//alert(InputFields["gebaeude"].value+" "+InputFields["etage"].value);
}


function apnr_getSelection(parentbox) {
	if (typeof(O(parentbox))!="object") return false;
	//alert("#290 "+parentbox.SBConf["OnSelect"]);
	var parentbox = O(parentbox);
	var SubItem = SelBox_getSelectedItem(parentbox);
	var obj = parentbox.SBConf["InputField"];
	if (SubItem) {
		//alert(SubItem.RaumTyp);RaumTyp
		parentbox.SBConf["InputField"].value = SubItem.value;
		var InputFields = getUmzugsItemInputFields(parentbox.SBConf["InputField"]);
		var z = (parentbox.SBConf["InputField"].name.indexOf("MA[z")==0)?"z":"";
		SelBox_release(parentbox);
		return false;
	} else {
		parentbox.SBConf["InputField"].value = "";
	}
	return true;
}

function get_apnr(obj) {
	if (typeof(O(obj))!="object") return false;
	obj.hasFocus = true;
	
	obj = O(obj);
	var InputFields = getUmzugsItemInputFields(obj);
	var SBConfRaumnr = getCopyOfArray(SBConfDefault);
	var z = (obj.name.indexOf("MA[z")==0)?"z":"";
	
	if (InputFields[z+"raumnr"].RaumTyp != "GBUE") return false;
	
	SBConfRaumnr["InputField"] = obj;
	SBConfRaumnr["OnInput"] = false; //etage_checkInput;
	SBConfRaumnr["OnSelect"] = apnr_getSelection;
	SBConfRaumnr["OnHover"] = false; //etage_getHover;
	SBConfRaumnr["InputType"] = "Raumnr";
	SBConfRaumnr["OnEnterClose"] = true;
	
	if (!InputFields[z+"gebaeude"].value) { alert("Wählen Sie erst ein Gebäude aus!"); get_gebaeude(InputFields[z+"gebaeude"]); return false; }
	if (!InputFields[z+"etage"].value) { alert("Wählen Sie erst eine Etage aus!"); get_gebaeude(InputFields[z+"etage"]); return false; }
	if (!InputFields[z+"raumnr"].value) { alert("Wählen Sie erst einen Raum aus!"); get_raumnr(InputFields[z+"raumnr"]); return false; }
	
	var g = InputFields[z+"gebaeude"].value;
	var e = InputFields[z+"etage"].value;
	var r = InputFields[z+"raumnr"].value;
	var SBBoxId = "SBItems";
	var SBBox = getcreateDivBoxById(SBBoxId, "SelBoxEasy SelBoxRaumnr");
	if (typeof(SBBox.SBConf)=="object" && SBBox.SBConf["InputField"]==obj && SBBox.captureEvents) return true;
	var PlaceHolder = new Array();
	//addEvent(obj, "blur", function(e) { SBBox.SelBox_checkBlur(e); })
	
	if (typeof(SBBox.SBConf)=="object" && SBBox.captureEvents) SelBox_release(SBBox);
	if (typeof(optionsApnrByGER)=="object" 
	 && typeof(optionsApnrByGER[g])=="object"
	 && typeof(optionsApnrByGER[g][e]) == "object"
	 && typeof(optionsApnrByGER[g][e][r]) == "object"
	 && optionsApnrByGER[g][e][r].length > 20) {
		//setTimeout("InfoBox('#983 get_apnr() umzugsformular.easy.js<br>\n'+showArray(optionsApnrByGER[g][e][r], 'optionsApnrByGER['+g+']['+e+']['+r+']'))", 5000);
		//alert("#985");
		SelBox_capture(SBBox, SBConfRaumnr, optionsApnrByGER[g][e][r]);
	} else {
		//alert("#988");
		SelBox_capture(SBBox, SBConfRaumnr, PlaceHolder);
		request_query_apnr(SBBoxId, g, e, r);
		//setTimeout("InfoBox('#983 get_apnr() umzugsformular.easy.js<br>\n'+showArray(optionsApnrByGER[g][e][r], 'optionsApnrByGER['+g+']['+e+']['+r+']'))", 2000);
	}
	dockBox(obj, SBBox);
	
	//alert(InputFields["gebaeude"].value+" "+InputFields["etage"].value);
}

function abteilung_getNameByGF(g) {
	if (typeof(GF)!="object") return "";
	for(var i in GF) if (GF[i]["Abteilung"]==g) return GF[i]["Abteilungsname"];
	return "";
}
function abteilung_getNameByB(b, g) {
	if (typeof(BereicheByGF)!="object") return "";
	if (typeof(g)!="string") g = "";
	if (g && typeof(BereicheByGF[g])) return false;
	
	if (g) for(var i in BereicheByGF[g]) if (BereicheByGF[g][i]["Abteilung"]==b) return BereicheByGF[g][i]["Abteilungsname"];
	return "";
}
function abteilung_getNameByA(a, b) {
	if (typeof(AbteilungenByBe)!="object") return "";
	if (typeof(b)!="string") b = "";
	if (b && typeof(AbteilungenByBe[b])) return false;
	
	if (b) for(var i in AbteilungenByBe[b]) if (AbteilungenByBe[b][i]["Abteilung"]==a) return AbteilungenByBe[b][i]["Abteilungsname"];
	return "";
}

function abteilung_filterData(parentbox, SubItem, query, frontTrunc) {
	SubItem.style.display = (SubItem.value.toLowerCase().indexOf(query.toLowerCase())!=-1)?"block":"none";
}

function abteilung_switchData(parentbox, InputType) {
	switch(InputType) {
		case "GF":
		with(parentbox.SBConf["InputField"]) {value=""; abteilung_g = ""; abteilung_b = ""; abteilung_a = ""; }
		SelBox_loadData(parentbox, GF); 
		parentbox.SBConf["InputType"] = InputType;
		break;
		
		case "B":
		var g = parentbox.SBConf["InputField"].abteilung_g;
		with(parentbox.SBConf["InputField"]) { value = abteilung_g; abteilung_b = ""; abteilung_a = ""; }
		SelBox_loadData(parentbox, BereicheByGF[g]);
		
		var CBoxSwitchGF = document.createElement("span");
		CBoxSwitchGF.style.display="block";
		CBoxSwitchGF.className = "SelBoxItem";
		CBoxSwitchGF.innerHTML = "<strong>GF "+g+" "+abteilung_getNameByGF(g)+"</strong> ";
		
		var lnkGet = document.createElement("a");
		lnkGet.innerHTML = " &uuml;bernehmen ";
		lnkGet.onclick = function() { abteilung_set(parentbox, "GF"); }
		lnkGet.style.color = "#00f";
		CBoxSwitchGF.appendChild(lnkGet);
		
		var lnkChg = document.createElement("a");
		lnkChg.innerHTML = " &nbsp; &auml;ndern";
		lnkChg.onclick = function() { abteilung_switchData(parentbox, "GF"); }
		lnkGet.style.color = "#f00";
		CBoxSwitchGF.appendChild(lnkChg);
		
		SelBox_addControlBox(parentbox, CBoxSwitchGF);
		parentbox.SBConf["InputType"] = InputType;
		break;
		
		case "A":
		var g = parentbox.SBConf["InputField"].abteilung_g;
		var b = parentbox.SBConf["InputField"].abteilung_b;
		SelBox_loadData(parentbox, AbteilungenByBe[b]);
		
		var CBoxSwitchB = document.createElement("span");
		CBoxSwitchB.className = "SelBoxItem";
		CBoxSwitchB.style.display="block";
		CBoxSwitchB.innerHTML = "<strong>Bereich "+b+" "+abteilung_getNameByB(b)+"</strong> ";
		
		var lnkBeGet = document.createElement("a");
		lnkBeGet.innerHTML = " &uuml;bernehmen ";
		lnkBeGet.onclick = function() { abteilung_set(parentbox, "B"); }
		lnkBeGet.style.color = "#00f";
		CBoxSwitchB.appendChild(lnkBeGet);
		
		var lnkBeChg = document.createElement("a");
		lnkBeChg.innerHTML = " &nbsp; &auml;ndern ";
		lnkBeChg.onclick = function() { abteilung_switchData(parentbox, "B"); }
		lnkBeGet.style.color = "#f00";
		CBoxSwitchB.appendChild(lnkBeChg);
		SelBox_addControlBox(parentbox, CBoxSwitchB);
		
		var CBoxSwitchGF = document.createElement("span");
		CBoxSwitchGF.className = "SelBoxItem";
		CBoxSwitchGF.style.display="block";
		CBoxSwitchGF.innerHTML = "<strong>GF "+g+" "+abteilung_getNameByGF(g)+"</strong> ";
		
		var lnkGet = document.createElement("a");
		lnkGet.innerHTML = " &uuml;bernehmen ";
		lnkGet.onclick = function() { abteilung_set(parentbox, "GF"); }
		lnkGet.style.color = "#00f";
		CBoxSwitchGF.appendChild(lnkGet);
		
		var lnkChg = document.createElement("a");
		lnkChg.innerHTML = " &nbsp; &auml;ndern ";
		lnkChg.onclick = function() { abteilung_switchData(parentbox, "GF"); }
		lnkGet.style.color = "#f00";
		CBoxSwitchGF.appendChild(lnkChg);
		SelBox_addControlBox(parentbox, CBoxSwitchGF);
		
		
		parentbox.SBConf["InputType"] = InputType;
		break;
	}
	return true;
}

function abteilung_set(parentbox, InputType) {
	if (typeof(O(parentbox))!="object") return false;
	var parentbox = O(parentbox);
	
	var InputFields = getUmzugsItemInputFields(parentbox.SBConf["InputField"]);
	var z = (parentbox.SBConf["InputField"].name.indexOf("MA[z")==0)?"z":"";
	
	switch(InputType) {
		case "GF":
		parentbox.SBConf["InputField"].value= parentbox.SBConf["InputField"].abteilung_g;
		parentbox.SBConf["InputField"].abteilung_b = "";
		parentbox.SBConf["InputField"].abteilung_a = "";
		SelBox_release(parentbox, true);
		get_gebaeude(InputFields[z+"gebaeude"]);
		break;
		
		case "B":
		parentbox.SBConf["InputField"].value= parentbox.SBConf["InputField"].abteilung_b;
		parentbox.SBConf["InputField"].abteilung_a = "";
		SelBox_release(parentbox, true);
		get_gebaeude(InputFields[z+"gebaeude"]);
		break;
	}
	return true;
}

function abteilung_getSelection(parentbox) {
	if (typeof(O(parentbox))!="object") return false;
	//alert("#290 "+parentbox.SBConf["OnSelect"]);
	
	var parentbox = O(parentbox);
	var SubItem = SelBox_getSelectedItem(parentbox);
	if (SubItem) {
		parentbox.SBConf["InputField"].value = SubItem.value;
		switch(SubItem.Ebene) {
			case "GF":
			parentbox.SBConf["InputField"].abteilung_g = SubItem.Abteilung;
			abteilung_switchData(parentbox, "B");
			return false;
			break;
			
			case "B":
			parentbox.SBConf["InputField"].abteilung_b = SubItem.Abteilung;
			abteilung_switchData(parentbox, "A");
			return false;
			break;
			
			case "A":
			var InputFields = getUmzugsItemInputFields(parentbox.SBConf["InputField"]);
			var z = (parentbox.SBConf["InputField"].name.indexOf("MA[z")==0)?"z":"";
			SelBox_release(parentbox, true);
			get_gebaeude(InputFields[z+"gebaeude"]);
			return false;
			break;
		}
	} else {
		parentbox.SBConf["InputField"].value = "";
	}
	return true;
}

function get_abteilung(obj) {
	if (typeof(O(obj))!="object") return false;
	obj.hasFocus = true;
	
	obj = O(obj);
	var InputFields = getUmzugsItemInputFields(obj);
	var SBConfAbteilung = getCopyOfArray(SBConfDefault);
	SBConfAbteilung["InputField"] = obj;
	SBConfAbteilung["OnInput"] = false; //Abteilung_checkInput(parentbox);
	SBConfAbteilung["OnFilter"] = abteilung_filterData; //Abteilung_checkInput(parentbox, subitem, query, frontTrunc);
	SBConfAbteilung["OnSelect"] = abteilung_getSelection;
	SBConfAbteilung["OnHover"] = false; //Abteilung_getHover(parentbox);
	SBConfAbteilung["InputType"] = "A";
	SBConfAbteilung["OnEnterClose"] = true;
	
	if (typeof(obj.abteilung_g)!="string") obj.abteilung_g = "";
	if (typeof(obj.abteilung_b)!="string") obj.abteilung_b = "";
	if (typeof(obj.abteilung_a)!="string") obj.abteilung_a = "";
	
	var SBBoxId = "SBItems";
	var SBBox = getcreateDivBoxById(SBBoxId, "SelBoxEasy SelBoxAbteilungen");
	if (typeof(SBBox.SBConf)=="object" && SBBox.SBConf["InputField"]==obj && SBBox.captureEvents) return true;
	var PlaceHolder = new Array();
	//addEvent(obj, "blur", function(e) { SBBox.SelBox_checkBlur(e); })
	
	if (typeof(SBBox.SBConf)=="object" && SBBox.captureEvents) SelBox_release(SBBox);
	
	SelBox_capture(SBBox, SBConfAbteilung, PlaceHolder);
	dockBox(obj, SBBox);
	
	if (!SBBox.SBConf["InputField"].abteilung_g) {
		SBConfAbteilung["InputType"] = "GF";
		SBBox.SBConf["InputField"].abteilung_b = "";
		SBBox.SBConf["InputField"].abteilung_a = "";
		if (typeof(GF)=="object") SelBox_loadData(SBBox, GF);
		// Load optionsGF
	} else if (!SBBox.SBConf["InputField"].abteilung_b) {
		abteilung_switchData(SBBox, "B");
		// Load optionsBByGF
	} else {
		abteilung_switchData(SBBox, "A");
		// Load optionsAbtByGFB
	}
	
	
	//alert(InputFields["gebaeude"].value+" "+InputFields["etage"].value);
}

function get_extern_firma(obj) {
	if (typeof(O(obj))!="object") return false;
	obj.hasFocus = true;
	
	obj = O(obj);
	var InputFields = getUmzugsItemInputFields(obj);
	var SBConfXF = getCopyOfArray(SBConfDefault);
	SBConfXF["InputField"] = obj;
	
	var SBBoxId = "SBItems";
	var SBBox = getcreateDivBoxById(SBBoxId, "SelBoxEasy SelBoxEtagen");
	if (typeof(SBBox.SBConf)=="object" && SBBox.SBConf["InputField"]==obj && SBBox.captureEvents) return true;
	var Placeholder = new Array();
	if (typeof(SBBox.SBConf)=="object" && SBBox.captureEvents) SelBox_release(SBBox);
	if (typeof(optionsExterneFirmen)=="object" && optionsExterneFirmen.length) Placeholder = optionsExterneFirmen;
	
	
	SelBox_capture(SBBox, SBConfXF, Placeholder);
	dockBox(obj, SBBox);
	if (!Placeholder.length) request_query_xf(SBBoxId);
	
	//alert(InputFields["gebaeude"].value+" "+InputFields["etage"].value);
}

function show_raum_mitarbeiter(obj, dst) {
	if (typeof(O(obj))!="object") return false;
	
	InputFields = getUmzugsItemInputFields(obj);
	var z = (dst=="ziel" ? "z" : "");
	//alert("#1099 show_raum_mitarbeiter(obj, '"+dst+"') z:"+z);
	var SBBoxId = "SBItems";
	var SBConfMa = getCopyOfArray(SBConfDefault);
	SBConfMa["InputField"] = obj;
	SBConfMa["BoxChild"] = obj;
	//SBConfMa["OnInput"] = mitarbeiter_check_reload;
	//SBConfMa["OnSelect"] = mitarbeiter_getSelection;
	SBConfMa["OnEnterClose"] = true;
	var Placeholder = new Array();
	var SBBox = getcreateDivBoxById(SBBoxId, "SelBoxEasy SelBoxRaumbelegung");
	if (typeof(SBBox.SBConf)=="object" && typeof(SBBox.SBConf["BoxChild"])=="object" && SBBox.SBConf["BoxChild"]==obj && SBBox.captureEvents) return true;
	if (typeof(SBBox.SBConf)=="object" && SBBox.captureEvents) SelBox_release(SBBox);
	
	SelBox_capture(SBBox, SBConfMa, Placeholder);
	dockBox(obj, SBBox);
	
	request_query_ma(SBBoxId, InputFields[z+"gebaeude"].value, InputFields[z+"etage"].value, InputFields[z+"raumnr"].value);
}

function check_mitarbeiter(BoxChild) {
	if (typeof(O(BoxChild))!="object") return false;
	
	InputFields = getUmzugsItemInputFields(BoxChild);
	if (InputFields["name"]) {
		get_namecheck(InputFields["name"]);
	} else alert("#1100 check_mitarbeiter()");
}

function namecheck_loadData(parentbox, optionsData) {
	if (typeof(O(parentbox))!="object" || typeof(O(parentbox).SBConf)!="object") return false;
	parentbox = O(parentbox);
	SelBox_loadData(parentbox, optionsData);
}

function get_namecheck(obj) {
	if (typeof(O(obj))!="object") return false;
	
	InputFields = getUmzugsItemInputFields(obj);
	//alert("#1099 show_raum_mitarbeiter(obj, '"+dst+"') z:"+z);
	var SBBoxId = "SBItems";
	var SBConfMa = getCopyOfArray(SBConfDefault);
	SBConfMa["InputField"] = obj;
	SBConfMa["BoxChild"] = obj;
	//SBConfMa["OnInput"] = mitarbeiter_check_reload;
	//SBConfMa["OnSelect"] = mitarbeiter_getSelection;
	SBConfMa["OnEnterClose"] = true;
	var Placeholder = new Array();
	var SBBox = getcreateDivBoxById(SBBoxId, "SelBoxEasy SelBoxMitarbeiter");
	if (typeof(SBBox.SBConf)=="object" && typeof(SBBox.SBConf["BoxChild"])=="object" && SBBox.SBConf["BoxChild"]==obj && SBBox.captureEvents) return true;
	if (typeof(SBBox.SBConf)=="object" && SBBox.captureEvents) SelBox_release(SBBox);
	
	SelBox_capture(SBBox, SBConfMa, Placeholder);
	dockBox(obj, SBBox);
	
	//request_query_ma(SBBoxId, InputFields["gebaeude"].value, InputFields[z+"etage"].value, InputFields[z+"raumnr"].value);
	request_query_ma_namecheck(SBBoxId, InputFields["name"].value, InputFields["vorname"].value, "", "", "", 100);
	
	/*QBox.namecheck = function() {
		request_query_ma_namecheck(this.SBBoxId, this.InputName.value, this.InputVorname.value, "", "", "", 100);
	}*/
}

function mitarbeiter_switchData(parentbox, InputType) {
	if (typeof(O(parentbox))!="object" || typeof(O(parentbox).SBConf)!="object") return false;
	parentbox = O(parentbox);
	
	//document.getElementsByTagName("textarea")[0].value = "#604 SwitchData InputType: "+InputType
	if (InputType=="Vorname" && !parentbox.SBConf["InputField"].nachname) {
		InputType = "Nachname";
	}
	
	//document.getElementsByTagName("textarea")[0].value+= "\n609 SwitchData InputType: "+InputType;
	switch(InputType) {
		case "Vorname":
		//document.getElementsByTagName("textarea")[0].value+= "\n612 SwitchData InputType: "+InputType;
		parentbox.SBConf["InputType"] = InputType;
		var nachname = parentbox.SBConf["InputField"].nachname;
		
		if (typeof(optionsMaByName)=="object" && optionsMaByName["Query"]==nachname) {
			//document.getElementsByTagName("textarea")[0].value+= "\n#617 SwitchData loadData optionsMaByName '"+nachname+"': "+InputType;
			mitarbeiter_loadDataByName(parentbox, optionsMaByName["Data"]);
		} else {
			//document.getElementsByTagName("textarea")[0].value+= "\n#629 SwitchData checkReload '"+nachname+"': "+InputType;
			mitarbeiter_check_reload(parentbox);
		}
		break;
		
		case "Nachname":
		default:
		parentbox.SBConf["InputType"]= "Nachname";
		//document.getElementsByTagName("textarea")[0].value+= "\n635 SwitchData InputType: "+InputType
		parentbox.SBConf["InputField"].value = parentbox.SBConf["InputField"].nachname;
		parentbox.SBConf["InputField"].nachname = "";
		mitarbeiter_check_reload(parentbox);
		break;
	}
	return true;
}

function mapflege_new_search() {
	if (typeof(O('SelectMitarbeiter'))!="object") return false;
	
	var SearchField = O('SelectMitarbeiter');
	if (SearchField.nachname) SearchField.nachname = "";
	
	var SBBoxId = "SBItems";
	var Placeholder = new Array();
	
	if (typeof(O(SBBoxId)=="object") && typeof(O(SBBoxId).SBConf)=="object" && O(SBBoxId).captureEvents) {
		SelBox_release(O(SBBoxId));
	}
	//alert("get_mapflege");
	get_mapflege(O('SelectMitarbeiter'));
}

function mitarbeiter_new_search() {
	if (typeof(O('SelectMitarbeiter'))!="object") return false;
	
	var SearchField = O('SelectMitarbeiter');
	if (SearchField.nachname) SearchField.nachname = "";
	
	var SBBoxId = "SBItems";
	var Placeholder = new Array();
	
	if (typeof(O(SBBoxId)=="object") && typeof(O(SBBoxId).SBConf)=="object" && O(SBBoxId).captureEvents) {
		SelBox_release(O(SBBoxId));
	}
	
	get_mitarbeiter(O('SelectMitarbeiter'));
}

function mapflege_neu_anlegen() {
	var MaRow = addMa_Datenpflege("Input");
	
	var InputFields = getUmzugsItemInputFields(MaRow.getElementsByTagName("input")[3]);
	
	//return false;
	InputFields["mid"].value = "neu";

	InputFields["name"].focus();

	InputFields["name"].onclick = function() { return true; }
	InputFields["name"].onfocus = function() { return true; }
	if (InputFields["vorname"].removeAttribute) InputFields["vorname"].removeAttribute("readonly");
	InputFields["extern_firma"].onclick = function() { return true; }
	InputFields["extern_firma"].onfocus = function() { return true; }
	
	if (InputFields["vorname"].removeAttribute) InputFields["MA[extern_firma][]"].removeAttribute("readonly");
	InputFields["extern_firma"].onclick = function() { get_extern_firma(InputFields["extern_firma"]); }
	InputFields["extern_firma"].onfocus = function() { get_extern_firma(InputFields["extern_firma"]); }
	
	InputFields["abteilung"].onfocus = function() { get_abteilung(InputFields["abteilung"]); }
	InputFields["abteilung"].onclick = function() { get_abteilung(InputFields["abteilung"]); }
	
	InputFields["gebaeude"].onfocus = function() { get_gebaeude(InputFields["gebaeude"]); }
	InputFields["gebaeude"].onclick = function() { get_gebaeude(InputFields["gebaeude"]); }
	
	InputFields["etage"].onfocus = function() { get_etage(InputFields["etage"]); }
	InputFields["etage"].onclick = function() { get_etage(InputFields["etage"]); }
	
	InputFields["raumnr"].onfocus = function() { get_raumnr(InputFields["raumnr"]); }
	InputFields["raumnr"].onclick = function() { get_raumnr(InputFields["raumnr"]); }
	
}

function mitarbeiter_neu_anlegen() {
	var MaRow = addMa("Input");
	var InputFields = getUmzugsItemInputFields(MaRow.getElementsByTagName("input")[3]);
	
	InputFields["mid"].value = "neu";
	InputFields["name"].focus();
	InputFields["name"].onclick = function() { return true; }
	InputFields["name"].onfocus = function() { return true; }
	if (InputFields["vorname"].removeAttribute) InputFields["vorname"].removeAttribute("readonly");
	InputFields["extern_firma"].onclick = function() { return true; }
	InputFields["extern_firma"].onfocus = function() { return true; }
	
	if (InputFields["vorname"].removeAttribute) InputFields["MA[extern_firma][]"].removeAttribute("readonly");
	InputFields["extern_firma"].onclick = function() { get_extern_firma(InputFields["extern_firma"]); }
	InputFields["extern_firma"].onfocus = function() { get_extern_firma(InputFields["extern_firma"]); }
	
	InputFields["abteilung"].onfocus = function() { get_abteilung(InputFields["abteilung"]); }
	InputFields["abteilung"].onclick = function() { get_abteilung(InputFields["abteilung"]); }
	
	InputFields["gebaeude"].onfocus = function() { get_gebaeude(InputFields["gebaeude"]); }
	InputFields["gebaeude"].onclick = function() { get_gebaeude(InputFields["gebaeude"]); }
	
	InputFields["etage"].onfocus = function() { get_etage(InputFields["etage"]); }
	InputFields["etage"].onclick = function() { get_etage(InputFields["etage"]); }
	
	InputFields["raumnr"].onfocus = function() { get_raumnr(InputFields["raumnr"]); }
	InputFields["raumnr"].onclick = function() { get_raumnr(InputFields["raumnr"]); }
	
	get_umzugsart(InputFields["umzugsart"]);
}

function mitarbeiter_check_reload(parentbox) {
	if (!parentbox || typeof(O(parentbox))!="object") return false;
	parentbox = O(parentbox);
	var send_query = false;
	var limit = 10;
	parentbox.SBConf["InputField"].value = parentbox.SBConf["InputField"].value.toUpperCase();
	
	var hasUnallowedChars = false;
	var allowedChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ 0123456789 ,.-_/&";
	for(var i = 0; i < parentbox.SBConf["InputField"].value.length; i++) {
		if (allowedChars.indexOf(parentbox.SBConf["InputField"].value.charAt(i))==-1) {
			var m = "<strong>Achtung:</strong> Ihre Eingabe enthält Sonderzeichen ("+parentbox.SBConf["InputField"].value.charAt(i)+")<br>\n";
			m+= "Die Namen liegen im einfachen Zeichensatz vor wie sie bei Emails verwendet werden.<br>\n";
			m+= "Schreiben Sie MUELLER statt MÜLLER, E statt È, SS statt &szlig; etc.<br>\n";
			m+= "Verwendete Zeichen: "+allowedChars;
			myAlert = InfoBox(m, parentbox.SBConf["InputField"], "right", 5, 0);
			myAlert.onmousemove = myAlert.close;
			myAlert.InfoType = "AllowedChars";
			hasUnallowedChars = true;
			break;
		}
	}
	if (hasUnallowedChars == false && typeof(myAlert)=="object" && myAlert.InfoType=="AllowedChars") myAlert.close();
	
	//alert("#657 "+parentbox.SBConf["InputType"]);
	var lastQuery = {Input:"",Size:-1,NumAll:-1};
	if (parentbox.SBConf["InputType"] == "Nachname") {
		
		if (parentbox.SBConf["InputField"].value.length) {
			
			if (typeof(optionsNachnamen)=="object") {
				if (typeof(optionsNachnamen["Query"])=="string") lastQuery["Input"] = optionsNachnamen["Query"];
				if (typeof(optionsNachnamen["NumAll"])=="number") lastQuery["NumAll"] = optionsNachnamen["NumAll"];
				if (typeof(optionsNachnamen["Size"])=="number") lastQuery["Size"] = optionsNachnamen["Size"];
			}
			
			if (!lastQuery["Input"] || lastQuery["NumAll"] > limit) send_query = 1;
			if (typeof(optionsNachnamen)!="object" || !optionsNachnamen["Data"].length) send_query = 2;
			else if(lastQuery["Input"].length > parentbox.SBConf["InputField"].value.length) send_query = 3;
			else if(parentbox.SBConf["InputField"].value.toUpperCase().indexOf(lastQuery["Input"].toUpperCase())!=0) send_query = 4;
			
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
				
				request_query_ma_nachname(parentbox.id, parentbox.SBConf["InputField"].value, 10);
			}
		} else {
			var CBoxInfo = document.createElement("span");
			CBoxInfo.className = "SelBoxItem";
			CBoxInfo.style.display="block";
			CBoxInfo.innerHTML = "</strong> <em style=\"italic\">warte auf Eingabe: Nachname ...</em>";
			SelBox_addControlBox(parentbox, CBoxInfo);
		}
	} else {
		//alert("#692 Load Vornamen");
		if (typeof(optionsMaByName)=="object") {
			if (typeof(optionsMaByName["Query"])=="string") lastQuery["Input"] = optionsMaByName["Query"];
			if (typeof(optionsMaByName["NumAll"])=="number") lastQuery["NumAll"] = optionsMaByName["NumAll"];
			if (typeof(optionsMaByName["Size"])=="number") lastQuery["Size"] = optionsMaByName["Size"];
		}
		
		////document.getElementsByTagName("textarea")[0].value = "send_query:"+send_query+" Input:"+parentbox.SBConf["InputField"].value+"\n";
		//for(i in lastQuery) //document.getElementsByTagName("textarea")[0].value+= i+": "+lastQuery[i]+"\t";
		
		if (!lastQuery["Input"] || lastQuery["NumAll"] > limit) send_query = true;
		if (typeof(optionsMaByName)!="object" || !optionsMaByName.length) send_query = true;
		else if(lastQuery["Input"] != parentbox.SBConf["InputField"].nachname) send_query = true;
		
		//if (send_query) 
		
		var CBoxLoading = document.createElement("span");
		CBoxLoading.style.display="block";
		CBoxLoading.className = "SelBoxItem";
		CBoxLoading.innerHTML = "<em style=\"italic\">Daten werden geladen</em>";
		SelBox_addControlBox(parentbox, CBoxLoading);
		
		request_query_ma_vorname(parentbox.id, parentbox.SBConf["InputField"].nachname, '');
	}
}

function mitarbeiter_loadDataByName(parentbox, optionsData) {
	if (typeof(O(parentbox))!="object" || typeof(O(parentbox).SBConf)!="object") return false;
	parentbox = O(parentbox);
	if (parentbox.SBConf["InputType"]!="Vorname") return false;
	SelBox_loadData(parentbox, optionsData);
	
	var CBoxSwitch = document.createElement("span");
	CBoxSwitch.style.display="block";
	CBoxSwitch.className = "SelBoxItem";
	CBoxSwitch.innerHTML = "<strong>"+parentbox.SBConf["InputField"].nachname+"</strong> <em style=\"italic\">anderen Nachnamen auswählen</em>";
	CBoxSwitch.onclick = function() { mitarbeiter_switchData(parentbox, "Nachname"); }
	CBoxSwitch.onmouseover = function() { AC(this, "IsHoverItem"); }
	CBoxSwitch.onmouseout = function() { RC(this, "IsHoverItem"); }
	SelBox_addControlBox(parentbox, CBoxSwitch);
}

function mitarbeiter_loadDataNachname(parentbox, optionsData) {
	if (typeof(O(parentbox))!="object" || typeof(O(parentbox).SBConf)!="object") return false;
	parentbox = O(parentbox);
	if (parentbox.SBConf["InputType"]!="Nachname") return false;
	SelBox_loadData(parentbox, optionsData);
}

function mitarbeiter_getSelection(parentbox) {
	//alert("mitarbeiter_getSelection");
	if (typeof(O(parentbox))!="object") return false;
	var parentbox = O(parentbox);
	var InputFields = getUmzugsItemInputFields(parentbox.SBConf["InputField"]);
	var SubItem = SelBox_getSelectedItem(parentbox);
	
	switch(parentbox.SBConf["InputType"]) {
		case "Vorname":
		//var m=""; for(var i in SubItem) if(i.indexOf("ma_")==0) m+=i+":"+SubItem[i]+"\n"; alert(m);
		if (parentbox.SBConf["InputField"].name == "MA[name][]") {
			InputFields = getUmzugsItemInputFields(parentbox.SBConf["InputField"]);
		} else {
			var MaRow = addMa("Select");
			InputFields = getUmzugsItemInputFields(MaRow.getElementsByTagName("input")[3]);
		}
		//var m=""; if (typeof(InputFields)=="object") for(i in InputFields) m+= i+":"+typeof(InputFields[i])+"\n"; alert(m);
		
		InputFields["maid"].value = SubItem.ma_id;
		InputFields["name"].value = SubItem.ma_n;
		InputFields["vorname"].value = SubItem.ma_v;
		
		InputFields["abteilung"].abteilung_g = SubItem.ma_gf;
		InputFields["abteilung"].abteilung_b = SubItem.ma_b;
		InputFields["abteilung"].abteilung_a = SubItem.ma_a;
		if (SubItem.ma_a) InputFields["abteilung"].value = SubItem.ma_a;
		else if (SubItem.ma_b) InputFields["abteilung"].value = SubItem.ma_b;
		else if (SubItem.ma_gf) InputFields["abteilung"].value = SubItem.ma_gf;
		else InputFields["abteilung"].value = "";
		
		InputFields["gebaeude"].value = SubItem.ma_g;
		InputFields["etage"].value = SubItem.ma_e;
		InputFields["raumnr"].value = SubItem.ma_r;
		InputFields["apnr"].value = (SubItem.ma_ap) ? SubItem.ma_ap : "";
		InputFields["extern_firma"].value = SubItem.ma_xf;
		
		InputFields["extern_firma"].value = SubItem.ma_xf;
		
		parentbox.SBConf["InputField"].value = "";
		
		SelBox_release(parentbox, true);
		InputFields["umzugsart"].focus();
		get_umzugsart(InputFields["umzugsart"]);
		
		return false;
		break;
		
		case "Nachname":
		parentbox.SBConf["InputField"].nachname = SubItem.value
		mitarbeiter_switchData(parentbox, "Vorname");
		break;
	}
	return false;
}


function mapflege_getSelection(parentbox) {
	if (typeof(O(parentbox))!="object") return false;
	var parentbox = O(parentbox);
	var InputFields = getUmzugsItemInputFields(parentbox.SBConf["InputField"]);
	var SubItem = SelBox_getSelectedItem(parentbox);
	
	switch(parentbox.SBConf["InputType"]) {
		case "Vorname":
		//var m=""; for(var i in SubItem) if(i.indexOf("ma_")==0) m+=i+":"+SubItem[i]+"\n"; alert(m);
		if (parentbox.SBConf["InputField"].name == "MA[name][]") {
			InputFields = getUmzugsItemInputFields(parentbox.SBConf["InputField"]);
		} else {
			var MaRow = addMa("Select");
			InputFields = getUmzugsItemInputFields(MaRow.getElementsByTagName("input")[3]);
		}
		//var m=""; if (typeof(InputFields)=="object") for(i in InputFields) m+= i+":"+typeof(InputFields[i])+"\n"; alert(m);
		
		InputFields["maid"].value = SubItem.ma_id;
		InputFields["name"].value = SubItem.ma_n;
		InputFields["vorname"].value = SubItem.ma_v;
		
		InputFields["abteilung"].abteilung_g = SubItem.ma_gf;
		InputFields["abteilung"].abteilung_b = SubItem.ma_b;
		InputFields["abteilung"].abteilung_a = SubItem.ma_a;
		if (SubItem.ma_a) InputFields["abteilung"].value = SubItem.ma_a;
		else if (SubItem.ma_b) InputFields["abteilung"].value = SubItem.ma_b;
		else if (SubItem.ma_gf) InputFields["abteilung"].value = SubItem.ma_gf;
		else InputFields["abteilung"].value = "";
		
		InputFields["gebaeude"].value = SubItem.ma_g;
		InputFields["etage"].value = SubItem.ma_e;
		InputFields["raumnr"].value = SubItem.ma_r;
		InputFields["apnr"].value = (SubItem.ma_ap) ? SubItem.ma_ap : "";
		InputFields["extern_firma"].value = SubItem.ma_xf;
		
		InputFields["extern_firma"].value = SubItem.ma_xf;
		
		parentbox.SBConf["InputField"].value = "";
		
		InputFields["umzugsart"].value = "Datenpflege";
		
		SelBox_release(parentbox, true);
		InputFields["zabteilung"].focus();
		get_abteilung(InputFields["zabteilung"]);
		
		return false;
		break;
		
		case "Nachname":
		parentbox.SBConf["InputField"].nachname = SubItem.value
		mitarbeiter_switchData(parentbox, "Vorname");
		break;
	}
	return false;
}

function mapflege_save() {
	if (!document.forms["frmUmzugsantrag"]) return false;
	
	var dt = new Date();
	var umzugstermin = dt.getFullYear().toString();
	umzugstermin+= "-"+(dt.getMonth()<10?"0"+dt.getMonth().toString():dt.getMonth());
	umzugstermin+= "-"+(dt.getDate()<10?"0"+dt.getDate().toString():dt.getDate());
	document.forms["frmUmzugsantrag"].elements["AS[terminwunsch]"].value = umzugstermin;
	document.forms["frmUmzugsantrag"].elements["AS[umzugstermin]"].value = umzugstermin;
	
	var error = umzugsantrag_errors();
	
	if (error) {
		InfoBox(error);
		return false;
	}
	
	//alert("Antrag #"+id+" wird zur Zwischenspeicherung abgeschickt!");
	document.forms["frmUmzugsantrag"].action = "umzugsantrag.php";
	var selector = "MyInfoBoxTxt";
	umzugsantrag_loadingBar('');
	AjaxFormSend(document.forms["frmUmzugsantrag"], selector, "", "cmd=mapflege_speichern");
}

function get_mitarbeiter(obj) {
	if (typeof(O(obj))!="object") return false;
	var InputFields = getUmzugsItemInputFields(obj);
	
	var SBConfMa = getCopyOfArray(SBConfDefault);
	SBConfMa["InputField"] = obj;
	SBConfMa["OnInput"] = mitarbeiter_check_reload;
	SBConfMa["OnSelect"] = mitarbeiter_getSelection;
	SBConfMa["OnEnterClose"] = true;
	SBConfMa["InputType"] = "Nachname";
	
	if (typeof(obj.nachname)!="string") obj.nachname = "";
	if (obj.nachname) SBConfMa["InputType"] = "Vorname";
	
	var SBBoxId = "SBItems";
	var Placeholder = new Array();
	var SBBox = getcreateDivBoxById(SBBoxId, "SelBoxEasy SelBoxMitarbeiter");
	if (typeof(SBBox.SBConf)=="object" && SBBox.SBConf["InputField"]==obj && SBBox.captureEvents) return true;
	if (typeof(SBBox.SBConf)=="object" && SBBox.captureEvents) SelBox_release(SBBox);
	
	SelBox_capture(SBBox, SBConfMa, Placeholder);
	dockBox(obj, SBBox);
	
	mitarbeiter_check_reload(SBBox);
}
function get_mapflege(obj) {
	if (typeof(O(obj))!="object") return false;
	var InputFields = getUmzugsItemInputFields(obj);
	
	var SBConfMa = getCopyOfArray(SBConfDefault);
	SBConfMa["InputField"] = obj;
	SBConfMa["OnInput"] = mitarbeiter_check_reload;
	SBConfMa["OnSelect"] = mapflege_getSelection;
	SBConfMa["OnEnterClose"] = true;
	SBConfMa["InputType"] = "Nachname";
	
	if (typeof(obj.nachname)!="string") obj.nachname = "";
	if (obj.nachname) SBConfMa["InputType"] = "Vorname";
	
	var SBBoxId = "SBItems";
	var Placeholder = new Array();
	var SBBox = getcreateDivBoxById(SBBoxId, "SelBoxEasy SelBoxMitarbeiter");
	if (typeof(SBBox.SBConf)=="object" && SBBox.SBConf["InputField"]==obj && SBBox.captureEvents) return true;
	if (typeof(SBBox.SBConf)=="object" && SBBox.captureEvents) SelBox_release(SBBox);
	
	SelBox_capture(SBBox, SBConfMa, Placeholder);
	dockBox(obj, SBBox);
	
	mitarbeiter_check_reload(SBBox);
}

function mitarbeiter_getSelectionByGER(parentbox) {
	if (typeof(O(parentbox))!="object") return false;
	var InputFields = getUmzugsItemInputFields(parentbox.SBConf["InputField"]);
	var SubItem = SelBox_getSelectedItem(parentbox);
	//var m=""; for(var i in SubItem) if(i.indexOf("ma_")==0) m+=i+":"+SubItem[i]+"\n"; alert(m);
	
	InputFields["name"].value = SubItem.ma_n;
	InputFields["vorname"].value = SubItem.ma_v;
	
	InputFields["abteilung"].abteilung_g = SubItem.ma_gf;
	InputFields["abteilung"].abteilung_b = SubItem.ma_b;
	InputFields["abteilung"].abteilung_a = SubItem.ma_a;
	if (SubItem.ma_a) InputFields["abteilung"].value = SubItem.ma_gf+"."+SubItem.ma_b+"."+SubItem.ma_a;
	else if (SubItem.ma_b) InputFields["abteilung"].value = SubItem.ma_gf+"."+SubItem.ma_b;
	else if (SubItem.ma_gf) InputFields["abteilung"].value = SubItem.ma_gf;
	
	InputFields["gebaeude"].value = SubItem.ma_g;
	InputFields["etage"].value = SubItem.ma_e;
	InputFields["raumnr"].value = SubItem.ma_r;
	InputFields["apnr"].value = SubItem.ma_ap;
	InputFields["extern_firma"].value = SubItem.ma_xf;
	
	SelBox_release(parentbox, true);
	get_umzugsart(InputFields["umzugsart"]);
	return false;
}

function get_mitarbeiterByGER(obj) {
	if (typeof(O(obj))!="object") return false;
	var InputFields = getUmzugsItemInputFields(obj);
	
	if (!InputFields["gebaeude"].value) { get_gebaeude(InputFields["gebaeude"]); return true; }
	if (!InputFields["etage"].value)    { get_gebaeude(InputFields["etage"]);    return true; }
	if (!InputFields["raumnr"].value)   { get_gebaeude(InputFields["raumnr"]);   return true; }
	
	var gebaeude = InputFields["gebaeude"].value;
	var etage = InputFields["etage"].value;
	var raumnr = InputFields["raumnr"].value;
	
	var SBConfMa = getCopyOfArray(SBConfDefault);
	SBConfMa["InputField"] = obj;
	SBConfMa["OnSelect"] = mitarbeiter_getSelection;
	SBConfMa["OnEnterClose"] = true;
	
	var SBBoxId = "SBItems";
	var Placeholder = new Array();
	var SBBox = getcreateDivBoxById(SBBoxId, "SelBoxEasy SelBoxMitarbeiter");
	if (typeof(SBBox.SBConf)=="object" && SBBox.SBConf["InputField"]==obj && SBBox.captureEvents) return true;
	if (typeof(SBBox.SBConf)=="object" && SBBox.captureEvents) SelBox_release(SBBox);
	
	SelBox_capture(SBBox, SBConfMa, Placeholder);
	dockBox(obj, SBBox);
	
	request_query_ma(SBBoxId, gebaeude, etage, raumnr);
}

function umzugsart_getSelection(parentbox) {
	if (typeof(O(parentbox))!="object") return false;
	var InputFields = getUmzugsItemInputFields(parentbox.SBConf["InputField"]);
	var SubItem = SelBox_getSelectedItem(parentbox);
	
	parentbox.SBConf["InputField"].value = SubItem.value;
	
	SelBox_release(parentbox, true);
	get_abteilung(InputFields["zabteilung"]);
	return false;
}

function get_umzugsart(obj) {
	if (typeof(O(obj))!="object" || typeof(optionsUmzugsarten)!="object") return false;
	var InputFields = getUmzugsItemInputFields(obj);
	
	var SBBoxId = "SBItems";
	var Placeholder = new Array();
	var SBBox = getcreateDivBoxById(SBBoxId, "SelBoxEasy SelBoxUmzugsart");
	if (typeof(SBBox.SBConf)=="object" && SBBox.captureEvents) SelBox_release(SBBox, true);
	
	var SBConfUa = getCopyOfArray(SBConfDefault);
	SBConfUa["InputField"] = obj;
	SBConfUa["OnSelect"] = umzugsart_getSelection;
	SBConfUa["OnEnterClose"] = true;
	
	SelBox_capture(SBBox, SBConfUa, optionsUmzugsarten);
	dockBox(obj, SBBox);
}


function get_fon(obj) {
	if (typeof(O(obj))!="object" || typeof(optionsFon)!="object") return false;
	var InputFields = getUmzugsItemInputFields(obj);
	
	var SBBoxId = "SBItems";
	var Placeholder = new Array();
	var SBBox = getcreateDivBoxById(SBBoxId, "SelBoxEasy SelBoxUmzugsart");
	if (typeof(SBBox.SBConf)=="object" && SBBox.captureEvents) SelBox_release(SBBox, true);
	
	var SBConfUa = getCopyOfArray(SBConfDefault);
	SBConfUa["InputField"] = obj;
	SBConfUa["OnSelect"] = default_getSelection;
	SBConfUa["OnEnterClose"] = true;
	
	SelBox_capture(SBBox, SBConfUa, optionsFon);
	dockBox(obj, SBBox);
}

function default_getSelection(parentbox) {
	if (typeof(O(parentbox))!="object") return false;
	var InputFields = getUmzugsItemInputFields(parentbox.SBConf["InputField"]);
	var SubItem = SelBox_getSelectedItem(parentbox);
	
	parentbox.SBConf["InputField"].value = SubItem.value;
	
	SelBox_release(parentbox, true);
	return false;
}

function get_pcnr(obj) {
	if (typeof(O(obj))!="object" || typeof(optionsPcnr)!="object") return false;
	var InputFields = getUmzugsItemInputFields(obj);
	
	var SBBoxId = "SBItems";
	var Placeholder = new Array();
	var SBBox = getcreateDivBoxById(SBBoxId, "SelBoxEasy SelBoxUmzugsart");
	if (typeof(SBBox.SBConf)=="object" && SBBox.captureEvents) SelBox_release(SBBox, true);
	
	var SBConfUa = getCopyOfArray(SBConfDefault);
	SBConfUa["InputField"] = obj;
	SBConfUa["OnSelect"] = default_getSelection;
	SBConfUa["OnEnterClose"] = true;
	
	SelBox_capture(SBBox, SBConfUa, optionsPcnr);
	dockBox(obj, SBBox);
}

function get_umzugsart_checkbox(obj) {
	if (typeof(O(obj))!="object") return false;
	var InputFields = getUmzugsItemInputFields(obj);
	
	var SBBoxId = "SBItems";
	var Placeholder = new Array();
	var SBBox = getcreateDivBoxById(SBBoxId, "SelBoxEasy SelBoxUmzugsart");
	if (typeof(SBBox.SBConf)=="object" && SBBox.captureEvents) SelBox_release(SBBox, true);
	var SBBoxItems = O("SelBoxUartItems");
	SBBox.innerHTML = SBBoxItems.innerHTML;
	
	var SubItems =SBBox.getElementsByTagName("div");
	for (var i = 0; i < SubItems.length; i++) {
		SubItems[i].CheckBox = SubItems[i].getElementsByTagName("input")[0];
		SubItems[i].parentbox = SBBox;
		SubItems[i].InputField = obj;
		SubItems[i].CheckBox.checked = (SubItems[i].InputField.value.toUpperCase().indexOf(SubItems[i].CheckBox.value.toUpperCase())!=-1)
		
		SubItems[i].onmouseover = function() { AC(this, "IsHoverItem"); }
		SubItems[i].onmouseout = function() { RC(this, "IsHoverItem"); }
		SubItems[i].onclick = function() { 
			this.CheckBox.checked = !this.CheckBox.checked; 
			var aCheckedVals = new Array();
			for (var i = 0; i < this.parentbox.getElementsByTagName("input").length; i++) if (this.parentbox.getElementsByTagName("input")[i].checked) aCheckedVals.push(this.parentbox.getElementsByTagName("input")[i].value);
			this.InputField.value = aCheckedVals.join(", ");
			return false; 
		}
	}
	var CtrlBox = getcreateDivBoxById("SBBoxCloser", "SelBoxClose");
	CtrlBox.innerHTML = "<img align=\"absmiddle\" src=\"images/loeschen_off.png\" style=\"cursor:pointer\" width=\"14\" alt=\"schliessen\">";
	CtrlBox.parentbox = SBBox;
	CtrlBox.onclick = function() { this.parentbox.style.display="none"; this.parentbox.innerHTML=""; return false; }
	SBBox.insertBefore(CtrlBox, SBBox.firstChild);
	dockBox(obj, SBBox);
	SBBox.style.display="block";
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

function load_optionsEtagenByGebaeude(g) {
	if (typeof(Gebaeude)!="object" || !Gebaeude[g]) return false;
	optionsEtagenByGebaeude = new Array();
	var i;
	var j=0;
	for(i in Gebaeude[g]["Etagen"]) {
		optionsEtagenByGebaeude[j++] = {value:Gebaeude[g]["Etagen"][i], content:Gebaeude[g]["Etagen"][i]};
	}
	return true;
}

function load_optionsRaeumeByGebEtg(g, e) {
	if (typeof(Raeume)!="object") return false;
	optionsRaeume = new Array();
	var i;
	var j=0;
	for(i in Raeume) {
		optionsRaeume[j++] = {value:Raeume[i]["Nr"], content:Raeume[i]["Nr"]+" "+Raeume[i]["Typ"], raumtyp:Raeume[i]["Typ"]};
	}
}

function request_query_xf(SBBoxId) {
	igWShowLoadingBar(1, "Externe Firmen werden geladen!", SBBoxId);
	AjaxRequestUrl = "load_externefirmen_index.php?";
	AjaxRequestUrl+= '&SBBoxId='+escape(SBBoxId);
	AjaxRequestUrl+= '&resultFormat=XML';
	//alert(AjaxRequestUrl);
	fb_AjaxRequest(AjaxRequestUrl, 'get', "fb_AjaxXmlUpdate(%req%)");
}

function request_query_ma_nachname(SBBoxId, input, limit) {
	igWShowLoadingBar(1, "Mitarbeiter werden geladen!", SBBoxId);
	AjaxRequestUrl = "load_mitarbeiter_nachnamen_index.php?";
	AjaxRequestUrl+= '&input='+escape(input);
	AjaxRequestUrl+= '&limit='+escape(limit);
	AjaxRequestUrl+= '&SBBoxId='+escape(SBBoxId);
	AjaxRequestUrl+= '&resultFormat=XML';
	//alert(AjaxRequestUrl);
	fb_AjaxRequest(AjaxRequestUrl, 'get', "fb_AjaxXmlUpdate(%req%)");
}

function request_query_ma_vorname(SBBoxId, nachname, input, limit) {
	igWShowLoadingBar(1, "Mitarbeiter werden geladen!", SBBoxId);
	AjaxRequestUrl = "load_mitarbeiter_byname_index.php?";
	AjaxRequestUrl+= '&nachname='+escape(nachname);
	AjaxRequestUrl+= '&input='+escape(input);
	AjaxRequestUrl+= '&limit='+escape(limit);
	AjaxRequestUrl+= '&SBBoxId='+escape(SBBoxId);
	AjaxRequestUrl+= '&resultFormat=XML';
	//alert(AjaxRequestUrl);
	fb_AjaxRequest(AjaxRequestUrl, 'get', "fb_AjaxXmlUpdate(%req%)");
}

function request_query_ma_namecheck(SBBoxId, name, vorname, gebaeude, etage, raumnr, limit) {
	igWShowLoadingBar(1, "Namensprüfung läuft!", SBBoxId);
	AjaxRequestUrl = "load_mitarbeiter_namecheck.php?";
	AjaxRequestUrl+= '&name='+escape(name);
	AjaxRequestUrl+= '&vorname='+escape(vorname);
	AjaxRequestUrl+= '&gebaeude='+escape(gebaeude);
	AjaxRequestUrl+= '&etage='+escape(etage);
	AjaxRequestUrl+= '&raumnr='+escape(raumnr);
	AjaxRequestUrl+= '&limit='+escape(limit);
	AjaxRequestUrl+= '&SBBoxId='+escape(SBBoxId);
	AjaxRequestUrl+= '&resultFormat=XML';
	//alert(AjaxRequestUrl);
	fb_AjaxRequest(AjaxRequestUrl, 'get', "fb_AjaxXmlUpdate(%req%)");
}

function request_query_ma(SBBoxId, gebaeude, etage, raumnr) {
	igWShowLoadingBar(1, "Raumbelegung wird geladen!", SBBoxId);
	AjaxRequestUrl = "load_raum_bewegung.php?";
	AjaxRequestUrl+= '&gebaeude='+escape(gebaeude);
	AjaxRequestUrl+= '&etage='+escape(etage);
	AjaxRequestUrl+= '&raumnr='+escape(raumnr);
	AjaxRequestUrl+= '&SBBoxId='+escape(SBBoxId);
	AjaxRequestUrl+= '&resultFormat=XML';
	//prompt(AjaxRequestUrl, AjaxRequestUrl);
	fb_AjaxRequest(AjaxRequestUrl, 'get', "fb_AjaxXmlUpdate(%req%)");
}

function request_query_raeume(SBBoxId, gebaeude, etage) {
	igWShowLoadingBar(1, "Raumnummern werden geladen!", SBBoxId);
	AjaxRequestUrl = "load_raeume_index.php?";
	AjaxRequestUrl+= '&gebaeude='+escape(gebaeude);
	AjaxRequestUrl+= '&etage='+escape(etage);
	AjaxRequestUrl+= '&SBBoxId='+escape(SBBoxId);
	AjaxRequestUrl+= '&resultFormat=XML';
	//alert(AjaxRequestUrl);
	fb_AjaxRequest(AjaxRequestUrl, 'get', "fb_AjaxXmlUpdate(%req%)");
}

function request_query_apnr(SBBoxId, gebaeude, etage, raumnr) {
	igWShowLoadingBar(1, "Arbeitsplatznr. werden geladen!", SBBoxId);
	AjaxRequestUrl = "load_apnr_index.php?";
	AjaxRequestUrl+= '&gebaeude='+escape(gebaeude);
	AjaxRequestUrl+= '&etage='+escape(etage);
	AjaxRequestUrl+= '&raumnr='+escape(raumnr);
	AjaxRequestUrl+= '&SBBoxId='+escape(SBBoxId);
	AjaxRequestUrl+= '&resultFormat=XML';
	//InfoBox("#1873 umzugsformular.easy.js<br>\n"+AjaxRequestUrl);
	fb_AjaxRequest(AjaxRequestUrl, 'get', "fb_AjaxXmlUpdate(%req%)");
}

function load_index_orte_gebaeude_etagen() {
	AjaxRequestUrl = "load_gebaeude_index.php?";
	AjaxRequestUrl+= '&resultFormat=XML';
	AjaxRequestUrl+= '&refresh='+(new Date()).getTime();
	//alert(AjaxRequestUrl);
	fb_AjaxRequest(AjaxRequestUrl, 'get', 'fb_AjaxXmlUpdate(%req%)');
}

function load_index_abteilungen() {
	AjaxRequestUrl = "load_abteilungen_index.php?";
	AjaxRequestUrl+= '&resultFormat=XML';
	AjaxRequestUrl+= '&refresh='+(new Date()).getTime();
	//alert(AjaxRequestUrl);
	fb_AjaxRequest(AjaxRequestUrl, 'get', 'fb_AjaxXmlUpdate(%req%)');
}
function load_index_gf_bereiche() {
	AjaxRequestUrl = "load_gf_bereiche_index.php?";
	AjaxRequestUrl+= '&resultFormat=XML';
	AjaxRequestUrl+= '&refresh='+(new Date()).getTime();
	//alert(AjaxRequestUrl);
	fb_AjaxRequest(AjaxRequestUrl, 'get', 'fb_AjaxXmlUpdate(%req%)');
}
