<?php
/* Smarty version 3.1.34-dev-7, created on 2022-02-24 04:14:07
  from '/var/www/html/html/umzugsteam_umzugsformular.tpl.read.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_6217060f36f649_07957914',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3980e6590adbc901815f7cf277dd110fe5dbc980' => 
    array (
      0 => '/var/www/html/html/umzugsteam_umzugsformular.tpl.read.html',
      1 => 1646312042,
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
function content_6217060f36f649_07957914 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/smarty3/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>

<?php echo '<script'; ?>
 src="{WebRoot}js/PageInfo.js" type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="{WebRoot}js/ObjectHandler.js" type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="{WebRoot}js/umzugsformular.easy.js?lm=20101021" type="text/javascript"><?php echo '</script'; ?>
>


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
        <td style="padding:0;width:250px;"><div  class="itxt itxt2col"><?php echo smarty_modifier_date_format(htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['umzugstermin'], ENT_QUOTES, 'UTF-8', true),"%d.%m.%Y");?>
</div></td>
      </tr>
      <tr>
        <td style="padding:0;height:auto;"><label style="display:block;width:auto;">Ausf&uuml;hrungszeit:</label></td>
        <td style="padding:0;"><div  class="itxt itxt2col"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['umzugszeit'], ENT_QUOTES, 'UTF-8', true);?>
</div></td>
      </tr>

      <tr>
        <td style="padding:0;height:auto;"><label style="display:block;width:auto;">Antragsdatum:</label></td>
        <td style="padding:0;"><div  class="itxt itxt2col"><?php echo smarty_modifier_date_format(htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['antragsdatum'], ENT_QUOTES, 'UTF-8', true),"%d.%m.%Y");?>
</div></td>
      </tr>

      <tr>
        <td style="padding:0;"><label style="display:block;width:auto;">Bestätigt:</label></td>
        <td style="padding:0;" class="status_<?php echo $_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'];?>
"><div  class="itxt itxt2col"><img id="imgStatGen" src="images/status_<?php echo mb_strtolower($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'], 'UTF-8');?>
.png"><span id="txtStatGen"><?php if ($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'] != "Init") {?> <?php echo $_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'];?>
 am <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br_am'],"%d.%m.%Y %H:%M");?>
 <?php echo $_smarty_tpl->tpl_vars['AS']->value['genehmigt_br_von'];
}?></span></div></td>
      </tr>

      <tr>
        <td style="padding:0;"><label style="display:block;width:auto;">Abgeschlossen:</label></td>
        <td style="padding:0;" class="status_<?php echo $_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'];?>
"><div  class="itxt itxt2col"><img id="imgStatAbg" src="images/status_<?php echo mb_strtolower($_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'], 'UTF-8');?>
.png"><span id="txtStatAbg"><?php if ($_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'] != "Init") {?> <?php echo $_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'];?>
 am <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['AS']->value['abgeschlossen_am'],"%d.%m.%Y %H:%M");?>
 <?php echo $_smarty_tpl->tpl_vars['AS']->value['abgeschlossen_von'];
}?></span></div></td>
      </tr>
      <tr>
        <td style="padding:0;"><label style="display:block;width:auto;">Status:</label></td>
        <td style="padding:0;"><div  class="itxt itxt2col"><?php if (empty($_smarty_tpl->tpl_vars['AS']->value['angeboten_am']) && htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'], ENT_QUOTES, 'UTF-8', true) == "genehmigt") {?>bestaetigt<?php } else {
echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'], ENT_QUOTES, 'UTF-8', true);
}?></div></td>
      </tr>
      <!-- SelBox_initLSDefault(initInputObj, initLSName, initMultiple, initLSSearchFields, initLSInputFields) -->
    </table>
    <img src="%WebRoot%images/printer.png" width="16" height="16" alt="">
    <a href="%WebRoot%sites/umzugsblatt.php?id=<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
" style="display: none" target="_Umzugsblatt<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
">Anforderungsblatt / Druckansicht</a>
    <a href="%WebRoot%sites/lieferschein.php?aid=<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
" target="_Lieferschein<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
">Lieferschein (PDF)</a>
    | <a href="%WebRoot%sites/etiketten.php?aid=<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
" target="_Etiketten<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
">Etiketten (PDF)</a>
    <br>

    <?php if ($_smarty_tpl->tpl_vars['AS']->value['lieferhinweise']) {?>
    <div id="BoxLieferhinweise" style="margin-top:1.5rem">
      <strong>Lieferhinweise:</strong><br>
      <div id="LieferhinweiseContent"><?php echo nl2br($_smarty_tpl->tpl_vars['AS']->value['lieferhinweise']);?>
</div>
    </div>
    <?php }?>

    <div style="margin-top:1.5rem">
      <div style="float:left">
        <h2 style="margin:0;">Lieferdaten</h2>
        <table>
          <tr>
            <td style="padding:0;width:200px;"><label style="display:block;width:auto;">Vor &amp; Nachname:</label></td>
            <td style="padding:0;width:250px;"><div  class="itxt itxt2col"><span data-fld="AS[vorname]"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['vorname'], ENT_QUOTES, 'UTF-8', true);?>
</span> <span data-fld="AS[name]"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
</span></div></td>
          </tr>
          <tr>
            <td style="padding:0;"><label style="display:block;width:auto;">E-Mail:</label></td>
            <td style="padding:0;" data-fld="AS[email]"><div  class="itxt itxt2col"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['email'], ENT_QUOTES, 'UTF-8', true);?>
</div></td>
          </tr>
          <tr>
            <td style="padding:0;"><label style="display:block;width:auto;">Fon:</label></td>
            <td style="padding:0;" data-fld="AS[fon]"><div  class="itxt itxt2col"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['fon'], ENT_QUOTES, 'UTF-8', true);?>
</div></td>
          </tr>
          <!-- SelBox_initLSDefault(initInputObj, initLSName, initMultiple, initLSSearchFields, initLSInputFields) -->
        </table>
      </div>
      <?php if (!empty($_smarty_tpl->tpl_vars['DL']->value) && !empty($_smarty_tpl->tpl_vars['DL']->value['Firmenname'])) {?>
      <div style="float:left; margin-left:50px;">
        <?php $_smarty_tpl->_subTemplateRender("file:umzugsteam_umzugsformular_lieferauswahl.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
      </div>
      <?php }?>
      <div style="clear: both"></div>
    </div>
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
    <table class="form-table lbl-w-200" >
      <tr>
        <td>
          <label>Stra&szlig;e &amp; Nr</label>
        </td>
        <td style="width:250px;"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['strasse'], ENT_QUOTES, 'UTF-8', true);?>
</td>
      </tr>

      <tr>
        <td>
          <label>PLZ</label></td>
        <td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['plz'], ENT_QUOTES, 'UTF-8', true);?>
</td>
      </tr>

      <tr>
        <td><label><?php echo $_smarty_tpl->tpl_vars['ASConf']->value['ort']['label'];?>
</label></td>
        <td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['ort'], ENT_QUOTES, 'UTF-8', true);?>
</td>
      </tr>


      <tr>
        <td style="padding:0;">
          <label>Land</label></td>
        <td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['land'], ENT_QUOTES, 'UTF-8', true);?>

        </td>
      </tr>
          </table>

    <?php if (!empty($_smarty_tpl->tpl_vars['AS']->value['ansprechpartner']) || !empty($_smarty_tpl->tpl_vars['AS']->value['ansprechpartner_fon'])) {?>
    <div style="margin-top:1.5rem">
      <h2 style="margin:0;">Abweichender Ansprechpartner vor Ort</h2>
      <table>
        <tr>
          <td style="padding:0;width:200px;""><label style="width:100%;">Vor &amp; Nachname:</label></td>
          <td style="padding:0;width:250px;"><div  class="itxt itxt2col"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['ansprechpartner'], ENT_QUOTES, 'UTF-8', true);?>
</div></td>
        </tr>
        <tr>
          <td style="padding:0;"><label style="width:100%;">Telefon:</label></td>
          <td style="padding:0;"><div  class="itxt itxt2col"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['ansprechpartner_fon'], ENT_QUOTES, 'UTF-8', true);?>
</div></td>
        </tr>
      </table>
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
    <div style="margin-top:1.5rem"></div>
    <?php $_smarty_tpl->_subTemplateRender("file:umzugsteam_umzugsformular_leistungsauswahl.tpl.read.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <?php }?>
    <?php if (!empty($_smarty_tpl->tpl_vars['UmzugsAnlagen']->value)) {?>
    <div style="margin-top:1.5rem"></div>
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
        max-height: 95vh;
        overflow-y: scroll;
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

      .options-box .options-item,
      .options-box label {
        display:flex;
        align-items: center;
        width:100%;
        text-align:left;
        padding: 0 0 0 5px;
        margin: 0.7rem 0;
        background-color: #ebedef;
        color: gray;
        box-sizing: border-box;
        min-height: 2.5rem;
        border-radius:3px;
      }

      .options-box input[type=radio],
      .options-box input[type=checkbox] {
        width:1.4rem;
        height:1.4rem;
      }

      .options-box input + span,
      .options-box input + .label-text {
        font-size: 1rem;
        padding-left: 1rem;
        margin: 1px 1px 1px 5px;
        flex-grow: 2;
        display: flex;
        align-items: center;
        height: 100%;
        box-sizing: border-box;
      }
      .options-box input:checked + span,
      .options-box input:checked + .label-text {
        font-weight: bold;
        background-color: red;
        color: #fff;
      }

      .options-box .options-item.checked,
      .options-box label.checked {
        background-color: red;
        font-weight: bold;
        color: #fff;
      }
      .options-item-caption {
        display:inline-block;
        width:100%;
        font-weight:bold;
      }
      .inline-dialog-box,
      #UnzustellbarBox {
        background-color: #f1f8ff;
        border: 1px solid #0075B5;
        /* border-bottom: 1px solid red; */
        padding: 5px 5px 5px 5px;
      }
      .alert-dialog-label,
      .unzustellbar-label {
        color: red;
        font-weight: bold;
        font-size: 1.1rem;
        /* margin-bottom: 1rem; */
        padding: 0.3rem 0 0.3rem 0.2em;
        cursor: pointer;
      }
      textarea[name=unzustellbar_bemerkung] {
        width:100%;
        resize: vertical;display:none;
        border: 1px solid #0075B5;
        padding: .5rem;
        font-size: 1rem;
        height: 2.5rem;
        background-color:white;
        color: #0075B5;
        box-sizing: border-box;
        outline-color: #0075B5;
        border-radius: 4px;
      }
      .spinner.icon.loading {
        animation: lds-dual-ring 1.2s linear infinite;
      }
      @keyframes lds-dual-ring {
        0% {
          transform: rotate(0deg);
        }
        100% {
          transform: rotate(360deg);
        }
      }
    </style>
    <?php if (false) {?><i class="spinner icon loading"></i><?php }?>
    <?php echo '<script'; ?>
