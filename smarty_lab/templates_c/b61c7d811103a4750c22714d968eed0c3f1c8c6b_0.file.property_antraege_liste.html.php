<?php
/* Smarty version 3.1.34-dev-7, created on 2022-02-24 06:35:05
  from '/var/www/html/html/property_antraege_liste.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_62172719c95720_21772459',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b61c7d811103a4750c22714d968eed0c3f1c8c6b' => 
    array (
      0 => '/var/www/html/html/property_antraege_liste.html',
      1 => 1646312042,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62172719c95720_21772459 (Smarty_Internal_Template $_smarty_tpl) {
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
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "angeboten") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=angeboten<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>">Angebote</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "neue") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=neue<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>">Bestellungen</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "bearbeitung") {?>class="activeTab"<?php }?> style="display:none"><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=bearbeitung<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>">Neue Antr&auml;ge von merTens</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "aktive") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=aktive<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>">Avisiert</a></li><!-- Genehmigt -->
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "abgelehnte") {?>class="activeTab"<?php }?> style="display:none"><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=abgelehnte<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>" style="width:70px;color:#ffd700;">Abgelehnt</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "abgeschlossene") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=abgeschlossene<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>">Abgeschlossen</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "stornierte") {?>class="activeTab"<?php }?> style="display:none"><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=stornierte<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>">Storno</a></li>
        <!--  -->
</ul><br clear="all">
</div>
<!-- TAB NAVIGATION ITEMS END -->

<?php $_smarty_tpl->_assignInScope('showLieferdatum', true);
$_smarty_tpl->_assignInScope('showAvisiert', true);
$_smarty_tpl->_assignInScope('showAbgeschlossen', true);
$_smarty_tpl->_assignInScope('lieferdatumWidth', 100);?>
    <?php $_smarty_tpl->_assignInScope('avisiertWidth', 80);?>
    <?php $_smarty_tpl->_assignInScope('abgeschlossenWidth', 80);
$_smarty_tpl->_assignInScope('serviceWidth', 80);
$_smarty_tpl->_assignInScope('strasseWidth', 200);?>

<?php if ($_smarty_tpl->tpl_vars['cat']->value == "neue") {?>
    <?php $_smarty_tpl->_assignInScope('showLieferdatum', false);?>
    <?php $_smarty_tpl->_assignInScope('showAvisiert', false);?>
    <?php $_smarty_tpl->_assignInScope('showAbgeschlossen', false);?>
    <?php $_smarty_tpl->_assignInScope('strasseWidth', $_smarty_tpl->tpl_vars['strasseWidth']->value+$_smarty_tpl->tpl_vars['lieferdatumWidth']->value+$_smarty_tpl->tpl_vars['avisiertWidth']->value+$_smarty_tpl->tpl_vars['abgeschlossenWidth']->value);
} elseif ($_smarty_tpl->tpl_vars['cat']->value == "aktive") {?>
    <?php $_smarty_tpl->_assignInScope('showAbgeschlossen', false);?>
    <?php $_smarty_tpl->_assignInScope('strasseWidth', $_smarty_tpl->tpl_vars['strasseWidth']->value+$_smarty_tpl->tpl_vars['abgeschlossenWidth']->value);
}?>

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
<?php if ($_smarty_tpl->tpl_vars['cat']->value == "bearbeitung") {?>
<h2>Es liegen <?php echo $_smarty_tpl->tpl_vars['num_all']->value;?>
 Aufträge zur Bearbeitung vor</h2>
<?php } else { ?>
<h2>Es liegen <?php echo $_smarty_tpl->tpl_vars['num_all']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['cat']->value;?>
 Aufträge vor</h2>
<?php }?>
    <span style="display: none">(property antraege liste html)</span>
<div style="clear:both"></div>
<?php if ($_smarty_tpl->tpl_vars['ListBrowsing']->value) {
echo $_smarty_tpl->tpl_vars['ListBrowsing']->value;
}?>
  <ul class="ulLinkList"> 
  <div>
      <div style="float:left;display:block;width:30px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=id<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "id" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">ID</a></div>
      <div style="float:left;display:block;width:70px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=personalnr<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "personalnr" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">KID</a></div>
      <?php if ($_smarty_tpl->tpl_vars['showLieferdatum']->value) {?>
      <div style="float:left;display:block;width:<?php echo $_smarty_tpl->tpl_vars['lieferdatumWidth']->value;?>
px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=termin<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "termin" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Lieferdatum</a></div>
      <?php }?>
      <div style="float:left;display:block;width:70px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=land<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "land" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Land</a></div>
      <div style="float:left;display:block;width:100px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=ort<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "ort" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Lieferort</a></div>
      <div style="float:left;display:block;width:60px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=plz<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "plz" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">PLZ</a></div>
      <div style="float:left;display:block;width:<?php echo $_smarty_tpl->tpl_vars['strasseWidth']->value;?>
px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=strasse<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "strasse" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Stra&szlig;e</a></div>
            <div style="float:left;display:block;width:90px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=antragsdatum<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "antragsdatum" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Auftr.Dat.</a></div>
      <?php if ($_smarty_tpl->tpl_vars['showAvisiert']->value) {?>
      <div style="float:left;display:block;width:<?php echo $_smarty_tpl->tpl_vars['avisiertWidth']->value;?>
px;font-weight:bold;color:#00869c;" title="Von merTens geprueft"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=bestaetigt<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "bestaetigt" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Avisiert</a></div>
      <?php }?>
      <?php if ($_smarty_tpl->tpl_vars['showAbgeschlossen']->value) {?>
      <div style="float:left;display:block;width:<?php echo $_smarty_tpl->tpl_vars['abgeschlossenWidth']->value;?>
px;font-weight:bold;color:#00869c;" title="Abgeschlossen"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=abgeschlossen<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "abgeschlossen" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Abgeschlossen</a></div>
      <?php }?>
      <br clear=left>
  </div>
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
      <div style="float:left;display:block;width:70px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['kid'];?>
&nbsp;</div>
      <?php if ($_smarty_tpl->tpl_vars['showLieferdatum']->value) {?>
      <div style="float:left;display:block;width:<?php echo $_smarty_tpl->tpl_vars['lieferdatumWidth']->value;?>
px;font-weight:bold;"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['U']->value['Termin'],"%d.%m.%Y");?>
&nbsp;</div>
      <?php }?>
      <div style="float:left;display:block;width:70px;"><?php if ($_smarty_tpl->tpl_vars['U']->value['land'] == "Deutschland") {?>DE<?php } elseif ($_smarty_tpl->tpl_vars['U']->value['land'] == "Belgien") {?>BE<?php } elseif ($_smarty_tpl->tpl_vars['U']->value['land'] == "England") {?>EN<?php } elseif ($_smarty_tpl->tpl_vars['U']->value['land'] == "Niederlande") {?>NL<?php } else {
echo $_smarty_tpl->tpl_vars['U']->value['land'];
}?>&nbsp;</div>
      <div style="float:left;display:block;width:100px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['ort'];?>
&nbsp;</div>
      <div style="float:left;display:block;width:60px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['plz'];?>
&nbsp;</div>
      <div style="float:left;display:block;width:<?php echo $_smarty_tpl->tpl_vars['strasseWidth']->value;?>
px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['strasse'];?>
&nbsp;</div>
            <div style="float:left;display:block;width:90px;font-style:italic;"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['U']->value['Antragsdatum'],"%d.%m.%Y");?>
&nbsp;</div>
      <?php if ($_smarty_tpl->tpl_vars['showAvisiert']->value) {?>
      <div style="float:left;display:block;width:<?php echo $_smarty_tpl->tpl_vars['avisiertWidth']->value;?>
px;" data-u="<?php echo $_smarty_tpl->tpl_vars['U']->value['geprueft'];?>
,<?php echo $_smarty_tpl->tpl_vars['U']->value['bestaetigt'];?>
"><?php echo $_smarty_tpl->tpl_vars['U']->value['Avisiert'];?>
&nbsp;</div>
      <?php }?>
      <?php if ($_smarty_tpl->tpl_vars['showAbgeschlossen']->value) {?>
      <div style="float:left;display:block;width:<?php echo $_smarty_tpl->tpl_vars['abgeschlossenWidth']->value;?>
px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['Abgeschlossen'];?>
 <?php if ($_smarty_tpl->tpl_vars['U']->value['abgeschlossen_am']) {
echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['U']->value['abgeschlossen_am'],"%d.%m.%y");
}?>&nbsp;</div>
      <?php }?>
      <br clear=left>
  </div>
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
