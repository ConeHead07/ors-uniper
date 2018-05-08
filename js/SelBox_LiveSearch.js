// Livesearch-Box

function queryDomObjects(sSelektor) {
	var aSelektoren = sSelektor.split(" ");
	var aDomRange = [document];
	var aDomQuery;
	var aDomResults = new Array();
	var i, j, k, l, s;
	for (i in aSelektoren) {
		s = aSelektoren[i];
		aDomQuery = new Array()
		if (s.substr(0,1) == ".") aDomQuery[0] = {type: "class", value:s.substr(1)};
		else if (s.substr(0,1) == "#") aDomQuery[0] = {type: "id", value:s.substr(1)};
		else if (s.substr(0,1) == "§") aDomQuery[0] = {type: "name", value:s.substr(1)};
		else {
			if (s.split(".").length == 2) 
				aDomQuery = [{type: "tag", value:s.split(".")[0]}, {type: "class", value:s.split(".")[1]}];
			else if (s.split("#").length == 2) 
				aDomQuery = [{type: "tag", value:s.split("#")[0]}, {type: "id", value:s.split("#")[1]}];
			else if (s.split("§").length == 2) 
				aDomQuery = [{type: "tag", value:s.split("§")[0]}, {type: "name", value:s.split("§")[1]}];
			else 
				aDomQuery = [{type: "tag", value:s}];
		}
		
		if (typeof(aDomRange[0]) != "object" || !aDomRange[0].getElementById) {
			alert("#27 ungültiges object");
			break;
		}
		
		for (r in aDomRange) {
			if (aDomQuery[r]) switch(aDomQuery[0]["type"]) {
				case "tag":
				aDomResults = aDomRange[r].getElementsByTagName(aDomQuery[0]["value"]);
				break;
				
				case "id":
				aDomResults = aDomRange[r].getElementById(aDomQuery[0]["value"]);
				break;
				
				case "name":
				aDomResults = aDomRange[r].getElementsByName(aDomQuery[0]["value"]);
				break;
				
				case "class":
				aTmp = aDomRange[r].getElementsByTagName("*");
				for (o in aTmp) if (aDomResults[o].classname.indexOf(aDomQuery[0]["value"]) != -1) aDomResults.push(aTmp[o]);
				break;
			}
			
			if (aDomQuery[1]) switch(aDomQuery[1]["type"]) {
			
				case "id":		for (o in aDomResults) if (aDomResults[o].id != aDomQuery[1]["value"]) aDomResults[o] = null; 	break;
				
				case "name":	for (o in aDomResults) if (aDomResults[o].name != aDomQuery[1]["value"]) aDomResults[o] = null;	break;
				
				case "class":	for (o in aDomResults) if (aDomResults[o].classname.indexOf(aDomQuery[1]["value"]) == -1) aDomResults[o] = null; break;
			}
			aTmp = aDomResults; aDomResults = new Array; for(o in aTmp) if (aTmp[o]) aDomResults.push(aTmp[o]);
		}
		aDomRange = new Array();
	}
	return aDomResults;
}

function SelBox_InputSelektor(sSelektor) {
	// Bsp: #MA1 input:name=MA[ort][]
	var t = sSelektor.split(" ");
	var i = j = 0;
	var objRoot = document;
	var iElements;
	var iFilter = { "objRootId":"", "tagName":"", "attrName":"", "attrValue":"" };
	if (t[0].substr(0,1) == "#") {
		iFilter["objRootId"] = t[0].substr(1);
		objRoot = document.getElementById(iFilter["objRootId"]);
		if (!objRoot) return false;
		if (t.length== 1) return objRoot;
		i++;
	}
	
	if (t[i].indexOf(":") >0) {
		t2 = t[i].split(":");
		iFilter["tagName"] = t2[0];
		if (t2[1] && t2[1].indexOf("=")>0) {
			t3 = t2[1].split("=");
			iFilter["attrName"] = t3[0];
			iFilter["attrValue"] = t3[1];
		}
	}
	//alert(iFilter["objRootId"]+"; "+iFilter["tagName"]+"; "+iFilter["attrName"]+"; "+iFilter["attrValue"]);
	
	if (iFilter["tagName"]) {
		iElements = objRoot.getElementsByTagName(iFilter["tagName"]);
		if (!iElements) return false;
		
		if (iFilter["attrName"] && iFilter["attrValue"]) {
			for(j = 0; j < iElements.length; j++) {
				//alert("Check Attribute "+iFilter["attrName"]+"=?"+iFilter["attrValue"]+": "+iElements[j].getAttribute(iFilter["attrName"])+";");
				if (iElements[j].getAttribute(iFilter["attrName"]) == iFilter["attrValue"]) {
					return iElements[j];
				}
			}
		} else if(iFilter["attrName"] && iFilter["attrValue"]) {
			return false; // Unvollständige Filter
		} else {
			return iElements[0];
		}
	}
	return false;
}

