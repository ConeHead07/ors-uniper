<?php  
require("../header.php");
require($MConf["AppRoot"]."sites".DS."umzugsantrag_datenblatt.php");

$mode = getRequest('mode', '');
if (empty($AID)) $AID = getRequest("id",'11');

if ($AID ) {
    $view = '';
    if ($mode == 'property') {
        $row = $db->query_row('SELECT * FROM mm_umzuege WHERE aid = ' . (int)$AID);
        switch($row['umzugsstatus']) {
            case 'beantragt':
            case 'angeboten':
                $view = 'kalkulation';
                break;
            case 'abgeschlossen':
                $view = 'rechnung';
        }
    }
	// die("#".__LINE__ . ' ' . $view);
    $umzugsblatt = get_umzugsblatt($AID, $view);
    //$body_content = implode("", file($MConf["AppRoot"].$MConf["Tpl_Dir"]."umzugsformular.tpl.html"));
    if (basename(__FILE__) == basename($_SERVER["PHP_SELF"])) {
            echo $umzugsblatt;
    }

}