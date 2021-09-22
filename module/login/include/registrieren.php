<?php 

$arrFormVars = array(
	'email' => '',
	'pw' => '',
	'pwc' => '',
	'anrede' => '',
	'vorname' => '',
	'nachname' => '',
	'strasse' => '',
	'personalnr' => '',
	'plz' => '',
	'ort' => '',
	'fon' => '',
	'standort' => '',
	'gebaeude' => '',
	'user' => '',
	'anzeigename' => '',
	'agb_confirm' => '',
);

$errorFlds = '';
$error = '';
$msg = '';
$errorbox = '';
$serrorbox = '';
$msgbox = '';
$arrErrFlds = array();
$view = 'reg_eingabe'; // reg_error, reg_saved, regc_sent, reg_syserror

if (isset($_POST['register'])) {
	// echo '<pre>_POST: ';print_r($_POST);echo '</pre>';
	foreach($arrFormVars as $fld => $val) {
		if (isset($_POST['eingabe'][$fld]) && trim($_POST['eingabe'][$fld]) <> '') {
			$arrFormVars[$fld] = str_replace('&', '&amp;', strip_tags(stripslashes(trim($_POST['eingabe'][$fld]))));
		}
	}
        $_mail_parts = explode('@', $arrFormVars['email']);
	$arrFormVars['user'] = $_mail_parts[0];
	
	if ($arrFormVars['email'] && !@empty($_CONF['regc_mail_tld_check']) && !empty($_CONF['regc_mail_tld_only'])) {
		$p = strpos($arrFormVars['email'], '@');
		if (is_int($p)) {
			$mail_tld = substr($arrFormVars['email'], $p);
			if ($mail_tld == $_CONF['regc_mail_tld_only']) {
				$arrFormVars['email'] = substr($arrFormVars['email'], 0, $p);
			} else {
				$errorFlds.= "<li>Ungültige E-Mail-Domaine: $mail_tld!</li>\n";
				$arrErrFlds['email'] = 1;
			}
		}
	}
	
	if (!$arrFormVars['email']) {
		$errorFlds.= "<li>Bitte geben Sie eine E-Mail-Adresse an!</li>\n";
		$arrErrFlds['email'] = 1;
	} elseif ($_CONF['regc_mail_tld_check']) {
		if (!check_email($arrFormVars['email'].$_CONF['regc_mail_tld_only'])) {
			$errorFlds.= '<li>Ungültige E-Mail-Angabe!</li>\n';
			$arrErrFlds['email'] = 1;
		} elseif (!unique_email($conn, $arrFormVars['email'].$_CONF['regc_mail_tld_only'])) {
			$errorFlds.= "<li>Es existiert bereits ein User mit dieser E-Mail!</li>\n";
			$errorFlds.= "<li>Falls Sie Ihr Passwort vergessen haben, können Sie es sich an Ihr E-Mail-Postfach schicken lassen!</li>\n";
			$arrErrFlds['email'] = 1;
		}
	} elseif (!check_email($arrFormVars['email'])) {
		$errorFlds.= "<li>Ungültige E-Mail-Angabe!</li>\n";
		$arrErrFlds['email'] = 1;
	} elseif (!unique_email($conn, $arrFormVars['email'])) {
		$errorFlds.= "<li>Es existiert bereits ein User mit dieser E-Mail!</li>\n";
		$errorFlds.= "<li>Falls Sie Ihr Passwort vergessen haben, können Sie es sich an Ihr E-Mail-Postfach schicken lassen!</li>\n";
		$arrErrFlds['email'] = 1;
	}
	
	if (!empty($arrErrFlds['email'])) {
		// Benutzername wird aus Namensteil der Email generiert
		if (!$arrFormVars['user']) {
			$errorFlds.= "<li>Bitte geben Sie einen Benutzernamen an!</li>\n";
			$arrErrFlds['user'] = 1;
		} elseif (!unique_fldval($user_connid, 'user', $arrFormVars['user'], $uid = '')) {
			$errorFlds.= "<li>Es existiert bereits ein Benutzer " . $arrFormVars['user'] . "!</li>\n";
			$arrErrFlds['user'] = 1;
		}
	}

	if (!empty($arrErrFlds['personalnr'])) {
        if (!unique_fldval($user_connid, 'personalnr', $arrFormVars['personalnr'], $uid = '')) {
            $errorFlds.= "<li>Es existiert bereits ein Benutzer mit der Personalnr " . $arrFormVars['personalnr'] . "!</li>\n";
            $arrErrFlds['personalnr'] = 1;
        }
    }
	
	if (!$arrFormVars['pw']) {
		$errorFlds.= "<li>Bitten geben Sie ein Passwort an!</li>\n";
		$arrErrFlds['pw'] = 1;
	} elseif($arrFormVars['user'] && $arrFormVars['user'] == $arrFormVars['pw']) {
		$errorFlds.= "<li>Das Passwort darf nicht mit dem Benutzernamen übereinstimmen!</li>\n";
		$arrErrFlds['pw'] = 1;
	} elseif ($arrFormVars['pw'] <> $arrFormVars['pwc']) {
		$errorFlds.= "<li>Die Passwortwiederholung stimmt nicht mit der Passwortangabe überein!</li>\n";
		$arrErrFlds['pw'] = 1;
	} elseif (strlen($arrFormVars['pw']) < $_CONF['pw_min_length']) {
		$errorFlds.= "<li>Das Passwort muss mind. " . $_CONF['pw_min_length'] . " Zeichen lang sein!</li>\n";
		$arrErrFlds['pw'] = 1;
	}
	
	if (!$arrFormVars['anrede'])   {
		$errorFlds.= "<li>Bitte machen Sie Angaben zur Anrede!</li>\n";
		$arrErrFlds['anrede'] = 1;
	}
	if (!$arrFormVars['vorname'])  {
		$errorFlds.= "<li>Bitte gebeb Sie Ihren Vornamen an!</li>\n";
		$arrErrFlds['vorname'] = 1;
	}
	if (!$arrFormVars['nachname']) {
		$errorFlds.= "<li>Bitte geben Sie Ihren Nachnamen an!</li>\n";
		$arrErrFlds['nachname'] = 1;
	}
	if (!$arrFormVars['personalnr']) {
        $errorFlds.= "<li>Bitte geben Sie Ihre Personalnr an!</li>\n";
        $arrErrFlds['personalnr'] = 1;
    }
	if (!$arrFormVars['strasse']) {
        $errorFlds.= "<li>Bitte geben Sie Ihre Stra&szlig; an!</li>\n";
        $arrErrFlds['strasse'] = 1;
    }
	if (!$arrFormVars['plz']) {
		$errorFlds.= "<li>Bitte geben Sie Ihre Postleitzahl an!</li>\n";
		$arrErrFlds['plz'] = 1;
	}
	if (!$arrFormVars['ort']) {
		$errorFlds.= "<li>Bitte geben Sie Ihren Ort an!</li>\n";
		$arrErrFlds['ort'] = 1;
	}
	if (0 && !$arrFormVars['standort']) {
		$errorFlds.= "<li>Bitte geben Sie Ihren Standort an!</li>\n";
		$arrErrFlds['standort'] = 1;
	}
	if (0 && !$arrFormVars['gebaeude']) {
		$errorFlds.= "<li>Bitte geben Sie Ihr Geb&auml;ude an!</li>\n";
		$arrErrFlds['gebaeude'] = 1;
	}
	if (!$arrFormVars['agb_confirm']) {
		$errorFlds.= '<li>für die Anmeldung benötigen wir Ihre Zustimmung zu unseren AGB!</li>\n';
		$arrErrFlds['agb_confirm'] = 1;
	}
	
	if ($errorFlds) {
		$error.= "<strong>Einige Angaben sind leer oder unzulässig:</strong>\n";
		$error.= "<ul>\n"  .$errorFlds . "</ul>\n";
	}
	if (!empty($arrFormVars['ort']) && empty($arrFormVars['standort'])) {
        $arrFormVars['standort'] = $arrFormVars['ort'];
    }
	if (!$error) {
		srand ( (double)microtime () * 1000000 );
		$authentcode = substr(md5(rand().$arrFormVars['email'].$arrFormVars['pw']), 0, 10);
		if ($arrFormVars['email'] && !@empty($_CONF['regc_mail_tld_check']) && !empty($_CONF['regc_mail_tld_only'])) {
			$arrFormVars['email'].= $_CONF['regc_mail_tld_only'];
		}
		if (insert_reguser($conn, $_TABLE['user'], $arrFormVars, $authentcode)) {
			$view = 'reg_saved';
			$vorlage = implode('', file($_CONF['regc_mail_text']));
			if (send_regcmail($vorlage, $arrFormVars, $authentcode)) {
				$view = 'regc_sent';
			}
		}
	} else {
		$view = 'reg_error';
	}
}