function SelBox_Simple(initInputObj, aConf) { // additionalQuery
	//var aConfDefault = { qAdd:'', qName:initInputObj.name, qUrl:'livesearch.php', qSelectMultiple:false, qSeachFields:false/*objArray*/, qInputFields:false/*objArray*/ };
	if (typeof(aConf) == "undefined") aConf = new Array();
	if (typeof(initInputObj) != "object" || (!initInputObj.name && typeof(aConf['qName']) == "undefined")) return false;
	
	var initLSMultiple = (typeof(aConf['qSelectMulitple']) != "undefined") ? (aConf['qSelectMulitple']?true:false) : false;
	var initLSName = (typeof(aConf['qName']) == "string") ? aConf['qName'] : initInputObj.name;
	var initLSSearchFields = (typeof(aConf['qSearchFields']) == "object") ? aConf['qSearchFields'] : false;
	var initLSInputFields = (typeof(aConf['qInputFields']) == "object") ? aConf['qInputFields'] : false;
	var initLSUrl = (typeof(aConf['qUrl']) == "string") ? aConf['qUrl'] : "livesearch.php";
	SelBox_initLSDefault(initInputObj, initInputObj.name, initLSMultiple, initLSSearchFields, initLSInputFields);
	
	initInputObj.additionalQuery = (typeof(aConf['qAdd']) == "string") ? aConf['qAdd'] : '';
	initInputObj.LiveSearchUrl = initLSUrl
	initInputObj.onBeforeInsert = (typeof(aConf["onBeforeInsert"]) == "function") ? aConf["onBeforeInsert"] : false;
	initInputObj.onAfterInsert = (typeof(aConf["onAfterInsert"]) == "function") ? aConf["onAfterInsert"] : false;
	//alert("SelBoxItems: "+document.getElementById("SelBoxItems"));
}

