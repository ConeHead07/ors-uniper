<?php /* Smarty version 2.6.26, created on 2015-12-10 22:16:06
         compiled from umzugsformular_leistungsauswahl.tpl.read.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'umzugsformular_leistungsauswahl.tpl.read.html', 43, false),array('modifier', 'number_format', 'umzugsformular_leistungsauswahl.tpl.read.html', 45, false),)), $this); ?>
<style>
<?php echo '
tr.row * {
    font-size:11px;
}
div.ktg1, div.lstg {
    min-height: 18px;
}
tr#summary td { 
    text-align:right;
    padding:5px;
    font-weight:bold;
}
td.unit {
    text-align:center;
}
td.menge, td.menge, input.menge, td.preis, td.sum {
    text-align:right;
}
'; ?>

</style>
<?php if (count ( $this->_tpl_vars['Umzugsleistungen'] )): ?>
<h2 style="margin:0;padding:0">Leistungen</h2>
<table class="MitarbeierItem" style="margin-top:0;padding-top:0;width:100%;">
    <thead>
        <tr>
            <td>Kategorie</td>
            <td>Leistung</td>
            <td>Menge 1 DSD</td>
            <td>Einheit 1</td>
            <td>Menge 2 DSD</td>
            <td>Einheit 2</td>
            <td>Menge1 M</td>
            <td>Menge2 M</td>
            <?php if ($this->_tpl_vars['PreiseAnzeigen']): ?><td class="preis">Preis/Einh.</td>
            <td class="sum">Gesamt</td>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody id="TblLeistungenBody">
    <?php $_from = $this->_tpl_vars['Umzugsleistungen']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['GList'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['GList']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['L']):
        $this->_foreach['GList']['iteration']++;
?>
        <tr class="row inputRowVon">
            <td class="ktg1"><?php echo ((is_array($_tmp=$this->_tpl_vars['L']['kategorie'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
            <td class="lstg"><?php echo ((is_array($_tmp=$this->_tpl_vars['L']['leistung'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
            <td><?php if (is_numeric ( $this->_tpl_vars['L']['menge_property'] )): ?><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['L']['menge_property'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : number_format($_tmp, 2, ",", ".")); ?>
<?php endif; ?></td>
            <td class="unit"><?php echo ((is_array($_tmp=$this->_tpl_vars['L']['leistungseinheit'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
            <td><?php if (is_numeric ( $this->_tpl_vars['L']['menge2_property'] )): ?><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['L']['menge2_property'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : number_format($_tmp, 2, ",", ".")); ?>
<?php endif; ?></td>
            <td class="unit"><?php echo ((is_array($_tmp=$this->_tpl_vars['L']['leistungseinheit2'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
            <td class="menge"><?php if (is_numeric ( $this->_tpl_vars['L']['menge_mertens'] )): ?><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['L']['menge_mertens'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : number_format($_tmp, 2, ",", ".")); ?>
<?php endif; ?></td>
            <td class="menge"><?php if (is_numeric ( $this->_tpl_vars['L']['menge2_mertens'] )): ?><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['L']['menge2_mertens'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : number_format($_tmp, 2, ",", ".")); ?>
<?php endif; ?></td>
            <?php if ($this->_tpl_vars['PreiseAnzeigen']): ?><td class="preis"><?php echo $this->_tpl_vars['L']['preis_pro_einheit']; ?>
</td>
            <td class="sum"><?php if (is_numeric ( $this->_tpl_vars['L']['gesamtpreis'] )): ?><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['L']['gesamtpreis'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : number_format($_tmp, 2, ",", ".")); ?>
<?php endif; ?></td>
            <?php endif; ?>
        </tr>
    <?php endforeach; endif; unset($_from); ?>
        <tr id="summary">
        <?php if ($this->_tpl_vars['PreiseAnzeigen']): ?>
            <td colspan="<?php if ($this->_tpl_vars['PreiseAnzeigen']): ?>11<?php else: ?>9<?php endif; ?>"><span id="allsum" data-allsum="0"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['Gesamtsumme'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : number_format($_tmp, 2, ",", ".")); ?>
</span><span style="margin-left:5px">&euro;</span></td>
        <?php endif; ?>
        </tr>
    </tbody>
</table>
<?php else: ?>
<strong>Leistungen:</strong> <em>Keine </em> <br>
<?php endif; ?>
<br>