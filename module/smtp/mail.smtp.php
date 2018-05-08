<?php 

$aSmtpConn = array(
	"server" => 	"10.10.1.15",
	"port" => 		"25",
	"helofrom" => 		getenv('HTTP_HOST'),
	"from" => 		"\"MoveManagement\" <move@mertens.ag>",
	"postfach_from" => 	"<move@mertens.ag>",
	"auth_user" =>	"mertens\\move", // Alter Eintrag: "wp154616-bewerbung",
	"auth_pass" =>	"move2010",
	"socket" => 	"",
	"timeIn" => 	time(),
	"antwort" => 	"",
	"logsmtp" => 	1,
	"logfile" => 	"log/log_smtp_".date("YmdHis").".txt",
	"tat"  => 		"" // Transaktionstext mit SERVER
);

$aHeader = array(
	"From" =>		"\"MoveManagement\" move@mertens.ag",
	"Reply-To" =>	"move@mertens.ag",
	"Errors-To" =>	"move@mertens.ag",
	"BCC" => 		"move@mertens.ag",
	"multipart_data" => ""
);


class fbsmtp {
	
	var $aSmtpConn = array();
	var $log = "";
	var $logfile = "";
	var $server = "";
	var $socket = "";
	var $logsmtp = "";
	var $timeIn = "";
	var $tat = "";
	var $antwort = "";
	var $port = "";
	var $helofrom = "";
	var $postfach_from = "";
	var $auth_user = "";
	var $auth_pass = "";
	var $limitOffset = ""; // Formular-Duchreiche-Wert in stichtag_test.php
	
	function fbsmtp($aSmtpConn) {
		if (isset($aSmtpConn["log"])) 			$this->log = 			$aSmtpConn["log"];
		if (isset($aSmtpConn["logfile"])) 		$this->logfile = 		$aSmtpConn["logfile"];
		if (isset($aSmtpConn["server"])) 		$this->server = 		$aSmtpConn["server"];
		if (isset($aSmtpConn["socket"])) 		$this->socket = 		$aSmtpConn["socket"];
		if (isset($aSmtpConn["logsmtp"])) 		$this->logsmtp = 		$aSmtpConn["logsmtp"];
		if (isset($aSmtpConn["timeIn"])) 		$this->timeIn = 		$aSmtpConn["timeIn"];
		if (isset($aSmtpConn["tat"])) 			$this->tat = 			$aSmtpConn["tat"];
		if (isset($aSmtpConn["antwort"])) 		$this->antwort = 		$aSmtpConn["antwort"];
		if (isset($aSmtpConn["port"])) 			$this->port = 			$aSmtpConn["port"];
		if (isset($aSmtpConn["helofrom"])) 		$this->helofrom = 		$aSmtpConn["helofrom"];
		if (isset($aSmtpConn["postfach_from"])) $this->postfach_from = 	$aSmtpConn["postfach_from"];
		if (isset($aSmtpConn["auth_user"])) 	$this->auth_user = 		$aSmtpConn["auth_user"];
		if (isset($aSmtpConn["auth_pass"])) 	$this->auth_pass = 		$aSmtpConn["auth_pass"];
		if (isset($aSmtpConn["limitOffset"])) 	$this->limitOffset = 	$aSmtpConn["limitOffset"]; // Formular-Duchreiche-Wert in stichtag_test.php
	}
	
	function listen2server()
	{
		$this->antwort = fgets($this->socket, 1500);
		$this->tat.=$this->antwort;
		if ($this->logsmtp == 1)
		{
			$this->log = "\r\n".trim("|secs:".(time()-$this->timeIn)."|SERVER: ".$this->antwort);
			if (is_writeable($this->logfile)) {
				$datei = @fopen($this->logfile, "a+");
				if ($datei) {
					fputs($datei, $this->log);
					fclose($datei);
				}
			} // else echo "#".__LINE__." Is-Not-Writeable!<br>\n";
		} // else echo "#".__LINE__." NoLog!<br>\n";
		
		$code = substr($this->antwort, 0, 3);
		return $code;
	}
	
	function talk2server($commands)
	{
		fputs($this->socket, $commands);
		$this->tat.= $commands;
		if ($this->logsmtp == 1)
		{
			$this->log = "\r\n".trim("|secs:".(time()-$this->timeIn)."|CLIENT: ".$commands);
			if (is_writeable($this->logfile)) {
				$datei = @fopen($this->logfile, "a+");
				if ($datei) {
					fputs($datei, $this->log);
					fclose($datei);
				}
			}
		} // else echo "#".__LINE__." NoLog!<br>\n";
	}
	