function SelBox_initLSDefault(initInputObj, initLSName, initLSMultiple, initLSSearchFields, initLSInputFields) {
	var obj = (typeof initInputObj == "string") ? document.getElementById(initInputObj) : initInputObj;
	var initSBCId = "SelBox";
	var initIsLiveSearch = true;
	var i;
	var f;
	var chckObj;
	if ((typeof aPathToObj) == "undefined") aPathToObj = new Array();
	
	if (O("Monitor") && obj.SelBox) 
		O("Monitor").innerHTML = "obj.SelBox.inputField.id:"+obj.SelBox.inputField.id+"; obj.id:"+obj.id;
	
	var refreshObj = true;
	if (obj.SelBox && obj.SelBox.inputField == obj && obj.SelBox.Container.SelBox.inputField == obj && obj.SelBox.Items.SelBox.inputField == obj) {
		refreshObj = false;
	}
	
	if (!obj.SelBoxInit || obj.SelBox.inputField != obj) {
		if (initLSSearchFields) {
			for(i in initLSSearchFields) {
				if (!aPathToObj[initLSSearchFields[i]]) aPathToObj[initLSSearchFields[i]] = SelBox_InputSelektor(initLSSearchFields[i]);
				if (aPathToObj[initLSSearchFields[i]]) {
					t = (isNaN(i)?i:(obj.name?obj.name:i));
					if (!obj.InputSearchFields) obj.InputSearchFields = new Array();
					obj.InputSearchFields[t] = aPathToObj[initLSSearchFields[i]];
					alert(aPathToObj[initLSSearchFields[i]].value);
		
				} else {
					alert("LSSearchField '"+initLSSearchFields[i]+"' wurde nicht gefunden. \nLivesearch für "+initLSName+" kann nicht initialisiert werden!");
				}
				// #MA1 input:name=MA[ort][]
			}
		}
		if (initLSInputFields) {
			for(i = 0; i < initLSInputFields.length; i++) {
				if (!aPathToObj[initLSInputFields[i]]) aPathToObj[initLSInputFields[i]] = SelBox_InputSelektor(initLSInputFields[i]);
				if (aPathToObj[initLSInputFields[i]]) {
					if (!obj.InputResultFields) obj.InputResultFields = new Array();
					obj.InputResultFields[0] = aPathToObj[initLSInputFields[i]];
				} else {
					alert("LSInputField '"+initLSInputFields[i]+"' wurde nicht gefunden. \nLivesearch für "+initLSName+" kann nicht initialisiert werden!");
				}
			}
		}
	}
	SelBox_init(initInputObj, initLSName, initSBCId, initIsLiveSearch, initLSMultiple);
}
function SelBox_init(initInputObj, initLSName, initSBCId, initIsLiveSearch, initLSMultiple) {
	initSelBox(initInputObj, initLSName, initSBCId, initIsLiveSearch, initLSMultiple);
}
function initSelBox(initInputObj, initLSName, initSBCId, initIsLiveSearch, initMultiple) {
  var obj = (typeof initInputObj == "string") ? document.getElementById(initInputObj) : initInputObj;
  var t="";
  if (!initSBCId) initSBCId = "SelBox";
  obj.SelBoxInitDisplay = true;
  //document.getElementById("terminwunsch").value+= "I:"+obj.SelBoxInitDisplay.toString();
  var parentObj = false;
  obj.hasFocus = true;
  if (!obj.SelBoxInit || !obj.SelBox.inputField || obj.SelBox.inputField != obj || !obj.SelBoxContainer.inputField || obj.SelBoxContainer.inputField != obj) {
	obj.refreshQuery =true;
	obj.LiveSearchUrl = "livesearch.php";
	obj.LiveSearchBaseQuery = "";
	obj.isLiveSearch = initIsLiveSearch;
	obj.autocomplete = "off";
  	obj.SelBoxInit = true;
	obj.SelBoxMaxHeight = 200;
	obj.selectMultiple = (initMultiple?true:false);
	obj.LiveSearchName = (initLSName) ? initLSName : (obj.name ? obj.name : "");
	if (!obj.multipleSeparator) obj.multipleSeparator=",";
	if (!obj.SelBoxTitle) obj.SelBoxTitle = initLSName;
	if (!obj.SelBoxContainer) {
		if (document.getElementById(initSBCId)) obj.SelBoxContainer = document.getElementById(initSBCId);
		else {
			obj.SelBoxContainer = document.createElement("div");
			// class="SelBox" style="position:absolute;"
			obj.SelBoxContainer.className = "SelBox";
			obj.SelBoxContainer.style["position"] = "absolute";
			obj.SelBoxContainer.id = initSBCId;
			document.body.appendChild(obj.SelBoxContainer);
		}
	}
	obj.SelBoxContainer.inputField = obj;
	
	obj.SelBoxContainer.innerHTML = "<div style=\"position:absolute;right:1px;\"><img align=\"absmiddle\" src=\"images/loeschen_off.png\" style=\"cursor:pointer\" onclick=\"document.getElementById('"+initSBCId+"').style.display='none'\" width=\"14\" alt=\"\"></div>";
	if (obj.SelBoxTitle) obj.SelBoxContainer.innerHTML+= "<div class=\"SelTitle\"><strong>"+obj.SelBoxTitle+"</strong></div>\n";
	//alert(obj.SelBoxTitle);
	if (!obj.SelBox) {
		if (document.getElementById(initSBCId+"Items")) obj.SelBox = document.getElementById(initSBCId+"Items");
		else if (document.getElementById("SelBoxItems")) obj.SelBox = document.getElementById("SelBoxItems");
		else {
			obj.SelBox = document.createElement("div");
			obj.SelBox.id = "SelBoxItems";
			obj.SelBoxContainer.appendChild(obj.SelBox);
		}
	}
	obj.SelBoxContainer.style["display"] = "none";
	if (!obj.SelBox_show) obj.SelBox_show = SelBox_show;
	if (!obj.SelBox_hide) obj.SelBox_hide = SelBox_hide;
	if(obj.SelBoxContainer.parentNode.tagName.toUpperCase() != "BODY") document.body.appendChild(obj.SelBoxContainer);
  	obj.SelBox.inputField = obj;
	obj.focusField = obj;
	obj.SelBoxContainer.inputField = obj;
	obj.SelBoxContainer.focusField = obj;
	obj.SelBox.onfocus = function() { obj.hasFocus = true; }
	obj.SelBoxContainer.onfocus = function() { obj.hasFocus = true; }
	obj.SelBoxInsert = SelBox_insertSelection;
	
	obj.SelBox.SelBoxInsert = function () { obj.SelBoxInsert(); }
	
	ISFLen = 0; 
	if (obj.InputSearchFields && obj.InputSearchFields.length == 0) {
		for(var ISFI in obj.InputSearchFields) ISFLen++;
		if (obj.InputSearchFields.length < ISFLen) obj.InputSearchFields.length = ISFLen;
	}
	
	//alert("#135 umzugsformular.js: "+this.activeRow+"; "+this.SelBox.iterationActiveItem);
	//alert("#148 initSelBox()obj.InputSearchFields.length:"+obj.InputSearchFields.length);
	if (!obj.InputSearchFields || (!obj.InputSearchFields.length && !ISFLen)) {
		t = (obj.name?obj.name:0);
		obj.InputSearchFields = new Array();
		obj.InputSearchFields[t] = obj;
	}
	if (!obj.InputResultFields || !obj.InputResultFields.length) {
		obj.InputResultFields = new Array();
		obj.InputResultFields[0] = obj;
	}
    obj.SelBox_keyDown = SelBox_keyDown;
    obj.onkeydown = SelBox_keyDown;
	
	obj.SelBox_initDisplay = SelBox_initDisplay;
	
	obj.switchSelBox = SelBox_switchDisplay;
	//obj.onclick = obj.switchSelBox;
	obj.SelBox_liveSearch=SelBox_liveSearch
	obj.SelBox_nextResult = SelBox_nextResult;
	if (obj.isLiveSearch) obj.onkeyup=SelBox_liveSearch;
	for (i in obj.InputSearchFields) {
		if (obj.InputSearchFields[i] != obj) {
			obj.InputSearchFields[i].onfocus= function() { obj.hasFocus = true; }
			obj.InputSearchFields[i].onkeydown= function(e) { 
				obj.focusField = this;
				obj.SelBox_keyDown(e); 
			}
			obj.InputSearchFields[i].onkeyup= function(e) { obj.SelBox.focusField = obj.InputSearchFields[i]; obj.SelBoxContainer.focusField = obj.InputSearchFields[i]; obj.SelBox_liveSearch(e); }
		}
	}
	if (initIsLiveSearch) obj.SelBox_liveSearch();
	else SelBox_initChilds(obj.SelBox.id);
	obj.onblur = function() { obj.hasFocus=false; setTimeout("SelBox_checkBlur('"+obj.SelBoxContainer.id+"')",200); };
  } else {
	  if (initIsLiveSearch) obj.SelBox_liveSearch();
  }
  obj.SelBox_initDisplay();
}

