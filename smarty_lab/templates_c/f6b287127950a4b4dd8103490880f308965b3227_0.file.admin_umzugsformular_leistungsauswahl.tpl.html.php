<?php
/* Smarty version 3.1.34-dev-7, created on 2022-01-19 14:18:48
  from '/var/www/html/html/admin_umzugsformular_leistungsauswahl.tpl.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_61e80fb8c325c4_18316269',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f6b287127950a4b4dd8103490880f308965b3227' => 
    array (
      0 => '/var/www/html/html/admin_umzugsformular_leistungsauswahl.tpl.html',
      1 => 1642598245,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_61e80fb8c325c4_18316269 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('colspan', 3);?>

<?php if (!isset($_smarty_tpl->tpl_vars['mengeReklasReadOnly']->value)) {
$_smarty_tpl->_assignInScope('mengeReklasReadOnly', 1);
}?>

<?php if ($_smarty_tpl->tpl_vars['PreiseAnzeigen']->value) {
$_smarty_tpl->_assignInScope('colspan', $_smarty_tpl->tpl_vars['colspan']->value+2);
}?>

<?php if (!empty($_smarty_tpl->tpl_vars['enableLeistungCheckbox']->value)) {
$_smarty_tpl->_assignInScope('colspan', $_smarty_tpl->tpl_vars['colspan']->value+1);
}?>

<?php if (!empty($_smarty_tpl->tpl_vars['addReklas']->value)) {
$_smarty_tpl->_assignInScope('colspan', $_smarty_tpl->tpl_vars['colspan']->value+1);
}
if (!empty($_smarty_tpl->tpl_vars['showReklas']->value)) {
$_smarty_tpl->_assignInScope('colspan', $_smarty_tpl->tpl_vars['colspan']->value+1);
}?>

<?php if (!empty($_smarty_tpl->tpl_vars['addTeilmengen']->value)) {
$_smarty_tpl->_assignInScope('colspan', $_smarty_tpl->tpl_vars['colspan']->value+1);
}
if (!empty($_smarty_tpl->tpl_vars['showLiefermenge']->value)) {
$_smarty_tpl->_assignInScope('colspan', $_smarty_tpl->tpl_vars['colspan']->value+1);
}?>
<div data-test="2" style="display:block;margin-top:15px;">
    <!-- admin_umzugsformular_leistungsauswahl.tpl.html -->
</div>

<style>

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
        if ( objInpM.length) objInpM.not(":").get(0).focus();
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

function drop_Leistung(obj) {
    $( obj ).closest( "tr" ).remove();
    leistungenRowsChanged = true;
    calc_AllSum();
    callbackLeistungenChanged();
}

$(function(){
    $( "input.menge.creator, input.menge2.creator" ).bind("change", mengeChangeCallback);
});

function add_Leistung() {
    if (!$("#TplLeistungTable tr.row").length ) return false;
    if (!$("#TblLeistungenBody").length) return false;

    var l = $("table#TplLeistungTable tr.row").clone(true);
    l.find("input:disabled").each(function(){
        this.disabled = false;
    }); //.prop("disabled", false);
    $("#TblLeistungenBody tr#summary").before(l);
    $("td input").change( callbackLeistungenChanged );
    $(".ktg1", l ).trigger("click");
}

var leistungenOnLoad = {};
var leistungenChangedFromP = true;
var leistungenChanged = true;
var leistungenRowsChanged = false;

var callbackLeistungenChanged = function() {
    var b = $("#btnStatGenJa");
    if (!b.attr("data-reCheckLabel")) return;
    b.val( getLeistungenChanged() ? b.attr("data-reCheckLabel") : b.attr("data-sendLabel") );
}

var getLeistungenChanged = function() {
    var changed = false;
    var leistungen = getLeistungenFormData();
    console.log("leistungen", leistungen);
    console.log("leistungenOnLoad", leistungenOnLoad);
    
    for(var j in leistungenOnLoad) {
        console.log("j:", j, "j in leistungen", (j in leistungen));
        if (!(j in leistungen)) return true;
    }
    
    for(var i in leistungen) {
        if (!(i in leistungenOnLoad) && (leistungen[i].pm1 > 0 || leistungen[i].pm2 > 0)) {
            return true;
        }
    }
    
    $.each(leistungen, function(i, val){
        console.log("val", val);
        if (
            parseFloat(val.pm1.replace(',','.')).toFixed(2) != parseFloat(val.mm1.replace(',','.')).toFixed(2) 
            || parseFloat(val.pm2.replace(',','.')).toFixed(2) != parseFloat(val.mm2.replace(',','.')).toFixed(2) ) {
            changed = true;
        }
    });
    return changed;
};

var getLeistungenFormData = function() {
        var leistungen = {};
        $("#TblLeistungenBody tr").each(function(){
           if ($("td input", this).length < 4) return;
           var ilstg = $("input.ilstg", this).val();
           leistungen[ilstg] = {
                 'pm1': $("td:eq(3) input.menge", this).val(),
                 'pm2': $("td:eq(5) input.menge", this).val(),
                 'mm1': $("td:eq(7) input.menge", this).val(),
                 'mm2': $("td:eq(8) input.menge", this).val()
           };
        });
        return leistungen;
   };

$(function(){
   leistungenOnLoad = getLeistungenFormData();
   $("#TblLeistungenBody tr input").change( callbackLeistungenChanged).eq(0).trigger("change");
});

<?php echo '</script'; ?>
>

<style>
    .table-leistungen .col.menge,
    .table-leistungen .col.field-menge_mertens,
    .table-leistungen .col.field-menge_rekla,
    .table-leistungen .col.field-menge_geliefert,
    .table-leistungen .col.field-add_rekla {
        max-width:7vh;
    }
    .table-leistungen thead th {
        font-size: 0.75rem;
    }
</style>

<h2 style="margin:0"><?php if (empty($_smarty_tpl->tpl_vars['title']->value)) {?>Bestellte Leistungen<?php } else {
echo $_smarty_tpl->tpl_vars['title']->value;
}?></h2>
<table class="table-leistungen MitarbeierItem" style="width:100%;">
    <thead>
        <tr>
                        <?php if (isset($_smarty_tpl->tpl_vars['enableLeistungCheckbox']->value) && !empty($_smarty_tpl->tpl_vars['enableLeistungCheckbox']->value)) {?>
            <th style="width:14px;padding:0;"> [-] </th>
            <?php }?>
            <th class="col field-kategorie">Kategorie</th>
            <th class="col field-leistung">Leistung</th>
            <th class="col field-menge_mertens menge">B-Menge</th>
            <?php if (!empty($_smarty_tpl->tpl_vars['showReklas']->value)) {?>
            <th class="col field-menge_rekla menge">R-Menge</th>
            <?php }?>
            <?php if (!empty($_smarty_tpl->tpl_vars['addReklas']->value)) {?>
            <th class="col field-add_rekla menge" style="background-color:red;color:#fff;">Neue Reklas</th>
            <?php }?>
            <?php if (!empty($_smarty_tpl->tpl_vars['showLiefermenge']->value)) {?>
            <th class="col field-menge_geliefert menge">Gelief</th>
            <?php }?>
            <?php if (!empty($_smarty_tpl->tpl_vars['addTeilmengen']->value)) {?>
            <th class="col field-add_teil menge" style="background-color:darkgreen;color:#fff;">T-Menge</th>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['PreiseAnzeigen']->value) {?><th class="col col-preis preis">Preis</th>
            <th class="col field-sum sum">Gesamt</th>
            <?php }?>
        </tr>
    </thead>
    <tbody id="TblLeistungenBody">
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['Umzugsleistungen']->value, 'L', false, NULL, 'LList', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['L']->value) {
?>
        <tr class="row inputRowVon" data-lid="<?php echo $_smarty_tpl->tpl_vars['L']->value['leistung_id'];?>
" data-row="<?php echo htmlspecialchars(json_encode($_smarty_tpl->tpl_vars['L']->value), ENT_QUOTES, 'UTF-8', true);?>
">
                        <?php if (isset($_smarty_tpl->tpl_vars['enableLeistungCheckbox']->value) && !empty($_smarty_tpl->tpl_vars['enableLeistungCheckbox']->value)) {?>
            <td>
                <input type="checkbox" name="chckLeistung[]" value="<?php echo $_smarty_tpl->tpl_vars['L']->value['leistung_id'];?>
">
            </td>
            <?php }?>
            <td class="col field-kategorie ktg1" ><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['kategorie'], ENT_QUOTES, 'UTF-8', true);?>
</td>
            <td class="col field-leistung lstg"  data-p="<?php echo $_smarty_tpl->tpl_vars['L']->value['preis_pro_einheit'];?>
" data-sum="<?php echo $_smarty_tpl->tpl_vars['L']->value['gesamtpreis'];?>
">
                <?php if (!empty($_smarty_tpl->tpl_vars['L']->value['image'])) {?>
                <div class="bild">
                    <img src="images/leistungskatalog/<?php echo $_smarty_tpl->tpl_vars['L']->value['image'];?>
" style="float:left;max-width:120px;max-height:120px;border:0;margin-right:1rem;margin-bottom:.5rem;">
                </div>
                <?php }?>
                <div class="Bezeichnung"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['leistung'], ENT_QUOTES, 'UTF-8', true);?>
</div>
                <?php if (!empty($_smarty_tpl->tpl_vars['L']->value['Beschreibung'])) {?>
                <div class="Beschreibung"><?php echo $_smarty_tpl->tpl_vars['L']->value['Beschreibung'];?>
</div>
                <?php }?>
                <?php if (!empty($_smarty_tpl->tpl_vars['L']->value['Farbe']) || !empty($_smarty_tpl->tpl_vars['L']->value['Groesse'])) {?>
                <div class="produkt_varianten">
                    <?php if (!empty($_smarty_tpl->tpl_vars['L']->value['Farbe'])) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['Farbe'], ENT_QUOTES, 'UTF-8', true);
}?>
                    <?php if (!empty($_smarty_tpl->tpl_vars['L']->value['Groesse'])) {
if (!empty($_smarty_tpl->tpl_vars['L']->value['Farbe'])) {?>, <?php }
echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['Groesse'], ENT_QUOTES, 'UTF-8', true);
}?>
                </div>
                <?php }?>
                <?php if (!empty($_smarty_tpl->tpl_vars['L']->value['produkt_link'])) {?>
                <div class="produkt_link">
                    <a href="<?php echo $_smarty_tpl->tpl_vars['L']->value['produkt_link'];?>
" target="_PL<?php echo $_smarty_tpl->tpl_vars['L']->value['leistung_id'];?>
">mehr Infos</a>
                </div>
                <?php }?>
            </td>

            <td class="col field-mertens_menge menge"><?php if (!empty($_smarty_tpl->tpl_vars['mengeMertensReadOnly']->value)) {?>
                <?php echo number_format($_smarty_tpl->tpl_vars['L']->value['menge_mertens'],2,",",".");
} else { ?>
                <input class="menge" name="L[menge_mertens][]" value="<?php echo number_format($_smarty_tpl->tpl_vars['L']->value['menge_mertens'],2,',','.');?>
" type="text">
            <?php }?><input class="ilstg" name="L[leistung_id][]" value="<?php echo $_smarty_tpl->tpl_vars['L']->value['leistung_id'];?>
" type="hidden"></td>

            <?php if (!empty($_smarty_tpl->tpl_vars['showReklas']->value)) {?>
            <td class="col field-menge_rekla menge"><?php if (!empty($_smarty_tpl->tpl_vars['mengeReklasReadOnly']->value)) {?>
                <?php echo number_format($_smarty_tpl->tpl_vars['L']->value['menge_rekla'],2,",",".");
} else { ?>
                <input class="menge" name="L[menge_rekla][<?php echo $_smarty_tpl->tpl_vars['L']->value['leistung_id'];?>
]" value="<?php echo number_format($_smarty_tpl->tpl_vars['L']->value['menge_rekla'],2,',','.');?>
" type="text">
            <?php }?>
            </td>
            <?php }?>

            <?php if (!empty($_smarty_tpl->tpl_vars['addReklas']->value)) {?>
            <td class="col field-add_rekla menge"><input style="color:red" class="menge" name="L[neue_rekla][<?php echo $_smarty_tpl->tpl_vars['L']->value['leistung_id'];?>
]" value="0" type="text"></td>
            <?php }?>

            <?php if (!empty($_smarty_tpl->tpl_vars['showLiefermenge']->value)) {?>
            <td class="col field-menge_geliefert menge"><?php if (!empty($_smarty_tpl->tpl_vars['mengeGeliefertReadOnly']->value)) {?>
                <?php echo number_format($_smarty_tpl->tpl_vars['L']->value['menge_geliefert'],2,",",".");
} else { ?>
                <input class="menge" name="L[menge_geliefert][<?php echo $_smarty_tpl->tpl_vars['L']->value['leistung_id'];?>
]" value="<?php echo number_format($_smarty_tpl->tpl_vars['L']->value['menge_geliefert'],2,',','.');?>
" type="text">
                <?php }?></td>
            <?php }?>

            <?php if (!empty($_smarty_tpl->tpl_vars['addTeilmengen']->value)) {?>
            <td class="col field-add_teil menge"><input style="color:darkgreen" class="menge" name="L[neue_teilmenge][<?php echo $_smarty_tpl->tpl_vars['L']->value['leistung_id'];?>
]" value="0" type="text"></td>
            <?php }?>

            <?php if ($_smarty_tpl->tpl_vars['PreiseAnzeigen']->value) {?>
            <td class="preis"><?php if ($_smarty_tpl->tpl_vars['L']->value['preis_pro_einheit']) {
echo number_format($_smarty_tpl->tpl_vars['L']->value['preis_pro_einheit'],2,",",".");?>
 €<?php }?></td>
            <td class="sum"><?php if (is_numeric($_smarty_tpl->tpl_vars['L']->value['gesamtpreis'])) {
echo number_format($_smarty_tpl->tpl_vars['L']->value['gesamtpreis'],2,",",".");?>
 €<?php }?></td>
            <?php }?>
        </tr>
    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        <tr id="summary">
    <?php if ($_smarty_tpl->tpl_vars['PreiseAnzeigen']->value) {?>
            <td colspan="<?php echo $_smarty_tpl->tpl_vars['colspan']->value;?>
"><span id="allsum" data-allsum="0"><?php echo number_format(htmlspecialchars($_smarty_tpl->tpl_vars['Gesamtsumme']->value, ENT_QUOTES, 'UTF-8', true),2,",",".");?>
</span><span style="margin-left:5px">&euro;</span></td>
    <?php }?>
        </tr>
    </tbody>
</table>

<table id="TplLeistungTable" style="display:none;">
        <tr class="row inputRowVon">
            <td style="padding:0;"><span onclick="drop_Leistung(this)" style="cursor:pointer;margin:0;padding:0;"><img style="cursor:pointer;margin:0;padding:0;" align="absmiddle" src="images/loeschen_off.png" width="14" alt=""><span></td>
            <td class="ktg1" onclick="get_Kategorie(this)"></td>
            <td class="lstg" onclick="get_Leistung(this)" data-p="" data-sum=""></td>
            <td><input class="ilstg" name="L[leistung_id][]" disabled="disabled" value="" type="hidden"><!--
             --><input
                    class="menge<?php if ($_smarty_tpl->tpl_vars['creator']->value == 'property') {?> creator editable<?php } else { ?> readonly<?php }?>"
                    name="L[menge_property][]"
                    <?php if ($_smarty_tpl->tpl_vars['creator']->value != "property") {?> readonly="readonly"<?php }?>
                    disabled="disabled"
                    value="1" type="number">
                <input type="hidden"name="L[menge2_property][]" value="1">
                <input type="hidden"name="L[menge_mertens][]" value="1">
                <input type="hidden"name="L[menge2_mertens][]" value="1">
            </td>
                        <td><input
                    class="menge<?php if ($_smarty_tpl->tpl_vars['creator']->value == "mertens") {?> creator editable<?php } else { ?> readonly<?php }?>"
                name="L[menge_mertens][]"
                <?php if ($_smarty_tpl->tpl_vars['creator']->value !== "mertens") {?> readonly="readonly"<?php }?>
                disabled="disabled" value=""
                type="number"></td>
                        <?php if ($_smarty_tpl->tpl_vars['PreiseAnzeigen']->value) {?>
            <td class="preis"></td>
            <td class="sum"></td>
            <?php }?>
        </tr>
</table>
<input type="hidden" name="AS[leistungen_csv]" value="">

<?php }
}
