function showContextBox(obj, dataselect, multiple, sContextFieldIds) {
	var aContextFieldIds = sContextFieldIds.split(",");
	var trackGetVars = "";
	var id = "";
	var f = "";
	for (var i = 0; i < aContextFieldIds.length; i++) {
		id = aContextFieldIds[i];
		if (document.getElementById(id) && (typeof document.getElementById(id).value != "undefined")) {
			f = (id.indexOf("eingabe[") != 0) ? id : id.substring(8, (id.length-1));
			trackGetVars+= "&ContextField["+f+"]="+escape(document.getElementById(id).value);
		}
	}
	showComboBox(obj, dataselect, multiple, trackGetVars);
}

function showComboBox(obj, dataselect) {
	if ((typeof last_obj) == "object" && last_obj == obj) {
		if (document.getElementById("cmboBox").style.display != "none") {
			document.getElementById("cmboBox").style.display="none";
			last_obj = null;
			return false;
		}
	}
	if (!obj.initOnChange) {
		// alert("#4 obj.onchange:"+obj.id+", DEFAULT:"+obj.defaultValue+","+dataselect);
		obj.onkeyup = function() {
			showComboBox(obj, dataselect);
			obj.initOnChange = true;
			// alert("#8 obj.onchange:"+obj.id+","+dataselect, obj.value);
		}
	}
	var multiple = (arguments.length > 2) ? arguments[2] : "single";
	var trackGetVars = (arguments.length > 3) ? "&"+arguments[3] : "";
	
	var livesearch = (obj.value != obj.defaultValue) ? obj.value : "";
	
	if (obj.autocomplete) {
		obj.autocomplete = "Off";
	}
	
	if (!document.getElementById("cmboBox")) {
		divcmboBox = document.createElement("div");
		divcmboBox.id = "cmboBox";
		document.getElementsByTagName("body")[0].appendChild(divcmboBox);
	}
	if (!document.getElementById("outerBlurBox")) {
		divDtpOuterBox = document.createElement("div");
		divDtpOuterBox.id = "outerBlurBox";
		divDtpOuterBox.onclick = function() {
			if (document.getElementById("outerBlurBox")) document.getElementById("outerBlurBox").style.display="none";
			if (document.getElementById("cmboBox")) document.getElementById("cmboBox").style.display="none";
		}
		document.getElementsByTagName("body")[0].appendChild(divDtpOuterBox);
	}/**/
	if (document.getElementById("cmboBox")) {
		document.getElementById("cmboBox").style.display = "";
		document.getElementById("cmboBox").style.left = PageInfo.getElementLeft(obj)+"px";
		document.getElementById("cmboBox").style.top = PageInfo.getElementTop(obj)+PageInfo.getElementHeight(obj)+"px";
		var sMonth = "";
		var aD = new Array();
		if (obj.value.indexOf("-") != -1 &&  obj.value.split("-").length > 1) {
			aD = obj.value.split("-");
			while(aD[1].charAt(0) == "0") aD[1] = aD[1].substr(1);
			while(aD[2].charAt(0) == "0") aD[2] = aD[2].substr(1);
			if (parseInt(aD[1])) {
				aD[1] = (parseInt(aD[1]));
				sMonth = obj.value.split("-")[0]+"-"+(aD[1]-1);
				if (parseInt(aD[2])) sMonth+= "-"+aD[2];
			}
		}
		
		get_ComboBox("id:"+obj.id, "id:cmboBox", "url", "selectbox.php?select="+dataselect+trackGetVars, multiple, livesearch);
		last_obj = obj;
		// get_kalenderPicker("id:"+obj.id, "id:cmboBox", sMonth);
	}
	
	if (document.getElementById("outerBlurBox")) {
		// alert("#39 outerBlurBox: inputFld.top:"+PageInfo.getElementTop(obj)+"px, documentHeight:"+PageInfo.getDocumentHeight());
		document.getElementById("outerBlurBox").style.display = "";
		document.getElementById("outerBlurBox").style.left = "0px";
		document.getElementById("outerBlurBox").style.top = (PageInfo.getElementTop(obj)+PageInfo.getElementHeight(obj))+"px";
		document.getElementById("outerBlurBox").style.height = (PageInfo.getDocumentHeight())+"px"; //-PageInfo.getElementTop(obj);
	}
}

