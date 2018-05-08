<?php 
require("../header.php");

if (1 && basename(__FILE__)==basename($_SERVER["PHP_SELF"])) {
	// Beispiel, Test & Debug-Ansicht
	include("../include/user.inc.php");
	
	/*
	function user_load($obj)                { return true; echo "<div>#".__LINE__." User Function ".__FUNCTION__."(obj,  func_num_args: ".func_num_args().") :-)</div>\n"; return true; }
	function user_onLoadFields() 			{ return true; echo "<div>#".__LINE__." User Function ".__FUNCTION__."(obj,  func_num_args: ".func_num_args().") :-)</div>\n"; return true; }
	function user_onLoadInput() 			{ return true; echo "<div>#".__LINE__." User Function ".__FUNCTION__."(obj,  func_num_args: ".func_num_args().") :-)</div>\n"; return true; }
	function user_onLoadData() 				{ return true; echo "<div>#".__LINE__." User Function ".__FUNCTION__."(obj,  func_num_args: ".func_num_args().") :-)</div>\n"; return true; }
	function user_onLoadForm() 				{ return true; echo "<div>#".__LINE__." User Function ".__FUNCTION__."(obj,  func_num_args: ".func_num_args().") :-)</div>\n"; return true; }
	function user_onValidate() 				{ return true; echo "<div>#".__LINE__." User Function ".__FUNCTION__."(obj,  func_num_args: ".func_num_args().") :-)</div>\n"; return true; }
	function user_onInsert() 				{ return true; echo "<div>#".__LINE__." User Function ".__FUNCTION__."(obj,  func_num_args: ".func_num_args().") :-)</div>\n"; return true; }
	function user_onSave() 					{ return true; echo "<div>#".__LINE__." User Function ".__FUNCTION__."(obj,  func_num_args: ".func_num_args().") :-)</div>\n"; return true; }
	function user_onUpdate() 				{ return true; echo "<div>#".__LINE__." User Function ".__FUNCTION__."(obj,  func_num_args: ".func_num_args().") :-)</div>\n"; return true; }
	function user_onDelete() 				{ return true; echo "<div>#".__LINE__." User Function ".__FUNCTION__."(obj,  func_num_args: ".func_num_args().") :-)</div>\n"; return true; }
	function user_onForm() 					{ return true; echo "<div>#".__LINE__." User Function ".__FUNCTION__."(obj,  func_num_args: ".func_num_args().") :-)</div>\n"; return true; }
	function user_onAfterValidate() 		{ return true; echo "<div>#".__LINE__." User Function ".__FUNCTION__."(obj,  func_num_args: ".func_num_args().") :-)</div>\n"; return true; }
	function user_onAfterInsert() 			{ return true; echo "<div>#".__LINE__." User Function ".__FUNCTION__."(obj,  func_num_args: ".func_num_args().") :-)</div>\n"; return true; }
	function user_onAfterSave() 			{ return true; echo "<div>#".__LINE__." User Function ".__FUNCTION__."(obj,  func_num_args: ".func_num_args().") :-)</div>\n"; return true; }
	function user_onAfterUpdate() 			{ return true; echo "<div>#".__LINE__." User Function ".__FUNCTION__."(obj,  func_num_args: ".func_num_args().") :-)</div>\n"; return true; }
	function user_onAfterDelete() 			{ return true; echo "<div>#".__LINE__." User Function ".__FUNCTION__."(obj,  func_num_args: ".func_num_args().") :-)</div>\n"; return true; }
	function user_onAfterForm() 			{ return true; echo "<div>#".__LINE__." User Function ".__FUNCTION__."(obj,  func_num_args: ".func_num_args().") :-)</div>\n"; return true; }
	*/
	$id = getRequest("id", "");
	$Input = getRequest("eingabe", array());
	if (empty($id) && isset($Input["uid"])) $id = $Input["uid"];
	
	$_CONF["user"]["OnLoadFields"] = "user_onLoadFields";
	$FormData = new FormData();
	$FormData->loadConf($_CONF["user"]);
	
	$FormData->conf("FormInput", "../html/user_eingabe.html");
	/*
	$FormData->conf("OnLoad", "user_load");
	$FormData->conf("OnLoadFields", "user_onLoadFields");
	$FormData->conf("OnLoadInput", "user_onLoadInput");
	$FormData->conf("OnLoadData", "user_onLoadData");
	$FormData->conf("OnLoadForm", "user_onLoadForm");
	$FormData->conf("OnValidate", "user_onValidate");
	$FormData->conf("OnInsert", "user_onInsert");
	$FormData->conf("OnSave", "user_onSave");
	$FormData->conf("OnUpdate", "user_onUpdate");
	$FormData->conf("OnDelete", "user_onDelete");
	$FormData->conf("OnForm", "user_onForm");
	$FormData->conf("OnAfterValidate", "user_onAfterValidate");
	$FormData->conf("OnAfterInsert", "user_onAfterInsert");
	$FormData->conf("OnAfterSave", "user_onAfterSave");
	$FormData->conf("OnAfterUpdate", "user_onAfterUpdate");
	$FormData->conf("OnAfterDelete", "user_onAfterDelete");
	$FormData->conf("OnAfterForm", "user_onAfterForm");
	*/
	
	$FormData->init_db($db);
	
	if ($Input) {
		$FormData->load($id, $Input);
		$FormData->validate();
		
		if ($FormData->isValid) {
			if ($FormData->save()) {
				$FormData->load($FormData->id());
			} else {
				echo "#".__LINE__." cannot save!<br>\n";
			}
		}
		
		if ($FormData->update(array("fon"))) {
			$FormData->load($FormData->id());
		} else {
			echo "#".__LINE__." cannot update!<br>\n";
		}
	} else {
		if ($id) $FormData->load($id);
		else $FormData->loadDefault();
		echo "#".__LINE__." ".basename(__FILE__)." ".print_r($FormData->Input,1)."<br>\n";
	}
	
	//print_r($FormData->InputErrors);
	echo $FormData->error();
	$Form = $FormData->form();
	$Form = str_replace("{action}","?", $Form);
	$Form = str_replace("{id}",$FormData->id(), $Form);
	echo $Form;
}

