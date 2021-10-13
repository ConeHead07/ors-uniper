<?php
$op = ""; // Ausgabebuffer 
if (basename($_SERVER["PHP_SELF"])==basename(__FILE__)) require_once("../header.php");

if (empty($s)) $s = getRequest("s", "umzugssuche");
$queryForm = file_get_contents($HtmlBaseDir."admin_antraege_suche.html");
$queryForm = str_replace("{s}", $s, $queryForm);
require_once($InclBaseDir."parse_userquery.php");

$searchFields = array(
	"a.aid" => "",
    "a.vorname" => "",
    "a.name" => "",
	"a.ort" => "",
    "a.plz" => "",
    "a.strasse" => "",
    "a.land" => "",
	"a.gebaeude" => "",
	"a.umzugstermin" => "",
	"a.umzugsstatus" => "",
	"u.kid" => "",
	"m.name" => "",
	"m.vorname" => "",
	"m.nutzung" => "",
	"m.extern_firma" => "",
    "m.ort" => "",
	"m.gebaeude" => "",
	"m.etage" => "",
	"m.raumnr" => "",
	"m.gf" => "",
	"m.bereich" => "",
	"m.abteilung" => "",
	"m.ziel_ort" => "",
	"m.ziel_gebaeude" => "",
	"m.ziel_etage" => "",
	"m.ziel_raumnr" => "",
	"m.ziel_gf" => "",
	"m.ziel_bereich" => "",
	"m.ziel_abteilung" => "",
	"m.umzugsart" => ""
);
foreach($searchFields as $k => $v) $searchFields[$k] = "";

$defaultOrder = "ORDER BY a.umzugstermin";
$q = getRequest("q");
$sendquery = getRequest("sendquery");
$offset = getRequest("offset", 0);
$limit = getRequest("limit", 50);
$ofld = getRequest("ofld", "");
$odir = getRequest("odir", "ASC");
$View = getRequest("View", "Antraege");
$trackGetQuery = "&sendquery=1";

$originOFld = $ofld;
//echo "<pre>".print_r($q,1)."</pre>\n";
//if (is_array($q)) foreach($q as $k => $v) echo "case \"".$k."\":<br>\n";
if ($ofld === 'TicketID') {
    $ofld = 'a.aid';
}
if (in_array($ofld, ['ort', 'PLZ', 'Land', 'Vorname', 'Name'])) {
	$ofld = 'a.' . strtolower($ofld);
} elseif($ofld === 'Liefertermin') {
	$ofld = 'a.umzugstermin';
} elseif($ofld === 'Status') {
	$ofld = 'a.umzugsstatus';
} elseif($ofld === 'kid') {
	$ofld = 'u.personalnr';
}

if ($ofld && isset($searchFields[$ofld])) {
	$orderBy = "ORDER BY ".$ofld." ".($odir!="DESC" ? "ASC" : "DESC");
} else {
	$orderBy = $defaultOrder;
}

foreach($searchFields as $f => $v) {
	if (isset($q[$f])) $searchFields[$f] = $q[$f];
	$queryForm = str_replace("{"."q[$f]"."}", fb_htmlEntities($searchFields[$f]), $queryForm);
	if ($searchFields[$f]) $trackGetQuery.= "&q[$f]=".urlencode($searchFields[$f]);
}
$queryForm = str_replace("chck_View=\"".$View."\"", "checked=\"true\"", $queryForm);
$ListBaseLink = "?s=$s".$trackGetQuery."&View=".urlencode($View);

