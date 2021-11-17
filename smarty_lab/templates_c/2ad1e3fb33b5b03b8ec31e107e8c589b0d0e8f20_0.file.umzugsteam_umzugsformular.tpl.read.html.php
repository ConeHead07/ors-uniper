<?php
/* Smarty version 3.1.34-dev-7, created on 2021-11-03 16:55:47
  from '/var/www/html/uniper/htdocs/html/umzugsteam_umzugsformular.tpl.read.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_6182b103c97893_44480492',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2ad1e3fb33b5b03b8ec31e107e8c589b0d0e8f20' => 
    array (
      0 => '/var/www/html/uniper/htdocs/html/umzugsteam_umzugsformular.tpl.read.html',
      1 => 1635951540,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:umzugsteam_umzugsformular_lieferauswahl.tpl.html' => 1,
    'file:admin_umzugsformular_mitarbeiterauswahl.tpl.html' => 1,
    'file:admin_umzugsformular_geraeteauswahl.tpl.html' => 1,
    'file:admin_umzugsformular_ortsauswahl.tpl.html' => 1,
    'file:umzugsteam_umzugsformular_leistungsauswahl.tpl.read.html' => 1,
    'file:umzugsformular_attachments.tpl.read.html' => 1,
    'file:umzugsformular_attachments.tpl.read2.html' => 1,
    'file:admin_umzugsformular_gruppierung.tpl.html' => 1,
  ),
),false)) {
function content_6182b103c97893_44480492 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/uniper/htdocs/smarty3/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<link rel="STYLESHEET" type="text/css" href="../css/SelBox.easy.css">
<link rel="STYLESHEET" type="text/css" href="css/SelBox.easy.css">

<div id="SysInfoBox"></div>

<link rel="stylesheet" type="text/css" href="css/umzugsformular.css">
<link rel="stylesheet" type="text/css" href="../css/umzugsformular.css">
<!-- MODUL UEBERSCHRIFTENBOX 109099 BEGIN --> 
<div class="divModuleBasic padding6px width5Col heightAuto colorContentMain"> 
<h1><span class="spanTitle">Leistungsanforderung #<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
</span></h1> 
<p>
<div id="Umzugsantrag" class="divInlay"> 
  <h2 style="margin:0;">Auftragsdaten</h2>
  <?php if ($_smarty_tpl->tpl_vars['umzugsstatus']->value == "abgeschlossen") {?>
  <div>
    <h2>Der Auftrag wurde bereits abgeschlossen!</h2>
  </div>
  <?php }?>
  <table border=0 cellspacing=1 cellpadding=1>
    <tr>
      <td style="padding:0;height:auto;width:200px;"><label style="display:block;width:auto;">Ausf&uuml;hrungstermin:</label></td>
      <td style="padding:0;width:250px;"><?php echo smarty_modifier_date_format(htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['umzugstermin'], ENT_QUOTES, 'UTF-8', true),"%d.%m.%Y");?>
</td>
    </tr>
    <tr>
      <td style="padding:0;height:auto;"><label style="display:block;width:auto;">Ausf&uuml;hrungszeit:</label></td>
      <td style="padding:0;"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['umzugszeit'], ENT_QUOTES, 'UTF-8', true);?>
</td>
    </tr>

    <tr>
      <td style="padding:0;height:auto;"><label style="display:block;width:auto;">Antragsdatum:</label></td>
      <td style="padding:0;"><?php echo smarty_modifier_date_format(htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['antragsdatum'], ENT_QUOTES, 'UTF-8', true),"%d.%m.%Y");?>
</td>
    </tr>

    <tr>
      <td style="padding:0;"><label style="display:block;width:auto;">Bestätigt:</label></td>
      <td style="padding:0;" class="status_<?php echo $_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'];?>
"><img id="imgStatGen" src="images/status_<?php echo mb_strtolower($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'], 'UTF-8');?>
.png"><span id="txtStatGen"><?php if ($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'] != "Init") {?> <?php echo $_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'];?>
 am <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br_am'],"%d.%m.%Y %H:%M");?>
 <?php echo $_smarty_tpl->tpl_vars['AS']->value['genehmigt_br_von'];
}?></span></td>
    </tr>

    <tr>
      <td style="padding:0;"><label style="display:block;width:auto;">Abgeschlossen:</label></td>
      <td style="padding:0;" class="status_<?php echo $_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'];?>
"><img id="imgStatAbg" src="images/status_<?php echo mb_strtolower($_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'], 'UTF-8');?>
.png"><span id="txtStatAbg"><?php if ($_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'] != "Init") {?> <?php echo $_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'];?>
 am <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['AS']->value['abgeschlossen_am'],"%d.%m.%Y %H:%M");?>
 <?php echo $_smarty_tpl->tpl_vars['AS']->value['abgeschlossen_von'];
}?></span></td>
    </tr>
    <tr>
      <td style="padding:0;"><label style="display:block;width:auto;">Status:</label></td>
      <td style="padding:0;"><?php if (empty($_smarty_tpl->tpl_vars['AS']->value['angeboten_am']) && htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'], ENT_QUOTES, 'UTF-8', true) == "genehmigt") {?>bestaetigt<?php } else {
echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'], ENT_QUOTES, 'UTF-8', true);
}?></td>
    </tr>
      <!-- SelBox_initLSDefault(initInputObj, initLSName, initMultiple, initLSSearchFields, initLSInputFields) -->
  </table>
  <a href="%WebRoot%sites/umzugsblatt.php?id=<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
" style="display: none" target="_Umzugsblatt<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
"><img src="%WebRoot%images/printer.png" width="16" height="16" alt="">Anforderungsblatt / Druckansicht</a>
    <a href="%WebRoot%sites/lieferschein.php?id=<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
" target="_Lieferschein<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
"><img src="%WebRoot%images/printer.png" width="16" height="16" alt="">Lieferschein (PDF)</a>
  <br>

  <div id="BoxBemerkungen" style="display: none;">
    <strong>Bemerkungen:</strong><br>
    <div id="BemerkungenHistorie"><?php echo nl2br($_smarty_tpl->tpl_vars['AS']->value['bemerkungen']);?>
</div>
    <br>
  </div>
  <div id="BoxLieferhinweise">
    <strong>Lieferhinweise:</strong><br>
    <div id="LieferhinweiseContent"><?php echo nl2br($_smarty_tpl->tpl_vars['AS']->value['lieferhinweise']);?>
</div>
  </div>

  <div style="float:left">
      <h2 style="margin:0;">Lieferdaten</h2>
      <table>
        <tr>
          <td style="padding:0;width:200px;"><label style="display:block;width:auto;">Vor &amp; Nachname:</label></td>
          <td style="padding:0;width:250px;"><span data-fld="AS[vorname]"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['vorname'], ENT_QUOTES, 'UTF-8', true);?>
</span> <span data-fld="AS[name]"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
</span></td>
        </tr>
        <tr>
          <td style="padding:0;"><label style="display:block;width:auto;">E-Mail:</label></td>
          <td style="padding:0;" data-fld="AS[email]"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['email'], ENT_QUOTES, 'UTF-8', true);?>
</td>
        </tr>
        <tr>
          <td style="padding:0;"><label style="display:block;width:auto;">Fon:</label></td>
          <td style="padding:0;" data-fld="AS[fon]"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['fon'], ENT_QUOTES, 'UTF-8', true);?>
</td>
        </tr>
          <!-- SelBox_initLSDefault(initInputObj, initLSName, initMultiple, initLSSearchFields, initLSInputFields) -->
      </table>
      <br>
  </div>
  <?php if (!empty($_smarty_tpl->tpl_vars['DL']->value) && !empty($_smarty_tpl->tpl_vars['DL']->value['Firmenname'])) {?>
  <div style="float:left; margin-left:50px;">
      <?php $_smarty_tpl->_subTemplateRender("file:umzugsteam_umzugsformular_lieferauswahl.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
  </div>
  <?php }?>

  <div clear= "all" style="clear:both;"></div>

  <?php if (0) {?>
      <?php $_smarty_tpl->_subTemplateRender("file:admin_umzugsformular_mitarbeiterauswahl.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
  <?php }?>
  <?php if (0) {?>
      <?php $_smarty_tpl->_subTemplateRender("file:admin_umzugsformular_geraeteauswahl.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
  <?php }?>
  <?php if (0) {?>
      <?php $_smarty_tpl->_subTemplateRender("file:admin_umzugsformular_ortsauswahl.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
  <?php }?>
  <?php if (1) {?>
      <?php $_smarty_tpl->_subTemplateRender("file:umzugsteam_umzugsformular_leistungsauswahl.tpl.read.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
  <?php }?>
  <?php if (!empty($_smarty_tpl->tpl_vars['UmzugsAnlagen']->value)) {?>
    <?php $_smarty_tpl->_subTemplateRender("file:umzugsformular_attachments.tpl.read.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('internal'=>1), 0, false);
?>
  <?php }?>
  <br>
  <?php if (!empty($_smarty_tpl->tpl_vars['UmzugsAnlagenIntern']->value)) {?>
    <?php $_smarty_tpl->_subTemplateRender("file:umzugsformular_attachments.tpl.read2.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('UmzugsAnlagen'=>$_smarty_tpl->tpl_vars['UmzugsAnlagenIntern']->value,'internal'=>1), 0, false);
?>
  <?php }?>

  <?php if (0) {?>
  <div style="width:100%">
      <?php $_smarty_tpl->_subTemplateRender("file:admin_umzugsformular_gruppierung.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
  </div>
  <?php }?>

  <div id="LoadingBar"></div>

  <style>
    #KundenabnahmeBox label {
      width:initial;
    }
    #KundenabnahmeBox label,
    #KundenabnahmeBox input {
      background-color: initial;
      border: initial;
      color: initial;
    }
    #KundenabnahmeBox label + input {
      margin-right:.5rem;
    }
    .canvas-sig,
    .m-signature-pad--body canvas {
      position: relative;
      left: 0;
      top: 0;
      width: 99%;
      height:200px;
      border: 1px solid #CCCCCC;
      box-sizing: border-box;
      background-color: #eaf9ff;
    }

    #DialogBackLayer,
    .dialog-back-layer {
      position: fixed;
      top: 0;
      width: 100vw;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(0, 0, 0, 0.75);
      z-index: 9999;
      box-sizing: border-box;
    }
    #DialogWrapper,
    .dialog-wrapper
    {
      border: 3px solid #0078dc;
      padding: 0.5rem;
      background-color: #ffffff;
      border-radius: .5rem;
      box-sizing: border-box;
    }
    .dialog-back-layer,
    .signature-dialog {
      display: none;
    }
    .signature-dialog-active {
      display: flex;
    }
    .doc-body-no-scrollbars {
      overflow: hidden !important;
    }
    form#frmAbnahme .input-bg-color {
      /* background-color: #eaf9ff; */
    }
  </style>
  <div id="KundenabnahmeBox" <?php if ($_smarty_tpl->tpl_vars['umzugsstatus']->value == "abgeschlossen") {?>style="display:none"<?php }?>>
    <h2>Kundenabnahme</h2>

    <form id="frmAbnahme" class="w3-container" action="%WebRoot%lieferscheinabnahme.php" method="POST"
          name="abnahme" enctype="multipart/form-data" target="_self">
      <input type="hidden" name="aid" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['aid'], ENT_QUOTES, 'UTF-8', true);?>
