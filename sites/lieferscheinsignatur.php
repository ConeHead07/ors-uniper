<?php
set_time_limit(30);


// Include the main TCPDF library (search for installation path).
require(__DIR__ . '/../include/conf.php');
require_once $MConf['AppRoot'] . 'header.php';
require_once $MConf['AppRoot'] . $MConf['Inc_Dir'] . 'conf_lib.php';
require_once $MConf['AppRoot'] . $MConf['Class_Dir'] . 'dbconn.class.php';
require_once $MConf['AppRoot'] . $MConf['Class_Dir'] . 'SmtpMailer.class.php';
require_once $MConf['AppRoot'] . $MConf['Inc_Dir'] . 'conn.php';
require_once $MConf['AppRoot'] . $MConf['Inc_Dir'] . 'tcpdf_include.php';
require_once $MConf['AppRoot'] . $MConf['Modul_Dir'] . 'lieferschein/lieferschein.model.php';
require_once $MConf['AppRoot'] . $MConf['Modul_Dir'] . 'lieferschein/lieferschein.model.php';

$AID = $_REQUEST['id'] ?? 0;
if ($AID && !empty($_REQUEST['img'])) {
    $img = $_REQUEST['img'] ?? '';
    $lid = $_REQUEST['lid'] ?? '';

    if ($img && in_array($img, ['mt', 'kd']) && $AID && $lid) {
        $lsmodel = new LS_Model($AID, $lid);
        $daten = $lsmodel->getData();

        if (substr($daten["sig_{$img}_dataurl"], 0, 5) === 'data:') {
            $p = strpos($daten["sig_{$img}_dataurl"], ',');
            $dataurlStartInfo = substr($daten["sig_{$img}_dataurl"], 0, $p);
            list($mimeType, $encoding) = explode(';', substr($dataurlStartInfo, 5, -1));

            header('Content-Type: ' . $mimeType);
            $binary = base64_decode(substr($daten["sig_{$img}_dataurl"], $p + 1));

            echo $binary;
            exit;
        }
    }
    die('Image-Error');
}