>
      $(function() {
        $(".options-box").each(function() {
          var box = this;
          $(box).find("input[type=radio],input[type=checkbox]").on("change", function() {
            console.log("input changed #373");
            var checked = this.checked;
            if (this.type === 'radio') {
              console.log("input radio changed #373", { checked });
              $(box).find("input[type=radio]:not(:checked)").closest("label").removeClass("checked");
              $(box).find("input[type=radio]:not(:checked)").closest(".options-item").removeClass("checked");

              $(box).find("input[type=radio]:checked").closest("label").addClass("checked");
              $(box).find("input[type=radio]:checked").closest(".options-item").addClass("checked");
              if (checked) {
                $(this).closest("label").attr("data-checked", this.value);
                $(this).closest(".options-item").attr("data-checked", this.value);
              }
            } else {
              $(this).closest("label").toggleClass("checked", checked);
              $(this).closest(".options-item").toggleClass("checked", checked);
            }
          });
        });
        $(".alert-dialog-label[data-target]").on("click", function() {
          var targetSelector = $(this).data("target");
          var isVisible = $(targetSelector).toggle().is(":visible");
          $(this).find(".caret.icon").toggleClass("down", !isVisible).toggleClass("up", isVisible);
        });

        $("#reklamationDialog").find("input[value=geliefert]")
                .trigger("click")
                .trigger("change");
      });
    <?php echo '</script'; ?>
