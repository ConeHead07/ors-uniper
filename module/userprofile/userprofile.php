<?php
/**
 * Created by PhpStorm.
 * User: f.barthold
 * Date: 04.11.2021
 * Time: 18:02
 */
namespace module\userprofile;

class UserProfile {

    private $error = '';

    public function getEditableProfile(array $user) {
        $tplUserProfileFile = __DIR__ . '/html/profile.edit.html';
        $tpl = file_get_contents($tplUserProfileFile);

        $publicUserFields = [
            'uid', 'user', 'vorname', 'nachname', 'email', 'fon', 'kid',
            'strasse', 'plz', 'ort', 'registerdate', 'created'
        ];
        $publicUserData = [];
        foreach($publicUserFields as $f) {
            $n = ($f !== 'kid') ? $f : 'personalnr';
            $publicUserData[$f] = isset($user[$n]) ? $user[$n] : '';
        }

        foreach($publicUserData as $k => $v) {
            $tpl = str_replace('{' . $k . '}', $v, $tpl);
        }
        // die('<pre>' . print_r(compact('user','publicUserFields', 'publicUserData', 'tpl'), 1));
        return $tpl;
    }

    public function changePassword($newPw, $newPwc, $oldPw) {
        global $_CONF;
        global $db;
        global $user;

        if (strlen($newPw) < $_CONF['pw_min_length']) {
            $this->error = 'Das Passwort muss mind ' . $_CONF['pw_min_length'] . ' Zeichen lang sein!';
            return false;
        }

        if ($newPw !== $newPwc) {
            $this->error = 'Das Passwort stimmt nicht mit der Passwort-Wiederholung überein!';
            return false;
        }

        $sql = 'SELECT count(1) FROM mm_user WHERE uid = :uid AND pw = :pw';
        $count = (int)$db->query_one($sql, [ 'uid' => $user['uid'], 'pw' => $oldPw]);
        if ($count < 1) {
            $this->error = 'Das alte Passwort ist nicht korrekt!';
            return false;
        }

        $update = 'UPDATE mm_user SET pw = :pwMd5 WHERE uid = :uid';
        $sth = $db->query($update, ['uid' => $user['uid'], 'pwMd5' => md5($newPw)]);

    }

    public function getUserProfileData() {
        global $user;

        return $user;
    }
}
