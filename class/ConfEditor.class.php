<?php 

class ConfEditor {
	var $aListTypes = array("A"=>"Assoziatives Array", "N"=>"Numeriertes Array");
	var $sCnfPostVar = "CnfP2";
	var $ConfName = "";
	var $aCnfData = array();
	var $boxNr = -1;
	var $aFormatVarsByFnc = array();
	var $arrConfProperties = array();
	var $aCnfPathAlerts = array();
	var $sCnfForm = "";
	var $saved = false;
	var $formAction = "";
	
	// $aFormatVarsByFnc = array(
	//	"pfad" => array($bDoRekursive , $sFunktion, $sIdOfChildbox)
	// )
	
	function __construct($ConfFile = "", $ConfName = "") {
		global $_POST;
		global $_GET;
		
		// Debug-Vars
		$this->loopnr = 0;
		$this->loopmax=1000;
		
		// Get Conf-File
		$this->ConfName = $ConfName;
		$this->ConfFile = $ConfFile;
		
		if (empty($this->ConfName)) {
			include($this->ConfFile);
			if (!empty($_CONF)) $this->ConfName = key($_CONF);
		}
		echo "#".__LINE__." ".basename(__FILE__)." ".$this->ConfFile."; ".$this->ConfName."<br>\n";
	}
	
	function load_struct($aSruct = array()) {
		if (!empty($aStruct)) $this->arrConfProperties = $aStruct;
		elseif (empty($this->arrConfProperties)) $this->load_properties();
		return (!empty($this->arrConfProperties));
	}
	
	function load_conf($aData = array()) {
		global $db_name;
		global $tbl_prefix;
		global $_TABLE;
		global $MConf;
		
		echo "#".__LINE__." ".basename(__FILE__)."<br>\n";
		if (!empty($aData)) $this->aCnfData = $aData;
		elseif (empty($this->aCnfData)) {
			// Hole Conf-Werte
			echo "#".__LINE__." ".basename(__FILE__)."<br>\n";
			if (isset($_POST[$this->sCnfPostVar])) {
				$this->aCnfData = $_POST[$this->sCnfPostVar];
			} elseif (file_exists($this->ConfFile)) {
				echo "#".__LINE__." ".basename(__FILE__)."<br>\n";
				if (file_exists(str_replace(".inc.php",".fnc.php",$this->ConfFile))) {
					include_once(str_replace(".inc.php",".fnc.php",$this->ConfFile));
				}
				include($this->ConfFile);
				$this->aCnfData = $_CONF[$this->ConfName];	// print_r($this->aCnfData);
			}
		}
		if (empty($this->ConfName) && !empty($this->aCnfData["ConfName"])) $this->ConfName = $this->aCnfData["ConfName"];
		elseif (!empty($this->ConfName) && empty($this->aCnfData["ConfName"])) $this->aCnfData["ConfName"] = $this->ConfName;
	}
	
	function drop_nodes($aDropPaths = array()) {
		if (empty($aDropPaths) && isset($_POST["DropNodes"])) $aDropPaths = &$_POST["DropNodes"];
		if ($aDropPaths) {
			echo "DropNodes!<br>\n";
			//$this->dropNodesByPath($_POST["DropNodes"]);
			foreach($aDropPaths as $k => $r_path) {
				$r_path = substr($r_path, strpos($r_path, "["));
				if ($r_path[0] == "[") {
					echo "Lösche: ".$_POST["DropNodes"][$k]."<br>\n";
					eval("if (isset(\$this->aCnfData{$r_path})) unset(\$this->aCnfData{$r_path});");
				}
			}
		}
	}
	