>
  <?php if (1) {?>
    <div id="UnzustellBarToggle" data-target="#UnzustellbarBox" class="unzustellbar-label alert-dialog-label">Lieferung kann nicht zugestellt werden <i class="caret down icon"></i></div>
  <?php } else { ?>
  <div id="UnzustellBarToggle"></div>
  <?php }?>
    <div id="UnzustellbarBox" class="inline-dialog-box" style="display: none;">
      <form id="frmUnzustellbar" method="post" action="{WebRoot}zustellversuch.php"
            onsubmit="return false;">
        <input name="datum" type="hidden" value="<?php echo smarty_modifier_date_format(time(),'%Y-%m-%d');?>
">
        <input name="zeit" type="hidden" value="<?php echo smarty_modifier_date_format(time(),'%H:%M:%S');?>
">
        <input type="hidden" name="aid" value="<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
">
      <div class="options-box">
        <label class="options-item">
          <input type="radio" name="unzustellbar_grund" value="Kunde nicht angetroffen">
          <span class="label-text">Kunde nicht angetroffen</span></label>
        <label class="options-item">
          <input type="radio" name="unzustellbar_grund" value="Falsche Adresse">
          <span class="label-text">Falsche Adresse</span></label>
        <label class="options-item">
          <input type="radio" name="unzustellbar_grund" value="Annahme verweigert">
          <span class="label-text">Annahme verweigert</span></label>
        <label class="options-item">
          <input type="radio" name="unzustellbar_grund" value="Abbruch der Anlieferung">
          <span class="label-text">Abbruch der Anlieferung</span></label>
        <label class="options-item">
          <input data-toggletarget="#uzb_bemerkung" type="radio" name="unzustellbar_grund" value="Sonstiges">
          <span class="label-text">Sonstiges</span></label>
      </div>
      <textarea id="uzb_bemerkung" name="unzustellbar_bemerkung" style="width:100%;resize: vertical;display:none" placeholder="Geben Sie bitte einen Grund an warum die Lieferung nicht zugestellt werde konnte"></textarea>
      </form>
      <button id="btnUnzustellbar" class="btn blue" style="width:100%;font-size:1.2rem;margin-top: 1rem;">Speichern und senden</button>
    </div>
    <h2 style="margin-left:0;margin-top:1rem;">Kundenabnahme</h2>
    <div id="KundenabnahmeBox" style="padding:0rem;<?php if ($_smarty_tpl->tpl_vars['umzugsstatus']->value == "abgeschlossen") {?>display:none:<?php }?>">
    <form id="frmAbnahme" class="w3-container" action="%WebRoot%lieferscheinabnahme.php" method="POST"
          name="abnahme" enctype="multipart/form-data" target="_self">
      <input type="hidden" name="aid" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['aid'], ENT_QUOTES, 'UTF-8', true);?>
