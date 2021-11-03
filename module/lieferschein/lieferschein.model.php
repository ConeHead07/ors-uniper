<?php
/**
 * Created by PhpStorm.
 * User: f.barthold
 * Date: 25.10.2021
 * Time: 09:42
 */

include_once($InclBaseDir . '/lieferscheine.inc.php');

class LS_Model {

    private $error = '';
    private $validationErrors = [];
    private $db = null;
    private $mysqli = null;
    private $inputVarNames = [];
    private $AID = 0;
    private $lid = 0;
    private $auftragsdaten = [];
    private $leistungen = [];
    private $lsdata = [];

    private $ktgIdLieferung = 18;
    private $ktgIdRabatt = 25;

    public function __construct(int $aid = 0, int $lid = 0)
    {
        global $db;
        $this->db = $db;
        $this->mysqli = $db->conn;

        $this->inputVarNames = [
            'aid',
            'lid',
            'lieferdatum',
            'ankunft',
            'abfahrt',
            'etikettierung_erfolgt',
            'funktionspruefung_erfolgt',
            'leistung',
            'sig_mt_dataurl',
            'sig_mt_datetime',
            'sig_mt_created',
            'sig_mt_geodata',
            'sig_ma_unterzeichner',
            'sig_ma_dataurl',
            'sig_ma_datetime',
            'sig_ma_created',
            'sig_ma_geodata',
            'leistung',
            'daten'
        ];

        $this->AID = $aid;
        $this->lid = $lid;
        if ($this->AID) {
            $this->auftragsdaten = $this->getAuftragsdaten();
            $this->leistungen = $this->getLeistungen();
            $this->loadLieferschein();
        }
    }


    public function AidExists($aid) {
        $num = $this->db->query_one(
            'SELECT count(1) FROM mm_umzuege WHERE aid = ' . (int)$aid
        );
        return (int)$num > 0;
    }

    public function lidExists($lid) {
        $num = $this->db->query_one(
            'SELECT count(1) FROM mm_lieferscheine WHERE lid = ' . (int)$lid
        );
        return (int)$num > 0;
    }

    public function getLieferscheinIdsByLid(int $lid) {
        return $this->db->query_row(
            'SELECT lid, aid FROM mm_lieferscheine WHERE lid = ' . (int)$lid
        );
    }

    public function getAuftragsdaten() {
        return $this->db->query_row(
            'SELECT a.*, u.personalnr, u.personalnr AS kid
FROM mm_umzuege a
JOIN mm_user u ON (a.antragsteller_uid = u.uid)
WHERE aid = ' . (int)$this->AID
        );
    }

