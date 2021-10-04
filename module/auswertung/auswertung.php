<?php 
$trackVars = "&s=$s";
//$body_content.= "<div style=\"color:red;font-weight:bold;border:1px solid red;background:#fff;padding:5px;margin-bottom:10px;\">Die Auswertung befindet sich noch in Bearbeitung !</div>\n";
die($SQL);
function daysOfMonthByYM($ym) {
	if (count(explode("-", $ym)) == 2) {
		$t = explode("-", $ym);
		if (checkdate($t[1], 1, $t[0])) {
			$mt = date("t", mktime(5,0,0, $t[1], 1, $t[0]));
			return $mt;
		}
	}
	return 31;
}

function chckSearchDate($dt, $dir) {
	if (!$dt) return "";
	if (is_int(strpos($dt, ".")) && !is_int(strpos($dt, "-"))) {
		$t = explode(".", $dt);
		$dt = $t[2]."-".$t[1]."-".$t[0];
	}
	
	if (count(explode("-", $dt)) <= 3) {
		$t = explode("-", $dt);
		for($i = 0; $i < 3; $i++) {
			if ($i && !isset($t[$i])) {
				switch($i) {
					case 1: $t[$i] = ($dir == "Bis") ? 12 : 1; break;
					case 2: $t[$i] = ($dir == "Bis") ? daysOfMonthByYM($t[0]."-".$t[1]) : 1; break;
				}
			} else {
				$t[$i] = (intval($t[$i]) ? intval($t[$i]) : 1);
			}
		}
		
		if ($t[0] < 100) $t[0]+= 2000;
		
		if ($t[1] < 10)  $t[1] = substr("0".$t[1], -2);
		elseif ($t[1] > 12) $t[1] = 12;
		
		if ($t[2] > daysOfMonthByYM($t[0]."-".$t[1]))  $t[2] = daysOfMonthByYM($t[0]."-".$t[1]);
		elseif($t[2] < 10) $t[2] = substr("0".$t[2], -2);
		return $t[0]."-".$t[1]."-".$t[2];
	}
	return "";
}

function get_aSearchQuery($aSearch) {
	$sWhere = "
	Eingangsdatum >= \"".MyDB::escape_string($aSearch["DtVon"])."\" 
	AND Eingangsdatum <= \"".MyDB::escape_string($aSearch["DtBis"])."\"\n";
	if ($aSearch["K"]) {
		$sWhere.= " AND Kunde IN (";
		foreach($aSearch["K"] as $k) $sWhere.= "\"".MyDB::escape_string($k)."\",";
		$sWhere = substr($sWhere,0,-1);
		$sWhere.= ")\n";
	}
	if ($aSearch["MA"]) {
		$sWhere.= " AND (";
		for($i = 0; $i < count($aSearch["MA"]); $i++) {
			$sWhere.= ($i ? "OR ":"")." CONCAT(`P`.Mitarbeiter,',') LIKE \"%".MyDB::escape_string($aSearch["MA"][$i]).",%\"\n";
			$sWhere.= " OR `Z`.Mitarbeiter LIKE \"".MyDB::escape_string($aSearch["MA"][$i])."\"\n";
		}
		$sWhere.= ")\n";
	}
	if ($aSearch["ADM"]) {
		$sWhere.= " AND ADM IN (''";
		foreach($aSearch["ADM"] as $k) $sWhere.= ",\"".MyDB::escape_string($k)."\"";
		$sWhere.= ")\n";
	}
	return $sWhere;
}

