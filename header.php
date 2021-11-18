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

require_once $MConf['AppRoot'] . $MConf['Modul_Dir'] . 'activitylog/activitylog.php';
require_once $MConf['AppRoot'] . $MConf['Modul_Dir'] . 'login/login.php';
require_once $MConf['AppRoot'] . 'smarty3/Smarty.class.php';
require_once($MConf['AppRoot'] . $MConf['Class_Dir'] . 'myTplEngine.class.php');

$vendorAutoloaderFile = __DIR__ . '/vendor/autoload.php';
if (file_exists($vendorAutoloaderFile)) {
    require_once $vendorAutoloaderFile;
}

spl_autoload_register(function($className) {
    $loadFileIfExists = function($file) {
        if (file_exists($file)) {
            include $file;
            return true;
        }
        return false;
    };

    if (!$loadFileIfExists(__DIR__ . '/class/' . $className . '.class.php')) {
        // Call __autoload defined in include/stdlib.php
        __autoload($className);
    }

});

