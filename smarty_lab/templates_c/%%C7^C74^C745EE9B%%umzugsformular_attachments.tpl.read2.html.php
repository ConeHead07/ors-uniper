<?php /* Smarty version 2.6.26, created on 2017-05-03 02:26:47
         compiled from umzugsformular_attachments.tpl.read2.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'truncate', 'umzugsformular_attachments.tpl.read2.html', 18, false),)), $this); ?>
<?php if (empty ( $this->_tpl_vars['noOuterBox'] )): ?>
<?php if (empty ( $this->_tpl_vars['noCss'] )): ?><link rel="stylesheet" type="text/css" href="<?php if (! empty ( $this->_tpl_vars['WebRoot'] )): ?><?php echo $this->_tpl_vars['WebRoot']; ?>
<?php endif; ?>css/umzugsformular_attachements.css" /><?php endif; ?>

<fieldset><legend><strong><?php if (! empty ( $this->_tpl_vars['internal'] )): ?>Interne <?php endif; ?>Dateianhänge</strong></legend>
	<div class="attachements_list" id="attachments<?php if (! empty ( $this->_tpl_vars['internal'] )): ?>_internal<?php endif; ?>_list" data-url="sites/umzugsantrag_attachements_list.php?aid=<?php echo $this->_tpl_vars['AS']['aid']; ?>
&internal=<?php echo $this->_tpl_vars['internal']; ?>
" style="padding:5px;">
	<?php endif; ?>
		<div class="row names<?php if (count ( $this->_tpl_vars['UmzugsAnlagen'] ) == 0): ?> hidden<?php endif; ?>">
			<span class="col fname">Datei</span>
			<span class="col fsize">Gr&ouml;&szlig;e</span>
			<span class="col fdate">Upload vom</span>
		</div>

		<ul class="ulAttachements row-values">

		<?php $_from = $this->_tpl_vars['UmzugsAnlagen']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['ATList'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['ATList']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['AT']):
        $this->_foreach['ATList']['iteration']++;
?>

			<li class="row values">
			 <span class="col fname" title="<?php echo $this->_tpl_vars['AT']['titel']; ?>
"><a href="<?php echo $this->_tpl_vars['AT']['datei_link']; ?>
" target="_blank"><?php if ($this->_tpl_vars['AT']['titel']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['AT']['titel'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 60, "...") : smarty_modifier_truncate($_tmp, 60, "...")); ?>
<?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['AT']['dok_datei'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 60, "...") : smarty_modifier_truncate($_tmp, 60, "...")); ?>
<?php endif; ?></a></span> 
			 <span class="col fsize"><?php echo $this->_tpl_vars['AT']['datei_groesse']; ?>
</span> 
			 <span class="col fdate"><?php echo $this->_tpl_vars['AT']['created']; ?>
</span>
			</li>
		<?php endforeach; else: ?>
			<li class="none"><em>keine</em></li>
		<?php endif; unset($_from); ?>
		</ul>
	<?php if (empty ( $this->_tpl_vars['noOuterBox'] )): ?>
	</div>
</fieldset>
<?php endif; ?>