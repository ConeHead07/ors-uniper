<?php
/* Smarty version 3.1.34-dev-7, created on 2021-12-15 14:23:31
  from '/var/www/html/html/umzugsteam_antraege_tabs.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_61b9ec53081d40_39547903',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'eb119414c9efc32265f3258489fca622c9d1a188' => 
    array (
      0 => '/var/www/html/html/umzugsteam_antraege_tabs.html',
      1 => 1639574591,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_61b9ec53081d40_39547903 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="divTabbedList" style="width:100%;">

    <ul>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "heute") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=heute<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>">Auslieferung heute</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "aktive") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=aktive<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>">Aktive Aufträge</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "abgeschlossene") {?>class="activeTab"<?php }?>><a href="?s=<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
&cat=abgeschlossene<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>" style="width:150px">Abgeschlossen</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "suche") {?>class="activeTab"<?php }?>><a href="?s=umzugssuche<?php if ($_smarty_tpl->tpl_vars['allusers']->value) {?>&allusers=1<?php }?>&top=auslieferung">Suche</a></li>
        <!-- <li><a href="#ID128586" style="width:60px">&nbsp;</a></li> -->
    </ul><br clear="all">
</div>
<?php }
}
