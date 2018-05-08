<?php /* Smarty version 2.6.26, created on 2016-02-12 07:44:14
         compiled from umzugsblatt.tpl.read.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'umzugsblatt.tpl.read.html', 62, false),array('modifier', 'date_format', 'umzugsblatt.tpl.read.html', 62, false),array('modifier', 'substr', 'umzugsblatt.tpl.read.html', 62, false),array('modifier', 'nl2br', 'umzugsblatt.tpl.read.html', 170, false),)), $this); ?>
<html>
<head>
<link rel="STYLESHEET" type="text/css" href="../css/tablelisting.css">
<link rel="STYLESHEET" type="text/css" href="css/tablelisting.css">
<link rel="STYLESHEET" type="text/css" href="../css/SelBox.easy.css">
<link rel="STYLESHEET" type="text/css" href="css/SelBox.easy.css">
<link rel="stylesheet" type="text/css" xhref="css/umzugsformular.css">
<link rel="stylesheet" type="text/css" xhref="../css/umzugsformular.css">
<link rel="stylesheet" type="text/css" xhref="<?php echo $this->_tpl_vars['WebRoot']; ?>
css/umzugsformular.css">

<link rel="STYLESHEET" type="text/css" href="<?php echo $this->_tpl_vars['WebRoot']; ?>
css/tablelisting.css">
<link rel="STYLESHEET" type="text/css" href="<?php echo $this->_tpl_vars['WebRoot']; ?>
css/SelBox.easy.css">

<link rel="STYLESHEET" type="text/css" media="print" href="../css/tablelisting.print.css">
<link rel="STYLESHEET" type="text/css" media="print" href="css/tablelisting.print.css">
<link rel="STYLESHEET" type="text/css" media="print" href="<?php echo $this->_tpl_vars['WebRoot']; ?>
css/tablelisting.print.css">
<link rel="STYLESHEET" type="text/css" media="print" href="<?php echo $this->_tpl_vars['WebRoot']; ?>
css/umzugsdatenblatt.print.css">
<link rel="STYLESHEET" type="text/css" media="print" href="../css/umzugsdatenblatt.print.css">
<link rel="STYLESHEET" type="text/css" media="print" href="css/umzugsdatenblatt.print.css">

<style>
<?php echo '
@page { size:landscape; }
*,
html * {
	font-family:Arial,sans-serif;
        font-size:12px;
}
table {
    border-bottom:1px solid #bbb;
    border-left:1px solid #bbb;
    border-collapse:collapse;
}
table td {
    border-top:1px solid #bbb;
    border-right:1px solid #bbb;
    border-collapse:collapse;
    padding:3px;
}
td label {
    margin-left:10px;
}
h2 {
    font-size:16px;
	letter-spacing:3px;
}
'; ?>

</style>
</head>
<body onload="window.print()">
<div id="SysInfoBox"></div>

<!-- MODUL UEBERSCHRIFTENBOX 109099 BEGIN --> 
<div class="divModuleBasic padding6px width5Col heightAuto colorContentMain"> 
    <div><img src="<?php echo $this->_tpl_vars['WebRoot']; ?>
images/logo_mertens.png" width="174" height="32" style="margin-bottom:20px"></div>
	<table style="width:480px">
	<tr>
		<td style="padding:8px 13px">
			<h1 style="display:inline;margin:0;font-size:18px;color:#bbb;">Leistungsanforderungsblatt ID <span style="float:right;font-size:18px;">#<?php echo $this->_tpl_vars['AS']['aid']; ?>
</span></h1>
			<div style="margin-top:10px;height:30px;position:relative;">
			<em style="font-size:14px;font-weight:bold;color:#bbb;position:absolute;bottom:0;">Ausf&uuml;hrungstermin</em>
			<span style="float:right;font-size:18px;position:absolute;bottom:0;right:0;"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['AS']['umzugstermin'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>
 ab <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['AS']['umzugszeit'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('substr', true, $_tmp, 0, 5) : substr($_tmp, 0, 5)); ?>
</span>
			</div>
		</td>
	</tr>
	</table>

<div id="Umzugsantrag" class="divInlay"> 
<?php print_r($AS,1 ) ?>
<br>
<div>
<h2 style="margin:6px 0;">Leistungsantragsteller</h2> 
<input type="hidden" name="AS[aid]" value="<?php echo $this->_tpl_vars['AS']['aid']; ?>
">
<table style="width:480px">
  <tr>
      <td style="width:160px"><label for="mitarbeiter"><b>Vor &amp; Nachname:</b></label></td>
    <td style="width:220px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['vorname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>
  <tr>
    <td style=""><label for="termin"><b>E-Mail:</b></label></td>
    <td style=""><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>
  <tr>
    <td style=""><label for="termin"><b>Fon:</b></label></td>
    <td style=""><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['fon'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>

  <tr>
    <td style=""><label for="ort"><b>Standort:</b></label></td>
    <td style=""><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['ort'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>

  <tr>
    <td style=""><label for="gebaeude"><b>Wirtschaftseinheit:</b></label></td>
    <td style=""><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['gebaeude_text'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>
  <tr>
    <td style=""><label><b><?php echo $this->_tpl_vars['ASConf']['kostenstelle']['label']; ?>
:</b></label></td>
    <td style=""><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['kostenstelle'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>
  <tr>
    <td style="" class="th"><label><b>Ticket Nr.</b></label></td>
    <td style=""><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['planonnr'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>
  <tr>
    <td style=""><label for="termin"><b>Terminwunsch:</b></label></td>
    <td style=""><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['AS']['terminwunsch'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>
</td>
  </tr>
  <tr>
      <td style=""><label for="von_gebaeude_text"><b>Von:</b></label></td>
    <td style=""><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['von_gebaeude_text'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>
  <tr>
      <td style=""><label for="nach_gebaeude_text"><b>Nach:</b></label></td>
    <td style=""><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['nach_gebaeude_text'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>
	<!-- SelBox_initLSDefault(initInputObj, initLSName, initMultiple, initLSSearchFields, initLSInputFields) -->
</table>
<br>
<h2 style="margin:6px 0;">Ansprechpartner vor Ort</h2> 
<table style="width:480px">
  <tr>
      <td style="width:160px"><label for="ansprechpartner"><b>Vor &amp; Nachname:</b></label></td>
    <td style="width:220px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['ansprechpartner'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>
  <tr>
    <td style=""><label for="termin"><b>E-Mail:</b></label></td>
    <td style=""><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['ansprechpartner_email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>
  <tr>
    <td style=""><label for="termin"><b>Fon:</b></label></td>
    <td style=""><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['ansprechpartner_fon'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>
</table>
</div>
<br>

<div>

<?php if (0): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "umzugsblatt_mitarbeiterauswahl.tpl.html", 'smarty_include_vars' => array()));
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
<?php endif; ?><?php if (1): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "umzugsblatt_leistungsauswahl.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

</div>
</div>

<?php if ($this->_tpl_vars['UmzugsAnlagen']): ?>
<ul class="ulAttachements">
<strong>Dateianhänge</strong><br>
<?php $_from = $this->_tpl_vars['UmzugsAnlagen']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['ATList'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['ATList']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['AT']):
        $this->_foreach['ATList']['iteration']++;
?>
<li><a href="<?php echo $this->_tpl_vars['AT']['datei_link']; ?>
" target="_blank"><?php echo $this->_tpl_vars['AT']['dok_datei']; ?>
</a> <?php echo $this->_tpl_vars['AT']['datei_groesse']; ?>
</li>
<?php endforeach; endif; unset($_from); ?>
</ul>
<?php else: ?>
<strong>Dateianhänge: </strong><em>Keine</em><br>
<?php endif; ?>
<br>
<h2 style="margin:6px 0">Bemerkungen</h2>
<div style="border:1px solid #888;height:100px;padding:"></div>
<br>
<?php if ($this->_tpl_vars['AS']['bemerkungen']): ?>
<div id="BoxBemerkungenHistorie" style="">
<h2 style="margin:6px 0">Bisherige Bemerkungen</h2>
<div id="BemerkungenHistorie" style="border:1px solid #888;min-height:20px;padding:8px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['bemerkungen'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</div>
</div>
</div>
<?php endif; ?>
<div style="height:60px;margin-top:70px;">
    <div style="width:100%;margin-top:25px;">
        <div style="width:40%;float:left;">
        <br>
        <hr style="border:1px dotted #000;">
        Unterschrift Datum/Dienstleister</div>
    </div>
    <div style="width:100%;margin-top:25px;">
        <div style="width:40%;float:right;">
        <br>
        <hr style="border:1px dotted #000;">
        Unterschrift Datum/Auftraggeber</div>
    </div>
</div>
</body>
</html>