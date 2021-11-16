<?php
/* Smarty version 3.1.34-dev-7, created on 2021-11-10 22:50:34
  from '/var/www/html/html/admin_antraege_suche.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_618c4cba431813_50404323',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '50b493b5a4c88c817bd8a70d9dbaccca42639b34' => 
    array (
      0 => '/var/www/html/html/admin_antraege_suche.html',
      1 => 1636584555,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_618c4cba431813_50404323 (Smarty_Internal_Template $_smarty_tpl) {
?>
<link rel="STYLESHEET" type="text/css" href="{WebRoot}css/suchformular.css" />
<?php echo '<script'; ?>
 src="{WebRoot}js/suchformular.antreage.easy.js" type="text/javascript"><?php echo '</script'; ?>
>	
<?php echo '<script'; ?>
 src="{WebRoot}js/SelBox.easy.js" type="text/javascript"><?php echo '</script'; ?>
>	
<?php echo '<script'; ?>
 src="{WebRoot}js/ObjectHandler.js" type="text/javascript"><?php echo '</script'; ?>
>
<link rel="STYLESHEET" type="text/css" href="{WebRoot}css/SelBox.easy.css" />
<link rel="stylesheet" type="text/css" href="{WebRoot}css/umzugsformular.css">
<style>
.frmSuche input.qField { width:200px; }
.frmSuche label { width:100px; margin-left:0; }
</style>



<form name="frmSuche" class="frmSuche">
	<table>
		<tr>
			<td style="padding:0;width:200px;">
				<label for="as_vornachname" style="display:block;width:auto;">
					Vor &amp; Nachname
				</label></td>

			<td style="padding:0;width:250px;">
				<input type="text" id="as_vornachname" value="{q[a.vorname]}" name="q[a.vorname]" class="itxt itxt1col floatLeft">
				<input type="text" id="as_name" value="{q[a.name]}" name="q[a.name]" class="itxt itxt1col floatRight" title="Name">
			</td>
		</tr>
		<tr>
			<td style="padding:0;"><label for="q_aid" style="display:block;width:auto;">Ticket-ID</label></td>
			<td style="padding:0;"><input type="text" id="q_aid" value="{q[a.aid]}" name="q[a.aid]" class="itxt itxt2col" title="Ticket-ID"></td>
		</tr>
		<tr>
			<td style="padding:0;"><label for="q_kid" style="display:block;width:auto;">KID</label></td>
			<td style="padding:0;"><input type="text" id="q_kid" value="{q[u.kid]}" name="q[u.kid]" class="itxt itxt2col" title="KID"></td>
		</tr>
		<tr>
			<td style="padding:0;"><label for="q_ort" style="display:block;width:auto;">Ort</label></td>
			<td style="padding:0;"><input type="text" id="q_ort" value="{q[a.ort]}" name="q[a.ort]" class="itxt itxt2col" title="Ort"></td>
		</tr>
		<tr>
			<td style="padding:0;"><label for="q_plz" style="display:block;width:auto;">PLZ</label></td>
			<td style="padding:0;"><input type="text" id="q_plz" value="{q[a.plz]}" name="q[a.plz]" class="itxt itxt2col" title="Postleitzahl"></td>
		</tr>
		<tr>
			<td style="padding:0;"><label for="q_strasse" style="display:block;width:auto;">Strasse</label></td>
			<td style="padding:0;"><input type="text" id="q_strasse" value="{q[a.strasse]}" name="q[a.strasse]" class="itxt itxt2col" title="Postleitzahl"></td>
		</tr>
		<tr>
			<td style="padding:0;"><label for="q_land" style="display:block;width:auto;">Land</label></td>
			<td style="padding:0;"><input type="text" id="q_land" value="{q[a.land]}" name="q[a.land]" class="itxt itxt2col" title="Land" list="laenderauswahl"></td>
		</tr>
		<datalist id="laenderauswahl">
			<option>Deutschland</option>
			<option>Niederlande</option>
			<option>Ungarn</option>
		</datalist>
		<tr>
			<td style="padding:0;"><label for="q_umzugsstatus" style="display:block;width:auto;">Status</label></td>
			<td style="padding:0;"><select id="q_umzugsstatus" value="{q[a.umzugsstatus]}" name="q[a.umzugsstatus]" class="iselect" title="Status">
				<option value=""></option>
				<option value="temp">temp / Bestellung noch nicht abgeschickt</option>
				<option value="beantragt">beantragt / bestellt</option>
				<option value="geprueft">geprueft / avisiert</option>
				<option value="abgeschlossen">abgeschlossen</option>
			</select></td>
		</tr>
	</table>
	<?php echo '<script'; ?>
>
		var selectedUmzugsstatus = "{q[a.umzugsstatus]}";
		$("#q_umzugsstatus").find("option[value="+selectedUmzugsstatus+"]").prop("selected", true);
	<?php echo '</script'; ?>
>

	<input type="hidden" name="View" value="Antraege">
<input type="submit" class="qSubmit" value="Suche starten">
<input type="hidden" name="sendquery" value="1">
<input type="hidden" name="s" value="{s}">
<!-- {hiddenFields} -->
</form>

<?php }
}
