<?php
/* Smarty version 3.1.34-dev-7, created on 2021-11-11 15:05:14
  from '/var/www/html/uniper/htdocs/html/admin_umzugsformular_leistungsauswahl.tpl.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_618d231a1ed606_04208818',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1124678b215138e6398e1c428344e0d257263f96' => 
    array (
      0 => '/var/www/html/uniper/htdocs/html/admin_umzugsformular_leistungsauswahl.tpl.html',
      1 => 1636639508,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_618d231a1ed606_04208818 (Smarty_Internal_Template $_smarty_tpl) {
?><div data-test="2" style="display:block;margin-top:15px;">
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
<table class="MitarbeierItem" style="width:100%;">
    <thead>
        <tr>
                        <td>Kategorie</td>
            <td>Leistung</td>
            <td>Menge</td>
            <?php if ($_smarty_tpl->tpl_vars['PreiseAnzeigen']->value) {?><td>Preis</td>
            <td class="sum">Gesamt</td>
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
        <tr class="row inputRowVon">
                        <td class="ktg1" onclick="get_Kategorie(this)"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['kategorie'], ENT_QUOTES, 'UTF-8', true);?>
</td>
            <td class="lstg" onclick="get_Leistung(this)" data-p="<?php echo $_smarty_tpl->tpl_vars['L']->value['preis_pro_einheit'];?>
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
                <?php if (!empty($_smarty_tpl->tpl_vars['L']->value['produkt_link'])) {?>
                <div class="produkt_link">
                    <a href="<?php echo $_smarty_tpl->tpl_vars['L']->value['produkt_link'];?>
" target="_PL<?php echo $_smarty_tpl->tpl_vars['L']->value['leistung_id'];?>
">mehr Infos</a>
                </div>
                <?php }?>
            </td>
            <td><input class="ilstg" name="L[leistung_id][]" value="<?php echo $_smarty_tpl->tpl_vars['L']->value['leistung_id'];?>
" type="hidden"><!-- 
             --><input class="menge name="L[menge_mertens][]" value="<?php echo number_format(htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['menge_property'], ENT_QUOTES, 'UTF-8', true),2,",",".");?>
" type="text">
            </td>
            <?php if ($_smarty_tpl->tpl_vars['PreiseAnzeigen']->value) {?>
            <td class="preis"><?php if ($_smarty_tpl->tpl_vars['L']->value['preis_pro_einheit']) {
echo number_format($_smarty_tpl->tpl_vars['L']->value['preis_pro_einheit'],2,",",".");
}?></td>
            <td class="sum"><?php if (is_numeric($_smarty_tpl->tpl_vars['L']->value['gesamtpreis'])) {
echo number_format(htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['gesamtpreis'], ENT_QUOTES, 'UTF-8', true),2,",",".");
}?></td>
            <?php }?>
        </tr>
    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        <tr id="summary">
    <?php if ($_smarty_tpl->tpl_vars['PreiseAnzeigen']->value) {?>
            <td colspan="<?php if ($_smarty_tpl->tpl_vars['PreiseAnzeigen']->value) {?>6<?php } else { ?>4<?php }?>"><span id="allsum" data-allsum="0"><?php echo number_format(htmlspecialchars($_smarty_tpl->tpl_vars['Gesamtsumme']->value, ENT_QUOTES, 'UTF-8', true),2,",",".");?>
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
