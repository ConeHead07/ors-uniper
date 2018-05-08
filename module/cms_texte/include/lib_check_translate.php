<?php 

function get_clkById($id) {
	global $connid;
	global $editTable;
	global $editTableKey;
	
	$SQL = "SELECT common_lang_key, lang FROM `$editTable` \n";
	$SQL.= "WHERE `$editTableKey` = \"".MyDB::escape_string($id)."\" LIMIT 1";
	$r = MyDB::query($SQL, $connid);
	if ($r) {
		$n = MyDB::num_rows($r);
		
		if ($n) {
			
			$e =  MyDB::fetch_array($r, MyDB::NUM);
			list($clk, $lang) = $e;
			MyDB::free_result($r);
			if (!$clk) {
				
				if (!$lang) $lang = "DE";
				$clk = $lang."-".$id;
				$SQL = "UPDATE `$editTable` SET `common_lang_key` = \"".MyDB::escape_string($clk)."\"";
				if (!$e["lang"]) $SQL.= ",\n lang = \"".MyDB::escape_string($lang)."\"\n";
				$SQL.= " WHERE `$editTableKey` = \"".MyDB::escape_string($id)."\"";
				MyDB::query($SQL, $connid);
			}
			
			return $clk;
		}
		MyDB::free_result($r);
	} else {
		echo "#".__LINE__." ".basename(__FILE__)." ".MyDB::error()."<br>SQL:".$SQL."<br>\n";
	}
	
	return false;
}

function get_translatedItemIdsByCLK($clk, $from_lang) {
	global $connid;
	global $editTable;
	global $editTableKey;
	$aTranslatedIds = array();
	
	$SQL = "SELECT `$editTableKey`, `lang`, `listentitel`, `titel` FROM `$editTable` \n";
	$SQL.= " WHERE `common_lang_key` = \"".MyDB::escape_string($clk)."\"\n";
	if ($from_lang) $SQL.= " AND lang != \"".MyDB::escape_string($from_lang)."\"";
	$r = MyDB::query($SQL, $connid);
	if ($r) {
		$n = MyDB::num_rows($r);
		for ($i = 0; $i < $n; $i++) {
			$aTranslatedIds[] = MyDB::fetch_array($r, MYSQL_ASSOC);
		}
		MyDB::free_result($r);
	}
	return $aTranslatedIds;
}

?>