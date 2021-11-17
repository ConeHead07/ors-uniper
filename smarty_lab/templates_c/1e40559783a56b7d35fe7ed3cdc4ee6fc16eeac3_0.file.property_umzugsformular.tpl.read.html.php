<?php
/* Smarty version 3.1.34-dev-7, created on 2021-11-11 13:42:09
  from '/var/www/html/uniper/htdocs/html/property_umzugsformular.tpl.read.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_618d0fa1930864_78944737',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1e40559783a56b7d35fe7ed3cdc4ee6fc16eeac3' => 
    array (
      0 => '/var/www/html/uniper/htdocs/html/property_umzugsformular.tpl.read.html',
      1 => 1636634506,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:property_umzugsformular_mitarbeiterauswahl.tpl.read.html' => 1,
    'file:property_umzugsformular_geraeteauswahl.tpl.read.html' => 1,
    'file:property_umzugsformular_leistungsauswahl.tpl.read.html' => 1,
    'file:umzugsformular_attachments.tpl.read.html' => 1,
  ),
),false)) {
function content_618d0fa1930864_78944737 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/uniper/htdocs/smarty3/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>

<link rel="STYLESHEET" type="text/css" href="{WebRoot}css/SelBox.easy.css" />
<link rel="stylesheet" type="text/css" href="{WebRoot}css/umzugsformular.css" />
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
 src="{WebRoot}js/umzugsformular.easy.js?lm=20150126" type="text/javascript"><?php echo '</script'; ?>
>

<div id="SysInfoBox"></div>
<!-- MODUL UEBERSCHRIFTENBOX 109099 BEGIN --> 
<div class="divModuleBasic padding6px width5Col heightAuto colorContentMain">
  <?php if (empty($_smarty_tpl->tpl_vars['AS']->value['umzugstermin'])) {?>
  <h1><span class="spanTitle">Bestellung #<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
</span></h1>
  <?php } else { ?>
  <h1><span class="spanTitle">Auftrag #<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
</span></h1>
  <?php }?>
  <p>
  <div id="Umzugsantrag" class="divInlay">
    <h2 style="margin:0;" data-site="property/umzugsformular/tpl/read/html">Auftragsstatus</h2>
    <table xborder=0 xcellspacing=1 xcellpadding=1>
      <?php if (!empty($_smarty_tpl->tpl_vars['AS']->value['umzugstermin'])) {?>
      <tr>
        <td style="padding:0;width:200px;"><label for="termin" style="display:block;width:auto;">Ausf&uuml;hrungstermin:</label></td>
        <td style="padding:0;width:300px;"><div class="itxt itxt2col ireadonly"><?php echo smarty_modifier_date_format(htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['umzugstermin'], ENT_QUOTES, 'UTF-8', true),"%d.%m.%Y");?>
</div>
          </td>
      </tr>
      <?php }?>
            <tr>
        <td style="padding:0;width:200px;"><label for="termin" style="display:block;width:auto;">Auftragsdatum:</label></td>
        <td style="padding:0;"><div class="itxt itxt2col ireadonly"><span data-fld="AS[antragsdatum]"><?php echo smarty_modifier_date_format(htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['antragsdatum'], ENT_QUOTES, 'UTF-8', true),"%d.%m.%Y");?>
</span></td>
      </tr>
      <?php if (!empty($_smarty_tpl->tpl_vars['AS']->value['umzugsstatus']) && $_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'] == "abgeschlossen") {?>
      <tr>
        <td style="padding:0;width:200px;"><label style="display:block;width:auto;">Auftrag abgeschlossen:</label></td>
        <td style="padding:0;" class="status_<?php echo $_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'];?>
"><div class="itxt itxt2col ireadonly">
          <img id="imgStatAbg" src="images/status_<?php echo mb_strtolower($_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'], 'UTF-8');?>
.png"><span id="txtStatAbg"><?php if ($_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'] != "Init") {?> <?php echo $_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'];?>
 am <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['AS']->value['abgeschlossen_am'],"%d.%m.%Y %H:%M");?>
 <?php echo $_smarty_tpl->tpl_vars['AS']->value['abgeschlossen_von'];
}?></span></div></td>
      </tr>
      <?php }?>
      <tr>
        <td style="padding:0;width:200px;"><label style="display:block;width:auto;">Status:</label></td>
        <td style="padding:0;"><div class="itxt itxt2col ireadonly">
          <?php if (empty($_smarty_tpl->tpl_vars['AS']->value['angeboten_am']) && htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'], ENT_QUOTES, 'UTF-8', true) == "genehmigt") {?>bestaetigt<?php } else {
echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'], ENT_QUOTES, 'UTF-8', true);
}?>
        </div></td>
      </tr>
        <!-- SelBox_initLSDefault(initInputObj, initLSName, initMultiple, initLSSearchFields, initLSInputFields) -->
    </table>
  <?php if (!empty($_smarty_tpl->tpl_vars['AS']->value['umzugsstatus']) && $_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'] == "abgeschlossen") {?>
    <a href="%WebRoot%sites/umzugsblatt.php?id=<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
&mode=property" style="display:none" target="_Umzugsblatt<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
"><img src="%WebRoot%images/printer.png" width="16" height="16" alt="">Anforderungsblatt / Druckansicht</a>
    <a href="%WebRoot%sites/lieferschein.php?id=<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
&mode=property" target="_Lieferschein<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
"><img src="%WebRoot%images/printer.png" width="16" height="16" alt="">Lieferschein</a>
    | <a href="%WebRoot%index.php?s=pantrag&id=<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
&export=csv" target="_blank">CSV-Export</a>

  <?php }?>
  <br>
    <h2 style="margin:0;">Lieferdaten</h2>
    <table>
      <tr>
        <td style="padding:0;width:200px;"><label style="display:block;width:auto;">Vor &amp; Nachname:</label></td>
        <td style="padding:0;width:250px;"><div class="itxt itxt2col ireadonly"><span data-fld="AS[vorname]">
          <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['vorname'], ENT_QUOTES, 'UTF-8', true);?>
</span> <span data-fld="AS[name]"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
</span>
      </div></td>
      </tr>
      <tr>
        <td style="padding:0;"><label style="display:block;width:auto;">E-Mail:</label></td>
        <td style="padding:0;"><div class="itxt itxt2col ireadonly">
          <span data-fld="AS[email]"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['email'], ENT_QUOTES, 'UTF-8', true);?>
</span>
        </div></td>
      </tr>
      <tr>
        <td style="padding:0;"><label style="display:block;width:auto;">Fon:</label></td>
        <td style="padding:0;"><div class="itxt itxt2col ireadonly">
          <span data-fld="AS[fon]"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['fon'], ENT_QUOTES, 'UTF-8', true);?>
</span>
        </div></td>
      </tr>
      <tr>
        <td style="padding:0;"><label style="display:block;width:auto;">KID:</label></td>
        <td style="padding:0;"><div class="itxt itxt2col ireadonly">
          <span data-fld="AS[kid]"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['kid'], ENT_QUOTES, 'UTF-8', true);?>
</span>
        </div></td>
      </tr>
    </table>
    <br>
    <h2 style="margin:0;">Lieferadresse</h2>
    <table>
      <tr>
        <td style="padding:0;width:200px;"><label style="display:block;width:auto;">Stra&szlig;e &amp; Nr:</label></td>
        <td style="padding:0;width:250px;"><div class="itxt itxt2col ireadonly">
          <span data-fld="AS[strasse]"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['strasse'], ENT_QUOTES, 'UTF-8', true);?>
</span>
        </div></td>
      </tr>
      <tr>
        <td style="padding:0;"><label style="display:block;width:auto;">PLZ:</label></td>
        <td style="padding:0;"><div class="itxt itxt2col ireadonly">
          <span data-fld="AS[plz]"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['plz'], ENT_QUOTES, 'UTF-8', true);?>
</span>
        </div></td>
      </tr>
      <tr>
        <td style="padding:0;"><label style="display:block;width:auto;">Ort:</label></td>
        <td style="padding:0;"><div class="itxt itxt2col ireadonly">
          <span data-fld="AS[ort]"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['ort'], ENT_QUOTES, 'UTF-8', true);?>
</span>
        </div></td>
      </tr>
      <tr>
        <td style="padding:0;"><label style="display:block;width:auto;">Land:</label></td>
        <td style="padding:0;"><div class="itxt itxt2col ireadonly">
          <span data-fld="AS[land]"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['land'], ENT_QUOTES, 'UTF-8', true);?>
</span>
        </div></td>
      </tr>
    </table>
    <br>
  <?php if (!empty($_smarty_tpl->tpl_vars['AS']->value['ansprechpartner']) || !empty($_smarty_tpl->tpl_vars['AS']->value['ansprechpartner_fon'])) {?>
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
        <br clear="all" >
    <br>
<?php if (0) {?>
    <?php $_smarty_tpl->_subTemplateRender("file:property_umzugsformular_mitarbeiterauswahl.tpl.read.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
if (0) {?>
    <?php $_smarty_tpl->_subTemplateRender("file:property_umzugsformular_geraeteauswahl.tpl.read.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
if (1) {?>
    <?php $_smarty_tpl->_subTemplateRender("file:property_umzugsformular_leistungsauswahl.tpl.read.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}?>

<?php if (!empty($_smarty_tpl->tpl_vars['UmzugsAnlagen']->value)) {?>
    <?php $_smarty_tpl->_subTemplateRender("file:umzugsformular_attachments.tpl.read.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}?>

  <?php if (0 && $_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'] == "angeboten") {?>
    <form name="frmUmzugsantrag" method="POST">
    <input type="hidden" name="AS[aid]" value="<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
">

    <div style="margin-top:20px;width:100%;">
  <?php if (0) {?><!--
     --><input type="submit" name="CatchDefaultEnterReturnFalse" onclick="return false;" value="" style="border:0;background:#fff;color:#fff;position:relative;left:-500px;"><!--
     --><input type="submit" onclick="umzugsantrag_save()" style="padding:0 0 9px 0;background:url(images/BtnGrey.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Speichern"><!--
     --><input type="submit" onclick="umzugsantrag_reload()" style="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Neu laden"><!--
     --><input type="submit" onclick="umzugsantrag_add_attachement()" style="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Dateianhänge">
    <br>
  <?php }?>
    <br>

    <strong>Aktuellen Status <span style="color:#00f;"><?php echo $_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'];?>
</span> &auml;ndern</strong>
    <div class="statusConsole" style="xdisplay:none;">
      <input id="btnStatGenJa"
             type="submit"
             class="<?php if ($_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'] != 'angeboten' && ($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'] == 'Ja' || $_smarty_tpl->tpl_vars['AS']->value['bestaetigt'] != 'Init')) {?>cssHide<?php } else { ?>btn green<?php }?>" onclick="umzugsantrag_set_status('genehmigt','Ja')"
             value="Genehmigen">
      <input id="btnStatGenNein"
             type="submit"
             class="<?php if ($_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'] != 'angeboten' && ($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'] == 'Nein' || $_smarty_tpl->tpl_vars['AS']->value['bestaetigt'] != 'Init')) {?>cssHide<?php } else { ?>btn red<?php }?>"
             onclick="umzugsantrag_set_status('genehmigt','Nein')"
             value="Ablehnen">
      <input id="btnStatGenReset"
             type="submit"
             class="<?php if ($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'] == 'Init' || $_smarty_tpl->tpl_vars['AS']->value['bestaetigt'] != 'Init') {?>cssHide<?php } else { ?>btn red<?php }?>"
             onclick="umzugsantrag_set_status('genehmigt','Init')"
             value="Genehmigung aufheben">
    </div>
    <br>
    <div id="BoxBemerkungen">
      <strong>Bemerkungen / Grund für Ablehnung:</strong><br>
      <textarea class="iarea bemerkungen" name="AS[bemerkungen]"></textarea>
    </div>
  </div>

</form>
<?php }?>

<?php if (!empty($_smarty_tpl->tpl_vars['AS']->value['bemerkungen'])) {?>
<div id="BoxBemerkungenHistorie">
  <strong>Bisherige Bemerkungen</strong><br>
  <div id="BemerkungenHistorie"><?php echo nl2br($_smarty_tpl->tpl_vars['AS']->value['bemerkungen']);?>
</div>
</div>
<?php }?>
</div>
<div id="LoadingBar"></div>
</p>
</div>
<?php }
}
