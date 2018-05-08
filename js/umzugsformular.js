
function show_date(targetElm) {
  var l = PageInfo.getElementLeft(targetElm); //      : function(element) { var element = (typeof element == "string") ? document.getElementById(element) : element; var left = element.offsetLeft; var oParent = element.offsetParent; while (oParent != null) { left += oParent.offsetLeft; oParent = oParent.offsetParent; } return left; },
  var t = PageInfo.getElementTop(targetElm); //       : function(element) { var element = (typeof element == "string") ? document.getElementById(element) : element; var top = element.offsetTop; var oParent = element.offsetParent; while (oParent != null) { top += oParent.offsetTop; oParent = oParent.offsetParent; } return top; },
  var w = PageInfo.getElementWidth(targetElm); //     : function(element) { var element = (typeof element == "string") ? document.getElementById(element) : element; return element.offsetWidth; },
  var h = PageInfo.getElementHeight(targetElm); //
  var o = document.getElementById("SelbBoxDate");
  document.body.appendChild(o);
  o.style.top = (t+h-1)+"px";
  o.style.left= l+"px";
  o.style.width = "200px"; //w+"px";
  o.style.display = '';
  if(navigator.userAgent.indexOf("MSIE")==-1) o.style.width = "196px"; //w+"px";
  // alert("hoho uart1 =  l:"+l+", t:"+t+", w:"+w+", h:"+h+"; o.id:"+o.id+", o.style.top:"+o.style.top+"");
}

function initMaSelBox(targetElm) {
	var obj = ((typeof targetElm) == "string") ? document.getElementById(targetElm) : targetElm;
	if (!obj) {
		alert("!obj");
		return false;
	}
	if (!obj.SelBoxInit || obj.SelBox.inputField != obj) {
	  	//obj.SelBox = document.getElementById("SelBoxMaItems");
		//obj.SelBoxContainer = document.getElementById("SelbBoxMA");
		obj.LiveSearchUrl = "livesearch.php";
		obj.selectMultiple = true;
		obj.multipleSeparator = ",";
		parentObj = obj.parentNode;
	    do { 
	      if (parentObj.tagName.toUpperCase() == "TABLE" && parentObj.id.indexOf("MA") == 0) { break; }
	      parentObj = parentObj.parentNode;
	    } while(parentObj);
	  
	    if (parentObj) {obj.activeRow = parentObj; }
		
		obj.InputSearchFields = new Array();
		obj.InputResultFields = new Array();
		if (obj.activeRow) {
			aRowInputFlds = obj.activeRow.getElementsByTagName("input");
			for (var i = 0; i < aRowInputFlds.length; i++) {
				//alert(aRowInputFlds[i].name);
				bindRowInputFld = false;
				switch(aRowInputFlds[i].name) {
					case "MA[mid][]":
					obj.InputResultFields[0] = aRowInputFlds[i];
					bindRowInputFld = true;
					break;
					case "MA[name][]":
					obj.InputSearchFields["name"] = aRowInputFlds[i];
					obj.InputResultFields[1] = aRowInputFlds[i];
					bindRowInputFld = true;
					break;
					case "MA[vorname][]":
					obj.InputSearchFields["vorname"] = aRowInputFlds[i];
					obj.InputResultFields[2] = aRowInputFlds[i];
					bindRowInputFld = true;
					break;
					case "MA[ort][]":
					obj.InputSearchFields["ort"] = aRowInputFlds[i];
					obj.InputResultFields[3] = aRowInputFlds[i];
					bindRowInputFld = true;
					break;
					case "MA[gebaeude][]":
					obj.InputSearchFields["gebaeude"] = aRowInputFlds[i];
					obj.InputResultFields[4] = aRowInputFlds[i];
					bindRowInputFld = true;
					break;
					case "MA[raumnr][]":
					obj.InputSearchFields["raumnr"] = aRowInputFlds[i];
					obj.InputResultFields[5] = aRowInputFlds[i];
					bindRowInputFld = true;
					//alert("raumnr: "+obj.InputResultFields[5].name+": "+obj.InputResultFields[5].value);
					break;
					case "MA[abteilung][]":
					obj.InputSearchFields["abteilung"] = aRowInputFlds[i];
					obj.InputResultFields[6] = aRowInputFlds[i];
					bindRowInputFld = true;
					break;
				}
				if (bindRowInputFld && aRowInputFlds[i]) {
					//
				}
			}
			if (obj.InputSearchFields.length == 0) {
				ISFLen = 0;
				for(var ISFI in obj.InputSearchFields) { ISFLen++; }
				if (obj.InputSearchFields.length < ISFLen) obj.InputSearchFields.length = ISFLen;
			}
		}
	}
	SelBox_init(targetElm, "Mitarbeiter", "SelBoxMa", true, '');
}


