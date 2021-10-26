<?php 
include_once(dirname(__FILE__)."/ConfEditor.class.php");

class formcreator {
	var $classname = "formcreator";
	var $db_conn_id = "";
	var $db_connected = false;
	var $db_tblconf = array();
	var $arrConf = array();
	var $arrConfProperties = array();
	var $confName = "";
	var $dirBase = "";
	var $inclDir = "";
	var $tplDir = "";
	var $Error = "";
	var $Msg = "";
	
	// public
	function __construct($confName, $includeDir = "./include", $templateDir = "./html") 
	//function __construct($confName, $dirBase = "{confName}", $inclDir = "/lib", $tplDir = "/html") 
	{
		$this->confName = $confName;
		$this->inclDir  = $includeDir;
		$this->tplDir   = $templateDir;
	}
	
	// public
	function db_connect($host, $user, $pass) 
	{
		echo "#".__LINE__." ".basename(__FILE__)."<br>\n";
		$this->db_connid = MyDB::connect($host, $user, $pass);
		if ($this->db_connid) {
			$this->db_connected = true;
			// $this->Msg.= "#".__LINE__." ".basename(__FILE__)." db->db_connid: $this->db_connid <br>\n";
		} else {
			$this->Error.= "#".__LINE__." ".basename(__FILE__)." Mysql-Verbindung schlug fehl!<br>\n";
			//echo "#".__LINE__." ".MyDB::error();
		}
		
	}
	
	// public
	function db_close() 
	{
		if ($this->db_connected) {
			//MyDB::close($this->db_connid);
			$this->db_connected = false;
		}
	}
	
	// public
	function load_tblconf_from_db($db, $tbl, $refresh = false) 
	{
		if (!isset($this->db_tblconf[$tbl]) || $refresh == true) {
			$this->db_tblconf[$tbl] = array();
			$SQL = "SHOW FIELDS FROM " . (!empty($db) ? "`$db`." : '' ) . "`$tbl`";
			$r = $this->db_query($SQL, __LINE__);
			if ($r) {
				$n = MyDB::num_rows($r);
				for($i  = 0; $i < $n; $i++) {
					$_e = MyDB::fetch_array($r, MYSQL_ASSOC);
					$this->db_tblconf[$tbl][$_e[key($_e)]] = $_e;
				}
				$this->db_free_result($r);
				return true;
			}
		}
		return (isset($this->db_tblconf[$tbl]) && key($this->db_tblconf[$tbl]));
	}
	
