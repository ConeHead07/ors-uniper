<?php 

// Struktur des zweidimensionalen Arrays validFields
// $validFields[inputFldname][0]  = Alias-Name
// $validFields[inputFldname][1]  = Default
// $validFields[inputFldname][2]  = Pflichteingabe
// $validFields[inputFldname][3]  = MyDB::fldname
// $validFields[inputFldname][4]  = special_features
// $validFields[inputFldname][5]  = tplFeldlabel
// $validFields[inputFldname][6]  = tplPflichtfeldkennzeichnung
// $validFields[inputFldname][7]  = tplWarnungAnzeigen
// $validFields[inputFldname][8]  = tplFeldwertLesen
// $validFields[inputFldname][9]  = tplFeldwertEingabe
// $validFields[inputFldname][10] = isUnique
// $validFields[inputFldname][11] = check_input

$tbl_defaultOrderFeld = "ordnungszahl";
if (!isset($_TABLE["cms_texte"])) $_TABLE["cms_texte"] = "mm_cms_texte";

$validFields[$_TABLE["cms_texte"]]=array(
		"common_lang_key"=>array("Notation",				    // 0 = Alias
						"",										// 1 = Default
						0,										// 2 = Pflicht
						"common_lang_key",						// 3 = MySQL-DB-Feld
						0,										// 4 = Specialfeatures
						"%strcommon_lang_key%",					// 5 = tplFeldlabel
						"<!-- %strcommon_lang_keyPflicht*% -->",// 6 = tplPflichtmarke
						"<!-- %common_lang_keyAlert% -->",		// 7 = tplWarning
						"%lesen[common_lang_key]%",				// 8 = tplFeldwertLesen
						"%eingabe[common_lang_key]%",			// 9 = tplFeldwertEingabe
						false,									// 10 = isUnique
						0,										// 11 = check Input
						1),										// 12 = zeilen
						
		"lang"=>array("Sprachschl�ssel",				    	// 0 = Alias
						"DE",									// 1 = Default
						0,										// 2 = Pflicht
						"lang",									// 3 = MySQL-DB-Feld
						0,										// 4 = Specialfeatures
						"%strlang%",							// 5 = tplFeldlabel
						"<!-- %strlangPflicht*% -->",			// 6 = tplPflichtmarke
						"<!-- %langAlert% -->",					// 7 = tplWarning
						"%lesen[lang]%",						// 8 = tplFeldwertLesen
						"%eingabe[lang]%",						// 9 = tplFeldwertEingabe
						false,									// 10 = isUnique
						0,										// 11 = check Input
						1),										// 12 = zeilen
						
		"notation"=>array("Notation",				    		// 0 = Alias
						"",										// 1 = Default
						1,										// 2 = Pflicht
						"notation",								// 3 = MySQL-Seitenbereich
						0,										// 4 = Specialfeatures
						"%strnotation%",						// 5 = tplFeldlabel
						"<!-- %strnotationPflicht*% -->",		// 6 = tplPflichtmarke
						"<!-- %notationAlert% -->",				// 7 = tplWarning
						"%lesen[notation]%",					// 8 = tplFeldwertLesen
						"%eingabe[notation]%",					// 9 = tplFeldwertEingabe
						false,									// 10 = isUnique
						0,										// 11 = check Input
						1),										// 12 = zeilen
						
		"seitenbereich"=>array("Seitenbereich",				    // 0 = Alias
						(isset($srv) ? $srv : ""),				// 1 = Default
						1,										// 2 = Pflicht
						"seitenbereich",						// 3 = MySQL-Seitenbereich
						0,										// 4 = Specialfeatures
						"%strSeitenbereich%",					// 5 = tplFeldlabel
						"<!-- %strSeitenbereichPflicht*% -->",	// 6 = tplPflichtmarke
						"<!-- %seitenbereichAlert% -->",		// 7 = tplWarning
						"%lesen[seitenbereich]%",				// 8 = tplFeldwertLesen
						"%eingabe[seitenbereich]%",				// 9 = tplFeldwertEingabe
						false,									// 10 = isUnique
						1,										// 11 = check Input
						1),										// 12 = zeilen
						
		"seitenname"=>array("Seitenname",				        // 0 = Alias
						"",										// 1 = Default
						0,										// 2 = Pflicht
						"seitenname",							// 3 = MySQL-Seitenname
						0,										// 4 = Specialfeatures
						"%strSeitenname%",						// 5 = tplFeldlabel
						"<!-- %strSeitennamePflicht*% -->",	    // 6 = tplPflichtmarke
						"<!-- %seitennameAlert% -->",			// 7 = tplWarning
						"%lesen[seitenname]%",					// 8 = tplFeldwertLesen
						"%eingabe[seitenname]%",				// 9 = tplFeldwertEingabe
						false,									// 10 = isUnique
						1,										// 11 = check Input
						1),										// 12 = zeilen
						
		"antrailern"=>array("Antrailern",				        // 0 = Alias
						"Ja",									// 1 = Default
						1,										// 2 = Pflicht
						"antrailern",							// 3 = MySQL-Antrailern
						0,										// 4 = Specialfeatures
						"%strAntrailern%",						// 5 = tplFeldlabel
						"<!-- %strAntrailernPflicht*% -->",	    // 6 = tplPflichtmarke
						"<!-- %antrailernAlert% -->",			// 7 = tplWarning
						"%lesen[antrailern]%",					// 8 = tplFeldwertLesen
						"%eingabe[antrailern]%",				// 9 = tplFeldwertEingabe
						false,									// 10 = isUnique
						1,										// 11 = check Input
						1),										// 12 = zeilen
						
		"listentitel"=>array("Listentitel",						// 0 = Alias
						"",										// 1 = Default
						0,										// 2 = Pflicht
						"listentitel",							// 3 = MySQL-Listentitel
						0,										// 4 = Specialfeatures
						"%strListentitel%",						// 5 = tplFeldlabel
						"<!-- %strListentitelPflicht*% -->",	// 6 = tplPflichtmarke
						"<!-- %listentitelAlert% -->",			// 7 = tplWarning
						"%lesen[listentitel]%",					// 8 = tplFeldwertLesen
						"%eingabe[listentitel]%",				// 9 = tplFeldwertEingabe
						false,									// 10 = isUnique
						1,										// 11 = check Input
						1),										// 12 = zeilen
						
		"listentext"=>array("Startseite: Kurztext",				// 0 = Alias
						"",										// 1 = Default
						0,										// 2 = Pflicht
						"listentext",							// 3 = MySQL-Name
						0,										// 4 = Specialfeatures
						"%strText1%",							// 5 = tplFeldlabel
						"<!-- %strListentextPflicht*% -->",		// 6 = tplPflichtmarke
						"<!-- %listentextAlert% -->",			// 7 = tplWarning
						"%lesen[listentext]%",					// 8 = tplFeldwertLesen
						"%eingabe[listentext]%",				// 9 = tplFeldwertEingabe
						false,									// 10 = isUnique
						1,										// 11 = check Input
						1),										// 12 = zeilen
						
		"text2mehr"=>array("Text f�r -&gt;mehr ...",			// 0 = Alias
						"mehr",									// 1 = Default
						0,										// 2 = Pflicht
						"text2mehr",							// 3 = MySQL-Name
						0,										// 4 = Specialfeatures
						"%strText2mehr%",						// 5 = tplFeldlabel
						"<!-- %strText2mehrPflicht*% -->",		// 6 = tplPflichtmarke
						"<!-- %text2mehrAlert% -->",			// 7 = tplWarning
						"%lesen[text2mehr]%",					// 8 = tplFeldwertLesen
						"%eingabe[text2mehr]%",					// 9 = tplFeldwertEingabe
						false,									// 10 = isUnique
						1,										// 11 = check Input
						1),										// 12 = zeilen
						
		"linkzumvolltext"=>array("Link zum Volltext",			// 0 = Alias
						"Auto",									// 1 = Default
						1,										// 2 = Pflicht
						"linkzumvolltext",						// 3 = MySQL-Name
						0,										// 4 = Specialfeatures
						"%strLinkzumvolltext%",					// 5 = tplFeldlabel
						"<!-- %strLinkzumvolltextPflicht*% -->",// 6 = tplPflichtmarke
						"<!-- %linkzumvolltextAlert% -->",		// 7 = tplWarning
						"%lesen[linkzumvolltext]%",				// 8 = tplFeldwertLesen
						"%eingabe[linkzumvolltext]%",			// 9 = tplFeldwertEingabe
						false,									// 10 = isUnique
						1,										// 11 = check Input
						1),										// 12 = zeilen
						
		"media_src"=>array("Media/Video",						// 0 = Alias
						"",										// 1 = Default
						0,										// 2 = Pflicht
						"media_src",							// 3 = MySQL-Name
						1,										// 4 = Specialfeatures
						"%strMedia_src%",						// 5 = tplFeldlabel
						"<!-- %strMedia_srcPflicht*% -->",		// 6 = tplPflichtmarke
						"<!-- %titelAlert% -->",				// 7 = tplWarning
						"%lesen[media_src]%",					// 8 = tplFeldwertLesen
						"%eingabe[media_src]%",					// 9 = tplFeldwertEingabe
						false,									// 10 = isUnique
						0,										// 11 = check Input
						0),										// 12 = zeilen
						
		"media_params"=>array("Media/Videoeigenschaften",		// 0 = Alias
						"",										// 1 = Default
						0,										// 2 = Pflicht
						"media_params", 						// 3 = MySQL-Name
						0,										// 4 = Specialfeatures
						"%strMedia_params%",					// 5 = tplFeldlabel
						"<!-- %strMedia_paramsPflicht*% -->",	// 6 = tplPflichtmarke
						"<!-- %titelAlert% -->",				// 7 = tplWarning
						"%lesen[media_params]%",				// 8 = tplFeldwertLesen
						"%eingabe[media_params]%",				// 9 = tplFeldwertEingabe
						false,									// 10 = isUnique
						0,										// 11 = check Input
						0),										// 12 = zeilen
						
		"titel"=>array("Titel",									// 0 = Alias
						"",										// 1 = Default
						0,										// 2 = Pflicht
						"titel",								// 3 = MySQL-Name
						0,										// 4 = Specialfeatures
						"%strTitel%",							// 5 = tplFeldlabel
						"<!-- %strTitelPflicht*% -->",			// 6 = tplPflichtmarke
						"<!-- %titelAlert% -->",				// 7 = tplWarning
						"%lesen[titel]%",						// 8 = tplFeldwertLesen
						"%eingabe[titel]%",						// 9 = tplFeldwertEingabe
						false,									// 10 = isUnique
						1,										// 11 = check Input
						1),										// 12 = zeilen
						
		"volltext"=>array("Volltext",							// 0 = Alias
						"",										// 1 = Default
						0,										// 2 = Pflicht
						"volltext",								// 3 = MySQL-Name
						0,										// 4 = Specialfeatures
						"%strVolltext%",						// 5 = tplFeldlabel
						"<!-- %strVolltextPflicht*% -->",		// 6 = tplPflichtmarke
						"<!-- %volltextAlert% -->",				// 7 = tplWarning
						"%lesen[volltext]%",					// 8 = tplFeldwertLesen
						"%eingabe[volltext]%",					// 9 = tplFeldwertEingabe
						false,									// 10 = isUnique
						1,										// 11 = check Input
						1),										// 12 = zeilen
						
		"schlagwort"=>array("Schlagwort",						// 0 = Alias
						"",										// 1 = Default
						0,										// 2 = Pflicht
						"schlagwort",							// 3 = MySQL-Name
						0,										// 4 = Specialfeatures
						"%strSchlagwort%",						// 5 = tplFeldlabel
						"<!-- %strSchlagwortPflicht*% -->",		// 6 = tplPflichtmarke
						"<!-- %schlagwortAlert% -->",			// 7 = tplWarning
						"%lesen[schlagwort]%",					// 8 = tplFeldwertLesen
						"%eingabe[schlagwort]%",				// 9 = tplFeldwertEingabe
						false,									// 10 = isUnique
						1,										// 11 = check Input
						1),										// 12 = zeilen
						
		"deskriptor"=>array("Deskriptor",						// 0 = Alias
						"",										// 1 = Default
						0,										// 2 = Pflicht
						"deskriptor",							// 3 = MySQL-Name
						0,										// 4 = Specialfeatures
						"%strDeskriptor%",						// 5 = tplFeldlabel
						"<!-- %strDeskriptorPflicht*% -->",		// 6 = tplPflichtmarke
						"<!-- %deskriptor2Alert% -->",			// 7 = tplWarning
						"%lesen[deskriptor]%",					// 8 = tplFeldwertLesen
						"%eingabe[deskriptor]%",				// 9 = tplFeldwertEingabe
						false,									// 10 = isUnique
						1,										// 11 = check Input
						1),										// 12 = zeilen
						
		"classid"=>array("ClassID",								// 0 = Alias
						"",										// 1 = Default
						0,										// 2 = Pflicht
						"classid",								// 3 = MySQL-Name
						0,										// 4 = Specialfeatures
						"%strClassid%",							// 5 = tplFeldlabel
						"<!-- %strClassidPflicht*% -->",		// 6 = tplPflichtmarke
						"<!-- %classid2Alert% -->",				// 7 = tplWarning
						"%lesen[classid]%",						// 8 = tplFeldwertLesen
						"%eingabe[classid]%",					// 9 = tplFeldwertEingabe
						false,									// 10 = isUnique
						1,										// 11 = check Input
						1),										// 12 = zeilen
						
		"webfreigabe"=>array("Webfreigabe",						// 0 = Alias
						"Nein",									// 1 = Default
						0,										// 2 = Pflicht
						"webfreigabe",							// 3 = MySQL-Name
						0,										// 4 = Specialfeatures
						"%strWebfreigabe%",						// 5 = tplFeldlabel
						"<!-- %strWebfreigabePflicht*% -->",	// 6 = tplPflichtmarke
						"<!-- %webfreigabeAlert% -->",			// 7 = tplWarning
						"%lesen[webfreigabe]%",					// 8 = tplFeldwertLesen
						"%eingabe[webfreigabe]%",				// 9 = tplFeldwertEingabe
						false,									// 10 = isUnique
						1,										// 11 = check Input
						1),										// 12 = zeile
						
		"timer_datumvon"=>array("Timer:Datum von",				// 0 = Alias
						date("Y-m-d"),										// 1 = Default
						0,										// 2 = Pflicht
						"timer_datumvon",						// 3 = MySQL-Name
						0,										// 4 = Specialfeatures
						"%strTimer_datumvon%",					// 5 = tplFeldlabel
						"<!-- %strTimer_datumvonPflicht*% -->",	// 6 = tplPflichtmarke
						"<!-- %timer_datumvonAlert% -->",		// 7 = tplWarning
						"%lesen[timer_datumvon]%",				// 8 = tplFeldwertLesen
						"%eingabe[timer_datumvon]%",			// 9 = tplFeldwertEingabe
						false,									// 10 = isUnique
						1,										// 11 = check Input
						1),										// 12 = zeile
						
		"timer_datumbis"=>array("Timer:Datum bis",				// 0 = Alias
						"",										// 1 = Default
						0,										// 2 = Pflicht
						"timer_datumbis",						// 3 = MySQL-Name
						0,										// 4 = Specialfeatures
						"%strTimer_datumbis%",					// 5 = tplFeldlabel
						"<!-- %strTimer_datumbisPflicht*% -->",	// 6 = tplPflichtmarke
						"<!-- %timer_datumbisAlert% -->",		// 7 = tplWarning
						"%lesen[timer_datumbis]%",				// 8 = tplFeldwertLesen
						"%eingabe[timer_datumbis]%",			// 9 = tplFeldwertEingabe
						false,									// 10 = isUnique
						1,										// 11 = check Input
						1),										// 12 = zeile
						
		"timer_zeitvon"=>array("Timer:Zeit von",				// 0 = Alias
						"00:00:00",								// 1 = Default
						0,										// 2 = Pflicht
						"timer_zeitvon",						// 3 = MySQL-Name
						0,										// 4 = Specialfeatures
						"%strTimer_zeitvon%",					// 5 = tplFeldlabel
						"<!-- %strTimer_zeitvonPflicht*% -->",	// 6 = tplPflichtmarke
						"<!-- %timer_zeitvonAlert% -->",		// 7 = tplWarning
						"%lesen[timer_zeitvon]%",				// 8 = tplFeldwertLesen
						"%eingabe[timer_zeitvon]%",				// 9 = tplFeldwertEingabe
						false,									// 10 = isUnique
						1,										// 11 = check Input
						1),										// 12 = zeile
						
		"timer_zeitbis"=>array("Timer:Zeit bis",				// 0 = Alias
						"23:59:59",								// 1 = Default
						0,										// 2 = Pflicht
						"timer_zeitbis",						// 3 = MySQL-Name
						0,										// 4 = Specialfeatures
						"%strTimer_zeitbis%",					// 5 = tplFeldlabel
						"<!-- %strTimer_zeitbisPflicht*% -->",	// 6 = tplPflichtmarke
						"<!-- %timer_zeitbisAlert% -->",		// 7 = tplWarning
						"%lesen[timer_zeitbis]%",				// 8 = tplFeldwertLesen
						"%eingabe[timer_zeitbis]%",				// 9 = tplFeldwertEingabe
						false,									// 10 = isUnique
						1,										// 11 = check Input
						1),										// 12 = zeile
						
		"timer_wochentage"=>array("Timer:Wochentage",			// 0 = Alias
						"Mo,Di,Mi,Do,Fr,Sa,So",					// 1 = Default
						0,										// 2 = Pflicht
						"timer_wochentage",						// 3 = MySQL-Name
						0,										// 4 = Specialfeatures
						"%strTimer_wochentage%",				// 5 = tplFeldlabel
						"<!-- %strTimer_wochentagePflicht*% -->",	// 6 = tplPflichtmarke
						"<!-- %timer_wochentageAlert% -->",		// 7 = tplWarning
						"%lesen[timer_wochentage]%",			// 8 = tplFeldwertLesen
						"%eingabe[timer_wochentage]%",			// 9 = tplFeldwertEingabe
						false,									// 10 = isUnique
						1,										// 11 = check Input
						1),										// 12 = zeile
						
		"id"=>array("ID",										// 0 = Alias
						"",										// 1 = Default
						0,										// 2 = Pflicht
						"id",									// 3 = MySQL-Name
						0,										// 4 = Specialfeatures
						"%strId%",								// 5 = tplFeldlabel
						"<!-- %strIdPflicht*% -->",				// 6 = tplPflichtmarke
						"<!-- %idAlert% -->",					// 7 = tplWarning
						"%lesen[id]%",						    // 8 = tplFeldwertLesen
						"%eingabe[id]%",						// 9 = tplFeldwertEingabe
						false,									// 10 = isUnique
						0,										// 11 = check Input
						1),										// 12 = zeile
		
		"erstelltam"=>array("Erstellt am",					// 0 = Alias
						"",										// 1 = Default
						0,										// 2 = Pflicht
						"erstelltam",						// 3 = MySQL-Name
						0,										// 4 = Specialfeatures
						"%strErstelltam%",					// 5 = tplFeldlabel
						"<!-- %strErstelltamPflicht*% -->",	// 6 = tplPflichtmarke
						"<!-- %erstelltamAlert% -->",		// 7 = tplWarning
						"%lesen[erstelltam]%",				// 8 = tplFeldwertLesen
						"%eingabe[erstelltam]%",				// 9 = tplFeldwertEingabe
						false,									// 10 = isUnique
						0,										// 11 = check Input
						1),										// 12 = zeile
		
		"erstelltvon"=>array("Eingestellt von",				// 0 = Alias
						"",										// 1 = Default
						0,										// 2 = Pflicht
						"erstelltvon",						// 3 = MySQL-Name
						0,										// 4 = Specialfeatures
						"%strErstelltvon%",					// 5 = tplFeldlabel
						"<!-- %strErstelltvonPflicht*% -->",	// 6 = tplPflichtmarke
						"<!-- %erstelltvonAlert% -->",		// 7 = tplWarning
						"%lesen[erstelltvon]%",				// 8 = tplFeldwertLesen
						"%eingabe[erstelltvon]%",			// 9 = tplFeldwertEingabe
						false,									// 10 = isUnique
						0,										// 11 = check Input
						1),										// 12 = zeile
		
		"bearbeitetam"=>array("Bearbeitet am",					// 0 = Alias
						"",										// 1 = Default
						0,										// 2 = Pflicht
						"bearbeitetam",							// 3 = MySQL-Name
						0,										// 4 = Specialfeatures
						"%strBearbeitetam%",					// 5 = tplFeldlabel
						"<!-- %strBearbeitetamPflicht*% -->",	// 6 = tplPflichtmarke
						"<!-- %jidAlert% -->",					// 7 = tplWarning
						"%lesen[jid]%",							// 8 = tplFeldwertLesen
						"%eingabe[jid]%",						// 9 = tplFeldwertEingabe
						false,									// 10 = isUnique
						0,										// 11 = check Input
						1),										// 12 = zeile
		
		"bearbeitetvon"=>array("Bearbeitet von",				// 0 = Alias
						"",										// 1 = Default
						0,										// 2 = Pflicht
						"bearbeitetvon",						// 3 = MySQL-Name
						0,										// 4 = Specialfeatures
						"%strBearbeitetvon%",					// 5 = tplFeldlabel
						"<!-- %strBearbeitetvonPflicht*% -->",	// 6 = tplPflichtmarke
						"<!-- %bearbeitetvonAlert% -->",		// 7 = tplWarning
						"%lesen[bearbeitetvon]%",				// 8 = tplFeldwertLesen
						"%eingabe[bearbeitetvon]%",				// 9 = tplFeldwertEingabe
						false,									// 10 = isUnique
						0,										// 11 = check Input
						1) 										// 12 = zeile
		);

$_SYS2MYSQL[$_TABLE["cms_texte"]]=array(
		"listentitel"	=> "listentitel",
		"listentext"	=> "listentext",
		"titel"			=> "titel",
		"volltext"		=> "volltext",
		"sortierfeld"	=> "ordnungszahl",
		"erstelltam"	=> "erstelltam",
		"erstelltvon"	=> "erstelltvon",
		"bearbeitetam"	=> "bearbeitetam",
		"bearbeitetvon"	=> "bearbeitetvon"
);

// Erweitern des Array validFields um MYSQL-FELD-Eigenschaften
$mysql_tbl_fields[$_TABLE["cms_texte"]] = db_show_fields($_TABLE["cms_texte"]);
while(list($k,$v) = each ($validFields[$_TABLE["cms_texte"]])) {
	$validFields[$_TABLE["cms_texte"]][$k]["mysql"] = array();
	if (isset($mysql_tbl_fields[$_TABLE["cms_texte"]][$v[3]])) {
		$validFields[$_TABLE["cms_texte"]][$k]["mysql"] = $mysql_tbl_fields[$_TABLE["cms_texte"]][$v[3]];
	}
}
reset($validFields);/**/
?>
