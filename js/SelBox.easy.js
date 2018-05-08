function SelBox_capture(parentbox, SBConf, optionsData, defaultValue) {
	if (typeof(O(parentbox)) != "object" || typeof(O(parentbox).tagName)!="string") return false;
	
	//if (parentbox.SBConf) SelBox_release(parentbox);
	var parentbox = O(parentbox);
	if (typeof(SBConf) == "object") {
		parentbox.SBConf = SBConf;
	} else SBConf = new Object();
	
	parentbox.SBConf["InputField"] = (typeof(SBConf["InputField"])!="undefined") ? O(SBConf["InputField"]) : null;
	parentbox.SBConf["InputSrc"] = (typeof(SBConf["InputSrc"])=="string") ? SBConf["InputSrc"] : "content";
	parentbox.SBConf["InputFrontTrunc"] = (typeof(SBConf["InputFrontTrunc"])!="undefined") ? SBConf["InputFrontTrunc"] : false;
	parentbox.SBConf["OnSelect"] = (typeof(SBConf["OnSelect"])=="function") ? SBConf["OnSelect"] : null;
	parentbox.SBConf["OnHover"] = (typeof(SBConf["OnHover"])=="function") ? SBConf["OnHover"] : null;
	parentbox.SBConf["OnCapture"] = (typeof(SBConf["OnCapture"])=="function") ? SBConf["OnCapture"] : null;
	parentbox.SBConf["OnRelease"] = (typeof(SBConf["OnRelease"])=="function") ? SBConf["OnRelease"] : null;
	parentbox.SBConf["OnSelectClass"] = (typeof(SBConf["OnSelectClass"])=="string") ? SBConf["OnSelectClass"] : "selectedItem";
	parentbox.SBConf["OnHoverClass"] = (typeof(SBConf["OnHoverClass"])=="string") ? SBConf["OnHoverClass"] : "hoverItem";
	parentbox.SBConf["OnEnterClose"] = (typeof(SBConf["OnEnterClose"])!="undefined") ? SBConf["OnEnterClose"] : true;
	parentbox.SBConf["Multiple"] = (SBConf["Multiple"] && 1); // Wird (noch) nicht unterstützt!
	parentbox.SBConf["MultipleSeparator"] = (typeof(SBConf["MultipleSeparator"])=="string") ? SBConf["MultipleSeparator"] : ","; // Wird (noch) nicht unterstützt!
	//alert(parentbox.SBConf["OnSelect"]);
	
	if (parentbox.SBConf["OnCapture"] && typeof(parentbox.SBConf["OnCapture"])=="function") {
		callFunction(parentbox.SBConf["OnCapture"], parentbox);
	}
	
	parentbox.captureEvents = true;
	parentbox.selectedItem = -1;
	parentbox.lastInput = null;
	if (typeof(optionsData)=="object") {
		if (typeof(defaultValue)=="undefined") defaultValue = "";
		if (optionsData[0] && ("value" in optionsData[0]))
			SelBox_loadData(parentbox, optionsData, defaultValue);
		else 
			SelBox_loadAssoc(parentbox, optionsData, defaultValue);
	}
	if (typeof(parentbox.SBConf["InputField"])=="object") {
		parentbox.lastInput = parentbox.SBConf["InputField"].value;
		parentbox.SBConf["InputField"].focus();
		parentbox.OriginalAutoComplete = parentbox.SBConf["InputField"].getAttribute("autocomplete");
		//alert("parentbox.SBConf[InputField].getAttribute(autocomplete):"+parentbox.SBConf["InputField"].getAttribute("autocomplete"));
		parentbox.SBConf["InputField"].setAttribute("autocomplete", "off");
		parentbox.SelBox_checkInput = SelBox_checkInput;
		parentbox.SelBox_checkBlur = SelBox_checkBlur;
		parentbox.SelBox_checkDisplay = SelBox_checkDisplay;
		removeEvent(parentbox.SBConf["InputField"], "keydown", function(e) { parentbox.SelBox_checkDisplay(SBConf, optionsData, e) });
		addEvent(parentbox.SBConf["InputField"], "keydown",  function(e) { parentbox.SelBox_checkDisplay(SBConf, optionsData, e) });
		addEvent(parentbox.SBConf["InputField"], "keyup",  function(e) { parentbox.SelBox_checkInput(e) });
		addEvent(parentbox.SBConf["InputField"], "blur",   function() { this.hasFocus = false; parentbox.SelBox_checkBlur() });
		//addEvent(parentbox.SBConf["InputField"], "focus",   function() { parentbox.SBConf["InputField"].hasFocus = true; return true });
		/*parentbox.SBConf["InputField"].onkeydown = function(e) { 
			parentbox.SelBox_checkDisplay(SBConf, optionsData, e);
			parentbox.SelBox_checkKeyArrows(e);
		}*/
	}
	SelBox_initSubItems(parentbox);
	parentbox.SelBox_checkKeyArrows = SelBox_checkKeyArrows;
	parentbox.SelBox_checkKeyEnter = SelBox_checkKeyEnter;
	addEvent(document, "keydown", function(e) { parentbox.SelBox_checkKeyArrows(e)});
	//removeEvent(document, "keydown", function(e) { parentbox.SelBox_checkKeyArrows(e)});
	addEvent(document, "keypress",function(e) { parentbox.SelBox_checkKeyEnter(e) });
	addEvent(parentbox,   "blur",    function()  { parentbox.SelBox_checkBlur() });
	parentbox.style.display = "block";
	SelBox_fitHeight(parentbox);
	//SelBox_fitWidth(parentbox);
	//alert("parentbox.SBConf[InputField].scrollWidth:"+parentbox.SBConf["InputField"].scrollWidth);
	////document.getElementsByTagName("textarea")[0].value = ("parentbox.selectedItem: "+parentbox.selectedItem);
}

