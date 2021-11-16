<?php /* Smarty version 2.6.26, created on 2015-12-14 22:05:59
         compiled from kostenkalkulation.tpl.read.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'kostenkalkulation.tpl.read.html', 81, false),array('modifier', 'date_format', 'kostenkalkulation.tpl.read.html', 81, false),array('modifier', 'substr', 'kostenkalkulation.tpl.read.html', 85, false),)), $this); ?>
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

<style media="print,screen">
<?php echo '
@media print {
body {-webkit-print-color-adjust: exact;}
}
@page { size:landscape; }
*,
html * {
	font-family:Arial,sans-serif;
        font-size:12px;
}
table {
    border-bottom:1px solid #ddd;
    border-left:1px solid #ddd;
    border-collapse:collapse;
    border-color: #ddd;
}
table td {
    border-top:1px solid #ddd;
    border-right:1px solid #ddd;
    border-collapse:collapse;
    padding:3px;
    border-color: #ddd;
}
td label {
    margin-left:10px;
}
h2 {
    font-size:16px;
}
'; ?>

thead tr td,
thead tr th,
td.th,
td label <?php echo '{'; ?>

    background-color:#eee !important;
    background-attachment: url("<?php echo $this->_tpl_vars['MConf']['WebRoot']; ?>
themes/imgcolor.php?color=eee");
    color:#5d5d5d !important;
    -webkit-print-color-adjust: exact;
<?php echo '}'; ?>

</style>
</head>
<body onload="window.print()">
<table width="100%" border="0" style="border:0;margin-bottom:50px">
    <tr><td style="text-align:right;border:0;"><img src="../images/header_logo_mertenshenk_237x150.png" width="237" height="150" /></td></tr>
    <tr><td style="text-align:left;border:0;padding-top:40px;"><img src="../images/header_address_line_425x14.png" width="425" height="14" /></td></tr>
</table>
<div id="SysInfoBox"></div>

<!-- MODUL UEBERSCHRIFTENBOX 109099 BEGIN --> 
<div class="divModuleBasic padding6px width5Col heightAuto colorContentMain"> 

<h1 style="font-size:1.5em">Kostenkalkulation</h1>

<p>
<div>
<h2 style="margin:0;">Auftragsdaten</h2>
<table>
  <tr>
    <td style="width:140px" bgcolor="#eeeeee" class="th"><label for="ansprechpartner"><b>Ausf&uuml;hrungstermin</b></label></td>
    <td style="width:340px;"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['AS']['umzugstermin'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>
</td>
  </tr>
  <tr>
      <td class="th"><label for="umzugszeit"><b>Ausf&uuml;hrungszeit</b></label></td>
      <td><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['AS']['umzugszeit'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('substr', true, $_tmp, 0, 5) : substr($_tmp, 0, 5)); ?>
</td>
  </tr>
  <tr>
      <td class="th"><label for="termin"><b>Antragsdatum</b></label></td>
    <td><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['AS']['antragsdatum'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>
</td>
  </tr>
</table>    
<br>    
    
<h2 style="margin:0;">Leistungsantragsteller</h2> 
<input type="hidden" name="AS[aid]" value="<?php echo $this->_tpl_vars['AS']['aid']; ?>
">
<table>
  <tr>
    <td style="width:140px" class="th"><label for="mitarbeiter"><b>Vor &amp; Nachname</b></label></td>
    <td style="width:340px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['vorname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>
  <tr>
    <td style="" class="th"><label for="termin"><b>E-Mail</b></label></td>
    <td style=""><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>
  <tr>
    <td style="" class="th"><label for="termin"><b>Fon</b></label></td>
    <td style=""><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['fon'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>

  <tr>
    <td style="" class="th"><label for="ort"><b>Standort</b></label></td>
    <td style=""><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['ort'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>

  <tr>
    <td style="" class="th"><label for="gebaeude"><b>Wirtschaftseinheit</b></label></td>
    <td style=""><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['gebaeude_text'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>

  <tr>
    <td style="" class="th"><label for="kostenstelel"><b>PSP-Element</b></label></td>
    <td style=""><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['kostenstelle'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>
  
  <tr>
    <td style="" class="th"><label for="termin"><b>Terminwunsch</b></label></td>
    <td style=""><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['AS']['terminwunsch'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>
</td>
  </tr>
  <tr>
      <td style="" class="th"><label for="von_gebaeude_text"><b>Von</b></label></td>
    <td style=""><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['von_gebaeude_text'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>
  <tr>
      <td style="" class="th"><label for="nach_gebaeude_text"><b>Nach</b></label></td>
    <td style=""><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['nach_gebaeude_text'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  </tr>
	<!-- SelBox_initLSDefault(initInputObj, initLSName, initMultiple, initLSSearchFields, initLSInputFields) -->
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
$this->_smarty_include(array('smarty_include_tpl_file' => "umzugsblatt_leistungsauswahl2.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

</div>
</p>
</div>


<footer style="margin-top:100px;page-break-inside:avoid">
    <div style="display:block;color:#9d9d9c;font-family:Arial,Verdana,font-size:9px;line-height:145%;text-align:justify;">
    merTensHenk GmbH & Co. OHG<br>
    Stahlwerk Becker 8, D-47877 Willich, Tel. +49(0) 2154 4705 0, Fax +49(0) 2154 4705 40000, info@mertens-henk.de, www.mertens-henk.de,
    Stadt<wbr>spar<wbr>kasse D�sseldorf, IBAN DE80 3005 0110 1006 8653 70, BIC DUSSDEDDXXX, Steuer-Nr. 102/5748/1231, USt-IdNr. DE294456615, 
    Amts<wbr>gericht Krefeld HRA 6363, Gesell<wbr>schafter merTens AG, Henk Inter<wbr>national GmbH, Unsere AGBs finden Sie unter www.mertens-henk.de/agb
</div>
    
<div>
    <img src="../images/druckansicht_foot_848x90.png" style="display:none;margin-top:100px" width="848" height="90" />
</div>
</footer>
</body>
</html>
