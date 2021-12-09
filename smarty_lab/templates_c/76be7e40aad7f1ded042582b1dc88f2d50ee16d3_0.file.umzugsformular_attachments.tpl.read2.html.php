<?php
/* Smarty version 3.1.34-dev-7, created on 2021-12-08 13:11:42
  from '/var/www/html/html/umzugsformular_attachments.tpl.read2.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_61b0a0fe5b3cd8_80562400',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '76be7e40aad7f1ded042582b1dc88f2d50ee16d3' => 
    array (
      0 => '/var/www/html/html/umzugsformular_attachments.tpl.read2.html',
      1 => 1638965486,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_61b0a0fe5b3cd8_80562400 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/smarty3/plugins/modifier.truncate.php','function'=>'smarty_modifier_truncate',),));
if (empty($_smarty_tpl->tpl_vars['noOuterBox']->value)) {
if (empty($_smarty_tpl->tpl_vars['noCss']->value)) {?><link rel="stylesheet" type="text/css" href="<?php if (!empty($_smarty_tpl->tpl_vars['WebRoot']->value)) {
echo $_smarty_tpl->tpl_vars['WebRoot']->value;
}?>css/umzugsformular_attachements.css" /><?php }?>

<fieldset><legend><strong><?php if (!empty($_smarty_tpl->tpl_vars['internal']->value)) {?>Interne <?php }?>Dateianhänge</strong></legend>
	<div class="attachements_list" id="attachments<?php if (!empty($_smarty_tpl->tpl_vars['internal']->value)) {?>_internal<?php }?>_list" data-url="sites/umzugsantrag_attachements_list.php?aid=<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
&internal=<?php echo $_smarty_tpl->tpl_vars['internal']->value;?>
" style="padding:5px;">
	<?php }?>
		<div class="row names<?php if (count($_smarty_tpl->tpl_vars['UmzugsAnlagen']->value) == 0) {?> hidden<?php }?>">
			<span class="col fname">Datei</span>
			<span class="col fsize">Gr&ouml;&szlig;e</span>
			<span class="col fdate">Upload vom</span>
			<span class="col ftarget">Target</span>
		</div>

		<ul class="ulAttachements row-values">

		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['UmzugsAnlagen']->value, 'AT', false, NULL, 'ATList', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['AT']->value) {
?>

			<li class="row values">
			 <span class="col fname" title="<?php echo $_smarty_tpl->tpl_vars['AT']->value['titel'];?>
"><a href="<?php echo $_smarty_tpl->tpl_vars['AT']->value['datei_link'];?>
" target="_blank"><?php if ($_smarty_tpl->tpl_vars['AT']->value['titel']) {
echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['AT']->value['titel'],60,"...");
} else {
echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['AT']->value['dok_datei'],60,"...");
}?></a></span> 
			 <span class="col fsize"><?php echo $_smarty_tpl->tpl_vars['AT']->value['datei_groesse'];?>
</span>
				<span class="col fdate"><?php echo $_smarty_tpl->tpl_vars['AT']->value['created'];?>
</span>
				<span class="col ftarget"><?php if (!empty($_smarty_tpl->tpl_vars['AT']->value['target'])) {
echo $_smarty_tpl->tpl_vars['AT']->value['target'];
}?></span>
			</li>
		<?php
}
} else {
?>
			<li class="none"><em>keine</em></li>
		<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
		</ul>
	<?php if (empty($_smarty_tpl->tpl_vars['noOuterBox']->value)) {?>
	</div>
</fieldset>
<?php }
}
}