">
      <div>
        Die Ware wurde ordnungsgemäß geliefert und in einwandfreiem Zustand montiert. Ebenfalls bestätigen Sie hiermit, dass durch
        uns keine Schäden an Ihrem Gebäude und Ihren Räumlichkeiten entstanden sind. Sollten Schäden entstanden sein, notieren
        Sie diese bitte auf dem beiliegendem Reklamationsformular.
      </div>
      <div>
        <div style="display: inline-block;width:14rem;height:2rem;font-weight:bold;">
          <label style="width:initial;margin:initial;text-align: left;">Ihr Montageteam der merTens AG</label>
        </div>
        <div id="imgSignatureBoxMertens"
             class="input-bg-color"
             style="display: inline-flex;width:15rem;height:2rem;border-bottom:2px solid black;">
          <img id="imgSignatureMertens" src="" style="max-height:2rem;max-width:15rem;display:none;align-self: flex-end">
          <input id="lsSignatureMertens" type="hidden" name="sig_mt_dataurl" value="">
          <input id="lsSignatureMertensGeodata" type="hidden" name="sig_mt_geodata" value="">
          <input id="lsSignatureMertensCreated" type="hidden" name="sig_mt_created" value="">
        </div>
      </div>

      <div>
        <label style="margin-right:3rem;width:initial;">Ankunft <input name="ankunft" id="ankunftsZeit" type="time" size="8" class="input-bg-color" > Uhr</label>
        <label style="margin-right:3rem;width:initial;">Abfahrt <input name="abfahrt" id="abfahrtsZeit" type="time" size="8" class="input-bg-color" > Uhr</label>
      </div>
      <div>
        <div><label style="margin-right:3rem;width:initial;" class="input-bg-color" > Etikettierung erfolgt</label></div>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['Umzugsleistungen']->value, 'L', false, NULL, 'GList', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['L']->value) {
?>
        <?php if ($_smarty_tpl->tpl_vars['L']->value['kategorie_id'] == "18" || $_smarty_tpl->tpl_vars['L']->value['kategorie_id'] == "25") {?>
        <?php continue 1;?>
        <?php }?>
        <label style="margin-right:3rem;width:initial;" class="input-bg-color" >
          <input name="etikettierung_erfolgt[<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['leistung_id'], ENT_QUOTES, 'UTF-8', true);?>
]"
                 data-label="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['kategorie'], ENT_QUOTES, 'UTF-8', true);?>