$aSearch["DtVon"] = (!empty($_POST["DtVon"]) ? $_POST["DtVon"] : (!empty($_GET["DtVon"]) ? $_GET["DtVon"] : null));
$aSearch["DtBis"] = (!empty($_POST["DtBis"]) ? $_POST["DtBis"] : (!empty($_GET["DtBis"]) ? $_GET["DtBis"] : null));
$aSearch["K"] = (!empty($_POST["K"]) ? $_POST["K"] : (!empty($_GET["K"]) ? $_GET["K"] : null));
$aSearch["MA"] = (!empty($_POST["MA"]) ? $_POST["MA"] : (!empty($_GET["MA"]) ? $_GET["MA"] : null));
$aSearch["ADM"] = (!empty($_POST["ADM"]) ? $_POST["ADM"] : (!empty($_GET["ADM"]) ? $_GET["ADM"] : null));
$aSearch["QSID"] = (!empty($_POST["QSID"]) ? $_POST["QSID"] : (!empty($_GET["QSID"]) ? $_GET["QSID"] : null));
$aSearch["DtVon"] = ($aSearch["DtVon"] && chckSearchDate($aSearch["DtVon"], "Von")) ? chckSearchDate($aSearch["DtVon"], "Von") : date("Y")."-01-01";
$aSearch["DtBis"] = ($aSearch["DtBis"] && chckSearchDate($aSearch["DtBis"], "Bis")) ? chckSearchDate($aSearch["DtBis"], "Bis") : date("Y")."-12-31";

// echo "<pre>#".__LINE__." print_r(aSearch): ".print_r($aSearch,true)."</pre>\n";

if (isset($_POST["DtVon"])) {
	// QSID: Query-Session-ID
	$aSearch["QSID"] = (!isset($_SESSION["QueryFilter"])) ? "S1" : "S".(count($_SESSION["QueryFilter"])+1);
	$_SESSION["QueryFilter"][$aSearch["QSID"]] = $aSearch;
} elseif (!empty($aSearch["QSID"])) {
	$QSID = $aSearch["QSID"];
	// echo "<pre>#".__LINE__." print_r(_SESSION): ".print_r($_SESSION, true)."</pre>\n";
	$aSearch = $_SESSION["QueryFilter"][$QSID];
	// echo "<pre>#".__LINE__." print_r(aSearch): ".print_r($aSearch, true)."</pre>\n";
}
if (!empty($aSearch["QSID"])) $trackVars.= "&QSID=".urlencode($aSearch["QSID"]);

$sWhere = get_aSearchQuery($aSearch);

$SQL = "SELECT `Kunde`, COUNT(*) AS Anzahl FROM `".$_TABLE["projects"]."` AS `P`";
$SQL.= "GROUP BY `Kunde` 
ORDER BY `Kunde`";

$r = MyDB::query($SQL, $connid);
if ($r) {
	$n = MyDB::num_rows($r);
	for ($i = 0; $i < $n; $i++) {
		$_e =  MyDB::fetch_array($r, MYSQL_ASSOC);
		$aKunden[$_e["Kunde"]] = $_e["Anzahl"];
	}
	MyDB::free_result($r);
} else echo MyDB::error()."<br>$SQL<br>n";

$SQL = "SELECT ADM, COUNT(*) AS Anzahl FROM `".$_TABLE["projects"]."` GROUP BY `ADM` ORDER BY ADM";
$r = MyDB::query($SQL, $connid);
if ($r) {
	$n = MyDB::num_rows($r);
	for ($i = 0; $i < $n; $i++) {
		$_e =  MyDB::fetch_array($r, MYSQL_ASSOC);
		$aADM[$_e["ADM"]] = $_e["Anzahl"];
	}
	MyDB::free_result($r);
}

$aMitarbeiter = array();
$aMaPids = array();

$SQL = "SELECT `Mitarbeiter`, COUNT(*) AS Anzahl 
FROM `".$_TABLE["projects"]."`
GROUP BY `Mitarbeiter` 
ORDER BY `Mitarbeiter`";
$r = MyDB::query($SQL, $connid);
if ($r) {
	$n = MyDB::num_rows($r);
	for ($i = 0; $i < $n; $i++) {
		$_e =  MyDB::fetch_array($r, MYSQL_ASSOC);
		$aTmp = explode(",",$_e["Mitarbeiter"]);
		for ($j = 0; $j < count($aTmp); $j++) {
			if (!isset($aMitarbeiter[$aTmp[$j]])) {
				$aMitarbeiter[$aTmp[$j]] = $_e["Anzahl"];
			} else {
				$aMitarbeiter[$aTmp[$j]]+= $_e["Anzahl"];
			}
			$aMaPids[$aTmp[$j]] = $_e["pid"];
		}
	}
	MyDB::free_result($r);
} else echo "#".__LINE__." ".__FILE__." ".MyDB::error()."<br>\n".$SQL."<br>\n";

