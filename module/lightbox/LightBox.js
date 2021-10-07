
	function ce(esrc) {
		var br  = document.createElement("br");
		var obj = document.createElement("input");
		obj.type = "file";
		frm1.appendChild(obj)
		frm1.appendChild(br);
		obj.focus();
		// esrc.blur();
	}
	function addScript(esrc) {
		var obj = document.createElement("script");
		obj.src = "hallo.js";
		document.body.appendChild(obj)
		document.body.focus();
	}
	
	function no_addLightBox() {
		var lbx = document.createElement("div");
		lbx.id = "lbx";
		lbx.setAttribute("style","position:absolute;top:0px;left:0px;");
		with(lbx.style) {
			position = "absolute";
			width = "1200px";
			textAlign = "center";
			background= "#008000";
			//filter = "Alpha(opacity=80)";
		}
		
		document.body.appendChild(lbx);
		
		// document.getElementById("lbx").innerHTML = sHTML;
		var lfr = document.createElement("iframe");
		lfr.src= "static_hallo.html";
		lfr.setAttribute("frameborder","0px");
		lfr.style.marginLeft = "auto";
		lfr.style.marginRight = "auto";
		lfr.style.border = "1px solid #ff0000";
		lfr.style.width = "500px";
		lfr.style.height = "600px";
		document.getElementById("lbx").appendChild(lfr);
		/**/
		alert("End Of Function");
	}
	var didAlertStyle = true;
	function styleAttribute(k, v) {
		
		if (this.style.setAttribute) {
			// Z.B. IE6
			if (k == "a") alert("a: B1");
			this.style.setAttribute(k, v, false);
			if (!didAlertStyle) alert("#52 "+k+":"+v);
		} else {
			// Z.B. Firefox3
			if (k == "a") alert("a: B2");
			t = k.split("-");
			if (t.length >1) {
				// k = t[0]+t[1].substr(0,1).toUpperCase()+t[1].substr(1);
				// alert("k-k:"+k);
			}
			this.style[k] = v;
			if (!didAlertStyle) alert("#62 "+k+":"+v+" this.style["+k+"]:"+this.style[k]);
			// else if (this.style[k] != v) alert("#62 "+k+":"+v+" this.style["+k+"]:"+this.style[k]);
		}
		didAlertStyle =true;
	}
	
	function getFrameVisHeight(frameId) {
		var frame = top.frames[frameId];
		var visibleHeight = 0; 
		
		if (frame.innerHeight) { visibleHeight = frame.innerHeight; } 
		else if (frame.document.documentElement && frame.document.documentElement.clientHeight) { visibleHeight = frame.document.documentElement.clientHeight; } 
		else if (frame.document.body) { visibleHeight = frame.document.body.clientHeight; } 
		
		return visibleHeight;
	}
	function getFrameDocHeight(frameId) {
		var frame = top.frames[frameId];
		var documentHeight = 0; 
		var h1 = frame.document.body.scrollHeight; 
		var h2 = frame.document.body.offsetHeight; 
		
		if (h1 > h2) documentHeight = frame.document.body.scrollHeight;
		else documentHeight = frame.document.body.offsetHeight;
		
		return documentHeight;
	}
	
	function getFrameDocWidth(frameId) {
		var frame = top.frames[frameId];
		var documentWidth = 0; 
		var w1 = frame.document.body.scrollWidth; 
		var w2 = frame.document.body.offsetWidth; 
		
		if (w1 > w2) documentWidth = frame.document.body.scrollWidth;
		else documentWidth = frame.document.body.offsetWidth;
		
		return documentWidth;
	}
	
	function finishLightBox_onload() {
		// alert("#93 finishLightBox_onload");
		var WinVisHeight = PageInfo.getVisibleHeight();
		var WinVisWidth = PageInfo.getVisibleWidth();
		
		var FrameDocHeight = getFrameDocHeight("lfr");
		var FrameDocWidth = getFrameDocWidth("lfr");
		
		var FrameHeight = getFrameVisHeight("lfr");
		
		if (FrameDocHeight < FrameHeight || FrameDocHeight < (WinVisHeight-40)) {
			top.frames["lfr"].height = FrameDocHeight;
			//alert("#116 \nWinVisHeight:"+WinVisHeight+"\nFrameDocHeight:"+FrameDocHeight+"\nFrameHeight:"+FrameHeight);
		} else if (FrameDocHeight > (WinVisHeight-40) || FrameHeight > (WinVisHeight-40)) {
			top.frames["lfr"].height = (WinVisHeight-40)+"px";
			//alert("#119 \nWinVisHeight:"+WinVisHeight+"\nFrameDocHeight:"+FrameDocHeight+"\nFrameHeight:"+FrameHeight+"\ntop.frames[lfr].height:"+top.frames["lfr"].height);
		} else {
			//alert("#121 \nWinVisHeight:"+WinVisHeight+"\nFrameDocHeight:"+FrameDocHeight+"\nFrameHeight:"+FrameHeight);
		}
		document.getElementById("ibx").style.height = parseInt(top.frames["lfr"].height)+40+"px";
	}

	function addLightBox(esrc, url) {
		// LightBox
		var lbx = document.createElement("div");
		lbx.setStyle = styleAttribute;
		lbx.id = "lbx";
		lbx.setAttribute("align","center");
		lbx.setStyle("z-index", 1);
		lbx.setStyle("position","absolute");
		lbx.setStyle("left","0px");
		lbx.setStyle("top","0px");
		if (PageInfo) lbx.setStyle("width", PageInfo.getDocumentWidth()+"px");
		else lbx.setStyle("width","1200px");
		// lbx.setStyle("height","600px");
		lbx.setStyle("background","#ffff00");
		lbx.setStyle("border","0px solid #008000");
		
		// ShadowBox: Layer
		var sbx = document.createElement("div");
		lbx.appendChild(sbx);
		sbx.setStyle = styleAttribute;
		sbx.id = "sbx";
		sbx.setAttribute("align","center");
		sbx.setStyle("z-index", 3);
		sbx.setStyle("visibility", "inherit");
		sbx.setStyle("position","absolute");
		sbx.setStyle("left","0px");
		sbx.setStyle("top", "0px");
		
		if (PageInfo) {
			sbx.setStyle("width", (PageInfo.getScrollLeft()+(2*PageInfo.getDocumentWidth()))+"px");
			sbx.setStyle("height", (PageInfo.getScrollTop()+(2*PageInfo.getDocumentHeight()))+"px");
		} else {
			sbx.setStyle("width",  "2000px");
			sbx.setStyle("height", "4000px");
		}
		/**/
		sbx.setStyle("background","#009");
		// sbx.setStyle("border","0px solid #009");
		sbx.setStyle("opacity","0.5");
		sbx.setStyle("filter","alpha(opacity=75)");
		sbx.onclick = function() { lbx.style.visibility="hidden"; lfr.src="about:blank"; return false; }
		
		// ShadowBox in DOM einbinden
		
		// ShadowBox: Transparente Hintergrundgrafik für ShadowBox
		var si = document.createElement("img");
		si.setStyle = styleAttribute;
		si.id = "si";
		si.setAttribute("width","100%");
		si.setAttribute("height", "100%"); // (PageInfo.getScrollTop()+(2*PageInfo.getDocumentHeight()))+
		si.setAttribute("src","images/clear.gif");
		sbx.appendChild(si);
		
		// InnerBox
		var ibx = document.createElement("div");
		ibx.setStyle = styleAttribute;
		ibx.id = "ibx";
		ibx.setAttribute("align","center");
		lbx.appendChild(ibx);
		ibx.setStyle("z-index", 3);
		ibx.setStyle("background","#121c70"); // #d1d1d1");
		ibx.setStyle("height","520px");
		ibx.setStyle("width","700px");
		ibx.setStyle("padding","5px 10px 10px 10px");
		ibx.setStyle("border","0px solid #121c70"); // #d1d1d1");
		ibx.setStyle("visibility", "inherit");
		ibx.setStyle("position","absolute");
		ibx.setStyle("left","50px");
		ibx.setStyle("top", PageInfo.getScrollTop()+20+"px");
		
		lbx.appendChild(ibx);
		// MenuBox: Close
		var mbx = document.createElement("table");
		mbx.setStyle = styleAttribute;
		mbx.setAttribute("width", "1000");
		// mbx.setAttribute("background","#4b4b4b");
		mbx.setAttribute("cellpadding", "0");
		mbx.setAttribute("cellspacing", "0");
		mbx.setStyle("cell-spacing", "0px");
		mbx.setAttribute("border", "0");
		mbx.setStyle("width", "100%");
		ibx.appendChild(mbx);
		
		var h = document.createElement("thead");
		mbx.appendChild(h);
		var b = document.createElement("tbody");
		mbx.appendChild(b);
		
		var row = document.createElement("tr");
		b.appendChild(row);
		
		var cell= document.createElement("td");
		row.appendChild(cell);
		
		var aClose= document.createElement("div");
		aClose.setStyle = styleAttribute;
		// aClose.classname="lbxMenu";
		aClose.id="lbxClose";
		aClose.setAttribute("href", "#");
		aClose.onclick = function() { lbx.style.visibility="hidden"; lfr.src="about:blank"; return false; }
		//aClose.style.color="#ff0000";
		aClose.setStyle("cursor","hand");
		aClose.setStyle("color","#fff");
		aClose.setStyle("font-size","11px");
		aClose.setStyle("font-family","Arial,Helvetica,sans-serif");
		aClose.setStyle("text-decoration","none");
		aClose.innerHTML = "<div style=\"cursor:hand;text-align:right;float:right;\">schliessen [x]</div>";
		cell.appendChild(aClose);
		
		// LightboxFrame
		var lfr = document.createElement("iframe");
		lfr.setStyle = styleAttribute;
		lfr.id = "lfr";
		lfr.onload = finishLightBox_onload;
		lfr.setStyle = styleAttribute;
		lfr.setAttribute("src", url); // "http://www.heise.de"); //"static_hallo.html";
		lfr.setAttribute("frameborder","0px");
		lfr.setStyle("background","#fff");
		lfr.setStyle("border","1px solid #a8a8a8");
		lfr.setStyle("width","100%");
		lfr.setStyle("height","500px");
		ibx.appendChild(lfr);
		/**/
		document.body.appendChild(lbx);
	}
