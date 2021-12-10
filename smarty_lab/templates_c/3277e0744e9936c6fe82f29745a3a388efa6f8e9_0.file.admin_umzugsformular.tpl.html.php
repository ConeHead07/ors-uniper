<?php
/* Smarty version 3.1.34-dev-7, created on 2021-12-10 15:01:11
  from '/var/www/html/html/admin_umzugsformular.tpl.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_61b35da75e4bf9_39292861',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3277e0744e9936c6fe82f29745a3a388efa6f8e9' => 
    array (
      0 => '/var/www/html/html/admin_umzugsformular.tpl.html',
      1 => 1639084702,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:admin_antraege_tabs.html' => 1,
    'file:admin_umzugsformular_lieferauswahl.tpl.html' => 1,
    'file:admin_umzugsformular_mitarbeiterauswahl.tpl.html' => 1,
    'file:admin_umzugsformular_geraeteauswahl.tpl.html' => 1,
    'file:admin_umzugsformular_ortsauswahl.tpl.html' => 1,
    'file:admin_umzugsformular_leistungsauswahl.tpl.html' => 1,
    'file:umzugsformular_attachments.tpl.read.html' => 1,
    'file:umzugsformular_attachments.tpl.read2.html' => 1,
    'file:umzugsformular_lieferscheine.tpl.read2.html' => 1,
    'file:admin_umzugsformular_gruppierung.tpl.html' => 1,
  ),
),false)) {
function content_61b35da75e4bf9_39292861 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/smarty3/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
$_smarty_tpl->_assignInScope('laenderCsv', substr($_smarty_tpl->tpl_vars['ASConf']->value['land']['size'],1,-1));
$_smarty_tpl->_assignInScope('laenderLst', explode("','",$_smarty_tpl->tpl_vars['laenderCsv']->value));?>


<link href="{WebRoot}css/SelBox.easy.css?%assetsRefreshId%" rel="STYLESHEET" type="text/css" />
<?php echo '<script'; ?>
 src="{WebRoot}js/FbAjaxUpdater.js?%assetsRefreshId%" type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="{WebRoot}js/PageInfo.js?%assetsRefreshId%" type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="{WebRoot}js/ObjectHandler.js?%assetsRefreshId%" type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="{WebRoot}js/SelBox.easy.js?%assetsRefreshId%" type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="{WebRoot}js/umzugsformular.easy.js?%assetsRefreshId%" type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="{WebRoot}js/geraeteumzug.easy.js?%assetsRefreshId%" type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="{WebRoot}js/dienstleister.js?%assetsRefreshId%" type="text/javascript"><?php echo '</script'; ?>
>

<?php echo '<script'; ?>
>
  
optionsUmzugsarten.push({value:"Datenpflege", content:"Datenpflege"});

<?php echo '</script'; ?>
>

<div id="SysInfoBox"></div>

<link rel="stylesheet" type="text/css" href="css/umzugsformular.css?%assetsRefreshId%">

<?php $_smarty_tpl->_subTemplateRender("file:admin_antraege_tabs.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('cat'=>"auftrag",'aid'=>$_smarty_tpl->tpl_vars['AS']->value['aid'],'s'=>"aantraege",'allusers'=>1), 0, false);
?>
<!-- MODUL UEBERSCHRIFTENBOX 109099 BEGIN --> 
<div class="divModuleBasic padding12px width5Col heightAuto colorContentMain">
  <div class="divInlay noMarginBottom borderTop"></div>
<div id="Umzugsantrag" data-html="html/admin/umzugsformular/tpl/html" class="divInlay">
<h2 style="margin:0;">Auftragsdaten</h2>
  <form action="umzugsantrag_speichern.php" name="frmUmzugsantrag" method="post" style="margin:0;padding:0;display:inline;">
<table class="form-table" border=1 cellspacing=1 cellpadding=1>
  <tr>
    <td class="label" style="padding:0;width:200px;height:auto;width:auto;"><label for="termin" style="width:180px;">Liefertermin:</label></td>
    <td style="padding:0;width:250px;"><input id="termin" type="text" value='<?php echo smarty_modifier_date_format(htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['umzugstermin'], ENT_QUOTES, 'UTF-8', true),"%d.%m.%Y");?>
'
    onfocus="showDtPicker(this)" name="AS[umzugstermin]" class="itxt itxt2col"></td>
  </tr>
  <tr>
    <td style="padding:0;width:200px;height:auto;width:auto;"><label for="tour_kennung" style="width:180px;">Tour-Kennung/ID:</label></td>
    <td style="padding:0;width:250px;"><input
            id="tour_kennung"name="AS[tour_kennung]" type="text" value='<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['tour_kennung'], ENT_QUOTES, 'UTF-8', true);?>
'
            class="itxt itxt2col jtooltip-local" rel="#infoTourkennung"></td>
  </tr>
  <?php if (!empty($_smarty_tpl->tpl_vars['AS']->value['tour_kennung'])) {?>
  <tr>
    <td style="padding:0;width:auto;"><label style="width:180px;">Tourzuweisung:</label></td>
    <td style="padding:0;width:auto;">
      <div class="itxt itxt2col"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['AS']->value['tour_zugewiesen_am'],"%d.%m.%y %H:%M");?>
, <?php echo $_smarty_tpl->tpl_vars['AS']->value['tour_zugewiesen_von'];?>
</div>
    </td>
  </tr>
  <?php }?>
  <tr style="display: none;">
    <td style="padding:0;height:auto;width:auto;"><label for="AS[Umzugszeit]" style="width:180px;">Uhrzeit:</label></td>
    <td style="padding:0;width:250px;"><input id="AS[Umzugszeit]" type="time" value='<?php echo substr(htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['umzugszeit'], ENT_QUOTES, 'UTF-8', true),0,5);?>
'
    id="umzugszeit" name="AS[umzugszeit]" class="itxt itxt2col"></td>
  </tr>
  <tr>
    <td style="padding:0;height:auto;width:auto;"><label style="width:180px;">Antragsdatum:</label></td>
    <td style="padding:0;width:auto;"><div  class="itxt itxt2col"><?php echo smarty_modifier_date_format(htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['antragsdatum'], ENT_QUOTES, 'UTF-8', true),"%d.%m.%Y");?>
</div></td>
  </tr>
  <tr>
    <td style="padding:0;width:auto;"><label style="width:180px;"><?php if ($_smarty_tpl->tpl_vars['AS']->value['angeboten_am']) {?>Genehmigt<?php } else { ?>Best&auml;tigt<?php }?>:</label></td>
    <td style="padding:0;width:auto;" class="status_<?php echo $_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'];?>
">
      <div  class="itxt itxt2col"><img id="imgStatGen" src="images/status_<?php echo mb_strtolower($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'], 'UTF-8');?>
.png"><span id="txtStatGen"><?php if ($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'] != "Init") {?> <?php echo $_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'];?>
 am <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br_am'],"%d.%m.%Y %H:%M");?>
 <?php echo $_smarty_tpl->tpl_vars['AS']->value['genehmigt_br_von'];
}?></span></div></td>
  </tr>
  <tr>
    <td style="padding:0;width:auto;"><label style="width:180px;">Abgeschlossen:</label></td>
    <td style="padding:0;width:auto;" class="status_<?php echo $_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'];?>
">
      <div  class="itxt itxt2col">
      <img id="imgStatAbg" src="images/status_<?php echo mb_strtolower($_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'], 'UTF-8');?>
.png"><span id="txtStatAbg"><?php if ($_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'] != "Init") {?> <?php echo $_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'];?>
 am <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['AS']->value['abgeschlossen_am'],"%d.%m.%Y %H:%M");?>
 <?php echo $_smarty_tpl->tpl_vars['AS']->value['abgeschlossen_von'];
}?></span></div></td>
  </tr>
  <tr>
    <td style="padding:0;"><label style="width:180px;">Status:</label></td>
    <td style="padding:0;"><div class="itxt itxt2col"><?php if (empty($_smarty_tpl->tpl_vars['AS']->value['angeboten_am']) && htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'], ENT_QUOTES, 'UTF-8', true) == "genehmigt") {?>bestaetigt<?php } else {
echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'], ENT_QUOTES, 'UTF-8', true);
}?></div></td>
  </tr>
  <?php if ($_smarty_tpl->tpl_vars['AS']->value['angeboten_von']) {?>
  <tr>
    <td style="padding:0;"><label style="width:180px;">Angeboten:</label></td>
    <td style="padding:0;"><div class="itxt itxt2col"><?php echo $_smarty_tpl->tpl_vars['AS']->value['angeboten_am'];?>
 von <?php echo $_smarty_tpl->tpl_vars['AS']->value['angeboten_von'];?>
</div></td>
  </tr>
  <?php }?>
	<!-- SelBox_initLSDefault(initInputObj, initLSName, initMultiple, initLSSearchFields, initLSInputFields) -->
</table>
  <a href="%WebRoot%sites/umzugsblatt.php?id=<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
" style="display:none;" target="_Umzugsblatt<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
"><img src="%WebRoot%images/printer.png" width="16" height="16" alt="">Umzugsblatt / Druckansicht</a>

    <div style="display:none">
      <div id="infoTourkennung">
        Wenn der Auftrag einer Tour in der Tourenplanung zugeordnet ist, hinterlegen Sie hier bitte die Tour-Nr.
      </div>
    </div>
  <img src="%WebRoot%images/printer.png" width="16" height="16" alt="">
  <a href="%WebRoot%sites/lieferschein.php?aid=<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
" target="_Lieferschein<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
">Lieferschein</a>
  | <a href="%WebRoot%sites/lieferschein.php?aid=<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
&art=kommission" target="_Lieferschein<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
">Kommissonierschein </a>
  | <a href="%WebRoot%sites/etiketten.php?aid=<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
" target="_Etiketten<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
">Etiketten</a>
<br>
    <?php if (!empty($_smarty_tpl->tpl_vars['AS']->value['bemerkungen'])) {?>
    <table class="form-table" width="100%" border=1 cellspacing=1 cellpadding=1 style="margin-top: 1.5rem">
        <tr>
          <td>Bisherige Bemerkungen:</td>
          <td class="no-border"></td>
        </tr>
        <tr>
          <td colspan="2">
            <div id="BemerkungenHistorie" style="resize: vertical;overflow:auto;height:4rem"><?php echo nl2br($_smarty_tpl->tpl_vars['AS']->value['bemerkungen']);?>
</div>
          </td>
        </tr>
    </table>
    <?php }?>

    <?php if (!empty($_smarty_tpl->tpl_vars['AS']->value['lieferhinweise'])) {?>
    <table class="form-table"  width="100%" border=1 cellspacing=1 cellpadding=1 style="margin-top: 1.5rem">
      <tr>
        <td style="padding:0;width:200px;height:auto;width:auto;"><label style="width:180px;">Lieferhinweise:</label></td>
        <td></td>
      </tr>
      <tr>
        <td colspan="2">
          <div id="LieferhinweiseContent" style="resize: vertical;overflow:auto;height:4rem"><?php echo nl2br($_smarty_tpl->tpl_vars['AS']->value['lieferhinweise']);?>
</div>
        </td>
      </tr>
    </table>
    <?php }?>

  <div  style="margin-top: 2rem">
<div style="float:left">
<h2 style="margin:0;">Lieferdaten</h2>
<input type="hidden" name="AS[aid]" value="<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
">
<input type="hidden" name="AS[token]" value="<?php echo $_smarty_tpl->tpl_vars['AS']->value['token'];?>
">
<table class="form-table" >
  <tr>
      <td style="padding:0;"><label for="mitarbeiter" style="width:180px;">Vor<?php if ($_smarty_tpl->tpl_vars['ASConf']->value['vorname']['required']) {?><span class="required">*</span><?php }?> &amp; Nachname<span class="right"><?php if ($_smarty_tpl->tpl_vars['ASConf']->value['name']['required']) {?><span class="required">*</span><?php }?></span></label></td>
    <td style="padding:0;width:250px;"><input type="text" readonly="true" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['vorname'], ENT_QUOTES, 'UTF-8', true);?>
" name="AS[vorname]" class="itxt itxt1col floatLeft"><input type="text" readonly="true" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
" name="AS[name]" class="itxt itxt1col floatRight" title="Name"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="email" style="width:180px;">E-Mail<span class="right"><?php if ($_smarty_tpl->tpl_vars['ASConf']->value['email']['required']) {?><span class="required">*</span><?php }?></span></label></td>
    <td style="padding:0;"><input id="email" type="text" readonly="true" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['email'], ENT_QUOTES, 'UTF-8', true);?>
" name="AS[email]" class="itxt itxt2col" title="E-Mail"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="fon" style="width:180px;">Telefon<span class="right"><?php if ($_smarty_tpl->tpl_vars['ASConf']->value['fon']['required']) {?><span class="required">*</span><?php }?></span></label></td>
    <td style="padding:0;"><input id="fon" type="text" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['fon'], ENT_QUOTES, 'UTF-8', true);?>
" name="AS[fon]" class="itxt itxt2col jtooltip" rel="%WebRoot%hilfetexte/antrag_fon.php"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="as_kid" style="display:block;width:auto;">KID</label></td>
    <td style="padding:0;"><input type="text" id="as_kid" name="AS[personalnr]"  readonly value="<?php if (!empty($_smarty_tpl->tpl_vars['AS']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['personalnr'], ENT_QUOTES, 'UTF-8', true);
}?>" class="itxt itxt2col" ></td>
  </tr>
</table>

  <div style="margin-top:2rem;"><h2 style="margin:0;">Lieferadresse <i class="geo-address"
                                                                            data-address="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['strasse'], ENT_QUOTES, 'UTF-8', true);?>
,<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['plz'], ENT_QUOTES, 'UTF-8', true);?>
+<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['ort'], ENT_QUOTES, 'UTF-8', true);?>
,<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['land'], ENT_QUOTES, 'UTF-8', true);?>
"
                                                                            data-geo-strasse="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['strasse'], ENT_QUOTES, 'UTF-8', true);?>
"
                                                                            data-geo-plz="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['plz'], ENT_QUOTES, 'UTF-8', true);?>
"
                                                                            geo-geo-ort="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['ort'], ENT_QUOTES, 'UTF-8', true);?>
"
                                                                            data-geo-land="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['land'], ENT_QUOTES, 'UTF-8', true);?>
"></i></h2></div>
  <?php echo '<script'; ?>
>
    $(function() {
      $("i.geo-address[data-address]").each(function() {
        var gmapUrl = "https://www.google.com/maps/dir/?api=1&travelmode=driving&destination=";
        var query = encodeURIComponent( $(this).data("address") );
        // https://www.google.com/maps/dir/?api=1&destination=Mainzer+Straße+97,65189+Wiesbaden,Deutschland&travelmode=driving
        $(this).wrap( $("<a/>").attr({
          href: gmapUrl + query,
          target: "gmap",
          title: "Lieferadresse in Gmap anzeigen"
        }) ).addClass("marker icon");
      });
    });
  <?php echo '</script'; ?>
>
  <table class="form-table" >
  <tr>
    <td style="padding:0;">
      <label for="as_strasse" style="display:block;width:180px;">Stra&szlig;e &amp; Nr<span class="right"><?php if ($_smarty_tpl->tpl_vars['ASConf']->value['strasse']['required']) {?><span class="required">*</span><?php }?></span></label>
    </td>
    <td style="padding:0;width:250px;"><input type="text" id="as_strasse" autocomplete="off" xreadonly="true" <?php if ($_smarty_tpl->tpl_vars['ASConf']->value['strasse']['required']) {?>required="required"<?php }?> value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['strasse'], ENT_QUOTES, 'UTF-8', true);?>
" name="AS[strasse]" class="itxt itxt2col"></td>
  </tr>

  <tr>
    <td style="padding:0;">
      <label for="as_plz" style="display:block;width:auto;">PLZ<span class="right"><?php if ($_smarty_tpl->tpl_vars['ASConf']->value['plz']['required']) {?><span class="required">*</span><?php }?></span></label></td>
    <td style="padding:0;"><input type="text" id="as_plz" autocomplete="postal code" xreadonly="true" <?php if ($_smarty_tpl->tpl_vars['ASConf']->value['plz']['required']) {?>required="required"<?php }?> value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['plz'], ENT_QUOTES, 'UTF-8', true);?>
" name="AS[plz]" class="itxt itxt2col"></td>
  </tr>

  <tr>
    <td style="padding:0;"><label for="ort" style="width:180px;"><?php echo $_smarty_tpl->tpl_vars['ASConf']->value['ort']['label'];?>
<span class="right"><?php if ($_smarty_tpl->tpl_vars['ASConf']->value['ort']['required']) {?><span class="required">*</span><?php }?></span></label></td>
    <td style="padding:0;"><input id="ort" type="text" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['ort'], ENT_QUOTES, 'UTF-8', true);?>
" name="AS[ort]" class="itxt itxt2col"></td>
  </tr>


    <tr>
      <td style="padding:0;">
        <label style="display:block;width:auto;">Land<span class="right"><?php if ($_smarty_tpl->tpl_vars['ASConf']->value['land']['required']) {?><span class="required">*</span><?php }?></span></label></td>
      <td style="padding:0;">
        <select autocomplete="address-level2" name="AS[land]" class="iselect">
          <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['laenderLst']->value, 'IT', false, NULL, 'F1', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['IT']->value) {
?>
          <option value="<?php echo $_smarty_tpl->tpl_vars['IT']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['AS']->value['land'] == $_smarty_tpl->tpl_vars['IT']->value) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['IT']->value;?>
</option>
          <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </select>
      </td>
    </tr>
</table>
<br>
<div style="margin-top:2rem;"><h2 style="margin:0;">Abweichender Ansprechpartner vor Ort</h2></div>
<table class="form-table" >
  <tr>
    <td style="padding:0;"><label for="ansprechpartner" style="width:180px;">Vor &amp; Nachname:</label></td>
    <td style="padding:0;width:250px;"><input id="ansprechpartner" type="text" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['ansprechpartner'], ENT_QUOTES, 'UTF-8', true);?>
" name="AS[ansprechpartner]" class="itxt itxt2col" title="Ansprechpartner"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="ansprechpartner_fon" style="width:180px;">Telefon:</label></td>
    <td style="padding:0;"><input id="ansprechpartner_fon" type="text" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['ansprechpartner_fon'], ENT_QUOTES, 'UTF-8', true);?>
" name="AS[ansprechpartner_fon]" class="itxt itxt2col jtooltip" rel="%WebRoot%hilfetexte/antrag_fon.php"></td>
  </tr>
</table>

<table class="form-table"  style="display:none;">
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
</div>
<div style="clear:both;"></div>

<?php if (0) {?>
    <?php $_smarty_tpl->_subTemplateRender("file:admin_umzugsformular_mitarbeiterauswahl.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}?>

<?php if (0) {?>
    <?php $_smarty_tpl->_subTemplateRender("file:admin_umzugsformular_geraeteauswahl.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}?>

<?php if (0) {?>
    <?php $_smarty_tpl->_subTemplateRender("file:admin_umzugsformular_ortsauswahl.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}?>

<?php if (1) {?>
        <div style="margin-top:1.5rem">
    <?php $_smarty_tpl->_subTemplateRender("file:admin_umzugsformular_leistungsauswahl.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    </div>
<?php }?>

<?php if (!empty($_smarty_tpl->tpl_vars['UmzugsAnlagen']->value)) {?>
  <?php $_smarty_tpl->_subTemplateRender("file:umzugsformular_attachments.tpl.read.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}?>

<?php if (!empty($_smarty_tpl->tpl_vars['UmzugsAnlagenIntern']->value)) {
$_smarty_tpl->_subTemplateRender("file:umzugsformular_attachments.tpl.read2.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('UmzugsAnlagen'=>$_smarty_tpl->tpl_vars['UmzugsAnlagenIntern']->value,'internal'=>1), 0, false);
}?>

<?php if (!empty($_smarty_tpl->tpl_vars['UmzugLieferscheine']->value)) {?>
    <div style="margin-top:1.5rem"></div>
  <?php $_smarty_tpl->_subTemplateRender("file:umzugsformular_lieferscheine.tpl.read2.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('UmzugsAnlagen'=>$_smarty_tpl->tpl_vars['UmzugLieferscheine']->value,'internal'=>1), 0, false);
}?>

<?php if (0) {?>
<div style="width:100%">
    <?php $_smarty_tpl->_subTemplateRender("file:admin_umzugsformular_gruppierung.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
</div>
<?php }?>
<!-- <div style="color:#549e1a;font-weight:bold;text-decoration:none;cursor:pointer;" onclick="addMa();return false;">Weiteren Mitarbeiter ausw&auml;hlen <img align="absmiddle" src="images/hinzufuegen_off.png" width="14" alt=""></div><br> -->


  <div id="BoxBemerkungen" style="margin-top: 2rem">
      <strong>Bemerkung hinzufügen:</strong><br>
      <textarea class="iarea bemerkungen" name="AS[bemerkungen]" style="resize: vertical;overflow: auto"></textarea>
  </div>

  <div style="margin-top: 2rem">
    <strong>Lieferinweis (für Lieferschein):</strong><br>
<?php if ($_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'] != "abgeschlossen" && $_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'] != "storniert" && $_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'] != "abgelehnt") {?>
    <div id="BoxLieferhinweise">
        <textarea class="iarea bemerkungen lieferhinweise" name="AS[lieferhinweise]" style="resize: vertical;overflow: auto"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['lieferhinweise'], ENT_QUOTES, 'UTF-8', true);?>
</textarea>
<?php } else { ?>
      <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['lieferhinweise'], ENT_QUOTES, 'UTF-8', true);?>

<?php }?>
    </div>
  </div>

<div style="margin-top:1.5rem;width:100%;margin-top:2.5rem;"><!--
 --><input type="submit" name="CatchDefaultEnterReturnFalse" onclick="return false;" value="" style="display:none;border:0;background:#fff;color:#fff;position:relative;left:-500px;"><!-- 
 --><input type="submit" class="btn grey"
           onclick="umzugsantrag_save()"
           title="Beim Speichern wird der Auftragsstatus nicht geändert und es werden keine Benachrichtigungen versendet"
           value="Speichern"><!--
 --><?php if (0) {?><input type="submit" class="btn red d-none"
           onclick="umzugsantrag_reload()"
           value="Neu laden"><?php }?><!--
 --><input type="submit" class="btn red"
           onclick="umzugsantrag_add_attachement()"
           value="Dateianhänge">
<?php if ($_smarty_tpl->tpl_vars['creator']->value == "mertens") {?>
  <input
          type="submit" class="btn red"
          onclick="umzugsantrag_add_internal_attachement()"
          value="Interne Dateianhänge">
  <?php }?>
  <?php if ($_smarty_tpl->tpl_vars['creator']->value == "mertens") {?>
  <input
          type="submit" class="btn red"
          onclick="umzugsantrag_add_lieferschein()"
          value="Quittierten Lieferschein hinzufügen">
  <?php }?>
<br>
<?php if ($_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'] != "temp") {?>
  <div style="margin-top:2.5rem;"><strong>Status mit Benachrichtung setzen: </strong></div>
<div class="statusConsole">
  <?php if ($_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'] == "beantragt" || $_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'] == "angeboten") {?>
    <input
          id="btnStatGeprBackToUser" type="submit"
          class="<?php if ($_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'] == 'beantragt' || $_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'] == 'erneutpruefen' || $_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'] == 'angeboten') {?>btn blue<?php } else { ?>cssHide<?php }?>"
          onclick="umzugsantrag_set_status('zurueckgeben','Ja')"
          value="Zurueckgeben">

    <input
            id="btnStatGenJa" type="submit"
            class="btn green"
            onclick="umzugsantrag_set_status('bestaetigt','Ja')"
            title="Senden als Bestätigt"
            value="Auftrag bestätigen">
  <?php }?>
  <?php if (0) {?>
    <input
            id="btnStatGenJa" type="submit"
            class="<?php if ($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'] == 'Ja' || $_smarty_tpl->tpl_vars['AS']->value['bestaetigt'] != 'Init') {?>cssHide<?php } else { ?>btn green<?php }?>"
            onclick="umzugsantrag_set_status('genehmigt','Ja')"
            title="Bestätigung senden" value="Senden">

    <input
            id="btnStatGenNein" type="submit"
            class="<?php if ($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'] == 'Nein' || $_smarty_tpl->tpl_vars['AS']->value['bestaetigt'] != 'Init') {?>cssHide<?php } else { ?>btn red<?php }?>"
            onclick="umzugsantrag_set_status('genehmigt','Nein')"
          value="Ablehnen">
  <?php }?>
  <?php if ($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'] == 'Ja') {?>
    <input
            id="btnStatGenReset" type="submit"
            class="<?php if ($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'] == 'Init' || $_smarty_tpl->tpl_vars['AS']->value['bestaetigt'] != 'Init') {?>cssHide<?php } else { ?>btn blue<?php }?>"
            onclick="umzugsantrag_set_status('genehmigt','Init')"
            value="Genehmigung aufheben">
&nbsp; <?php }?>
  <?php if ($_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'] == 'bestaetigt' || $_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'] == 'geprueft') {?>
    <input
            id="btnStatAbgJa" type="submit"
            class="<?php if ($_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'] != 'Init') {?>cssHide<?php } else { ?>btn green<?php }?>"
            onclick="umzugsantrag_set_status('abgeschlossen','Ja')"
            value="Auftrag jetzt abschließen">
&nbsp; <?php }?>
  <?php if ($_smarty_tpl->tpl_vars['user']->value['gruppe'] == "admin" && $_smarty_tpl->tpl_vars['user']->value['adminmode'] == "superadmin") {?>
  <input id="btnStatAbgReset" type="submit"
         class="<?php if ($_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'] == 'Init') {?>cssHide<?php } else { ?>btn blue<?php }?>"
         onclick="umzugsantrag_set_status('abgeschlossen','Init')"
         value="Abschluss wieder aufheben">
  <input id="btnStatAbgStorno" type="submit"
         class="<?php if ($_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'] != 'Init') {?>cssHide<?php } else { ?>btn red<?php }?>"
         onclick="umzugsantrag_set_status('abgeschlossen','Storniert')"
         value="Auftrag Stornieren">
  <?php }?>
</div>

<?php }?>
</div>

<?php if (0) {?>
  <?php if ($_smarty_tpl->tpl_vars['AS']->value['umzug'] == "Nein") {?>
    Antrag ist ohne Umzug und kann direkt ausgeführt werden
  <?php } else { ?>
    Antrag ist mit Umzug und erfordert die Genehmigung durch Property
  <?php }
}?>
<!-- Debug-Btn:
<input type="submit" onclick="return umzugsantrag_submit_debug('speichern')" style="padding:0 0 9px 0;background:url(images/BtnGrey.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" name="cmd" value="speichern">
<input type="submit" onclick="return umzugsantrag_submit_debug('senden')" style="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" name="cmd" value="senden">
<input type="submit" onclick="return umzugsantrag_submit_debug('stornieren')" style="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" name="cmd" value="stornieren">
<input type="submit" onclick="return umzugsantrag_submit_debug('laden')" style="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" name="cmd" value="laden">
 -->
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
	 --><input value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['name'], ENT_QUOTES, 'UTF-8', true);
}?>" id="mitarbeiter"  xonclick="get_mitarbeiter(this)"  class="AutoFill UpperCase" type="text" name="MA[name][]"></td>
    <td class="vn"><input value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['vorname'], ENT_QUOTES, 'UTF-8', true);
}?>" class="itxt itxt1col UpperCase AutoFill" type="text" name="MA[vorname][]"></td>
	<td class="xf"><input value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['extern_firma'], ENT_QUOTES, 'UTF-8', true);
}?>" onclick="get_extern_firma(this)" class="itxt itxt1col AutoFill" size=15 type="text" name="MA[extern_firma][]"></td>
	
	<td class="abt"><input value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['abteilung'], ENT_QUOTES, 'UTF-8', true);
}?>" readonly="true" onclick="get_abteilung(this)" class="itxt AutoFill" size=4 type="text" name="MA[abteilung][]"></td>
	<td class="geb"><input  autocomplete="off" value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['gebaeude'], ENT_QUOTES, 'UTF-8', true);
}?>" readonly="true" onclick="get_gebaeude(this)" class="itxt itxt1col AutoFill" type="text" name="MA[gebaeude][]"></td>
    <td class="etg"><input value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['etage'], ENT_QUOTES, 'UTF-8', true);
}?>" onclick="get_etage(this)" readonly="true" class="itxt AutoFill" type="text" name="MA[etage][]"></td>
    <td class="rnr"><input value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['raumnr'], ENT_QUOTES, 'UTF-8', true);
}?>" onclick="get_raumnr(this)" readonly="true" class="itxt AutoFill" size=8 type="text" name="MA[raumnr][]"></td>
    <td class="apnr"><input value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['apnr'], ENT_QUOTES, 'UTF-8', true);
}?>" onclick="get_apnr(this)" class="itxt AutoFill" readonly="true" size=8 type="text" name="MA[apnr][]"></td>
    
    <td class="fon"><input value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['fon'], ENT_QUOTES, 'UTF-8', true);
}?>" class="itxt" type="text" name="MA[fon][]"></td>
    <td class="pcnr"><input value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['pcnr'], ENT_QUOTES, 'UTF-8', true);
}?>" class="itxt" type="text" name="MA[pcnr][]"></td>
    <td class="festeip"><input value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['festeip'], ENT_QUOTES, 'UTF-8', true);
}?>" class="itxt" type="text" name="MA[festeip][]"></td>
</tr>
  <tr class="inputRowZiel">
    <td class="ziel" align=right>Anforderungsart</td>
	<td class="uart"><input value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['umzugsart'], ENT_QUOTES, 'UTF-8', true);
}?>" class="UserInput" readonly="true" onclick="get_umzugsart(this)" type="text" name="MA[umzugsart][]"></td>
    <td class="ziel" align=right> Nach:</td>
    <td class="etg"><input value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['zabteilung'], ENT_QUOTES, 'UTF-8', true);
}?>" readonly="true" onclick="get_abteilung(this)" class="itxt UserInput" type="text" name="MA[zabteilung][]"></td>
	<td class="zort"><input value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['zgebaeude'], ENT_QUOTES, 'UTF-8', true);
}?>" readonly="true" onclick="get_gebaeude(this)" class="itxt UserInput" type="text" name="MA[zgebaeude][]"></td>
    <td class="geb"><input value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['zetage'], ENT_QUOTES, 'UTF-8', true);
}?>" readonly="true" onclick="get_etage(this)" class="itxt UserInput" type="text" name="MA[zetage][]"></td>
    <td class="rnr"><input value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['zraumnr'], ENT_QUOTES, 'UTF-8', true);
}?>" readonly="true" onclick="get_raumnr(this)" class="itxt UserInput" type="text" name="MA[zraumnr][]"></td>
    <td class="rnr"><input value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['zapnr'], ENT_QUOTES, 'UTF-8', true);
}?>" class="itxt" type="text" name="MA[zapnr][]"></td>
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
	 --><input
            value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['name'], ENT_QUOTES, 'UTF-8', true);
}?>"
            id="mitarbeiter" xonclick="get_mitarbeiter(this)"
            class="itxt itxt1col UpperCase UserInput"
            type="text" name="MA[name][]"></td>
    <td class="vn"><input
            value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['vorname'], ENT_QUOTES, 'UTF-8', true);
}?>"
            class="itxt itxt1col UpperCase UserInput"
            type="text" name="MA[vorname][]"></td>
	<td class="xf"><input
            value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['extern_firma'], ENT_QUOTES, 'UTF-8', true);
}?>"
            onclick="get_extern_firma(this)"
            class="itxt itxt1col UserInput" size=15
            type="text" name="MA[extern_firma][]"></td>
	
	<td class="abt"><input
            value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['abteilung'], ENT_QUOTES, 'UTF-8', true);
}?>"
            readonly="true"
            onclick="get_abteilung(this)"
            class="itxt UserInput" size=4
            type="text" name="MA[abteilung][]"></td>
	<td class="geb"><input
            autocomplete="off"
            value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['gebaeude'], ENT_QUOTES, 'UTF-8', true);
}?>"
            readonly="true"
            onclick="get_gebaeude(this)"
            class="itxt itxt1col UserInput"
            type="text" name="MA[gebaeude][]"></td>
    <td class="etg"><input
            value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['etage'], ENT_QUOTES, 'UTF-8', true);
}?>"
            onclick="get_etage(this)"
            readonly="true"
            class="itxt UserInput"
            type="text" name="MA[etage][]"></td>
    <td class="rnr"><input
            value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['raumnr'], ENT_QUOTES, 'UTF-8', true);
}?>"
            onclick="get_raumnr(this)"
            readonly="true"
            class="itxt UserInput" size=8
            type="text" name="MA[raumnr][]"></td>
    <td class="apnr"><input value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['apnr'], ENT_QUOTES, 'UTF-8', true);
}?>" class="itxt UserInput" readonly="true" size=8 type="text" name="MA[apnr][]"></td>
    
    <td class="fon"><input value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['fon'], ENT_QUOTES, 'UTF-8', true);
}?>" class="itxt" type="text" name="MA[fon][]"></td>
    <td class="pcnr"><input value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['pcnr'], ENT_QUOTES, 'UTF-8', true);
}?>" class="itxt" type="text" name="MA[pcnr][]"></td>
    <td class="festeip"><input value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['festeip'], ENT_QUOTES, 'UTF-8', true);
}?>" class="itxt" type="text" name="MA[festeip][]"></td>
	</tr>
  <tr class="inputRowZiel">
    <td class="ziel" align=right>Anforderungsart</td>
	<td class="uart"><input value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['umzugsart'], ENT_QUOTES, 'UTF-8', true);
}?>" class="UserInput" readonly="true" onclick="get_umzugsart(this)" type="text" name="MA[umzugsart][]"></td>
    <td class="ziel" align=right> Nach:</td>
    <td class="abt"><input value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['zabteilung'], ENT_QUOTES, 'UTF-8', true);
}?>" readonly="true" onclick="get_abteilung(this)" class="itxt UserInput" type="text" name="MA[zabteilung][]"></td>
	<td class="geb"><input value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['zgebaeude'], ENT_QUOTES, 'UTF-8', true);
}?>" readonly="true" onclick="get_gebaeude(this)" class="itxt UserInput" type="text" name="MA[zgebaeude][]"></td>
    <td class="etg"><input value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['zetage'], ENT_QUOTES, 'UTF-8', true);
}?>" readonly="true" onclick="get_etage(this)" class="itxt UserInput" type="text" name="MA[zetage][]"></td>
    <td class="rnr"><input value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['zraumnr'], ENT_QUOTES, 'UTF-8', true);
}?>" readonly="true" onclick="get_raumnr(this)" class="itxt UserInput" type="text" name="MA[zraumnr][]"></td>
    <td class="apnr"><input value="<?php if (!empty($_smarty_tpl->tpl_vars['MA']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['MA']->value['zapnr'], ENT_QUOTES, 'UTF-8', true);
}?>" class="itxt UserInput" type="text" name="MA[zapnr][]"></td>
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
