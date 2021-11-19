<?php
/* Smarty version 3.1.34-dev-7, created on 2021-11-19 02:45:28
  from '/var/www/html/html/umzugsformular_leistungsauswahl.tpl.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_619701b8b10912_59974813',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '346d952b773372d484fde33cfdf99fd837ff0bb3' => 
    array (
      0 => '/var/www/html/html/umzugsformular_leistungsauswahl.tpl.html',
      1 => 1637286320,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_619701b8b10912_59974813 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div style="display:block;margin-top:15px;">
    <!-- umzugsformular_leistungsauswahl.tpl.html -->
</div>
<link rel="stylesheet" type="text/css" href="{WebRoot}/css/auftragsformular_leistungsauswahl.css?%assetsRefreshId%">
<?php echo '<script'; ?>
>
var aid = <?php echo $_smarty_tpl->tpl_vars['AIDJson']->value;?>
;
var lkItems = <?php echo $_smarty_tpl->tpl_vars['lkTreeItemsJson']->value;?>
;
var lkmById = <?php echo $_smarty_tpl->tpl_vars['lkmByIdJson']->value;?>
;
var umzugsleistungen = <?php echo $_smarty_tpl->tpl_vars['UmzugsleistungenJson']->value;?>
;
var umzugsstatus = <?php echo $_smarty_tpl->tpl_vars['umzugsstatusJson']->value;?>
;
var antragsstatus = <?php echo $_smarty_tpl->tpl_vars['antragsstatusJson']->value;?>
;



$("body").append( $("<select/>" ).css({width:"220px",position:"absolute", zIndex:99, minWidth:250}).attr({id: "lktgselect", size:5}).hide()    );
$("body").append( $("<select/>" ).css({width:"220px",position:"absolute", zIndex:100, minWidth:280}).attr({id: "ldescselect", size:5}).hide()    );

function blurBox(callback, hide) {
    var blurBoxId = "BlurBox1";
    var body = $("body");
    if ( !$("#"+blurBoxId).length ) {
        
        body.append( $("<div/>").attr({id:blurBoxId}).css({position:"fixed",top:0,left:0,zIndex:98,display:"none"}) );
    }
    if (arguments.length > 1 && hide) {
        return $("#" + blurBoxId).hide();
    }
    $("#" + blurBoxId)
        .css({width:"100%",minWidth:body.width(),height:"100%",minHeight:body.height()})
        .show()
        .click(function(e){
          $( this ).hide();
          if (typeof(callback) === "function") {
              callback.call();
          }
          else {
              if (callback) $( callback ).hide();
          }
    });
}

function get_Kategorie( obj ) {
   $("#lktgselect").html( $("<option/>").text("Bitte auswählen") );
   for(var ktg1 in lkItems) {
        $("#lktgselect").append( 
            $("<option/>").val(ktg1).text(ktg1).data("leistungen", lkItems[ktg1]).data("kategorie1", ktg1)
        );
   }
   
   $("#lktgselect").unbind("change").change(function(e) {
        $( obj ).data( "kategorie1", $(this).find("option:selected").data("kategorie1") );
        $( obj ).data( "leistungen", $.extend({}, $(this).find("option:selected").data("leistungen")));
        $( this ).hide();
        get_Leistung( $( obj ).closest("tr").find(".lstg").val("") );
    }).bind("blur", function(event){
        $( this ).hide();
    }).css({
       top: $(obj).offset().top + $(obj).height(),
       left: $(obj).offset().left
   }).show();
   blurBox( "#lktgselect" );
}

function lstgIsAllreadyInList(id, obj) {
    var re = 0;
    $("input.ilstg").not(obj).each(function(i){
        if ($(this).val() == id) re = 1;
    });
    return re;
}

function get_Leistung( obj ) {
    var objKtg1 = $( obj ).closest("tr").find(".ktg1");
    var objUnit = $( obj ).closest("tr").find(".unit:eq(0)");
    var objUnit2 = $( obj ).closest("tr").find(".unit:eq(1)");
    var objInpAll = $( obj ).closest("tr").find("input");
    var objInp = $( obj ).closest("tr").find("input.ilstg");
    var objInpM = $( obj ).closest("tr").find("input.menge:not([readonly]):not(.menge2)");
    var objInpM2 = $( obj ).closest("tr").find("input.menge2.creator");
    var objPreis = $( obj ).closest("tr").find("td.preis");
    var objSum = $( obj ).closest("tr").find("td.sum");
    if ( !$( objKtg1 ).data("kategorie1") ||  !$( objKtg1 ).data("leistungen") ) {
        get_Kategorie( objKtg1 );
        return;
    }
    
    var leistungen = $( objKtg1 ).data( "leistungen" );
    $("#ldescselect").html( $("<option/>").text("Bitte auswählen") );
    for(var lstg in leistungen) {
        $("#ldescselect").append( 
            $("<option/>").val(lstg).text(lstg).data("leistung", leistungen[lstg])
        );
    }
    $("#ldescselect").unbind("change").change(function(event){
        var data = $(this).find("option:selected").data("leistung");
        if ( lstgIsAllreadyInList(data.leistung_id, objInp) ) {
            alert("Die Leistung wurde bereits in die Liste aufgenommen!\n" + 
                  "Bitte fügen Sie die Mengen dem bestehenden Eintrag hinzu.\n"+
                  "Andernfalls werden bestehende Mengen überschrieben."
            );
            objInp.blur();
            return false;
        }
        
        $( objKtg1 ).html( $( objKtg1).data( "kategorie1") );
        $( obj ).html( data.leistung ).attr({"data-p": data.preis_pro_einheit, "data-sum":0});
        objInpAll.val("");
        $( objInp ).val( data.leistung_id );
        $( objUnit ).html( data.leistungseinheit );
        $( objUnit2 ).html( data.leistungseinheit2 );
        objPreis.html( numberFormat(data.preis_pro_einheit, 2, ",") );
        objSum.html("");
        if (data.leistungseinheit2) {
            objInpM2.val("1");
            objInpM2.removeAttr("readonly");
            objInpM2.unbind("change").bind("change", mengeChangeCallback);
        } else {
            objInpM2.val("");
            objInpM2.attr("readonly", "readonly");
            objInpM2.unbind("change");
        }
        objInpM.unbind("change");
        if (objUnit.html().toLowerCase().indexOf("prozent") < 0) objInpM.bind("change", mengeChangeCallback);
        
        $( this ).hide();
        blurBox( "#ldescselect", 1);
        if ( objInpM.length) objInpM.get(0).focus();
    }).bind("blur", function(event){
        $( this ).hide();
    }).css({
       top: $(obj).offset().top + $(obj).height(),
       left: $(obj).offset().left
   }).show();
    blurBox( "#ldescselect" );
}

function calc_AllSum() {
    $("#TblLeistungenBody").each(function(){
        var allsum = 0;
        $(this).find("td.lstg").each(function(){
            allsum+= parseFloat($(this).attr("data-sum"));
        });
        $(this).find("#allsum").attr("data-allsum",allsum).text(numberFormat(allsum,2, ",", "."));
    });
}

var numberFormat = function(number, dec, decToken, thousandSep) {
    var n = parseFloat(number).toFixed(dec).split(".");
    if (n[0].length > 3 && thousandSep) {
        var nr = n[0];
        var nnr= "";
        for(var i = 0; i < nr.length; ++i) {
            nnr+= nr.charAt(i);
            if (nr.length-(i+1) > 0 && (nr.length-(i+1)) % 3 === 0) nnr+=".";
        }
        n[0] = nnr;
    }
    return n.join(decToken);
}

var mengeChangeCallback = function() {
    var tr = $( this ).closest("tr");
    var objUnit = tr.find(".unit:eq(0)");
    if (objUnit.html().toLowerCase().indexOf("prozent") > -1) return;
        
    var id = tr.find("input.ilstg").val();
    var preis = parseFloat(tr.find("td.lstg").attr("data-p"));
    var val  = parseFloat(tr.find("input.menge.creator").val().replace(',', '.'));
    var val2 = parseFloat(tr.find("input.menge2.creator").val().replace(',', '.')) || 1;
    var mx = lkmById[ id ] || [];
    for(var i = 0; i < mx.length; ++i) {
        if ((1*mx[i].von) <= val && ( (1*mx[i].bis) < 0.01 || (1*mx[i].bis) >= val) ) {
            preis = mx[i].preis;
            tr.find("td.preis").html( numberFormat(preis, 2, ",", ".") );
            break;
        }
    }
    tr.find("td.lstg").attr("data-sum", preis * val * val2);
    tr.find("td.sum").html( numberFormat(preis * val * val2, 2, ",", ".") );
    calc_AllSum();
    return false;
};

$(function(){
    $( "input.menge.creator, input.menge2.creator" ).bind("change", mengeChangeCallback);
    calc_AllSum();
});

function drop_Leistung(obj) {
    $( obj ).closest( "tr" ).remove();
    calc_AllSum();
}

function add_Leistung() {
    if (!$("#TplLeistungTable tr.row").length ) {
        console.log("#196 length 0");
        return false;
    }
    if (!$("#TblLeistungenBody").length) {
        console.log("#200 length 0");
        return false;
    }

    var l = $("table#TplLeistungTable tr.row").clone(true);
    l.find("input:disabled").each(function(){
        this.disabled = false;
    }); //.prop("disabled", false);
    $("#TblLeistungenBody tr#summary").before(l);
    $(".ktg1", l ).trigger("click");
}

$(function(){
    $( "input.menge.creator,input.menge2.creator" ).bind("change", mengeChangeCallback);
});

<?php echo '</script'; ?>
>

<div id="auswahlUmzugsleistungenBox"></div>


<?php echo '<script'; ?>
>
    function escapeSelector(name) {
        return name.replaceAll('[', '\\[').replaceAll(']', '\\]');
    }

    var createSelectLstgInput = function(leistungen, name, value, disabled) {

        var selectBox = $("<select/>").attr({name});
        selectBox.append(
            $("<option/>")
                .val('')
                .text('Bitte auswählen')
        );
        for (var it of leistungen) {
            var isSelected = it.leistung_id === value;
            var optText = '';
            if (it.Farbe) {
                optText = it.Farbe;
            }
            if (it.Groesse) {
                optText+= (optText.length ? ', ' : '') + it.Groesse;
            }
            if (!optText) {
                optText = it.Bezeichnung;
            }
            selectBox.append(
                $("<option/>")
                    .val(it.leistung_id)
                    .text(optText)
                    .data(JSON.parse(JSON.stringify(it)))
                    .prop("selected", isSelected)
            );
        }
        return selectBox;
    };
    var createHiddenLstgInput = function(name, value, disabled, classNames, data) {
        var elm = $("<input/>").attr({
            name,
            value,
            type: "hidden",
            disabled
        });

        if (data && (typeof data === 'object') && Object.keys(data).length > 0) {
            for(var k in data) {
                if (!data.hasOwnProperty(k)) continue;
                elm.attr('data-' + k, data[k]).data(k, data[k]);
            }
        }

        return elm
            .prop("disabled", disabled)
            .addClass("lstg-input " +classNames);
    };
    var checkDisableState = function () {
        var success = true;
        var container = $("#auswahlUmzugsleistungenBox");
        var rows = container.find(".row");
        rows.each(function() {
            var row = $(this);
            var name = row.find("div.lstg").text();
            var rowCheckbox = row.find("div.chck label input[type=radio]");
            var rowIsChecked = row.is(".checked");
            var chckIsChecked = rowCheckbox.prop("checked");
            console.log("STATUS-CHECK: " + name);
            if (rowIsChecked !== chckIsChecked) {
                console.error("STATUS - FEHLER", { rowIsChecked, chckIsChecked });
                rowCheckbox.prop("checked");
                success = false;
            } else {
                console.log("OK: row.checked == inputRadio.checked");
            }
            row.find("div.chck > input").each(function() {
                var inputName = $(this).attr("name");
                var isEnabled = !$(this).prop("disabled");
                var isDisabled = $(this).prop("disabled");
                if (rowIsChecked !== isEnabled) {
                    console.error("INPUT-DISABLED-FEHLER ", { name, rowIsChecked, isEnabled });
                    $(this).prop("disabled", !rowIsChecked);
                    success = false;
                } else {
                    console.log("OK: row.checked == input.enabled", { name, rowIsChecked, isEnabled });
                }
            });
        });
        return success;
    };

    var toggleBundleauswahl = function(ktgChecked, ktgIdChecked, ktgBundleName, ktgBundleId) {
        var boxLeistungenSel = "div#auswahlUmzugsleistungenBox";
        var boxLeistungen = $(boxLeistungenSel);
        if (1) {
            var rowSelector = (ktgChecked === ktgBundleName || ktgIdChecked === ktgBundleId)
                ? ".row:not([data-ktg=" + ktgBundleName + '])'
                : ".row[data-ktg=" + ktgBundleName + ']';

            boxLeistungen.find(rowSelector).each(function() {
                var ktg = $(this).toggleClass("checked", false).attr("data-ktg");
                $(this).find('div.chck input').each(function() {
                    if (this.type === 'radio') {
                        this.checked = false;
                    } else {
                        this.disabled = true;
                    }
                    var name = this.name;
                    var type = this.type;
                    var checked = (type === 'radio') ? this.checked : '';
                    var disabled = (type !== 'radio') ? this.disabled : '';
                    console.log({ktg, name, type, checked, disabled});
                });
            });
        }
    };

    var renderBestellListe = function(umzugsstatus) {
        alert("renderBestellListe()\n" + JSON.stringify(arguments));
        var boxLeistungenSel = "div#auswahlUmzugsleistungenBox";
        var boxLeistungen = $(boxLeistungenSel);
        if (!boxLeistungen.length) {
            alert("NOT FOUND " + boxLeistungenSel);
        }
        boxLeistungen.html("");

        var liste = umzugsleistungen;
        for (var i = 0; i < liste.length; i++) {

            var it = liste[i];
            var ktg = it.kategorie;
            var ktgId = +it.kategorie_id;
            if (ktgId === 18 || ktgId === 25) {
                continue;
            }

            console.log({ktg});
            var box = $("<div/>");
            var boxTitle = $("<h3/>").text("Auswahl: " + ktg);
            boxTitle.appendTo(box);

                var lstId = it.leistung_id;
                var tr = $("<div/>").addClass("row ktg-" + ktgId).attr({
                    "data-ktg": ktg,
                    "data-ktgId": ktgId,
                    "data-id": lstId
                });
                var tdBild = $("<div/>").addClass('bild').appendTo(tr);
                var tdLstg = $("<div/>").addClass('lstg').appendTo(tr);
                var tdChck = $("<div/>").addClass('chck').appendTo(tr);

                var leistungInfos = '<div class="Bezeichnung">' + it.leistung + '</div>';
                if (it.Beschreibung) {
                    leistungInfos+= '<div class="Beschreibung">' + it.Beschreibung + '</div>';
                }
                if (it.produkt_link) {
                    leistungInfos+= '<div class="produkt_link"><a href="' + it.produkt_link + '" target="_blank">Mehr Infos</a></div>';
                }
            console.log('#479 ', {it});

                if (it.image) {
                    var bildHref = $("<a/>")
                        .attr("href", "images/leistungskatalog/" + it.image)
                        .attr("target", "_blank")
                        .css({
                            border: 0
                        });
                    if (it.produkt_link) {
                        bildHref.attr("href", it.produkt_link);
                    }
                    bildHref.appendTo(tdBild);
                    var bild = $("<img/>")
                        .attr({
                            src: "images/leistungskatalog/" + it.image
                        })
                        .appendTo(bildHref);
                }

                tdLstg.html( leistungInfos ).off("click").on("click", function () {
                    var tr = $(this).closest(".row");
                    // tr.find(".chck label")[0].click();
                    console.log('click leistung');
                });

                var labelChck = $("<label/>").addClass("container");
                var inputChck = $("<input/>").attr({
                    name: "check[" + it.kategorie_id + "]",
                    type: "radio",
                    value: it.leistung_id
                }).prop("checked", true);

                var spanChck = $("<span/>").addClass("checkmark");
                labelChck.append(inputChck).append(spanChck);
                tdChck.append(labelChck);
                var _ldata = {leistung_id: it.leistung_id};
                var _lclss = ' group-leistungid-' + it.leistung_id;
                tdChck
                    .append(
                        createHiddenLstgInput('L[leistung_id][]', it.leistung_id, true, 'leistung_id' + _lclss, _ldata)
                    )
                    .append(
                        createHiddenLstgInput('L[menge_property][]', 0, true, 'menge menge_property' + _lclss, _ldata)
                    )
                    .append(
                        createHiddenLstgInput('L[menge2_property][]', 0, true, 'menge menge2_property' + _lclss, _ldata)
                    )
                    .append(
                        createHiddenLstgInput('L[menge_mertens][]', 0, true, 'menge menge_mertens' + _lclss, _ldata)
                    )
                    .append(
                        createHiddenLstgInput('L[menge2_mertens][]', 0, true, 'menge menge2_mertens' + _lclss, _ldata)
                    );

                box.append(tr);

            box.appendTo(boxLeistungen);
        }
    };

    var renderAuswahlListe = function() {
        var boxLeistungenSel = "div#auswahlUmzugsleistungenBox";
        var boxLeistungen = $(boxLeistungenSel);
        var selectedLIds = [];
        if (!boxLeistungen.length) {
            alert("NOT FOUND " + boxLeistungenSel);
        }
        boxLeistungen.html("");


        for(var i = 0; i < umzugsleistungen.length; i++) {
            selectedLIds.push( +umzugsleistungen[i].leistung_id );
        }

        var lkItemsByKtg = lkItems;
        for (var ktg in lkItemsByKtg) {

            if (!lkItemsByKtg.hasOwnProperty(ktg)) {
                continue;
            }
            var ktgItems = Object.values(lkItemsByKtg[ktg]);

            if (ktg === "Transportpositionen" || ktg === "Rabatt") {

                for (var it of ktgItems) {
                    var _data = {leistung_id: it.leistung_id};
                    // alert("it.verfuegbar: " + it.verfuegbar);
                    var _clss = ' group-leistungid-' + it.leistung_id;
                    if (!"verfuegbar" in it || it.verfuegbar !== "Nein") {
                        boxLeistungen
                            .append(
                                createHiddenLstgInput('L[leistung_id][]', it.leistung_id, true, 'leistung_id' + _clss, _data)
                            )
                            .append(
                                createHiddenLstgInput('L[menge_property][]', 0, true, 'menge menge_property' + _clss, _data)
                            )
                            .append(
                                createHiddenLstgInput('L[menge2_property][]', 0, true, 'menge menge2_property' + _clss, _data)
                            )
                            .append(
                                createHiddenLstgInput('L[menge_mertens][]', 0, true, 'menge menge_mertens' + _clss, _data)
                            )
                            .append(
                                createHiddenLstgInput('L[menge2_mertens][]', 0, true, 'menge menge2_mertens' + _clss, _data)
                            );
                    }
                }
                continue;
            }

            console.log({ktg});
            var box = $("<div/>");
            var boxTitle = $("<h3/>").text("Auswahl: " + ktg);
            var ktgBundleName = "Komplettpaket";
            var ktgBundleId = 18;
            var ktgRabatName = "Rabatt";
            var ktgRabatId = 25;

            var aListStammIds = [];
            boxTitle.appendTo(box);
            for (var it of ktgItems) {
                console.log({it});
                var ktgId = it.kategorie_id;
                var lstId = it.leistung_id;
                var stammId = it.leistung_stamm_id > 0 ? it.leistung_stamm_id : lstId;
                var isSelected = selectedLIds.indexOf(lstId) > -1;
                var isStammArtikel = !stammId || stammId === lstId;
                var istVerfuegbar = ("verfuegbar" in it) ? (it.verfuegbar !== "Nein") : true;
                var numVarianten = ( ("NumVarianten" in it) && !isNaN(it.NumVarianten)) ? +it.NumVarianten : 0;

                if (isStammArtikel) {
                    aListStammIds.push(stammId);
                    var tr = $("<div/>").addClass("row ktg-" + ktgId + " stamm-" + stammId).attr({
                        "data-ktg": ktg,
                        "data-ktgId": ktgId,
                        "data-id": lstId,
                        "stamm-id": lstId
                    }).data('leistung', it);
                    var tdBild = $("<div/>").addClass('bild').appendTo(tr);
                    var tdLstg = $("<div/>").addClass('lstg').appendTo(tr);
                    var tdChck = $("<div/>").addClass('chck').appendTo(tr);
                    console.log('#603 ', {it});

                    var lstgBezeichnung = $("<div/>").addClass("Bezeichnung").html(it.leistung);
                    var lstgBeschreibung = $("<div/>").addClass("Beschreibung");
                    var lstgProduktLinkBox = $("<div/>").addClass("produkt_link");
                    var lstgProduktLink = $("<a/>").attr("target", "_blank");
                    var lstgImage = $("<img/>");
                    var lstgImgHref = $("<a/>").attr("target", "_blank");

                    lstgProduktLinkBox.append(lstgProduktLink);
                    lstgImgHref.append(lstgImage);
                    tdBild.append(lstgImgHref);

                    if (it.Beschreibung) {
                        lstgBeschreibung.html(it.Beschreibung);
                    } else {
                        lstgBeschreibung.hide();
                    }

                    if (it.produkt_link) {
                        lstgProduktLink.attr("href", it.produkt_link).text("Mehr Infos");
                        lstgImgHref.attr("href", it.produkt_link);
                    } else {
                        lstgProduktLinkBox.hide();
                        lstgProduktLink.hide();
                    }

                    if (it.image) {
                        lstgImage.attr({
                                src: "images/leistungskatalog/" + it.image
                            });
                    } else {
                        lstgImage.hide();
                    }

                    tdLstg.append( lstgBezeichnung)
                        .append(lstgBeschreibung)
                        .append(lstgProduktLinkBox);

                    tdLstg.off("click").on("click", function () {
                        var tr = $(this).closest(".row");
                        // tr.find(".chck label")[0].click();
                        console.log('click leistung');
                    });

                    var inputSelect = null;
                    var selectedLeistungId = isSelected ? it.leistung_id : 0;
                    if (numVarianten) {
                        tr.addClass("leistung-mit-varianten");
                        var optionsLeistungen = [ it ];
                        var variantenDisabled = false;
                        for (var itV of ktgItems) {
                            if (itV.leistung_stamm_id === stammId && itV.leistung_id !== lstId) {
                                optionsLeistungen.push(itV);
                                if (selectedLIds.indexOf(+itV.leistung_id) > -1) {
                                    selectedLeistungId = itV.leistung_id;
                                }
                            }
                        }
                        inputSelect = createSelectLstgInput(optionsLeistungen, 'X[leistung_id][]', selectedLeistungId, variantenDisabled);

                        inputSelect.on("change", function() {
                            $(this).find("option:selected").each(function() {
                                var row = $(this).closest(".row");
                                var optVal = $(this).val();
                                var lstgData = (optVal) ? $(this).data() : row.data('leistung');

                                var lstgBeschreibung = row.find(".Beschreibung");
                                var lstgBezeichnung = row.find(".Bezeichnung");
                                var lstgProduktLinkBox = row.find(".produkt_link");
                                var lstgProduktLink = row.find(".produkt_link a");
                                var lstgImageHref = row.find(".bild a");
                                var lstgImage = row.find(".bild img");
                                var tdChck = row.find(".chck");

                                lstgBezeichnung.html( lstgData.leistung );
                                lstgBeschreibung.html( lstgData.Beschreibung );
                                if (lstgData.produkt_link) {
                                    lstgProduktLink.attr("href", lstgData.produkt_link);
                                    lstgProduktLinkBox.show();
                                    lstgImageHref.attr("href", lstgData.produkt_link);
                                } else {
                                    lstgProduktLinkBox.hide();
                                    lstgProduktLink.removeAttr("href");
                                    lstgImageHref.removeAttr("href");
                                }

                                if (lstgData.image) {
                                    var img = new Image();
                                    $(img).on("load", function() {
                                        lstgImage.attr("src", this.src).show();
                                    });
                                    img.src = "images/leistungskatalog/" + lstgData.image;
                                } else {
                                    lstgImage.hide();
                                }

                                tdChck.find("input[name=" + escapeSelector('L[leistung_id][]') + "]").val( optVal );

                            });
                        });
                        tdLstg.append(inputSelect);

                        if (selectedLeistungId && selectedLeistungId != it.leistung_id) {
                            inputSelect.trigger("change");
                        }
                    }


                    console.log("#712", {it});
                    if ("verfuegbar" in it && it.verfuegbar === "Nein") {
                        box.append(tr);
                        continue;
                        // labelChck.off("click");
                        // inputChck.off("click").prop('disabled', true);
                        // tdChck.hide();
                    }

                    var labelChck = $("<label/>").addClass("container");
                    var inputChck = $("<input/>").attr({
                        name: "check[" + it.kategorie_id + "]",
                        type: "radio",
                        value: it.leistung_id
                    });

                    var spanChck = $("<span/>").addClass("checkmark");
                    labelChck.append(inputChck).append(spanChck);
                    labelChck.off("click").on("click", function (e) {
                        console.log('click label', {e});
                        if (!("srcElement" in e) || e.srcElement.nodeName !== 'SPAN') {
                            var tr = $(this).closest(".row");
                            var chckCell = tr.find("div.chck");
                            var chckRadio = chckCell.find("label input[type=radio]");
                            console.log('checkRadio ', chckRadio.attr("name"), chckRadio.prop("checked"));
                            var ktg = tr.attr("data-ktg");
                            var ktgId = tr.attr("data-ktgId");
                            if (tr.is(".checked")) {
                                chckRadio.prop("checked", false);
                            }
                            if (chckRadio.prop("checked")) {
                                toggleBundleauswahl(ktg, ktgId, ktgBundleName, ktgBundleId);
                                var checkedRows = boxLeistungen.find(".row .chck label input:checked");
                                var maxCheckedPositionen = 9999;
                                if (checkedRows.length > maxCheckedPositionen) {
                                    var isStuhlChecked = checkedRows.find("[data-ktg=Stuhl],[data-ktgId=21]").length > 0;
                                    var isTischChecked = checkedRows.find("[data-ktg=Tisch],[data-ktg=22]").length > 0;
                                    var isLampeChecked = checkedRows.find("[data-ktg=Schreibtischlampe],[data-ktg=23]").length > 0;
                                    console.log("Auswahlkontrolle ", {isStuhlChecked, isTischChecked, isLampeChecked});

                                    alert("Es können maximal " + mmaxCheckedPositionen + " Einzelpositionen ausgewählt werden oder ein Komplettpaket!");
                                    chckRadio.prop("checked", false);
                                }
                            }
                            var checked = chckRadio.prop("checked");

                            var selector = ".row.checked.ktg-" + ktgId;
                            console.log({tr, ktg, ktgId, checked, selector});
                            $(".row.ktg-" + ktgId).removeClass("checked");
                            $(".row.ktg-" + ktgId).find("input.lstg-input").prop("disabled", true);

                            if (checked) {
                                tr.addClass("checked");
                                chckCell.find("input.lstg-input").prop("disabled", false);
                                chckCell.find("input.lstg-input.menge").val(1);
                            } else {
                                chckCell.find("input.lstg-input").prop("disabled", true);
                                chckCell.find("input.lstg-input.menge").val(0);
                            }
                        } else {
                            console.log("No Reaction to the click :-(");
                        }
                    });
                    tdChck.append(labelChck);
                    var _ldata = {leistung_id: it.leistung_id};
                    var _lclss = ' group-leistungid-' + it.leistung_id;

                    var leistung_id = it.leistung_id;
                    if (numVarianten) {
                        leistung_id = selectedLeistungId;
                    }

                    tdChck
                        .append(
                            createHiddenLstgInput('L[leistung_id][]', leistung_id, true, 'leistung_id' + _lclss, _ldata)
                        )
                        .append(
                            createHiddenLstgInput('L[menge_property][]', 0, true, 'menge menge_property' + _lclss, _ldata)
                        )
                        .append(
                            createHiddenLstgInput('L[menge2_property][]', 0, true, 'menge menge2_property' + _lclss, _ldata)
                        )
                        .append(
                            createHiddenLstgInput('L[menge_mertens][]', 0, true, 'menge menge_mertens' + _lclss, _ldata)
                        )
                        .append(
                            createHiddenLstgInput('L[menge2_mertens][]', 0, true, 'menge menge2_mertens' + _lclss, _ldata)
                        );



                    box.append(tr);
                }


                if (isSelected || (numVarianten && selectedLeistungId)) {
                    inputChck.prop('checked', true);
                    tr.addClass("checked");
                    tdChck.find("input.lstg-input").prop("disabled", false);
                    tdChck.find("input.lstg-input.menge").val(1);
                    // labelChck.trigger("click");
                }
            }
            box.appendTo(boxLeistungen);
        }
    };

    if (!umzugsleistungen || (antragsstatus === 'bearbeitung' && umzugsstatus === 'temp') || umzugsstatus === 'zurueckgegeben') {
        if (0) {
            alert("renderAuswahlliste() " +
                "\n umzugsstatus: " + umzugsstatus +
                "\n antragsstatus: " + antragsstatus +
                "\n umzugsleistungen:\n" + JSON.stringify(umzugsleistungen));
        }
        renderAuswahlListe();
    } else if (umzugsstatus !== 'abgeschlossen') {
        alert("renderBestellListe() " +
            "\n umzugsstatus: "  + umzugsstatus  +
            "\n antragsstatus: " + antragsstatus +
            "\n umzugsleistungen:\n" + JSON.stringify(umzugsleistungen));
        renderBestellListe();
    } else {
        // Umzugsstatus: abgeschlossen
        // @ToDo Mit Option Reklamation / Umtausch für gelieferte Artikel anzufordern
        renderBestellListe();
    }

<?php echo '</script'; ?>
>


<input type="hidden" name="AS[leistungen_csv]" value="">
<?php }
}
