var DtPWebPath = "./module/datepicker/";
function showDtPicker(obj) {
	if ((typeof last_obj) == "object" && last_obj == obj) {
		if (document.getElementById("dtpBox").style.display != "none") {
			document.getElementById("dtpBox").style.display="none";
			last_obj = null;
			return false;
		}
	}
	var msg = "";
	/*for (i = 0; i < arguments.length; i++) msg+= (msg?";":"")+arguments[i];
	alert("#3 showDtPicker:"+msg);*/
	if (!document.getElementById("dtpBox")) {
		divDtpBox = document.createElement("div");
		divDtpBox.id = "dtpBox";
		document.getElementsByTagName("body")[0].appendChild(divDtpBox);
	}
	if (!document.getElementById("dtpOuterBlurBox")) {
		divDtpOuterBox = document.createElement("div");
		divDtpOuterBox.id = "dtpOuterBlurBox";
		divDtpOuterBox.onclick = function() {
			if (document.getElementById("dtpOuterBlurBox")) document.getElementById("dtpOuterBlurBox").style.display="none";
			if (document.getElementById("dtpBox")) document.getElementById("dtpBox").style.display="none";
		}
		document.getElementsByTagName("body")[0].appendChild(divDtpOuterBox);
	}/**/
	if (document.getElementById("dtpBox")) {
		document.getElementById("dtpBox").style.display = "";
		document.getElementById("dtpBox").style.left = PageInfo.getElementLeft(obj)+"px";
		document.getElementById("dtpBox").style.top = PageInfo.getElementTop(obj)+PageInfo.getElementHeight(obj)+"px";
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
		get_kalenderPicker("id:"+obj.id, "id:dtpBox", sMonth);
		last_obj = obj;
	}
	
	if (document.getElementById("dtpOuterBlurBox")) {
		document.getElementById("dtpOuterBlurBox").style.display = "";
		document.getElementById("dtpOuterBlurBox").style.left = "0px";
		document.getElementById("dtpOuterBlurBox").style.top = (PageInfo.getElementTop(obj)+PageInfo.getElementHeight(obj))+"px";
		document.getElementById("dtpOuterBlurBox").style.height = PageInfo.getDocumentHeight(); //-PageInfo.getElementTop(obj);
	}/**/
}

function get_firstWDayOfMonth(objDate) {
	StartDate = new Date(objDate.getFullYear(), objDate.getMonth(), 1);
	// alert("StartDate.toString():"+StartDate.toString()+"\nStartDate.getDay():"+StartDate.getDay());
	return StartDate.getDay();
}

function get_lastDayOfMonth(objDate) {
	var nextMonth = (objDate.getMonth()<11 ? (objDate.getMonth()+1) : 0);
	var nextYear = (nextMonth != 0 ? objDate.getFullYear() : (objDate.getFullYear()+1));
	NextDate = new Date(nextYear, nextMonth, 1, 5, 0, 0);
	var LastDay = new Date(NextDate.getTime()-(1000*60*60*24));
	return LastDay.getDate();
}

function get_nextMonth(objDate) {
	var nextMonth = (objDate.getMonth()<11 ? (objDate.getMonth()+1) : 0);
	var nextYear = (nextMonth != 0 ? objDate.getFullYear() : (objDate.getFullYear()+1));
	NextDate = new Date(nextYear, nextMonth, 1, 5, 0, 0);
	return NextDate;
}

function get_MonthString(objDate) {
	return objDate.getFullYear()+"-"+(objDate.getMonth());
}
function get_HumanMonthString(objDate) {
	var m = objDate.getMonth()+1;
	if (m < 10) m = "0"+""+m;
	return objDate.getFullYear()+"-"+m;
	//return objDate.getFullYear()+"-"+(("0"+(objDate.getMonth()+1)).substr(-2,2));
}

function get_prevMonth(objDate) {
	var a = get_MonthString(objDate);
	var prevMonth = (objDate.getMonth()>1 ? (objDate.getMonth()-1) : 11);
	var prevYear = (prevMonth != 11 ? objDate.getFullYear() : (objDate.getFullYear()-1));
	PrevDate = new Date(prevYear, prevMonth, 1, 5, 0, 0);
	var b = get_MonthString(PrevDate);
	return PrevDate;
}

