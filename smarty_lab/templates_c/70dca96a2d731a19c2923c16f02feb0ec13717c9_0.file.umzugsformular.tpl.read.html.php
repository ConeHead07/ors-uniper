<?php
/* Smarty version 3.1.34-dev-7, created on 2022-03-07 12:13:02
  from '/var/www/html/html/umzugsformular.tpl.read.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_6225f6ce6f6305_19740286',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '70dca96a2d731a19c2923c16f02feb0ec13717c9' => 
    array (
      0 => '/var/www/html/html/umzugsformular.tpl.read.html',
      1 => 1646650758,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:umzugsformular_mitarbeiterauswahl.tpl.read.html' => 1,
    'file:umzugsformular_geraeteauswahl.tpl.read.html' => 1,
    'file:umzugsformular_leistungsauswahl.tpl.read.html' => 1,
    'file:umzugsformular_attachments.tpl.read.html' => 1,
    'file:umzugsformular_lieferscheine.tpl.read2.html' => 1,
    'file:admin_umzugsformular_teillieferungen.tpl.html' => 1,
    'file:admin_umzugsformular_reklamationen.tpl.html' => 1,
    'file:admin_ordered_rueckholungen.tpl.html' => 1,
    'file:admin_umzugsformular_leistungsauswahl.tpl.html' => 1,
  ),
),false)) {
function content_6225f6ce6f6305_19740286 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/smarty3/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>

<link rel="STYLESHEET" type="text/css" href="{WebRoot}css/SelBox.easy.css?%assetsRefreshId%">
<link rel="stylesheet" type="text/css" href="{WebRoot}/css/umzugsformular.css?%assetsRefreshId%"/>
<?php echo '<script'; ?>
 type="text/javascript" src="{WebRoot}js/FbAjaxUpdater.js?%assetsRefreshId%"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="{WebRoot}js/PageInfo.js?%assetsRefreshId%"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="{WebRoot}js/ObjectHandler.js?%assetsRefreshId%"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="{WebRoot}js/SelBox.easy.js?%assetsRefreshId%"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="{WebRoot}js/umzugsformular.easy.js?%assetsRefreshId%"><?php echo '</script'; ?>
>


<div id="SysInfoBox"></div>


<!-- MODUL UEBERSCHRIFTENBOX 109099 BEGIN --> 
<div class="divModuleBasic padding6px width5Col heightAuto colorContentMain"> 
<h1><span class="spanTitle">Auftrag</span></h1>

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

    <?php if (!empty($_smarty_tpl->tpl_vars['AS']->value['bestaetigt_am'])) {?>
    <tr>
      <td style="padding:0;"><label style="display:block;width:auto;">Best&auml;tigt / Avisiert:</label></td>
      <td style="padding:0;" class="status_<?php echo $_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['AS']->value['bestaetigt'];
if ($_smarty_tpl->tpl_vars['AS']->value['bestaetigt_am']) {?> am <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['AS']->value['bestaetigt_am'],'%d.%m.%Y um %H:%M');?>
 von <?php echo $_smarty_tpl->tpl_vars['AS']->value['bestaetigt_von'];
}?>">
        <div  class="itxt itxt2col ireadonly"><img id="imgStatGen" src="images/status_<?php echo mb_strtolower($_smarty_tpl->tpl_vars['AS']->value['bestaetigt'], 'UTF-8');?>
.png"><span id="txtStatGen"><?php echo $_smarty_tpl->tpl_vars['AS']->value['bestaetigt'];
if ($_smarty_tpl->tpl_vars['AS']->value['bestaetigt_am']) {?> am <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['AS']->value['bestaetigt_am'],"%d.%m. um %H:%M");?>
 von <?php echo $_smarty_tpl->tpl_vars['AS']->value['bestaetigt_von'];
}?></span></div></td>
    </tr>
    <?php }?>

    <?php if (!empty($_smarty_tpl->tpl_vars['AS']->value['abgeschlossen_am'])) {?>
    <tr>
      <td style="padding:0;"><label style="display:block;width:auto;">Abgeschlossen:</label></td>
      <td style="padding:0;" class="status_<?php echo $_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'];?>
">
        <div  class="itxt itxt2col ireadonly">
          <img id="imgStatAbg" src="images/status_<?php echo mb_strtolower($_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'], 'UTF-8');?>
.png"><span id="txtStatAbg"><?php if ($_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'] != "Init") {?> <?php echo $_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'];?>
 am <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['AS']->value['abgeschlossen_am'],"%d.%m.%Y %H:%M");?>
 <?php echo $_smarty_tpl->tpl_vars['AS']->value['abgeschlossen_von'];
}?></span></div></td>
    </tr>
    <?php }?>
    <?php if (!empty($_smarty_tpl->tpl_vars['AS']->value['ref_aid'])) {?>
    <tr>
      <td style="padding:0;width:200px;"><label style="display:block;width:auto;">Bezieht sich auf:</label></td>
      <td style="padding:0;width:250px;"><a href="/?s=kantrag&id=<?php echo $_smarty_tpl->tpl_vars['AS']->value['ref_aid'];?>
"><?php echo $_smarty_tpl->tpl_vars['AS']->value['ref_aid'];?>
</a></td>
    </tr>
    <?php }?>
    <?php if (!empty($_smarty_tpl->tpl_vars['AS']->value['rueckholung_von']) || !empty($_smarty_tpl->tpl_vars['AS']->value['rueckholung_am'])) {?>
    <tr>
      <td style="padding:0;"><label style="display:block;width:auto;">RückholAuftrag:</label></td>
      <td style="padding:0;" class="options-onoff"><label class="itxt itxt2col off active"><?php echo $_smarty_tpl->tpl_vars['AS']->value['rueckholung_am'];?>
 von <?php echo $_smarty_tpl->tpl_vars['AS']->value['rueckholung_von'];?>
</label></td>
    </tr>
    <?php }?>
    <?php if ($_smarty_tpl->tpl_vars['AS']->value['angeboten_von']) {?>
    <tr>
      <td style="padding:0;"><label style="display:block;width:auto;">Angeboten:</label></td>
      <td style="padding:0;"><div class="itxt itxt2col ireadonly"><?php echo $_smarty_tpl->tpl_vars['AS']->value['angeboten_am'];?>
 von <?php echo $_smarty_tpl->tpl_vars['AS']->value['angeboten_von'];?>
</div></td>
    </tr>
    <?php }?>
    <tr>
      <td style="padding:0;width:200px;" valign="top"><label style="display:block;width:auto;">Bisherige Bemerkungen:</label></td>
      <td style="padding:0;"><?php if (empty($_smarty_tpl->tpl_vars['AS']->value['bemerkungen'])) {?><div class="itxt itxt2col ireadonly"><i>Keine</i></div><?php }?></td>
    </tr>
  </table>
  <?php if (!empty($_smarty_tpl->tpl_vars['AS']->value['bemerkungen'])) {?>
  <table width="100%">
    <tr>
      <td style="padding:0;">
        <div id="BemerkungenHistorie" style="resize: vertical;overflow-y: auto; height: 4.5rem;"><?php echo nl2br($_smarty_tpl->tpl_vars['AS']->value['bemerkungen']);?>
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
    <td style="padding:0;width:250px;"><div class="itxt itxt2col ireadonly"><?php if ($_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'] != "beantragt" && $_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'] != "temp") {
echo smarty_modifier_date_format(htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['umzugstermin'], ENT_QUOTES, 'UTF-8', true),"%d.%m.%Y");
}?></div></td>
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
    <?php if ($_smarty_tpl->tpl_vars['AS']->value['autocalc_ref_mengen']) {?>
      <?php $_smarty_tpl->_assignInScope('show_menge_mertens', "0");?>
    <?php } else { ?>
      <?php $_smarty_tpl->_assignInScope('show_menge_mertens', "1");?>
    <?php }?>
    <?php $_smarty_tpl->_subTemplateRender("file:umzugsformular_leistungsauswahl.tpl.read.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('show_menge_mertens'=>$_smarty_tpl->tpl_vars['show_menge_mertens']->value), 0, false);
}?>

<?php if (!empty($_smarty_tpl->tpl_vars['UmzugsAnlagen']->value)) {
$_smarty_tpl->_subTemplateRender("file:umzugsformular_attachments.tpl.read.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}?>

<?php if (!empty($_smarty_tpl->tpl_vars['UmzugLieferscheine']->value)) {?>
<div style="margin-top:1.5rem"></div>
<?php $_smarty_tpl->_subTemplateRender("file:umzugsformular_lieferscheine.tpl.read2.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('UmzugsAnlagen'=>$_smarty_tpl->tpl_vars['UmzugLieferscheine']->value,'internal'=>1), 0, false);
}?>

  <?php if (!empty($_smarty_tpl->tpl_vars['Teillieferungen']->value)) {?>
  <div style="margin-top:1.5rem"></div>
  <fieldset><legend><strong>Teil-Lieferungen</strong></legend>
    <div style="padding:5px">
      <?php $_smarty_tpl->_subTemplateRender("file:admin_umzugsformular_teillieferungen.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('aItems'=>$_smarty_tpl->tpl_vars['Teillieferungen']->value), 0, false);
?>
    </div>
  </fieldset>
  <?php }?>

  <?php if (!empty($_smarty_tpl->tpl_vars['Reklamationen']->value)) {?>
  <div style="margin-top:1.5rem"></div>
  <fieldset><legend><strong>Reklamationen</strong></legend>
    <div style="padding:5px">
      <?php $_smarty_tpl->_subTemplateRender("file:admin_umzugsformular_reklamationen.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('aReklas'=>$_smarty_tpl->tpl_vars['Reklamationen']->value), 0, false);
?>
    </div>
  </fieldset>
  <?php }?>

  <?php if (!empty($_smarty_tpl->tpl_vars['aOrderedRHItems']->value)) {?>
  <div style="margin-top:1.5rem"></div>
  <fieldset><legend><strong>Rückholaufträge</strong></legend>
    <div style="padding:5px">
      <?php $_smarty_tpl->_subTemplateRender("file:admin_ordered_rueckholungen.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('baseService'=>"kantrag",'aItems'=>$_smarty_tpl->tpl_vars['aOrderedRHItems']->value), 0, false);
?>
    </div>
  </fieldset>
  <?php }?>

  <div style="margin-top:1.5rem"></div>
<form action="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
" method="post" name="frmUmzugsantrag">
<input type="hidden" name="AS[aid]" value="<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
">
<input type="hidden" name="AS[token]" value="<?php echo $_smarty_tpl->tpl_vars['AS']->value['token'];?>
">
<strong>Bemerkung hinzufügen:</strong><br>
<textarea class="iarea bemerkungen" name="AS[add_bemerkungen]"></textarea>
<br>

  <button class='btn blue btn-add-bemerkung'
          onclick="umzugsantrag_add_bemerkung()"
          style="cursor: pointer"
          type="button">Bemerkung hinzufügen</button>

  <?php if (false) {?><button id="btnShowReklaDialog" type="button" class="btn red">Leistung reklamieren</button><?php }?>
</form>

<div id="LoadingBar"></div>

</div>
</div>


<div id="ReklaDialog" class="dialog dialog-back-layer">
  <div class="dialog-wrapper" style="width:90%;max-height: 95vh;overflow-y: scroll;">
    <div style="width:90%;">
      <form>
        <div id="reklaContent" style="max-height:80vh;overflow-y: auto;text-align:left;">

          <?php $_smarty_tpl->_subTemplateRender("file:admin_umzugsformular_leistungsauswahl.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('enableLeistungCheckbox'=>"1",'addReklas'=>"1",'showReklas'=>"0",'PreiseAnzeigen'=>"0",'mengeMertensReadOnly'=>"1",'mengeGeliefertReadOnly'=>"1",'title'=>"Welche Leistung möchten Sie reklamieren"), 0, false);
?>

        </div>
        <div id="BoxBemerkungen" style="margin-top: 1rem">
          <strong>Reklamationsgrund hinzufügen: (<em>erforderlich</em>)</strong><br>
          <textarea class="iarea bemerkungen" name="grund" style="resize: vertical;overflow: auto"></textarea>
        </div>

        <div class="hint-box">
          <div class="hint-box-title">Wichtiger Hinweis, bitte lesen und beachten!</div>
          Beim Anlegen einer Reklamation wird zur Bearbeitung ein Reklamationsgrund benötigt.<br>
          Bitte prüfen Sie vor dem Absenden die ausgewählten Leistungen, Mengen und den Reklamationsgrund.
        </div>

        <div style="text-align: center;margin-top: 1rem">
          <input type="hidden" name="aid" value="<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
">
          <button type="button" onclick="return false;" class="btn red btn-apply">Reklamation anlegen</button>
          <button type="button" onclick="return false;" class="btn gray btn-cancel">Schließen</button>
        </div>
      </form>
    </div>

  </div>
</div>


<?php echo '<script'; ?>
>
  function showReklaDialog() {

    var container = $("#ReklaDialog");
    $("body").addClass("doc-body-no-scrollbars");
    var dialog = container.closest(".dialog");
    console.log({containerLength: container.length, dialogLength: dialog.length });
    dialog
            .addClass("dialog-active")
            .find("button.btn-cancel")
            .off("click")
            .on("click", function() {
              $("body").removeClass("doc-body-no-scrollbars");
              container.removeClass("dialog-active");
            }).end()
            .find("button.btn-apply")
            .off("click")
            .on("click", function() {
              var btnApply = $(this);
              var frm = $(this).closest("form");
              var btnCancel = frm.find("button.btn-cancel");
              btnApply.prop("disabled", true).waitMe();
              btnCancel.prop("disabled", true);

              var aid = frm.find('[name=aid]').val();
              var grund = frm.find('[name=grund]').val();
              var chckLeistungen = frm.find('[name=chckLeistung\\[\\]]:checked').serializeArray();
              console.log({ aid, chckLeistungen });

              var leistungen = [];
              for(var i = 0; i < chckLeistungen.length; i++) {
                var lid = chckLeistungen[i].value;
                var sel = '[name^=L\\[neue_rekla\\]\\[' + lid + '\\]]';
                console.log('Rekla-Mengen Selector: ', sel, frm.find(sel).length, frm.find(sel).val());
                var mng = frm.find('[name^=L\\[neue_rekla\\]\\[' + lid + '\\]]').val();
                if (!parseInt(mng)) {
                  alert("Für die " + (i+1) + ". ausgewählte Position wurde keine Menge angegeben!");
                  btnApply.prop("disabled", false).waitMe('hide');
                  btnCancel.prop("disabled", false);
                  return;
                }
                leistungen.push({ leistung_id: lid, menge: mng });
              }
              console.log({ aid, chckLeistungen, leistungen });

              if(!leistungen.length) {
                alert("Es wurden keine Leistungen für die Reklamation ausgewählt!");
                btnApply.prop("disabled", false).waitMe('hide');
                btnCancel.prop("disabled", false);
                return;
              }
              if ($.trim(grund) === "") {
                alert("Es wurden kein Grund für die Reklamation angegeben!");
                btnApply.prop("disabled", false).waitMe('hide');
                btnCancel.prop("disabled", false);
                return;
              }

              var request = $.post('/reklamieren.php',
                      {
                        aid,
                        leistungen,
                        grund
                      }, function(response) {
                        console.log({ response });
                        if (response.type === "success") {
                          alert("Reklamation wurde angelegt mit der ID " + response.reklaAid + "\nSeite wird neu geladen!");
                          self.location.reload();
                        } else {
                          alert("Es sind Fehler aufgetreten!\n" + response.msg);
                        }
                      }
              );
              request.always(function() {
                $("body").removeClass("doc-body-no-scrollbars");
                btnApply.prop("disabled", false).waitMe('hide');
                btnCancel.prop("disabled", false);
              });
            }).end();
    return;
  }

  $(function() {
    $("#ReklaDialog").appendTo("body");
    var frm = $("#ReklaDialog").find("form");
    var tplTable = $("#ReklaDialog").find("#TplLeistungTable");
    frm.after(tplTable);

    frm.find("[name=chckLeistung\\[\\]]").on("change", function(e) {
      var lid = $(this).val();
      var row = $(this).closest(".row[data-row]").data("row");
      var neu = $(this).closest(".row").find("[name^=L\\[neue_rekla\\]]");
      if (!$(this).prop("checked")) {
        neu.val(0);
        return;
      }
      var mtMenge = parseFloat(row.menge_mertens || 1);
      var rkMenge = parseFloat(row.menge_rekla || 0);
      var glMenge = parseFloat(row.menge_geliefert || 0);
      var neuVal = Math.max(1, mtMenge - rkMenge - glMenge);

      neu.val(neuVal);
    });

    // $("#reklaContent").load("/textfiles/rekla.html");

    $("#btnShowReklaDialog").on("click", function(e) {
      e.preventDefault();
      showReklaDialog();
      return false;
    });
  });
<?php echo '</script'; ?>
>

<?php }
}