function SelBox_show() {
  var l = PageInfo.getElementLeft(this); //      : function(element) { var element = (typeof element == "string") ? document.getElementById(element) : element; var left = element.offsetLeft; var oParent = element.offsetParent; while (oParent != null) { left += oParent.offsetLeft; oParent = oParent.offsetParent; } return left; },
  var t = PageInfo.getElementTop(this); //       : function(element) { var element = (typeof element == "string") ? document.getElementById(element) : element; var top = element.offsetTop; var oParent = element.offsetParent; while (oParent != null) { top += oParent.offsetTop; oParent = oParent.offsetParent; } return top; },
  var w = PageInfo.getElementWidth(this); //     : function(element) { var element = (typeof element == "string") ? document.getElementById(element) : element; return element.offsetWidth; },
  var h = PageInfo.getElementHeight(this); //
  
  this.SelBoxContainer.style.top = (t+h-1)+"px";
  this.SelBoxContainer.style.left= l+"px";
  this.SelBoxContainer.style.width = Math.max(w,350).toString()+"px"; //w+"px";
  this.SelBoxContainer.style.display = '';
  //this.SelBoxContainer.style.height = this.SelBoxMaxHeight;
  this.SelBoxContainer.style.overflow = "auto";
  if(navigator.userAgent.indexOf("MSIE")==-1) this.SelBoxContainer.style.width = "300px"; //w+"px";
  //fb_AjaxRequest('umzugsantrag_livesearchresult_ma.xml?'+'&refresh='+(new Date()).getTime(), 'get', 'fb_AjaxXmlUpdate(%req%)');
  // alert("hoho uart1 =  l:"+l+", t:"+t+", w:"+w+", h:"+h+"; o.id:"+o.id+", o.style.top:"+o.style.top+"");
}

function callFunction(fn) {
	var r = false; // return
	var evalStr = "r = "+fn+"(";
	for (i = 1; i < arguments.length; i++) evalStr+= (i>1?",":"")+"arguments["+i+"]";
	evalStr+= ");\n";
	eval(evalStr);
	return r;
}