function initZielSelBox(targetElm) {
	var obj = ((typeof targetElm) == "string") ? document.getElementById(targetElm) : targetElm;
	if (!obj) {
		alert("!obj");
		return false;
	}
	if (!obj.SelBoxInit || obj.SelBox.inputField != obj) {
	  	//obj.SelBox = document.getElementById("SelBoxMaItems");
		//obj.SelBoxContainer = document.getElementById("SelbBoxMA");
		obj.LiveSearchUrl = "livesearch.php";
		obj.selectMultiple = true;
		obj.multipleSeparator = ",";
		parentObj = obj.parentNode;
	    do { 
	      if (parentObj.tagName.toUpperCase() == "TABLE" && parentObj.id.indexOf("MA") == 0) { break; }
	      parentObj = parentObj.parentNode;
	    } while(parentObj);
	  
	    if (parentObj) {obj.activeRow = parentObj; }
		
		obj.InputSearchFields = new Array();
		obj.InputResultFields = new Array();
		if (obj.activeRow) {
			aRowInputFlds = obj.activeRow.getElementsByTagName("input");
			for (var i = 0; i < aRowInputFlds.length; i++) {
				//alert(aRowInputFlds[i].name);
				bindRowInputFld = false;
				
				switch(aRowInputFlds[i].name) {
					case "MA[zort][]":
					obj.InputSearchFields["ort"] = aRowInputFlds[i];
					obj.InputResultFields[0] = aRowInputFlds[i];
					bindRowInputFld = true;
					break;
					case "MA[zgebaeude][]":
					obj.InputSearchFields["gebaeude"] = aRowInputFlds[i];
					obj.InputResultFields[1] = aRowInputFlds[i];
					bindRowInputFld = true;
					break;
					case "MA[zraumnr][]":
					obj.InputSearchFields["raumnr"] = aRowInputFlds[i];
					obj.InputResultFields[2] = aRowInputFlds[i];
					bindRowInputFld = true;
					//alert("raumnr: "+obj.InputResultFields[5].name+": "+obj.InputResultFields[5].value);
					break;
					case "MA[zabteilung][]":
					obj.InputSearchFields["abteilung"] = aRowInputFlds[i];
					obj.InputResultFields[3] = aRowInputFlds[i];
					bindRowInputFld = true;
					break;
				}
				if (bindRowInputFld && aRowInputFlds[i]) {
					//
				}
			}
			if (obj.InputSearchFields.length == 0) {
				ISFLen = 0;
				for(var ISFI in obj.InputSearchFields) { ISFLen++; }
				if (obj.InputSearchFields.length < ISFLen) obj.InputSearchFields.length = ISFLen;
			}
		}
	}
	SelBox_init(targetElm, "Ziel", "SelBox", true, '');
}

function init_date() {
	show_date('terminwunsch');
}

/*
function show_uart(targetElm) {
  var l = PageInfo.getElementLeft(targetElm); //      : function(element) { var element = (typeof element == "string") ? document.getElementById(element) : element; var left = element.offsetLeft; var oParent = element.offsetParent; while (oParent != null) { left += oParent.offsetLeft; oParent = oParent.offsetParent; } return left; },
  var t = PageInfo.getElementTop(targetElm); //       : function(element) { var element = (typeof element == "string") ? document.getElementById(element) : element; var top = element.offsetTop; var oParent = element.offsetParent; while (oParent != null) { top += oParent.offsetTop; oParent = oParent.offsetParent; } return top; },
  var w = PageInfo.getElementWidth(targetElm); //     : function(element) { var element = (typeof element == "string") ? document.getElementById(element) : element; return element.offsetWidth; },
  var h = PageInfo.getElementHeight(targetElm); //
  var o = document.getElementById("SelbBoxUart");
  document.body.appendChild(o);
  o.style.top = (t+h-1)+"px";
  o.style.left= l+"px";
  o.style.display = '';
}

function GetMaSearch() {
	if (document.getElementById("SelBoxMaItems") && document.getElementById("SelBoxMaItems").SelBoxInsert)
		document.getElementById("SelBoxMaItems").SelBoxInsert(); //insert_ma_selection();
}

function show_ma() {  
  
  var l = PageInfo.getElementLeft(this); //      : function(element) { var element = (typeof element == "string") ? document.getElementById(element) : element; var left = element.offsetLeft; var oParent = element.offsetParent; while (oParent != null) { left += oParent.offsetLeft; oParent = oParent.offsetParent; } return left; },
  var t = PageInfo.getElementTop(this); //       : function(element) { var element = (typeof element == "string") ? document.getElementById(element) : element; var top = element.offsetTop; var oParent = element.offsetParent; while (oParent != null) { top += oParent.offsetTop; oParent = oParent.offsetParent; } return top; },
  var w = PageInfo.getElementWidth(this); //     : function(element) { var element = (typeof element == "string") ? document.getElementById(element) : element; return element.offsetWidth; },
  var h = PageInfo.getElementHeight(this); //
  
  this.SelBoxContainer.style.top = (t+h-1)+"px";
  this.SelBoxContainer.style.left= l+"px";
  this.SelBoxContainer.style.width = "300px"; //w+"px";
  this.SelBoxContainer.style.display = '';
  if(navigator.userAgent.indexOf("MSIE")==-1) this.SelBoxContainer.style.width = "300px"; //w+"px";
  //fb_AjaxRequest('umzugsantrag_livesearchresult_ma.xml?'+'&refresh='+(new Date()).getTime(), 'get', 'fb_AjaxXmlUpdate(%req%)');
  // alert("hoho uart1 =  l:"+l+", t:"+t+", w:"+w+", h:"+h+"; o.id:"+o.id+", o.style.top:"+o.style.top+"");
}
function init_ma() {
	//show_ma('mitarbeiter');
}
if (window.addEventListener) window.addEventListener("load", init_uart, false);
else if (window.attachEvent) window.attachEvent("onload", init_uart);
if (window.addEventListener) window.addEventListener("load", init_ma, false);
else if (window.attachEvent) window.attachEvent("onload", init_ma);
*/


