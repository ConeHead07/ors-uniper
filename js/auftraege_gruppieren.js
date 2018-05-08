
function gruppierung_neu_anlegen() {
    gruppierungsauftrag_fields().attr({readonly:null}).css({backgroundColor:"#dfd"}).val('');
    $("#dl_firmenname").focus();
}

function gruppierungsauftrag_fields() {
    return $("#dl_id, #dl_firmenname, " +
             "#dl_ansprechpartner, #dl_ort, " +
             "#dl_strasse, #dl_handy, " +
             "#dl_festnetz, #dl_email");
}

function gruppierungsauftrag_new_search() {
    if (typeof(O('SelectGruppierungsauftrag'))!="object") return false;

    var SearchField = O('SelectGruppierungsauftrag');

    var SBBoxId = "SBItems";

    if (typeof(O(SBBoxId)=="object") && typeof(O(SBBoxId).SBConf)=="object" && O(SBBoxId).captureEvents) {
        SelBox_release(O(SBBoxId));
    }

    get_gruppierungsauftrag(O('SelectGruppierungsauftrag'));
}

function get_gruppierungsauftrag(obj) {
    if (typeof(O(obj))!="object") return false;

    var SBConfMa = getCopyOfArray(SBConfDefault);
    SBConfMa["InputField"] = obj;
    SBConfMa["OnInput"]    = gruppierungsauftrag_check_reload;
    SBConfMa["OnSelect"]   = gruppierungsauftrag_getSelection;
    SBConfMa["OnEnterClose"] = true;
    SBConfMa["InputType"]  = "Nachname";

    var SBBoxId = "SBItems";
    var Placeholder = new Array();
    var SBBox = getcreateDivBoxById(SBBoxId, "SelBoxEasy SelBoxMitarbeiter");
    if (typeof(SBBox.SBConf)=="object" && SBBox.SBConf["InputField"]==obj && SBBox.captureEvents) return true;
    if (typeof(SBBox.SBConf)=="object" && SBBox.captureEvents) SelBox_release(SBBox);

    SelBox_capture(SBBox, SBConfMa, Placeholder);
    dockBox(obj, SBBox);

    gruppierungsauftrag_check_reload(SBBox);
}

function gruppierungsauftrag_check_reload(parentbox) {
	if (!parentbox || typeof(O(parentbox))!="object") return false;
	parentbox = O(parentbox);
	var send_query = 0;
	var limit = 10;
	
	//alert("#657 "+parentbox.SBConf["InputType"]);
	var lastQuery = {Input:"",Size:-1,NumAll:-1};
		
        if (parentbox.SBConf["InputField"].value.length) {
            
            if (typeof(optionsGruppierungsauftrag)=="object") {
                    if (typeof(optionsGruppierungsauftrag["Query"])=="string") lastQuery["Input"] = optionsGruppierungsauftrag["Query"];
                    if (typeof(optionsGruppierungsauftrag["NumAll"])=="number") lastQuery["NumAll"] = optionsGruppierungsauftrag["NumAll"];
                    if (typeof(optionsGruppierungsauftrag["Size"])=="number") lastQuery["Size"] = optionsGruppierungsauftrag["Size"];
            }

            if (!lastQuery["Input"] || lastQuery["NumAll"] > limit) send_query = 1;
            if (typeof(optionsGruppierungsauftrag)!="object" || !optionsGruppierungsauftrag["Data"].length) send_query = 2;
            else if(lastQuery["Input"].length > parentbox.SBConf["InputField"].value.length) send_query = 3;
            else if(parentbox.SBConf["InputField"].value.toUpperCase().indexOf(lastQuery["Input"].toUpperCase())!=0) send_query = 4;

            ////document.getElementsByTagName("textarea")[0].value = "send_query:"+send_query+" Input:"+parentbox.SBConf["InputField"].value+"\n";
            //for(i in lastQuery) //document.getElementsByTagName("textarea")[0].value+= i+": "+lastQuery[i]+"\t";

            if (1 || send_query) {

                var CBoxLoading = document.createElement("span");
                CBoxLoading.style.display="block";
                CBoxLoading.className = "SelBoxItem";
                CBoxLoading.innerHTML = "<em style=\"italic\">Daten werden geladen</em>";
                CBoxLoading.onmouseover = function() { AC(this, "IsHoverItem"); }
                CBoxLoading.onmouseout = function() { RC(this, "IsHoverItem"); }
                SelBox_addControlBox(parentbox, CBoxLoading);

                request_query_gruppierungsauftrag(parentbox.id, parentbox.SBConf["InputField"].value, 10);
            }
        } else {
            var CBoxInfo = document.createElement("span");
            CBoxInfo.className = "SelBoxItem";
            CBoxInfo.style.display="block";
            CBoxInfo.innerHTML = "</strong> <em style=\"italic\">warte auf Eingabe: Auftragsid, Datum(Antrag, Umzug), Etage oder Ansprechpartner ...</em>";
            SelBox_addControlBox(parentbox, CBoxInfo);
        }
}

function auftragsliste_add(id, item) {
	if (auftragsliste_hasId(id)) return true;
	
	var ai = $("#gruppierteauftraege");
	var newItem = $("#TplGruppierungsTable tr:first").clone().attr("data-id", id);
	for(var i in item) {
		newItem.find("[data-fld=" + i + "]").html( item[i] );
	}
	newItem.find("[data-editid]").attr("data-editid", id);
	newItem.find("[data-lnkto=umzug]").attr("href", '?s=aantrag&id=' + id);
	
	$("#TblGruppierungenBody").append( newItem );
	
	ai.val( (ai.val() + "," + id + ",").split(",,").join(",") );
}

function auftragsliste_remove(id) {
	var ai = $("#gruppierteauftraege"), al = $("#TblGruppierungenBody");
	ai.val((","+ai.val()+",").replace(","+id+",", ",").split(/,{2,}/).join(","));
	
	al.find("tr[data-id=" + id + "]").remove();
}

function auftragsliste_hasId(id) {
	var ai = $("#gruppierteauftraege");
	return (","+ai.val()+",").indexOf(","+id+",") !== -1;
}

function gruppierungsauftrag_getSelection(parentbox) {
	//alert("mitarbeiter_getSelection");
	if (typeof(O(parentbox))!="object") {
		console.log("#100 Can not find parentbox!");
		return false;
	} else {
		console.log("#103 Found parentbox :-)");
	}
	var parentbox = O(parentbox);
	var SubItem = SelBox_getSelectedItem(parentbox);
	console.log("#108");
	
	auftragsliste_add(SubItem.aid, SubItem);
	return false;
}

function request_query_gruppierungsauftrag(SBBoxId, query, limit) {
    igWShowLoadingBar(1, "Auftraege werden geladen!", SBBoxId);
    AjaxRequestUrl = "load_gruppierungsauftrag.php?";
    AjaxRequestUrl+= '&query='+escape(query);
    AjaxRequestUrl+= '&limit='+escape(limit);
    AjaxRequestUrl+= '&SBBoxId='+escape(SBBoxId);
    AjaxRequestUrl+= '&resultFormat=XML';
    //alert(AjaxRequestUrl);
    fb_AjaxRequest(AjaxRequestUrl, 'get', "fb_AjaxXmlUpdate(%req%)");
}
