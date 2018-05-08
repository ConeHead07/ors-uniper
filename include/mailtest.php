<?php 

$mail_msg="";
$mail_error="";
$systemmmeldung="";

if (isset($hu)) unset($hu);
if (isset($hp)) unset($hp);

if (isset($login)) {
	if (isset($pass)) $hp=md5($pass);
	if (isset($muser)) $hu=$muser;
}

if (isset($HTTP_POST_VARS["hu"])) $hu=$HTTP_POST_VARS["hu"];
if (isset($HTTP_POST_VARS["hp"])) $hp=$HTTP_POST_VARS["hp"];

if (!isset($hu) || !isset($hp) || 
$hu!="mailtest" || 
$hp!=md5("lotion5") ) {
	if (!isset($muser)) $muser="";
	echo "<html><body>\n<br>\n<form action=mailtest.php method=post>";
	echo "<table align=center border=1 cellspacing=0 cellpadding=0 bgcolor=#f3f3f3>";
	echo "<tr><td>";
	echo "<table align=center border=0 cellspacing=0 cellpadding=5 bgcolor=#f3f3f3>";
	echo "<tr><td colspan=2><b><font face='Arial' size='+1'>Login für Web-Mail-Client</font></b></td></tr>";
	echo "<tr><td><b><font face='Arial'>Benutzer </font></b></td>";
	echo "<td width=200><input value='".$muser."' style='width:180;' type=text size=10 name=muser></td></tr>\n";
	echo "<tr><td><b><font face='Arial'>Passwort </font></b></td>";
	echo "<td><input style='width:180;' type=password size=10 name=pass></td></tr>\n";
	echo "<tr><td>&nbsp;</td>";
	echo "<td><input type=submit size=10 name=login value=Amelden></td></tr>\n";
	echo "</table>";
	echo "</td></tr>\n";
	echo "</table>";
	echo "</form></body></html>";
	exit;
}

include("lib_mail.php");


$prot=$SERVER_PROTOCOL;
$prot=substr($prot,0,strpos($prot,"/"))."://"; // HTTP/1.0 
$path=$REQUEST_URI;
$path=substr($path,0,strrpos($path,"/"));
$HostIP=(isset($HTTP_HOST) && $HTTP_HOST)?$HTTP_HOST:$HTTP_SERVER_VARS["LOCAL_ADDR"];
$webPath=$prot.$HostIP.$path."/";
$baseURL=$prot.$HostIP;

$selfPath=$webPath.basename($PATH_INFO);
//if (md5($password)==$checkPassword))

$defaultSubject="Test: Multipartmail";
$attachement="";

$validFields=array(
		"to"=>array("Empfänger (To)","francey@gmx.de",1),
		"cc"=>array("Kopie-Empfänger (CC)","",0),
		"bc"=>array("Unsichtbarer Kopie-Empfänger (BC)","frank.barthold@azmedia.de",0),
		"from"=>array("Von (Nickname)","",0),
		"replyto"=>array("Antwort An","frank.barthold@azmedia.de",0),
		"subject"=>array("Betreff","",1),
		"htmltext"=>array("Nachricht in HTML-Formatierung","",0),
		"text"=>array("Nachricht in alternativem Plaintext","",0),
		"priority"=>array("Priorität","0",0));


$checkInput=0;
$missingInput="";
$priority=array("","");
$priorityText=array("Normal","High");

reset($validFields);
if (isset($HTTP_POST_VARS['send'])) {
	while(list($key,$val) = each ($validFields))
	{
		$input[$key]=(isset($HTTP_POST_VARS[$key]))?$HTTP_POST_VARS[$key]:"";
	}
	$checkInput=1;
} else {
	while(list($key,$val) = each ($validFields))
	{
		$input[$key]=$val[1];
	}
}
reset($validFields);

if ($checkInput)
while(list($key,$val) = each ($validFields))
{
	if ($val[2]==1 && $input[$key]=="")
		$missingInput.=",".$val[0];
}
$priority[$input['priority']]=" selected ";