	function gosmtp($to, $subject, $message, $aHeader, $show_status = false)
	{
		$mail_error = false;
		
		if (is_array($to)) $arrayTo = $to;
		else $arrayTo[0] = array("email" => $to, "anrede" => "");
		$email_liste = "";
		$fehler_liste = "";
		$mail_all_count = count($arrayTo);
		$mail_sent_count = 0;
		$mail_error_count = 0;
		$strlen_count = strlen(strval($mail_all_count));
		$strlen_fill = "";
		for ($sl = 0; $sl<= $strlen_count; $sl++) $strlen_fill.="0";
		
		// echo "#".__LINE__." show_status:".($show_status?"TRUE":"FALSE")."<br>\n";
		
		if ($this->logsmtp) {
			$datei = fopen($this->logfile, "a+");
			if ($datei) {
				$logStart = "\r\n\r\nSTART:SMTP-CONNECT: ".date("Y-m-d H:i:s");
				fputs($datei, $logStart);
				fclose($datei);
			}
		}
		$this->tat.= $logStart;
		
		if ($this->socket = fsockopen($this->server, $this->port))
		{
			//set_socket_timeout($socket, 30);
			$esmtp_status = true;
			if ($this->listen2server() == "220")
			{
				$this->talk2server("EHLO ".$this->helofrom."\r\n");
				$answerID = $this->listen2server();
				$esmtp_answer = $this->antwort;
				if ($answerID == 500) {
					$this->talk2server("HELO ".$this->helofrom."\r\n");
					$answerID = $this->listen2server();
					$esmtp_status = false;
				} else {
					$answerID = $this->listen2server();
					while($answerID == 250 && strchr($this->antwort,"-")) {
						$answerID = $this->listen2server();
					}
				}
				
				if ($answerID == "250" || $answerID == "220")
				{
					if ($esmtp_status) {
						$this->talk2server("AUTH LOGIN\r\n");
						$answerID = $this->listen2server();
						$this->talk2server(base64_encode($this->auth_user)."\r\n");
						$answerID = $this->listen2server();
						$this->talk2server(base64_encode($this->auth_pass)."\r\n");
						$answerID = $this->listen2server();
					}
	
					for ($ti = 0; $ti < count($arrayTo); $ti++) {
						$mail_sent = false;
						$this->talk2server("MAIL FROM: ".$this->postfach_from."\r\n");
						$answerID = $this->listen2server();
						if ($answerID == "250") {
							$this->talk2server("RCPT TO:".$arrayTo[$ti]["email"]."\r\n");
							$answerID = $this->listen2server();
						}
						
						$DefaultReplyTo = (!empty($aHeader["Reply-To"])) ? $aHeader["Reply-To"] : $aHeader["From"];
						$ErrorsTo = (!empty($aHeader["Errors-To"])) ? $aHeader["Errors-To"] : $DefaultReplyTo;
						if ($answerID == "250") {
							$this->talk2server("DATA\r\n");
							$answerID = $this->listen2server();
							if ($answerID == "354")
							{	
								
								if (!empty($aHeader["multipart_data"])) {
									$lines = explode("\n", $aHeader["multipart_data"]);
									$lines[0].= "\nTo: ".$arrayTo[$ti]["email"];
									$lines[0].= "\nSubject: ".$subject;
									$data = implode("\n", $lines);
								} else {
									
									$data .= "To: ".$arrayTo[$ti]["email"]."\r\n";
									$data  = "Subject: $subject\r\n";
									if (!empty($aHeader["From"])) {
										$data .= "From: ".$aHeader["From"]."\r\n";
									}
									if (!empty($aHeader["Reply-To"])) {
										$data .= "Reply-To: ".$aHeader["Reply-To"]."\r\n";
									}
									if (!empty($aHeader["BCC"])) {
										$data .= "BCC: ".$aHeader["BCC"]."\r\n";
									}
									$data .= "Errors-To: $ErrorsTo\r\n";
									$data .= "Return-Path: $ErrorsTo\r\n";
									$data .= "Bounce-To: $ErrorsTo\r\n";
									$data .= "X-QUEUE-Date: ".date("Y-m-d H:i:s")."\r\n";
									$data .= "X-QUEUE-ID:".($limitOffset+$ti+1)."\r\n";
									if ($arrayTo[$ti]["anrede"]) {
										$data .= "\r\n".$arrayTo[$ti]["anrede"]."\r\n";
									}
									$data .= "\r\n".$message."\r\n";
								}
								$data .= ".\r\n";
								//file_put_contents(dirname(__FILE__)."/log/".date("Ymd").".log", $data, FILE_APPEND);
								$this->talk2server($data);
								$answerID = $this->listen2server();
								if ($answerID == "250") {
									$mail_sent = true;
								}
							}
						}
						
						if ($mail_sent) {
							$mail_sent_count++;
							if ($mail_liste) $mail_liste.= "\r\n";
							$mail_liste.=substr($strlen_fill.strval($ti+1),-($strlen_count)).": ".fb_htmlEntities($arrayTo[$ti]["email"]);
						} else {
							$mail_error_count++;
							if ($fehler_liste) $fehler_liste.= "\r\n";
							$fehler_liste.=substr($strlen_fill.strval($ti+1),-($strlen_count)).": ".fb_htmlEntities($arrayTo[$ti]["email"]);
							$mail_error = true;
						}
					}
					
					$this->talk2server("QUIT\r\n");
					$answerID = $this->listen2server();
					if ($this->logsmtp) {
						$datei = fopen($this->logfile, "a+");
						if ($datei) {
							$logStart = "\r\n\r\nENDE:SMTP-CONNECT: ".date("Y-m-d H:i:s");
							fputs($datei, $logStart);
							fclose($datei);
						}
					}
				}
			}
			
		    if ($mail_error == false) {
		    	if ($show_status) {
					echo "<span style=\"color:green;font-weight:bold;\">Mailversand an ".$mail_all_count." Empfänger war erfolgreich!</span><br>\n";
					echo "Versendete Emails: <br>\n".nl2br($mail_liste);
					echo "<pre><a href='".$this->logfile."' target='_blank'>SMTP-Protokoll:</a></pre>\n<br>\n";
				}
				return true;
		    } else {
		    	if ($show_status) {
					echo "<span style=\"color:#f00;font-weight:bold;\">Beim Mailversand sind Fehler aufgetreten!</span><br>\n";
					echo "Anzahl &uuml;bergebener Mailempf&auml;ngr: ".$mail_all_count."<br>\n";
					echo "Versendete Emails: <br>\n".nl2br($mail_liste)."<br>\n";
					echo "Fehler - Emails: <br>\n".nl2br($fehler_liste)."<br>\n";
					echo "<pre><a href='$logfile' target='_blank'>SMTP-Protokoll:</a></pre>\n<br>\n";
				}
				return false;
			}
		} else {
			echo "<font color='red'>Wasn't able to connect to ".$this->server.":".$this->port."!</b></font><br>";
			return false;
		}
	}
}


