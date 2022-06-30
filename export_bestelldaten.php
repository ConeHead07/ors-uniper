<?php
require_once 'header.php';
require_once __DIR__ . '/sites/umzugsantrag_sendmail.php';
require_once __DIR__ . '/include/umzugsantrag.inc.php';


function rowToSheetHeader($row) {
    $sheet1header = [];
    if (empty($row) || count($row) === 0) {
        return [];
    }

    foreach($row as $_colTitle) {

        if ( preg_match('#(summe|preis)#i', $_colTitle)) {
            $_colFormat = 'euro';
        } elseif ( preg_match('#(menge)#i', $_colTitle) ) {
            $_colFormat = '0';
        } else {
            $_colFormat = 'string';
        }
        $sheet1header[$_colTitle] = $_colFormat;
    }
    return $sheet1header;
}
// 'temp','angeboten','beantragt','zurueckgegeben','geprueft','erneutpruefen','genehmigt','abgelehnt','bestaetigt','storniert','abgeschlossen'
$AlleLeistungen = <<<EOT
SELECT 
 		a.umzugsstatus AS Auftragsstatus,
		usr.personalnr AS KID,
 		if (IFNULL(a.ref_aid,0)=0, a.aid, a.ref_aid) BestellID,
 		a.aid
 		,a.service
 		,a.antragsdatum
 		,a.strasse
 		, a.plz
 		, a.ort
 		, a.land
 		,CONCAT(SUBSTR(YEARWEEK(a.umzugstermin), 1, 4), "/", LPAD(WEEKOFYEAR(a.umzugstermin), 2, "0") ) AS LieferKW
 		,a.umzugstermin AS Liefertermin
 		,al.leistung_id
 		,al.menge_mertens AS Menge
 		,ktg.kategorie_abk AS "KtgAbk"
 		,ktg.leistungskategorie AS Kategorie
 		,lk.leistung_abk AS "LstAbk"
 		,lk.Bezeichnung
 		,lk.Farbe
 		,lk.Groesse
 		,lk.preis_pro_einheit AS Preis
 		,a.bestaetigt
 		,a.bestaetigt_am
 		,a.bestaetigt_von
 		,a.tour_kennung AS Tour
 		,a.tour_zugewiesen_am
 		,a.tour_zugewiesen_von
 		,a.abgeschlossen
 		,a.abgeschlossen_am
 		,a.abgeschlossen_von
 		,a.berechnet_am
 		,a.vorgangsnummer AS RechNr
 		,a.zurueckgegeben_am
 		,a.zurueckgegeben_von
		,a.bemerkungen		
 		
	FROM mm_umzuege a
	LEFT JOIN mm_user AS usr ON (a.antragsteller_uid = usr.uid)
	LEFT JOIN mm_umzuege_leistungen AS al ON (a.aid = al.aid)
	LEFT JOIN mm_leistungskatalog AS lk ON (al.leistung_id = lk.leistung_id)
	LEFT JOIN mm_leistungskategorie AS ktg ON (lk.leistungskategorie_id = ktg.leistungskategorie_id) 
	ORDER BY if (IFNULL(a.ref_aid,0)=0, a.aid, a.ref_aid)
EOT;

$aSQL = [];


$aSQL[] = [
    'name' => 'AnstehendeLeistungenPerKW',
    'sql' => <<<EOT
SELECT AL.LieferKW AS KW, 
	MIN(AL.Liefertermin) AS "First",
	MAX(AL.Liefertermin) AS "Last",
	AL.leistung_id,
	AL.Kategorie, 
	AL.Bezeichnung, 
	AL.Farbe, 
	AL.Groesse, 
	AL.Preis,
	SUM(IFNULL(AL.Menge, 0)) AS Menge, 
	SUM(IFNULL(AL.Preis, 0)) AS Summe, 
	GROUP_CONCAT(DISTINCT(AL.Liefertermin)) AS Liefertermine
	FROM ( $AlleLeistungen ) AS AL
	WHERE 
		AL.Auftragsstatus = "bestaetigt"
        AND AL.Liefertermin IS NOT NULL AND AL.Liefertermin != ""
AND YEARWEEK(AL.Liefertermin) >  YEARWEEK(NOW())
	GROUP BY AL.LieferKW, AL.leistung_id, AL.Kategorie, AL.Bezeichnung, AL.Farbe, AL.Groesse, AL.Preis
	ORDER BY AL.LieferKW, AL.Kategorie, AL.Bezeichnung, AL.Farbe, AL.Groesse
EOT
];

$aSQL[] = [
    'name' => 'AnstehendeLeistungenPerTag',
    'sql' => <<<EOT
SELECT AL.LieferKW AS KW, 
	AL.Liefertermin,
	AL.leistung_id,
	AL.Kategorie, 
	AL.Bezeichnung, 
	AL.Farbe, 
	AL.Groesse, 
	AL.Preis,
	SUM(IFNULL(AL.Menge, 0)) AS Menge, 
	SUM(IFNULL(AL.Preis, 0)) AS Summe
	FROM ( $AlleLeistungen ) AS AL
	WHERE 
		AL.Auftragsstatus = "bestaetigt"
        AND AL.Liefertermin IS NOT NULL AND AL.Liefertermin != ""
AND AL.Liefertermin >  NOW()
	GROUP BY AL.LieferKW, AL.Liefertermin, AL.leistung_id, AL.Kategorie, AL.Bezeichnung, AL.Farbe, AL.Groesse, AL.Preis
	ORDER BY AL.Liefertermin, AL.Kategorie, AL.Bezeichnung, AL.Farbe, AL.Groesse
EOT
];

