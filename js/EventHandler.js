	/*
	document.documentElement.ondragstart = crop_cancelEvent;
	document.documentElement.onselectstart = crop_cancelEvent;
	MouseIsDown = false;
	addEvent(document, "mouseup", globalRegisterMouseUp);
	addEvent(document, "mousedown", globalRegisterMouseDown);
	*/

	function globalRegisterMouseUp() {
		MouseIsDown = false;
	}
	function globalRegisterMouseDown() {
		MouseIsDown = true;
	}
	function crop_cancelEvent(e)
	{
		if(document.all)e = event;
		if (e.target) source = e.target;
			else if (e.srcElement) source = e.srcElement;
			if (source.nodeType == 3) // defeat Safari bug
				source = source.parentNode;
						
		if(source.tagName && source.tagName.toLowerCase()=='input')return true;
		return false;
	}
	function cancelXpWidgetEvent()
	{
		return false;	// Übergabe an obj.onselectstart = cancelXpWidgetEvent;
		
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
	
function check_keydown(e) {
	if (!e) e = event;
   if (e.keyCode) eCharCode = e.keyCode;
   else if (e.charCode) eCharCode = e.charCode;
   else return false;
   
   var msg = "";
   var focusMove = "";
   switch(eCharCode) {
   	case 37: msg+= eCharCode+" IfChar:"+String.fromCharCode(eCharCode)+" - Pfeil links!\n"; break; // Doesn't work in IE
   	case 38: focusMove="up"; msg+= eCharCode+" IfChar:"+String.fromCharCode(eCharCode)+" - Pfeil hoch!\n"; break;
   	case 39: msg+= eCharCode+" IfChar:"+String.fromCharCode(eCharCode)+" - Pfeil rechts!\n"; break; // Doesn't work in IE
   	case 40: focusMove="down"; msg+= eCharCode+" IfChar:"+String.fromCharCode(eCharCode)+" - Pfeil unten!\n"; break;
   }
   //alert(msg);
   if (e.ctrlKey && String.fromCharCode(eCharCode) == "B") return false; // Prevent Default Firefox Strg+B
   if (e.ctrlKey && String.fromCharCode(eCharCode) == "N") return false; // Prevent Default IE/Ff Strg +N -> New Window
   if (e.keyCode) msg+= "e.keyCode:" + e.keyCode+"\n";
   if (e.which) msg+= "e.which:"+e.which+"\n";
   if (e.charCode) msg+= "e.charCode:"+e.charCode+"\n";
   if (e.keyIdentifier) msg+= "e.keyIdentifier:"+ e.keyIdentifier+"\n";
   if (e.keyLocation) msg+= "e.keyLocation:"+e.keyLocation+"\n";
   if (e.shiftKey) msg+= "e.shiftKey:"+e.shiftKey+"\n";
   if (e.ctrlKey) msg+= "ctrlKey="+e.ctrlKey+"\n";
   if (e.altKey) msg+= "e.altKey:"+e.altKey+"\n";
   if (e.metaKey) msg+= "e.metaKey:"+e.metaKey+"\n"; // Macintosh keyboard
   if (e.keyCode) msg+= "String.fromCharCode(e.keyCode):"+String.fromCharCode(e.keyCode)+"\n";
   if (e.charCode) msg+= "String.fromCharCode(e.charCode):"+String.fromCharCode(e.charCode)+"\n";
   //alert(msg);
}