<?php
/* Smarty version 3.1.34-dev-7, created on 2021-11-17 15:11:57
  from '/var/www/html/html/auswertung_filter.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_61950dad2a2488_30635073',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '28ac27e94067e9ae5772935eb2d8617b490828fd' => 
    array (
      0 => '/var/www/html/html/auswertung_filter.html',
      1 => 1637158184,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_61950dad2a2488_30635073 (Smarty_Internal_Template $_smarty_tpl) {
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

            <table class="tblList">
                <thead>
                <tr>
                    <th class="order" data-fld="aid">ID</th>
                    <th class="order" data-fld="kid">KID</th>
                    <th class="order" data-fld="land">Land</th>
                    <th class="order" data-fld="ort">Lieferort</th>
                    <th class="order" data-fld="plz">PLZ</th>
                    <th class="order" data-fld="strasse">Stra&szlig;e</th>
                    <th class="order" data-fld="service">Service</th>
                    <th class="order" data-fld="antragsdatum">Auftr.Dat.</th>
                    <th class="order" data-fld="umzugstermin">Lief.Dat.</th>
                    <th class="order" data-fld="abgeschlossen_am">Abschluss</th>
                    <th class="order" data-fld="berechnet_am">RechDatum</th>
                    <th class="order" data-fld="vorgangsnummer">Rechnungsnr</th>
                    <th class="order" data-fld="summe">Summe</th>
                </tr>
                <tr>
                    <th><input name="q[aid]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['aid'])) {
echo $_smarty_tpl->tpl_vars['q']->value['aid'];
}?>"></th>
                    <th><input name="q[kid]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['aid'])) {
echo $_smarty_tpl->tpl_vars['q']->value['kid'];
}?>"></th>
                    <th><input name="q[land]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['aid'])) {
echo $_smarty_tpl->tpl_vars['q']->value['land'];
}?>"></th>
                    <th><input name="q[ort]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['aid'])) {
echo $_smarty_tpl->tpl_vars['q']->value['ort'];
}?>"></th>
                    <th><input name="q[plz]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['aid'])) {
echo $_smarty_tpl->tpl_vars['q']->value['plz'];
}?>"></th>
                    <th><input name="q[strasse]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['aid'])) {
echo $_smarty_tpl->tpl_vars['q']->value['strasse'];
}?>"></th>
                    <th><input name="q[service]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['aid'])) {
echo $_smarty_tpl->tpl_vars['q']->value['service'];
}?>"></th>
                    <th><input name="q[antragsdatum]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['aid'])) {
echo $_smarty_tpl->tpl_vars['q']->value['antragsdatum'];
}?>"></th>
                    <th><input name="q[umzugstermin]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['aid'])) {
echo $_smarty_tpl->tpl_vars['q']->value['umzugstermin'];
}?>"></th>
                    <th><input name="q[abgeschlossen_am]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['aid'])) {
echo $_smarty_tpl->tpl_vars['q']->value['abgeschlossen_am'];
}?>"></th>
                    <th><input name="q[berechnet_am]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['aid'])) {
echo $_smarty_tpl->tpl_vars['q']->value['berechnet_am'];
}?>"></th>
                    <th><input name="q[vorgangsnummer]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['aid'])) {
echo $_smarty_tpl->tpl_vars['q']->value['vorgangsnummer'];
}?>"></th>
                    <th><input name="q[summe]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['aid'])) {
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
                    <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['antragsdatum'],"%d.%m.%Y");?>
</td>
                    <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['umzugstermin'],"%d.%m.%Y");?>
</td>
                    <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['abgeschlossen_am'],"%d.%m.%Y");?>
</td>
                    <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['berechnet_am'],"%d.%m.%Y");?>
</td>
                    <td><?php echo $_smarty_tpl->tpl_vars['item']->value['vorgangsnummer'];?>
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
        </form>
    </div>
</div>
<?php }
}
