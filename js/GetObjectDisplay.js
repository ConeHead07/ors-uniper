function O(ElId) { return (typeof(ElId) == "string") ? document.getElementById(ElId) : ElId; }
function ChgD(ElId) { var o = O(ElId); D(ElId, o.style.display ? '' : 'none'); }
function D(ElId /* [, Option SetVal]*/) { var o = O(ElId); if (arguments.length > 1) {o.style.display = arguments[1];} return o.style.display; }
function C(ElId /* [, Option SetVal [, Option RplVal]] */) {
	// var o = document.getElementById(ElId);
	var o = O(ElId); if (!o) return false;
	// Falls nur ein Argument(Objekt-Id) übergeben wurde, zeige aktuelle className an
  if (arguments.length == 1) return o.className;
  // Falls Objekt-Klasse leer oder nur zu zwei Argumente übergeben wurden weise className zu
  if (!o.className || arguments.length == 2) {o.className = arguments[1];return o.className;}
  // Deklariere Variablen für Stringverarbeitung
  var  s = new String(" "+o.className+" "); var t1 = new String(" "+arguments[1]+" "); var t2 = new String(" "+arguments[2]+" ");
  // Falls die Klasse schon gesetzt wurde, ist keine weitere  Operation erforderlich
  if (s.indexOf(t1) != -1) {if(s.indexOf(t2)){o.className = s.split(t2).join(" ");}return o.className;}
  // Falls zu ersetzende Klasse nicht enthalten ist, füge neue Klasse hinzu
  if (s.indexOf(t2) == -1) {o.className+= " "+arguments[1];return o.className;}
  // Führe Ersetzung durch
  s2 = s.split(t2).join(t1).substr(1); o.className = s2.substr(0, s2.length-1); return o.className; 
}
// Toggle-Class
function TC(ElId, tc1, tc2) {
	if (!tc2) tc2 = "";
	var o = O(ElId); if (!o) return false;
	var c = C(o); 
	var ac = c.split(" ");
	var nc = new Array();
	var t = "add";
	for(var i in ac) if (ac[i]==tc1 || ac[i]==tc2) { t="replaced"; ac[i]=(ac[i]==tc1?tc2:tc1); break; }
	if (t == "add") { ac.push(tc1); for(i in ac) if (ac[i]) nc.push(ac[i]); ac = nc; }
	return C(o, ac.join(" "));	
}

function getFormElementValue(Elm, Frm) {
	var el;
	if (typeof(Elm) == "object")  el = Elm 	;
	else if(Frm && document.forms[Frm] && document.forms[Frm].elements[Elm]) el = document.forms[Frm].elements[Elm];
	else if (document.getElementById(Elm)) el = document.getElementById(Elm);
	else if (document.getElementsByName(Elm).length) el = document.getElementsByName(Elm)[0];
	if (!el) { alert("Frm:"+Frm+" Elm:"+Elm+"  ist kein FormularElement (document.forms["+Frm+"].elements["+Elm+"])!"); return false; }
	//alert(el);
	var elLength = (el.length) ? el.length : 1; elType = (el.type ? el.type : (el[0] && el[0].type ? el[0].type : ""));
	var v = new Array();
	
	switch(elType) {
		case "radio": if (elLength > 1) for(j = 0; j < el.length; j++) if (el[j].checked) { v = el[j].value; break; } else if (el.checked) v = el.value; break;
		case "checkbox": if (elLength > 1) for(j = 0; j < el.length; j++) if (el[j].checked) { v.push(el[j].value); } else if (el.checked) v.push(el.value); break;
		case "select-one": case "select-single": if (el.selectedIndex!=-1) v = el.options[el.selectedIndex].value; break;
		case "select-multiple": for (j = 0; j < el.options.length; j++) if (el.options[j].selected) v.push(el.options[j].value); break;
		default: if (elLength > 1) { for (j = 0; j < elLength; j++) v.push(el[j].value); } else v = el.value;
	}
	return v;
}

