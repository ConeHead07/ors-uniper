<?php
/* Smarty version 3.1.34-dev-7, created on 2022-03-07 12:12:00
  from '/var/www/html/html/kantraege_liste.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_6225f690c7c520_71203394',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '82792695f456c4f71cbec8ff637c476489cc8409' => 
    array (
      0 => '/var/www/html/html/kantraege_liste.html',
      1 => 1646650186,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6225f690c7c520_71203394 (Smarty_Internal_Template $_smarty_tpl) {
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
    <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "gesendet") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=gesendet" style="width:180px">Gesendet</a></li>
  <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "bearbeitung") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=bearbeitung" style="width:220px">Noch nicht gesendete Aufträge</a></li>
          <li><a href="#ID128586" style="width:0px;display:none;">&nbsp;</a></li><!--  -->
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
 Aufträge vor</h2>
<span class="d-none" data-site="kantraege liste html"></span>
<?php if ($_smarty_tpl->tpl_vars['ListBrowsing']->value) {
echo $_smarty_tpl->tpl_vars['ListBrowsing']->value;
}?>
  <ul class="ulLinkList"> 
  <div>
    <div style="float:left;display:block;width:30px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=id<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "id" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">ID</a></div>
    <div style="float:left;display:block;width:85px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=kid<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "kid" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">KID</a></div>
    <div style="float:left;display:block;width:150px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=ort<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "ort" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Lieferort</a></div>
    <div style="float:left;display:block;width:60px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=plz<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "plz" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">PLZ</a></div>
    <div style="float:left;display:block;width:190px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=strasse<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "strasse" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Stra&szlig;e</a></div>
    <div style="float:left;display:block;width:75px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=umzug<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "umzug" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Service</a></div>
    <div style="float:left;display:block;width:90px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=antragsdatum<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "antragsdatum" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Auftr.Dat.</a></div>
    <div style="float:left;display:block;width:90px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=termin<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "termin" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Lieferdatum</a></div>
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
    <div style="float:left;display:block;width:85px;font-weight:bold;"><?php echo $_smarty_tpl->tpl_vars['U']->value['kid'];?>
&nbsp;</div>
    <div style="float:left;display:block;width:150px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['ort'];?>
&nbsp;</div>
    <div style="float:left;display:block;width:60px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['plz'];?>
&nbsp;</div>
    <div style="float:left;display:block;width:190px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['strasse'];?>
&nbsp;</div>
    <div style="float:left;display:block;width:75px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['umzug'];?>
&nbsp;</div>
    <div style="float:left;display:block;width:90px;font-style:italic;"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['U']->value['Antragsdatum'],"%d.%m.%Y");?>
&nbsp;</div>
    <div style="float:left;display:block;width:90px;font-weight:bold;"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['U']->value['Termin'],"%d.%m.%Y");?>
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
