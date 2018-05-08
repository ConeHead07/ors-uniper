
function dienstleister_neu_anlegen() {
    dienstleister_fields().attr({readonly:null}).css({backgroundColor:"#dfd"}).val('');
    $("#dl_firmenname").focus();
}

function dienstleister_fields() {
    return $("#dl_id, #dl_firmenname, " +
             "#dl_ansprechpartner, #dl_ort, " +
             "#dl_strasse, #dl_handy, " +
             "#dl_festnetz, #dl_email");
}

function dienstleister_new_search() {
    if (typeof(O('SelectDienstleister'))!="object") return false;

    var SearchField = O('SelectDienstleister');

    var SBBoxId = "SBItems";

    if (typeof(O(SBBoxId)=="object") && typeof(O(SBBoxId).SBConf)=="object" && O(SBBoxId).captureEvents) {
        SelBox_release(O(SBBoxId));
    }

    get_dienstleister(O('SelectDienstleister'));
}

function get_dienstleister(obj) {
    if (typeof(O(obj))!="object") return false;

    var SBConfMa = getCopyOfArray(SBConfDefault);
    SBConfMa["InputField"] = obj;
    SBConfMa["OnInput"]    = dienstleister_check_reload;
    SBConfMa["OnSelect"]   = dienstleister_getSelection;
    SBConfMa["OnEnterClose"] = true;
    SBConfMa["InputType"]  = "Nachname";

    var SBBoxId = "SBItems";
    var Placeholder = new Array();
    var SBBox = getcreateDivBoxById(SBBoxId, "SelBoxEasy SelBoxMitarbeiter");
    if (typeof(SBBox.SBConf)=="object" && SBBox.SBConf["InputField"]==obj && SBBox.captureEvents) return true;
    if (typeof(SBBox.SBConf)=="object" && SBBox.captureEvents) SelBox_release(SBBox);

    SelBox_capture(SBBox, SBConfMa, Placeholder);
    dockBox(obj, SBBox);

    dienstleister_check_reload(SBBox);
}

function dienstleister_check_reload(parentbox) {
	if (!parentbox || typeof(O(parentbox))!="object") return false;
	parentbox = O(parentbox);
	var send_query = 0;
	var limit = 10;
	
	//alert("#657 "+parentbox.SBConf["InputType"]);
	var lastQuery = {Input:"",Size:-1,NumAll:-1};
		
        if (parentbox.SBConf["InputField"].value.length) {
            
            if (typeof(optionsDienstleister)=="object") {
                    if (typeof(optionsDienstleister["Query"])=="string") lastQuery["Input"] = optionsDienstleister["Query"];
                    if (typeof(optionsDienstleister["NumAll"])=="number") lastQuery["NumAll"] = optionsDienstleister["NumAll"];
                    if (typeof(optionsDienstleister["Size"])=="number") lastQuery["Size"] = optionsDienstleister["Size"];
            }

            if (!lastQuery["Input"] || lastQuery["NumAll"] > limit) send_query = 1;
            if (typeof(optionsDienstleister)!="object" || !optionsDienstleister["Data"].length) send_query = 2;
            else if(lastQuery["Input"].length > parentbox.SBConf["InputField"].value.length) send_query = 3;
            else if(parentbox.SBConf["InputField"].value.toUpperCase().indexOf(lastQuery["Input"].toUpperCase())!=0) send_query = 4;

            ////document.getElementsByTagName("textarea")[0].value = "send_query:"+send_query+" Input:"+parentbox.SBConf["InputField"].value+"\n";
            //for(i in lastQuery) //document.getElementsByTagName("textarea")[0].value+= i+": "+lastQuery[i]+"\t";

            if (send_query) {

                var CBoxLoading = document.createElement("span");
                CBoxLoading.style.display="block";
                CBoxLoading.className = "SelBoxItem";
                CBoxLoading.innerHTML = "<em style=\"italic\">Daten werden geladen</em>";
                CBoxLoading.onmouseover = function() { AC(this, "IsHoverItem"); }
                CBoxLoading.onmouseout = function() { RC(this, "IsHoverItem"); }
                SelBox_addControlBox(parentbox, CBoxLoading);

                request_query_dienstleister(parentbox.id, parentbox.SBConf["InputField"].value, 10);
            }
        } else {
            var CBoxInfo = document.createElement("span");
            CBoxInfo.className = "SelBoxItem";
            CBoxInfo.style.display="block";
            CBoxInfo.innerHTML = "</strong> <em style=\"italic\">warte auf Eingabe: Firmenname, Ort oder Ansprechpartner ...</em>";
            SelBox_addControlBox(parentbox, CBoxInfo);
        }
}


function dienstleister_getSelection(parentbox) {
	//alert("mitarbeiter_getSelection");
	if (typeof(O(parentbox))!="object") return false;
	var parentbox = O(parentbox);
	var SubItem = SelBox_getSelectedItem(parentbox);
        dienstleister_fields().attr({readonly:"readonly"}).css({backgroundColor:"inherit"});
	
        $("#dl_id").val(SubItem.id);
        $("#dl_firmenname").val(SubItem.Firmenname);
        $("#dl_ansprechpartner").val(SubItem.Ansprechpartner);
        $("#dl_ort").val(SubItem.Ort);
        $("#dl_strasse").val(SubItem.Strasse);
        $("#dl_handy").val(SubItem.Handy);
        $("#dl_festnetz").val(SubItem.Festnetz);
        $("#dl_email").val(SubItem.Email);
        $("#dl_bemerkung").val(SubItem.Bemerkung);
		
	return false;
}

function request_query_dienstleister(SBBoxId, query, limit) {
    igWShowLoadingBar(1, "Dienstleister werden geladen!", SBBoxId);
    AjaxRequestUrl = "load_dienstleister.php?";
    AjaxRequestUrl+= '&query='+escape(query);
    AjaxRequestUrl+= '&limit='+escape(limit);
    AjaxRequestUrl+= '&SBBoxId='+escape(SBBoxId);
    AjaxRequestUrl+= '&resultFormat=XML';
    //alert(AjaxRequestUrl);
    fb_AjaxRequest(AjaxRequestUrl, 'get', "fb_AjaxXmlUpdate(%req%)");
}



