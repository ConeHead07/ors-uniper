<?php
/* Smarty version 3.1.34-dev-7, created on 2021-12-07 15:17:12
  from '/var/www/html/html/auswertung_filter.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_61af6ce87282c5_24250585',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '28ac27e94067e9ae5772935eb2d8617b490828fd' => 
    array (
      0 => '/var/www/html/html/auswertung_filter.html',
      1 => 1638886623,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_61af6ce87282c5_24250585 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/smarty3/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
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

            <div style="width:100%;overflow-x:scroll">
            <table class="tblList">
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
                <tr>
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
            </div>
        </form>
    </div>
</div>
<?php }
}
