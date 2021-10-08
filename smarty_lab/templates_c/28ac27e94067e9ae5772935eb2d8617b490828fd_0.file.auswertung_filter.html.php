<?php
/* Smarty version 3.1.34-dev-7, created on 2021-10-04 11:01:17
  from '/var/www/html/html/auswertung_filter.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_615adefdb48ba1_76513220',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '28ac27e94067e9ae5772935eb2d8617b490828fd' => 
    array (
      0 => '/var/www/html/html/auswertung_filter.html',
      1 => 1633345273,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_615adefdb48ba1_76513220 (Smarty_Internal_Template $_smarty_tpl) {
?><pre style="display:none;">
html/auswertung_filter.html
<b>Vodafone und Mertens</b>

Weiterhin soll es eine flexible Auswertung geben.
Hierzu soll eine Auswahl von KW bis KW  und das Jahr und Berechnet  ja/Nein angegeben werden.
 
Folgende Felder sollen angezeigt werden:
 
ID|STOM|Region|Standort|Wirtschaftseinheit|PSP-Element|Planon Nr.|Leistungsdatum|Abschluﬂdatum|Rechnungsdatum|Summe
 
Jedes Feld soll Filterbar sein ( unter dem jeweiligen Feld ein Freifeld in dem Text eingegeben 
/ ausgew‰hlt werden kann.

</pre>

<style type="text/css">
    th.order {
        cursor:pointer;
    }
</style>
<form id="frmStat" name="frmStat" method="get" action="?">
<span style="border:0;font-weight:bold;font-size:12px;">Zeitraum
    Von: <input type="date" name="datumvon" value="<?php echo $_smarty_tpl->tpl_vars['datumvon']->value;?>
" onchange="document.forms['frmStat'].submit()">
    Bis: <input type="date" name="datumbis" value="<?php echo $_smarty_tpl->tpl_vars['datumbis']->value;?>
" onchange="document.forms['frmStat'].submit()">
<input type="hidden" name="s" value="<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
">
<input type="hidden" name="order" value="" id="orderby">
<input type="hidden" name="queriedorder" value="<?php echo $_smarty_tpl->tpl_vars['order']->value;?>
">
<input type="hidden" name="queriedodir" value="<?php echo $_smarty_tpl->tpl_vars['odir']->value;?>
">
<noscript>&lt;input type="submit" value="Auswertung starten"&gt;</noscript>
</span>
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
            <th class="order" data-fld="aid">ID</th>
            <th class="order" data-fld="vorgangsnummer">WWS</th>
            <th class="order" data-fld="nachname">STOM</th>
            <th class="order" data-fld="bundesland">Region</th>
            <th class="order" data-fld="stadtname">Standort</th>
            <th class="order" data-fld="Wirtschaftseinheit">WE</th>
            <th class="order" data-fld="kostenstelle">PSP-Element</th>
            <th class="order" data-fld="planonnr">Ticket Nr.</th>
            <th class="order" data-fld="umzugstermin">Leistungsdatum</th>
            <th class="order" data-fld="abgeschlossen_am">Abschlussdatum</th>
            <th class="order" data-fld="berechnet_am">Rechnungsdatum</th>
            <th class="order" data-fld="summe">Summe</th>
        </tr>
        <tr>
            <th><input name="q[aid]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['aid'])) {
echo $_smarty_tpl->tpl_vars['q']->value['aid'];
}?>"></th>
            <th><input name="q[vorgangsnummer]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['aid'])) {
echo $_smarty_tpl->tpl_vars['q']->value['vorgangsnummer'];
}?>"></th>
            <th><input name="q[nachname]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['aid'])) {
echo $_smarty_tpl->tpl_vars['q']->value['nachname'];
}?>"></th>
            <th><input name="q[bundesland]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['aid'])) {
echo $_smarty_tpl->tpl_vars['q']->value['bundesland'];
}?>"></th>
            <th><input name="q[stadtname]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['aid'])) {
echo $_smarty_tpl->tpl_vars['q']->value['stadtname'];
}?>"></th>
            <th><input name="q[Wirtschaftseinheit]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['aid'])) {
echo $_smarty_tpl->tpl_vars['q']->value['Wirtschaftseinheit'];
}?>"></th>
            <th><input name="q[kostenstelle]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['aid'])) {
echo $_smarty_tpl->tpl_vars['q']->value['kostenstelle'];
}?>"></th>
            <th><input name="q[planonnr]" style="width:100%" value="<?php if (!empty($_smarty_tpl->tpl_vars['q']->value) && isset($_smarty_tpl->tpl_vars['q']->value['aid'])) {
echo $_smarty_tpl->tpl_vars['q']->value['planonnr'];
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
            <td><?php echo $_smarty_tpl->tpl_vars['item']->value['vorgangsnummer'];?>
</td>
            <td><?php if ($_smarty_tpl->tpl_vars['item']->value['antragsteller_gruppe'] != "kunde_report" || $_smarty_tpl->tpl_vars['item']->value['nachname'] == $_smarty_tpl->tpl_vars['item']->value['antragsteller_name']) {
echo $_smarty_tpl->tpl_vars['item']->value['nachname'];
} else { ?>!<?php echo $_smarty_tpl->tpl_vars['item']->value['antragsteller_name'];
}?></td>
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
</form>
<?php }
}