	function add_nodes($aAddNodes = array()) {
		if (empty($aAddNodes) && isset($_POST["AddNodes"])) $aAddNodes = &$_POST["AddNodes"];
		if ($aAddNodes) {
			if (isset($aAddNodes["A"])) {
				foreach($aAddNodes["A"] as $k => $v) {
					if (!trim($v)) continue;
					$k = urldecode($k);
					$v = stripslashes($v);
					// echo "F�ge {$t}-Element '".fb_htmlEntities($v)."' zu: ".$k."<br>\n";
					$r_path = substr($k, strpos($k, "["));
					$evalCode = "if (isset(\$this->aCnfData{$r_path}) && !isset(\$this->aCnfData{$r_path}[\"".addslashes($v)."\"])) \$this->aCnfData{$r_path}[\"".addslashes($v)."\"] = array(1);";
					// echo fb_htmlEntities($evalCode)."<br>\n";
					eval("if (isset(\$this->aCnfData{$r_path}) && !isset(\$this->aCnfData{$r_path}[\"".addslashes($v)."\"])) \$this->aCnfData{$r_path}[\"".addslashes($v)."\"] = array(1);");
				}
			}
			if (isset($aAddNodes["N"])) {
				foreach($aAddNodes["N"] as $k => $v) {
					$k = urldecode($k);
					// echo "F�ge {$t}-Element  zu: ".$k."<br>\n";
					$r_path = substr($k, strpos($k, "["));
					eval("if (isset(\$this->aCnfData{$r_path})) array_push(\$this->aCnfData{$r_path}, array(1));");
				}
			}
		}
	}
	
	function chg_pos($aChgPos = array()) {
		if (empty($aChgPos) && isset($_POST["ChgPos"])) $aChgPos = &$_POST["ChgPos"];
		if ($aChgPos) {
			$aCP = $aChgPos;
			$NewOrder = false;
			$aNewOrderLists = array();
			foreach($aCP as $pfad => $aOldToNewPos) {
				
				$pfad = urldecode($pfad);
				$pfad = substr($pfad, strpos($pfad, "["));
				list($oldp, $newp) = each($aOldToNewPos);
				$aOrderInput[$this->get_parent_path($pfad)][$this->get_last_pathkey($pfad)] = $newp;
				if ($oldp != $newp) {
					if (!isset($aNewOrderLists[$this->get_parent_path($pfad)])) {
						$aNewOrderLists[$this->get_parent_path($pfad)] = &$aOrderInput[$this->get_parent_path($pfad)];
					}
					$NewOrder = true;
				}
			}
			
			if ($NewOrder == true) {
				echo "Starte Neusortierung!<br>\n";
				$aTmpSort = array();
				foreach($aNewOrderLists as $k => $v) {
					asort($aNewOrderLists[$k]);
					foreach($aNewOrderLists[$k] as $k2 => $v2) {
						$evalCode = "\$aTmpSort[$k2] = \$this->aCnfData{$k}[$k2];";
						eval($evalCode);
					}
					eval("\$this->aCnfData{$k} = \$aTmpSort;");
				}
			} else {
				echo "Keine Neusortierung erforderlich!<br>\n";
			}
			//echo "#".__LINE__." ChgPos: <pre>".print_r($_POST["ChgPos"], true)."</pre>\n";
		}
	}
	
	function get_form($formAction = "") {
		if (!$formAction) {
			if ($this->formAction) $formAction = $this->formAction;
			else $formAction = ($_SERVER["QUERY_STRING"]) ? "?".$_SERVER["QUERY_STRING"] : "";
		}
		if (!$formAction) $formAction=basename($_SERVER["PHP_SELF"]);
		// Generiere Formular
		return "<form action=\"$formAction\" method=post>
		".$this->get_conf_form($this->arrConfProperties, $this->aCnfData, "")."
		<input type=submit name=\"PREVIEW\" value=\"Vorschau\">
		<input type=submit name=\"SAVE\" value=\"Speichern\">
		</form>
		<form action=$formAction method=post><input type=submit name=\"RESET\" value=\"Letzte gespeicherte Version laden\"></form>";
	}
	
	function autorun() {
		$this->load_struct();
		$this->load_conf();
		
		if (isset($_POST["DropNodes"])) {
			$this->drop_nodes();
		}
		
		if (isset($_POST["AddNodes"])) {
			$this->add_nodes();
		}
		
		if (isset($_POST["ChgPos"])) {
			$this->chg_pos();
		}
		
		if (isset($_POST["SAVE"])) {
			if (!file_exists($this->ConfFile)) die("#".__LINE__." ".__FILE__." ConfFile:".$this->ConfFile." kann nicht geladen werden!<br>\n");
			$this->write_conf();
			echo "<strong>Änderungen wurden gespeichert!</strong><br>\n";
		}
		
		// Generiere Formular
		$this->sCnfForm = $this->get_form();
	}
	
