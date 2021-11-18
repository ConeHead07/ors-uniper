<?php
/**
 * Created by PhpStorm.
 * User: f.barthold
 * Date: 18.11.2021
 * Time: 11:46
 */

function leistungsRowToSheetHeader($row) {
    $sheet1header = [];
    if (empty($row) || count($row) === 0) {
        return [];
    }
    $firstIdx = key($row);
    $isAssoc = !is_int($firstIdx) && !is_numeric($firstIdx);
    foreach($row as $idx => $v) {
        $k = $isAssoc ? $idx : $v;
        $_colFormat = 'string';
        $_colTitle = str_replace('_', ' ', ucfirst($k));

        if ( preg_match('#(datum|_am|umzugsstatus_vom)#i', $k)) {
            $_colFormat ='MM.DD.YYYY HH:MM';
        } elseif ( preg_match('#(summe|preis)#i', $k)) {
            $_colFormat ='euro';
        } elseif ( preg_match('#(menge|plz)#i', $k) ) {
            $_colFormat = '0';
        }

        switch(strtolower($k)) {
            case 'tour_kennung':
                $_colTitle = 'Tour';
                break;

            case 'antragsdatum':
                $_colTitle = 'Bestelldatum';
                break;

            case 'bestaetigt_am':
                $_colTitle = 'Avisiert';
                break;

            case 'umzugstermin':
            case 'lieferdatum':
                $_colFormat ='MM.DD.YYYY';
                $_colTitle = 'Lieferdatum';
                break;

            case 'umzugsstatus':
                $_colTitle = 'Auftragsstatus';
                break;

            case 'umzugsstatus_vom':
                $_colTitle = 'Status vom';
                break;
        }
        $sheet1header[$_colTitle] = $_colFormat;
    }
    return $sheet1header;
}
