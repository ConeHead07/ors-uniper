<?php
/* Smarty version 3.1.34-dev-7, created on 2021-12-14 08:55:30
  from '/var/www/html/html/admin_auswertung_tabs.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_61b84df26d5ce3_62971459',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2301d55f54380b4f8d14f3d0dd7fd429c775fa67' => 
    array (
      0 => '/var/www/html/html/admin_auswertung_tabs.html',
      1 => 1639468521,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_61b84df26d5ce3_62971459 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="divTabbedList" style="width:100%;">

    <ul>
        <?php if ($_smarty_tpl->tpl_vars['cat']->value == "auftrag") {?>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "auftrag") {?>class="activeTab"<?php }?>><a href="?s=aantraege&id=<?php echo $_smarty_tpl->tpl_vars['aid']->value;?>
&cat=auftrag&allusers=1&tabs=auswertung" style="width:90px">Auftrag &nbsp; #<?php echo $_smarty_tpl->tpl_vars['aid']->value;?>
</a></li>
        <?php }?>
        <?php if ($_smarty_tpl->tpl_vars['cat']->value == "tourenplanung") {?>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "tourenplanung") {?>class="activeTab"<?php }?>><a href="?s=tourenplanung&tabs=auswertung" style="width:90px">Tourenplanung</a></li>
        <?php }?>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "auswertung") {?>class="activeTab"<?php }?>><a href="?s=auswertung&tabs=auswertung" style="width:90px">Auswertung</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "abrechnung") {?>class="activeTab"<?php }?>><a href="?s=abrechnung&tabs=auswertung" style="width:90px">Abrechnung</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cat']->value == "suche") {?>class="activeTab"<?php }?>><a href="?s=umzugssuche&tabs=auswertung">Suche</a></li>
        <!-- <li><a href="#ID128586" style="width:60px">&nbsp;</a></li> -->
    </ul><br clear="all">
</div>
<?php }
}