$aSQL[] = [
    'name' => 'Mehrachbestellungen',
    'sql' => <<<EOT
SELECT 
 		a.antragsteller_uid, 
		 usr.user, 
		 usr.personalnr, 
		 ktg.leistungskategorie, 
		 COUNT(1) AS Menge,
		 GROUP_CONCAT(
             CONCAT(lk.Farbe, lk.Groesse) 
			 ORDER BY a.aid 
			 SEPARATOR ", "
		) AS "Modelle",
		 GROUP_CONCAT(
             CONCAT_WS(" ", "AID:", a.aid, "vom", DATE_FORMAT(a.antragsdatum, "%d.%m."), a.umzugsstatus, IF(IFNULL(a.umzugstermin,"")="","", CONCAT("Termin:", DATE_FORMAT(a.umzugstermin, "%d.%m."))), IF (IFNULL(a.vorgangsnummer,"")="", "", "berechnet")) 
			 ORDER BY a.aid 
			 SEPARATOR " // "
		) AS "AID Antragsdatum Status Berechnet",
		SUM(lk.preis_pro_einheit) AS Summe,
		MAX(a.antragsdatum) AS LetzteBestellung
 	FROM mm_umzuege AS a
 	JOIN mm_user AS usr ON (a.antragsteller_uid = usr.uid)
 	JOIN mm_umzuege_leistungen AS al ON (a.aid = al.aid)
 	JOIN mm_leistungskatalog AS lk ON (al.leistung_id = lk.leistung_id)
 	JOIN mm_leistungskategorie AS ktg ON (lk.leistungskategorie_id = ktg.leistungskategorie_id)
 	WHERE
	  a.umzugsstatus != "temp"
      AND a.service = "Ja"
      AND a.abgeschlossen != "Storniert"
      AND ktg.kategorie_abk NOT IN ("M", "P", "R", "Z")
 	GROUP BY a.antragsteller_uid, usr.user, usr.personalnr, ktg.leistungskategorie, ktg.leistungskategorie_id
 	HAVING COUNT(1) > 1
EOT
];

$aSQL[] = [
    'name' => 'AlleBestellLeistungen',
    'sql' =>$AlleLeistungen
];

$aSQL[] = [
    'name' => 'AlleKumulierteLeistungenPerKW',
    'sql' => <<<EOT
SELECT AL.LieferKW AS KW, 
	MIN(AL.Liefertermin) AS "First",
	MAX(AL.Liefertermin) AS "Last",
	AL.leistung_id,
	AL.Kategorie, 
	AL.Bezeichnung, 
	AL.Farbe, 
	AL.Groesse, 
	AL.Preis,
	SUM(IFNULL(AL.Menge, 0)) AS Menge, 
	SUM(IFNULL(AL.Preis, 0)) AS Summe, 
	GROUP_CONCAT(DISTINCT(AL.Liefertermin)) AS Liefertermine
	FROM ( $AlleLeistungen ) AS AL
	WHERE 
		AL.Auftragsstatus NOT IN ("temp", "zurueckgegeben", "erneutpruefen", "abgelehnt", "storniert")
AND AL.Liefertermin IS NOT NULL AND AL.Liefertermin != ""
	GROUP BY AL.LieferKW, AL.leistung_id, AL.Kategorie, AL.Bezeichnung, AL.Farbe, AL.Groesse, AL.Preis
	ORDER BY AL.LieferKW, AL.leistung_id, AL.Kategorie, AL.Bezeichnung, AL.Farbe, AL.Groesse
EOT
];

$writer = new XLSXWriter();
$writer->setAuthor('Frank Barthold, merTens AG');

// $aSQL = array_slice($aSQL, 3, 1);

foreach($aSQL as $_query) {
    $name = $_query['name'];
    $sql = $_query['sql'];
    $sth = $db->query($sql);

    $aFields = $sth->fetch_fields();
    $aColumnNames = array_column($aFields, 'name');

    $sheet01Name = $name;
    $sheet01Header = rowToSheetHeader($aColumnNames);

    if (0) {
        echo '<pre>' . $sql . '</pre>' . "\n";
        echo 'Num-Rows: ' . $sth->num_rows . "<br>";
        echo '<table><thead><tr><th>' . implode('</th><th>', array_keys($sheet01Header)) . '</th></tr></thead>' . "\n";
        echo '<tbody>' . "\n";
        while($row = $sth->fetch_row()) {
            echo '<tr><td>' . implode('</td><td>', $row) . '</td></tr>' . "\n";
        }
        echo '</tbody></table>';
        exit;
    }

    // die('<pre>' . print_r(compact('sheet01Header'), 1));
    $writer->writeSheetHeader($sheet01Name , $sheet01Header, [ 'freeze_rows' => 1]);
    $_styles = [];
    while($row = $sth->fetch_row()) {
        $writer->writeSheetRow($sheet01Name, $row, $_styles);
    }
}

header('Content-Type: application/xls');
header('Content-Disposition: attachment; filename="ZurichBestelldaten' . date('YmdHi') . '.xlsx"');
$writer->writeToStdOut();

exit;
