<?php /* Smarty version 2.6.26, created on 2015-12-11 09:16:20
         compiled from umzugsformular_leistungsauswahl.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'umzugsformular_leistungsauswahl.tpl.html', 238, false),array('modifier', 'number_format', 'umzugsformular_leistungsauswahl.tpl.html', 241, false),)), $this); ?>
<div style="display:block;margin-top:15px;">
<span style="margin-bottom:2px;color:#549e1a;font-weight:bold;text-decoration:none;cursor:pointer;" onclick="add_Leistung();return false;">
    Leistung hinzuf&uuml;gen <img align="absmiddle" src="images/hinzufuegen_off.png" width="14" alt=""></span>
</div>
<style>
<?php echo '

'; ?>

</style>
<script>
var lkItems = <?php echo $this->_tpl_vars['lkTreeItemsJson']; ?>
;
var lkmById = <?php echo $this->_tpl_vars['lkmByIdJson']; ?>
;

<?php echo '
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
   $("#lktgselect").html( $("<option/>").text("Bitte ausw?hlen") );
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
    $("#ldescselect").html( $("<option/>").text("Bitte ausw?hlen") );
    for(var lstg in leistungen) {
        $("#ldescselect").append( 
            $("<option/>").val(lstg).text(lstg).data("leistung", leistungen[lstg])
        );
    }
    $("#ldescselect").unbind("change").change(function(event){
        var data = $(this).find("option:selected").data("leistung");
        if ( lstgIsAllreadyInList(data.leistung_id, objInp) ) {
            alert("Die Leistung wurde bereits in die Liste aufgenommen!\\n" + 
                  "Bitte f?gen Sie die Mengen dem bestehenden Eintrag hinzu.\\n"+
                  "Andernfalls werden bestehende Mengen ?berschrieben."
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
    var val  = parseFloat(tr.find("input.menge.creator").val().replace(\',\', \'.\'));
    var val2 = parseFloat(tr.find("input.menge2.creator").val().replace(\',\', \'.\')) || 1;
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
'; ?>

</script>
<table class="MitarbeierItem" style="width:100%;">
    <thead>
        <tr>
            <td style="width:14px;padding:0;"> X </td>
            <td>Kategorie</td>
            <td>Leistung</td>
            <td>Menge 1 Prop</td>
            <td>Einheit 1</td>
            <td>Menge 2 Prop</td>
            <td>Einheit 2</td>
            <td>Menge1 M</td>
            <td>Menge2 M</td>
            <?php if ($this->_tpl_vars['PreiseAnzeigen']): ?><td>Preis/Einh.</td>
            <td class="sum">Gesamt</td>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody id="TblLeistungenBody">
    <?php $_from = $this->_tpl_vars['Umzugsleistungen']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['LList'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['LList']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['L']):
        $this->_foreach['LList']['iteration']++;
?>
        <tr class="row inputRowVon">
            <td style="padding:0;"><span onclick="drop_Leistung(this)" style="cursor:pointer;margin:0;padding:0;"><img style="cursor:pointer;margin:0;padding:0;" align="absmiddle" src="images/loeschen_off.png" width="14" alt=""><span></td>
            <td class="ktg1" onclick="get_Kategorie(this)"><?php echo ((is_array($_tmp=$this->_tpl_vars['L']['kategorie'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
            <td class="lstg" onclick="get_Leistung(this)" data-p="<?php echo $this->_tpl_vars['L']['preis_pro_einheit']; ?>
" data-sum="<?php echo $this->_tpl_vars['L']['gesamtpreis']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['L']['leistung'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php echo $this->_tpl_vars['creator']; ?>
</td>
            <td><input class="ilstg" name="L[leistung_id][]" value="<?php echo $this->_tpl_vars['L']['leistung_id']; ?>
" type="hidden"><!-- 
             --><input class="menge<?php if ($this->_tpl_vars['creator'] == 'property'): ?> creator editable<?php else: ?> readonly<?php endif; ?> <?php if ($this->_tpl_vars['creator'] !== 'property'): ?>readonly<?php else: ?>editable<?php endif; ?>" name="L[menge_property][]"<?php if ($this->_tpl_vars['creator'] !== 'property'): ?> readonly="readonly"<?php endif; ?> value="<?php if (is_numeric ( $this->_tpl_vars['L']['menge_property'] )): ?><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['L']['menge_property'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : number_format($_tmp, 2, ",", ".")); ?>
<?php endif; ?>" type="text"></td>
            <td class="unit"><?php echo ((is_array($_tmp=$this->_tpl_vars['L']['leistungseinheit'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
            <td><input class="menge menge2<?php if ($this->_tpl_vars['creator'] == 'property'): ?> creator editable<?php else: ?> readonly<?php endif; ?> <?php if ($this->_tpl_vars['creator'] !== 'property'): ?>readonly<?php else: ?>editable<?php endif; ?>" name="L[menge2_property][]"<?php if ($this->_tpl_vars['creator'] !== 'property'): ?> readonly="readonly"<?php endif; ?> value="<?php if (is_numeric ( $this->_tpl_vars['L']['menge2_property'] )): ?><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['L']['menge2_property'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : number_format($_tmp, 2, ",", ".")); ?>
<?php endif; ?>" type="text"></td>
            <td class="unit unit2"><?php echo ((is_array($_tmp=$this->_tpl_vars['L']['leistungseinheit2'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
            <td><input class="menge<?php if ($this->_tpl_vars['creator'] == 'mertens'): ?> creator editable<?php else: ?> readonly<?php endif; ?> <?php if ($this->_tpl_vars['creator'] !== 'mertens'): ?>readonly<?php else: ?>editable<?php endif; ?>" name="L[menge_mertens][]"<?php if ($this->_tpl_vars['creator'] !== 'mertens'): ?> readonly="readonly"<?php endif; ?> value="<?php if (is_numeric ( $this->_tpl_vars['L']['menge_mertens'] )): ?><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['L']['menge_mertens'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : number_format($_tmp, 2, ",", ".")); ?>
<?php endif; ?>" type="text"></td>
            <td><input class="menge menge2<?php if ($this->_tpl_vars['creator'] == 'mertens'): ?> creator editable<?php else: ?> readonly<?php endif; ?> <?php if ($this->_tpl_vars['creator'] !== 'mertens'): ?>readonly<?php else: ?>editable<?php endif; ?>" name="L[menge2_mertens][]"<?php if ($this->_tpl_vars['creator'] !== 'mertens'): ?> readonly="readonly"<?php endif; ?> value="<?php if (is_numeric ( $this->_tpl_vars['L']['menge2_mertens'] )): ?><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['L']['menge2_mertens'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : number_format($_tmp, 2, ",", ".")); ?>
<?php endif; ?>" type="text"></td>
            <?php if ($this->_tpl_vars['PreiseAnzeigen']): ?><td class="preis"><?php if ($this->_tpl_vars['L']['preis_pro_einheit']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['L']['preis_pro_einheit'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : number_format($_tmp, 2, ",", ".")); ?>
<?php endif; ?></td>
            <td class="sum"><?php if ($this->_tpl_vars['L']['gesamtpreis']): ?><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['L']['gesamtpreis'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : number_format($_tmp, 2, ",", ".")); ?>
<?php endif; ?></td>
            <?php endif; ?>
        </tr>
    <?php endforeach; endif; unset($_from); ?>
        <tr id="summary">
    <?php if ($this->_tpl_vars['PreiseAnzeigen']): ?>
            <td colspan="<?php if ($this->_tpl_vars['PreiseAnzeigen']): ?>11<?php else: ?>9<?php endif; ?>"><span id="allsum" data-allsum="0"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['Gesamtsumme'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : number_format($_tmp, 2, ",", ".")); ?>
</span><span style="margin-left:5px">&euro;</span></td>
    <?php endif; ?>
        </tr>
    </tbody>
</table>
<table id="TplLeistungTable" style="display:none;">
        <tr class="row inputRowVon">
            <td style="padding:0;"><span onclick="drop_Leistung(this)" style="cursor:pointer;margin:0;padding:0;"><img style="cursor:pointer;margin:0;padding:0;" align="absmiddle" src="images/loeschen_off.png" width="14" alt=""><span></td>
            <td class="ktg1" onclick="get_Kategorie(this)"></td>
            <td class="lstg" onclick="get_Leistung(this)" data-p="" data-sum="0"></td>
            <td><input class="ilstg" name="L[leistung_id][]" disabled="disabled" value="" type="hidden"><!--
             --><input class="menge<?php if ($this->_tpl_vars['creator'] == 'property'): ?> creator editable<?php else: ?> readonly<?php endif; ?>" name="L[menge_property][]"<?php if ($this->_tpl_vars['creator'] !== 'property'): ?> readonly="readonly"<?php endif; ?> disabled="disabled" value="" type="text"></td>
            <td class="unit"></td>
            <td><input class="menge menge2<?php if ($this->_tpl_vars['creator'] == 'property'): ?> creator editable<?php else: ?> readonly<?php endif; ?>" name="L[menge2_property][]"<?php if ($this->_tpl_vars['creator'] !== 'property'): ?> readonly="readonly"<?php endif; ?> disabled="disabled" value="" type="text"></td>
            <td class="unit unit2"></td>
            <td><input class="menge<?php if ($this->_tpl_vars['creator'] == 'mertens'): ?> creator editable<?php else: ?> readonly<?php endif; ?>" name="L[menge_mertens][]"<?php if ($this->_tpl_vars['creator'] !== 'mertens'): ?> readonly="readonly"<?php endif; ?> disabled="disabled" value="" type="text"></td>
            <td><input class="menge menge2<?php if ($this->_tpl_vars['creator'] == 'mertens'): ?> creator editable<?php else: ?> readonly<?php endif; ?>" name="L[menge2_mertens][]"<?php if ($this->_tpl_vars['creator'] !== 'mertens'): ?> readonly="readonly"<?php endif; ?> disabled="disabled" value="" type="text"></td>
            <?php if ($this->_tpl_vars['PreiseAnzeigen']): ?><td class="preis"></td>
            <td class="sum"></td>
            <?php endif; ?>
        </tr>
</table>
<input type="hidden" name="AS[leistungen_csv]" value="">