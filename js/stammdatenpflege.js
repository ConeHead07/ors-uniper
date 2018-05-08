
function AjaxFormSend(frm, selector, sConfirm) {
		frm = getFormObj(frm);
		if (typeof(frm) != "object" || typeof(frm.tagName) != "string" || frm.tagName.toUpperCase() != "FORM") return false;
		if (typeof(sConfirm) == "string" && sConfirm.length) if (!confirm(sConfirm)) return false;
		
		var sPostData = "";
		var sAjaxAdds = "&AjaxRequest=1&boxid="+selector+"&refresh="+(new Date()).getTime();
		
		if (frm.method.toUpperCase() == "POST") {
			sPostData = frmSerialize(frm);
			sPostData+= sAjaxAdds;
		} else {
			frm.action+(frm.action.indexOf("?") == -1 ? "?":"&")+frmSerialize(frm);
			frm.action+= sAjaxAdds;
		}
		//alert(sPostData);
		if (fb_AjaxRequest(frm.action, frm.method, 'fb_AjaxXmlUpdate(%req%, "'+selector+'")', sPostData)) {
			if (frm.preventDefault) frm.preventDefault();
		}
		return true;
	}
	
	function refreshImoFilter(obj, sAddQuery) {
		//alert("refreshImoFilter("+obj+", "+sAddQuery+")");
		var ImoFilter = (typeof(obj) == "object" && obj.name) ? obj.name : (typeof(obj)=="string" ? obj : "");
		var rHref = "";
		var frm = document.forms["frmMAFilter"];
		var ort = (frm.ort.selectedIndex != -1) ? frm.ort.options[frm.ort.selectedIndex].value : "";
		var gebaeude = (frm.gebaeude.selectedIndex != -1) ? frm.gebaeude.options[frm.gebaeude.selectedIndex].value : "";
		var etage = (frm.etage.selectedIndex != -1) ? frm.etage.options[frm.etage.selectedIndex].value : "";
		var raum = (frm.raum.selectedIndex != -1) ? frm.raum.options[frm.raum.selectedIndex].value : "";
		var s = frm.s.value;
		rHref = "?s="+escape(s);
		switch(ImoFilter) {
			case "ort": rHref+= "&ort="+escape(ort); break;
			
			case "gebaeude":
			rHref+= "&ort="+escape(ort);
			rHref+= "&gebaeude="+escape(gebaeude);
			break;
			
			case "etage":
			rHref+= "&ort="+escape(ort);
			rHref+= "&gebaeude="+escape(gebaeude);
			rHref+= "&etage="+escape(etage);
			break;
			
			default:
			rHref+= "&ort="+escape(ort);
			rHref+= "&gebaeude="+escape(gebaeude);
			rHref+= "&etage="+escape(etage);
			rHref+= "&raum="+escape(raum);
			break;
		}	
		if (sAddQuery) rHref+= (rHref.indexOf("?") == -1 ? "?" : "&")+sAddQuery;
		rHref+= "&refresh="+(new Date()).getTime();
		//alert(sAddQuery);	
		//alert(rHref);
		if (rHref) self.location.href= rHref;
	}
	
	function getInlineForm($cat, addQuery) {
		var sUrl = "";
		var sGet = "";
		var frm = document.forms["frmMAFilter"];
		
		if (frm.ort.selectedIndex!=-1) sGet+= "&ort="+escape(frm.ort.options[frm.ort.selectedIndex].value);
		else sGet+= "&ort=";
		
		if (frm.gebaeude.selectedIndex!=-1) sGet+= "&gebaeude="+escape(frm.gebaeude.options[frm.gebaeude.selectedIndex].value);
		else sGet+= "&gebaeude=";
		
		if (frm.etage.selectedIndex!=-1) sGet+= "&etage="+escape(frm.etage.options[frm.etage.selectedIndex].value);
		else sGet+= "&etage=";
		
		if (frm.raum.selectedIndex!=-1) sGet+= "&raum="+escape(frm.raum.options[frm.raum.selectedIndex].value);
		else sGet+= "&raum=";
		/**/
		switch($cat) {
			case 'NewRoom':
			sUrl = "bestandsaufnahme_anlegen.php?cat=raum"+sGet;
			break;
			case 'NewDepart':
			sUrl = "bestandsaufnahme_anlegen.php?cat=abteilung"+sGet;
			break;
			case 'NewMainDepart':
			sUrl = "bestandsaufnahme_anlegen.php?cat=bereich"+sGet;
			break;
			case 'NewGF':
			sUrl = "bestandsaufnahme_anlegen.php?cat=gf"+sGet;
			break;
			case 'NewEmployer':
			sUrl = "bestandsaufnahme_anlegen.php?cat=mitarbeiter"+sGet;
			break;
			
			case 'close':
			document.getElementById('frmEditData').innerHTML = "";
			return true;
		}
		//alert(sUrl);
		if (typeof(addQuery) == "string") sUrl+= "&"+addQuery;
		if (sUrl) SendRequest(sUrl, 'frmEditData');
	}
	
	function reloadSelectBereiche(el, frm) {
		if (!document.forms[frm] || !document.forms[frm].elements["bereich"]) return false;
		var v = getFormElementValue(el, frm);
		var el = document.forms[frm].elements["bereich"];
		var j;
		el.options.length = 1;
		el.options[0].value = ""; el.options[0].text = "...";
		for(var i in aBereiche) if (aBereiche[i]["oe"] == v) {
			j = el.options.length;
			el.options.length = j+1;
			el.options[j].value = i;
			el.options[j].text = aBereiche[i]["b"]+" ("+aBereiche[i]["bname"]+")";
		}
		reloadSelectAbteilungen(el, frm)
	}
	
	function reloadSelectAbteilungen(el, frm) {
		if (!document.forms[frm] || !document.forms[frm].elements["abteilungen_id"]) return false;
		var v = getFormElementValue(el, frm);
		//alert("Abteilungen des Bereichs "+v+" ("+bObj.options[bObj.selectedIndex].value+"=>"+bObj.options[bObj.selectedIndex].value+")");
		var el = document.forms[frm].elements["abteilungen_id"];
		var j;
		el.options.length = 1;
		el.options[0].value = ""; el.options[0].text = "...";
		for(var i in aAbteilungen) if (aAbteilungen[i]["b"] == v) {
			j = el.options.length;
			el.options.length = j+1;
			el.options[j].value = i;
			el.options[j].text = aAbteilungen[i]["a"]+" ("+aAbteilungen[i]["aname"]+")";
		}
	}
	
	function reloadListSelectBereiche(k) {
		var frm = "frmListe";
		if (!document.forms[frm] || !document.forms[frm].elements["bereich["+k+"]"]) return false;
		var v = getFormElementValue("gf["+k+"]", frm);
		var el = document.forms[frm].elements["bereich["+k+"]"];
		var j;
		var selectedValue = (el.selectedIndex >= 0) ? el.options[el.selectedIndex].value : "";
		var selectIndex = -1;
		el.options.length = 1;
		el.options[0].value = ""; el.options[0].text = "...";
		for(var i in aBereiche) if (aBereiche[i]["oe"] == v) {
			j = el.options.length;
			el.options.length = j+1;
			el.options[j].value = i;
			el.options[j].text = aBereiche[i]["b"];
			if (el.options[j].value == selectedValue) selectIndex = j;
		}
		if (selectIndex > -1) el.options[selectIndex].selected = true;
		else reloadSelectAbteilungen(k)
	}
	
	function reloadListSelectAbteilungen(k) {
		var frm = "frmListe";
		if (!document.forms[frm] || !document.forms[frm].elements["abteilungen_id["+k+"]"]) return false;
		var v = getFormElementValue("bereich["+k+"]", frm);
		//alert("Abteilungen des Bereichs "+v+" ("+bObj.options[bObj.selectedIndex].value+"=>"+bObj.options[bObj.selectedIndex].value+")");
		var el = document.forms[frm].elements["abteilungen_id["+k+"]"];
		var j;
		var selectedValue = (el.selectedIndex >= 0) ? el.options[el.selectedIndex].value : "";
		var selectIndex = -1;
		el.options.length = 1;
		el.options[0].value = ""; el.options[0].text = "...";
		for(var i in aAbteilungen) if (aAbteilungen[i]["b"] == v) {
			j = el.options.length;
			el.options.length = j+1;
			el.options[j].value = i;
			el.options[j].text = aAbteilungen[i]["a"];
			if (el.options[j].value == selectedValue) selectIndex = j;
			//alert("i:"+i+" "+el.options[j].value+"=>"+el.options[j].text);
		}
		if (selectIndex > -1) el.options[selectIndex].selected = true;
	}
	
	function reloadAllListSelectBereiche(oe) {
		if (typeof(oe) == "undefined") oe = "";
		var frm = 'frmListe';
		var f = document.forms['frmListe'];
		if (!f) return false;
		var elms = f.getElementsByTagName("select");
		var k;
		var v;
		if (!elms.length) elms[0] = elms;
		for (var i = 0; i < elms.length; i++) {
			if (elms[i].name.indexOf("bereich[") != -1) {
				k = parseInt(elms[i].name.substr(8));
				v = getFormElementValue(f.elements["gf["+k+"]"], frm);
				//alert("reload "+elms[i].name+"; k:"+k+"; oe:"+oe+"; v:"+v);
				if (!v || (oe && oe != v)) continue;
				reloadListSelectBereiche(k);
			}
		}
	}
	
	function reloadAllListSelectAbteilungen(b) {
		if (typeof(b) == "undefined") b = "";
		var frm = 'frmListe';
		var f = document.forms['frmListe'];
		if (!f) return false;
		var elms = f.getElementsByTagName("select");
		var k;
		if (!elms.length) elms[0] = elms;
		for (var i = 0; i < elms.length; i++) {
			if (elms[i].name.indexOf("abteilungen_id[") != -1) {
				k = parseInt(elms[i].name.substr(15));
				v = getFormElementValue(f.elements["bereich["+k+"]"], frm);
				if (!b || b == v) {
					//alert("reload "+elms[i].name+"; k:"+k+"; b:"+b+"; v:"+v);
					reloadListSelectAbteilungen(k);
				}
			}
		}
	}
	
	function checkExternFirma(frm) {
		var v = getFormElementValue('extern', frm);
		document.forms[frm].elements['extern_firma'].disabled = (v != "Ja" && v != "Extern");
	}
	
	function checkListExternFirma(k) {
		var frm = "frmListe";
		var v = getFormElementValue('extern['+k+']', frm);
		document.forms[frm].elements['extern_firma['+k+']'].disabled = (v != "Ja" && v != "Extern");
	}
	
	function reloadAllListExternFirma() {
		var frm = 'frmListe';
		var f = document.forms['frmListe'];
		if (!f) return false;
		var elms = f.getElementsByTagName("select");
		var k;
		if (!elms.length) elms[0] = elms;
		for (var i = 0; i < elms.length; i++) {
			k = "?";
			if (elms[i].name.indexOf("extern[") != -1) {
				k = parseInt(elms[i].name.substr(7));
				checkListExternFirma(k);
			}/**/
			//alert("reloadAllListExternFirma() #242 i:"+i+"/"+elms.length+"; k:"+k+"; elms[i].name:"+elms[i].name);
		}
	}
	
	function checkArbeitsplatzNr(frm) {
		var vex = getFormElementValue('extern', frm);
		var vrt = getFormElementValue('raum_typ', frm);
		var vbt = getFormElementValue('buerotyp', frm);
		document.forms[frm].elements['arbeitsplatznr'].disabled = (vex != "Spare" && vrt != "GBUE");
	}
	
	function checkAbteilungsAuswahl(frm) {
		var v = getFormElementValue('abteilungs_kategorie', frm);
		document.forms[frm].elements['gf'].disabled = (v == "");
		document.forms[frm].elements['bereich'].disabled = (v == "GF" || v == "");
		document.forms[frm].elements['abteilung'].disabled = (v != "Abteilung");
	}
	
	function checkListAbteilungsAuswahl(k) {
		var frm = "frmListe";
		var v = getFormElementValue('abteilungs_kategorie['+k+']', frm);
		document.forms[frm].elements['gf['+k+']'].disabled = (v == "");
		document.forms[frm].elements['bereich['+k+']'].disabled = (v == "GF" || v == "");
		document.forms[frm].elements['abteilungen_id['+k+']'].disabled = (v != "Abteilung");
	}
	
	$(document).ready(function() { reloadAllListExternFirma();});
	
	// LiveSearch
	function get_SearchInputListeOrte(obj) {
		SelBox_Simple(obj, {
			qUrl:'livesearch.php',
			qName:'Ort',
			onBeforeInsert:function(d) {
				O("gebaeudeFilter").value="";
				O("etageFilter").value="";
				return true;
			},
			onAfterInsert:function(d) {
				O("gebaeudeFilter").focus();
				//get_SearchInputListeGebaeude();
				return true;
			} 
		});
	}
	
	function get_SearchInputListeGebaeude(obj) {
		if (!O('ortsFilter').value) {
			alert("Wählen Sie erst einenStandort aus!");
			O("ortsFilter").focus();
			//get_SearchInputListeOrte(O('ortsFilter'));
			return false;
		}
		SelBox_Simple(obj, {
			qUrl:'livesearch.php',
			qName:'Gebaeude',
			qAdd: "&ort="+escape(O('ortsFilter').value),
			onBeforeInsert:function(d) { 
				//alert('onBeforeInsert:'+d);
				O("etageFilter").value="";
				return true;
			},
			onAfterInsert:function(d) { 
				//alert('onAfterInsert:'+d);
				O("etageFilter").click();
				//get_SearchInputListeEtage();
				return true;
			}
		});
	}
	
	function get_SearchInputListeEtage(obj) {
		if (!document.frmMAFilter.elements['ort'].value) {
			alert("Wählen Sie erst einen Standort aus!");
			//O("ortsFilter").focus();
			//get_SearchInputListeOrte();
			return false;
		}
		if (!document.frmMAFilter.elements['gebaeude'].value) {
			alert("Wählen Sie erst ein Gebaeude aus!");
			//O("gebaeudeFilter").focus();
			//get_SearchInputListeGebaeude();
			return false;
		}
		SelBox_Simple(obj, {
			qUrl:'livesearch.php',
			qName:'Etage',
			qAdd: "&ort="+escape(O('ortsFilter').value)+"&gebaeude="+escape(O('gebaeudeFilter').value),
			onBeforeInsert:function(d) { 
				//alert('onBeforeInsert:'+d); 
				return true;
			},
			onAfterInsert:function(d) { 
				//alert('onAfterInsert:'+d); 
				return true;
			} 
		});
	}
	
	function dropMa(MaId, rowId) {
		if (!confirm("Arbeitsplatzdaten löschen?")) return false;
		
		var sUrl = "bestandsaufnahme_loeschen.php?cat=mitarbeiter&id="+MaId+"&rowId="+rowId;
		SendRequest(sUrl, 'frmEditData');
	}
	