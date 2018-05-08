<?php 
if ( basename(__FILE__) == basename($_SERVER["PHP_SELF"])) {
	require __DIR__ . '/../include/conf.php';
}
try {
	throw new Exception("Load StackTrace");
} catch (Exception $e) {
	$stackTrace = $e->getTraceAsString();
}
$error = 'Class fbsmtp is deprecated and doesn t support tls. Use SmtpMailer.class.php!';
error_log($error . PHP_EOL . 'Stack-Trace: ' . PHP_EOL . $stackTrace . PHP_EOL . ' SERVER: ' . PHP_EOL . print_r($_SERVER,1) . PHP_EOL . 'REQUEST: ' . PHP_EOL . print_r($_REQUEST,1) . PHP_EOL . '----------------');
die($error);
$aSmtpConn = array(
	"server"    => $MConf['smtp_server'], //"10.10.1.70",
	"port"      => $MConf['smtp_port'], //"25",
	"helofrom"  => getenv('HTTP_HOST'),
	"from"      => '"'.$MConf['smtp_from_name'] . '" <' . $MConf['smtp_from_addr'].'>',
        "postfach_from" => '<' . $MConf['smtp_from_addr'].'>',
	"auth_user" => $MConf['smtp_auth_user'],
	"auth_pass" => $MConf['smtp_auth_pass'],
	"socket"    => "",
        "connection_timeout" => 5,
	"timeIn"    => time(),
	"antwort"   => "",
	"logsmtp"   => 1,
	"logfile"   => dirname(__FILE__) . "/../log/log_smtp_".date("YmdHis").".txt",
	"tat"       => "" // Transaktionstext mit SERVER
);

