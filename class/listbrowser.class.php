<?php 
/*
Einfaches Standardbeispiel
$rlist_nav = new listbrowser(array(
	"offset"     => $offset,
	"limit"      => $limit,
	"num_result" => $num,
	"num_all"    => $num_all,
	"baselink"   => "index.php?cmd=admin&task=rliste&rlistmod=list&offset={offset}&limit={limit}"));

$rlist_nav->render_browser();
$listen_ausgabe.= $rlist_nav->get_nav("all")."<br>\n"; 
*/

class listbrowser {
	var $classname = "listbrowser";
	
	function __construct($_ListResults = array()) {
		$this->listRslt["num_all"] = 0;
		$this->listRslt["num_result"] = 0;
		$this->listRslt["offset"] = 0;
		$this->listRslt["limit"] = 0;
		$this->row_von = 0;
		$this->row_bis = 0;
		$this->row_gesamt = 0;
		$this->page = 0;
		$this->page_gesamt = 0;
		
		$this->tplVar = array();
		$this->tplVar["offset"] = "{offset}";
		$this->tplVar["limit"] = "{limit}";
		$this->tplVar["navall_trenner"] = " &nbsp; ";
		$this->tplVar["baselink"] = '&offset='.$this->tplVar['offset'].'&limit='.$this->tplVar['limit'];
		
		$this->nav = array();
		$this->nav["all"] = '';
		$this->nav["start"] = '';
		$this->nav["previous"] = '';
		$this->nav["next"] = '';
		$this->nav["end"] = '';
		
		$this->navTpl = array();
		$this->navTpl["StartText"] = '';
		$this->navTpl["PrevText"]  = '';
		$this->navTpl["NextText"]  = '';
		$this->navTpl["EndeText"]  = '';
		$this->navTpl["AllText"]   = '';
		$this->navTpl["SearchForm"] = '';
		$this->navTpl["defaultStartText"] = "|&lt;";
		$this->navTpl["defaultPrevText"] = "&lt;&lt;";
		$this->navTpl["defaultNextText"] = "&gt;&gt;";
		$this->navTpl["defaultEndeText"] = "&gt;|";
		$this->navTpl["defaultAllText"] = "[Alle anzeigen]";
		
		$this->navTpl["start"]    = " <a href=\"{navLink}\" class=\"lbnav\" title=\"Anfang\">{StartText}</a> ";
		$this->navTpl["previous"] = " <a href=\"{navLink}\" class=\"lbnav\" title=\"Zurueck\">{PrevText}</a> ";
		$this->navTpl["next"]     = " <a href=\"{navLink}\" class=\"lbnav\" title=\"Vor\">{NextText}</a> ";
		$this->navTpl["end"]      = " <a href=\"{navLink}\" class=\"lbnav\" title=\"Ans Ende\">{EndeText}</a> ";
		$this->navTpl["showall"]  = " <a href=\"{navLink}\" class=\"lbnav\" title=\"Alle Einträe anzeigen\">{AllText}</a> ";
		
		if (count($_ListResults)) {
			$this->set_listRslt($_ListResults);
		}
	}
	
	function set_listRslt($_ListResults) {
		reset($_ListResults);
		while(list($k, $v) = each($_ListResults)) {
			switch($k) {
				case "offset":
				$this->set_offset($v);
				break;
				
				case "limit":
				$this->set_limit($v);
				break;
				
				case "num_result":
				$this->set_num_result($v);
				break;
				
				case "num_all":
				$this->set_num_all($v);
				break;
				
				case "baselink":
				$this->set_baselink($v);
				break;
				
				default:
				if (isset($this->listRslt[$k])) {
					$this->listRslt[$k] = $v;
					// echo "#".__LINE__." $k : $v <br>\n";
				}
			}
			
		}
		if (isset($_ListResults["baselink"])) {
			$this->tplVar["baselink"] = $_ListResults["baselink"];
		}
		reset($_ListResults);/**/
	}
	
	function set_offset($offset) {
		$this->listRslt["offset"] = $offset;
	}
	
	function set_limit($limit) {
		$this->listRslt["limit"] = $limit;
	}
	
