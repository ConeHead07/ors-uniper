<?php /* Smarty version 2.6.26, created on 2016-02-10 21:58:07
         compiled from property_umzugsformular.tpl.read.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'property_umzugsformular.tpl.read.html', 20, false),array('modifier', 'date_format', 'property_umzugsformular.tpl.read.html', 20, false),array('modifier', 'substr', 'property_umzugsformular.tpl.read.html', 25, false),array('modifier', 'lower', 'property_umzugsformular.tpl.read.html', 33, false),array('modifier', 'nl2br', 'property_umzugsformular.tpl.read.html', 179, false),)), $this); ?>
<?php echo '
<link rel="STYLESHEET" type="text/css" href="{WebRoot}css/SelBox.easy.css" />
<link rel="stylesheet" type="text/css" href="{WebRoot}css/umzugsformular.css" />
<script src="{WebRoot}js/FbAjaxUpdater.js" type="text/javascript"></script>
<script src="{WebRoot}js/PageInfo.js" type="text/javascript"></script>
<script src="{WebRoot}js/ObjectHandler.js" type="text/javascript"></script>
<script src="{WebRoot}js/SelBox.easy.js" type="text/javascript"></script>
<script src="{WebRoot}js/umzugsformular.easy.js?lm=20150126" type="text/javascript"></script>
'; ?>

<div id="SysInfoBox"></div>
<!-- MODUL UEBERSCHRIFTENBOX 109099 BEGIN --> 
<div class="divModuleBasic padding6px width5Col heightAuto colorContentMain"> 
<h1><span class="spanTitle">Leistungsanforderung #<?php echo $this->_tpl_vars['AS']['aid']; ?>
</span></h1> 
<p>
<div id="Umzugsantrag" class="divInlay"> 
<h2 style="margin:0;">Auftrags-Status</h2>
<table border=0 cellspacing=1 cellpadding=1>
  <tr>
    <td style="padding:0;width:200px;"><label for="termin" style="display:block;width:auto;">Ausf&uuml;hrungstermin:</label></td>
    <td style="padding:0;width:300px;"><input type="text" value='<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['AS']['umzugstermin'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>
'
	onfocus="showDtPicker(this)" id="umzugstermin" name="AS[umzugstermin]" class="itxt itxt2col"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="umzugszeit" style="display:block;width:auto;">Ausf&uuml;hrungszeit:</label></td>
    <td style="padding:0;"><span data-fld="AS[umzugszeit]"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['AS']['umzugszeit'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('substr', true, $_tmp, 0, 5) : substr($_tmp, 0, 5)); ?>
</span></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="termin" style="display:block;width:auto;">Antragsdatum:</label></td>
    <td style="padding:0;"><span data-fld="AS[antragsdatum]"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['AS']['antragsdatum'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>
</td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="mitarbeiter" style="display:block;width:auto;"><?php if ($this->_tpl_vars['AS']['angeboten_am']): ?>Genehmigt<?php else: ?>Best&auml;tigt<?php endif; ?>:</label></td>
    <td style="padding:0;" class="status_<?php echo $this->_tpl_vars['AS']['genehmigt_br']; ?>
"><img id="imgStatGen" src="images/status_<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['genehmigt_br'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)); ?>
.png"><span id="txtStatGen"><?php if ($this->_tpl_vars['AS']['genehmigt_br'] != 'Init'): ?> <?php echo $this->_tpl_vars['AS']['genehmigt_br']; ?>
 am <?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['genehmigt_br_am'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y %H:%M") : smarty_modifier_date_format($_tmp, "%d.%m.%Y %H:%M")); ?>
 <?php echo $this->_tpl_vars['AS']['genehmigt_br_von']; ?>
<?php endif; ?></span></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="mitarbeiter" style="display:block;width:auto;">Auftrag abgeschlossen:</label></td>
    <td style="padding:0;" class="status_<?php echo $this->_tpl_vars['AS']['abgeschlossen']; ?>
"><img id="imgStatAbg" src="images/status_<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['abgeschlossen'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)); ?>
.png"><span id="txtStatAbg"><?php if ($this->_tpl_vars['AS']['abgeschlossen'] != 'Init'): ?> <?php echo $this->_tpl_vars['AS']['abgeschlossen']; ?>
 am <?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['abgeschlossen_am'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y %H:%M") : smarty_modifier_date_format($_tmp, "%d.%m.%Y %H:%M")); ?>
 <?php echo $this->_tpl_vars['AS']['abgeschlossen_von']; ?>
<?php endif; ?></span></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="umzugsstatus" style="display:block;width:auto;">Status:</label></td>
    <td style="padding:0;"><?php if (empty ( $this->_tpl_vars['AS']['angeboten_am'] ) && ((is_array($_tmp=$this->_tpl_vars['AS']['umzugsstatus'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)) == 'genehmigt'): ?>bestaetigt<?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['umzugsstatus'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php endif; ?></td>
  </tr>
	<!-- SelBox_initLSDefault(initInputObj, initLSName, initMultiple, initLSSearchFields, initLSInputFields) -->
</table>
<a href="%WebRoot%sites/umzugsblatt.php?id=<?php echo $this->_tpl_vars['AS']['aid']; ?>
&mode=property" target="_Umzugsblatt<?php echo $this->_tpl_vars['AS']['aid']; ?>
"><img src="%WebRoot%images/printer.png" width="16" height="16" alt="">Anforderungsblatt / Druckansicht</a>
<a href="%WebRoot%index.php?s=pantrag&id=<?php echo $this->_tpl_vars['AS']['aid']; ?>
&export=csv" target="_blank">CSV-Export</a>
<br>

<h2 style="margin:0;">Leistungsantragsteller</h2> 
<table>
  <tr>
    <td style="padding:0;width:200px;"><label style="display:block;width:auto;">Vor &amp; Nachname:</label></td>
    <td style="padding:0;width:300px;"><span data-fld="AS[vorname]"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['vorname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span> <span data-fld="AS[name]"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span></td>
  </tr>
  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;">E-Mail:</label></td>
    <td style="padding:0;"><span data-fld="AS[email]"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span></td>
  </tr>
  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;">Fon:</label></td>
    <td style="padding:0;"><span data-fld="AS[fon]"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['fon'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span></td>
  </tr>

  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;">Standort:</label></td>
    <td style="padding:0;"><span data-fld="AS[ort]"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['ort'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span></td>
  </tr>

  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;">Wirtschaftseinheit:</label></td>
    <td style="padding:0;"><span data-fld="AS[gebaeude_text]"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['gebaeude_text'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span></td>
  </tr>
  
  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;">Etage:</label></td>
    <td style="padding:0;"><span data-fld="AS[etage]"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['etage'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span></td>
  </tr>
  
  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;">Raum-Nr:</label></td>
    <td style="padding:0;"><span data-fld="AS[raumnr]"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['raumnr'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span></td>
  </tr>
  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;"><?php echo $this->_tpl_vars['ASConf']['kostenstelle']['label']; ?>
:</label></td>
    <td style="padding:0;"><span data-fld="AS[kostenstelle]"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['kostenstelle'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span></td>
  </tr>
  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;">Ticket Nr.:</label></td>
    <td style="padding:0;"><span data-fld="AS[planonnr]"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['planonnr'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span></td>
  </tr>
  
  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;">Terminwunsch:</label></td>
    <td style="padding:0;" class="jtooltip" rel="%WebRoot%hilfetexte/terminwunsch.php"><span data-fld="AS[terminwunsch]"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['AS']['terminwunsch'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>
</span></td>
  </tr>

  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;">Von</label></td>
    <td class="ort"><span data-fld="AS[von_gebaeude_text]"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['von_gebaeude_text'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span></td>
  </tr>
  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;">Nach</label>
    </td>
    <td class="zort"><span data-fld="AS[nach_gebaeude_text]"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['nach_gebaeude_text'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span></td>
  </tr>
</table>
<br>
<h2 style="margin:0;">Ansprechpartner vor Ort</h2> 
<table>
  <tr>
    <td style="padding:0;width:200px;"><label style="display:block;width:auto;">Vor &amp; Nachname</label></td>
    <td style="padding:0;width:300px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['ansprechpartner'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>
  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;">E-Mail</label></td>
    <td style="padding:0;"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['ansprechpartner_email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>
  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;">Fon</label></td>
    <td style="padding:0;"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['ansprechpartner_fon'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>
</table>
<table>
  <tr>
    <td style="padding:0;width:200px;"><label style="display:block;width:auto;">Umzug</label></td>
    <td style="padding:0;width:300px;" class="options-onoff"><label class='<?php if ($this->_tpl_vars['AS']['umzug'] == 'Ja'): ?>on<?php else: ?>off<?php endif; ?> active'><?php echo $this->_tpl_vars['AS']['umzug']; ?>
</label></td>
  </tr>
</table>
<br clear="all" >
<br>
<br>

<?php if (0): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "property_umzugsformular_mitarbeiterauswahl.tpl.read.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<?php if (0): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "property_umzugsformular_geraeteauswahl.tpl.read.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<?php if (1): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "property_umzugsformular_leistungsauswahl.tpl.read.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "umzugsformular_attachments.tpl.read.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['AS']['umzugsstatus'] == 'angeboten'): ?>
<form name="frmUmzugsantrag" method="POST">
<input type="hidden" name="AS[aid]" value="<?php echo $this->_tpl_vars['AS']['aid']; ?>
">
<?php if (0): ?>
<div style="margin-top:20px;width:100%;"><!-- 
 --><input type="submit" name="CatchDefaultEnterReturnFalse" onclick="return false;" value="" style="border:0;background:#fff;color:#fff;position:relative;left:-500px;"><!-- 
 --><input type="submit" onclick="umzugsantrag_save()" style="padding:0 0 9px 0;background:url(images/BtnGrey.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Speichern"><!-- 
 --><input type="submit" onclick="umzugsantrag_reload()" style="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Neu laden"><!-- 
 --><input type="submit" onclick="umzugsantrag_add_attachement()" style="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Dateianhänge"> 
<br>
<?php endif; ?>
<br>

<strong>Aktuellen Status <span style="color:#00f;"><?php echo $this->_tpl_vars['AS']['umzugsstatus']; ?>
</span> &auml;ndern</strong>
<div class="statusConsole" style="xdisplay:none;">
<input id="btnStatGenJa"    type="submit" class="<?php if ($this->_tpl_vars['AS']['umzugsstatus'] != 'angeboten' && ( $this->_tpl_vars['AS']['genehmigt_br'] == 'Ja' || $this->_tpl_vars['AS']['bestaetigt'] != 'Init' )): ?>cssHide<?php else: ?>btn green<?php endif; ?>" onclick="umzugsantrag_set_status('genehmigt','Ja')" xstyle="padding:0 0 9px 0;background:url(images/BtnGreen.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Genehmigen">
<input id="btnStatGenNein"  type="submit" class="<?php if ($this->_tpl_vars['AS']['umzugsstatus'] != 'angeboten' && ( $this->_tpl_vars['AS']['genehmigt_br'] == 'Nein' || $this->_tpl_vars['AS']['bestaetigt'] != 'Init' )): ?>"cssHide<?php else: ?>btn red<?php endif; ?>" onclick="umzugsantrag_set_status('genehmigt','Nein')" xstyle="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Ablehnen">
<input id="btnStatGenReset" type="submit" class="<?php if ($this->_tpl_vars['AS']['genehmigt_br'] == 'Init' || $this->_tpl_vars['AS']['bestaetigt'] != 'Init'): ?>cssHide<?php else: ?>btn red<?php endif; ?>" onclick="umzugsantrag_set_status('genehmigt','Init')" xstyle="padding:0 0 9px 0;background:url(images/BtnBlue_160.png) bottom left no-repeat;border:0;width:160px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Genehmigung aufheben">
</div>
<br>
<div id="BoxBemerkungen">
<strong>Bemerkungen / Grund für Ablehnung:</strong><br>
<textarea class="iarea bemerkungen" name="AS[bemerkungen]"></textarea>
</div>
</form>
<?php endif; ?>
<!-- Debug-Btn:
<input type="submit" onclick="return umzugsantrag_submit_debug('speichern')" style="padding:0 0 9px 0;background:url(images/BtnGrey.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" name="cmd" value="speichern">
<input type="submit" onclick="return umzugsantrag_submit_debug('senden')" style="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" name="cmd" value="senden">
<input type="submit" onclick="return umzugsantrag_submit_debug('stornieren')" style="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" name="cmd" value="stornieren">
<input type="submit" onclick="return umzugsantrag_submit_debug('laden')" style="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" name="cmd" value="laden">
 -->
<div id="BoxBemerkungenHistorie">
<strong>Bisherige Bemerkungen</strong><br>
<div id="BemerkungenHistorie"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['bemerkungen'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</div>
</div>
</form>
</div>
<div id="LoadingBar"></div>
</p>
</div>