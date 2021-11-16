<?php
/* Smarty version 3.1.34-dev-7, created on 2021-11-09 11:40:34
  from '/var/www/html/html/umzugsformular_leistungsauswahl.tpl.csv' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_618a5e3261ed09_87887110',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b1ed38a0dab0d61ba9ff7844e6d51ef65852506b' => 
    array (
      0 => '/var/www/html/html/umzugsformular_leistungsauswahl.tpl.csv',
      1 => 1631631864,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_618a5e3261ed09_87887110 (Smarty_Internal_Template $_smarty_tpl) {
?>Kategorie;Leistung;Menge 1 DSD;Einheit 1;Menge 2 DSD;Einheit 2;Menge1 MH;Menge2 MH;Gesamt 
<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['Umzugsleistungen']->value, 'L', false, NULL, 'LList', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['L']->value) {
echo trim(htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['kategorie'], ENT_QUOTES, 'UTF-8', true));?>
;<?php echo trim(htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['leistung'], ENT_QUOTES, 'UTF-8', true));?>
;<?php if ($_smarty_tpl->tpl_vars['L']->value['menge_property']) {
echo number_format(trim(htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['menge_property'], ENT_QUOTES, 'UTF-8', true)),2,",",".");
}?>;<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['leistungseinheit'], ENT_QUOTES, 'UTF-8', true);?>
;<?php if ($_smarty_tpl->tpl_vars['L']->value['menge2_property']) {
echo number_format(htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['menge2_property'], ENT_QUOTES, 'UTF-8', true),2,",",".");
}?>;<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['leistungseinheit2'], ENT_QUOTES, 'UTF-8', true);?>
;<?php if ($_smarty_tpl->tpl_vars['L']->value['menge_mertens']) {
echo number_format(htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['menge_mertens'], ENT_QUOTES, 'UTF-8', true),2,",",".");
}?>;<?php if ($_smarty_tpl->tpl_vars['L']->value['menge2_mertens']) {
echo number_format(htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['menge2_mertens'], ENT_QUOTES, 'UTF-8', true),2,",",".");
}
if ($_smarty_tpl->tpl_vars['L']->value['gesamtpreis']) {
echo number_format(htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['gesamtpreis'], ENT_QUOTES, 'UTF-8', true),2,",",".");
}
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?> 
 <?php }
}
