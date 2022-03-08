<?php
/* Smarty version 3.1.34-dev-7, created on 2022-02-24 04:15:44
  from '/var/www/html/html/dialogs/rekla_dialog.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_6217067098c601_57324358',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '58a11b0dd2d048ab56cdc9cfd32e290f3f8b207f' => 
    array (
      0 => '/var/www/html/html/dialogs/rekla_dialog.html',
      1 => 1646312042,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:admin_umzugsformular_leistungsauswahl.tpl.html' => 1,
  ),
),false)) {
function content_6217067098c601_57324358 (Smarty_Internal_Template $_smarty_tpl) {
?><style>
    .dialog .hint-box,
    .dialog.rekla-dialog .hint-box {
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
    .dialog.rekla-dialog .hint-box-title {
        color: #000;
        font-weight: bold;
        font-style: normal;
        font-size: 1rem;
    }
</style>
<div id="ReklaDialog" class="dialog rekla-dialog dialog-back-layer">
    <div class="dialog-wrapper" style="width:90%;max-height: 95vh;overflow-y: scroll;">
        <div style="width:90%;">
            <form>
                <div id="reklaContent" style="max-height:80vh;overflow-y: auto;text-align:left;">

                    <?php $_smarty_tpl->_subTemplateRender("file:admin_umzugsformular_leistungsauswahl.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('enableLeistungCheckbox'=>"1",'addReklas'=>"1",'showReklas'=>"1",'title'=>"Welche Leistung möchten Sie reklamieren ..."), 0, false);
?>

                </div>
                <div id="BoxBemerkungen" style="margin-top: 1rem">
                    <strong>Reklamationsgrund hinzufügen: (<em>erforderlich</em>)</strong><br>
                    <textarea class="iarea bemerkungen" name="grund" style="resize: vertical;overflow: auto"></textarea>
                </div>

                <div class="hint-box">
                    <div class="hint-box-title">Wichtiger Hinweis, bitte lesen und beachten!</div>
                    Beim Anlegen einer Reklamation erhält der Kunde eine Benachrichtigung mit dem angegebenen Reklamationsgrund.<br>
                    Bitte prüfen Sie vor dem Absenden die ausgewählten Leistungen, Mengen und den Reklamationsgrund.<br>
                    Mit den Leistungen verbundenen Bundle-Leistungen wie Montage, DGUV, Power-Cube, Transport, etc. müssen
                    händisch hinzugefügt werden!
                </div>

                <div style="text-align: center;margin-top: 1rem">
                    <input type="hidden" name="aid" value="<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
">
                    <button type="button" class="btn red btn-apply">Reklamation anlegen</button>
                    <button type="button" class="btn gray btn-cancel">Schließen</button>
                </div>
            </form>
        </div>

    </div>
</div>


<?php echo '<script'; ?>
>
    function showReklaDialog() {

        var container = $("#ReklaDialog");
        $("body").addClass("doc-body-no-scrollbars");
        var dialog = container.closest(".dialog");
        console.log({containerLength: container.length, dialogLength: dialog.length });
        dialog
            .addClass("dialog-active")
            .find("button.btn-cancel")
            .off("click")
            .on("click", function() {
                $("body").removeClass("doc-body-no-scrollbars");
                $("#eingabe_datenschutz_confirm").prop("checked", false);
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
                    var lid = chckLeistungen[i].value;
                    var sel = '[name^=L\\[neue_rekla\\]\\[' + lid + '\\]]';
                    console.log('Rekla-Mengen Selector: ', sel, frm.find(sel).length, frm.find(sel).val());
                    var mng = frm.find('[name^=L\\[neue_rekla\\]\\[' + lid + '\\]]').val();
                    if (!parseInt(mng)) {
                        btnApply.prop("disabled", false);
                        btnCancel.prop("disabled", false);
                        alert("Für die " + (i+1) + ". ausgewählte Position wurde keine Menge angegeben!");
                        return;
                    }
                    leistungen.push({ leistung_id: lid, menge: mng });
                }
                console.log({ aid, chckLeistungen, leistungen });

                if(!leistungen.length) {
                    btnApply.prop("disabled", false);
                    btnCancel.prop("disabled", false);
                    alert("Es wurden keine Leistungen für die Reklamation ausgewählt!");
                    return;
                }

                if ($.trim(grund) === "") {
                    btnApply.prop("disabled", false);
                    btnCancel.prop("disabled", false);
                    alert("Es wurden kein Grund für die Reklamation angegeben!");
                    return;
                }

                if (false) {
                    btnApply.prop("disabled", false);
                    btnCancel.prop("disabled", false);
                    return;
                }

                var request = $.post('/reklamieren.php',
                    {
                        aid,
                        leistungen,
                        grund
                    }, function(response) {
                        console.log({ response });
                        if (response.type === "success") {
                            alert("Reklamation wurde angelegt mit der ID " + response.reklaAid);
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

    $(function() {
        $("#ReklaDialog").appendTo("body");
        var frm = $("#ReklaDialog").find("form");
        var tplTable = frm.find("#TplLeistungTable").addClass("tpl-rekla-row");
        frm.after(tplTable);

        frm.find("[name=chckLeistung\\[\\]]").on("change", function(e) {
            var lid = $(this).val();
            var row = $(this).closest(".row[data-row]").data("row");
            var neu = $(this).closest(".row").find("[name^=L\\[neue_rekla\\]]");
            if (!$(this).prop("checked")) {
                neu.val(0);
                return;
            }
            var mtMenge = parseFloat(row.menge_mertens || 1);
            var rkMenge = parseFloat(row.menge_rekla || 0);
            var glMenge = parseFloat(row.menge_geliefert || 0);
            var neuVal = Math.max(1, mtMenge - rkMenge - glMenge);

            neu.val(neuVal);
        });

    });
<?php echo '</script'; ?>
>

<?php }
}