class FormData {
	
	function __construct($FieldConf=false) {
		$this->KEY = false;
		$this->ID = false;
		$this->db = false;
		$this->TplEngine = "default"; // default || smarty
		$this->TplVarPrefix = "{"; // { || {$ || {$xy. || %
		$this->smarty = false;
		$this->Conf = array();
		$this->Fields = array();
		$this->Input = array();
		$this->Data = array();
		$this->FormTpl = "";
		$this->Form = "";
		$this->Validator = new checkType();
		$this->InputErrors = array();
		$this->DbErrors = array();
		$this->SysErrors = array();
		$this->isValid = true;
	}
	
	function id() { return $this->ID; }
	function loadConf($Conf) 			{ 	$this->Conf = $Conf;   if(isset($this->Conf["Fields"])) { $this->loadFields($this->Conf["Fields"]); unset($this->Conf["Fields"]);} }
	function loadFields($Fields=false) 	{	$this->Fields=$Fields; $this->init_key(); $this->onLoadFields(); }
	function loadInput($Input=false) 	{ 	$this->Input=$Input;   $this->onLoadInput(); 	}
	function loadData($Data=false)		{	$this->Data=$Data;     $this->onLoadData(); 	}
	function loadForm($Form=false)		{	$this->Form=$Form;     $this->onLoadForm();     }
	
	function init_key() 				{	foreach($this->Fields as $field => $fConf) if (isset($fConf["key"]) && $fConf["key"]=="PRI") { $this->KEY=$field; break; }}
	function init_db($db) 				{	$this->db = $db; }
	function is_func($name) 			{	return (!empty($this->Conf[$name]) && function_exists($this->Conf[$name])); }
	function call($name) 				{	return eval("return call_user_func(\$this->Conf[\$name], \$this".(func_num_args()>1 ? ",".$this->serial_func_args(func_num_args(),1):"").");"); }
	function serial_func_args($n,$o=0)  {   $sArgs = ""; for ($i = $o; $i < $n; $i++) $sArgs.= ($i?",":"")."func_get_arg($i)"; return $sArgs;}
	
