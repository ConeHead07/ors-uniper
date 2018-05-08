/*
Vollständiges Beispiel für eine XML-Ausgabe, die von diesem Script weiterverarbeit werden kann
<?xml version="1.0" ?>
<Result type="success">
	<Update  id="Box1" options="InsertBefore|Replace(default)|Append"><![CDATA[Duisburg]]></Update>
	<Replace id="Box2"><![CDATA[<div id="RplBox">Box2 wurde ersetzt durch RplBox</div>]]></Replace>
	<Delete id="Box3"><![CDATA[]]></Delete>
	<Attribute id="Box4" name="title" value="Ho Ho Ho"><![CDATA[]]></Attribute>
	<ClassName id="Box5" name="blackbox" replace=""/>
	<Style id="Box5" name="border"><![CDATA[1px dotted #800080]]></Style>
	<Insert to="Box4" pos="Before" options="Before|Next|First|Append|{Numerische Position}"><![CDATA[<div id="NewBox">NewBox wurde hinzugefugt</div>]]></Insert>
	<Move id="Box1" to="Box4" pos="Before" options="Before|Next|First|Append|{Numerische Position}"><![CDATA[]]></Move>
	<Script language="JavaScript" src=""><![CDATA[]]></Script>
</Result>

AllXmlNodes2Data(myNode) wandelt alle XML-Knoten in Array um
reduceXmlBasedData() wandelt Ergebnis aus AllXmlNodes2Data mit teilw. langen Pfaden in verkürzte Pfade
igWNodeUpdate(nodeId, data) aktualisiert Elementeninhalt (innerHTML)
replaceBoxByObj(objId, toObjId)
replaceBoxByHtmlSource(htmlSource, toObjId)
gWNodeInsert(toObj, data, pos)
getChildElementsLength(obj) Gibt die Anzahl echter Elementknoten zurück, ohne Kommentare, Zeilenumbrüche etc.
getChildElements(obj) Gibt ein Array mit Objekten echte Elementknoten zurück
getObjPos(obj) Gibt die Sortier-Position eines Elements innerhalb seines Elternknoten zurück, wichtig für igWNodeMove()
igWNodeMove(obj, toObj, pos) Verschiebt ein Objekt im DOM-Baum. Je nach pos-Angabe wird obj als Child eingeordnet oder auf gleicher Ebene (nur bei Next und Before)
igWNodeDelete(obj) Löschen eines ElementKnoten
igWNodeAttribute(nodeId, attrName, attrValue) Setzen eines Attributs eines Elements
igWNodeClassName(nodeId, className, replace) Setzen bzw. Ersetzen einer CSS-ClassName eines Elements
igWNodeStyle(nodeId, styleName, styleValue) Setzen einer Style-Eigenschaft eines Elements
igWDomUpdater(aData) finale Funktion in der Funktionskette: übernimmt das von AllXmlNodes2Data() erstellte Array aus dem Ajax-XML und führt automatisch alle darin enthaltenen Aktualisierungen durch
fb_AjaxXmlUpdate(req) übernimmt das von AjaxHTTP empfangene Request-Objekt und gibt das umgewandelte Datenformat (Array aData) an igWDomUpdater()
SendRequest(sUrl) Aufrufende Funktion aus einem Link onclick. Startet den Ajax-Request, die Loadingbar und gibt die zu verarbeitende CallBack-Funktion an, für die vom Ajax-Request zurückgelieferten Daten
igWShowLoadingBar(mode,msg)
*/
function O(ElId) { return ((typeof ElId == "string")?document.getElementById(ElId):ElId); }

