function onChgLeistungFitTyp(obj) {
		var a = new Array();
		var v = "";
		
		if (obj.value.substring(obj.value.length-1) == ")") {
			a = obj.value.split(")");
			a = a[a.length-2].split("(");
			v = a[a.length-1];
			// alert("v:"+v);/**/
			
			for (i = 0; i < document.forms.length; i++) {
				if (typeof document.forms[i].elements["eingabe[Auftragstyp]"] == "object") {
					for (j = 0; j < document.forms[i].elements["eingabe[Auftragstyp]"].length; j++) {
						if (document.forms[i].elements["eingabe[Auftragstyp]"][j].value == v) {
							document.forms[i].elements["eingabe[Auftragstyp]"][j].checked = true;
						}
					}
					break;
				}
			}
		}
	}