	// public
	function create_default_conf_fromdb($dbName, $tbl, $refresh = false)
	{
		$isLoaded = $this->load_tblconf_from_db($dbName, $tbl, $refresh);
		$aAppendFlds = array();
		
		if ($isLoaded || isset($this->db_tblconf[$tbl])) {
			if (!$this->arrConf) {
				$this->arrConf = array(			
					"Title" => ucfirst($this->confName),
					"Db" => $dbName,
					"Table" => $tbl,
					"readMinAccess" => 0,	// 0:Normal Webuser, 1:Regist Webuser, 2:Systemuser, 3:Systemuser, 4:Systeuser, 5:Admin
					"insertMinAccess" => 2, // 
					"editMinAccess" => 3,	// 
					"delMinAccess"  => 3,
					"Fields" => array()
				);
				$writeMode = "Insert";
			} else {
				$writeMode = "Append";
				
				foreach($this->aChanges as $k => $v) {
					if ($v["FeldStatus"] == "NEW") $aAppendFlds[] = $k;
				}
			}
			
			$fi = 0;
			foreach($this->db_tblconf[$tbl] as $k => $v) {
				if ($writeMode == "Append" && !in_array($k, $aAppendFlds)) continue;
				
				$this->arrConf["Fields"][$k] = array(
					"dbField" => $k,
					"key" => $v["Key"],
					"fieldGroup"=> "main",
					"fieldPos"=> ++$fi,
					"label" => ucfirst($k),
					"listlabel" => ucfirst($k),
					"type" => $v["Type"],
					"default" => $v["Default"],
					"required"=> (strtoupper($v["Null"]) != "YES"),
					"null"=> (strtoupper($v["Null"]) == "YES"),
					"editByRuntime" => false,
					"readMinAccess" => 0,
					"editMinAccess" => 3
				);
				
				$p = strpos($v["Type"], "(");
				$type = (!$p) ? $v["Type"] : substr($v["Type"], 0, $p); 
				
				if (is_int($p)) {
					$this->arrConf["Fields"][$k]["type"] = substr($v["Type"], 0, $p);
					$this->arrConf["Fields"][$k]["size"] = substr($v["Type"], $p+1, -1);
				}
				
				if (strtoupper($v["Key"]) != "PRI") {
					$this->arrConf["Fields"][$k]["sysType"] = $this->get_baseType($type);
				} else {
					$this->arrConf["PrimaryKey"] = $k;
					$this->arrConf["Fields"][$k]["sysType"] = "key";
				}
				
				// Default: SysType
				$this->arrConf["Fields"][$k]["sysType"] = $this->get_baseType($type);
				
				switch($k) {
					case "created":
					case "erstelltam":
					case "erstellt_am":
					if (preg_match("/date/", $type)) {
						$this->arrConf["Fields"][$k]["sysType"] = "created";
						$this->arrConf["Fields"][$k]["default"] = "";
						$this->arrConf["Fields"][$k]["required"] = false;
					}
					break;
					
					case "modified":
					case "bearbeitetam":
					case "bearbeitet_am":
					if (preg_match("/date/", $type)) {
						$this->arrConf["Fields"][$k]["sysType"] = "modified";
						$this->arrConf["Fields"][$k]["default"] = "";
						$this->arrConf["Fields"][$k]["required"] = false;
					}
					break;
					
					case "createdby":
					case "created_by":
					case "erstelltvon":
					case "erstellt_von":
					$this->arrConf["Fields"][$k]["sysType"] = "createdby";
					$this->arrConf["Fields"][$k]["default"] = "";
					$this->arrConf["Fields"][$k]["required"] = false;
					break;
					
					case "createduid":
					case "created_uid":
					$this->arrConf["Fields"][$k]["sysType"] = "createduid";
					break;
					
					case "modifiedby":
					case "modified_by":
					case "bearbeitetvon":
					case "bearbeitet_von":
					$this->arrConf["Fields"][$k]["sysType"] = "modifiedby";
					$this->arrConf["Fields"][$k]["default"] = "";
					$this->arrConf["Fields"][$k]["required"] = false;
					break;
					
					case "modifieduid":
					case "modified_uid":
					$this->arrConf["Fields"][$k]["sysType"] = "modifieduid";
					break;
					
					case "email":
					if (preg_match("/char|text/", $type)) {
						$this->arrConf["Fields"][$k]["sysType"] = "email";
						$this->arrConf["Fields"][$k]["default"] = "";
					}
					break;
					
					case "pw":
					case "pass":
					case "password":
					case "passwort":
					$this->arrConf["Fields"][$k]["sysType"] = "password";
					$this->arrConf["Fields"][$k]["default"] = "init";
					break;
				}
				
				switch(strtolower($this->arrConf["Fields"][$k]["sysType"])) {
					case "set":
					$c = count(explode(",", $this->arrConf["Fields"][$k]["size"]));
					if ($c < 5) {
						$this->arrConf["Fields"][$k]["htmlType"] = "checkbox";
					} else {
						$this->arrConf["Fields"][$k]["htmlType"] = "select multiple";
					}
					break;
					
					case "enum":
					$c = count(explode(",", $v["Type"]));
					if ($c < 5) {
						$this->arrConf["Fields"][$k]["htmlType"] = "radio";
					} else {
						$this->arrConf["Fields"][$k]["htmlType"] = "select single";
					}
					break;
					
					case "text":
					case "blob":
					$this->arrConf["Fields"][$k]["htmlType"] = "textarea";
					break;
					
					default:
					$this->arrConf["Fields"][$k]["htmlType"] = "text";
				}
			}
		}
	}
	