">
      <div style="padding:.5rem;background-color: #f1f8ff;">
        Die Ware wurde ordnungsgemäß geliefert und in einwandfreiem Zustand montiert. Ebenfalls bestätigen Sie hiermit, dass durch
        uns keine Schäden an Ihrem Gebäude und Ihren Räumlichkeiten entstanden sind. Sollten Schäden entstanden sein, notieren
        Sie diese bitte auf dem beiliegendem Reklamationsformular.
      </div>
      <div style="min-height:1.5rem;margin-top:.8rem;">
        <div style="display: inline-block;font-weight:bold;">
          <label style="width:initial;margin:initial;text-align: left;">Ihr Montageteam der merTens AG </label>
        </div>
        <div id="imgSignatureBoxMertens"
             class="input-bg-color"
             style="display: inline-block;width:15rem;min-height:1.5rem;border-bottom:2px solid #0078dc;position:relative;">
          <span style="position:absolute;right:-1.5rem;bottom:0;color:#0078dc"><i class="pen square  icon"></i></span>
          <img id="imgSignatureMertens" src="" style="max-height:1.5rem;max-width:15rem;display:none;align-self: flex-end">
          <input id="lsSignatureMertens" type="hidden" name="sig_mt_dataurl" value="">
          <input id="lsSignatureMertensGeodata" type="hidden" name="sig_mt_geodata" value="">
          <input id="lsSignatureMertensCreated" type="hidden" name="sig_mt_created" value="">

        </div>
      </div>

      <div style="min-height:1.5rem;margin-top:1.2rem;">
        <label style="margin-right:3rem;width:initial;display: inline-block;height:2rem;text-align:left;">
          <span style="display: inline-block;width:4rem;">Ankunft</span>
          <span style="border-bottom:2px solid #0078dc;margin-left:1rem;">
          <input
                  name="ankunft" id="ankunftsZeit" type="time" size="8"
                  style="min-width:80px;color: #0078dc;" class="input-bg-color"> Uhr</span>
        </label>
        <label style="margin-right:3rem;width:initial;display: inline-block;text-align:left;">
          <span style="display: inline-block;width:4rem;">Abfahrt</span>
          <span style="border-bottom:2px solid #0078dc;margin-left:1rem">
          <input
                  name="abfahrt" id="abfahrtsZeit" type="time" size="8"
                  style="min-width:80px;color:#0078dc;" class="input-bg-color"> Uhr</span>
        </label>
      </div>

      <div style="min-height:1.5rem;margin-top:1.2rem;">
        <div><label style="margin-right:3rem;width:initial;" class="input-bg-color" > Etikettierung erfolgt</label></div>
        <?php if (!empty($_smarty_tpl->tpl_vars['Umzugsleistungen']->value) && is_array($_smarty_tpl->tpl_vars['Umzugsleistungen']->value)) {?>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['Umzugsleistungen']->value, 'L', false, NULL, 'GList', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['L']->value) {
?>
        <?php if ($_smarty_tpl->tpl_vars['L']->value['kategorie_id'] == "18" || $_smarty_tpl->tpl_vars['L']->value['kategorie_id'] == "25" || $_smarty_tpl->tpl_vars['L']->value['kategorie'] == "Montage") {?>
        <?php continue 1;?>
        <?php }?>
        <label style="margin-right:3rem;width:12rem;padding: 10px 3px 5px 0;text-align: left;color:#0078dc;" class="input-bg-color" >
          <input name="etikettierung_erfolgt[<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['leistung_id'], ENT_QUOTES, 'UTF-8', true);?>
]"
                 data-label="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['kategorie'], ENT_QUOTES, 'UTF-8', true);?>
"
                 id="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['kategorie'], ENT_QUOTES, 'UTF-8', true);?>
"
                 style="width:1rem;height:1rem;outline: 2px solid #0078dc;outline-style: auto;"
                 value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['kategorie'], ENT_QUOTES, 'UTF-8', true);?>