$SQL = "SELECT  `Z`.`Mitarbeiter`, `pid`
FROM `".$_TABLE["p_entries"]."` AS `Z`
LEFT JOIN `".$_TABLE["projects"]."` AS `P` USING(pid)
GROUP BY `Z`.`Mitarbeiter`, `pid`
ORDER BY `Z`.`Mitarbeiter`";
$r = MyDB::query($SQL, $connid);
if ($r) {
	$n = MyDB::num_rows($r);
	for ($i = 0; $i < $n; $i++) {
		$_e =  MyDB::fetch_array($r, MYSQL_ASSOC);
		$aTmp = explode(",",$_e["Mitarbeiter"]);
		for ($j = 0; $j < count($aTmp); $j++) {
			if (!isset($aMitarbeiter[$aTmp[$j]])) {
				$aMitarbeiter[$aTmp[$j]] = 1;
			} else {
				if (empty($aMaPids[$aTmp[$j]])) $aMitarbeiter[$aTmp[$j]]++;
			}
		}
	}
	MyDB::free_result($r);
} else echo "#".__LINE__." ".__FILE__." ".MyDB::error()."<br>\n".$SQL."<br>\n";

$SQL = "SELECT user FROM `".$_TABLE["user"]."` ORDER BY user";
$r = MyDB::query($SQL, $connid);
if ($r) {
	$n = MyDB::num_rows($r);
	for ($i = 0; $i < $n; $i++) {
		$_e =  MyDB::fetch_array($r, MYSQL_ASSOC);
		if (empty($aMaPids[$_e["user"]])) $aMitarbeiter[$_e["user"]] = 0;
	}
	MyDB::free_result($r);
} else echo "#".__LINE__." ".__FILE__." ".MyDB::error()."<br>\n".$SQL."<br>\n";

$body_content.= "
<script>
function O(ElId) {
	return document.getElementById(ElId);
}

function ChgD(ElId) {
	// var o = document.getElementById(ElId);
	var o = O(ElId);
	D(ElId, o.style.display ? '' : 'none');
	// o.style.display = (o.style.display) ? '' : 'none';
}
function D(ElId) { // [, Option SetVal]
	// var o = document.getElementById(ElId);
	var o = O(ElId);
	if (arguments.length > 1) {
		o.style.display = arguments[1];
	}
	return o.style.display; 
}

function C(ElId) { // [, Option SetVal]
	// var o = document.getElementById(ElId);
	
	var o = O(ElId);
	if (arguments.length > 1) {
		o.className = arguments[1];
		// document.getElementById(ElId).className = arguments[1];
	}
	return o.className; 
}

