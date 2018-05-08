<?php 

require_once("../header.php");
set_time_limit(600);
error_reporting(E_ALL);
//echo ini_get("error_reporting");
if (function_exists("activity_log")) register_shutdown_function("activity_log");

require_once($MConf["AppRoot"]."sites".DS."umzugsantrag_save_attachement.php");

function getOrderLink($chckfld, $ofld, $odir) {
	if ($ofld != $ofld || $odir == "DESC") return "ofld=$chckfld&odir=ASC";
	return "ofld=$chckfld&odir=DESC";
}

$aOrderFields = array(
	"dok_datei" => array("field"=>"dok_datei", "defOrder"=>"ASC"),
	"dok_groesse" => array("field"=>"dok_groesse", "defOrder"=>"ASC"),
	"created" => array("field"=>"created", "defOrder"=>"DESC"),
);

$isAdmin = (strpos($user["gruppe"],"admin")!==false);
$aid = getRequest("aid","");
$token = getRequest("token","");
$drop = getRequest("drop","");
$titel = getRequest("titel","");
$ofld = getRequest("ofld","");
$odir = getRequest("odir","");
$response = getRequest("response","");

if (!$token && $aid) {
    switch($response) {
        case 'json':
            $custom_error['jquery-upload-file-error']="Es wurde kein Token übergeben";
            echo json_encode($custom_error);
            //exit;
            break;
        
        default:
    die("<strong>Es wurde kein token &uuml;bergeben!</strong> <br>"
        ."<br>"
        ."<strong>Wie erhalte ich einen Token</strong>"
        ."Einen Token erhalten Sie automatisch beim Öffnen eines Leistungsformulars.<br>"
        ."<br>"
        ."Anschlie&szlig;end k&ouml;nnen Sie Dateien hinzuf&uuml;gen!<br>\n");
    }
}


if (isset($aOrderFields[$ofld])) {
    if (!in_array(strtoupper($odir), array("ASC","DESC"))) $odir = "";
    $OrderBy = "ORDER BY ".$aOrderFields[$ofld]["field"]." ".(empty($odir) ? $aOrderFields[$ofld]["defOrder"] : $odir);
} else {
    $ofld = "created";
    $odir = "DESC";
    $OrderBy = "ORDER BY $ofld $odir";
}

$dropError = "";
$uploadError = "";
$uploadMsg = "";
$webdir = $MConf["WebRoot"]."/attachements/";
$Self = basename($_SERVER["PHP_SELF"])."?token=$token";

if (isset($_FILES) && isset($_FILES["uploadfile"])) {
	$dokid = save_upload($aid, $token, $isAdmin);
	if ($dokid) $uploadMsg = "Hochgeladene Datei wurde gespeichert!";
        
        if ($response === 'json') {
            echo json_encode(
                array(
                    'name' => $_FILES["uploadfile"]['name'],
                    'size' => format_file_size($_FILES["uploadfile"]['size']),
                    'date' => date("H-m-d")
                )
            );
            exit;
        }
}
if (!empty($drop)) {
	if ($isAdmin) {
		drop_attachement($token, $drop);
		if ($dropError) $uploadError.= ($uploadError?"<br>\n":"").$dropError;
                else $uploadMsg = "Datei wurde gelöscht!";
	} else {
		$uploadError.= "Nur Administratoren dürfen Dateianhänge l&ouml;schen!<br>\n";
	}
}


$sql = "SELECT * FROM `".$_TABLE["umzugsanlagen"]."` \n";
$sql.= " WHERE `token` = \"".$db->escape($token)."\" \n";
$sql.= $OrderBy;
$rows = $db->query_rows($sql);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Upload</title>
<style>
.upMsg { font-size:11px; color:#228b22; font-family:Arial,Helvetica,sans-serif; }
.upErr { font-size:11px; color:#f00; font-family:Arial,Helvetica,sans-serif; }
table.tblList.tblAdminAttachments {
    width:100%;
    max-width:650px;
}
</style><link rel="STYLESHEET" type="text/css" href="<?php echo $MConf["WebRoot"]; ?>css/tablelisting.css">
</head>
<body>
<?php 

$dropLink = "<a href=\"".$Self."&drop={dokid}&token={token}&ofld=$ofld&odir=$odir\" style=\"text-decoration:none;border:0;\">";
$dropLink.= "<img src=\"".$MConf["WebRoot"]."images/status_storniert.png\" style=\"text-decoration:none;border:0;\" border=0 align=\"absmiddle\" width=\"16\" height=\"16\"> L&ouml;schen";
$dropLink.= "</a>";

$fileList = "";

if (is_array($rows) && count($rows)) for($i = 0; $i < count($rows); $i++) {
	$row = $rows[$i];
	$fileList.= "<tr>";
	$fileList.= "<td align=right>".($i+1)."</td>\n";
	$fileList.= "<td><a href=\"".$webdir.$row["dok_datei"]."\" title=\"".$row['titel']."\">".($row["titel"]?:$row["dok_datei"])."</a></td>\n";
	$fileList.= "<td>".format_file_size($row["dok_groesse"])."</td>\n";
	$fileList.= "<td>".$row["created"]."</td>\n";
	if ($isAdmin) $fileList.= "<td>".strtr($dropLink, array("{dokid}"=>$row["dokid"], "{token}"=>$row["token"]))." </td>\n";
	$fileList.= "</tr>\n";
}

if ($fileList) {
	$fileListHd = "<thead>";
	$fileListHd.= "<tr>";
	$fileListHd.= "<td align=right>#</td>\n";
	$fileListHd.= "<td><a href=\"".$Self."&".getOrderLink("dok_datei", $ofld, $odir)."\">Datei</a></td>\n";
	$fileListHd.= "<td><a href=\"".$Self."&".getOrderLink("dok_groesse", $ofld, $odir)."\">Gr&ouml;&szlig;e</a></td>\n";
	$fileListHd.= "<td><a href=\"".$Self."&".getOrderLink("created", $ofld, $odir)."\">Upload vom</a></td>\n";
	if ($isAdmin) $fileListHd.= "<td>L&ouml;schen</td>\n";
	$fileListHd.= "</tr>\n";
	$fileListHd.= "</thead>\n";
	
	echo "<table class=\"tblList tblAdminAttachments\">\n";
	echo $fileListHd;
	echo "<tbody>\n".$fileList."</tbody>\n</table>\n";
}
?>
<div style="min-height:15px;margin:4px 0;">
<?php 
if ($uploadError) echo "<div class=\"upErr\">".$uploadError."</div>\n";
if ($uploadMsg) {
    echo "<div class=\"upMsg\">".$uploadMsg."</div>\n";
    echo "<script>window.opener.umzugsantrag_reload_attachments()</script>\n";
}
?>
</div>
<form name="frmImUp" onsubmit="showLoadingBar(1,'')"
      action="umzugsantrag_add_attachement.php" 
      method="post" enctype="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="26214400"><!-- Angabe in Bytes; MAX:25MB; 1MB=1048576 -->
<input type="File" name="uploadfile" onchange="this.form.submit()">
<input type="hidden" name="aid" value="<?php echo $aid; ?>">
<input type="hidden" name="token" value="<?php echo $token; ?>">
<noscript><input type="submit" value="senden"></noscript>
</form>
</body>
</html>
