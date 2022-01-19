<?php
/* Smarty version 3.1.34-dev-7, created on 2022-01-11 10:50:13
  from '/var/www/html/html/umzugsformular_attachments.tpl.read.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_61dd52d542a114_81462381',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '43a5b32506c4f315a9d77c18bee32ad5cf57ef2d' => 
    array (
      0 => '/var/www/html/html/umzugsformular_attachments.tpl.read.html',
      1 => 1639084702,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_61dd52d542a114_81462381 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/smarty3/plugins/modifier.truncate.php','function'=>'smarty_modifier_truncate',),));
?>

<style>
    #attachments_list ul.ulAttachements li {
        width:100%;
    }
    
    #attachments_list .row .col.fname {
        width:40%;
    }
    #attachments_list .row .col.fsize {
        width:20%;
    }
    #attachments_list .row .col.fdate {
        width:20%;
    }
    #attachments_list .row .col.ftarget {
        width:20%;
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
        background: url("images/attachment-icon2.png") left top no-repeat;
        padding-left:18px;
        box-sizing: border-box;
    }
</style>

<fieldset><legend><strong>Dateianhänge</strong></legend>
<div id="attachments_list" data-url="sites/umzugsantrag_attachements_list.php?aid=<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
" style="padding:5px;">

    <div class="row names<?php if (empty($_smarty_tpl->tpl_vars['UmzugsAnlagen']->value)) {?> hidden<?php }?>">
    <span class="col fname">Datei</span>
        <span class="col fsize">Gr&ouml;&szlig;e</span>
        <span class="col fdate">Upload vom</span>
        <span class="col ftarget">Target</span>
</div>

<ul class="ulAttachements row-values">
<?php if (!empty($_smarty_tpl->tpl_vars['UmzugsAnlagen']->value)) {
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['UmzugsAnlagen']->value, 'AT', false, NULL, 'ATList', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['AT']->value) {
?>

<li class="row values">
    <span class="col fname" title="<?php echo $_smarty_tpl->tpl_vars['AT']->value['titel'];?>
">
        <a href="<?php echo $_smarty_tpl->tpl_vars['AT']->value['datei_link'];?>
" target="_blank"><?php if ($_smarty_tpl->tpl_vars['AT']->value['titel']) {
echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['AT']->value['titel'],60,"...");
} else {
echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['AT']->value['dok_datei'],60,"...");
}?></a>
    </span>
    <span class="col fsize"><?php echo $_smarty_tpl->tpl_vars['AT']->value['datei_groesse'];?>
</span>
    <span class="col fdate"><?php echo $_smarty_tpl->tpl_vars['AT']->value['created'];?>
</span>
    <span class="col ftarget"><?php if (!empty($_smarty_tpl->tpl_vars['AT']->value['target'])) {
echo $_smarty_tpl->tpl_vars['AT']->value['target'];
}?></span>
</li>
<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
} else { ?>
<li class="none"><em>keine</em></li>
<?php }?>
</ul>
</div>
</fieldset>
<?php }
}
