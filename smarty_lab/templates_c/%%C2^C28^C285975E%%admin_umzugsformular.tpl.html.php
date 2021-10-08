<?php /* Smarty version 2.6.26, created on 2017-05-03 02:58:42
         compiled from admin_umzugsformular.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'admin_umzugsformular.tpl.html', 29, false),array('modifier', 'date_format', 'admin_umzugsformular.tpl.html', 29, false),array('modifier', 'substr', 'admin_umzugsformular.tpl.html', 34, false),array('modifier', 'lower', 'admin_umzugsformular.tpl.html', 43, false),array('modifier', 'nl2br', 'admin_umzugsformular.tpl.html', 237, false),)), $this); ?>
<?php echo '
<link href="{WebRoot}css/SelBox.easy.css" rel="STYLESHEET" type="text/css" />
<script src="{WebRoot}js/FbAjaxUpdater.js" type="text/javascript"></script>
<script src="{WebRoot}js/PageInfo.js" type="text/javascript"></script>
<script src="{WebRoot}js/ObjectHandler.js" type="text/javascript"></script>
<script src="{WebRoot}js/SelBox.easy.js" type="text/javascript"></script>
<script src="{WebRoot}js/umzugsformular.easy.js?lm=20101021" type="text/javascript"></script>
<script src="{WebRoot}js/geraeteumzug.easy.js?lm=20101021" type="text/javascript"></script>
<script src="{WebRoot}js/dienstleister.js?lm=20101021" type="text/javascript"></script>
'; ?>

<script><?php echo '
optionsUmzugsarten.push({value:"Datenpflege", content:"Datenpflege"});
'; ?>
</script>

<div id="SysInfoBox"></div>

<link rel="stylesheet" type="text/css" href="css/umzugsformular.css">
<!-- MODUL UEBERSCHRIFTENBOX 109099 BEGIN --> 
<div class="divModuleBasic padding6px width5Col heightAuto colorContentMain"> 
<h1><span class="spanTitle">Leistungsanforderung #<?php echo $this->_tpl_vars['AS']['aid']; ?>
</span></h1> 
<p>
<div id="Umzugsantrag" class="divInlay"> 
<form action="umzugsantrag_speichern.php" name="frmUmzugsantrag" method="post" style="margin:0;padding:0;display:inline;">
<h2 style="margin:0;">Auftrags-Status</h2>
Angeboten: <?php echo $this->_tpl_vars['AS']['angeboten_am']; ?>
 von <?php echo $this->_tpl_vars['AS']['angeboten_von']; ?>

<table border=1 cellspacing=1 cellpadding=1>
  <tr>
    <td style="padding:0;height:auto;width:auto;"><label for="termin" style="width:180px;">Umzugstermin:</label></td>
    <td style="padding:0;width:250px;"><input type="text" value='<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['AS']['umzugstermin'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>
' 
    onfocus="showDtPicker(this)" id="umzugstermin" name="AS[umzugstermin]" class="itxt itxt2col"></td>
  </tr>
  <tr>
    <td style="padding:0;height:auto;width:auto;"><label for="AS[Umzugszeit]" style="width:180px;">Uhrzeit:</label></td>
    <td style="padding:0;width:250px;"><input type="text" value='<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['AS']['umzugszeit'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('substr', true, $_tmp, 0, 5) : substr($_tmp, 0, 5)); ?>
' 
    id="umzugszeit" name="AS[umzugszeit]" class="itxt itxt2col"></td>
  </tr>
  <tr>
    <td style="padding:0;height:auto;width:auto;"><label for="termin" style="width:180px;">Antragsdatum:</label></td>
    <td style="padding:0;width:auto;"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['AS']['antragsdatum'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>
</td>
  </tr>
  <tr>
    <td style="padding:0;width:auto;"><label for="mitarbeiter" style="width:180px;"><?php if ($this->_tpl_vars['AS']['angeboten_am']): ?>Genehmigt<?php else: ?>Best&auml;tigt<?php endif; ?>:</label></td>
    <td style="padding:0;width:auto;" class="status_<?php echo $this->_tpl_vars['AS']['genehmigt_br']; ?>
"><img id="imgStatGen" src="images/status_<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['genehmigt_br'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)); ?>
.png"><span id="txtStatGen"><?php if ($this->_tpl_vars['AS']['genehmigt_br'] != 'Init'): ?> <?php echo $this->_tpl_vars['AS']['genehmigt_br']; ?>
 am <?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['genehmigt_br_am'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y %H:%M") : smarty_modifier_date_format($_tmp, "%d.%m.%Y %H:%M")); ?>
 <?php echo $this->_tpl_vars['AS']['genehmigt_br_von']; ?>
<?php endif; ?></span></td>
  </tr>
  <tr>
    <td style="padding:0;width:auto;"><label for="mitarbeiter" style="width:180px;">Abgeschlossen:</label></td>
    <td style="padding:0;width:auto;" class="status_<?php echo $this->_tpl_vars['AS']['abgeschlossen']; ?>
"><img id="imgStatAbg" src="images/status_<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['abgeschlossen'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)); ?>
.png"><span id="txtStatAbg"><?php if ($this->_tpl_vars['AS']['abgeschlossen'] != 'Init'): ?> <?php echo $this->_tpl_vars['AS']['abgeschlossen']; ?>
 am <?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['abgeschlossen_am'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y %H:%M") : smarty_modifier_date_format($_tmp, "%d.%m.%Y %H:%M")); ?>
 <?php echo $this->_tpl_vars['AS']['abgeschlossen_von']; ?>
<?php endif; ?></span></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="umzugsstatus" style="width:180px;">Status:</label></td>
    <td style="padding:0;"><?php if (empty ( $this->_tpl_vars['AS']['angeboten_am'] ) && ((is_array($_tmp=$this->_tpl_vars['AS']['umzugsstatus'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)) == 'genehmigt'): ?>bestaetigt<?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['umzugsstatus'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php endif; ?></td>
  </tr>
	<!-- SelBox_initLSDefault(initInputObj, initLSName, initMultiple, initLSSearchFields, initLSInputFields) -->
</table>
<a href="%WebRoot%sites/umzugsblatt.php?id=<?php echo $this->_tpl_vars['AS']['aid']; ?>
" target="_Umzugsblatt<?php echo $this->_tpl_vars['AS']['aid']; ?>
"><img src="%WebRoot%images/printer.png" width="16" height="16" alt="">Umzugsblatt / Druckansicht</a>
<br>

<div style="float:left">
<h2 style="margin:0;">Leistungsantragsteller</h2> 
<input type="hidden" name="AS[aid]" value="<?php echo $this->_tpl_vars['AS']['aid']; ?>
">
<input type="hidden" name="AS[token]" value="<?php echo $this->_tpl_vars['AS']['token']; ?>
">
<table>
  <tr>
      <td style="padding:0;"><label for="mitarbeiter" style="width:180px;">Vor<?php if ($this->_tpl_vars['ASConf']['vorname']['required']): ?><span class="required">*</span><?php endif; ?> &amp; Nachname<span class="right"><?php if ($this->_tpl_vars['ASConf']['name']['required']): ?><span class="required">*</span><?php endif; ?></span></label></td>
    <td style="padding:0;width:250px;"><input type="text" readonly="true" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['vorname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[vorname]" class="itxt itxt1col floatLeft"><input type="text" readonly="true" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[name]" class="itxt itxt1col floatRight" title="Name"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="termin" style="width:180px;">E-Mail<span class="right"><?php if ($this->_tpl_vars['ASConf']['email']['required']): ?><span class="required">*</span><?php endif; ?></span></label></td>
    <td style="padding:0;"><input type="text" readonly="true" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[email]" class="itxt itxt2col" title="E-Mail"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="termin" style="width:180px;">Fon<span class="right"><?php if ($this->_tpl_vars['ASConf']['fon']['required']): ?><span class="required">*</span><?php endif; ?></span></label></td>
    <td style="padding:0;"><input type="text" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['fon'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[fon]" class="itxt itxt2col jtooltip" rel="%WebRoot%hilfetexte/antrag_fon.php"></td>
  </tr>

  <tr>
    <td style="padding:0;"><label for="ort" style="width:180px;"><?php echo $this->_tpl_vars['ASConf']['ort']['label']; ?>
<span class="right"><?php if ($this->_tpl_vars['ASConf']['ort']['required']): ?><span class="required">*</span><?php endif; ?></span></label></td>
    <td style="padding:0;"><input type="text" onclick="get_standort_ort(this)" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['ort'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[ort]" class="itxt itxt2col"></td>
  </tr>

  <tr>
    <td style="padding:0;"><label for="gebaeude" style="width:180px;"><?php echo $this->_tpl_vars['ASConf']['gebaeude']['label']; ?>
<span class="right"><?php if ($this->_tpl_vars['ASConf']['gebaeude']['required']): ?><span class="required">*</span><?php endif; ?></span></label>
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
    <td style="padding:0;"><label for="as_raumnr" style="display:block;width:auto;"><?php echo $this->_tpl_vars['ASConf']['raumnr']['label']; ?>
<span class="right"><?php if ($this->_tpl_vars['ASConf']['raumnr']['required']): ?><span class="required">*</span><?php endif; ?></span></label></td>
    <td style="padding:0;"><input type="text" id="as_raumnr" xreadonly="true" <?php if ($this->_tpl_vars['ASConf']['raumnr']['required']): ?>required="required"<?php endif; ?> xonclick="get_standort_raumnr(this)" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['raumnr'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[raumnr]" class="itxt itxt2col"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="kostenstelle" style="width:180px;"><?php echo $this->_tpl_vars['ASConf']['kostenstelle']['label']; ?>
<span class="right"><?php if ($this->_tpl_vars['ASConf']['kostenstelle']['required']): ?><span class="required">*</span><?php endif; ?></span></label></td>
    <td style="padding:0;"><input type="text" id="kostenstelle" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['kostenstelle'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[kostenstelle]" class="itxt itxt2col"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="planonnr" style="display:block;width:auto;"><?php echo $this->_tpl_vars['ASConf']['planonnr']['label']; ?>
<span class="right"><?php if ($this->_tpl_vars['ASConf']['planonnr']['required']): ?><span class="required">*</span><?php endif; ?></span></label></td>
    <td style="padding:0;"><input type="text" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['planonnr'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[planonnr]" class="itxt itxt2col"></td>
  </tr>
  
  <tr>
    <td style="padding:0;"><label for="termin" style="width:180px;">Terminwunsch<span class="right"><?php if ($this->_tpl_vars['ASConf']['terminwunsch']['required']): ?><span class="required">*</span><?php endif; ?></span></label></td>
    <td style="padding:0;"><input type="text" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['AS']['terminwunsch'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>
" 
	onfocus="showDtPicker(this)" id="terminwunsch" name="AS[terminwunsch]" class="itxt itxt2col jtooltip" rel="%WebRoot%hilfetexte/terminwunsch.php"></td>
  </tr>
	<!-- SelBox_initLSDefault(initInputObj, initLSName, initMultiple, initLSSearchFields, initLSInputFields) -->
  <tr>
    <td style="padding:0;"><label for="von_gebaeude_text" style="width:180px;">Von<span class="right"><?php if ($this->_tpl_vars['ASConf']['von']['required']): ?><span class="required">*</span><?php endif; ?></span></label>
        <input type="hidden" readonly="readonly" id="von_gebaeude_id" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['von_gebaeude_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[von_gebaeude_id]">
    </td>
    <td style="padding:0;">
        <input onclick="get_gebaeude(this, O('von_gebaeude_id'))" id="von_gebaeude_text" type="text" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['von_gebaeude_text'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" readonly="readonly" class="itxt itxt2col">
    </td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="nach_gebaeude_text" style="width:180px;">Nach<span class="right"><?php if ($this->_tpl_vars['ASConf']['nach']['required']): ?><span class="required">*</span><?php endif; ?></span></label>
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
    <td style="padding:0;"><label for="ansprechpartner" style="width:180px;">Vor &amp; Nachname:</label></td>
    <td style="padding:0;width:250px;"><input type="text" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['ansprechpartner'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[ansprechpartner]" class="itxt itxt2col" title="Ansprechpartner"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="termin" style="width:180px;">E-Mail:</label></td>
    <td style="padding:0;"><input type="text" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['ansprechpartner_email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[ansprechpartner_email]" class="itxt itxt2col" title="Ansprechpartner E-Mail"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="termin" style="width:180px;">Fon:</label></td>
    <td style="padding:0;"><input type="text" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['ansprechpartner_fon'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[ansprechpartner_fon]" class="itxt itxt2col jtooltip" rel="%WebRoot%hilfetexte/antrag_fon.php"></td>
  </tr>
</table>

<table>
  <tr>
    <td style="padding:0;width:180px;"><label style="display:block;width:auto;"><?php echo $this->_tpl_vars['ASConf']['umzug']['label']; ?>
</label></td>
    <td style="padding:0;width:300px;" class="options-onoff"><label class='<?php if ($this->_tpl_vars['AS']['umzug'] == 'Ja'): ?>on<?php else: ?>off<?php endif; ?> active'><?php echo $this->_tpl_vars['AS']['umzug']; ?>
</label></td>
  </tr>
</table>
</div>

<?php if (1): ?>
<div style="float:left; margin-left:50px;">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin_umzugsformular_lieferauswahl.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
<?php endif; ?>
<br clear= "all">
<div style="clear:all;"></div>

<?php if (0): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin_umzugsformular_mitarbeiterauswahl.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<?php if (0): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin_umzugsformular_geraeteauswahl.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<?php if (0): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin_umzugsformular_ortsauswahl.tpl.html", 'smarty_include_vars' => array()));
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

<?php if (! empty ( $this->_tpl_vars['UmzugsAnlagenIntern'] )): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "umzugsformular_attachments.tpl.read2.html", 'smarty_include_vars' => array('UmzugsAnlagen' => $this->_tpl_vars['UmzugsAnlagenIntern'],'internal' => 1)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<br>
<?php if (1): ?>
<div style="width:100%">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin_umzugsformular_gruppierung.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
<?php endif; ?>
<!-- <div style="color:#549e1a;font-weight:bold;text-decoration:none;cursor:pointer;" onclick="addMa();return false;">Weiteren Mitarbeiter ausw&auml;hlen <img align="absmiddle" src="images/hinzufuegen_off.png" width="14" alt=""></div><br> -->
<br>
<div id="BoxBemerkungen">
<strong>Bemerkungen:</strong><br>
<textarea class="iarea bemerkungen" name="AS[bemerkungen]"></textarea>
</div>
<div style="margin-top:20px;width:100%;"><!-- 
 --><input type="submit" name="CatchDefaultEnterReturnFalse" onclick="return false;" value="" style="display:none;border:0;background:#fff;color:#fff;position:relative;left:-500px;"><!-- 
 --><input type="submit" class="btn grey" onclick="umzugsantrag_save()" xstyle="padding:0 0 9px 0;background:url(images/BtnGrey.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Speichern"><!-- 
 --><input type="submit" class="btn red" onclick="umzugsantrag_reload()" xstyle="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Neu laden"><!-- 
 --><input type="submit" class="btn red" onclick="umzugsantrag_add_attachement()" xstyle="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Dateianhänge">
<?php if ($this->_tpl_vars['creator'] == 'mertens'): ?><input type="submit" class="btn red" onclick="umzugsantrag_add_internal_attachement()" xstyle="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Interne Dateianhänge"><?php endif; ?>
<br>
<br>
<?php if ($this->_tpl_vars['AS']['umzugsstatus'] != 'temp'): ?>
<strong>Status setzen: </strong> 
<div class="statusConsole">
<?php if ($this->_tpl_vars['AS']['umzugsstatus'] != 'angeboten'): ?>
<?php if ($this->_tpl_vars['AS']['genehmigt_br'] == 'Init'): ?>
<input id="btnStatGeprBackToUser" type="submit" class="<?php if ($this->_tpl_vars['AS']['geprueft'] == 'Ja' || $this->_tpl_vars['AS']['genehmigt_br'] != 'Init' || $this->_tpl_vars['AS']['bestaetigt'] != 'Init'): ?>cssHide<?php else: ?>btn blue<?php endif; ?>" onclick="umzugsantrag_set_status('zurueckgeben','Ja')" xstyle="padding:0 0 9px 0;background:url(images/BtnBlue_160.png) bottom left no-repeat;border:0;width:160px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Zurueckgeben">
<?php if ($this->_tpl_vars['AS']['umzug'] == 'Ja'): ?>
<input id="btnStatGenJa" type="submit" class="<?php if (( $this->_tpl_vars['AS']['genehmigt_br'] == 'Ja' || $this->_tpl_vars['AS']['bestaetigt'] != 'Init' || $this->_tpl_vars['AS']['geprueft'] != 'Init' ) && $this->_tpl_vars['AS']['umzugsstatus'] != 'erneutpruefen'): ?>cssHide<?php else: ?>btn green<?php endif; ?>" onclick="umzugsantrag_set_status('geprueft','Ja')" xstyle="padding:0 0 9px 0;background:url(images/BtnGreen.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" title="Senden als Gepr�ft" value="Senden">
<?php else: ?>
<input id="btnStatGenJa" type="submit" class="btn green" <?php if ($this->_tpl_vars['AS']['genehmigt_br'] == 'Ja' || $this->_tpl_vars['AS']['bestaetigt'] != 'Init'): ?>class="cssHide"<?php endif; ?> onclick="umzugsantrag_set_status('genehmigt','Ja')" xstyle="padding:0 0 9px 0;background:url(images/BtnGreen.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" title="Best�tigung senden" value="Senden">
<?php endif; ?>
<?php if (0): ?><input id="btnStatGenNein" type="submit" class="btn red" <?php if ($this->_tpl_vars['AS']['genehmigt_br'] == 'Nein' || $this->_tpl_vars['AS']['bestaetigt'] != 'Init'): ?>class="cssHide"<?php endif; ?> onclick="umzugsantrag_set_status('genehmigt','Nein')" xstyle="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Ablehnen"><?php endif; ?>
&nbsp;<?php endif; ?>
<?php if ($this->_tpl_vars['AS']['genehmigt_br'] == 'Ja'): ?>
<input id="btnStatGenReset" type="submit" class=<?php if ($this->_tpl_vars['AS']['genehmigt_br'] == 'Init' || $this->_tpl_vars['AS']['bestaetigt'] != 'Init'): ?>cssHide<?php else: ?>btn blue<?php endif; ?>" onclick="umzugsantrag_set_status('genehmigt','Init')" xstyle="padding:0 0 9px 0;background:url(images/BtnBlue_160.png) bottom left no-repeat;border:0;width:160px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Genehmigung aufheben">
&nbsp;<?php endif; ?>
<?php if ($this->_tpl_vars['AS']['genehmigt_br'] == 'Ja' || $this->_tpl_vars['AS']['bestaetigt'] == 'Ja'): ?>
<input id="btnStatAbgJa" type="submit" class="btn green" <?php if ($this->_tpl_vars['AS']['abgeschlossen'] != 'Init'): ?>class="cssHide"<?php endif; ?> onclick="umzugsantrag_set_status('abgeschlossen','Ja')" xstyle="padding:0 0 9px 0;background:url(images/BtnGreen_160.png) bottom left no-repeat;border:0;width:160px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Umzug ist abgeschlossen">
&nbsp;<?php endif; ?>
<input id="btnStatAbgReset" type="submit" class="<?php if ($this->_tpl_vars['AS']['abgeschlossen'] == 'Init'): ?>cssHide<?php else: ?>btn blue<?php endif; ?>" onclick="umzugsantrag_set_status('abgeschlossen','Init')"      xstyle="padding:0 0 9px 0;background:url(images/BtnBlue_160.png) bottom left no-repeat;border:0;width:160px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Abschluss aufheben">
<input id="btnStatAbgStorno" type="submit" class="<?php if ($this->_tpl_vars['AS']['abgeschlossen'] != 'Init'): ?>cssHide<?php else: ?>btn red<?php endif; ?>" onclick="umzugsantrag_set_status('abgeschlossen','Storniert')" xstyle="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Stornieren"></div>
<?php endif; ?>
</div>
<?php endif; ?>
<?php if ($this->_tpl_vars['AS']['umzug'] == 'Nein'): ?>Antrag ist ohne Umzug und kann direkt ausgef�hrt werden
<?php else: ?>Antrag ist mit Umzug und erfordert die Genehmigung durch Property
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
<style>
</style>

<div style="display:none;">
<table id="MA_SELECT" class="MitarbeierItem">
  <caption style="font-size:11px;padding:0px;height:18px;">
  <div style="float:left;"><strong>Mitarbeiter</strong> <span name="aktionsstatus" style="margin-left:40px;">Aus Stammdaten</span></div>
  <div style="float:right;">[Anzeigen/Bearbeiten] 
  <img name="RaumStatImg" src="" align="absmiddle" style="border:0;" width=16 height=16 title=""><a href="" onclick="show_raum_mitarbeiter(this, 'ziel');return false;">[Raum-Neu: <span class="RaumStatInfo"></span> ]</a> &nbsp; 
  <a href="" onclick="show_raum_mitarbeiter(this,'');return false;">[Raum-Alt]</a> <span onclick="dropMA(this)" style="cursor:pointer;">Aus Umzugsliste l�schen <img align="absmiddle" src="images/loeschen_off.png" width="14" alt=""><span></div>
</caption>
  <thead>
  <tr>
    <td class="ColNName">Nachname</td>
    <td class="ColVName">Vorname</td>
    <td class="ColXF" title="Bitte geben Sie bei externen Mitarbeitern die Firma an!">Fremdfirma</td>
	
    <td class="ColAbt">Abtg</td>
	<td class="ColGeb">Geb&auml;ude</td>
    <td class="ColEtg">Etage</td>
    <td class="ColRnr">R-Nr</td>
    <td class="ColAP">AP-Nr</td>
    
    <td class="ColFon">Tel-Nr</td>
    <td class="ColPC">PC-Nr</td>
    <td class="ColIP">Feste IP</td>
  </tr>
  </thead>
  <tbody>
  <tr class="inputRowVon">
	<td class="nn"><input type="hidden" name="MA[mid][]" value=""><!-- 
 	 --><input type="hidden" name="MA[maid][]" value=""><!-- 
	 --><input value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" id="mitarbeiter"  xonclick="get_mitarbeiter(this)"  class="AutoFill UpperCase" type="text" name="MA[name][]"></td>
    <td class="vn"><input value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['vorname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="itxt itxt1col UpperCase AutoFill" type="text" name="MA[vorname][]"></td>
	<td class="xf"><input value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['extern_firma'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="get_extern_firma(this)" class="itxt itxt1col AutoFill" size=15 type="text" name="MA[extern_firma][]"></td>
	
	<td class="abt"><input value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['abteilung'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" readonly="true" onclick="get_abteilung(this)" class="itxt AutoFill" size=4 type="text" name="MA[abteilung][]"></td>
	<td class="geb"><input  autocomplete="off" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['gebaeude'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" readonly="true" onclick="get_gebaeude(this)" class="itxt itxt1col AutoFill" type="text" name="MA[gebaeude][]"></td>
    <td class="etg"><input value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['etage'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="get_etage(this)" readonly="true" class="itxt AutoFill" type="text" name="MA[etage][]"></td>
    <td class="rnr"><input value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['raumnr'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="get_raumnr(this)" readonly="true" class="itxt AutoFill" size=8 type="text" name="MA[raumnr][]"></td>
    <td class="apnr"><input value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['apnr'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="get_apnr(this)" class="itxt AutoFill" readonly="true" size=8 type="text" name="MA[apnr][]"></td>
    
    <td class="fon"><input value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['fon'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="itxt" type="text" name="MA[fon][]"></td>
    <td class="pcnr"><input value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['pcnr'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="itxt" type="text" name="MA[pcnr][]"></td>
    <td class="festeip"><input value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['festeip'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="itxt" type="text" name="MA[festeip][]"></td>
</tr>
  <tr class="inputRowZiel">
    <td class="ziel" align=right>Anforderungsart</td>
	<td class="uart"><input value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['umzugsart'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="UserInput" readonly="true" onclick="get_umzugsart(this)" type="text" name="MA[umzugsart][]"></td>
    <td class="ziel" align=right> Nach:</td>
    <td class="etg"><input value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['zabteilung'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" readonly="true" onclick="get_abteilung(this)" class="itxt UserInput" type="text" name="MA[zabteilung][]"></td>
	<td class="zort"><input value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['zgebaeude'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" readonly="true" onclick="get_gebaeude(this)" class="itxt UserInput" type="text" name="MA[zgebaeude][]"></td>
    <td class="geb"><input value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['zetage'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" readonly="true" onclick="get_etage(this)" class="itxt UserInput" type="text" name="MA[zetage][]"></td>
    <td class="rnr"><input value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['zraumnr'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" readonly="true" onclick="get_raumnr(this)" class="itxt UserInput" type="text" name="MA[zraumnr][]"></td>
    <td class="rnr"><input value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['zapnr'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="itxt" type="text" name="MA[zapnr][]"></td>
    <td class="zspace" colspan=3></td>
</tr>
</tbody>
</table>
</div>

<div style="display:none;">
<table id="MA_INPUT" class="MitarbeierItem">
  <caption style="font-size:11px;padding:0px;height:18px;">
  <div style="float:left;"><strong>Mitarbeiter</strong> <span name="aktionsstatus" style="margin-left:40px;color:#f00;">H&auml;ndischer Eintrag!</div>
  <div style="float:right;">[Namen pr&uuml;fen] 
  <img name="RaumStatImg" src="" align="absmiddle" style="border:0;" width=16 height=16 title=""><a href="" onclick="show_raum_mitarbeiter(this, 'ziel');return false;">[Raum-Neu: <span id="RaumStatInfo"></span> ]</a> &nbsp; 
  <a href="" onclick="show_raum_mitarbeiter(this,'');return false;">[Raum-Alt]</a> <span onclick="dropMA(this)" style="cursor:pointer;">Aus Anforderungsliste l&ouml;schen <img align="absmiddle" src="images/loeschen_off.png" width="14" alt=""><span></div><div style="clear:both;"></div>
</caption>
  <thead>
  <tr>
    <td class="ColNName">Nachname</td>
    <td class="ColVName">Vorname</td>
    <td class="ColXF" title="Bitte geben Sie bei externen Mitarbeitern die Firma an!">Fremdfirma</td>
	
    <td class="ColAbt">Abtg</td>
	<td class="ColGeb">Geb&auml;ude</td>
    <td class="ColEtg">Etage</td>
    <td class="ColRnr">R-Nr</td>
    <td class="ColAP">AP-Nr</td>
    
    <td class="ColFon">Tel-Nr</td>
    <td class="ColPC">PC-Nr</td>
    <td class="ColIP">Feste IP</td>
  </tr>
  </thead>
  <tbody>
  <tr class="inputRowVon">
	<td class="nn"><input type="hidden" name="MA[mid][]" value=""><!-- 
 	 --><input type="hidden" name="MA[maid][]" value=""><!-- 
	 --><input value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" id="mitarbeiter" xonclick="get_mitarbeiter(this)"  class="itxt itxt1col UpperCase UserInput" type="text" name="MA[name][]"></td>
    <td class="vn"><input value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['vorname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="itxt itxt1col UpperCase UserInput" type="text" name="MA[vorname][]"></td>
	<td class="xf"><input value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['extern_firma'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="get_extern_firma(this)" class="itxt itxt1col UserInput" size=15 type="text" name="MA[extern_firma][]"></td>
	
	<td class="abt"><input value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['abteilung'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" readonly="true" onclick="get_abteilung(this)" class="itxt UserInput" size=4 type="text" name="MA[abteilung][]"></td>
	<td class="geb"><input  autocomplete="off" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['gebaeude'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" readonly="true" onclick="get_gebaeude(this)" class="itxt itxt1col UserInput" type="text" name="MA[gebaeude][]"></td>
    <td class="etg"><input value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['etage'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="get_etage(this)" readonly="true" class="itxt UserInput" type="text" name="MA[etage][]"></td>
    <td class="rnr"><input value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['raumnr'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="get_raumnr(this)" readonly="true" class="itxt UserInput" size=8 type="text" name="MA[raumnr][]"></td>
    <td class="apnr"><input value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['apnr'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="itxt UserInput" readonly="true" size=8 type="text" name="MA[apnr][]"></td>
    
    <td class="fon"><input value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['fon'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="itxt" type="text" name="MA[fon][]"></td>
    <td class="pcnr"><input value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['pcnr'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="itxt" type="text" name="MA[pcnr][]"></td>
    <td class="festeip"><input value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['festeip'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="itxt" type="text" name="MA[festeip][]"></td>
	</tr>
  <tr class="inputRowZiel">
    <td class="ziel" align=right>Anforderungsart</td>
	<td class="uart"><input value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['umzugsart'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="UserInput" readonly="true" onclick="get_umzugsart(this)" type="text" name="MA[umzugsart][]"></td>
    <td class="ziel" align=right> Nach:</td>
    <td class="abt"><input value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['zabteilung'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" readonly="true" onclick="get_abteilung(this)" class="itxt UserInput" type="text" name="MA[zabteilung][]"></td>
	<td class="geb"><input value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['zgebaeude'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" readonly="true" onclick="get_gebaeude(this)" class="itxt UserInput" type="text" name="MA[zgebaeude][]"></td>
    <td class="etg"><input value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['zetage'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" readonly="true" onclick="get_etage(this)" class="itxt UserInput" type="text" name="MA[zetage][]"></td>
    <td class="rnr"><input value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['zraumnr'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" readonly="true" onclick="get_raumnr(this)" class="itxt UserInput" type="text" name="MA[zraumnr][]"></td>
    <td class="apnr"><input value="<?php echo ((is_array($_tmp=$this->_tpl_vars['MA']['zapnr'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="itxt UserInput" type="text" name="MA[zapnr][]"></td>
    <td class="zspace" colspan=3></td>
</tr>
</tbody>
</table>
</div>

<div id="SelBoxUart" class="SelBox">
<div style="position:absolute;right:0;"><img align="absmiddle" src="images/loeschen_off.png" style="cursor:pointer" onclick="document.getElementById('SelbBoxUart').style.display='none'" width="14" alt=""></div>
<div class="SelTitle"><strong>Anforderungsarten</strong></div>
<div id="SelBoxUartItems">
<div class="SelItem"><input type="checkbox" name="uartbox" value="Box" checked=1> <strong>Box</strong>move</div>
<div class="SelItem"><input type="checkbox" name="uartbox" value="Mit M�bel" checked=1> Mit <strong>M&ouml;bel</strong></div>
</div>
</div>
