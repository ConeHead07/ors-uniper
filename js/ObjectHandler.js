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

// Remove-Class
function RC(ElId, c) {	if (O(ElId)) O(ElId).className = (" "+O(ElId).className+" ").split(" "+c+" ").join(" "); }

// Add-Class
function AC(ElId, c) {	if (O(ElId) && (" "+O(ElId).className+" ").indexOf(" "+c+" ")==-1) O(ElId).className+= " "+c; }

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
			if (typeof(document.getElementsByName(ElId).tagName)=="string") return document.getElementsByName(ElId);
			if (typeof(i) == "undefined") i = 0;
			if (typeof(document.getElementsByName(ElId)[i])=="object" && typeof(document.getElementsByName(ElId)[i].tagName)=="string") return document.getElementsByName(ElId)[i];
		}
	}
	return false;
}

function addEvent(obj, eve, fnc) {
	if (typeof(obj)!="object" || obj == null) return false;
	if (obj.addEventListener) {
		//alert(fnc); 
		obj["e"+eve] = fnc;
		obj.addEventListener(eve, obj["e"+eve], false);
		//obj.addEventListener(eve, fnc, false);
	}
	else if (obj.attachEvent) {
		obj["e"+eve+fnc] = fnc;
		obj["e"+eve] = function() { obj["e"+eve+fnc] (window.event); }
		obj.attachEvent("on"+eve, obj["e"+eve]);
	} else {
		eval("obj.on"+eve+" = fnc;\n");
		//alert("#130 ObjectHandler eval(obj.on...)\ntypeof(obj.attachEvent):"+typeof(obj.attachEvent));
	}
}

function removeEvent(obj, eve, fnc) {
	if (typeof(obj)!="object" || obj == null) return false;
	if (obj.removeEventListener) { 
		if (typeof(obj["e"+eve])!="function") obj["e"+eve] = fnc;
		obj.removeEventListener(eve, obj["e"+eve], false); 
	}
	else if (obj.detachEvent && typeof(obj["e"+eve])=="function") {
		obj.detachEvent("on"+eve, obj["e"+eve]);
     	obj["e"+eve] = null;
     	obj["e"+eve+fnc] = null;
  	} else eval("obj.on"+eve+" = null;\n");
}

function cancelEvent(e)
{
	if(document.all)e = event;
	if (e.target) source = e.target;
	else if (e.srcElement) source = e.srcElement;
	
	if (source.nodeType == 3) // defeat Safari bug
		source = source.parentNode;
	
	//if (typeof(e.cancelBubble) != "undefined") 
	e.cancelBubble = true;
	//if (typeof(e.returnValue) != "undefined") 
	e.returnValue = false;
	
	if (typeof(e.stopPropagation)=="function") e.stopPropagation();
	else e.stopPropagation = true;
	
	if (e.preventDefault) e.preventDefault();
	//alert("#162 cancelEvent!");		
	//if(source.tagName && source.tagName.toLowerCase()=='input')return true;
	return false;
}

function StripTags(strMod){
    return strMod;
	if(arguments.length<3) strMod=strMod.replace(/<\/?(?!\!)[^>]*>/gi, '');
    else{
        var IsAllowed=arguments[1];
        var Specified=eval("["+arguments[2]+"]");
        if(IsAllowed){
            var strRegExp='</?(?!(' + Specified.join('|') + '))\b[^>]*>';
            strMod=strMod.replace(new RegExp(strRegExp, 'gi'), '');
        }else{
            var strRegExp='</?(' + Specified.join('|') + ')\b[^>]*>';
            strMod=strMod.replace(new RegExp(strRegExp, 'gi'), '');
        }
    }
    return strMod;
}
//var htmlTest = "<a href=\"#test\">ein link</a> oi oi oi <b>ui ui</b<br\n"; alert(htmlTest+"\n"+StripTags(htmlTest));

function dockBox(obj, dockBox, dockPosition, hspace, vspace) {
	if (typeof(O(obj))!="object" || typeof(O(dockBox))!="object") return false;
	obj = O(obj);
	dockBox = O(dockBox);
	
	var l = PageInfo.getElementLeft(obj);
	var t = PageInfo.getElementTop(obj);
	var w = PageInfo.getElementWidth(obj);
	var h = PageInfo.getElementHeight(obj);
	
	if (typeof(dockPosition)!="string") dockPosition = "";
	if (typeof(hspace)!="number") hspace = 0;
	if (typeof(vspace)!="number") vspace = 0;
	
	document.body.appendChild(dockBox);
	dockBox.style.position = "absolute";
	switch(dockPosition) {
		case "right":
		dockBox.style.top = (t-1+hspace)+"px";
		dockBox.style.left= (l+w+vspace)+"px";
		dockBox.style.overflow = "auto";
		break;
		
		case "below":
		default:
		dockBox.style.top = (t+h-1+hspace)+"px";
		dockBox.style.left= (l+vspace)+"px";
		dockBox.style.overflow = "auto";
	}
	return dockBox;
}
function InfoBoxClose() {
	var InfoBoxId = "MyInfoBox";
	if (typeof(O(InfoBoxId))=="object") {
		if (typeof(O(InfoBoxId).close)=="function") O(InfoBoxId).close();
		if (typeof(O(InfoBoxId))=="object" && typeof(O(InfoBoxId).parentNode)=="object") O(InfoBoxId).parentNode.removeChild(O(InfoBoxId));
	}
}
function ErrorBox(sHtml, dockToObj, dockAlign, hspace, vspace) {
	console.log('#228 app/js/ObjectHandler.js MyInfoxBox', { sHtml });
	return MyInfoBox("Es sind Fehler aufgetreten!", sHtml, dockToObj, dockAlign, hspace, vspace);
}

