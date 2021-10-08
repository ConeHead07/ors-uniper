<?php /* Smarty version 2.6.26, created on 2017-05-11 16:13:58
         compiled from property_umzugsformular.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'property_umzugsformular.tpl.html', 24, false),array('modifier', 'date_format', 'property_umzugsformular.tpl.html', 24, false),array('modifier', 'lower', 'property_umzugsformular.tpl.html', 34, false),array('modifier', 'nl2br', 'property_umzugsformular.tpl.html', 184, false),)), $this); ?>
<?php echo '
<script src="{WebRoot}js/FbAjaxUpdater.js" type="text/javascript"></script>
<script src="{WebRoot}js/PageInfo.js" type="text/javascript"></script>
<link rel="STYLESHEET" type="text/css" href="{WebRoot}css/SelBox.easy.css">
<script src="{WebRoot}js/ObjectHandler.js" type="text/javascript"></script>
<script src="{WebRoot}js/SelBox.easy.js" type="text/javascript"></script>
<script src="{WebRoot}js/umzugsformular.easy.js?lm=20101021" type="text/javascript"></script>
<script src="{WebRoot}js/geraeteumzug.easy.js?lm=20101021" type="text/javascript"></script>
'; ?>

<div id="SysInfoBox"></div>

<link rel="stylesheet" type="text/css" href="css/umzugsformular.css">
<!-- MODUL UEBERSCHRIFTENBOX 109099 BEGIN --> 
<div class="divModuleBasic padding6px width5Col heightAuto colorContentMain"> 
<h1><span class="spanTitle">Leistungsanforderung #<?php echo $this->_tpl_vars['AS']['aid']; ?>
</span></h1> 
<p>
<div id="Umzugsantrag" class="divInlay"> 
<form action="umzugsantrag_speichern.php" name="frmUmzugsantrag" method="post" style="margin:0;padding:0;display:inline;">
<input type="hidden" name="AS[token]" value="<?php echo $this->_tpl_vars['AS']['token']; ?>
">
<h2 style="margin:0;">Genehmigungs-Status</h2>
<table border=0 cellspacing=1 cellpadding=1>
  <tr>
    <td style="padding:0;width:180px;"><label for="termin" style="display:block;width:auto;">Umzugstermin:</label></td>
    <td style="padding:0;width:250px;"><input type="text" value='<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['AS']['umzugstermin'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>
'
	onfocus="showDtPicker(this)" id="umzugstermin" name="AS[umzugstermin]" class="itxt itxt2col"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="termin" style="display:block;width:auto;">Umzugszeit:</label></td>
    <td style="padding:0;"><input type="text" value='<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['umzugszeit'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
'
	id="umzugszeit" name="AS[umzugszeit]" class="itxt itxt2col"></td>
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
    <td style="padding:0;"><label for="mitarbeiter" style="display:block;width:auto;">Abgeschlossen:</label></td>
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
"><img src="%WebRoot%images/printer.png" width="16" height="16" alt="">Umzugsblatt / Druckansicht</a>
<a href="%WebRoot%index.php?s=pantrag&id=<?php echo $this->_tpl_vars['AS']['aid']; ?>
&export=csv" target="_blank">CSV-Export</a>
<br>

<div>
<h2 style="margin:0;">Leistungsantragsteller</h2> 
<input type="hidden" name="AS[aid]" value="<?php echo $this->_tpl_vars['AS']['aid']; ?>
">
<table>
  <tr>
    <td style="padding:0;width:180px;"><label for="mitarbeiter" style="display:block;width:auto;">Vor &amp; Nachname:</label></td>
    <td style="padding:0;width:250px;"><input type="text" readonly="true" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['vorname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[vorname]" class="itxt itxt1col floatLeft"><input type="text" readonly="true" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[name]" class="itxt itxt1col floatRight" title="Name"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="termin" style="display:block;width:auto;">E-Mail:</label></td>
    <td style="padding:0;"><input type="text" readonly="true" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[email]" class="itxt itxt2col" title="E-Mail"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="termin" style="display:block;width:auto;">Fon:</label></td>
    <td style="padding:0;"><input type="text" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['fon'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[fon]" class="itxt itxt2col jtooltip" rel="%WebRoot%hilfetexte/antrag_fon.php"></td>
  </tr>

  <tr>
    <td style="padding:0;"><label for="ort" style="display:block;width:auto;">Standort:</label></td>
    <td style="padding:0;"><input type="text" onclick="get_standort_ort(this)" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['ort'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[ort]" class="itxt itxt2col"></td>
  </tr>

  <tr>
    <td style="padding:0;"><label for="gebaeude" style="display:block;width:auto;">Wirtschaftseinheit:</label>
        <input type="hidden" id="gebaeude" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['gebaeude'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[gebaeude]"></td>
    <td style="padding:0;"><input type="text" onclick="get_standort_gebaeude(this, O('gebaeude'))" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['gebaeude_text'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[gebaeude_text]" class="itxt itxt2col"></td>
  </tr>

  <tr>
    <td style="padding:0;"><label for="etage" style="display:block;width:auto;"><?php echo $this->_tpl_vars['ASConf']['etage']['label']; ?>
<span class="right"><?php if ($this->_tpl_vars['ASConf']['etage']['required']): ?><span class="required">*</span><?php endif; ?></span></label>
        <input type="hidden" id="etage" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['etage'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php if ($this->_tpl_vars['ASConf']['etage']['required']): ?>required="required"<?php endif; ?> name="AS[etage]">
    </td>
    <td style="padding:0;"><input type="text" readonly="true" <?php if ($this->_tpl_vars['ASConf']['etage']['required']): ?>required="required"<?php endif; ?> id="ASEtageUsrInput" onclick="get_gebaeude_etage(this, O('etage'))" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['etage'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[etage_text]" class="itxt itxt2col"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="kostenstelle" style="display:block;width:auto;"><?php echo $this->_tpl_vars['ASConf']['kostenstelle']['label']; ?>
<span class="right"><?php if ($this->_tpl_vars['ASConf']['kostenstelle']['required']): ?><span class="required">*</span><?php endif; ?></span></label></td>
    <td style="padding:0;"><input type="text" id="kostenstelle" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['kostenstelle'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[kostenstelle]" class="itxt itxt2col"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="kostenstelel" style="display:block;width:auto;"><?php echo $this->_tpl_vars['ASConf']['planonnr']['label']; ?>
<span class="right"><?php if ($this->_tpl_vars['ASConf']['planonnr']['required']): ?><span class="required">*</span><?php endif; ?></span></label></td>
    <td style="padding:0;"><input type="text" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['planonnr'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[planonnr]" class="itxt itxt2col"></td>
  </tr>
  
  <tr>
    <td style="padding:0;"><label for="termin" style="display:block;width:auto;">Terminwunsch:</label></td>
    <td style="padding:0;"><input type="text" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['AS']['terminwunsch'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>
" 
	onfocus="showDtPicker(this)" id="terminwunsch" name="AS[terminwunsch]" class="itxt itxt2col jtooltip" rel="%WebRoot%hilfetexte/terminwunsch.php"></td>
  </tr>
	<!-- SelBox_initLSDefault(initInputObj, initLSName, initMultiple, initLSSearchFields, initLSInputFields) -->
  <tr>
    <td style="padding:0;"><label for="von_gebaeude_text" style="display:block;width:auto;">Von:</label>
        <input type="hidden" readonly="readonly" id="von_gebaeude_id" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['von_gebaeude_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[von_gebaeude_id]">
    </td>
    <td style="padding:0;">
        <input onclick="get_gebaeude(this, O('von_gebaeude_id'))" id="von_gebaeude_text" type="text" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['von_gebaeude_text'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" readonly="readonly" class="itxt itxt2col">
    </td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="nach_gebaeude_text" style="display:block;width:auto;">Nach:</label>
        <input type="hidden" id="nach_gebaeude_id" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['nach_gebaeude_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[nach_gebaeude_id]">
    </td>
    <td style="padding:0;">
        <input onclick="get_gebaeude(this, O('nach_gebaeude_id'))" id="nach_gebaeude_text" type="text" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['nach_gebaeude_text'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" readonly="readonly" class="itxt itxt2col">
    </td>
  </tr>
</table>
<br>
<h2 style="margin:0;">Ansprechpartner vor Ort</h2> 
<table>
  <tr>
    <td style="padding:0;width:180px;"><label for="ansprechpartner" style="display:block;width:auto;">Vor &amp; Nachname:</label></td>
    <td style="padding:0;width:250px;"><input type="text" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['ansprechpartner'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[ansprechpartner]" class="itxt itxt2col" title="Ansprechpartner"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="termin" style="display:block;width:auto;">E-Mail:</label></td>
    <td style="padding:0;"><input type="text" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['ansprechpartner_email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[ansprechpartner_email]" class="itxt itxt2col" title="Ansprechpartner E-Mail"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="termin" style="display:block;width:auto;">Fon:</label></td>
    <td style="padding:0;"><input type="text" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['ansprechpartner_fon'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[ansprechpartner_fon]" class="itxt itxt2col jtooltip" rel="%WebRoot%hilfetexte/antrag_fon.php"></td>
  </tr>
</table>
</div>

<table>
  <tr>
    <td style="padding:0;width:180px;"><label style="display:block;width:auto;"><?php echo $this->_tpl_vars['ASConf']['umzug']['label']; ?>
</label></td>
    <td style="padding:0;width:300px;" class="options-onoff"><label class='<?php if ($this->_tpl_vars['AS']['umzug'] == 'Ja'): ?>on<?php else: ?>off<?php endif; ?> active'><?php echo $this->_tpl_vars['AS']['umzug']; ?>
</label></td>
  </tr>
</table>
</div>

<?php if (0): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "property_umzugsformular_mitarbeiterauswahl.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<?php if (0): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "property_umzugsformular_geraeteauswahl.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<?php if (1): ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin_umzugsformular_leistungsauswahl.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "umzugsformular_attachments.tpl.read.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<!-- <div style="color:#549e1a;font-weight:bold;text-decoration:none;cursor:pointer;" onclick="addMa();return false;">Weiteren Mitarbeiter ausw&auml;hlen <img align="absmiddle" src="images/hinzufuegen_off.png" width="14" alt=""></div><br> -->
<br>
<div id="BoxBemerkungen">
<strong>Bemerkungen:</strong><br>
<textarea class="iarea bemerkungen" name="AS[bemerkungen]"></textarea>
</div>
<div style="margin-top:20px;width:100%;"><!-- 
 --><input type="submit" name="CatchDefaultEnterReturnFalse" onclick="return false;" value="" style="border:0;background:#fff;color:#fff;position:relative;left:-500px;"><!-- 
 --><input class="btn grey" type="submit" onclick="umzugsantrag_save()" xstyle="padding:0 0 9px 0;background:url(images/BtnGrey.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Speichern"><!-- 
 --><input class="btn red" type="submit" onclick="umzugsantrag_reload()" xstyle="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Neu laden"><!-- 
 --><input class="btn red" type="submit" onclick="umzugsantrag_add_attachement()" xstyle="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Dateianhänge">
<br>
<br>
<strong>Status setzen: </strong> <?php echo $this->_tpl_vars['AS']['umzugsstatus']; ?>

<div class="statusConsole" xstyle="display:none;">
<?php if ($this->_tpl_vars['AS']['umzugsstatus'] == 'geprueft' || $this->_tpl_vars['AS']['umzugsstatus'] == 'angeboten'): ?>
<input id="btnStatZurueck"  class="btn blue"  type="submit"   <?php if ($this->_tpl_vars['AS']['umzugsstatus'] != 'angeboten' && ( $this->_tpl_vars['AS']['genehmigt_br'] != 'Init' || $this->_tpl_vars['AS']['genehmigt'] != 'Init' )): ?>class="cssHide"<?php endif; ?> onclick="umzugsantrag_set_status( 'erneutpruefen','Ja')" xstyle="padding:0 0 9px 0;background:url(images/BtnGreen.png) bottom left no-repeat;border:0;width:150px;height:24px;font-size:12px;color:#fff;font-weight:bold;" data-reCheckLabel="Erneut pr�fen lassen" data-sendLabel="Senden" title="Erneut pr�fen lassen" value="Zur�ck geben">
<input id="btnStatGenJa"    class="btn green" type="submit"   <?php if ($this->_tpl_vars['AS']['umzugsstatus'] != 'angeboten' && ( $this->_tpl_vars['AS']['genehmigt_br'] != 'Init' || $this->_tpl_vars['AS']['genehmigt'] != 'Init' )): ?>class="cssHide"<?php endif; ?> onclick="umzugsantrag_set_status( getLeistungenChanged() ? 'erneutpruefen' : 'genehmigt','Ja')" xstyle="padding:0 0 9px 0;background:url(images/BtnGreen.png) bottom left no-repeat;border:0;width:150px;height:24px;font-size:12px;color:#fff;font-weight:bold;" data-reCheckLabel="Erneut pr�fen lassen" data-sendLabel="Senden" title="Genehmigung senden" value="Senden">
<input id="btnStatGenNein"  class="btn red" type="submit" <?php if ($this->_tpl_vars['AS']['umzugsstatus'] != 'angeboten' && ( $this->_tpl_vars['AS']['genehmigt_br'] != 'Init' || $this->_tpl_vars['AS']['genehmigt'] != 'Init' )): ?>class="cssHide"<?php endif; ?> onclick="umzugsantrag_set_status('genehmigt','Nein')" xstyle="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Ablehnen">
<input id="btnStatGenReset" type="submit" class="<?php if ($this->_tpl_vars['AS']['genehmigt_br'] == 'Init' || $this->_tpl_vars['AS']['bestaetigt'] != 'Init'): ?>cssHide<?php else: ?>btn blue<?php endif; ?> onclick="umzugsantrag_set_status('genehmigt','Init')" xstyle="padding:0 0 9px 0;background:url(images/BtnBlue_160.png) bottom left no-repeat;border:0;width:160px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Genehmigung aufheben">
<?php endif; ?></div>
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

<div id="SelBoxUart" class="SelBox">
<div style="position:absolute;right:0;"><img align="absmiddle" src="images/loeschen_off.png" style="cursor:pointer" onclick="document.getElementById('SelbBoxUart').style.display='none'" width="14" alt=""></div>
<div class="SelTitle"><strong>Anforderungsarten</strong></div>
<div id="SelBoxUartItems">
<div class="SelItem"><input type="checkbox" name="uartbox" value="Box" checked=1> <strong>Box</strong>move</div>
<div class="SelItem"><input type="checkbox" name="uartbox" value="Mit M�bel" checked=1> Mit <strong>M�bel</strong></div>
</div>
</div>