	function get_conf($name) 			{	return $this->Conf[$name];   }
	function set_conf($name, $value) 	{	$this->Conf[$name] = $value; return true; }
	function conf($name) 				{	return (func_num_args()<2) ? $this->get_conf($name) : $this->set_conf($name, func_get_arg(1));  }
	function input($field)              {   return (isset($this->Input) && isset($this->Input[$field])) ? $this->Input[$field] : "";        }
	function data($field)               {   return (isset($this->Data) && isset($this->Data[$field])) ? $this->Data[$field] : "";           }
	function field($field)              {   return (isset($this->Field) && isset($this->Field[$field])) ? $this->Field[$field] : null;      }
	
	function load($id, $Input=false) {
		$this->ID = $id;
		$Data = array();
		if ($id && $this->KEY && $this->db && $this->Conf["Table"]) {
			$sql = "SELECT * FROM `".$this->Conf["Table"]."` WHERE `".$this->KEY."` = \"".$this->db->escape($id)."\" LIMIT 1";
			$row = $this->db->query_singlerow($sql);
			
			if ($row) {
				foreach($this->Fields as $field => $fConf) {
					if ($fConf["dbField"] && isset($row[$fConf["dbField"]])) $Data[$field] = $row[$fConf["dbField"]];
				}
			}
			$this->loadData($Data);
		}
		if (empty($Input)) {
			if (empty($this->Data)) $this->loadDefault();
			else $this->loadInput($this->Data);
		} else {
			$this->loadInput($Input);
		}
		$this->onLoad();
	}
	
	function loadDefault() {
		$Input = array();
		foreach($this->Fields as $field => $fConf) $Input[$field] = $fConf["default"];
		$this->loadInput($Input);
	}
	
	function validate($FieldRange=false) {
		$this->isValid = true;
		//echo "#".__LINE__." FieldRange: ".print_r($FieldRange,1)."<br>\n";
		if(!$this->onValidate()) return $this->isValid;
		foreach($this->Fields as $field => $fConf) {
			if (!is_array($FieldRange) || in_array($field,$FieldRange)) if (!$this->validateField($field)) $this->isValid = false;
		}
		//
		$this->onAfterValidate();
		return $this->isValid;
	}
	
	function validateField($field) {
		if (!isset($this->Fields[$field])) return true;
		
		if (isset($this->Fields[$field]["onValidate"])) {
			if (function_exists($this->Fields[$field]["onValidate"])) return call_user_function($this->Fields[$field]["onValidate"], $this->Input[$field], $this);
			else {
				$this->FieldErros[$field] = "Fehlende Prüffunktion ".$this->Fields[$field]["onValidate"]."!";
				return false;
			}
		}
		
		if (empty($this->Input[$field])) {
			if (!$this->Fields[$field]["required"]) return true;
			else { $this->InputErrors[$field] = "Fehlende Angabe!"; return false; }
		}
		
		if (!$this->Validator->isValidType(
		 $this->Input[$field], 
		 $this->Fields[$field]["type"], 
		 $this->Fields[$field]["size"], 
		 $this->Fields[$field]["min"], 
		 $this->Fields[$field]["max"], 
		 &$err)) {
		 	$this->InputErrors[$field] = $this->Fields[$field]["type"].":".$err;
			return false;
		}
		
		if ($this->Fields[$field]["unique"] && !empty($this->Fields[$field]["dbField"])) {
			if ($this->db && $this->Conf["Table"]) {
				$sql = "SELECT COUNT(*) count FROM `".$this->Conf["Table"]."` \n";
				$sql.= "WHERE `".$this->Fields[$field]["dbField"]."` LIKE \"".$this->Input[$field]."\"\n";
				if ($this->id()) $sql.= " AND `".$this->KEY."` != \"".$this->id()."\"";
				$row = $this->db->query_singlerow($sql);
				if ($row["count"]) {
					$this->addInputError($field, "Es existiert bereits ein Eintrag mit diesem Wert!$sql");
					return false;
				}
			}
		}
		return true;
	}
	