function fb_str_replace(ndl, rpl, str) {
	return str.split(ndl).join(rpl);
}

  function fb_AjaxRequest(reqUrl, reqMethod, callBackFn) {
	//alert("#7 fb_AjaxRequestUrl()");
	if ((typeof callBackFn) == "undefined" || callBackFn=="") callBackFn = "fb_AjaxXmlUpdate(%req%)";
  var msxml = [
		"MSXML2.XMLHTTP.5.0",
		"MSXML2.XMLHTTP.4.0",
		"MSXML2.XMLHTTP.3.0",
		"MSXML2.XMLHTTP",
		"Microsoft.XMLHTTP"
	];
	
	// alert("23");
	var req = false;
	if (window.XMLHttpRequest) {
		req = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		for (var i = 0; i < msxml.length; i++) {
			try {
				req = new ActiveXObject(msxml[i]);
			} catch(e) { req = false; }
		}
	}
	
	if (req) {
		req.open(reqMethod, reqUrl, true);
		req.onreadystatechange = function() {
			
			if (req.readyState == 4) {
				eval(fb_str_replace("%req%", "req", callBackFn));
			}
		}
		req.send(null);
	}
}

//var xloops = 1;
//var maxxloops = 1000;
function AllXmlNodes2Data(myNode) {
  
  //if (++xloops >= maxxloops) return false;
  if (!myNode || !myNode.childNodes || !myNode.childNodes.length) return false;
  
  var aData = new Array();
  var aiName = "";
  var myItem = new Object();
  var myItemData = new Array();
  var i;
  var t;
  var numChilds = 0;
  
  for (i = 0; i < myNode.childNodes.length; i++) {
    myItem = myNode.childNodes.item(i);
    myItemData = new Array();
    
    switch(myItem.nodeName) {
      case "#text":
      break;
      
      default:
      for(ai = 0; ai < myItem.attributes.length; ai++) {
        aiName = myItem.attributes[ai].nodeName;
        myItemData[aiName] = myItem.getAttribute(aiName);
      }
      
      if (myItem.childNodes[0] && myItem.childNodes[0].nodeType == 4) {myItemData["CDATA"] = myItem.childNodes[0].nodeValue;}
      numChilds = 0; for (t = 0; t < myItem.childNodes.length; t++) if (myItem.childNodes[t].nodeName!="#text") {++numChilds};
      if (parseInt(numChilds)>1) {
        myItemData["XDATA"] = AllXmlNodes2Data(myItem);
      }
      
      if (aData[myItem.nodeName]) {
        if (!aData[myItem.nodeName][0]) aData[myItem.nodeName] = new Array(aData[myItem.nodeName]);
        aData[myItem.nodeName][aData[myItem.nodeName].length] = myItemData;
      } else {
        aData[myItem.nodeName] = new Array();
        aData[myItem.nodeName] = myItemData;
      }
    }
  }
  return aData;
}

//var rloops = 1;
//var maxrloops = 1000;
function reduceXmlBasedData(aData, name) {
  //if (++rloops >= maxrloops) return false;
  var i,j, i_num = j_num = 0;
  if ((typeof aData) == "object") {
    for (i in aData) i_num++; 
    if (i_num == 1) {
      switch(typeof aData[i]) {
        case "string": case "number": case "boolean": return aData[i]; break;
        default: aData = reduceXmlBasedData(aData[i], name);
      }
    } else if (i_num > 1) { for (i in aData) aData[i] = reduceXmlBasedData(aData[i], name); }
  }
  return aData;
}

var XML_newDocument = function(rootTagName, namespaceURL) {
    if (!rootTagName) rootTagName = "";
    if (!namespaceURL) namespaceURL = "";

    if (document.implementation && document.implementation.createDocument) {
        // This is the W3C standard way to do it
        return document.implementation.createDocument(namespaceURL, 
                       rootTagName, null);
    }
    else { // This is the IE way to do it
        // Create an empty document as an ActiveX object
        // If there is no root element, this is all we have to do
        var doc = new ActiveXObject("MSXML2.DOMDocument");

        // If there is a root tag, initialize the document
        if (rootTagName) {
            // Look for a namespace prefix
            var prefix = "";
            var tagname = rootTagName;
            var p = rootTagName.indexOf(':');
            if (p != -1) {
                prefix = rootTagName.substring(0, p);
                tagname = rootTagName.substring(p+1);
            }

            // If we have a namespace, we must have a namespace prefix
            // If we don't have a namespace, we discard any prefix
            if (namespaceURL) {
                if (!prefix) prefix = "a0"; // What Firefox uses
            }
            else prefix = "";

            // Create the root element (with optional namespace) as a
            // string of text
            var text = "<" + (prefix?(prefix+":"):"") + tagname +
                (namespaceURL
                 ?(" xmlns:" + prefix + '="' + namespaceURL +'"')
                 :"") +
                "/>";
            // And parse that text into the empty document
            doc.loadXML(text);
        }
        return doc;
    }
};
/**
 * Parse the XML document contained in the string argument and return
 * a Document object that represents it.
 */
