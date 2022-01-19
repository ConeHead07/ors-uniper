<?php
/**
 * Created by PhpStorm.
 * User: f.barthold
 * Date: 20.12.2021
 * Time: 14:53
 */

namespace module\Auftragsbearbeitung;

class BatchErinnerungsmails
{
    private $aids = [];
    private $lastError = '';
    private $tplMail = '';
    private $tplDir = '';
    private $tplFile = '';
    private $tplSubject = '';
    private $tplBody = '';
    private $aHeader = [];
    /** @var \dbconn|null  */
    private $db;

    public function __construct()
    {
        global $MConf;
        global $aHeader;
        $this->aHeader = $aHeader;
        $this->tplDir = $MConf['AppRoot'] . $MConf['Texte_Dir'];
        $this->tplFile = $this->tplDir . 'statusmail_user_temp_remember.txt';
        if (file_exists($this->tplFile)) {
            $this->tplMail = file_get_contents($this->tplFile);

            $lines = explode("\n", $this->tplMail);

            $this->tplSubject = trim((strpos($lines[0], 'Betreff=') === 0)
                ? substr($lines[0],8)
                : $lines[0]
            );

            $this->tplBody = trim(implode("\n",array_slice($lines, 1)));
        } else {
            $this->lastError = 'Template-File not found: ' . $this->tplFile;
            throw new \Exception($this->lastError);
        }

        $this->db = \dbconn::getInstance();
    }

    public function setAuftragsIds(array $aids) {
        $this->aids = array_map('intval', $aids);
        return $this;
    }

    public function addAuftragsId(int $aid) {
        $this->aids[] = $aid;
        return $this;
    }

    public function getPreview(int $aid) {
        $row = $this->getAuftragByAID($aid);
        $rplVars = $this->getTplVars($row);

        if (0) {
            echo json_encode([
                'aid' => $aid,
                'row' => $row,
                'rplVars' => $rplVars,
                'tplDir' => $this->tplDir,
                'tplFile' => $this->tplFile,
                'tplMail' => $this->tplMail,
                'tplSubject' => $this->tplSubject,
                'subject' => $this->renderTplVars($this->tplSubject, $rplVars),
                'body' => $this->renderTplVars($this->tplBody, $rplVars),
            ], JSON_PRETTY_PRINT);
        }

        $to = $this->getTo($row);
        $subject = $this->renderTplVars($this->tplSubject, $rplVars);
        $body = $this->renderTplVars($this->tplBody, $rplVars);

        return 'To: ' . $to . "<br>\n"
            . 'Betreff: ' . $subject . "<br>\n<br>\n"
            . str_replace("\n", "<br>\n", $body);
    }

    public function getTplFile() {
        return $this->tplFile;
    }

    public function getTplMail() {
        return $this->tplMail;
    }

    public function getTplSubject() {
        return $this->tplSubject;
    }

    public function getTplBody() {
        return $this->tplBody;
    }

    public function getAuftragByAID(int $aid) {
        $sql = $this->getAuftragsQuery([ $aid ]);
        return $this->db->query_row($sql);
    }

    public function getAuftraege() {
        $aids = array_unique($this->aids);
        if (!$aids) {
            $this->lastError = 'Es wurden keine Aufträge übergeben!';
            return false;
        }
        $sql = $this->getAuftragsQuery($aids);
        return $this->db->query_rows($sql);
    }

    public function getAuftragsQuery(array $aids) {
        $NL = "\n";
        return 'SELECT a.*, u.email as user_email, u.vorname as user_vorname, u.nachname as user_nachname, u.personalnr AS kid ' . $NL
        . ' FROM mm_umzuege a ' . $NL
        . ' JOIN mm_user u ON (a.antragsteller_uid = u.uid) ' . $NL
        . ' WHERE aid IN (' . implode(', ', $aids) . ')  AND u.freigegeben = "Ja"';
    }

    public function getTo($row) {
        return $row['user_email'];
    }

    public function getTplVars($row) {
        global $MConf;
        $AID = $row['aid'];
        $rplVars =
            array_merge(
                $row,
                array(
                    'AID' => $AID,
                    'StatusLink' => $MConf['WebRoot'] . '?s=kantrag&id=' . $AID,
                    'HomepageTitle' => $MConf['AppTitle'],
                )
            );

        $timeAntragsdatum = strtotime($row['antragsdatum']);
        $rplVars['antragsdatum']  = date('d.m.Y', $timeAntragsdatum) . ' um ' . date('H:i', $timeAntragsdatum) . ' Uhr';
        $rplVars['UserTo']  = $row['user_email'];
        $rplVars['Vorname'] = $row['user_vorname'];
        $rplVars['Name']    = $row['user_nachname'];

        $rplVars['Lieferadresse'] = $rplVars['strasse']
            . ', ' . $this->getLaenderKuerzelByLand($rplVars['land'])
            . '-' . $rplVars['plz']
            . ' ' . $rplVars['ort'];

        return $rplVars;
    }

    public function updateErinnertAm(int $aid) {
        $sql = 'UPDATE mm_umzuege SET temp_erinnerungsmail_am = NOW() WHERE aid = ' . $aid . ' LIMIT 1';
        $this->db->query($sql);
    }

    public function run() {
        $rows = $this->getAuftraege();
        $numSent = 0;
        $aUserHeader = $this->aHeader;

        foreach($rows as $row) {
            $aid = (int)$row['aid'];
            $rplVars = $this->getTplVars($row);
            $to = $this->getTo($row);
            $su = $this->tplSubject;
            $body= $this->tplBody;
            $aAttachements = [];
            $mailer = \SmtpMailer::getNewInstance();
            $mailer->setTplVars($rplVars);
            $sent = $mailer->sendMultiMail([ ['email' => $to, 'anrede' => ''] ], $su, null, $body, $aAttachements, $aUserHeader);
            if ($sent) {
                $numSent++;
                $this->updateErinnertAm($aid);
            }
        }
        return $numSent;
    }

    function renderTplVars($sVal, $tplVars) {
        $sReturn = $sVal;
        foreach($tplVars as $k => $v) {
            $sReturn = str_replace('{' . $k . '}', $v, $sReturn);
        }

        return $sReturn;
    }

    function getLaenderKuerzelByLand($land) {
        switch($land) {
            case 'Deutschland':
                return 'D';
            case 'Belgien':
                return 'BE';
            case 'England':
                return 'EN';
            case 'Niederlande':
                return 'NL';
                break;
            case 'Ungarn':
                return 'HU';

            default:
                return $land;
        }
    }
}

