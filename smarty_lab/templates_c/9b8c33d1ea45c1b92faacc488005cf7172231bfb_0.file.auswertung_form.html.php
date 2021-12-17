<?php
/* Smarty version 3.1.34-dev-7, created on 2021-12-17 15:19:51
  from '/var/www/html/html/auswertung_form.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_61bc9c872b0818_87361478',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9b8c33d1ea45c1b92faacc488005cf7172231bfb' => 
    array (
      0 => '/var/www/html/html/auswertung_form.html',
      1 => 1639750784,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:admin_auswertung_tabs.html' => 1,
  ),
),false)) {
function content_61bc9c872b0818_87361478 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/smarty3/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>

<div style="display: flex;justify-content: space-between">
    <div><?php $_smarty_tpl->_subTemplateRender("file:admin_auswertung_tabs.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('cat'=>"abrechnung",'allusers'=>1,'s'=>"aantraege"), 0, false);
?></div>
    <div style="align-self: flex-end;margin-right:5px;margin-bottom:2px;">
        <button id="btnPdfReport" data-href="{WebRoot}sites/admin_rechnungsreport.php" class="btn btn-blue" style="padding:10px;cursor: pointer;">Rechnungs-Report</button>
    </div>
</div>

<div class="divModuleBasic padding6px width5Col heightAuto colorContentMain">
    <h1><span class="spanTitle">Abrechnung abgeschlossener Aufträge</span></h1>

    <div id="Auswertung" class="divInlay">
<form id="frmStat" name="frmStat" method="get" action="?" data-site="auswertung/form/html">
    <span style="border:0;font-weight:bold;font-size:12px;">
    Zeitraum in dem die Aufträge im ORS abgeschlossen wurden:<br>
    <select style="display: none;" readonly id="auswertungDatumsfeld" name="datumfeld">
        <option value="umzugstermin">Lieferdatum</option>
        <option value="antragsdatum">Auftragsdatum</option>
        <option value="abgeschlossen_am">Abschlussdatum</option>
        <!-- option value="bestaetigt_am">Bestätigungsdatum</option -->
        <option value="berechnet_am">Rechnungsdatum</option>
    </select>
        <input type="hidden" name="datumfeld" value="abgeschlossen_am">
    Von: <input type="date" name="datumvon" value="<?php echo $_smarty_tpl->tpl_vars['datumvon']->value;?>
" xonchange="document.forms['frmStat'].submit()">
    Bis: <input type="date" name="datumbis" value="<?php echo $_smarty_tpl->tpl_vars['datumbis']->value;?>
" xonchange="document.forms['frmStat'].submit()">
    <input style="margin-left:15px;" id="all" name="all" type="checkbox" value="1" <?php if ($_smarty_tpl->tpl_vars['all']->value) {?>checked="checked"<?php }?>><label for="all"><span title="Auch bereits abgerechnete Leistungen anzeigen">Alle (auch abgerechnet)</span></label>
<input type="hidden" name="s" value="<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
">
<input type="hidden" name="order" value="" id="orderby">
<input type="hidden" name="queriedorder" value="<?php echo $_smarty_tpl->tpl_vars['order']->value;?>
">
<input type="hidden" name="queriedodir" value="<?php echo $_smarty_tpl->tpl_vars['odir']->value;?>
"><br>
<button type="submit" class="btn btn-apply">Auswertung starten</button>
<noscript>&lt;input type="submit" value="Auswertung starten"&gt;</noscript>
</span>
<style type="text/css">
    th.order {
        cursor:pointer;
    }
</style>
<?php echo '<script'; ?>
>
    var datumFilterFeld = "<?php echo $_smarty_tpl->tpl_vars['datumfeld']->value;?>
";
<?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>

$(function(){

    $("#auswertungDatumsfeld").val(datumFilterFeld);

    var pdfReportLink = "{WebRoot}sites/admin_rechnungsreport.php";
    
    var send = function(addQuery = '') {
        var url = "?" + $("#frmStat :input")
            .filter(function(index, element) {
                if (['radio', 'checkbox'].indexOf(element.type) !== -1) {
                    return element.checked;
                }
                return $.trim($(element).val()) !== '';
            })
            .serialize();

        if (addQuery && addQuery.charAt(0) !== '&') {
            addQuery = '&' + addQuery;
        }
        // alert('url: ' + url + "\naddQuery: " + addQuery);
        self.location.href = url + addQuery;
    };


    var openPdfReport = function(addQuery = '') {
        var url = pdfReportLink + "?" + $("#frmStat :input")
            .filter(function(index, element) {
                if (['radio', 'checkbox'].indexOf(element.type) !== -1) {
                    return element.checked;
                }
                return $.trim($(element).val()) !== '';
            })
            .serialize();

        if (addQuery && addQuery.charAt(0) !== '&') {
            addQuery = '&' + addQuery;
        }

        window.open(url + addQuery, 'pdfreport');
        // alert('url: ' + url + "\naddQuery: " + addQuery);
        // self.location.href = url + addQuery;
    };
    
    $("th.order").click(function(e){        
        $("input#orderby").val( $(this).attr("data-fld") );
        send();
    });
    
    $("th input").keypress(function(e){
        if ( (e.keyCode || e.which) === 13) send();
    });


    $("#btnCsvExport").bind("click", function() {
        send('format=csv');
    });

    $("#btnPdfReport").bind("click", function() {
        openPdfReport();
    });
    
});

<?php echo '</script'; ?>
>

<table class="tblList">
    <thead>
        <tr>
            <th style="cursor:pointer" 
                onclick="(function(){
                    $('input:checkbox[name^=aids]').attr('checked', !$('input:checkbox[name^=aids]:first').is(':checked') );
            })()">x</th>
            <th class="order" data-fld="aid">ID</th>
            <th class="order" data-fld="kid">KID</th>
            <th class="order" data-fld="land">Land</th>
            <th class="order" data-fld="ort">Lieferort</th>
            <th class="order" data-fld="PLZ">PLZ</th>
            <th class="order" data-fld="strasse">Stra&szlig;e</th>
            <th class="order" data-fld="service">Service</th>
            <th class="order" data-fld="antragsdatum">Auftr.Dat.</th>
            <th class="order" data-fld="umzugstermin">Geliefert</th>
            <th class="order" data-fld="abgeschlossen_am">Abschluss</th>
            <th class="order" data-fld="summe">Summe</th>
        </tr>
        <tr>
            <th></th>
            <th><input name="q[aid]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['aid'])) {
echo $_smarty_tpl->tpl_vars['q']->value['aid'];
}?>"></th>
            <th><input name="q[kid]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['kid'])) {
echo $_smarty_tpl->tpl_vars['q']->value['kid'];
}?>"></th>
            <th><input name="q[land]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['land'])) {
echo $_smarty_tpl->tpl_vars['q']->value['land'];
}?>"></th>
            <th><input name="q[ort]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['ort'])) {
echo $_smarty_tpl->tpl_vars['q']->value['ort'];
}?>"></th>
            <th><input name="q[plz]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['plz'])) {
echo $_smarty_tpl->tpl_vars['q']->value['plz'];
}?>"></th>
            <th><input name="q[strasse]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['strasse'])) {
echo $_smarty_tpl->tpl_vars['q']->value['strasse'];
}?>"></th>
            <th><input name="q[service]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['service'])) {
echo $_smarty_tpl->tpl_vars['q']->value['service'];
}?>"></th>
            <th><input name="q[antragsdatum]" placeholder="JJJJ-MM-TT" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['antragsdatum'])) {
echo $_smarty_tpl->tpl_vars['q']->value['antragsdatum'];
}?>"></th>
            <th><input name="q[umzugstermin]" placeholder="JJJJ-MM-TT" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['umzugstermin'])) {
echo $_smarty_tpl->tpl_vars['q']->value['umzugstermin'];
}?>"></th>
            <th><input name="q[abgeschlossen_am]" placeholder="JJJJ-MM-TT" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['abgeschlossen_am'])) {
echo $_smarty_tpl->tpl_vars['q']->value['abgeschlossen_am'];
}?>"></th>
            <th><input name="q[summe]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['summe'])) {
echo $_smarty_tpl->tpl_vars['q']->value['summe'];
}?>"></th>
        </tr>
    </thead>
    </thead>
    <tbody>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['Auftraege']->value, 'item', false, NULL, 'AList', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
?>
        <tr>
            <?php if (!empty($_smarty_tpl->tpl_vars['aAids']->value) && in_array($_smarty_tpl->tpl_vars['item']->value['aid'],$_smarty_tpl->tpl_vars['aAids']->value)) {?>
            <?php $_smarty_tpl->_assignInScope('checked', "checked=\"checked\"");?>
            <?php } else { ?>
            <?php $_smarty_tpl->_assignInScope('checked', '');?>
            <?php }?>
            <td><?php if (!$_smarty_tpl->tpl_vars['item']->value['berechnet_am']) {?><input type="checkbox" name="aids[]" <?php echo $_smarty_tpl->tpl_vars['checked']->value;?>
 value="<?php echo $_smarty_tpl->tpl_vars['item']->value['aid'];?>
"><?php }?></td>
            <td><a href="?s=<?php echo $_smarty_tpl->tpl_vars['site_antrag']->value;?>
&id=<?php echo $_smarty_tpl->tpl_vars['item']->value['aid'];?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value['aid'];?>
</a></td>
            <td><?php echo $_smarty_tpl->tpl_vars['item']->value['kid'];?>
</td>
            <td><?php if ($_smarty_tpl->tpl_vars['item']->value['land'] == "Deutschland") {?>DE<?php } elseif ($_smarty_tpl->tpl_vars['item']->value['land'] == "Niederlande") {?>NL<?php } else {
echo $_smarty_tpl->tpl_vars['item']->value['land'];
}?></td>
            <td><?php echo $_smarty_tpl->tpl_vars['item']->value['ort'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['item']->value['plz'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['item']->value['strasse'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['item']->value['service'];?>
</td>
            <td title="<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['antragsdatum'],"%d.%m.%Y %H:%M");?>
">
                <?php ob_start();
echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['antragsdatum'],"%Y");
$_prefixVariable1 = ob_get_clean();
ob_start();
echo date('Y');
$_prefixVariable2 = ob_get_clean();
if (!empty($_smarty_tpl->tpl_vars['item']->value['antragsdatum']) && $_prefixVariable1 == $_prefixVariable2) {?>
                    <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['antragsdatum'],"%d.%m.");?>

                <?php } else { ?>
                    <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['antragsdatum'],"%d.%m.%y");?>

                <?php }?>
            </td>

            <td title="<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['umzugstermin'],"%d.%m.%Y");?>
">
            <?php ob_start();
echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['umzugstermin'],"%Y");
$_prefixVariable3 = ob_get_clean();
ob_start();
echo date('Y');
$_prefixVariable4 = ob_get_clean();
if (!empty($_smarty_tpl->tpl_vars['item']->value['umzugstermin']) && $_prefixVariable3 == $_prefixVariable4) {?>
                <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['umzugstermin'],"%d.%m.");?>

            <?php } else { ?>
                <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['umzugstermin'],"%d.%m.%y");?>

            <?php }?>
            </td>

            <td title="<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['abgeschlossen_am'],"%d.%m.%Y %H:%M");?>
">
            <?php ob_start();
echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['abgeschlossen_am'],"%Y");
$_prefixVariable5 = ob_get_clean();
ob_start();
echo date('Y');
$_prefixVariable6 = ob_get_clean();
if (!empty($_smarty_tpl->tpl_vars['item']->value['abgeschlossen_am']) && $_prefixVariable5 == $_prefixVariable6) {?>
            <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['abgeschlossen_am'],"%d.%m.");?>

            <?php } else { ?>
            <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['abgeschlossen_am'],"%d.%m.%y");?>

            <?php }?>
            </td>

            <td style="text-align:right;"><?php echo number_format($_smarty_tpl->tpl_vars['item']->value['summe'],2,",",".");?>
 &euro;</td>
        </tr>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

    <?php if (!empty($_smarty_tpl->tpl_vars['TeilLieferungen']->value) && count($_smarty_tpl->tpl_vars['TeilLieferungen']->value) > 0) {?>
    <tr>
        <th colspan="12">Teil-Lieferungen (Ohne Spaltenfilter)</th>
    </tr>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['TeilLieferungen']->value, 'item', false, NULL, 'AList', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
?>
    <tr>
        <?php if (!empty($_smarty_tpl->tpl_vars['aUlids']->value) && in_array($_smarty_tpl->tpl_vars['item']->value['ulid'],$_smarty_tpl->tpl_vars['aUlids']->value)) {?>
            <?php $_smarty_tpl->_assignInScope('checked', "checked=\"checked\"");?>
        <?php } else { ?>
            <?php $_smarty_tpl->_assignInScope('checked', '');?>
        <?php }?>
        <td><?php if (!$_smarty_tpl->tpl_vars['item']->value['berechnet_am']) {?><input type="checkbox" name="ulids[]" <?php echo $_smarty_tpl->tpl_vars['checked']->value;?>
 value="<?php if (!empty($_smarty_tpl->tpl_vars['item']->value['ulid'])) {
echo $_smarty_tpl->tpl_vars['item']->value['ulid'];
}?>"><?php }?></td>
        <td><a href="?s=<?php echo $_smarty_tpl->tpl_vars['site_antrag']->value;?>
&id=<?php echo $_smarty_tpl->tpl_vars['item']->value['aid'];?>
">TL-<?php if (!empty($_smarty_tpl->tpl_vars['item']->value['ulid'])) {
echo $_smarty_tpl->tpl_vars['item']->value['ulid'];
}?></a></td>
        <td><?php echo $_smarty_tpl->tpl_vars['item']->value['kid'];?>
</td>
        <td><?php if ($_smarty_tpl->tpl_vars['item']->value['land'] == "Deutschland") {?>DE<?php } elseif ($_smarty_tpl->tpl_vars['item']->value['land'] == "Niederlande") {?>NL<?php } else {
echo $_smarty_tpl->tpl_vars['item']->value['land'];
}?></td>
        <td><?php echo $_smarty_tpl->tpl_vars['item']->value['ort'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['item']->value['plz'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['item']->value['strasse'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['item']->value['service'];?>
</td>
        <td title="<?php if (!empty($_smarty_tpl->tpl_vars['item']->value['antragsdatum'])) {
echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['antragsdatum'],"%d.%m.%Y %H:%M");
}?>">
        <?php ob_start();
echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['antragsdatum'],"%Y");
$_prefixVariable7 = ob_get_clean();
ob_start();
echo date('Y');
$_prefixVariable8 = ob_get_clean();
if (!empty($_smarty_tpl->tpl_vars['item']->value['antragsdatum']) && $_prefixVariable7 == $_prefixVariable8) {?>
        <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['antragsdatum'],"%d.%m.");?>

        <?php } else { ?>
        <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['antragsdatum'],"%d.%m.%y");?>

        <?php }?>
        </td>

        <td title="<?php if (!empty($_smarty_tpl->tpl_vars['item']->value['lieferdatum'])) {
echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['lieferdatum'],"%d.%m.%Y");
}?>">
        <?php ob_start();
echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['lieferdatum'],"%Y");
$_prefixVariable9 = ob_get_clean();
ob_start();
echo date('Y');
$_prefixVariable10 = ob_get_clean();
if (!empty($_smarty_tpl->tpl_vars['item']->value['lieferdatum']) && $_prefixVariable9 == $_prefixVariable10) {?>
            <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['lieferdatum'],"%d.%m.");?>

        <?php } elseif (!empty($_smarty_tpl->tpl_vars['item']->value['lieferdatum'])) {?>
            <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['lieferdatum'],"%d.%m.%y");?>

        <?php }?>
        </td>

        <td title="">
        </td>

        <td style="text-align:right;"><?php echo number_format($_smarty_tpl->tpl_vars['item']->value['summe'],2,",",".");?>
 &euro;</td>
    </tr>
    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    <?php }?>
    </tbody>
</table>
    <?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && !empty($_smarty_tpl->tpl_vars['q']->value['aids'])) {?>
    <div style="margin-top:.5rem">
        <label style="font-weight: bold;">Auftrag-Ids</label>
        <textarea placeholder="Auftrags-Ids" name="q[aids]" id="q_aids" style="width:100%;resize:vertical;overflow:auto;border:1px solid #888888; border-radius:5px;padding:5px;box-sizing: border-box;"><?php if (!empty($_smarty_tpl->tpl_vars['q']->value['aids'])) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['q']->value['aids'], ENT_QUOTES, 'UTF-8', true);
}?></textarea>
    </div>
    <?php }?>

    <?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && !empty($_smarty_tpl->tpl_vars['q']->value['ulids'])) {?>
    <div style="margin-top:.5rem">
        <label style="font-weight: bold;">Teil-Lieferungs-Ids</label>
        <textarea placeholder="Teil-Lieferungs-Ids" name="q[ulids]" style="width:100%;resize:vertical;overflow:auto;border:1px solid #888888; border-radius:5px;padding:5px;box-sizing: border-box;"><?php if (!empty($_smarty_tpl->tpl_vars['q']->value['ulids'])) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['q']->value['ulids'], ENT_QUOTES, 'UTF-8', true);
}?></textarea>
    </div>
    <?php }?>
    <div style="margin-top:.5rem">
        <label style="font-weight: bold;">Rechnungsnr zuweisen</label><br>
    <input type="text" name="wwsnr" placeholder="Rechnungsnr" size="15" style="border:1px solid #888888;width:180px;font-size:11px;border-radius:5px;padding:5px;" value="<?php echo $_smarty_tpl->tpl_vars['wwsnr']->value;?>
">
    </div>
    <input type="submit" name="finish" value="Rechnungsnummer jetzt zuweisen"
       style="margin-top:0.5rem;cursor:pointer;border:1px solid #41b3f3;background-color:#41b3f3;color:#fff;font-weight:bold;border-radius:5px;padding:5px;">
</form>
    </div>
</div>
<?php }
}