// Funktion wurde von der Funktion show_ma() ans Eingabefeld gebunden
function SelBox_insertSelection() {
	var i;
	var runNext = false;
	if (this.SelBox) {
		if (this.SelBox.iterationActiveItem) {
			SelectedData = new Array(this.SelBox.iterationActiveItem.getElementsByTagName("input")[0].value);
			insert = true;
			if (typeof(this.onBeforeInsert) == "function") callFunction(this.onBeforeInsert, SelectedData);
			if (insert) {
				if (!this.selectMultiple) {
					if (this.InputResultFields.length > 1) SelectedData = SelectedData[0].split(";");
					for (i = 0; i < SelectedData.length; i++) {
						if (this.InputResultFields[i]) this.InputResultFields[i].value = SelectedData[i];
					}
				} else {
					inputFld = this.InputResultFields[0];
					if (this.SelBox.iterationActiveItem.isSelected) {
						if ((','+inputFld.value+',').indexOf(','+SelectedData[0]+',')==-1)
							inputFld.value+= (inputFld.value?",":"")+SelectedData[0];
					} else {
						inputFld.value = (','+inputFld.value+',').split(','+SelectedData[0]+',').join(',');
						while(inputFld.value.charAt(0) == ",") inputFld.value = inputFld.value.substr(1);
						while(inputFld.value.charAt(inputFld.value.length-1) == ",") inputFld.value = inputFld.value.substr(0,inputFld.value.length-1);
					}
				}
			}
			
			
				for(i = 0; i < this.InputResultFields.length; i++) {
					if (this.InputResultFields[i].value == "") {
						this.SelBox_liveSearch(false);
						runNext = true;
					}
				}
			//}
		}
	}
	if (!this.selectMultiple && !runNext) this.SelBox_hide();
	if (typeof(this.onAfterInsert) == "function") callFunction(this.onAfterInsert, SelectedData);
}

// Allgemeines Iteration-Object-ListItems in Livesearch-Box
// Wird an Eingabebox gebunden z.B. durch show_ma() und wird aufgerufen 
// von der ans Eingabefeld gebundenen Funktion SelBox_keyDown
// SelBox_keyDown übergibt die Laufrichtung an Iteration-Object : up | down
// SelBox_initChilds(SelBoxId) muss von Ajax aufgerufen werden, damit die neuen Objekte ansprechbar sind

function SelBox_liveSearch(e, trackObj) {
	if ((typeof e) != "object"  && (typeof event) == "object") e = event;
	if (e) {
		var eCharCode = (typeof(e.keyCode) == "number") ? e.keyCode : (typeof(e.charCode)=="number" ?  e.charCode : "");
		if(eCharCode==38 || eCharCode==20) return false;
	}
   
	var inputObj = (this && this.SelBox) ? this : ((trackObj && trackObj.SelBox) ? trackObj: false);
	if (!inputObj || !inputObj.hasFocus) return false;
	if (!trackObj) inputObj.lastKeyEventTime = (new Date()).getTime();
	
	var query = "";
	if (inputObj.lastKeyEventTime && (new Date()).getTime()-inputObj.lastKeyEventTime>1000) {
		for (var i in inputObj.InputSearchFields) query+= "&q["+i+"]="+escape(inputObj.InputSearchFields[i].value);
		if (inputObj.additionalQuery) query+= inputObj.additionalQuery;
		if (!inputObj.refreshQuery && inputObj.lastQuery && inputObj.lastQuery == query) return false;
		inputObj.lastQuery = query;
		inputObj.refreshQuery =false;
		if ((typeof SelBoxTimeout) != "undefined" && SelBoxTimeout) { clearTimeout(SelBoxTimeout); SelBoxTimeout=false; }
		AjaxRequestUrl = inputObj.LiveSearchUrl+'?'+query;
		AjaxRequestUrl+= '&LSBoxId='+inputObj.SelBox.id;
		AjaxRequestUrl+= '&LSMultiple='+(inputObj.selectMultiple?1:0);
		AjaxRequestUrl+= '&LSName='+inputObj.LiveSearchName;
		AjaxRequestUrl+= '&refresh='+(new Date()).getTime();
		//alert(AjaxRequestUrl);
		fb_AjaxRequest(AjaxRequestUrl, 'get', 'fb_AjaxXmlUpdate(%req%)');
		//alert("#339 SelBox_liveSearch()inputObj.InputSearchFields.length:"+inputObj.InputSearchFields.length);
	} else {
		SelBox_LS_TimeOutEvent = e;
		SelBox_LS_TimeOutObj = inputObj;
		SelBoxTimeout = setTimeout("SelBox_liveSearch(SelBox_LS_TimeOutEvent,SelBox_LS_TimeOutObj)", 1500);
	}
}

