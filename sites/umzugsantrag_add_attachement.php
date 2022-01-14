<?php 

require_once('../header.php');

set_time_limit(600);
error_reporting(E_ALL);
//echo ini_get('error_reporting');
if (function_exists('activity_log')) {
    register_shutdown_function('activity_log');
}

require_once($MConf['AppRoot'] . 'sites' . DS . 'umzugsantrag_save_attachement.php');

function getOrderLink($chckfld, $ofld, $odir) {
	if ($chckfld !== $ofld || strcmp($odir, 'DESC') === 0) {
	    return "ofld=$chckfld&odir=ASC";
    }
	return "ofld=$chckfld&odir=DESC";
}

$aOrderFields = array(
	'dok_datei' => array('field'=>'dok_datei', 'defOrder'=>'ASC'),
	'dok_groesse' => array('field'=>'dok_groesse', 'defOrder'=>'ASC'),
    'created' => array('field'=>'created', 'defOrder'=>'DESC'),
    'target' => array('field'=>'target', 'defOrder'=>'ASC'),
);

$isAdmin = (strpos($user['gruppe'],'admin')!==false);
$cmd = getRequest('cmd','');
$aid = getRequest('aid','');
$token = getRequest('token','');
$target = getRequest('target','');
$drop = getRequest('drop','');
$titel = getRequest('titel','');
$ofld = getRequest('ofld','');
$odir = getRequest('odir','');
$response = getRequest('response','');
$internal = getRequest('internal',0);

$lieferdatum = getRequest("lieferdatum", '');
$uebergeben_an = getRequest("uebergeben_an", '');
$leistungen = getRequest("leistungen", []);
$tracking_id = getRequest("tracking_id", '');
$tracking_link = getRequest("tracking_link", '');

$aLeistungen = [];
$selectedLeistungen = [];

$pageTitel = ($internal ? 'Interne ' : '') . 'Datei hochladen';

if (!empty($target) && $target === 'lieferscheine') {
    $pageTitel = 'Lieferschein hochladen';
    require_once $ModulBaseDir . 'lieferschein/lieferschein.model.php';

    $lsmodel = new LS_Model($aid);
    $lrows = $lsmodel->getLeistungen();

    if (empty($lrows)) {
        $uploadError = 'Es wurden keine Leistungen zur der Auftrags-ID ' . $aid . ' gefunden!';
    } else {
        foreach($lrows as $_row) {
            $leistung_id = $_row['leistung_id'];
            $aLeistungen[$leistung_id] = $_row['Kategorie'];
        }
    }

    if (!empty($leistungen)) {
        foreach($leistungen as $_idx => $_id) {
            if (!empty($aLeistungen)) {
                $selectedLeistungen[$_id] = $aLeistungen[$_id];
            }
        }
    }
}

if (!$token && $aid) {
    switch($response) {
        case 'json':
            header('Content-Type: application/json');
            json_encode([
                    'success' => false,
                'error' => 'Es wurde kein Token übergeben',
                'jquery-upload-file-error' => 'Es wurde kein Token übergeben',
            ]);
        
        default:
            echo
                '<strong>Es wurde kein token &uuml;bergeben!</strong> <br>'
                . '<br>'
                . '<strong>Wie erhalte ich einen Token</strong>'
                . 'Einen Token erhalten Sie automatisch beim Öffnen eines Leistungsformulars.<br>'
                . '<br>'
                . 'Anschlie&szlig;end k&ouml;nnen Sie Dateien hinzuf&uuml;gen!<br>\n'
            ;
    }
    exit;
}


if (isset($aOrderFields[$ofld])) {
    if (!in_array(strtoupper($odir), array('ASC','DESC'))) {
        $odir = '';
    }
    $OrderBy = 'ORDER BY ' . $aOrderFields[$ofld]['field'] . ' ' . (empty($odir) ? $aOrderFields[$ofld]['defOrder'] : $odir);
} else {
    $ofld = 'created';
    $odir = 'DESC';
    $OrderBy = "ORDER BY $ofld $odir";
}

$dropError = '';
$uploadError = '';
$uploadMsg = '';
$webdir = $MConf['WebRoot'].'/attachements/';
$Self = basename($_SERVER['PHP_SELF'])."?token=$token";

