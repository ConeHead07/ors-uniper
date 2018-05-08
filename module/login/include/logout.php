<?php 

if (isset($user) && isset($user["uid"]) ) {
	// SETZE ONLINE-STATUS => LOGIN
	$SQL = "UPDATE `".$_TABLE["user"]."` SET \n";
	$SQL.= " onlinestatus = 'loggedout', \n";
	$SQL.= " lastlogin = NOW() \n";
	$SQL.= " WHERE uid = '".$user["uid"]."'";
	MyDB::query($SQL);
	// echo "<pre>#".__LINE__." ERROR:".MyDB::error()."\nSQL:".fb_htmlEntities($SQL)."</pre>\n";
	// die("<pre>#".__LINE__." ".basename(__FILE__)." user:".print_r($user, true)."</pre>\n");
} else {
	// DEBUG
}

$_SESSION = array();
session_destroy();
$msg = "Sie wurden abgemeldet!<br>\n";
?>