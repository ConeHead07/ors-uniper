<?php
// START BASE CONF
$_CONF["user"] = array(
	"ConfName" => "user",
	"Title" => "User",
	"Description" => "",
	"Src" => "",
	"Db" => $MConf["DB_Name"],
	"Table" => "mm_user",
	"PrimaryKey" => "uid",
	"readMinAccess" => 0,
	"insertMinAccess" => 2,
	"updateMinAccess" => null,
	"deleteMinAccess" => null,
	"FormInput" => "",
	"FormPreview" => "",
	"FormRead" => "",
        "EditClass" => "ItemEditUser",
	"Fields" => array(
		"uid" => array(
			"dbField" => "uid",
			"key" => "PRI",
			"label" => "Uid",
			"listlabel" => "Uid",
			"fieldPos" => 1,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "int",
			"size" => "11",
			"sql" => "",
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
		"user" => array(
			"dbField" => "user",
			"key" => "",
			"label" => "User",
			"listlabel" => "User",
			"fieldPos" => 2,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "50",
			"sql" => "",
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
			"fieldPos" => 3,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "varchar",
			"size" => "50",
			"sql" => "",
			"sysType" => "email",
			"htmlType" => "text",
			"default" => "",
			"required" => true,
			"null" => false,
			"unique" => true,
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
			"dbField" => "fon",
			"key" => "",
			"label" => "Fon",
			"listlabel" => "Fon",
			"fieldPos" => 4,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "50",
			"sql" => "",
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
		"pw" => array(
			"dbField" => "pw",
			"key" => "",
			"label" => "Pw",
			"listlabel" => "Pw",
			"fieldPos" => 5,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "varchar",
			"size" => "32",
			"sql" => "",
			"sysType" => "password",
			"htmlType" => "text",
			"default" => "init",
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
		"gruppe" => array(
			"dbField" => "gruppe",
			"key" => "",
			"label" => "Gruppe",
			"listlabel" => "Gruppe",
			"fieldPos" => 6,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "enum",
			"size" => "'user=V-Mitarbeiter','umzugsteam','kunde_report=V-Property','admin_standort','admin_gesamt','admin=mertens'",
			"sql" => "",
			"sysType" => "enum",
			"htmlType" => "select single",
			"default" => "user",
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
		"rechte" => array(
			"dbField" => "rechte",
			"key" => "",
			"label" => "Rechte",
			"listlabel" => "Rechte",
			"fieldPos" => 7,
			"fieldGroup" => "main",
			"description" => "hallo",
			"type" => "enum",
			"size" => "'0','1','2','3','4','5','6','7'",
			"sql" => "",
			"sysType" => "enum",
			"htmlType" => "select single",
			"default" => "0",
			"required" => false,
			"null" => true,
			"unique" => false,
			"min" => null,
			"max" => null,
			"inputAttribute" => " rel=\"./hilfetexte/terminwunsch.php\"",
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
		"adminmode" => array(
			"dbField" => "adminmode",
			"key" => "",
			"label" => "Adminmode",
			"listlabel" => "Adminmode",
			"fieldPos" => 8,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "enum",
			"size" => "'normal','superadmin'",
			"sql" => "",
			"sysType" => "enum",
			"htmlType" => "radio",
			"default" => "normal",
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
		"darf_preise_sehen" => array(
			"dbField" => "darf_preise_sehen",
			"key" => "",
			"label" => "Preise anzeigen",
			"listlabel" => "Preise J/N",
			"fieldPos" => 9,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "enum",
			"size" => "'Nein','Ja'",
			"sql" => "",
			"sysType" => "enum",
			"htmlType" => "radio",
			"default" => "Nein",
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
		"standortverwaltung" => array(
			"dbField" => "standortverwaltung",
			"key" => "",
			"label" => "Standortverwaltung",
			"listlabel" => "Standortverwaltung",
			"fieldPos" => 8,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "set",
			"size" => "",
			"sql" => "select stadtname FROM `mm_stamm_gebaeude` GROUP BY stadtname ORDER BY stadtname",
			"sysType" => "set",
			"htmlType" => "select multiple",
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
		"freigegeben" => array(
			"dbField" => "freigegeben",
			"key" => "",
			"label" => "Freigegeben",
			"listlabel" => "Freigegeben",
			"fieldPos" => 9,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "enum",
			"size" => "'init','Nein','Ja'",
			"sql" => "",
			"sysType" => "enum",
			"htmlType" => "radio",
			"default" => "Nein",
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
		"anrede" => array(
			"dbField" => "anrede",
			"key" => "",
			"label" => "Anrede",
			"listlabel" => "Anrede",
			"fieldPos" => 10,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "enum",
			"size" => "'Frau','Herr'",
			"sql" => "",
			"sysType" => "enum",
			"htmlType" => "radio",
			"default" => "Frau",
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
			"fieldPos" => 11,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "varchar",
			"size" => "50",
			"sql" => "",
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
		"nachname" => array(
			"dbField" => "nachname",
			"key" => "",
			"label" => "Nachname",
			"listlabel" => "Nachname",
			"fieldPos" => 12,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "varchar",
			"size" => "50",
			"sql" => "",
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
		"firma" => array(
			"dbField" => "firma",
			"key" => "",
			"label" => "Firma",
			"listlabel" => "Firma",
			"fieldPos" => 13,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "50",
			"sql" => "",
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
		"standort" => array(
			"dbField" => "standort",
			"key" => "",
			"label" => "Standort",
			"listlabel" => "Standort",
			"fieldPos" => 14,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "50",
			"sql" => "select stadtname FROM `mm_stamm_gebaeude` GROUP BY stadtname ORDER BY stadtname",
			"sysType" => "char",
			"htmlType" => "select single",
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
			"fieldPos" => 15,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "50",
			"sql" => "",
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
        "emails_cc" => array(
            "dbField" => "emails_cc",
            "key" => "",
            "label" => "Emails-CC",
            "listlabel" => "CC",
            "fieldPos" => 3,
            "fieldGroup" => "main",
            "description" => "",
            "type" => "varchar",
            "size" => "250",
            "sql" => "",
            "sysType" => "text",
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
		"authentcode" => array(
			"dbField" => "authentcode",
			"key" => "",
			"label" => "Authentcode",
			"listlabel" => "Authentcode",
			"fieldPos" => 16,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "varchar",
			"size" => "10",
			"sql" => "",
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
		"registerdate" => array(
			"dbField" => "registerdate",
			"key" => "",
			"label" => "Registerdate",
			"listlabel" => "Registerdate",
			"fieldPos" => 17,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "datetime",
			"size" => "",
			"sql" => "",
			"sysType" => "char",
			"htmlType" => "text",
			"default" => "0000-00-00 00:00:00",
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
			"editByRuntime" => true,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"agb_confirm" => array(
			"dbField" => "agb_confirm",
			"key" => "",
			"label" => "Agb_confirm",
			"listlabel" => "Agb_confirm",
			"fieldPos" => 18,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "enum",
			"size" => "'init','Ja','Nein'",
			"sql" => "",
			"sysType" => "enum",
			"htmlType" => "radio",
			"default" => "init",
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
		"confirmdate" => array(
			"dbField" => "confirmdate",
			"key" => "",
			"label" => "Confirmdate",
			"listlabel" => "Confirmdate",
			"fieldPos" => 19,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "datetime",
			"size" => "",
			"sql" => "",
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
			"editByRuntime" => true,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
		"lastlogin" => array(
			"dbField" => "lastlogin",
			"key" => "",
			"label" => "Lastlogin",
			"listlabel" => "Lastlogin",
			"fieldPos" => 20,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "datetime",
			"size" => "",
			"sql" => "",
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
		"lastvisit" => array(
			"dbField" => "lastvisit",
			"key" => "",
			"label" => "Lastvisit",
			"listlabel" => "Lastvisit",
			"fieldPos" => 21,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "datetime",
			"size" => "",
			"sql" => "",
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
		"lastvisitbefore" => array(
			"dbField" => "lastvisitbefore",
			"key" => "",
			"label" => "Lastvisitbefore",
			"listlabel" => "Lastvisitbefore",
			"fieldPos" => 22,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "datetime",
			"size" => "",
			"sql" => "",
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
		"onlinestatus" => array(
			"dbField" => "onlinestatus",
			"key" => "",
			"label" => "Onlinestatus",
			"listlabel" => "Onlinestatus",
			"fieldPos" => 23,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "enum",
			"size" => "'online','loggedout','timeout'",
			"sql" => "",
			"sysType" => "enum",
			"htmlType" => "radio",
			"default" => "loggedout",
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
		"created" => array(
			"dbField" => "created",
			"key" => "",
			"label" => "Created",
			"listlabel" => "Created",
			"fieldPos" => 24,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "datetime",
			"size" => "",
			"sql" => "",
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
			"fieldPos" => 25,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "datetime",
			"size" => "",
			"sql" => "",
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
		)
	),
	"Joins" => array(

	),
	"Lists" => array(
		"0" => array(
			"ListRenderMode" => "Auto",
			"ListFunction" => "",
			"ListTemplate" => "",
			"name" => "",
			"select" => "uid, onlinestatus, user, email, gruppe, freigegeben, standort, gebaeude, created",
			"from" => "",
			"join" => "",
			"where" => "",
			"group" => "",
			"having" => "",
			"defaultOrderFld" => "created",
			"defaultOrderDir" => "DESC",
			"setDefaultButtons" => "",
			"addButtons" => array(

			),
			"addFormFields" => array(

			),
			"addColumnHandler" => array(

			)
		)
	)
);
// ENDE BASE CONF
