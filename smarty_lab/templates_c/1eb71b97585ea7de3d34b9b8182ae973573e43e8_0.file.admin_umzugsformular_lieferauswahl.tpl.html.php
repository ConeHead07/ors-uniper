<?php
/* Smarty version 3.1.34-dev-7, created on 2021-09-22 08:49:16
  from '/var/www/html/html/admin_umzugsformular_lieferauswahl.tpl.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_614aee0c32baf5_51983823',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1eb71b97585ea7de3d34b9b8182ae973573e43e8' => 
    array (
      0 => '/var/www/html/html/admin_umzugsformular_lieferauswahl.tpl.html',
      1 => 1632300549,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_614aee0c32baf5_51983823 (Smarty_Internal_Template $_smarty_tpl) {
?><h2 style="margin:0;">Lieferfirma / Dienstleister</h2> 

<div class="SelBoxDienstleisterWidth" style="width:390px;">
<span style="float:left;margin-bottom:2px;color:#549e1a;font-weight:bold;text-decoration:none;cursor:pointer;" 
      onclick="dienstleister_new_search();return false;">
Aus Liste hinzuf&uuml;gen <img align="absmiddle" src="images/hinzufuegen_off.png" width="14" alt=""></span>
    
<span style="display:xnone;float:right;margin-bottom:2px;color:#000;font-weight:bold;text-decoration:none;cursor:pointer;" 
      onclick="dienstleister_neu_anlegen();return false;">
Neu anlegen <img align="absmiddle" src="images/uebernehmen_off.png" width="14" alt=""></span><br clear="all">
<table class="MitarbeierItem" style="border:0;padding:0;margin:0 0 15px 0;width:100%;">
    <tr>
        <td style="border:0;padding:0;margin:0">
            <input name="SelectDienstleister" id="SelectDienstleister"  
                   style="width:100%;border:1px solid #549e1a" 
                   onclick="get_dienstleister(this)" 
                   ondblclick="dienstleister_new_search()">
        </td>
    </tr>
</table>
</div>

<input type="hidden" id="dl_id" name="AS[dienstleister_id]" value="<?php echo $_smarty_tpl->tpl_vars['AS']->value['dienstleister_id'];?>
">
<table id="InputDienstleister">
  <tr>
    <td style="padding:0;"><label for="dl_firmenname" style="width:180px;">Firmenname</label></td>
    <td style="padding:0;width:200px;"><input id="dl_firmenname" type="text" readonly="true" value="<?php if (!empty($_smarty_tpl->tpl_vars['DL']->value['Firmenname'])) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['DL']->value['Firmenname'], ENT_QUOTES, 'ISO-8859-1', true);
}?>" name="DL[Firmenname]" class="itxt itxt2col"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="dl_ansprechpartner" style="width:180px;">Ansprechpartner</label></td>
    <td style="padding:0;"><input id="dl_ansprechpartner" type="text" readonly="true" value="<?php if (!empty($_smarty_tpl->tpl_vars['DL']->value['Ansprechpartner'])) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['DL']->value['Ansprechpartner'], ENT_QUOTES, 'ISO-8859-1', true);
}?>" name="DL[Ansprechpartner]" class="itxt itxt2col"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="dl_strasse" style="width:180px;">Strasse</label></td>
    <td style="padding:0;"><input id="dl_strasse" type="text" readonly="true" value="<?php if (!empty($_smarty_tpl->tpl_vars['DL']->value['Strasse'])) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['DL']->value['Strasse'], ENT_QUOTES, 'ISO-8859-1', true);
}?>" name="DL[Strasse]" class="itxt itxt2col"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="dl_ort" style="width:180px;">Ort</label></td>
    <td style="padding:0;"><input id="dl_ort" type="text" readonly="true" value="<?php if (!empty($_smarty_tpl->tpl_vars['DL']->value['Ort'])) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['DL']->value['Ort'], ENT_QUOTES, 'ISO-8859-1', true);
}?>" name="DL[Ort]" class="itxt itxt2col"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="dl_handy" style="width:180px;">Handy</label></td>
    <td style="padding:0;"><input id="dl_handy" type="text" readonly="true" value="<?php if (!empty($_smarty_tpl->tpl_vars['DL']->value['Handy'])) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['DL']->value['Handy'], ENT_QUOTES, 'ISO-8859-1', true);
}?>" name="DL[Handy]" class="itxt itxt2col"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="dl_festnetz" style="width:180px;">Festnetz</label></td>
    <td style="padding:0;"><input id="dl_festnetz" type="text" readonly="true" value="<?php if (!empty($_smarty_tpl->tpl_vars['DL']->value['Festnetz'])) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['DL']->value['Festnetz'], ENT_QUOTES, 'ISO-8859-1', true);
}?>" name="DL[Festnetz]" class="itxt itxt2col"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="dl_email" style="width:180px;">E-Mail</label></td>
    <td style="padding:0;"><input id="dl_email" type="text" readonly="true" value="<?php if (!empty($_smarty_tpl->tpl_vars['DL']->value['Email'])) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['DL']->value['Email'], ENT_QUOTES, 'ISO-8859-1', true);
}?>" name="DL[Email]" class="itxt itxt2col"></td>
  </tr>
  <tr>
      <td colspan="2">
          <label style="text-align: left;background:none;border:none;color:#000;width:100%;">Bemerkung Mertens intern</label>
          <textarea id="as_dl_bemerkung" style="width:100%;height:160px;max-width:380px;" class="iarea" name="AS[dienstleister_bemerkung]"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['dienstleister_bemerkung'], ENT_QUOTES, 'ISO-8859-1', true);?>
</textarea>
      </td>
  </tr>
</table>

<?php }
}
