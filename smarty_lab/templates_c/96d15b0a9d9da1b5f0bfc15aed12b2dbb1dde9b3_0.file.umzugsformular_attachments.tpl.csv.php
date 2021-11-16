<?php
/* Smarty version 3.1.34-dev-7, created on 2021-11-09 11:40:34
  from '/var/www/html/html/umzugsformular_attachments.tpl.csv' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_618a5e326e5f78_93032108',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '96d15b0a9d9da1b5f0bfc15aed12b2dbb1dde9b3' => 
    array (
      0 => '/var/www/html/html/umzugsformular_attachments.tpl.csv',
      1 => 1631631864,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_618a5e326e5f78_93032108 (Smarty_Internal_Template $_smarty_tpl) {
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
