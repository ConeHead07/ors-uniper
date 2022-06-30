<?php 

// Start Block: MIME-TYPE-Strukturvorlagen
$vorlage_mail_multipart="MIME-Version: 1.0
Content-Type: multipart/mixed; boundary=\"%boundary%\"
Subject: %subject%
%header%

This is a MIME encapsulated multipart message -
please use a MIME-compliant e-mail program to open it.

Dies ist eine mehrteilige Nachricht im MIME-Format -
bitte verwenden Sie zum Lesen ein MIME-konformes Mailprogramm.
%mail_multiparts%
--%boundary%--";

$vorlage_mail_htmltext="--%boundary%
Content-Type: text/html; charset=\"us-ascii\"

%htmltext%";

$vorlage_mail_plaintext="--%boundary%
Content-Type: text/plain; charset=\us-ascii\"
Content-Transfer-Encoding: 7bit

%plaintext%";

$vorlage_mail_attachement="--%boundary%
Content-Type: %mime/Type%; name=\"%dateiname%\"
Content-Transfer-Encoding: %file_encodetype%
Content-Disposition: attachment; filename=\"%dateiname%\"

%file_encodestr%";

// Ende Block: MIME-TYPE-Strukturvorlagen


// Typ des Arguments attachement: array
// Erste Array-Ebene indiziert das Objekt als Array mit weiteren Eigenschaften
// die folgende Struktur aufweist:
// [objektID][quelle]= enum(tmp,local,encodedstr)
// FALLS QUELLE den Wert tmp oder encodedstr hat
// [objektID][file]=
// [objektID][fname]=
// [objektID][fsize]=
// [objektID][fmime]=

function encode_mail_attachement($fobject,$vorlage) {
	global $mail_msg;
	$attachement="";
	$file_encodestr="";
	
	switch($fobject["quelle"]) {
		case "tmp":
		$file=$fobject["file"];
		$file_name=$fobject["fname"];
		$file_size=$fobject["fsize"];
		$file_encodestr=@fread(fopen($file,"r"),$file_size);
		$file_encodestr=@chunk_split(@base64_encode($file_encodestr));
		break;
		
		case "local":
		$file=$fobject["file"];
		$file_name=basename($file);
		$file_size=filesize($file);
		$file_encodestr=@fread(fopen($file,"r"),@filesize($file));
		$file_encodestr=@chunk_split(@base64_encode($file_encodestr));
		break;
		
		case "data":
		$file=$fobject["file"];
		$file_name=$fobject["fname"];
		$file_size=$fobject["fsize"];
		$file_encodestr=@chunk_split(@base64_encode($file));
		break;
		
		case "encodedstr":
		$file_name=$fobject["fname"];
		$file_size=$fobject["fsize"];
		$file_encodestr=$fobject["file"];
		break;
	}
	if ($file_encodestr) {
		$attachement=$vorlage;
		$attachement=str_replace("%file_encodestr%",$file_encodestr,$attachement);
		$attachement=str_replace("%dateiname%",$file_name,$attachement);
		$mail_msg.="Incl. Anhang: $file_name (".$file_size." Byte)<br>";
	}
	return array($file_encodestr,$attachement);
}

function format_mail_attachements($attachements,$vorlage_mail_multipart) {
	global $mail_msg;
	$send_file_list="";
	if (is_array($attachements) && 
	count($attachements)) {
		$send_file_list="";
		$file_encodetype="base64";
		$file_mimetype="application/octet-stream";
		$send_file_vorlage=$vorlage_mail_multipart;
		$send_file_vorlage=str_replace("%file_encodetype%",$file_encodetype,$send_file_vorlage);
		$send_file_vorlage=str_replace("%mime/Type%",$file_mimetype,$send_file_vorlage);
		for($i=0; $i<count($attachements); $i++) {
			$fobject=$attachements[$i];
			list($tmp,$send_file)=encode_mail_attachement($fobject,$send_file_vorlage);
			if ($send_file_list && $send_file) $send_file_list.="\n";
			$send_file_list.=$send_file;
		}
	}
	return $send_file_list;
}