	function get_parent_path($path) {
		return implode("[", array_slice(explode("[", $path), 0, -1));
	}
	
	function get_last_pathkey($path) {
		if (substr($path, -1) == "]") {
			$a = explode("[", substr($path, 0, -1));
			return array_pop($a);
		}
		return false;
	}
	
	function strip_post_data(&$D) {
		foreach($D as $k => $v) {
			if (is_array($v)) $this->strip_post_data($D[$k]);
			else $D[$k] = stripslashes($v);
		}
	}
	
	function get_conf_codevar($k, $pfad, $dval, $cnf = array("text","",""), $isListItem = false) {
		$k = trim($k);
		$VarIs = (empty($k) && intval($k) == $k) ? "$k => " : "\"$k\" => ";
		switch($cnf[1]) {
			
			case "str":
			case "txt":
			return $VarIs.= "\"".str_replace('"', '\\"', $dval)."\"";
			break;
			
			case "int":
			return $VarIs.= (strlen($dval)) ? intval($dval) : "null";
			break;
			
			case "bln":
			return $VarIs.= ($dval) ? "true" : "false";
		}
	}
	
	function get_conf_input($dlabel, $pfad, $dval, $cnf = array("text","",""), $isListItem = false) {
		
		if ($isListItem) {
			$form = "\t<tr><td>";
			$listProps = explode(":", $isListItem);
			$form.= "<input type=\"checkbox\" name=\"DropNodes[]\" value=\"".$pfad."\"> ";
			$form.= "<input type=\"text\" name=\"ChgPos[".urlencode($pfad)."][".$listProps[1]."]\" value=\"".$listProps[1]."\" style=\"width:25px;\" size=\"3\">";
		} else {
			$form = "\t<tr><td width=100>";
		}
		$form.= $this->get_last_pathkey($pfad)."</td><td>";
		switch($cnf[0]) {
			case "text":
			if ($cnf[1] !== "txt") {
				$form.= "<input LINE=\"".__LINE__."\" LINE=\"".__LINE__."\" 
				name=\"".$this->sCnfPostVar."$pfad\" class=\"itext\" type=\"text\"  
				value=\"".(!is_null($dval)?fb_htmlEntities(stripslashes($dval)):'')."\">";
			} else {
				$form.= "<textarea class=\"itxarea\" name=\"".$this->sCnfPostVar."$pfad\">".(!is_null($dval)?fb_htmlEntities(stripslashes($dval)):'')."</textarea>\n";
			}
			break;
			
			case "select":
			case "radio":
			case "checkbox":
			if ($cnf[1] == "bln") {
				$checked = (!is_null($dval) && $dval);
				$form.= "<input LINE=\"".__LINE__."\" name=\"".$this->sCnfPostVar."$pfad\" class=\"ichck\" type=\"checkbox\" value=\"true\" ".(!$checked?'':"checked='true'").">";
			} elseif (count(explode(",", $cnf[2])) == 1) {
				$checked = (!is_null($dval) && $dval == $cnf[2]);
				$form.= "<input LINE=\"".__LINE__."\" name=\"".$this->sCnfPostVar."$pfad\" class=\"ichck\" type=\"checkbox\" value=\"".$cnf[2]."\" ".(!$checked?'':"checked='true'").">";
			} else {
				// $this->Msg.= "#".__LINE__." count(explode(',', {$cnf[2]}):".count(explode(",", $cnf[2]))."<br>\n";
				$opt = explode(",", $cnf[2]);
				if ($cnf[0] == "select") {
					$form.= "<select class=\"ichck iselect\" name=\"".$this->sCnfPostVar."$pfad\">";
				}
				for ($i = 0; $i < count($opt); $i++) {
					if (substr($opt[$i],0,1) == "'") $opt[$i] = substr($opt[$i], 1, -1);
					$checked = (!is_null($dval) && $opt[$i] == $dval);
					if ($cnf[0] == "select") {
						$form.= "<option value=\"".fb_htmlEntities($opt[$i])."\"".(!$checked?'':"selected='true'").">";
						$form.= $opt[$i]."</option>\n";
					} else {
						$form.= "<input LINE=\"".__LINE__."\" name=\"".$this->sCnfPostVar."$pfad\" class=\"ichck\" type=\"".$cnf[0]."\" value=\"".fb_htmlEntities($opt[$i])."\"".(!$checked?'':"checked='true'").">";
						$form.= $opt[$i]." ";
					}
				}
				if ($cnf[0] == "select") {
					$form.= "</select>\n";
				}
			}
			break;
		}
		$form.= "</td></tr>\n";
		return $form;
	}
	
