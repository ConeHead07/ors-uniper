<?php
/**
 * Created by PhpStorm.
 * User: f.barthold
 * Date: 20.12.2021
 * Time: 11:46
 */
require_once '../header.php';

$batchCmd = getRequest('batchCmd', '');
$aids = getRequest('aids', '');
$s = $s ?? getRequest('s', '');

$userGruppe = $user['gruppe'];
$istUmzugsteam = $userGruppe === 'umzugsteam' || $s === 'auslieferung';
$istAdmin = $userGruppe === 'admin';
$istSuperAdmin = $istAdmin && $user['adminmode'] === 'superadmin';

if (!$istSuperAdmin) {
    http_send_status(403);
    die('Access denied');
}

if (is_string($aids)) {
    $aids = explode(',', $aids);
}

if ($batchCmd === 'erinnerungsmail') {
    if (is_array($aids) && count($aids) > 0) {
        $batchErinnern = new \module\Auftragsbearbeitung\BatchErinnerungsmails();
        $batchErinnern->setAuftragsIds($aids);
        $numMails = $batchErinnern->run();

        echo 'Es wurden ' . $numMails . ' Erinnerungsmails versendet!';
    }
}

if ($batchCmd === 'erinnerungsmailPreview') {
    if (is_array($aids) && count($aids) > 0) {
        $aid = $aids[0];
        $batchErinnern = new \module\Auftragsbearbeitung\BatchErinnerungsmails();
        echo $batchErinnern->getPreview($aid);
    }
}