	function compare_tbl_and_conf() {
		if (!$this->db_tblconf) {
			// print_r($this->arrConf);
			$this->load_tblconf_from_db($this->arrConf["Db"], $this->arrConf["Table"]);
		}
		$this->aChanges = array();
		$aFld2Cnf = array();
		$tbl = $this->arrConf["Table"];
		
		foreach($this->arrConf["Fields"] as $fN => $fC) {
			$dbFld = $fC["dbField"];
			if ($dbFld) {
				$aFld2Cnf[$dbFld] = $fN;
				if (!isset($this->db_tblconf[$tbl][$dbFld])) {
					$this->aChanges[$fN]["FeldStatus"] = "DROPPED";
				}
			}
		}
		
		$XXi = 0;
		foreach($this->db_tblconf[$tbl] as $dbFld =>$dbFC) {
			if (!isset($aFld2Cnf[$dbFld])) $this->aChanges[$dbFld]["FeldStatus"] = "NEW";
			else {
				$fN = $aFld2Cnf[$dbFld];
				$fC = &$this->arrConf["Fields"][$fN];
				$fC["null"] = !empty($fC["null"]);
				$fC["key"] = !empty($fC["key"]);
				$fC["unique"] = !empty($fC["unique"]);
				
				if ($dbFC["Type"] != $fC["type"] && $dbFC["Type"] != $fC["type"]."(".$fC["size"].")") {
					$this->aChanges[$fN]["type"] = "DB: ".$dbFC["Type"]." != CONF: ".$fC["type"]."(".$fC["size"].")";
				}
				if (($dbFC["Null"] == "YES") != $fC["null"]) {
					$this->aChanges[$fN]["null"] = "DB: ".$dbFC["Null"]." != CONF: ".($fC["null"]?"true":"false");
				}
				$dbFC_Key_Pri = ($dbFC["Key"] == "PRI") ? $dbFC["Key"] : "";
				$dbFC_Key_Uni = ($dbFC["Key"] == "UNI") ? $dbFC["Key"] : "";
				
				$fC_Key_Pri = ($fC["key"] == "PRI") ? $fC["key"] : "";
				$fC_Key_Uni = (isset($fC["unique"]) && $fC["unique"] == "UNI") ? "UNI" : "";
				
				if ($dbFC_Key_Pri != $fC_Key_Pri) {
					$this->aChanges[$fN]["key"] = "DB: ".$dbFC["Key"]." != CONF: ".$fC["key"];
				} elseif ($dbFC_Key_Uni != $fC_Key_Uni) {
					$this->aChanges[$fN]["unique"] = "DB: ".$dbFC["Key"]." != CONF: ".($fC["unique"]?"TRUE":"FALSE");
				}
				if ($dbFC["Default"] && $dbFC["Default"] != $fC["default"]) {
					$this->aChanges[$fN]["default"] = "DB: ".$dbFC["Default"]." != CONF: ".$fC["default"];
				}
				if (isset($this->aChanges[$fN]) && count($this->aChanges[$fN]) && !isset($this->aChanges[$fN]["FeldStatus"])) {
					$this->aChanges[$fN]["FeldStatus"] = "CHANGED";
				}
				//if (++$XXi < 10) echo "#".__LINE__."<pre>";var_dump($dbFC); var_dump($fC); echo "</pre>\n";
			}
		}
		
		if (count($this->aChanges)) {
			$this->Msg.= "<strong>Es wurden in ".count($this->aChanges)." Feldern unterschiedliche Definitionen festgestellt!</strong><br>\n";
			$this->Msg.= "(Siehe Anmerkungen in der Feldliste!)<br>\n<br>\n";
		}
	}
	
	function getFieldAlerts() {
		$aFieldAlerts = array();
		foreach($this->arrConf["Fields"] as $k => $v) {
			if (isset($this->aChanges[$k])) {
				$r_chg = " <span class=\"".$this->aChanges[$k]["FeldStatus"]."\">***".$this->aChanges[$k]["FeldStatus"]."***</span>";
				if ($this->aChanges[$k]["FeldStatus"] == "CHANGED") {
					foreach($this->aChanges[$k] as $chKey => $chVal) {
						if ($chKey != "FeldStatus") $r_chg.= "<br>".strtoupper($chKey).": ".$chVal."";
					}
				}
				$aFieldAlerts["[Fields][$k]"] = $r_chg;
			}
		}
		return $aFieldAlerts;
	}
	
