<?php 
require_once('header.php');

$query= trim(getRequest('query', ''));
$log = [];

function lsResponseError(string $error) {
    global $query, $log;
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode([
        'type' => 'error',
        'query' => $query,
        'error' => $error,
        'log' => $log

    ]);
    exit;
}

function lsResponseSuccess(array $daten = []) {
    global $query, $log;
    header('Content-Type: application/json; charset=UTF-8');
    $daten['type'] = 'success';
    $daten['query'] = $query;
    echo json_encode($daten);
    exit;
}

if (!trim($query)) {
    $sql = 'SELECT * FROM mm_user WHERE freigegeben = "Ja" ORDER BY lastlogin DESC';
} else {
    $isNumber = is_numeric($query);
    $isEmail = strpos($query, '@') !== false;
    $queryLen = strlen($query);

    $orWhere = [];
    if ($isEmail) {
        $orWhere[] = ' email LIKE ' . $db::quote('%' . $query . '%');
        $orderBy = 'POSITION( LOWER(' . $db::quote($query ) . ') IN LOWER(email))';
    } elseif ($isNumber) {
        $orWhere[] = ' uid = ' . $db::quote($query );
        $orWhere[] = ' personalnr LIKE ' . $db::quote('%' . $query . '%');
        $orWhere[] = ' user LIKE ' . $db::quote('%' . $query . '%');

        $orderBy = '
            IF (
                uid = ' . $db::quote($query ) . '
                OR personalnr LIKE ' . $db::quote("_$query" ) . '
                OR personalnr LIKE ' . $db::quote($query ) . ',
                0,
                IF (
                    POSITION( UPPER(' . $db::quote($query ) . ') IN UPPER(personalnr)) > 0,
                    POSITION( UPPER(' . $db::quote($query ) . ') IN UPPER(personalnr)) + (LENGTH(personalnr) - ' . $queryLen . '),
                    
                    IF (
                        POSITION( UPPER(' . $db::quote($query ) . ') IN UPPER(user)) > 0,
                        POSITION( UPPER(' . $db::quote($query ) . ') IN UPPER(user)) + (LENGTH(user) - ' . $queryLen . '),
                        ' . $queryLen . '
                    )
                )
            ) ASC
            ';
    } else {
        $orWhere[] = ' user LIKE ' . $db::quote('%' . $query . '%');
        $orWhere[] = ' email LIKE ' . $db::quote('%' . $query . '%');
        $orWhere[] = ' personalnr LIKE ' . $db::quote('%' . $query . '%');
        $orWhere[] = ' nachname LIKE ' . $db::quote('%' . $query . '%');
        $orWhere[] = ' vorname LIKE ' . $db::quote('%' . $query . '%');

        $orderBy = '
            IF (
                user LIKE ' . $db::quote($query ) . '
                 OR email LIKE ' . $db::quote($query ) . '
                 OR nachname LIKE ' . $db::quote($query ) . '
                 OR vorname LIKE ' . $db::quote($query ) . '
                 OR personalnr LIKE ' . $db::quote($query ) . ',
                0,
                IF (
                    POSITION( LOWER(' . $db::quote($query ) . ') IN LOWER(user)) > 0,
                    POSITION( LOWER(' . $db::quote($query ) . ') IN LOWER(user)) + (LENGTH(user) - ' . $queryLen . '),
                    20
                )
                +
                IF (
                    POSITION( LOWER(' . $db::quote($query ) . ') IN LOWER(email)) > 0,
                    POSITION( LOWER(' . $db::quote($query ) . ') IN LOWER(email)) + (LENGTH(user) - ' . $queryLen . '),
                    20
                )
                +
                IF (
                    POSITION( UPPER(' . $db::quote($query ) . ') IN UPPER(personalnr)) > 0,
                    POSITION( UPPER(' . $db::quote($query ) . ') IN UPPER(personalnr)) + (LENGTH(personalnr) - ' . $queryLen . '),
                    20
                )
                +
                IF (
                    POSITION( LOWER(' . $db::quote($query ) . ') IN LOWER(nachname)) > 0,
                    POSITION( LOWER(' . $db::quote($query ) . ') IN LOWER(nachname)) + (LENGTH(nachname) - ' . $queryLen . '),
                    20
                )
                +
                IF (
                    POSITION( LOWER(' . $db::quote($query ) . ') IN LOWER(vorname)) > 0,
                    POSITION( LOWER(' . $db::quote($query ) . ') IN LOWER(vorname)) + (LENGTH(vorname) - ' . $queryLen . '),
                    20
                )
            ) ASC
            ';
    }

    $sql = 'SELECT 
    *
    FROM mm_user 
    WHERE ' . implode(' OR ', $orWhere) . ' ORDER BY ' . $orderBy; // ; //
}

$countSql = 'SELECT COUNT(1) AS total FROM (' . $sql . ') AS t';
$total = (int)$db->query_one($countSql);
$rows = $db->query_rows($sql . '  LIMIT 100', 0);

lsResponseSuccess([
    'total' => $total,
    'sql' => $sql,
    'countSql' => $countSql,
    'data' => $rows,
]);
