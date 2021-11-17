<?php
/* Smarty version 3.1.34-dev-7, created on 2021-11-17 15:12:05
  from '/var/www/html/html/admin_antraege_liste.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_61950db52962b5_53660118',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ca20b2939820e6abcfcabb4cad3d6e1d9d7f1e93' => 
    array (
      0 => '/var/www/html/html/admin_antraege_liste.html',
      1 => 1637158184,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:admin_antraege_tabs.html' => 1,
  ),
),false)) {
function content_61950db52962b5_53660118 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/smarty3/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<!-- TAB NAVIGATION ITEMS BEGIN --> 
<div id="ID128585" class="divTabbedNavigation" style="width:100%;">
    <?php $_smarty_tpl->_subTemplateRender("file:admin_antraege_tabs.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
<!-- TAB NAVIGATION ITEMS END --> 
 
<!-- TAB ITEM (128587) BEGIN --> 
<div id="ID128587" class="divModuleBasic padding12px width5Col heightAuto"> 
<div class="divInlay noMarginBottom borderTop">
</div>

<!-- Add-On Fließtext(dyn) ID: 128588 BEGIN -->
<div class="divInlay borderTop">
    
    <div>
<?php if ($_smarty_tpl->tpl_vars['cat']->value == "angeboten") {?>
    <h2 style="float:left;" data-site="admin/antraege/liste/html">Es liegen <?php echo $_smarty_tpl->tpl_vars['num_all']->value;?>
 von Mertens gestellte Aufträge vor</h2>
<?php } else { ?>
    <h2 style="float:left;" data-site="admin/antraege/liste/html">Es liegen <?php echo $_smarty_tpl->tpl_vars['num_all']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['cat']->value;?>
 Aufträge vor</h2>
<?php }?>
    <h2 style="float:right"><?php echo number_format($_smarty_tpl->tpl_vars['summeTotal']->value,2,",",".");?>
 &euro;</h2>
    <span style="clear:both"></span>
</div>
<?php $_smarty_tpl->_assignInScope('showLieferdatum', true);
$_smarty_tpl->_assignInScope('showAbgeschlossen', true);
$_smarty_tpl->_assignInScope('lieferdatumWidth', 100);
$_smarty_tpl->_assignInScope('abgeschlossenWidth', 80);
$_smarty_tpl->_assignInScope('serviceWidth', 80);?>
    <?php $_smarty_tpl->_assignInScope('strasseWidth', 180);?>
    <?php $_smarty_tpl->_assignInScope('summeWidth', 60);?>

<?php if ($_smarty_tpl->tpl_vars['cat']->value == "neue") {?>
    <?php $_smarty_tpl->_assignInScope('showLieferdatum', false);?>
    <?php $_smarty_tpl->_assignInScope('showAbgeschlossen', false);?>
    <?php $_smarty_tpl->_assignInScope('strasseWidth', $_smarty_tpl->tpl_vars['strasseWidth']->value+$_smarty_tpl->tpl_vars['lieferdatumWidth']->value+$_smarty_tpl->tpl_vars['abgeschlossenWidth']->value);
} elseif ($_smarty_tpl->tpl_vars['cat']->value == "aktive") {?>
    <?php $_smarty_tpl->_assignInScope('showAbgeschlossen', false);?>
    <?php $_smarty_tpl->_assignInScope('strasseWidth', $_smarty_tpl->tpl_vars['strasseWidth']->value+$_smarty_tpl->tpl_vars['abgeschlossenWidth']->value);
}?>

<div style="clear:both"></div>
<?php if ($_smarty_tpl->tpl_vars['ListBrowsing']->value) {
echo $_smarty_tpl->tpl_vars['ListBrowsing']->value;
}?>
  <ul class="ulLinkList"> 
  <div>
  <div style="float:left;display:block;width:30px;font-weight:bold;color:#00869c;" title="Auftrags-ID"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=id<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "id" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">ID</a></div>
  <div style="float:left;display:block;width:75px;font-weight:bold;color:#00869c;" title="Uniper MitarbeiterNr."><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=kid<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "kid" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">KID</a></div>
  <?php if ($_smarty_tpl->tpl_vars['showLieferdatum']->value) {?>
  <div style="float:left;display:block;width:<?php echo $_smarty_tpl->tpl_vars['lieferdatumWidth']->value;?>
px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=termin<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "termin" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Lieferdatum</a></div>
  <?php }?>
  <div style="float:left;display:block;width:60px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=land<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "land" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Land</a></div>
  <div style="float:left;display:block;width:100px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=ort<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "ort" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Lieferort</a></div>
  <div style="float:left;display:block;width:50px;font-weight:bold;color:#00869c;" title="Postleitzahl"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=plz<?php if ($_smarty_tpl->tpl_vars['ofld']->value == 'plz' && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">PLZ</a></div>
  <div style="float:left;display:block;width:<?php echo $_smarty_tpl->tpl_vars['strasseWidth']->value;?>
px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=strasse<?php if ($_smarty_tpl->tpl_vars['ofld']->value == 'strasse' && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Stra&szlig;e</a></div>
  <div style="float:left;display:block;width:75px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=service<?php if ($_smarty_tpl->tpl_vars['ofld']->value == 'service' && $_smarty_tpl->tpl_vars['odir']->value != 'DESC') {?>&odir=DESC<?php }?>">Service</a></div>
  <div style="float:left;display:block;width:100px;font-weight:bold;color:#00869c;" title="Auftragsdatum"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=antragsdatum<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "antragsdatum" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Auftr.Dat.</a></div>
  <div style="float:left;display:none;width:35px;font-weight:bold;color:#00869c;" title="Von Property genehmigt"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=genehmigt<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "genehmigt_br" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Gen</a></div>
  <div style="float:left;display:none;width:35px;font-weight:bold;color:#00869c;" title="Von merTens geprueft / Avisisiert"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=bestaetigt<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "bestaetigt" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Avi</a></div>
  <?php if ($_smarty_tpl->tpl_vars['showAbgeschlossen']->value) {?>
  <div style="float:left;display:block;width:<?php echo $_smarty_tpl->tpl_vars['abgeschlossenWidth']->value;?>
px;font-weight:bold;color:#00869c;" title="Abgeschlossen"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=abgeschlossen<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "abgeschlossen" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Abgeschl.</a></div>
  <?php }?>
      <div style="float:left;display:block;width:<?php echo $_smarty_tpl->tpl_vars['summeWidth']->value;?>
px;font-weight:bold;color:#00869c;" title="Auftragswert"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=summe<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "summe" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Summe</a></div>
      <br clear=left></div>
<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['Umzuege']->value, 'U');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['U']->value) {
?>
  <li><a class="iconRightContentMain" href="<?php echo $_smarty_tpl->tpl_vars['U']->value['LinkOpen'];?>
">
  <div>
      <div style="float:left;display:block;width:30px;font-weight:bold;"><?php echo $_smarty_tpl->tpl_vars['U']->value['aid'];?>
&nbsp;</div>
      <div style="float:left;display:block;width:75px;" ><?php echo $_smarty_tpl->tpl_vars['U']->value['kid'];?>
&nbsp;</div>
      <?php if ($_smarty_tpl->tpl_vars['showLieferdatum']->value) {?>
      <div style="float:left;display:block;width:<?php echo $_smarty_tpl->tpl_vars['lieferdatumWidth']->value;?>
px;font-weight:bold;"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['U']->value['Termin'],"%d.%m.%Y");?>
&nbsp;</div>
      <?php }?>
      <div style="float:left;display:block;width:60px;"><?php if ($_smarty_tpl->tpl_vars['U']->value['land'] == "Deutschland") {?>DE<?php } elseif ($_smarty_tpl->tpl_vars['U']->value['land'] == "Niederlande") {?>NL<?php } else {
echo $_smarty_tpl->tpl_vars['U']->value['land'];
}?> &nbsp;</div>
      <div style="float:left;display:block;width:100px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['ort'];?>
&nbsp;</div>
      <div style="float:left;display:block;width:50px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['plz'];?>
&nbsp;</div>
      <div style="float:left;display:block;width:<?php echo $_smarty_tpl->tpl_vars['strasseWidth']->value;?>
px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['strasse'];?>
&nbsp;</div>
      <div style="float:left;display:block;width:75px;" ><?php echo $_smarty_tpl->tpl_vars['U']->value['service'];?>
&nbsp&nbsp;</div>
      <div style="float:left;display:block;width:100px;font-style:italic;"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['U']->value['Antragsdatum'],"%d.%m.%Y");?>
&nbsp;</div>
      <div style="float:left;display:none;width:35px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['Genehmigt'];?>
&nbsp;</div>
      <div style="float:left;display:none;width:35px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['Geprueft'];?>
&nbsp;</div>
      <?php if ($_smarty_tpl->tpl_vars['showAbgeschlossen']->value) {?>
      <div style="float:left;display:block;width:<?php echo $_smarty_tpl->tpl_vars['abgeschlossenWidth']->value;?>
px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['Abgeschlossen'];?>
 <?php if ($_smarty_tpl->tpl_vars['U']->value['abgeschlossen_am']) {
echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['U']->value['abgeschlossen_am'],"%d.%m.%y");
}?>&nbsp;</div>
      <?php }?>
      <div style="float:left;display:inline-block;width:<?php echo $_smarty_tpl->tpl_vars['summeWidth']->value;?>
px;text-align:right"><?php echo number_format($_smarty_tpl->tpl_vars['U']->value['summe'],2,",",".");?>
&nbsp;</div>
      <br clear=left></div>
  </a></li>
      <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
</ul>
<h2 style="margin:1rem 0 0 0;padding-bottom:0;">Enthaltene Artikel</h2>
    <table class="tblList" width="100%">
        <thead>
            <tr>
                <th>Artikel</th>
                <th>Farbe</th>
                <th>Größe</th>
                <th>Menge</th>
            </tr>

        </thead>
        <tbody>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['artikelStat']->value, 'A');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['A']->value) {
?>
    <tr>
        <td><?php echo $_smarty_tpl->tpl_vars['A']->value['Bezeichnung'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['A']->value['Farbe'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['A']->value['Groesse'];?>
</td>
        <td style="text-align:right"><?php echo $_smarty_tpl->tpl_vars['A']->value['count'];?>
</td>
    </tr>
    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </tbody>
    </table>
    <pre style="display: none;">
        <?php echo print_r($_smarty_tpl->tpl_vars['artikelStat']->value);?>

    </pre>

<!-- <h2>Startseite Mertens AG</h2> 
<p>
- Infos nachlegen
- Mails an Beteiligte verschicken
- Gesprächsnotizen
- Lagerbestand abfragen</p> 
 --> 
 </div>
<!-- Add-On Fließtext(dyn) ID: 128588 END -->
</div> 
<!-- TAB ITEM (128587) END --> 
 
<br class="floatNone" /> 
</div> 
<!-- TAB BASIC MODULE (128585) END -->

<?php }
}
