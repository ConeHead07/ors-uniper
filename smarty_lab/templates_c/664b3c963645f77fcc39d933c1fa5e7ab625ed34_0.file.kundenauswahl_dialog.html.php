<?php
/* Smarty version 3.1.34-dev-7, created on 2022-02-24 04:15:44
  from '/var/www/html/html/dialogs/kundenauswahl_dialog.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_62170670adf473_99080919',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '664b3c963645f77fcc39d933c1fa5e7ab625ed34' => 
    array (
      0 => '/var/www/html/html/dialogs/kundenauswahl_dialog.html',
      1 => 1646312042,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62170670adf473_99080919 (Smarty_Internal_Template $_smarty_tpl) {
?><style>
    .dialog .hint-box,
    .dialog.kundenauswahl .hint-box {
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
    .dialog.kundenauswahl .hint-box-title {
        color: #000;
        font-weight: bold;
        font-style: normal;
        font-size: 1rem;
    }
    #KundenauswahlDialog table.livesearch-table {
        width: 100%;
        border-collapse: collapse;
    }
    #KundenauswahlDialog .livesearch-table thead tr,
    #KundenauswahlDialog .livesearch-table thead tr th,
    #KundenauswahlDialog .livesearch-table thead tr td  {
        border-bottom: 1px solid black;
    }
    #KundenauswahlDialog .livesearch-table tbody tr,
    #KundenauswahlDialog .livesearch-table tbody tr td {
        border-bottom: 1px solid #b1b1b1;
        min-height:1.2rem;
    }
    #KundenauswahlDialog .livesearch-result-list .result-item:hover,
    #KundenauswahlDialog .livesearch-result-list .result-item:hover * {
        cursor: pointer;
        background-color: #0075b5;
        color: #ffffff;
    }
    #KundenauswahlDialog input#searchCustomer {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #77a4ff;
        border-radius: 5px;
        padding: 0.3em;
        background: #ecf6ff;
    }
</style>
<div id="KundenauswahlDialog" class="dialog kundenauswahl dialog-back-layer">
    <div class="dialog-wrapper" style="width:98%;max-height: 95vh;overflow-y: scroll;">
        <div style="width:100%;">
            <form>
                <div id="tlContent" style="max-height:80vh;overflow-y: auto;text-align:left;">

                    <input placeholder="Kundensuche" id="searchCustomer"/>

                    <div class="alert alert-msg"></div>
                    <div class="alert alert-error"></div>
                    <table class="livesearch-table">
                        <thead>
                            <tr>
                                <th>KID</th>
                                <th>Vorname</th>
                                <th>Nachname</th>
                                <th>Benutzer</th>
                                <th>Email</th>
                                <th>Gruppe</th>
                            </tr>
                        </thead>
                        <tbody class="livesearch-result-list">
                        </tbody>
                    </table>

                    <table id="tplTableWithResultRow" style="display: none">
                        <tbody>
                            <tr class="tpl-row">
                                <td class="ool col-personalnr" data-field="personalnr"></td>
                                <td class="col col-vorname" data-field="vorname"></td>
                                <td class="col col-nachname" data-field="nachname"></td>
                                <td class="col col-user" data-field="user"></td>
                                <td class="col col-email" data-field="email"></td>
                                <td class="col col-gruppe" data-field="gruppe"></td>
                            </tr>
                        </tbody>
                    </table>

                </div>

                <div style="text-align: center;margin-top: 1rem">
                    <input type="hidden" name="aid" value="<?php echo $_smarty_tpl->tpl_vars['AS']->value['aid'];?>
">
                    <button type="button" class="btn gray btn-cancel">Schließen</button>
                </div>
            </form>
        </div>

    </div>
</div>


<?php echo '<script'; ?>
>


    $(function() {

        var initKundenauswahl = function(selectCallback) {


            var timerLivesearchCustomer = null;
            var livesearchCustomerRows = [];
            var waitingForRequest = false;
            var queuedInput = '';
            var boxContent = box.find("#tlContent");
            var tplRow = box.find("#tplTableWithResultRow .tpl-row").clone().removeClass("tpl-row").addClass("result-item");
            var resultList = box.find(".livesearch-result-list");
            var alertMsg = box.find(".alert.alert-msg");
            var alertError = box.find(".alert.alert-error");

            $("#searchCustomer")
                .data('lastQuery', '')
                .bind("input", function(e) {
                    var $this = this;

                    if (!$("#searchCustomer").is(":visible")) {
                        queuedInput = '';
                        try {
                            (timerLivesearchCustomer) && clearTimeout(timerLivesearchCustomer);
                        } catch(e){}
                        waitingForRequest = false;
                        return;
                    }

                    if (waitingForRequest) {
                        queuedInput = $this.value;
                        return;
                    }
                    if (timerLivesearchCustomer) {
                        try {
                            clearTimeout(timerLivesearchCustomer);
                        } catch (e) {
                            console.error(e);
                        }
                        timerLivesearchCustomer = null;
                    }
                    queuedInput = '';

                    timerLivesearchCustomer = setTimeout(function() {
                        console.log("#110");
                        waitingForRequest = false;
                        var query = $this.value;
                        alertMsg.html("").hide();
                        alertError.html("").hide();
                        waitingForRequest = true;
                        boxContent.waitMe({effect:'ios'});
                        $.get("/livesearch_user.php", { query }, function(response) {
                            console.log("#117");
                            livesearchCustomerRows = [];
                            resultList.html("");
                            var isSuccessful = response.type === "success";
                            var isWithDataArray = isSuccessful && "data" in response && Array.isArray(response.data);
                            var isNotEmpty = isWithDataArray &&  response.data.length > 0;
                            var dataLength =  isWithDataArray ? response.data.length : 0;
                            var total = ("total" in response) ? response.total : dataLength;
                            console.log({isSuccessful, isWithDataArray, isNotEmpty, dataLength })

                            if (isSuccessful && dataLength > 0) {
                                console.log("#121");
                                livesearchCustomerRows = response.data;
                                var msg = ( total > dataLength )
                                          ? "Es werden " + dataLength + " von " + total + " Treffern angezeigt ..."
                                          : "Suche ergab " + dataLength + " Treffer ...";

                                alertMsg.html( msg ).show();

                                console.log("#124", { resultList });
                                for (var i = 0; i < livesearchCustomerRows.length; i++) {
                                    var rowData = livesearchCustomerRows[i];
                                    console.log("#127", { i, rowData });
                                    var row = tplRow.clone().data("rowData", rowData);

                                    for(var k in rowData) {
                                        row.find("[data-field=" + k + "]").text(rowData[k]);
                                    }
                                    console.log("#133");
                                    resultList.append(row);
                                    console.log("#135");
                                }
                                resultList.find(".result-item").bind("click", function(e) {
                                    console.log("#138 SELECT click on Item!", this);
                                    selectCallback( $(this).data("rowData") );
                                });
                            } else {
                                var error = ("error" in response && response.error.length) ? "<br>" + response.error : "";
                                console.error('No valid Result in response', response);
                                alertError.html("Suche ergab keine Treffer." + error).show();
                            }
                        })
                            .always(function() {
                                console.log("always!");
                                waitingForRequest = false;
                                boxContent.waitMe('hide');
                                if (queuedInput) {
                                    $("#searchCustomer").trigger("input");
                                }
                            });
                    }, 500);
                });
        };

        window.showKundenauswahlDialog = function(selectCallback) {

            var container = $("#KundenauswahlDialog");
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
            }).end();

            initKundenauswahl(function(rowData) {
                var btnCancel = box.find("button.btn-cancel");
                btnCancel.trigger("click");
                selectCallback(rowData);
            });
            $("#searchCustomer").get(0).focus();
            return;
        };

        // Kundenauswahl
        $("#KundenauswahlDialog").appendTo("body");
        var box = $("#KundenauswahlDialog");
    });
<?php echo '</script'; ?>
>

<?php }
}
