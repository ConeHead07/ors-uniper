<?php
/* Smarty version 3.1.34-dev-7, created on 2021-12-01 13:30:18
  from '/var/www/html/html/admin_umzugsformular.tpl.read.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_61a76ada5e23b4_00181889',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'cc8fd65736537d78c65320693677a1a2bf211b63' => 
    array (
      0 => '/var/www/html/html/admin_umzugsformular.tpl.read.html',
      1 => 1638361811,
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
    'file:admin_umzugsformular_gruppierung.tpl.html' => 1,
  ),
),false)) {
function content_61a76ada5e23b4_00181889 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/smarty3/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<link rel="STYLESHEET" type="text/css" href="../css/SelBox.easy.css">
<link rel="STYLESHEET" type="text/css" href="css/SelBox.easy.css">

<div id="SysInfoBox"></div>

<link rel="stylesheet" type="text/css" href="css/umzugsformular.css">
<link rel="stylesheet" type="text/css" href="../css/umzugsformular.css">
<!-- MODUL UEBERSCHRIFTENBOX 109099 BEGIN -->
<?php $_smarty_tpl->_subTemplateRender("file:admin_antraege_tabs.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('cat'=>"auftrag",'aid'=>$_smarty_tpl->tpl_vars['AS']->value['aid'],'s'=>"aantraege",'allusers'=>1), 0, false);
?>
<div class="divModuleBasic padding6px width5Col heightAuto colorContentMain"> 
  <h1><span class="spanTitle">Leistungsanforderung #<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
</span></h1>

  <div id="Umzugsantrag" data-html="html/admin/umzugsformular/tpl/read/html" class="divInlay">
  <h2 style="margin:0;">Auftrags-Status</h2>
  <table class="form-table"  border=0 cellspacing=1 cellpadding=1>
    <tr>
      <td style="padding:0;height:auto;width:200px;"><label style="display:block;width:auto;">Ausf&uuml;hrungstermin:</label></td>
      <td style="padding:0;width:250px;"><input type="text" value='<?php echo smarty_modifier_date_format(htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['umzugstermin'], ENT_QUOTES, 'UTF-8', true),"%d.%m.%Y");?>
'
      onfocus="showDtPicker(this)" id="umzugstermin" name="AS[umzugstermin]" class="itxt itxt2col"></td>
    </tr>
    <tr>
      <td style="padding:0;width:200px;height:auto;width:auto;"><label for="tour_kennung" style="display:block;width:auto;">Tour-Kennung/ID:</label></td>
      <td style="padding:0;width:250px;"><input id="tour_kennung" type="text" value='<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['tour_kennung'], ENT_QUOTES, 'UTF-8', true);?>
'
                                                name="AS[tour_kennung]" class="itxt itxt2col"></td>
    </tr>
    <tr>
      <td style="padding:0;height:auto;"><label style="display:block;width:auto;">Ausf&uuml;hrungszeit:</label></td>
      <td style="padding:0;"><input type="text" value='<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['umzugszeit'], ENT_QUOTES, 'UTF-8', true);?>
'
      id="umzugszeit" name="AS[umzugszeit]" class="itxt itxt2col"></td>
    </tr>
    <tr>
      <td style="padding:0;height:auto;"><label style="display:block;width:auto;">Antragsdatum:</label></td>
      <td style="padding:0;"><div class="itxt itxt2col ireadonly"><?php echo smarty_modifier_date_format(htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['antragsdatum'], ENT_QUOTES, 'UTF-8', true),"%d.%m.%Y");?>
</div></td>
    </tr>
    <?php if ($_smarty_tpl->tpl_vars['AS']->value['angeboten_am']) {?>
    <tr>
      <td style="padding:0;"><label style="display:block;width:auto;">Genehmigt:</label></td>
      <td style="padding:0;" class="status_<?php echo $_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'];?>
"><div class="itxt itxt2col ireadonly">
        <img id="imgStatGen" src="images/status_<?php echo mb_strtolower($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'], 'UTF-8');?>
.png"><span id="txtStatGen"><?php if ($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'] != "Init") {?> <?php echo $_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'];?>
 am <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br_am'],"%d.%m.%Y %H:%M");?>
 <?php echo $_smarty_tpl->tpl_vars['AS']->value['genehmigt_br_von'];
}?></span>
      </div></td>
    </tr>
    <?php } else { ?>
    <tr>
      <td style="padding:0;"><label style="display:block;width:auto;">Bestätigt:</label></td>
      <td style="padding:0;" class="status_<?php echo $_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'];?>
"><div class="itxt itxt2col ireadonly">
        <img id="imgStatGenBr" src="images/status_<?php echo mb_strtolower($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'], 'UTF-8');?>
.png"><span id="txtStatGenBr"><?php if ($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'] != "Init") {?> <?php echo $_smarty_tpl->tpl_vars['AS']->value['genehmigt_br'];?>
 am <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['AS']->value['genehmigt_br_am'],"%d.%m.%Y %H:%M");?>
 <?php echo $_smarty_tpl->tpl_vars['AS']->value['genehmigt_br_von'];
}?></span>
      </div></td>
    </tr>
    <?php }?>
    <tr>
      <td style="padding:0;"><label style="display:block;width:auto;">Abgeschlossen:</label></td>
      <td style="padding:0;" class="status_<?php echo $_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'];?>
"><div class="itxt itxt2col ireadonly">
        <img id="imgStatAbg" src="images/status_<?php echo mb_strtolower($_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'], 'UTF-8');?>
.png"><span id="txtStatAbg"><?php if ($_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'] != "Init") {?> <?php echo $_smarty_tpl->tpl_vars['AS']->value['abgeschlossen'];?>
 am <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['AS']->value['abgeschlossen_am'],"%d.%m.%Y %H:%M");?>
 <?php echo $_smarty_tpl->tpl_vars['AS']->value['abgeschlossen_von'];
}?></span>
      </div></td>
    </tr>
    <tr>
      <td style="padding:0;"><label style="display:block;width:auto;">Status:</label></td>
      <td style="padding:0;"><div class="itxt itxt2col ireadonly">
        <?php if (empty($_smarty_tpl->tpl_vars['AS']->value['angeboten_am']) && htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'], ENT_QUOTES, 'UTF-8', true) == "genehmigt") {?>bestaetigt<?php } else {
echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['umzugsstatus'], ENT_QUOTES, 'UTF-8', true);
}?>
      </div></td>
    </tr>
      <!-- SelBox_initLSDefault(initInputObj, initLSName, initMultiple, initLSSearchFields, initLSInputFields) -->
  </table>
    <img src="%WebRoot%images/printer.png" width="16" height="16" alt="">
  <a href="%WebRoot%sites/umzugsblatt.php?id=<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
" style="display: none" target="_Umzugsblatt<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
"><img src="%WebRoot%images/printer.png" width="16" height="16" alt="">Anforderungsblatt / Druckansicht</a>
    <a href="%WebRoot%sites/lieferschein.php?aid=<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
" target="_Lieferschein<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
">Lieferschein</a>
    | <a href="%WebRoot%sites/etiketten.php?aid=<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
" target="_Etiketten<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
">Etiketten</a>
  <br>


    <?php if (!empty($_smarty_tpl->tpl_vars['AS']->value['bemerkungen'])) {?>
    <table class="form-table" width="100%" border=1 cellspacing=1 cellpadding=1 style="margin-top: 1.5rem">
      <tr>
        <td style="padding:0;height:auto;width:200px;">Bisherige Bemerkungen:</td>
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

    <div style="margin-top:1.5rem">
  <div style="float:left">
  <h2 style="margin:0;">Leistungsantragsteller</h2>
  <table class="form-table" >
    <tr>
      <td style="padding:0;width:200px;"><label style="display:block;width:auto;">Vor &amp; Nachname:</label></td>
      <td style="padding:0;width:250px;"><div class="itxt itxt2col ireadonly">
        <span data-fld="AS[vorname]"></span><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['vorname'], ENT_QUOTES, 'UTF-8', true);?>
</span> <span data-fld="AS[name]"></span><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
</span>
      </div></td>
    </tr>
    <tr>
      <td style="padding:0;"><label style="display:block;width:auto;">E-Mail:</label></td>
      <td style="padding:0;" data-fld="AS[email]"><div class="itxt itxt2col ireadonly"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['email'], ENT_QUOTES, 'UTF-8', true);?>
</div></td>
    </tr>
    <tr>
      <td style="padding:0;"><label style="display:block;width:auto;">Fon:</label></td>
      <td style="padding:0;" data-fld="AS[fon]"><div class="itxt itxt2col ireadonly"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['fon'], ENT_QUOTES, 'UTF-8', true);?>
</div></td>
    </tr>

    <tr>
      <td style="padding:0;"><label style="display:block;width:auto;">Standort:</label></td>
      <td style="padding:0;" data-fld="AS[ort]"><div class="itxt itxt2col ireadonly"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['ort'], ENT_QUOTES, 'UTF-8', true);?>
</div></td>
    </tr>

    <tr>
      <td style="padding:0;"><label style="display:block;width:auto;">Gebäude:</label></td>
      <td style="padding:0;" data-fld="AS[gebauede]"><div class="itxt itxt2col ireadonly"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['gebaeude'], ENT_QUOTES, 'UTF-8', true);?>
</div></td>
    </tr>

    <tr>
      <td style="padding:0;"><label style="display:block;width:auto;">Etage:</label></td>
      <td style="padding:0;"><div class="itxt itxt2col ireadonly"><span data-fld="AS[etage]"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['etage'], ENT_QUOTES, 'UTF-8', true);?>
</span></div></td>
    </tr>

    <tr>
      <td style="padding:0;"><label style="display:block;width:auto;">Raum-Nr:</label></td>
      <td style="padding:0;"><div class="itxt itxt2col ireadonly"><span data-fld="AS[raumnr]"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['raumnr'], ENT_QUOTES, 'UTF-8', true);?>
</span></div></td>
    </tr>
    <tr>
      <td style="padding:0;"><label style="display:block;width:auto;"><?php echo $_smarty_tpl->tpl_vars['ASConf']->value['kostenstelle']['label'];?>
:</label></td>
      <td style="padding:0;"><div class="itxt itxt2col ireadonly"><span data-fld="AS[kostenstelle]"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['kostenstelle'], ENT_QUOTES, 'UTF-8', true);?>
</span></div></td>
    </tr>
    <tr>
      <td style="padding:0;"><label style="display:block;width:auto;">Ticket-Nr.:</label></td>
      <td style="padding:0;"><div class="itxt itxt2col ireadonly"><span data-fld="AS[planonr]"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['planonnr'], ENT_QUOTES, 'UTF-8', true);?>
</span></div></td>
    </tr>

    <tr>
      <td style="padding:0;"><label style="display:block;width:auto;">Terminwunsch:</label></td>
      <td style="padding:0;" data-fld="AS[terminwunsch]"><div class="itxt itxt2col ireadonly"><?php echo smarty_modifier_date_format(htmlspecialchars($_smarty_tpl->tpl_vars['AS']->value['terminwunsch'], ENT_QUOTES, 'UTF-8', true),"%d.%m.%Y");?>
</div></td>
    </tr>
      <!-- SelBox_initLSDefault(initInputObj, initLSName, initMultiple, initLSSearchFields, initLSInputFields) -->
  </table>
  <br>
  </div>
  <?php if (1) {?>
  <div style="float:left; margin-left:50px;">
      <?php $_smarty_tpl->_subTemplateRender("file:admin_umzugsformular_lieferauswahl.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
  </div>
  <?php }?>

  <br clear= "all">
  <div style="clear:both;"></div>
    </div>


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
      <?php $_smarty_tpl->_subTemplateRender("file:admin_umzugsformular_leistungsauswahl.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
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

    <?php if (!empty($_smarty_tpl->tpl_vars['AS']->value['bemerkungen'])) {?>
    <div id="BoxBemerkungen" style="display:none;">
      <strong>Bemerkungen:</strong><br>
      <div id="BemerkungenHistorie"><?php echo nl2br($_smarty_tpl->tpl_vars['AS']->value['bemerkungen']);?>
</div>
    </div>
    <br>
    <?php }?>

    <?php if (!empty($_smarty_tpl->tpl_vars['AS']->value['lieferhinweise'])) {?>
    <div id="BoxLieferhinweise">
      <strong>Lieferhinweise:</strong><br>
      <div id="LieferhinweiseContent"><?php echo nl2br($_smarty_tpl->tpl_vars['AS']->value['lieferhinweise']);?>
</div>
    </div>
    <?php }?>
    <div id="LoadingBar"></div>

  </div>
</div>
<?php }
}