function get_browsingLink(dir, objDate, aTrackArgs) {
	switch(dir) {
		case "prev":
		var sMonth = get_MonthString(get_prevMonth(objDate));
		break;
		
		case "next":
		var sMonth = get_MonthString(get_nextMonth(objDate));
		break;
		
		default:
		return "";
	}
	return " href=# onclick=\"get_kalenderPicker('"+aTrackArgs[0]+"', '"+aTrackArgs[1]+"', '"+sMonth+"');return false;\"";
}

function get_pickupLink(aTrackArgs, sDate) {
	var aT = aTrackArgs[0].split(":");
	var aS = aTrackArgs[1].split(":");
	switch(aT[0]) {
		case "id":
		return " onclick=\"pickD('"+aT[1]+"','"+sDate+"','"+aS[1]+"')\"";
		
		case "f":
		return " onclick=\""+aT[1]+"('"+sDate+"','"+aS[1]+"')\"";
		
		default:
		// if (sDate == "2009-5-1") alert("get_pickupLink");
	}
}

function pickD(id, sDate, dateBoxId) {
	if (document.getElementById(id)) {
		document.getElementById(id).value = sDate;
	}
	closeDtpBox(dateBoxId);
}

function closeDtpBox(id) {
	if (document.getElementById(id)) {
		document.getElementById(id).style.display = "none";
	}
	if (document.getElementById("dtpOuterBlurBox")) {
		document.getElementById("dtpOuterBlurBox").style.display = "none";
	}
}

function ChBtnMethod() {
	ChBtn(this);
}

function ChBtn(obj) {
	if (!obj.BtnStat) {
		obj.ChBtnMethod = ChBtnMethod;
		obj.BtnStat = "off";
		obj.BtnOff = new Image();
		obj.BtnOff.src = obj.src;
		obj.BtnOn = new Image();
		obj.BtnOn.src = obj.src.split("_off").join("_on");
		obj.onmouseout = obj.ChBtnMethod;
	} 
	if (obj.BtnStat) {
		switch(obj.BtnStat) {
			case "off":
			if (obj.BtnOn.complete) obj.src = obj.BtnOn.src;
			obj.BtnStat = "on";
			break;
			case "on":
			if (obj.BtnOff.complete) obj.src = obj.BtnOff.src;
			obj.BtnStat = "off";
			break;
		}
	}
}

