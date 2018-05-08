<?php /* Smarty version 2.6.26, created on 2016-01-18 13:35:41
         compiled from admin_umzugsformular_lieferauswahl.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'admin_umzugsformular_lieferauswahl.tpl.html', 27, false),)), $this); ?>
<h2 style="margin:0;">Lieferfirma / Dienstleister</h2> 

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

<input type="hidden" id="dl_id" name="AS[dienstleister_id]" value="<?php echo $this->_tpl_vars['AS']['dienstleister_id']; ?>
">
<table id="InputDienstleister">
  <tr>
    <td style="padding:0;"><label for="dl_firmenname" style="width:180px;">Firmenname</label></td>
    <td style="padding:0;width:200px;"><input id="dl_firmenname" type="text" readonly="true" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['DL']['Firmenname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="DL[Firmenname]" class="itxt itxt2col"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="dl_ansprechpartner" style="width:180px;">Ansprechpartner</label></td>
    <td style="padding:0;"><input id="dl_ansprechpartner" type="text" readonly="true" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['DL']['Ansprechpartner'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="DL[Ansprechpartner]" class="itxt itxt2col"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="dl_strasse" style="width:180px;">Strasse</label></td>
    <td style="padding:0;"><input id="dl_strasse" type="text" readonly="true" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['DL']['Strasse'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="DL[Strasse]" class="itxt itxt2col"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="dl_ort" style="width:180px;">Ort</label></td>
    <td style="padding:0;"><input id="dl_ort" type="text" readonly="true" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['DL']['Ort'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="DL[Ort]" class="itxt itxt2col"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="dl_handy" style="width:180px;">Handy</label></td>
    <td style="padding:0;"><input id="dl_handy" type="text" readonly="true" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['DL']['Handy'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="DL[Handy]" class="itxt itxt2col"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="dl_festnetz" style="width:180px;">Festnetz</label></td>
    <td style="padding:0;"><input id="dl_festnetz" type="text" readonly="true" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['DL']['Festnetz'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="DL[Festnetz]" class="itxt itxt2col"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="dl_email" style="width:180px;">E-Mail</label></td>
    <td style="padding:0;"><input id="dl_email" type="text" readonly="true" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['DL']['Email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="DL[Email]" class="itxt itxt2col"></td>
  </tr>
  <tr>
      <td colspan="2">
          <label style="text-align: left;background:none;border:none;color:#000;width:100%;">Bemerkung Mertens intern</label>
          <textarea id="as_dl_bemerkung" style="width:100%;height:160px;max-width:380px;" class="iarea" name="AS[dienstleister_bemerkung]"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['dienstleister_bemerkung'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</textarea>
      </td>
  </tr>
</table>
