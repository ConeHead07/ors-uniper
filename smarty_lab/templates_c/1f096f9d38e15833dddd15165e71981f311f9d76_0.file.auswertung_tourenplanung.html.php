<?php
/* Smarty version 3.1.34-dev-7, created on 2021-11-19 09:45:04
  from '/var/www/html/html/auswertung_tourenplanung.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_61976410928ce1_56455852',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1f096f9d38e15833dddd15165e71981f311f9d76' => 
    array (
      0 => '/var/www/html/html/auswertung_tourenplanung.html',
      1 => 1637311497,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:admin_antraege_tabs.html' => 1,
  ),
),false)) {
function content_61976410928ce1_56455852 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/smarty3/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<pre style="display: none;">
html/auswertung_form.html
Rechnungsstellung nur Mertens-Henk:
 
Es wird eine Funktion zur Abrechnung der Vorgänge benötigt.
Hierzu soll eine Auswahl von KW bis KW  und das Jahr angegeben werden.
Daraus soll eine Liste mit allen Vorgängen die Abgeschlossen worden sind
und noch nicht abgerechnet worden sind mit
Folgenden Feldern ausgegeben werden:
 
ID|STOM|Region|Standort|Wirtschaftseinheit|PSP-Element|Planon Nr.|Leistungsdatum|AbschlussDatum|Summe
 
Unter dieser Tabelle muss es ein Feld geben wo wir unsere WWS Vorgangs Nr. 
eintragen können und dann ein Fertigsstellungsbutton.
Beim Bestätigen müssen der Status alle angezeigten Vorgänge auf Berechnet
gesetzt werden und das Rechnungsdatum des aktuellen Tages eingetragen werden.
</pre>

<div style="display: flex;justify-content: space-between">
    <div><?php $_smarty_tpl->_subTemplateRender("file:admin_antraege_tabs.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('cat'=>"tourenplanung",'allusers'=>1,'s'=>"aantraege"), 0, false);
?></div>
    <div style="align-self: flex-end;margin-right:5px;margin-bottom:2px;">
        <button id="btnCsvExport" class="btn btn-blue" style="padding:10px;cursor: pointer;">CSV-Export</button>
    </div>
</div>

<?php if ($_smarty_tpl->tpl_vars['error']->value != '') {?>
    <div style="border:2px solid red; border-radius:5px;background-color:white;padding:1rem;">
        <?php echo $_smarty_tpl->tpl_vars['error']->value;?>

    </div>
<?php }?>
<div class="divModuleBasic padding6px width5Col heightAuto colorContentMain">
    <h1><span class="spanTitle">Zuordnung von Aufträgen zu Tourenplänen</span></h1>

    <div id="Auswertung" class="divInlay">
    <form id="frmStat" name="frmStat" method="get" action="?" data-site="auswertung/form/html">
        <div id="frmFilterBox" style="margin-bottom:1rem; border:1px solid #E0E0E0;padding:1rem;border-radius:8px;">
            <span style="border:0;font-weight:bold;font-size:12px;">
                Zeitraum
                <select id="auswertungDatumsfeld" name="datumfeld" style="margin-right:1rem;">
                    <option value="umzugstermin">Lieferdatum</option>
                    <option value="antragsdatum">Auftragsdatum</option>
                    <option value="tour_disponiert_am">Disponiert am</option>
                    <option value="abgeschlossen_am">Abschlussdatum</option>
                    <option value="bestaetigt_am">Bestätigungsdatum</option>
                    <option value="berechnet_am">Rechnungsdatum</option>
                </select>
                Von: <input size="10" type="date" name="datumvon" value="<?php echo $_smarty_tpl->tpl_vars['datumvon']->value;?>
" xonchange="document.forms['frmStat'].submit()" style="margin-right:1rem;border-bottom: 1px solid #a4cbe0;">
                Bis: <input size="10" type="date" name="datumbis" value="<?php echo $_smarty_tpl->tpl_vars['datumbis']->value;?>
" xonchange="document.forms['frmStat'].submit()" style="margin-right:1rem;border-bottom: 1px solid #a4cbe0;">
                                <input style="margin-left:15px;" id="all" name="all" type="checkbox" value="1" <?php if ($_smarty_tpl->tpl_vars['all']->value) {?>checked="checked"<?php }?>><label for="all"> <span title="Auch bereits disponierte Leistungen anzeigen">Auch bereits disponierte Aufträge</span></label>


                <div style="margin-top:5px;margin-bottom:5px;">
                    <label style="margin-right:2rem"><input type="checkbox" name="auftragsstatus[]" id="filter_beauftragt" value="beauftragt"> Beauftragt</label>
                    <label style="margin-right:2rem"><input type="checkbox" name="auftragsstatus[]" id="filter_avisiert" value="avisiert"> Avisiert</label>
                    <label style="margin-right:2rem"><input type="checkbox" name="auftragsstatus[]" id="filter_disponiert" value="disponiert"> Disponiert</label>
                    <label style="margin-right:2rem"><input type="checkbox" name="auftragsstatus[]" id="filter_abgeschlossen" value="abgeschlossen"> Abgeschlossen</label>
                </div>

                <input type="hidden" name="s" value="<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
">
                <input type="hidden" name="order" value="" id="orderby">
                <input type="hidden" name="queriedorder" value="<?php echo $_smarty_tpl->tpl_vars['order']->value;?>
">
                <input type="hidden" name="queriedodir" value="<?php echo $_smarty_tpl->tpl_vars['odir']->value;?>
">
                <button type="submit" class="btn btn-apply">Auswertung starten</button>
            </span>
        </div>
        <noscript>&lt;input type="submit" value="Auswertung starten"&gt;</noscript>

        <style type="text/css">
            th.order {
                cursor:pointer;
            }
        </style>

<table id="tblTourenplanung" class="tblList">
    <thead>
        <tr>
            <th style="cursor:pointer" 
                onclick="(function(){
                    $('input:checkbox[name^=aids]').attr('checked', !$('input:checkbox[name^=aids]:first').is(':checked') );
            })()">x</th>
            <th class="order" data-fld="aid">ID</th>
            <th class="order" data-fld="kid">KID</th>
            <th class="order" data-fld="land" title="Land">L.</th>
            <th class="order" data-fld="ort">Lieferort</th>
            <th class="order" data-fld="plz">PLZ</th>
            <th class="order" data-fld="strasse">Stra&szlig;e</th>
            <th class="order" data-fld="service">Service</th>
            <th class="order" data-fld="antragsdatum">Auftr.Dat.</th>
            <th class="order" data-fld="umzugstermin">Lieferdatum</th>
            <th class="order" data-fld="tour_kennung">Tour</th>
            <th class="order" data-fld="Leistungen">Lstg.</th>
            <th class="order" data-fld="summe">Summe</th>
        </tr>
        <tr class="flds-head-colsearch">
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
            <th><input name="q[plz]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['plzd'])) {
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
            <th><input name="q[tour_kennung]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['tour_kennung'])) {
echo $_smarty_tpl->tpl_vars['q']->value['tour_kennung'];
}?>"></th>
            <th><input name="q[Leistungen]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['Leistungen'])) {
echo $_smarty_tpl->tpl_vars['q']->value['Leistungen'];
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
        <tr class="data-href" data-href="?s=<?php echo $_smarty_tpl->tpl_vars['site_antrag']->value;?>
&id=<?php echo $_smarty_tpl->tpl_vars['item']->value['aid'];?>
">
            <td><input type="checkbox" name="aids[]" value="<?php echo $_smarty_tpl->tpl_vars['item']->value['aid'];?>
"<?php if (!empty($_smarty_tpl->tpl_vars['aids']->value) && in_array($_smarty_tpl->tpl_vars['item']->value['aid'],$_smarty_tpl->tpl_vars['aids']->value)) {?> checked="checked"<?php }?>></td>
            <td><?php echo $_smarty_tpl->tpl_vars['item']->value['aid'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['item']->value['kid'];?>
</td>
            <td><?php if ($_smarty_tpl->tpl_vars['item']->value['land'] == "Deutschland") {?>D<?php } elseif ($_smarty_tpl->tpl_vars['item']->value['land'] == "Niederlande") {?>NL<?php } else {
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
"><?php ob_start();
echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['antragsdatum'],"%Y");
$_prefixVariable1 = ob_get_clean();
ob_start();
echo date('Y');
$_prefixVariable2 = ob_get_clean();
if (!empty($_smarty_tpl->tpl_vars['item']->value['antragsdatum']) && $_prefixVariable1 == $_prefixVariable2) {?>
                    <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['antragsdatum'],"%d.%m.");?>

                <?php } else { ?>
                    <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['antragsdatum'],"%d.%m.'%y");?>

                <?php }?>
            </td>
            <td title="<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['umzugstermin'],"%d.%m.%Y");?>
"><?php ob_start();
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
            <td <?php if (!empty($_smarty_tpl->tpl_vars['item']->value['tour_disponiert_am'])) {?>title="<?php echo $_smarty_tpl->tpl_vars['item']->value['tour_disponiert_am'];?>
, <?php echo $_smarty_tpl->tpl_vars['item']->value['tour_disponiert_von'];?>
"<?php }?>>
                <?php echo $_smarty_tpl->tpl_vars['item']->value['tour_kennung'];?>

            </td>
            <td title="<?php echo $_smarty_tpl->tpl_vars['item']->value['LeistungenFull'];?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value['Leistungen'];?>
</td>
            <td style="text-align:right;"><?php echo number_format($_smarty_tpl->tpl_vars['item']->value['summe'],2,",",".");?>
 &euro;</td>
        </tr>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    </tbody>
</table>
        Tour: <input type="text" name="tourkennung" placeholder="Tourkennung/ID" size="15" style="border:1px solid #666;width:180px;font-size:11px;" value="<?php echo $_smarty_tpl->tpl_vars['tourkennung']->value;?>
">
        Datum: <input type="date" name="tourdatum" placeholder="YYYY-MM-DD" size="15" style="border:1px solid #666;width:180px;font-size:11px;" value="<?php echo $_smarty_tpl->tpl_vars['tourdatum']->value;?>
">
<input type="submit" name="finish" value="Tourkennung markierten Aufträgen zuweisen"
       style="border-style:solid;border-width:1px;background-color:#666;color:#fff;font-weight:bold;">
</form>
        <div>
            <?php if (!empty($_smarty_tpl->tpl_vars['listAidsByTour']->value)) {?>

            <?php }?>
        </div>
    </div>
</div>
<?php echo '<script'; ?>
>
    var datumFilterFeld = "<?php echo $_smarty_tpl->tpl_vars['datumfeld']->value;?>
";
    var aAuftragsstatusFilter = <?php echo $_smarty_tpl->tpl_vars['aAuftragsstatus']->value;?>
;
<?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>

$(function(){

    $("#auswertungDatumsfeld").val(datumFilterFeld);

    if (aAuftragsstatusFilter && Array.isArray(aAuftragsstatusFilter) && aAuftragsstatusFilter.length > 0) {
        for(var ai = 0; ai < aAuftragsstatusFilter.length; ai++) {
            var asel = '#filter_' + aAuftragsstatusFilter[ai];
            $(asel).prop("checked", true);
        }
    }

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

    $("th.order").click(function(e){
        $("input#orderby").val( $(this).attr("data-fld") );
        send();
    });

    $("th input").keypress(function(e){
        if ( (e.keyCode || e.which) === 13) send();
    });

    $("#tblTourenplanung .flds-head-colsearch input[name^=q]").bind('change', function(e){
        send();
    });

    $("#tblTourenplanung tr[data-href]").on("click", function() {
        var link = $(this).data("href");
        console.log({link});
        window.open( $(this).data("href"), "auftrag");
        window.status= $(this).data("href");
    });

    $("#tblTourenplanung input[type=checkbox][name^=aids]").on("click", function(e) {
        e.stopPropagation();
        return true;
    });

    $("#btnCsvExport").bind("click", function() {
        send('format=csv');
    });

});

<?php echo '</script'; ?>
>
<?php }
}
