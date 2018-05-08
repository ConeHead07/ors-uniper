<?php 

require_once(dirname(__FILE__)."/login_lib.php");
if (!isset($pruefe_login)) $pruefe_login = true;
kill_sessionsByTimeout($ConnUserDB["connid"], $_TABLE["user"], $_CONF["SessionExpireMinutes"]);

$user = get_userBySession();
// echo "<pre>#".__LINE__." print_r(user):".print_r($user, true)."</pre>\n";
if ($pruefe_login) {
    // die("#".__LINE__." ".basename(__FILE__)." SESSION:".print_r($_SESSION, true)."<br>\n");
	if ($user && isset($user["uid"]) ) {
		// echo "#".__LINE__." <br>\n";
        // Alles OK
		// SETZE LAST-LOGIN => NOW()
		set_lastlogin($ConnUserDB["connid"], $_TABLE["user"], $user["uid"]);
	} else {
		// echo "#".__LINE__." <br>\n";
        include("login.php");
        // echo "#".__LINE__." <br>\n";
		exit;
	}
}
// echo "#".__LINE__." <br>\n";
?>