function get_kalenderPicker() {
	var aWochentage = new Array("Mo", "Di", "Mi", "Do", "Fr", "Sa", "So");
	var aWtBaseSo = new Array("So", "Mo", "Di", "Mi", "Do", "Fr", "Sa");
	var aMonate = new Array("Januar", "Februar", "M&auml;rz", "April", "Mai", "Juni", "Juli", "August", "Septempter", "Oktober", "November", "Dezember");
	
	var dNow = new Date();
	var todayY = dNow.getFullYear();
	var todayM = dNow.getMonth()+1;
	var todayD = dNow.getDate();
	var today = todayY+"-"+todayM+"-"+todayD;
	
	var msg = "";
	/*for (i = 0; i < arguments.length; i++) msg+= (msg?";":"")+arguments[i];
	alert("#140 get_kalenderPicker:"+msg);
	msg = "";*/
	var d = null;
	var aT = new Array("","");
	var aS = new Array("","");
	var aTrackArgs = new Array("", "", "")
	var sDate = "";
	
	if (arguments.length > 0 && arguments[0].indexOf(":")!=-1) {
		// array Target für Datumsauswahl
		aT = arguments[0].split(":");
		aTrackArgs[0] = arguments[0];
		// Target-Type: aT[0] = "f" (Funktion) oder "id" (Input-Feld)
		// Target-Ziel: aT[0] = Funktionsname für Typ "f" oder ID des Input-Feldes für Typ "i"
	}
	if (arguments.length > 1 && arguments[1].indexOf(":")!=-1) {
		// array Self-Identification
		aS = arguments[1].split(":");
		aTrackArgs[1] = arguments[1];
		// Monatsbrowsing-Type: aS[0] = "f" (Funktion) oder "id"
		// Monatsbrowsing-Ziel: aS[1] = Funktionsname an den das Monatsobjekt übergeben werden soll
		// oder ID des HTML-Ements id das der Kalender geschrieben werden soll!!
	}
	if (arguments.length > 2 && arguments[2].indexOf("-")!=-1) {
		aD = arguments[2].split("-");
		aD[2] = (aD.length > 2 && parseInt(aD[2])) ? aD[2] : 1;
		d = new Date(aD[0], aD[1], 1);
		// alert("#98 d "+get_MonthString(d)+" new Date("+aD[0]+", "+aD[1]+", 1));");
	}
	if (d == null) d = new Date();
	// alert("#100 d "+get_MonthString(d));
	// Ersten Tag des Monats ermitteln
	var SWT = get_firstWDayOfMonth(d);      // (Wochentag ermitteln) // StartWochenTag
	
	
	// Letzten Tag des Monats ermitteln
	var lastDayOfMonth = get_lastDayOfMonth(d);
	// alert("#107 d "+get_MonthString(d));
	
	var kalender = "";
	kalender+= "<div style=\"text-align:right;\"><img src=\""+DtPWebPath+"images/icon_close10x10_off.gif\" onmouseover=ChBtn(this) width=10 height=10 onclick=\"closeDtpBox('"+aS[1]+"')\"></div>\n";
	kalender+= "<table align=center class='tblNavDtPicker' width=90% border=0><thead><tr>\n";
	kalender+= "<td class='navPrev' "+get_browsingLink("prev", d, aTrackArgs)+"> &laquo;</td>";
	kalender+= "<td>"+(aMonate[StartDate.getMonth()])+" "+StartDate.getFullYear()+"</td>";
	
	nextLink = "<td class='navNext' "+get_browsingLink("next", d, aTrackArgs)+"> &raquo;</td>\n";
	kalender+= "<td class='navNext' "+get_browsingLink("next", d, aTrackArgs)+"> &raquo;</td>\n";
	kalender+= "</tr></thead></table>\n";
	kalender+= "<table class='tblDtPicker' border=0 cellpadding= cellspacing=0>";
	
	// Kalendertitel: Wochentage
	kalender+= "<thead><tr>";
	for (i = 0; i < aWochentage.length; i++) {
		kalender+= " <td class=\""+aWochentage[i]+"\">"+(aWochentage[i])+"</td>\n";
	}
	kalender+= " </tr></thead><tbody>\n";
	
	var t;
	// Kalender am Anfang auffüllen
	var vorlauf = (SWT == 0) ? 6 : SWT-1;
	kalender+= "<tr>";
	for(i = 0; i < vorlauf; i++) kalender+= "<td> </td>";
	
	// kalender
	sMonth = get_HumanMonthString(d);
	var r_datum = "";
	var wtKey = "";
	var wtDay = "";
	for(t = 1; t <= lastDayOfMonth; t++) {
		sDate = d.getFullYear()+"-"+(d.getMonth()+1)+"-"+t;
		r_datum = sMonth+"-"+(t<10?("0"+t.toString()):t);
		wtKey = ((vorlauf+t) % 7);
		wtDay = aWtBaseSo[wtKey];
		pickupLink = (wtDay!="Sa" && wtDay!="So")?get_pickupLink(aTrackArgs, r_datum):"";
		// if (t == 1) alert("get_pickupLink():"+pickupLink);
		kalender+= "  <td class=\""+wtDay+"\""+(sDate!=today?"":" style=\"color:#f00;font-weight:bold;\"")+"\" "+pickupLink+"> "+t+""+" </td>"; //   "+""+(get_pickupLink(aTrackArgs, sDate)+""+" 
		if ((vorlauf+t) % 7 == 0) {
			kalender+= " </tr>\n";
			if (t < lastDayOfMonth) kalender+= " <tr>\n";
		}
	}
	
	// Kalender am Ende auffüllen
	var nachlauf = 7-((vorlauf + lastDayOfMonth) % 7);
	if (nachlauf != 7) {
		for(i = 0; i < nachlauf; i++) kalender+= "<td> </td>";
		kalender+= " </tr>\n";
	}
	kalender+= "</tbody></table>";
	kalender+= "<button id=\"btnDtpClose2\" onclick=\"closeDtpBox('"+aS[1]+"')\">schliessen</button>";
	
	switch(aS[0]) {
		case "id": 
		if (document.getElementById(aS[1])) {
			document.getElementById(aS[1]).innerHTML = kalender;
		}
		break;
		
		case "f": 
		eval(aS[1]+"(kalender)"); 
		break;
		
		default: return kalender;
	}
}