	function isStructurInfo($v) {
		if (is_array($v)) {
			if (count($v) == 3
				&& isset($v[0]) && is_scalar($v[0])
				&& isset($v[1]) && is_scalar($v[1])
				&& isset($v[2]) && is_scalar($v[2])
			) {
				return true;
			}
		}
		return false;
	}
	
	function isList($k) {
		return (strlen($k)>3 && $k[0].$k[2].substr($k,-1) == "{:}" && isset($this->aListTypes[$k[1]])) ? $k[1] : false;
	}
	
	function pfad2Label($pfad) {
		if (!is_int(strpos($pfad, "["))) return $this->sCnfPostVar;
		$aT = explode("][", substr($pfad, strpos($pfad, "[")+1, -1));
		return array_pop($aT);
	}
	
	function get_propertie_vars($cp, $pfad) {
		if (++$this->loopnr > $this->loopmax) die("#".__LINE__." Loop:".$this->loopnr.", cp:".gettype($cp).", pfad:$pfad<br>\n");
		$sCnfVars = "";
		foreach($cp as $k => $v) {
			if (is_array($v)) {
				$isStruct = $this->isStructurInfo($v);
				$isList = $this->isList($k);
				if ($isStruct) {
					$sCnfVars.= $pfad."[$k] = array(\"".implode("\", \"",$v)."\");<br>\n";
				} else {
					$sCnfVars.= $this->get_propertie_vars($v, $pfad."[$k]");
				}
			} else {
				echo "<div style=\"color:#f00;\">".$pfad."[$k] = ERROR! Ungültige Struktur. Erwarte Array! v:$v"."</div>\n";
				$sCnfVars.= $pfad."[$k] = ERROR! Ungültige Struktur. Erwarte Array! v:$v<br>\n";
			}
		}
		return $sCnfVars;
	}
	
	function get_conf_code($c, $d, $pfad, $isListItem = false, $deep = 0) {
		if (++$this->loopnr > $this->loopmax) die("#".__LINE__." Loop:".$this->loopnr.", cp:".gettype($cp).", pfad:$pfad<br>\n");
		
		$sCnfForm = "";
		$TAB = "";
		$TAB2 = "";
		for($i = 0; $i < $deep; $i++) $TAB.= "\t";
		if ($deep) {
			$sCnfForm.= $TAB."\"".$this->pfad2Label($pfad)."\" => array(";
		} else {
			$sCnfForm.= $TAB.$pfad." = array(";
		}
		$TAB2.= $TAB."\t";
		$loop1 = 0;
		foreach($c as $k => $v) {
			if (is_array($v)) {
				$sCnfForm.= ($loop1 ? ",":"")."\n";
				$isStruct = $this->isStructurInfo($v);
				$isList = $this->isList($k);
				if ($isStruct && !$isList) {
					$sCnfForm.= $TAB2.$this->get_conf_codevar($k, $pfad."[$k]", (isset($d[$k])?$d[$k]:""), $v);
				} else {
					if ($isList) {
						if (is_array($d)) {
							$loop2 = 0;
							foreach($d as $dk => $dv) {
								if ($loop2) $sCnfForm.= ",\n";
								if (!$isStruct) {
									// SubArray
									$sCnfForm.= $this->get_conf_code($c[$k], $dv, $pfad."[$dk]", true, $deep+1);
								} else {
									// Skalar
									$sCnfForm.= $TAB2.$this->get_conf_codevar($dk, $pfad."[$dk]", $dv, $v, true);
								}
								$loop2++;
							}
						} else {
							// echo "#".__LINE__." ".__FILE__." Is Not A Array $pfad: $d<br>\n";
						}
					} else {
						// SubArray
						$sCnfForm.= $this->get_conf_code(
								$c[$k], 
								(isset($d[$k])?$d[$k]:false), 
								$pfad."[$k]", 
								false, 
								$deep+1);
					}
				}
				$loop1++;
			}
		}
		$sCnfForm.= "\n".$TAB.")";
		return $sCnfForm;
	}
	
