<?php
/* Smarty version 3.1.34-dev-7, created on 2020-01-09 10:32:04
  from '/application/html/property_umzugsformular.tpl.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5e170124a12c16_36747487',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '467fb880df7e64cc0ef63966f85befac62e70d84' => 
    array (
      0 => '/application/html/property_umzugsformular.tpl.html',
      1 => 1577987866,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:property_umzugsformular_mitarbeiterauswahl.tpl.html' => 1,
    'file:property_umzugsformular_geraeteauswahl.tpl.html' => 1,
    'file:admin_umzugsformular_leistungsauswahl.tpl.html' => 1,
    'file:umzugsformular_attachments.tpl.read.html' => 1,
  ),
),false)) {
function content_5e170124a12c16_36747487 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/application/smarty3/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>

<?php echo '<script'; ?>
 src="{WebRoot}js/FbAjaxUpdater.js" type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="{WebRoot}js/PageInfo.js" type="text/javascript"><?php echo '</script'; ?>
>
<link rel="STYLESHEET" type="text/css" href="{WebRoot}css/SelBox.easy.css">
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

<div id="SysInfoBox"></div>

<link rel="stylesheet" type="text/css" href="css/umzugsformular.css">
<!-- MODUL UEBERSCHRIFTENBOX 109099 BEGIN --> 
<div class="divModuleBasic padding6px width5Col heightAuto colorContentMain"> 
<h1><span class="spanTitle">Leistungsanforderung #<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
</span></h1> 
<p>
<div id="Umzugsantrag" class="divInlay"> 
<form action="umzugsantrag_speichern.php" name="frmUmzugsantrag" method="post" style="margin:0;padding:0;display:inline;">
<input type="hidden" name="AS[token]" value="<?php echo $_smarty_tpl->tpl_vars['AS']->value['token'];?>
">
<h2 style="margin:0;">Genehmigungs-Status</h2>
<table border=0 cellspacing=1 cellpadding=1>
  <tr>
    <td style="padding:0;width:180px;"><label for="termin" style="display:block;width:auto;">Umzugstermin:</label></td>
    <td style="padding:0;width:250px;"><input type="text" value='<?php echo smarty_modifier_date_format(htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['umzugstermin'], ENT_QUOTES, 'UTF-8', true),"%d.%m.%Y");?>
'
	onfocus="showDtPicker(this)" id="umzugstermin" name="AS[umzugstermin]" class="itxt itxt2col"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="termin" style="display:block;width:auto;">Umzugszeit:</label></td>
    <td style="padding:0;"><input type="text" value='<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['umzugszeit'], ENT_QUOTES, 'UTF-8', true);?>
'
	id="umzugszeit" name="AS[umzugszeit]" class="itxt itxt2col"></td>
  </tr>  
  <tr>
    <td style="padding:0;"><label for="mitarbeiter" style="display:block;width:auto;"><?php if ($_smarty_tpl->tpl_vars['AS']->value['angeboten_am']) {?>Genehmigt<?php } else { ?>Best&auml;tigt<?php }?>:</label></td>
    <td style="padding:0;" class="status_<?php echo $_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'];?>
"><img id="imgStatGen" src="images/status_<?php echo mb_strtolower($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'], 'UTF-8');?>
.png"><span id="txtStatGen"><?php if ($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'] != "Init") {?> <?php echo $_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'];?>
 am <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br_am'],"%d.%m.%Y %H:%M");?>
 <?php echo $_smarty_tpl->tpl_vars['AS']->value['genehmigt_br_von'];
}?></span></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="mitarbeiter" style="display:block;width:auto;">Abgeschlossen:</label></td>
    <td style="padding:0;" class="status_<?php echo $_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'];?>
"><img id="imgStatAbg" src="images/status_<?php echo mb_strtolower($_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'], 'UTF-8');?>
.png"><span id="txtStatAbg"><?php if ($_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'] != "Init") {?> <?php echo $_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'];?>
 am <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['AS']->value['abgeschlossen_am'],"%d.%m.%Y %H:%M");?>
 <?php echo $_smarty_tpl->tpl_vars['AS']->value['abgeschlossen_von'];
}?></span></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="umzugsstatus" style="display:block;width:auto;">Status:</label></td>
    <td style="padding:0;"><?php if (empty($_smarty_tpl->tpl_vars['AS']->value['angeboten_am']) && htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'], ENT_QUOTES, 'UTF-8', true) == "genehmigt") {?>bestaetigt<?php } else {
echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'], ENT_QUOTES, 'UTF-8', true);
}?></td>
  </tr>
	<!-- SelBox_initLSDefault(initInputObj, initLSName, initMultiple, initLSSearchFields, initLSInputFields) -->
</table>
<a href="%WebRoot%sites/umzugsblatt.php?id=<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
&mode=property" target="_Umzugsblatt<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
"><img src="%WebRoot%images/printer.png" width="16" height="16" alt="">Umzugsblatt / Druckansicht</a>
<a href="%WebRoot%index.php?s=pantrag&id=<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
&export=csv" target="_blank">CSV-Export</a>
<br>

<div>
<h2 style="margin:0;">Leistungsantragsteller</h2> 
<input type="hidden" name="AS[aid]" value="<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
">
<table>
  <tr>
    <td style="padding:0;width:180px;"><label for="mitarbeiter" style="display:block;width:auto;">Vor &amp; Nachname:</label></td>
    <td style="padding:0;width:250px;"><input type="text" readonly="true" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['vorname'], ENT_QUOTES, 'UTF-8', true);?>
" name="AS[vorname]" class="itxt itxt1col floatLeft"><input type="text" readonly="true" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
" name="AS[name]" class="itxt itxt1col floatRight" title="Name"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="termin" style="display:block;width:auto;">E-Mail:</label></td>
    <td style="padding:0;"><input type="text" readonly="true" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['email'], ENT_QUOTES, 'UTF-8', true);?>
" name="AS[email]" class="itxt itxt2col" title="E-Mail"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="termin" style="display:block;width:auto;">Fon:</label></td>
    <td style="padding:0;"><input type="text" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['fon'], ENT_QUOTES, 'UTF-8', true);?>
" name="AS[fon]" class="itxt itxt2col jtooltip" rel="%WebRoot%hilfetexte/antrag_fon.php"></td>
  </tr>

  <tr>
    <td style="padding:0;"><label for="ort" style="display:block;width:auto;">Standort:</label></td>
    <td style="padding:0;"><input type="text" onclick="get_standort_ort(this)" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['ort'], ENT_QUOTES, 'UTF-8', true);?>
" name="AS[ort]" class="itxt itxt2col"></td>
  </tr>

  <tr>
    <td style="padding:0;"><label for="gebaeude" style="display:block;width:auto;">Wirtschaftseinheit:</label>
        <input type="hidden" id="gebaeude" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['gebaeude'], ENT_QUOTES, 'UTF-8', true);?>
" name="AS[gebaeude]"></td>
    <td style="padding:0;"><input type="text" onclick="get_standort_gebaeude(this, O('gebaeude'))" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['gebaeude_text'], ENT_QUOTES, 'UTF-8', true);?>
" name="AS[gebaeude_text]" class="itxt itxt2col"></td>
  </tr>

  <tr>
    <td style="padding:0;"><label for="etage" style="display:block;width:auto;"><?php echo $_smarty_tpl->tpl_vars['ASConf']->value['etage']['label'];?>
<span class="right"><?php if ($_smarty_tpl->tpl_vars['ASConf']->value['etage']['required']) {?><span class="required">*</span><?php }?></span></label>
        <input type="hidden" id="etage" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['etage'], ENT_QUOTES, 'UTF-8', true);?>
" <?php if ($_smarty_tpl->tpl_vars['ASConf']->value['etage']['required']) {?>required="required"<?php }?> name="AS[etage]">
    </td>
    <td style="padding:0;"><input type="text" readonly="true" <?php if ($_smarty_tpl->tpl_vars['ASConf']->value['etage']['required']) {?>required="required"<?php }?> id="ASEtageUsrInput" onclick="get_gebaeude_etage(this, O('etage'))" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['etage'], ENT_QUOTES, 'UTF-8', true);?>
" name="AS[etage_text]" class="itxt itxt2col"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="kostenstelle" style="display:block;width:auto;"><?php echo $_smarty_tpl->tpl_vars['ASConf']->value['kostenstelle']['label'];?>
<span class="right"><?php if ($_smarty_tpl->tpl_vars['ASConf']->value['kostenstelle']['required']) {?><span class="required">*</span><?php }?></span></label></td>
    <td style="padding:0;"><input type="text" id="kostenstelle" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['kostenstelle'], ENT_QUOTES, 'UTF-8', true);?>
" name="AS[kostenstelle]" class="itxt itxt2col"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="kostenstelel" style="display:block;width:auto;"><?php echo $_smarty_tpl->tpl_vars['ASConf']->value['planonnr']['label'];?>
<span class="right"><?php if ($_smarty_tpl->tpl_vars['ASConf']->value['planonnr']['required']) {?><span class="required">*</span><?php }?></span></label></td>
    <td style="padding:0;"><input type="text" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['planonnr'], ENT_QUOTES, 'UTF-8', true);?>
" name="AS[planonnr]" class="itxt itxt2col"></td>
  </tr>
  
  <tr>
    <td style="padding:0;"><label for="termin" style="display:block;width:auto;">Terminwunsch:</label></td>
    <td style="padding:0;"><input type="text" value="<?php echo smarty_modifier_date_format(htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['terminwunsch'], ENT_QUOTES, 'UTF-8', true),"%d.%m.%Y");?>
" 
	onfocus="showDtPicker(this)" id="terminwunsch" name="AS[terminwunsch]" class="itxt itxt2col jtooltip" rel="%WebRoot%hilfetexte/terminwunsch.php"></td>
  </tr>
	<!-- SelBox_initLSDefault(initInputObj, initLSName, initMultiple, initLSSearchFields, initLSInputFields) -->
  <tr>
    <td style="padding:0;"><label for="von_gebaeude_text" style="display:block;width:auto;">Von:</label>
        <input type="hidden" readonly="readonly" id="von_gebaeude_id" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['von_gebaeude_id'], ENT_QUOTES, 'UTF-8', true);?>
" name="AS[von_gebaeude_id]">
    </td>
    <td style="padding:0;">
        <input onclick="get_gebaeude(this, O('von_gebaeude_id'))" id="von_gebaeude_text" type="text" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['von_gebaeude_text'], ENT_QUOTES, 'UTF-8', true);?>
" readonly="readonly" class="itxt itxt2col">
    </td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="nach_gebaeude_text" style="display:block;width:auto;">Nach:</label>
        <input type="hidden" id="nach_gebaeude_id" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['nach_gebaeude_id'], ENT_QUOTES, 'UTF-8', true);?>
" name="AS[nach_gebaeude_id]">
    </td>
    <td style="padding:0;">
        <input onclick="get_gebaeude(this, O('nach_gebaeude_id'))" id="nach_gebaeude_text" type="text" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['nach_gebaeude_text'], ENT_QUOTES, 'UTF-8', true);?>
" readonly="readonly" class="itxt itxt2col">
    </td>
  </tr>
</table>
<br>
<h2 style="margin:0;">Ansprechpartner vor Ort</h2> 
<table>
  <tr>
    <td style="padding:0;width:180px;"><label for="ansprechpartner" style="display:block;width:auto;">Vor &amp; Nachname:</label></td>
    <td style="padding:0;width:250px;"><input type="text" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['ansprechpartner'], ENT_QUOTES, 'UTF-8', true);?>
" name="AS[ansprechpartner]" class="itxt itxt2col" title="Ansprechpartner"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="termin" style="display:block;width:auto;">E-Mail:</label></td>
    <td style="padding:0;"><input type="text" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['ansprechpartner_email'], ENT_QUOTES, 'UTF-8', true);?>
" name="AS[ansprechpartner_email]" class="itxt itxt2col" title="Ansprechpartner E-Mail"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="termin" style="display:block;width:auto;">Fon:</label></td>
    <td style="padding:0;"><input type="text" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['ansprechpartner_fon'], ENT_QUOTES, 'UTF-8', true);?>
" name="AS[ansprechpartner_fon]" class="itxt itxt2col jtooltip" rel="%WebRoot%hilfetexte/antrag_fon.php"></td>
  </tr>
</table>
</div>

<table>
  <tr>
    <td style="padding:0;width:180px;"><label style="display:block;width:auto;"><?php echo $_smarty_tpl->tpl_vars['ASConf']->value['umzug']['label'];?>
</label></td>
    <td style="padding:0;width:300px;" class="options-onoff"><label class='<?php if ($_smarty_tpl->tpl_vars['AS']->value['umzug'] == "Ja") {?>on<?php } else { ?>off<?php }?> active'><?php echo $_smarty_tpl->tpl_vars['AS']->value['umzug'];?>
</label></td>
  </tr>
</table>
</div>

<?php if (0) {?>
    <?php $_smarty_tpl->_subTemplateRender("file:property_umzugsformular_mitarbeiterauswahl.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
if (0) {?>
    <?php $_smarty_tpl->_subTemplateRender("file:property_umzugsformular_geraeteauswahl.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
if (1) {?>
        <?php $_smarty_tpl->_subTemplateRender("file:admin_umzugsformular_leistungsauswahl.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}?>

<?php $_smarty_tpl->_subTemplateRender("file:umzugsformular_attachments.tpl.read.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
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
<strong>Status setzen: </strong> <?php echo $_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'];?>

<div class="statusConsole" xstyle="display:none;">
<?php if ($_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'] == "geprueft" || $_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'] == "angeboten") {?>
<input id="btnStatZurueck"  class="btn blue"  type="submit"   <?php if ($_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'] != 'angeboten' && ($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'] != 'Init' || $_smarty_tpl->tpl_vars['AS']->value['genehmigt'] != 'Init')) {?>class="cssHide"<?php }?> onclick="umzugsantrag_set_status( 'erneutpruefen','Ja')" xstyle="padding:0 0 9px 0;background:url(images/BtnGreen.png) bottom left no-repeat;border:0;width:150px;height:24px;font-size:12px;color:#fff;font-weight:bold;" data-reCheckLabel="Erneut prüfen lassen" data-sendLabel="Senden" title="Erneut prüfen lassen" value="Zurück geben">
<input id="btnStatGenJa"    class="btn green" type="submit"   <?php if ($_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'] != 'angeboten' && ($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'] != 'Init' || $_smarty_tpl->tpl_vars['AS']->value['genehmigt'] != 'Init')) {?>class="cssHide"<?php }?> onclick="umzugsantrag_set_status( getLeistungenChanged() ? 'erneutpruefen' : 'genehmigt','Ja')" xstyle="padding:0 0 9px 0;background:url(images/BtnGreen.png) bottom left no-repeat;border:0;width:150px;height:24px;font-size:12px;color:#fff;font-weight:bold;" data-reCheckLabel="Erneut prüfen lassen" data-sendLabel="Senden" title="Genehmigung senden" value="Senden">
<input id="btnStatGenNein"  class="btn red" type="submit" <?php if ($_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'] != 'angeboten' && ($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'] != 'Init' || $_smarty_tpl->tpl_vars['AS']->value['genehmigt'] != 'Init')) {?>class="cssHide"<?php }?> onclick="umzugsantrag_set_status('genehmigt','Nein')" xstyle="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Ablehnen">
<input id="btnStatGenReset" type="submit" class="<?php if ($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'] == 'Init' || $_smarty_tpl->tpl_vars['AS']->value['bestaetigt'] != 'Init') {?>cssHide<?php } else { ?>btn blue<?php }?> onclick="umzugsantrag_set_status('genehmigt','Init')" xstyle="padding:0 0 9px 0;background:url(images/BtnBlue_160.png) bottom left no-repeat;border:0;width:160px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Genehmigung aufheben">
<?php }?></div>
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

<div id="SelBoxUart" class="SelBox">
<div style="position:absolute;right:0;"><img align="absmiddle" src="images/loeschen_off.png" style="cursor:pointer" onclick="document.getElementById('SelbBoxUart').style.display='none'" width="14" alt=""></div>
<div class="SelTitle"><strong>Anforderungsarten</strong></div>
<div id="SelBoxUartItems">
<div class="SelItem"><input type="checkbox" name="uartbox" value="Box" checked=1> <strong>Box</strong>move</div>
<div class="SelItem"><input type="checkbox" name="uartbox" value="Mit Möbel" checked=1> Mit <strong>Möbel</strong></div>
</div>
</div><?php }
}
