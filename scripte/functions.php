<?php

function fitUsername($name) {
    return strtr(strtolower($name), array(
        '�' => 'ae',
        '�' => 'oe',
        '�' => 'ue',
        '�' => 'ss',
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