"
                 id="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['kategorie'], ENT_QUOTES, 'UTF-8', true);?>
"
                 value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['kategorie'], ENT_QUOTES, 'UTF-8', true);?>
" type="checkbox"> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['kategorie'], ENT_QUOTES, 'UTF-8', true);?>

        </label>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
      </div>

      <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['Umzugsleistungen']->value, 'L', false, NULL, 'GList', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['L']->value) {
?>
        <?php if ($_smarty_tpl->tpl_vars['L']->value['kategorie'] == "Schreibtisch") {?>
          <div>
            <div>
              <label style="margin-right:3rem;width:initial;" class="input-bg-color" >
              Funktionsprüfung erfolgt</label>
            </div>
            <label style="margin-right:3rem;width:initial;" class="input-bg-color" >
              <input name="funktionspruefung_erfolgt[]"
                     data-label="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['kategorie'], ENT_QUOTES, 'UTF-8', true);?>
"
                     id="Schreibtischpruefung"
                     value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['kategorie'], ENT_QUOTES, 'UTF-8', true);?>
" type="checkbox"> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['kategorie'], ENT_QUOTES, 'UTF-8', true);?>

            </label>
          </div>
          <?php break 1;?>
        <?php }?>
      <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

      <div>
        <div style="float:left;">
          <div
                  class="input-bg-color"
                  style="border-bottom:2px solid black;height:2.5rem;width:8rem;display: inline-flex;">
            <input id="abnahmeLieferdatum" type="date" name="lieferdatum" style="width:100%;align-self: flex-end">
          </div>
          <div>(Datum)</div>
        </div>
        <div style="float:left;margin-left:2rem;">
          <div
                  class="input-bg-color"
                  style="border-bottom:2px solid black;height:2.5rem;width:14rem;display: inline-flex;">
            <input id="lsSignatureUnterzeichner" type="text" name="sig_ma_unterzeichner"
                   value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['vorname'], ENT_QUOTES, 'UTF-8', true);?>
 <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
