<?php 

function fbmail($to, $su, $bo, $hd) {
	global $aSmtpConn;
	global $aHeader;
        
	$aHdLines = explode("\n", $hd);
	$aUserHeader = $aHeader;
	for ($i = 0; $i < count($aHdLines); $i++) {
            $t = explode(":", $aHdLines[$i]);
            $k = trim(array_shift($t));
            $aUserHeader[$k] = implode(":", $t);
	}
	
	// START: TESTVERSAND
	$show_status = false;
	if (is_scalar($to)) $arrayTo[0] = array("email" => "<".$to.">", "anrede" => "");
	else if (isset($to["email"]) || isset($to[0]["email"])) $arrayTo = $to;
	else if (isset($to["to"]))  { $arrayTo = $to; $arrayTo["email"]=$to["to"]; }
	else if (isset($to[0]["to"])) { $arrayTo = $to; foreach($arrayTo as $k => $v) $arrayTo[$k]["email"] = $arrayTo[$k]["to"]; }
	else return false;
//	echo '<pre>#' . __LINE__ . ' ' . print_r($arrayTo,1) . '</pre>' . PHP_EOL;
        
	if ($_SERVER["HTTP_HOST"]=="localhost" || $_SERVER["HTTP_HOST"]=="127.0.0.1") {
            $li = 1;
            $logfile = __DIR__ . '/../log/fbmail_'.date('YmdHis').".txt";
            while(file_exists($logfile)) {
                $logfile = __DIR__ . '/../log/fbmail_'.date('YmdHis')."(".(++$li).").txt";
            }
            file_put_contents($logfile, print_r(array('arrayTo'=>$arrayTo,'su'=>$su,'bo'=>$bo, 'aUserHeader'=>$aUserHeader),1));
            return true;
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
?>
