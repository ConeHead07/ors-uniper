<?php
set_time_limit(30);

function LOX($line, $file) {
    // echo '#' . $line . ' ' . $file . "<br>\n";
}

// Include the main TCPDF library (search for installation path).
require(__DIR__ . '/../header.php');
require_once $MConf['AppRoot'] . $MConf['Inc_Dir'] . 'conf_lib.php';
require_once $MConf['AppRoot'] . $MConf['Class_Dir'] . 'dbconn.class.php';
require_once $MConf['AppRoot'] . $MConf['Class_Dir'] . 'SmtpMailer.class.php';
require_once $MConf['AppRoot'] . $MConf['Inc_Dir'] . 'conn.php';
require_once $MConf['AppRoot'] . $MConf['Inc_Dir'] . 'tcpdf_include.php';


$ktgIdLieferung = 18;
$ktgIdRabatt = 25;
$mode = $_REQUEST['mode'] ?? '';
if (empty($AID)) {
    $AID = $_REQUEST["aid"] ?? 0;
}
if (empty($AID)) {
    $AID = $_REQUEST["id"] ?? 0;
}
if (empty($lid)) {
    $lid= $_REQUEST["lid"] ?? 0;
}

if (empty($AID)) {
    die('INGUELTIGER SEITENAUFRUF! Es wurde keine AuftragsID übergeben');
}

if ($AID ) {
    LOX(__FILE__, __LINE__);
    $view = '';
    if (true) {
        $auftrag = $db->query_row('SELECT * FROM mm_umzuege WHERE aid = ' . (int)$AID);
        if (!$lid) {
            $lid = $db->query_one(
                'SELECT lid FROM mm_lieferscheine WHERE aid = :aid ORDER BY lid DESC',
                [ 'aid' => $AID]
            );
        }

        if (!$lid) {
            die('Fehlende Lieferschein-ID oder es wurde noch kein Lieferschein angelegt!');
        }
        $lieferschein = $db->query_row(
            'SELECT * FROM mm_lieferscheine WHERE aid = :aid AND lid = :lid',
            [ 'aid' => (int)$AID, 'lid' => $lid]
        );

        if (!is_array($lieferschein) || empty($lieferschein)) {
            die('Es wurde kein Lieferschein mit der ID ' . $lid . ' gefunden!');
        }

        LOX(__FILE__, __LINE__);
        $leistungen = $db->query_rows(
            'SELECT l.*,
                    k.leistungskategorie_id,
                    k.Bezeichnung,
                    k.produkt_link AS Link,
                    ktg.leistungskategorie AS Kategorie
                 FROM mm_umzuege_leistungen l
                 LEFT JOIN  `mm_leistungskatalog` k ON l.leistung_id = k.leistung_id
                 LEFT JOIN  `mm_leistungskategorie` ktg ON k.leistungskategorie_id = ktg.leistungskategorie_id
                 WHERE aid = ' . (int)$AID . ' AND k.leistungskategorie_id NOT IN (' . $ktgIdLieferung . ', ' . $ktgIdRabatt . ')');

        if (!count($leistungen)) {
            die('UNGUELTIGER SEITENAUFRUF! Es wurde keine Leistungen zum angegebenen Auftrag gefunden!');
        }

        // U-00030
        $lsNr = 'U-' . str_pad($AID, 5, '0', STR_PAD_LEFT);
        $lsDatum = date('d.m.Y', strtotime($lieferschein['lieferdatum']));

        $aArtikels = array_map(
            function($item) { return
                [
                    'Kategorie' => $item['Kategorie'],
                    'Bezeichnung' => $item['Bezeichnung'],
                    'Artikel' =>$item['Kategorie'] . ' ' . $item['Bezeichnung'],
                    'Link' => $item['Link'],
                ];
            },
            $leistungen
        );

        // $pdf = new EtikettenPDF();
        $pdf = new \module\Pdf\MertensLieferscheinEtiketten();

        $pdf->setArtikels($lsNr, $lsDatum, $aArtikels);

        $pdf->Output('lieferschein-' . $lid . '.pdf', 'I');
    }
}

//============================================================+
// END OF FILE
//============================================================+

