

var optionsGeraete = [
	{value:"Fax", content:"Fax"},
	{value:"Drucker", content:"Drucker"}
];

function geraet_hinzufuegen() {
    if (!O("TplGeraet")) return false;
    if (!O("TblGeraeteBody")) return false;

    var newG = O("TplGeraet").cloneNode(true);
    newG.id = "";
    O("TblGeraeteBody").appendChild(newG);
}

function dropGeraet(obj) {
    if (!O("TblGeraeteBody")) return false;

    var rowG = getParentNodeByTagName(obj, "tr");
    O("TblGeraeteBody").removeChild(rowG);

}

function get_geraet(obj) {
	if (typeof(O(obj))!=="object" || typeof(optionsGeraete)!=="object") return false;

	var SBBoxId = "SBItems";
	var SBBox = getcreateDivBoxById(SBBoxId, "SelBoxEasy SelBoxUmzugsart");
	if (typeof(SBBox.SBConf)==="object" && SBBox.captureEvents) SelBox_release(SBBox, true);

	var SBConfUa = getCopyOfArray(SBConfDefault);
	SBConfUa["InputField"] = obj;
	SBConfUa["OnEnterClose"] = true;

	SelBox_capture(SBBox, SBConfUa, optionsGeraete);
	dockBox(obj, SBBox);
}

function frmSerializeGeraete() {
    if (!("AS[geraete_csv]" in document.forms["frmUmzugsantrag"].elements)) return;
    document.forms["frmUmzugsantrag"].elements["AS[geraete_csv]"].value = get_geraete_csv();
}

function get_geraete_csv() {
    if (!O("TblGeraeteBody")) return "";
    var rows = O("TblGeraeteBody").getElementsByTagName("tr");
    var inputNodes = new Array();
    var inputFields = { Art:"", Nr:"", Von:"", Nach:"" };
    var GeraeteCsv = "";
    var rowSep = "\n";
    var colSep = "\t";
    if (rows) {

        for (var i = 0; i < rows.length; i++) {
            inputNodes = rows[i].getElementsByTagName("input");
            inputFields = { Art:"", Nr:"", Von:"", Nach:"" };
            for (var j = 0; j < inputNodes.length; j++) {
                switch(inputNodes[j].name) {
                    case "G[Art][]":
                    inputFields["Art"] = inputNodes[j].value;
                    break;

                    case "G[Nr][]":
                    inputFields["Nr"] = inputNodes[j].value;
                    break;

                    case "G[Von][]":
                    inputFields["Von"] = inputNodes[j].value;
                    break;

                    case "G[Nach][]":
                    inputFields["Nach"] = inputNodes[j].value;
                    break;
                }
            }
            GeraeteCsv+= (GeraeteCsv.length > 0 ? rowSep : "")+inputFields["Art"]+colSep+inputFields["Nr"]+colSep+inputFields["Von"]+colSep+inputFields["Nach"];
        }
    }
    //alert(GeraeteCsv);
    return GeraeteCsv;
}