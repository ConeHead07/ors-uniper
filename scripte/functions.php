<?php

function fitUsername($name) {
    return strtr(strtolower($name), array(
        'ä' => 'ae',
        'ö' => 'oe',
        'ü' => 'ue',
        'ß' => 'ss',
        ' ' => '',
        '-' => '',
    ));
}

function getPwByUsername($username) {
    return substr(md5(strrev(md5( strtolower($username) ))), 5, 5);
}

function getAnredeByVorname($vorname) {
    return !preg_match('/[aeui]\b/', $vorname) ? 'Herr' : 'Frau';
}

