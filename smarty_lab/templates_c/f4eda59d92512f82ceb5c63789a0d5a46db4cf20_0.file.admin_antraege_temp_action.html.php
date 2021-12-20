<?php
/* Smarty version 3.1.34-dev-7, created on 2021-12-20 18:08:57
  from '/var/www/html/html/admin_antraege_temp_action.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_61c0b8a98b1804_21457206',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f4eda59d92512f82ceb5c63789a0d5a46db4cf20' => 
    array (
      0 => '/var/www/html/html/admin_antraege_temp_action.html',
      1 => 1640020134,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_61c0b8a98b1804_21457206 (Smarty_Internal_Template $_smarty_tpl) {
?><link rel="stylesheet" type="text/css" href="{WebRoot}css/umzugsformular.css?%assetsRefreshId%">
<style>
    .btn,
    .btn.link {
        cursor: pointer;
        color: #0a53be;
    }
    .btn:hover {
        text-decoration: underline;
    }
</style>
<div>
    <button id="btnTempDelete" type="button" class="btn red">Anträge löschen</button>
    <button id="btnSendErinnerungsmail" type="button" class="btn blue">Erinnerungsmail an Antragsteller senden</button>
    <button id="btnPreview" class="btn link">Vorschau für Erinnerungsmail anzeigen</button>
</div>

<?php echo '<script'; ?>
>
    var validateRangeForm = function() {
        var datumvon = $("#rangeDatumvon").val();
        var datumbis = $("#rangeDatumbis").val();

        if (datumvon && datumbis && datumvon > datumbis) {
            alert('Im Datumsfilter darf die Angabe "Von" nicht größer ala "Bis" sein!');
            return false;
        }
        return true;
    };
    var getNumChecked = function() {
        return $("input[type=checkbox][name=aids\\[\\]]:checked").length;
    };
    var getCheckedAids = function() {
        var aids = [];
        $("input[type=checkbox][name=aids\\[\\]]:checked").each(function() {
            aids.push( $(this).val() );
        });
        return aids;
    };
$(function() {

    $("#btnPreview").bind("click", function(e) {
        var elm = $("input[type=checkbox][name=aids\\[\\]]:checked").first();
        if (!elm.length) {
            alert('Es muss mind. ein Eintrag für die Vorschau markiert sein!');
            return false;
        }
        var aid = elm.val();
        var url = '/sites/admin_erinnerungsmail.php?batchCmd=erinnerungsmailPreview&aids=' + aid;

        window.open(url, 'erinnerungsmailPreview');
    });

    $("#btnTempDelete").bind("click", function(e) {
        var numChecked = getNumChecked();
        var checkedAids = getCheckedAids();
        if (!numChecked) {
            e.preventDefault();
            alert("Es wurden keine Aufträge zum Löschen ausgewählt!");
            return false;
        }
        if (confirm('Möchten Sie die ' + numChecked + ' ausgewählten Aufträge wirklich löschen?')) {
            var url = '/sites/admin_temp_delete.php?batchCmd=tempDelete&aids=' + checkedAids.join(",");
            window.open(url, 'tempDelete');
            return true;
        }
        return false;
    });


    $("#btnSendErinnerungsmail").bind("click", function(e) {
        var numChecked = getNumChecked();
        var checkedAids = getCheckedAids();
        if (!numChecked) {
            e.preventDefault();
            alert("Es wurden keine Aufträge für die Erinnerungsmail ausgewählt!");
            return false;
        }
        if (confirm('Möchten Sie für die ' + numChecked + ' ausgewählten Aufträge wirklich Erinnerungsmails senden?')) {
            // alert("Die Erinnerungsmails wurde noch nicht implementiert!");

            var url = '/sites/admin_erinnerungsmail.php?batchCmd=erinnerungsmail&aids=' + checkedAids.join(",");

            window.open(url, 'erinnerungsmailSend');
        }
        return false;
    });
});
<?php echo '</script'; ?>
>

<?php }
}
