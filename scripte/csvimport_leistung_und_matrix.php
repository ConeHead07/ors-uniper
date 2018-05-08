<?php

set_time_limit(90);
//header('Content-Type: text/html; charset="ISO-8859-1"');
header('Content-Type: text/html; charset="UTF-8"');

require_once dirname(__FILE__)."/../include/conf.php";
require_once $MConf["AppRoot"] . $MConf["Inc_Dir"]   . "conf_lib.php";
require_once $MConf["AppRoot"] . $MConf["Class_Dir"] . "dbconn.class.php";
require_once $MConf["AppRoot"] . $MConf["Inc_Dir"]   . "conn.php";
require_once $MConf["AppRoot"] . $MConf["Class_Dir"] . "CsvXls2Array.class.php";

$csv_dir = str_replace('/', DIRECTORY_SEPARATOR, $MConf["AppRoot"] . 'material/');
$csv_file_lk = 'Leistungskatalog_2014-03-31.utf8.csv';
$csv_file_pm = 'Leistungskatalog_preismatrix_2014-03-31.utf8.csv';

$CsvPM = new CsvXls2Array();
$CsvPM->parse_xls_file($csv_dir . $csv_file_pm);
$ktg1 = '';
$ktg2 = '';

$PM = array();
$itemsByKtg1 = array();
for ($di = 0; $di < count($CsvPM->DATA); $di++) {
    $row = $CsvPM->DATA[$di];
    if ( '' === trim( implode('', $row))) continue;
    
    $isThead = 0;
    if (strpos($row[0], 'Innerhalb des Geb') !== false) {
        $ktg1 = 'Innerhalb des Gebaeudes';
        $isThead = 1;
    } elseif (strpos($row[0], 'Innerhalb des Ortes') !== false) {
        $ktg1 = 'Innerhalb des Ortes';
        $isThead = 2;
    } elseif (trim($row[4]) === '' && strpos($row[0], 'koordinierend') !== false) {
        $ktg1 = 'Koordinierende Taetigkeit';
        $isThead = 3;
    }
    
    if ($isThead && $isThead < 3) {
        $mengen = array();
        for($ci = 0; $ci < 4; ++$ci) {
            $mengen[$ci] = array('von'=>'','bis'=>'');
        }
        for($ci = 0; $ci < 3; ++$ci) {
            //echo $row[$ci+1] . '; <br>' . PHP_EOL;
            if (preg_match('/^\s*([0-9]*)\b.*?\b([0-9]*)\s*AP\s*$/', $row[$ci+1], $m)) {
                $mengen[$ci]['von'] = $m[1];
                $mengen[$ci]['bis'] = $m[2];
                //die("Matches m: " . print_r($m, 1));
            } else{
                die("No Matches in " . $row[$ci+1]);
            }
        }
        if (preg_match('/\b([0-9]+)\s*AP\s*$/', $row[4], $m)) {
            $mengen[3]['von'] = $m[1];
            $mengen[3]['bis'] = '';
//            die("Matches m: " . print_r($m, 1));
        } else{
            die("No Matches in " . $row[4]);
        }
        continue;
    }
    
//    die(print_r($mengen,1));
    switch($ktg1) {
        case 'Innerhalb des Gebaeudes':
        case 'Innerhalb des Ortes':
            $av = str_replace('Variante', 'Arbeitsvariante', $row[0]);
            for($ci = 1; $ci < 5; ++$ci) {
                $p = array($row[$ci], '');
                if (preg_match('/^\s*([0-9,]*)\s*([^0-9,]*)\s*$/', $row[$ci], $m)) {
                    $p[0] = $m[1];
                    $p[1] = $m[2];
                }
                $PM[] = array(
                    'kategorie1'   => $ktg1,
                    'kategorie2'   => trim($av),
                    'leistung'     => '',
                    'leistungseinheit' => 'AP',
                    'mengen_von'   => $mengen[$ci-1]['von'],
                    'mengen_bis'   => $mengen[$ci-1]['bis'],
                    'preis'        => $p[0],
                    'preiseinheit' => $p[1],
                );
                $itemsByKtg1[$ktg1][] = $PM[count($PM)-1];
            }
            break;
        
        case 'Koordinierende Taetigkeit':
                if ('' === trim($row[3].$row[4])) continue;
                $p = array($row[4]);
                if (preg_match('/^\s*([0-9,]*)\s*([^0-9,]*)\s*$/', $row[4], $m)) {
                    $p[0] = $m[1];
                    $p[1] = $m[2];
                }
                
                $mengen = array('von'=>'','bis'=>'');
                $txt = trim($row[0].$row[1].$row[2]);
                $m = array();
                if (preg_match('/\b([0-9]+)\s*-\s*([0-9]+)\s*(AP|MA)\s*$/', $txt, $m)) {
                    $mengen['von'] = $m[1];
                    $mengen['bis'] = $m[2];
                    
                } elseif (preg_match('/-\s*([0-9]+)\s*(AP|MA)\s*/', $txt, $m)) {
                    $mengen['von'] = 1;
                    $mengen['bis'] = $m[1];
                    
                } elseif (preg_match('/.{3}\.\s+\b([0-9]+)\s*(AP|MA)\s*/', $txt, $m)) {
                    $mengen['von'] = $m[1];
                    
                }
                if (!empty($m)) {
//                    echo '#' . __LINE__ . ' Matches in ' . $txt . ': ' . print_r($m, 1) . '<br>' . PHP_EOL;
                    $txt = str_replace($m[0], '', $txt);
                }
                
                $PM[] = array(
                    'kategorie1'   => $ktg1,
                    'kategorie2'   => '',
                    'leistung'     => $txt,
                    'leistungseinheit' => $row[3],
                    'mengen_von'   => $mengen['von'],
                    'mengen_bis'   => $mengen['bis'],
                    'preis'        => $p[0],
                    'preiseinheit' => str_replace('?', 'EUR', $p[1]),
                );
                $itemsByKtg1[$ktg1][] = $PM[count($PM)-1];
            break;
    }
}

