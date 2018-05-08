<?php
// START BASE CONF
$_CONF["cms_bereiche"] = array(
	"ConfName" => "cms_bereiche",
	"Title" => "Cms_bereiche",
	"Description" => "heiiii234",
	"Src" => "",
	"Db" => $MConf["DB_Name"],
	"Table" => "mm_cms_bereiche",
	"PrimaryKey" => "id",
	"readMinAccess" => 0,
	"insertMinAccess" => 2,
	"updateMinAccess" => 3,
	"deleteMinAccess" => 4,
	"FormInput" => "",
	"FormPreview" => "",
	"FormRead" => "",
	"Fields" => array(
		"id" => array(
			"dbField" => "id",
			"key" => "PRI",
			"label" => "Id",
			"listlabel" => "Id",
			"fieldPos" => 1,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "int",
			"size" => "6",
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
		"srv" => array(
			"dbField" => "srv",
			"key" => "",
			"label" => "Srv",
			"listlabel" => "Srv",
			"fieldPos" => 2,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "32",
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
		"name" => array(
			"dbField" => "name",
			"key" => "",
			"label" => "Name",
			"listlabel" => "Name",
			"fieldPos" => 3,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "120",
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
		"lang" => array(
			"dbField" => "lang",
			"key" => "",
			"label" => "Lang",
			"listlabel" => "Lang",
			"fieldPos" => 4,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "enum",
			"size" => "'DE','EN'",
			"sysType" => "enum",
			"htmlType" => "radio",
			"default" => "DE",
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
		"geschuetzt" => array(
			"dbField" => "geschuetzt",
			"key" => "",
			"label" => "Geschuetzt",
			"listlabel" => "Geschuetzt",
			"fieldPos" => 5,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "enum",
			"size" => "'Ja','Nein'",
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
		"rechte" => array(
			"dbField" => "rechte",
			"key" => "",
			"label" => "Rechte",
			"listlabel" => "Rechte",
			"fieldPos" => 6,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "int",
			"size" => "7",
			"sysType" => "int",
			"htmlType" => "text",
			"default" => "0",
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
		"content" => array(
			"dbField" => "content",
			"key" => "",
			"label" => "Content",
			"listlabel" => "Content",
			"fieldPos" => 7,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "enum",
			"size" => "'Ja','Nein'",
			"sysType" => "enum",
			"htmlType" => "radio",
			"default" => "Ja",
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
		"webfreigabe" => array(
			"dbField" => "webfreigabe",
			"key" => "",
			"label" => "Webfreigabe",
			"listlabel" => "Webfreigabe",
			"fieldPos" => 8,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "enum",
			"size" => "'Ja','Nein'",
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
		"visibility" => array(
			"dbField" => "visibility",
			"key" => "",
			"label" => "Visibility",
			"listlabel" => "Visibility",
			"fieldPos" => 9,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "enum",
			"size" => "'allways','never','conditional'",
			"sysType" => "enum",
			"htmlType" => "radio",
			"default" => "allways",
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
		"visibility_condition" => array(
			"dbField" => "visibility_condition",
			"key" => "",
			"label" => "Visibility_condition",
			"listlabel" => "Visibility_condition",
			"fieldPos" => 10,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "set",
			"size" => "'login','logout','loginas:gruppe','loginas:gleicherechte','loginas:mindestrechte','loginas:admin'",
			"sysType" => "set",
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
		"gruppen" => array(
			"dbField" => "gruppen",
			"key" => "",
			"label" => "Gruppen",
			"listlabel" => "Gruppen",
			"fieldPos" => 11,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "varchar",
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
		"parentid" => array(
			"dbField" => "parentid",
			"key" => "",
			"label" => "Parentid",
			"listlabel" => "Parentid",
			"fieldPos" => 12,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "int",
			"size" => "6",
			"sysType" => "int",
			"htmlType" => "text",
			"default" => "0",
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
		"ordnungszahl" => array(
			"dbField" => "ordnungszahl",
			"key" => "",
			"label" => "Ordnungszahl",
			"listlabel" => "Ordnungszahl",
			"fieldPos" => 13,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "int",
			"size" => "5",
			"sysType" => "int",
			"htmlType" => "text",
			"default" => "1",
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
		"create_menu_function" => array(
			"dbField" => "create_menu_function",
			"key" => "",
			"label" => "Create_menu_function",
			"listlabel" => "Create_menu_function",
			"fieldPos" => 14,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "varchar",
			"size" => "200",
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
		"create_menu_script" => array(
			"dbField" => "create_menu_script",
			"key" => "",
			"label" => "Create_menu_script",
			"listlabel" => "Create_menu_script",
			"fieldPos" => 15,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "varchar",
			"size" => "200",
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
		"cmd" => array(
			"dbField" => "cmd",
			"key" => "",
			"label" => "Cmd",
			"listlabel" => "Cmd",
			"fieldPos" => 16,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "varchar",
			"size" => "32",
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
		"script" => array(
			"dbField" => "script",
			"key" => "",
			"label" => "Script",
			"listlabel" => "Script",
			"fieldPos" => 17,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "varchar",
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
		"adminlinks" => array(
			"dbField" => "adminlinks",
			"key" => "",
			"label" => "Adminlinks",
			"listlabel" => "Adminlinks",
			"fieldPos" => 18,
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
		"name_translations" => array(
			"dbField" => "name_translations",
			"key" => "",
			"label" => "Name_translations",
			"listlabel" => "Name_translations",
			"fieldPos" => 19,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "varchar",
			"size" => "100",
			"sysType" => "char",
			"htmlType" => "text",
			"default" => "EN=",
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
		"html_vorlage" => array(
			"dbField" => "html_vorlage",
			"key" => "",
			"label" => "Html_vorlage",
			"listlabel" => "Html_vorlage",
			"fieldPos" => 20,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "varchar",
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
		"html_metatags" => array(
			"dbField" => "html_metatags",
			"key" => "",
			"label" => "Html_metatags",
			"listlabel" => "Html_metatags",
			"fieldPos" => 21,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "varchar",
			"size" => "255",
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
		"html_header" => array(
			"dbField" => "html_header",
			"key" => "",
			"label" => "Html_header",
			"listlabel" => "Html_header",
			"fieldPos" => 22,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "varchar",
			"size" => "255",
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
		"text" => array(
			"dbField" => "text",
			"key" => "",
			"label" => "Text",
			"listlabel" => "Text",
			"fieldPos" => 23,
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
		"logo" => array(
			"dbField" => "logo",
			"key" => "",
			"label" => "Logo",
			"listlabel" => "Logo",
			"fieldPos" => 24,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "varchar",
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
		"redirect" => array(
			"dbField" => "redirect",
			"key" => "",
			"label" => "Redirect",
			"listlabel" => "Redirect",
			"fieldPos" => 25,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "varchar",
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
		"kommentar" => array(
			"dbField" => "kommentar",
			"key" => "",
			"label" => "Kommentar",
			"listlabel" => "Kommentar",
			"fieldPos" => 26,
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
		"menu_groupname" => array(
			"dbField" => "menu_groupname",
			"key" => "",
			"label" => "Menu_groupname",
			"listlabel" => "Menu_groupname",
			"fieldPos" => 27,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "varchar",
			"size" => "20",
			"sysType" => "char",
			"htmlType" => "text",
			"default" => "main",
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
		"menu_icon" => array(
			"dbField" => "menu_icon",
			"key" => "",
			"label" => "Menu_icon",
			"listlabel" => "Menu_icon",
			"fieldPos" => 28,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "varchar",
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
		"menu_title" => array(
			"dbField" => "menu_title",
			"key" => "",
			"label" => "Menu_title",
			"listlabel" => "Menu_title",
			"fieldPos" => 29,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "varchar",
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
		"menu_behaviour" => array(
			"dbField" => "menu_behaviour",
			"key" => "",
			"label" => "Menu_behaviour",
			"listlabel" => "Menu_behaviour",
			"fieldPos" => 30,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "enum",
			"size" => "'openSubMenu','openLink','keepSubMenuOpen'",
			"sysType" => "enum",
			"htmlType" => "radio",
			"default" => "openSubMenu",
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
		"submenu_behaviour" => array(
			"dbField" => "submenu_behaviour",
			"key" => "",
			"label" => "Submenu_behaviour",
			"listlabel" => "Submenu_behaviour",
			"fieldPos" => 31,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "enum",
			"size" => "'hidden','visible'",
			"sysType" => "enum",
			"htmlType" => "radio",
			"default" => "visible",
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
		"menu_target" => array(
			"dbField" => "menu_target",
			"key" => "",
			"label" => "Menu_target",
			"listlabel" => "Menu_target",
			"fieldPos" => 32,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "varchar",
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
		"menu_class" => array(
			"dbField" => "menu_class",
			"key" => "",
			"label" => "Menu_class",
			"listlabel" => "Menu_class",
			"fieldPos" => 33,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "varchar",
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
		"menu_style" => array(
			"dbField" => "menu_style",
			"key" => "",
			"label" => "Menu_style",
			"listlabel" => "Menu_style",
			"fieldPos" => 34,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "varchar",
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
		"menu_onclick" => array(
			"dbField" => "menu_onclick",
			"key" => "",
			"label" => "Menu_onclick",
			"listlabel" => "Menu_onclick",
			"fieldPos" => 35,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "varchar",
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
		"menu_link" => array(
			"dbField" => "menu_link",
			"key" => "",
			"label" => "Menu_link",
			"listlabel" => "Menu_link",
			"fieldPos" => 36,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "varchar",
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
		"menu_html" => array(
			"dbField" => "menu_html",
			"key" => "",
			"label" => "Menu_html",
			"listlabel" => "Menu_html",
			"fieldPos" => 37,
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
		"menu_div_attr" => array(
			"dbField" => "menu_div_attr",
			"key" => "",
			"label" => "Menu_div_attr",
			"listlabel" => "Menu_div_attr",
			"fieldPos" => 38,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "varchar",
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
		"menu_ahref_attr" => array(
			"dbField" => "menu_ahref_attr",
			"key" => "",
			"label" => "Menu_ahref_attr",
			"listlabel" => "Menu_ahref_attr",
			"fieldPos" => 39,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "varchar",
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
		"menu_sub_attr" => array(
			"dbField" => "menu_sub_attr",
			"key" => "",
			"label" => "Menu_sub_attr",
			"listlabel" => "Menu_sub_attr",
			"fieldPos" => 40,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "varchar",
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
		"menu_code_before" => array(
			"dbField" => "menu_code_before",
			"key" => "",
			"label" => "Menu_code_before",
			"listlabel" => "Menu_code_before",
			"fieldPos" => 41,
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
		"menu_code_behind" => array(
			"dbField" => "menu_code_behind",
			"key" => "",
			"label" => "Menu_code_behind",
			"listlabel" => "Menu_code_behind",
			"fieldPos" => 42,
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
			"fieldPos" => 43,
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
			"fieldPos" => 44,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "datetime",
			"size" => "",
			"sysType" => "modified",
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
		)
	),
	"Joins" => array(

	),
	"Lists" => array(

	)
);
// ENDE BASE CONF
?>