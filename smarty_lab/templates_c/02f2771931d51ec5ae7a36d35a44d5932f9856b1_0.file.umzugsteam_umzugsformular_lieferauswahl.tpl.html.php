<?php
/* Smarty version 3.1.34-dev-7, created on 2021-10-19 10:48:14
  from '/var/www/html/html/umzugsteam_umzugsformular_lieferauswahl.tpl.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_616ea26ecee270_19865068',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '02f2771931d51ec5ae7a36d35a44d5932f9856b1' => 
    array (
      0 => '/var/www/html/html/umzugsteam_umzugsformular_lieferauswahl.tpl.html',
      1 => 1634640382,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_616ea26ecee270_19865068 (Smarty_Internal_Template $_smarty_tpl) {
?><h2 style="margin:0;">Lieferfirma / Dienstleister</h2> 

<table id="InputDienstleister">
  <tr>
    <td style="padding:0;"><label style="width:180px;">Firmenname</label></td>
    <td style="padding:0;width:200px;"><?php if (!empty($_smarty_tpl->tpl_vars['DL']->value['Firmenname'])) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['DL']->value['Firmenname'], ENT_QUOTES, 'UTF-8', true);
}?>></td>
  </tr>
  <tr>
    <td style="padding:0;"><label style="width:180px;">Ansprechpartner</label></td>
    <td style="padding:0;"><?php if (!empty($_smarty_tpl->tpl_vars['DL']->value['Ansprechpartner'])) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['DL']->value['Ansprechpartner'], ENT_QUOTES, 'UTF-8', true);
}?></td>
  </tr>
  <tr>
    <td style="padding:0;"><label style="width:180px;">Strasse</label></td>
    <td style="padding:0;"><?php if (!empty($_smarty_tpl->tpl_vars['DL']->value['Strasse'])) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['DL']->value['Strasse'], ENT_QUOTES, 'UTF-8', true);
}?></td>
  </tr>
  <tr>
    <td style="padding:0;"><label style="width:180px;">Ort</label></td>
    <td style="padding:0;"><?php if (!empty($_smarty_tpl->tpl_vars['DL']->value['Ort'])) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['DL']->value['Ort'], ENT_QUOTES, 'UTF-8', true);
}?></td>
  </tr>
  <tr>
    <td style="padding:0;"><label style="width:180px;">Handy</label></td>
    <td style="padding:0;"><?php if (!empty($_smarty_tpl->tpl_vars['DL']->value['Handy'])) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['DL']->value['Handy'], ENT_QUOTES, 'UTF-8', true);
}?></td>
  </tr>
  <tr>
    <td style="padding:0;"><label style="width:180px;">Festnetz</label></td>
    <td style="padding:0;"><?php if (!empty($_smarty_tpl->tpl_vars['DL']->value['Festnetz'])) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['DL']->value['Festnetz'], ENT_QUOTES, 'UTF-8', true);
}?></td>
  </tr>
  <tr>
    <td style="padding:0;"><label style="width:180px;">E-Mail</label></td>
    <td style="padding:0;"><?php if (!empty($_smarty_tpl->tpl_vars['DL']->value['Email'])) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['DL']->value['Email'], ENT_QUOTES, 'UTF-8', true);
}?></td>
  </tr>
  <tr>
      <td colspan="2">
          <label style="text-align: left;background:none;border:none;color:#000;width:100%;">Bemerkung Mertens intern</label>
          <div><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['dienstleister_bemerkung'], ENT_QUOTES, 'UTF-8', true);?>
</div>
      </td>
  </tr>
</table>

<?php }
}
