<?php
/* Smarty version 3.1.34-dev-7, created on 2021-10-29 11:26:30
  from '/var/www/html/uniper/htdocs/html/umzugsformular_attachments.tpl.csv' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_617bbe46b8c783_16650406',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7ad8202dc0632d34a18dd208147e3b57472face5' => 
    array (
      0 => '/var/www/html/uniper/htdocs/html/umzugsformular_attachments.tpl.csv',
      1 => 1632950133,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_617bbe46b8c783_16650406 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['UmzugsAnlagen']->value) {?>
Datei;Groesse;Upload vom
<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['UmzugsAnlagen']->value, 'AT', false, NULL, 'ATList', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['AT']->value) {
if ($_smarty_tpl->tpl_vars['AT']->value['titel']) {
echo $_smarty_tpl->tpl_vars['AT']->value['titel'];
} else {
echo $_smarty_tpl->tpl_vars['AT']->value['dok_datei'];
}?>;<?php echo $_smarty_tpl->tpl_vars['AT']->value['datei_groesse'];?>
;<?php echo $_smarty_tpl->tpl_vars['AT']->value['created'];?>

<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}
}
}
