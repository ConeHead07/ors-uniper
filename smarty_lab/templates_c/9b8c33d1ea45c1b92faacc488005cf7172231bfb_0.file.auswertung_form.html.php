<?php
/* Smarty version 3.1.34-dev-7, created on 2021-11-11 15:12:50
  from '/var/www/html/html/auswertung_form.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_618d32f2cb0677_18795737',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9b8c33d1ea45c1b92faacc488005cf7172231bfb' => 
    array (
      0 => '/var/www/html/html/auswertung_form.html',
      1 => 1636472582,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_618d32f2cb0677_18795737 (Smarty_Internal_Template $_smarty_tpl) {
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
<div class="divModuleBasic padding6px width5Col heightAuto colorContentMain">
    <h1><span class="spanTitle">Abrechnung abgeschlossener Aufträge</span></h1>

    <div id="Auswertung" class="divInlay">
<form id="frmStat" name="frmStat" method="get" action="?" data-site="auswertung/form/html">
    <span style="border:0;font-weight:bold;font-size:12px;">
    Zeitraum
    <select id="auswertungDatumsfeld" name="datumfeld">
        <option value="umzugstermin">Lieferdatum</option>
        <option value="antragsdatum">Auftragsdatum</option>
        <option value="abgeschlossen_am">Abschlussdatum</option>
        <!-- option value="bestaetigt_am">Bestätigungsdatum</option -->
        <option value="berechnet_am">Rechnungsdatum</option>
    </select>
    Von: <input type="date" name="datumvon" value="<?php echo $_smarty_tpl->tpl_vars['datumvon']->value;?>
" xonchange="document.forms['frmStat'].submit()">
    Bis: <input type="date" name="datumbis" value="<?php echo $_smarty_tpl->tpl_vars['datumbis']->value;?>
" xonchange="document.forms['frmStat'].submit()">
    <input style="margin-left:15px;" id="all" name="all" type="checkbox" value="1" <?php if ($_smarty_tpl->tpl_vars['all']->value) {?>checked="checked"<?php }?>><label for="all"><span title="Auch bereits abgerechnete Leistungen anzeigen">Alle</span></label>
<input type="hidden" name="s" value="<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
">
<input type="hidden" name="order" value="" id="orderby">
<input type="hidden" name="queriedorder" value="<?php echo $_smarty_tpl->tpl_vars['order']->value;?>
">
<input type="hidden" name="queriedodir" value="<?php echo $_smarty_tpl->tpl_vars['odir']->value;?>
">
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
            <th><input name="q[summe]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['aid'])) {
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
            <td><?php if (!$_smarty_tpl->tpl_vars['item']->value['berechnet_am']) {?><input type="checkbox" name="aids[]" value="<?php echo $_smarty_tpl->tpl_vars['item']->value['aid'];?>
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
            <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['antragsdatum'],"%d.%m.%Y");?>
</td>
            <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['umzugstermin'],"%d.%m.%Y");?>
</td>
            <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['abgeschlossen_am'],"%d.%m.%Y");?>
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
    <input type="text" name="wwsnr" placeholder="Vorgangsnummer" size="15" style="border:1px solid #666;width:180px;font-size:11px;" value="<?php echo $_smarty_tpl->tpl_vars['wwsnr']->value;?>
">
<input type="submit" name="finish" value="Fertig stellen" 
       style="border-style:grove;border-width:1px;background-color:#666;color:#fff;font-weight:bold;">
</form>
    </div>
</div>
<?php }
}