$aHeader = array(
	"From"      => $MConf['smtp_from_name'] . ' <' . $MConf['smtp_from_addr'].'>',
	"Reply-To"  => $MConf['smtp_from_addr'],
	"Errors-To" => $MConf['smtp_from_addr'],
	"BCC"       => $MConf['smtp_from_addr'], 
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
	var $command = '';
	var $antwort = "";
	var $port = "";
	var $helofrom = "";
	var $postfach_from = "";
	var $auth_user = "";
	var $auth_pass = "";
	var $limitOffset = ""; // Formular-Duchreiche-Wert in stichtag_test.php
	var $connection_timeout = 10;
	var $constHeader = array();
        var $hostDummieOptions = array(
            //|secs:0|CLIENT: EHLO 62.225.25.6
            "250-E2013.LOCALHOST.LOG_DUMMIE Hello [10.10.1.62]",
            "250-SIZE 36700160",
            "250-PIPELINING",
            "250-DSN",
            "250-ENHANCEDSTATUSCODES",
            "250-STARTTLS",
            "250-X-ANONYMOUSTLS",
            "250-AUTH NTLM LOGIN",
            "250-X-EXPS GSSAPI NTLM",
            "250-8BITMIME",
            "250-BINARYMIME",
            "250-CHUNKING",
            "250 XRDST",
        );
        var $hostDummieLoginPhase = 0;
        var $hostDummieDataPhase = 0;
	
	function __construct($aSmtpConn) {
		if (isset($aSmtpConn["log"]))             $this->log =           $aSmtpConn["log"];
		if (isset($aSmtpConn["logfile"]))         $this->logfile =       $aSmtpConn["logfile"];
		if (isset($aSmtpConn["server"]))          $this->server =        $aSmtpConn["server"];
		if (isset($aSmtpConn["socket"]))          $this->socket =        $aSmtpConn["socket"];
		if (isset($aSmtpConn["logsmtp"]))         $this->logsmtp =       $aSmtpConn["logsmtp"];
		if (isset($aSmtpConn["timeIn"]))          $this->timeIn =        $aSmtpConn["timeIn"];
		if (isset($aSmtpConn["tat"]))             $this->tat =           $aSmtpConn["tat"];
		if (isset($aSmtpConn["antwort"]))         $this->antwort =       $aSmtpConn["antwort"];
		if (isset($aSmtpConn["port"]))            $this->port =          $aSmtpConn["port"];
		if (isset($aSmtpConn["helofrom"]))        $this->helofrom =      $aSmtpConn["helofrom"];
		if (isset($aSmtpConn["postfach_from"]))   $this->postfach_from = $aSmtpConn["postfach_from"];
		if (isset($aSmtpConn["auth_user"]))       $this->auth_user =     $aSmtpConn["auth_user"];
		if (isset($aSmtpConn["auth_pass"]))       $this->auth_pass =     $aSmtpConn["auth_pass"];
		if (isset($aSmtpConn["limitOffset"]))     $this->limitOffset =   $aSmtpConn["limitOffset"]; // Formular-Duchreiche-Wert in stichtag_test.php
		if (isset($aSmtpConn["connection_timeout"])) 	$this->connection_timeout = 	$aSmtpConn["connection_timeout"]; // Formular-Duchreiche-Wert in stichtag_test.php
		$this->constHeader = array(
			"From" => 'ors <bayerors@mertens.ag>',
		);
	}
	
	function listen2server()
	{        
                if ($this->socket !== 'LOG_DUMMIE') {
                    $this->antwort = fgets($this->socket, 1500);
                } else {
                    if (strpos($this->command, "AUTH LOGIN") === 0) {
                        $this->hostDummieLoginPhase = 1;
                    }
                    if (!$this->command) {
                        $this->antwort = "220 LOCALHOST.LOG_DUMMIE ESMTP MAIL Service";
                    }   
                    elseif (strpos($this->command, "EHLO") === 0 || strpos($this->command, "HELO") === 0) {
                        $this->antwort = array_shift($this->hostDummieOptions);
                    }
                    elseif (strpos($this->command, "MAIL FROM") === 0) {
                        $this->antwort = "250 2.1.0 Sender OK";
                    }
                    elseif (strpos($this->command, "RCPT TO") === 0) {
                        $this->antwort = "250 2.1.5 Recipient OK";
                    }
                    elseif (strpos($this->command, "DATA") === 0) {
                        $this->hostDummieDataPhase = 1;
                        $this->antwort = "354 Start mail input; end with <CRLF>.<CRLF>";
                    } elseif ($this->hostDummieDataPhase) {
                        if (substr($this->command, -5) === "\r\n.\r\n") {
                            $this->antwort = "250 2.6.0 <fd106a5c-750f-450c-b156-5c8868b03007@E2013.LOCALHOST.LOG_DUMMIE> [InternalId=13344463388832, Hostname=e2013.LOCALHOST.LOG_DUMMIE] Queued mail for delivery";
                        }
                    }
                    elseif (strpos($this->command, "QUIT") === 0) {
                        $this->antwort = "221 LOCALHOST.LOG_DUMMIE Service closing transmission channel";
                    }                 
                    elseif ($this->hostDummieLoginPhase && $this->hostDummieLoginPhase < 4) {
                        $phase = $this->hostDummieLoginPhase;                        
                        $this->hostDummieLoginPhase++;
                        switch($phase) {
                            case 1: $this->antwort = "334 VXNlcm5hbWU6"; break;
                            case 2: $this->antwort = "334 UGFzc3dvcmQ6"; break;
                            case 3: 
                                $this->antwort = "235 2.7.0 Authentication successful"; 
                                $this->hostDummieLoginPhase = 0;
                                break;
                        }
                    } else {
                        $this->antwort = "500 Command";
                    }
                    
                    $this->antwort.= '( last-command: ' . rtrim(substr($this->command, 0, 20)) . ')';
                }
                
		$this->tat.=$this->antwort;
		if ($this->logsmtp == 1)
		{
			$this->log = "\r\n".trim("|secs:".(time()-$this->timeIn)."|SERVER: ".$this->antwort);
			if (is_writeable($this->logfile)) {
				$datei = @fopen($this->logfile, "a+");
				if ($datei) {
					fputs($datei, $this->log);
					fclose($datei);
				} else echo "#".__LINE__." CanNot Open / Create LogFile !<br>\n";
			} else echo "#".__LINE__." Is-Not-Writeable!<br>\n";
		} else echo "#".__LINE__." NoLog!<br>\n";
		
		$code = substr($this->antwort, 0, 3);
		return $code;
	}
        
        
	
	function talk2server($commands)
	{
		$this->command = $commands;     
                if ($this->socket !== 'LOG_DUMMIE') {
                    fputs($this->socket, $commands);
                }		
		$this->tat.= $commands;
		if ($this->logsmtp == 1)
		{
			$this->log = "\r\n".trim("|secs:".(time()-$this->timeIn)."|CLIENT: ".$commands);
			if (is_writeable($this->logfile)) {
				$datei = @fopen($this->logfile, "a+");
				if ($datei) {
					fputs($datei, $this->log);
					fclose($datei);
				} else echo "#".__LINE__." CanNot Open / Create LogFile !<br>\n";
			} else echo "#".__LINE__." LogFile Is-Not-Writeable!<br>\n";
		} // else echo "#".__LINE__." NoLog!<br>\n";
	}
	
        /**
         * 
         * @param type $to
         * @param type $subject
         * @param type $message
         * @param type $aHeader
         * @param type $show_status
         * @deprecated since version Vodafone2, use instead send
         */
	function gosmtp($to, $subject, $message, $aHeader, $show_status = false) {
            $this->send($to, $subject, $message, $aHeader, $show_status);
        }
	
	function send($to, $subject, $message, $aHeader, $show_status = false)
	{
		$mail_error = false;
		$mail_error_text = '';
		$aHeader = array_merge($aHeader, $this->constHeader);
		//echo '#'.__LINE__ . ' ' . __METHOD__ . '(' . print_r(func_get_args(),1) . ')<hr>' . PHP_EOL;
		//return true;
		
		if (is_array($to)) $arrayTo = $to;
		elseif(is_scalar($to)) $arrayTo[0] = array("email" => $to, "anrede" => "");
		else return false;
		$data = ''; $limitOffset = 0;
		$email_liste = ""; $mail_liste = '';
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
			} else echo "#".__LINE__." CanNot Open / Create LogFile !<br>\n";

		}
		if (!empty($logStart)) {
                   $this->tat.= $logStart;
                }
                if ($_SERVER["HTTP_HOST"]==="localhost" || $_SERVER["HTTP_HOST"]==="127.0.0.1") {
                    $this->socket = 'LOG_DUMMIE'; 
                }
                elseif (strpos( __FILE__, 'staging') !== false) {
                    $this->socket = 'LOG_DUMMIE'; 
                }
                elseif (defined('APP_ENVIRONMENT') && APP_ENVIRONMENT === 'DEVELOPMENT') {
                   $this->socket = 'LOG_DUMMIE'; 
                } else {               
                    $this->socket = fsockopen($this->server, $this->port, $errno, $errstr, $this->connection_timeout);
                }
		
		if ($this->socket)
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
                                    while($answerID == 250 && strpos($this->antwort,"-")===3) {
                                        $answerID = $this->listen2server();
                                    }
				}
				
				if ($answerID == "250" || $answerID == "220")
				{
					if ($esmtp_status) {
						$this->talk2server("AUTH LOGIN\r\n");
						$answerID = $this->listen2server();
						if ( $answerID >= '500') $mail_error_text.= 'AUTH LOGIN => ' . $this->antwort . PHP_EOL;
						
						$this->talk2server(base64_encode($this->auth_user)."\r\n");
						$answerID = $this->listen2server();
						if ($answerID >= '500') $mail_error_text.= 'AUTH SEND USER => ' . $this->antwort . PHP_EOL;
						
						$this->talk2server(base64_encode($this->auth_pass)."\r\n");
						$answerID = $this->listen2server();
						if ($answerID >= '500') $mail_error_text.= 'AUTH SEND PASS => ' . $this->antwort . PHP_EOL;
					}
					if ($mail_error_text) $mail_error = true;
	
					if (!$mail_error) for ($ti = 0; $ti < count($arrayTo); $ti++) {
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
									$data .= "\r\n".$message;
								}
								$data .= "\r\n.\r\n";
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
						} else echo "#".__LINE__." CanNot Open / Create LogFile !<br>\n";
					}
				}
			}
			
		    if ($mail_error === false ) {
		    	if ($show_status) {
					echo "<span style=\"color:green;font-weight:bold;\">Mailversand an ".$mail_all_count." Empfänger war erfolgreich!</span><br>\n";
					echo "Versendete Emails: <br>\n".nl2br($mail_liste);
					echo "<pre>".basename($this->logfile)."</pre>\n<br>\n";
				}
				return true;
		    } else {
		    	if ($show_status) {
                            echo "<span style=\"color:#f00;font-weight:bold;\">Beim Mailversand sind Fehler aufgetreten!</span><br>\n";
                            echo "Anzahl &uuml;bergebener Mailempf&auml;ngr: ".$mail_all_count."<br>\n";
                            echo "Versendete Emails " . $mail_sent_count . ": <br>\n".nl2br($mail_liste)."<br>\n";
                            echo "Fehler - Emails " . $mail_error_count . ": <br>\n".nl2br($fehler_liste)."<br>\n";
                            echo "<pre>".basename($this->logfile)."</pre>\n<br>\n";
                        }
                        echo '<pre>Mail-Error: ' . $mail_error_text . '</pre>' . PHP_EOL;
                        return false;
                    }
		} else {
			echo "<font color='red'>Wasn't able to connect to ".$this->server.":".$this->port."!</b></font><br>";
                        echo $errno . ' ' . $errstr . '<br>' . PHP_EOL;
			return false;
		}
	}
}