"
                   style="width:100%;align-self: flex-end">
          </div>

          <div>(Name Kunde in Blockbuchstaben)</div>
        </div>
        <div style="float:left;margin-left:2rem;">
          <div id="imgSignatureBoxMA"
               class="input-bg-color"
               style="border-bottom:2px solid black;height:2.5rem;display: inline-flex;width:15rem;">
            <img id="imgSignatureMA" src="" style="max-height:2rem;max-width:15rem;display:none;align-self: flex-end">
            <input id="lsSignatureMA" type="hidden" name="sig_ma_dataurl" value="">
            <input id="lsSignatureMAGeodata" type="hidden" name="sig_ma_geodata" value="">
            <input id="lsSignatureMACreated" type="hidden" name="sig_ma_created" value="">
          </div>
          <div>(Unterschrift)</div>
        </div>
        <div style="clear: both"></div>
      </div>
      <button id="btnSubmit" class="btn blue btn-submit" type="button" onclick="return false;">Speichern</button>
      <div id="output"></div>
    </form>
  </div>

</div>

<?php echo '<script'; ?>
 src="%WebRoot%js/signature_pad.js"><?php echo '</script'; ?>
>

  <div id="SigDialogMertens" class="signature-dialog dialog-back-layer">
    <div class="dialog-wrapper" style="width:90vw">
      <div id="signature-pad-mertens" style="display:none;width:90%;" date-refpad="" data-refimage="#imgSignatureMertens" data-refinput="#lsSignatureMertens" class="signature-pad m-signature-pad">
        <div style="font-weight:bold;">Montageteam der merTens AG</div>
        <div class="signature-pad--body m-signature-pad--body">
          <canvas id="canvas-sig-mertens" class="canvas-sig canvas-sig-mertens"></canvas>
        </div>
        <div style="text-align: center">
          <button type="button" onclick="cancelSignaturePad('#signature-pad-mertens');return false;" class="btn gray btn-cancel">Abbrechen</button>
          <button type="button" onclick="loeschenSignaturePad('#signature-pad-mertens');return false;" class="btn red btn-clear">Leeren</button>
          <button type="button" onclick="submitSignature('#signature-pad-mertens');return false;" class="btn green btn-apply">OK</button>
        </div>
      </div>

    </div>
  </div>

  <div id="SigDialogMA" class="signature-dialog dialog-back-layer">
    <div class="dialog-wrapper">
      <div id="signature-pad-ma" style="display:none;width:90vw;" data-refpad="" data-refimage="#imgSignatureMA" data-refinput="#lsSignatureMA" class="signature-pad m-signature-pad">
        <div style="font-weight:bold;">Name Kunde Blockbuchstaben / Unterschrift</div>
        <div class="signature-pad--body m-signature-pad--body">
          <canvas id="canvas-sig-ma" class="canvas-sig canvas-sig-ma"></canvas>
        </div>
        <div style="text-align: center">
          <button type="button"
                  class="btn gray btn-cancel btn-sig-cancel"
                  onclick="cancelSignaturePad('#signature-pad-ma');return false;">Abbrechen</button>
          <button type="button"
                  class="btn red btn-clear btn-sig-erase"
                  onclick="loeschenSignaturePad('#signature-pad-ma');return false;">Leeren</button>
          <button type="button"
                  class="btn green btn-apply btn-sig-apply"
                  onclick="submitSignature('#signature-pad-ma');return false;">OK</button>
        </div>
      </div>

    </div>
  </div>

  <div style="display:none;" id="statusCheck">Browser-Status-Check: <span id="browserOnlineStatus"></span></div>

  <div id="signature-pad-test" style="width:90vw;display:none;" data-refpad="" data-refimage="#imgSignatureMA" data-refinput="#lsSignatureMA" class="signature-pad m-signature-pad">
    <div class="signature-pad--body m-signature-pad--body">
      <canvas id="canvas-sig-test" class="canvas-sig canvas-sig-test"></canvas>
    </div>
  </div>

</div>

<?php echo '<script'; ?>
>
    var aid = <?php echo json_encode($_smarty_tpl->tpl_vars['AS']->value['aid']);?>