echo '<pre>#' . __LINE__ . print_r($PM, 1) . '</pre>' . PHP_EOL;

$preparedInsertLK = 'INSERT INTO mm_leistungskatalog_alt_mit_matrix('
                   .'leistung, leistungseinheit, mengen_von, mengen_bis, kategorie, kategorie2, '
                   .'preis, preiseinheit, dussmann, mertens_henk, reserve1, reserve2) '
                   .'VALUES('
                   .':leistung, :leistungseinheit, :mengen_von, :mengen_bis, :kategorie, :kategorie2, '
                   .':preis, :preiseinheit, :dussmann, :mertens_henk, :reserve1, :reserve2'
                   .')';

$preparedInsertMx = 'INSERT INTO mm_leistungspreismatrix('
                   .'leistung_id, preis, preiseinheit, mengen_von, mengen_bis'
                   .') '
                   .'VALUES('
                   .':leistung_id, :preis, :preiseinheit, :mengen_von, :mengen_bis'
                   .')';

$Csv = new CsvXls2Array();
$Csv->parse_xls_file($csv_dir . $csv_file_lk);

$firstRowIsTitle = 1;
$offset = $firstRowIsTitle?1:0;
$NewCSV = array();

$leistungsIdx = 0;
$leistungseinheitIdx = 1;
$preisIdx = 2;
$dussIdx = 3;
$mertensIdx = 4;
$reserve1Idx = 5;
$reserve2Idx = 6;