function send_multipart_mail($to,$subject,$html,$text,$attachements,$header,$specs) {
    try {
        throw new Exception('function send_multipart_mail is not anymore supported!');
    } catch(Exception $e) {
        $stackTrace = $e->getTraceAsString();
        error_log(
                'function send_multipart_mail is not anymore supported!'
                . PHP_EOL . 'to: ' . print_r($to,1)
                . PHP_EOL . 'subject: ' . $su
                . PHP_EOL . 'Stack-Trace: '
                . PHP_EOL . $stackTrace
                . PHP_EOL . '----------------------');

    }

	// MIME-TYPE-Vorlagen
	global $vorlage_mail_multipart;
	global $vorlage_mail_htmltext;
	global $vorlage_mail_plaintext;
	global $vorlage_mail_attachement;
	
	global $mail_error, $mail_msg;
	if (!$header) $header="X-Platzhalter: none";
	$x=0;
	$send_html="";
	$send_text="";
	$send_file_list = "";
	
	if ($x) {
		echo "<pre>send_multipart_mail($to,$subject,";
		echo fb_htmlEntities(stripslashes($html)).",$text,$attachement_paths,$header,$specs)";
		echo "</pre><br>\n";
	}
	if ($to) {
		$boundary="========NextPart_FranceysBoundary".md5(time());
		$multipart_header=$vorlage_mail_multipart;
		$multipart_header=str_replace("%subject%",$subject,$multipart_header);
		$multipart_header=str_replace("%header%",$header,$multipart_header);
		
		if (trim(str_replace("&nbsp;","",strtolower(strip_tags($html))))) {
			$send_html=$vorlage_mail_htmltext;
			$html=wordwrap($html);
			$send_html=str_replace("%htmltext%",$html,$send_html);
		}
		
		if ($text) {
			$text=wordwrap($text);
			$send_text=$vorlage_mail_plaintext;
			$send_text=str_replace("%plaintext%",$text,$send_text);
		}
		
		if (is_array($attachements) && count($attachements)) {
			$send_file_list=format_mail_attachements($attachements,$vorlage_mail_attachement);
		}
		$multiparts=$send_html."\n".$send_text."\n".$send_file_list;
		
		$multipart_header=str_replace("%mail_multiparts%",$multiparts,$multipart_header);
		$multipart_header=str_replace("%boundary%",$boundary,$multipart_header);
		if ($x) {
			echo "\n<br>*********<br>\n";
			echo "<pre>mail($to,'','','".fb_htmlEntities(stripslashes($multipart_header))."');</pre>";
			echo "\n<br>*********<br>\n";
		}
		/*$fp = fopen("mail_source.txt", "w++");
		if ($fp) {
			fputs($fp, "An mail() �bergebene Argumente: to, subject, body, header:\n\n");
			fputs($fp, "to:\n".$to."\n\n");
			fputs($fp, "subject:\n".$subject."\n\n");
			fputs($fp, "body:\n\n\n");
			fputs($fp, "header:\n".$multipart_header."\n\n");
			fclose($fp);
		}*/
		//return @fbmail($to,$subject,"",$multipart_header);
		return @fbmail_multipart($to, $subject, "", $multipart_header);
	}
}

function send_html_mail($to,$subject,$htmltext,$attachements,$header) {
        if ($to) {
                $aHeader = SmtpMailer::mimeHeaderTxtToArray($header);
                $numRecipients = SmtpMailer::getNewInstance()->sendMultiMail($to, $subject, $htmltext, '', $attachements, $aHeader);
                return $numRecipients;
        }
        return false;
}

function send_text_mail($to,$subject,$plaintext,$attachements,$header) {
        if ($to) {
                 $aHeader = SmtpMailer::mimeHeaderTxtToArray($header);
                 $numRecipients = SmtpMailer::getNewInstance()->sendMultiMail($to, $subject, '', $plaintext, $attachements, $aHeader);
                 return $numRecipients;
        }
        return false;
}

if (basename(__FILE__) == basename($_SERVER["PHP_SELF"])) {
	require("../header.php");
	$to = "frank.barthold@gmail.com";
	$su = "Kleine Testmail mit Anahng";
	$cc = "";
	$bc = "";
	$priority = "";
	$header="";
	if ($cc) $header.="CC: ".$cc;
	$header.="\nFROM: <service-zurich@mertens.ag>";
	if ($bc) $header.="\nBCC: ".$bc;
	if ($priority) $header.="\nX-Priority: ".$priority;
	$header.="\nReply-To: service-zurich@mertens.ag";
	$header.="\nReturn-Path: service-zurich@mertens.ag";
	$header.="\nX-Entwickler: Frank Barthold";
        $aHeader = SmtpMailer::mimeHeaderTxtToArray($header);
	
	$subject=$su;
	$htmltext="<strong>Hallo</strong> <em>Frank</em>";
	$plaintext="Hallo Frank";
	
	$attachmentFile = "../images/BtnBlue.png";
	$attachement[0]["quelle"]="local";
	$attachement[0]["file"]=$attachmentFile;
	$attachement[0]["fname"]=basename($attachmentFile);
	$attachement[0]["fsize"]=filesize($attachmentFile);
	$attachement[0]["fmime"]="";;
	
	$specs="";
	$sendmail=send_multipart_mail($to,$subject,$htmltext,$plaintext,$attachement,$header,$specs);
        $numRecipients = SmtpMailer::getNewInstance()->sendMultiMail($to, $subject, $htmltext, $plaintext, $attachement, $aHeader);
	if ($numRecipients) {
		$mail_msg="Nachricht wurde versendet<br>";
	} else {
		$mail_error.="Nachricht konnte nicht versendet werden!<br>\n";
	}
	echo $mail_msg;
	echo $mail_error;
}

