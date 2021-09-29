<?php
/* Smarty version 3.1.34-dev-7, created on 2021-09-29 20:53:05
  from '/var/www/html/html/umzugsformular_leistungsauswahl.tpl.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_6154d231985549_16260918',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '346d952b773372d484fde33cfdf99fd837ff0bb3' => 
    array (
      0 => '/var/www/html/html/umzugsformular_leistungsauswahl.tpl.html',
      1 => 1632948680,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6154d231985549_16260918 (Smarty_Internal_Template $_smarty_tpl) {
?><div style="display:block;margin-top:15px;">
    <!-- umzugsformular_leistungsauswahl.tpl.html -->
</div>
<style>

div.row {
    width: 100%;
    display: flex;
    border: 2px solid #0075b5;
    border-radius: 8px;
    flex-wrap: nowrap;
    margin-bottom: 1rem;
    background-color: #f1f8ff;
}
div.row.checked {
    border: 2px solid green;
    background-color: #F2FAF3;
}
div.row div.bild {
    flex-grow: 0;
    width:160px;
}
div.row div.bild img {
    max-width: 350px;
    max-height: 150px;
    border: 1px solid transparent;
    border-top-left-radius: 8px;
    border-bottom-left-radius: 8px;
}
div.row div.lstg {
    flex: 1 1 auto;
    align-self: center;
    font-weight: bold;
    font-size: 1rem;
}
div.row div.chck {
    flex: 0 0 auto;
    align-self: center;
}
    /* The container */
label.container {
    border:0;
    background:none;
    display: block;
    position: relative;
    margin-bottom: 12px;
    width: initial;
    min-height:25px;
    min-width:25px;
    cursor: pointer;
    font-size: 22px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

div {
    min-height: 25px;
}

/* Hide the browser's default checkbox */
label.container input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}

/* Create a custom checkbox */
label.container .checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 25px;
    width: 25px;
    background-color: #eee;
}

/* On mouse-over, add a grey background color */
label.container .container:hover input ~ .checkmark {
    background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
label.container  input:checked ~ .checkmark {
    background-color: green;
}

/* Create the checkmark/indicator (hidden when not checked) */
label.container .checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

/* Show the checkmark when checked */
label.container  input:checked ~ .checkmark:after {
    display: block;
}

/* Style the checkmark/indicator */
label.container  .checkmark:after {
    left: 9px;
    top: 5px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 3px 3px 0;
    -webkit-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
}

</style>
<?php echo '<script'; ?>
>
var lkItems = <?php echo $_smarty_tpl->tpl_vars['lkTreeItemsJson']->value;?>
;
var lkmById = <?php echo $_smarty_tpl->tpl_vars['lkmByIdJson']->value;?>
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

    var boxLeistungenSel = "div#auswahlUmzugsleistungenBox";
    var boxLeistungen = $(boxLeistungenSel);
    if (!boxLeistungen.length) {
        alert("NOT FOUND " + boxLeistungenSel);
    }
    var lkItemsByKtg = lkItems;
    for (var ktg in lkItemsByKtg) {

        if (!lkItemsByKtg.hasOwnProperty(ktg)) {
            continue;
        }
        console.log({ktg});
        var box = $("<div/>");
        var boxTitle = $("<h3/>").text("Auswahl: " + ktg);
        boxTitle.appendTo(box);
        var ktgItems = Object.values(lkItemsByKtg[ktg]);
        for (var it of ktgItems) {
            console.log({it});
            var tr = $("<div/>").addClass("row ktg-"+ktg).attr("data-ktg", ktg);
            var tdBild = $("<div/>").addClass('bild').appendTo(tr);
            var tdLstg = $("<div/>").addClass('lstg').appendTo(tr);
            var tdChck = $("<div/>").addClass('chck').appendTo(tr);

            if (it.image) {
                var bildHref = $("<a/>").attr("href", "images/leistungskatalog/" + it.image).css({
                    border: 0
                });
                bildHref.appendTo(tdBild);
                var bild = $("<img/>")
                    .attr({
                        src: "images/leistungskatalog/" + it.image
                    })
                    .appendTo(bildHref);
            }

            tdLstg.text(it.leistung).off("click").on("click", function() {
                var tr = $(this).closest(".row");
                tr.find(".chck label")[0].click();
                console.log('click leistung');
            });

            var labelChck = $("<label/>").addClass("container");
            var inputChck = $("<input/>").attr({
                name: "check[" + it.kategorie + "]",
                type: "radio",
                value: it.leistung_id
            });

            var spanChck = $("<span/>").addClass("checkmark");
            labelChck.append(inputChck).append(spanChck);
            labelChck.off("click").on("click", function(e) {
                console.log('click label', { e });
                if (!("srcElement" in e) || e.srcElement.nodeName !== 'SPAN') {
                    var tr = $(this).closest(".row");
                    var chckCell = tr.find("div.chck");
                    var ktg = tr.attr("data-ktg");
                    if (tr.is(".checked")) {
                        $("input", this).prop("checked", false);
                    }
                    var checked = $("input", this).prop("checked");
                    var selector = ".row.checked.ktg-" + ktg;
                    console.log({tr, ktg, checked, selector });
                    $(".row.checked.ktg-" + ktg).removeClass("checked");
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
            tdChck
                .append(
                    $("<input/>").attr({
                        name: "L[leistung_id][]",
                        value: it.leistung_id,
                        type: "hidden",
                        disabled: "disabled"
                    })
                        .prop("disabled", true)
                        .addClass("lstg-input leistung_id")
                )
                .append(
                    $("<input/>").attr({
                        name: "L[menge_property][]",
                        value: 0,
                        type: "hidden",
                        disabled: "disabled"
                    })
                        .prop("disabled", true)
                        .addClass("lstg-input menge menge_property")
                )
                .append(
                    $("<input/>").attr({
                        name: "L[menge2_property][]",
                        value: 0,
                        type: "hidden",
                        disabled: "disabled"
                    })
                        .prop("disabled", true)
                        .addClass("lstg-input menge menge2_property")
                )
                .append(
                    $("<input/>").attr({
                        name: "L[menge_mertens][]",
                        value: 0,
                        type: "hidden",
                        disabled: "disabled"
                    })
                        .prop("disabled", true)
                        .addClass("lstg-input menge menge_mertens")
                )
                .append(
                    $("<input/>").attr({
                        name: "L[menge2_mertens][]",
                        value: 0,
                        type: "hidden",
                        disabled: "disabled"
                    })
                        .prop("disabled", true)
                        .addClass("lstg-input menge menge2_mertens")
                );

            box.append( tr );
        }
        box.appendTo(boxLeistungen);
    }

<?php echo '</script'; ?>
>


<input type="hidden" name="AS[leistungen_csv]" value="">
<?php }
}
