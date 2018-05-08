<?php

/**
 * 
 * @param int $id
 * @return string
 */
function leistung_einheit2($id) {
    /* @var $db dbconn */
    global $db;
    $sql = 'SELECT leistungseinheit2 FROM mm_leistungskatalog WHERE leistung_id = :id';
    return (string)$db->query_one($sql, array('id' => $id));
}

