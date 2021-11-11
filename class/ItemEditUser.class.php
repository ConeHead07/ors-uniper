<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class ItemEditUser extends ItemEdit {

    private $anonymisierungsStat = [];
    public function __construct($conf, $connid, $user, $id = false)
    {
        parent::__construct($conf, $connid, $user, $id);
    }

    function loadDbdata() {
        $loaded = parent::loadDbdata();
        if (!$loaded) {
            return $loaded;
        }

//        print_r(['this->arrConf' => $this->arrConf]);
//        exit;
        $SQL = 'SELECT g.*, z.bezeichnung zustaendigkeit FROM mm_gebaeude_user gu ' . "\n"
              .' LEFT JOIN mm_gebaeude_zustaendigkeitsarten z ' ."\n"
              .' ON(gu.zustaendigkeits_id = z.zustaendigkeits_id)' ."\n"
              .' LEFT JOIN mm_stamm_gebaeude g ' ."\n"
              .' ON(gu.gebaeude_id = g.id)' ."\n"
              .' WHERE gu.' . $this->arrConf["PrimaryKey"] . ' = ' . (int)$this->id . "\n"
              .' ORDER BY z.zustaendigkeits_id, g.stadtname, g.adresse';
        $r = $this->db_query($SQL);
        
        if ($r) {
            $n = MyDB::num_rows($r);
            for($i = 0; $i < $n; ++$i) {
                $row = MyDB::fetch_assoc($r);
                $this->arrDbdata[$row['zustaendigkeit']]['stadtname'][] = $row;
            }
            $this->db_free_result($r);
            return true;
        } else {
            throw new Exception('#'.__LINE__ . ' ' . __FILE__ . ' ' . MyDB::error() . "\n" . $SQL);
        }
        return $loaded;
    }
    
    function saveInput() {
        parent::saveInput();
    }
    
    function saveZustaendigkeiten($data) {
        // data(gebaeude_id=>int, zustaendigkeits_id=>int)
        
        $SQL = 'DELETE FROM mm_gebaeude_user WHERE uid = ' . (int)$this->id;
        
        for($i = 0; $i < count($data); ++$i) {
            
        }
    }

    function killItem() {
        /** @var dbconn $db */
        $db = $this->dbConnId;
        if (!$this->id) {
            $this->Error = 'Es wurde keine User-ID für Loschanfrage übergeben!';
            return false;
        }

        $editUser = $this->arrDbdata;
        if (!$this->arrDbdata) {
            $this->loadDbdata();
        }
        $sql = 'SELECT count(1) FROM mm_umzuege a WHERE antragsteller_uid = :uid AND umzugsstatus != "temp"';
        $count = $db->query_one($sql, [ 'uid' => $this->id ]);

        if ($count) {
            $this->Error = 'Der Benutzer ist in ' . $count . ' Aufträgen als Antragsteller eingetragen. ' . "\n";
            $this->Error.= 'Benutzer können aktuell nicht gelöscht werden, wenn Bestellungen damit verbunden sind!';
            return false;
        }

        return parent::killItem();
    }

    function anonymisiereBenutzerdaten() {
        /** @var dbconn $db */
        $db = $this->dbConnId;

        $this->anonymisierungsStat['AffectedAuftraege'] = 0;
        $this->anonymisierungsStat['AffectedLieferscheine'] = 0;

        if (!$this->arrDbdata) {
            $this->loadDbdata();
        }
        $uid = (int)$this->id;

        $origUserData = $this->arrDbdata;
        $anonData = [
            'user' => 'anonym.' . $this->id,
            'email' => 'anonym',
            'authentcode' => '',
            'freigegeben' => 'Nein',
            'vorname' => 'Anonym',
            'nachname' => 'Anonym',
            'fon' => null,
            'mobil' => null,
            'strasse' => '',
            'plz' => '',
            'ort' => '',
            'firma' => '',
        ];

        $sqlAids = 'SELECT aid FROM mm_umzuege WHERE antragsteller_uid = :uid';
        $aidRows = $db->query_rows($sqlAids, [ 'uid' => $uid ]);

        if (count($aidRows) > 0) {
            /*
             * Anonymisiere Auftraege
             */
            $aids = array_map(function($row) { return (int)$row['aid']; }, $aidRows);
            $aidsCsv = implode(',', $aids);

            $sqlAuftraege = <<<EOT
            UPDATE mm_umzuege SET
            anrede = null, 
            name = :nachname,
            vorname = :vorname,
            email = :email,
            fon = :fon,
            strasse = :strasse,
            plz = :plz,
            ort = :ort,
            land = ''
            WHERE antragsteller_uid = :uid
EOT;
            $sthA = $db->query($sqlAuftraege, [ 'uid' => $uid]);
            $this->anonymisierungsStat['AffectedAuftraege'] = $sthA->num_rows;



            /*
             * Anonymisiere Lieferscheine
             */
            $sqlLieferscheine = <<<EOT
            UPDATE mm_lieferscheine SET
            lieferschein = null,
            sig_kd_unterzeichner = null,
            sig_kd_dataurl = null,
            sig_kd_geodata = null,
            sig_kd_blob = null
            WHERE aid IN (:aids)
EOT;
            $sthLS = $db->query($sqlLieferscheine, [ 'aids' => $aidsCsv]);
            $this->anonymisierungsStat['AffectedLieferscheine'] = $sthLS->num_rows;
        }

        /*
         * Anonymisiere ActivityLog
         */
        $sqlActivityLog = <<<EOT
            UPDATE mm_activity_log SET
            user = :anonUser
            WHERE uid IN :uid
EOT;
        $sthAL = $db->query($sqlActivityLog, [ 'anonUser' => $anonData['user'], 'uid' => $uid]);
        $this->anonymisierungsStat['AffectedLogs'] = $sthAL->num_rows;

        /*
         * Anonymisiere User
         */
        $aSets = [];
        foreach($anonData as $fld => $val) {
            $aSets[] = "$fld = " . $db::quote($val);
        }
        $sqlUser = 'UPDATE mm_user SET ' . implode(",\n", $aSets) . ' WHERE uid = :uid';
        $sthU = $db->query($sqlUser, [ 'uid' => $uid]);
        $this->anonymisierungsStat['AffectedUser'] = $sthU->num_rows;

        return true;
    }

    public function getAnonymisierungsStat() {
        return $this->anonymisierungsStat;
    }
}