function getFormValues(frm, format, sep) {
	if (!format) format = "array"; if (!sep) sep = ",";
	var el; var msg = urlquery = elName = elType = v = ""; var i = j = elLength = elIndex = 0; var aFormValues = new Array();
	for (i = 0; i < document.forms[frm].elements.length; i++) {
		elName = document.forms[0].elements[i].name; elIndex = (elName?elName:i);
		if (elName && typeof(aFormValues[elName]) != "undefined") continue;			
		el = document.forms[0].elements[elIndex]; v = getFormElementValue(el); aFormValues[elIndex] = "";
		switch(format) {
			case "urlquery": if (typeof(v) != "object") urlquery+= "&"+elIndex+"="+escape(v); else for(j = 0; j < v.length; j++) urlquery+= "&"+elIndex+"="+escape(v[j]); break;
			case "array-strings": aFormValues[elIndex] = (typeof(v) != "object" ? v : v.join(sep)); break;
			case "array": default: aFormValues[elIndex] = v;
		} // msg+= elName+": "+(typeof(v)!="object" ? v : v.join(sep))+"\n";
	} // alert(msg);
	return (format != "urlquery") ? aFormValues : urlquery;
}

function getFormObj(frm) {
	if(typeof(frm) == "number") frm = document.forms[frm]; else if(typeof(frm) == "string") { frm = (frm.charAt(0)=="#") ? document.getElementById(frm.substr(1)) : document.forms[frm]; }
	return (typeof(frm) != "object" || frm.tagName.toLowerCase() != "form") ? false : frm;
}

function frmSerialize(frm) { // object, number, name oder id
	if(typeof(frm) == "number") frm = document.forms[frm]; else if(typeof(frm) == "string") { frm = (frm.charAt(0)=="#") ? document.getElementById(frm.substr(1)) : document.forms[frm]; }
	if (typeof(frm) != "object" || frm.tagName.toLowerCase() != "form") return false;
	
	var i, j, k, el; var fs = "";
	for (i = 0; i < frm.elements.length; i++) {
		if (frm.elements[i].disabled) continue;
                el = frm.elements[i]; k = (el.name?el.name:i);
		//alert(el.name+": "+el.value);
		switch(el.type) {
			case "radio": case "checkbox": if (el.checked) fs+= "&"+k+"="+escape(el.value); break;
			case "select-one": case "select-single": if (el.selectedIndex!=-1) fs+= "&"+k+"="+escape(el.options[el.selectedIndex].value); break;
			case "select-multiple": for(j = 0; j < el.options.length; j++) if (el.options[j].selected) fs+= "&"+k+"="+escape(el.options[j].value); break;
			default: if (typeof(el.value) != "undefined") fs+= "&"+k+"="+escape(el.value);
		}
		//alert(fs);
	}
	return fs;
}

function O(ElId, i) { 
	if (typeof(ElId) == "object") return ElId;
	if (typeof(ElId) == "string") {
		if (document.getElementById(ElId) && document.getElementById(ElId).id && document.getElementById(ElId).id == ElId)
			return document.getElementById(ElId);
		if (document.getElementsByName(ElId)) {
			if (!document.getElementsByName(ElId).length) return document.getElementsByName(ElId);
			if (typeof(i) == "undefined") i = 0;
			if (document.getElementsByName(ElId)[i]) return document.getElementsByName(ElId)[i];
		}
	}
	return false;
}

function addEvent(obj, eve, fnc) {
	if (obj.addEventListener) obj.addEventListener(eve, fnc, false);
	else if (obj.attachEvent) {
		obj["e"+eve+fnc] = fnc;
		obj["e"+eve] = function() { obj["e"+eve+fnc] (window.event); }
		obj.attachEvent("on"+eve, obj["e"+eve]);
	} else eval("obj.on"+eve+" = fnc;\n");
}

function removeEvent(obj, eve, fnc) {
	if (obj.removeEventListener) obj.removeEventListener(eve, fnc, false);
	else if (obj.detachEvent) {
		obj.detachEvent("on"+eve, obj["e"+eve]);
     		obj["e"+eve] = null;
     		obj["e"+eve+fnc] = null;
  	} else eval("obj.on"+eve+" = null;\n");
}
function removeAutocompleteAll() { var F = document.getElementsByTagName("input"); for(var i = 0; i < F.length; i++) if (F[i].type=="text") F[i].setAttribute("autocomplete", "off"); }
function addAutocompleteAll() { var F = document.getElementsByTagName("input"); for(var i = 0; i < F.length; i++) if (F[i].type=="text") F[i].setAttribute("autocomplete", "on"); }
	