" type="checkbox"> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['kategorie'], ENT_QUOTES, 'UTF-8', true);?>

        </label>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        <?php }?>
      </div>

      <?php if (!empty($_smarty_tpl->tpl_vars['Umzugsleistungen']->value) && is_array($_smarty_tpl->tpl_vars['Umzugsleistungen']->value)) {?>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['Umzugsleistungen']->value, 'L', false, NULL, 'GList', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['L']->value) {
?>
          <?php if ($_smarty_tpl->tpl_vars['L']->value['kategorie'] == "Schreibtisch" || $_smarty_tpl->tpl_vars['L']->value['kategorie'] == "Schreibtischlampe") {?>
          <div style="min-height:1.5rem;margin-top:1.2rem;">
            <div>
              <label style="margin-right:3rem;width:initial;" class="input-bg-color" >
                Funktionsprüfung erfolgt</label>
            </div>
          </div>
          <?php break 1;?>
          <?php }?>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        <div>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['Umzugsleistungen']->value, 'L', false, NULL, 'GList', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['L']->value) {
?>
          <?php if ($_smarty_tpl->tpl_vars['L']->value['kategorie'] == "Schreibtisch" || $_smarty_tpl->tpl_vars['L']->value['kategorie'] == "Schreibtischlampe") {?>
          <label style="margin-right:3rem;width:initial;color:#0078dc;padding: 10px 3px 5px 0" class="input-bg-color" >
            <input name="funktionspruefung_erfolgt[]"
                   data-label="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['kategorie'], ENT_QUOTES, 'UTF-8', true);?>
"
                   id="Schreibtischpruefung"
                   style="width:1rem;height:1rem;outline: 2px solid #0078dc;outline-style: auto;"
                   value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['kategorie'], ENT_QUOTES, 'UTF-8', true);?>
" type="checkbox"> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['kategorie'], ENT_QUOTES, 'UTF-8', true);?>

          </label>
          <?php }?>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </div>
      <?php }?>

      <style>
        #ReklamationLabel {

        }
        #reklamationDialog .options-item {
          border-radius: 5px;
        }
        #reklamationDialog .options-item .item label {
          min-height: 1.5rem;
          color: #4d4d4d;
        }
        #reklamationDialog .options-item .item {
          background-image: linear-gradient(to bottom, rgba(250, 250, 250, 0.8), #9a9393);
        }
        #reklamationDialog .options-item[data-checked=geliefert] {
          background-color: lightgreen;
          color: #005100;
          border: 1px solid #005100;
        }
        #reklamationDialog .options-item[data-checked=geliefert] .item-geliefert {
          background-color: green;
          background-image: linear-gradient(to bottom, #90ee90, #006400);
        }
        #reklamationDialog .options-item[data-checked=geliefert] .item-geliefert,
        #reklamationDialog .options-item[data-checked=geliefert] .item-geliefert label {
          color: lightgreen;
          color: #fff;
        }

        #reklamationDialog .options-item[data-checked=fehlt] {
          background-color: lightskyblue;
          color: #0141ac;
          border: 1px solid #0141ac;
        }
        #reklamationDialog .options-item[data-checked=fehlt] .item-fehlt {
          background-color: blue;
          background-image: linear-gradient(to bottom, #afd9fb, #0148bf);
        }
        #reklamationDialog .options-item[data-checked=fehlt] .item-fehlt,
        #reklamationDialog .options-item[data-checked=fehlt] .item-fehlt label {
          color: lightskyblue;
          color: #fff;
        }

        #reklamationDialog .options-item[data-checked=rekla] {
          background-color: lightpink;
          color: #980000;
          border: 1px solid red;
        }
        #reklamationDialog .options-item .item-rekla {
          border-top-right-radius: 5px;
          border-bottom-right-radius: 5px;
        }
        #reklamationDialog .options-item[data-checked=rekla] .item-rekla {
          background-color: red;
          background-image: linear-gradient(to bottom, #fbdada, #d50707);
        }
        #reklamationDialog .options-item[data-checked=rekla] .item-rekla,
        #reklamationDialog .options-item[data-checked=rekla] .item-rekla label {
          color: lightpink;
          color: #fff;
        }
        #reklamationDialog textarea[name=rekla_bemerkung] {
          width:100%;
          resize: vertical;
          border: 1px solid #0075B5;
          padding: .5rem;
          font-size: 1rem;
          height: 2.5rem;
          background-color:white;
          color: #0075B5;
          box-sizing: border-box;
          outline-color: #0075B5;
          border-radius: 4px;
        }
      </style>

      <div>
        <?php if (1) {?>
        <div id="ReklamationLabel" data-target="#reklamationDialog" class="reklamation-label alert-dialog-label">Reklamation hinzufügen <i class="caret down icon"></i></div>
        <?php } else { ?>
        <div id="ReklamationLabel"></div>
        <?php }?>
        <div id="reklamationDialog" class="inline-dialog-box" style="display: none;">
          <div class="options-box">
            <?php if (!empty($_smarty_tpl->tpl_vars['Umzugsleistungen']->value) && is_array($_smarty_tpl->tpl_vars['Umzugsleistungen']->value)) {?>
              <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['Umzugsleistungen']->value, 'L', false, NULL, 'GList', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['L']->value) {
?>
                <?php if ($_smarty_tpl->tpl_vars['L']->value['kategorie_id'] == "18" || $_smarty_tpl->tpl_vars['L']->value['kategorie_id'] == "25" || $_smarty_tpl->tpl_vars['L']->value['kategorie'] == "Montage") {?>
                  <?php continue 1;?>
                <?php }?>

            <div class="options-item">

                <?php if ($_smarty_tpl->tpl_vars['L']->value['menge_mertens'] == 1) {?>
              <div class="options-item-caption" style="width:100%;display: block;">
                  <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['kategorie'], ENT_QUOTES, 'UTF-8', true);?>
</div>
              <div class="item item-geliefert" style="display:inline-block;width:30%;border-left: 1px solid rgba(0, 0, 0, .5);"><label style="">OK<input type="radio" value="geliefert" name="lieferpos[<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['leistung_id'], ENT_QUOTES, 'UTF-8', true);?>
]" style="display:none" checked></label></div>
              <div class="item item-fehlt" style="display:inline-block;width:30%;border-left: 1px solid rgba(0, 0, 0, .5);"><label style="">Fehlt<input type="radio" value="fehlt" name="lieferpos[<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['leistung_id'], ENT_QUOTES, 'UTF-8', true);?>
]" style="display:none;background-color:#fff;color:red;text-align:right;"></label></div>
              <div class="item item-rekla" style="display:inline-block;width:30%;border-left: 1px solid rgba(0, 0, 0, .5);"><label style="">Rekla<input type="radio" value="rekla" name="lieferpos[<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['leistung_id'], ENT_QUOTES, 'UTF-8', true);?>
]" style="display:none;background-color:#fff;color:red;text-align:right;"></label></div>
                <?php } else { ?>
              <div class="options-item-caption"><input type="checkbox" data-target="" name="failure[<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['leistung_id'], ENT_QUOTES, 'UTF-8', true);?>
]" value="1">
                  <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['kategorie'], ENT_QUOTES, 'UTF-8', true);?>