	function write_conf($AsConfName = "", $AsConfFile = "") {
		$existingCode = (file_exists($this->ConfFile)) ? file_get_contents($this->ConfFile) : "";
		$ndlA = "// START BASE CONF";
		$ndlZ = "// ENDE BASE CONF";
		$pA = strpos($existingCode, $ndlA);
		$pZ = strpos($existingCode, $ndlZ);
		$writeMode = ($existingCode && is_int($pA) && is_int($pZ) && $pA < $pZ) ? "UPDATE" : "INSERT";
		
		if (!$AsConfName) $AsConfName = $this->ConfName;
		if (!$AsConfFile) $AsConfFile = $this->ConfFile;
		$CVAR_NAME = "\$_CONF[\"".($AsConfName?$AsConfName:$this->ConfName)."\"]";
		$this->aCnfData["ConfName"] = $AsConfName;
		$sCnfCode = $this->get_conf_code($this->arrConfProperties, $this->aCnfData, $CVAR_NAME, $isListItem = false);
		if ($writeMode == "INSERT") {
			file_put_contents(
				$AsConfFile,
				"<?php\n".$ndlA."\n".$sCnfCode.";\n".$ndlZ."\n?>"
			);
			$this->saved = true;
		} else {
			file_put_contents(
				$AsConfFile,
				set_cms($existingCode, $ndlA, $ndlZ, "\n".$sCnfCode.";\n")
			);
			$this->saved = true;
		}
	}
	
	function get_conf_form($c, $d, $pfad, $isListItem = false) {
		if (++$this->loopnr > $this->loopmax) die("#".__LINE__." Loop:".$this->loopnr.", cp:".gettype($cp).", pfad:$pfad<br>\n");
		
		$boxId = "CNFBX".$this->boxNr++;
		$sCnfForm = "";
		// $aFormatVarsByFnc = array(
		//	"pfad" => array($sFunktion, $sIdOfChildbox)
		// )
		if (isset($aFormatVarsByFnc[$pfad])) {
			$sUserFormat = call_user_func($aFormatVarsByFnc[$pfad], $c, $d, $pfad, $isListItem, $boxId);
			if ($sUserFormat) return $sUserFormat;
		}
		$sCnfForm.= "<div>";
		if ($isListItem) {
			$listProps = explode(":", $isListItem);
			$sCnfForm.= "<input type=\"checkbox\" name=\"DropNodes[]\" value=\"".$pfad."\"> ";
			$sCnfForm.= "<input type=\"text\" name=\"ChgPos[".urlencode($pfad)."][".$listProps[1]."]\" value=\"".$listProps[1]."\" style=\"width:25px;\" size=\"3\">";
		}
		$sCnfForm.= "<span onclick=ChgD('$boxId')><strong>".$this->pfad2Label($pfad)."</strong></span>";
		
		if (isset($this->aCnfPathAlerts[$pfad])) {
			$sCnfForm.= $this->aCnfPathAlerts[$pfad];
		}
		$sCnfForm.= "</div>\n";
		$sCnfForm.= "<div id={$boxId} style=\"display:".($this->boxNr?"none":"normal").";\">\n";
		$sCnfForm.= "<table class=\"CnfTbl\">\n";
		foreach($c as $k => $v) {
			if (is_array($v)) {
				$isStruct = $this->isStructurInfo($v);
				$isList = $this->isList($k);
				if ($isStruct && !$isList) {
					$sCnfForm.= $this->get_conf_input($k, $pfad."[$k]", (isset($d[$k])?$d[$k]:""), $v);
				} else {
					if ($isList) {
						if (is_array($d)) {
							$nr = 0;
							$num= count($d);
							foreach($d as $dk => $dv) {
								if (!$isStruct) {
									$sCnfForm.= "\t<tr><td colspan=2>".$this->get_conf_form($c[$k], $dv, $pfad."[$dk]", "$isList:$nr:$num")."</td></tr>\n";
								} else {
									$sCnfForm.= $this->get_conf_input($k, $pfad."[$dk]", $dv, $v, "$isList:$nr:$num");
								}
								$nr++;
							}
							$sCnfForm.= "\t<tr><td colspan=2>[x] Markierte Elemente werden entfernt!</td></tr>\n";
						} else {
							// echo "#".__LINE__." ".__FILE__." Is Not A Array $pfad: $d<br>\n";
						}
						if ($isList == "A") {
							$sCnfForm.= "\t<tr><td colspan=2><input type=\"text\" name=\"AddNodes[$isList][".urlencode($pfad)."]\" value=\"\">Element hinzufügen zu $pfad</td></tr>\n";
						} else {
							$sCnfForm.= "\t<tr><td colspan=2><input type=\"checkbox\" name=\"AddNodes[$isList][".urlencode($pfad)."]\" value=\"1\">Element hinzufügen zu $pfad</td></tr>\n";
						}
					} else {
						$sCnfForm.= "\t<tr><td colspan=2>".$this->get_conf_form($c[$k], (isset($d[$k])?$d[$k]:false), $pfad."[$k]")."</td></tr>\n";
					}
				}
			}
		}
		$sCnfForm.= "</table>\n";
		$sCnfForm.= "</div>\n";
		return $sCnfForm;
	}
	