	function date2mysql($val, &$err) {
		$DT = explode(" ", $val);
		$dt = $DT[0];
		$time = trim(implode("",array_slice($DT, 1)));
		$mysqldate = "";
		if ( count(explode(".", $dt)) == 3 || count(explode("-", $dt)) == 3) {
			if (strpos($dt, ".")) list($d, $m, $y) = explode(".", $dt);
			else list($y, $m, $d) = explode("-", $dt);
			if (checkdate($m, $d, $y)) {
				if (intval($d) < 10) $d = "0".intval($d);
				if (intval($m) < 10) $m = "0".intval($m);
				$mysqldate = "$y-$m-$d";
			} else {
				$err.= "Ungültiges Datumsangabe: $dt<br>\n";
			}
		} else {
			$err.= "Ungültiges Datumsformat: $dt (Erlaubte Formate: JJJJ-MM-TT oder TT.MM.JJJJ)<br>\n";
		}
		if ($time) $mysqldate.= " ".$time;
		return $mysqldate;
	}
	
	function insert() {
		if(!$this->onInsert()) return false;
		
		// 
		$this->onAfterInsert();
	}
	
	function save($AsNew=false) {
		if(!$this->onSave()) return false;
		
		if ($AsNew) $this->ID = 0;
		if (!$this->validate()) {
			$this->Errors.= "Ungültige Daten. Datensatz kann nicht gespeichert werden!<br>\n";
			return false;
		}
		$SaveMode = (!$this->id()) ? "INSERT" : "UPDATE";
		$set = "";
		foreach($this->Fields as $field => $fConf) {
			if (!$fConf["dbField"]) continue;
			if ($fConf["editByRuntime"] && $SaveMode=="UPDATE") continue;
			if ($fConf["key"]=="PRI") continue;
			
			$val = (isset($this->Input[$field])) ? $this->Input[$field] : "";
			$typ = $fConf["type"];
			
			// Check Systemverwaltete Felder für SaveMode-Insert
			if ($fConf["sysType"]=="created")    if ($SaveMode=="INSERT") { $typ="function"; $val = "NOW()";} else continue;
			elseif ($fConf["sysType"]=="createdby")  if ($SaveMode=="INSERT") { $val = (!empty($this->user))?$this->user:"";} else continue;
			elseif ($fConf["sysType"]=="createduid") if ($SaveMode=="INSERT") { $val = (!empty($this->uid))?$this->uid:"";} else continue;
			
			// Check Systemverwaltete Felder für SaveMode-UPDATE
			elseif ($fConf["sysType"]=="modified")    { $typ="function"; $val = "NOW()";}
			elseif ($fConf["sysType"]=="modifiedby")  $val = (!empty($this->user))?$this->user:"";
			elseif ($fConf["sysType"]=="modifieduid") $val = (!empty($this->uid))?$this->uid:"";
			elseif ($val && ($typ == "date" || $typ=="datetime")) {
				$val = $this->date2mysql($val, $err);
				if (!$val) { $this->addSysError("#".__LINE__." Datum '".$this->Data[$field]."' konnte nicht ins DB-Format konvertiert werden: $err", "Datenspeichern", 1, $field); return false; }
			}
			$set.= ($set?",\n":"").$this->db->setFieldValue($fConf["dbField"], $val, $typ, $fConf["null"]);
		}
		$sql = $SaveMode." `".$this->Conf["Table"]."` SET \n".$set;
		if ($SaveMode=="UPDATE") $sql.= "\nWHERE `".$this->KEY."` = \"".$this->id()."\"";
		$this->db->query($sql);
		if ($this->db->error()) {
			$this->addDbError("#".__LINE__." Fehler bei Datenaktualisierung", $this->db->error(), $sql);
			return false;
		} else {
			if ($SaveMode=="INSERT") $this->ID = $this->db->insert_id();
		}
		// 
		$this->onAfterSave();
		return true;
	}
	