function InfoBox(sHtml, dockToObj, dockAlign, hspace, vspace) {
	return MyInfoBox("Hinweis!", sHtml, dockToObj, dockAlign, hspace, vspace);
}

function MyInfoBox(sTitle, sHtml, dockToObj, dockAlign, hspace, vspace) {
	if (typeof(PageInfo)!="object" || typeof(O)!="function" || typeof(dockBox)!="function") { 
		alert("Missing Functions PageInfo, O, dockBox\n"+sHtml);
		return false;
	}
	
	var InfoShadowBoxId = "MyInfoBoxShadow";
	var ShadowBox = (document.getElementById(InfoShadowBoxId))?document.getElementById(InfoShadowBoxId):document.createElement("div");
	document.body.appendChild(ShadowBox);
	ShadowBox.id = InfoShadowBoxId;
	ShadowBox.style.position = "fixed";
	ShadowBox.style.top = 0;
	ShadowBox.style.left = 0;
	ShadowBox.style.display = "block";
	ShadowBox.style.width = "100%";
	ShadowBox.style.height = PageInfo.getDocumentHeight()+"px";
	ShadowBox.style.background = "rgba(0, 0, 0, 0.75)";
	ShadowBox.style.zIndex = 9999;
	ShadowBox.innerHTML = "&nbsp;";
	
	var InfoBoxId = "MyInfoBox";
	var InfoBox = (document.getElementById(InfoBoxId))?document.getElementById(InfoBoxId):document.createElement("div");
	document.body.appendChild(InfoBox);
	InfoBox.id = InfoBoxId;
	InfoBox.ShadowBox = ShadowBox;
	InfoBox.style.position = "fixed";
	InfoBox.style.top = "20%";
	InfoBox.innerHTML = "<h3 id=\"MyInfoBoxTitle\">"+sTitle+"</h3>\n";
	InfoBox.innerHTML+= "<div id=\"MyInfoBoxText\" class=\"InfoText\"></div>\n";
	InfoBox.innerHTML+= "<div id=\"MyInfoBoxBtn\" class=\"CloseBtn\" onclick=\"document.getElementById('"+InfoBoxId+"').close();\">OK</div>\n";
	$(InfoBox).find("#MyInfoBoxText").append(sHtml);

	console.log('#264app/js/ObjectHandler.js MyInfoxBox', { InfoBoxId, sTitle, sHtml });
	InfoBox.close = function() {
		this.style.display="";
		if (this.ShadowBox && this.ShadowBox.parentNode) {
			this.ShadowBox.parentNode.removeChild(this.ShadowBox);
		}
		if (this.parentNode) {
			this.parentNode.removeChild(this);
		}
	}
	InfoBox.InfoType = "";
	if (typeof(O(dockToObj))=="object") {
		dockBox(dockToObj, InfoBox, dockAlign, hspace, vspace);
	}
	else {
		var BoxWidth = PageInfo.getElementWidth(InfoBox);
		var BoxHeight= PageInfo.getElementHeight(InfoBox);
		
		var ScrollLeft= PageInfo.getScrollLeft();
		var ScrollTop = PageInfo.getScrollTop();
		
		var DocWidth = PageInfo.getDocumentWidth();
		var DocHeight= PageInfo.getDocumentHeight();
		
		var t = ScrollTop;
		var l = ScrollLeft;
		
		var visibleHeight = PageInfo.getVisibleHeight();
		var visibleWidth = PageInfo.getVisibleWidth();
		
	    CenterWidth = (visibleWidth>BoxWidth) ? parseInt((visibleWidth-BoxWidth)/2) : 0;
	    CenterHeight = (visibleHeight>BoxHeight) ? parseInt((visibleHeight-BoxHeight)/2) : 0;
		
		t+= CenterHeight;
		l+= CenterWidth;
		InfoBox.style.top = "25%"; // t+"px";
		InfoBox.style.left = l+"px";
		//alert("BoxHeight:"+BoxHeight+"\nScrollTop:"+ScrollTop+"\nDocHeight:"+DocHeight+"\nt:"+t+"\nvisibleHeight:"+visibleHeight);
	}
	InfoBox.style.display = "block";
	InfoBox.style.zIndex = 10000;
	return InfoBox;
}
