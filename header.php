<?php
set_time_limit(90);
require_once __DIR__ . '/include/conf.php';
header('Content-Type: text/html; charset=' . $charset);

require_once $MConf['AppRoot'] . $MConf['Inc_Dir'] . 'conf_lib.php';
require_once $MConf['AppRoot'] . $MConf['Class_Dir'] . 'dbconn.class.php';
require_once $MConf['AppRoot'] . $MConf['Class_Dir'] . 'SmtpMailer.class.php';
require_once $MConf['AppRoot'] . $MConf['Inc_Dir'] . 'conn.php';
require_once $MConf['AppRoot'] . $MConf['Inc_Dir'] . 'user.inc.php';

require_once $MConf['AppRoot'] . $MConf['Inc_Dir'] . 'stdlib.php';
require_once $MConf['AppRoot'] . $MConf['Inc_Dir'] . 'strip_magic_quoted_gpc.php';
require_once $MConf['AppRoot'] . $MConf['Inc_Dir'] . 'form_request.php';
require_once $MConf['AppRoot'] . $MConf['Inc_Dir'] . 'fbmail.php';
require_once $MConf['AppRoot'] . $MConf['Inc_Dir'] . 'lib_mail.php';
$userlogin = true; //
require_once $MConf['AppRoot'] . $MConf['Modul_Dir'] . 'login/login.php';
require_once $MConf['AppRoot'] . 'smarty3/Smarty.class.php';
require_once($MConf['AppRoot'] . $MConf['Class_Dir'] . 'myTplEngine.class.php');

require_once $MConf['AppRoot'] . $MConf['Modul_Dir'] . 'activitylog/activitylog.php';

spl_autoload_register(function($className) {
    $loadFileIfExists = function($file) {
        if (file_exists($file)) {
            include $file;
            return true;
        }
        return false;
    };

    $loadFileIfExists(__DIR__ . '/class/' . $className . '.class.php');

});

