<?php
/* Smarty version 3.1.34-dev-7, created on 2022-03-07 10:55:34
  from '/var/www/html/html/umzugsformular_leistungsauswahl.tpl.read.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_6225e4a6beb300_86192139',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '010060dc1ab07dd5e886b7e46b9585982e9eb53e' => 
    array (
      0 => '/var/www/html/html/umzugsformular_leistungsauswahl.tpl.read.html',
      1 => 1646312042,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6225e4a6beb300_86192139 (Smarty_Internal_Template $_smarty_tpl) {
if (!isset($_smarty_tpl->tpl_vars['show_menge_property']->value)) {
$_smarty_tpl->_assignInScope('show_menge_property', "0");
}
if (!isset($_smarty_tpl->tpl_vars['show_menge2_property']->value)) {
$_smarty_tpl->_assignInScope('show_menge2_property', "0");
}
if (!isset($_smarty_tpl->tpl_vars['show_menge_mertens']->value)) {
$_smarty_tpl->_assignInScope('show_menge_mertens', "0");
}
if (!isset($_smarty_tpl->tpl_vars['show_menge2_mertens']->value)) {
$_smarty_tpl->_assignInScope('show_menge2_mertens', "0");
}
if (!isset($_smarty_tpl->tpl_vars['show_unit1']->value)) {
$_smarty_tpl->_assignInScope('show_unit1', "0");
}
if (!isset($_smarty_tpl->tpl_vars['show_unit2']->value)) {
$_smarty_tpl->_assignInScope('show_unit2', "0");
}
if (!isset($_smarty_tpl->tpl_vars['PreiseAnzeigen']->value)) {
$_smarty_tpl->_assignInScope('PreiseAnzeigen', "0");
}
if (!isset($_smarty_tpl->tpl_vars['show_menge_property']->value)) {
$_smarty_tpl->_assignInScope('show_menge_property', "0");
}?>

<?php if (false) {?>umzugsformular_leistungsauswahl.tpl.read.html<br><?php }?>
<link rel="stylesheet" type="text/css" href="{WebRoot}/css/auftragsformular_leistungsauswahl.css"><style>

tr.row * {
    font-size:1rem;
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
.hideForUniper {
    display:none;
}
.MitarbeierItem.table-leistungen  .inputRowVon td,
.MitarbeierItem.table-leistungen  .inputRowZiel td,
.table-leistungen thead td,
.table-leistungen thead th,
.table-leistungen tbody td,
.table-leistungen td {
    padding-left:.5rem;
    padding-right: .5rem;
    vertical-align: top;
}

</style>
<?php if (count($_smarty_tpl->tpl_vars['Umzugsleistungen']->value)) {?>
<h2 style="margin:0;padding:0" data-src="html/umzugsformular/leistungsauswahl/tpl/read/html">Leistungen</h2>

<table class="MitarbeierItem table-leistungen" style="margin-top:0;padding-top:0;width:100%;">
    <thead>
        <tr>
            <td>Kategorie</td>
            <td>Leistung</td>
            <?php if ($_smarty_tpl->tpl_vars['show_menge_property']->value) {?>
            <td class="">Menge<span class=""> 1 DSD</span></td>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['show_unit1']->value) {?>
            <td class="">Einheit 1</td>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['show_menge2_property']->value) {?>
            <td class="">Menge 2 DSD</td>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['show_unit2']->value) {?>
            <td class="">Einheit 2</td>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['show_menge_mertens']->value) {?>
            <td class="">Menge1 M</td>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['show_menge2_mertens']->value) {?>
            <td class="">Menge2 M</td>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['PreiseAnzeigen']->value) {?>
            <td class="preis">Preis/Einh.</td>
            <td class="sum">Gesamt</td>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['umzugsstatus']->value == "abgeschlossen") {?>
            <td class="hideForUniper">Rekla</td>
            <?php }?>
        </tr>
    </thead>
    <tbody id="TblLeistungenBody">
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['Umzugsleistungen']->value, 'L', false, NULL, 'GList', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['L']->value) {
?>
        <?php if ($_smarty_tpl->tpl_vars['AS']->value['autocalc_ref_mengen'] && ($_smarty_tpl->tpl_vars['L']->value['kategorie_id'] == "18" || $_smarty_tpl->tpl_vars['L']->value['kategorie_id'] == "25")) {?>
            <?php continue 1;?>
        <?php }?>
                <tr class="row inputRowVon">
            <td class="ktg1"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['kategorie'], ENT_QUOTES, 'UTF-8', true);?>
</td>
            <td class="lstg">
                <?php if (!empty($_smarty_tpl->tpl_vars['L']->value['image'])) {?>
                    <div class="bild">
                    <img src="images/leistungskatalog/<?php echo $_smarty_tpl->tpl_vars['L']->value['image'];?>
" style="float:left;max-width:120px;max-height:120px;border:0;margin-right:1rem;margin-bottom:.5rem;">
                    </div>
                <?php }?>
                <div class="Bezeichnung"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['leistung'], ENT_QUOTES, 'UTF-8', true);?>
</div>
                <?php if (!empty($_smarty_tpl->tpl_vars['L']->value['Beschreibung'])) {?>
                    <div class="Beschreibung"><?php echo $_smarty_tpl->tpl_vars['L']->value['Beschreibung'];?>
</div>
                <?php }?>
                <?php if (!empty($_smarty_tpl->tpl_vars['L']->value['Farbe']) || !empty($_smarty_tpl->tpl_vars['L']->value['Groesse'])) {?>
                <div class="produkt_varianten">
                    <?php if (!empty($_smarty_tpl->tpl_vars['L']->value['Farbe'])) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['Farbe'], ENT_QUOTES, 'UTF-8', true);
}?>
                    <?php if (!empty($_smarty_tpl->tpl_vars['L']->value['Groesse'])) {
if (!empty($_smarty_tpl->tpl_vars['L']->value['Farbe'])) {?>, <?php }
echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['Groesse'], ENT_QUOTES, 'UTF-8', true);
}?>
                </div>
                <?php }?>
                <?php if (!empty($_smarty_tpl->tpl_vars['L']->value['produkt_link'])) {?>
                    <div class="produkt_link">
                        <a href="<?php echo $_smarty_tpl->tpl_vars['L']->value['produkt_link'];?>
" target="_PL<?php echo $_smarty_tpl->tpl_vars['L']->value['leistung_id'];?>
">mehr Infos</a>
                    </div>
                <?php }?>
            </td>
            <?php if ($_smarty_tpl->tpl_vars['show_menge_property']->value) {?>
            <td class="menge"><?php if (is_numeric($_smarty_tpl->tpl_vars['L']->value['menge_property'])) {
echo number_format(htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['menge_property'], ENT_QUOTES, 'UTF-8', true),0,",",".");
}?></td>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['show_unit1']->value) {?>
            <td class="unit"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['leistungseinheit'], ENT_QUOTES, 'UTF-8', true);?>
</td>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['show_menge2_property']->value) {?>
            <td class=""><?php if (is_numeric($_smarty_tpl->tpl_vars['L']->value['menge2_property'])) {
echo number_format(htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['menge2_property'], ENT_QUOTES, 'UTF-8', true),2,",",".");
}?></td>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['show_unit2']->value) {?>
            <td class="unit"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['leistungseinheit2'], ENT_QUOTES, 'UTF-8', true);?>
</td>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['show_menge_mertens']->value) {?>
            <td class="menge"><?php if (is_numeric($_smarty_tpl->tpl_vars['L']->value['menge_mertens'])) {
echo number_format(htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['menge_mertens'], ENT_QUOTES, 'UTF-8', true),2,",",".");
}?></td>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['show_menge2_mertens']->value) {?>
            <td class="menge"><?php if (is_numeric($_smarty_tpl->tpl_vars['L']->value['menge2_mertens'])) {
echo number_format(htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['menge2_mertens'], ENT_QUOTES, 'UTF-8', true),2,",",".");
}?></td>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['PreiseAnzeigen']->value) {?><td class="preis"><?php echo $_smarty_tpl->tpl_vars['L']->value['preis_pro_einheit'];?>
</td>
            <td class="sum"><?php if (is_numeric($_smarty_tpl->tpl_vars['L']->value['gesamtpreis'])) {
echo number_format(htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['gesamtpreis'], ENT_QUOTES, 'UTF-8', true),2,",",".");
}?></td>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['umzugsstatus']->value == "abgeschlossen") {?>
            <td class="hideForUniper">Rekla</td>
            <?php }?>
        </tr>
    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        <tr id="summary">
        <?php if ($_smarty_tpl->tpl_vars['PreiseAnzeigen']->value) {?>
            <td <?php if ($_smarty_tpl->tpl_vars['umzugsstatus']->value != "abgeschlossen") {?>
                colspan="11"
                <?php } else { ?>
                colspan="10"
            <?php }?>><span id="allsum" data-allsum="0"><?php echo number_format(htmlspecialchars($_smarty_tpl->tpl_vars['Gesamtsumme']->value, ENT_QUOTES, 'UTF-8', true),2,",",".");?>
</span><span style="margin-left:5px">&euro;</span></td>
        <?php }?>
        </tr>
    </tbody>
</table>
<?php } else { ?>
<strong>Leistungen:</strong> <em>Keine </em> <br>
<?php }?>

<?php }
}
