<?php /* Smarty version 2.6.26, created on 2015-12-17 01:59:01
         compiled from property_umzugsformular_lieferauswahl.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'property_umzugsformular_lieferauswahl.tpl.html', 6, false),)), $this); ?>
<h2 style="margin:0;">Lieferfirma / Dienstleister</h2> 
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
</table>
