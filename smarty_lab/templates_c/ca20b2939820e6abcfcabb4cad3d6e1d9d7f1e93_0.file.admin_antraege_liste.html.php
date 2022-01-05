<?php
/* Smarty version 3.1.34-dev-7, created on 2022-01-05 16:34:18
  from '/var/www/html/html/admin_antraege_liste.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_61d5ba7a9c13f9_34875102',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ca20b2939820e6abcfcabb4cad3d6e1d9d7f1e93' => 
    array (
      0 => '/var/www/html/html/admin_antraege_liste.html',
      1 => 1641396314,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:umzugsteam_antraege_tabs.html' => 1,
    'file:admin_antraege_tabs.html' => 1,
  ),
),false)) {
function content_61d5ba7a9c13f9_34875102 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/smarty3/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<!-- TAB NAVIGATION ITEMS BEGIN -->
<style>
    .tour-caption {
        font-weight:bold;
        font-size: small;
        margin-right: 5px;
    }
    a.tour-link {
        font-size: small;
        text-decoration: none;
        border: 1px solid #848383;
        background-color: #c4c3c3;
        border-radius: 4px;
        padding-left: 5px;
        padding-right: 5px;
    }
    a.tour-link:hover {
        background-color: #dbdada;
        text-decoration: none;
    }
    .flds-body-row .fld-cell {
        word-break: break-all;
    }
    .fld-cell.fld-checkbox {
        display: inline-block;
        width:24px;
    }

</style>
<div id="ID128585" class="divTabbedNavigation" style="width:100%;">

    <div style="display: flex;justify-content: space-between">
        <?php echo $_smarty_tpl->tpl_vars['top']->value;?>

        <div><?php if ($_smarty_tpl->tpl_vars['s']->value == "auslieferung" || $_smarty_tpl->tpl_vars['top']->value == "auslieferung") {?>
            <?php $_smarty_tpl->_subTemplateRender("file:umzugsteam_antraege_tabs.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
        <?php } else { ?>
            <?php $_smarty_tpl->_subTemplateRender("file:admin_antraege_tabs.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
        <?php }?></div>
        <div style="align-self: flex-end;margin-right:5px;margin-bottom:2px;">
            <button id="btnCsvExport" class="btn btn-blue" style="padding:10px;cursor: pointer;">CSV-Export</button>
        </div>
    </div>
<!-- TAB NAVIGATION ITEMS END --> 
 
<!-- TAB ITEM (128587) BEGIN --> 
<div id="ID128587" class="divModuleBasic padding12px width5Col heightAuto"> 
<div class="divInlay noMarginBottom borderTop">
</div>

<!-- Add-On Fließtext(dyn) ID: 128588 BEGIN -->
<div class="divInlay borderTop">
    
    <div>
    <?php if ($_smarty_tpl->tpl_vars['cat']->value == "angeboten") {?>
        <h2 style="float:left;margin-left:0;padding-left:0;" data-site="admin/antraege/liste/html">Es liegen <?php echo $_smarty_tpl->tpl_vars['num_all']->value;?>
 von Mertens gestellte Aufträge vor</h2>
    <?php } else { ?>
        <h2 style="float:left;margin-left:0;padding-left:0;" data-site="admin/antraege/liste/html">Es liegen <?php echo $_smarty_tpl->tpl_vars['num_all']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['cat']->value;?>
 Aufträge vor</h2>
    <?php }?>
        <h2 style="float:right"><?php echo number_format($_smarty_tpl->tpl_vars['summeTotal']->value,2,",",".");?>
 &euro;</h2>
        <span style="clear:both"></span>
    </div>
    <?php if (!empty($_smarty_tpl->tpl_vars['aTourNrs']->value)) {?>
    <span style="clear:both"></span>
    <div style="width:100%;display:block;float:left;margin-top:0.5rem;">
        <span class="tour-caption">Touren: </span>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['aTourNrs']->value, 'T');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['T']->value) {
?>
            <a class="tour-link" href="<?php echo $_smarty_tpl->tpl_vars['T']->value['url'];?>
"><?php echo $_smarty_tpl->tpl_vars['T']->value['tour_kennung'];?>
</a>
    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        <span style="clear:both"></span>
    </div>
    <?php }?>
    <?php $_smarty_tpl->_assignInScope('showCheck', !empty($_smarty_tpl->tpl_vars['selectable']->value));?>
    <?php $_smarty_tpl->_assignInScope('showTour', false);?>
    <?php $_smarty_tpl->_assignInScope('showAuftragsdatum', true);?>
    <?php $_smarty_tpl->_assignInScope('showLieferdatum', true);?>
    <?php $_smarty_tpl->_assignInScope('showBestaetigt', true);?>
    <?php $_smarty_tpl->_assignInScope('showAbgeschlossen', true);?>
    <?php $_smarty_tpl->_assignInScope('showGenehmigt', false);?>
    <?php $_smarty_tpl->_assignInScope('showGeprueft', false);?>
    <?php $_smarty_tpl->_assignInScope('showLeistungen', false);?>
    <?php $_smarty_tpl->_assignInScope('showSumme', true);?>
    <?php $_smarty_tpl->_assignInScope('showZeit', false);?>
    <?php $_smarty_tpl->_assignInScope('showName', false);?>
    <?php $_smarty_tpl->_assignInScope('showErinnert', false);?>
    <?php $_smarty_tpl->_assignInScope('checkWidth', 24);?>
    <?php $_smarty_tpl->_assignInScope('aidWidth', 35);?>
    <?php $_smarty_tpl->_assignInScope('kidWidth', 65);?>
    <?php $_smarty_tpl->_assignInScope('landWidth', 40);?>
    <?php $_smarty_tpl->_assignInScope('ortWidth', 115);?>
    <?php $_smarty_tpl->_assignInScope('plzWidth', 50);?>
    <?php $_smarty_tpl->_assignInScope('zeitWidth', 50);?>
    <?php $_smarty_tpl->_assignInScope('tourWidth', 50);?>
    <?php $_smarty_tpl->_assignInScope('strasseWidth', 140);?>
    <?php $_smarty_tpl->_assignInScope('serviceWidth', 50);?>
    <?php $_smarty_tpl->_assignInScope('beauftragtWidth', 75);?>
    <?php $_smarty_tpl->_assignInScope('bestaetigtWidth', 75);?>
    <?php $_smarty_tpl->_assignInScope('genehmigtWidth', 60);?>
    <?php $_smarty_tpl->_assignInScope('leistungenWidth', 50);?>
    <?php $_smarty_tpl->_assignInScope('lieferdatumWidth', 70);?>
    <?php $_smarty_tpl->_assignInScope('abgeschlossenWidth', 75);?>
    <?php $_smarty_tpl->_assignInScope('summeWidth', 60);?>
    <?php $_smarty_tpl->_assignInScope('erinnertWidth', 75);?>

<?php if ($_smarty_tpl->tpl_vars['s']->value == "auslieferung") {?>
    <?php $_smarty_tpl->_assignInScope('zeitWidth', 45);?>
    <?php $_smarty_tpl->_assignInScope('landWidth', 60);?>
    <?php $_smarty_tpl->_assignInScope('strasseWidth', 180);?>
    <?php $_smarty_tpl->_assignInScope('nameWidth', 80);?>
    <?php $_smarty_tpl->_assignInScope('showName', true);?>
    <?php $_smarty_tpl->_assignInScope('showZeit', true);?>
    <?php $_smarty_tpl->_assignInScope('showAuftragsdatum', false);?>
    <?php $_smarty_tpl->_assignInScope('showAbgeschlossen', false);?>
    <?php $_smarty_tpl->_assignInScope('showBestaetigt', false);?>
    <?php $_smarty_tpl->_assignInScope('showTour', true);?>

    <?php if ($_smarty_tpl->tpl_vars['cat']->value == "heute") {?>

    <?php } elseif ($_smarty_tpl->tpl_vars['cat']->value == "aktive") {?>

    <?php } elseif ($_smarty_tpl->tpl_vars['cat']->value == "abgeschlossene") {?>
        <?php $_smarty_tpl->_assignInScope('showName', false);?>
        <?php $_smarty_tpl->_assignInScope('showZeit', false);?>
        <?php $_smarty_tpl->_assignInScope('strasseWidth', $_smarty_tpl->tpl_vars['strasseWidth']->value+$_smarty_tpl->tpl_vars['zeitWidth']->value+$_smarty_tpl->tpl_vars['nameWidth']->value);?>
    <?php }
} elseif ($_smarty_tpl->tpl_vars['cat']->value == "neue") {?>
    <?php $_smarty_tpl->_assignInScope('showLieferdatum', false);?>
    <?php $_smarty_tpl->_assignInScope('showAbgeschlossen', false);?>
    <?php $_smarty_tpl->_assignInScope('showLeistungen', true);?>
    <?php $_smarty_tpl->_assignInScope('strasseWidth', $_smarty_tpl->tpl_vars['strasseWidth']->value+$_smarty_tpl->tpl_vars['abgeschlossenWidth']->value+$_smarty_tpl->tpl_vars['lieferdatumWidth']->value);
} elseif ($_smarty_tpl->tpl_vars['cat']->value == "temp") {?>
    <?php $_smarty_tpl->_assignInScope('showLieferdatum', false);?>
    <?php $_smarty_tpl->_assignInScope('showAbgeschlossen', false);?>
    <?php $_smarty_tpl->_assignInScope('showBestaetigt', false);?>
    <?php $_smarty_tpl->_assignInScope('showLeistungen', true);?>
    <?php $_smarty_tpl->_assignInScope('showErinnert', true);?>
    <?php $_smarty_tpl->_assignInScope('strasseWidth', $_smarty_tpl->tpl_vars['strasseWidth']->value+$_smarty_tpl->tpl_vars['abgeschlossenWidth']->value+$_smarty_tpl->tpl_vars['lieferdatumWidth']->value+$_smarty_tpl->tpl_vars['bestaetigtWidth']->value-$_smarty_tpl->tpl_vars['checkWidth']->value-$_smarty_tpl->tpl_vars['erinnertWidth']->value);
} elseif ($_smarty_tpl->tpl_vars['cat']->value == "disponierte") {?>
    <?php $_smarty_tpl->_assignInScope('showBestaetigt', false);?>
    <?php $_smarty_tpl->_assignInScope('showAbgeschlossen', false);?>
    <?php $_smarty_tpl->_assignInScope('showAuftragsdatum', false);?>
    <?php $_smarty_tpl->_assignInScope('showGenehmigt', false);?>
    <?php $_smarty_tpl->_assignInScope('showTour', true);?>
    <?php $_smarty_tpl->_assignInScope('showGeprueft', false);?>
    <?php $_smarty_tpl->_assignInScope('showLeistungen', true);?>
    <?php $_smarty_tpl->_assignInScope('showZeit', true);?>

    <?php $_smarty_tpl->_assignInScope('strasseWidth', $_smarty_tpl->tpl_vars['strasseWidth']->value+$_smarty_tpl->tpl_vars['bestaetigtWidth']->value+$_smarty_tpl->tpl_vars['abgeschlossenWidth']->value+$_smarty_tpl->tpl_vars['beauftragtWidth']->value-$_smarty_tpl->tpl_vars['zeitWidth']->value-$_smarty_tpl->tpl_vars['tourWidth']->value);
} elseif ($_smarty_tpl->tpl_vars['cat']->value == "aktive") {?>
    <?php $_smarty_tpl->_assignInScope('showAbgeschlossen', false);?>
    <?php $_smarty_tpl->_assignInScope('showTour', true);?>
    <?php $_smarty_tpl->_assignInScope('showZeit', true);?>
    <?php $_smarty_tpl->_assignInScope('strasseWidth', $_smarty_tpl->tpl_vars['strasseWidth']->value+$_smarty_tpl->tpl_vars['abgeschlossenWidth']->value-$_smarty_tpl->tpl_vars['zeitWidth']->value-$_smarty_tpl->tpl_vars['tourWidth']->value+50);
} elseif ($_smarty_tpl->tpl_vars['cat']->value == "abgeschlossene") {?>
    <?php $_smarty_tpl->_assignInScope('showBestaetigt', false);?>
    <?php $_smarty_tpl->_assignInScope('strasseWidth', $_smarty_tpl->tpl_vars['strasseWidth']->value+$_smarty_tpl->tpl_vars['bestaetigtWidth']->value+$_smarty_tpl->tpl_vars['zeitWidth']->value);?>
    <?php $_smarty_tpl->_assignInScope('showZeit', false);
}?>
<style>
    .ulLinkList * {
        box-sizing: border-box;
    }
    .ulLinkList .fld-cell {
        float:left;
        display:block;
    }
    .ulLinkList .flds-head-colnames .fld-cell {
        float:left;
        display:block;
        font-weight:bold;
    }
    .ulLinkList .flds-head-colnames .order {
        text-decoration: underline;
        cursor: pointer;
    }
    .ulLinkList .flds-head-colnames .order:hover {
        color: #0078dc;
    }
    .ulLinkList .flds-head-colsearch .fld-cell input {
        width: calc(100% - 2px);
    }
    .fld-cell-amount,
    .ulLinkList .fld-summe,
    .ulLinkList .fld-summe input {
        text-align: right;
    }
    .currency-euro::after {
        content: " €";
    }
</style>
<div style="clear:both"></div>
<?php if ($_smarty_tpl->tpl_vars['ListBrowsing']->value) {
echo $_smarty_tpl->tpl_vars['ListBrowsing']->value;
}?>
    <form id="frmList" method="get">
        <input type="hidden" name="s" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['s']->value, ENT_QUOTES, 'UTF-8', true);?>
">
        <input type="hidden" name="cat" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cat']->value, ENT_QUOTES, 'UTF-8', true);?>
">
        <input type="hidden" name="allusers" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['allusers']->value, ENT_QUOTES, 'UTF-8', true);?>
">
        <input type="hidden" name="ofld" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ofld']->value, ENT_QUOTES, 'UTF-8', true);?>
">
        <input type="hidden" name="odir" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['odir']->value, ENT_QUOTES, 'UTF-8', true);?>
">

    <span style="border:0;font-weight:bold;font-size:12px;">
        <?php if (!empty($_smarty_tpl->tpl_vars['showDateRangeFilter']->value)) {?>
            <?php if (!empty($_smarty_tpl->tpl_vars['rangeDateFields']->value) && count($_smarty_tpl->tpl_vars['rangeDateFields']->value) > 1) {?>
            Aufträge zeitlich eingrenzen:<br>
            <select id="datumfeld" name="datumfeld">
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rangeDateFields']->value, 'opt');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['opt']->value) {
?>
                    <option value="<?php echo $_smarty_tpl->tpl_vars['opt']->value['value'];?>
" <?php if (!empty($_smarty_tpl->tpl_vars['opt']->value['checked'])) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['opt']->value['label'];?>
</option>
                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            </select>
            <?php } elseif (!empty($_smarty_tpl->tpl_vars['rangeDateFields']->value) && count($_smarty_tpl->tpl_vars['rangeDateFields']->value) > 0) {?>
                <?php echo $_smarty_tpl->tpl_vars['rangeDateFields']->value[0]['label'];?>
 zeitlich eingrenzen:<br>
                <input type="hidden" name="datumfeld" value="<?php echo $_smarty_tpl->tpl_vars['rangeDateFields']->value[0]['value'];?>
">
            <?php }?>
            Von: <input type="date" id="rangeDatumvon" name="datumvon" value="<?php if (!empty($_smarty_tpl->tpl_vars['rangeDatumvon']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['rangeDatumvon']->value, ENT_QUOTES, 'UTF-8', true);
}?>">
            Bis: <input type="date" id="rangeDatumbis" name="datumbis" value="<?php if (!empty($_smarty_tpl->tpl_vars['rangeDatumvon']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['rangeDatumbis']->value, ENT_QUOTES, 'UTF-8', true);
}?>">
            <button type="submit">Filter anwenden</button>
        <?php }?>
    </span>

  <ul class="ulLinkList"> 
  <div class="flds-head-colnames">
      <?php if ($_smarty_tpl->tpl_vars['showCheck']->value) {?>
        <div class="fld-cell fld-checkbox" style="width:<?php echo $_smarty_tpl->tpl_vars['checkWidth']->value;?>
px;"><input type="checkbox" id="toggleCheckboxes" name="aids_toggle" value="1"></div>
      <?php }?>
      <div class="fld-cell fld-aid order" data-fld="aid" style="width:<?php echo $_smarty_tpl->tpl_vars['aidWidth']->value;?>
px;" title="Auftrags-ID">ID</div>
      <div class="fld-cell fld-kid order" data-fld="kid" style="width:<?php echo $_smarty_tpl->tpl_vars['kidWidth']->value;?>
px;" title="Uniper MitarbeiterNr."><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=kid<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "kid" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">KID</a></div>
      <?php if ($_smarty_tpl->tpl_vars['showTour']->value) {?>
      <div class="fld-cell fld-tour_kennung order" data-fld="tour_kennung" style="width:<?php echo $_smarty_tpl->tpl_vars['tourWidth']->value;?>
px;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=tour_kennung<?php if ($_smarty_tpl->tpl_vars['ofld']->value == 'tour_kennung' && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Tour</a></div>
      <?php }?>
      <?php if ($_smarty_tpl->tpl_vars['showLieferdatum']->value) {?>
      <div class="fld-cell fld-lieferdatum order" data-fld="termin" style="width:<?php echo $_smarty_tpl->tpl_vars['lieferdatumWidth']->value;?>
px;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=termin<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "termin" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">LiefDat</a></div>
      <?php }?>
      <?php if ($_smarty_tpl->tpl_vars['showZeit']->value) {?>
      <div class="fld-cell fld-umzugszeit order" data-fld="umzugszeit" style="width:<?php echo $_smarty_tpl->tpl_vars['zeitWidth']->value;?>
px;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=umzugszeit<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "umzugszeit" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Zeit</a></div>
      <?php }?>
      <div class="fld-cell fld-land order" data-fld="land" style="width:<?php echo $_smarty_tpl->tpl_vars['landWidth']->value;?>
px;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=land<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "land" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Land</a></div>
      <div class="fld-cell fld-plz order" data-fld="plz" style="width:<?php echo $_smarty_tpl->tpl_vars['plzWidth']->value;?>
px;" title="Postleitzahl"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=plz<?php if ($_smarty_tpl->tpl_vars['ofld']->value == 'plz' && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">PLZ</a></div>
      <div class="fld-cell fld-ort order" data-fld="ort" style="width:<?php echo $_smarty_tpl->tpl_vars['ortWidth']->value;?>
px;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=ort<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "ort" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Lieferort</a></div>
      <div class="fld-cell fld-strasse order" data-fld="strasse" style="width:<?php echo $_smarty_tpl->tpl_vars['strasseWidth']->value;?>
px;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=strasse<?php if ($_smarty_tpl->tpl_vars['ofld']->value == 'strasse' && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Stra&szlig;e</a></div>
      <?php if ($_smarty_tpl->tpl_vars['showName']->value) {?>
      <div class="fld-cell fld-name order" data-fld="name" style="float:left;display:block;width:<?php echo $_smarty_tpl->tpl_vars['nameWidth']->value;?>
px;font-weight:bold;color:#00869c;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=name<?php if ($_smarty_tpl->tpl_vars['ofld']->value == 'name' && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Name</a></div>
      <?php }?>
      <div class="fld-cell fld-service order" data-fld="service" style="width:<?php echo $_smarty_tpl->tpl_vars['serviceWidth']->value;?>
px;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=service<?php if ($_smarty_tpl->tpl_vars['ofld']->value == 'service' && $_smarty_tpl->tpl_vars['odir']->value != 'DESC') {?>&odir=DESC<?php }?>">Service</a></div>
      <?php if ($_smarty_tpl->tpl_vars['showAuftragsdatum']->value) {?>
      <div class="fld-cell fld-beauftragt order" data-fld="antragsdatum" style="width:<?php echo $_smarty_tpl->tpl_vars['beauftragtWidth']->value;?>
px;" title="Auftragsdatum"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=antragsdatum<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "antragsdatum" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Auftr.Dat.</a></div>
      <?php }?>
      <?php if ($_smarty_tpl->tpl_vars['showErinnert']->value) {?>
      <div class="fld-cell fld-erinnert order" data-fld="temp_erinnerungsmail_am" style="width:<?php echo $_smarty_tpl->tpl_vars['erinnertWidth']->value;?>
px;" title="ErinnertAm"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=antragsdatum<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "temp_erinnerungsmail_am" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">ErinnertAm</a></div>
      <?php }?>
      <?php if ($_smarty_tpl->tpl_vars['showGenehmigt']->value) {?>
      <div class="fld-cell fld-genehmigt order" data-fld="genehmigt" style="float:left;width:<?php echo $_smarty_tpl->tpl_vars['genehmigtWidth']->value;?>
px;" title="Von Property genehmigt"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=genehmigt<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "genehmigt_br" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Gen</a></div>
      <?php }?>
      <?php if ($_smarty_tpl->tpl_vars['showBestaetigt']->value) {?>
      <div class="fld-cell fld-bestaetigt order" data-fld="bestaetigt" style="float:left;width:<?php echo $_smarty_tpl->tpl_vars['bestaetigtWidth']->value;?>
px;" title="Von merTens avisisiert"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=bestaetigt<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "bestaetigt" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Avi</a></div>
      <?php }?>
      <?php if ($_smarty_tpl->tpl_vars['showAbgeschlossen']->value) {?>
      <div class="fld-cell fld-abgeschlossen order" data-fld="abgeschlossen" style="width:<?php echo $_smarty_tpl->tpl_vars['abgeschlossenWidth']->value;?>
px;" title="Leistungen"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=abgeschlossen<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "abgeschlossen" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Abgeschl.</a></div>
      <?php }?>
      <?php if ($_smarty_tpl->tpl_vars['showLeistungen']->value) {?>
      <div class="fld-cell fld-leistungen order" data-fld="leistungen" style="width:<?php echo $_smarty_tpl->tpl_vars['leistungenWidth']->value;?>
px;"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=leistungen<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "leistungen" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Lstg.</a></div>
      <?php }?>
      <?php if ($_smarty_tpl->tpl_vars['showSumme']->value) {?>
      <div class="fld-cell fld-summe order" data-fld="summe" style="width:<?php echo $_smarty_tpl->tpl_vars['summeWidth']->value;?>
px;" title="Auftragswert"><a href="<?php echo $_smarty_tpl->tpl_vars['ListBaseLink']->value;?>
&ofld=summe<?php if ($_smarty_tpl->tpl_vars['ofld']->value == "summe" && $_smarty_tpl->tpl_vars['odir']->value != "DESC") {?>&odir=DESC<?php }?>">Summe</a></div>
      <?php }?>
      <br clear=left>
  </div>
      <div class="flds-head-colsearch">
          <?php if ($_smarty_tpl->tpl_vars['showCheck']->value) {?>
          <div class="fld-cell fld-checkbox" style="width:<?php echo $_smarty_tpl->tpl_vars['checkWidth']->value;?>
px;"></div>
          <?php }?>
          <div class="fld-cell fld-aid" style="width:<?php echo $_smarty_tpl->tpl_vars['aidWidth']->value;?>
px;"><input name="q[aid]" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['aid'])) {
echo $_smarty_tpl->tpl_vars['q']->value['aid'];
}?>"></div>
          <div class="fld-cell fld-kid" style="width:<?php echo $_smarty_tpl->tpl_vars['kidWidth']->value;?>
px;"><input name="q[kid]" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['kid'])) {
echo $_smarty_tpl->tpl_vars['q']->value['kid'];
}?>"></div>
          <?php if ($_smarty_tpl->tpl_vars['showTour']->value) {?>
          <div class="fld-cell fld-tour_kennung" style="width:<?php echo $_smarty_tpl->tpl_vars['tourWidth']->value;?>
px;"><input name="q[tour_kennung]" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['tour_kennung'])) {
echo $_smarty_tpl->tpl_vars['q']->value['tour_kennung'];
}?>"></div>
          <?php }?>
          <?php if ($_smarty_tpl->tpl_vars['showLieferdatum']->value) {?>
          <div class="fld-cell fld-lieferdatum" style="width:<?php echo $_smarty_tpl->tpl_vars['lieferdatumWidth']->value;?>
px;"><input name="q[termin]" placeholder="JJJJ-MM-TT" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['termin'])) {
echo $_smarty_tpl->tpl_vars['q']->value['termin'];
}?>"></div>
          <?php }?>
          <?php if ($_smarty_tpl->tpl_vars['showZeit']->value) {?>
          <div class="fld-cell fld-umzugszeit" style="width:<?php echo $_smarty_tpl->tpl_vars['zeitWidth']->value;?>
px;"><input name="q[umzugszeit]" placeholder="SS:MM" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['umzugszeit'])) {
echo $_smarty_tpl->tpl_vars['q']->value['umzugszeit'];
}?>"></div>
          <?php }?>
          <div class="fld-cell fld-land" style="width:<?php echo $_smarty_tpl->tpl_vars['landWidth']->value;?>
px;"><input name="q[land]" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['land'])) {
echo $_smarty_tpl->tpl_vars['q']->value['land'];
}?>"></div>
          <div class="fld-cell fld-plz" style="width:<?php echo $_smarty_tpl->tpl_vars['plzWidth']->value;?>
px;"><input name="q[plz]" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['plz'])) {
echo $_smarty_tpl->tpl_vars['q']->value['plz'];
}?>"></div>
          <div class="fld-cell fld-ort" style="width:<?php echo $_smarty_tpl->tpl_vars['ortWidth']->value;?>
px;"><input name="q[ort]" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['ort'])) {
echo $_smarty_tpl->tpl_vars['q']->value['ort'];
}?>"></div>
          <div class="fld-cell fld-strasse" style="width:<?php echo $_smarty_tpl->tpl_vars['strasseWidth']->value;?>
px;"><input name="q[strasse]" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['strasse'])) {
echo $_smarty_tpl->tpl_vars['q']->value['strasse'];
}?>"></div>
          <?php if ($_smarty_tpl->tpl_vars['showName']->value) {?>
          <div class="fld-cell fld-name" style="width:<?php echo $_smarty_tpl->tpl_vars['nameWidth']->value;?>
px;"><input name="q[name]" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['name'])) {
echo $_smarty_tpl->tpl_vars['q']->value['name'];
}?>"></div>
          <?php }?>
          <div class="fld-cell fld-service" style="width:<?php echo $_smarty_tpl->tpl_vars['serviceWidth']->value;?>
px;"><input name="q[service]" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['service'])) {
echo $_smarty_tpl->tpl_vars['q']->value['service'];
}?>"></div>
          <?php if ($_smarty_tpl->tpl_vars['showAuftragsdatum']->value) {?>
          <div class="fld-cell fld-beauftragt" style="width:<?php echo $_smarty_tpl->tpl_vars['beauftragtWidth']->value;?>
px;"><input name="q[antragsdatum]" placeholder="JJJJ-MM-TT" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['antragsdatum'])) {
echo $_smarty_tpl->tpl_vars['q']->value['antragsdatum'];
}?>"></div>
          <?php }?>
          <?php if ($_smarty_tpl->tpl_vars['showErinnert']->value) {?>
          <div class="fld-cell fld-erinnert" style="width:<?php echo $_smarty_tpl->tpl_vars['erinnertWidth']->value;?>
px;"><input name="q[temp_erinnerungsmail_am]" placeholder="JJJJ-MM-TT" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['temp_erinnerungsmail_am'])) {
echo $_smarty_tpl->tpl_vars['q']->value['temp_erinnerungsmail_am'];
}?>"></div>
          <?php }?>
          <?php if ($_smarty_tpl->tpl_vars['showGenehmigt']->value) {?>
          <div class="fld-cell fld-genehmigt" style="width:<?php echo $_smarty_tpl->tpl_vars['genehmigtWidth']->value;?>
px;"><input name="q[genehmigt_am]" placeholder="JJJJ-MM-TT" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['genehmigt_am'])) {
echo $_smarty_tpl->tpl_vars['q']->value['genehmigt_am'];
}?>"></div>
          <?php }?>
          <?php if ($_smarty_tpl->tpl_vars['showBestaetigt']->value) {?>
          <div class="fld-cell fld-bestaetigt" style="width:<?php echo $_smarty_tpl->tpl_vars['bestaetigtWidth']->value;?>
px;"><input name="q[bestaetigt_am]" placeholder="JJJJ-MM-TT" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['bestaetigt_am'])) {
echo $_smarty_tpl->tpl_vars['q']->value['bestaetigt_am'];
}?>"></div>
          <?php }?>
          <?php if ($_smarty_tpl->tpl_vars['showAbgeschlossen']->value) {?>
          <div class="fld-cell fld-abgeschlossen" style="width:<?php echo $_smarty_tpl->tpl_vars['abgeschlossenWidth']->value;?>
px;"><input name="q[abgeschlossen_am]" placeholder="JJJJ-MM-TT" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['abgeschlossen_am'])) {
echo $_smarty_tpl->tpl_vars['q']->value['abgeschlossen_am'];
}?>"></div>
          <?php }?>
          <?php if ($_smarty_tpl->tpl_vars['showLeistungen']->value) {?>
          <div class="fld-cell fld-leistungen" style="width:<?php echo $_smarty_tpl->tpl_vars['leistungenWidth']->value;?>
px;"><input name="q[Leistungen]" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['Leistungen'])) {
echo $_smarty_tpl->tpl_vars['q']->value['Leistungen'];
}?>"></div>
          <?php }?>
          <?php if ($_smarty_tpl->tpl_vars['showSumme']->value) {?>
          <div class="fld-cell fld-summe" style="width:<?php echo $_smarty_tpl->tpl_vars['summeWidth']->value;?>
px;"><input name="q[summe]" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['summe'])) {
echo $_smarty_tpl->tpl_vars['q']->value['summe'];
}?>"></div>
          <?php }?>
          <br clear=left>
      </div>
<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['Umzuege']->value, 'U');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['U']->value) {
?>
  <li class="flds-body-row-wrapper">
      <a class="flds-body-row-link iconRightContentMain">
          <div class="flds-body-row flds-body-row-link" data-href="<?php echo $_smarty_tpl->tpl_vars['U']->value['LinkOpen'];?>
">
              <?php if ($_smarty_tpl->tpl_vars['showCheck']->value) {?>
                <div class="fld-cell fld-checkbox" style="width:<?php echo $_smarty_tpl->tpl_vars['checkWidth']->value;?>
px;"><input type="checkbox" name="aids[]" value="<?php echo $_smarty_tpl->tpl_vars['U']->value['aid'];?>
">
                </div>
              <?php }?>
              <div class="fld-cell fld-aid" style="width:<?php echo $_smarty_tpl->tpl_vars['aidWidth']->value;?>
px;font-weight:bold;"><?php echo $_smarty_tpl->tpl_vars['U']->value['aid'];?>
&nbsp;</div>
              <div class="fld-cell fld-kid" style="width:<?php echo $_smarty_tpl->tpl_vars['kidWidth']->value;?>
px;" ><?php echo $_smarty_tpl->tpl_vars['U']->value['kid'];?>
&nbsp;</div>
              <?php if ($_smarty_tpl->tpl_vars['showTour']->value) {?>
              <div class="fld-cell fld-tour_kennung" style="width:<?php echo $_smarty_tpl->tpl_vars['tourWidth']->value;?>
px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['tour_kennung'];?>
&nbsp;</div>
              <?php }?>
              <?php if ($_smarty_tpl->tpl_vars['showLieferdatum']->value) {?>
              <div class="fld-cell fld-lieferdatum" style="width:<?php echo $_smarty_tpl->tpl_vars['lieferdatumWidth']->value;?>
px;font-weight:bold;"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['U']->value['Termin'],"%d.%m.%y");?>
&nbsp;</div>
              <?php }?>
              <?php if ($_smarty_tpl->tpl_vars['showZeit']->value) {?>
              <div class="fld-cell fld-umzugszeit" style="width:<?php echo $_smarty_tpl->tpl_vars['zeitWidth']->value;?>
px;"><?php echo substr($_smarty_tpl->tpl_vars['U']->value['umzugszeit'],0,5);?>
&nbsp;</div>
              <?php }?>
              <div class="fld-cell fld-land" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['U']->value['land'], ENT_QUOTES, 'UTF-8', true);?>
" style="width:<?php echo $_smarty_tpl->tpl_vars['landWidth']->value;?>
px;text-overflow: ellipsis"><?php if ($_smarty_tpl->tpl_vars['U']->value['land'] == "Deutschland") {?>DE<?php } elseif ($_smarty_tpl->tpl_vars['U']->value['land'] == "England") {?>EN<?php } elseif ($_smarty_tpl->tpl_vars['U']->value['land'] == "Niederlande") {?>NL<?php } else {
echo $_smarty_tpl->tpl_vars['U']->value['land'];
}?> &nbsp;</div>
              <div class="fld-cell fld-plz" style="width:<?php echo $_smarty_tpl->tpl_vars['plzWidth']->value;?>
px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['plz'];?>
&nbsp;</div>
              <div class="fld-cell fld-ort" style="width:<?php echo $_smarty_tpl->tpl_vars['ortWidth']->value;?>
px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['ort'];?>
&nbsp;</div>
              <div class="fld-cell fld-strasse" style="width:<?php echo $_smarty_tpl->tpl_vars['strasseWidth']->value;?>
px;">
                  <span class="geo-address"
                        data-address="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['U']->value['strasse'], ENT_QUOTES, 'UTF-8', true);?>
,<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['U']->value['plz'], ENT_QUOTES, 'UTF-8', true);?>
+<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['U']->value['ort'], ENT_QUOTES, 'UTF-8', true);?>
,<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['U']->value['land'], ENT_QUOTES, 'UTF-8', true);?>
" style="color:red;">
                <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['U']->value['strasse'], ENT_QUOTES, 'UTF-8', true);?>
</span>
              </div>

              <?php if ($_smarty_tpl->tpl_vars['showName']->value) {?>
              <div class="fld-cell fld-service" style="width:<?php echo $_smarty_tpl->tpl_vars['nameWidth']->value;?>
px;" ><?php echo $_smarty_tpl->tpl_vars['U']->value['name'];?>
&nbsp&nbsp;</div>
              <?php }?>
              <div class="fld-cell fld-service" style="width:<?php echo $_smarty_tpl->tpl_vars['serviceWidth']->value;?>
px;" ><?php echo $_smarty_tpl->tpl_vars['U']->value['service'];?>
&nbsp&nbsp;</div>
              <?php if ($_smarty_tpl->tpl_vars['showAuftragsdatum']->value) {?>
              <div class="fld-cell fld-beauftragt" style="width:<?php echo $_smarty_tpl->tpl_vars['beauftragtWidth']->value;?>
px;font-style:italic;" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['U']->value['Antragsdatum'], ENT_QUOTES, 'UTF-8', true);?>
"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['U']->value['Antragsdatum'],"%d.%m.%y");?>
&nbsp;</div>
              <?php }?>
              <?php if ($_smarty_tpl->tpl_vars['showErinnert']->value) {?>
              <div class="fld-cell fld-erinnert" style="width:<?php echo $_smarty_tpl->tpl_vars['erinnertWidth']->value;?>
px;font-style:italic;" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['U']->value['temp_erinnerungsmail_am'], ENT_QUOTES, 'UTF-8', true);?>
"><?php if (!empty($_smarty_tpl->tpl_vars['U']->value['temp_erinnerungsmail_am'])) {
echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['U']->value['temp_erinnerungsmail_am'],"%d.%m.%y");
}?>&nbsp;</div>
              <?php }?>
              <?php if ($_smarty_tpl->tpl_vars['showGenehmigt']->value) {?>
              <div class="fld-cell fld-genehmigt" style="width:<?php echo $_smarty_tpl->tpl_vars['genehmigtWidth']->value;?>
px;"><?php echo $_smarty_tpl->tpl_vars['U']->value['Genehmigt'];?>
&nbsp;</div>
              <?php }?>
              <?php if ($_smarty_tpl->tpl_vars['showBestaetigt']->value) {?>
              <div class="fld-cell fld-bestaetigt" style=";width:<?php echo $_smarty_tpl->tpl_vars['bestaetigtWidth']->value;?>
px;" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['U']->value['Bestaetigt'], ENT_QUOTES, 'UTF-8', true);?>
"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['U']->value['Bestaetigt'],"%d.%m.%y");?>
&nbsp;</div>
              <?php }?>
              <?php if ($_smarty_tpl->tpl_vars['showAbgeschlossen']->value) {?>
              <div class="fld-cell fld-abgeschlossen" style="width:<?php echo $_smarty_tpl->tpl_vars['abgeschlossenWidth']->value;?>
px;" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['U']->value['abgeschlossen_am'], ENT_QUOTES, 'UTF-8', true);?>
"><?php echo $_smarty_tpl->tpl_vars['U']->value['Abgeschlossen'];?>
 <?php if ($_smarty_tpl->tpl_vars['U']->value['abgeschlossen_am']) {
echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['U']->value['abgeschlossen_am'],"%d.%m.%y");
}?>&nbsp;</div>
              <?php }?>
              <?php if ($_smarty_tpl->tpl_vars['showLeistungen']->value) {?>
              <div class="fld-cell fld-leistungen" style="width:<?php echo $_smarty_tpl->tpl_vars['leistungenWidth']->value;?>
px;" title="<?php echo $_smarty_tpl->tpl_vars['U']->value['LeistungenFull'];?>
"><?php echo $_smarty_tpl->tpl_vars['U']->value['Leistungen'];?>
&nbsp;</div>
              <?php }?>
              <?php if ($_smarty_tpl->tpl_vars['showSumme']->value) {?>
              <div class="fld-cell fld-summe" style="width:<?php echo $_smarty_tpl->tpl_vars['summeWidth']->value;?>
px;"><?php echo number_format($_smarty_tpl->tpl_vars['U']->value['Summe'],2,",",".");?>
&nbsp;</div>
              <?php }?>
              <br clear=left>
          </div>
      </a>
  </li>
      <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
</ul>
        <?php if (!empty($_smarty_tpl->tpl_vars['selectable']->value) && !empty($_smarty_tpl->tpl_vars['selectableActionTemplate']->value)) {?>
            <?php $_smarty_tpl->_subTemplateRender($_smarty_tpl->tpl_vars['selectableActionTemplate']->value, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>
        <?php }?>
    </form>
<h2 style="margin:1rem 0 0 0;padding-bottom:0;">Enthaltene Artikel</h2>
    <table class="tblList" width="100%">
        <thead>
            <tr>
                <th>Kategorie</th>
                <th>Artikel</th>
                <th>Farbe</th>
                <th>Größe</th>
                <th class="menge fld-cell-amount">Preis</th>
                <th class="menge fld-cell-amount">Menge</th>
                <th class="menge fld-cell-amount">Summe</th>
            </tr>

        </thead>
        <tbody>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['artikelStat']->value, 'A');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['A']->value) {
?>
    <tr>
        <td><?php echo $_smarty_tpl->tpl_vars['A']->value['Kategorie'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['A']->value['Bezeichnung'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['A']->value['Farbe'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['A']->value['Groesse'];?>
</td>
        <td class="fld-cell-amount"><?php echo $_smarty_tpl->tpl_vars['A']->value['Preis'];?>
</td>
        <td class="fld-cell-amount" style="text-align:right"><?php echo $_smarty_tpl->tpl_vars['A']->value['count'];?>
</td>
        <td class="menge fld-summe currency-euro"><?php echo number_format($_smarty_tpl->tpl_vars['A']->value['Summe'],2,",",".");?>
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
<?php echo '<script'; ?>
>
    

    $(function() {
        var ofld = "{$ofld}";
        var odir = "{$odir}";

        $("#toggleCheckboxes").bind("change", function(e) {
            $(":input[type=checkbox][name=aids\\[\\]]").prop("checked", this.checked );
            return false;
        });

        $("input[type=checkbox][name^=aids\\[\\]]").bind("click", function(e) {
            e.stopPropagation();
        });

        $(".flds-body-row-link[data-href]").bind("click", function(e) {
            self.location.href= $(this).data("href");
        });

        var send = function(addQuery = '') {
            var url = "?" + $("#frmList :input")
                .filter(function(index, element) {
                    return $.trim($(element).val()) !== '';
                })
                .serialize();

            if (addQuery && addQuery.charAt(0) !== '&') {
                addQuery = '&' + addQuery;
            }
            self.location.href = url + addQuery;
        };

        $(".ulLinkList .flds-head-colnames .order").click(function(e){
            e.preventDefault();
            var fld = $(this).attr("data-fld");
            $("input[name=ofld]").val( fld );
            $("input[name=odir]").val( fld === ofld && odir === 'ASC' ? 'DESC' : 'ASC' );
            send();
            return false;
        });

        $(".ulLinkList .flds-head-colsearch input").keypress(function(e){
            if ( (e.keyCode || e.which) === 13) send();
        });

        $(".ulLinkList .flds-head-colsearch input").bind('change', function(e){
            send();
        });

        $("#btnCsvExport").bind("click", function() {
            send('format=csv');
        });

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
