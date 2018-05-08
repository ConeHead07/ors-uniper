<?php /* Smarty version 2.6.26, created on 2017-05-03 01:52:15
         compiled from admin_umzugsformular_gruppierung.tpl.html */ ?>
<script src="js/auftraege_gruppieren.js?201704250208"></script>
<div style="clear:both;width:100%">

<div class="SelBoxDienstleisterWidth" style="width:390px;clear:both">
  <span style="float:left;margin-bottom:2px;color:#549e1a;font-weight:bold;text-decoration:none;cursor:pointer;" 
      onclick="gruppierungsauftrag_new_search();return false;">
  Gruppierung hinzuf&uuml;gen <img align="absmiddle" src="images/hinzufuegen_off.png" width="14" alt=""></span>
    
<table class="MitarbeierItem" style="border:0;padding:0;margin:0 0 5px 0;width:100%;">
    <tr>
        <td style="border:0;padding:0;margin:0">
            <input name="SelectGruppierung" id="SelectGruppierung"  
                   style="width:100%;border:1px solid #549e1a" 
                   onclick="get_gruppierungsauftrag(this)" 
                   ondblclick="gruppierungsauftrag_new_search()">
        </td>
    </tr>
</table>
</div>
</div>

<table class="MitarbeierItem" style="width:100%;">
    <thead>
        <tr>
            <td style="width:14px;padding:0;"> X </td>
            <td>ID</td>
            <td>Termin</td>
            <td>Auftragsort</td>
            <td>Etage</td>
            <td>Raumnr</td>
            <td>Mit Umzug</td>
            <td>Auftragseingang</td>
            <td>G</td>
            <td>M</td>
			<td>Abgeschlossen</td>
        </tr>
    </thead>
    <tbody id="TblGruppierungenBody">
    <?php $_from = $this->_tpl_vars['UmzugsGruppierungen']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['GList'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['GList']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['G']):
        $this->_foreach['GList']['iteration']++;
?>
        <tr data-id="<?php echo $this->_tpl_vars['G']['aid']; ?>
" class="row inputRowVon">
            <td style="padding:0;">
			<span data-editid="<?php echo $this->_tpl_vars['G']['aid']; ?>
"  onclick="auftragsliste_remove(<?php echo $this->_tpl_vars['G']['aid']; ?>
)" style="cursor:pointer;margin:0;padding:0;"><img style="cursor:pointer;margin:0;padding:0;" align="absmiddle" src="images/loeschen_off.png" width="14" alt=""><span></td>
            <td><a href="?s=aantrag&id=<?php echo $this->_tpl_vars['G']['aid']; ?>
"><?php echo $this->_tpl_vars['G']['aid']; ?>
</a></td>
			<td><?php if ($this->_tpl_vars['G']['umzugstermin']): ?><?php echo $this->_tpl_vars['G']['umzugstermin']; ?>
<?php else: ?><?php echo $this->_tpl_vars['G']['terminwunsch']; ?>
<?php endif; ?></td>
			<td><?php echo $this->_tpl_vars['G']['ort']; ?>
</td>
			<td><?php echo $this->_tpl_vars['G']['etage']; ?>
</td>
			<td><?php echo $this->_tpl_vars['G']['raumnr']; ?>
</td>
			<td><?php echo $this->_tpl_vars['G']['umzug']; ?>
</td>
			<td><?php echo $this->_tpl_vars['G']['antragsdatum']; ?>
</td>
			<td><?php echo $this->_tpl_vars['G']['Genehmigt']; ?>
<?php if ($this->_tpl_vars['G']['genehmigt_br_am']): ?> <?php echo $this->_tpl_vars['G']['genehmigt_br_am']; ?>
<?php endif; ?></td>
			<td><?php echo $this->_tpl_vars['G']['Geprueft']; ?>
<?php if ($this->_tpl_vars['G']['geprueft_am']): ?> <?php echo $this->_tpl_vars['G']['geneprueft_am']; ?>
<?php endif; ?></td>
			<td><?php echo $this->_tpl_vars['G']['abgeschlossen']; ?>
<?php if ($this->_tpl_vars['G']['abgeschlossen_am']): ?><?php echo $this->_tpl_vars['G']['abgeschlossen_am']; ?>
<?php endif; ?></td>
		</tr>
	<?php endforeach; endif; unset($_from); ?>
	</tbody>
</table>
<input id="gruppierteauftraege" name="gruppierteauftraege" type="hidden" value="<?php echo $this->_tpl_vars['UmzugsGruppierungsIds']; ?>
">


<table id="TplGruppierungsTable" style="display:none;">
        <tr class="row inputRowVon">
            <td style="padding:0;">
			<span data-editid="" onclick="auftragsliste_remove($(this).attr('data-editid'))" style="cursor:pointer;margin:0;padding:0;"><img style="cursor:pointer;margin:0;padding:0;" align="absmiddle" src="images/loeschen_off.png" width="14" alt=""><span></td>
            <td><a data-lnkto="umzug" data-fld="aid" href=""></a></td>
			<td data-fld="termin"></td>
			<td data-fld="ort"></td>
			<td data-fld="etage"></td>
			<td data-fld="raumnr"></td>
			<td data-fld="umzug"></td>
			<td data-fld="antragsdatum"></td>
			<td><span data-fld="Genehmigt"></span><span data-fld="genehmigt_br_am"></span></td>
			<td><span data-fld="Geprueft"></span><span data-fld="geprueft_am"></span></td>
			<td><span data-fld="Abgeschlossen"></span><span data-fld="abgeschlossen_am"></span></td>
		</tr>
</table>