<?php
/* Smarty version 3.1.34-dev-7, created on 2021-12-13 16:38:15
  from '/var/www/html/html/auswertung_filter.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_61b768e792cd77_19975120',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '28ac27e94067e9ae5772935eb2d8617b490828fd' => 
    array (
      0 => '/var/www/html/html/auswertung_filter.html',
      1 => 1639409892,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_61b768e792cd77_19975120 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/smarty3/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),1=>array('file'=>'/var/www/html/smarty3/plugins/modifier.replace.php','function'=>'smarty_modifier_replace',),));
?>

<style type="text/css">
    th.order {
        cursor:pointer;
    }
    #Auswertung.divInlay input[type=data] {
        background-color: #e2f2ff;
    }
</style>

<?php if ($_smarty_tpl->tpl_vars['s']->value == "auswertung") {?>
    <?php $_smarty_tpl->_assignInScope('showStatus', 1);
} else { ?>
    <?php $_smarty_tpl->_assignInScope('showStatus', 0);
}?>
<div class="divModuleBasic padding6px width5Col heightAuto colorContentMain">
    <h1><span class="spanTitle">Auswertung beauftragter Aufträge</span></h1>

    <div id="Auswertung" class="divInlay">
        <form id="frmStat" name="frmStat" method="get" action="?">
            <div id="frmFilterBox" style="margin-bottom:1rem; border:1px solid #E0E0E0;padding:1rem;border-radius:8px;">
                <span style="border:0;font-weight:bold;">Zeitraum
                    <select id="auswertungDatumsfeld" name="datumfeld">
                        <option value="umzugstermin">Lieferdatum</option>
                        <option value="antragsdatum">Auftragsdatum</option>
                        <!-- option value="geprueft_am">Bestätigungsdatum</option -->
                        <option value="berechnet_am">Rechnungsdatum</option>
                    </select>
                    Von: <input type="date" name="datumvon" style="border-bottom:1px solid #E0E0E0;text-align:right;padding-right:5px" value="<?php echo $_smarty_tpl->tpl_vars['datumvon']->value;?>
" xonchange="document.forms['frmStat'].submit()">
                    Bis: <input type="date" name="datumbis" style="border-bottom:1px solid #E0E0E0;text-align:right;padding-right:5px"  value="<?php echo $_smarty_tpl->tpl_vars['datumbis']->value;?>
" xonchange="document.forms['frmStat'].submit()">
                                <input type="hidden" name="s" value="<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
">
                <input type="hidden" name="order" value="" id="orderby">
                <input type="hidden" name="queriedorder" value="<?php echo $_smarty_tpl->tpl_vars['order']->value;?>
">
                <input type="hidden" name="queriedodir" value="<?php echo $_smarty_tpl->tpl_vars['odir']->value;?>
">


                    <div style="margin-top:5px;margin-bottom:5px;">
                        <label style="margin-right:2rem"><input type="checkbox" name="auftragsstatus[]" id="filter_beauftragt" value="beauftragt"> Beauftragt</label>
                        <label style="margin-right:2rem"><input type="checkbox" name="auftragsstatus[]" id="filter_avisiert" value="avisiert"> Avisiert</label>
                        <label style="margin-right:2rem"><input type="checkbox" name="auftragsstatus[]" id="filter_abgeschlossen" value="abgeschlossen"> Abgeschlossen</label>
                        <label style="margin-right:2rem"><input type="checkbox" name="auftragsstatus[]" id="filter_abgerechnet" value="abgerechnet"> Abgerechnet</label>
                    </div>
                        <button type="submit" class="btn btn-apply">Auswertung starten</button>
                </span>
            </div>

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
            $(function() {
                $("#auswertungDatumsfeld").val(datumFilterFeld);
                // $("#auswertungDatumsfeld").find("option[value=" + datumFilterFeld + "]").prop("selected", true);

                if (aAuftragsstatusFilter && Array.isArray(aAuftragsstatusFilter) && aAuftragsstatusFilter.length > 0) {
                    for(var ai = 0; ai < aAuftragsstatusFilter.length; ai++) {
                        var asel = '#filter_' + aAuftragsstatusFilter[ai];
                        $(asel).prop("checked", true);
                    }
                }

                var send = function() {
                    self.location.href = "?" + $("#frmStat").serialize();
                };

                $("th.order").click(function(e){
                    $("input#orderby").val( $(this).attr("data-fld") );
                    send();
                });

                $("th input").keypress(function(e){
                    if ( (e.keyCode || e.which) === 13) send();
                });

            });
            
            <?php echo '</script'; ?>
>

            <div>
                <h2 style="float:left;" data-site="admin/antraege/liste/html"><?php echo $_smarty_tpl->tpl_vars['numAll']->value;?>
 Aufträge</h2>
                <h2 style="float:right"><?php echo number_format($_smarty_tpl->tpl_vars['sumAll']->value,2,",",".");?>
 &euro;</h2>
                <span style="clear: both"></span>
            </div>
            <span style="clear:both"></span>

            <div style="width:100%;overflow-x:scroll">
            <table id="tblTourenplanung" class="tblList">
                <thead>
                <tr>
                    <th class="order" data-fld="aid" title="Auftrags-ID">ID</th>
                    <th class="order" data-fld="kid">KID</th>
                    <th class="order" data-fld="land" title="Land">L.</th>
                    <th class="order" data-fld="ort" data-fld="ort">Lieferort</th>
                    <th class="order" data-fld="plz">PLZ</th>
                    <th class="order" data-fld="strasse">Stra&szlig;e</th>
                    <th class="order" data-fld="service">Service</th>
                    <th class="order" data-fld="antragsdatum" title="Auftragsdatum">Auftr.Dat.</th>
                    <th class="order" data-fld="umzugstermin" title="Liefertermin">Lief.Dat.</th>
                    <th class="order" data-fld="abgeschlossen_am" title="Abgeschlossen am">Abschluss</th>
                    <th class="order" data-fld="berechnet_am" title="Berechnet am">RechDatum</th>
                    <th class="order" data-fld="vorgangsnummer" title="Rechnungsnummer">RechNr</th>
                    <?php if ($_smarty_tpl->tpl_vars['showStatus']->value) {?>
                    <th class="order" data-fld="umzugsstatus" title="Auftragsstatus">Stat</th>
                    <?php }?>
                    <th class="order" data-fld="Leistungen abgekürzt" title="Leistungen abgekürzt">Lstg.</th>
                    <th class="order" data-fld="summe" title="Summe">Summe</th>
                </tr>
                <tr>
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
                    <th><input placeholder="JJJJ-MM-TT" name="q[antragsdatum]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['antragsdatum'])) {
echo $_smarty_tpl->tpl_vars['q']->value['antragsdatum'];
}?>"></th>
                    <th><input placeholder="JJJJ-MM-TT" name="q[umzugstermin]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['umzugstermin'])) {
echo $_smarty_tpl->tpl_vars['q']->value['umzugstermin'];
}?>"></th>
                    <th><input placeholder="JJJJ-MM-TT" name="q[abgeschlossen_am]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['abgeschlossen_am'])) {
echo $_smarty_tpl->tpl_vars['q']->value['abgeschlossen_am'];
}?>"></th>
                    <th><input placeholder="JJJJ-MM-TT" name="q[berechnet_am]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['berechnet_am'])) {
echo $_smarty_tpl->tpl_vars['q']->value['berechnet_am'];
}?>"></th>
                    <th><input name="q[vorgangsnummer]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['vorgangsnummer'])) {
echo $_smarty_tpl->tpl_vars['q']->value['vorgangsnummer'];
}?>"></th>
                    <?php if ($_smarty_tpl->tpl_vars['showStatus']->value) {?>
                    <th><input name="q[umzugsstatus]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['umzugsstatus'])) {
echo $_smarty_tpl->tpl_vars['q']->value['umzugsstatus'];
}?>"></th>
                    <?php }?>
                    <th><input name="q[Leistungen]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['Leistungen'])) {
echo $_smarty_tpl->tpl_vars['q']->value['Leistungen'];
}?>"></th>
                    <th><input name="q[summe]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['summe'])) {
echo $_smarty_tpl->tpl_vars['q']->value['summe'];
}?>"></th>
                </tr>
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
                    <td><a href="?s=<?php echo $_smarty_tpl->tpl_vars['site_antrag']->value;?>
&id=<?php echo $_smarty_tpl->tpl_vars['item']->value['aid'];?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value['aid'];?>
</a></td>
                    <td><?php echo $_smarty_tpl->tpl_vars['item']->value['kid'];?>
</td>
                    <td><?php if ($_smarty_tpl->tpl_vars['item']->value['land'] == "Deutschland") {?>D<?php } elseif ($_smarty_tpl->tpl_vars['item']->value['land'] == "Niederlande") {?>NL<?php } else {
echo substr($_smarty_tpl->tpl_vars['item']->value['land'],0,3);
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


                    <td title="<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['berechnet_am'],"%d.%m.%Y %H:%M");?>
">
                    <?php ob_start();
echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['berechnet_am'],"%Y");
$_prefixVariable7 = ob_get_clean();
ob_start();
echo date('Y');
$_prefixVariable8 = ob_get_clean();
if (!empty($_smarty_tpl->tpl_vars['item']->value['berechnet_am']) && $_prefixVariable7 == $_prefixVariable8) {?>
                    <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['berechnet_am'],"%d.%m.");?>

                    <?php } else { ?>
                    <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['berechnet_am'],"%d.%m.%y");?>

                    <?php }?>
                    </td>

                    <td><?php echo $_smarty_tpl->tpl_vars['item']->value['vorgangsnummer'];?>
</td>
                    <?php if ($_smarty_tpl->tpl_vars['showStatus']->value) {?>
                    <td title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['umzugsstatus'], ENT_QUOTES, 'UTF-8', true);?>
"><?php echo htmlspecialchars(substr($_smarty_tpl->tpl_vars['item']->value['umzugsstatus'],0,5), ENT_QUOTES, 'UTF-8', true);?>
</td>
                    <?php }?>
                    <td title="<?php echo smarty_modifier_replace(smarty_modifier_replace($_smarty_tpl->tpl_vars['item']->value['LeistungenFull'],';',''),'<|#|>','  ');?>
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
            </div>
        </form>

        <div id="TourStats">

        </div>
    </div>
</div>
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
var selectorAllItems = "#tblTourenplanung tr.flds-body-row";
var showCsvAids = false;
var showTotalLstgAbk = false;

function renderTourStats(stats) {
    var container = $("div#TourStats");
    container.html("");

    var aids = ("aids" in stats && Array.isArray(stats.aids)) ? stats.aids : [];

    if (showCsvAids) {
        var boxEnthalteneAids = $("<div/>")
            .append($("<div/>").addClass("title").text((aids.length ? aids.length + ' ' : '') + "Aufträge"))
            .append($("<div/>").addClass("content").text(aids.join(", ")))
            .appendTo(container);
    }

    var abks = ("LeistungenTotalAbk" in stats) ? stats.LeistungenTotalAbk : {};

    if (showTotalLstgAbk) {
        var boxAbks = $("<div/>").addClass("content");
        var boxEnthalteneAbks = $("<div/>")
            .append($("<div/>").addClass("title").text("Leistungen"))
            .append(boxAbks)
            .appendTo(container);

        for (var k in abks) {
            if (abks.hasOwnProperty(k)) {
                var n = abks[k];
                boxAbks.append($("<span/>").html(n.toString() + k + ' '));
            }
        }
    }

    var leistungen = ("LeistungenTotalLid" in stats && stats.LeistungenTotalLid) ? stats.LeistungenTotalLid : {};
    var tblLstg = $("<table/>").addClass("tblList").css("width", "100%");
    var boxEnthalteneAbks = $("<div/>")
        .append( $("<div/>").addClass("title").text( "Leistungsübersicht" ) )
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
    var selector = selectorSelectedItems;
    return procesTourStatsBySelector(selector);
}

function procesTourStatsAll() {
    var selector = selectorAllItems;
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


$(function() {

    $(".geo-address[data-address]").each(function () {
        var gmapUrl = "https://www.google.com/maps/dir/?api=1&travelmode=driving&destination=";
        var query = encodeURIComponent($(this).data("address"));
        // https://www.google.com/maps/dir/?api=1&destination=Mainzer+Straße+97,65189+Wiesbaden,Deutschland&travelmode=driving
        $(this)
            .wrap(
                $("<a/>").attr({
                    href: gmapUrl + query,
                    target: "gmap",
                    title: "Lieferadresse in Gmap anzeigen"
                }))
            .prepend($("<i/>").addClass("marker icon").css("width", "auto"))
            .addClass("marker icon")
            .bind("click", function (e) {
                e.stopPropagation();
            });
    });

    statsOfAllItems = procesTourStatsAll();
    renderTourStats(statsOfAllItems);
});

<?php echo '</script'; ?>
>
<?php }
}
