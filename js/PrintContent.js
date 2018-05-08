function test_print_content(content) {
	var PWin = window.open("print.js.php", "PWin", "left=10,top=10,width=800,resizable=1,scrollbars=1");
	PWin.document.body.appendChild(content.cloneNode(true));
	return true;
}
function print_content(content) {
	if (!content) return false;
	
	var CopyHelperNode = document.createElement("div");
	if (typeof(content) == "object" && content.cloneNode) 
		CopyHelperNode.appendChild(content.cloneNode(true));
	else if(typeof(content) == "string" || typeof(content) == "number")
		CopyHelperNode.innerHTML = content;
	else
		return false;
	
	var base_url_path = document.location.href.split("/").slice(0, document.location.href.split("/").length-1).join("/")+"/";
	alert("print.js.php base_url_path:"+base_url_path);
	var PrintHTML = "";
	PrintHTML = "<html>";
	PrintHTML+= "<head>";
	PrintHTML+= "<base href=\""+base_url_path+"\">";
	PrintHTML+= document.getElementsByTagName("head")[0].innerHTML;
	PrintHTML+= "<style media=\"print\">#PrintBar { display:none;} </style>\n";
	PrintHTML+= "<style media=\"screen\">\n";
	PrintHTML+= "a#PrintBar { display:block; margin:0; padding:2px; width:100%; background:#fff; color:#4169e1; text-decoration:none; border-bottom:thin solid #e0e0e0; }\n";
	PrintHTML+= "a:hover#PrintBar, a:active#PrintBar { color:#f00; text-decoration:none; border-bottom:thin solid #e0e0e0; }\n";
	PrintHTML+= "body { margin:0; padding:0; }\n";
	PrintHTML+= "</style>\n";
	PrintHTML+= "</head>\n";
	PrintHTML+= "<body onload=\"self.focus();self.print();\">\n";
	
	PrintHTML+= "<a id=\"PrintBar\" href=\"print:\" onclick=\"self.print();return false;\">Seite ausdrucken</a>\n";
	PrintHTML+= CopyHelperNode.innerHTML;
	PrintHTML+= "</body>\n";
	PrintHTML+= "</html>";
	
	var PWin = window.open("print.js.php", "PWin", "left=10,top=10,width=800,resizable=1,scrollbars=1");
	PWin.document.open();
	PWin.document.write(PrintHTML);
	PWin.document.close();
	
	PWin.focus();
	PWin.document.body.focus();
	return true;
}
