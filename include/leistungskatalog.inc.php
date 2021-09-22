<?php
// START BASE CONF
$_CONF["leistungskatalog"] = array(
	"ConfName" => "leistungskatalog",
	"Title" => "Leistungskatalog",
	"Description" => "",
	"Src" => "",
	"Db" => "mt_move_bayer",
	"Table" => "mm_leistungskatalog",
	"PrimaryKey" => "leistung_id",
	"readMinAccess" => 0,
	"insertMinAccess" => 2,
	"updateMinAccess" => null,
	"deleteMinAccess" => null,
//	"FormInput" => "html/leistungskatalog_eingabe.html",
	"FormInput" => "",
	"FormPreview" => "",
	"FormRead" => "",
	"Fields" => array(
		"leistung_id" => array(
			"dbField" => "leistung_id",
			"key" => "PRI",
			"label" => "Leistung_id",
			"listlabel" => "id",
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
			"editByRuntime" => true,
			"readMinAccess" => 0,
			"insertMinAccess" => null,
			"updateMinAccess" => null,
			"deleteMinAccess" => null
		),
        "leistungskategorie_id" => array(
            "dbField" => "leistungskategorie_id",
            "key" => "",
            "label" => "Leistungskategorie_id",
            "listlabel" => "LK-ID",
            "fieldPos" => 2,
            "fieldGroup" => "main",
            "description" => "",
            "type" => "int",
            "size" => "11",
            "sql" => "Select leistungskategorie_id, leistungskategorie FROM {TABLE.leistungskategorie}",
            "sysType" => "int",
            "htmlType" => "select single",
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
        "aktiv" => array(
            "dbField" => "aktiv",
            "key" => "",
            "label" => "Aktiv",
            "listlabel" => "Aktiv",
            "fieldPos" => 2,
            "fieldGroup" => "main",
            "description" => "",
            "type" => "enum",
            "size" => "'Ja','Nein'",
            "sql" => "",
            "sysType" => "enum",
            "htmlType" => "select single",
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
		"Bezeichnung" => array(
			"dbField" => "Bezeichnung",
			"key" => "",
			"label" => "Bezeichnung",
			"listlabel" => "Bez.",
			"fieldPos" => 5,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "text",
			"size" => "",
			"sql" => "",
			"sysType" => "text",
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
		"leistungseinheit" => array(
			"dbField" => "leistungseinheit",
			"key" => "",
			"label" => "Leistungseinheit",
			"listlabel" => "LE",
			"fieldPos" => 6,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "60",
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
		"leistungseinheit_abk" => array(
			"dbField" => "leistungseinheit_abk",
			"key" => "",
			"label" => "Leistungseinheit (Abk)",
			"listlabel" => "LE",
			"fieldPos" => 6,
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
		"leistungseinheit2" => array(
			"dbField" => "leistungseinheit2",
			"key" => "",
			"label" => "Leistungseinheit 2",
			"listlabel" => "LE",
			"fieldPos" => 6,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "60",
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
		"leistungseinheit2_abk" => array(
			"dbField" => "leistungseinheit2_abk",
			"key" => "",
			"label" => "Leistungseinheit 2 (Abk)",
			"listlabel" => "LE 2",
			"fieldPos" => 6,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "10",
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
		"preis_pro_einheit" => array(
			"dbField" => "preis_pro_einheit",
			"key" => "",
			"label" => "Preis / Einheit",
			"listlabel" => "Preis /Einh.",
			"fieldPos" => 7,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "float",
			"size" => "8,2",
			"sql" => "",
			"sysType" => "float",
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
		"waehrung" => array(
			"dbField" => "waehrung",
			"key" => "",
			"label" => "Währung",
			"listlabel" => "Whrg",
			"fieldPos" => 8,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "20",
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
		"image" => array(
			"dbField" => "image",
			"key" => "",
			"label" => "Bild",
			"listlabel" => "Bild",
			"fieldPos" => 8,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "file",
			"size" => "1",
			"sql" => "",
			"sysType" => "input",
			"htmlType" => "file",
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
		"created" => array(
			"dbField" => "created",
			"key" => "",
			"label" => "Created",
			"listlabel" => "Created",
			"fieldPos" => 9,
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
			"fieldPos" => 10,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "",
			"sql" => "",
			"sysType" => "modified",
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
		)
	),
	"Joins" => array(
            "Leistungskategorie" => array(
                    "name"          =>"Leistungskategorie",  // "Zeiterfassungen",
                    "confkey"       => "leistungskategorie",  // "p_entries",
                    "rel"           => "OneToOne",  // "ManyToOne",
                    "leadingTblConfName" => "leistungskatalog",
                    "key"           => "leistungskategorie_id", 
                    "foreignTbl"    => "{TABLE.leistungskategorie}", 
                    "foreignKey"    => "leistungskategorie_id",
                    "dependent"     => false, //array("checkbox", "bln", "true,false"),  //  false,
                    "listAutoJoin"  => true, //array("checkbox", "bln", "true,false"),  // true,
                    "listFields"    => "leistungskategorie",  // "Mitarbeiter", 
                    "listHideKey"   => '', //array("text", "txt", ""), 
                    "listHideFlds"  => '', //array("text", "txt", ""),
                    "listPosition"  => 'append', //array("text", "str", "10")
                )
	),
	"Lists" => array(
		"0" => array(
			"ListRenderMode" => "Auto",
			"ListFunction" => "",
			"ListTemplate" => "",
			"name" => "",
			"select" => "aktiv, Bezeichnung, leistungseinheit_abk,preis_pro_einheit, modified, leistungskategorie_id",
			"from" => "",
			"join" => "",
			"where" => "",
			"group" => "",
			"having" => "",
			"defaultOrderFld" => "leistungskategorie_id",
			"defaultOrderDir" => "ASC",
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
?>