</div>
              <div style="display:inline-block;width:48%;">Fehlt: <input type="number" size="3" min="0" max="<?php echo $_smarty_tpl->tpl_vars['L']->value['menge_mertens'];?>
" value="0" name="fehlmenge[<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['leistung_id'], ENT_QUOTES, 'UTF-8', true);?>
]" align="right" style="background-color:#fff;color:red;text-align:right;"></div>
              <div style="display:inline-block;width:48%;">Rekla: <input type="number" align="right" size="3" min="0" max="<?php echo $_smarty_tpl->tpl_vars['L']->value['menge_mertens'];?>
" value="0" name="fehlerhaft[<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['leistung_id'], ENT_QUOTES, 'UTF-8', true);?>
]" style="background-color:#fff;color:red;text-align:right;"></div>
                <?php }?>

            </div>
              <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            <?php }?>
          </div>
          <b>Erläuterung / Bemerkung / Sonstiges</b>
          <textarea name="rekla_bemerkung"></textarea>
        </div>
      </div>

      <div style="margin-top:.5rem;">
        <div style="display:inline-block;margin-right:2rem;margin-top:1rem;">
          <div
                  class="input-bg-color"
                  style="border-bottom:2px solid #0078dc;min-height:1.5rem;width:8rem;display: inline-flex;">
            <input id="abnahmeLieferdatum" type="date" name="lieferdatum" style="width:100%;align-self: flex-end;color:#0078dc;">
          </div>
          <div>(Datum)</div>
        </div>

        <div style="display:inline-block;margin-top:1rem;">
          <div style="display:inline-block;margin-right:2rem;">
            <div
                    class="input-bg-color"
                    style="border-bottom:2px solid #0078dc;min-height:1.5rem;width:14rem;display: inline-flex;">
              <input id="lsSignatureUnterzeichner" type="text" name="sig_ma_unterzeichner"
                     value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['vorname'], ENT_QUOTES, 'UTF-8', true);?>
 <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