function igWXmlLoad(text) {
    if (typeof DOMParser != "undefined") {
        // Mozilla, Firefox, and related browsers
        return (new DOMParser()).parseFromString(text, "application/xml");
    }
    else if (typeof ActiveXObject != "undefined") {
        // Internet Explorer.
        var doc = XML_newDocument( );   // Create an empty document
        doc.loadXML(text);              //  Parse text into it
        return doc;                     // Return it
    }
    else {
        // As a last resort, try loading the document from a data: URL
        // This is supposed to work in Safari. Thanks to Manos Batsis and
        // his Sarissa library (sarissa.sourceforge.net) for this technique.
        var url = "data:text/xml;charset=utf-8," + encodeURIComponent(text);
        var request = new XMLHttpRequest();
        request.open("GET", url, false);
        request.send(null);
        return request.responseXML;
    }
}
function RunXmlDomUpdate(sXml) {
	Xml = igWXmlLoad(sXml);
	aData = AllXmlNodes2Data(Xml);
	//alert("aData:"+showArray(aData, "aData"));
	igWDomUpdater(aData["Result"]["XDATA"]);
}
function showArray(a, p) {
	if (!p) p = "var";
	if ((typeof a) == "number" || (typeof a) == "string" || (typeof a) == "boolean") return p+" Is Not An Array: "+a+"\n";
	var s;
	for(i in a) {
		if ((typeof a[i]) == "number" || (typeof a[i]) == "string" || (typeof a[i]) == "boolean") s+= p+"["+i+"] = "+a[i]+"\n";
		else if ((typeof a[i]) == "object") s+= showArray(a[i], p+"["+i+"]")+"\n";
		else s+= p+"["+i+"] = [Unbekanntes Object "+(typeof a[i])+"\n";
	}
	return s;
}

function igWNodeUpdate(nodeId, data, options) {
  //alert("igWNodeUpdate(\nnodeId:"+nodeId+",\ndata:"+data+",\noptions:"+options);
  if (O(nodeId)) { 
  	switch(options) {
		case "InsertBefore": 
		//alert("InsertBefore options:"+options);
		O(nodeId).innerHTML = data+O(nodeId).innerHTML; break;
		case "Append": 
		//alert("Append options:"+options);
		O(nodeId).innerHTML+= data; break;
		case "": case "Replace": default: 
		//alert("default options:"+options);
		//$(nodeId).html("Hallo Frank");
		O(nodeId).innerHTML = data; break;
	}
	return true; }
  return false;
}


function replaceBoxByObj(objId, toObjId) {
  obj = O(objId);
  toObj = O(toObjId);
  if (toObj) {
    parentObj = toObj.parentNode;
    // Mit Clone ersetzen und Original anschl. löschen ist zuverlässiger
    parentObj.replaceChild(obj.cloneNode(true), toObj);
    obj.parentNode.removeChild(obj);
    return true;
  } else {
    alert("ZielObjekt "+toObjId+" nicht gefunden!");
  }
  return false;
}

function replaceBoxByHtmlSource(htmlSource, toObjId) {
  container = document.createElement("div");
  container.innerHTML = htmlSource;
  obj = container.lastChild
  toObj = O(toObjId);
  if (toObj) {
    parentObj = toObj.parentNode;
    //parentObj.replaceChild(obj, toObj);
    parentObj.insertBefore(obj, toObj);
    parentObj.removeChild(toObj);
    
    for(var i = 0; i < container.childNodes.length; i++) {
      parentObj.insertBefore(container.childNodes[i].cloneNode(true), obj);
    }
    container.innerHTML = "";
    container = null;
    return true;
  } else {
    alert("ZielObjekt "+toObjId+" nicht gefunden!");
  }
  return false;
}