	function load_properties() 
	{
		$this->arrConfProperties = array(
			"ConfName" => array("text", "str", 50),
			"Title" => array("text", "str", 50),
			"Description" => array("text", "txt", ""),
			"Src"   => array("select", "str", ",MYSQL"),//,SQLLITE,FILE
			"Db"    => array("text", "str", 50),
			"Table" => array("text", "str", 50),
			"PrimaryKey" => array("text", "str", 50),
			"readMinAccess"   => array("radio", "int", "0,1,2,3,4,5,6"),
			"insertMinAccess" => array("radio", "int", "0,1,2,3,4,5,6"),
			"updateMinAccess" => array("radio", "int", "0,1,2,3,4,5,6"),
			"deleteMinAccess" => array("radio", "int", "0,1,2,3,4,5,6"),
			"FormInput" => array("text", "str", 100),
			"FormPreview" => array("text", "str", 100),
			"FormRead" => array("text", "str", 100),
			"Fields" => array(
				"{A:Field}" => array(
					"dbField" => array("text", "str", 50),
					"key"     => array("checkbox", "str", "PRI"),
					"label"   => array("text", "str", "60"),
					"listlabel" => array("text", "str", "40"),
					"fieldPos" => array("text", "int", "10"),
					"fieldGroup" => array("text", "str", "250"),
					"description" => array("text", "txt", ""),
					"type" => array("select", "str", "char,date,datetime,enum,double,float,int,set,text,time,varchar"),
					"size" => array("text", "str", "250"),
					"sql" => array("text", "txt", ""),
					"sysType"  => array("select", "str", "char,double,enum,float,created,createdby,createduid,email,int,modified,modifiedby,modifieduid,password,set,text,varchar"),
					"htmlType" => array("select", "str", "text,textarea,radio,checkbox,select single,select multiple,system,hidden"),
					"default"  => array("text", "str", "250"),
					"required" => array("checkbox", "bln", "false,true"),
					"null"   => array("checkbox", "bln", "false,true"),
					"unique" => array("checkbox", "bln", "false,true"),
					"min" => array("text", "int", "10"),
					"max" => array("text", "int", "10"),
					"inputAttribute" => array("text", "txt", ""),
					"readAttribute" => array("text", "txt", ""),
					"createByFunction" => array("text", "str", "50"),
					"checkByFunction"  => array("text", "str", "50"),
					"formatEingabeFunction" => array("text", "str", "50"),
					"formatLesenFunction"   => array("text", "str", "50"),
					"editByRuntime" => array("checkbox", "bln", "false,true"),
					"readMinAccess"   => array("radio", "int", "0,1,2,3,4,5,6"),
					"insertMinAccess" => array("radio", "int", "0,1,2,3,4,5,6"),
					"updateMinAccess" => array("radio", "int", "0,1,2,3,4,5,6"),
					"deleteMinAccess" => array("radio", "int", "0,1,2,3,4,5,6")
				)
			),
			"Joins" => array(
				"{A:FIELD}" => array(
					"name"    => array("text", "str", "50"),  // "Zeiterfassungen",
					"confkey" => array("text", "str", "50"),  // "p_entries",
					"rel"     => array("radio", "str", "OneToOne,OneToMany,ManyToOne,ManyToMany"),  // "ManyToOne",
					"leadingTblConfName" => array("text", "str", "50"),  // "",
					"key"     => array("text", "str", "50"),  // "pid", 
					"foreignTbl"    => array("text", "str", "50"),  // $_TABLE["p_entries"], 
					"foreignKey"    => array("text", "str", "50"),  // "pid",
					"dependent"     => array("checkbox", "bln", "true,false"),  //  false,
					"listAutoJoin"  => array("checkbox", "bln", "true,false"),  // true,
					"listFields"    => array("text", "txt", ""),  // "Mitarbeiter", 
					"listHideKey"   => array("text", "txt", ""), 
					"listHideFlds"  => array("text", "txt", ""),
					"listPosition"  => array("text", "str", "10")
				)
			),
			"Lists" => array(
				"{N:}" => array(
					"ListRenderMode"=> array("radio", "str", "Auto,Function,Template"), // String: Auto | Function | Template
					"ListFunction"  => array("text", "str", "50"),  // String Name Of Function
					"ListTemplate"  => array("text", "str", "50"),  // String Template
					"name"   => array("text", "str", "50"),
					"select" => array("text", "txt", ""),
					"from"   => array("text", "str", "50"),
					"join"   => array("text", "str", "50"),
					"where"  => array("text", "txt", ""),
					"group"  => array("text", "str", "50"),
					"having" => array("text", "str", "50"),
					"defaultOrderFld" => array("text", "str", "50"),
					"defaultOrderDir" => array("radio", "str", "ASC,DESC"),
					"setDefaultButtons" => array("radio", "str", "auto,open,kill"), // auto | open | kill
					
					"addButtons" => array(
						"{A:BUTTON}"  => array(
							"label" => array("text", "str", "50"),
							"link"  => array("text", "str", "50"),
							"tplVars" => array("text", "str", "50"), // Angabe zu ersetzender FeldVariablen
							"innerHTML"  => array("text", "txt", ""),
							"beforeHTML" => array("text", "txt", ""),
							"behindHTML" => array("text", "txt", ""),
							"events" => array(
								"{A:JSEVENT}" => array(
									"fnc" => array("text", "str", "50"),
									"flds"=> array("text", "str", "50")
								)
							)
						)
					),
					"addFormFields"   => array(
						"{A:NAME}" => array(
							"label"   => array("text", "str", "50"),
							"type"    => array("text", "str", "50"), //"text",
							"inputName" => array("text", "str", "50"), //"iOne",
							"inputId" => array("text", "str", "50"), //"iOneId",
							"valueByDbFld" => array("text", "str", "50"), //"",
							"default" => array("text", "str", "50"), //"testDefault",
							"defChck" => array("text", "str", "50"), //
							"options" => array("text", "str", "50")
						)
					),
					
					"addColumnHandler" => array(
						// "cache_warenkorb" => "showWkOnMouseOver",
						"{A:COLUMNNAME}" => array("text", "str", "50")
					)
				)
			)
		);
	}
}

if (basename(__FILE__) == basename($_SERVER["PHP_SELF"])) {
	echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html>'."\n";
	echo '<head><title>Untitled</title>'."\n";
	echo '<script src="../js/GetObjectDisplay.js" type="text/javascript"></script>'."\n";
	echo '<style>'."\n";
	echo '.CnfTbl { width: 100%; margin-left:5px; }'."\n";
	echo '</style>'."\n";
	echo '</head>'."\n";
	echo '<body>'."\n";
	$CE = new ConfEditor("dummie", dirname(__FILE__)."/../include/dummie.inc.php");
	$CE->autorun();
	echo $CE->sCnfForm;
	echo "</body></html>";
}
?>