function SelBox_nextResult() {
	//alert("SelBox_nextResult()");
	AjaxRequestUrl = this.LiveSearchUrl+'?'+this.SelBox.LSResultQuery;
	AjaxRequestUrl+= '&LSBoxId='+this.SelBox.id;
	AjaxRequestUrl+= '&LSMultiple='+(this.selectMultiple?1:0);
	AjaxRequestUrl+= '&LSName='+this.LiveSearchName;
	AjaxRequestUrl+= '&LSOffset='+this.SelBox.LSResultNextOffset;
	AjaxRequestUrl+= '&refresh='+(new Date()).getTime();
	//alert(AjaxRequestUrl);
	fb_AjaxRequest(AjaxRequestUrl, 'get', 'fb_AjaxXmlUpdate(%req%)');
}

function SelBox_keyDown(e) {
   if (!e) e = event;
   if (e.keyCode) eCharCode = e.keyCode;
   else if (e.charCode) eCharCode = e.charCode;
   else return false;
   
   var msg = "";
   var action = "";
   switch(eCharCode) {
   	case 38: action="up"; break;
   	case 40: action="down"; break;
   	case 32: action="selectHoverItem"; break;
	case 13: break; //  Leertaste: Insert
	
	default:
	return false;
	//alert("eCharCode:"+eCharCode);
   }
   
   if (action == "selectHoverItem" && this.SelBox.iterationHoverItem) {
   	  this.SelBox.iterationHoverItem.selectItem();
   }
   
   //parentObj = this.SelBox;  // parentObj = document.getElementById("SelBoxMaItems");
   if ((action=="up" || action=="down") && this.SelBox.nextItem) {
   	  if (this.SelBoxContainer.style.display == "none") this.SelBox_initDisplay();
      this.SelBox.nextItem(action);
	  this.SelBox.onclick = function() { this.inputField.hasFocus=true; this.inputField.focusField.focus(); }
      if (e.preventDefault) e.preventDefault();
      if (e.stopPropagation) e.stopPropagation();
   }
   if (eCharCode == 13) this.SelBoxInsert();
   
   // More Details of keyCapturing in js/EventHandler.js
   //return false; // return false verhindert Texteingabe im Eingabefeld
}

function SelBox_nextChild(dir) {
	//alert("getNextChild("+dir+") this.childs.length:"+this.childs.length);
	if (!this.childs.length) return false;
	var nextChildID = (dir == "down") ? this.iterationHoverId+1 : this.iterationHoverId-1;
	if (nextChildID < 0) nextChildID = this.iterationMaxId;
	if (nextChildID > this.iterationMaxId) nextChildID = 0;
	if (!this.childs[nextChildID]) {
		return false;
	}
	this.childs[nextChildID].hoverItem();
	//document.getElementById("terminwunsch").value = this.activeSwitch+" "+dir+" oldChildID:"+oldChildID+ "; nextChildID:"+nextChildID;
	return false;
}