if ($cmd === 'upload') {
    $uploadName = 'uploadfile';
    $uploadCheck = check_upload($uploadName);

    $responseData = [
        'success' => false,
        'error' => '',
        'msg' => '',
    ];

    if ($uploadCheck['success']) {

        $upload = $uploadCheck['upload'];
        $responseData['filename'] = $upload['name'];
        $responseData['filesize'] = $upload['size'];
        $uploadFile = $uploadCheck['file'];

        // save_upload($aid, $token, $isAdmin, $internal);
        $saveCheck = save_checkedUploadFile($aid, $uploadCheck, $token, $isAdmin, $internal);
        $dokid = $saveCheck['success'] ? $saveCheck['dokid'] : false;
        if ($dokid) {
            $responseData['dokid'] = $dokid;
            $responseData['success'] = true;
            if (strcmp($target, 'lieferscheine') === 0) {
                $uploadMsg = 'Hochgeladene Datei wurde gespeichert!';
                $uploadFile = $saveCheck['savedas'];

                $saveCheckLS = save_lieferschein($aid, $uploadFile, [
                        'dokid' => $dokid,
                    'leistungen' => $selectedLeistungen,
                    'uebergeben_an' => $uebergeben_an,
                    'lieferdatum' => $lieferdatum,
                    'tracking_id' => $tracking_id,
                    'tracking_link' => $tracking_link,
                ]);
                if ($saveCheckLS['success']) {
                    $uploadMsg = "<br>\n" . 'Lieferschein wurde importiert!';
                    $responseData['lid'] = $saveCheckLS['lid'];
                } else {
                    $responseData['error'] = 'Upload konnte als Anlage importiert werden, aber beim Lieferscheinimport sind Fehler aufgetreten!' . "\n";
                    $responseData['error'].= $saveCheckLS['error'];
                    $responseData['success'] = false;
                }
            }
        } else {
            $responseData['error'] = $saveCheck['error'];
        }
    } else {
        $responseData['error'] = $uploadCheck['error'];
    }

    if ($response === 'json') {
        header('Content-Type: application/json');
        echo json_encode($responseData);
        exit;
    }
}

if (!empty($drop)) {
	if ($isAdmin) {
		drop_attachement($token, $drop);
		if ($dropError) {
		    $uploadError.= ($uploadError ? '<br>' . "\n" : '') . $dropError;
        }
        else {
            $uploadMsg = 'Datei wurde gelöscht!';
        }
	} else {
		$uploadError.= 'Nur Administratoren dürfen Dateianhünge l&ouml;schen!<br>' . "\n";
	}
}


