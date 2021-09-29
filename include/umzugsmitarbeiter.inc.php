<?php
// START BASE CONF
$_CONF["umzugsmitarbeiter"] = array(
	"ConfName" => "umzugsmitarbeiter",
	"Title" => "Umzugsmitarbeiter",
	"Description" => "",
	"Src" => '',
	"Db"  => '',
	"Table" => "mm_umzuege_arbeitsplaetze",
	"PrimaryKey" => "mid",
	"readMinAccess" => 0,
	"insertMinAccess" => 2,
	"updateMinAccess" => null,
	"deleteMinAccess" => null,
	"FormInput" => "",
	"FormPreview" => "",
	"FormRead" => "",
	"Fields" => array(
		"aid" => array(
			"dbField" => "aid",
			"key" => "",
			"label" => "Aid",
			"listlabel" => "Aid",
			"fieldPos" => 1,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "int",
			"size" => "11",
			"sysType" => "int",
			"htmlType" => "text",
			"default" => "",
			"required" => false,
			"null" => false,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => "",
			"readAttribute" => "",
			"createByFunction" => "",
			"checkByFunction" => "",
			"formatEingabeFunction" => "",
			"formatLesenFunction" => "",
			"editByRuntime" => false,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"mid" => array(
			"dbField" => "mid",
			"key" => "PRI",
			"label" => "Mid",
			"listlabel" => "Mid",
			"fieldPos" => 2,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "int",
			"size" => "11",
			"sysType" => "int",
			"htmlType" => "text",
			"default" => "",
			"required" => true,
			"null" => false,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => "",
			"readAttribute" => "",
			"createByFunction" => "",
			"checkByFunction" => "",
			"formatEingabeFunction" => "",
			"formatLesenFunction" => "",
			"editByRuntime" => false,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"maid" => array(
			"dbField" => "maid",
			"key" => "",
			"label" => "maid",
			"listlabel" => "maid",
			"fieldPos" => 2,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "int",
			"size" => "11",
			"sysType" => "int",
			"htmlType" => "text",
			"default" => "",
			"required" => false,
			"null" => true,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => "",
			"readAttribute" => "",
			"createByFunction" => "",
			"checkByFunction" => "",
			"formatEingabeFunction" => "",
			"formatLesenFunction" => "",
			"editByRuntime" => false,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"name" => array(
			"dbField" => "name",
			"key" => "",
			"label" => "Name",
			"listlabel" => "Name",
			"fieldPos" => 3,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "50",
			"sysType" => "char",
			"htmlType" => "text",
			"default" => "",
			"required" => true,
			"null" => false,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => "",
			"readAttribute" => "",
			"createByFunction" => "",
			"checkByFunction" => "",
			"formatEingabeFunction" => "",
			"formatLesenFunction" => "",
			"editByRuntime" => false,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"vorname" => array(
			"dbField" => "vorname",
			"key" => "",
			"label" => "Vorname",
			"listlabel" => "Vorname",
			"fieldPos" => 4,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "50",
			"sysType" => "char",
			"htmlType" => "text",
			"default" => "",
			"required" => true,
			"null" => false,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => "",
			"readAttribute" => "",
			"createByFunction" => "",
			"checkByFunction" => "",
			"formatEingabeFunction" => "",
			"formatLesenFunction" => "",
			"editByRuntime" => false,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"email" => array(
			"dbField" => "email",
			"key" => "",
			"label" => "Email",
			"listlabel" => "Email",
			"fieldPos" => 5,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "50",
			"sysType" => "email",
			"htmlType" => "text",
			"default" => "",
			"required" => false,
			"null" => true,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => "",
			"readAttribute" => "",
			"createByFunction" => "",
			"checkByFunction" => "",
			"formatEingabeFunction" => "",
			"formatLesenFunction" => "",
			"editByRuntime" => false,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"raumid" => array(
			"dbField" => "raumid",
			"key" => "",
			"label" => "RaumID",
			"listlabel" => "RaumID",
			"fieldPos" => 5,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "int",
			"size" => "11",
			"sysType" => "int",
			"htmlType" => "text",
			"default" => "",
			"required" => false,
			"null" => true,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => "",
			"readAttribute" => "",
			"createByFunction" => "",
			"checkByFunction" => "",
			"formatEingabeFunction" => "",
			"formatLesenFunction" => "",
			"editByRuntime" => false,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"ort" => array(
			"dbField" => "ort",
			"key" => "",
			"label" => "Ort",
			"listlabel" => "Ort",
			"fieldPos" => 6,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "50",
			"sysType" => "char",
			"htmlType" => "text",
			"default" => "",
			"required" => false,
			"null" => true,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => "",
			"readAttribute" => "",
			"createByFunction" => "",
			"checkByFunction" => "",
			"formatEingabeFunction" => "",
			"formatLesenFunction" => "",
			"editByRuntime" => false,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"gebaeude" => array(
			"dbField" => "gebaeude",
			"key" => "",
			"label" => "Gebaeude",
			"listlabel" => "Gebaeude",
			"fieldPos" => 7,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "50",
			"sysType" => "char",
			"htmlType" => "text",
			"default" => "",
			"required" => true,
			"null" => true,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => "",
			"readAttribute" => "",
			"createByFunction" => "",
			"checkByFunction" => "",
			"formatEingabeFunction" => "",
			"formatLesenFunction" => "",
			"editByRuntime" => false,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"etage" => array(
			"dbField" => "etage",
			"key" => "",
			"label" => "Etage",
			"listlabel" => "Etage",
			"fieldPos" => 7,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "50",
			"sysType" => "char",
			"htmlType" => "text",
			"default" => "",
			"required" => true,
			"null" => true,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => "",
			"readAttribute" => "",
			"createByFunction" => "",
			"checkByFunction" => "",
			"formatEingabeFunction" => "",
			"formatLesenFunction" => "",
			"editByRuntime" => false,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"raumnr" => array(
			"dbField" => "raumnr",
			"key" => "",
			"label" => "Raumnr",
			"listlabel" => "Raumnr",
			"fieldPos" => 8,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "15",
			"sysType" => "char",
			"htmlType" => "text",
			"default" => "",
			"required" => false,
			"null" => true,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => "",
			"readAttribute" => "",
			"createByFunction" => "",
			"checkByFunction" => "",
			"formatEingabeFunction" => "",
			"formatLesenFunction" => "",
			"editByRuntime" => false,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"apnr" => array(
			"dbField" => "arbeitsplatznr",
			"key" => "",
			"label" => "AP-Nr",
			"listlabel" => "AP-Nr",
			"fieldPos" => 8,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "15",
			"sysType" => "char",
			"htmlType" => "text",
			"default" => "",
			"required" => false,
			"null" => true,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => "",
			"readAttribute" => "",
			"createByFunction" => "",
			"checkByFunction" => "",
			"formatEingabeFunction" => "",
			"formatLesenFunction" => "",
			"editByRuntime" => false,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"abteilung" => array(
			"dbField" => "abteilung",
			"key" => "",
			"label" => "Abteilung",
			"listlabel" => "Abteilung",
			"fieldPos" => 9,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "50",
			"sysType" => "char",
			"htmlType" => "text",
			"default" => "",
			"required" => false,
			"null" => true,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => "",
			"readAttribute" => "",
			"createByFunction" => "",
			"checkByFunction" => "",
			"formatEingabeFunction" => "",
			"formatLesenFunction" => "",
			"editByRuntime" => false,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"extern_firma" => array(
			"dbField" => "extern_firma",
			"key" => "",
			"label" => "Externe Firma",
			"listlabel" => "ext. Firma",
			"fieldPos" => 9,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "50",
			"sysType" => "char",
			"htmlType" => "text",
			"default" => "",
			"required" => false,
			"null" => true,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => "",
			"readAttribute" => "",
			"createByFunction" => "",
			"checkByFunction" => "",
			"formatEingabeFunction" => "",
			"formatLesenFunction" => "",
			"editByRuntime" => false,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"umzugsart" => array(
			"dbField" => "umzugsart",
			"key" => "",
			"label" => "Umzugsart",
			"listlabel" => "Umzugsart",
			"fieldPos" => 10,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "100",
			"sysType" => "char",
			"htmlType" => "text",
			"default" => "",
			"required" => false,
			"null" => true,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => "",
			"readAttribute" => "",
			"createByFunction" => "",
			"checkByFunction" => "",
			"formatEingabeFunction" => "",
			"formatLesenFunction" => "",
			"editByRuntime" => false,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"umzugsart_details" => array(
			"dbField" => "umzugsart_details",
			"key" => "",
			"label" => "Umzugsart_details",
			"listlabel" => "Umzugsart_details",
			"fieldPos" => 11,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "text",
			"size" => "",
			"sysType" => "text",
			"htmlType" => "textarea",
			"default" => "",
			"required" => false,
			"null" => true,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => "",
			"readAttribute" => "",
			"createByFunction" => "",
			"checkByFunction" => "",
			"formatEingabeFunction" => "",
			"formatLesenFunction" => "",
			"editByRuntime" => false,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"kostenstelle" => array(
			"dbField" => "kostenstelle",
			"key" => "",
			"label" => "Kostenstelle",
			"listlabel" => "Kostenstelle",
			"fieldPos" => 12,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "50",
			"sysType" => "char",
			"htmlType" => "text",
			"default" => "",
			"required" => false,
			"null" => true,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => "",
			"readAttribute" => "",
			"createByFunction" => "",
			"checkByFunction" => "",
			"formatEingabeFunction" => "",
			"formatLesenFunction" => "",
			"editByRuntime" => false,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"fon" => array(
			"dbField" => "fon_dw",
			"key" => "",
			"label" => "Tel-Durchwahl",
			"listlabel" => "Fon",
			"fieldPos" => 13,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "20",
			"sysType" => "char",
			"htmlType" => "text",
			"default" => "",
			"required" => false,
			"null" => true,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => "",
			"readAttribute" => "",
			"createByFunction" => "",
			"checkByFunction" => "umzugsmitarbeiter_check_fon",
			"formatEingabeFunction" => "",
			"formatLesenFunction" => "",
			"editByRuntime" => false,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"pcnr" => array(
			"dbField" => "pcnr",
			"key" => "",
			"label" => "Pcnr",
			"listlabel" => "Pcnr",
			"fieldPos" => 14,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "50",
			"sysType" => "char",
			"htmlType" => "text",
			"default" => "",
			"required" => false,
			"null" => true,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => "",
			"readAttribute" => "",
			"createByFunction" => "",
			"checkByFunction" => "",
			"formatEingabeFunction" => "",
			"formatLesenFunction" => "",
			"editByRuntime" => false,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"festeip" => array(
			"dbField" => "feste_ip",
			"key" => "",
			"label" => "feste IP",
			"listlabel" => "IP",
			"fieldPos" => 15,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "30",
			"sysType" => "char",
			"htmlType" => "text",
			"default" => "",
			"required" => false,
			"null" => true,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => "",
			"readAttribute" => "",
			"createByFunction" => "",
			"checkByFunction" => "",
			"formatEingabeFunction" => "",
			"formatLesenFunction" => "",
			"editByRuntime" => false,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"zraumid" => array(
			"dbField" => "ziel_raumid",
			"key" => "",
			"label" => "Z-RaumID",
			"listlabel" => "Z-RaumID",
			"fieldPos" => 16,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "int",
			"size" => "11",
			"sysType" => "int",
			"htmlType" => "text",
			"default" => "",
			"required" => false,
			"null" => true,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => "",
			"readAttribute" => "",
			"createByFunction" => "",
			"checkByFunction" => "",
			"formatEingabeFunction" => "",
			"formatLesenFunction" => "",
			"editByRuntime" => false,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"zort" => array(
			"dbField" => "ziel_ort",
			"key" => "",
			"label" => "Ziel-Ort",
			"listlabel" => "Z-Ort",
			"fieldPos" => 16,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "50",
			"sysType" => "char",
			"htmlType" => "text",
			"default" => "",
			"required" => false,
			"null" => true,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => "",
			"readAttribute" => "",
			"createByFunction" => "",
			"checkByFunction" => "",
			"formatEingabeFunction" => "",
			"formatLesenFunction" => "",
			"editByRuntime" => false,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"zgebaeude" => array(
			"dbField" => "ziel_gebaeude",
			"key" => "",
			"label" => "Ziel-Geb�ude",
			"listlabel" => "Z-Geb�ude",
			"fieldPos" => 17,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "50",
			"sysType" => "char",
			"htmlType" => "text",
			"default" => "",
			"required" => false,
			"null" => true,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => "",
			"readAttribute" => "",
			"createByFunction" => "",
			"checkByFunction" => "",
			"formatEingabeFunction" => "",
			"formatLesenFunction" => "",
			"editByRuntime" => false,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"zetage" => array(
			"dbField" => "ziel_etage",
			"key" => "",
			"label" => "Ziel-Etage",
			"listlabel" => "Z-Etage",
			"fieldPos" => 17,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "50",
			"sysType" => "char",
			"htmlType" => "text",
			"default" => "",
			"required" => false,
			"null" => true,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => "",
			"readAttribute" => "",
			"createByFunction" => "",
			"checkByFunction" => "",
			"formatEingabeFunction" => "",
			"formatLesenFunction" => "",
			"editByRuntime" => false,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"zraumnr" => array(
			"dbField" => "ziel_raumnr",
			"key" => "",
			"label" => "Ziel Raum-Nr",
			"listlabel" => "Z-Raumnr",
			"fieldPos" => 18,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "15",
			"sysType" => "char",
			"htmlType" => "text",
			"default" => "",
			"required" => false,
			"null" => true,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => "",
			"readAttribute" => "",
			"createByFunction" => "",
			"checkByFunction" => "",
			"formatEingabeFunction" => "",
			"formatLesenFunction" => "",
			"editByRuntime" => false,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"zapnr" => array(
			"dbField" => "ziel_arbeitsplatznr",
			"key" => "",
			"label" => "Ziel AP-NR",
			"listlabel" => "Ziel AP-Nr",
			"fieldPos" => 18,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "15",
			"sysType" => "char",
			"htmlType" => "text",
			"default" => "",
			"required" => false,
			"null" => true,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => "",
			"readAttribute" => "",
			"createByFunction" => "",
			"checkByFunction" => "",
			"formatEingabeFunction" => "",
			"formatLesenFunction" => "",
			"editByRuntime" => false,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"zabteilung" => array(
			"dbField" => "ziel_abteilung",
			"key" => "",
			"label" => "Ziel_abteilung",
			"listlabel" => "Ziel_abteilung",
			"fieldPos" => 19,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "50",
			"sysType" => "char",
			"htmlType" => "text",
			"default" => "",
			"required" => false,
			"null" => true,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => "",
			"readAttribute" => "",
			"createByFunction" => "",
			"checkByFunction" => "",
			"formatEingabeFunction" => "",
			"formatLesenFunction" => "",
			"editByRuntime" => false,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"bemerkungen" => array(
			"dbField" => "bemerkungen",
			"key" => "",
			"label" => "Bemerkungen",
			"listlabel" => "Bemerkungen",
			"fieldPos" => 20,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "text",
			"size" => "",
			"sysType" => "text",
			"htmlType" => "textarea",
			"default" => "",
			"required" => false,
			"null" => true,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => "",
			"readAttribute" => "",
			"createByFunction" => "",
			"checkByFunction" => "",
			"formatEingabeFunction" => "",
			"formatLesenFunction" => "",
			"editByRuntime" => false,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"created" => array(
			"dbField" => "created",
			"key" => "",
			"label" => "Created",
			"listlabel" => "Created",
			"fieldPos" => 21,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "datetime",
			"size" => "",
			"sysType" => "created",
			"htmlType" => "text",
			"default" => "",
			"required" => false,
			"null" => false,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => "",
			"readAttribute" => "",
			"createByFunction" => "",
			"checkByFunction" => "",
			"formatEingabeFunction" => "",
			"formatLesenFunction" => "",
			"editByRuntime" => false,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"modified" => array(
			"dbField" => "modified",
			"key" => "",
			"label" => "Modified",
			"listlabel" => "Modified",
			"fieldPos" => 22,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "datetime",
			"size" => "",
			"sysType" => "modified",
			"htmlType" => "text",
			"default" => "",
			"required" => false,
			"null" => true,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => "",
			"readAttribute" => "",
			"createByFunction" => "",
			"checkByFunction" => "",
			"formatEingabeFunction" => "",
			"formatLesenFunction" => "",
			"editByRuntime" => false,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"b_modified" => array(
			"dbField" => "b_modified",
			"key" => "",
			"label" => "B_modified",
			"listlabel" => "B_modified",
			"fieldPos" => 23,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "datetime",
			"size" => "",
			"sysType" => "char",
			"htmlType" => "text",
			"default" => "",
			"required" => false,
			"null" => true,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => "",
			"readAttribute" => "",
			"createByFunction" => "",
			"checkByFunction" => "",
			"formatEingabeFunction" => "",
			"formatLesenFunction" => "",
			"editByRuntime" => false,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"b_modifiedby" => array(
			"dbField" => "b_modifiedby",
			"key" => "",
			"label" => "B_modifiedby",
			"listlabel" => "B_modifiedby",
			"fieldPos" => 24,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "int",
			"size" => "5",
			"sysType" => "int",
			"htmlType" => "text",
			"default" => "",
			"required" => false,
			"null" => true,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => "",
			"readAttribute" => "",
			"createByFunction" => "",
			"checkByFunction" => "",
			"formatEingabeFunction" => "",
			"formatLesenFunction" => "",
			"editByRuntime" => false,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		)
	),
	"Joins" => array(

	),
	"Lists" => array(

	)
);
// ENDE BASE CONF
?>
