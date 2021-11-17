<?php
/* Smarty version 3.1.34-dev-7, created on 2021-11-11 01:20:53
  from '/var/www/html/uniper/htdocs/html/admin_antraege_tabs.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_618c61e56bbe40_16181469',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0b8e8f95389c96446bea93ebd34d323a564617e1' => 
    array (
      0 => '/var/www/html/uniper/htdocs/html/admin_antraege_tabs.html',
      1 => 1636590027,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_618c61e56bbe40_16181469 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="divTabbedList" style="width:100%;">

    <ul>
        <?php if ($_smarty_tpl->tpl_vars['cat']->value == "auftrag") {?>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "auftrag") {?>class="activeTab"<?php }?>><a xhref="?s=aantraege&id=<?php echo $_smarty_tpl->tpl_vars['aid']->value;?>
&cat=auftrag&allusers=1" style="width:90px">Auftrag &nbsp; #<?php echo $_smarty_tpl->tpl_vars['aid']->value;?>
</a></li>
        <?php }?>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "neue") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=neue<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>" style="width:90px">Bestellungen</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "angeboten") {?>class="activeTab"<?php }?> style="display:none"><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=angeboten<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>" style="width:125px">zur Genehmigung</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "aktive") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=aktive<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>">Aktive Aufträge</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "abgeschlossene") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=abgeschlossene<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>">Abgeschlossen</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "temp") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=temp<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>" style="display:none;">Tmp</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "zurueckgegeben") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=zurueckgegeben<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>" style="color:#ffd700;display:none;">Zur&uuml;ck</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "abgelehnte") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=abgelehnte<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>" style="color:#ffd700;display:none;">Abgelehnt</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "stornierte") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=stornierte<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>" style="color:#ffd700;display:none;">Storno</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "suche") {?>class="activeTab"<?php }?>><a href="?s=umzugssuche<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>">Suche</a></li>
        <!-- <li><a href="#ID128586" style="width:60px">&nbsp;</a></li> -->
    </ul><br clear="all">
</div>
<?php }
}
