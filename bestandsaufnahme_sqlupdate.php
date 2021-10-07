<?php 
//echo "#".__LINE__." ".basename(__FILE__)."<br>\n";

$Liste = "NL_N_BR;Bremen
NL_N_HA;Hannover
NL_N_HH;Hamburg
NL_NO_AT;Berlin
NL_NO_ST;Berlin
NLNOST5A;Berlin
NL_NW_DO;Dortmund
NL_NW_KA;Dortmund
NL_O_BAU;Bautzen
NL_O_DD;Dresden
NL_RA_H1;Ratingen
NL_RA_H2;Ratingen
NL_RA_H3;Ratingen
NL_RA_H5;Ratingen
NL_RM_ES;Eschborn
NL_S_DI;M�nchen
NL_S_DO;N�rnberg
NL_S_KA;M�nchen
NL_SW_ST;Stuttgart
ZV_FVF;D�sseldorf
ZV_MAN;D�sseldorf
ZV_NKL;D�sseldorf
ZV_SEE_1;D�sseldorf
ZV_SEE_3;D�sseldorf
ZV_SEE3A;D�sseldorf
ZV_SEE_4;D�sseldorf
ZV_SEE_5;D�sseldorf
NL_NW_KA;Kassel
NL_N_HH_A61;Hamburg
NL_N_H_K51;Hannover
NL_N_H_V4;Hannover
NL_NO_B_ATE;Berlin
NL_NO_B_M24;Berlin
NL_O_DD_C48;Dresden
NL_RM_ES_AH1;Eschborn
NL_RM_ES_K5;Eschborn
NL_RM_SA_I14;Saarbr�cken
NL_RM_SU_OV19;Sulzbach
NL_S_M_LB312;M�nchen
NL_SW_FB_R1;Freiburg
NL_SW_MA_FW57;Mannheim
NL_SW_ST_IH20;Stuttgart
NL_SW_ST_SR7;Stuttgart
NL_W_E_RH27;Essen
NL_W_E_TL9;Essen
NL_W_RA_EP2;Ratingen
ZV_BA25;D�sseldorf
ZV_KAI_4H;D�sseldorf
ZV_MAN_2B;D�sseldorf
ZV_MAN_2V;D�sseldorf";

$aRows = explode("
", $Liste);


foreach($aRows as $row) {
	$t = explode(";", $row);
	$sql = "UPDATE mm_stamm_immobilien SET ort = \"".$t[1]."\" WHERE gebaeude = \"".$t[0]."\"";
	if (isset($db) && is_object($db)) { $db->query($sql); if ($db->error()) die($db->error()."<br>\nSQL:".$sql."<br>\n"); }
	else echo $sql.";<br>\n";
}


$A= "NL_N_BR	Stresemannstr. 37, 28207 Bremen
NL_N_HA	Frankenring 36-38; 30855 Langenhagen
NL_N_HH	Heidenkampsweg 77, 20097 Hamburg
NL_NO_AT	Attilastra�e 61-67 (Geb�ude M), 12105 Berlin
NL_NO_ST	Ruhlsdorfer Stra�e 95, 14532 Stahnsdorf
NLNOST5A	Ruhlsdorfer Stra�e 95 (Anbau), 14532 Stahnsdorf
NL_NW_DO	Kammerst�ck 17, 44357 Dortmund
NL_O_BAU	Wilthener Stra�e 32, 02625 Bautzen
NL_O_DD	Mei�ner Stra�e 79, 01445 Radebeul
NL_RA_H1	D2-Park 1-3 (Haus 1), 40878 Ratingen
NL_RA_H2	D2-Park 2-4, 40878 Ratingen
NL_RA_H3	D2-Park 1-3 (Haus 3), 40878 Ratingen
NL_RA_H5	D2-Park 5, 40878 Ratingen
NL_RM_ES	Hauptstra�e 119, 65760 Eschborn
NL_S_DO	Donaustra�e 36; 90451 N�rnberg
NL_S_KA	Kastenbauerstra�e 2, 81677 M�nchen
NL_SW_ST	Ingersheimer Stra�e 10, 70499 Stuttgart
ZV_MAN	Mannesmannufer 3, 40213 D�sseldorf
ZV_NKL	Niederkasseler Lohweg 20, 40547 D�sseldorf
ZV_SEE_1	Am Seestern 1, 40547 D�sseldorf
ZV_SEE_3	Am Seestern 3, 40547 D�sseldorf
ZV_SEE3A	Am Seestern 3 (Anbau), 40547 D�sseldorf
ZV_SEE_4	Am Seestern 4, 40547 D�sseldorf
ZV_SEE_5	Am Seestern 5, 40547 D�sseldorf
NL_NW_KA	K�lnische Stra�e 58a, 34117 Kassel
NL_N_HH_A61	Amsinckstra�e 61, 20097 Hamburg
NL_N_H_K51	Kestnerstra�e 51, Hannover
NL_NO_B_ATE	Attilastra�e 61-67 (Geb�ude E), 12105 Berlin
NL_NO_B_M24	Markgrafendamm 24 (Haus 34), 10245 Berlin
NL_O_DD_C48	Chemnitzer Stra�e 48,01187 Dresden
NL_RM_ES_AH1	Alfred-Herrhausen-Allee 1, 65760 Eschborn
NL_RM_ES_K5	K�lner Stra�e 5, 65760 Eschborn
NL_RM_SA_I14	Innovationsring 14, 66115 Saarbr�cken
NL_RM_SU_OV19	Otto-Volger-Stra�e 19, 65843 Sulzbach (Taunus)
NL_SW_FB_R1	Rennerstra�e 1, 79106 Freiburg im Breisgau
NL_SW_MA_FW57	Flo�w�rthstra�e 57, 68199 Mannheim
NL_SW_ST_IH20	Ingersheimer Stra�e 20, 70499 Stuttgart
NL_W_E_RH27	Rellinghauser Stra�e 27, 45127 Essen
NL_W_E_TL9	Thea-Leymann-Stra�e 9, 45127 Essen
NL_W_RA_EP2	Eutelisplatz 2, 40878 Ratingen
ZV_KAI_4H	Kaistra�e 4-6, 40221 D�sseldorf";

$CSV = explode("
", $A);

foreach($CSV as $C) {
	$T = explode("	", $C);
	$sql = "UPDATE `mm_stamm_gebaeude` SET `adresse` = \"".$T[1]."\" WHERE gebaeude = \"".$T[0]."\"";
	if (isset($db) && is_object($db)) { $db->query($sql); if ($db->error()) die($db->error()."<br>\nSQL:".$sql."<br>\n"); }
	else echo $sql.";<br>\n";
}
