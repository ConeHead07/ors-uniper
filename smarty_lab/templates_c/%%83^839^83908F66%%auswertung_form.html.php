<?php /* Smarty version 2.6.26, created on 2016-01-18 13:26:48
         compiled from auswertung_form.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'auswertung_form.html', 23, false),array('modifier', 'number_format', 'auswertung_form.html', 112, false),)), $this); ?>
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
<select onchange="document.forms['frmStat'].submit()" selected="<?php echo $this->_tpl_vars['kwvon']; ?>
" name="kwvon" style="border:0;font-weight:bold;font-size:12px;width:150px;background:none;">
<option value="">auswählen</option>
        <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['kw_options'],'selected' => $this->_tpl_vars['kwvon']), $this);?>

</select> bis 
<select onchange="document.forms['frmStat'].submit()" selected="<?php echo $this->_tpl_vars['kwbis']; ?>
" name="kwbis" style="border:0;font-weight:bold;font-size:12px;width:150px;background:none;">
<option value="">auswählen</option>
        <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['kw_options'],'selected' => $this->_tpl_vars['kwbis']), $this);?>

</select> 
<input style="margin-left:15px;" id="all" name="all" type="checkbox" value="1" <?php if ($this->_tpl_vars['all']): ?>checked="checked"<?php endif; ?>><label for="all"><span title="Auch bereits abgerechnete Leistungen anzeigen">Alle</span></label>
<input type="hidden" name="s" value="<?php echo $this->_tpl_vars['s']; ?>
">
<input type="hidden" name="order" value="" id="orderby">
<input type="hidden" name="queriedorder" value="<?php echo $this->_tpl_vars['order']; ?>
">
<input type="hidden" name="queriedodir" value="<?php echo $this->_tpl_vars['odir']; ?>
">
<noscript>&lt;input type="submit" value="Auswertung starten"&gt;</noscript>
</span>
<style type="text/css"><?php echo '
    th.order {
        cursor:pointer;
    }
'; ?>
</style>
<script><?php echo '
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
'; ?>

</script>

<table class="tblList">
    <thead>
        <tr>
            <th style="cursor:pointer" <?php echo '
                onclick="(function(){
                    $(\'input:checkbox[name^=aids]\').attr(\'checked\', !$(\'input:checkbox[name^=aids]:first\').is(\':checked\') );
            })()'; ?>
">x</th>
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
            <th><input name="q[aid]" style="width:100%" value="<?php echo $this->_tpl_vars['q']['aid']; ?>
"></th>
            <th><input name="q[vorgangsnummer]" style="width:100%" value="<?php echo $this->_tpl_vars['q']['vorgangsnummer']; ?>
"></th>
            <th><input name="q[nachname]" style="width:100%" value="<?php echo $this->_tpl_vars['q']['nachname']; ?>
"></th>
            <th><input name="q[bundesland]" style="width:100%" value="<?php echo $this->_tpl_vars['q']['bundesland']; ?>
"></th>
            <th><input name="q[stadtname]" style="width:100%" value="<?php echo $this->_tpl_vars['q']['stadtname']; ?>
"></th>
            <th><input name="q[Wirtschaftseinheit]" style="width:100%" value="<?php echo $this->_tpl_vars['q']['Wirtschaftseinheit']; ?>
"></th>
            <th><input name="q[kostenstelle]" style="width:100%" value="<?php echo $this->_tpl_vars['q']['kostenstelle']; ?>
"></th>
            <th><input name="q[planonnr]" style="width:100%" value="<?php echo $this->_tpl_vars['q']['planonnr']; ?>
"></th>
            <th><input name="q[umzugstermin]" style="width:100%" value="<?php echo $this->_tpl_vars['q']['umzugstermin']; ?>
"></th>
            <th><input name="q[abgeschlossen_am]" style="width:100%" value="<?php echo $this->_tpl_vars['q']['abgeschlossen_am']; ?>
"></th>
            <th><input name="q[berechnet_am]" style="width:100%" value="<?php echo $this->_tpl_vars['q']['berechnet_am']; ?>
"></th>
            <th><input name="q[summe]" style="width:100%" value="<?php echo $this->_tpl_vars['q']['summe']; ?>
"></th>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['Auftraege']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['AList'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['AList']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['AList']['iteration']++;
?>
    <tr>
        <td><?php if (! $this->_tpl_vars['item']['berechnet_am']): ?><input type="checkbox" name="aids[]" value="<?php echo $this->_tpl_vars['item']['aid']; ?>
"><?php endif; ?></td>
        <td><a href="?s=aantrag&id=<?php echo $this->_tpl_vars['item']['aid']; ?>
"><?php echo $this->_tpl_vars['item']['aid']; ?>
</a></td>
            <td><?php echo $this->_tpl_vars['item']['vorgangsnummer']; ?>
</td>
            <td><?php echo $this->_tpl_vars['item']['nachname']; ?>
</td>
            <td><?php echo $this->_tpl_vars['item']['bundesland']; ?>
</td>
            <td><?php echo $this->_tpl_vars['item']['stadtname']; ?>
, <?php echo $this->_tpl_vars['item']['adresse']; ?>
</td>
            <td><?php echo $this->_tpl_vars['item']['Wirtschaftseinheit']; ?>
</td>
            <td><?php echo $this->_tpl_vars['item']['kostenstelle']; ?>
</td>
            <td><?php echo $this->_tpl_vars['item']['planonnr']; ?>
</td>
            <td><?php echo $this->_tpl_vars['item']['umzugstermin']; ?>
</td>
            <td><?php echo $this->_tpl_vars['item']['abgeschlossen_am']; ?>
</td>
            <td><?php echo $this->_tpl_vars['item']['berechnet_am']; ?>
</td>
            <td style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['summe'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : number_format($_tmp, 2, ",", ".")); ?>
 &euro;</td>
        </tr>
        <?php endforeach; endif; unset($_from); ?>
    </tbody>
</table>
    <input type="text" name="wwsnr" placeholder="Vorgangsnummer" size="15" style="border:1px solid #666;width:180px;font-size:11px;" value="<?php echo $this->_tpl_vars['wwsnr']; ?>
">
<input type="submit" name="finish" value="Fertig stellen" 
       style="border-style:grove;border-width:1px;background-color:#666;color:#fff;font-weight:bold;">
</form>