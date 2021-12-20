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

if ($batchCmd === 'tempDelete') {
    if (is_array($aids) && count($aids) > 0) {

        $aids = array_filter($aids, function($val) { return is_numeric($val) && (int)$val > 0; });
        $aids = array_map('intval', $aids);
        $aids = array_unique($aids);

        if (!count($aids)) {
            die('Es wurden keine Auftrags-Ids übergeben!');
        }

        $sql = 'SELECT aid FROM mm_umzuege WHERE aid IN (' . implode(',', $aids) . ') '
            . ' AND umzugsstatus = "temp"';

        $rows = $db->query_rows($sql);
        $aAids = array_column($rows, 'aid');

        if (!count($aAids)) {
            die('<pre>Es wurden keine Aufträge mit temp-Status zu den Auftrags-IDs gefunden!' . "\n"
                . $sql . "<br>\n"
                . 'rows: ' . "\n" . json_encode($rows, JSON_PRETTY_PRINT) . "\n"
                . 'aAids: ' . "\n" . json_encode($aAids, JSON_PRETTY_PRINT)
                . '</pre>'
            );
        }

        $sql = 'DELETE FROM mm_umzuege_leistungen WHERE aid IN (' . implode(',', $aAids). ')';
        $sthL = $db->query($sql);
        $numDelLeistungen = $db->affected_rows();

        $sql = 'DELETE FROM mm_umzuege WHERE aid IN (' . implode(',', $aAids). ')';
        $sthA = $db->query($sql);
        $numAuftraege = $db->affected_rows();

        echo 'Es wurden ' . $numAuftraege . ' Aufträge mit insgesamt ' . $numDelLeistungen . ' Leistungen gelöscht!';
    }
}
