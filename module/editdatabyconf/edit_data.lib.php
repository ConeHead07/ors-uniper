<?php
/**
 * Created by PhpStorm.
 * User: f.barthold
 * Date: 06.11.2021
 * Time: 12:37
 */

function getItemEditInstance(&$conf, $connid, $user, $id = false): ItemEdit {
    if (empty($conf['ItemEditClass'])) {
        $itemEditClass = 'ItemEdit';
    } else {
        $itemEditClass = $conf['ItemEditClass'];
    }
    if (class_exists($itemEditClass)) {
        // Hurra, keine Ahnung ob class_exists bereits den Autoloade aktiviert
    }
    return new $itemEditClass($conf, $connid, $user, $id);
}
