<?php
require_once(dirname(__FILE__)."/../../header.php");
if (basename(__FILE__)==basename($_SERVER["PHP_SELF"])) register_shutdown_function("activity_log");


function activity_log() {
	global $db;
	global $_TABLE;
	global $s;
	global $id;
	global $user;
	
	$LogFields = $db->get_fields_assoc($_TABLE["activity_log"]);
	
	$_LOGPOST = $_POST;
	if (!empty($_LOGPOST["password"])) $_LOGPOST["password"] = "****";
	if (!empty($_LOGPOST["eingabe"]["pw"])) $_LOGPOST["eingabe"]["pw"] = "****";
	if (!empty($_LOGPOST["eingabe"]["pw_wh"])) $_LOGPOST["eingabe"]["pw_wh"] = "****";
	
	$sql = "INSERT INTO `".$_TABLE["activity_log"]."` SET ";
	$sql.= "\n".$db->setFieldValue("timestamp", "NOW()", "function");
	$sql.= ",\n".$db->setFieldValue("serverscript", substr($_SERVER["PHP_SELF"],     0, $LogFields["serverscript"]["Size"]), "string", 1);
	$sql.= ",\n".$db->setFieldValue("ip",           substr($_SERVER["REMOTE_ADDR"],  0, $LogFields["ip"]["Size"]),           "string", 1); 
	$sql.= ",\n".$db->setFieldValue("user",         substr($user["user"],            0, $LogFields["user"]["Size"]),         "string", 1);
	$sql.= ",\n".$db->setFieldValue("uid",          $user["uid"], "integer", 1);
	$sql.= ",\n".$db->setFieldValue("s",            substr(getRequest("s"),          0, $LogFields["get"]["Size"]),          "string", 1);
	
	$sql.= ",\n".$db->setFieldValue("docid",        getRequest("id"), "integer", 1);
	$sql.= ",\n".$db->setFieldValue("get",          substr($_SERVER["QUERY_STRING"], 0, $LogFields["get"]["Size"]),          "string", 1);
	$sql.= ",\n".$db->setFieldValue("post",         arraytoQueryString("\$_POST", $_LOGPOST), "string", 1);
	$sql.= ",\n".$db->setFieldValue("files",        substr(arraytoQueryString("\$_FILES", $_FILES), 0, $LogFields["files"]["Size"]), "string", 1);
	$sql.= ",\n".$db->setFieldValue("useragent",    substr($_SERVER["HTTP_USER_AGENT"], 0, $LogFields["useragent"]["Size"]), "string", 1);
	if (basename(__FILE__)==basename($_SERVER["PHP_SELF"])) echo "#".__LINE__." ".basename(__FILE__)." sql:$sql<br>\n";
	
	$db->query($sql);
	
	activity_log_dropold_entries();
}

function activity_log_dropold_entries($days = 60) {
	global $db;
	global $_TABLE;
	
	$sql = "DELETE FROM ".$_TABLE["activity_log"]." WHERE timestamp < \"".date("Y-m-d", time()-($days*24*3600))."\"";
	$db->query($sql);
	if (basename(__FILE__)==basename($_SERVER["PHP_SELF"])) echo "#".__LINE__." ".basename(__FILE__)." sql:$sql<br>".$db->error()."<br>\n";
}

?>