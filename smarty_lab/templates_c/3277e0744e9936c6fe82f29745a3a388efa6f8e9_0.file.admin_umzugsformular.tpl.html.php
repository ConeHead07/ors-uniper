<?php
/* Smarty version 3.1.34-dev-7, created on 2021-09-22 12:41:09
  from '/var/www/html/html/admin_umzugsformular.tpl.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_614b24658f84e7_32253124',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3277e0744e9936c6fe82f29745a3a388efa6f8e9' => 
    array (
      0 => '/var/www/html/html/admin_umzugsformular.tpl.html',
      1 => 1632308430,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:admin_umzugsformular_lieferauswahl.tpl.html' => 1,
    'file:admin_umzugsformular_mitarbeiterauswahl.tpl.html' => 1,
    'file:admin_umzugsformular_geraeteauswahl.tpl.html' => 1,
    'file:admin_umzugsformular_ortsauswahl.tpl.html' => 1,
    'file:admin_umzugsformular_leistungsauswahl.tpl.html' => 1,
    'file:umzugsformular_attachments.tpl.read.html' => 1,
    'file:umzugsformular_attachments.tpl.read2.html' => 1,
    'file:admin_umzugsformular_gruppierung.tpl.html' => 1,
  ),
),false)) {
function content_614b24658f84e7_32253124 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/smarty3/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>

<link href="{WebRoot}css/SelBox.easy.css" rel="STYLESHEET" type="text/css" />
<?php echo '<script'; ?>
 src="{WebRoot}js/FbAjaxUpdater.js" type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="{WebRoot}js/PageInfo.js" type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="{WebRoot}js/ObjectHandler.js" type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="{WebRoot}js/SelBox.easy.js" type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="{WebRoot}js/umzugsformular.easy.js?lm=20101021" type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="{WebRoot}js/geraeteumzug.easy.js?lm=20101021" type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="{WebRoot}js/dienstleister.js?lm=20101021" type="text/javascript"><?php echo '</script'; ?>
>

<?php echo '<script'; ?>
>
  
optionsUmzugsarten.push({value:"Datenpflege", content:"Datenpflege"});

<?php echo '</script'; ?>
>
admin_umzugsformular.tpl.html
<div id="SysInfoBox"></div>

<link rel="stylesheet" type="text/css" href="css/umzugsformular.css">
<!-- MODUL UEBERSCHRIFTENBOX 109099 BEGIN --> 
<div class="divModuleBasic padding6px width5Col heightAuto colorContentMain"> 
<h1><span class="spanTitle">Leistungsanforderung #<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
</span></h1> 
<p>
<div id="Umzugsantrag" class="divInlay"> 
<form action="umzugsantrag_speichern.php" name="frmUmzugsantrag" method="post" style="margin:0;padding:0;display:inline;">
<h2 style="margin:0;">Auftrags-Status</h2>
Angeboten: <?php echo $_smarty_tpl->tpl_vars['AS']->value['angeboten_am'];?>
 von <?php echo $_smarty_tpl->tpl_vars['AS']->value['angeboten_von'];?>

<table border=1 cellspacing=1 cellpadding=1>
  <tr>
    <td style="padding:0;height:auto;width:auto;"><label for="termin" style="width:180px;">Umzugstermin:</label></td>
    <td style="padding:0;width:250px;"><input id="termin" type="text" value='<?php echo smarty_modifier_date_format(htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['umzugstermin'], ENT_QUOTES, 'ISO-8859-1', true),"%d.%m.%Y");?>
'
    onfocus="showDtPicker(this)" id="umzugstermin" name="AS[umzugstermin]" class="itxt itxt2col"></td>
  </tr>
  <tr>
    <td style="padding:0;height:auto;width:auto;"><label for="AS[Umzugszeit]" style="width:180px;">Uhrzeit:</label></td>
    <td style="padding:0;width:250px;"><input id="AS[Umzugszeit]" type="text" value='<?php echo substr(htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['umzugszeit'], ENT_QUOTES, 'ISO-8859-1', true),0,5);?>
'
    id="umzugszeit" name="AS[umzugszeit]" class="itxt itxt2col"></td>
  </tr>
  <tr>
    <td style="padding:0;height:auto;width:auto;"><label style="width:180px;">Antragsdatum:</label></td>
    <td style="padding:0;width:auto;"><?php echo smarty_modifier_date_format(htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['antragsdatum'], ENT_QUOTES, 'ISO-8859-1', true),"%d.%m.%Y");?>
</td>
  </tr>
  <tr>
    <td style="padding:0;width:auto;"><label style="width:180px;"><?php if ($_smarty_tpl->tpl_vars['AS']->value['angeboten_am']) {?>Genehmigt<?php } else { ?>Best&auml;tigt<?php }?>:</label></td>
    <td style="padding:0;width:auto;" class="status_<?php echo $_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'];?>
"><img id="imgStatGen" src="images/status_<?php echo mb_strtolower($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'], 'ISO-8859-1');?>
.png"><span id="txtStatGen"><?php if ($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'] != "Init") {?> <?php echo $_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'];?>
 am <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br_am'],"%d.%m.%Y %H:%M");?>
 <?php echo $_smarty_tpl->tpl_vars['AS']->value['genehmigt_br_von'];
}?></span></td>
  </tr>
  <tr>
    <td style="padding:0;width:auto;"><label style="width:180px;">Abgeschlossen:</label></td>
    <td style="padding:0;width:auto;" class="status_<?php echo $_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'];?>
"><img id="imgStatAbg" src="images/status_<?php echo mb_strtolower($_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'], 'ISO-8859-1');?>
.png"><span id="txtStatAbg"><?php if ($_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'] != "Init") {?> <?php echo $_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'];?>
 am <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['AS']->value['abgeschlossen_am'],"%d.%m.%Y %H:%M");?>
 <?php echo $_smarty_tpl->tpl_vars['AS']->value['abgeschlossen_von'];
}?></span></td>
  </tr>
  <tr>
    <td style="padding:0;"><label style="width:180px;">Status:</label></td>
    <td style="padding:0;"><?php if (empty($_smarty_tpl->tpl_vars['AS']->value['angeboten_am']) && htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'], ENT_QUOTES, 'ISO-8859-1', true) == "genehmigt") {?>bestaetigt<?php } else {
echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'], ENT_QUOTES, 'ISO-8859-1', true);
}?></td>
  </tr>
	<!-- SelBox_initLSDefault(initInputObj, initLSName, initMultiple, initLSSearchFields, initLSInputFields) -->
</table>
<a href="%WebRoot%sites/umzugsblatt.php?id=<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
" target="_Umzugsblatt<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
"><img src="%WebRoot%images/printer.png" width="16" height="16" alt="">Umzugsblatt / Druckansicht</a>
<br>

<div style="float:left">
<h2 style="margin:0;">Antragsteller</h2>
<input type="hidden" name="AS[aid]" value="<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
">
<input type="hidden" name="AS[token]" value="<?php echo $_smarty_tpl->tpl_vars['AS']->value['token'];?>
">
<table>
  <tr>
      <td style="padding:0;width:200px;"><label for="mitarbeiter" style="width:180px;">Vor<?php if ($_smarty_tpl->tpl_vars['ASConf']->value['vorname']['required']) {?><span class="required">*</span><?php }?> &amp; Nachname<span class="right"><?php if ($_smarty_tpl->tpl_vars['ASConf']->value['name']['required']) {?><span class="required">*</span><?php }?></span></label></td>
    <td style="padding:0;width:250px;"><input type="text" readonly="true" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['vorname'], ENT_QUOTES, 'ISO-8859-1', true);?>
" name="AS[vorname]" class="itxt itxt1col floatLeft"><input type="text" readonly="true" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['name'], ENT_QUOTES, 'ISO-8859-1', true);?>
" name="AS[name]" class="itxt itxt1col floatRight" title="Name"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="email" style="width:180px;">E-Mail<span class="right"><?php if ($_smarty_tpl->tpl_vars['ASConf']->value['email']['required']) {?><span class="required">*</span><?php }?></span></label></td>
    <td style="padding:0;"><input id="email" type="text" readonly="true" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['email'], ENT_QUOTES, 'ISO-8859-1', true);?>
" name="AS[email]" class="itxt itxt2col" title="E-Mail"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="fon" style="width:180px;">Telefon<span class="right"><?php if ($_smarty_tpl->tpl_vars['ASConf']->value['fon']['required']) {?><span class="required">*</span><?php }?></span></label></td>
    <td style="padding:0;"><input id="fon" type="text" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['fon'], ENT_QUOTES, 'ISO-8859-1', true);?>
" name="AS[fon]" class="itxt itxt2col jtooltip" rel="%WebRoot%hilfetexte/antrag_fon.php"></td>
  </tr>
</table>

  <h2 style="margin:0;">Lieferadresse</h2>
  <table>
  <tr>
    <td style="padding:0;width:200px;">
      <label for="as_strasse" style="display:block;width:auto;">Stra&szlig;e<span class="right"><?php if ($_smarty_tpl->tpl_vars['ASConf']->value['strasse']['required']) {?><span class="required">*</span><?php }?></span></label></td>
    <td style="padding:0;width:250px;"><input type="text" id="as_strasse" autocomplete="off" xreadonly="true" <?php if ($_smarty_tpl->tpl_vars['ASConf']->value['strasse']['required']) {?>required="required"<?php }?> value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['strasse'], ENT_QUOTES, 'ISO-8859-1', true);?>
" name="AS[strasse]" class="itxt itxt2col"></td>
  </tr>

  <tr>
    <td style="padding:0;">
      <label for="as_plz" style="display:block;width:auto;">PLZ<span class="right"><?php if ($_smarty_tpl->tpl_vars['ASConf']->value['plz']['required']) {?><span class="required">*</span><?php }?></span></label></td>
    <td style="padding:0;"><input type="text" id="as_plz" autocomplete="postal code" xreadonly="true" <?php if ($_smarty_tpl->tpl_vars['ASConf']->value['plz']['required']) {?>required="required"<?php }?> value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['plz'], ENT_QUOTES, 'ISO-8859-1', true);?>
" name="AS[plz]" class="itxt itxt2col"></td>
  </tr>

  <tr>
    <td style="padding:0;"><label for="ort" style="width:180px;"><?php echo $_smarty_tpl->tpl_vars['ASConf']->value['ort']['label'];?>
<span class="right"><?php if ($_smarty_tpl->tpl_vars['ASConf']->value['ort']['required']) {?><span class="required">*</span><?php }?></span></label></td>
    <td style="padding:0;"><input id="ort" type="text" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['ort'], ENT_QUOTES, 'ISO-8859-1', true);?>
" name="AS[ort]" class="itxt itxt2col"></td>
  </tr>
</table>
<br>
<h2 style="margin:0;">Abweichender Ansprechpartner vor Ort</h2>
<table>
  <tr>
    <td style="padding:0;"><label for="ansprechpartner" style="width:180px;">Vor &amp; Nachname:</label></td>
    <td style="padding:0;width:250px;"><input id="ansprechpartner" type="text" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['ansprechpartner'], ENT_QUOTES, 'ISO-8859-1', true);?>
" name="AS[ansprechpartner]" class="itxt itxt2col" title="Ansprechpartner"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="ansprechpartner_email" style="width:180px;">E-Mail:</label></td>
    <td style="padding:0;"><input id="ansprechpartner_email" type="text" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['ansprechpartner_email'], ENT_QUOTES, 'ISO-8859-1', true);?>
" name="AS[ansprechpartner_email]" class="itxt itxt2col" title="Ansprechpartner E-Mail"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="ansprechpartner_fon" style="width:180px;">Telefon:</label></td>
    <td style="padding:0;"><input id="ansprechpartner_fon" type="text" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['ansprechpartner_fon'], ENT_QUOTES, 'ISO-8859-1', true);?>
" name="AS[ansprechpartner_fon]" class="itxt itxt2col jtooltip" rel="%WebRoot%hilfetexte/antrag_fon.php"></td>
  </tr>
</table>

<table>
  <tr>
    <td style="padding:0;width:180px;"><label style="display:block;width:auto;"><?php echo $_smarty_tpl->tpl_vars['ASConf']->value['umzug']['label'];?>
</label></td>
    <td style="padding:0;width:300px;" class="options-onoff"><label class='<?php if ($_smarty_tpl->tpl_vars['AS']->value['umzug'] == "Ja") {?>on<?php } else { ?>off<?php }?> active'><?php echo $_smarty_tpl->tpl_vars['AS']->value['umzug'];?>
</label></td>
  </tr>
</table>
</div>

<?php if (1) {?>
<div style="float:left; margin-left:50px;">
    <?php $_smarty_tpl->_subTemplateRender("file:admin_umzugsformular_lieferauswahl.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
</div>
<?php }?>
<br clear= "all">
<div style="clear:all;"></div>

<?php if (0) {?>
    <?php $_smarty_tpl->_subTemplateRender("file:admin_umzugsformular_mitarbeiterauswahl.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
if (0) {?>
    <?php $_smarty_tpl->_subTemplateRender("file:admin_umzugsformular_geraeteauswahl.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
if (0) {?>
    <?php $_smarty_tpl->_subTemplateRender("file:admin_umzugsformular_ortsauswahl.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}?>

<?php if (1) {
$_smarty_tpl->_subTemplateRender("file:admin_umzugsformular_leistungsauswahl.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}?>

<?php $_smarty_tpl->_subTemplateRender("file:umzugsformular_attachments.tpl.read.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php if (!empty($_smarty_tpl->tpl_vars['UmzugsAnlagenIntern']->value)) {
$_smarty_tpl->_subTemplateRender("file:umzugsformular_attachments.tpl.read2.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('UmzugsAnlagen'=>$_smarty_tpl->tpl_vars['UmzugsAnlagenIntern']->value,'internal'=>1), 0, false);
}?>
<br>
<?php if (1) {?>
<div style="width:100%">
    <?php $_smarty_tpl->_subTemplateRender("file:admin_umzugsformular_gruppierung.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
</div>
<?php }?>
<!-- <div style="color:#549e1a;font-weight:bold;text-decoration:none;cursor:pointer;" onclick="addMa();return false;">Weiteren Mitarbeiter ausw&auml;hlen <img align="absmiddle" src="images/hinzufuegen_off.png" width="14" alt=""></div><br> -->
<br>

    <div id="BoxBemerkungen">
        <strong>Bemerkungen:</strong><br>
        <textarea class="iarea bemerkungen" name="AS[bemerkungen]"></textarea>
    </div>

    <div id="BoxLieferhinweise">
        <strong>Lieferinweis (für Lieferschein):</strong><br>
        <textarea class="iarea bemerkungen lieferhinweise" name="AS[lieferhinweise]"></textarea>
    </div>

<div style="margin-top:20px;width:100%;"><!-- 
 --><input type="submit" name="CatchDefaultEnterReturnFalse" onclick="return false;" value="" style="display:none;border:0;background:#fff;color:#fff;position:relative;left:-500px;"><!-- 
 --><input type="submit" class="btn grey" onclick="umzugsantrag_save()" xstyle="padding:0 0 9px 0;background:url(images/BtnGrey.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Speichern"><!-- 
 --><input type="submit" class="btn red" onclick="umzugsantrag_reload()" xstyle="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Neu laden"><!-- 
 --><input type="submit" class="btn red" onclick="umzugsantrag_add_attachement()" xstyle="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Dateianhänge">
<?php if ($_smarty_tpl->tpl_vars['creator']->value == "mertens") {?><input type="submit" class="btn red" onclick="umzugsantrag_add_internal_attachement()" xstyle="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Interne Dateianhänge"><?php }?>
<br>
<br>
<?php if ($_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'] != "temp") {?>
<strong>Status setzen: </strong> 
<div class="statusConsole">
<?php if ($_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'] != "angeboten") {
if ($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'] == 'Init') {?>
<input id="btnStatGeprBackToUser" type="submit" class="<?php if ($_smarty_tpl->tpl_vars['AS']->value['geprueft'] == 'Ja' || $_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'] != 'Init' || $_smarty_tpl->tpl_vars['AS']->value['bestaetigt'] != 'Init') {?>cssHide<?php } else { ?>btn blue<?php }?>" onclick="umzugsantrag_set_status('zurueckgeben','Ja')" xstyle="padding:0 0 9px 0;background:url(images/BtnBlue_160.png) bottom left no-repeat;border:0;width:160px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Zurueckgeben">
<?php if ($_smarty_tpl->tpl_vars['AS']->value['umzug'] == "Ja") {?>
<input id="btnStatGenJa" type="submit" class="{ if ($AS.genehmigt_br eq 'Ja' || $AS.bestaetigt ne 'Init' || $AS.geprueft ne 'Init') && $AS.umzugsstatus ne 'erneutpruefen'}cssHide<?php } else { ?>btn green<?php }?>" onclick="umzugsantrag_set_status('geprueft','Ja')" xstyle="padding:0 0 9px 0;background:url(images/BtnGreen.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" title="Senden als Geprüft" value="Senden">
<?php } else { ?>
<input id="btnStatGenJa" type="submit" class="btn green" <?php if ($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'] == 'Ja' || $_smarty_tpl->tpl_vars['AS']->value['bestaetigt'] != 'Init') {?>class="cssHide"<?php }?> onclick="umzugsantrag_set_status('genehmigt','Ja')" xstyle="padding:0 0 9px 0;background:url(images/BtnGreen.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" title="Bestätigung senden" value="Senden">
<?php }
if (0) {?><input id="btnStatGenNein" type="submit" class="btn red" <?php if ($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'] == 'Nein' || $_smarty_tpl->tpl_vars['AS']->value['bestaetigt'] != 'Init') {?>class="cssHide"<?php }?> onclick="umzugsantrag_set_status('genehmigt','Nein')" xstyle="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Ablehnen"><?php }?>
&nbsp;<?php }
if ($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'] == 'Ja') {?>
<input id="btnStatGenReset" type="submit" class=<?php if ($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'] == 'Init' || $_smarty_tpl->tpl_vars['AS']->value['bestaetigt'] != 'Init') {?>cssHide<?php } else { ?>btn blue<?php }?>" onclick="umzugsantrag_set_status('genehmigt','Init')" xstyle="padding:0 0 9px 0;background:url(images/BtnBlue_160.png) bottom left no-repeat;border:0;width:160px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Genehmigung aufheben">
&nbsp;<?php }
if ($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'] == 'Ja' || $_smarty_tpl->tpl_vars['AS']->value['bestaetigt'] == 'Ja') {?>
<input id="btnStatAbgJa" type="submit" class="btn green" <?php if ($_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'] != 'Init') {?>class="cssHide"<?php }?> onclick="umzugsantrag_set_status('abgeschlossen','Ja')" xstyle="padding:0 0 9px 0;background:url(images/BtnGreen_160.png) bottom left no-repeat;border:0;width:160px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Umzug ist abgeschlossen">
&nbsp;<?php }?>
<input id="btnStatAbgReset" type="submit" class="<?php if ($_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'] == 'Init') {?>cssHide<?php } else { ?>btn blue<?php }?>" onclick="umzugsantrag_set_status('abgeschlossen','Init')"      xstyle="padding:0 0 9px 0;background:url(images/BtnBlue_160.png) bottom left no-repeat;border:0;width:160px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Abschluss aufheben">
<input id="btnStatAbgStorno" type="submit" class="<?php if ($_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'] != 'Init') {?>cssHide<?php } else { ?>btn red<?php }?>" onclick="umzugsantrag_set_status('abgeschlossen','Storniert')" xstyle="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Stornieren"></div>
<?php }?>
</div>

<?php if ($_smarty_tpl->tpl_vars['AS']->value['umzug'] == "Nein") {?>Antrag ist ohne Umzug und kann direkt ausgeführt werden
<?php } else { ?>Antrag ist mit Umzug und erfordert die Genehmigung durch Property
<?php }?>
<!-- Debug-Btn:
<input type="submit" onclick="return umzugsantrag_submit_debug('speichern')" style="padding:0 0 9px 0;background:url(images/BtnGrey.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" name="cmd" value="speichern">
<input type="submit" onclick="return umzugsantrag_submit_debug('senden')" style="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" name="cmd" value="senden">
<input type="submit" onclick="return umzugsantrag_submit_debug('stornieren')" style="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" name="cmd" value="stornieren">
<input type="submit" onclick="return umzugsantrag_submit_debug('laden')" style="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" name="cmd" value="laden">
 -->
<div id="BoxBemerkungenHistorie">
<strong>Bisherige Bemerkungen</strong><br>
<div id="BemerkungenHistorie"><?php echo nl2br($_smarty_tpl->tpl_vars['AS']->value['bemerkungen']);?>
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
  <a href="" onclick="show_raum_mitarbeiter(this,'');return false;">[Raum-Alt]</a> <span onclick="dropMA(this)" style="cursor:pointer;">Aus Umzugsliste löschen <img align="absmiddle" src="images/loeschen_off.png" width="14" alt=""><span></div>
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
	 --><input value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['name'], ENT_QUOTES, 'ISO-8859-1', true);?>
" id="mitarbeiter"  xonclick="get_mitarbeiter(this)"  class="AutoFill UpperCase" type="text" name="MA[name][]"></td>
    <td class="vn"><input value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['vorname'], ENT_QUOTES, 'ISO-8859-1', true);?>
" class="itxt itxt1col UpperCase AutoFill" type="text" name="MA[vorname][]"></td>
	<td class="xf"><input value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['extern_firma'], ENT_QUOTES, 'ISO-8859-1', true);?>
" onclick="get_extern_firma(this)" class="itxt itxt1col AutoFill" size=15 type="text" name="MA[extern_firma][]"></td>
	
	<td class="abt"><input value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['abteilung'], ENT_QUOTES, 'ISO-8859-1', true);?>
" readonly="true" onclick="get_abteilung(this)" class="itxt AutoFill" size=4 type="text" name="MA[abteilung][]"></td>
	<td class="geb"><input  autocomplete="off" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['gebaeude'], ENT_QUOTES, 'ISO-8859-1', true);?>
" readonly="true" onclick="get_gebaeude(this)" class="itxt itxt1col AutoFill" type="text" name="MA[gebaeude][]"></td>
    <td class="etg"><input value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['etage'], ENT_QUOTES, 'ISO-8859-1', true);?>
" onclick="get_etage(this)" readonly="true" class="itxt AutoFill" type="text" name="MA[etage][]"></td>
    <td class="rnr"><input value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['raumnr'], ENT_QUOTES, 'ISO-8859-1', true);?>
" onclick="get_raumnr(this)" readonly="true" class="itxt AutoFill" size=8 type="text" name="MA[raumnr][]"></td>
    <td class="apnr"><input value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['apnr'], ENT_QUOTES, 'ISO-8859-1', true);?>
" onclick="get_apnr(this)" class="itxt AutoFill" readonly="true" size=8 type="text" name="MA[apnr][]"></td>
    
    <td class="fon"><input value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['fon'], ENT_QUOTES, 'ISO-8859-1', true);?>
" class="itxt" type="text" name="MA[fon][]"></td>
    <td class="pcnr"><input value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['pcnr'], ENT_QUOTES, 'ISO-8859-1', true);?>
" class="itxt" type="text" name="MA[pcnr][]"></td>
    <td class="festeip"><input value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['festeip'], ENT_QUOTES, 'ISO-8859-1', true);?>
" class="itxt" type="text" name="MA[festeip][]"></td>
</tr>
  <tr class="inputRowZiel">
    <td class="ziel" align=right>Anforderungsart</td>
	<td class="uart"><input value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['umzugsart'], ENT_QUOTES, 'ISO-8859-1', true);?>
" class="UserInput" readonly="true" onclick="get_umzugsart(this)" type="text" name="MA[umzugsart][]"></td>
    <td class="ziel" align=right> Nach:</td>
    <td class="etg"><input value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['zabteilung'], ENT_QUOTES, 'ISO-8859-1', true);?>
" readonly="true" onclick="get_abteilung(this)" class="itxt UserInput" type="text" name="MA[zabteilung][]"></td>
	<td class="zort"><input value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['zgebaeude'], ENT_QUOTES, 'ISO-8859-1', true);?>
" readonly="true" onclick="get_gebaeude(this)" class="itxt UserInput" type="text" name="MA[zgebaeude][]"></td>
    <td class="geb"><input value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['zetage'], ENT_QUOTES, 'ISO-8859-1', true);?>
" readonly="true" onclick="get_etage(this)" class="itxt UserInput" type="text" name="MA[zetage][]"></td>
    <td class="rnr"><input value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['zraumnr'], ENT_QUOTES, 'ISO-8859-1', true);?>
" readonly="true" onclick="get_raumnr(this)" class="itxt UserInput" type="text" name="MA[zraumnr][]"></td>
    <td class="rnr"><input value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['zapnr'], ENT_QUOTES, 'ISO-8859-1', true);?>
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
	 --><input value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['name'], ENT_QUOTES, 'ISO-8859-1', true);?>
" id="mitarbeiter" xonclick="get_mitarbeiter(this)"  class="itxt itxt1col UpperCase UserInput" type="text" name="MA[name][]"></td>
    <td class="vn"><input value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['vorname'], ENT_QUOTES, 'ISO-8859-1', true);?>
" class="itxt itxt1col UpperCase UserInput" type="text" name="MA[vorname][]"></td>
	<td class="xf"><input value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['extern_firma'], ENT_QUOTES, 'ISO-8859-1', true);?>
" onclick="get_extern_firma(this)" class="itxt itxt1col UserInput" size=15 type="text" name="MA[extern_firma][]"></td>
	
	<td class="abt"><input value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['abteilung'], ENT_QUOTES, 'ISO-8859-1', true);?>
" readonly="true" onclick="get_abteilung(this)" class="itxt UserInput" size=4 type="text" name="MA[abteilung][]"></td>
	<td class="geb"><input  autocomplete="off" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['gebaeude'], ENT_QUOTES, 'ISO-8859-1', true);?>
" readonly="true" onclick="get_gebaeude(this)" class="itxt itxt1col UserInput" type="text" name="MA[gebaeude][]"></td>
    <td class="etg"><input value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['etage'], ENT_QUOTES, 'ISO-8859-1', true);?>
" onclick="get_etage(this)" readonly="true" class="itxt UserInput" type="text" name="MA[etage][]"></td>
    <td class="rnr"><input value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['raumnr'], ENT_QUOTES, 'ISO-8859-1', true);?>
" onclick="get_raumnr(this)" readonly="true" class="itxt UserInput" size=8 type="text" name="MA[raumnr][]"></td>
    <td class="apnr"><input value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['apnr'], ENT_QUOTES, 'ISO-8859-1', true);?>
" class="itxt UserInput" readonly="true" size=8 type="text" name="MA[apnr][]"></td>
    
    <td class="fon"><input value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['fon'], ENT_QUOTES, 'ISO-8859-1', true);?>
" class="itxt" type="text" name="MA[fon][]"></td>
    <td class="pcnr"><input value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['pcnr'], ENT_QUOTES, 'ISO-8859-1', true);?>
" class="itxt" type="text" name="MA[pcnr][]"></td>
    <td class="festeip"><input value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['festeip'], ENT_QUOTES, 'ISO-8859-1', true);?>
" class="itxt" type="text" name="MA[festeip][]"></td>
	</tr>
  <tr class="inputRowZiel">
    <td class="ziel" align=right>Anforderungsart</td>
	<td class="uart"><input value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['umzugsart'], ENT_QUOTES, 'ISO-8859-1', true);?>
" class="UserInput" readonly="true" onclick="get_umzugsart(this)" type="text" name="MA[umzugsart][]"></td>
    <td class="ziel" align=right> Nach:</td>
    <td class="abt"><input value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['zabteilung'], ENT_QUOTES, 'ISO-8859-1', true);?>
" readonly="true" onclick="get_abteilung(this)" class="itxt UserInput" type="text" name="MA[zabteilung][]"></td>
	<td class="geb"><input value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['zgebaeude'], ENT_QUOTES, 'ISO-8859-1', true);?>
" readonly="true" onclick="get_gebaeude(this)" class="itxt UserInput" type="text" name="MA[zgebaeude][]"></td>
    <td class="etg"><input value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['zetage'], ENT_QUOTES, 'ISO-8859-1', true);?>
" readonly="true" onclick="get_etage(this)" class="itxt UserInput" type="text" name="MA[zetage][]"></td>
    <td class="rnr"><input value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['zraumnr'], ENT_QUOTES, 'ISO-8859-1', true);?>
" readonly="true" onclick="get_raumnr(this)" class="itxt UserInput" type="text" name="MA[zraumnr][]"></td>
    <td class="apnr"><input value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['zapnr'], ENT_QUOTES, 'ISO-8859-1', true);?>
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
<div class="SelItem"><input type="checkbox" name="uartbox" value="Mit M?bel" checked=1> Mit <strong>M&ouml;bel</strong></div>
</div>
</div>
<?php }
}