	function update($FieldRange) {
		if(!$this->onUpdate()) return false;
		$NewID = false;
		if ($this->validate($FieldRange)) {
			$set = "";
			foreach($this->Fields as $field => $fConf) {
				if (!in_array($field, $FieldRange)) continue;
				if (!$fConf["dbField"]) continue;
				
				$val = (isset($this->Input[$field])) ? $this->Input[$field] : "";
				$typ = $fConf["type"];
				
				if ($fConf["key"]=="PRI" && $val && $val!=$this->id()) $NewID = $val;
				
				// Check Systemverwaltete Felder für SaveMode-UPDATE
				if ($fConf["sysType"]=="modified")    { $typ="function"; $val = "NOW()";}
				elseif ($fConf["sysType"]=="modifiedby")  $val = (!empty($this->user))?$this->user:"";
				elseif ($fConf["sysType"]=="modifieduid") $val = (!empty($this->uid))?$this->uid:"";
				elseif ($val && ($typ == "date" || $typ=="datetime")) {
					$val = $this->date2mysql($val, $err);
					if (!$val) { $this->addSysError("#".__LINE__." Datum '".$this->Input[$field]."' konnte nicht ins DB-Format konvertiert werden: $err", "Datenspeichern", 1, $field); return false; }
				}
				$set.= ($set?",\n":"").$this->db->setFieldValue($fConf["dbField"], $val, $typ, $fConf["null"]);
			}
			$sql = "UPDATE `".$this->Conf["Table"]."` SET \n".$set;
			$sql.= "\nWHERE `".$this->KEY."` = \"".$this->id()."\"";
			
			$this->db->query($sql);
			if ($this->db->error()) {
				$this->addDbError("#".__LINE__." Fehler bei Datenaktualisierung", $this->db->error(), $sql);
				return false;
			} else {
				if ($NewID) $this->ID = $NewID;
			}
		}
		// 
		$this->onAfterUpdate();
		return true;
	}
	
	function form($FormTpl="") {
		switch($this->TplEngine) {
			case "smarty":
			$this->smartyForm();
			break;
			
			default:
			$this->defaultForm($FormTpl);
		}
		
		$this->onAfterForm();
		return $this->Form;
	}
	
	function defaultForm($FormTpl) {
		if (!$FormTpl) $FormTpl = $this->getFormTpl();
		echo "#".__LINE__." ".basename(__FILE__)."<br>\n";
		if (!$FormTpl) {
			$this->addSysError(
				"Formularansicht konnte nicht geladen werden!",
				"Template");
				return false;
		}
		
		$pf = $this->TplVarPrefix;
		$sf = (substr($pf, 0, 1)=="{") ? "}" : substr($pf, 0, 1);
		$mod = (substr($pf, 1, 1)=="\$" && strpos($FormTpl, "|escape}")!==false);
		$eingabeVar = (strpos($FormTpl, $pf[0]."eingabe[")!==false);
		$lesenVar = (strpos($FormTpl, $pf[0]."eingabe[")!==false);
		
		$this->Form = $FormTpl;
		foreach($this->Fields as $field => $fConf) {
			$value = $this->input($field);
			$this->Form = str_replace($pf.$field.$sf, $value, $this->Form);
			if ($mod) $this->Form = str_replace($pf.$field."|escape".$sf, fb_htmlEntities($value), $this->Form);
			if ($eingabeVar) $this->Form = str_replace($pf[0]."eingabe[".$field."]".$sf, fb_htmlEntities($value), $this->Form);
			if ($lesenVar) $this->Form = str_replace($pf[0]."lesen[".$field."]".$sf, $value, $this->Form);
			
			// {$AS.terminwunsch|escape|date_format:"%d.%m.%Y"}
			if (strpos($this->Form, $pf.$field."|")!==false) {
				$this->setFormModifier($pf.$field."|", $value, $this->Form);
			}
			
			switch ($this->Fields[$field]["htmlType"]) {
				case "radio":
				case "checkbox":
				$this->Form = str_replace("check_".$field."=\"".$value."\"", "checked=\"true\"", $this->Form);
				break;
				
				case "select":
				case "select single":
				case "single multiple":
				case "select multiple":
				$this->Form = str_replace("check_".$field."=\"".$value."\"", "selected=\"true\"", $this->Form);
				break;
			}
			
			switch ($this->Fields[$field]["htmlType"]) {
				case "radio":
				case "checkbox":
				case "select":
				case "select single":
				case "single multiple":
				case "select multiple":
				
				$needle_generate_check[] = $pf."options_".$field.$sf;
				$needle_generate_check[] = "<!-- ".$pf."options_".$field.$sf." -->";
				if (strlen($pf)>1) $needle_generate_check[] = $pf[0]."options_".$field.$sf;
				if (strlen($pf)>1) $needle_generate_check[] = "<!-- ".$pf[0]."options_".$field.$sf." -->";;
				for($i = 0; $i < count($needle_generate_check); $i++) {
					if (strpos($this->Form, $needle_generate_check[$i])!==false) {
						$this->Form = str_replace(
							$needle_generate_check[$i], 
							$this->getFormOptions($this->Fields[$field]["htmlType"], $this->Fields[$field]["size"], $value),
							$this->Form
						);
					}
				}
				break;
			}
		}
	}
	