;
    function escapeSelector(name) {
      return name.replaceAll('[', '\\[').replaceAll(']', '\\]');
    }
    

    $(function() {
      if ($("#abnahmeLieferdatum").val() === '') {
        var heute = new Date();
        var defautLieferdatum = heute.getFullYear() + "-" +
                (heute.getMonth() < 9 ? '0' : '') + (heute.getMonth() +1) + "-" +
                (heute.getDate() < 10 ? '0' : '') + heute.getDate();
        $("#abnahmeLieferdatum").val(defautLieferdatum);
      }
      // SignaturePad.prototype.getPlainCanvasElement = function() {
      //   return this._canvas;
      // };
      //
      // SignaturePad.prototype.getCanvasElement = function() {
      //   return $(this._canvas);
      // };
      //
      // SignaturePad.prototype.getOuterBox = function() {
      //   return $(this._canvas).closest("div.signature-pad");
      // };
      //
      // SignaturePad.prototype.getOuterBody = function() {
      //   return $(this._canvas).closest("div.signature-pad-body");
      // };
      //
      // SignaturePad.prototype.getTargetInput = function() {
      //   var outerBox = this.getOuterBox();
      //   var refSelector = outerBox.data("refinput");
      //   if (refSelector) {
      //     return $(refSelector);
      //   }
      //   return null;
      // };
      //
      // SignaturePad.prototype.getTargetImage = function() {
      //   var outerBox = this.getOuterBox();
      //   var refSelector = outerBox.data("refimage");
      //   if (refSelector) {
      //     return $(refSelector);
      //   }
      //   return null;
      // };
      console.log('Initially ' + (window.navigator.onLine ? 'on' : 'off') + 'line');

      $(window).on('online', function() {
        console.log('Became online');
        $("#browserOnlineStatus").text("ONLINE");
      });
      $(window).on('offline', function() {
        console.log('Became offline');
        $("#browserOnlineStatus").text("OFFLINE");
      });

      document.getElementById('statusCheck').addEventListener('click', function() {
        var isOnline = window.navigator.onLine;
        console.log('window.navigator.onLine is ' + isOnline);
        $("#browserOnlineStatus").text(isOnline ? "ONLINE" : "OFFLINE");
      });

      var createSignaturPad = function(containerSelector, options) {
        var defaults = {
          minWidth: 1.5, // minimale Breite des Stiftes
          maxWidth: 6, // maximale Breite des Stiftes
          penColor: "#000000", // Stiftfarbe
          backgroundColor: '#FFFFFF'
        };
        var conf = defaults;
        if (options !== undefined && options !== null && typeof options === "object") {
          if ("minWidth" in options && !isNaN(options.minWidth) && options.minWidth > 0) {
            conf.minWidth = options.minWidth;
          }
          if ("maxWidth" in options && !isNaN(options.maxWidth) && options.maxWidth > 0) {
            conf.maxWidth = options.maxWidth;
          }
          if (conf.maxWidth < conf.minWidth) {
            conf.maxWidth = conf.minWidth;
          }
          if ("penColor" in options && typeof options.penColor === "string") {
            conf.penColor = options.penColor;
          }
          if ("backgroundColor" in options && typeof options.backgroundColor === "string") {
            conf.backgroundColor = options.backgroundColor;
          }
        }
        var container = $(containerSelector);
        if (container.length === 0) {
          console.error('Signatur-Pad Container not found by containerSelector', { containerSelector });
          return;
        }
        if (container.length > 1) {
          console.error('containerSelector selektiert mehr als ein Element', { containerSelector });
          return;
        }

        var jSigBody = container.find("[class$=body]");
        var jCanvas = container.find("canvas");

        if (jSigBody.length === 0) {
          jSigBody = $("<div/>").addClass("m-signature-pad--body").appendTo(container);
          if (jCanvas.length === 1) {
            jCanvas.appendTo(jSigBody);
          }
        }

        if (jCanvas.length === 0) {
          jCanvas = $("<canvas/>").appendTo(jSigBody);
        }

        var signaturePad = new SignaturePad(jCanvas.get(0), conf);
        container.data('refpad', signaturePad);

        $(window).on("resize", onResizeCanvas.bind(window, signaturePad));
        onResizeCanvas(signaturePad);

        console.log("createSignaturePad", { wrapper: container, jSigBody, jCanvas, signaturePad } );

        var targetImage = signaturePad.getTargetImage();
        if (targetImage && targetImage.length) {
          var targetImageParent = targetImage.parent();
          // targetImage.off("click").on("click", function() { showSignatureDialog(containerSelector); });
          targetImageParent.off("click").on("click", function() { showSignatureDialog(containerSelector); });
        }
        return signaturePad;
      };

      $(".signature-dialog").appendTo("body");
      createSignaturPad('#signature-pad-mertens');
      createSignaturPad('#signature-pad-ma');
      var canvasTest = document.getElementById('canvas-sig-test');
      if (canvasTest) {
        var sigPadTest = new SignaturePad(canvasTest);
        onResizeCanvas(sigPadTest);
      }

      // $("#imgSignatureBoxMertens").on("click", function() {
      //   showSignatureDialog('#signature-pad-mertens');
      // });
      // $("#imgSignatureBoxMA").on("click", function() {
      //   showSignatureDialog('#signature-pad-ma');
      // });

    });

    function onResizeCanvas(sigPad) {
      var oldContent = sigPad.toData();
      var sigPadCanvas = sigPad.getPlainCanvasElement();
      var ratio = Math.max(window.devicePixelRatio || 1, 1);
      sigPadCanvas.width = sigPadCanvas.offsetWidth * ratio;
      sigPadCanvas.height = sigPadCanvas.offsetHeight * ratio;
      sigPadCanvas.getContext("2d").scale(ratio, ratio);
      sigPad.clear();
      sigPad.fromData(oldContent);
    };

    function releaseContainerFromOverlay(container) {
      var originParent = container.data("originParent");
      var originPrev = container.data("originPrev");
      if (originPrev && originPrev.length && originParent && originParent.length) {
        if (originParent.get(0) === originPrev.parent().get(0)) {
          container.insertAfter(originPrev);
          return true;
        }
      }
      if (originParent && originParent.length === 1) {
        container.appendTo(originParent);
        return true;
      }
      if (originPrev && originPrev.length === 1) {
        container.insertAfter(originPrev);
        return true;
      }
      container.appendTo("body");
    }

    function showSignatureDialog(containerSelector) {

      var container = $(containerSelector);
      $("body").addClass("doc-body-no-scrollbars");
      $(".signature-dialog.signature-dialog-active").removeClass("signature-dialog-active");
      container.show().closest(".signature-dialog").addClass("signature-dialog-active");
      var signaturePad = container.data("refpad");
      console.log("showSignatureDialog: ", { containerData: container.data(), signaturePad })
      onResizeCanvas(signaturePad);
      return;
    }

    function createSignatureDialog(containerSelector) {

      var container = $(containerSelector);
      var overlayId = "DialogBackLayer";
      var overlay = $("#" + overlayId);
      var dialogWrapperId = "DialogWrapper";
      var dialogWrapper = $("#" + dialogWrapperId);

      var containerOriginPrev = container.data("originPrev");
      var containerOriginParent = container.data("originParent");

      if (!containerOriginParent) {
        container.data("originParent", container.parent());
      }

      if (!containerOriginPrev) {
        container.data("originPrev", container.prev());
      }

      if (overlay.length === 0) {
        overlay = $("<div/>")
                .attr({id: overlayId })
                .addClass(".dialog-back-layer")
                .appendTo("body");
      }

      if (dialogWrapper.length === 0) {
        dialogWrapper = $("<div/>")
                .attr({id: dialogWrapperId })
                .addClass(".dialog-wrapper")
                .appendTo(overlay);
      } else {
        dialogWrapper.find("*").each(function() {
          var originParent = $(this).data("originParent");
          var originPrev = $(this).data("originPrev");
          if (originParent.length > 0 || originPrev.length > 0) {
            releaseContainerFromOverlay( $(this) );
          }
        });
      }
      console.log('showSignatureDialog: ', { containerSelector, container, overlayId, overlay });
      overlay.show();
      dialogWrapper.show();

      container.appendTo(dialogWrapper)
              .css({
                width: "90vw"
              })
              .show();
    }

    function hideSignatureDialog(containerSelector) {

      var container = $(containerSelector);
      $("body").removeClass("doc-body-no-scrollbars");
      $(".signature-dialog.signature-dialog-active").removeClass("signature-dialog-active");
      container.hide().closest(".signature-dialog").removeClass("signature-dialog-active");
      return;

      var container = $(containerSelector);
      var overlayId = "DialogBackLayer";
      var overlay = $("#" + overlayId);
      console.log('hideSignatureDialog: ', { containerSelector, container, overlayId, overlay });
      container.hide();
      overlay.hide();
      releaseContainerFromOverlay( container );
    }

    function cancelSignaturePad(selector) {
      hideSignatureDialog(selector);
    }

    function getLocation(fnSuccessPos) {
      var options = {
        enableHighAccuracy: true,
        timeout: 5000,
        maximumAge: 0
      };

      var error = function() {
        console.error('GEO-ACCESS-ERROR', arguments);
      };

      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(fnSuccessPos, error, options);
      } else {
        console.error('No GEO-Access!');
      }
    }

    function getDbDateTimeString() {
      var d = new Date();
      if (d.toLocaleDateString && d.toLocaleTimeString) {
        var dtParts = d.toLocaleDateString().split('.').reverse();
        if (dtParts[1].length < 2) dtParts[1] = '0' + dtParts[1];
        if (dtParts[2].length < 2) dtParts[2] = '0' + dtParts[2];
        return dtParts.join('-') + " "  + d.toLocaleTimeString();
      }
      var Y = d.getFullYear();
      var m = (d.getMonth() < 9 ? '0' : '') + (d.getMonth() + 1).toString(10);
      var d = (d.getDate() < 10 ? '0' : '') + d.getDate().toString(10);
      var H = (d.getHours() < 10 ? '0' : '') + d.getHours().toString(10);
      var i = (d.getMinutes() < 10 ? '0' : '') + d.getMinutes().toString(10);
      var s = (d.getSeconds() < 10 ? '0' : '') + d.getSeconds().toString(10);

      return [Y, m, d].join('-') + ' ' + [H, i, s].join(':');

    }

    function submitSignature(selector) {
      var container = $(selector);
      var signaturePad = container.data("refpad");
      var img = signaturePad.getTargetImage();
      var input = signaturePad.getTargetInput();
      var inputGeo = $("#" + input.attr("id") + "Geodata");
      var inputTime = $("#" + input.attr("id") + "Created");

      //Unterschrift in verstecktes Feld übernehmen
      // var imgDataUrl = signaturePad.toDataURL();
      var imgDataUrl = signaturePad.getDataUrlOfPaintedArea();

      if (input && input.length) {
        input.val(imgDataUrl != "data:" ? imgDataUrl : '');
        inputGeo.val('');
        inputTime.val('');
      } else {
        console.error('input not found', { selector, container, signaturePad, img, input });
      }

      if (img && img.length) {
        if (imgDataUrl && (typeof imgDataUrl === 'string') && imgDataUrl.length > 6) {
          img.attr({src: imgDataUrl}).show();
          var dt = getDbDateTimeString();
          inputTime.val(dt);
          getLocation(function getPosition(position) {
            var geodata = {
              latitude: position.coords.latitude,
              longitude: position.coords.longitude,
              altitude: position.coords.altitude,
              accuracy: position.coords.accuracy,
              speed: position.coords.speed,
              timestamp: position.timestamp
            };
            inputGeo.val(JSON.stringify(geodata));
            console.log('getLocation callback position:', { arguments, position });
          });

        } else {
          console.error('Nothing to write as Signatur-Image: ', { imgDataUrl });
          img.attr({src: ''}).hide();
        }
      } else {
        console.error('img not found', { selector, container, signaturePad, img, input });
      }
      hideSignatureDialog(selector);
    }
    function loeschenSignaturePad(selector) {
      var container = $(selector);
      var signaturePad = container.data("refpad");
      var img = signaturePad.getTargetImage();
      var input = signaturePad.getTargetInput();
      var inputGeo = $("#" + input.attr("id") + "Geodata");
      var inputTime = $("#" + input.attr("id") + "Created");

      signaturePad.clear();

      if (input && input.length) {
        input.val('');
        inputGeo.val('');
        inputTime.val('');
      } else {
        console.error('input not found', { selector, container, signaturePad, img, input });
      }
      if (img && img.length) {
        img.hide().attr({src: ''});
      } else {
        console.error('img not found!', { selector, container, signaturePad, img, input });
      }
    }

    function getLieferscheinDaten(aid) {
      var storageKey = 'lieferschein_' + aid;
      var item = localStorage.getItem(storageKey);
      if (item === null) {
        return {};
      } else {
        return JSON.parse(item);
      }
    }
    function setLieferscheinDaten(aid, daten) {
      var storageKey = 'lieferschein_' + aid;
      daten.aid = aid;
      localStorage.setItem(storageKey, JSON.stringify(daten));
    }
    function addLieferscheinDaten(aid, name, value) {
      var daten = getLieferscheinDaten(aid);
      daten.aid = aid;
      daten[name] = value;
      localStorage.setItem(storageKey, JSON.stringify(daten));
    }
    function removeLieferscheinByAID(aid) {
      var storageKey = 'lieferschein_' + aid;
      localStorage.removeItem(storageKey);
    }

    function lieferscheinSpeichern() {
      var frm = $("#frmAbnahme");
      var formData = new FormData(frm.get(0));

      var daten = {};
      formData.forEach(function(value, name) {
        daten[name] = value;
        console.log({name, value});
      });

      if (aid && (!daten.aid || isNaN(daten.aid))) {
        daten.aid = aid;
      }

      if (!daten.aid) {
        var error = "Lieferschein kann nicht ohne Auftrags-ID zwichengespeichert werden!";
        console.error(error);
        alert(error);
        return false;
      }

      setLieferscheinDaten(aid, daten);

    }

    function lieferscheinVonLocalStorageLaden() {
      var frm = $("#frmAbnahme");
      var daten = getLieferscheinDaten(aid);

      if (!daten) {
        console.log('Es existieren keine Lieferschein-Daten im LocalStorage für die AID' + aid);
        return false;
      }

      for(var name in daten) {
        if (!daten.hasOwnProperty(name)) {
          continue;
        }
        name.replace('[', '\\[');
        var input = frm.find("input[name=" + escapeSelector(name) + "]");
        if (input.length === 1) {
          var type = input.attr("type");
          if (['radio', 'checkbox'].indexOf(type) > -1 ) {
            input.prop("checked", true);
          }
          input.val(daten[name]);
        }
      }

      var showImgMt = ("sig_mt_dataurl" in daten && daten['sig_mt_dataurl'].length > 6);
      $("#imgSignatureMertens").attr("src", showImgMt ? daten["sig_mt_dataurl"] : "NOT-FOUND" ).toggle(showImgMt);

      var showImgMa = ("sig_ma_dataurl" in daten && daten['sig_ma_dataurl'].length > 6);
      $("#imgSignatureMA").attr("src", showImgMa ? daten["sig_ma_dataurl"] : "NOT-FOUND" ).toggle(showImgMa);
    }

    function submitAbnahme() {
      var frm = $("#frmAbnahme");
      var errors = '';
      var warnings = '';
      var aid = frm.find("input[name=aid]").val();

      var sigMt = frm.find("input[name=sig_mt_dataurl]").val();
      var sigMa = frm.find("input[name=sig_ma_dataurl]").val();

      var ankunft = frm.find("input[name=ankunft]").val();
      var abfahrt = frm.find("input[name=abfahrt]").val();
      var lieferdatum = frm.find("input[name=lieferdatum]").val();

      var etikettierung = frm.find("input[name=etikettierung_erfolgt]");
      var leistungen = frm.find("input[name^=leistung]");
      var etikettiert = frm.find("input[name^=etikettierung_erfolgt]");
      var checkedLength = etikettiert.filter(function() { return this.checked; }).length;

      var leistungenLabels = [];
      leistungen.each(function() { leistungenLabels.push( $(this).data("label")  )});

      console.log({
        aid,
        sigMt,
        sigMa,
        ankunft,
        abfahrt,
        lieferdatum,
        etikettierung,
        leistungen,
        checkedLength,
        leistungenLabels
      });

      if (!aid || isNaN(aid)) {
        alert("Bitte Seite neu laden. Es liegt keine Auftrags-ID vor!");
        return false;
      }

      if (!sigMt) {
        errors+= "Die Unterschrift eines merTens-Mitarbeiter fehlt!\n";
      }
      if (!sigMa) {
        errors+= "Die Kunden-Unterschrift fehlt!\n";
      }
      if (!lieferdatum) {
        errors+= "Angabe Lieferdatum (Datum) fehlt!\n";
      }
      if (!ankunft) {
        errors+= "Angabe Ankunftszeit fehlt!\n";
      }
      if (!abfahrt) {
        errors+= "Angabe Abfahrtszeit fehlt!\n";
      }
      if (ankunft >= abfahrt) {
        errors+= "Die Abfahrtszeit kann nicht vor der Ankunftszeit liegen!\n";
      }
      if (!checkedLength) {
        errors+= "Es wurde kein Artikel als etikettiert markiert:\n";
        errors+= " - " + leistungenLabels.join("\n - ") + "\n";
      }

      if (errors) {
        alert("Der Lieferschein wurde nicht vollständig ausgefüllt.\n\n" + errors);
        return false;
      }

      if (etikettierung.length && !etikettierung.prop("checked")) {
        warnings = "Die Etikettierung wurde nicht bestätigt.\n";
      }
      if (checkedLength < leistungen.length) {
        var missingChecks = leistungen.length - checkedLength;
        warnings+= "Die Lieferung von " + missingChecks + " Artikeln wurde nicht bestätigt.\n";
      }

      if (warnings) {
        var warningsQuestion = "Möchten Sie trotz unvollständiger Angaben den Lieferschein so abnehmen?\n";
        if ( !confirm(warningsQuestion + warnings) ) {
          return false;
        }
      }
      var formData = new FormData(frm.get(0));
      lieferscheinSpeichern();

      if (!window.navigator.onLine) {
        alert("Aktuell besteht keine Internetverbindung um die Daten zum Server zu übertragen!");
        return;
      }

      frm.find("#btnSubmit").prop("disabled", true);
      $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: frm.attr("action"),
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        success: function (data) {
          $("#output").html('');
          console.log("SUCCESS : ", data);
          if (data.type === 'success') {
            frm.find("#btnSubmit")
                    .text('Auftrag wurde abgeschlossen')
                    .prop("disabled", true);
            if ('msg' in data) {
              $("#output").addClass("success").removeClass("error").html( data.msg.split("\n").join("<br>") );
            }
          } else {
            var errMsg = '';
            if (typeof data === 'object' && 'errors' in data) {
              errMsg = (Array.isArray(data.errors)) ? data.errors.join("\n") : JSON.stringify(data.errors);
            }
            $("#output").addClass("error").removeClass("success").html(errMsg.split("\n").join("<br>") );
            frm.find("#btnSubmit").prop("disabled", false);
          }
        },
        error: function (e) {
          $("#output").addClass("error").removeClass("success").text(e.responseText);
          console.log("ERROR : ", e);
          frm.find("#btnSubmit").prop("disabled", false);
        }
      });

    }

    $("#frmAbnahme").find("#btnSubmit").off("click").on("click", submitAbnahme);
    
  <?php echo '</script'; ?>
>
<?php }
}
