<?php /* Smarty version 2.6.26, created on 2016-02-10 21:48:44
         compiled from umzugsformular.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'umzugsformular.tpl.html', 57, false),array('modifier', 'date_format', 'umzugsformular.tpl.html', 103, false),array('modifier', 'substr', 'umzugsformular.tpl.html', 108, false),array('modifier', 'nl2br', 'umzugsformular.tpl.html', 271, false),array('function', 'html_radios', 'umzugsformular.tpl.html', 159, false),)), $this); ?>
<?php echo '
<script src="{WebRoot}js/FbAjaxUpdater.js" type="text/javascript"></script>
<script src="{WebRoot}js/PageInfo.js" type="text/javascript"></script>
<link rel="STYLESHEET" type="text/css" href="{WebRoot}css/SelBox.easy.css">
<script src="{WebRoot}js/ObjectHandler.js" type="text/javascript"></script>
<script src="{WebRoot}js/SelBox.easy.js" type="text/javascript"></script>
<script src="{WebRoot}js/umzugsformular.easy.js?lm=20101021" type="text/javascript"></script>
<script src="{WebRoot}js/geraeteumzug.easy.js?lm=20101021" type="text/javascript"></script>
'; ?>

<style>
<?php echo '
    .hide-not-saved {
        display:none;
    }
    span.right {
        display:inline-block;
        width:10px;
        text-align:right;
    }
    span.required {
        color:#eef;
        font-weight:bold;
        font-size:1.2em;
    }
    span.required-label {
        color:#0075B5;
    }
    
    td.cell-buttons {
        vertical-align:top;text-align:center;width:350px;
    }
    
    td.cell-buttons button,
    td.cell-buttons .button {
        padding-left:15px;
        padding-right:15px;
        margin-left:20px;
    }
'; ?>

</style>
<div id="SysInfoBox"></div>

<link rel="stylesheet" type="text/css" href="css/umzugsformular.css">
<!-- MODUL UEBERSCHRIFTENBOX 109099 BEGIN --> 
<div class="divModuleBasic padding6px width5Col heightAuto colorContentMain"> 
<h1><span class="spanTitle">Leistungsanforderung - Neuer Auftrag</span></h1> 
<p>
<div id="Umzugsantrag" class="divInlay"> 
<h2 style="margin:0;">Leistungsantragsteller</h2> 
<form action="umzugsantrag_speichern.php" name="frmUmzugsantrag" method="post" style="margin:0;padding:0;display:inline;">
<input type="hidden" name="AS[aid]" value="<?php echo $this->_tpl_vars['AS']['aid']; ?>
">
<input type="hidden" name="AS[token]" value="<?php echo $this->_tpl_vars['AS']['token']; ?>
">
<span class="required-label">* Erforderlicher Angaben</span>
<table>
  <tr>
      <td style="padding:0;width:200px;"><label for="as_vornachname" style="display:block;width:auto;">Vor<?php if ($this->_tpl_vars['ASConf']['vorname']['required']): ?><span class="required">*</span><?php endif; ?> &amp; Nachname<span class="right"><?php if ($this->_tpl_vars['ASConf']['name']['required']): ?><span class="required">*</span><?php endif; ?></span></label></td>
    <td style="padding:0;width:250px;"><input type="text" id="as_vornachname" readonly="true" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['vorname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php if ($this->_tpl_vars['ASConf']['name']['required']): ?>required="required"<?php endif; ?> name="AS[vorname]" class="itxt itxt1col floatLeft"><input type="text" id="as_name" readonly="true" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[name]" class="itxt itxt1col floatRight" title="Name"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="as_email" style="display:block;width:auto;">E-Mail<span class="right"><?php if ($this->_tpl_vars['ASConf']['email']['required']): ?><span class="required">*</span><?php endif; ?></span></label></td>
    <td style="padding:0;"><input type="text" id="as_email" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[email]" <?php if ($this->_tpl_vars['ASConf']['email']['required']): ?>required="required"<?php endif; ?> readonly="true" class="itxt itxt2col" title="E-Mail"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="as_fon" style="display:block;width:auto;">Fon<span class="right"><?php if ($this->_tpl_vars['ASConf']['fon']['required']): ?><span class="required">*</span><?php endif; ?></span></label></td>
    <td style="padding:0;"><input type="text" id="as_fon" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['fon'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php if ($this->_tpl_vars['ASConf']['fon']['required']): ?>required="required"<?php endif; ?> name="AS[fon]" class="itxt itxt2col jtooltip" rel="%WebRoot%hilfetexte/antrag_fon.php"></td>
  </tr>

  <tr>
    <td style="padding:0;">
        <label for="as_ort" style="display:block;width:auto;">Auftragsort<span class="right"><?php if ($this->_tpl_vars['ASConf']['ort']['required']): ?><span class="required">*</span><?php endif; ?></span></label></td>
    <td style="padding:0;"><input type="text" id="as_ort" xreadonly="true" <?php if ($this->_tpl_vars['ASConf']['ort']['required']): ?>required="required"<?php endif; ?> onclick="get_standort_ort(this)" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['ort'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[ort]" class="itxt itxt2col"></td>
  </tr>

  <tr>
    <td style="padding:0;"><label for="gebaeude" style="display:block;width:auto;">Wirtschaftseinheit<span class="right"><?php if ($this->_tpl_vars['ASConf']['gebaeude']['required']): ?><span class="required">*</span><?php endif; ?></span></label>
        <input type="hidden" id="gebaeude" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['gebaeude'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php if ($this->_tpl_vars['ASConf']['gebaeude']['required']): ?>required="required"<?php endif; ?> name="AS[gebaeude]">
    </td>
    <td style="padding:0;"><input type="text" readonly="true" <?php if ($this->_tpl_vars['ASConf']['gebaeude']['required']): ?>required="required"<?php endif; ?> id="ASGebaeudeUsrInput" onclick="get_standort_gebaeude(this, O('gebaeude'))" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['gebaeude_text'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[gebaeude_text]" class="itxt itxt2col"></td>
  </tr>

  <tr>
    <td style="padding:0;"><label for="etage" style="display:block;width:auto;">Etage<span class="right"><?php if ($this->_tpl_vars['ASConf']['etage']['required']): ?><span class="required">*</span><?php endif; ?></span></label>
        <input type="hidden" id="etage" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['etage'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php if ($this->_tpl_vars['ASConf']['etage']['required']): ?>required="required"<?php endif; ?> name="AS[etage]">
    </td>
    <td style="padding:0;"><input type="text" readonly="true" <?php if ($this->_tpl_vars['ASConf']['etage']['required']): ?>required="required"<?php endif; ?> id="ASEtageUsrInput" onclick="get_gebaeude_etage(this, O('etage'))" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['etage'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[etage_text]" class="itxt itxt2col"></td>
  </tr>

  <tr>
    <td style="padding:0;"><label for="as_raumnr" style="display:block;width:auto;">Raumnummer<span class="right"><?php if ($this->_tpl_vars['ASConf']['raumnr']['required']): ?><span class="required">*</span><?php endif; ?></span></label></td>
    <td style="padding:0;"><input type="text" id="as_raumnr" xreadonly="true" <?php if ($this->_tpl_vars['ASConf']['raumnr']['required']): ?>required="required"<?php endif; ?> xonclick="get_standort_raumnr(this)" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['raumnr'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[raumnr]" class="itxt itxt2col"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="as_kostenstelle" style="display:block;width:auto;"><?php echo $this->_tpl_vars['ASConf']['kostenstelle']['label']; ?>
<span class="right"><?php if ($this->_tpl_vars['ASConf']['kostenstelle']['required']): ?><span class="required">*</span><?php endif; ?></span></label></td>
    <td style="padding:0;"><input type="text" id="as_kostenstelle" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['kostenstelle'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[kostenstelle]"<?php if ($this->_tpl_vars['ASConf']['kostenstelle']['required']): ?> required="required"<?php endif; ?> class="itxt itxt2col"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="as_planonnr" style="display:block;width:auto;"><?php echo $this->_tpl_vars['ASConf']['planonnr']['label']; ?>
<span class="right"><?php if ($this->_tpl_vars['ASConf']['planonnr']['required']): ?><span class="required">*</span><?php endif; ?></span></label></td>
    <td style="padding:0;"><input type="text" id="as_planonnr" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['planonnr'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php if ($this->_tpl_vars['ASConf']['planonnr']['required']): ?> required="required"<?php endif; ?> name="AS[planonnr]" class="itxt itxt2col"></td>
  </tr>
  
  <tr>
    <td style="padding:0;"><label for="as_terminwunsch" style="display:block;width:auto;">Terminwunsch<span class="right"><?php if ($this->_tpl_vars['ASConf']['terminwunsch']['required']): ?><span class="required">*</span><?php endif; ?></span></label></td>
    <td style="padding:0;"><input type="text" id="as_terminwunsch" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['AS']['terminwunsch'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>
" <?php if ($this->_tpl_vars['ASConf']['terminwunsch']['required']): ?>required="required"<?php endif; ?>
	onfocus="showDtPicker(this)" id="terminwunsch" name="AS[terminwunsch]" class="itxt itxt2col jtooltip" rel="%WebRoot%hilfetexte/terminwunsch.php"></td>
  </tr>
  <tr>
    <td style="padding:0;"><label for="as_umzugszeit" style="display:block;width:auto;">Uhrzeit<span class="right"><?php if ($this->_tpl_vars['ASConf']['umzugszeit']['required']): ?><span class="required">*</span><?php endif; ?></span></label></td>
    <td style="padding:0;"><input type="text" id="as_umzugszeit" value='<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['AS']['umzugszeit'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('substr', true, $_tmp, 0, 5) : substr($_tmp, 0, 5)); ?>
' <?php if ($this->_tpl_vars['ASConf']['umzugszeit']['required']): ?>required="required"<?php endif; ?>
        id="umzugszeit" name="AS[umzugszeit]" class="itxt itxt2col"></td>
  </tr>
	<!-- SelBox_initLSDefault(initInputObj, initLSName, initMultiple, initLSSearchFields, initLSInputFields) -->
  <tr>
    <td style="padding:0;"><label for="von_gebaeude_text" style="display:block;width:auto;">Von<span class="right"><?php if ($this->_tpl_vars['ASConf']['von_gebaeude_id']['required']): ?><span class="required">*</span><?php endif; ?></span></label>
        <input type="hidden" readonly="readonly" id="von_gebaeude_id" <?php if ($this->_tpl_vars['ASConf']['von_gebaeude_id']['required']): ?>required="required"<?php endif; ?>
               value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['von_gebaeude_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="AS[von_gebaeude_id]">
    </td>
    <td class="ort">
        <input onclick="get_gebaeude(this, O('von_gebaeude_id'))" id="von_gebaeude_text" type="text" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['von_gebaeude_text'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" xreadonly="readonly" class="itxt itxt2col">
    </td>
  </tr>
	<!-- SelBox_initLSDefault(initInputObj, initLSName, initMultiple, initLSSearchFields, initLSInputFields) -->
  <tr>
    <td style="padding:0;"><label for="nach_gebauede_text" style="display:block;width:auto;">Nach<span class="right"><?php if ($this->_tpl_vars['ASConf']['nach_gebaeude_id']['required']): ?><span class="required">*</span><?php endif; ?></span></label>
        <input type="hidden" id="nach_gebaeude_id" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['nach_gebaeude_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php if ($this->_tpl_vars['ASConf']['nach_gebaeude_id']['required']): ?>required="required"<?php endif; ?>
               name="AS[nach_gebaeude_id]">
    </td>
    <td class="zort">
        <input onclick="get_gebaeude(this, O('nach_gebaeude_id'))" id="nach_gebaeude_text" type="text" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['nach_gebaeude_text'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" xreadonly="readonly" class="itxt itxt2col">
    </td>
  </tr>
</table>
<br>
<h2 style="margin:0;">Ansprechpartner vor Ort <span class="infolink" id="copyASDataToAPVorOrt">(Angaben vom Antragsteller übernehmen)</span></h2> 
<table>
  <tr>
      <td style="padding:0;width:200px;"><label for="as_ansprechpartner" title="Ansprechpartner Name" style="display:block;width:auto;">Vor &amp; Nachname<span class="right"><?php if ($this->_tpl_vars['ASConf']['ansprechpartner']['required']): ?><span class="required">*</span><?php endif; ?></span></label></td>
      <td style="padding:0;width:250px;"><input type="text" id="as_ansprechpartner" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['ansprechpartner'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php if ($this->_tpl_vars['ASConf']['ansprechpartner']['required']): ?>required="required"<?php endif; ?> name="AS[ansprechpartner]" class="itxt itxt2col" title="Ansprechpartner"></td>
      <td rowspan="3" class="cell-buttons">
          <?php echo '<a href="{WebRoot}downloads/leistungen.xls" target="_blank"><div class="button" id="filedownload" data-href="{WebRoot}/download/leistungen.xls"><span>Download Vorlage</span></div></a>'; ?>

<!--          <button onclick="umzugsantrag_add_attachement()"><span>Upload Dokument</span></button>-->
          
          <div class="button" id="fileuploader">Upload Dokument</div>
      </td>
  </tr>
  <tr>
      <td style="padding:0;"><label for="as_ansprech_email" title="Ansprechpartner E-Mail" style="display:block;width:auto;">E-Mail<span class="right"><?php if ($this->_tpl_vars['ASConf']['ansprechpartner_email']['required']): ?><span class="required">*</span><?php endif; ?></span></label></td>
    <td style="padding:0;"><input type="text" id="as_ansprech_email" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['ansprechpartner_email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php if ($this->_tpl_vars['ASConf']['ansprechpartner_email']['required']): ?>required="required"<?php endif; ?> name="AS[ansprechpartner_email]" class="itxt itxt2col" title="Ansprechpartner E-Mail"></td>
  </tr>
  <tr>
      <td style="padding:0;"><label for="as_ansprech_fon" title="Ansprechpartner Fon" style="display:block;width:auto;">Fon<span class="right"><?php if ($this->_tpl_vars['ASConf']['ansprechpartner_fon']['required']): ?><span class="required">*</span><?php endif; ?></span></label></td>
    <td style="padding:0;"><input type="text" id="as_ansprech_fon" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['ansprechpartner_fon'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php if ($this->_tpl_vars['ASConf']['ansprechpartner_fon']['required']): ?>required="required"<?php endif; ?> name="AS[ansprechpartner_fon]" class="itxt itxt2col jtooltip" rel="%WebRoot%hilfetexte/antrag_fon.php"></td>
  </tr>
</table>

<table>
  <tr>
      <td style="padding:0;width:200px;"><label for="as_umzug" title="Umzug" style="display:block;width:auto;">Umzug<span class="right"><?php if ($this->_tpl_vars['ASConf']['umzug']['required']): ?><span class="required">*</span><?php endif; ?></span></label></td>
      <td style="padding:0;width:250px;" class="options-onoff">
          <?php echo smarty_function_html_radios(array('name' => "AS[umzug]",'options' => $this->_tpl_vars['umzug_options'],'checked' => $this->_tpl_vars['AS']['umzug'],'separator' => ' '), $this);?>

      </td>
  </tr>
</table>
<script>
<?php echo '
$(document).ready(function() {
    
        $("td.options-onoff input:radio")
        .each(function(){
            $(this).closest("label").addClass( ($(this).val().match(/ja|on/i)!==null) ? "on" : "off");
        })
        .bind("change", function() {
           $(this).closest(".options-onoff").find("label.active").removeClass("active");
           $(this).closest("label").toggleClass("active", $(this)[0].checked);
        });
        
        $("#filedownload").click(function(e){
            //e.preventDefault(); 
        });
        
        $("#copyASDataToAPVorOrt")
            .css({cursor:"pointer","fontSize":"11px"})
            .click(function(e){
                //name
                $("#as_ansprechpartner").val($(\'#as_vornachname\').val() + " " + $(\'#as_name\').val());
                //email
                $("#as_ansprech_email").val($(\'#as_email\').val());
                //fon
                $("#as_ansprech_fon").val($(\'#as_fon\').val());
            });
    
        var uploadFileOpts = {
            url:"./sites/umzugsantrag_add_attachement.php",
            fileName:"uploadfile",
            formData: {
                aid:$(\'input[name="AS[aid]"\').val(),
                token:$(\'input[name="AS[token]"\').val(),
                response:\'json\',
                MAX_FILE_SIZE:1024*1024
            },
            onSuccess: function(files,data,xhr,pd) {
                window.lastResponseData = data;
                if (typeof(data) === "string") data = JSON.parse(data);
                if (0) alert("Datei wurde hochgeladen!\\n" +
                      "files: " + JSON.stringify(files) + "\\n\\n" +
                      "data: " + JSON.stringify(data) + "\\n\\n" +
                      "pd: " + JSON.stringify(pd) + "\\n\\n" +
                      "data.name: " + data.name + "\\n\\n" +
                      "data.size: " + data.size + "\\n\\n" +
                      "data.date: " + data.date );
                
                $("#attachments_list .row.names.hidden").removeClass("hidden");
                $("#attachments_list ul.ulAttachements").append(
                     $("<li/>").addClass("row values")
                        .append( $("<span>").addClass("col fname").text(data.name) )
                        .append( $("<span>").addClass("col fsize").text(data.size) )
                        .append( $("<span>").addClass("col fdate").text(data.date) )
                );
            }
        };
        
	$("#fileuploader").uploadFile($.extend({}, uploadFileOpts, {
            uploadStr: $("#fileuploader").text(),
            dragDrop: false,
            showQueueDiv:true,
            uploadButtonClass:""         
	}));
});
'; ?>

</script>
<br clear="all" >
<!-- test 1.2.3 -->
<?php if (0): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "umzugsformular_mitarbeiterauswahl.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<?php if (0): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "umzugsformular_geraeteauswahl.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<?php if (0): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "umzugsformular_ortsauswahl.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['user']['gruppe'] == 'admin' || $this->_tpl_vars['user']['gruppe'] == 'kunde_report'): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "umzugsformular_leistungsauswahl.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<div style="width:550px;">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "umzugsformular_attachments.tpl.read.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
<!-- <div style="color:#549e1a;font-weight:bold;text-decoration:none;cursor:pointer;" onclick="addMa();return false;">Weiteren Mitarbeiter ausw&auml;hlen <img align="absmiddle" src="images/hinzufuegen_off.png" width="14" alt=""></div><br> -->
<br>
<div id="BoxBemerkungen">
<strong>Bemerkungen</strong><br>
<textarea class="iarea bemerkungen" name="AS[bemerkungen]"></textarea>
</div>
<div style="margin-top:20px;">
<input type="submit" name="CatchDefaultEnterReturnFalse" onclick="return false;" value="" style="display:none;border:0;background:#fff;color:#fff;position:relative;left:-500px;">
<input class="btn grey" type="submit" onclick="umzugsantrag_save_notsend()" xstyle="padding:0 0 9px 0;background:url(images/BtnGrey.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Speichern">
<input class="btn green" type="submit" onclick="umzugsantrag_send()" xstyle="padding:0 0 9px 0;background:url(images/BtnGreen_200.png) bottom left no-repeat;border:0;width:200px;height:24px;font-size:12px;color:#fff;font-weight:bold;" title="<?php if ($this->_tpl_vars['creator'] == 'property'): ?>An M <?php elseif ($this->_tpl_vars['creator'] == 'mertens'): ?>An Property <?php endif; ?>senden" value="Senden">

<input type="submit" onclick="umzugsantrag_storno()" class="<?php if (empty ( $this->_tpl_vars['AS']['aid'] )): ?>hide-not-saved<?php else: ?>btn red <?php endif; ?>" xstyle="padding:0 0 9px 0;background:url(images/BtnRed_160.png) bottom left no-repeat;border:0;width:160px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Antrag stornieren">
<input type="submit" onclick="umzugsantrag_reload()" class="<?php if (empty ( $this->_tpl_vars['AS']['aid'] )): ?>hide-not-saved<?php else: ?>btn blue<?php endif; ?>" xtyle="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Neu laden">
<input type="submit" onclick="umzugsantrag_add_attachement()" class="<?php if (empty ( $this->_tpl_vars['AS']['aid'] )): ?>hide-not-saved<?php else: ?>btn grey<?php endif; ?>" xstyle="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" value="Dateianhänge"></div>

<!-- Debug-Btn:
<input type="submit" onclick="return umzugsantrag_submit_debug('speichern')" style="padding:0 0 9px 0;background:url(images/BtnGrey.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" name="cmd" value="speichern">
<input type="submit" onclick="return umzugsantrag_submit_debug('senden')" style="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" name="cmd" value="senden">
<input type="submit" onclick="return umzugsantrag_submit_debug('stornieren')" style="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" name="cmd" value="stornieren">
<input type="submit" onclick="return umzugsantrag_submit_debug('laden')" style="padding:0 0 9px 0;background:url(images/BtnRed.png) bottom left no-repeat;border:0;width:90px;height:24px;font-size:12px;color:#fff;font-weight:bold;" name="cmd" value="laden">
 -->
<div id="BoxBemerkungenHistorie">
<strong>Bisherige Bemerkungen</strong><br>
<div id="BemerkungenHistorie"><?php echo ((is_array($_tmp=$this->_tpl_vars['AS']['bemerkungen'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</div>
</div>
</form>
</div>
<div id="LoadingBar"></div>
</div> 