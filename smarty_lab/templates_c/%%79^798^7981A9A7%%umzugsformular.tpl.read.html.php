<?php /* Smarty version 2.6.26, created on 2015-12-10 00:33:01
         compiled from umzugsformular.tpl.read.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'umzugsformular.tpl.read.html', 17, false),array('modifier', 'date_format', 'umzugsformular.tpl.read.html', 17, false),array('modifier', 'nl2br', 'umzugsformular.tpl.read.html', 128, false),)), $this); ?>
<link rel="STYLESHEET" type="text/css" href="../css/SelBox.easy.css">
<link rel="STYLESHEET" type="text/css" href="css/SelBox.easy.css">

<div id="SysInfoBox"></div>

<link rel="stylesheet" type="text/css" href="css/umzugsformular.css">
<link rel="stylesheet" type="text/css" href="../css/umzugsformular.css">
<!-- MODUL UEBERSCHRIFTENBOX 109099 BEGIN --> 
<div class="divModuleBasic padding6px width5Col heightAuto colorContentMain"> 
<h1><span class="spanTitle">Leistungsanforderung <?php if ($this->_tpl_vars['AS']['aid']): ?>#<?php echo $this->_tpl_vars['AS']['aid']; ?>
<?php endif; ?></span></h1> 
<p>
<div id="Umzugsantrag" class="divInlay"> 
<h2 style="margin:0;">Leistungsantragsteller</h2> 
<table>
  <tr>
    <td style="padding:0;width:200px;"><label style="display:block;width:auto;">Antragsdatum:</label></td>
    <td style="padding:0;width:250px;"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['AS']['antragsdatum'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>
</td>
  </tr>
  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;">Vor &amp; Nachname:</label></td>
    <td style="padding:0;"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['vorname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>
  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;">E-Mail:</label></td>
    <td style="padding:0;"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>
  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;">Fon:</label></td>
    <td style="padding:0;"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['fon'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>

  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;">Standort:</label></td>
    <td style="padding:0;"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['ort'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>

  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;">Wirtschaftseinheit:</label></td>
    <td style="padding:0;"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['gebaeude_text'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>

  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;">Etage:</label></td>
    <td style="padding:0;"><?php echo $this->_tpl_vars['AS']['etage']; ?>
</td>
  </tr>

  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;">Raumnr:</label></td>
    <td style="padding:0;"><?php echo $this->_tpl_vars['AS']['raumnr']; ?>
</td>
  </tr>
  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;">Terminwunsch:</label></td>
    <td style="padding:0;"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['AS']['terminwunsch'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>
</td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="umzugszeitt" style="display:block;width:auto;">Uhrzeit</label></td>
    <td style="padding:0;"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['umzugszeit'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>
  <tr>
    <td style="padding:0;"><label style="background:#f00;border:0;display:block;width:auto;">Ausf&uuml;hrungstermin:</label></td>
    <td style="padding:0;"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['AS']['umzugstermin'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>
</td>
  </tr>
	<!-- SelBox_initLSDefault(initInputObj, initLSName, initMultiple, initLSSearchFields, initLSInputFields) -->
  <tr>
    <td style="padding:0;"><label for="von_gebaeude_text" style="display:block;width:auto;">Von:</label></td>
    <td class="ort"><?php if ($this->_tpl_vars['AS']['von_gebaeude_text'] == ""): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['von_gebaeude_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php endif; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['von_gebaeude_text'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>
	<!-- SelBox_initLSDefault(initInputObj, initLSName, initMultiple, initLSSearchFields, initLSInputFields) -->
  <tr>
    <td style="padding:0;"><label for="nach_gebauede_text" style="display:block;width:auto;">Nach:</label></td>
    <td class="zort"><?php if ($this->_tpl_vars['AS']['nach_gebaeude_text'] == ""): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['nach_gebaeude_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php endif; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['nach_gebaeude_text'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
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

<?php if (0): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "umzugsformular_mitarbeiterauswahl.tpl.read.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<?php if (0): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "umzugsformular_geraeteauswahl.tpl.read.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<?php if (1): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "umzugsformular_leistungsauswahl.tpl.read.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "umzugsformular_attachments.tpl.read.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<form action="?s=<?php echo $this->_tpl_vars['s']; ?>
" method="post" name="frmUmzugsantrag">
<input type="hidden" name="AS[aid]" value="<?php echo $this->_tpl_vars['AS']['aid']; ?>
">
<input type="hidden" name="AS[token]" value="<?php echo $this->_tpl_vars['AS']['token']; ?>
">
<strong>Bemerkung:</strong><br>
<textarea class="iarea bemerkungen" name="AS[add_bemerkungen]"></textarea>
<br>

<input class='btn blue' type="submit" xstyle="padding:0 0 9px 0;background:url(images/BtnBlue_160.png) bottom left no-repeat;border:0;width:160px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Anmerkung hinzufügen">
</form>
<div id="BoxBemerkungen">
<strong>Bisherige Bemerkungen:</strong><br>
<div id="BemerkungenHistorie"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['bemerkungen'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</div>
</div>
<div id="LoadingBar"></div>

</p>
</div> 