<?php
/* Smarty version 3.1.34-dev-7, created on 2020-01-09 10:31:48
  from '/application/html/admin_antraege_liste.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5e17011482a887_08571412',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '784472f2e7f9d061b8ffedd517e50c8f7683617d' => 
    array (
      0 => '/application/html/admin_antraege_liste.html',
      1 => 1577987865,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5e17011482a887_08571412 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/application/smarty3/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<!-- TAB NAVIGATION ITEMS BEGIN --> 
<div id="ID128585" class="divTabbedNavigation" style="width:100%;"> 
<div class="divTabbedList" style="width:100%;"> 

<ul> 
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "neue") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=neue<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>" style="width:90px">Neue Anträge</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "angeboten") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=angeboten<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>" style="width:125px">zur Genehmigung</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "aktive") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=aktive<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>" style="width:105px">Aktive Anträge</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "abgeschlossene") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=abgeschlossene<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>" style="width:150px">Abgeschlossene Anträge</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "temp") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=temp<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>" style="width:30px">Tmp</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "zurueckgegeben") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=zurueckgegeben<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>" style="width:60px;color:#ffd700;">Zur&uuml;ck</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "abgelehnte") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=abgelehnte<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>" style="width:60px;color:#ffd700;">Abgelehnt</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "stornierte") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=stornierte<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>" style="width:60px;color:#ffd700;">Storno</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "suche") {?>class="activeTab"<?php }?>><a href="?s=umzugssuche<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>" style="width:60px">Suche</a></li>
        <!-- <li><a href="#ID128586" style="width:60px">&nbsp;</a></li> -->
</ul><br clear="all">
</div>
<!-- TAB NAVIGATION ITEMS END --> 
 
<!-- TAB ITEM (128587) BEGIN --> 
<div id="ID128587" class="divModuleBasic padding12px width5Col heightAuto"> 
<div class="divInlay noMarginBottom borderTop"> 
</div> 

<!-- Add-On Fließtext(dyn) ID: 128588 BEGIN --> 
<div class="divInlay borderTop">
    <div style="float:right;padding:10px 20px 0 0;">
        <a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=id&odir=<?php echo $_smarty_tpl->tpl_vars['odir']->value;?>
&allusers=<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>0<?php } else { ?>1<?php }?>">Wechsel zu <?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>meine Antr&auml;ge<?php } else { ?>Antr&auml;gen aller User<?php }?></a>
    </div>

<?php if ($_smarty_tpl->tpl_vars['cat']->value == "angeboten") {?>
<h2>Es liegen <?php echo $_smarty_tpl->tpl_vars['num_all']->value;?>
 von Mertens gestellte Leistungsanforderungen vor</h2>
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
  <div style="float:left;display:block;width:75px;" ><?php echo $_smarty_tpl->tpl_vars['U']->value['umzug'];?>
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