function SelBox_initChilds(SelBoxId) {
   var SelBox = document.getElementById(SelBoxId); //"SelBoxMaItems");
   var InputObjValue = "";
   for(var i = 0; i < SelBox.inputField.InputResultFields.length; i++) {
      InputObjValue+= (i?";":"")+SelBox.inputField.InputResultFields[i].value;
   }
   if (!SelBox) return false;
   SelBox.childs = SelBox.getElementsByTagName("div");
   SelBox.selectMultiple = false;
   if (SelBox.childs.length && SelBox.childs[0].getElementsByTagName("input")) {
   	 if (SelBox.childs[0].getElementsByTagName("input")[0].type == "checkbox") SelBox.selectMultiple = true;
   }
   if (!SelBox.inputField.focusField) SelBox.inputField.focusField = SelBox.inputField;
   SelBox.iterationMaxId = SelBox.childs.length-1;
   SelBox.iterationActiveId = -1;
   SelBox.iterationActiveItem = false;
   SelBox.iterationHoverId = -1;
   SelBox.iterationHoverItem = false;
   if (SelBox.LSResultNum) {
   	var rsNum = parseInt(SelBox.LSResultNum);
   	var rsNumAll = parseInt(SelBox.LSResultNumAll);
	var rsOffset = parseInt(SelBox.LSResultOffset);
	var rsLimit = parseInt(SelBox.LSResultLimit);
	var rsName = parseInt(SelBox.LSResultName);
	var rsQuery = parseInt(SelBox.LSResultQuery);
	if (document.getElementById("SelBoxMoreResults")) ElmtMore =  document.getElementById("SelBoxMoreResults");
	else { ElmtMore = document.createElement("span"); ElmtMore.id = "SelBoxMoreResults"; }
	if (rsOffset+rsNum < rsNumAll) {
		//alert("rsOffset:"+rsOffset+"; rsLimit:"+rsLimit+"; rsNum:"+rsNum+"; rsNumAll:"+rsNumAll);
		nextResultStart= rsOffset+rsNum;
		nextResultEnde = ((nextResultStart + rsLimit) < rsNumAll ? (nextResultStart + rsLimit): rsNumAll);
		ElmtMore.innerHTML = "mehr Vorschläge: "+nextResultStart+"-"+nextResultEnde+"("+rsNumAll+")";
		ElmtMore.style["cursor"] = "pointer";
		ElmtMore.onclick = function() { this.parentNode.inputField.SelBox_nextResult(); };
		SelBox.appendChild(ElmtMore);
	} else { ElmtMore.style["display"] = "none"; }
   }
   if (SelBox.lastChild) {
   	SelBox.style["height"] = (PageInfo.getElementTop(SelBox.lastChild)+PageInfo.getElementHeight(SelBox.lastChild)-PageInfo.getElementTop(SelBox))+"px";
   }
   
   if (SelBox.inputField.SelBoxMaxHeight < PageInfo.getElementHeight(SelBox)) {
   	SelBox.style["height"] = SelBox.inputField.SelBoxMaxHeight+"px";
	SelBox.style.overflow = "scroll";
	SelBox.style.overflowX = "hidden";
    //alert("#267 SelBox_LiveSearch.js SelBoxMaxHeight:"+SelBox.inputField.SelBoxMaxHeight+" elementHeight:"+PageInfo.getElementHeight(SelBox));
   }
   SelBox.srollToChild = SelBox_scrollToChild;
   SelBox.onclick = function() { this.inputField.focusField.focus(); }
   SelBox.setSelection = SelBox_insertSelection;
   PageInfo.getElementHeight(SelBox);
   //alert("SelBox_initChilds SelBox.inputField.tagName:"+SelBox.inputField.tagName);
   if (!SelBox.SelBoxInsert) {
      SelBox.SelBoxInsert = function() {
         if (this.inputField && this.iterationActiveItem) {
            switch(this.inputField.tagName.toUpperCase()) {
               case "INPUT":
			   case "TEXTAREA":
			   this.inputField.value = this.iterationActiveItem.getElementsByTagName("input")[0].value;
			   break;
			   case "DIV":
			   this.inputField.innerHTML = this.iterationActiveItem.getElementsByTagName("input")[0].value;
            }
         }
      }
   }
   for (i = 0; i < SelBox.childs.length; i++) {
        SelBox.childs[i].iterationId = i;
        //SelBox.childs[i].iterationMax = SelBox.childs.length-1;
		SelBox.childs[i].selectItem = function() {
			this.parentNode.inputField.focusField.focus();
			this.parentNode.inputField.hasFocus = true;
			var selectItem = (!this.parentNode.selectMultiple || !this.isSelected);
			if (!this.parentNode.selectMultiple && this.parentNode.iterationActiveItem) {
				this.parentNode.iterationActiveItem.className = "SelItem";
				this.parentNode.iterationActiveItem.isSelected = false;
			}
			this.isSelected = selectItem;
			this.parentNode.iterationActiveId = this.iterationId;
			this.parentNode.iterationActiveItem = this;
			this.className = selectItem ? "SelItem IsActiveItem" : "SelItem IsHoverItem";
			if (this.getElementsByTagName("input")[0].type == "radio" || this.getElementsByTagName("input")[0].type == "checkbox")
				this.getElementsByTagName("input")[0].checked = selectItem;
			this.parentNode.SelBoxInsert();
		}
		SelBox.childs[i].initItem = function() {
			this.parentNode.inputField.focusField.focus();
			this.parentNode.inputField.hasFocus = true;
			if (this.isSelected) {
				if (!this.parentNode.selectMultiple && this.parentNode.iterationActiveItem && this.parentNode.iterationActiveItem != this) {
					this.isSelected = false;
				}
			}
			if (this.isSelected) this.parentNode.iterationActiveItem = this;
			this.className = this.isSelected ? "SelItem IsActiveItem" : "SelItem";
			if (this.getElementsByTagName("input")[0].type == "radio" || this.getElementsByTagName("input")[0].type == "checkbox")
				this.getElementsByTagName("input")[0].checked = this.isSelected;
		}
		SelBox.childs[i].hoverItem = function() {
			this.parentNode.inputField.focusField.focus();
			this.parentNode.inputField.hasFocus = true;
			if (this.isHover) return false;
			if (this.parentNode.iterationHoverItem) {
				this.parentNode.iterationHoverItem.className = (!this.parentNode.iterationHoverItem.isSelected)?"SelItem":"SelItem IsActiveItem";
				this.parentNode.iterationHoverItem.isHover = false;
			}
			this.parentNode.iterationHoverId = this.iterationId;
			this.parentNode.iterationHoverItem = this;
			this.className = "SelItem IsHoverItem";
			this.isHover = true;
			//scrollToChild(this.parentNode, this);
			SelBox_scrollToChild(this.parentNode, this);
		}
		SelBox.childs[i].dehoverItem = function() {
			this.parentNode.inputField.hasFocus = true;
			this.parentNode.inputField.focusField.focus();
			if (!this.isHover) return false;
			this.className = (!this.isSelected)?"SelItem":"SelItem IsActiveItem";
			this.isHover = false;
			if (this.parentNode.iterationHoverItem == this) this.parentNode.iterationHoverItem = false;
		}
        SelBox.childs[i].onfocus = function() { SelBox.inputField.hasFocus = true; };
        SelBox.childs[i].onmouseover = SelBox.childs[i].hoverItem;
        SelBox.childs[i].onmouseout = SelBox.childs[i].dehoverItem;
        SelBox.childs[i].onclick = SelBox.childs[i].selectItem;
		
		checkVal = SelBox.childs[i].getElementsByTagName("input")[0].value;
		if (SelBox.inputField.selectMultiple) {
			// alert("#468 checkVal:"+checkVal+"; InputObjValue:"+InputObjValue);
			SelBox.childs[i].isSelected = ((","+InputObjValue+",").indexOf(checkVal)!= -1);
		} else {
			SelBox.childs[i].isSelected = (InputObjValue.indexOf(checkVal)==0);
		}
		SelBox.childs[i].initItem();
   }
   SelBox.nextItem = SelBox_nextChild;
   	if ((typeof SelBox.LSResultOffset) != "undefined" && parseInt(SelBox.LSResultOffset)>0 && SelBox.childs.length>=parseInt(SelBox.LSResultOffset)) {
		SelBox_scrollToChild(SelBox, SelBox.childs[parseInt(SelBox.LSResultOffset)-1]);
	}
}

