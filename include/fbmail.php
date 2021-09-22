<?php 

function fbmail($to, $su, $bo, $hd) {
	global $aHeader;
        
	$aHdLines = explode("\n", $hd);
	$aUserHeader = $aHeader;
	$aUserHeader['X-LINES'] = empty($aUserHeader['X-LINES']) ? '#' . __LINE__ : $aUserHeader['X-LINES'].= ';#' . __LINE__;
	$iNumHdLines = count($aHdLines);

	for ($i = 0; $i < $iNumHdLines; $i++) {
		$t = explode(':', $aHdLines[$i]);
		$k = trim(array_shift($t));
		$aUserHeader[$k] = implode(':', $t);
	}


	if (is_scalar($to)) {
		$arrayTo[0] = ['email' => $to, 'anrede' => ''];
	}
    elseif (!empty($to[0]['email'])) {
        $arrayTo = $to;
    }
	elseif (!empty($to['email'])) {
		$arrayTo[0] = $to;
	}
	elseif (!empty($to['to']))  {
		$arrayTo[0] = $to;
		$arrayTo[0]['email'] = $to['to'];
	}
	elseif (empty($to[0]['email']) && !empty($to[0]['to'])) {
		$arrayTo = $to;
		foreach($arrayTo as $k => $v) {
			$arrayTo[$k]['email'] = $arrayTo[$k]['email'] ?? $arrayTo[$k]['to'] ?? '';
		}
	}
	else {
		return false;
	}

	$numRecipients = SmtpMailer::getNewInstance()
		->sendMultiMail($arrayTo, $su, null, $bo, [], $aUserHeader);

	return $numRecipients;
}

if (basename(__FILE__)==basename($_SERVER["PHP_SELF"])) {
	require_once("../header.php");
	$to = "frank.barthold@gmail.com";
	$su = "Hallo Testmail von ".$_SERVER["HTTP_HOST"];
	$bo = "Hallo ...";
	$hd = "Reply-To: frank.barthold@googlemail.com";
	
	$show_status = true;
	if (fbmail($to, $su, $bo, $hd, $show_status)) {
		echo "Mail wurde verschickt!<br>\n";
	} else {
		echo "Mail konnte nicht verschickt werden!<br>\n";
	}
	
	echo "fbmail($to, $su, $bo, $hd)<br>\n";
}