	function get_dynFormByConf($inputArrayName = "eingabe", $view = "input,preview,read") {
		
		$makeInput   = ($view == "input");
		$makePreview = ($view == "preview");
		$makeRead    = ($view == "read");
		
		$prefix = ($inputArrayName ? $inputArrayName."[" : "");
		$suffix = ($inputArrayName ? "]" : "");
		
		$form = "";
		
		if ($makeInput) {
			$form.= "<h3 class=\"formTitle\">".$this->arrConf["Title"]."</h3>\n";
			if (!empty($this->arrConf["Description"])) {
				$form.= "<div class=\"formDesc\">".$this->arrConf["Description"]."</div>\n";
			}
			$form.= "<div class=\"formBox\"><form action=\"{action}\" method=\"post\" name=\"frmInput\">";
		}
		
		if ($makePreview) {
			$hidden = "";
			$form.= "<h3 class=\"formTitle\">".$this->arrConf["Title"]."</h3>\n";
			$form.= "<div class=\"formBox\"><form action=\"{action}\" method=\"post\" name=\"frmInput\">";
		}
		
		if ($makeRead) {
			$form.= "<h3 class=\"formTitle\">".$this->arrConf["Title"]."</h3>\n";
			$form.= "<div class=\"formBox\">";
		}
		
		$strIdsShowPflicht = "";
		$strIdsHidePflicht = "";
		foreach($this->arrConf["Fields"] as $k => $v) {
			if (!empty($v["required"])) {
				if ($strIdsShowPflicht) $strIdsShowPflicht.=",\n";
				$strIdsShowPflicht.= "#p{$k}";
			} else {
				if ($strIdsHidePflicht) $strIdsHidePflicht.=",\n";
				$strIdsHidePflicht.= "#p{$k}";
			}
		}
		if ($makeInput) {
			$form.= "<style>\n";
			$form.= "/*cssPflichtIds*/ { display:inline; color:#f00 }\n";
			$form.= "</style>\n";
			$form.= "<style>\n";
			if ($strIdsHidePflicht) {
				$form.= $strIdsHidePflicht;
				$form.= " { display:none; }\n";
			}
			if ($strIdsShowPflicht) {
				$form.= $strIdsShowPflicht;
				$form.= " { color:#f00 }\n";
			}
			$form.= "</style>\n";
		}
		
		foreach($this->arrConf["Fields"] as $k => $v) {
			$size = (!empty($v["size"]) ? $v["size"] : "");
			$fname = $prefix.$k.$suffix;
			$iAttr = (!empty($v["inputAttribute"])) ? " ".$v["inputAttribute"] : "";
			$rAttr = (!empty($v["readAttribute"])) ? " ".$v["readAttribute"] : "";
			if ($makeInput) {
				
				if ($v["htmlType"] != "hidden") {
					$opt = explode(",", $size);
					//$muster = "/^(x)(.*)(x)$/U";
					for($i = 0; $i < count($opt); $i++) {
						$opt[$i] = preg_replace("/^(')(.*)(')$/U", "\\2", $opt[$i]);
						if (substr($opt[$i],0,1) == "'") $opt[$i] = substr($opt[$i], 1, -1);
					}
					$str = "";
					$form.= "<div class=\"input\" id=\"InputBox{$k}\">\n";
					$form.= "<div class=\"inputLbl\"><label errclass=\"{$k}\" for=\"{$fname}\" id=\"InputLbl{$k}\">".$v["label"]."";
					if (!empty($v["required"])) $form.= "<span class=\"showPflicht\" id=\"p{$k}\">*</span>";
					else $form.= "<span class=isPflicht id=\"p{$k}\">*</span>";
					$form.= "</label></div>\n";
					$form.= "<div class=\"inputFrm\">";
					switch($v["htmlType"]) {
						case "radio":
						for ($i = 0; $i < count($opt); $i++) {
							list($r_key, $r_ti) = (count(explode("=", $opt[$i])) == 2) ? explode("=", $opt[$i]) : array($opt[$i], $opt[$i]);
							$str.= "<input LINE=\"".__LINE__."\" {$iAttr} class=\"chckbox\" type=\"radio\" name=\"".$fname."\" id=\"".$fname.$i."\" value=\"".$r_key."\" check_".$k."=\"".$r_key."\">".$r_ti." ";
						}
						break;
						
						case "checkbox":
						for ($i = 0; $i < count($opt); $i++) {
							list($r_key, $r_ti) = (count(explode("=", $opt[$i])) == 2) ? explode("=", $opt[$i]) : array($opt[$i], $opt[$i]);
							$str.= "<input LINE=\"".__LINE__."\" {$iAttr} class=\"chckbox\" type=\"checkbox\" name=\"".$fname."[]\" id=\"".$fname.$i."\" value=\"".$r_key."\" check_".$k."=\"".$r_key."\">".$r_ti." ";
						}
						break;
						
						case "text":
						switch($v["sysType"]) {
							case "password":
							$str.= "<input LINE=\"".__LINE__."\" {$iAttr} type=\"password\" class=\"itext ipass\" name=\"".$fname."\" id=\"".$fname."\" maxlength=\"$size\" value=\"{".$fname."}\">";
							$form.= $str;
							$kWH = $k."_wh";
							$fnameWH = $prefix.$kWH.$suffix;
							$form.= "</div>\n";
							$form.= "</div>\n";
							$form.= "<div class=\"input\" id=\"InputBox{$kWH}\">\n";
							$form.= "<div class=\"inputLbl\"><label for=\"{$fnameWH}\" id=\"InputLbl{$kWH}\"><em>wiederholen</em></label><span class=isPflicht id=\"p{$k}\">*</span></div>\n";
							$form.= "<div class=\"inputFrm\">";
							$str = "<input LINE=\"".__LINE__."\" {$iAttr} type=\"password\" class=\"itext ipass\" name=\"".$fnameWH."\" id=\"".$fnameWH."\" maxlength=\"$size\" value=\"{".$fnameWH."}\">";
							break;
							
							case "modified":
							case "modifiedby":
							case "modifieduid":
							case "created":
							case "createdby":
							case "createduid":
							case "system":
							$str.= "<div class=\"sysfield\">{".$fname."}</div>\n";
							break;
							
							default:
							$str.= "<input LINE=\"".__LINE__."\" {$iAttr} type=\"text\" class=\"itext\" name=\"".$fname."\" id=\"".$fname."\" maxlength=\"$size\" value=\"{".$fname."}\">";
						}
						break;
						
						case "textarea":
						$str.= "<textarea {$iAttr} class=\"itxarea\" name=\"".$fname."\" id=\"".$fname."\">{".$fname."}</textarea>\n";
						break;
						
						case "select single":
						case "select multiple":
						$multiple = (!preg_match("/multiple/", $v["htmlType"]) ? " name=\"".$fname."\"" : "name=\"".$fname."[]\" multiple=\"true\"");
						$str.= "<select {$iAttr} class=\"ichck iselect\" id=\"".$fname."\" $multiple>\n";
						for ($i = 0; $i < count($opt); $i++) {
							list($r_key, $r_ti) = (count(explode("=", $opt[$i])) == 2) ? explode("=", $opt[$i]) : array($opt[$i], $opt[$i]);
							$str.= "<option value=\"".$r_key."\" check_".$k."=\"".$r_key."\">".$r_ti." </option>\n";
						}
						$str.= "</select>";
						break;
						
						case "hidden":
						break;
						
						case "system";
						$str.= "<div class=\"sysfield\">{".$fname."}</div>\n";
						break;
						
						default:
						$this->Error.= "#".__LINE__." ".basename(__FILE__)." $k unbekannter htmlType:".$v["htmlType"]."<br>\n";
					}
					$form.= $str;
					$form.= "</div>\n";
					$form.= "</div>\n";
				} else {
					$form.= "<input LINE=\"".__LINE__."\" type=\"hidden\" name=\"".$fname."\" id=\"".$fname."\" value=\"{".$fname."}\">";
				}
			}
			
			if ($makePreview || $makeRead) {
				if ($v["sysType"] != "password") {
					$read = "<div class=\"Input\">\n<div class=\"inputLbl\">".$v["label"]."</div>\n<div{$rAttr}>{lesen[".$k."]}</div>\n</div>\n";
				} else {
					$read = "<div class=\"Input\">\n<div class=\"inputLbl\">".$v["label"]."</div>\n<div>***</div>\n</div>\n";
				}
			}
			
			if ($makePreview) {
				$form.= $read;
				$hidden.= "<input LINE=\"".__LINE__."\" type=\"hidden\" name=\"".$fname."\" maxlength=\"$size\" value=\"{".$fname."}\">\n";
			}
			
			if ($makeRead) {
				$form.= $read;
			}
		}
		if ($makePreview || $makeInput) $form.= "<!-- {trackPostVars} -->\n";
		$hiddenUpdateById  = "<input LINE=\"".__LINE__."\" type=\"hidden\" name=\"id\" value=\"{id}\">\n";
		$arrBtn["preview"] = "<input LINE=\"".__LINE__."\" type=\"submit\" class=\"ibtn isubmit\" name=\"editCmd[Preview]\" value=\"Vorschau\">";
		$arrBtn["save"]    = "<input LINE=\"".__LINE__."\" type=\"submit\" class=\"ibtn isubmit\" name=\"editCmd[Save]\" value=\"Fertigstellen\">";
		$arrBtn["correct"] = "<input LINE=\"".__LINE__."\" type=\"submit\" class=\"ibtn isubmit\" name=\"editCmd[Correct]\" value=\"Korrigieren\">";
		$arrBtn["gotoNextInsert"] = "<br>
<input LINE=\"".__LINE__."\" type=\"checkbox\" class=\"ichck inext\" name=\"gotoNext\" value=\"New\" chck_gotoNext=\"New\"> Anschliessend neuen Eintrag anlegen";
		
		if ($makeInput)   $form.=   $hiddenUpdateById.$arrBtn["preview"].$arrBtn["save"].$arrBtn["gotoNextInsert"]."</form></div>\n";
		if ($makePreview) $form.= $hidden.$hiddenUpdateById.$arrBtn["save"].$arrBtn["correct"]."</form></div>\n";
		if ($makeRead)    $form.= "</div>\n";
		return $form;
	}
	
