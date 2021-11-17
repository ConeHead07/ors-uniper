<?php
/* Smarty version 3.1.34-dev-7, created on 2021-11-11 12:51:44
  from '/var/www/html/uniper/htdocs/html/umzugsteam_antraege_liste.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_618d03d0b860c9_11650327',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'fdbb6dc7645909ce41a40242e2078a9c2c8c44e9' => 
    array (
      0 => '/var/www/html/uniper/htdocs/html/umzugsteam_antraege_liste.html',
      1 => 1636476504,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_618d03d0b860c9_11650327 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/uniper/htdocs/smarty3/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<!-- TAB NAVIGATION ITEMS BEGIN --> 
<div id="ID128585" class="divTabbedNavigation" style="width:100%;"> 
<div class="divTabbedList" style="width:100%;"> 

<ul>
    <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "heute") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=heute<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>">Auslieferung heute</a></li>
    <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "aktive") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=aktive<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>">Aktive Aufträge</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "abgeschlossene") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=abgeschlossene<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>" style="width:150px">Abgeschlossen</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "suche") {?>class="activeTab"<?php }?>><a href="?s=umzugssuche<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>">Suche</a></li>
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
    <div style="float:right;padding:10px 20px 0 0;display:none">
        <a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=id&odir=<?php echo $_smarty_tpl->tpl_vars['odir']->value;?>
&allusers=<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>0<?php } else { ?>1<?php }?>">Wechsel zu <?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>meine Auftr&auml;ge<?php } else { ?>Auftr&auml;gen aller User<?php }?></a>
    </div>

<?php if ($_smarty_tpl->tpl_vars['cat']->value == "angeboten") {?>
<h2 data-site="admin/antraege/liste/html">Es liegen <?php echo $_smarty_tpl->tpl_vars['num_all']->value;?>
 von Mertens gestellte Aufträge vor</h2>
<?php } else { ?>
<h2 data-site="admin/antraege/liste/html">Es liegen <?php echo $_smarty_tpl->tpl_vars['num_all']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['cat']->value;?>
 Aufträge vor</h2>
<?php }?>

<div style="clear:both"></div>
<?php if ($_smarty_tpl->tpl_vars['ListBrowsing']->value) {
echo $_smarty_tpl->tpl_vars['ListBrowsing']->value;
}?>
  <ul class="ulLinkList"> 
  <div>
  <div style="float:left;display:block;width:30px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=id<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "id" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">ID</a></div>
      <div style="float:left;display:block;width:75px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=kid<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "kid" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">KID</a></div>
  <div style="float:left;display:block;width:100px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=termin<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "termin" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Lieferdatum</a></div>
      <div style="float:left;display:block;width:60px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=land<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "land" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Land</a></div>
      <div style="float:left;display:block;width:100px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=ort<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "ort" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Lieferort</a></div>
  <div style="float:left;display:block;width:50px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=plz<?php if ($_smarty_tpl->tpl_vars['ofld']->value == 'plz' && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">PLZ</a></div>
      <div style="float:left;display:block;width:180px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=strasse<?php if ($_smarty_tpl->tpl_vars['ofld']->value == 'strasse' && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Stra&szlig;e</a></div>
      <div style="float:left;display:block;width:100px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=name<?php if ($_smarty_tpl->tpl_vars['ofld']->value == 'name' && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Name</a></div>
  <div style="float:left;display:block;width:75px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=service<?php if ($_smarty_tpl->tpl_vars['ofld']->value == 'service' && $_smarty_tpl->tpl_vars['odir']->value != 'DESC') {?>&odir=DESC<?php }?>">Service</a></div>
  <div style="float:left;display:block;width:100px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=antragsdatum<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "antragsdatum" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Auftr.Dat.</a></div>

  <div style="float:left;display:none;width:80px;font-weight:bold;color:#00869c;" title="Abgeschlossen"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
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
      <div style="float:left;display:block;width:75px;" ><?php echo $_smarty_tpl->tpl_vars['U']->value['kid'];?>
&nbsp;&nbsp;</div>
      <div style="float:left;display:block;width:100px;font-weight:bold;"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['U']->value['Termin'],"%d.%m.%Y");?>
&nbsp;</div>
      <div style="float:left;display:block;width:60px;"><?php if ($_smarty_tpl->tpl_vars['U']->value['land'] == "Deutschland") {?>DE<?php } elseif ($_smarty_tpl->tpl_vars['U']->value['land'] == "Niederlande") {?>NL<?php } else {
echo $_smarty_tpl->tpl_vars['U']->value['land'];
}?> &nbsp;</div>
      <div style="float:left;display:block;width:100px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['ort'];?>
&nbsp</div>
      <div style="float:left;display:block;width:50px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['plz'];?>
&nbsp;</div>
      <div style="float:left;display:block;width:180px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['strasse'];?>
&nbsp;</div>
      <div style="float:left;display:block;width:100px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['name'];?>
&nbsp;</div>
      <div style="float:left;display:block;width:75px;" ><?php echo $_smarty_tpl->tpl_vars['U']->value['service'];?>
&nbsp;&nbsp;</div>
      <div style="float:left;display:block;width:100px;font-style:italic;"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['U']->value['Antragsdatum'],"%d.%m.%Y");?>
&nbsp;</div>
      <div style="float:left;display:none;width:80px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['Abgeschlossen'];?>
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
