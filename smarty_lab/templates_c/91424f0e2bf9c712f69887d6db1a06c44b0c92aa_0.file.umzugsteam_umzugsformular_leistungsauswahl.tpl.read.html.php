<?php
/* Smarty version 3.1.34-dev-7, created on 2021-11-03 20:33:57
  from '/var/www/html/html/umzugsteam_umzugsformular_leistungsauswahl.tpl.read.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_6182f235bb1ae0_66195516',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '91424f0e2bf9c712f69887d6db1a06c44b0c92aa' => 
    array (
      0 => '/var/www/html/html/umzugsteam_umzugsformular_leistungsauswahl.tpl.read.html',
      1 => 1635971630,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6182f235bb1ae0_66195516 (Smarty_Internal_Template $_smarty_tpl) {
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
.hideForUniper {
    display:none;
}
.MitarbeierItem.table-leistungen  .inputRowVon td,
.MitarbeierItem.table-leistungen  .inputRowZiel td,
.table-leistungen thead td,
.table-leistungen tbody td,
.table-leistungen td {
    padding-left:.5rem;
    padding-right: .5rem;
}

</style>
<?php if (!empty($_smarty_tpl->tpl_vars['Umzugsleistungen']->value) && count($_smarty_tpl->tpl_vars['Umzugsleistungen']->value)) {?>
<h2 style="margin:0;padding:0" data-src="umzugsformular_leistungsauswahl.tpl.read.html">Leistungen</h2>
<table class="MitarbeierItem table-leistungen" style="margin-top:0;padding-top:0;width:100%;">
    <thead>
        <tr>
            <td>Kategorie</td>
            <td>Leistung</td>
            <td>Menge<span class="hideForUniper"> 1 DSD</span></td>
            <td class="hideForUniper">Einheit 1</td>
            <td class="hideForUniper">Menge 2 DSD</td>
            <td class="hideForUniper">Einheit 2</td>
            <td class="hideForUniper">Menge1 M</td>
            <td class="hideForUniper">Menge2 M</td>
            <?php if ($_smarty_tpl->tpl_vars['umzugsstatus']->value == "abgeschlossen") {?>
            <td class="hideForUniper">Rekla</td>
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
        <?php if ($_smarty_tpl->tpl_vars['L']->value['kategorie_id'] == "18" || $_smarty_tpl->tpl_vars['L']->value['kategorie_id'] == "25") {?>
            <?php continue 1;?>
        <?php }?>
        <tr class="row inputRowVon">
            <td class="ktg1"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['kategorie'], ENT_QUOTES, 'UTF-8', true);?>
</td>
            <td class="lstg"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['leistung'], ENT_QUOTES, 'UTF-8', true);?>
</td>
            <td class="menge"><?php if (is_numeric($_smarty_tpl->tpl_vars['L']->value['menge_property'])) {
echo number_format(htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['menge_property'], ENT_QUOTES, 'UTF-8', true),0,",",".");
}?></td>
            <td class="unit hideForUniper"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['leistungseinheit'], ENT_QUOTES, 'UTF-8', true);?>
</td>
            <td class=" hideForUniper"><?php if (is_numeric($_smarty_tpl->tpl_vars['L']->value['menge2_property'])) {
echo number_format(htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['menge2_property'], ENT_QUOTES, 'UTF-8', true),2,",",".");
}?></td>
            <td class="unit hideForUniper"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['leistungseinheit2'], ENT_QUOTES, 'UTF-8', true);?>
</td>
            <td class="menge hideForUniper"><?php if (is_numeric($_smarty_tpl->tpl_vars['L']->value['menge_mertens'])) {
echo number_format(htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['menge_mertens'], ENT_QUOTES, 'UTF-8', true),2,",",".");
}?></td>
            <td class="menge hideForUniper"><?php if (is_numeric($_smarty_tpl->tpl_vars['L']->value['menge2_mertens'])) {
echo number_format(htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['menge2_mertens'], ENT_QUOTES, 'UTF-8', true),2,",",".");
}?></td>
            <?php if ($_smarty_tpl->tpl_vars['umzugsstatus']->value == "abgeschlossen") {?>
            <td class="hideForUniper">Rekla</td>
            <?php }?>
        </tr>
    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    </tbody>
</table>
<?php } else { ?>
<strong>Leistungen:</strong> <em>Keine </em>
<?php }
}
}
