<?php /* Smarty version 2.6.26, created on 2015-12-21 01:10:00
         compiled from kantraege_liste.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'kantraege_liste.html', 41, false),array('modifier', 'date_format', 'kantraege_liste.html', 60, false),)), $this); ?>
<!-- TAB BASIC MODULE (128585) BEGIN --> 
<!-- INITIAL JS BEGIN --> 
<script type="text/javascript"> 
  //<?php echo '
  $(function() {
    //$(\'#ID128585\').tabs({fxFade: true, fxSpeed: \'fast\' });
  });
  //'; ?>

</script> 
<!-- INITIAL JS END --> 
 
<!-- TAB NAVIGATION ITEMS BEGIN --> 
<div id="ID128585" class="divTabbedNavigation" style="width:100%;"> 
<div class="divTabbedList" style="width:100%;"> 
<ul> 
        <li <?php if ($this->_tpl_vars['cat'] == 'bearbeitung'): ?>class="activeTab"<?php endif; ?>><a href="?s=<?php echo $this->_tpl_vars['s']; ?>
&cat=bearbeitung" style="width:160px">Noch nicht Gesendete</a></li> 
        <li <?php if ($this->_tpl_vars['cat'] == 'zurueckgegeben'): ?>class="activeTab"<?php endif; ?>><a href="?s=<?php echo $this->_tpl_vars['s']; ?>
&cat=zurueckgegeben" style="width:160px">Zu korrigierende Antr&auml;ge</a></li> 
        <li <?php if ($this->_tpl_vars['cat'] == 'gesendet'): ?>class="activeTab"<?php endif; ?>><a href="?s=<?php echo $this->_tpl_vars['s']; ?>
&cat=gesendet" style="width:180px">Gesendet und in Bearbeitung</a></li>
        <li <?php if ($this->_tpl_vars['cat'] == 'aktiv'): ?>class="activeTab"<?php endif; ?>><a href="?s=<?php echo $this->_tpl_vars['s']; ?>
&cat=aktiv" style="width:160px">Aktiv</a></li>
        <li <?php if ($this->_tpl_vars['cat'] == 'geschlossen'): ?>class="activeTab"<?php endif; ?>><a href="?s=<?php echo $this->_tpl_vars['s']; ?>
&cat=geschlossen" style="width:140px">Abgeschlossen</a></li>
        <li><a href="#ID128586" style="width:0px;display:none;">&nbsp;</a></li><!--  -->
        <!-- <li><a href="#ID128587">Neue Anträge</a></li> 
        <li><a href="#ID128586">Aktive Umzüge</a></li>
        <li><a href="#ID128586">Abgeschlossene Umzüge / Bewertungen</a></li>
        <li><a href="#ID128586">Lagerbestand</a></li>
        <li><a href="#ID128586">Infos nachlegen</a></li>
        <li><a href="#ID128586">E-Mails an Beteiligte senden</a></li>
        <li><a href="#ID128586">Gesprächsnotizen</a></li> -->
</ul> 
<div class="clearLeft"></div> 
</div>
<!-- TAB NAVIGATION ITEMS END --> 
 
<!-- TAB ITEM (128587) BEGIN --> 
<div id="ID128587" class="divModuleBasic padding12px width5Col heightAuto"> 
<div class="divInlay noMarginBottom borderTop"> 
</div> 

<!-- Add-On Fließtext(dyn) ID: 128588 BEGIN --> 
<div class="divInlay borderTop"> 
<h2>Es liegen <?php echo count($this->_tpl_vars['Umzuege']); ?>
 <?php echo $this->_tpl_vars['catTitle']; ?>
 Leistungsanforderungen vor</h2>
<?php if ($this->_tpl_vars['ListBrowsing']): ?><?php echo $this->_tpl_vars['ListBrowsing']; ?>
<?php endif; ?>
  <ul class="ulLinkList"> 
  <div>
  <div style="float:left;display:block;width:30px;font-weight:bold;color:#00869c;"><a href="<?php echo $this->_tpl_vars['ListBaseLink']; ?>
&ofld=id<?php if ($this->_tpl_vars['ofld'] == 'id' && $this->_tpl_vars['odir'] != 'DESC'): ?>&odir=DESC<?php endif; ?>">ID</a></div>
  <div style="float:left;display:block;width:100px;font-weight:bold;color:#00869c;"><a href="<?php echo $this->_tpl_vars['ListBaseLink']; ?>
&ofld=termin<?php if ($this->_tpl_vars['ofld'] == 'termin' && $this->_tpl_vars['odir'] != 'DESC'): ?>&odir=DESC<?php endif; ?>">Termin</a></div>
  <div style="float:left;display:block;width:100px;font-weight:bold;color:#00869c;"><a href="<?php echo $this->_tpl_vars['ListBaseLink']; ?>
&ofld=von<?php if ($this->_tpl_vars['ofld'] == 'von' && $this->_tpl_vars['odir'] != 'DESC'): ?>&odir=DESC<?php endif; ?>">Von</a></div>
  <div style="float:left;display:block;width:100px;font-weight:bold;color:#00869c;"><a href="<?php echo $this->_tpl_vars['ListBaseLink']; ?>
&ofld=nach<?php if ($this->_tpl_vars['ofld'] == 'nach' && $this->_tpl_vars['odir'] != 'DESC'): ?>&odir=DESC<?php endif; ?>">Nach</a></div>
  <div style="float:left;display:block;width:75px;font-weight:bold;color:#00869c;"><a href="<?php echo $this->_tpl_vars['ListBaseLink']; ?>
&ofld=ort<?php if ($this->_tpl_vars['ofld'] == 'ort' && $this->_tpl_vars['odir'] != 'DESC'): ?>&odir=DESC<?php endif; ?>">Auftragsort</a></div>
  <div style="float:left;display:block;width:75px;font-weight:bold;color:#00869c;"><a href="<?php echo $this->_tpl_vars['ListBaseLink']; ?>
&ofld=umzug<?php if ($this->_tpl_vars['ofld'] == 'umzug' && $this->_tpl_vars['odir'] != 'DESC'): ?>&odir=DESC<?php endif; ?>">Mit Umzug</a></div>
  <div style="float:left;display:block;width:100px;font-weight:bold;color:#00869c;"><a href="<?php echo $this->_tpl_vars['ListBaseLink']; ?>
&ofld=antragsdatum<?php if ($this->_tpl_vars['ofld'] == 'antragsdatum' && $this->_tpl_vars['odir'] != 'DESC'): ?>&odir=DESC<?php endif; ?>">Auftragseingang</a></div>
  <div style="float:left;display:block;width:90px;font-weight:bold;color:#00869c;"><a href="<?php echo $this->_tpl_vars['ListBaseLink']; ?>
&ofld=genehmigt<?php if ($this->_tpl_vars['ofld'] == 'genehmigt' && $this->_tpl_vars['odir'] != 'DESC'): ?>&odir=DESC<?php endif; ?>" title="Genehmigt">G</a></div>
  <div style="float:left;display:block;width:90px;font-weight:bold;color:#00869c;"><a href="<?php echo $this->_tpl_vars['ListBaseLink']; ?>
&ofld=geprueft<?php if ($this->_tpl_vars['ofld'] == 'geprueft' && $this->_tpl_vars['odir'] != 'DESC'): ?>&odir=DESC<?php endif; ?>" title="von merTens geprueft">M</a></div>
  <div style="float:left;display:block;width:80px;font-weight:bold;color:#00869c;" title="Abgeschlossen"><a href="<?php echo $this->_tpl_vars['ListBaseLink']; ?>
&ofld=abgeschlossen<?php if ($this->_tpl_vars['ofld'] == 'abgeschlossen' && $this->_tpl_vars['odir'] != 'DESC'): ?>&odir=DESC<?php endif; ?>">Abgeschlossen</a></div><br clear=left></div>

<?php $_from = $this->_tpl_vars['Umzuege']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['U']):
?>
  <li><a class="iconRightContentMain" href="<?php echo $this->_tpl_vars['U']['LinkOpen']; ?>
">
  <div>
  <div style="float:left;display:block;width:30px;font-weight:bold;"><?php echo $this->_tpl_vars['U']['aid']; ?>
&nbsp;</div>
  <div style="float:left;display:block;width:100px;font-weight:bold;"><?php echo ((is_array($_tmp=$this->_tpl_vars['U']['Termin'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>
&nbsp;</div>
  <div style="float:left;display:block;width:100px;"><?php echo $this->_tpl_vars['U']['Von']; ?>
&nbsp;</div>
  <div style="float:left;display:block;width:100px;"><?php echo $this->_tpl_vars['U']['Nach']; ?>
&nbsp;</div>
  <div style="float:left;display:block;width:75px;"><?php echo $this->_tpl_vars['U']['ort']; ?>
&nbsp;</div>
  <div style="float:left;display:block;width:75px;"><?php echo $this->_tpl_vars['U']['umzug']; ?>
&nbsp;</div>
  <div style="float:left;display:block;width:100px;font-style:italic;"><?php echo ((is_array($_tmp=$this->_tpl_vars['U']['Antragsdatum'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>
&nbsp;</div>
  <div style="float:left;display:block;width:90px;"><?php echo $this->_tpl_vars['U']['Genehmigt']; ?>
&nbsp;</div>
  <div style="float:left;display:block;width:90px;"><?php echo $this->_tpl_vars['U']['Geprueft']; ?>
&nbsp;</div>
  <div style="float:left;display:block;width:80px;"><?php echo $this->_tpl_vars['U']['Abgeschlossen']; ?>
 <?php if ($this->_tpl_vars['U']['abgeschlossen_am']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['U']['abgeschlossen_am'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>
<?php endif; ?></div><br clear=left></div>
  </a></li>
<?php endforeach; endif; unset($_from); ?>
  </ul> 
<!-- <h2>Startseite Mertens AG</h2> 
<p>
- Infos nachlegen
- Mails an Beteiligte verschicken
- Gesprächsnotizen
- Lagerbestand abfragen</p> 
</div> --> 
<!-- Add-On Fließtext(dyn) ID: 128588 END --> 
</div> 
<!-- TAB ITEM (128587) END --> 
 
<br class="floatNone" /> 
</div> 
<!-- TAB BASIC MODULE (128585) END -->