switch($view) {
	case 'reg_eingabe':
	case 'reg_error':
	$contentFile = &$_CONF['HTML']['registrieren_eingabe'];
	$content = implode('', file($contentFile));
	// echo '<pre>#'.__LINE__.' arrErrFlds:'.print_r($arrErrFlds, true).'</pre>\n';
	foreach($arrErrFlds as $k => $v) {
		$content = str_replace('errclass=\'$k\'', 'class=\'lblInputError\'', $content);
	}
	break;
	
	case 'reg_saved':
	case 'regc_sent':
	$contentFile = &$_CONF['HTML']['registrieren_saved'];
	$content = implode('', file($contentFile));
	break;
	
	case 'reg_syserror':
	break;
}
// echo '<pre>arrFormVars: ';print_r($arrFormVars);echo '</pre>';

foreach($arrFormVars as $fld => $val) {
	$content = str_replace('{'.$fld.'}', $val, $content);
	// if ($view == 'reg_eingabe' || $view == 'reg_error') {
		$content = str_replace('{eingabe['.$fld.']}', fb_htmlEntities($val), $content);
		$content = str_replace('{'.$fld.'}', $val, $content);
	// }
}
if ($_CONF['regc_mail_tld_only'] && $_CONF['regc_mail_tld_check']) {
	$content = str_replace('{email_tld_only}', $_CONF['regc_mail_tld_only'], $content);
} else {
	$content = str_replace('{email_tld_only}', '', $content);
}
$content = str_replace('{pw_min_length}', $_CONF['pw_min_length'], $content);


$content = str_replace('check_anrede=\''.$arrFormVars['anrede'].'\'', 'checked=\'true\'', $content);
$content = str_replace('check_anzeigename=\''.$arrFormVars['anzeigename'].'\'', 'checked=\'true\'', $content);
$content = str_replace('check_agb_confirm=\''.$arrFormVars['agb_confirm'].'\'', 'checked=\'true\'', $content);

$content = str_replace('{supportmail}', $_CONF['email']['webmaster'], $content);
if ($error) {
	$errorboxTpl = implode('', file($_CONF['HTML']['errorbox']));
	$errorbox = str_replace('{txt}', $error, $errorboxTpl);
}
if ($sys_error) {
	$serrorboxTpl = implode('', file($_CONF['HTML']['errorbox']));
	$serrorbox = str_replace('{txt}', "<h3>Systemfehler</h3>\n" . $sys_error, $errorboxTpl);
}
if($error || $sys_error) {
	$content = str_replace('<!-- {error} -->', $errorbox.$serrorbox, $content);
}

if ($msg) {
	$msgbox = implode('', file($_CONF['HTML']['msgbox']));
	$msgbox = str_replace('{txt}', $error, $msgbox);
	$content = str_replace('<!-- {msg} -->', $msgbox, $content);
}