$sql = 'SELECT * FROM `' . $_TABLE['umzugsanlagen'] . "` \n";
$sql.= ' WHERE `token` = ' . $db::quote($token) . " \n";
$sql.= $OrderBy;
$rows = $db->query_rows($sql);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title><?= $pageTitel ?></title>
<style>
.upMsg { font-size:11px; color:#228b22; font-family:Arial,Helvetica,sans-serif; }
.upErr { font-size:11px; color:#f00; font-family:Arial,Helvetica,sans-serif; }
table.tblList.tblAdminAttachments {
    width:100%;
    max-width:650px;
}
    label, input, * {
        font-family: Arial,Helvetica,sans-serif;
        font-size:1rem;
    }
    label {
        font-family: Arial,Helvetica,sans-serif
        font-weight: bold;
    }
    input[type=text] {
        padding: 2px;
        border-radius: 4px;
        border: 1px solid #a8a7a7;
    }

    .form-group {
        margin-top: 0.5rem;
        padding:3px 3px 3px 0;
    }
</style><link rel='STYLESHEET' type='text/css' href="<?php echo $MConf['WebRoot']; ?>css/tablelisting.css">
</head>
<body>
<?php if ($uploadError || $uploadMsg): ?>
<div style="min-height:15px;margin:4px 0;">
    <?php
    if ($uploadError) {
        echo '<div class="upErr">' . $uploadError . "</div>\n";
    }

    if ($uploadMsg) {
        echo '<div class="upMsg">' . $uploadMsg . "</div>\n";
        echo "<script>window.opener.umzugsantrag_reload_attachments()</script>\n";
    }
    ?>
</div>
<?php endif; ?>
<script>
    function checkUpload(e) {
        var frm = document.forms['frmImUp'];
        var t = document.getElementById('documentTarget').value;
        console.log("checkUpload ", { frm, t });
        if (t.length < 1) {
            console.log("checkUpload #240 return true", { frm, t });
            return true;
        }

        var error = '';
        var up = document.getElementById('uploadfile').value;
        var ue = document.getElementById('uebergebenAn').value;
        var ld = document.getElementById('lieferdatum').value;
        var tid = document.getElementById('trackingId').value;
        var tlnk = document.getElementById('trackingLink').value;

        var numLeistungenChecked = 0;
        for (var i = 0; i < frm.elements.length; i++) {
            var el = frm.elements[i];
            if (el.name === 'leistungen[]' && el.checked) {
                numLeistungenChecked++;
            }
        }

        if (!up) {
            error = 'Es wurde noch keine Datei ausgewählt.'
        }

        if (!ue || ue.length < 3) {
            error += (error.length > 0 ? "\n" : "");
            error += 'Die Angabe "Übergeben an" ist leer oder zu kurz, mind. 3 Zeichen.';
        }

        if (!ld || ld.length < 3) {
            error += (error.length > 0 ? "\n" : "");
            error += 'Bitte geben Sie noch das Liefer- bzw. Übergabedatum an.';
        }

        if (numLeistungenChecked === 0) {
            error += (error.length > 0 ? "\n" : "");
            error += "Es wurden keine enthaltenen Artikel / Leistungen ausgewählt.";
        }

        if (error) {
            alert('Bitte vervollständigen Sie die Angaben:' + "\n" + error);
            console.log("checkUpload #274 return false", { frm, error, up, ue, numLeistungenChecked });
            return false;
        }

        if (typeof showLoadingBar === 'function') {
            showLoadingBar(1, '');
        }
        console.log("checkUpload #240 return true", { frm, error, up, ue, numLeistungenChecked });

        frm.submit();

    }
</script>
<form name="frmImUp" <?php if ($target !== 'lieferscheine'): ?> onsubmit="showLoadingBar(1, '')"<?php else: ?> onsubmit="return false;"<?php endif; ?>
      action="umzugsantrag_add_attachement.php"
      method='post' enctype="multipart/form-data">
    <div style="margin:0 0 1rem 0">
        <div><b><?= $pageTitel ?></b></div>
    <?php if (empty($target) || $target !== 'lieferscheine'): ?>
        <div style="font-size:smaller">
            <?php if ($internal): ?>
                Interne Dokumente sind für den Kunden nicht sichtbar!
            <?php else: ?>
                Normale Dokumente sind auch für den Kunden einsehbar!
            <?php endif; ?>
        </div>
    <?php endif; ?>
    </div>
    <input type="hidden" name="MAX_FILE_SIZE" value="26214400"><!-- Angabe in Bytes; MAX:25MB; 1MB=1048576 -->
    <input type="File" name="uploadfile" id="uploadfile"
        <?php if ($target !== 'lieferscheine'): ?>
            onchange="this.form.submit()"
        <?php else: ?>
            accept="application/pdf"
        <?php endif; ?>>
    <input type="hidden" name="aid"      value="<?php echo htmlentities($aid); ?>">
    <input type="hidden" name="token"    value="<?php echo htmlentities($token); ?>">
    <input type="hidden" name="target" id="documentTarget"   value="<?php echo htmlentities($target); ?>">
    <input type="hidden" name="internal" value="<?php echo htmlentities($internal); ?>">
    <input type="hidden" name="cmd" value="upload">
    <?php if ($target === 'lieferscheine'): ?>
        <div class="form-group">
            <label for="uebergebenAn" style="font-weight: bold;">Übergeben an:</label><br>
            <input required type="text" style="width:100%" name="uebergebenAn" id="uebergebenAn" value="<?= htmlentities($uebergeben_an ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="lieferdatum" style="font-weight: bold;">Übergabedatum:</label><br>
            <input required type="date" style="width:100%" name="lieferdatum" id="lieferdatum" value="<?= htmlentities($lieferdatum ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="leistungen" style="font-weight: bold;">Ausgelieferte Artikel markieren:</label><br>
            <?php foreach($aLeistungen as $_id => $_label): ?>
            <input type="checkbox" name="leistungen[]" id="leistungen<?= $_id ?>" value="<?= $_id ?>">
            <label for="leistungen<?= $_id ?>" class="inline"><?= $_label ?></label> &nbsp;
            <?php endforeach; ?>

            <?php /*
            <input type="checkbox" name="leistungen[]" id="leistungenSchreibtsich" value="Schreibtisch">
            <label for="leistungenSchreibtsich" class="inline">Schreibtisch</label> &nbsp;
                <input type="checkbox" name="leistungen[]" id="leistungenStuhl" value="Stuhl">
                <label for="leistungenStuhl" class="inline">Stuhl</label> &nbsp;
                <input type="checkbox" name="leistungen[]" id="leistungenLampe" value="Lampe">
                <label for="leistungenLampe" class="inline">Lampe</label> &nbsp;
                <input type="checkbox" name="leistungen[]" id="leistungenSonstiges" value="Sonstiges">
                <label for="leistungenSonstiges" class="inline">Sonstiges</label>
            */ ?>
        </div>
        <?php /* <input type="text" name="leistungen" id="leistungen" value="<?= htmlentities($leistungen ?? ''); ?>"><br> */ ?>
        <div class="form-group">
            <label for="trackingId" style="font-weight: bold;">TrackingID:</label><br>
            <input type="text" style="width:100%" name="tracking_id" id="trackingId" value="<?= htmlentities($tracking_id ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="trackingLink" style="font-weight: bold;">TrackingLink:</label><br>
            <input type="text" style="width:100%" name="tracking_link" id="trackingLink" value="<?= htmlentities($tracking_link ?? '') ?>">
        </div>
    <?php endif; ?>
    <noscript><input type="submit" value="senden"></noscript>
    <?php if ($target === 'lieferscheine'): ?><input type="submit" value="senden" onclick="checkUpload()"><?php endif; ?>
</form><br>
<?php

$dropLink = "<a href=\"" . $Self."&drop={dokid}&token={token}&ofld=$ofld&odir=$odir\" style=\"text-decoration:none;border:0;\">";
$dropLink.= '<img src="' . $MConf['WebRoot'] . 'images/status_storniert.png" style="text-decoration:none;border:0;" border=0 align="absmiddle" width="16" height="16"> L&ouml;schen';
$dropLink.= "</a>";

$fileList = '';

if (is_array($rows) && count($rows)) {
    $iNumRows = count($rows);
    for($i = 0; $i < $iNumRows; $i++) {
        $row = $rows[$i];
        $fileList.= '<tr>';
        $fileList.= '<td align=right>'.($i+1)."</td>\n";
        $fileList.= '<td><a href="' . $webdir . $row['dok_datei'] . '" title="' . $row['titel'] . '">' . ($row['titel']?:$row['dok_datei']) . "</a></td>\n";
        $fileList.= '<td align=right>' . format_file_size($row['dok_groesse']) . "</td>\n";
        $fileList.= '<td>' . $row['created'] . "</td>\n";
        $fileList.= '<td>' . $row['target'] . "</td>\n";
        if ($isAdmin) {
            $fileList.= '<td>' . strtr($dropLink, ['{dokid}'=>$row['dokid'], '{token}'=>$row['token']] ). " </td>\n";
        }
        $fileList.= "</tr>\n";
    }
}

if ($fileList) {
	$fileListHd = '<thead>';
	$fileListHd.= '<tr>';
	$fileListHd.= "<td align=right>#</td>\n";
	$fileListHd.= '<td><a href="' . $Self . '&' . getOrderLink('dok_datei', $ofld, $odir) . "\">Datei</a></td>\n";
	$fileListHd.= '<td align=right><a href="' . $Self . '&' . getOrderLink('dok_groesse', $ofld, $odir) . "\">Gr&ouml;&szlig;e</a></td>\n";
    $fileListHd.= '<td><a href="' . $Self . '&' . getOrderLink('created', $ofld, $odir) . "\">Upload vom</a></td>\n";
    $fileListHd.= '<td><a href="' . $Self . '&' . getOrderLink('target', $ofld, $odir) . "\">Target</a></td>\n";
	if ($isAdmin) {
	    $fileListHd.= "<td>L&ouml;schen</td>\n";
    }
	$fileListHd.= "</tr>\n";
	$fileListHd.= "</thead>\n";

	echo "<table class=\"tblList tblAdminAttachments\">\n";
	echo $fileListHd;
	echo "<tbody>\n" . $fileList . "</tbody>\n</table>\n";
}
?>
</body>
</html>
