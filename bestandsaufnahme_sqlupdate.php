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
NL_S_DI;München
NL_S_DO;Nürnberg
NL_S_KA;München
NL_SW_ST;Stuttgart
ZV_FVF;Düsseldorf
ZV_MAN;Düsseldorf
ZV_NKL;Düsseldorf
ZV_SEE_1;Düsseldorf
ZV_SEE_3;Düsseldorf
ZV_SEE3A;Düsseldorf
ZV_SEE_4;Düsseldorf
ZV_SEE_5;Düsseldorf
NL_NW_KA;Kassel
NL_N_HH_A61;Hamburg
NL_N_H_K51;Hannover
NL_N_H_V4;Hannover
NL_NO_B_ATE;Berlin
NL_NO_B_M24;Berlin
NL_O_DD_C48;Dresden
NL_RM_ES_AH1;Eschborn
NL_RM_ES_K5;Eschborn
NL_RM_SA_I14;Saarbrücken
NL_RM_SU_OV19;Sulzbach
NL_S_M_LB312;München
NL_SW_FB_R1;Freiburg
NL_SW_MA_FW57;Mannheim
NL_SW_ST_IH20;Stuttgart
NL_SW_ST_SR7;Stuttgart
NL_W_E_RH27;Essen
NL_W_E_TL9;Essen
NL_W_RA_EP2;Ratingen
ZV_BA25;Düsseldorf
ZV_KAI_4H;Düsseldorf
ZV_MAN_2B;Düsseldorf
ZV_MAN_2V;Düsseldorf";

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
NL_NO_AT	Attilastraße 61-67 (Gebäude M), 12105 Berlin
NL_NO_ST	Ruhlsdorfer Straße 95, 14532 Stahnsdorf
NLNOST5A	Ruhlsdorfer Straße 95 (Anbau), 14532 Stahnsdorf
NL_NW_DO	Kammerstück 17, 44357 Dortmund
NL_O_BAU	Wilthener Straße 32, 02625 Bautzen
NL_O_DD	Meißner Straße 79, 01445 Radebeul
NL_RA_H1	D2-Park 1-3 (Haus 1), 40878 Ratingen
NL_RA_H2	D2-Park 2-4, 40878 Ratingen
NL_RA_H3	D2-Park 1-3 (Haus 3), 40878 Ratingen
NL_RA_H5	D2-Park 5, 40878 Ratingen
NL_RM_ES	Hauptstraße 119, 65760 Eschborn
NL_S_DO	Donaustraße 36; 90451 Nürnberg
NL_S_KA	Kastenbauerstraße 2, 81677 München
NL_SW_ST	Ingersheimer Straße 10, 70499 Stuttgart
ZV_MAN	Mannesmannufer 3, 40213 Düsseldorf
ZV_NKL	Niederkasseler Lohweg 20, 40547 Düsseldorf
ZV_SEE_1	Am Seestern 1, 40547 Düsseldorf
ZV_SEE_3	Am Seestern 3, 40547 Düsseldorf
ZV_SEE3A	Am Seestern 3 (Anbau), 40547 Düsseldorf
ZV_SEE_4	Am Seestern 4, 40547 Düsseldorf
ZV_SEE_5	Am Seestern 5, 40547 Düsseldorf
NL_NW_KA	Kölnische Straße 58a, 34117 Kassel
NL_N_HH_A61	Amsinckstraße 61, 20097 Hamburg
NL_N_H_K51	Kestnerstraße 51, Hannover
NL_NO_B_ATE	Attilastraße 61-67 (Gebäude E), 12105 Berlin
NL_NO_B_M24	Markgrafendamm 24 (Haus 34), 10245 Berlin
NL_O_DD_C48	Chemnitzer Straße 48,01187 Dresden
NL_RM_ES_AH1	Alfred-Herrhausen-Allee 1, 65760 Eschborn
NL_RM_ES_K5	Kölner Straße 5, 65760 Eschborn
NL_RM_SA_I14	Innovationsring 14, 66115 Saarbrücken
NL_RM_SU_OV19	Otto-Volger-Straße 19, 65843 Sulzbach (Taunus)
NL_SW_FB_R1	Rennerstraße 1, 79106 Freiburg im Breisgau
NL_SW_MA_FW57	Floßwörthstraße 57, 68199 Mannheim
NL_SW_ST_IH20	Ingersheimer Straße 20, 70499 Stuttgart
NL_W_E_RH27	Rellinghauser Straße 27, 45127 Essen
NL_W_E_TL9	Thea-Leymann-Straße 9, 45127 Essen
NL_W_RA_EP2	Eutelisplatz 2, 40878 Ratingen
ZV_KAI_4H	Kaistraße 4-6, 40221 Düsseldorf";

$CSV = explode("
", $A);

foreach($CSV as $C) {
	$T = explode("	", $C);
	$sql = "UPDATE `mm_stamm_gebaeude` SET `adresse` = \"".$T[1]."\" WHERE gebaeude = \"".$T[0]."\"";
	if (isset($db) && is_object($db)) { $db->query($sql); if ($db->error()) die($db->error()."<br>\nSQL:".$sql."<br>\n"); }
	else echo $sql.";<br>\n";
}