	function getFormTpl() {
		if ($this->FormTpl) return $this->FormTpl;
		else if ($this->Conf["FormInput"] && file_exists($this->Conf["FormInput"])) return file_get_contents($this->Conf["FormInput"]);
	}
	
	function setFormModifier($modPrefix, $value, &$Form) {
		$offset = 0;
		do {
			$p=strpos($Form, $modPrefix, $offset); $p2 = strpos($Form, "}", $p);
			if (is_int($p) && is_int($p2)) {
				$modifiedValue = $value; $tpl = substr($Form, $p, $p2-$p+1);
				$Mods = explode("|", substr($tpl,0, -1));
				for ($i = 1; $i < count($Mods); $i++) {
					$t = explode(":",$Mods[$i]); if ($t[1]) $t[1] = implode(":",array_slice($t, 1));
					switch($t[0]) {
						case "escape":	$modifiedValue = fb_htmlEntities($modifiedValue);	break;
						
						case "date_format":
						if ($t[1]) {
							$format = str_replace("%","",substr($t[1],1,-1));
							$d = explode("-", strtr($modifiedValue, array(" "=>"-",":"=>"-")));
							if (count($d)==1 && strlen($d[0])>4) { $modifiedValue = date($format, $t[0]); continue; }
							for ($j = 0; $j < 6; $j++) if (!isset($d[$j]) || !is_int($d[$j])) $d[$j] = 0;
							if (checkdate($d[1], $d[2], $d[0])) $modifiedValue = date($format, mktime($d[3], $d[4], $d[5], $d[1], $d[2], $d[0]));
						}
					
					}
				}
				$Form = str_replace($tpl, $modifiedValue, $Form);
			}
			$offset = $p+1;
		} while($p!==false);
	}
	
	function getFormOptions($typ, $value, $size) {
		$html = "";
		$options = (is_array($size)) ? $size : explode("','", substr($size,1,-1));
		$values = ($typ=="checkbox" || strpos($typ, "multiple")) ? explode(",",$value) : array($value);
		$checked = "checked=\"true\"";
		$selected = "selected=\"true\"";
		for($i=0; $i<count($options); $i++) {
			$t = explode("=", $options[$i]);
			if (count($t)<2) $t[1] = $t[0]; elseif (count($t)>2) $t[1] = array_slice($t, 1);
			switch($typ) {
				case "checkbox":
				case "radio":
				$html.= "<input type=\"$typ\" value=\"".fb_htmlEntities($t[0])."\" ".(in_array($t[0], $values)?$checked:"").">".$t[1]." ";
				break;
				
				default:
				$html.= "<option value=\"".$t[0]."\" ".(in_array($t[0], $values)?$selected:"").">".$t[1]."</option>\n";
			}
		}
		return $html;
	}
	
	function smartyForm() {
		if (!$this->smarty) { $this->addSysError("#".__LINE__." Smarty Template-Engine fehlt!","Template"); return false; }
		if (!$this->Conf["FormInput"]) { $this->addSysError("#".__LINE__." Smarty Template Dateiangabe fehlt!","Template"); return false; }
		
		foreach($this->Data as $field => $value) $this->smarty->assign($field, $value);
		$this->Form = $this->smarty->fetch($this->SmartyFormFile);
		return true;
	}
	
