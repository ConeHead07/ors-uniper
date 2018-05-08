<?php 
require_once("../header.php");
if (function_exists("activity_log")) register_shutdown_function("activity_log");

require_once($MConf["AppRoot"]."sites".DS."nebenleistung_save_attachement.php");

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
$nid = getRequest("nid","");
$drop = getRequest("drop","");
$titel = getRequest("titel","");
$ofld = getRequest("ofld","");
$odir = getRequest("odir","");

if (!$nid) die("Es wurde keine AntragsID &uuml;bergeben! ".print_r($_GET,1)."".print_r($_POST,1)."".print_r($_REQUEST,1)."<br>\n");


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
$Self = basename($_SERVER["PHP_SELF"])."?nid=$nid";

if (isset($_FILES) && isset($_FILES["uploadfile"])) {
	$dokid = nl_save_upload($nid, $isAdmin);
	if ($dokid) $uploadMsg = "Hochgeladene Datei (#$dokid) wurde gespeichert!";
}
if (!empty($drop)) {
	nl_drop_attachement($nid, $drop);
	if ($dropError) $uploadError.= ($uploadError?"<br>\n":"").$dropError;
}


$sql = "SELECT * FROM `".$_TABLE["nebenleistungsanlagen"]."` \n";
$sql.= " WHERE `nid` = \"".$db->escape($nid)."\" \n";
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
    max-width:500px;
}
</style><link rel="STYLESHEET" type="text/css" href="<?php echo $MConf["WebRoot"]; ?>css/tablelisting.css">
</head>
<body>
<form name="frmImUp" onsubmit="showLoadingBar(1,'')" action="nebenleistung_add_attachement.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="26214400"><!-- Angabe in Bytes; MAX:25MB; 1MB=1048576 -->
<input type="File" name="uploadfile" onchange="submitUpload()">
<input type="hidden" name="nid" value="<?php echo $nid; ?>">
<input type="submit" value="Datei anhängen">
</form>

<?php 

if ($uploadError) echo "<div class=\"upErr\">".$uploadError."</div>\n";
if ($uploadMsg) echo "<div class=\"upMsg\">".$uploadMsg."</div>\n";

$dropLink = "<a href=\"".$Self."&drop={dokid}&ofld=$ofld&odir=$odir\" style=\"text-decoration:none;border:0;\">";
$dropLink.= "<img src=\"".$MConf["WebRoot"]."images/status_storniert.png\" style=\"text-decoration:none;border:0;\" border=0 align=\"absmiddle\" width=\"16\" height=\"16\"> L&ouml;schen";
$dropLink.= "</a>";

$fileList = "";

if (is_array($rows) && count($rows)) for($i = 0; $i < count($rows); $i++) {
	$row = $rows[$i];
	$fileList.= "<tr>";
	$fileList.= "<td align=right>".($i+1)."</td>\n";
	$fileList.= "<td><a href=\"".$webdir.$row["dok_datei"]."\">".$row["dok_datei"]."</a></td>\n";
	$fileList.= "<td>".format_file_size($row["dok_groesse"])."</td>\n";
	$fileList.= "<td>".$row["created"]."</td>\n";
	$fileList.= "<td>".str_replace("{dokid}", $row["dokid"], $dropLink)." </td>\n";
	$fileList.= "</tr>\n";
}

if ($fileList) {
	$fileListHd = "<thead>";
	$fileListHd.= "<tr>";
	$fileListHd.= "<td align=right>#</td>\n";
	$fileListHd.= "<td><a href=\"".$Self."&".getOrderLink("dok_datei", $ofld, $odir)."\">Datei</a></td>\n";
	$fileListHd.= "<td><a href=\"".$Self."&".getOrderLink("dok_groesse", $ofld, $odir)."\">Gr&ouml;&szlig;e</a></td>\n";
	$fileListHd.= "<td><a href=\"".$Self."&".getOrderLink("created", $ofld, $odir)."\">Upload vom</a></td>\n";
	$fileListHd.= "<td>L&ouml;schen</td>\n";
	$fileListHd.= "</tr>\n";
	$fileListHd.= "</thead>\n";
	
	echo "<table class=\"tblList tblAdminAttachments\">\n";
	echo $fileListHd;
	echo "<tbody>\n".$fileList."</tbody>\n</table>\n";
}

?>
</body>
</html>