function igWNodeReplace(nodeId, data) {
  return replaceBoxByHtmlSource(data, nodeId);
  return false;
}

function igWNodeInsert(toObj, data, pos) {
  // Erzeuge und positioniere temporäres Element als Platzhalter
  var TempBox = document.createElement("div");
  var TempId = "TmpBoxRand"+Math.random().toString().substr(2,6)+"T"+(new Date().getTime().toString().substr(4,9))
  TempBox.id = TempId;
  TempBox.innerHTML = "Hallo ich bin nur ein Platzhalter";
  document.body.appendChild(TempBox);
  igWNodeMove(TempBox.id, toObj, pos);
  
  // Ersetze Platzhalter mit Daten
  replaceBoxByHtmlSource(data, TempBox.id);
}


function getChildElementsLength(obj) {
  for(var i = 0,n=0; i < obj.childNodes.length; i++) if (obj.childNodes[i].nodeType == 1) ++n; // NodeType 1 ist Elementknoten
  return n;
}

function getChildElements(obj) {
  for(var i = 0,n=0,aChilds = new Array(); i < obj.childNodes.length; i++) if (obj.childNodes[i].nodeType == 1) aChilds[n++] = obj.childNodes[i]; // NodeType 1 ist Elementknoten
  return aChilds;
}

function getObjPos(obj) {
  for(var i = 0, aChilds = getChildElements(obj.parentNode); i < aChilds.length; i++) if (aChilds[i] == obj) return ++i;
  return 0;
}
  
function igWNodeMove(obj, toObj, pos, base) {
  //alert(obj+"; "+toObj);
  if (typeof obj == "string") obj = O(obj);
  if (typeof toObj == "string") toObj = O(toObj);
  if (obj && !toObj) toObj = obj.parentNode;
  if (!obj || !toObj) return false;
  if (!pos) pos = "Before";
  if (pos == "First" || pos == 0) pos = 1;
  if (parseInt(pos) == pos && pos >= getChildElementsLength(toObj)) pos = "Append";
  
  //alert(obj+"; "+toObj+" to pos:"+pos+" von "+getChildElementsLength(toObj));
  switch(pos) {
    // Auf gleicher Ebene
    case "Before":
    toObj.parentNode.insertBefore(obj, toObj);
    break;
    
    case "Next":
    toObj.parentNode.insertBefore(obj, toObj); // Erst vor dem Zielobjekt einsetzen
    toObj.parentNode.insertBefore(toObj, obj); // Und dann die beiden vertauschen
    break;
    
    // Auf Kindebene. toObj ist/wird parent von obj
    case "Append":
    toObj.appendChild(obj);
    break;
    
    // numerische Positionsangabe
    default:
    if (parseInt(pos) != pos) return false;
    toObjChildElements = getChildElements(toObj);
	if (parseInt(pos) >= toObjChildElements.length) { toObj.appendChild(obj); return true; }
    if (obj.parentNode == toObj) if (getObjPos(obj) < pos) pos++;
    toObj.insertBefore(obj, toObjChildElements[pos-1]);
  }
}

function igWNodeDelete(nodeId) {
  if (O(nodeId)) {
    O(nodeId).parentNode.removeChild(O(nodeId));
    return true;
  }
  return false;
}

function igWNodeAttribute(nodeId, attrName, attrValue) {
  var r_obj = O(nodeId);
  if (r_obj) { eval("r_obj."+attrName+" = attrValue;"); r_obj.setAttribute(attrName, attrValue); return true; }
  return false;
}

function igWNodeClassName(nodeId, className, replace) {
  return C(nodeId, className, replace);
}