    public function getLeistungenIds() {
        if (!$this->AID) {
            return [];
        }
        $rows = $this->db->query_rows(
            'SELECT 
l.leistung_id
FROM mm_umzuege_leistungen l
LEFT JOIN  `mm_leistungskatalog` k ON l.leistung_id = k.leistung_id
WHERE aid = ' . (int)$this->AID . ' AND k.leistungskategorie_id NOT IN (' . $this->ktgIdLieferung . ', ' . $this->ktgIdRabatt . ')');

        if (empty($rows)) {
            return [];
        }

        return array_column($rows, 0);
    }

    public function getLeistungen() {
        return $this->db->query_rows(
            'SELECT 
l.*,
k.leistungskategorie_id, k.Bezeichnung, k.leistungseinheit, k.preis_pro_einheit, k.waehrung,
ktg.leistungskategorie AS Kategorie
FROM mm_umzuege_leistungen l
LEFT JOIN  `mm_leistungskatalog` k ON l.leistung_id = k.leistung_id
LEFT JOIN  `mm_leistungskategorie` ktg ON k.leistungskategorie_id = ktg.leistungskategorie_id
WHERE aid = ' . (int)$this->AID . ' AND k.leistungskategorie_id NOT IN (' . $this->ktgIdLieferung . ', ' . $this->ktgIdRabatt . ')');
    }

    public function setLieferscheinId(int $lid) {
        $row = $this->db->query_row(
            'SELECT aid, lid FROM mm_lieferscheine WHERE lid = ' . (int)$lid
        );

        if (empty($row)) {
            $this->error = 'Es existiert kein Lieferschein mit der ID ' . $lid;
            throw new \Exception($this->error);
        }

        if ($this->AID && $row['aid'] != $this->AID) {
            $this->error = 'Der Lieferschein mit der ID ' . $lid
                . ' ist einem anderen Auftrag (ID ' . $row['aid'] . ') zugeordnet';
            throw new \Exception($this->error);
        }

        $this->AID = $row['aid'];

        $this->lid = $lid;
        return $this;
    }

    public function updateLieferscheinPdf(&$pdfdata) {
        $lid = $this->lid;
        $aIdsByLid = $this->getLieferscheinIdsByLid($lid);
        $null = null;
        $sql = 'UPDATE mm_lieferscheine SET lieferschein = ? WHERE lid = ' . (int)$lid;
        $sth = $this->mysqli->prepare(
            $sql
        );
        $sth->bind_param('b', $null);
        $sth->send_long_data(0, $pdfdata);

        $success = $sth->execute();

        $affected_rows = $sth->affected_rows;
        $sthError = $sth->error;
        $mysqliError = $this->mysqli->error;

        if (!$affected_rows || !$success || $sthError || $mysqliError) {
            // die(print_r(compact('sql', 'success', 'affected_rows', 'lid', 'aIdsByLid', 'sthError', 'mysqliError', 'pdfdata'), 1));
        }


        if ($sth->error) {
            $this->error = $sth->error;
            return false;
        }
        return true;
    }

    public function getError() {
        return $this->error;

    }

    public function getValidationErrors() {
        return $this->validationErrors;
    }

    public function loadLastLieferschein() {
        $this->lsdata = $this->db->query_row(
            'SELECT * FROM mm_lieferscheine WHERE aid = :aid ORDER BY lid DESC LIMIT 1',
            [ 'aid' => $this->AID]);

        if (!empty($this->lsdata) && is_numeric($this->lsdata['lid'])) {
            $this->lid = (int)$this->lsdata['lid'];
            return true;
        }

        $this->lid = 0;
        return false;
    }

    /**
     * @param bool $autoCreate
     * @return $this
     */
    public function loadLieferschein(bool $autoCreate = true) {
        if (!$this->AID) {
            return $this;
        }
        if ($this->lid) {
            $this->lsdata = $this->db->query_row(
                'SELECT * FROM mm_lieferscheine WHERE aid = :aid AND lid = :lid LIMIT 1',
                [ 'aid' => $this->AID, 'lid' => $this->lid]);
        } else {
            $this->lsdata = $this->db->query_row(
                'SELECT * FROM mm_lieferscheine WHERE aid = :aid ORDER BY lid DESC LIMIT 1',
                [ 'aid' => $this->AID]);
            if (!empty($this->lsdata)) {
                $this->lid = $this->lsdata['lid'];
            }
        }
        if (empty($this->lsdata) && $autoCreate) {
            $this->createLieferschein();
        }
        return $this;
    }

    public function getAbgenommenenLieferscheinPDF() {
        $sql = 'SELECT lieferschein FROM mm_lieferscheine '
            . ' WHERE aid = ' . $this->AID . ' '
            . '    AND lieferschein IS NOT NULL AND LENGTH(lieferschein) > 0 '
            . '    AND sig_kd_dataurl IS NOT NULL '
            . '    AND LENGTH(sig_kd_dataurl) > 50 '
            . ' ORDER BY lid DESC '
            . ' LIMIT 1';

        return $this->db->query_one($sql);
        die(print_r(compact('sql', 'row'), 1));

        return $this->db->query_one($sql, [ 'aid' => $this->AID ]);
    }

    public function getLieferscheinPdfLength(int $lid = 0) {
        if (!$lid) {
            $lid = $this->lid;
        }
        if (!$lid) {
            return 0;
        }
        $sql = 'SELECT LENGTH(lieferschein) FROM mm_lieferscheine WHERE lid = :lid LIMIT 1';

        return $this->db->query_one($sql, ['lid' => $lid]);
    }

    public function getData() {
        return $this->lsdata;
    }

    public function createLieferschein() {
        $this->db->query('INSERT INTO mm_lieferscheine (aid) VALUES(:aid)',
            ['aid' => $this->AID]
        );
        $this->error = $this->db->error();
        if ($this->error) {
            $this->lsdata = [];
            $this->lid = 0;
            return false;
        }
        $this->lid = (int)$this->db->last_insert_id();
        $this->loadLieferschein();
        return true;
    }

    public function save(array $input) {
        if (empty($input['lid'])) {
            return $this->insert($input);
        }
        return $this->update($input);
    }

    public function insert($input) {
        global $user;
        $data = $input;
        $db = $this->db;
        if (isset($data['leistung'])) {
            $data['leistungen'] = json_encode($data['leistung']);
            unset($data['leistung']);
        }
        $data['created_uid'] = $user['uid'];
        $data['created_user'] = $user['user'];
        $cols = [];
        $vals = [];
        foreach($data as $_k => $_v) {
            if ($_k === 'lid' || $_k === 'id') {
                continue;
            }
            $cols[] = $_k;
            $vals[] = $_v;
        }

        $sql = 'INSERT INTO mm_lieferscheine (' . implode(', ', $cols) . ')'
             . 'VALUES(:' . implode(', :', $cols) . ')';
        // die($sql . "\n" . print_r(compact('cols', 'vals'), 1));

        $db->query($sql, $data);
        if ($db->error()) {
            $this->error = $db->error();
            return false;
        }
        $this->lid = $db->last_insert_id();
        $this->aid = $data['aid'];

        $this->createSigBlobsFromDataUrlCols((int)$this->lid);

        return $this->lid;
    }

    public function update($input) {
        global $user;
        $data = $input;
        $db = $this->db;
        if (empty($this->lid) && empty($input['lid'])) {
            $this->error = 'Systemfehler. Es wurde keine lid zur Aktualisierung der Lieferscheindaten übergeben!';
            return false;
        }
        if (isset($data['leistung'])) {
            $data['leistungen'] = json_encode($data['leistung']);
            unset($data['leistung']);
        }
        $data['created_uid'] = $user['uid'];
        $data['created_user'] = $user['user'];
        $sets = [];
        foreach($data as $_k => $_v) {
            $sets[] = "$_k = :$_k";
        }

        if (!empty($this->lid) && empty($data['lid'])) {
            $data['lid'] = $this->lid;
        }

        $sql = 'UPDATE mm_lieferscheine SET ' . implode("\n", $sets) . ' '
            . 'WHERE lid = :lid LIMIT 1';
        // die($sql . "\n" . print_r(compact('cols', 'vals'), 1));

        $db->query($sql, $data);
        if ($db->error()) {
            $this->error = $db->error();
            return false;
        }
        $this->lid = $data['lid'];
        $this->setLieferscheinId($this->lid);

        $this->createSigBlobsFromDataUrlCols((int)$this->id);

        return $this->lid;
    }

    public function getAuftragsStatus() {
        $sql = 'SELECT umzugsstatus FROM mm_umzuege 
    WHERE aid = :aid LIMIT 1';

        return $this->db->query_one($sql, ['aid' => $this->AID]);
    }

    public function auftragAbschliessen() {
        global $user;

        if (!$this->AID) {
            return false;
        }

        $sql = 'UPDATE mm_umzuege SET 
    umzugsstatus = :status,
    abgeschlossen_am = NOW(),
    abgeschlossen_von = :abgeschlossen_von,
    modified = NOW()
    WHERE aid = :aid LIMIT 1';

        $this->db->query($sql, [
            'status' => 'abgeschlossen',
            'abgeschlossen_von' => $user['user'],
            'aid' => $this->AID
        ]);

        if ($this->db->error()) {
            $this->error;
            return false;
        }
        return true;
    }

    public function getBinaryFromBase64DataUrl($dataurl) {
        $_val = $dataurl;
        $commaPos = strpos($_val, ',');
        if ($commaPos > 20 && $commaPos < 50) {
            $metaInfo = substr($_val, 0, $commaPos);
            list($_d, $_i, $_type, $_enc) = preg_split("#[:/;,]#", $metaInfo);

            if ($_d === 'data' && $_enc === 'base64') {
                $base64Start = $commaPos + 1;
                $base64Data = substr($_val, $base64Start);
                return base64_decode($base64Data);
            }
        }
        return '';
    }

    public function validateInput($rawInput) {
        $this->error = '';
        $this->validationErrors = [];
        $input = [];
        $auftragLeistungenIds = $this->getLeistungenIds();

        foreach($this->inputVarNames as $_name) {
            $_val = $rawInput[$_name] ?? '';
            $_colName = $_name;
            switch($_name) {
                case 'aid':
                    if (empty($_val)) {
                        $this->validationErrors[$_name] = 'Systemfehler. Es wurde keine AuftragsID übergeben!';
                    } elseif (!$this->AidExists((int)$_val)) {
                        $this->validationErrors[$_name] = 'Systemfehler. Zu der aid ' . $_val
                            . ' existiert kein Auftrag!';
                    }
                    break;

                case 'lid':
                    if (!empty($_val)) {
                        $aIds = $this->getLieferscheinIdsByLid((int)$_val);
                        if (empty($aIds)) {
                            $this->validationErrors[$_name] = 'Systemfehler: Zu der lid ' . $_val
                                . ' existieren keine Lieferscheindaten!';
                        } elseif (isset($rawInput['aid']) && (int)$rawInput['aid'] !== (int)$aIds['aid']) {
                            $this->validationErrors[$_name] = 'Systemfehler: Der Lieferschein ' . $_val
                                . " ist einem anderen Auftrag zugeordnet:\n";
                            $this->validationErrors[$_name].= 'AID-Eingabe ' . $rawInput['aid']
                                . ' != AID-Lieferschein ' . $aIds['aid'] . '!';
                        }
                    }
                    break;

                case 'lieferdatum':
                    if ($_val && strtotime($_val)) {
                        $_val = date('Y-m-d', strtotime($_val));
                    } else {
                        $this->validationErrors[$_name] = 'Bitte geben Sie ein gültiges Lieferdatum an!';
                        $_val = null;
                    }
                    break;

                case 'ankunft':
                case 'abfahrt':
                    if (empty($_val) || strlen($_val) < 2) {
                        $this->validationErrors[$_name] = 'Es fehlt die Angabe zur ' . ucfirst($_name) . 'szeit!';
                        break;
                    }
                    list($h, $m, $s) = explode(':', $_val . ':0:0');
                    $time = '';
                    if (is_numeric($h) && is_numeric($m) && is_numeric($s)) {
                        $h = (int)$h;
                        $m = (int)$m;
                        $s = (int)$s;
                        if ( 0 <= $h && $h <= 23
                            && $m >= 0 && $m <= 59
                            && $m >= 0 && $m <= 59) {
                            $time = ($h < 10 ? '0' : '') . $h;
                            $time.= ':' . ($m < 10 ? '0' : '') . $m;
                            $time.= ':' . ($s < 10 ? '0' : '') . $s;
                        }
                    }
                    if ($time) {
                        $_val = $time;
                    } else {
                        $this->validationErrors[$_name] = 'Bitte geben Sie eine gültige Uhrzeit für ' . ucfirst($_name) . 'an!';
                    }
                    break;

                case 'sig_mt_geodata':
                case 'sig_ma_geodata':
                    $_colName = str_replace('ma', 'kd', $_name);
                    if (empty($_val)) {
                        $_val = '{}';
                    }
                    break;

                case 'sig_ma_unterzeichner':
                    $_colName = str_replace('ma', 'kd', $_name);

                    if (empty($_val)) {
                        $this->validationErrors[$_name] = 'Es fehlt der Name des Unterzeichners in Blockbuchstaben!';
                    }
                    break;
                case 'sig_ma_datetime':
                    $_colName = str_replace('ma', 'kd', $_name);
                    break;

                case 'sig_mt_created':
                case 'sig_ma_created':
                    $_colName = str_replace('created', 'datetime', $_name);
                    $_colName = str_replace('ma', 'kd', $_colName);
                    break;

                case 'sig_mt_dataurl':
                case 'sig_ma_dataurl':
                    if ($_name === 'sig_ma_dataurl') {
                        $_colName = 'sig_kd_dataurl';
                    }
                    if (empty($_val) || strlen($_val) < 30) {
                        if ($_name === 'sig_ma_dataurl') {
                            $this->validationErrors[$_name] = 'Es fehlt die Unterschrift des Kunden!';
                            break;
                        }
                    }
                    $base = substr($_colName, 0, 7);
                    $commaPos = strpos($_val, ',');
                    if ($commaPos > 20 && $commaPos < 50) {
                        $metaInfo = substr($_val, 0, $commaPos);
                        list($_d, $_i, $_type, $_enc) = preg_split("#[:/;,]#", $metaInfo);

                        if ($_d === 'data' && $_i === 'image' && $_enc === 'base64') {
                            if (in_array($_type, ['png', 'jpeg', 'jpg', 'gif'])) {
                                $imgInfo = getimagesizefromstring($this->getBinaryFromBase64DataUrl($_val));
                                $tmpSize = strlen($_val);
                                if (false === $imgInfo) {
                                    $tmpImg = tempnam(sys_get_temp_dir(), 'img_sig');
                                    $tmpImgTyped = $tmpImg . '.' . $_type;
                                    rename($tmpImg, $tmpImgTyped);
                                    file_put_contents($tmpImgTyped, $this->getBinaryFromBase64DataUrl($_val));
                                    $imgInfo = getimagesize($tmpImgTyped);
                                    @unlink($tmpImgTyped);
                                }


                                if (is_array($imgInfo) && count($imgInfo)) {
                                    $input[$base . 'size'] = $tmpSize;
                                    $input[$base . 'width'] = $imgInfo[0];
                                    $input[$base . 'height'] = $imgInfo[1];
                                    $input[$base . 'mime'] = image_type_to_mime_type($imgInfo[2]);
                                }
                            }
                        } else {
                            if ($_i !== 'image') {
                                $this->validationErrors[$_name] = 'Die Datei ' . $_name
                                    . ' wurde nicht als Grafik übermittelt!';
                            } elseif ($_enc !== 'base64') {
                                $this->validationErrors[$_name] = 'Die Datei ' . $_name
                                    . ' wurde nicht mit base64 encodiert!';
                            } else {
                                $this->validationErrors[$_name] = 'Die Datei ' . $_name
                                    . ' kann nicht ausgelesen werden!';
                            }
                        }
                    } else {
                        $this->validationErrors[$_name] = 'Die Signatur ' . $_name
                            . ' wurde in unerwarteter Data-URL-Codierung übermittelt!';
                    }
                    break;

                case 'etikettierung_erfolgt':
                    if (empty($_val)) {
                        $this->validationErrors[$_name] = 'Es fehlen Angaben zur Etikettierung!';
                    }
                    if (is_array($_val) && count($_val) < count($auftragLeistungenIds)) {
                        $this->validationErrors[$_name] = 'Es wurden nicht alle Artikel als etikettiert markiert!';
                    }
                    $_val = json_encode($_val);
                    break;

                case 'funktionspruefung_erfolgt':
                    $_val = json_encode($_val);
                    break;

                case 'leistung':
                    $_colName = 'leistungen';
                    $_val = json_encode($_val);
                    break;

                case 'data':
                    if (!$_val) {
                        $_val = '{}';
                    }
                    break;
            }
            $input[$_colName] = $_val;
        }
        if ($input['ankunft'] && $input['abfahrt'] && $input['abfahrt'] <= $input['ankunft']) {
            $this->validationErrors[$_name] = 'Die Ankunftszeit muss vor der Abfahrtszeit liegen!';
        }

        if (!count($this->validationErrors)) {
            return $input;
        }
        return false;

    }

    public function addSignaturenImages(int $id, string $filenameMt, string $filenameKd) {
        $this->error = '';
        $iNumExecuted = 0;
        $mysqli = $this->mysqli;

        if (!empty($filenameMt) && file_exists($filenameMt) && filesize($filenameMt) > 0) {
            $filesizeMt = filesize($filenameMt);
            $queryMt = "UPDATE mm_lieferscheine SET sig_mt_blob = ?, sig_mt_size = ? WHERE INFULL(sig_mt_blob, '') = '' AND lid = ?"; // . $id;
            $stmt = $mysqli>prepare($queryMt);
            $null = NULL;
            $stmt->bind_param("bii", $null, $filesizeMt, $id);
            $stmt->send_long_data(0, file_get_contents($filenameMt));
            $addedSigMtBlob = $stmt->execute();

            if ($stmt->error) {
                $this->error .= "#" . __LINE__ . ' ' . $stmt->error . "\n";
            }
            if ($addedSigMtBlob) {
                ++$iNumExecuted;
            }
        }

        if (!empty($filenameKd) && file_exists($filenameKd) && filesize($filenameKd) > 0) {
            $filesizeKd = filesize($filenameKd);
            $queryKd = "UPDATE mm_lieferscheine SET sig_kd_blob =  ?, sig_kd_size = ? WHERE lid = ?";
            $stmt = $mysqli->prepare($queryKd);
            $null = NULL;
            $stmt->bind_param("bii", $null, $filesizeKd, $id);
            $stmt->send_long_data(0, file_get_contents($filenameKd));
            $addedSigKdBlob = $stmt->execute();
            if ($stmt->error) {
                $this->error .= "#" . __LINE__ . ' ' . $stmt->error . "\n";
            }
            if ($addedSigKdBlob) {
                ++$iNumExecuted;
            }
        }

        return $iNumExecuted;
    }

    public function createSigBlobsFromDataUrlCols(int $id) {
        $this->error = '';
        $iNumExecuted = 0;
        $mysqli = $this->mysqli;

        $sqlBlobMt = <<<EOT
     UPDATE mm_lieferscheine SET 
     sig_mt_blob = 
        FROM_BASE64(
            SUBSTR(sig_mt_dataurl, 
                LOCATE(',', sig_mt_dataurl) + 1
            )
        )
     WHERE 
     IFNULL(sig_mt_blob, '') = ''
     AND SUBSTR(sig_mt_dataurl, 1, 23) LIKE "data:image/%;base64,%"
     AND lid = ?;
EOT;

        $stmt = $mysqli->prepare($sqlBlobMt);
        if ($mysqli->error) {
            die($mysqli->error);
        }
        if ($stmt->error) {
            $this->error .= "#" . __LINE__ . ' ' . $stmt->error . "\n";
        }
        $stmt->bind_param('i', $id);
        if ($stmt->error) {
            $this->error .= "#" . __LINE__ . ' ' . $stmt->error . "\n";
        }
        $executedMT = $stmt->execute();
        if ($stmt->error) {
            $this->error .= "#" . __LINE__ . ' ' . $stmt->error . "\n";
        }
        if ($executedMT) {
            ++$iNumExecuted;
        }


        $sqlBlobKd = <<<EOT
     UPDATE mm_lieferscheine SET 
     sig_kd_blob = 
        FROM_BASE64(
            SUBSTR(sig_kd_dataurl, 
                LOCATE(',', sig_kd_dataurl) + 1
            )
        )
     WHERE 
     IFNULL(sig_kd_blob, '') = ''
     AND SUBSTR(sig_kd_dataurl, 1, 23) LIKE "data:image/%;base64,%"
     AND lid = ?;
EOT;
        $stmt = $mysqli->prepare($sqlBlobKd);
        if ($mysqli->error) {
            die($mysqli->error);
        }
        $stmt->bind_param('i', $id);
        $executedKd = $stmt->execute();
        if ($stmt->error) {
            $this->error .= "#" . __LINE__ . ' ' . $stmt->error . "\n";
        }
        if ($executedKd) {
            ++$iNumExecuted;
        }

        return $iNumExecuted;
    }
}