function SelBox_release(parentbox, destroy) {
	if (typeof(O(parentbox)) != "object" || typeof(O(parentbox).tagName)!="string") return false;
	var parentbox = O(parentbox);
	
	
	if (parentbox.SBConf && typeof(parentbox.SBConf)=="object" && typeof(parentbox.SBConf["OnRelease"])=="function")
		callFunction(parentbox.SBConf["OnRelease"], parentbox);
	
	if (typeof(parentbox.SelBox_checkKeyArrows)== "function") {
		removeEvent(document, "keydown", function(e) {parentbox.SelBox_checkKeyArrows(e)});
		//removeEvent(document, "keydown", parentbox.SelBox_checkKeyArrows);
	}
	
	if (typeof(parentbox.SelBox_checkKeyEnter) == "function")
		removeEvent(document, "keypress", function(e) {parentbox.SelBox_checkKeyEnter(e)});
	
	if (typeof(parentbox.SelBox_checkBlur) == "function")
		removeEvent(parentbox, "blur",   function() { parentbox.SelBox_checkBlur() });
	
	
	//var msg = ""; for(var i in parentbox) msg+= i+":"+parent[i]+"\n"; alert(msg);
	if (typeof(parentbox)=="object" 
	&& parentbox.SBConf!=null && typeof(parentbox.SBConf)=="object" 
	&& typeof(parentbox.SBConf["InputField"])=="object" 
	&& typeof(parentbox.SelBox_checkInput) == "function") {
		//parentbox.SBConf["InputField"].setAttribute("autocomplete", parentbox.OriginalAutoComplete);
		
		if (destroy) removeEvent(parentbox.SBConf["InputField"], "keydown", function(e) { parentbox.SelBox_checkDisplay(SBConf, optionsData, e) });
		removeEvent(parentbox.SBConf["InputField"], "keyup",   function(e) { parentbox.SelBox_checkInput(e) });
		removeEvent(parentbox.SBConf["InputField"], "blur",   function() { this.hasFocus = false; parentbox.SelBox_checkBlur() });
		//removeEvent(parentbox.SBConf["InputField"], "focus",   function() { parentbox.SBConf["InputField"].hasFocus = true; return true });
		//parentbox.SBConf["InputField"].onkeydown = null;
	}
	parentbox.lastInput = null;
	SelBox_selectItem(parentbox, '-1');
	parentbox.captureEvents = false;
	parentbox.SBConf = false;
	parentbox.style.display = "none";
	
	if (destroy) {
		if (typeof(parentbox.SelBox_checkDisplay)=="function") parentbox.SelBox_checkDisplay = null;
		if (typeof(parentbox.SelBox_checkInput)=="function") parentbox.SelBox_checkInput = null;
		if (typeof(parentbox.SelBox_checkBlur)=="function") parentbox.SelBox_checkBlur = null;
		if (typeof(parentbox.SelBox_checkKeyEnter)=="function") parentbox.SelBox_checkKeyEnter = null;
		if (typeof(parentbox.SelBox_checkKeyArrows)=="function") parentbox.SelBox_checkKeyArrows = null;
		/**/
	}
}

