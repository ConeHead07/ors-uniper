<?php
/* Smarty version 3.1.34-dev-7, created on 2021-09-21 15:14:58
  from '/var/www/html/html/property_antraege_liste.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_6149f6f20e7b30_06665974',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b61c7d811103a4750c22714d968eed0c3f1c8c6b' => 
    array (
      0 => '/var/www/html/html/property_antraege_liste.html',
      1 => 1631631864,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6149f6f20e7b30_06665974 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/smarty3/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<!-- TAB BASIC MODULE (128585) BEGIN --> 
<!-- INITIAL JS BEGIN --> 
<?php echo '<script'; ?>
 type="text/javascript"> 
  //
  //$(function() {
    //$('#ID128585').tabs({fxFade: true, fxSpeed: 'fast' });
  //});
  //
<?php echo '</script'; ?>
> 
<!-- INITIAL JS END --> 

<!-- TAB NAVIGATION ITEMS BEGIN --> 
<div id="ID128585" class="divTabbedNavigation" style="width:100%;"> 
<div class="divTabbedList" style="width:100%;"> 
<ul> 
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "neue") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=neue<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>" style="width:170px">Neue Antr&auml;ge</a></li> 
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "bearbeitung") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=bearbeitung<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>" style="width:170px">Neue Antr&auml;ge von merTens</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "aktive") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=aktive<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>" style="width:170px">Aktive Antr&auml;ge</a></li><!-- Genehmigt -->
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "abgelehnte") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=abgelehnte<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>" style="width:70px;color:#ffd700;">Abgelehnt</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "abgeschlossene") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=abgeschlossene<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>" style="width:90px">Abgeschlossen</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "stornierte") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=stornierte<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>" style="width:60px">Storno</a></li>
        <li><a href="#ID128586" style="width:50px">&nbsp;</a></li><!--  -->
</ul><br clear="all">
</div>
<!-- TAB NAVIGATION ITEMS END --> 
 
<!-- TAB ITEM (128587) BEGIN --> 
<div id="ID128587" class="divModuleBasic padding12px width5Col heightAuto"> 
<div class="divInlay noMarginBottom borderTop"> 
</div> 

<!-- Add-On Flie�text(dyn) ID: 128588 BEGIN --> 
<div class="divInlay borderTop"> 
    <div style="float:right;padding:10px 20px 0 0;">
        <a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=id&odir=<?php echo $_smarty_tpl->tpl_vars['odir']->value;?>
&allusers=<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>0<?php } else { ?>1<?php }?>">Wechsel zu <?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>meine Antr&auml;ge<?php } else { ?>Antr&auml;gen aller User<?php }?></a>
    </div>
<?php if ($_smarty_tpl->tpl_vars['cat']->value == "bearbeitung") {?>
<h2>Es liegen <?php echo $_smarty_tpl->tpl_vars['num_all']->value;?>
 Leistungsanforderungen zur Bearbeitung vor</h2>
<?php } else { ?>
<h2>Es liegen <?php echo $_smarty_tpl->tpl_vars['num_all']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['cat']->value;?>
 Leistungsanforderungen vor</h2>
<?php }?>
<div style="clear:both"></div>
<?php if ($_smarty_tpl->tpl_vars['ListBrowsing']->value) {
echo $_smarty_tpl->tpl_vars['ListBrowsing']->value;
}?>
  <ul class="ulLinkList"> 
  <div>
  <div style="float:left;display:block;width:30px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=id<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "id" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">ID</a></div>
  <div style="float:left;display:block;width:100px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=termin<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "termin" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Termin</a></div>
  <div style="float:left;display:block;width:130px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=von<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "von" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Von</a></div>
  <div style="float:left;display:block;width:130px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=nach<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "nach" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Nach</a></div>
  <div style="float:left;display:block;width:75px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=ort<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "ort" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Auftragsort</a></div>
  <div style="float:left;display:block;width:75px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=umzug<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "umzug" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Mit Umzug</a></div>
  <div style="float:left;display:block;width:100px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=antragsdatum<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "antragsdatum" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Auftragseingang</a></div>
  <div style="float:left;display:block;width:35px;font-weight:bold;color:#00869c;" title="Genehmigt"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=genehmigt<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "genehmigt_br" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">G</a></div>
  <div style="float:left;display:block;width:35px;font-weight:bold;color:#00869c;" title="Von merTens geprueft"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=bestaetigt<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "bestaetigt" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">M</a></div>
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
  <div style="float:left;display:block;width:130px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['Von'];?>
&nbsp;</div>
  <div style="float:left;display:block;width:130px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['Nach'];?>
&nbsp;</div>
  <div style="float:left;display:block;width:75px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['ort'];?>
&nbsp;</div>
  <div style="float:left;display:block;width:75px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['umzug'];?>
&nbsp;</div>
  <div style="float:left;display:block;width:100px;font-style:italic;"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['U']->value['Antragsdatum'],"%d.%m.%Y");?>
&nbsp;</div>
  <div style="float:left;display:block;width:35px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['Genehmigt'];?>
&nbsp;</div>
  <div style="float:left;display:block;width:35px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['Geprueft'];?>
&nbsp;</div>
  <div style="float:left;display:block;width:80px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['Abgeschlossen'];?>
 <?php if ($_smarty_tpl->tpl_vars['U']->value['abgeschlossen_am']) {
echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['U']->value['abgeschlossen_am'],"%d.%m.%y");
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
- Gespr�chsnotizen
- Lagerbestand abfragen</p> 
</div> --> 
<!-- Add-On Flie�text(dyn) ID: 128588 END --> 
</div> 
<!-- TAB ITEM (128587) END --> 
 
<br class="floatNone" /> 
</div> 
<!-- TAB BASIC MODULE (128585) END -->

<?php }
}
