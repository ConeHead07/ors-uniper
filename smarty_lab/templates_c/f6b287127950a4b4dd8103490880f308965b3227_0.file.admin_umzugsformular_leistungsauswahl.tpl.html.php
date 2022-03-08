<?php
/* Smarty version 3.1.34-dev-7, created on 2022-02-24 04:15:44
  from '/var/www/html/html/admin_umzugsformular_leistungsauswahl.tpl.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_621706708d52a7_55296038',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f6b287127950a4b4dd8103490880f308965b3227' => 
    array (
      0 => '/var/www/html/html/admin_umzugsformular_leistungsauswahl.tpl.html',
      1 => 1646312042,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_621706708d52a7_55296038 (Smarty_Internal_Template $_smarty_tpl) {
if (false) {?>admin_umzugsformular_leistungsauswahl.tpl.html<br><?php }
$_smarty_tpl->_assignInScope('colspan', 3);?>

<?php if (!isset($_smarty_tpl->tpl_vars['mengeReklasReadOnly']->value)) {
$_smarty_tpl->_assignInScope('mengeReklasReadOnly', 1);
}?>

<?php if (!isset($_smarty_tpl->tpl_vars['showAID']->value)) {
$_smarty_tpl->_assignInScope('showAID', 0);
}?>

<?php if ($_smarty_tpl->tpl_vars['PreiseAnzeigen']->value) {
$_smarty_tpl->_assignInScope('colspan', $_smarty_tpl->tpl_vars['colspan']->value+2);
}?>

<?php if (!empty($_smarty_tpl->tpl_vars['enableLeistungCheckbox']->value)) {
$_smarty_tpl->_assignInScope('colspan', $_smarty_tpl->tpl_vars['colspan']->value+1);
}?>

<?php if (!empty($_smarty_tpl->tpl_vars['addReklas']->value)) {
$_smarty_tpl->_assignInScope('colspan', $_smarty_tpl->tpl_vars['colspan']->value+1);
}
if (!empty($_smarty_tpl->tpl_vars['showReklas']->value)) {
$_smarty_tpl->_assignInScope('colspan', $_smarty_tpl->tpl_vars['colspan']->value+1);
}?>

<?php if (!empty($_smarty_tpl->tpl_vars['addTeilmengen']->value)) {
$_smarty_tpl->_assignInScope('colspan', $_smarty_tpl->tpl_vars['colspan']->value+1);
}?>

<?php if (!empty($_smarty_tpl->tpl_vars['showLiefermenge']->value)) {
$_smarty_tpl->_assignInScope('colspan', $_smarty_tpl->tpl_vars['colspan']->value+1);
}?>

<?php if (!empty($_smarty_tpl->tpl_vars['showAID']->value)) {
$_smarty_tpl->_assignInScope('colspan', $_smarty_tpl->tpl_vars['colspan']->value+1);
}?>

<?php if (!isset($_smarty_tpl->tpl_vars['showAddLeistung']->value)) {
$_smarty_tpl->_assignInScope('showAddLeistung', 0);
}?>

<?php if (!isset($_smarty_tpl->tpl_vars['showDropLeistung']->value)) {
$_smarty_tpl->_assignInScope('showDropLeistung', 0);
}?>

<?php $_smarty_tpl->_assignInScope('Gesamtsumme', "0");?>

<?php $_smarty_tpl->_assignInScope('uniqueId', uniqid());?>


<style>
    .table-leistungen .col.menge,
    .table-leistungen .col.field-menge_mertens,
    .table-leistungen .col.field-menge_rekla,
    .table-leistungen .col.field-menge_geliefert,
    .table-leistungen .col.field-add_rekla {
        max-width:7vh;
    }
    .table-leistungen thead th {
        font-size: 0.75rem;
    }
    .table-leistungen .row-checkbox,
    .table-leistungen .col.menge {
        vertical-align: middle;
    }
    .table-leistungen.MitarbeierItem .row.inputRowVon input {
        height:1.2em;
    }
    .table-leistungen td.with-input {
        padding:0;
    }

    table.table-leistungen .col.field-kategorie,
    table.table-leistungen th.col.field-kategorie,
    table.table-leistungen td.col.field-kategorie {
        width: 25%;
        max-widht: 250px;
        box-sizing: border-box;
        padding: 0;
    }
    table.table-leistungen .col.preis,
    table.table-leistungen .col.menge,
    table.table-leistungen .col.sum,
    table.table-leistungen th.col.preis,
    table.table-leistungen th.col.menge,
    table.table-leistungen th.col.sum,
    table.table-leistungen td.col.preis,
    table.table-leistungen td.col.menge,
    table.table-leistungen td.col.sum {
        width: 10%;
        max-widht: 120px;
        box-sizing: border-box;
        padding: 0;
        padding-right: 3px;
        text-align: right;
    }
    table.table-leistungen th.col.field-kategorie,
    table.table-leistungen th.col.field-leistung {
        text-align: left;
        padding-left: 3px;
    }


</style>

<div class="widget-leistungsauswahl" id="widgetLA_<?php echo $_smarty_tpl->tpl_vars['uniqueId']->value;?>
" data-src="admin/umzugsformular/leistungsauswahl/tpl/html" data-test="2" style="display:block;margin-top:15px;">
    <!-- admin_umzugsformular_leistungsauswahl.tpl.html -->
    <h2 style="margin:0"><?php if (empty($_smarty_tpl->tpl_vars['title']->value)) {?>Bestellte Leistungen<?php } else {
echo $_smarty_tpl->tpl_vars['title']->value;
}?></h2>
    <table class="table-leistungen MitarbeierItem" style="width:100%;">
        <thead>
            <tr>
                <?php if ($_smarty_tpl->tpl_vars['showDropLeistung']->value) {?>
                <th class="drop-leistung" style="width:14px;padding:0;"> X </th>
                <?php }?>

                <?php if (isset($_smarty_tpl->tpl_vars['enableLeistungCheckbox']->value) && !empty($_smarty_tpl->tpl_vars['enableLeistungCheckbox']->value)) {?>
                <th class="leistung-checkbox" style="width:14px;padding:0;"> [-] </th>
                <?php }?>

                <?php if (!empty($_smarty_tpl->tpl_vars['showAID']->value)) {?>
                <th class="col field-aid">AID</th>
                <?php }?>

                <th class="col field-kategorie">Kategorie</th>
                <th class="col field-leistung">Leistung</th>
                <th class="col field-menge_mertens menge">B-Menge</th>

                <?php if (!empty($_smarty_tpl->tpl_vars['showReklas']->value)) {?>
                <th class="col field-menge_rekla menge">R-Menge</th>
                <?php }?>

                <?php if (!empty($_smarty_tpl->tpl_vars['addReklas']->value)) {?>
                <th class="col field-add_rekla menge" style="background-color:red;color:#fff;">Neue Reklas</th>
                <?php }?>

                <?php if (!empty($_smarty_tpl->tpl_vars['showLiefermenge']->value)) {?>
                <th class="col field-menge_geliefert menge">Gelief</th>
                <?php }?>

                <?php if (!empty($_smarty_tpl->tpl_vars['addTeilmengen']->value)) {?>
                <th class="col field-add_teil menge" style="background-color:darkgreen;color:#fff;">T-Menge</th>
                <?php }?>

                <?php if ($_smarty_tpl->tpl_vars['PreiseAnzeigen']->value) {?>
                <th class="col col-preis field-preis_pro_einhei preis">Preis</th>
                <th class="col field-gesamtpreis sum">Gesamt</th>
                <?php }?>
            </tr>
        </thead>
        <tbody id="TblLeistungenBody">
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['Umzugsleistungen']->value, 'L', false, NULL, 'LList', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['L']->value) {
?>
            <tr class="row inputRowVon" data-lid="<?php echo $_smarty_tpl->tpl_vars['L']->value['leistung_id'];?>
" data-row="<?php echo htmlspecialchars(json_encode($_smarty_tpl->tpl_vars['L']->value), ENT_QUOTES, 'UTF-8', true);?>
">

                <?php if ($_smarty_tpl->tpl_vars['showDropLeistung']->value) {?>
                <td class="drop-leistung" style="padding:0;"><span style="cursor:pointer;margin:0;padding:0;"><img style="cursor:pointer;margin:0;padding:0;" align="absmiddle" src="images/loeschen_off.png" width="14" alt=""></span></td>
                <?php }?>

                <?php if (isset($_smarty_tpl->tpl_vars['enableLeistungCheckbox']->value) && !empty($_smarty_tpl->tpl_vars['enableLeistungCheckbox']->value)) {?>
                <td class="row-checkbox">EX:<?php echo $_smarty_tpl->tpl_vars['enableLeistungCheckbox']->value;?>

                    <input type="checkbox" name="chckLeistung[]" value="<?php echo $_smarty_tpl->tpl_vars['L']->value['leistung_id'];?>
">
                </td>
                <?php }?>

                <?php if (!empty($_smarty_tpl->tpl_vars['showAID']->value)) {?>
                <td class="col field-aid aid">
                    <a href="?s=aantrag&id=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['aid'], ENT_QUOTES, 'UTF-8', true);?>
" style="width:100%"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['aid'], ENT_QUOTES, 'UTF-8', true);?>
</a>
                </td>
                <?php }?>

                <td class="col field-kategorie ktg1"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['kategorie'], ENT_QUOTES, 'UTF-8', true);?>
</td>
                <td class="col field-leistung lstg" data-p="<?php echo $_smarty_tpl->tpl_vars['L']->value['preis_pro_einheit'];?>
" data-sum="<?php echo $_smarty_tpl->tpl_vars['L']->value['gesamtpreis'];?>
">
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

                <td class="col field-mertens_menge menge<?php if (empty($_smarty_tpl->tpl_vars['mengeMertensReadOnly']->value)) {?> with-input<?php }?>"><?php if (!empty($_smarty_tpl->tpl_vars['mengeMertensReadOnly']->value)) {?>
                    <?php echo number_format($_smarty_tpl->tpl_vars['L']->value['menge_mertens'],2,",",".");
} else { ?>
                    <input class="menge creator auftragsmenge" name="L[menge_mertens][]" value="<?php echo $_smarty_tpl->tpl_vars['L']->value['menge_mertens'];?>
" type="number">
                    <input class="menge2 creator" name="L[menge2_mertens][]" value="<?php echo number_format($_smarty_tpl->tpl_vars['L']->value['menge2_mertens'],2,',','.');?>
" type="hidden"><input class="ilstg" name="L[leistung_id][]" value="<?php echo $_smarty_tpl->tpl_vars['L']->value['leistung_id'];?>
" type="hidden">
                <?php }
if ($_smarty_tpl->tpl_vars['showAddLeistung']->value) {?>
                        <input type="hidden" name="L[Bezeicnung][]" value="">
                        <?php if ($_smarty_tpl->tpl_vars['PreiseAnzeigen']->value) {?>
                        <input type="hidden" name="L[preis_pro_einheit][]" value="">
                        <?php }?>
                    <?php }?></td>

                <?php if (!empty($_smarty_tpl->tpl_vars['showReklas']->value)) {?>
                <td class="col field-menge_rekla menge<?php if (empty($_smarty_tpl->tpl_vars['mengeReklasReadOnly']->value)) {?> with-input<?php }?>"><?php if (!empty($_smarty_tpl->tpl_vars['mengeReklasReadOnly']->value)) {?>
                    <?php echo number_format($_smarty_tpl->tpl_vars['L']->value['menge_rekla'],2,",",".");
} else { ?>
                    <input class="menge" name="L[menge_rekla][<?php echo $_smarty_tpl->tpl_vars['L']->value['leistung_id'];?>
]" value="<?php echo number_format($_smarty_tpl->tpl_vars['L']->value['menge_rekla'],2,',','.');?>
" type="number">
                <?php }?>
                </td>
                <?php }?>

                <?php if (!empty($_smarty_tpl->tpl_vars['addReklas']->value)) {?>
                <td class="col field-add_rekla menge"><input style="color:red" class="menge" name="L[neue_rekla][<?php echo $_smarty_tpl->tpl_vars['L']->value['leistung_id'];?>
]" value="0" type="number" step="1" min="0" max="<?php echo $_smarty_tpl->tpl_vars['L']->value['menge_mertens'];?>
"></td>
                <?php }?>

                <?php if (!empty($_smarty_tpl->tpl_vars['showLiefermenge']->value)) {?>
                <td class="col field-menge_geliefert menge<?php if (empty($_smarty_tpl->tpl_vars['mengeGeliefertReadOnly']->value)) {?> with-input<?php }?>"><?php if (!empty($_smarty_tpl->tpl_vars['mengeGeliefertReadOnly']->value)) {?>
                    <?php echo number_format($_smarty_tpl->tpl_vars['L']->value['menge_geliefert'],2,",",".");
} else { ?>
                    <input class="menge" name="L[menge_geliefert][<?php echo $_smarty_tpl->tpl_vars['L']->value['leistung_id'];?>
]" value="<?php echo number_format($_smarty_tpl->tpl_vars['L']->value['menge_geliefert'],2,',','.');?>
" type="text">
                    <?php }?></td>
                <?php }?>

                <?php if (!empty($_smarty_tpl->tpl_vars['addTeilmengen']->value)) {?>
                <td class="col field-add_teil menge"><input style="color:darkgreen" class="menge" name="L[neue_teilmenge][<?php echo $_smarty_tpl->tpl_vars['L']->value['leistung_id'];?>
]" value="0" type="number" step="1" min="0" max="<?php echo $_smarty_tpl->tpl_vars['L']->value['menge_mertens'];?>
"></td>
                <?php }?>

                <?php if ($_smarty_tpl->tpl_vars['PreiseAnzeigen']->value) {?>
                <td class="col field-preis_pro_einheit preis"><?php if ($_smarty_tpl->tpl_vars['L']->value['preis_pro_einheit']) {
echo number_format($_smarty_tpl->tpl_vars['L']->value['preis_pro_einheit'],2,",",".");?>
 €<?php }?></td>
                <td class="col field-gesamtpreis sum"><?php if (is_numeric($_smarty_tpl->tpl_vars['L']->value['gesamtpreis'])) {
echo number_format($_smarty_tpl->tpl_vars['L']->value['gesamtpreis'],2,",",".");?>
 €<?php }?></td>
                <?php $_smarty_tpl->_assignInScope('Gesamtsumme', $_smarty_tpl->tpl_vars['Gesamtsumme']->value+$_smarty_tpl->tpl_vars['L']->value['gesamtpreis']);?>
                <?php }?>
            </tr>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            <tr id="summary">
                <?php if ($_smarty_tpl->tpl_vars['showAddLeistung']->value || $_smarty_tpl->tpl_vars['PreiseAnzeigen']->value) {?>
                <td colspan="<?php echo $_smarty_tpl->tpl_vars['colspan']->value;?>
">

                    <?php if ($_smarty_tpl->tpl_vars['showAddLeistung']->value) {?>
                    <span style="float:left;margin-bottom:2px;color:#549e1a;font-weight:bold;text-decoration:none;cursor:pointer;"
                          class="btn-add-leistung" data-test="3">
                    Leistung hinzuf&uuml;gen <img align="absmiddle" src="images/hinzufuegen_off.png" width="14" alt=""></span>
                    <?php }?>
                    <?php if ($_smarty_tpl->tpl_vars['PreiseAnzeigen']->value) {?>
                    <div style="display:inline-block;float:right">
                    <span id="allsum" data-allsum="0"><?php echo number_format(htmlspecialchars($_smarty_tpl->tpl_vars['Gesamtsumme']->value, ENT_QUOTES, 'UTF-8', true),2,",",".");?>
</span>
                    <span style="margin-left:5px">&euro;</span>
                    </div>
                    <?php }?>
                    <span style="clear: both"></span>
                </td>
                <?php } else { ?>
                <td colspan="<?php echo $_smarty_tpl->tpl_vars['colspan']->value;?>
" style="display:none;"></td>
                <?php }?>
            </tr>
        </tbody>
    </table>

    <table id="TplLeistungTable" class="tpl-leistungen tpl-leistungen-<?php echo $_smarty_tpl->tpl_vars['uniqueId']->value;?>
" style="display:none;">
        <tr class="row inputRowVon">
            <?php if ($_smarty_tpl->tpl_vars['showDropLeistung']->value) {?>
            <td class="drop-leistung" style="padding:0;"><span style="cursor:pointer;margin:0;padding:0;"><img style="cursor:pointer;margin:0;padding:0;" align="absmiddle" src="images/loeschen_off.png" width="14" alt=""/></span></td>
            <?php }?>

            <?php if (isset($_smarty_tpl->tpl_vars['enableLeistungCheckbox']->value) && !empty($_smarty_tpl->tpl_vars['enableLeistungCheckbox']->value)) {?>
            <td class="leistung-checkbox" class="row-checkbox">EC:<?php echo $_smarty_tpl->tpl_vars['enableLeistungCheckbox']->value;?>

                <input type="checkbox" name="chckLeistung[]" value="<?php echo $_smarty_tpl->tpl_vars['L']->value['leistung_id'];?>
">
            </td>
            <?php }?>

            <?php if (!empty($_smarty_tpl->tpl_vars['showAID']->value)) {?>
            <td class="col field-aid aid">
                <a href="?s=aantrag&id=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['aid'], ENT_QUOTES, 'UTF-8', true);?>
" style="width:100%"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['L']->value['aid'], ENT_QUOTES, 'UTF-8', true);?>
</a>
            </td>
            <?php }?>

            <td class="col field-kategorie ktg1"></td>
            <td class="col field-leistung lstg" data-p="" data-sum=""></td>

            <td class="col field-mertens_menge menge<?php if (empty($_smarty_tpl->tpl_vars['mengeMertensReadOnly']->value)) {?> with-input<?php }?>">
                <input class="menge creator" name="L[menge_mertens][]" value="1" min="0" type="number">
                <input class="menge2 creator" name="L[menge2_mertens][]" value="1" type="hidden">
                <input class="ilstg" name="L[leistung_id][]" value="" type="hidden"><?php if ($_smarty_tpl->tpl_vars['showAddLeistung']->value) {?>
                <input type="hidden" name="L[Bezeicnung][]" value="">
                <?php if ($_smarty_tpl->tpl_vars['PreiseAnzeigen']->value) {?>
                <input type="hidden" name="L[preis_pro_einheit][]" value="">
                <?php }?>
                <?php }?></td>

            <?php if (!empty($_smarty_tpl->tpl_vars['showReklas']->value)) {?>
            <td class="col field-menge_rekla menge<?php if (empty($_smarty_tpl->tpl_vars['mengeReklasReadOnly']->value)) {?> with-input<?php }?>">
                <input class="menge" name="L[menge_rekla][<?php echo $_smarty_tpl->tpl_vars['L']->value['leistung_id'];?>
]" value="0" type="number">
            </td>
            <?php }?>

            <?php if (!empty($_smarty_tpl->tpl_vars['addReklas']->value)) {?>
            <td class="col field-add_rekla menge"></td>
            <?php }?>

            <?php if (!empty($_smarty_tpl->tpl_vars['showLiefermenge']->value)) {?>
            <td class="col field-menge_geliefert menge<?php if (empty($_smarty_tpl->tpl_vars['mengeGeliefertReadOnly']->value)) {?> with-input<?php }?>"></td>
            <?php }?>

            <?php if (!empty($_smarty_tpl->tpl_vars['addTeilmengen']->value)) {?>
            <td class="col field-add_teil menge"></td>
            <?php }?>

            <?php if ($_smarty_tpl->tpl_vars['PreiseAnzeigen']->value) {?>
            <td class="col field-preis_pro_einheit preis">0 €</td>
            <td class="col field-gesamtpreis sum">0 € </td>
            <?php }?>
        </tr>
    </table>
    <input type="hidden" name="AS[leistungen_csv]" value="">
</div>

<?php echo '<script'; ?>
>
    $(function(){
        var lkItems = <?php echo $_smarty_tpl->tpl_vars['lkTreeItemsJson']->value;?>
;
        var lkmById = <?php echo $_smarty_tpl->tpl_vars['lkmByIdJson']->value;?>
;
        var widgetId = "widgetLA_<?php echo $_smarty_tpl->tpl_vars['uniqueId']->value;?>
";
        var widget = $("#" + widgetId);
        var lktgselectId = "widgetLA_<?php echo $_smarty_tpl->tpl_vars['uniqueId']->value;?>
_lktgselect";
        var ldescselectId = "widgetLA_<?php echo $_smarty_tpl->tpl_vars['uniqueId']->value;?>
_ldescselect";
        var tplLeistungenClass = "tpl-leistungen-<?php echo $_smarty_tpl->tpl_vars['uniqueId']->value;?>
";

        
        var lktgselect = $("<select/>" )
            .css({width:"500px",position:"absolute", zIndex:99, minWidth:250})
            .attr({id: lktgselectId, size:5})
            .hide()
            .appendTo("body");

        var ldescselect = $("<select/>" )
            .css({width:"500px",position:"absolute", zIndex:100, minWidth:280})
            .attr({id: ldescselectId, size:5})
            .hide()
            .appendTo( "body");

        var blurBox = function(callback, hide) {
            var blurBoxId = "BlurBox1";
            var body = $("body");

            if ( !$("#"+blurBoxId).length ) {
                body.append( $("<div/>").attr({id:blurBoxId}).css({position:"fixed",top:0,left:0,zIndex:98,display:"none"}) );
            }

            if (arguments.length > 1 && hide) {
                var bb = $("#" + blurBoxId).hide();
                return bb;
            }

            $("#" + blurBoxId)
                .css({width:"100%",minWidth:body.width(),height:"100%",minHeight:body.height()})
                .show()
                .click(function(e){
                    $( this ).hide();
                    if (typeof(callback) === "function") {
                        callback.call();
                    }
                    else {
                        if (callback) $( callback ).hide();
                    }
                });
        }

        var get_Kategorie = function() {
            obj = this;

            lktgselect
                .html(
                    $("<option/>").text("Bitte auswählen")
                );

            for(var ktg1 in lkItems) {
                var lNames = Object.keys(lkItems[ktg1]);
                var lArt = "";
                var ktgId = 0;
                for (var k in lkItems[ktg1]) {
                    if (lkItems[ktg1][k] && lkItems[ktg1][k].leistungsart) {
                        lArt = lkItems[ktg1][k].leistungsart;
                    }
                    if (lkItems[ktg1][k] && lkItems[ktg1][k].kategorie_id) {
                        ktgId = lkItems[ktg1][k].kategorie_id;
                    }
                }
                // alert(JSON.stringify({ ktg1, lArt, lNames, "lkItems[ktg1]": lkItems[ktg1], lkItems }))

                var opt = $("<option/>")
                    .val(ktg1)
                    .text(ktg1)
                    .data("kategorie1", ktg1)
                    .data("leistungsart", lArt)
                    .data("kategorie_id", ktgId)
                    .data("leistungen", lkItems[ktg1]);
                lktgselect.append( opt );
                // alert(JSON.stringify({ "opt.data()": opt.data() }));
            }

            lktgselect
                .unbind("change")
                .change(function(e) {
                    $( obj ).data(
                        "leistungsart",
                        $(this).find("option:selected").data("leistungsart")
                    );

                    $( obj ).data(
                        "kategorie1",
                        $(this).find("option:selected").data("kategorie1")
                    );

                    $( obj ).data(
                        "kategorie_id",
                        $(this).find("option:selected").data("kategorie_id")
                    );

                    $( obj ).data(
                        "leistungen",
                        $.extend({}, $(this).find("option:selected").data("leistungen"))
                    );

                    $( this ).hide();

                    get_Leistung.apply( $( obj ).closest("tr").find(".lstg").val("") );
                })
                .bind("blur", function(event){
                    $( this ).hide();
                })
                .css({
                    top: $(obj).offset().top + $(obj).height(),
                    left: $(obj).offset().left
                })
                .show();

            console.log("#140");
            blurBox( "#" + lktgselectId );
        }

        function lstgIsAllreadyInList(id, obj) {
            var widget = $( obj ).closest(".widget-leistungsauswahl");
            console.log("#148");
            var re = 0;
            widget.find("input.ilstg").not(obj).each(function(i){
                if ($(this).val() == id) re = 1;
            });
            return re;
        }

        function get_Leistung( obj ) {
            obj = this;
            console.log("#157");
            var row = $( obj ).closest("tr");
            console.log("tr.data(doNotOpenOnFocus)", row.data("doNotOpenOnFocus"));
            if (row.data("doNotOpenOnFocus")) {
                return;
            }
            var objKtg1 = row.find(".ktg1");
            var objUnit = row.find(".unit:eq(0)");
            var objUnit2 = row.find(".unit:eq(1)");
            var objInpAll = row.find("input");
            var objInp = row.find("input.ilstg");
            var objInpM = row.find("input.menge:not([readonly]):not(.menge2)");
            var objInpM2 = row.find("input.menge2.creator");
            var objPreis = row.find("td.preis");
            var objSum = row.find("td.sum");

            if ( !$( objKtg1 ).data("kategorie1") ||  !$( objKtg1 ).data("leistungen") ) {
                get_Kategorie.apply( objKtg1 );
                return;
            };

            var kategorie = $(objKtg1).data( "kategorie1" );
            var leistungen = $( objKtg1 ).data( "leistungen" );
            var leistungsart = $( objKtg1 ).data( "leistungsart" );
            var kategorie_id = $( objKtg1 ).data( "kategorie_id" );
            var IstIndividuellesAngebot = leistungsart === "Angebot";
            obj.data('kategorie_id', kategorie_id);

            console.log("#174");

            ldescselect.data("leistungen", leistungen).html("");
            ldescselect.append($("<option/>").text("Bitte auswählen"));
            if (leistungsart === "Angebot") {
                ldescselect.append(
                    $("<option/>")
                        .val("+")
                        .text("Neues Angebot anlegen")
                        .data('leistung', {
                            leistung_id: 0,
                            kategorie_id,
                            Bezeichnung: '',
                            Farbe: '',
                            Groesse: '',
                            leistung: '',
                            preis_pro_einheit: 1,
                            menge_mertens: 1,
                            menge2_mertens: 1,
                            menge_property: 1,
                            menge2_property: 1,
                            leistungseinheit: 'St.',
                            leistungseinheit2: '',
                        })
                );
            }

            console.log("#179");
            for(var lstg in leistungen) {
                var lprops = leistungen[lstg];
                var optTxt = lprops.Bezeichnung;
                if ("Farbe" in lprops && typeof lprops.Farbe === 'string' && lprops.Farbe.length > 0) {
                    optTxt += ', ' + lprops.Farbe;
                }
                if ("Groesse" in lprops && typeof lprops.Groesse === 'string' && lprops.Groesse.length > 0) {
                    optTxt += ', ' + lprops.Groesse;
                }
                console.log('ldescselect option ', { lstg, lprops, optTxt });
                ldescselect.append(
                    $("<option/>")
                        .val(lstg)
                        .text(lstg)
                        .data("leistung", lprops)
                );
            }

            console.log("#190");
            ldescselect
                .unbind("change")
                .change(function(event){
                    var data = $(this)
                        .find("option:selected")
                        .data("leistung");

                    row.data("leistung", data);
                    row.data("kategorie", {
                        kategorie,
                        leistungsart,
                        kategorie_id,
                    });

                    row.toggleClass("ist-angebot", leistungsart === "Angebot");
                    row.toggleClass("leistung-exists", data.leistung_id && +data.leistung_id > 0);

                    console.log("#468");
                    var oldMenge = (objInpM.length && objInpM.val()) ? +objInpM.val() : 0;
                    var newMenge = oldMenge > 0 ? oldMenge : 1;

                    console.log("#471");
                    var IstNeuesAngebot = IstIndividuellesAngebot && !data.leistung_id;

                    if (!IstNeuesAngebot && lstgIsAllreadyInList(data.leistung_id, objInp) ) {
                        console.log("#477");
                        console.log("Die Leistung wurde bereits in die Liste aufgenommen!\n" +
                            "Bitte fügen Sie die Mengen dem bestehenden Eintrag hinzu.\n"+
                            "Andernfalls werden bestehende Mengen überschrieben."
                        );
                        objInp.blur();
                        return false;
                    }

                    console.log("85");
                    $( objKtg1 )
                        .html( $( objKtg1)
                            .data( "kategorie1") );

                    objInpAll.val("");
                    $( objInp ).val( data.leistung_id );
                    $( objUnit ).html( data.leistungseinheit );
                    $( objUnit2 ).html( data.leistungseinheit2 );
                    objSum.html("");


                    if (data.leistungseinheit2) {
                        objInpM2.val("1");
                        objInpM2.removeAttr("readonly");
                        objInpM2.unbind("change").bind("change", mengeChangeCallback);
                    } else {
                        objInpM2.val("");
                        objInpM2.attr("readonly", "readonly");
                        objInpM2.unbind("change");
                    }

                    objInpM.unbind("change");
                    $( obj )
                        .html( data.leistung )
                        .attr({"data-p": data.preis_pro_einheit, "data-sum":0});

                    if (!objUnit.html() || objUnit.html().toLowerCase().indexOf("prozent") < 0) {
                        objInpM.bind("change", mengeChangeCallback);
                    }

                    if (!IstIndividuellesAngebot) {
                        $( obj )
                            .toggleClass("with-input", false)
                            .html( data.leistung );

                        objPreis
                            .toggleClass("with-input", false)
                            .html( numberFormat(data.preis_pro_einheit, 2, ",") );

                        if ( objInpM.length) {
                            console.log("#255");
                            objInpM.val(newMenge).get(0).focus();
                            objInpM.trigger("change");
                        }

                    } else {
                        var sLeistungTxt = data.leistung;
                        var iLeistungMng = data.menge_mertens;

                        row.data("doNotOpenOnFocus", 1);
                        $( obj )
                            .toggleClass("with-input", true)
                            .html("")
                            .append(
                          $("<input/>")
                              .attr({
                                  name: "L[Bezeichnung][]",
                              })
                              .css({ height: "100%" })
                              .addClass("Bezeichnung")
                              .val( sLeistungTxt )
                              .bind("change input", function(e) {
                                  data.leistung = $(this).val();
                                  data.Bezeichnung = $(this).val();
                              })
                              .bind("focus", function(e) {
                                  var trV = $(this).closest("tr");
                                  trV.data("doNotOpenOnFocus", 1);
                                  var trVData = trV.data();
                                  console.log({trV, trVData, "trV.data()": trV.data() });
                                  e.preventDefault();
                                  e.stopPropagation();
                              })
                              .bind("blur", function() {
                                  console.log("input blur");
                                  $(this).closest("tr").data("doNotOpenOnFocus", 0);
                              })
                        );
                        objInpM.val(iLeistungMng);

                        objPreis
                            .toggleClass("with-input", true)
                            .html("")
                            .append(
                            $("<input/>")
                                .attr({
                                    type: "number",
                                    name: "L[preis_pro_einheit][]",
                                    min: 0
                                })
                                .css({ height: "100%" })
                                .addClass("menge preis preis_pro_einheit")
                                .val( 1 )
                                .bind("change", function(e) {
                                    $( obj ).attr({"data-p": this.value.replace(",", ".") });
                                    data.preis_pro_einheit = this.value.replace(",", ".");
                                    mengeChangeCallback.apply(this);
                                })
                        );

                        objInpM.trigger("change");

                    }

                    console.log("#552");
                    $( this ).hide();
                    console.log("#554");
                    blurBox( "#" + ldescselectId, 1);

                })
                .bind("blur", function(event){
                    $( this ).hide();
                })
                .css({
                    top: $(obj).offset().top + $(obj).height(),
                    left: $(obj).offset().left
                })
                .show();
            console.log("#269");
            blurBox( "#" + ldescselectId );
            console.log("#258");
        }

        function calc_AllSum() {
            widget.find("#TblLeistungenBody").each(function(){
                var allsum = 0;
                $(this).find("td.lstg").each(function(){
                    allsum+= parseFloat($(this).attr("data-sum"));
                });
                $(this).find("#allsum").attr("data-allsum",allsum).text(numberFormat(allsum,2, ",", "."));
            });
        }

        var numberFormat = function(number, dec, decToken, thousandSep) {
            console.log("#272");
            var n = parseFloat(number).toFixed(dec).split(".");
            if (n[0].length > 3 && thousandSep) {
                var nr = n[0];
                var nnr= "";
                for(var i = 0; i < nr.length; ++i) {
                    nnr+= nr.charAt(i);
                    if (nr.length-(i+1) > 0 && (nr.length-(i+1)) % 3 === 0) nnr+=".";
                }
                n[0] = nnr;
            }
            return n.join(decToken);
        }

        var mengeChangeCallback = function() {
            console.log("#287");
            var $this = this;
            var tr = $( this ).closest("tr");
            var objUnit = tr.find(".unit:eq(0)");
            if (objUnit.html() && objUnit.html().toLowerCase().indexOf("prozent") > -1) {
                return;
            }

            var selVal1 = "input.menge.creator";
            var selVal2 = "input.menge2.creator";
            var id = tr.find("input.ilstg").val();
            var preis = parseFloat(tr.find("td.lstg").attr("data-p"));
            console.log({
                'function': "mengeChangeCallback",
                "$this": $this,
                tr,
                selVal1,
                selVal2,
                "tr.find(selVal1).length": tr.find(selVal1).length,
                "tr.find(selVal2).length": tr.find(selVal2).length
            });
            var val1  = parseFloat(tr.find("input.menge.creator").val().replace(',', '.'));
            var val2 = parseFloat(tr.find("input.menge2.creator").val().replace(',', '.')) || 1;
            var dataLeistung = tr.data("leistung");
            if (tr.find("input.menge.creator").length) {
                dataLeistung.menge_mertens = val1;
            }
            if (tr.find("input.menge2.creator").length) {
                dataLeistung.menge2_mertens = val2;
            }
            var mx = lkmById[ id ] || [];
            for(var i = 0; i < mx.length; ++i) {
                if ((1*mx[i].von) <= val1 && ( (1*mx[i].bis) < 0.01 || (1*mx[i].bis) >= val1) ) {
                    preis = mx[i].preis;
                    tr.find("td.preis").html( numberFormat(preis, 2, ",", ".") );
                    break;
                }
            }
            tr.find("td.lstg").attr("data-sum", preis * val1 * val2);
            tr.find("td.sum").html( numberFormat(preis * val1 * val2, 2, ",", ".") );
            calc_AllSum();
            return false;
        };

        function drop_Leistung(obj) {
            if (typeof obj === 'undefined') {
                obj = this;
            }
            $( obj ).closest( "tr" ).remove();
            leistungenRowsChanged = true;
            calc_AllSum();
            callbackLeistungenChanged();
        }

        $(function(){
            $( "input.menge.creator, input.menge2.creator" ).bind("change", mengeChangeCallback);

            $("input[type=number][max]").bind('change', function(e) {
                var val = $(this).val();
                var maxVal = $(this).attr("max");
                var minVal = $(this).attr("min");

                if (isNaN(val)) {
                    $(this).val(0);
                    return;
                }

                if (maxVal !== undefined && !isNaN(maxVal)) {
                    if (parseFloat(val) > parseFloat(maxVal)) {
                        $(this).val(maxVal);
                        return;
                    }
                }

                if (minVal !== undefined && !isNaN(minVal)) {
                    if (parseFloat(val) < parseFloat(minVal)) {
                        $(this).val(minVal);
                        return;
                    }
                }

            });
        });

        function add_Leistung(e) {
            var selTblBody = "#TblLeistungenBody";
            var selTplRow = ".tpl-leistungen." + tplLeistungenClass + " .row";

            console.log("#597 executing add_Leistung", {
                widget,
                selTblBody,
                selTplRow,
                "widget.length": widget.length,
                "widget.TblLeistungenBody length": widget.find(selTblBody).length,
                "selTplRow length": $(selTplRow).length,
                e
            });
            if (!widget.find(selTblBody).length ) return false;
            if (!$(selTplRow).length) return false;

            var l = $(selTplRow).clone(true);
            l.find("input:disabled").each(function(){
                this.disabled = false;
            }); //.prop("disabled", false);

            var lastSumRowSel = "#TblLeistungenBody tr#summary";
            var lastSumRow = widget.find(lastSumRowSel);
            if (lastSumRow.length) {
                lastSumRow.before(l);

                l.find(".col.ktg1").unbind("click").bind("click", get_Kategorie.bind(l.find(".col.ktg1").get(0) ) );
                l.find(".col.lstg").unbind("click").bind("click", get_Leistung.bind(l.find(".col.lstg").get(0) ));
                l.find(".drop-leistung span").unbind("click").bind("click", drop_Leistung.bind(l.find(".drop-leistung span").get(0) ));

                l.find("td input").unbind("change").bind("change", callbackLeistungenChanged);
                $(".ktg1", l).trigger("click");
            } else {
                alert("lastSumRow not! found widget.find(" + lastSumRowSel + ")");
            }
            console.log("#345");
        }

        var leistungenOnLoad = {};
        var leistungenChangedFromP = true;
        var leistungenChanged = true;
        var leistungenRowsChanged = false;

        var callbackLeistungenChanged = function() {
            var b = widget.find("#btnStatGenJa");
            if (!b.attr("data-reCheckLabel")) return;
            b.val( getLeistungenChanged() ? b.attr("data-reCheckLabel") : b.attr("data-sendLabel") );
        };

        var getLeistungenChanged = function() {
            var changed = false;
            var leistungen = getLeistungenFormData();
            console.log("leistungen", leistungen);
            console.log("leistungenOnLoad", leistungenOnLoad);

            for(var j in leistungenOnLoad) {
                if (!(j in leistungen)) return true;
            }

            for(var i in leistungen) {
                if (!(i in leistungenOnLoad) && (leistungen[i].pm1 > 0 || leistungen[i].pm2 > 0)) {
                    return true;
                }
            }

            $.each(leistungen, function(i, val){
                if (
                    parseFloat(val.pm1.replace(',','.')).toFixed(2) != parseFloat(val.mm1.replace(',','.')).toFixed(2)
                    || parseFloat(val.pm2.replace(',','.')).toFixed(2) != parseFloat(val.mm2.replace(',','.')).toFixed(2) ) {
                    changed = true;
                }
            });
            return changed;
        };

        var getLeistungenFormData = function() {
        var leistungen = {};
        widget.find("#TblLeistungenBody tr").each(function(){
            if ($("td input", this).length < 4) return;
            var ilstg = $("input.ilstg", this).val();
            leistungen[ilstg] = {
                'pm1': $("td:eq(3) input.menge", this).val(),
                'pm2': $("td:eq(5) input.menge", this).val(),
                'mm1': $("td:eq(7) input.menge", this).val(),
                'mm2': $("td:eq(8) input.menge", this).val()
            };
        });
        return leistungen;
    };

        leistungenOnLoad = getLeistungenFormData();
        widget.find("#TblLeistungenBody tr input").change( callbackLeistungenChanged).eq(0).trigger("change");

        widget.find(".btn-add-leistung").unbind("click").bind("click", add_Leistung);
        widget.find(".col.ktg1").bind("click", get_Kategorie);
        widget.find(".col.lstg").bind("click", get_Leistung);
        widget.find(".drop-leistung span").bind("click", get_Leistung);
    });
    
<?php echo '</script'; ?>
>
<?php }
}
