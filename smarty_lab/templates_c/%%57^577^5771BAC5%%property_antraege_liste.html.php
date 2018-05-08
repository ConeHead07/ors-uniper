<?php /* Smarty version 2.6.26, created on 2016-01-12 02:01:10
         compiled from property_antraege_liste.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'property_antraege_liste.html', 60, false),)), $this); ?>
<!-- TAB BASIC MODULE (128585) BEGIN --> 
<!-- INITIAL JS BEGIN --> 
<script type="text/javascript"> 
  //<?php echo '
  //$(function() {
    //$(\'#ID128585\').tabs({fxFade: true, fxSpeed: \'fast\' });
  //});
  //'; ?>

</script> 
<!-- INITIAL JS END --> 

<!-- TAB NAVIGATION ITEMS BEGIN --> 
<div id="ID128585" class="divTabbedNavigation" style="width:100%;"> 
<div class="divTabbedList" style="width:100%;"> 
<ul> 
        <li <?php if ($this->_tpl_vars['cat'] == 'neue'): ?>class="activeTab"<?php endif; ?>><a href="?s=<?php echo $this->_tpl_vars['s']; ?>
&cat=neue<?php if ($this->_tpl_vars['allusers']): ?>&allusers=1<?php endif; ?>" style="width:170px">Neue Antr&auml;ge</a></li> 
        <li <?php if ($this->_tpl_vars['cat'] == 'bearbeitung'): ?>class="activeTab"<?php endif; ?>><a href="?s=<?php echo $this->_tpl_vars['s']; ?>
&cat=bearbeitung<?php if ($this->_tpl_vars['allusers']): ?>&allusers=1<?php endif; ?>" style="width:170px">Neue Antr&auml;ge von merTens</a></li>
        <li <?php if ($this->_tpl_vars['cat'] == 'aktive'): ?>class="activeTab"<?php endif; ?>><a href="?s=<?php echo $this->_tpl_vars['s']; ?>
&cat=aktive<?php if ($this->_tpl_vars['allusers']): ?>&allusers=1<?php endif; ?>" style="width:170px">Aktive Antr&auml;ge</a></li><!-- Genehmigt -->
        <li <?php if ($this->_tpl_vars['cat'] == 'abgelehnte'): ?>class="activeTab"<?php endif; ?>><a href="?s=<?php echo $this->_tpl_vars['s']; ?>
&cat=abgelehnte<?php if ($this->_tpl_vars['allusers']): ?>&allusers=1<?php endif; ?>" style="width:70px;color:#ffd700;">Abgelehnt</a></li>
        <li <?php if ($this->_tpl_vars['cat'] == 'abgeschlossene'): ?>class="activeTab"<?php endif; ?>><a href="?s=<?php echo $this->_tpl_vars['s']; ?>
&cat=abgeschlossene<?php if ($this->_tpl_vars['allusers']): ?>&allusers=1<?php endif; ?>" style="width:90px">Abgeschlossen</a></li>
        <li <?php if ($this->_tpl_vars['cat'] == 'stornierte'): ?>class="activeTab"<?php endif; ?>><a href="?s=<?php echo $this->_tpl_vars['s']; ?>
&cat=stornierte<?php if ($this->_tpl_vars['allusers']): ?>&allusers=1<?php endif; ?>" style="width:60px">Storno</a></li>
        <li><a href="#ID128586" style="width:50px">&nbsp;</a></li><!--  -->
</ul><br clear="all">
</div>
<!-- TAB NAVIGATION ITEMS END --> 
 
<!-- TAB ITEM (128587) BEGIN --> 
<div id="ID128587" class="divModuleBasic padding12px width5Col heightAuto"> 
<div class="divInlay noMarginBottom borderTop"> 
</div> 

<!-- Add-On Fließtext(dyn) ID: 128588 BEGIN --> 
<div class="divInlay borderTop"> 
    <div style="float:right;padding:10px 20px 0 0;">
        <a href="<?php echo $this->_tpl_vars['ListBaseLink']; ?>
&ofld=id&odir=<?php echo $this->_tpl_vars['odir']; ?>
&allusers=<?php if ($this->_tpl_vars['allusers']): ?>0<?php else: ?>1<?php endif; ?>">Wechsel zu <?php if ($this->_tpl_vars['allusers']): ?>meine Antr&auml;ge<?php else: ?>Antr&auml;gen aller User<?php endif; ?></a>
    </div>
<?php if ($this->_tpl_vars['cat'] == 'bearbeitung'): ?>
<h2>Es liegen <?php echo $this->_tpl_vars['num_all']; ?>
 Leistungsanforderungen zur Bearbeitung vor</h2>
<?php else: ?>
<h2>Es liegen <?php echo $this->_tpl_vars['num_all']; ?>
 <?php echo $this->_tpl_vars['cat']; ?>
 Leistungsanforderungen vor</h2>
<?php endif; ?>
<div style="clear:both"></div>
<?php if ($this->_tpl_vars['ListBrowsing']): ?><?php echo $this->_tpl_vars['ListBrowsing']; ?>
<?php endif; ?>
  <ul class="ulLinkList"> 
  <div>
  <div style="float:left;display:block;width:30px;font-weight:bold;color:#00869c;"><a href="<?php echo $this->_tpl_vars['ListBaseLink']; ?>
&ofld=id<?php if ($this->_tpl_vars['ofld'] == 'id' && $this->_tpl_vars['odir'] != 'DESC'): ?>&odir=DESC<?php endif; ?>">ID</a></div>
  <div style="float:left;display:block;width:100px;font-weight:bold;color:#00869c;"><a href="<?php echo $this->_tpl_vars['ListBaseLink']; ?>
&ofld=termin<?php if ($this->_tpl_vars['ofld'] == 'termin' && $this->_tpl_vars['odir'] != 'DESC'): ?>&odir=DESC<?php endif; ?>">Termin</a></div>
  <div style="float:left;display:block;width:130px;font-weight:bold;color:#00869c;"><a href="<?php echo $this->_tpl_vars['ListBaseLink']; ?>
&ofld=von<?php if ($this->_tpl_vars['ofld'] == 'von' && $this->_tpl_vars['odir'] != 'DESC'): ?>&odir=DESC<?php endif; ?>">Von</a></div>
  <div style="float:left;display:block;width:130px;font-weight:bold;color:#00869c;"><a href="<?php echo $this->_tpl_vars['ListBaseLink']; ?>
&ofld=nach<?php if ($this->_tpl_vars['ofld'] == 'nach' && $this->_tpl_vars['odir'] != 'DESC'): ?>&odir=DESC<?php endif; ?>">Nach</a></div>
  <div style="float:left;display:block;width:75px;font-weight:bold;color:#00869c;"><a href="<?php echo $this->_tpl_vars['ListBaseLink']; ?>
&ofld=ort<?php if ($this->_tpl_vars['ofld'] == 'ort' && $this->_tpl_vars['odir'] != 'DESC'): ?>&odir=DESC<?php endif; ?>">Auftragsort</a></div>
  <div style="float:left;display:block;width:75px;font-weight:bold;color:#00869c;"><a href="<?php echo $this->_tpl_vars['ListBaseLink']; ?>
&ofld=umzug<?php if ($this->_tpl_vars['ofld'] == 'umzug' && $this->_tpl_vars['odir'] != 'DESC'): ?>&odir=DESC<?php endif; ?>">Mit Umzug</a></div>
  <div style="float:left;display:block;width:100px;font-weight:bold;color:#00869c;"><a href="<?php echo $this->_tpl_vars['ListBaseLink']; ?>
&ofld=antragsdatum<?php if ($this->_tpl_vars['ofld'] == 'antragsdatum' && $this->_tpl_vars['odir'] != 'DESC'): ?>&odir=DESC<?php endif; ?>">Auftragseingang</a></div>
  <div style="float:left;display:block;width:35px;font-weight:bold;color:#00869c;" title="Genehmigt"><a href="<?php echo $this->_tpl_vars['ListBaseLink']; ?>
&ofld=genehmigt<?php if ($this->_tpl_vars['ofld'] == 'genehmigt_br' && $this->_tpl_vars['odir'] != 'DESC'): ?>&odir=DESC<?php endif; ?>">G</a></div>
  <div style="float:left;display:block;width:35px;font-weight:bold;color:#00869c;" title="Von merTens geprueft"><a href="<?php echo $this->_tpl_vars['ListBaseLink']; ?>
&ofld=bestaetigt<?php if ($this->_tpl_vars['ofld'] == 'bestaetigt' && $this->_tpl_vars['odir'] != 'DESC'): ?>&odir=DESC<?php endif; ?>">M</a></div>
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
  <div style="float:left;display:block;width:130px;"><?php echo $this->_tpl_vars['U']['Von']; ?>
&nbsp;</div>
  <div style="float:left;display:block;width:130px;"><?php echo $this->_tpl_vars['U']['Nach']; ?>
&nbsp;</div>
  <div style="float:left;display:block;width:75px;"><?php echo $this->_tpl_vars['U']['ort']; ?>
&nbsp;</div>
  <div style="float:left;display:block;width:75px;"><?php echo $this->_tpl_vars['U']['umzug']; ?>
&nbsp;</div>
  <div style="float:left;display:block;width:100px;font-style:italic;"><?php echo ((is_array($_tmp=$this->_tpl_vars['U']['Antragsdatum'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>
&nbsp;</div>
  <div style="float:left;display:block;width:35px;"><?php echo $this->_tpl_vars['U']['Genehmigt']; ?>
&nbsp;</div>
  <div style="float:left;display:block;width:35px;"><?php echo $this->_tpl_vars['U']['Geprueft']; ?>
&nbsp;</div>
  <div style="float:left;display:block;width:80px;"><?php echo $this->_tpl_vars['U']['Abgeschlossen']; ?>
 <?php if ($this->_tpl_vars['U']['abgeschlossen_am']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['U']['abgeschlossen_am'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%y") : smarty_modifier_date_format($_tmp, "%d.%m.%y")); ?>
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