function get_pageInfos() {
	var element = (arguments.length) ? arguments[0] : false;
	var showDialog = (arguments.length >1 ) ? arguments[1] : true;
	var pInfos = "Page-Infos:";
	
	pInfos+= "\n ResolutionWidth: "+PageInfo.getResolutionWidth(); //   : function() { return self.screen.width; },
	pInfos+= "\n ResolutionHeight: "+PageInfo.getResolutionHeight(); //  : function() { return self.screen.height; },
	pInfos+= "\n ColorDepth: "+PageInfo.getColorDepth(); //        : function() { return self.screen.colorDepth; },
	
	pInfos+= "\n ScrollLeft: "+PageInfo.getScrollLeft(); //        : function() { var scrollLeft = 0; if (document.documentElement && document.documentElement.scrollLeft && document.documentElement.scrollLeft != 0) { scrollLeft = document.documentElement.scrollLeft; } if (document.body && document.body.scrollLeft && document.body.scrollLeft != 0) { scrollLeft = document.body.scrollLeft; } if (window.pageXOffset && window.pageXOffset != 0) { scrollLeft = window.pageXOffset; } return scrollLeft; },
	pInfos+= "\n ScrollTop: "+PageInfo.getScrollTop(); //         : function() { var scrollTop = 0; if (document.documentElement && document.documentElement.scrollTop && document.documentElement.scrollTop != 0) { scrollTop = document.documentElement.scrollTop; } if (document.body && document.body.scrollTop && document.body.scrollTop != 0) { scrollTop = document.body.scrollTop; } if (window.pageYOffset && window.pageYOffset != 0) { scrollTop = window.pageYOffset; } return scrollTop; },
	
	pInfos+= "\n DocumentWidth: "+PageInfo.getDocumentWidth(); //     : function() { var documentWidth = 0; var w1 = document.body.scrollWidth; var w2 = document.body.offsetWidth; if (w1 > w2) { documentWidth = document.body.scrollWidth; } else { documentWidth = document.body.offsetWidth; } return documentWidth; },
	pInfos+= "\n DocumentHeight: "+PageInfo.getDocumentHeight(); //    : function() { var documentHeight = 0; var h1 = document.body.scrollHeight; var h2 = document.body.offsetHeight; if (h1 > h2) { documentHeight = document.body.scrollHeight; } else { documentHeight = document.body.offsetHeight; } return documentHeight; },
	pInfos+= "\n VisibleWidth: "+PageInfo.getVisibleWidth(); //      : function() { var visibleWidth = 0; if (self.innerWidth) { visibleWidth = self.innerWidth; } else if (document.documentElement && document.documentElement.clientWidth) { visibleWidth = document.documentElement.clientWidth; } else if (document.body) { visibleWidth = document.body.clientWidth; } return visibleWidth; },
	pInfos+= "\n VisibleHeight: "+PageInfo.getVisibleHeight(); //     : function() { var visibleHeight = 0; if (self.innerHeight) { visibleHeight = self.innerHeight; } else if (document.documentElement && document.documentElement.clientHeight) { visibleHeight = document.documentElement.clientHeight; } else if (document.body) { visibleHeight = document.body.clientHeight; } return visibleHeight; },
	
	if (element) {
		pInfos+= "\n ElementLeft: "+PageInfo.getElementLeft(element); //       : function(element) { var element = (typeof element == "string") ? document.getElementById(element) : element; var left = element.offsetLeft; var oParent = element.offsetParent; while (oParent != null) { left += oParent.offsetLeft; oParent = oParent.offsetParent; } return left; },
		pInfos+= "\n ElementTop: "+PageInfo.getElementTop(element); //        : function(element) { var element = (typeof element == "string") ? document.getElementById(element) : element; var top = element.offsetTop; var oParent = element.offsetParent; while (oParent != null) { top += oParent.offsetTop; oParent = oParent.offsetParent; } return top; },
		pInfos+= "\n ElementWidth: "+PageInfo.getElementWidth(element); //      : function(element) { var element = (typeof element == "string") ? document.getElementById(element) : element; return element.offsetWidth; },
		pInfos+= "\n ElementHeight: "+PageInfo.getElementHeight(element); //     : function(element) { var element = (typeof element == "string") ? document.getElementById(element) : element; return element.offsetHeight; },
	}
	pInfos+= "\n MouseX: "+PageInfo.getMouseX(); //            : function() { return pInfos+= "\n : "+PageInfo.mouseX; },
	pInfos+= "\n MouseY: "+PageInfo.getMouseY(); //            : function() { return pInfos+= "\n : "+PageInfo.mouseY; },/ 
	
	if (showDialog) alert(pInfos);
	return pInfos;
	
}
function show_pageInfos() {
	var showDialog = true; // false; // 
	var element = (arguments.length) ? arguments[0] : false;
}