function C(nodeId, className, replace) { // [, Option SetVal] [, Option RplVal]
	// var o = O(ElId);
	var o = O(nodeId); if (!o) return false;
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

function igWNodeStyle(nodeId, styleName, styleValue) {
  if (O(nodeId)) { O(nodeId).style[styleName] = styleValue; return true; }
  return false;
}

function igWLoadScript(src, language, cdata) {
  if (src == "cdata") eval(cdata);
  else if (src == "url") {
    var obj = document.createElement("script");
    if (language) obj.language = language;
    obj.src = src;
    document.body.appendChild(obj);
  }
  return false;
}

function igWDomUpdater(aData) {
  var r_obj;
  var msgj;
  
  for (i in aData) {
    //alert("#295 igWDomUpdater() i:"+i);
    switch(i) {
      case "Update":
	  if (aData[i]["id"]) { igWNodeUpdate(aData[i]["id"], aData[i]["CDATA"], (aData[i]["options"]?aData[i]["options"]:"")); }
      else if (aData[i][0]["id"]) { for (j = 0; j < aData[i].length; j++) { igWNodeUpdate(aData[i][j]["id"], aData[i][j]["CDATA"], (aData[i][j]["options"]?aData[i][j]["options"]:"")); }}
      break;
      
      case "Replace":
      if (aData[i]["id"]) { igWNodeReplace(aData[i]["id"], aData[i]["CDATA"]); }
      else if (aData[i][0]["id"]) { for (j = 0; j < aData[i].length; j++) { igWNodeReplace(aData[i][j]["id"], aData[i][j]["CDATA"]); }}
      break;
      
      case "Insert": // to="Box2" pos="1" CDATA
      if (aData[i]["to"]) { igWNodeInsert(aData[i]["to"], aData[i]["CDATA"], aData[i]["pos"]); }
      else if (aData[i][0]["to"]) { for (j = 0; j < aData[i].length; j++) { igWNodeInsert(aData[i][j]["to"], aData[i][j]["CDATA"], aData[i]["pos"]); }}
      break;
      
      case "Move": // id="Box1" to="Box5" pos
      if (aData[i]["id"]) { igWNodeMove(aData[i]["id"], aData[i]["to"], aData[i]["pos"]); }
      else if (aData[i][0]["id"]) { for (j = 0; j < aData[i].length; j++) { igWNodeMove(aData[i][j]["id"], aData[i]["to"], aData[i]["pos"]); }}
      break;
      
      case "Delete":
      if (aData[i]["id"]) { igWNodeDelete(aData[i]["id"]); }
      else if (aData[i][0]["id"]) { for (j = 0; j < aData[i].length; j++) { igWNodeDelete(aData[i][j]["id"]); }}
      break;
    
      case "Attribute":
	  if ((typeof aData[i]["CDATA"]) != "undefined" && aData[i]["CDATA"].length) aData[i]["value"] = aData[i]["CDATA"];
	  if (aData[i]["id"]) { igWNodeAttribute(aData[i]["id"], aData[i]["name"], (aData[i]["CDATA"]?aData[i]["CDATA"]:aData[i]["value"])); }
      else if (aData[i][0]["id"]) { for (j = 0; j < aData[i].length; j++) { 
		if ((typeof aData[i][j]["CDATA"]) != "undefined" && aData[i][j]["CDATA"].length) aData[i][j]["value"] = aData[i][j]["CDATA"];
		igWNodeAttribute(aData[i][j]["id"], aData[i][j]["name"], aData[i][j]["value"]); }}
      break;
    
      case "ClassName":
      if (aData[i]["id"]) { igWNodeClassName(aData[i]["id"], aData[i]["name"], aData[i]["replace"]); }
      else if (aData[i][0]["id"]) { for (j = 0; j < aData[i].length; j++) { igWNodeClassName(aData[i][j]["id"], aData[i][j]["name"], aData[i][j]["replace"]); }}
      break;
    
      case "Style":
      if (aData[i]["id"]) { igWNodeStyle(aData[i]["id"], aData[i]["name"], aData[i]["CDATA"]); }
      else if (aData[i][0]["id"]) { for (j = 0; j < aData[i].length; j++) { igWNodeStyle(aData[i][j]["id"], aData[i][j]["name"], aData[i][j]["CDATA"]);  }}
      break;
    
      case "LoadScript":
      if (aData[i]["src"]) { igWLoadScript(aData[i]["src"], aData[i]["language"], aData[i]["CDATA"]); }
      else if (aData[i][0]["src"]) { for (j = 0; j < aData[i].length; j++) { igWLoadScript(aData[i][j]["src"], aData[i][j]["language"], aData[i][j]["CDATA"]);  }}
      break;
    }
  }
}

function fb_AjaxXmlUpdate(req) {
	//alert("#341 fb_AjaxXmlUpdate(req)");
	
	if (req && req.status == 200) {
    	//alert(req.responseText);
		cType = req.getResponseHeader("Content-Type"); // "text/plain", "text/html", "text/html", "application/xml"
		// alert("53 cType:"+cType)
		if (cType.indexOf("/xml") > -1) {
			xmlDoc = req.responseXML;
			var responseNode = xmlDoc.documentElement;
			//aIgWData = fbXml2Data(responseNode);
			aIgWData = AllXmlNodes2Data(responseNode);
			// O("DebugArea").innerText = "<pre>"+showArray(aIgWData, "aIgWData")+"</pre>";
      
			var type = responseNode.getAttribute("type");
			//alert("#229 type:"+type);
			
			if (type == "success") {
				msg = "";
				//alert("#233 type:"+type);
				//alert("#485 aIgWData: "+showArray(aIgWData, "aIgWData"));
				igWDomUpdater(aIgWData);
				
				if (aIgWData["Msg"]) igWShowLoadingBar(0, "<span class=upMsg>"+aIgWData["Msg"]+"</span>");
				else {
					igWShowLoadingBar(0, "<span class=upMsg>Fertig!</span>");
					setTimeout("igWShowLoadingBar(0, '')", 2000);
				}
			} else {
				// alert("type:"+type+"\n"+req.responseText);
				if (aIgWData["Err"]) igWShowLoadingBar(0, "<span class=upErr>"+aIgWData["Err"]+"</span>");
				else igWShowLoadingBar(0, "<span class=upErr>Unbekannter Fehler: Aktion konnte nicht ausgeführt werden!</span>");
			}
			if (aIgWData["JScript"]) eval(aIgWData["JScript"]);
		} else {
			alert(req.responseText);
			// $O("D").value= req.responseText;
			// $O(toObjId).innerHTML = req.responseText;
		}
		return true;
	}
	igWShowLoadingBar(0, "<span class=upErr>Anfrage konnte nicht verarbeitet werden!</span>");
}

function igWShowLoadingBar(mode, msg, parentElement) { 
	LBar = O("LoadingBar");
	LBarParent = (parentElement) ? O(parentElement) : false;
	if (!LBar) {
      LBar = document.createElement("div");
      LBar.id = "LoadingBar";
      document.body.appendChild(LBar);
	}
	if (LBarParent) LBarParent.appendChild(LBar);
	
	if (LBar) {
    if (mode==0 && !msg) {
      LBar.innerHTML = "";
      LBar.style.display="none";
    } else {
      LBar.style.display="";
      if (mode==1 && !msg) msg = "<span class=upMsg>Daten werden übertragen</span>"; 
	    LBar.innerHTML = (mode == 1?"<img align=absmiddle src=\"loading.gif\">":"")+(msg && mode ? "":"")+(msg?msg:""); 
    }
  }
}

function SendRequest(sUrl) {
  igWShowLoadingBar(1, "<span class=upMsg>Lade Daten!</span>");
  //sUrl+= ((sUrl.indexOf("?") !== -1 ? "?":"&")+"AjaxRequest=1");
  // Verzögerung von n Sek. nur zum Testen der Statusanzeige ingWShowLoadingBar
  //setTimeout("fb_AjaxRequest('"+sUrl+"', 'get', 'fb_AjaxXmlUpdate(%req%)')", 1000);
  fb_AjaxRequest(sUrl, 'get', 'fb_AjaxXmlUpdate(%req%)');
}