if ($checkInput && $missingInput=="") 
{
  $aHeaders = [
        "CC: ".$input['cc'],
        "BCC" => $input['bc'],
        "X-Priority" => trim($input['priority']),
        "Reply-To" => trim($input['replyto']),
        "Return-Path" => trim($input['replyto']),
        "X-Entwickler" => "Francey Bartolino",
	];

    if (isset($input["from"]) && trim($input["from"])) {
        $aHeaders["FROM"] = $input['from'] . "<wwwrun>";
    }
	
	$to=$input['to'];
	$subject=$input['subject'];
	$htmltext=(isset($input["htmltext"]))?stripslashes($input["htmltext"]):"";
	$plaintext=(isset($input["text"]))?$input["text"]:"";
	if ($anhang<>"none") {
		$attachement[0]["quelle"]="tmp";
		$attachement[0]["file"]=$anhang;
		$attachement[0]["fname"]=$anhang_name;
		$attachement[0]["fsize"]=$anhang_size;
		$attachement[0]["fmime"]="";;
	} else {
		$attachement=array();
	}
	$sendmail= SmtpMailer::getNewInstance()->sendMultiMail(
	    $to, $subject, $htmltext, $plaintext, $attachement, $aHeaders
  	);
	if ($sendmail) {
		$mail_msg="Nachricht wurde versendet<br>".$mail_msg;
	} else {
		$mail_error.="Nachricht konnte nicht versendet werden!<br>\n";
	}
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Untitled</title>
	<style type="text/css">
	
	td { 
		padding-left: 0px; 
		padding-right:0px; 
		background-color: #888888;
		font-family:Arial; 
		font-size: 11px; 
	}
	
	.tdhead { 
		font-weight: bold; 
		background-color: #888888; 
		color:#ffffff; 
		padding-right:5px; 
		padding-left: 5px; 
	}
	
	table {  
		background-color: #666666; 
	}
	
	a { 
		text-decoration: none; 
		color: blue; 
		font-size:11px; 
		font-family:Arial; 
	}
	
	a:hover { 
		color:red; 
	}
	
	input { 
		height:20px; 
		font-size:11px; 
		border: thin groove;
	}
	
	input.inputTxt { 
		width: 450px; 
	}
	
	input.inputSend { 
		width: 60px; 
	}
	
	
	textarea { 
		width:510px; 
		height: 300;
		font-family:Arial;
		font-size:12px;
		color:navy;
	}
	
	b.msg {
		color: blue;
	}
	
	b.err {
		color: darkred;
	}
	</style>
<script language="javascript" src="../js/webedit.js"></script>
<script language="javascript">
	// Aus dem Stringwert von objAsStringArr lässt sich später 
	// mit eval() das Objekt referenzieren
	// Aufbau imgNamesArr: "'Bildname1,Befehl1,objStrID','...' für Eingabeobjekt",
	objAsStringArr=new Array('edit1'/*jsIFrameNames*/);
	objSourceArr=new Array('edit1,0,htmltext'/*objSourceItems*/);
	imgNamesArr=new Array("boldedit1,bold,edit1",
"italicedit1,italic,edit1",
"ulineedit1,underline,edit1",
"cutedit1,cut,edit1",
"copyedit1,copy,edit1",
"pasteedit1,paste,edit1",
"linkedit1,createlink,edit1",
"unlinkedit1,unlink,edit1",
"unformatedit1,removeformat,edit1",
"oledit1,insertorderedlist,edit1",
"uledit1,insertunorderedlist,edit1"
/*jsGroupButtons*/);
	
	function init_editbar()
	{
		if (typeof document.execCommand != "undefined") {
			init_images();
			init_eingabeframes();
			document.forms[0].onsubmit=transmit_iframe;
		} 
	}
</script>
</head>

<body onload="init_editbar();">
<form action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="hp" value="<?php echo $hp; ?>">
<input type="hidden" name="hu" value="<?php echo $hu; ?>">
<table height="90%" cellspacing=1 border=0 cellpadding=0 align="center">
<?php if ($missingInput!="")
{
	$mail_error.="Bitte f&uuml;llen Sie noch folgende Felder aus:<br>\n";
	$mail_error.=substr($missingInput,1)."</b><br>\n";
}

if ($mail_msg || $mail_error)
{
	$statuscolor=($mail_error)?"red":"green";
	echo "<tr bgcolor='#c0c0c0' style='background-color:#c0c0c0;'>";
	echo "<td width='60' style='background-color:$statuscolor;'>&nbsp;</td>";
	echo "<td style='padding-left:5px; background-color:#c0c0c0;'>";
	if ($mail_msg) { 
		if ($systemmmeldung) $systemmmeldung.="<br>\n";
		$systemmmeldung.="<b class='msg'>$mail_msg</b>";
	}
	if ($mail_error) {
		if ($systemmmeldung) $systemmmeldung.="<br>\n";
		$systemmmeldung.="<b class='err'>$mail_error</b>";
	}
	echo $systemmmeldung;
	echo "</td>";
	echo "</tr>";
}
?>
	<tr>
		<td width="60"><input type="submit" class="inputSend" name="send" value="senden"></td>
		<td><input type="submit" class="inputSend" name="new" value="Neu"></td>
	</tr>
	<tr>
		<td class="tdhead" align="right">To:&nbsp;</td>
		<td><input class="inputTxt" type="text" name="to" value="<?php echo $input['to']; ?>"></td>
	</tr>
	<tr>
		<td class="tdhead" align="right">CC:</td>
		<td><input class="inputTxt" type="text" name="cc" value="<?php echo $input['cc']; ?>"></td>
	</tr>
	<tr>
		<td class="tdhead" align="right">BC:</td>
		<td><input class="inputTxt" type="text" name="bc" value="<?php echo $input['bc']; ?>"></td>
	</tr>
	<tr>
		<td class="tdhead" align="right">Reply-To:</td>
		<td><input class="inputTxt" type="text" name="replyto" value="<?php echo $input['replyto']; ?>"></td>
	</tr>
	<tr>
		<td class="tdhead" align="right">Von (Nickname):</td>
		<td><input class="inputTxt" type="text" name="from" value="<?php echo $input['from']; ?>"></td>
	</tr>
	<tr>
		<td class="tdhead" align="right">Betreff:</td>
		<td><input class="inputTxt" type="text" name="subject" value="<?php echo $input['subject']; ?>"></td>
	</tr>
	<tr>
		<td colspan="2">
	<textarea type="text" name="htmltext" cols="30" rows="10" style="height:400; width:510px; border: thin groove;"><?php echo fb_htmlEntities(stripslashes($input['htmltext'])); ?></textarea><table background="../images/bg_menues.gif" width="99.9%" style="width:510;">
	<tr>
		<td><font face="Arial" color='white'><b>&nbsp;Nachricht in Html-Formatierung:</b></font></td>
		<td align="right"><img name="cutedit1" src="../images/btn_cut_0.gif" width="23" height="22" border="0"><!-- 
 --><img name="copyedit1" src="../images/btn_copy_0.gif" width="23" height="22" border="0"><!-- 
 --><img name="pasteedit1" src="../images/btn_paste_0.gif" width="23" height="22" border="0"><!-- 
 --><img name="boldedit1" src="../images/btn_bold_0.gif" width="23" height="22" border="0"><!-- 
 --><img name="italicedit1" src="../images/btn_italic_0.gif" width="23" height="22" border="0"><!-- 
 --><img name="ulineedit1" src="../images/btn_underline_0.gif" width="23" height="22" border="0"><!-- 
 --><img name="oledit1" src="../images/btn_insertorderedlist_0.gif" width="23" height="22" border="0"><!-- 
 --><img name="uledit1" src="../images/btn_insertunorderedlist_0.gif" alt="" width="23" height="22" border="0"><!-- 
 --><img name="linkedit1" src="../images/btn_createlink_0.gif" width="23" height="22" border="0"><!-- 
 --><img name="unlinkedit1" src="../images/btn_unlink_0.gif" width="23" height="22" border="0"><!-- 
 --><img name="unformatedit1" src="../images/btn_removeformat_0.gif" width="23" height="22" border="0"></td>
	</tr>
</table>
<IFRAME id="edit1" width="99.9%" height="350px"  style="width:510;height:300;"></IFRAME>
<textarea id="zwischenspeicheredit1" style="display:none;"></textarea>

	</td>
</tr>
	<tr>
		<td colspan="2" class="tdhead">
		<input type="checkbox" onclick="(this.checked)?document.forms[0].text.style.display='':document.forms[0].text.style.display='none';">Nachricht mit alternativem Plaintext:</td>
	</tr>
	<tr>
		<td colspan="2"><textarea cols="30" rows="15" name="text" style="display:none"><?php echo fb_htmlEntities(stripslashes($input['text'])); ?></textarea></td>
	</tr>
	<tr>
		<td class="tdhead" align="right">Anhang:</td>
		<td><input class="inputTxt" type="file" name="anhang"></td>
	</tr>
	<tr>
		<td class="tdhead">Priorität:</td>
		<td class="tdhead"><select name="priority">
				<option value="0" <?php echo $priority[0]; ?>>Normal</option>
				<option value="1" <?php echo $priority[1]; ?>>Hoch</option>
			</select></td>
	</tr>
</table>
<input type="hidden" name="password" value="<?php echo md5($password); ?>">

</form>
<a href="mailtest.php">Abmelden</a>
</body>
</html>
