// Livesearch-Box

function SelBoxInit(initInputObj, aConf) { // Ursprüngl. Params statt aConf: initLSName, initSBCId, initIsLiveSearch, initMultiple) {
	if (typeof(aConf) == "undefined") aConf = new Array();
	obj = SelBox_InputSelektor(initInputObj);
	//alert(typeof(obj));
	if (typeof(obj) != "object") return false;
	
	var SelBoxConf = new Array();
	SelBoxConf["LSMultiple"]		= (typeof(aConf['qSelectMulitple']) != "undefined") ? (aConf['qSelectMulitple']?true:false) : false;
	SelBoxConf["LSMSeparator"]		= (typeof(aConf['LSMultipleSeparator']) == "string") ? aConf['LSMultipleSeparator'] : ",";
	SelBoxConf["LSName"]			= (typeof(aConf['qName']) == "string") ? aConf['qName'] : (initInputObj.name?initInputObj.name:"q");
	SelBoxConf["LSTitle"]			= (typeof(aConf['qTitle']) == "string") ? aConf['qTitle'] : (initInputObj.title?initInputObj.title:(initInputObj.name?initInputObj.name:"Vorschl&auml;ge"));
	SelBoxConf["LSSearchFields"]	= (typeof(aConf['qSearchFields']) == "object") ? aConf['qSearchFields'] : false;
	SelBoxConf["LSInputFields"]		= (typeof(aConf['qInputFields']) == "object") ? aConf['qInputFields'] : false;
	SelBoxConf["LSUrl"]				= (typeof(aConf['qUrl']) == "string") ? aConf['qUrl'] : "livesearch.php";
	SelBoxConf["LSIsLiveSearch"] 	= (typeof(aConf["LSIsLiveSearch"]) != "undefined") ? aConf["LSIsLiveSearch"] : true;
	SelBoxConf["LSAddQuery"]		= (typeof(aConf['qAdd']) == "string") ? aConf['qAdd'] : '';
	SelBoxConf["LSDelayMSec"]		= (typeof(aConf['qDelay']) == "string") ? aConf['qDelay'] : 400; // Milli-Seconds
	SelBoxConf["LSAutoFilter"]		= (typeof(aConf['qFilter']) != "undefined") ? aConf['qFilter'] : true; //
	SelBoxConf["LSIsDynamic"]		= (typeof(aConf['qDynamic']) != "undefined") ? aConf['qDynamic'] : true; // 
	SelBoxConf["LSData"]		= (typeof(aConf['qData']) != "undefined") ? aConf['qData'] : false; // Milli-Seconds
	SelBoxConf["onGetQuery"]		= (typeof(aConf["onGetQuery"]) == "function") ? aConf["onGetQuery"] : false;
	SelBoxConf["onBeforeInsert"] 	= (typeof(aConf["onBeforeInsert"]) == "function") ? aConf["onBeforeInsert"] : false;
	SelBoxConf["onAfterInsert"]		= (typeof(aConf["onAfterInsert"]) == "function") ? aConf["onAfterInsert"] : false;
	SelBoxConf["SBContainerId"]		= (typeof(aConf["SBContainerId"]) == "string") ? aConf["SBContainerId"] : 'SelBox';
	SelBoxConf["SBBoxItemsId"]	 	= (typeof(aConf["SBBoxItemsId"]) == "string") ? aConf["SBBoxItemsId"] : SelBoxConf["SBContainerId"]+'Items';
	//alert("SelBoxItems: "+document.getElementById("SelBoxItems"));
	
	obj.hasFocus = true;
	
	var refreshObj = true;
	if (obj.SelBox && obj.SelBox.inputField == obj && obj.SelBox.Container.SelBox.inputField == obj && obj.SelBox.Items.SelBox.inputField == obj) {
		refreshObj = false;
	}
	
	//if (!obj.SelBox || obj.SelBox.inputField != obj || !obj.SelBox.SelBoxInit) {
	if (refreshObj) {
		
		obj.SelBox = null;
		obj.SelBox = new Object();
		obj.SelBox.inputField = obj;
		obj.SelBox.Conf = SelBoxConf;
		obj.SelBox.loaded = false;
		
		if (!obj.SelBox.Container) {
			if (document.getElementById(obj.SelBox.Conf["SBContainerId"])) obj.SelBox.Container = document.getElementById(obj.SelBox.Conf["SBContainerId"]);
			else {
				obj.SelBox.Container = document.createElement("div");
				// class="SelBox" style="position:absolute;"
				obj.SelBox.Container.className = "SelBox";
				obj.SelBox.Container.style["position"] = "absolute";
				obj.SelBox.Container.id = obj.SelBox.Conf["SBContainerId"];
				document.body.appendChild(obj.SelBox.Container);
			}
		}
		obj.SelBox.Container.SelBox = obj.SelBox;
		
		obj.SelBox.Container.innerHTML = "<div style=\"position:absolute;right:1px;\"><img align=\"absmiddle\" src=\"images/loeschen_off.png\" style=\"cursor:pointer\" onclick=\"document.getElementById('"+obj.SelBox.Container.id+"').style.display='none'\" width=\"14\" alt=\"\"></div>";
		if (obj.SelBox.Title) obj.SelBox.Container.innerHTML+= "<div class=\"SelTitle\"><strong>"+obj.SelBox.Conf["LSTitle"]+"</strong></div>\n";
		
		if (!obj.SelBox.Items) {
			if (document.getElementById(obj.SelBox.Conf["SBBoxItemsId"])) obj.SelBox.Items = document.getElementById(obj.SelBox.Conf["SBBoxItemsId"]);
			else {
				obj.SelBox.Items = document.createElement("div");
				obj.SelBox.Items.id = obj.SelBox.Conf["SBBoxItemsId"];
				obj.SelBox.Container.appendChild(obj.SelBox.Items);
			}
		}
		obj.SelBox.Items.SelBox = obj.SelBox;
		
		obj.SelBox.refreshQuery =true;
		obj.SelBox.LiveSearchBaseQuery = "";
		obj.autocomplete = "off";
		obj.SelBox.SelBoxInit = true;
		obj.SelBox.MaxHeight = 200;
		obj.SelBox.Container.style["display"] = "none";
		obj.SelBox.SelBox_show = SelBox_show;
		obj.SelBox.SelBox_hide = SelBox_hide;
		obj.SelBox.SelBox_filterItems = SelBox_filterItems;
		
		if(obj.SelBox.Container.parentNode.tagName.toUpperCase() != "BODY") 
			document.body.appendChild(obj.SelBox.Container);
		
		obj.SelBox.Container.inputField = obj;
		obj.SelBox.Container.focusField = obj;
		
		obj.SelBox.Container.onfocus = function() { obj.hasFocus = true; }
		obj.SelBox.SelBoxInsert = SelBox_insertSelection;
		
		obj.SelBox.insert = function () { obj.SelBox.SelBoxInsert(); }
		
		SelBoxConf["LSSearchFields"]	= (typeof(aConf['qSearchFields']) == "object") ? aConf['qSearchFields'] : false;
		SelBoxConf["LSInputFields"]
		
		obj.SelBox.InputSearchFields = (typeof(SelBoxConf["LSSearchFields"])=="object")?SelBoxConf["LSSearchFields"]:false;
		obj.SelBox.InputResultFields = (typeof(SelBoxConf["LSInputFields"])=="object")?SelBoxConf["LSInputFields"]:false;
		
		ISFLen = 0; 
		if (obj.SelBox.InputSearchFields && obj.SelBox.InputSearchFields.length == 0) {
			for(var ISFI in obj.SelBox.InputSearchFields) ISFLen++;
			if (obj.SelBox.InputSearchFields.length < ISFLen) obj.SelBox.InputSearchFields.length = ISFLen;
		}
		
		if (!obj.SelBox.InputSearchFields || (!obj.SelBox.InputSearchFields.length && !ISFLen)) {
			t = (obj.name?obj.name:0);
			obj.SelBox.InputSearchFields = new Array();
			obj.SelBox.InputSearchFields[t] = obj;
		}
		
		if (!obj.SelBox.InputResultFields || !obj.SelBox.InputResultFields.length) {
			obj.SelBox.InputResultFields = new Array();
			obj.SelBox.InputResultFields[0] = obj;
		}
		
		obj.SelBox.SelBox_keyDown = SelBox_keyDown;
		obj.onkeydown = obj.SelBox.SelBox_keyDown;
		
		obj.SelBox.SelBox_initDisplay = SelBox_initDisplay;
		
		obj.SelBox.switchSelBox = SelBox_switchDisplay;
		obj.SelBox.SelBox_sendQuery  = SelBox_sendQuery;
		obj.SelBox.SelBox_nextResult = SelBox_nextResult;
		if (obj.SelBox.Conf["LSIsLiveSearch"]) {
			//addEvent(obj, "keyup", function() {obj.SelBox.SelBox_sendQuery()});
			obj.onkeyup=obj.SelBox.SelBox_sendQuery;
		}
		for (i in obj.SelBox.InputSearchFields) {
			if (typeof(obj.SelBox.InputSearchFields[i]) == "object") {
				addEvent(obj.SelBox.InputSearchFields[i], "focus", function() { obj.hasFocus = true; });
				//obj.SelBox.InputSearchFields[i].onfocus= function() { obj.hasFocus = true; }
				/*addEvent(obj.SelBox.InputSearchFields[i], "keyup", function(e) { 
					obj.SelBox.focusField = this;
					obj.SelBox.SelBox_keyDown(e); 
				});
				*/
				obj.SelBox.InputSearchFields[i].onkeydown= function(e) { 
					obj.SelBox.focusField = this;
					obj.SelBox.SelBox_keyDown(e); 
				}/**/
				
				/*addEvent(obj.SelBox.InputSearchFields[i], "keyup", function(e) { 
					obj.SelBox.Items.focusField = obj.SelBox.InputSearchFields[i]; 
					obj.SelBox.Container.focusField = obj.SelBox.InputSearchFields[i]; 
					obj.SelBox.SelBox_sendQuery(e);
				});
				*/
				obj.SelBox.InputSearchFields[i].onkeyup= function(e) { 
					obj.SelBox.Items.focusField = obj.SelBox.InputSearchFields[i];
					obj.SelBox.Container.focusField = obj.SelBox.InputSearchFields[i]; 
					obj.SelBox.SelBox_sendQuery(e);
				}
			}
		}
		
		//alert("#167"); return false;
		if (obj.SelBox.Conf["LSIsLiveSearch"]) obj.SelBox.SelBox_sendQuery();
		else SelBox_initChilds(obj.SelBox.Items.id);
		addEvent(obj, "blur", function() { obj.hasFocus=false; setTimeout("SelBox_checkBlur('"+obj.SelBox.Container.id+"')",200); });
		//obj.onblur = function() { obj.hasFocus=false; setTimeout("SelBox_checkBlur('"+obj.SelBox.Container.id+"')",200); };
	} else {
		if (obj.SelBox.Conf["LSIsLiveSearch"]) obj.SelBox.SelBox_sendQuery();
	}
	obj.SelBox.focusField = obj;
	obj.SelBox.SelBox_initDisplay();
}

