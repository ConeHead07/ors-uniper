<?php
// START BASE CONF
$_CONF["nebenleistungsanlagen"] = array(
	"ConfName" => "nebenleistungsanlagen",
	"Title" => "Nebenleistungsanlagen",
	"Description" => "",
	"Src" => "",
	"Db" => '',
	"Table" => "mm_nebenleistungen_anlagen",
	"PrimaryKey" => "dokid",
	"readMinAccess" => 0,
	"insertMinAccess" => 2,
	"updateMinAccess" => null,
	"deleteMinAccess" => null,
	"FormInput" => "",
	"FormPreview" => "",
	"FormRead" => "",
	"Fields" => array(
		"nid" => array(
			"dbField" => "nid",
			"key" => "",
			"label" => "Nid",
			"listlabel" => "Nid",
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
		"dokid" => array(
			"dbField" => "dokid",
			"key" => "PRI",
			"label" => "Dokid",
			"listlabel" => "Dokid",
			"fieldPos" => 2,
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
		"oeffentlich" => array(
			"dbField" => "oeffentlich",
			"key" => "",
			"label" => "Oeffentlich",
			"listlabel" => "Oeffentlich",
			"fieldPos" => 3,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "enum",
			"size" => "'Ja','Nein'",
			"sql" => "",
			"sysType" => "enum",
			"htmlType" => "radio",
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
		"typ" => array(
			"dbField" => "typ",
			"key" => "",
			"label" => "Typ",
			"listlabel" => "Typ",
			"fieldPos" => 4,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "enum",
			"size" => "'Datei','Text','Url'",
			"sql" => "",
			"sysType" => "enum",
			"htmlType" => "radio",
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
		"titel" => array(
			"dbField" => "titel",
			"key" => "",
			"label" => "Titel",
			"listlabel" => "Titel",
			"fieldPos" => 5,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "100",
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
		"dok_datei" => array(
			"dbField" => "dok_datei",
			"key" => "",
			"label" => "Dok_datei",
			"listlabel" => "Dok_datei",
			"fieldPos" => 6,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "250",
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
		"dok_text" => array(
			"dbField" => "dok_text",
			"key" => "",
			"label" => "Dok_text",
			"listlabel" => "Dok_text",
			"fieldPos" => 7,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "text",
			"size" => "",
			"sql" => "",
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
		"dok_url" => array(
			"dbField" => "dok_url",
			"key" => "",
			"label" => "Dok_url",
			"listlabel" => "Dok_url",
			"fieldPos" => 8,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "250",
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
		"dok_groesse" => array(
			"dbField" => "dok_groesse",
			"key" => "",
			"label" => "Dok_groesse",
			"listlabel" => "Dok_groesse",
			"fieldPos" => 9,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "int",
			"size" => "11",
			"sql" => "",
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
		"dok_type" => array(
			"dbField" => "dok_type",
			"key" => "",
			"label" => "Dok_type",
			"listlabel" => "Dok_type",
			"fieldPos" => 10,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "30",
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
		"created" => array(
			"dbField" => "created",
			"key" => "",
			"label" => "Created",
			"listlabel" => "Created",
			"fieldPos" => 11,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "datetime",
			"size" => "",
			"sql" => "",
			"sysType" => "created",
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
		"createdby" => array(
			"dbField" => "createdby",
			"key" => "",
			"label" => "Createdby",
			"listlabel" => "Createdby",
			"fieldPos" => 12,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "int",
			"size" => "5",
			"sql" => "",
			"sysType" => "createdby",
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
		"modified" => array(
			"dbField" => "modified",
			"key" => "",
			"label" => "Modified",
			"listlabel" => "Modified",
			"fieldPos" => 13,
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
		),
		"modifiedby" => array(
			"dbField" => "modifiedby",
			"key" => "",
			"label" => "Modifiedby",
			"listlabel" => "Modifiedby",
			"fieldPos" => 14,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "int",
			"size" => "55",
			"sql" => "",
			"sysType" => "modifiedby",
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