var aSelBoxes = new Array('ASelect','KSelect', 'MSelect', 'ZSelect');
function SetSel(id) {
	// alert(\"SetSel(\"+id+\"\");
	C(\"SelCont\", id ? 'ActiveSelCont' : 'DeActiveSelCont');
	D('LnkSelClose', id ? '':'none');
	for (i in aSelBoxes) {
		D(aSelBoxes[i], aSelBoxes[i] == id ? '' : 'none');
		C(\"LnkSel\"+aSelBoxes[i], aSelBoxes[i] == id ? 'ActiveSel' : 'DeActiveSel');
	}
	
}
</script>
";

$body_content.= "<form action=\"?s=$s\" method=post>";
$body_content.= "<strong>Auswahl eingrenzen auf</strong> <br>\n";
$body_content.= "<a id=\"LnkSelZSelect\" class=\"DeActiveSel\" href=# onclick=\"SetSel('ZSelect')\">Zeitraum</a> ";
$body_content.= "<a id=\"LnkSelKSelect\" class=\"DeActiveSel\" href=# onclick=\"SetSel('KSelect')\"> Kunden</a> ";
$body_content.= "<a id=\"LnkSelMSelect\" class=\"DeActiveSel\" href=# onclick=\"SetSel('MSelect')\">Mitarbeiter</a> ";
$body_content.= "<a id=\"LnkSelASelect\" class=\"DeActiveSel\" href=# onclick=\"SetSel('ASelect')\">ADM</a> ";
$body_content.= "<a id=\"LnkSelClose\"   href=# onclick=\"SetSel('')\">schliessen</a>";

$body_content.= "<div id=\"SelCont\" class=\"DeActiveSelCont\">\n";

$body_content.= "<div id=\"ZSelect\" style=\"padding:15px;\">
<table>
<tr>
	<td>Von</td>
	<td><input onclick=\"showDtPicker(this)\" name=\"DtVon\" id=\"DtVon\" type=text value=\"".fb_htmlEntities($aSearch["DtVon"])."\"> JJJJ-MM-TT</td>
</tr>
<tr>
	<td>Bis</td>
	<td><input onclick=\"showDtPicker(this)\" name=\"DtBis\" id=\"DtBis\" type=text value=\"".fb_htmlEntities($aSearch["DtBis"])."\"> JJJJ-MM-TT</td>
</tr>
</table>
</div>";

$i = 0;
$c3Len = (count($aKunden) / 3 == intval(count($aKunden) / 3)) ? (count($aKunden) / 3) : intval(count($aKunden) / 3)+1;
$body_content.= "<div id=\"KSelect\"><table width=100%><tr><td valign=top>";
foreach($aKunden as $k => $v) {
	if ($i++ % $c3Len == 0) $body_content.= "</td><td valign=top>";
	$chck = (isset($_POST["K"]) && in_array($k, $_POST["K"])) ? "checked=\"true\"":"";
	$body_content.= "<input type='checkbox' name=K[] value=\"".fb_htmlEntities($k)."\" $chck>$k ($v)<br>\n";
}
$body_content.= "</td></tr></table></div>";

$i = 0;
$c3Len = (count($aADM) / 3 == intval(count($aADM) / 3)) ? (count($aADM) / 3) : intval(count($aADM) / 3)+1;
$body_content.= "<div id=\"ASelect\"><table width=100%><tr><td valign=top>";
foreach($aADM as $k => $v) {
	if ($i++ % $c3Len == 0) $body_content.= "</td><td valign=top>";
	$chck = (isset($_POST["ADM"]) && in_array($k, $_POST["ADM"])) ? "checked=\"true\"":"";
	$body_content.= "<input type='checkbox' name=ADM[] value=\"".fb_htmlEntities($k)."\" $chck>$k ($v)<br>\n";
}
$body_content.= "</td></tr></table></div>";

$i = 0;
$c3Len = (count($aMitarbeiter) / 3 == intval(count($aMitarbeiter) / 3)) ? (count($aMitarbeiter) / 3) : intval(count($aMitarbeiter) / 3)+1;
$body_content.= "<div id=\"MSelect\"><table width=100%><tr><td valign=top>";
foreach($aMitarbeiter as $k => $v) {
	if ($i++ % $c3Len == 0) $body_content.= "</td><td valign=top>";
	$chck = (isset($_POST["MA"]) && in_array($k, $_POST["MA"])) ? "checked=\"true\"":"";
	$body_content.= "<input type='checkbox' name=MA[] value=\"".fb_htmlEntities($k)."\" $chck>$k <br>\n";
}
$body_content.= "</td></tr></table></div>";

$body_content.= "<input type=\"submit\" value=\"Auswertung starten\">";
$body_content.= "</div>\n";
$body_content.= "</form>\n";
$body_content.= "<script>SetSel('ZSelect')</script>\n";

switch($s) {
	case "adm_stat":
	include(dirname(__FILE__)."/include/stat_adm_liste.php");
	include(dirname(__FILE__)."/include/stat_adm.php");
	break;
	
	case "projects_stat":
	case "projects_stat_plg":
	case "projects_stat_iab":
	include(dirname(__FILE__)."/include/stat_projects.php");
	break;
	
	case "mitarbeiter_stat":
	include(dirname(__FILE__)."/include/stat_mitarbeiter.php");
	break;
	
	case "kunden_stat":
	include(dirname(__FILE__)."/include/stat_kunden_liste.php");
	include(dirname(__FILE__)."/include/stat_kunden.php");
	break;
	
}
