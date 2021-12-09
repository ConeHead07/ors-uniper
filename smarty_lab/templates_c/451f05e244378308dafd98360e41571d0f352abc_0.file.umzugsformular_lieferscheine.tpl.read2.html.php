<?php
/* Smarty version 3.1.34-dev-7, created on 2021-12-08 15:20:31
  from '/var/www/html/html/umzugsformular_lieferscheine.tpl.read2.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_61b0bf2f1ba521_28835443',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '451f05e244378308dafd98360e41571d0f352abc' => 
    array (
      0 => '/var/www/html/html/umzugsformular_lieferscheine.tpl.read2.html',
      1 => 1638973223,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_61b0bf2f1ba521_28835443 (Smarty_Internal_Template $_smarty_tpl) {
if (empty($_smarty_tpl->tpl_vars['noOuterBox']->value)) {
if (empty($_smarty_tpl->tpl_vars['noCss']->value)) {?><link rel="stylesheet" type="text/css" href="<?php if (!empty($_smarty_tpl->tpl_vars['WebRoot']->value)) {
echo $_smarty_tpl->tpl_vars['WebRoot']->value;
}?>css/umzugsformular_attachements.css" /><?php }?>
<!--
              lid,
              aid,
              leistungen,
              lieferdatum,
              ankunft,
              abfahrt,
              LENGTH(IFNULL(lieferschein, "")) AS PdfSize,
              source,
              umzuege_anlagen_dokid,
              sig_mt_size,
              sig_kd_size,
              sig_kd_unterzeichner,
              created_uid,
              created_user,
              created_at,
              modified_uid,
              modified_user,
              modified_at
            FROM mm_lieferscheine

            datei_link"] = $MConf['WebRoot'] . 'sites/lieferschein.php?idx=' . $_lsidx;
        $aRows[$i]["datei_groesse
-->
<fieldset><legend><strong>Lieferscheine</strong></legend>
	<div class="lieferscheine_list" style="padding:5px;">
	<?php }?>

		<table class="tblList" width="100%">
			<thead>
				<tr>
					<th class="col fLid">LS-ID</th>
					<th class="col ftermin">Liefertermin</th>
					<th class="col fexists">PDF</th>
					<th class="col fsize">Groesse</th>
					<th class="col funterzeichner">Unterzeichner</th>
					<th class="col fquelle">Quelle</th>
					<th class="col fdate">Datum</th>
					<th class="col fuser">Von</th>
				</tr>
			</thead>
			<tbody>
			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['UmzugsAnlagen']->value, 'AT', false, NULL, 'ATList', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['AT']->value) {
?>
				<tr>
					<td class="col fLid" title="<?php echo $_smarty_tpl->tpl_vars['AT']->value['lid'];?>
">
						<?php if ($_smarty_tpl->tpl_vars['AT']->value['PdfSize'] > 0) {?><a href="<?php echo $_smarty_tpl->tpl_vars['AT']->value['datei_link'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['AT']->value['lid'];?>
</a>
						<?php } else {
echo $_smarty_tpl->tpl_vars['AT']->value['lid'];
}?>
					</td>
					<td class="col ftermin"><?php echo $_smarty_tpl->tpl_vars['AT']->value['lieferdatum'];?>
</td>
					<td class="col fexists"><?php if ($_smarty_tpl->tpl_vars['AT']->value['PdfSize'] > 0) {?>Ja<?php } else { ?>Nein<?php }?></td>
					<td class="col fsize"><?php echo $_smarty_tpl->tpl_vars['AT']->value['datei_groesse'];?>
</td>
					<td class="col funterzeichner"><?php echo $_smarty_tpl->tpl_vars['AT']->value['sig_kd_unterzeichner'];?>
</td>
					<td class="col fquelle"><?php echo $_smarty_tpl->tpl_vars['AT']->value['source'];?>
</td>
					<td class="col fdate"><?php echo $_smarty_tpl->tpl_vars['AT']->value['created_at'];?>
</td>
					<td class="col fuser"><?php echo $_smarty_tpl->tpl_vars['AT']->value['created_user'];?>
</td>
				</tr>
			<?php
}
} else {
?>
			<tr><td colspan="8"><li class="none"><em>keine</em></li></td></tr>
			<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
			</tbody>
		</table>
	<?php if (empty($_smarty_tpl->tpl_vars['noOuterBox']->value)) {?>
	</div>
</fieldset>
<?php }
}
}
