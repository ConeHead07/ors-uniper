<?php
// header('Content-Type: text/html; charset="UTF-8"');
//$form = file_get_contents($HtmlBaseDir . '/leistungskatalog_eingabe.html');
//echo 'strpos Währung' . strpos($form, 'ährung') . '<br>';
//echo $form;
//exit;

if (!isset($showConfData)) $showConfData = true;
if (!isset($showErrors)) $showErrors = true;
if (!isset($showMsg)) $showMsg = true;

require_once($InclBaseDir . "conn.php");
require_once($InclBaseDir . "conf.php");
require_once($ModulBaseDir . '/leistungskatalog/ItemEditLeistungskatalog.php');

if (!isset($showConfData)) $showConfData = true;
$set_base_dir = "./";
// require_once($InclBaseDir."app_logic_specials.php");

// require_once("../lib/dataCntrForm.func.php");
if (!isset($sTrackGetData)) $sTrackGetData = "";
if (!isset($showListNavigation)) $showListNavigation = true;
$error = "";
$msg   = "";
$modBaseLink = basename($_SERVER["PHP_SELF"])."?s=$s".$sTrackGetData;

if (empty($ConfRegData)) {
    $confRegDataFile = $InclBaseDir."registered_data.inc.php";
    if (file_exists($confRegDataFile)) include_once($confRegDataFile);
}

// START: Register Vars
$_V = array(
    //array(varname, Reihenfolge , setDefault, default
    array(   "confName", "pg", true,  ""),
    array(         "id", "pg", true,  false),
    array(     "offset", "pg", true,  0),
    array(       "size", "pg", true,  50),
    array(       "ofld", "pg", true,  ""),
    array(       "odir", "pg", true,  ""),
    array(          "d", "pg", true,  ""),
    array(          "c", "pg", true,  "liste"),
    array(       "view", "pg", true,  "liste"),
    array(    "editCmd", "pg", false, false),
    array(        "cmd", "pg", false, false),
    array(   "gotoNext", "pg", false, false),
    array( "searchTerm", "pg", true, ""),
    array("searchField", "pg", true, "*"),
    array(       "dVon", "pg", false, false),
    array(       "dBis", "pg", false, false)
);

foreach($_V as $varProps) {
    $varName = $varProps[0];
    if (!empty($$varName)) continue;
    // echo "#".__LINE__." varName:$varName <br>\n";
    for ($k = 0; $k < strlen($varProps[1]); $k++) {
        switch($varProps[1][$k]) {
            case "p": if (isset($_POST[$varName]))   { $$varName = $_POST[$varName];   continue 3;} break;
            case "g": if (isset($_GET[$varName]))    { $$varName = $_GET[$varName];    continue 3;} break;
            case "c": if (isset($_COOKIE[$varName])) { $$varName = $_COOKIE[$varName]; continue 3;} break;
        }
    }
    if ($varProps[2]) $$varName = $varProps[3];
}

$userId  = (!empty($user["uid"])) ? $user["uid"] : 0;
$groupId = (!empty($user["rechte"])) ? $user["rechte"] : 0;
// print_r($user);

