<?php
/* Smarty version 3.1.34-dev-7, created on 2022-02-24 06:35:22
  from '/var/www/html/html/property_umzugsformular_leistungsauswahl.tpl.read.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_6217272abcd0d2_67790228',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '34669be3a935a4bc0effd30cb0bd95ea8ab6afe2' => 
    array (
      0 => '/var/www/html/html/property_umzugsformular_leistungsauswahl.tpl.read.html',
      1 => 1646312042,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6217272abcd0d2_67790228 (Smarty_Internal_Template $_smarty_tpl) {
?><style>

tr.row * {
    font-size:0.8rem;
}
.MitarbeierItem tr.row.inputRowVon td,
tr.row td {
    font-size:0.8rem;
    padding:.2rem .8rem;
}
div.ktg1, div.lstg {
    min-height: 18px;
}
tr#summary td { 
    text-align:right;
    font-weight:bold;
}
td.unit {
    text-align:center;
}
td.menge, td.menge, input.menge, td.preis, td.sum {
    text-align:right;
    padding-right:1rem;
}

</style>
<?php echo '<script'; ?>
>
var lkItems = <?php echo $_smarty_tpl->tpl_vars['lkTreeItemsJson']->value;?>
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


<?php echo '</script'; ?>
>
<!-- Removed MH in Leistungen  -->
<h2 style="margin:0;padding:0">Leistungen</h2>
<table class="MitarbeierItem property-leistungsauswahl" style="width:100%;">
    <thead>
        <tr>
            <td>Kategorie</td>
            <td>Leistung</td>
            <td>Menge</td>
            <?php if ($_smarty_tpl->tpl_vars['PreiseAnzeigen']->value) {?><td>Preis/Einh.</td>
            <td>Gesamt</td>
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
            <td class="ktg1"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['kategorie'], ENT_QUOTES, 'UTF-8', true);?>
</td>
            <td class="lstg">
                <?php echo htmlspecialchars(trim($_smarty_tpl->tpl_vars['L']->value['leistung']), ENT_QUOTES, 'UTF-8', true);
if (!empty($_smarty_tpl->tpl_vars['L']->value['Farbe']) || !empty($_smarty_tpl->tpl_vars['L']->value['Groesse'])) {?>,<?php }?>
                <?php if (!empty($_smarty_tpl->tpl_vars['L']->value['Farbe'])) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['Farbe'], ENT_QUOTES, 'UTF-8', true);
if (!empty($_smarty_tpl->tpl_vars['L']->value['Groesse'])) {?>,<?php }
}?>
                <?php if (!empty($_smarty_tpl->tpl_vars['L']->value['Groesse'])) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['Groesse'], ENT_QUOTES, 'UTF-8', true);
}?>
            </td>
            <td class="menge"><?php if (is_numeric($_smarty_tpl->tpl_vars['L']->value['menge_mertens'])) {
echo number_format(htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['menge_mertens'], ENT_QUOTES, 'UTF-8', true),2,",",".");
} else { ?>1<?php }?></td>
            <?php if ($_smarty_tpl->tpl_vars['PreiseAnzeigen']->value) {?><td class="preis"><?php if ($_smarty_tpl->tpl_vars['L']->value['preis_pro_einheit']) {
echo number_format($_smarty_tpl->tpl_vars['L']->value['preis_pro_einheit'],2,",",".");
}?></td>
            <td class="preis sum"><?php if (is_numeric($_smarty_tpl->tpl_vars['L']->value['gesamtpreis'])) {
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
        <td colspan="<?php if ($_smarty_tpl->tpl_vars['PreiseAnzeigen']->value) {?>6<?php } else { ?>4<?php }?>"><span id="xallsum" data-allsum="0"><?php echo number_format(htmlspecialchars($_smarty_tpl->tpl_vars['Gesamtsumme']->value, ENT_QUOTES, 'UTF-8', true),2,",",".");?>
</span><span style="margin-left:5px">&euro;</span></td>
    <?php }?>
    </tr>
    </tbody>
</table>
<input type="hidden" name="AS[leistungen_csv]" value="">
<?php }
}
