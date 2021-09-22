<?php
/* Smarty version 3.1.34-dev-7, created on 2021-09-22 08:31:33
  from '/var/www/html/html/umzugsformular_leistungsauswahl.tpl.read.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_614ae9e5cfb318_70251081',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '010060dc1ab07dd5e886b7e46b9585982e9eb53e' => 
    array (
      0 => '/var/www/html/html/umzugsformular_leistungsauswahl.tpl.read.html',
      1 => 1631631864,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_614ae9e5cfb318_70251081 (Smarty_Internal_Template $_smarty_tpl) {
?><style>

tr.row * {
    font-size:11px;
}
div.ktg1, div.lstg {
    min-height: 18px;
}
tr#summary td { 
    text-align:right;
    padding:5px;
    font-weight:bold;
}
td.unit {
    text-align:center;
}
td.menge, td.menge, input.menge, td.preis, td.sum {
    text-align:right;
}

</style>
<?php if (count($_smarty_tpl->tpl_vars['Umzugsleistungen']->value)) {?>
<h2 style="margin:0;padding:0">Leistungen</h2>
<table class="MitarbeierItem" style="margin-top:0;padding-top:0;width:100%;">
    <thead>
        <tr>
            <td>Kategorie</td>
            <td>Leistung</td>
            <td>Menge 1 DSD</td>
            <td>Einheit 1</td>
            <td>Menge 2 DSD</td>
            <td>Einheit 2</td>
            <td>Menge1 M</td>
            <td>Menge2 M</td>
            <?php if ($_smarty_tpl->tpl_vars['PreiseAnzeigen']->value) {?><td class="preis">Preis/Einh.</td>
            <td class="sum">Gesamt</td>
            <?php }?>
        </tr>
    </thead>
    <tbody id="TblLeistungenBody">
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['Umzugsleistungen']->value, 'L', false, NULL, 'GList', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['L']->value) {
?>
        <tr class="row inputRowVon">
            <td class="ktg1"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['kategorie'], ENT_QUOTES, 'ISO-8859-1', true);?>
</td>
            <td class="lstg"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['leistung'], ENT_QUOTES, 'ISO-8859-1', true);?>
</td>
            <td><?php if (is_numeric($_smarty_tpl->tpl_vars['L']->value['menge_property'])) {
echo number_format(htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['menge_property'], ENT_QUOTES, 'ISO-8859-1', true),2,",",".");
}?></td>
            <td class="unit"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['leistungseinheit'], ENT_QUOTES, 'ISO-8859-1', true);?>
</td>
            <td><?php if (is_numeric($_smarty_tpl->tpl_vars['L']->value['menge2_property'])) {
echo number_format(htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['menge2_property'], ENT_QUOTES, 'ISO-8859-1', true),2,",",".");
}?></td>
            <td class="unit"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['leistungseinheit2'], ENT_QUOTES, 'ISO-8859-1', true);?>
</td>
            <td class="menge"><?php if (is_numeric($_smarty_tpl->tpl_vars['L']->value['menge_mertens'])) {
echo number_format(htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['menge_mertens'], ENT_QUOTES, 'ISO-8859-1', true),2,",",".");
}?></td>
            <td class="menge"><?php if (is_numeric($_smarty_tpl->tpl_vars['L']->value['menge2_mertens'])) {
echo number_format(htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['menge2_mertens'], ENT_QUOTES, 'ISO-8859-1', true),2,",",".");
}?></td>
            <?php if ($_smarty_tpl->tpl_vars['PreiseAnzeigen']->value) {?><td class="preis"><?php echo $_smarty_tpl->tpl_vars['L']->value['preis_pro_einheit'];?>
</td>
            <td class="sum"><?php if (is_numeric($_smarty_tpl->tpl_vars['L']->value['gesamtpreis'])) {
echo number_format(htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['gesamtpreis'], ENT_QUOTES, 'ISO-8859-1', true),2,",",".");
}?></td>
            <?php }?>
        </tr>
    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        <tr id="summary">
        <?php if ($_smarty_tpl->tpl_vars['PreiseAnzeigen']->value) {?>
            <td colspan="<?php if ($_smarty_tpl->tpl_vars['PreiseAnzeigen']->value) {?>11<?php } else { ?>9<?php }?>"><span id="allsum" data-allsum="0"><?php echo number_format(htmlspecialchars($_smarty_tpl->tpl_vars['Gesamtsumme']->value, ENT_QUOTES, 'ISO-8859-1', true),2,",",".");?>
</span><span style="margin-left:5px">&euro;</span></td>
        <?php }?>
        </tr>
    </tbody>
</table>
<?php } else { ?>
<strong>Leistungen:</strong> <em>Keine </em> <br>
<?php }?>
<br>
<?php }
}