function SelBox_scrollToSubItem(parentObj, childObj) {
	if (typeof(O(parentObj)) != "object" || typeof(O(parentObj).tagName)!="string") return false;
	if (typeof(O(parentObj))!="object" || typeof(O(childObj))!="object") return false;
	
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

function SelBox_checkKeyArrows(e) {
	 ////document.getElementsByTagName("textarea")[0].value = "SelBox_checkKeyArrows this:"+this+"; this.tagName:"+this.tagName;
	 if (typeof(this) != "object" || typeof(this.tagName)!="string" || !this.captureEvents) return false;
	 
	 if (!e) e = window.event;
	 if (e.keyCode) eCharCode = e.keyCode;
	 else if (e.charCode) eCharCode = e.charCode;
	 else return false;
	 
	 var returnValue = true;
	 var msg = "";
	 var action = "";
	 switch(eCharCode) {
	 	case 38: cancelEvent(e); returnValue = false; SelBox_selectItem(this, 'up'); break;
	 	case 40: cancelEvent(e); returnValue = false; SelBox_selectItem(this, 'down'); break;
	 	case 32: break; // Space-Taste
		case 13: break; // Enter-Taste
		
		default:
		
		SelBox_selectItem(this, -1);
		//return false;
		//alert("eCharCode:"+eCharCode);
	 }
	 return returnValue;
}

function SelBox_checkKeyEnter(e) {
	 if (typeof(this) != "object" || !this.captureEvents) return false;
	 if (!e) e = event;
	 if (e.keyCode) eCharCode = e.keyCode;
	 else if (e.charCode) eCharCode = e.charCode;
	 else return false;
	 //alert(eCharCode);
	 
	 var returnValue = true;
	 var msg = "";
	 var action = "";
	 if (typeof(KeyEventPreviousCharCode)!="number") KeyEventPreviousCharCode = 0;
	 switch(eCharCode) {
	 	//case 32: // Space: Insert
		case 13: // Enter: Insert
		//if (KeyEventPreviousCharCode == 38 || KeyEventPreviousCharCode == 40) {
			//alert(eCharCode+" selectedItem:"+O('Items').selectedItem);
			if (typeof(this.selectedItem)!="undefined" && this.selectedItem != -1) {
				//alert(O('Items').getElementsByTagName('div')[O('Items').selectedItem].innerHTML);
				if (typeof(document.preventDefault)=="function") document.preventDefault();
				cancelEvent(e);
				
				SelBox_enterSubItem(this);
				returnValue = false;
			}
		//}
		break;
		
		case 27: // Escape-Taste: Destroy SelBox
		SelBox_release(this, true);
		return false;
		break;
		
		default:
		//return false;
		//alert("eCharCode:"+eCharCode);
	}
	KeyEventPreviousCharCode = eCharCode;
	return returnValue;
}

function SelBox_checkInput() {
	if (typeof(this) != "object" || !this.captureEvents) return false;
	var filterData = true;
	var splittedValues = new Array();
	
	if (typeof(this.SBConf["InputField"])=="object" && typeof(this.SBConf["InputField"].lastInput)=="string") {
		if (this.SBConf["InputField"].lastInput == this.SBConf["InputField"].value) return false;
	}
	
	if (typeof(this.SBConf["OnInput"])=="function")
		filterData = callFunction(this.SBConf["OnInput"], this);
	
	if (filterData && typeof(this.SBConf["InputField"])=="object") {
		var frontTrunc = (this.SBConf["InputFrontTrunc"]) ? "*" : "";
		if (!this.SBConf["Multiple"]) {
			SelBox_filterData(this, frontTrunc+this.SBConf["InputField"].value);
		} else {
			splittedValues = this.SBConf["InputField"].value.split(this.SBConf["MultipleSeparator"]);
			SelBox_filterData(this, splittedValues[splittedValues.length-1]);
		}
	}
	if (typeof(this.SBConf["InputField"])=="object") this.SBConf["InputField"].lastInput = this.SBConf["InputField"].value;
}
function SelBox_release_dummie(parentbox) {}
function SelBox_checkBlur() {
	if (!this) return false;
	if (!this.SBConf) { SelBox_release(this); return true; }
	if (this.SBConf["InputField"].hasFocus) return false;
	var x = PageInfo.getMouseX();
	var y = PageInfo.getMouseY();
	var b = 3; // Toleranz über Div-Rahmen hinaus in Pixel
	
	var SBT = PageInfo.getElementTop(this);
	var SBL = PageInfo.getElementLeft(this);
	var SBR = PageInfo.getElementLeft(this)+PageInfo.getElementWidth(this);
	var SBB = PageInfo.getElementTop(this)+PageInfo.getElementHeight(this);
	
	MouseIsOutOfBox = (x < (SBL-b) || x > (SBR+b) || y < (SBT) || y > (SBB+b)); 
	//alert("#244 MouseIsOutOfBox:"+MouseIsOutOfBox+" x:"+x+" y:"+y+" T:"+SBT+" L:"+SBL+" R:"+SBR+" B:"+SBB);
	
	SelBox_parentbox = this;
	var reCheck = (arguments.length)?arguments[0]:0;
	
	if (reCheck!=1 || !MouseIsOutOfBox) setTimeout("if (SelBox_parentbox && typeof(SelBox_parentbox.SelBox_checkBlur)=='function') SelBox_parentbox.SelBox_checkBlur(1)", 500);
	else SelBox_release(this);
}

function SelBox_checkDisplay(SBConf, optionsData, e) {
	////document.getElementsByTagName("textarea")[0].value = (new Date()).getTime()+" SelBox_checkDisplay this.captureEvents:"+this.captureEvents;
	if (!this) return false;
	if (!this.captureEvents) SelBox_capture(this, SBConf, optionsData);
}

function SelBox_initSubItems(parentbox) {
	if (typeof(O(parentbox))!="object") return false;
	parentbox = O(parentbox);
	if (!parentbox.getElementsByTagName) return false;
	
	//var m="";for(ii in parentbox)m+=ii+":"+parent[ii]+"\n";alert(m);
	var aSubItems = parentbox.getElementsByTagName("div");
	////document.getElementsByTagName("textarea")[0].value+= ("\nSelBox_initSubItems #3: aSubItems.length:"+aSubItems.length);
	for (var i =0; i < aSubItems.length; i++) {
		
		SubItem = aSubItems[i];
		if (typeof(SubItem.content)!="string") SubItem.content = SubItem.innerHTML;
		SubItem.SubIndex = i;
		SubItem.hover = function() { SelBox_selectItem(parentbox, this.SubIndex); }
		SubItem.onmouseover = SubItem.hover;
		SubItem.onclick = function() { SelBox_enterSubItem(parentbox); };
		////document.getElementsByTagName("textarea")[0].value+= ("\nSelBox_initSubItems #4: init-Item .:"+i);
	}
}


	
function SelBox_enterSubItem(parentbox) {
	if (typeof(O(parentbox))!="object" || !O(parentbox).captureEvents) return false;
	var parentbox = O(parentbox);
	if (parentbox.selectedItem != -1 && parentbox.getElementsByTagName("div")[parentbox.selectedItem]) {
		var SubItem = parentbox.getElementsByTagName("div")[parentbox.selectedItem];
		var SubItemValue = "";
		switch(parentbox.SBConf["InputSrc"]) {
			case "innerHTML": 	SubItemValue = SubItem.innerHTML; break;
			case "content": 	SubItemValue = SubItem.content; break;
			case "value":
			default:		SubItemValue = SubItem.value;
		}
		if (typeof(parentbox.SBConf)=="object") {
			if (typeof(parentbox.SBConf["InputField"])=="object") {
				parentbox.SBConf["InputField"].focus();
			}
			if (typeof(parentbox.SBConf["OnSelect"])=="function") {
				var continueProcess = callFunction(parentbox.SBConf["OnSelect"], parentbox);
				if (!continueProcess) return true;
				//eval(parentbox.SBConf["OnSelect"]+"(parentbox);\n");
				//alert("#119 "+parentbox.SBConf["OnSelect"]);
			} else if (typeof(parentbox.SBConf["InputField"])=="object") {
				if (!parentbox.SBConf["Multiple"]) {
					parentbox.SBConf["InputField"].value = SubItemValue;
				} else {
					var v = parentbox.SBConf["InputField"].value;
					var splittedValues = v.split(parentbox.SBConf["MultipleSeparator"]);
					
					while(v.substr(v.length-1)==" ") v = v.substr(0, v.length-1);
					if (v.substr(v.length-1)==parentbox.SBConf["MultipleSeparator"]) {
						splittedValues[splittedValues.length] = SubItemValue;
					} else {
						splittedValues[splittedValues.length-1] = SubItemValue;
					}					
					parentbox.SBConf["InputField"].value = splittedValues.join(parentbox.SBConf["MultipleSeparator"])+parentbox.SBConf["MultipleSeparator"];
				}
			}
		}
	}
	//var m="";for(i in parentbox.SBConf)m+=i+":"+parentbox.SBConf[i]+"\n";alert(m);
	if (parentbox.SBConf["OnEnterClose"])
		SelBox_release(parentbox);
}

function SelBox_getNextItemKey(parentbox) {
	if (typeof(O(parentbox))!="object" || typeof(O(parentbox).selectedItem)=="undefined") return false;
	var parentbox = O(parentbox);
	var SubItems = parentbox.getElementsByTagName("div");
	var offset = (parentbox.selectedItem>-1)?parentbox.selectedItem+1:0;
	
	for (var i = offset; i < SubItems.length; i++) {
		if (typeof(SubItems[i])=="object" && SubItems[i].style.display!="none") return i;
	}
	return false;
}

function SelBox_getPrevItemKey(parentbox) {
	if (typeof(O(parentbox))!="object" || typeof(O(parentbox).selectedItem)=="undefined") return false;
	var parentbox = O(parentbox);
	var SubItems = parentbox.getElementsByTagName("div");
	var offset = (parentbox.selectedItem<SubItems.length)?parentbox.selectedItem-1:SubItems.length;
	
	for (var i = offset; i > -1; i--) {
		if (typeof(SubItems[i])=="object" && SubItems[i].style.display!="none") return i;
	}
	return false;
}

function SelBox_selectItem(parentbox, nextSelection) {
	// alert("SelBox_selectItem("+parentbox+", "+nextSelection+", "+selectionClass+")");
	if (typeof(O(parentbox))!="object" || !O(parentbox).captureEvents) return false;
	var parentbox = O(parentbox);
	if (!parentbox.getElementsByTagName) return false;
	
	var iDivs = parentbox.getElementsByTagName('div');
	var iLen = iDivs.length;
	var nextItem = null;
	var selectedItemOffset = parentbox.selectedItem;
	if (typeof(parentbox.selectedItem)=="undefined") parentbox.selectedItem = -1;
	selectionClass = parentbox.SBConf["OnSelectClass"];
	////document.getElementsByTagName("textarea")[0].value = ("parentbox.selectedItem: "+parentbox.selectedItem);
	switch(nextSelection) {
		case "down":
		//if (parentbox.selectedItem == "-1") nextItem = 0;
		//if (parentbox.selectedItem < iLen-1) nextItem = (parentbox.selectedItem*1)+1;
		nextItem = SelBox_getNextItemKey(parentbox);
		break
		
		case "up":
		//if (parentbox.selectedItem == "-1") nextItem = iLen-1;
		//else if (parentbox.selectedItem) nextItem = parentbox.selectedItem-1;
		nextItem = SelBox_getPrevItemKey(parentbox);
		break;
		
		case -1: case "-1":
		nextItem = -1;
		break;
		
		default:
		if (parseInt(nextSelection).toString() == nextSelection) nextItem = nextSelection*1;
	}
	
	////document.getElementsByTagName("textarea")[0].value = ("selectedItemOffset: "+selectedItemOffset+"; parentbox.selectedItem: "+parentbox.selectedItem+"; nextItem: "+nextItem);
	if (typeof(nextItem) == "number") {
		if (parentbox.selectedItem != -1) RC(iDivs[parentbox.selectedItem], selectionClass);
		if (nextItem != -1 && typeof(iDivs[nextItem])=="object") AC(iDivs[nextItem], selectionClass);
		parentbox.selectedItem = nextItem;
		if (nextItem != -1) SelBox_scrollToSubItem(parentbox, iDivs[nextItem]);
		if (typeof(parentbox.SBConf) == "object" && typeof(parentbox.SBConf["OnHover"])=="function") {
			//eval(parentbox.SBConf["OnHover"]+"(parentbox, nextItem);\n");
			callFunction(parentbox.SBConf["OnHover"], parentbox, nextItem);
		}
		if (nextItem != -1 && typeof(iDivs[nextItem])=="object") return iDivs[nextItem];
	} else {
		//alert("#93 nextItem:"+nextItem);
		return false
	}
}

function SelBox_loadAssoc(parentbox, optionsData, defaultValue) {
	var fitOptionsData = new Array();
	for(var i in optionsData) {
		fitOptionsData[fitOptionsData.length] = {
			value:i,
			content:optionsData[i]
		}
	}
	return SelBox_loadData(parentbox, fitOptionsData, defaultValue);
}

function SelBox_loadData(parentbox, optionsData, defaultValue) {
	if (typeof(O(parentbox))!="object") return false;
	if (typeof(optionsData)!="object") return false;
	
	if (!defaultValue || typeof(defaultValue)!="string") {
		if (typeof(parentbox.SBConf)=="object" && typeof(parentbox.SBConf["InputField"])=="object") {
			if (parentbox.SBConf["InputField"].value) defaultValue = parentbox.SBConf["InputField"].value;
		}
	}
	
	var parentbox = O(parentbox);
	parentbox.innerHTML = "";
	var selectedItemKey = -1;
	var item;
	for(var i = 0; i < optionsData.length; i++) {
		if (typeof(optionsData[i])=="object" && typeof(optionsData[i]["value"])=="undefined") continue;
		if (!parentbox.appendChild) break;
		
		item = document.createElement("div");
		if (typeof(optionsData[i])!="object") {
			item.value = optionsData[i];
			item.content = optionsData[i];
			item.innerHTML = optionsData[i];
		} else {
			for(j in optionsData[i]) item[j] = optionsData[i][j];
			if (typeof(optionsData[i]["setClassName"])=="string") {
				AC(item, optionsData[i]["setClassName"]);
			}
			if (typeof(item.content)=="undefined") item.content = item.value;
			item.content = StripTags(item.content);
			item.innerHTML = optionsData[i]["content"];
		}
		parentbox.appendChild(item);
		if (defaultValue == item.value) selectedItemKey = i;
	}
	SelBox_initSubItems(parentbox);
	SelBox_selectItem(parentbox, selectedItemKey);
	SelBox_fitHeight(parentbox);
	return parentbox;
}

function SelBox_filterData(parentbox, query) {
	if (typeof(O(parentbox))!="object") return false;
	O('Display').innerHTML = "SelBox_filterData: "+query;
	var parentbox = O(parentbox);
	var frontTrunc = (query.indexOf("*") == 0);
	if (frontTrunc) query = query.substr(1);
	var SubItems = parentbox.getElementsByTagName("div");
	
	var IsUserFilter = (typeof(parentbox.SBConf["OnFilter"])=="function");
	for (var i = 0; i < SubItems.length; i++) {
		if (!IsUserFilter) {
			if (query == "") SubItems[i].style.display = "block";
			else if (frontTrunc) SubItems[i].style.display = (SubItems[i].content.toLowerCase().indexOf(query.toLowerCase())!=-1)?"block":"none";
			else SubItems[i].style.display = (SubItems[i].content.toLowerCase().indexOf(query.toLowerCase())==0)?"block":"none";
		} else {
			callFunction(parentbox.SBConf["OnFilter"], parentbox, SubItems[i], query, frontTrunc);
		}
		//O('Display').innerHTML = "SelBox_filterData: "+query+" ("+SubItems[i].content.toLowerCase()+").indexOf("+query.toLowerCase()+"):"+SubItems[i].content.toLowerCase().indexOf(query.toLowerCase());
	}
	SelBox_selectItem(parentbox, "down");
	SelBox_fitHeight(parentbox);
	return parentbox;
}

function SelBox_addControlBox(parentbox, content, pos) {
	if (typeof(O(parentbox))!="object") return false;
	if (typeof(pos)=="undefined") pos = "";
	if (parentbox.firstChild && typeof(parentbox.firstChild)!="object") pos = "append";
	var CBox = document.createElement("span");
	if (typeof(content)=="object") CBox.appendChild(content);
	else CBox.innerHTML = content;
	
	switch(pos.toLowerCase()) {
		case "append":
		parentbox.appendChild(CBox);
		break;
		
		default:
		if (parentbox.firstChild && typeof(parentbox.firstChild)=="object")
			parentbox.insertBefore(CBox, parentbox.firstChild);
		else
			parentbox.appendChild(CBox);
	}
}

function SelBox_fitHeight(parentbox) {
	if (typeof(O(parentbox)) != "object" || typeof(O(parentbox).tagName)!="string") return false;
	var parentbox = O(parentbox);
	parentbox.style["overflow"] = "hidden";
	parentbox.style.overflowY = "auto";
	parentbox.style.height = "auto";
	//alert("parentbox.SBConf[MaxHeight]:"+parentbox.SBConf["MaxHeight"]+"\nparentbox.scrollHeight:"+parentbox.scrollHeight);
	if (typeof(parentbox.SBConf)=="object" && typeof(parentbox.SBConf["MaxHeight"])=="number" && parentbox.SBConf["MaxHeight"] < parentbox.scrollHeight) {
		//alert("#353 SelBox_fitHeight: Set Max Height "+parentbox.SBConf["MaxHeight"]);
		parentbox.style.height = parentbox.SBConf["MaxHeight"]+"px";
	}
	////document.getElementsByTagName("textarea")[0].value = ("parentbox.scrollHeight: "+parentbox.scrollHeight+" ("+parentbox.SBConf["InputField"].value+")");
	return parentbox;
}

function SelBox_fitWidth(parentbox) {
	if (typeof(O(parentbox)) != "object" || typeof(O(parentbox).tagName)!="string") return false;
	var parentbox = O(parentbox);
	parentbox.style["overflow"] = "hidden";
	parentbox.style.width = "auto";
	//alert("parentbox.SBConf[InputField].scrollWidth:"+parentbox.SBConf["InputField"].scrollWidth);
	//alert("parentbox.SBConf[MaxWidth]:"+parentbox.SBConf["MaxWidth"]+"\nparentbox.scrollWidth:"+parentbox.scrollWidth);
	if (typeof(parentbox.SBConf)=="object" && typeof(parentbox.SBConf["MaxWidth"])=="number" && parentbox.SBConf["MaxWidth"] < parentbox.scrollWidth) {
		parentbox.style.Width = parentbox.SBConf["MaxWidth"];
	} else if (parentbox.scrollWidth) {
		//alert("#449 SelBox_fitWidth: Set ListWidth "+parentbox.scrollWidth);
		if (typeof(parentbox.SBConf)=="object" && typeof(parentbox.SBConf["InputField"])=="object"
		 && parentbox.SBConf["InputField"].scrollWidth > parentbox.scrollWidth) {
			parentbox.style.width = parentbox.SBConf["InputField"].scrollWidth+"px";
		}
	}
	////document.getElementsByTagName("textarea")[0].value = ("parentbox.scrollWidth: "+parentbox.scrollWidth+" ("+parentbox.SBConf["InputField"].value+")");
	return parentbox;
}


function callFunction(fn) {
	var r = false; // return
	var evalStr = "r = "+fn+"(";
	for (i = 1; i < arguments.length; i++) evalStr+= (i>1?",":"")+"arguments["+i+"]";
	evalStr+= ");\n";
	//var msg = "";for (i = 1; i < arguments.length; i++) msg+= (i>1?",":"")+", "+arguments[i]; //document.getElementsByTagName("textarea")[0].value = msg+"\n"+evalStr;
	eval(evalStr);
	return r;
}

function SelBox_getSelectedItem(parentbox) {
	if (typeof(O(parentbox)) != "object" || typeof(O(parentbox).tagName)!="string") return false;
	if (typeof(parentbox.selectedItem)=="number" && parentbox.selectedItem>-1 
	&& typeof(parentbox.getElementsByTagName("div")[parentbox.selectedItem])=="object") {
		return parentbox.getElementsByTagName("div")[parentbox.selectedItem];
	}
	return false;
}

function SelBox_getItem(parentbox, SubItemKey) {
	if (typeof(O(parentbox)) != "object" || typeof(O(parentbox).tagName)!="string") return false;
	if (typeof(O(parentbox))=="object" && typeof(SubItemKey)=="number" && SubItemKey > -1 && typeof(parentbox.getElementsByTagName("div")[SubItemKey])=="object")
		return parentbox.getElementsByTagName("div")[SubItemKey];
	
	return false;
}

function userdefined_checkInput(parentbox) {
	if (typeof(O(parentbox)) != "object" || typeof(O(parentbox).tagName)!="string") return false;
	
	if (typeof(parentbox.lastInput)!="string") parentbox.lastInput = null;
	
	if (parentbox.lastInput == null) {
		alert("#404 userdefined_checkInput()");
	} else if (parentbox.lastInput.length > parentbox.SBConf["InputField"].value.length) {
		//alert("#406 userdefined_checkInput()");
	}
	parentbox.lastInput = parentbox.SBConf["InputField"].value;
}

function userdefined_getSelection(parentbox) {
	if (typeof(O(parentbox)) != "object" || typeof(O(parentbox).tagName)!="string") return false;
	
	//alert("#290 "+parentbox.SBConf["OnSelect"]);
	var parentbox = O(parentbox);
	var SubItem = SelBox_getSelectedItem(parentbox);
	
	if (SubItem) {
		
		parentbox.SBConf["InputField"].value = SubItem.content;
		
		//alert("Hoi Hoi "+SubItem.value); 
	} else {
		parentbox.SBConf["InputField"].value = "";
	}
}

function userdefined_getHover(parentbox, hoverItemKey) {
	if (typeof(O(parentbox)) != "object" || typeof(O(parentbox).tagName)!="string") return false;
	
	var parentbox = O(parentbox);
	var SubItem = SelBox_getItem(parentbox, hoverItemKey);
	
	if (SubItem) O("Display").innerHTML = "Hoi Hoi "+SubItem.value;
}

var optDataSBConf = {
	InputField:"inputField",
	InputFrontTrunc:false,
	OnInput:userdefined_checkInput,
	OnSelect:userdefined_getSelection,
	OnHover:userdefined_getHover,
	OnSelectClass:"selectedItem",
	OnEnterClose:true,
	MaxHeight:200
};
