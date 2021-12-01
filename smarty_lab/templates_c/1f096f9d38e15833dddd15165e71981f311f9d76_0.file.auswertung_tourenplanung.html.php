<?php
/* Smarty version 3.1.34-dev-7, created on 2021-12-01 12:49:44
  from '/var/www/html/html/auswertung_tourenplanung.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_61a76158681408_76464653',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1f096f9d38e15833dddd15165e71981f311f9d76' => 
    array (
      0 => '/var/www/html/html/auswertung_tourenplanung.html',
      1 => 1638359202,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:admin_antraege_tabs.html' => 1,
  ),
),false)) {
function content_61a76158681408_76464653 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/smarty3/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),1=>array('file'=>'/var/www/html/smarty3/plugins/modifier.replace.php','function'=>'smarty_modifier_replace',),));
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

        #TourStats .title {
            font-weight: bold;
            color: #0075b5;
            margin-top:1rem;
        }
        #TourStats .content,
        #TourStats .content-table {
            padding: 5px;
        }
        #TourStats .content-table {
            border-top: 1px solid #a4cbe0;
        }
        #TourStats .content {
            border: 1px solid #a4cbe0;
            border-radius: 5px;
            resize: vertical;
            height: 3rem;
            overflow: auto;
         }
        </style>
<?php $_smarty_tpl->_assignInScope('hintValidColSearchOperator', "Zulässige Suchoperatoren: > >= = < <= ! != UND * als Wildcard");?>
        <?php if (isset($_smarty_tpl->tpl_vars['Auftraege']->value)) {?><h2><?php echo count($_smarty_tpl->tpl_vars['Auftraege']->value);?>
 Aufträge</h2><?php }?>
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
            <th><input title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['hintValidColSearchOperator']->value, ENT_QUOTES, 'UTF-8', true);?>
" name="q[aid]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['aid'])) {
echo $_smarty_tpl->tpl_vars['q']->value['aid'];
}?>"></th>
            <th><input title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['hintValidColSearchOperator']->value, ENT_QUOTES, 'UTF-8', true);?>
" name="q[kid]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['kid'])) {
echo $_smarty_tpl->tpl_vars['q']->value['kid'];
}?>"></th>
            <th><input title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['hintValidColSearchOperator']->value, ENT_QUOTES, 'UTF-8', true);?>
" name="q[land]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['land'])) {
echo $_smarty_tpl->tpl_vars['q']->value['land'];
}?>"></th>
            <th><input title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['hintValidColSearchOperator']->value, ENT_QUOTES, 'UTF-8', true);?>
" name="q[ort]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['ort'])) {
echo $_smarty_tpl->tpl_vars['q']->value['ort'];
}?>"></th>
            <th><input title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['hintValidColSearchOperator']->value, ENT_QUOTES, 'UTF-8', true);?>
" name="q[plz]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['plz'])) {
echo $_smarty_tpl->tpl_vars['q']->value['plz'];
}?>"></th>
            <th><input title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['hintValidColSearchOperator']->value, ENT_QUOTES, 'UTF-8', true);?>
" name="q[strasse]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['strasse'])) {
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
            <th><input title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['hintValidColSearchOperator']->value, ENT_QUOTES, 'UTF-8', true);?>
" name="q[tour_kennung]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['tour_kennung'])) {
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
        <tr class="flds-body-row data-href"
            data-href="?s=<?php echo $_smarty_tpl->tpl_vars['site_antrag']->value;?>
&id=<?php echo $_smarty_tpl->tpl_vars['item']->value['aid'];?>
"
            data-umzugstermin="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['umzugstermin'], ENT_QUOTES, 'UTF-8', true);?>
"
            data-tour="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['tour_kennung'], ENT_QUOTES, 'UTF-8', true);?>
"
            data-aid="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['aid'], ENT_QUOTES, 'UTF-8', true);?>
"
            data-leistungen="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['Leistungen'], ENT_QUOTES, 'UTF-8', true);?>
"
            data-leistungenfull="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['LeistungenFull'], ENT_QUOTES, 'UTF-8', true);?>
">
            <td>
                <input type="checkbox" name="aids[]" value="<?php echo $_smarty_tpl->tpl_vars['item']->value['aid'];?>
"<?php if (!empty($_smarty_tpl->tpl_vars['aids']->value) && in_array($_smarty_tpl->tpl_vars['item']->value['aid'],$_smarty_tpl->tpl_vars['aids']->value)) {?> checked="checked"<?php }?>>
            </td>
            <td class="fld-cell fld-aid" data-field="aid" data-value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['aid'], ENT_QUOTES, 'UTF-8', true);?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['aid'], ENT_QUOTES, 'UTF-8', true);?>
</td>
            <td class="fld-cell fld-kid" data-field="kid" data-value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['kid'], ENT_QUOTES, 'UTF-8', true);?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['kid'], ENT_QUOTES, 'UTF-8', true);?>
</td>
            <td class="fld-cell fld-land" data-field="land" data-value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['land'], ENT_QUOTES, 'UTF-8', true);?>
"><?php if ($_smarty_tpl->tpl_vars['item']->value['land'] == "Deutschland") {?>D<?php } elseif ($_smarty_tpl->tpl_vars['item']->value['land'] == "Niederlande") {?>NL<?php } else {
echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['land'], ENT_QUOTES, 'UTF-8', true);
}?></td>
            <td class="fld-cell fld-ort" data-field="ort" data-value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['ort'], ENT_QUOTES, 'UTF-8', true);?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['ort'], ENT_QUOTES, 'UTF-8', true);?>
</td>
            <td class="fld-cell fld-plz" data-field="plz" data-value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['plz'], ENT_QUOTES, 'UTF-8', true);?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['plz'], ENT_QUOTES, 'UTF-8', true);?>
</td>
            <td class="fld-cell fld-strasse" data-field="strasse" data-value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['strasse'], ENT_QUOTES, 'UTF-8', true);?>
">
                <span class="geo-address"
                   data-address="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['strasse'], ENT_QUOTES, 'UTF-8', true);?>
,<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['plz'], ENT_QUOTES, 'UTF-8', true);?>
+<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['ort'], ENT_QUOTES, 'UTF-8', true);?>
,<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['land'], ENT_QUOTES, 'UTF-8', true);?>
" style="color:red;">
                <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['strasse'], ENT_QUOTES, 'UTF-8', true);?>
</span></td>
            <td class="fld-cell fld-service" data-field="service" data-value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['service'], ENT_QUOTES, 'UTF-8', true);?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['service'], ENT_QUOTES, 'UTF-8', true);?>
</td>
            <td class="fld-cell fld-antragsdatum" data-field="antragsdatum" data-value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['antragsdatum'], ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['antragsdatum'],"%d.%m.%Y %H:%M");?>
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
            <td class="fld-cell fld-umzugstermin" data-field="umzugstermin" data-value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['umzugstermin'], ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['umzugstermin'],"%d.%m.%Y");?>
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
            <td class="fld-cell fld-tour_kennung" data-field="tour_kennung" data-value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['tour_kennung'], ENT_QUOTES, 'UTF-8', true);?>
" <?php if (!empty($_smarty_tpl->tpl_vars['item']->value['tour_disponiert_am'])) {?>title="<?php echo $_smarty_tpl->tpl_vars['item']->value['tour_disponiert_am'];?>
, <?php echo $_smarty_tpl->tpl_vars['item']->value['tour_disponiert_von'];?>
"<?php }?>>
                <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['tour_kennung'], ENT_QUOTES, 'UTF-8', true);?>

            </td>
            <td class="fld-cell fld-Leistungen" data-field="Leistungen" data-value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['Leistungen'], ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo smarty_modifier_replace(smarty_modifier_replace($_smarty_tpl->tpl_vars['item']->value['LeistungenFull'],';',''),'<|#|>','  ');?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['Leistungen'], ENT_QUOTES, 'UTF-8', true);?>
</td>
            <td class="fld-cell fld-summe" data-field="summe" data-value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['summe'], ENT_QUOTES, 'UTF-8', true);?>
" style="text-align:right;"><?php echo number_format($_smarty_tpl->tpl_vars['item']->value['summe'],2,",",".");?>
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
        <div id="TourStats">

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

function numberFormat(number, dec) {
    var dec_point = ',';
    var thousands_sep = '.';
    if (isNaN(dec)) {
        dec = 2;
    }
    if (isNaN(+number)) {
        return number;
    }
    var parts = (+number).toFixed(dec).split(".");
    parts[0] = parts[0].split("").reverse();
    var fnum = [];
    for(var i = 0; i < parts[0].length; i++) {
        if ( i % 3 === 0 && !isNaN(parts[0][i]) ) {
            fnum.push(thousands_sep);
        }
        fnum.push( parts[0][i] );
    }

    return fnum.reverse().join("") + dec_point + parts[1];
}

var statsMetaByLid = {};
var statsOfAllItems = {};
var statsOfSelectedItems = {};
var selectorSelectedItems = "#tblTourenplanung input[type=checkbox][name^=aids\\[\\]]:checked";
var selectorAllItems = "#tblTourenplanung input[type=checkbox][name^=aids\\[\\]]";

function renderTourStats(stats) {
    var container = $("div#TourStats");
    container.html("");

    var aids = ("aids" in stats && Array.isArray(stats.aids)) ? stats.aids : [];

    var boxEnthalteneAids = $("<div/>")
        .append( $("<div/>").addClass("title").text( (aids.length ? aids.length + ' ' : '') + "Aufträge" ) )
        .append( $("<div/>").addClass("content").text( aids.join(", ") ) )
        .appendTo(container);

    var abks = ("LeistungenTotalAbk" in stats) ? stats.LeistungenTotalAbk : {};

    var boxAbks = $("<div/>").addClass("content");
    var boxEnthalteneAbks = $("<div/>")
        .append( $("<div/>").addClass("title").text( "Leistungen" ) )
        .append( boxAbks )
        .appendTo(container);

    for(var k in abks) {
        if (abks.hasOwnProperty(k)) {
            var n = abks[k];
            boxAbks.append( $("<span/>").html( n.toString() + k + ' ' ));
        }
    }

    var leistungen = ("LeistungenTotalLid" in stats && stats.LeistungenTotalLid) ? stats.LeistungenTotalLid : {};
    var tblLstg = $("<table/>").addClass("tblList").css("width", "100%");
    var boxEnthalteneAbks = $("<div/>")
        .append( $("<div/>").addClass("title").text( "Leistungen" ) )
        .append( $("<div/>").addClass("content-table").append( tblLstg ) )
        .appendTo(container);

    var thead = $("<thead/>").addClass("flds-head").appendTo(tblLstg);
    var tbody = $("<tbody/>").addClass("flds-head").appendTo(tblLstg);
    var thr = $("<tr/>").appendTo(thead);
    var thAbk = $("<th/>").addClass("fld-cell fld-abk").text('L-Abk').appendTo(thr);
    var thKtg = $("<th/>").addClass("fld-cell fld-ktg").text('Kategorie').appendTo(thr);
    var thBez = $("<th/>").addClass("fld-cell fld-bezeichnung").text('Bezeichnung').appendTo(thr);
    var thFarbe = $("<th/>").addClass("fld-cell fld-farbe").text('Farbe').appendTo(thr);
    var thGroese = $("<th/>").addClass("fld-cell fld-groesse").text('Größe').appendTo(thr);
    var thPreis = $("<th/>").addClass("fld-cell fld-preis menge float").text('Preis').appendTo(thr);
    var thMenge = $("<th/>").addClass("fld-cell fld-menge menge").text('Menge').appendTo(thr);
    var thSumme = $("<th/>").addClass("fld-cell fld-summe sum").text('Summe').appendTo(thr);


    var total = 0.0;
    for(var lg in leistungen) {
        if (leistungen.hasOwnProperty(lg)) {
            var menge = +leistungen[lg];
            var info = statsMetaByLid[lg];
            var preis = parseFloat(info.Preis);
            var summe = menge * preis;
            total+= summe;

            var tr = $("<tr/>").appendTo(tbody);

            tr
                .append( $("<td/>").text(info.Abk) )
                .append( $("<td/>").text(info.Kategorie) )
                .append( $("<td/>").text(info.Bezeichnung) )
                .append( $("<td/>").text(info.Farbe) )
                .append( $("<td/>").text(info.Groesse) )
                .append( $("<td/>").addClass("menge float currency-euro").text( numberFormat(preis) ) )
                .append( $("<td/>").addClass("menge int").text( menge) )
                .append( $("<td/>").addClass("menge sum float currency-euro").text( numberFormat(summe) ) )
            ;
        }
    }
    tbody.append(
        $("<tr/>").append(
            $("<td/>")
                .attr("colspan", 8)
                .addClass("menge sum float currency-euro")
                .text( numberFormat(total)
                )));
}

function procesTourStatsSelected() {
    var selector = "#tblTourenplanung input[type=checkbox][name^=aids\\[\\]]:checked";
    console.log('#188 ', { selector });
    return procesTourStatsBySelector(selector);
}

function procesTourStatsAll() {
    var selector = "#tblTourenplanung input[type=checkbox][name^=aids\\[\\]]";
    return procesTourStatsBySelector(selector);
}

function procesTourStatsBySelector(selector) {

    var stats = { aids: [], LeistungenTotalAbk: {}, LeistungenTotalKtg: {}, LeistungenTotalLid: {}, LeistungenByTermin: {}, LeistungenByTour: {} };
    var numItems = $(selector).closest("tr").length;
    console.log('#201 Found ', { numItems, selector });
    $(selector).closest("tr").each(function() {
        var d = $(this).data();
        // console.log('#235 Tourenplanung', { d  });
        // return;
        if (!$.trim(d.leistungenfull)) {
            console.log('#236 empty leistungenfull for aid ' + d.aid, d);
            return;
        }
        if (!$.trim($.trim(d.leistungenfull).split(";\n").join("").split("<|#|>").join("") )) {
            return;
        }
        var aLstFull = d.leistungenfull.split(";\n");
        var tour = d.tour;
        var liefertermin = d.umzugstermin;

        var lstg = [];
        for(li = 0; li < aLstFull.length; li++) {
            var csv = aLstFull[li].split("<|#|>");
            var lid = csv[0];
            var Abk = csv[1];
            if (!Abk) {
                console.error('#253 empty abk', { d, aLstFull, tour, liefertermin, lid });
            }
            var Kategorie = csv[2];
            var Bezeichnung = csv[3];
            var Farbe = csv[4];
            var Groesse = csv[5];
            var Waehrung = csv[6];
            var Preis = csv[7];
            var lstObj = { lid, Abk, Kategorie, Bezeichnung, Farbe, Groesse, Waehrung, Preis, csv };
            if (!(lid in statsMetaByLid)) {
                statsMetaByLid[lid] = lstObj;
            }
            lstg.push({ lid, Abk, Kategorie, Bezeichnung, Farbe, Groesse, Waehrung, Preis, csv });
        }
        console.log('#235 Tourenplanung', { d, 'aLstFull.length': aLstFull.length, 'lstg.length': lstg.length, aLstFull, tour, liefertermin });

        stats.aids.push(d.aid);
        for(var li = 0; li < lstg.length; li++) {
            var abk = lstg[li].Abk;
            if (!(abk in stats.LeistungenTotalAbk)) {
                stats.LeistungenTotalAbk[abk] = 1;
            } else {
                ++stats.LeistungenTotalAbk[abk];
            }


            var ktg = lstg[li].Kategorie;
            if (!(ktg in stats.LeistungenTotalKtg)) {
                stats.LeistungenTotalKtg[ktg] = 1;
            } else {
                ++stats.LeistungenTotalKtg[ktg];
            }

            var lid = lstg[li].lid;
            if (!(lid in stats.LeistungenTotalLid)) {
                stats.LeistungenTotalLid[lid] = 1;
            } else {
                ++stats.LeistungenTotalLid[lid];
            }

            if (tour) {
                if (!(tour in stats.LeistungenByTour)) {
                    stats.LeistungenByTour[tour] = {
                        Abk: {}, Ktg: {}, Lid: {}
                    };
                }
                if (!(abk in stats.LeistungenByTour[tour].Abk)) {
                    stats.LeistungenByTour[tour].Abk[abk] = 1;
                } else {
                    ++stats.LeistungenByTour[tour].Abk[abk];
                }
                if (!(ktg in stats.LeistungenByTour[tour].Ktg)) {
                    stats.LeistungenByTour[tour].Ktg[ktg] = 1;
                } else {
                    ++stats.LeistungenByTour[tour].Ktg[ktg];
                }
                if (!(lid in stats.LeistungenByTour[tour].Lid)) {
                    stats.LeistungenByTour[tour].Lid[lid] = 1;
                } else {
                    ++stats.LeistungenByTour[tour].Lid[lid];
                }
            }
            if (liefertermin) {
                if (!(liefertermin in stats.LeistungenByTermin)) {
                    stats.LeistungenByTermin[liefertermin] = {
                        Liefertermin: liefertermin, Abk: {}, Ktg: {}, Lid: {}
                    };
                }
                if (!(abk in stats.LeistungenByTermin[liefertermin].Abk)) {
                    stats.LeistungenByTermin[liefertermin].Abk[abk] = 1;
                } else {
                    ++stats.LeistungenByTermin[liefertermin].Abk[abk];
                }
                if (!(ktg in stats.LeistungenByTermin[liefertermin].Ktg)) {
                    stats.LeistungenByTermin[liefertermin].Ktg[ktg] = 1;
                } else {
                    ++stats.LeistungenByTermin[liefertermin].Ktg[ktg];
                }
                if (!(lid in stats.LeistungenByTermin[liefertermin].Lid)) {
                    stats.LeistungenByTermin[liefertermin].Lid[lid] = 1;
                } else {
                    ++stats.LeistungenByTermin[liefertermin].Lid[lid];
                }
            }
        }
    });
    console.log("335 stats", { stats, statsMetaByLid });
    return stats;
}

$(function(){

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

    $("tbody tr td.fld-umzugstermin[data-value]:not([data-value=''])").each(function() {
        var datum = $(this).data("value");
        var url = "http://tourenplanung.mertens.ag/touren/index?date=" + encodeURIComponent(datum) + "&lager_id=1";
        var html = $(this).html();

        // https://www.google.com/maps/dir/?api=1&destination=Mainzer+Straße+97,65189+Wiesbaden,Deutschland&travelmode=driving
        $(this)
            .html("")
            .append(
                $("<a/>")
                    .attr({
                        href: url,
                        target: "tourenplanung",
                        title: "Dispo / Tourenplanung für den Tag anzeigen"
                    })
                    .html(html)
                    .css({color: "blue"})
                    .bind("click", function(e) {
                        e.stopPropagation();
                    })
            )
            .bind("click", function(e) {
                e.stopPropagation();
            });
    });

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



    $("#tblTourenplanung input[type=checkbox][name^=aids\\[\\]]").bind("click", function(e) {
        e.stopPropagation();

        var numSelectedItems = $(selectorSelectedItems).length;
        var stats = (numSelectedItems > 0) ? procesTourStatsSelected() : statsOfAllItems;
        console.log("335 stats", { stats, statsMetaByLid });
        renderTourStats(stats);
        return true;
    });

    $("#btnCsvExport").bind("click", function() {
        send('format=csv');
    });

    $("#toggleDebugInfos").bind("click", function(e) {
        $("#debugInfosContainer").toggle();
    });

    statsOfAllItems = procesTourStatsAll();
    renderTourStats(statsOfAllItems);

});

<?php echo '</script'; ?>
>
<?php }
}
