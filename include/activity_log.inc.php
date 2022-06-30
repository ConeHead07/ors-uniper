<?php
// START BASE CONF
$_CONF["activity_log"] = array(
	"ConfName" => "id",
	"Title" => "Id",
	"Description" => "",
	"Src" => "",
	"Db" => "",
	"Table" => "mm_activity_log",
	"PrimaryKey" => "id",
	"readMinAccess" => 0,
	"insertMinAccess" => 2,
	"updateMinAccess" => null,
	"deleteMinAccess" => null,
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
		"timestamp" => array(
			"dbField" => "timestamp",
			"key" => "",
			"label" => "Timestamp",
			"listlabel" => "Timestamp",
			"fieldPos" => 2,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "timestamp",
			"size" => "",
			"sql" => "",
			"sysType" => "timestamp",
			"htmlType" => "text",
			"default" => "current_timestamp()",
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
		"serverscript" => array(
			"dbField" => "serverscript",
			"key" => "",
			"label" => "Serverscript",
			"listlabel" => "Serverscript",
			"fieldPos" => 3,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "100",
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
		"ip" => array(
			"dbField" => "ip",
			"key" => "",
			"label" => "Ip",
			"listlabel" => "Ip",
			"fieldPos" => 4,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "70",
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
		"user" => array(
			"dbField" => "user",
			"key" => "",
			"label" => "User",
			"listlabel" => "User",
			"fieldPos" => 5,
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
		"uid" => array(
			"dbField" => "uid",
			"key" => "",
			"label" => "Uid",
			"listlabel" => "Uid",
			"fieldPos" => 6,
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
		"s" => array(
			"dbField" => "s",
			"key" => "",
			"label" => "S",
			"listlabel" => "S",
			"fieldPos" => 7,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "80",
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
		"docid" => array(
			"dbField" => "docid",
			"key" => "",
			"label" => "Docid",
			"listlabel" => "Docid",
			"fieldPos" => 8,
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
		"get" => array(
			"dbField" => "get",
			"key" => "",
			"label" => "Get",
			"listlabel" => "Get",
			"fieldPos" => 9,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "200",
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
		"post" => array(
			"dbField" => "post",
			"key" => "",
			"label" => "Post",
			"listlabel" => "Post",
			"fieldPos" => 10,
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
		"files" => array(
			"dbField" => "files",
			"key" => "",
			"label" => "Files",
			"listlabel" => "Files",
			"fieldPos" => 11,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "160",
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
		"useragent" => array(
			"dbField" => "useragent",
			"key" => "",
			"label" => "Useragent",
			"listlabel" => "Useragent",
			"fieldPos" => 12,
			"fieldGroup" => "main",
			"description" => "",
			"type" => "char",
			"size" => "100",
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
		)
	),
	"Joins" => array(

	),
	"Lists" => array(

	)
);
// ENDE BASE CONF
?>
