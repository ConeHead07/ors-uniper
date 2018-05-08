<?php /* Smarty version 2.6.26, created on 2017-05-03 02:30:43
         compiled from admin_umzugsformular.tpl.read.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'admin_umzugsformular.tpl.read.html', 17, false),array('modifier', 'date_format', 'admin_umzugsformular.tpl.read.html', 17, false),array('modifier', 'lower', 'admin_umzugsformular.tpl.read.html', 32, false),array('modifier', 'nl2br', 'admin_umzugsformular.tpl.read.html', 140, false),)), $this); ?>
<link rel="STYLESHEET" type="text/css" href="../css/SelBox.easy.css">
<link rel="STYLESHEET" type="text/css" href="css/SelBox.easy.css">

<div id="SysInfoBox"></div>

<link rel="stylesheet" type="text/css" href="css/umzugsformular.css">
<link rel="stylesheet" type="text/css" href="../css/umzugsformular.css">
<!-- MODUL UEBERSCHRIFTENBOX 109099 BEGIN --> 
<div class="divModuleBasic padding6px width5Col heightAuto colorContentMain"> 
<h1><span class="spanTitle">Leistungsanforderung #<?php echo $this->_tpl_vars['AS']['aid']; ?>
</span></h1> 
<p>
<div id="Umzugsantrag" class="divInlay"> 
<h2 style="margin:0;">Auftrags-Status</h2>
<table border=0 cellspacing=1 cellpadding=1>
  <tr>
    <td style="padding:0;height:auto;width:200px;"><label for="termin" style="display:block;width:auto;">Ausf&uuml;hrungstermin:</label></td>
    <td style="padding:0;width:250px;"><input type="text" value='<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['AS']['umzugstermin'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>
'
	onfocus="showDtPicker(this)" id="umzugstermin" name="AS[umzugstermin]" class="itxt itxt2col"></td>
  </tr>
  <tr>
    <td style="padding:0;height:auto;"><label for="umzugszeitt" style="display:block;width:auto;">Ausf&uuml;hrungszeit:</label></td>
    <td style="padding:0;"><input type="text" value='<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['umzugszeit'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
'
	id="umzugszeit" name="AS[umzugszeit]" class="itxt itxt2col"></td>
  </tr>
  <tr>
    <td style="padding:0;height:auto;"><label for="termin" style="display:block;width:auto;">Antragsdatum:</label></td>
    <td style="padding:0;"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['AS']['antragsdatum'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>
</td>
  </tr>
  <?php if ($this->_tpl_vars['AS']['angeboten_am']): ?>
  <tr>
    <td style="padding:0;"><label for="mitarbeiter" style="display:block;width:auto;">Genehmigt:</label></td>
    <td style="padding:0;" class="status_<?php echo $this->_tpl_vars['AS']['genehmigt_br']; ?>
"><img id="imgStatGen" src="images/status_<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['genehmigt_br'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)); ?>
.png"><span id="txtStatGen"><?php if ($this->_tpl_vars['AS']['genehmigt_br'] != 'Init'): ?> <?php echo $this->_tpl_vars['AS']['genehmigt_br']; ?>
 am <?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['genehmigt_br_am'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y %H:%M") : smarty_modifier_date_format($_tmp, "%d.%m.%Y %H:%M")); ?>
 <?php echo $this->_tpl_vars['AS']['genehmigt_br_von']; ?>
<?php endif; ?></span></td>
  </tr>
  <?php else: ?>
  <tr>
    <td style="padding:0;"><label for="mitarbeiter" style="display:block;width:auto;">Bestätigt:</label></td>
    <td style="padding:0;" class="status_<?php echo $this->_tpl_vars['AS']['genehmigt_br']; ?>
"><img id="imgStatGen" src="images/status_<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['genehmigt_br'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)); ?>
.png"><span id="txtStatGen"><?php if ($this->_tpl_vars['AS']['genehmigt_br'] != 'Init'): ?> <?php echo $this->_tpl_vars['AS']['genehmigt_br']; ?>
 am <?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['genehmigt_br_am'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y %H:%M") : smarty_modifier_date_format($_tmp, "%d.%m.%Y %H:%M")); ?>
 <?php echo $this->_tpl_vars['AS']['genehmigt_br_von']; ?>
<?php endif; ?></span></td>
  </tr>
  <?php endif; ?>
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
" target="_Umzugsblatt<?php echo $this->_tpl_vars['AS']['aid']; ?>
"><img src="%WebRoot%images/printer.png" width="16" height="16" alt="">Anforderungsblatt / Druckansicht</a>
<br>

<div style="float:left">
<h2 style="margin:0;">Leistungsantragsteller</h2> 
<table>
  <tr>
    <td style="padding:0;width:200px;"><label style="display:block;width:auto;">Vor &amp; Nachname:</label></td>
    <td style="padding:0;width:250px;"><span data-fld="AS[vorname]"></span><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['vorname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span> <span data-fld="AS[name]"></span><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span></td>
  </tr>
  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;">E-Mail:</label></td>
    <td style="padding:0;" data-fld="AS[email]"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>
  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;">Fon:</label></td>
    <td style="padding:0;" data-fld="AS[fon]"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['fon'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>

  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;">Standort:</label></td>
    <td style="padding:0;" data-fld="AS[ort]"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['ort'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>

  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;">Gebäude:</label></td>
    <td style="padding:0;" data-fld="AS[gebauede]"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['gebaeude'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
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
    <td style="padding:0;"><label style="display:block;width:auto;">Ticket-Nr.:</label></td>
    <td style="padding:0;"><span data-fld="AS[planonr]"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['planonnr'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span></td>
  </tr>
  
  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;">Terminwunsch:</label></td>
    <td style="padding:0;" data-fld="AS[terminwunsch]"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['AS']['terminwunsch'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>
</td>
  </tr>
	<!-- SelBox_initLSDefault(initInputObj, initLSName, initMultiple, initLSSearchFields, initLSInputFields) -->
</table>
<br>
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
$this->_smarty_include(array('smarty_include_tpl_file' => "umzugsformular_leistungsauswahl.tpl.read.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "umzugsformular_attachments.tpl.read.html", 'smarty_include_vars' => array('internal' => 1)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "umzugsformular_attachments.tpl.read2.html", 'smarty_include_vars' => array('UmzugsAnlagen' => $this->_tpl_vars['UmzugsAnlagenIntern'],'internal' => 1)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if (1): ?>
<div style="width:100%">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin_umzugsformular_gruppierung.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
<?php endif; ?>

<div id="BoxBemerkungen">
<strong>Bemerkungen:</strong><br>
<div id="BemerkungenHistorie"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['bemerkungen'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</div>
</div>
<div id="LoadingBar"></div>

</div> 