//srollToChild = SelBox_scrollToChild(childObj)
function SelBox_scrollToChild(parentObj, childObj) {
	//parentObj = this;
	//childObj = this.iterationHoverItem;
	parentObj.style["overflow"] = "hidden";
	parentObj.style.overflowY = "scroll";
	
	if ((typeof parentObj.scrollHeight) == "undefined") return false;
	
	if (parentObj.scrollHeight > PageInfo.getElementHeight(parentObj)) {
		childTop = PageInfo.getElementTop(childObj);
		parentTop = PageInfo.getElementTop(parentObj);
		childOffset = childTop-parentTop;
		childHeight = PageInfo.getElementHeight(childObj);
		parentHeight = PageInfo.getElementHeight(parentObj);
		parentScrollTop = parseInt(parentObj.scrollTop);
		
		if (parentTop+parentScrollTop > childTop) { // 647+38 <= 687
			tryScrollTop = childOffset;
			parentObj.scrollTop = tryScrollTop;
		} else if (parentTop+parentHeight+parentScrollTop < childTop+childHeight) {
			tryScrollTop = (childOffset-parentHeight+childHeight);
			parentObj.scrollTop = tryScrollTop;
		}
	}
}


function SelBox_initDisplay() {
	this.SelBox_show();
}

function SelBox_switchDisplay() {
	if (this.SelBoxInitDisplay && this.SelBoxContainer.style.display == '') {
		this.SelBoxInitDisplay = false;
		return false;
	}
	
	if (this.SelBoxContainer.style.display == 'none') {
		this.SelBox_show();
	} else {
		this.SelBox_hide();
	}
}
function SelBox_hide() {
	if (!this.SelBoxContainer) return false;
	this.SelBoxContainer.style.display = 'none';
}

function SelBox_checkBlur(SelBoxObjId) {
	var o = document.getElementById(SelBoxObjId);
	if (!o.inputField.hasFocus) o.style.display = 'none';
}
// Ende