//die( '<pre>' . print_r($Csv->DATA, 1));
$lastKtg1 = '';
$insertRows = "";
$fields = array();
for ($i = 0; $i < count($Csv->DATA); $i++) {
        if (0 === $i && $firstRowIsTitle) {
            $fields = $Csv->DATA[0];
            $ktg1Idx = count($fields);
            $ktg2Idx = $ktg1Idx + 1;
            $preiseinheitIdx = $ktg1Idx + 2;
            $mengevonIdx = $ktg1Idx + 3;
            $mengebisIdx = $ktg1Idx + 4;
            $fields[$ktg1Idx] = 'Kategorie';
            $fields[$ktg2Idx] = 'Kategorie2';
            $fields[$preiseinheitIdx] = 'Preiseinheit';
            $fields[$mengevonIdx] = 'Menge-Von';
            $fields[$mengebisIdx] = 'Menge-bis';
            continue;
        }
        $row = $Csv->DATA[$i];
        for($j = count($row); $j < count($fields); ++$j) $row[$j] = '';
        
//        echo '#' . __LINE__ . ' i: ' . $i . ' :: row[0] => ' . $row[0] . '; row[1] => ' . $row[1] . '; row[2] => ' . $row[2] . '; <br>' . PHP_EOL;
        $variante = '';
        if ( trim($row[0]) && '' == trim($row[1] . $row[2])) {
            $lastKtg1 = $row[0];
            continue;
        }
        
        $p_av = strpos($row[0], 'Arbeitsvariante');
        if (false !== $p_av) {
            if (0 === $p_av) {
            
                $variante = substr($row[0], 0, 17);
                $row[0] = substr($row[0], 17);
            } else {
                die("#" . __LINE__ . ' p_av: ' . $p_av . '; ' . $row[0]);
            }
        }
        
        if (preg_match('/^\s*([0-9,]*)\s*([^0-9,]*)\s*$/', $row[2], $m)) {
            $row[2] = $m[1];
            $row[$preiseinheitIdx] = $m[2];
        }
        
        if (preg_match('/([0-9]*)\s*-?([0-9]*)\s*AP/', $row[0], $m)) {
//            echo '#'.__LINE__ . ' row[0] ' . $row[0] . ' matches ' . print_r($m,1) . '<br>' . PHP_EOL;
            if ($m[1] && $m[2]) {
                $row[$mengevonIdx] = $m[1];
                $row[$mengebisIdx] = $m[2];
            } else {
                $row[$mengevonIdx] = $m[2];
            }
            $row[0] = str_replace($m[0], '', $row[0]);
        }

        $row[$ktg1Idx] = $lastKtg1;
        $row[$ktg2Idx] = $variante;
        
        $db->query( $preparedInsertLK, 
            array(
                'leistung'         => utf8_decode($row[$leistungsIdx]),
                'leistungseinheit' => utf8_decode($row[$leistungseinheitIdx]),
                'mengen_von'       => $row[$mengevonIdx],
                'mengen_bis'       => $row[$mengebisIdx],
                'kategorie'        => utf8_decode($row[$ktg1Idx]),
                'kategorie2'       => utf8_decode($row[$ktg2Idx]),
                'preis'            => str_replace(',', '.', $row[$preisIdx]),
                'preiseinheit'     => str_replace('?', 'EUR', utf8_decode($row[$preiseinheitIdx]) ),
                'dussmann'         => $row[$dussIdx],
                'mertens_henk'     => $row[$mertensIdx],
                'reserve1'         => $row[$reserve1Idx],
                'reserve2'         => $row[$reserve2Idx],
            )
        );
        if ($db->error()) die( '#' . __LINE__ . ' ' . $db->error() . '<br>' . $db->lastQuery);
        $lstgID = $db->insert_id();
        
        if ( false !== strpos($row[$preiseinheitIdx], 'matrix') ) {
            
            if ( strstr($row[$ktg1Idx], 'innerhalb des Geb')) {
                $mtrxKtg1 = 'Innerhalb des Gebaeudes';
            }
            elseif( strstr($row[$ktg1Idx], 'innerhalb des Ortes')) {
                $mtrxKtg1 = 'Innerhalb des Ortes';
            }
            elseif( strstr($row[$ktg1Idx], 'Koordinierende')) {
                $mtrxKtg1 = 'Koordinierende Taetigkeit';
            }
            
//            if (strstr($mtrxKtg1, 'Innerhalb') ) die( print_r($itemsByKtg1[$mtrxKtg1],1));
            
            
            foreach($itemsByKtg1[$mtrxKtg1] as $k => $v) {
                // echo $v['kategorie2'] . ' von ' . $v['mengen_von'] . ' bis ' . $v['mengen_bis'] . '<br>' . PHP_EOL;
                if ( ($row[$ktg2Idx] && $row[$ktg2Idx] == $v['kategorie2'])
                      || strstr( $v['leistung'], $row[$leistungsIdx])
                   ) {
                    $newIdx = count($NewCSV);
                    $NewCSV[$newIdx] = $row;
                    $NewCSV[$newIdx][$mengevonIdx] = $v['mengen_von'];
                    $NewCSV[$newIdx][$mengebisIdx] = $v['mengen_bis'];
                    $NewCSV[$newIdx][$preisIdx] = $v['preis'];
                    $NewCSV[$newIdx][$preiseinheitIdx] = $v['preiseinheit'];
                    
                    $db->query($preparedInsertMx, array(
                        'leistung_id'  => $lstgID,
                        'preis'        => str_replace(',', '.', $v['preis']),
                        'preiseinheit' => str_replace('?', 'EUR', utf8_decode($v['preiseinheit'])),
                        'mengen_von'   => $v['mengen_von'],
                        'mengen_bis'   => $v['mengen_bis'],
                    ));
                
                }
            }
        } else {
            $NewCSV[] = $row;
        }
}

$saveas = '../material/preismatrix.kombiniert.csv';
$fp = fopen( $saveas, 'w+');
if ($fp) {
    fputs($fp, '"' . implode('";"', $fields) . '"' . "\n");
    for($i = 0; $i < count($NewCSV); ++$i) {
        foreach($NewCSV[$i] as $k => $v) {
            $NewCSV[$i][$k] = trim($v);
        }
        $NewCSV[$i][$preisIdx] = str_replace(',', '.', $NewCSV[$i][$preisIdx]);
        $_tmp = utf8_decode($NewCSV[$i][$preiseinheitIdx]);
        if ($_tmp === '?') {
            $NewCSV[$i][$preiseinheitIdx] = 'EUR';
        }
        fputs($fp, '"' . utf8_decode(implode('";"', $NewCSV[$i])) . '"' . "\n");
    }
    fclose($fp);
}

echo $saveas;
echo '<table>' . PHP_EOL;
if (!empty($fields)) {
    echo '<thead><tr><th>' . implode('</th><th>', $fields). '</th></tr></thead>' . PHP_EOL;
}
echo '<tbody>' . PHP_EOL;
for($i = 0; $i < count($NewCSV); ++$i) {
    echo '<tr><td>' . implode('</td><td>', $NewCSV[$i]) . '</td></tr>' . PHP_EOL;
}
echo '</tbody>' . PHP_EOL;
echo '</table>' . PHP_EOL;