"
                     style="width:100%;align-self: flex-end;color:#0078dc;">
            </div>

            <div>(Name Kunde in Blockbuchstaben)</div>
          </div>

          <div style="display:inline-block;">
            <div id="imgSignatureBoxMA"
                 class="input-bg-color"
                 style="border-bottom:2px solid #0078dc;min-height:1.5rem;display: inline-flex;width:15rem;position:relative;">
              <span style="position:absolute;right:-1.5rem;bottom:0;color:#0078dc;"><i class="pen square  icon"></i></span>
              <img id="imgSignatureMA" src="" style="max-height:1.5rem;max-width:15rem;display:none;align-self: flex-end">
              <input id="lsSignatureMA" type="hidden" name="sig_ma_dataurl" value="">
              <input id="lsSignatureMAGeodata" type="hidden" name="sig_ma_geodata" value="">
              <input id="lsSignatureMACreated" type="hidden" name="sig_ma_created" value="">
            </div>
            <div>(Unterschrift)</div>
          </div>
        </div>

        <div style="clear: both"></div>
      </div>
      <div style="margin-top:1rem">
        <button id="btnSubmit" style="float:left" class="btn blue btn-submit" type="button" onclick="return false;">Speichern<br>und senden</button>
        <button id="btnLaden" style="float:right" class="btn grey btn-submit" type="button">Letzte Eingabe für <br>Auftrag #<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
 laden</button>
        <div style="clear:both;"></div>
      </div>
      <div id="output" style="display: none;"></div>
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
      <div style="font-weight:bold;">Kunde: Unterschrift</div>
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
    $("#btnLaden").on("click", function() {
      lieferscheinVonLocalStorageLaden();
    });

    $("#btnUnzustellbar").on("click", function(e) {
      var btn = $(this).prop('disabled', true).waitMe({effect: 'ios'});
      var box = $(this).closest(".inline-dialog-box");
      var frm = box.find("form");
      var url = frm.attr("action");
      var aid = frm.find(":input[name=aid]").val();
      var grundOpts = frm.find(":input[name=unzustellbar_grund]");
      var grundText = box.find(":input[name=unzustellbar_bemerkung]");
      var grundTextVal = grundText.val();

      var checkedOpt = grundOpts.filter(":checked");

      if (!checkedOpt.length) {
        alert("Bitte wählen Sie einen Grund für die Unzustellbarkeit aus!");
        btn.prop('disabled', false).waitMe('hide');
        return;
      }

      if (checkedOpt.val() === 'Sonstiges' && grundText.is(":visible") && !grundText.val()) {
        alert("Geben Sie bitte einen Grund, warum die Lieferung nicht zugestellt werden konnte.");
        btn.prop('disabled', false).waitMe('hide');
        return;
      }

      var data = {
        aid: aid,
        grund: checkedOpt.val(), // '', //
        bemerkung: grundTextVal, // '', //
        datum: getDbDateString(), // '', //
        zeit: getDbTimeString() // '' //
      };

      var rq = $.post(url, data, function(d) {
        MyInfoBox('Daten wurden übertragen', 'Zustellversuch wurde gespeichert!');
      });

      rq.always(function() {
        btn.prop('disabled', false).waitMe('hide');
      });
    });

    $(":radio[data-toggletarget]").each(function() {
      var frm = $(this).closest("form");
      var self = this;
      var selTarget = $(this).data("toggletarget");
      var target = $(selTarget);
      var name = $(this).attr("name");
      var selOpts = ":radio[name=" + escapeSelector(name) + "]";

      frm.find(selOpts).on("change", function() {
        var toggleShow = this === self;
        target.toggle(toggleShow);
        if (toggleShow && target.length) {
          target[0].focus();
        }
      });
    });
    onchange="$( $(this).data('toggletarget') ).toggle(this.checked)"

    if ($("#abnahmeLieferdatum").val() === '') {
      var heute = new Date();
      var defautLieferdatum = getDbDateString();
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

  function getDbDateString() {
    var d = new Date();
    var Y = d.getFullYear();
    var m = (d.getMonth() < 9 ? '0' : '') + (d.getMonth() + 1).toString(10);
    var d = (d.getDate() < 10 ? '0' : '') + d.getDate().toString(10);
    return [Y, m, d].join('-');
  }

  function getDbTimeString() {
    var d = new Date();
    var H = (d.getHours() < 10 ? '0' : '') + d.getHours().toString(10);
    var i = (d.getMinutes() < 10 ? '0' : '') + d.getMinutes().toString(10);
    var s = (d.getSeconds() < 10 ? '0' : '') + d.getSeconds().toString(10);

    return [H, i, s].join(':');
  }

  function getDbDateTimeString() {
    return getDbDateString() + ' ' + getDbTimeString();
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
    daten[name] = value;
    setLieferscheinDaten(aid, daten);
  }
  function addLieferscheinSubmitted(aid) {
    var daten = getLieferscheinDaten(aid);
    daten.submitted = 1;
    daten.lastSumitDate = getDbDateTimeString();

    setLieferscheinDaten(aid, daten);
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
    daten.submitted = false;
    daten.lastSubmitDate = getDbDateTimeString();

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

    console.log('Letzte Lieferscheindaten für ' + aid, { daten });
    if (!daten || Object.keys(daten).length === 0) {
      console.log('Es existieren keine Lieferschein-Daten im LocalStorage für die Auftrags-ID ' + aid);
      alert('Es existieren keine Lieferschein-Daten im lokalen Speicher für die Auftrags-ID ' + aid);
      return false;
    }

    for(var name in daten) {
      var inputVal = daten[name];
      console.log('#1268 laod ', { name, inputVal });
      if (!daten.hasOwnProperty(name)) {
        console.log('#1270 cancel is no ownProperty');
        continue;
      }
      name.replace('[', '\\[');
      var input = frm.find(":input[name=" + escapeSelector(name) + "]");
      console.log('#1275 searched for "input[name="' + escapeSelector(name) + '"]" ', input.length);
      if (input.length === 1) {
        console.log('#1277');
        var type = input.attr("type");
        console.log('#1279 type', type);
        if (['radio', 'checkbox'].indexOf(type) > -1 ) {
          console.log('#1281 type is of type radio or checkbox', type);
          input.prop("checked", true).trigger("change");
        } else {
          input.val(daten[name]);
        }
      } else if (input.length > 1) {
        var type = input.eq(0).attr("type");
        if (['radio'].indexOf(type) > -1 ) {
          console.log('#1289 type is of type radio', type);
          input.filter(function() {
            var radioVal = $(this).val();
            var foundCheck = radioVal == inputVal;
            console.log("#1290 radio filter ", { foundCheck, radioVal, inputVal });
            return $(this).val() == inputVal; }).prop("checked", true).trigger("change");
        }
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
    var etikettiertChecked = etikettiert.filter(function() { return this.checked; });
    var checkedLength = etikettiertChecked.length;
    var fpruefung = frm.find("input[name^=funktionspruefung_erfolgt]");
    var fpruefungChecked = fpruefung.filter(function() { return this.checked; });
    var fcheckedLength = fpruefungChecked.length;


/*
  <input type="radio" value="geliefert" name="lieferpos[{$L.leistung_id|escape}]">
  <input type="radio" value="fehlt" name="lieferpos[{$L.leistung_id|escape}]">
  <input type="radio" value="rekla" name="lieferpos[{$L.leistung_id|escape}]">

  Fehlt: <input value="0" name="fehlmenge[{$L.leistung_id|escape}]"
  Rekla: <input value="0" name="fehlerhaft[{$L.leistung_id|escape}]"
  <textarea name="rekla_bemerkung"></textarea>
*/
    var reklaDialog = $("#reklamationDialog");
    var numFehlt = 0;
    var numRekla = 0;
    var reklaBem = reklaDialog.find(":input[name=rekla_bemerkung]").val();
    reklaDialog.find(":input[name^=lieferpos]:checked").each(function() {
      if ($(this).val() === "fehlt") {
        numFehlt+= +$(this).val();
        return;
      }
      if ($(this).val() === "rekla") {
        numRekla+= +$(this).val();
        return;
      }
    });
    reklaDialog.find(":input[name^=fehlmenge]").each(function() { numFehlt+= +$(this).val(); });
    reklaDialog.find(":input[name^=fehlerhaft]").each(function() { numRekla+= +$(this).val(); });
    var hasRekla = (reklaBem.length > 5 || numFehlt > 0 || numRekla > 0);



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
      etikettiert,
      checkedLength,
      leistungenLabels,
      fpruefung,
      fpruefungChecked,
      fcheckedLength
    });

    if (!aid || isNaN(aid)) {
      // alert("Bitte Seite neu laden. Es liegt keine Auftrags-ID vor!");
      MyInfoBox('Bitte Seite neu laden', 'Es liegt keine Auftrags-ID vor!');
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
      if (!numFehlt && !numRekla) {
        errors += "Es wurde kein Artikel als etikettiert markiert:\n";
        errors += " - " + leistungenLabels.join("\n - ") + "\n";
      } else {
        warnings += "Es wurde kein Artikel als etikettiert markiert:\n";
        warnings += " - " + leistungenLabels.join("\n - ") + "\n";
      }
    }

    if (errors) {
      console.log({errors});
      MyInfoBox('Der Lieferschein wurde nicht vollständig ausgefülltn!', errors.split("\n").join("<br>"));
      return false;
    }

    if (checkedLength < etikettiert.length) {
      var missingChecks = etikettiert.length - checkedLength;
      warnings+= "Die Etikettierung von " + missingChecks + " Artikeln wurde nicht bestätigt.\n";
    }
    if (fpruefung.length && fcheckedLength < fpruefung.length) {
      var fmissingChecks = fpruefung.length - fcheckedLength;
      warnings+= "Die Prüfung von " + fmissingChecks + " Artikeln wurde nicht bestätigt.\n";
    }

    if (warnings) {
      var warningsQuestion = "Möchten Sie trotz unvollständiger Angaben den Lieferschein so abnehmen?\n";
      if ( !confirm(warningsQuestion + warnings) ) {
        return false;
      }
    }
    console.log({checked: etikettiertChecked, etikettiert, warnings});

    var formData = new FormData(frm.get(0));
    lieferscheinSpeichern();

    if (!window.navigator.onLine) {
      if (0) alert(
              "Aktuell besteht keine Internetverbindung um die Daten zum Server zu übertragen!\n" +
              "Die Eingaben zu dieser Auslieferung wurden gespeichert und können später aus dieser Ansicht geladen und versendet werden."
      );
      MyInfoBox('Fehlende Internetverbindung!', "Aktuell besteht keine Internetverbindung um die Daten zum Server zu übertragen!<br>\n" +
              "Die Eingaben zu dieser Auslieferung wurden gespeichert und können später aus dieser Ansicht geladen und versendet werden.");
      return;
    }

    frm.find("#btnSubmit").prop("disabled", true);
    umzugsantrag_loadingBar('Daten werden übertragen!');

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
            var sHtml = data.msg.split("\n").join("<br>");
            $("#output").addClass("success").removeClass("error").html( data.msg.split("\n").join("<br>") );
            MyInfoBox('Auftrag wurde abgeschlossen', sHtml);

            addLieferscheinSubmitted(aid);
          }
        } else {
          var sHtml = '';
          if (typeof data === 'object' && 'errors' in data) {
            sHtml = (Array.isArray(data.errors)) ? data.errors.join("\n") : JSON.stringify(data.errors);
            sHtml = sHtml.split("\n").join("<br>");
          }
          $("#output").addClass("error").removeClass("success").html( sHtml );
          MyInfoBox('Es sind Fehler aufgetreten', sHtml);
          frm.find("#btnSubmit").prop("disabled", false);
        }
      },
      error: function (e) {
        $("#output").addClass("error").removeClass("success").text(e.responseText);
        var sHtml = e.responseText.split("\n").join("<br>");
        MyInfoBox('Serverfehler', sHtml);
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
