<?php
require("header.php");


$sql = 'SELECT `leistung_id`, `leistungskategorie_id`, `leistungsgruppe`, `aktiv`, `Bezeichnung`, `leistungseinheit`, `leistungseinheit_abk`,'
	. '`leistungseinheit2`, `leistungseinheit2_abk`, `preis_pro_einheit`, `waehrung`, `created`, `modified`, image '
	. ' FROM `mm_leistungskatalog` '
	. ' WHERE image LIKE "%\.jpg" ';
$rows = $db->query_rows($sql);

$imgDir = 'images/leistungskatalog/';
$webBaseDir = $MConf['WebRoot'] . $imgDir;
$appBaseDir = $MConf["AppRoot"] . $imgDir;

echo $webBaseDir . '<br>';

echo '<pre>';
$_sqlB1 = 'UPDATE mm_leistungskatalog SET image_urldata = ? WHERE leistung_id = ?';
$_sqlB2 = 'INSERT INTO mm_leistungskatalog_images SET '
    . ' leistung_id = ?, '
    . ' name = ?, '
    . ' mime_type = ?, '
    . ' width = ?, '
    . ' height = ?, '
    . ' urldata = ?, '
    . ' urldata_origin = ? ';

$stmt1 = $db->conn->prepare($_sqlB1);
if ($db->error()) {
    echo $db->error();
    die('FINISHED IN LINE ' . __LINE__);
}
$stmt2 = $db->conn->prepare($_sqlB2);
if ($db->error()) {
    echo $db->error();
    die('FINISHED IN LINE ' . __LINE__);
}

function getResizedJpgFile($srcPath, $maxWidth, $maxHeight, $dstPath = '', $quality = 100) {
    $_imgInfo = getimagesize($srcPath);
    list($oldWidth, $oldHeight) = $_imgInfo;
    if (!$dstPath) {
        $saveas = tempnam(sys_get_temp_dir(), basename($srcPath) . '_resized');
    } else {
        $saveas = $dstPath;
    }
    if (!function_exists('imagecreatefromjpeg')) {
        return $srcPath;
    }

    if (!$maxWidth) {
        $maxWidth = $_imgInfo[0];
    }

    if (!$maxHeight) {
        $maxHeight = $_imgInfo[1];
    }

    $source = imagecreatefromjpeg($srcPath);

    if ($_imgInfo[0] === $maxWidth || $_imgInfo[1] === $maxHeight) {
        $f1 = $_imgInfo[0] / $maxWidth;
        $f2 = $_imgInfo[1] / $maxHeight;
        $factor = max($f1, $f2);
        $newWidth = $_imgInfo[0] * $factor;
        $newHeight = $_imgInfo[1] * $factor;
// Bild laden
        $dst = imagecreatetruecolor($newWidth, $newHeight);

// Skalieren
        imagecopyresized($dst, $source, 0, 0, 0, 0, $newWidth, $newHeight, $oldWidth, $oldHeight);
    }

    imagejpeg ($dst, $saveas, $quality);

    return $saveas;
}


foreach($rows as $_row) {
    $_id = $_row['leistung_id'];
    $_img = $webBaseDir . $_row['image'];
    $_imgAbsPath = $appBaseDir . $_row['image'];
    echo $_img . "\n";
    echo "id: $_id, " . $_row['image'] . "\n";
    if (file_exists( $_imgAbsPath )) {
        $_imgInfo = getimagesize($_imgAbsPath);
        echo 'IMAGE FOUND ' . $_imgAbsPath . "!!!<br>\n";
        echo '<img src="' . $webBaseDir . $_row['image'] . '" style="max-width:150px;max-height:150px;">' . "\n";

        $tmpImgOrig = getResizedJpgFile($_imgAbsPath, 0, 0, '', 100);
        $tmpImgSmall = getResizedJpgFile($_imgAbsPath, 250, 250, '', 90);

        if ($_imgAbsPath !== $tmpImgOrig) {
            $_imgDataOrig = file_get_contents($tmpImgOrig);
            $_imgData = file_get_contents($tmpImgSmall);
            $_imgUrlData = 'data:image/jpg;base64,' . base64_encode($_imgData);
            $_imgUrlDataOrig = 'data:image/jpg;base64,' . base64_encode($_imgDataOrig);
            $_imgUrlData2 = $_imgUrlData;
            $_imgUrlData3 = $_imgUrlDataOrig;
        } else {
            $_imgDataOrig = file_get_contents($_imgAbsPath);
            $_imgData = $_imgDataOrig;
            $_imgUrlData = 'data:image/jpg;base64,' . base64_encode($_imgData);
            $_imgUrlDataOrig = $_imgUrlData;
            $_imgUrlData2 = $_imgUrlData;
            $_imgUrlData3 = $_imgUrlDataOrig;
        }


        echo '<img style="max-width:200px;max-height:200px;border:1px solid red;"
         src="' . $_imgUrlData . '">' . "<br>\n";
        try {
            $_d = '';
            $_d2 = '';
            $stmt1->bind_param('si', $_imgUrlData, $_id);
            $stmt1->execute();
            if ($db->error()) {
                echo $db->error() . "<br>\n";
            }
            echo 'affected_rows: ' . $stmt1->affected_rows . "<br>\n";
        } catch(\Exception $e) {
            echo $e->getMessage();
        }

        $_name = basename($_row['image']);
        $_width = $_imgInfo[0];
        $_height = $_imgInfo[1];
        $_mime_type = image_type_to_mime_type($_imgInfo[2]);
        echo 'AFFECTED_ROWS 1: ' . $db->affected_rows() . "<br>\n";

        try {
            $stmt2->bind_param('issiiss',
                $_id, $_name, $_mime_type,
                $_width, $_height, $_imgUrlData2, $_imgUrlData3);
            $stmt2->execute();

            if ($db->error()) {
                echo $db->error() . "\n";
            }
            // echo $db->lastQuery . "\n";
        } catch(\Exception $e) {
            echo $e->getMessage();
        }
        echo 'AFFECTED_ROWS 2: ' . $db->affected_rows() . "\n";
    } else {
        echo 'IMAGE NOT FOUND ' . $_imgAbsPath . "!!!<br>\n";
        break;
    }
}
echo '</pre>';