if ($sendquery) {
	$sqlWhereMa = "";
	$sqlWhereUA = "";
    $sqlWhereUsr = "";
	if (!empty($q)) {
		foreach($searchFields as $qField => $userQuery) {
			if ($userQuery) {
				
				switch($qField) {
					case "a.aid":
					case "a.name":
                    case "a.ort":
                    case "a.plz":
                    case "a.strasse":
                    case "a.land":
                    case "a.vorname":
                    case "a.name":
                    case "a.umzugsstatus":
					case "a.gebaeude":
					case "a.umzugstermin":
					case "a.umzugsstatus":
					$dbField = $qField;
					$sqlWhereUA.= ($sqlWhereUA?"AND ":"")." (";
					$aUQueryParts = userquery_parse($userQuery, "Both");
					$sqlWhereUA.= userquery_parts2sql($aUQueryParts, $dbField);
					$sqlWhereUA.= ")\n";
					break;
					
					case "m.name":
					case "m.vorname":
					case "m.nutzung":
					case "m.extern_firma":
					case "m.ort":
					case "m.gebaeude":
					case "m.etage":
					case "m.raumnr":
					case "m.gf":
					case "m.bereich":
					case "m.abteilung":
					case "m.ziel_ort":
					case "m.ziel_gebaeude":
					case "m.ziel_etage":
					case "m.ziel_raumnr":
					case "m.ziel_gf":
					case "m.ziel_bereich":
					case "m.ziel_abteilung":
					case "m.umzugsart":
					$dbField = ($qField!="m.nutzung" ? $qField : "m.extern");
					$sqlWhereMa.= ($sqlWhereMa?"AND ":"")." (";
					$aUQueryParts = userquery_parse($userQuery, "Both");
					$sqlWhereMa.= userquery_parts2sql($aUQueryParts, $dbField);
					$sqlWhereMa.= ")\n";
					break;

                    case "u.kid":
                    case "u.personalnr":
                        $dbField = "u.personalnr";
                        $sqlWhereUsr.= ($sqlWhereUsr ? "AND " : "") . " (";
                        $aUsrQueryParts = userquery_parse($userQuery, "Both");
                        $sqlWhereUsr.= userquery_parts2sql($aUsrQueryParts, $dbField);
                        $sqlWhereUsr.= ")\n";
                        break;
					
					default:
					$dbField;
				}
				
			}
		}
	}
	
	if ($View == "Antraege") {
		$sql = "Select COUNT(*) count\n";
		$sql.= "FROM `".$_TABLE["umzugsantrag"]."` a LEFT JOIN `".$_TABLE["umzugsmitarbeiter"]."` m USING(aid) \n";

		$sql.= "WHERE 1\n";
		if ($sqlWhereUA) {
		    $sql.= " AND ".$sqlWhereUA."\n";
        }
		if ($sqlWhereMa) {
		    $sql.= "AND a.aid IN(SELECT aid FROM `".$_TABLE["umzugsmitarbeiter"]."` m WHERE $sqlWhereMa)\n";
        }
		$row = $db->query_singlerow($sql);
		$num_all = $row["count"];
		
		$sql = "Select a.aid, a.aid AS \"TicketID\", u.personalnr AS kid, a.vorname AS Vorname, a.name AS Name, a.ort AS Ort, a.plz AS PLZ, a.strasse AS Strasse, "
			. "a.land AS Land, a.umzugstermin AS Liefertermin, a.umzugsstatus AS Status\n";
		$sql.= "FROM `" . $_TABLE["umzugsantrag"] . "` a "
			. "LEFT JOIN `".$_TABLE["user"]."` u ON (a.antragsteller_uid = u.uid) \n"
			. "LEFT JOIN `".$_TABLE["umzugsmitarbeiter"]."` m USING (aid) \n"
		;
		$sql.= "WHERE 1\n";
		if ($sqlWhereUA) {
		    $sql.= " AND ".$sqlWhereUA."\n";
        }
		if ($sqlWhereMa) {
		    $sql.= "AND a.aid IN(SELECT aid FROM `".$_TABLE["umzugsmitarbeiter"]."` m WHERE $sqlWhereMa)\n";
        }
        if ($sqlWhereUsr) {
            $sql.= "AND a.antragsteller_uid IN(SELECT uid FROM `".$_TABLE["user"]."` u WHERE $sqlWhereUsr)\n";
        }
		$sql.= "GROUP BY a.aid\n";
		$sql.= $orderBy."\n";
		$sql.= "LIMIT $offset, $limit";

		$rows = $db->query_rows($sql);
		$num = count($rows);
		// echo "<pre>".print_r($sql,1)."</pre>\n";
	} else {
		$sql = "Select COUNT(*) count\n";
		$sql.= "FROM `".$_TABLE["umzugsmitarbeiter"]."` m LEFT JOIN `".$_TABLE["umzugsantrag"]."` a USING(aid) \n";
		$sql.= "WHERE 1\n";
		if ($sqlWhereMa) {
		    $sql.= " AND ".$sqlWhereMa."\n";
        }
		if ($sqlWhereUA) {
		    $sql.= " AND ".$sqlWhereUA."\n";
        }
		$row = $db->query_singlerow($sql);
		$num_all = $row["count"];
		
		$sql = "Select a.aid, a.umzugstermin, a.umzugsstatus, m.name, m.vorname, m.ort, m.gebaeude, m.etage, m.raumnr, m.ziel_ort, m.ziel_gebaeude, m.ziel_etage, m.ziel_raumnr\n";
		$sql.= "FROM `".$_TABLE["umzugsmitarbeiter"] . "` AS m"
			."` LEFT JOIN `".$_TABLE["umzugsantrag"]."` a USING (aid) \n"
		//	."` LEFT JOIN `".$_TABLE["user"]."` a ON (a.antragsteller_uid = user.uid) \n"
		;
		$sql.= "WHERE 1\n";
		if ($sqlWhereMa) {
		    $sql.= " AND ".$sqlWhereMa."\n";
        }
		if ($sqlWhereUA) {
		    $sql.= " AND ".$sqlWhereUA."\n";
        }
		$sql.= $orderBy."\n";
		$sql.= "LIMIT $offset, $limit";
		
		$rows = $db->query_rows($sql);
		$num = count($rows);
		//echo "<pre>".print_r($rows,1)."</pre>\n";
	}
}
$op.= $queryForm;