$IsStandaloneTest = true;
// TESTVERSAND: Wenn IsStandaloneTest = true, WIRD DAS SCRIPT STAND-ALONE AUGERUFEN
if ($IsStandaloneTest && basename(__FILE__) == basename($_SERVER["PHP_SELF"])) {
	// START: TESTVERSAND
	$show_status = true;
	//$aHeader["From"] = "\"Move-Management\"<move@mertens.ag>";
	$arrayTo[0] = array("email" => "<frank.barthold@googlemail.com>", "anrede" => "");
	//$aHeader["From"] = "\"Move-Management\"<move@mertens.ag>";
	$arrayTo[1] = array("email" => "<o.kowalski@mertens.ag>", "anrede" => "");
	$arrayTo[2] = array("email" => "<frank.barthold@web.de>", "anrede" => "");
	$arrayTo[3] = array("email" => "<oliverkowalski@gmail.com>", "anrede" => "");
	$subject = "Move-Management startet Portal mertens.ag";
	$text = "Sehr geehrte Interessentin, sehr geehrter Interessent,
 
jetzt geht’s los! Wir ziehen um!

Machen Sie mit! Wir freuen uns auf Ihren Besuch unter www.mertens.ag
 
Ihr Move-Management-Team
";
	$MoveSmtp = new fbsmtp($aSmtpConn);
	$ckckSendmail = $MoveSmtp->gosmtp($arrayTo, $subject, $text, $aHeader, $show_status);
	
	if($ckckSendmail)
		echo "E-Mails wurden verschickt";
	else 
		echo "Beim Versenden der E-Mails ist ein Fehler aufgetreten!";
	// ENDE: TESTVERSAND
}


?>