function SelBox_InputSelektor(sSelektor) {
	// Bsp: #MA1 input:name=MA[ort][]
	if (typeof(sSelektor) == "object") return sSelektor;
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

function SelBox_show() {
	var l = PageInfo.getElementLeft(this.inputField); //			: function(element) { var element = (typeof element == "string") ? document.getElementById(element) : element; var left = element.offsetLeft; var oParent = element.offsetParent; while (oParent != null) { left += oParent.offsetLeft; oParent = oParent.offsetParent; } return left; },
	var t = PageInfo.getElementTop(this.inputField); //			 : function(element) { var element = (typeof element == "string") ? document.getElementById(element) : element; var top = element.offsetTop; var oParent = element.offsetParent; while (oParent != null) { top += oParent.offsetTop; oParent = oParent.offsetParent; } return top; },
	var w = PageInfo.getElementWidth(this.inputField); //		 : function(element) { var element = (typeof element == "string") ? document.getElementById(element) : element; return element.offsetWidth; },
	var h = PageInfo.getElementHeight(this.inputField); //
	
	this.Container.style.top = (t+h-1)+"px";
	this.Container.style.left= l+"px";
	this.Container.style.width = Math.max(w,350).toString()+"px"; //w+"px";
	this.Container.style.display = '';
	//this.Container.style.height = this.SelBoxMaxHeight;
	this.Container.style.overflow = "auto";
	if(navigator.userAgent.indexOf("MSIE")==-1) this.Container.style.width = "300px"; //w+"px";
	//fb_AjaxRequest('umzugsantrag_livesearchresult_ma.xml?'+'&refresh='+(new Date()).getTime(), 'get', 'fb_AjaxXmlUpdate(%req%)');
	// alert("hoho uart1 =	l:"+l+", t:"+t+", w:"+w+", h:"+h+"; o.id:"+o.id+", o.style.top:"+o.style.top+"");
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
	if (this) {
		if (this.Items.iterationActiveItem) {
			SelectedData = new Array(this.Items.iterationActiveItem.getElementsByTagName("input")[0].value);
			insert = true;
			if (typeof(this.Conf["onBeforeInsert"]) == "function") insert = callFunction(this.Conf["onBeforeInsert"], SelectedData, this);
			if (insert) {
				if (!this.Conf["LSMultiple"]) {
					if (this.InputResultFields.length > 1) SelectedData = SelectedData[0].split(";");
					for (i = 0; i < SelectedData.length; i++) {
						if (this.InputResultFields[i]) this.InputResultFields[i].value = SelectedData[i];
					}
				} else {
					inputFld = this.InputResultFields[0];
					if (this.Items.iterationActiveItem.isSelected) {
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
						this.SelBox_sendQuery(false);
						runNext = true;
					}
				}
			//}
		}
	}
	if (!this.Conf["LSMultiple"] && !runNext) this.SelBox_hide();
	if (typeof(this.Conf["onAfterInsert"]) == "function") callFunction(this.Conf["onAfterInsert"], SelectedData, this);
	return false;
}

// Allgemeines Iteration-Object-ListItems in Livesearch-Box
// Wird an Eingabebox gebunden z.B. durch show_ma() und wird aufgerufen 
// von der ans Eingabefeld gebundenen Funktion SelBox_keyDown
// SelBox_keyDown übergibt die Laufrichtung an Iteration-Object : up | down
// SelBox_initChilds(SelBoxId) muss von Ajax aufgerufen werden, damit die neuen Objekte ansprechbar sind

function SelBox_filterItems() {
	//alert("#285 "+typeof(this)+";"+typeof(this.Items)+";"+typeof(this.Items.childs));
	if(typeof(this)!="object" || typeof(this.Items)!="object" || typeof(this.Items.childs)!="object") return false;
	//alert("#286 "+this.Items.childs);
	this.inputField.focus();
	var v = this.inputField.value; while(v.indexOf(" ")==0) v = v.substr(1);
	for (var i = 0; i < this.Items.childs.length; i++) {
		//alert("filterItems: "+this.Items.childs[i].getElementsByTagName("input")[0].value+"; "+this.inputField.value);
		if (v == "" || this.Items.childs[i].getElementsByTagName("input")[0].value.toLowerCase().indexOf(this.inputField.value.toLowerCase())==0) {
			this.Items.childs[i].style.display = "block";
		} else {
			this.Items.childs[i].style.display = "none";
		}
	}
}

function SelBox_sendQuery(e, trackObj) {
	alert("#264 SelBox_sendQuery(e, trackObj)");
	if ((typeof e) != "object"	&& (typeof event) == "object") e = event;
	if (e) {
		var eCharCode = (typeof(e.keyCode) == "number") ? e.keyCode : (typeof(e.charCode)=="number" ?	e.charCode : "");
		if(eCharCode==38 || eCharCode==20) return false;
	}
	 
	var inputObj = (this && this.inputField) ? this.inputField : ((trackObj) ? trackObj: false);
	if (!inputObj || !inputObj.hasFocus) return false;
	if (!trackObj) inputObj.lastKeyEventTime = (new Date()).getTime();
	
	if (inputObj.SelBox.Conf["LSAutoFilter"]) {
		if (typeof(inputObj.SelBox.lastInput) == "string" && (inputObj.SelBox.lastInput == "" || inputObj.value.indexOf(inputObj.SelBox.lastInput) == 0)) {
			inputObj.SelBox.SelBox_filterItems();
			return true;
		}
	}
	
	if (!inputObj.SelBox.Conf["LSIsDynamic"] && inputObj.SelBox.loaded) return false;
	
	var query = "";
	if (inputObj.lastKeyEventTime && (new Date()).getTime()-inputObj.lastKeyEventTime>1000) {
		for (var i in inputObj.SelBox.InputSearchFields) query+= "&q["+i+"]="+escape(inputObj.SelBox.InputSearchFields[i].value);
		if (inputObj.SelBox.additionalQuery) query+= inputObj.SelBox.additionalQuery;
		if (!inputObj.SelBox.refreshQuery && inputObj.SelBox.lastQuery && inputObj.SelBox.lastQuery == query) return false;
		inputObj.SelBox.lastQuery = query;
		inputObj.SelBox.lastInput = inputObj.value;
		inputObj.SelBox.refreshQuery =false;
		inputObj.SelBox.loaded = true;
		if ((typeof SelBoxTimeout) != "undefined" && SelBoxTimeout) { clearTimeout(SelBoxTimeout); SelBoxTimeout=false; }
		AjaxRequestUrl = inputObj.SelBox.Conf["LSUrl"]+'?'+query;
		AjaxRequestUrl+= '&LSBoxId='+inputObj.SelBox.Items.id;
		AjaxRequestUrl+= '&LSMultiple='+(inputObj.SelBox.Conf["LSMultiple"]?1:0);
		AjaxRequestUrl+= '&LSName='+inputObj.SelBox.Conf["LSName"];
		AjaxRequestUrl+= '&refresh='+(new Date()).getTime();
		//alert(AjaxRequestUrl);
		fb_AjaxRequest(AjaxRequestUrl, 'get', 'fb_AjaxXmlUpdate(%req%)');
		//alert("#339 SelBox_sendQuery()inputObj.InputSearchFields.length:"+inputObj.InputSearchFields.length);
	} else {
		SelBox_LS_TimeOutEvent = e;
		SelBox_LS_TimeOutObj = inputObj;
		SelBox_LS_TimeOutDelay = inputObj.SelBox.Conf["LSDelayMSec"];		
		SelBoxTimeout = setTimeout("SelBox_sendQuery(SelBox_LS_TimeOutEvent,SelBox_LS_TimeOutObj)", SelBox_LS_TimeOutDelay);
	}
}

function SelBox_nextResult() {
	//alert("SelBox_nextResult()");
	AjaxRequestUrl = this.Conf["LSUrl"]+'?'+this.Items.LSResultQuery;
	AjaxRequestUrl+= '&LSBoxId='+this.Items.id;
	AjaxRequestUrl+= '&LSMultiple='+(this.Conf["LSMultiple"]?1:0);
	AjaxRequestUrl+= '&LSName='+this.Conf["LSName"];
	AjaxRequestUrl+= '&LSOffset='+this.LSResultNextOffset;
	AjaxRequestUrl+= '&refresh='+(new Date()).getTime();
	//alert(AjaxRequestUrl);
	fb_AjaxRequest(AjaxRequestUrl, 'get', 'fb_AjaxXmlUpdate(%req%)');
}

function SelBox_keyDown(e) {
	 if (!e) e = event;
	 if (e.keyCode) eCharCode = e.keyCode;
	 else if (e.charCode) eCharCode = e.charCode;
	 else return false;
	 
	 if (eCharCode == 40 && this.Container.style.display == "none") this.SelBox_show();
	 if (this.Container.style.display == "none") return false;
	 
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
	 
	 if (action == "selectHoverItem" && this.Items.iterationHoverItem) {
	 	this.Items.iterationHoverItem.selectItem();
	 }
	 
	 //parentObj = this.SelBox;	// parentObj = document.getElementById("SelBoxMaItems");
	 if ((action=="up" || action=="down") && this.Items.nextItem) {
	 	if (this.Container.style.display == "none") this.SelBox_initDisplay();
		this.Items.nextItem(action);
		this.Items.onclick = function() { this.SelBox.inputField.hasFocus=true; this.SelBox.focusField.focus(); }
		if (e.preventDefault) e.preventDefault();
		if (e.stopPropagation) e.stopPropagation();
	 }
	 if (eCharCode == 13) this.insert();
	 
	 // More Details of keyCapturing in js/EventHandler.js
	 //return false; // return false verhindert Texteingabe im Eingabefeld
}

function SelBox_nextChild(dir) {
	//alert("getNextChild("+dir+") this.childs.length:"+this.childs.length+"; this.iterationHoverId:"+this.iterationHoverId);
	if (!this.childs.length) return false;
	this.activeSwitch = (this.activeSwitch) ? 0 : 1;
	var oldChildID = this.iterationHoverId;
	var nextChildID = (dir == "down") ? this.iterationHoverId+1 : this.iterationHoverId-1;
	if (nextChildID < 0) nextChildID = this.iterationMaxId;
	if (nextChildID > this.iterationMaxId) nextChildID = 0;
	//alert("#343 nextChildID:"+nextChildID);
	if (!this.childs[nextChildID]) {
		//document.getElementById("terminwunsch").value = this.activeSwitch+" "+dir+" oldChildID:"+oldChildID+ "; nextChildID:"+nextChildID;
		return false;
	}
	this.childs[nextChildID].hoverItem();
	//document.getElementById("terminwunsch").value = this.activeSwitch+" "+dir+" oldChildID:"+oldChildID+ "; nextChildID:"+nextChildID;
	return false;
}

function SelBox_initChilds(SelBoxId) {
	var SelBoxItems = document.getElementById(SelBoxId); //"SelBoxMaItems");
	if (!SelBoxItems) return false;
	
	var InputObjValue = "";
	for(var i = 0; i < SelBoxItems.SelBox.InputResultFields.length; i++) {
		InputObjValue+= (i?";":"")+SelBoxItems.SelBox.InputResultFields[i].value;
	}
	SelBoxItems.childs = SelBoxItems.getElementsByTagName("div");
	
	SelBoxItems.iterationMaxId = SelBoxItems.childs.length-1;
	SelBoxItems.iterationActiveId = -1;
	SelBoxItems.iterationActiveItem = false;
	SelBoxItems.iterationHoverId = -1;
	SelBoxItems.iterationHoverItem = false;
	if (SelBoxItems.LSResultNum) {
		var rsNum = parseInt(SelBoxItems.LSResultNum);
		var rsNumAll = parseInt(SelBoxItems.LSResultNumAll);
		var rsOffset = parseInt(SelBoxItems.LSResultOffset);
		var rsLimit = parseInt(SelBoxItems.LSResultLimit);
		var rsName = parseInt(SelBoxItems.LSResultName);
		var rsQuery = parseInt(SelBoxItems.LSResultQuery);
		if (document.getElementById("SelBoxMoreResults")) ElmtMore =	document.getElementById("SelBoxMoreResults");
		else { ElmtMore = document.createElement("span"); ElmtMore.id = "SelBoxMoreResults"; }
		if (rsOffset+rsNum < rsNumAll) {
			//alert("rsOffset:"+rsOffset+"; rsLimit:"+rsLimit+"; rsNum:"+rsNum+"; rsNumAll:"+rsNumAll);
			nextResultStart= rsOffset+rsNum;
			nextResultEnde = ((nextResultStart + rsLimit) < rsNumAll ? (nextResultStart + rsLimit): rsNumAll);
			ElmtMore.innerHTML = "mehr Vorschläge: "+nextResultStart+"-"+nextResultEnde+"("+rsNumAll+")";
			if (ElmtMore.style) ElmtMore.style["cursor"] = "pointer";
			else alert("#389 "+ElmtMore+"; "+ElmtMore.style);
			
			ElmtMore.onclick = function() { 
				this.parentNode.SelBox.inputField.SelBox_nextResult(); 
			};
			SelBoxItems.appendChild(ElmtMore);
		} else { ElmtMore.style["display"] = "none"; }
	}
	
	if (SelBoxItems.lastChild) {
		//SelBoxItems.style["height"] = 
		recalcHeight = (PageInfo.getElementTop(SelBoxItems.lastChild)+PageInfo.getElementHeight(SelBoxItems.lastChild)-PageInfo.getElementTop(SelBoxItems))+"px";
		if (recalcHeight > 0) SelBoxItems.style.height = recalcHeight;
	}
	 
	if (SelBoxItems.SelBox.MaxHeight < PageInfo.getElementHeight(SelBoxItems)) {
		SelBoxItems.style["height"] = SelBoxItems.SelBox.MaxHeight+"px";
		SelBoxItems.style.overflow = "scroll";
		SelBoxItems.style.overflowX = "hidden";
		//alert("#267 SelBox_sendQuery.js SelBoxMaxHeight:"+SelBox.inputField.SelBoxMaxHeight+" elementHeight:"+PageInfo.getElementHeight(SelBox));
	}
	
	SelBoxItems.srollToChild = SelBox_scrollToChild;
	SelBoxItems.onclick = function() { this.SelBox.focusField.focus(); }
	
	PageInfo.getElementHeight(SelBoxItems);
	//alert("SelBox_initChilds SelBox.inputField.tagName:"+SelBox.inputField.tagName);
	if (!SelBoxItems.SelBox.insert) {
		SelBoxItems.SelBox.insert = function() {
			if (SelBoxItems.SelBox.inputField && SelBoxItems.iterationActiveItem) {
				switch(SelBoxItems.SelBox.inputField.tagName.toUpperCase()) {
					case "INPUT":
					case "TEXTAREA":
					SelBoxItems.SelBox.inputField.value = SelBoxItems.iterationActiveItem.getElementsByTagName("input")[0].value;
				 	break;
				
				 case "DIV":
				 	SelBoxItems.SelBox.inputField.innerHTML = SelBoxItems.iterationActiveItem.getElementsByTagName("input")[0].value;
				}
			}
		}
	}
	
	for (i = 0; i < SelBoxItems.childs.length; i++) {
		SelBoxItems.childs[i].iterationId = i;
		//SelBox.childs[i].iterationMax = SelBox.childs.length-1;
		SelBoxItems.childs[i].selectItem = function() {
			SelBoxItems.SelBox.focusField.focus();
			SelBoxItems.SelBox.inputField.hasFocus = true;
			var selectItem = (!SelBoxItems.SelBox.Conf["LSMultiple"] || !this.isSelected);
			if (!SelBoxItems.SelBox.Conf["LSMultiple"] && SelBoxItems.iterationActiveItem) {
				SelBoxItems.iterationActiveItem.className = "SelItem";
				SelBoxItems.iterationActiveItem.isSelected = false;
			}
			this.isSelected = selectItem;
			SelBoxItems.iterationActiveId = SelBoxItems.iterationId;
			SelBoxItems.iterationActiveItem = this;
			this.className = selectItem ? "SelItem IsActiveItem" : "SelItem IsHoverItem";
			if (this.getElementsByTagName("input")[0].type == "radio" || this.getElementsByTagName("input")[0].type == "checkbox")
				this.getElementsByTagName("input")[0].checked = selectItem;
			
			SelBoxItems.SelBox.insert();
		}
		
		SelBoxItems.childs[i].initItem = function() {
			SelBoxItems.SelBox.focusField.focus();
			SelBoxItems.SelBox.inputField.hasFocus = true;
			if (this.isSelected) {
				if (!SelBoxItems.SelBox.Conf["LSMultiple"] && SelBoxItems.iterationActiveItem && SelBoxItems.iterationActiveItem != this) {
					this.isSelected = false;
				}
			}
			if (this.isSelected) SelBoxItems.iterationActiveItem = this;
			this.className = this.isSelected ? "SelItem IsActiveItem" : "SelItem";
			if (this.getElementsByTagName("input")[0].type == "radio" || this.getElementsByTagName("input")[0].type == "checkbox")
				this.getElementsByTagName("input")[0].checked = this.isSelected;
		}
		
		SelBoxItems.childs[i].hoverItem = function() {
			SelBoxItems.SelBox.focusField.focus();
			SelBoxItems.SelBox.inputField.hasFocus = true;
			if (this.isHover) return false;
			if (SelBoxItems.iterationHoverItem) {
				SelBoxItems.iterationHoverItem.className = (!SelBoxItems.iterationHoverItem.isSelected)?"SelItem":"SelItem IsActiveItem";
				SelBoxItems.iterationHoverItem.isHover = false;
			}
			SelBoxItems.iterationHoverId = this.iterationId;
			SelBoxItems.iterationHoverItem = this;
			this.className = "SelItem IsHoverItem";
			this.isHover = true;
			//scrollToChild(this.parentNode, this);
			SelBox_scrollToChild(SelBoxItems, this);
		}
		
		SelBoxItems.childs[i].dehoverItem = function() {
			SelBoxItems.SelBox.inputField.hasFocus = true;
			SelBoxItems.SelBox.focusField.focus();
			if (!this.isHover) return false;
			this.className = (!this.isSelected)?"SelItem":"SelItem IsActiveItem";
			this.isHover = false;
			if (SelBoxItems.iterationHoverItem == this) SelBoxItems.iterationHoverItem = false;
		}
		
		SelBoxItems.childs[i].onfocus = function() { SelBoxItems.SelBox.inputField.hasFocus = true; };
		SelBoxItems.childs[i].onmouseover = SelBoxItems.childs[i].hoverItem;
		SelBoxItems.childs[i].onmouseout = SelBoxItems.childs[i].dehoverItem;
		SelBoxItems.childs[i].onclick = SelBoxItems.childs[i].selectItem;
		
		checkVal = SelBoxItems.childs[i].getElementsByTagName("input")[0].value;
		if (SelBoxItems.SelBox.Conf["LSMultiple"]) {
			// alert("#468 checkVal:"+checkVal+"; InputObjValue:"+InputObjValue);
			SelBoxItems.childs[i].isSelected = ((","+InputObjValue+",").indexOf(checkVal)!= -1);
		} else {
			SelBoxItems.childs[i].isSelected = (InputObjValue.indexOf(checkVal)==0);
		}
		SelBoxItems.childs[i].initItem();
	 }
		SelBoxItems.nextItem = SelBox_nextChild;
	 	if ((typeof SelBoxItems.LSResultOffset) != "undefined" && parseInt(SelBoxItems.LSResultOffset)>0 && SelBoxItems.childs.length>=parseInt(SelBoxItems.LSResultOffset)) {
		SelBox_scrollToChild(SelBoxItems, SelBoxItems.childs[parseInt(SelBox.LSResultOffset)-1]);
	}
}

//srollToChild = SelBox_scrollToChild(childObj)
function SelBox_scrollToChild(parentObj, childObj) {
	//parentObj = this;
	//childObj = this.SelBox.Items.iterationHoverItem;
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
	if (this.Container.style.display == 'none') {
		this.SelBox_show();
	} else {
		this.SelBox_hide();
	}
}
function SelBox_hide() {
	if (!this.Container) return false;
	this.Container.style.display = 'none';
}

function SelBox_checkBlur(SelBoxObjId) {
	var o = document.getElementById(SelBoxObjId);
	if (!o.inputField.hasFocus) o.style.display = 'none';
}

function SelBox_isVisible() {
	
}
// Ende