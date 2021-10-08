<?php 
require_once("../header.php");
set_time_limit(600);
error_reporting(E_ALL);
//echo ini_get("error_reporting");
if (function_exists("activity_log")) register_shutdown_function("activity_log");


function getOrderLink($chckfld, $ofld, $odir) {
    if ($ofld != $ofld || $odir == "DESC") return "ofld=$chckfld&odir=ASC";
    return "ofld=$chckfld&odir=DESC";
}

$aOrderFields = array(
    "dok_datei"   => array("field" => "dok_datei",   "defOrder" => "ASC"),
    "dok_groesse" => array("field" => "dok_groesse", "defOrder" => "ASC"),
    "created"     => array("field" => "created",     "defOrder" => "DESC"),
);

$isAdmin = (strpos($user["gruppe"],"admin")!==false);
$aid     = getRequest("aid",  "");
$drop    = getRequest("drop", "");
$titel   = getRequest("titel","");
$ofld    = getRequest("ofld", "");
$odir    = getRequest("odir", "");

if (!isset($int)) {
         $int = (int)getRequest("internal", 0);
}

if (!$aid) die("<strong>Es wurde keine AntragsID &uuml;bergeben!</strong> <br>
<br>
<strong>Wie erhalte ich eine AntragsID?</strong><br>
Eine ID erhält Ihr Antrag mit dem ersten erfolgreichen (Zwischen-)Speichern
mit mind. einem vollständigen Mitarbeitereintrag in der Umzugsliste.<br>
<br>
Anschlie&szlig;end k&ouml;nnen Sie Dateien hinzuf&uuml;gen!<br>\n");


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
$Self = basename($_SERVER["PHP_SELF"])."?aid=$aid";

if (isset($_FILES) && isset($_FILES["uploadfile"])) {
	$dokid = save_upload($aid, $isAdmin);
	if ($dokid) $uploadMsg = "Hochgeladene Datei wurde gespeichert!";
}
if (!empty($drop)) {
	if ($isAdmin) {
		drop_attachement($aid, $drop);
		if ($dropError) $uploadError.= ($uploadError?"<br>\n":"").$dropError;
	} else {
		$uploadError.= "Nur Administratoren dürfen Dateianhänge l&ouml;schen!<br>\n";
	}
}

$sql = "SELECT * FROM `".$_TABLE["umzugsanlagen"]."` \n";
$sql.= " WHERE `aid` = \"".$db->escape($aid)."\" \n";
$sql.= $OrderBy;
$rows = $db->query_rows($sql);

$dropLink = "<a href=\"".$Self."&drop={dokid}&ofld=$ofld&odir=$odir\" style=\"text-decoration:none;border:0;\">";
$dropLink.= "<img src=\"".$MConf["WebRoot"]."images/status_storniert.png\" style=\"text-decoration:none;border:0;\" border=0 align=\"absmiddle\" width=\"16\" height=\"16\"> L&ouml;schen";
$dropLink.= "</a>";

$fileList = "";
if (is_array($rows) && count($rows)) for($i = 0; $i < count($rows); $i++) {
    $row = $rows[$i];
    
    $_fp = pathinfo($row["titel"]?:$row["dok_datei"]);
    $_ftxt = ( strlen($_fp['filename']) < 60 ? $_fp['filename'] : substr($_fp['filename'], 0, 55).'...') . '.' . $_fp['extension'];
    $fileList.= '<li style="width:800px;">'
               . '<span style="display:inline-block;width:482px;" title="'.$row["dok_datei"].'"><a href="' . $webdir.$row["dok_datei"] . '" target="_blank">'.$_ftxt.'</a></span>' 
               . '<span style="display:inline-block;width:100px;">' . format_file_size($row["dok_groesse"]). '</span>' 
               . '<span style="display:inline-block;width:160px;">' . $row["created"] . '</span>'
               . '</li>';
}

if ($fileList) {
    $fileListHd = '<div>'
                 .'<span style="display:inline-block;width:500px;font-weight:bold;">Datei</span>'
                 .'<span style="display:inline-block;width:100px;font-weight:bold;">Gr&ouml;&szlig;e</span>'
                 .'<span style="display:inline-block;width:160px;font-weight:bold;">Upload vom</span>'
    //if ($isAdmin) .'<span>L&ouml;schen</span>'
                 .'</div>';

    echo $fileListHd;
    echo "<ul class=\"ulAttachements\">\n".$fileList."</ul>\n";
} else {
    echo 'Keine Dateien vorhanden!';
}

