<?php
/* Smarty version 3.1.34-dev-7, created on 2021-12-15 11:17:39
  from '/var/www/html/html/umzugsteam_antraege_liste.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_61b9c0c3b49ec5_45227831',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '42c397c738069ef07c60310672550663bcaded8a' => 
    array (
      0 => '/var/www/html/html/umzugsteam_antraege_liste.html',
      1 => 1639563454,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:umzugsteam_antraege_tabs.html' => 1,
  ),
),false)) {
function content_61b9c0c3b49ec5_45227831 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/smarty3/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<!-- TAB NAVIGATION ITEMS BEGIN --> 
<div id="ID128585" class="divTabbedNavigation" style="width:100%;"> 

<?php $_smarty_tpl->_subTemplateRender("file:umzugsteam_antraege_tabs.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

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
  <div class="flds-head-colnames">
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
&ofld=abgeschlossen<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "abgeschlossen" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Abgeschlossen</a></div><br clear=left>
  </div>
<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['Umzuege']->value, 'U');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['U']->value) {
?>
      <li class="flds-body-row-wrapper">
          <a class="flds-body-row-link iconRightContentMain" href="<?php echo $_smarty_tpl->tpl_vars['U']->value['LinkOpen'];?>
">
  <div>
      <div style="float:left;display:block;width:30px;font-weight:bold;"><?php echo $_smarty_tpl->tpl_vars['U']->value['aid'];?>
&nbsp;</div>
      <div style="float:left;display:block;width:75px;" ><?php echo $_smarty_tpl->tpl_vars['U']->value['kid'];?>
&nbsp;&nbsp;</div>
      <div style="float:left;display:block;width:100px;font-weight:bold;"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['U']->value['Termin'],"%d.%m.%Y");?>
&nbsp;</div>
      <div style="float:left;display:block;width:35px;font-weight:bold;"><?php echo substr($_smarty_tpl->tpl_vars['U']->value['umzugszeit'],0,5);?>
&nbsp;</div>
      <div style="float:left;display:block;width:60px;"><?php if ($_smarty_tpl->tpl_vars['U']->value['land'] == "Deutschland") {?>DE<?php } elseif ($_smarty_tpl->tpl_vars['U']->value['land'] == "Niederlande") {?>NL<?php } else {
echo $_smarty_tpl->tpl_vars['U']->value['land'];
}?> &nbsp;</div>
      <div style="float:left;display:block;width:100px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['ort'];?>
&nbsp</div>
      <div style="float:left;display:block;width:50px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['plz'];?>
&nbsp;</div>
      <div class="geo-address"
           data-address="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['U']->value['strasse'], ENT_QUOTES, 'UTF-8', true);?>
,<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['U']->value['plz'], ENT_QUOTES, 'UTF-8', true);?>
+<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['U']->value['ort'], ENT_QUOTES, 'UTF-8', true);?>
,<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['U']->value['land'], ENT_QUOTES, 'UTF-8', true);?>
" style="float:left;display:block;width:180px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['strasse'];?>
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


<?php echo '<script'; ?>
>
    $(function() {

        $(".geo-address[data-address]").each(function() {
            var gmapUrl = "https://www.google.com/maps/dir/?api=1&travelmode=driving&destination=";
            var query = encodeURIComponent( $(this).data("address") );
            // https://www.google.com/maps/dir/?api=1&destination=Mainzer+Straße+97,65189+Wiesbaden,Deutschland&travelmode=driving
            $(this)
                .wrap(
                    $("<a/>").attr({
                        href: gmapUrl + query,
                        target: "gmap",
                        title: "Lieferadresse in Gmap anzeigen"
                    }) )
                .prepend( $("<i/>").addClass("marker icon").css("width","auto") )
                .addClass("marker icon")
                .bind("click", function(e) {
                    e.stopPropagation();
                });
        });
    });

<?php echo '</script'; ?>
>

<?php }
}
