<?php
/* Smarty version 3.1.34-dev-7, created on 2021-09-22 12:40:25
  from '/var/www/html/html/kantraege_liste.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_614b243995d576_99588815',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '82792695f456c4f71cbec8ff637c476489cc8409' => 
    array (
      0 => '/var/www/html/html/kantraege_liste.html',
      1 => 1632311187,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_614b243995d576_99588815 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/smarty3/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<!-- TAB BASIC MODULE (128585) BEGIN --> 
<!-- INITIAL JS BEGIN --> 
<?php echo '<script'; ?>
 type="text/javascript"> 
  //
  $(function() {
    //$('#ID128585').tabs({fxFade: true, fxSpeed: 'fast' });
  });
  //
<?php echo '</script'; ?>
> 
<!-- INITIAL JS END --> 
 
<!-- TAB NAVIGATION ITEMS BEGIN --> 
<div id="ID128585" class="divTabbedNavigation" style="width:100%;"> 
<div class="divTabbedList" style="width:100%;"> 
<ul> 
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "bearbeitung") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=bearbeitung" style="width:160px">Noch nicht Gesendete</a></li> 
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "zurueckgegeben") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=zurueckgegeben" style="width:160px">Zu korrigierende Antr&auml;ge</a></li> 
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "gesendet") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=gesendet" style="width:180px">Gesendet und in Bearbeitung</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "aktiv") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=aktiv" style="width:160px">Aktiv</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "geschlossen") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=geschlossen" style="width:140px">Abgeschlossen</a></li>
        <li><a href="#ID128586" style="width:0px;display:none;">&nbsp;</a></li><!--  -->
        <!-- <li><a href="#ID128587">Neue Anträge</a></li> 
        <li><a href="#ID128586">Aktive Umzüge</a></li>
        <li><a href="#ID128586">Abgeschlossene Umzüge / Bewertungen</a></li>
        <li><a href="#ID128586">Lagerbestand</a></li>
        <li><a href="#ID128586">Infos nachlegen</a></li>
        <li><a href="#ID128586">E-Mails an Beteiligte senden</a></li>
        <li><a href="#ID128586">Gesprächsnotizen</a></li> -->
</ul> 
<div class="clearLeft"></div> 
</div>
<!-- TAB NAVIGATION ITEMS END --> 
 
<!-- TAB ITEM (128587) BEGIN --> 
<div id="ID128587" class="divModuleBasic padding12px width5Col heightAuto"> 
<div class="divInlay noMarginBottom borderTop"> 
</div> 

<!-- Add-On Fließtext(dyn) ID: 128588 BEGIN --> 
<div class="divInlay borderTop"> 
<h2>Es liegen <?php echo count($_smarty_tpl->tpl_vars['Umzuege']->value);?>
 <?php echo $_smarty_tpl->tpl_vars['catTitle']->value;?>
 Leistungsanforderungen vor</h2>
  (kantraege_liste.html)
<?php if ($_smarty_tpl->tpl_vars['ListBrowsing']->value) {
echo $_smarty_tpl->tpl_vars['ListBrowsing']->value;
}?>
  <ul class="ulLinkList"> 
  <div>
  <div style="float:left;display:block;width:30px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=id<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "id" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">ID</a></div>
  <div style="float:left;display:block;width:100px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=termin<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "termin" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Lieferdatum</a></div>
    <div style="float:left;display:block;width:75px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=ort<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "ort" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Lieferort</a></div>
    <div style="float:left;display:block;width:100px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=plz<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "plz" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">PLZ</a></div>
    <div style="float:left;display:block;width:100px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=strasse<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "strasse" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Stra&szlig;e</a></div>
  <div style="float:left;display:block;width:75px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=umzug<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "umzug" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Service</a></div>
  <div style="float:left;display:block;width:100px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=antragsdatum<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "antragsdatum" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Auftragseingang</a></div>
  <div style="float:left;display:block;width:90px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=genehmigt<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "genehmigt" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>" title="Genehmigt">Genehmigt</a></div>
  <div style="float:left;display:block;width:90px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=geprueft<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "geprueft" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>" title="von merTens geprueft">M</a></div>
  <div style="float:left;display:block;width:80px;font-weight:bold;color:#00869c;" title="Abgeschlossen"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=abgeschlossen<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "abgeschlossen" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Abgeschlossen</a></div><br clear=left></div>

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
  <div style="float:left;display:block;width:100px;font-weight:bold;"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['U']->value['Termin'],"%d.%m.%Y");?>
&nbsp;</div>
  <div style="float:left;display:block;width:100px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['ort'];?>
&nbsp;</div>
  <div style="float:left;display:block;width:100px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['plz'];?>
&nbsp;</div>
  <div style="float:left;display:block;width:75px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['strasse'];?>
&nbsp;</div>
  <div style="float:left;display:block;width:75px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['umzug'];?>
&nbsp;</div>
  <div style="float:left;display:block;width:100px;font-style:italic;"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['U']->value['Antragsdatum'],"%d.%m.%Y");?>
&nbsp;</div>
  <div style="float:left;display:block;width:90px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['Genehmigt'];?>
&nbsp;</div>
  <div style="float:left;display:block;width:90px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['Geprueft'];?>
&nbsp;</div>
  <div style="float:left;display:block;width:80px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['Abgeschlossen'];?>
 <?php if ($_smarty_tpl->tpl_vars['U']->value['abgeschlossen_am']) {
echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['U']->value['abgeschlossen_am'],"%d.%m.%Y");
}?></div><br clear=left></div>
  </a></li>
<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
  </ul> 
<!-- <h2>Startseite Mertens AG</h2> 
<p>
- Infos nachlegen
- Mails an Beteiligte verschicken
- Gesprächsnotizen
- Lagerbestand abfragen</p> 
</div> --> 
<!-- Add-On Fließtext(dyn) ID: 128588 END --> 
</div> 
<!-- TAB ITEM (128587) END --> 
 
<br class="floatNone" /> 
</div> 
<!-- TAB BASIC MODULE (128585) END -->

<?php }
}
