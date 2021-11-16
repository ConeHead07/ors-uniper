<?php
/* Smarty version 3.1.34-dev-7, created on 2021-11-11 09:38:04
  from '/var/www/html/html/umzugsformular.tpl.read.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_618ce47cde4024_96831690',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '70dca96a2d731a19c2923c16f02feb0ec13717c9' => 
    array (
      0 => '/var/www/html/html/umzugsformular.tpl.read.html',
      1 => 1636623481,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:umzugsformular_mitarbeiterauswahl.tpl.read.html' => 1,
    'file:umzugsformular_geraeteauswahl.tpl.read.html' => 1,
    'file:umzugsformular_leistungsauswahl.tpl.read.html' => 1,
    'file:umzugsformular_attachments.tpl.read.html' => 1,
  ),
),false)) {
function content_618ce47cde4024_96831690 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/smarty3/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<link rel="STYLESHEET" type="text/css" href="../css/SelBox.easy.css">
<link rel="STYLESHEET" type="text/css" href="css/SelBox.easy.css">

<div id="SysInfoBox"></div>

<link rel="stylesheet" type="text/css" href="css/umzugsformular.css">
<link rel="stylesheet" type="text/css" href="../css/umzugsformular.css">
<!-- MODUL UEBERSCHRIFTENBOX 109099 BEGIN --> 
<div class="divModuleBasic padding6px width5Col heightAuto colorContentMain"> 
<h1><span class="spanTitle">Auftrag</span></h1>
<p>
<div id="Umzugsantrag" class="divInlay" data-site="umzugsformular/tpl/read/html">
  <h2 style="margin:0;">Auftragsstatus</h2>
  <table>
    <tr>
      <td style="padding:0;width:200px;"><label style="display:block;width:auto;">Auftrags-ID:</label></td>
      <td style="padding:0;width:250px;"><div class="itxt itxt2col ireadonly"><?php echo str_pad($_smarty_tpl->tpl_vars['AS']->value['aid'],8,"0",0);?>
</div></td>
    </tr>
    <tr>
      <td style="padding:0;width:200px;"><label style="display:block;width:auto;">Auftragsdatum:</label></td>
      <td style="padding:0;width:250px;"><div class="itxt itxt2col ireadonly"><?php echo smarty_modifier_date_format(htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['antragsdatum'], ENT_QUOTES, 'UTF-8', true),"%d.%m.%Y");?>
</div></td>
    </tr>
    <tr>
      <td style="padding:0;width:200px;"><label style="display:block;width:auto;">Auftragsstatus:</label></td>
      <td style="padding:0;width:250px;"><div class="itxt itxt2col ireadonly"><?php echo $_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'];?>
</div></td>
    </tr>
    <tr>
      <td style="padding:0;width:200px;" valign="top"><label style="display:block;width:auto;">Bisherige Bemerkungen:</label></td>
      <td style="padding:0;"><?php if (empty($_smarty_tpl->tpl_vars['AS']->value['bemerkungen'])) {?><div class="itxt itxt2col ireadonly"><i>Keine</i></div><?php }?></td>
    </tr>
  </table>
  <?php if (!empty($_smarty_tpl->tpl_vars['AS']->value['bemerkungen'])) {?>
  <table width="100%">
    <tr>
      <td style="padding:0;">
        <div id="BemerkungenHistorie"><?php echo nl2br($_smarty_tpl->tpl_vars['AS']->value['bemerkungen']);?>
</div>
      </td>
    </tr>
  </table>
  <?php }?>

  <br>

  <h2 style="margin:0;">Lieferdaten</h2>
  <table>
  <tr>
    <td style="padding:0;width:200px;"><label style="background:#f00;border:0;display:block;width:auto;">Liefertermin:</label></td>
    <td style="padding:0;width:250px;"><div class="itxt itxt2col ireadonly"><?php echo smarty_modifier_date_format(htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['umzugstermin'], ENT_QUOTES, 'UTF-8', true),"%d.%m.%Y");?>
</div></td>
  </tr>
  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;">Vor &amp; Nachname:</label></td>
    <td style="padding:0;"><div class="itxt itxt2col ireadonly"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['vorname'], ENT_QUOTES, 'UTF-8', true);?>
 <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
</div></td>
  </tr>
  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;">E-Mail:</label></td>
    <td style="padding:0;"><div class="itxt itxt2col ireadonly"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['email'], ENT_QUOTES, 'UTF-8', true);?>
</div></td>
  </tr>
  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;">Telefon:</label></td>
    <td style="padding:0;"><div class="itxt itxt2col ireadonly"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['fon'], ENT_QUOTES, 'UTF-8', true);?>
</div></td>
  </tr>

  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;">Stra&szlig;e &amp; Nr:</label></td>
    <td style="padding:0;"><div class="itxt itxt2col ireadonly"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['strasse'], ENT_QUOTES, 'UTF-8', true);?>
</div></td>
  </tr>

  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;">PLZ:</label></td>
    <td style="padding:0;"><div class="itxt itxt2col ireadonly"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['plz'], ENT_QUOTES, 'UTF-8', true);?>
</div></td>
  </tr>

  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;">Ort:</label></td>
    <td style="padding:0;"><div class="itxt itxt2col ireadonly"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['ort'], ENT_QUOTES, 'UTF-8', true);?>
</div></td>
  </tr>
  <tr style="display:none;">
    <td style="padding:0;"><label style="display:block;width:auto;">Terminwunsch:</label></td>
    <td style="padding:0;"><div class="itxt itxt2col ireadonly"><?php echo smarty_modifier_date_format(htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['terminwunsch'], ENT_QUOTES, 'UTF-8', true),"%d.%m.%Y");?>
</div></td>
  </tr>
  <tr style="display:none;">
    <td style="padding:0;"><label style="display:block;width:auto;">Uhrzeit</label></td>
    <td style="padding:0;"><div class="itxt itxt2col ireadonly"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['umzugszeit'], ENT_QUOTES, 'UTF-8', true);?>
</div></td>
  </tr>
</table>
  <?php if ($_smarty_tpl->tpl_vars['AS']->value['ansprechpartner']) {?>
<br>
<h2 style="margin:0;">Abweichender Ansprechpartner vor Ort</h2>
<table>
  <tr>
    <td style="padding:0;width:200px;"><label style="display:block;width:auto;">Vor &amp; Nachname</label></td>
    <td style="padding:0;width:300px;"><div class="itxt itxt2col ireadonly"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['ansprechpartner'], ENT_QUOTES, 'UTF-8', true);?>
</div></td>
  </tr>
  <tr>
    <td style="padding:0;"><label style="display:block;width:auto;">Fon</label></td>
    <td style="padding:0;"><div class="itxt itxt2col ireadonly"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['ansprechpartner_fon'], ENT_QUOTES, 'UTF-8', true);?>
</div></td>
  </tr>
</table>
  <?php }?>

<br clear="both" >

<?php if (0) {?>
    <?php $_smarty_tpl->_subTemplateRender("file:umzugsformular_mitarbeiterauswahl.tpl.read.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
if (0) {?>
    <?php $_smarty_tpl->_subTemplateRender("file:umzugsformular_geraeteauswahl.tpl.read.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
if (1) {?>
    <?php $_smarty_tpl->_subTemplateRender("file:umzugsformular_leistungsauswahl.tpl.read.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}?>

<?php if (!empty($_smarty_tpl->tpl_vars['UmzugsAnlagen']->value)) {
$_smarty_tpl->_subTemplateRender("file:umzugsformular_attachments.tpl.read.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}?>

<form action="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
" method="post" name="frmUmzugsantrag">
<input type="hidden" name="AS[aid]" value="<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
">
<input type="hidden" name="AS[token]" value="<?php echo $_smarty_tpl->tpl_vars['AS']->value['token'];?>
">
<strong>Bemerkung hinzufügen:</strong><br>
<textarea class="iarea bemerkungen" name="AS[add_bemerkungen]"></textarea>
<br>

<input class='btn blue' type="submit" xstyle="padding:0 0 9px 0;background:url(images/BtnBlue_160.png) bottom left no-repeat;border:0;width:160px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Bemerkung hinzufügen">
</form>

<div id="LoadingBar"></div>

</p>
</div> 
<?php }
}
