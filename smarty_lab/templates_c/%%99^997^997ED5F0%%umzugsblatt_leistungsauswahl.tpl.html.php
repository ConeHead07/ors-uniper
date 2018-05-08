<?php /* Smarty version 2.6.26, created on 2016-02-03 01:14:00
         compiled from umzugsblatt_leistungsauswahl.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'umzugsblatt_leistungsauswahl.tpl.html', 19, false),array('modifier', 'number_format', 'umzugsblatt_leistungsauswahl.tpl.html', 32, false),)), $this); ?>

<?php if (count ( $this->_tpl_vars['Umzugsleistungen'] )): ?>
<h2 style="margin:6px 0;padding:0">Leistungen</h2>
<table class="MitarbeierItem" border="1" cellpadding="1" cellspacing="0" style="width:100%;margin-top:0;padding-top:0;">
    <thead>
        <tr>
            <td><b>Kategorie</b></td>
            <td><b>Leistung</b></td>
            <td><b>Menge 1</b></td>
            <td><b>Einheit 1</b></td>
            <td><b>Menge 2</b></td>
            <td><b>Einheit 2</b></td>
            <?php if ($this->_tpl_vars['PreiseAnzeigen']): ?><td class="preis"><b>Preis/Einh.</b></td></tr><?php endif; ?>
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
&nbsp;</td>
            <td class="lstg"><?php echo ((is_array($_tmp=$this->_tpl_vars['L']['leistung'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&nbsp;</td>
            <td><?php echo ((is_array($_tmp=$this->_tpl_vars['L']['menge_mertens'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&nbsp;</td>
            <td class="unit"><?php echo ((is_array($_tmp=$this->_tpl_vars['L']['leistungseinheit'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&nbsp;</td>
            <td><?php echo ((is_array($_tmp=$this->_tpl_vars['L']['menge2_mertens'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&nbsp;</td>
            <td class="unit"><?php echo ((is_array($_tmp=$this->_tpl_vars['L']['leistungseinheit2'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&nbsp;</td>            
            <?php if ($this->_tpl_vars['PreiseAnzeigen']): ?><td><?php echo $this->_tpl_vars['L']['preis_pro_einheit']; ?>
&nbsp;</td></tr><?php endif; ?>
        </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
</table>
<table border="0" cellpadding="1" cellspacing="0" style="border:0;width:100%;margin-top:20px;padding-top:0;">
    <tr id="summary">
     <!-- <td colspan="<?php if ($this->_tpl_vars['PreiseAnzeigen']): ?>11<?php else: ?>9<?php endif; ?>" style="border:0;text-align: right;padding-right:80px;font-weight:bold;font-size:20px;"><span id="allsum" data-allsum="0">Gesamtsumme Netto: <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['Gesamtsumme'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : number_format($_tmp, 2, ",", ".")); ?>
</span><span style="margin-left:5px">&euro;</span></td> -->
    </tr>    
</table>
<?php else: ?>
<strong>Leistungen:</strong> <em>Keine </em> <br>
<?php endif; ?>
<br>