<?php
/* Smarty version 3.1.34-dev-7, created on 2021-10-04 12:49:30
  from '/var/www/html/html/auswertung_form.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_615af85a8391b4_79019094',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9b8c33d1ea45c1b92faacc488005cf7172231bfb' => 
    array (
      0 => '/var/www/html/html/auswertung_form.html',
      1 => 1631631864,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_615af85a8391b4_79019094 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/smarty3/plugins/function.html_options.php','function'=>'smarty_function_html_options',),));
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

<form id="frmStat" name="frmStat" method="get" action="?">
<span style="border:0;font-weight:bold;font-size:12px;">Kalenderwoche 
<select onchange="document.forms['frmStat'].submit()" selected="<?php echo $_smarty_tpl->tpl_vars['kwvon']->value;?>
" name="kwvon" style="border:0;font-weight:bold;font-size:12px;width:150px;background:none;">
<option value="">auswählen</option>
        <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['kw_options']->value,'selected'=>$_smarty_tpl->tpl_vars['kwvon']->value),$_smarty_tpl);?>

</select> bis 
<select onchange="document.forms['frmStat'].submit()" selected="<?php echo $_smarty_tpl->tpl_vars['kwbis']->value;?>
" name="kwbis" style="border:0;font-weight:bold;font-size:12px;width:150px;background:none;">
<option value="">auswählen</option>
        <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['kw_options']->value,'selected'=>$_smarty_tpl->tpl_vars['kwbis']->value),$_smarty_tpl);?>

</select> 
<input style="margin-left:15px;" id="all" name="all" type="checkbox" value="1" <?php if ($_smarty_tpl->tpl_vars['all']->value) {?>checked="checked"<?php }?>><label for="all"><span title="Auch bereits abgerechnete Leistungen anzeigen">Alle</span></label>
<input type="hidden" name="s" value="<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
">
<input type="hidden" name="order" value="" id="orderby">
<input type="hidden" name="queriedorder" value="<?php echo $_smarty_tpl->tpl_vars['order']->value;?>
">
<input type="hidden" name="queriedodir" value="<?php echo $_smarty_tpl->tpl_vars['odir']->value;?>
">
<noscript>&lt;input type="submit" value="Auswertung starten"&gt;</noscript>
</span>
<style type="text/css">
    th.order {
        cursor:pointer;
    }
</style>
<?php echo '<script'; ?>
>
$(function(){
    
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
            <th class="order" data-fld="vorgangsnummer">WWS</th>
            <th class="order" data-fld="nachname">STOM</th>
            <th class="order" data-fld="bundesland">Region</th>
            <th class="order" data-fld="stadtname">Standort</th>
            <th class="order" data-fld="Wirtschaftseinheit">WE</th>
            <th class="order" data-fld="kostenstelle">PSP-Element</th>
            <th class="order" data-fld="planonnr">Planon Nr.</th>
            <th class="order" data-fld="umzugstermin">Leistungsdatum</th>
            <th class="order" data-fld="abgeschlossen_am">Abschlussdatum</th>
            <th class="order" data-fld="abgerechnet_am">Rechnungsdatum</th>
            <th class="order" data-fld="summe">Summe</th>
        </tr>
        <tr>
            <th></th>
            <th><input name="q[aid]" style="width:100%" value="<?php echo $_smarty_tpl->tpl_vars['q']->value['aid'];?>
"></th>
            <th><input name="q[vorgangsnummer]" style="width:100%" value="<?php echo $_smarty_tpl->tpl_vars['q']->value['vorgangsnummer'];?>
"></th>
            <th><input name="q[nachname]" style="width:100%" value="<?php echo $_smarty_tpl->tpl_vars['q']->value['nachname'];?>
"></th>
            <th><input name="q[bundesland]" style="width:100%" value="<?php echo $_smarty_tpl->tpl_vars['q']->value['bundesland'];?>
"></th>
            <th><input name="q[stadtname]" style="width:100%" value="<?php echo $_smarty_tpl->tpl_vars['q']->value['stadtname'];?>
"></th>
            <th><input name="q[Wirtschaftseinheit]" style="width:100%" value="<?php echo $_smarty_tpl->tpl_vars['q']->value['Wirtschaftseinheit'];?>
"></th>
            <th><input name="q[kostenstelle]" style="width:100%" value="<?php echo $_smarty_tpl->tpl_vars['q']->value['kostenstelle'];?>
"></th>
            <th><input name="q[planonnr]" style="width:100%" value="<?php echo $_smarty_tpl->tpl_vars['q']->value['planonnr'];?>
"></th>
            <th><input name="q[umzugstermin]" style="width:100%" value="<?php echo $_smarty_tpl->tpl_vars['q']->value['umzugstermin'];?>
"></th>
            <th><input name="q[abgeschlossen_am]" style="width:100%" value="<?php echo $_smarty_tpl->tpl_vars['q']->value['abgeschlossen_am'];?>
"></th>
            <th><input name="q[berechnet_am]" style="width:100%" value="<?php echo $_smarty_tpl->tpl_vars['q']->value['berechnet_am'];?>
"></th>
            <th><input name="q[summe]" style="width:100%" value="<?php echo $_smarty_tpl->tpl_vars['q']->value['summe'];?>
"></th>
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
        <td><?php if (!$_smarty_tpl->tpl_vars['item']->value['berechnet_am']) {?><input type="checkbox" name="aids[]" value="<?php echo $_smarty_tpl->tpl_vars['item']->value['aid'];?>
"><?php }?></td>
        <td><a href="?s=aantrag&id=<?php echo $_smarty_tpl->tpl_vars['item']->value['aid'];?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value['aid'];?>
</a></td>
            <td><?php echo $_smarty_tpl->tpl_vars['item']->value['vorgangsnummer'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['item']->value['nachname'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['item']->value['bundesland'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['item']->value['stadtname'];?>
, <?php echo $_smarty_tpl->tpl_vars['item']->value['adresse'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['item']->value['Wirtschaftseinheit'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['item']->value['kostenstelle'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['item']->value['planonnr'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['item']->value['umzugstermin'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['item']->value['abgeschlossen_am'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['item']->value['berechnet_am'];?>
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
</form><?php }
}