	function addInputError($field, $error) {	$this->InputErrors[$field] = $error; }
	function addDbError($error, $dberror, $sql) { $this->DbError[] = array("Error"=>$error,"DB-Error"=>$dberror, "SQL"=>$sql); }
	function addSysError($text, $type, $level=1, $field="") {	$this->SysErrors[] = array("Text"=>$text, "Type"=>$type, "Level"=>$level, "Field"=>$field); }
	
	function error() {
		$ErrorMsg = "";
		if (count($this->SysErrors))   $ErrorMsg.= "Es sind ".count($this->SysErrors)." Systemfehler aufgetreten!<br>\n";
		if (count($this->DbErrors))    $ErrorMsg.= "Es sind ".count($this->DbErrors)." Datenbankfehler aufgetreten!<br>\n";
		if (count($this->InputErrors)) $ErrorMsg.= "Es sind ".count($this->InputErrors)." Eingabefehler aufgetreten!<br>\n";
		return $ErrorMsg;
	}
	
	function delete() {
		if (!$this->onDelete()) return false;
		
		if (!$this->id()) {
			$this->addSysError("Fehlende Datensatz-ID: Datensatz konnte nicht gelöscht werden!", "Löschfehler");
			return false;
		}
		$sql = "DELETE FROM `".$this->Conf["Table"]."` WHERE `".$this->KEY."` = \"".$this->db->escape($this->id())."\"";
		$this->db->query($sql);
		
		if (!$this->db->error()) $this->onAfterDelete();
		else {
			$this->addDbError("Datensatz konnte nicht gelöscht werden!", $this->db->error(), $sql);
			return false;
		}
	}
	
	function onLoad()					{	return (!$this->is_func("OnLoad"))           ? true : $this->call("OnLoad");         }
	function onLoadFields() 			{	return (!$this->is_func("OnLoadFields"))     ? true : $this->call("OnLoadFields");   }
	function onLoadInput() 				{	return (!$this->is_func("OnLoadInput"))      ? true : $this->call("OnLoadInput");    }
	function onLoadData() 				{	return (!$this->is_func("OnLoadData"))       ? true : $this->call("OnLoadData");     }
	function onLoadForm() 				{	return (!$this->is_func("OnLoadForm"))       ? true : $this->call("OnLoadForm");     }
	
	function onValidate() 				{	return (!$this->is_func("OnValidate"))       ? true : $this->call("OnValidate");     }
	function onInsert() 				{	return (!$this->is_func("OnInsert"))         ? true : $this->call("OnInsert");       }
	function onSave() 					{	return (!$this->is_func("OnSave"))           ? true : $this->call("OnSave");         }
	function onUpdate() 				{	return (!$this->is_func("OnUpdate"))         ? true : $this->call("OnUpdate");       }
	function onDelete() 				{	return (!$this->is_func("OnDelete"))         ? true : $this->call("OnDelete");       }
	function onForm() 					{	return (!$this->is_func("OnForm"))           ? true : $this->call("OnForm");         }
	
	function onAfterValidate() 			{	return (!$this->is_func("OnAfterValidate"))  ? true : $this->call("OnAfterValidate");}
	function onAfterInsert() 			{	return (!$this->is_func("OnAfterInsert"))    ? true : $this->call("OnAfterInsert");  }
	function onAfterSave() 				{	return (!$this->is_func("OnAfterSave"))      ? true : $this->call("OnAfterSave");    }
	function onAfterUpdate() 			{	return (!$this->is_func("OnAfterUpdate"))    ? true : $this->call("OnAfterUpdate");  }
	function onAfterDelete() 			{	return (!$this->is_func("OnAfterDelete"))    ? true : $this->call("OnAfterDelete");  }
	function onAfterForm() 				{	return (!$this->is_func("OnAfterForm"))      ? true : $this->call("OnAfterForm");    }
	
	function __destruct() {
	
	}
}

?>
