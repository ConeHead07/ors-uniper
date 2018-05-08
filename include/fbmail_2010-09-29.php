<?php 

function fbmail($to, $su, $bo, $hd) {
	global $aSmtpConn;
	global $aHeader;
	if ($_SERVER["HTTP_HOST"]=="localhost" || $_SERVER["HTTP_HOST"]=="127.0.0.1") return true;
	
	if (empty($aSmtpConn) || !class_exists("fbsmtp")) {
		return mail($to, $su, $bo, $hd);
	}
	
	$aHdLines = explode("\n", $hd);
	$aUserHeader = $aHeader;
	for ($i = 0; $i < count($aHdLines); $i++) {
		$t = explode(":", $aHdLines[$i]);
		$k = trim(array_shift($t));
		$aUserHeader[$k] = implode(":", $t);
	}
	
	// START: TESTVERSAND
	$show_status = false;
	//$aHeader["From"] = "\"Move-Management\"<move@mertens.ag>";
	$arrayTo[0] = array("email" => "<".$to.">", "anrede" => "");
	$MoveSmtp = new fbsmtp($aSmtpConn);
	$ckckSendmail = $MoveSmtp->gosmtp($arrayTo, $su, $bo, $aUserHeader, $show_status);
	return $ckckSendmail;
	
	if($ckckSendmail) {
		echo "E-Mails wurden verschickt";
	} else {
		echo "Beim Versenden der E-Mails ist ein Fehler aufgetreten!";
	}
	// ENDE: TESTVERSAND
}

function fbmail_multipart($to, $su, $bo, $hd) {
	global $aSmtpConn;
	global $aHeader;
	if ($_SERVER["HTTP_HOST"]=="localhost" || $_SERVER["HTTP_HOST"]=="127.0.0.1") return true;
	
	if (empty($aSmtpConn) || !class_exists("fbsmtp")) {
		return mail($to, $su, $bo, $hd);
	}
	
	$aHdLines = explode("\n", $hd);
	$aUserHeader = $aHeader;
	for ($i = 0; $i < count($aHdLines); $i++) {
		if (!trim($aHdLines[$i])) break;
		
		$t = explode(":", $aHdLines[$i]);
		$k = trim(array_shift($t));
		$aUserHeader[$k] = implode(":", $t);
	}
	$aUserHeader["multipart_data"] = $hd;
	//echo "<pre>#".__LINE__." ".basename(__FILE__)." ".print_r($aUserHeader,1)."</pre><br>\n";
	
	// START: TESTVERSAND
	$show_status = false;
	//$aHeader["From"] = "\"Move-Management\"<move@mertens.ag>";
	$arrayTo[0] = array("email" => "<".$to.">", "anrede" => "");
	$MoveSmtp = new fbsmtp($aSmtpConn);
	$ckckSendmail = $MoveSmtp->gosmtp($arrayTo, $su, $bo, $aUserHeader, $show_status);
	return $ckckSendmail;
	
	if($ckckSendmail) {
		echo "E-Mails wurden verschickt";
	} else {
		echo "Beim Versenden der E-Mails ist ein Fehler aufgetreten!";
	}
	// ENDE: TESTVERSAND
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
?>