<?php /* Smarty version 2.6.26, created on 2016-01-18 10:35:04
         compiled from umzugsformular_attachments.tpl.read.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'truncate', 'umzugsformular_attachments.tpl.read.html', 76, false),)), $this); ?>
<?php echo '
<style>
    #attachments_list ul.ulAttachements li {
        width:100%;
    }
    
    #attachments_list .row .col.fname {
        width:50%;
        max-width:400px;
    }
    #attachments_list .row .col.fsize {
        width:20%;
        max-width:120px;
    }
    
    #attachments_list .row .col.fdate {
        width:20%;
        max-width:140px;
    }
    #attachments_list .row.names {
        background:#eee;
    }
    #attachments_list .row.names .col {
        font-weight:bold;
        color:#0075B5;
    }
    #attachments_list .row.names .col.fname {
        padding-left:4px;
    }
    
    #attachments_list .row {
        height:auto;
        overflow: hidden;
    }
    
    #attachments_list .row .col {
        display:inline-block;
        padding-bottom:5px;
        padding-top:3px;
    }
    .hidden {
        display:none;
    }
    
    #attachments_list .row.values {
        padding-top:4px;
    }
    #attachments_list .row.values:nth-child(2) {
        background:#fafafa;
    }
        
    .ulAttachements li {
        background: none;
        padding-left:0;
    }
    
    .ulAttachements .row.values .col.fname {
        background: url(images/attachment-icon2.png) left top no-repeat;
        padding-left:18px;
        box-sizing: border-box;
    }
</style>
'; ?>

<fieldset><legend><strong>Dateianhänge</strong></legend>
<div id="attachments_list" data-url="sites/umzugsantrag_attachements_list.php?aid=<?php echo $this->_tpl_vars['AS']['aid']; ?>
" style="padding:5px;">

    <div class="row names<?php if (count ( $this->_tpl_vars['UmzugsAnlagen'] ) == 0): ?> hidden<?php endif; ?>">
    <span class="col fname">Datei</span><span class="col fsize">Gr&ouml;&szlig;e</span><span class="col fdate">Upload vom</span>
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
<?php endforeach; endif; unset($_from); ?>
</ul>
</div>
</fieldset>
