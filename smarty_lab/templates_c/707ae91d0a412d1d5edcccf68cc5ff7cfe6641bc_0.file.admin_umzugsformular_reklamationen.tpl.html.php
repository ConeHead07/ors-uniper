<?php
/* Smarty version 3.1.34-dev-7, created on 2022-01-13 11:26:09
  from '/var/www/html/html/admin_umzugsformular_reklamationen.tpl.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_61dffe41861c57_35043842',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '707ae91d0a412d1d5edcccf68cc5ff7cfe6641bc' => 
    array (
      0 => '/var/www/html/html/admin_umzugsformular_reklamationen.tpl.html',
      1 => 1642069538,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_61dffe41861c57_35043842 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/smarty3/plugins/modifier.replace.php','function'=>'smarty_modifier_replace',),));
?>

<table class="tblList table-reklamationen" style="width:100%;">
    <thead>
        <tr>
            <td class="col field-aid">ID</td>
            <td class="col field-antragsdatum">ReklaDatum</td>
            <td class="col field-LeistungenKtg">Kateg</td>
            <td class="col field-LeistungenBez">Artikel</td>
            <td class="col field-umzugstermin">Liefertermin</td>
            <td class="col field-umzugsstatus">Status</td>
            <td class="col field-bestaetigt_am">AvisiertAm</td>
            <td class="col field-abgeschlossen">Abgeschlossen</td>
            <td class="col field-Summe sum menge">Summe</td>
        </tr>
    </thead>
    <tbody id="TblGruppierungenBody">
    <?php if (!empty($_smarty_tpl->tpl_vars['aReklas']->value)) {?>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['aReklas']->value, 'G', false, NULL, 'GList', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['G']->value) {
?>
            <tr data-id="<?php echo $_smarty_tpl->tpl_vars['G']->value['aid'];?>
" class="row inputRowVon">
                <td class="col field-aid"><a href="?s=aantrag&id=<?php echo $_smarty_tpl->tpl_vars['G']->value['aid'];?>
"><?php echo $_smarty_tpl->tpl_vars['G']->value['aid'];?>
</a></td>
                <td class="col field-antragsdatum"><?php if ($_smarty_tpl->tpl_vars['G']->value['antragsdatum']) {
echo $_smarty_tpl->tpl_vars['G']->value['antragsdatum'];
}?></td>
                <td class="col field-LeistungenKtg"><?php echo $_smarty_tpl->tpl_vars['G']->value['LeistungenKtg'];?>
</td>
                <td class="col field-LeistungenBez"><?php echo mb_convert_encoding(smarty_modifier_replace($_smarty_tpl->tpl_vars['G']->value['LeistungenBez'],";","<br>"), 'UTF-8', 'HTML-ENTITIES');?>
</td>
                <td class="col field-umzugstermin"><?php if ($_smarty_tpl->tpl_vars['G']->value['umzugstermin']) {
echo $_smarty_tpl->tpl_vars['G']->value['umzugstermin'];
}?></td>
                <td class="col field-field-umzugsstatus"><?php echo $_smarty_tpl->tpl_vars['G']->value['umzugsstatus'];?>
</td>
                <td class="col field-bestaetigt_am"><?php if ($_smarty_tpl->tpl_vars['G']->value['bestaetigt_am']) {
echo $_smarty_tpl->tpl_vars['G']->value['bestaetigt_am'];
}?></td>
                <td class="col field-abgeschlossen"><?php echo $_smarty_tpl->tpl_vars['G']->value['abgeschlossen'];
if ($_smarty_tpl->tpl_vars['G']->value['abgeschlossen_am']) {
echo $_smarty_tpl->tpl_vars['G']->value['abgeschlossen_am'];
}?></td>
                <td class="col field-Summe sum menge"><?php echo number_format($_smarty_tpl->tpl_vars['G']->value['Summe'],2,",",".");?>
 €</td>
            </tr>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    <?php }?>
    </tbody>
</table>
<?php }
}
