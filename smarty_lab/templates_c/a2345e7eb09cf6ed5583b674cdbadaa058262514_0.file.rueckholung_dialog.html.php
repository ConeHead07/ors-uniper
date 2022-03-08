<?php
/* Smarty version 3.1.34-dev-7, created on 2022-02-24 06:35:22
  from '/var/www/html/html/dialogs/rueckholung_dialog.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_6217272ae2c3b1_55878416',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a2345e7eb09cf6ed5583b674cdbadaa058262514' => 
    array (
      0 => '/var/www/html/html/dialogs/rueckholung_dialog.html',
      1 => 1646312042,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:admin_umzugsformular_leistungsauswahl.tpl.html' => 1,
    'file:admin_rueckholungen_leistungsauswahl.tpl.html' => 1,
  ),
),false)) {
function content_6217272ae2c3b1_55878416 (Smarty_Internal_Template $_smarty_tpl) {
?><style>
    .dialog .hint-box,
    .dialog.rekla-rueckholung .hint-box {
        margin-top: 1rem;
        border: 1px solid #0075b5;
        padding: 0.5rem;
        font-size: 0.9rem;
        font-weight: bold;
        background-color: #f1f8ff;
        color: #d7081e;
        border-radius: 0.5rem;
        font-style: italic;
    }
    .dialog .hint-box-title,
    .dialog.rekla-rueckholung .hint-box-title {
        color: #000;
        font-weight: bold;
        font-style: normal;
        font-size: 1rem;
    }
</style>
<div id="RueckholungDialog" class="dialog rekla-rueckholung dialog-back-layer">
    <div class="dialog-wrapper" style="width:90%;max-height: 95vh;overflow-y: scroll;">
        <div style="width:90%;">
            <div id="t2Content" style="max-height:40vh;overflow-y: auto;text-align:left;">
                <?php $_smarty_tpl->_subTemplateRender("file:admin_umzugsformular_leistungsauswahl.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('Umzugsleistungen'=>$_smarty_tpl->tpl_vars['AllOrderedLeistungen']->value,'enableLeistungCheckbox'=>"0",'addTeilmengen'=>"0",'showReklas'=>"0",'showLiefermengen'=>"1",'showAID'=>1,'Gesamtsumme'=>0,'mengeMertensReadOnly'=>1,'title'=>"Bisher gelieferte Artikel"), 0, false);
?>
            </div>
            <form>
                <div id="tlContent" style="max-height:60vh;overflow-y: auto;text-align:left;">
                    <?php $_smarty_tpl->_subTemplateRender("file:admin_rueckholungen_leistungsauswahl.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('Umzugsleistungen'=>$_smarty_tpl->tpl_vars['RueckholLeistungen']->value,'enableLeistungCheckbox'=>"1",'addTeilmengen'=>"0",'showReklas'=>"0",'showLiefermengen'=>"0",'Gesamtsumme'=>0,'title'=>"Wählen Sie Leistungen und Menge für die Rückholung aus"), 0, false);
?>
                </div>
                <div id="BoxBemerkungen" style="margin-top: 1rem">
                    <strong>Rueckholungsgrund hinzufügen:</strong><br>
                    <textarea class="iarea bemerkungen" name="grund" style="resize: vertical;overflow: auto"></textarea>
                </div>

                <div class="hint-box">
                    <div class="hint-box-title">Wichtiger Hinweis, bitte lesen und beachten!</div>
                    Bitte prüfen Sie vor dem Absenden die ausgewählten Leistungen und Rückholungsmengen. Sie haben auch die Möglichkeit zu Dokumentationszwecken einen Grund für die Rückholung anzugeben.
                </div>

                <div style="text-align: center;margin-top: 1rem">
                    <input type="hidden" name="aid" value="<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
">
                    <button type="button" class="btn green btn-apply">Rückholung anlegen</button>
                    <button type="button" class="btn gray btn-cancel">Schließen</button>
                </div>
            </form>
        </div>

    </div>
</div>


<?php echo '<script'; ?>
>


    var numberFormat = function(number, dec, decToken, thousandSep) {
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
    };

    function showRueckholungDialog() {

        var container = $("#RueckholungDialog");
        $("body").addClass("doc-body-no-scrollbars");
        var dialog = container.closest(".dialog");
        console.log({containerLength: container.length, dialogLength: dialog.length });
        dialog
            .addClass("dialog-active")
            .find("button.btn-cancel")
            .off("click")
            .on("click", function() {
                $("body").removeClass("doc-body-no-scrollbars");
                container.removeClass("dialog-active");
            }).end()
            .find("button.btn-apply")
            .off("click")
            .on("click", function() {
                var btnApply = $(this);
                var frm = $(this).closest("form");
                var btnCancel = frm.find("button.btn-cancel");
                btnApply.prop("disabled", true);
                btnCancel.prop("disabled", true);

                var aid = frm.find('[name=aid]').val();
                var grund = frm.find('[name=grund]').val();
                var chckLeistungen = frm.find('[name=chckLeistung\\[\\]]:checked').serializeArray();
                console.log({ aid, chckLeistungen });

                var leistungen = [];
                for(var i = 0; i < chckLeistungen.length; i++) {
                    var leistung_id = chckLeistungen[i].value;
                    var sel = '[name^=L\\[neue_rueckholmenge\\]\\[' + leistung_id + '\\]]';
                    console.log('Rueckholmengen Selector: ', sel, frm.find(sel).length, frm.find(sel).val());
                    var menge = frm.find(sel).val();
                    if (!parseInt(menge)) {
                        btnApply.prop("disabled", false);
                        btnCancel.prop("disabled", false);
                        alert("Für die " + (i+1) + ". ausgewählte Position wurde keine Menge angegeben!");
                        return;
                    }
                    leistungen.push({ leistung_id, menge });
                }
                console.log({ aid, chckLeistungen, leistungen });

                if(!leistungen.length) {
                    btnApply.prop("disabled", false);
                    btnCancel.prop("disabled", false);
                    alert("Es wurden keine Leistungen für die Rückholung ausgewählt!");
                    return;
                }

                if (false) {
                    btnApply.prop("disabled", false);
                    btnCancel.prop("disabled", false);
                    return;
                }

                var request = $.post('/rueckholung.php',
                    {
                        aid,
                        leistungen,
                        grund
                    }, function(response) {
                        console.log({ response });
                        if (response.type === "success") {
                            alert("Rueckholung wurde angelegt mit der ID " + response.newAid);
                            window.location.reload();
                        } else {
                            alert("Es sind Fehler aufgetreten!\n" + response.msg);
                        }
                    }
                );
                request.always(function() {
                    $("body").removeClass("doc-body-no-scrollbars");
                    btnApply.prop("disabled", false);
                    btnCancel.prop("disabled", false);
                });
            }).end();
        return;
    }

    var calcRHSum = function() {
        var frmTL = $("#RueckholungDialog").find("form");

        var tblSum = frmTL.find("#summary #allsum");
        var chckRowSum = frmTL.find(".row .sum").length > 0;
console.log({ frmTL, tblSum, chckRowSum });
        if (tblSum.length === 0 && !chckRowSum) {
            return;
        }

        var total = 0;
        var sTotal = "0,00";
        frmTL.find("#TblRLeistungenBody .col.field-sum").text("0 €");
        frmTL.find("#TblRLeistungenBody [name=chckLeistung\\[\\]]:checked").each(function() {
            var row = $(this).closest(".row[data-row]");
            var rowData = row.data("row");
            var p = rowData.preis_pro_einheit;
            var m = +row.find("[name^=L\\[neue_rueckholmenge\\]]").val();
            var sum = p * m;
            var sSum = numberFormat(sum, 2,  ',', '.');
            var sumCell = row.find(".col.field-sum");

            sumCell.text( sSum + " €");
            var reCheckRowSum = sumCell.text();

            total+= sum;
            // console.log({ row, rowData, p, m, sum, sSum, sumCell, reCheckRowSum });
        });

        sTotal = numberFormat(total, 2,  ',', '.');
        tblSum.text( sTotal );
        var reCheckTblSum = tblSum.text();
        // console.log({ total, sTotal, reCheckTblSum });
    };

    $(function() {

        // Rueckholung
        $("#RueckholungDialog").appendTo("body");
        var frm = $("#RueckholungDialog").find("form");
        var tplTable = frm.find("#TplLeistungTable").addClass("tpl-rueckholung-row");
        console.log("rueckholung tplTable length", tplTable.length);
        tplTable.remove();

        frm.find("#TblRLeistungenBody [name^=L\\[neue_rueckholmenge\\]]").on("change", function(e) {
            var t = $(this).closest(".row[data-row]").data("row");
            var val = +$(this).val();
            var checkbox = $(this).closest(".row").find(":input[name=chckLeistung\\[\\]]");

            if (val) {
                if (!checkbox.prop("checked")) {
                    checkbox.prop("checked", true);
                }
            } else {
                if (checkbox.prop("checked")) {
                    checkbox.prop("checked", false);
                }
            }
            calcRHSum();
        });

        frm.find("#TblRLeistungenBody [name=chckLeistung\\[\\]]").on("change", function(e) {
            var lid = $(this).val();
            var row = $(this).closest(".row[data-row]").data("row");
            var neu = $(this).closest(".row").find("[name^=L\\[neue_rueckholmenge\\]]");
            if (!$(this).prop("checked")) {
                neu.val(0);
                calcRHSum();
                return;
            }

            var mtMenge = parseFloat(row.menge_mertens || 1);
            var glMenge = parseFloat(row.menge_geliefert || 0);
            var neuVal = Math.max(1, mtMenge - glMenge);

            neu.val(neuVal);
            calcRHSum();
        });
    });
<?php echo '</script'; ?>
>

<?php }
}