	function set_num_result($num_result) {
		$this->listRslt["num_result"] = $num_result;
	}
	
	function set_num_all($num_all) {
		$this->listRslt["num_all"] = $num_all;
	}
	
	function set_baselink($link) {
		$this->tplVar["baselink"] = $link;
	}
	
	function set_nav_tpl($nav, $tpl) {
		if (isset($this->navTpl[$nav])) {
			$this->navTpl[$nav] = $tpl;
		}
	}
	
	function set_tpl_var($var, $val) {
		if (isset($this->tplVar[$var])) {
			$this->tplVar[$var] = $val;
		}
	}
	
	static function get_oDir($chckFld, $oFld, $oDir) {
		$dir = ($chckFld != $oFld ? "ASC" : ($oDir != "ASC" ? "ASC" : "DESC"));
		// echo "#".__LINE__." $dir = ".__FUNCTION__."($chckFld, $oFld, $oDir)<br>\n";
		return $dir;
	}
	
	function render_browser() {
		$o = &$this->listRslt["offset"];
		$l = &$this->listRslt["limit"];
		$num = &$this->listRslt["num_result"];
		$numall = &$this->listRslt["num_all"];
		
		if ($this->navTpl["StartText"] === '') $this->navTpl["StartText"] = $this->navTpl["defaultStartText"];
		if ($this->navTpl["PrevText"]  === '') $this->navTpl["PrevText"]  = $this->navTpl["defaultPrevText"];
		if ($this->navTpl["NextText"]  === '') $this->navTpl["NextText"]  = $this->navTpl["defaultNextText"];
		if ($this->navTpl["EndeText"]  === '') $this->navTpl["EndeText"]  = $this->navTpl["defaultEndeText"];
		if ($this->navTpl["AllText"]  === '') $this->navTpl["AllText"]  = $this->navTpl["defaultAllText"];
		// echo "#".__LINE__." ".$this->navTpl["StartText"]." | ".$this->navTpl["defaultStartText"]."<br>\n";
		
		$this->row_von = ($num) ? $o+1 : 0;
		$this->row_bis = ($num) ? $o+$num : 0;
		$this->rows = $num;
		$this->page =  (intval($l) !== 0) ? ceil(($o+1) / intval($l)) : 0;
		$this->pages = (intval($l) !== 0) ? ceil($numall / intval($l)) : 0;
		
		$os = 0;
		$op = ($o - $l > 0) ? $o - $l : 0;
		$on = ($o + $l < $numall) ? $o + $l : $numall - $num; // $numall - ($numall % $l);
		$oe = ($numall - $l > 0) ? $numall - $l : 0;
		// echo "#".__LINE__." num
		
		$linkStart = strtr($this->tplVar["baselink"], array($this->tplVar["offset"] => $os, $this->tplVar["limit"] => $l)); 
		$linkPrevious = strtr($this->tplVar["baselink"], array($this->tplVar["offset"] => $op, $this->tplVar["limit"] => $l)); 
		$linkNext  = strtr($this->tplVar["baselink"], array($this->tplVar["offset"] => $on, $this->tplVar["limit"] => $l)); 
		$linkEnd   = strtr($this->tplVar["baselink"], array($this->tplVar["offset"] => $oe, $this->tplVar["limit"] => $l)); 
		$linkAll   = strtr($this->tplVar["baselink"], array($this->tplVar["offset"] => $os, $this->tplVar["limit"] => $numall)); 
		//echo $this->tplVar["baselink"];
		
		$this->navTpl["start"]    = str_replace("{StartText}", $this->navTpl["StartText"], $this->navTpl["start"]);
		$this->navTpl["start"]    = str_replace("{navLink}", $linkStart, $this->navTpl["start"]);
		$this->navTpl["start"]    = str_replace($this->tplVar["offset"], $os, $this->navTpl["start"]);
		$this->navTpl["start"]    = str_replace($this->tplVar["limit"], $l, $this->navTpl["start"]);
		
		
		$this->navTpl["previous"] = str_replace("{PrevText}", $this->navTpl["PrevText"], $this->navTpl["previous"]);
		$this->navTpl["previous"] = str_replace("{navLink}", $linkPrevious, $this->navTpl["previous"]);
		$this->navTpl["previous"] = str_replace($this->tplVar["offset"], $op, $this->navTpl["previous"]);
		$this->navTpl["previous"] = str_replace($this->tplVar["limit"], $l, $this->navTpl["previous"]);
		
		$this->navTpl["next"]     = str_replace("{NextText}", $this->navTpl["NextText"], $this->navTpl["next"]);
		$this->navTpl["next"]     = str_replace("{navLink}", $linkNext, $this->navTpl["next"]);
		$this->navTpl["next"]     = str_replace($this->tplVar["offset"], $on, $this->navTpl["next"]);
		$this->navTpl["next"]     = str_replace($this->tplVar["limit"], $l, $this->navTpl["next"]);
		
		$this->navTpl["end"]      = str_replace("{EndeText}", $this->navTpl["EndeText"],  $this->navTpl["end"]);
		$this->navTpl["end"]      = str_replace("{navLink}", $linkEnd,  $this->navTpl["end"]);
		$this->navTpl["end"]      = str_replace($this->tplVar["offset"], $oe,  $this->navTpl["end"]);
		$this->navTpl["end"]      = str_replace($this->tplVar["limit"], $l,  $this->navTpl["end"]);
		
		$this->navTpl["showall"]      = str_replace("{AllText}", $this->navTpl["AllText"],  $this->navTpl["showall"]);
		$this->navTpl["showall"]      = str_replace("{navLink}", $linkAll,  $this->navTpl["showall"]);
		$this->navTpl["showall"]      = str_replace($this->tplVar["offset"], $os,  $this->navTpl["showall"]);
		$this->navTpl["showall"]      = str_replace($this->tplVar["limit"], $numall,  $this->navTpl["showall"]);
		
		$this->navTpl["page"]      = $this->page;
		
		$this->navTpl["all"] = '';
		$this->navTpl["all"].= $this->navTpl["start"];
		$this->navTpl["all"].= $this->tplVar["navall_trenner"].$this->navTpl["previous"];
		$this->navTpl["all"].= $this->tplVar["navall_trenner"].$this->navTpl["next"];
		$this->navTpl["all"].= $this->tplVar["navall_trenner"].$this->navTpl["end"];
		$this->navTpl["all"].= $this->tplVar["navall_trenner"].$this->navTpl["showall"];
		$this->navTpl["all"].= "<span class=\"lbnavinfobox\">Einträge: ".$this->row_von." - ".$this->row_bis." // ".$numall." &nbsp; ";
		$this->navTpl["all"].= "Seite: ".$this->page." / ".$this->pages."  </span>";
		
		$this->navTpl["compact"] = "<div class=\"lbnav-compact\">";
		// $this->page / $this->pages
		if ($this->navTpl["SearchForm"]) {
			$this->navTpl["compact"].= "<div class=\"lbnav-form-box\">";
			$this->navTpl["compact"].= $this->navTpl["SearchForm"];
			$this->navTpl["compact"].= "</div>";
		}
		$this->navTpl["compact"].= '';
		$this->navTpl["compact"].= "<div class=\"lbnav-back\">";
		$this->navTpl["compact"].= $this->navTpl["start"];
		if ($this->page > 2) $this->navTpl["compact"].= $this->tplVar["navall_trenner"].$this->navTpl["previous"];
		$this->navTpl["compact"].= "</div>";
		$this->navTpl["compact"].= "<div class=\"lbnav-nav-page\">";
		$this->navTpl["compact"].= "Seite: ".$this->page."  </span>";
		if ($this->page < $this->pages) $this->navTpl["compact"].= $this->tplVar["navall_trenner"].$this->navTpl["next"];
		$this->navTpl["compact"].= "</div>";
		
		$this->navTpl["compact"].= "<div class=\"clear-left\"></div>";
		$this->navTpl["compact"].= "</div>";
	}
	
	function get_nav($nav) {
		if ( isset($this->navTpl[$nav])) {
			return $this->navTpl[$nav];
		}
		return false;
	}/**/
}

?>
