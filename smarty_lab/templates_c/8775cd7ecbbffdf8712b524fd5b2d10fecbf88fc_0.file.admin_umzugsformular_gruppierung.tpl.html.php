<?php
/* Smarty version 3.1.34-dev-7, created on 2021-10-01 10:34:52
  from '/var/www/html/html/admin_umzugsformular_gruppierung.tpl.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_6156e44c3fbc23_13708885',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8775cd7ecbbffdf8712b524fd5b2d10fecbf88fc' => 
    array (
      0 => '/var/www/html/html/admin_umzugsformular_gruppierung.tpl.html',
      1 => 1633084483,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6156e44c3fbc23_13708885 (Smarty_Internal_Template $_smarty_tpl) {
echo '<script'; ?>
 src="js/auftraege_gruppieren.js?201704250208"><?php echo '</script'; ?>
>
<div style="clear:both;width:100%">

<div class="SelBoxDienstleisterWidth" style="width:390px;clear:both">
  <span style="float:left;margin-bottom:2px;color:#549e1a;font-weight:bold;text-decoration:none;cursor:pointer;" 
      onclick="gruppierungsauftrag_new_search();return false;">
  Gruppierung hinzuf&uuml;gen <img align="absmiddle" src="images/hinzufuegen_off.png" width="14" alt=""></span>
    
<table class="MitarbeierItem" style="border:0;padding:0;margin:0 0 5px 0;width:100%;">
    <tr>
        <td style="border:0;padding:0;margin:0">
            <input name="SelectGruppierung" id="SelectGruppierung"  
                   style="width:100%;border:1px solid #549e1a" 
                   onclick="get_gruppierungsauftrag(this)" 
                   ondblclick="gruppierungsauftrag_new_search()">
        </td>
    </tr>
</table>
</div>
</div>

<table class="MitarbeierItem" style="width:100%;">
    <thead>
        <tr>
            <td style="width:14px;padding:0;"> X </td>
            <td>ID</td>
            <td>Termin</td>
            <td>Lieferort</td>
            <td>PLZ</td>
            <td>Stra&szlig;e &amp; Nr</td>
            <td>Service</td>
            <td>Auftragseingang</td>
            <td>Genehmigt</td>
            <td>Avisiert</td>
			<td>Abgeschlossen</td>
        </tr>
    </thead>
    <tbody id="TblGruppierungenBody">
    <?php if (!empty($_smarty_tpl->tpl_vars['UmzugsGruppierungen']->value)) {?>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['UmzugsGruppierungen']->value, 'G', false, NULL, 'GList', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['G']->value) {
?>
            <tr data-id="<?php echo $_smarty_tpl->tpl_vars['G']->value['aid'];?>
" class="row inputRowVon">
                <td style="padding:0;">
                <span data-editid="<?php echo $_smarty_tpl->tpl_vars['G']->value['aid'];?>
"
                      onclick="auftragsliste_remove(<?php echo $_smarty_tpl->tpl_vars['G']->value['aid'];?>
)"
                      style="cursor:pointer;margin:0;padding:0;"><img style="cursor:pointer;margin:0;padding:0;" align="absmiddle" src="images/loeschen_off.png" width="14" alt=""><span></td>
                <td><a href="?s=aantrag&id=<?php echo $_smarty_tpl->tpl_vars['G']->value['aid'];?>
"><?php echo $_smarty_tpl->tpl_vars['G']->value['aid'];?>
</a></td>
                <td><?php if ($_smarty_tpl->tpl_vars['G']->value['umzugstermin']) {
echo $_smarty_tpl->tpl_vars['G']->value['umzugstermin'];
} else {
echo $_smarty_tpl->tpl_vars['G']->value['terminwunsch'];
}?></td>
                <td><?php echo $_smarty_tpl->tpl_vars['G']->value['ort'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['G']->value['etage'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['G']->value['raumnr'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['G']->value['umzug'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['G']->value['antragsdatum'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['G']->value['Genehmigt'];
if ($_smarty_tpl->tpl_vars['G']->value['genehmigt_br_am']) {?> <?php echo $_smarty_tpl->tpl_vars['G']->value['genehmigt_br_am'];
}?></td>
                <td><?php echo $_smarty_tpl->tpl_vars['G']->value['Geprueft'];
if ($_smarty_tpl->tpl_vars['G']->value['geprueft_am']) {?> <?php echo $_smarty_tpl->tpl_vars['G']->value['geneprueft_am'];
}?></td>
                <td><?php echo $_smarty_tpl->tpl_vars['G']->value['abgeschlossen'];
if ($_smarty_tpl->tpl_vars['G']->value['abgeschlossen_am']) {
echo $_smarty_tpl->tpl_vars['G']->value['abgeschlossen_am'];
}?></td>
            </tr>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    <?php }?>
	</tbody>
</table>
<input id="gruppierteauftraege" name="gruppierteauftraege" type="hidden" value="<?php if (!empty($_smarty_tpl->tpl_vars['UmzugsGruppierungsIds']->value)) {
echo $_smarty_tpl->tpl_vars['UmzugsGruppierungsIds']->value;
}?>">


<table id="TplGruppierungsTable" style="display:none;">
        <tr class="row inputRowVon">
            <td style="padding:0;">
			<span data-editid="" onclick="auftragsliste_remove($(this).attr('data-editid'))" style="cursor:pointer;margin:0;padding:0;"><img style="cursor:pointer;margin:0;padding:0;" align="absmiddle" src="images/loeschen_off.png" width="14" alt=""><span></td>
            <td><a data-lnkto="umzug" data-fld="aid" href=""></a></td>
			<td data-fld="termin"></td>
			<td data-fld="ort"></td>
			<td data-fld="etage"></td>
			<td data-fld="raumnr"></td>
			<td data-fld="umzug"></td>
			<td data-fld="antragsdatum"></td>
			<td><span data-fld="Genehmigt"></span><span data-fld="genehmigt_br_am"></span></td>
			<td><span data-fld="Geprueft"></span><span data-fld="geprueft_am"></span></td>
			<td><span data-fld="Abgeschlossen"></span><span data-fld="abgeschlossen_am"></span></td>
		</tr>
</table>
<?php }
}