	// public
	function write_templates($inputArrayName = "eingabe", $templates = "input,preview,read") 
	{
		echo "#".__LINE__." ".basename(__FILE__)."<br>\n";
		$inputFile = "";
		$previewFile = "";
		$readFile = "";
		
		$makeInput = preg_match("/input/", $templates);
		$makePreview = preg_match("/preview/", $templates);
		$makeRead = preg_match("/read/", $templates);
		
		$prefix = ($inputArrayName ? $inputArrayName."[" : "");
		$suffix = ($inputArrayName ? "]" : "");
		
		if ($makeInput) {
			$form = $this->get_dynFormByConf($inputArrayName, "input");
			
			if (empty($this->arrConf["FormInput"])) $this->arrConf["FormInput"] = $this->confName."_eingabe.html";
			$fpInput = fopen($this->tplDir."/".$this->arrConf["FormInput"], "w+");
			if ($fpInput) {
				// fputs($fpInput, "<h3 class=\"formTitle\">".$this->arrConf["Title"]."</h3>\n");
				fputs($fpInput, $form);
				fclose($fpInput);
			}
		}
		
		if ($makePreview) {
			$form = $this->get_dynFormByConf($inputArrayName, "preview");
			$hidden = "";
			if (empty($this->arrConf["FormPreview"])) $this->arrConf["FormPreview"] = $this->confName."_vorschau.html";
			$fpPreview = fopen($this->tplDir."/".$this->arrConf["FormPreview"], "w+");
			if ($fpPreview) {
				fputs($fpPreview, $form);
				fclose($fpPreview);
			}
		}
		
		if ($makeRead) {
			$form = $this->get_dynFormByConf($inputArrayName, "read");
			if (empty($this->arrConf["FormRead"])) $this->arrConf["FormRead"] = $this->confName."_lesen.html";
			$fpRead = fopen($this->tplDir."/".$this->arrConf["FormRead"], "w+");
			if ($fpRead) {
				fputs($fpRead, $form);
				fclose($fpRead);
			}
		}
		$this->Msg.= "#".__LINE__." ".basename(__FILE__)." Erstellte Templates:<br>
		inputFile: <a href='".$this->arrConf["FormInput"]."' target='_blank'>".$this->arrConf["FormInput"]."</a> <br>\n 
		previewFile: <a href='".$this->arrConf["FormPreview"]."' target='_blank'>".$this->arrConf["FormPreview"]."</a> <br>\n 
		readFile: <a href='".$this->arrConf["FormRead"]."' target='_blank'>".$this->arrConf["FormRead"]."</a> <br>\n";
		//echo $this->Msg;
	}
	
	function get_baseType(&$type) {
		if (preg_match("/int/", $type)) {
			$baseType = "int";
		} elseif(preg_match("/float|decimal/", $type)) {
			$baseType = "float";
		}  elseif(preg_match("/char/", $type)) {
			$baseType = "char";
		} elseif(preg_match("/text/", $type)) {
			$baseType = "text";
		} elseif(preg_match("/blob/", $type)) {
			$baseType = "blob";
		} else {
			$baseType = $type;
		}
		return $baseType;
	}
	
	// private
	function db_query($SQL, $line = "?") 
	{
		$r = @MyDB::query($SQL);
		if ($r) {
			return $r;
		} else {
			$this->db_error = "#$line Err:".MyDB::error()."\nQUERY:".fb_htmlEntities($SQL)."\n";
			die($this->db_error);
		}
	}
	
	// private
	function db_free_result($result) 
	{
		MyDB::free_result($result);
	}
	
	// private
	function __destruct() 
	{
		// $this->Msg.= "#".__LINE__." ".basename(__FILE__)." ".__CLASS__."::".__FUNCTION__." <br>\n";
		$this->db_close();
	}
	/**/
}

?>
</body>
</html>
