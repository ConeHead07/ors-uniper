<?php
// START BASE CONF
$_CONF["lieferfirmen"] = array(
	"ConfName" => "lieferfirmen",
	"Title" => "Lieferfirmen",
	"Description" => "",
	"Src" => "",
	"Db" => "mt_move_bayer",
	"Table" => "mm_lieferfirmen",
	"PrimaryKey" => "lieferfirma_id",
	"readMinAccess" => 0,
	"insertMinAccess" => 2,
	"updateMinAccess" => null,
	"deleteMinAccess" => null,
	"FormInput" => "",
	"FormPreview" => "",
	"FormRead" => "",
	"Fields" => array(
		"lieferfirma_id" => array(
			"dbField" => "lieferfirma_id",
			"key" => "PRI",
			"label" => "Lieferfirma_id",
			"listlabel" => "Lieferfirma_id",
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
		"Firmenname" => array(
			"dbField" => "Firmenname",
			"key" => "",
			"label" => "Firmenname",
			"listlabel" => "Firmenname",
			"fieldPos" => 2,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "80",
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
		"Strasse" => array(
			"dbField" => "Strasse",
			"key" => "",
			"label" => "Strasse",
			"listlabel" => "Strasse",
			"fieldPos" => 3,
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
		"Ort" => array(
			"dbField" => "Ort",
			"key" => "",
			"label" => "Ort",
			"listlabel" => "Ort",
			"fieldPos" => 4,
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
		"PLZ" => array(
			"dbField" => "PLZ",
			"key" => "",
			"label" => "PLZ",
			"listlabel" => "PLZ",
			"fieldPos" => 5,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
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
		"Ansprechpartner" => array(
			"dbField" => "Ansprechpartner",
			"key" => "",
			"label" => "Ansprechpartner",
			"listlabel" => "Ansprechpartner",
			"fieldPos" => 6,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "varchar",
			"size" => "40",
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
		"Handy" => array(
			"dbField" => "Handy",
			"key" => "",
			"label" => "Handy",
			"listlabel" => "Handy",
			"fieldPos" => 7,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "20",
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
		"Festnetz" => array(
			"dbField" => "Festnetz",
			"key" => "",
			"label" => "Festnetz",
			"listlabel" => "Festnetz",
			"fieldPos" => 8,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "20",
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
		"Email" => array(
			"dbField" => "Email",
			"key" => "",
			"label" => "Email",
			"listlabel" => "Email",
			"fieldPos" => 9,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "varchar",
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
		"created" => array(
			"dbField" => "created",
			"key" => "",
			"label" => "Created",
			"listlabel" => "Created",
			"fieldPos" => 10,
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
			"fieldPos" => 11,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
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