if ($sendquery) {
	if (is_array($rows) && count($rows)) {
		$rlist_nav = new listbrowser(array(
			"offset"     => $offset,
			"limit"      => $limit,
			"num_result" => $num,
			"num_all"    => $num_all,
			"baselink"   => $ListBaseLink."&offset={offset}&limit={limit}&ofld=$ofld&odir=$odir"));
		$rlist_nav->render_browser();

        $rows2Tbl = '';
		// $rows2Tbl.= '<pre>' . htmlentities($sql) . '</pre>';
		$rows2Tbl.= $rlist_nav->get_nav("all")."<br>\n";
		//if ($db->error()) 
		//$rows2Tbl.= $db->error()."<br>\nsql:".$sql."<br>\n";
		$wz = "";
		$rows2Tbl.= "<table class=\"tblList\" border=1 cellpadding=1 cellspacing=0>\n";
		$rows2Tbl.= "<thead>\n";
		$rows2Tbl.= "<td>#</td>";
		$rows2Tbl.= "<td colspan=1>Aktion</td>";
		foreach($rows[0] as $fld => $v) {
			if ($fld!="aid") $rows2Tbl.= "<td><a href=\"".$ListBaseLink."&ofld=$fld&odir=".listbrowser::get_oDir($fld, $originOFld, $odir)."\">".$fld."</a></td>";
		}
		$rows2Tbl.= "</thead>\n";
		$rows2Tbl.= "<tbody>\n";
		
		for($i = 0; $i < count($rows); $i++) {
			$wz = ($wz!=1)?1:2;
			$rows2Tbl.= "<tr class=\"wz$wz\">";
			$rows2Tbl.= "<td>".($offset+$i+1)."</td>";
			$rows2Tbl.= "<td><a href=\"?s=aantrag&id=".urlencode($rows[$i]["aid"])."\">Antrag anzeigen</a></td>";
			foreach($rows[$i] as $k => $v) if ($k!="aid") $rows2Tbl.= "<td>$v</td>";
			$rows2Tbl.= "</tr>\n";
		}
		$rows2Tbl.= "</tbody>\n";
		$rows2Tbl.= "</table>";
		$op.= "<br>\n".$rows2Tbl;
	} else {#
		$op.= "Ihre Suche ergab keine Treffer!<br>\n";
		if ($db->error()) {
            $op .= $db->error() . "<br>\nsql:" . $sql . "<br>\n";
        }
	}
}
if (basename($_SERVER["PHP_SELF"])==basename(__FILE__)) {
    echo $op;
}

$body_content.= "<div class=\"divModuleBasic padding6px width5Col heightAuto colorContentMain\"> 
<h1><span class=\"spanTitle\">Auftragssuche:</span></h1> 
<p>
<div id=\"Umzugsantrag\" class=\"divInlay\">\n";
$body_content.= $op;
$body_content.= "</div>\n";
$body_content.= "</div>\n";
?>