$IsStandaloneTest = true;
// TESTVERSAND: Wenn IsStandaloneTest = true, WIRD DAS SCRIPT STAND-ALONE AUGERUFEN
if ($IsStandaloneTest && basename(__FILE__) == basename($_SERVER["PHP_SELF"])) {
	// START: TESTVERSAND
	$show_status       = true;
	$arrayTo[0]        = array("email" => "<frank.barthold@googlemail.com>", "anrede" => "Herr Barthold");
	$arrayTo[1]        = array("email" => "<o.kowalski@mertens.ag>", "anrede" => "Herr Kowalski");
	$subject           = "Move-Management startet Portal mertens.ag";
	$text              = "Sehr geehrte Interessentin, sehr geehrter Interessent,
 
jetzt geht?s los! Wir ziehen um!

Machen Sie mit! Wir freuen uns auf Ihren Besuch unter www.mertens.ag
 
Ihr Move-Management-Team
";
	$MoveSmtp = new fbsmtp($aSmtpConn);
	$ckckSendmail = $MoveSmtp->gosmtp($arrayTo, $subject, $text, $aHeader, $show_status);
	
	if($ckckSendmail)
		echo "E-Mails wurden verschickt";
	else 
		echo "Beim Versenden der E-Mails sind Fehler aufgetreten!";
        echo "<pre><a href=\"../log/".basename($MoveSmtp->logfile).'">'.$MoveSmtp->logfile."</a></pre>\n<br>\n";
	// ENDE: TESTVERSAND
}