if (isset($confName) && isset($ConfRegData[$confName]) && file_exists($InclBaseDir.$ConfRegData[$confName])) {
    // if ($editCmd != "Insert") $editCmd = "";
    if (!isset($fieldVals)) $fieldVals = array();
    if (empty($gotoNext)) $gotoNext = "";
    $show_list = false;
    $formAction = $modBaseLink."&confName=$confName";
    $dataId = (isset($id)) ? $id : "";
    // $body_content.= "#".__LINE__." <br>\n";
    require_once($InclBaseDir.$ConfRegData[$confName]);
    $fnc_file = str_replace(".inc.php", ".fnc.php", $InclBaseDir.$ConfRegData[$confName]);
    if (file_exists($fnc_file)) require_once($fnc_file);
    // else echo "#".__LINE__." script not found: ".$fnc_file." <br>\n";

    $inputItem = new ItemEditLeistungskatalog($_CONF[$confName], $connid, $user, $dataId);

    // START: TEST //
    $groupId = 0;
    $inputItem->set_dokUid($user["uid"]);
    //$inputItem->set_dokRechte(1);
    $inputItem->set_uid($userId);
    $inputItem->set_userRechte($user["rechte"]);
    $inputItem->set_autoFormBuilder("file"); // file | render
    // ENDE:  TEST //

    $userHasInsertAccess = $inputItem->userHasRowAccess("Insert");

    //$body_content.= "#".__LINE__." editCmd: $editCmd<br>\n";
    if (!empty($editCmd) || $dataId) {
        // $body_content.= "#".__LINE__." editCmd:".print_r($editCmd, true)." <br>\n";
        if (!empty($editCmd) && is_array($editCmd)) {
            $editCmd = key($editCmd);
        }

        if ($view == "del") {
            $editCmd = "Drop";
        }


        if (!isset($editCmd)) {
            $editCmd = "Edit";
        }

        if (0) echo '<pre>'
            . json_encode(compact('editCmd', 'formAction', 'fieldVals', 'gotoNext'), JSON_PRETTY_PRINT)
            . '</pre>';

        $inputItem->autorun_byConf(
            // Per Default, Modifizierbar
            $editCmd, $formAction, $fieldVals, $gotoNext
        );

        //die('#'.__LINE__ . ' ' . __FILE__ . ' ' . $inputItem->Error);
        if ($inputItem->autorun_status == 0) {
            // Datensatz ist noch im Bearbeitungsprozess, zeige Formular
            if ($inputItem->Error) $body_content.= "<div style=\"color:red;\">".$inputItem->Error."</div>\n";
            if ($inputItem->Msg) $body_content.= "<div style=\"color:navy;\">".$inputItem->Msg."</div>\n";
            // $body_content.= "#".__LINE__." ";
            $body_content.= $inputItem->tplForm;

        } else {
            $show_list = true;
            switch($inputItem->autorun_status) {
                case 1:
                    $body_content.= "Eintrag wurde gelöscht!<br>\n";
                    break;

                case -1:
                    $body_content.= "Interner Fehler beim Löschen des Katalogeintrags!<br>\n";
                    break;

                case 2:
                case 3:
                    $body_content.= "Katalogeintrag wurde gespeichert!<br>\n";

                    if (isset($gotoNext) && $gotoNext == "New") {
                        $editCmd = "Edit";
                        $inputItem = new ItemEdit($_CONF[$confName], $connid, $groupId, false);
                        $inputItem->set_uid($userId);
                        $inputItem->set_userRechte($user["rechte"]);
                        $inputItem->set_autoFormBuilder("render");
                        $inputItem->autorun_byConf(
                        // Per Default, Modifizierbar
                            $editCmd, $formAction, $fieldVals, $gotoNext);
                        $body_content.= $inputItem->tplForm;
                        $show_list = false;
                    }
                    break;

                case -2:
                    $body_content.= "<L".__LINE__." Interner Fehler beim Speichern!<br>\n";
                    break;

                default:
                    $body_content.= "<L".__LINE__."/> <br>\n";
            }

        }
    } else $show_list = true;

    if ($show_list) {
        if ($userHasInsertAccess) {
            $body_content.= '[<a href="'.$modBaseLink."&confName=".rawurlencode($confName).'&editCmd=Edit">Neuen Eintrag hinzufügen</a>]<br>' . "\n";
        } else {
            $body_content.= "(No Insert-Access)<br>\n";
        }

        $inputItem->objItemList = new ItemListClass($inputItem, "*", true);
        $inputItem->objItemList->set_baseLink($formAction."&ofld={ofld}&odir={odir}");
        // $list = $itemList->mkList($offset, $size, $ofld, $odir);
        // $body_content.= $list;
        $body_content.= $inputItem->autorun_itemlist(array("data" => $confName, "baseLink" => $formAction));
    }
    if ($inputItem->dbError) $body_content.= "#".__LINE__." <pre>".$inputItem->dbError."</pre>\n";
    // $body_content.= "#".__LINE__." ".date("H:i:s")." <br>\n";
} else {
    $body_content.= "Treffen Sie eine Auswahl, welche Datenbest�nde sie bearbeiten m�chten!<br>\n";
    // echo "#".__LINE__." confName:$confName, ".$ConfRegData[$confName]." isFile(".$ConfRegData[$confName].") ".intval(file_exists($ConfRegData[$confName]))." <br>\n";
}

return true;