function ComboClick(tTyp, tZielId, tMulti, value) {
	if (tTyp == "id") {
		if (tMulti != "multiple") {
			document.getElementById(tZielId).value = unescape(value);
		} else {
			document.getElementById(tZielId).value = document.getElementById(tZielId).value.split(",").slice(0,-1).join(",");
			document.getElementById(tZielId).value+= (document.getElementById(tZielId).value?", ":"")+unescape(value)+",";
		}
		if (document.getElementById(tZielId).onchange)
			document.getElementById(tZielId).onchange();
	}
	if (tMulti != "multiple") closecmboBox();
	
}

function closecmboBox() {
	if (document.getElementById("cmboBox")) {
		document.getElementById("cmboBox").style.display = "none";
	}
	if (document.getElementById("outerBlurBox")) {
		document.getElementById("outerBlurBox").style.display = "none";
	}
}

function get_ComboBox() { // Auswahl-Target, Rückgabemodus, Datenquelle
	
	var msg = "";
	var aTrackArgs = new Array();
	var aTgt = new Array();
	var aRet = new Array();
	var srcType = "html";
	var srcValue = "";
	
	if (arguments.length > 0 && arguments[0].indexOf(":")!=-1) {
		//  Auswahl-Target
		aTgt = arguments[0].split(":");
		aTrackArgs[0] = arguments[0];
		// Target-Type: aTgt[0] = "f" (Funktion) oder "id" (Input-Feld)
		// Target-Ziel: aTgt[0] = Funktionsname für Typ "f" oder ID des Input-Feldes für Typ "i"
	}
	
	if (arguments.length > 1 && arguments[1].indexOf(":")!=-1) {
		// Rückgabemodus
		aRet = arguments[1].split(":");
		aTrackArgs[1] = arguments[1];
		// Übergabe der Auswahlbox an 
		// Ausgabe-Type: aRet[0] = "f" (Funktion) oder "id"
		// Ausgabe-Ziel: aRet[1] = Funktionsname an den die Ausgabe übergeben werden soll
		// oder ID des HTML-Ements id das der Kalender geschrieben werden soll!!
	}
	
	if (arguments.length > 3) {
		// Datenquelle
		srcType = arguments[2];
		srcValue = arguments[3];
	}
	var multiple = (arguments.length > 4) ? arguments[4] : "single";
	var livesearch = (arguments.length > 5) ? arguments[5] : "";
	// alert("#116 livesearch:"+livesearch);
	
	var combobox = "";
	if (aTgt[0] == "id") {
		inputWidth = PageInfo.getElementWidth(document.getElementById(aTgt[1]));
	} else {
		inputWidth = "";
	}
	
	combobox+= "<div "+(inputWidth?"style=\"width:"+inputWidth+"px;\" ":"")+"id=\"outerFrameBox\">";
	
	if (srcType == "url") combobox+= "<iframe"+" style=\"width:100%\" frameborder=0 border=0 id=\"ifrCombo\" src=\""+srcValue+"&CallBackFnc=ComboClick&tTyp="+aTgt[0]+"&tZielId="+aTgt[1]+"&tMulti="+multiple+"&search="+escape(livesearch)+"\"></iframe>";
	else if (srcType == "html") {
		combobox+= srcValue;
	}
	else combobox+= "Unbekannte Datenquelle!";
	combobox+= "</div>";
	/**/
	// combobox+= "<button id=\"btnDtpClose\" onclick=\"closecmboBox('"+aRet[1]+"')\">schliessen</button>";
	
	switch(aRet[0]) {
		case "id": 
		if (document.getElementById(aRet[1])) {
			document.getElementById(aRet[1]).innerHTML = combobox;
		}
		break;
		
		case "f": 
		eval(aRet[1]+"(combobox)"); 
		break;
		
		default: return kalender;
	}
}
