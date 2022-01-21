<?php 
// echo "#".__LINE__." ".date("H:i:s")." <br>\n";
// Idee der 7 Benutzerstufen: Jede nächste Stufe hat voreingestellt mind. die Rechte der niederen Stufen
// 0: Jeder | Unbekannter Webuser | Kein Account erforderlich
// 1: Registrierter User: Nur Leserechte
// 2: Registrierter User: Darf neuen Inhalt arstellen, aber nicht bearbeiten
// 3: Registrierter User: Darf eigenen Inhalt bearbeiten
// 4: Registrierter User: Darf Inhalte anderer User bearbeiten, die in der gleicher Gruppe sind
// 5: Registrierter User: Darf alle Inhalte bearbeiten
// 6: Administrator

// Die Benutzerrechte können auf Datensatz und Feldebene frei überschrieben werden
// echo "#".__LINE__." ".basename(__FILE__)." ".date("H:i:s")."<br>\n";

class ItemListClass 
{
	var $classname  = "ItemEdit";
	var $ItemClass  = NULL;
	var $ItemConf = null;
	var $baseLink   = "";
	var $numAll     = NULL;
	var $num = NULL;
	var $tblClass   = "tblList";
	var $arrListButtons = array();
	var $arrListInput = array();
	var $strBaseQuery   = "";
	var $strWhere   = "";
	var $strJoin    = "";
	var $strGroup   = "";
	var $selectFlds = "*";
	var $searchFlds = ""; // default für Suche in 'allen Feldern': Wenn leer wie selectFlds
	var $strSQL = "";
	var $strSqlNumAll = "";
	var $arrListJoins = array();
	var $arrListJoinsTbl2Name = array();
	var $arrHideFields = array();
	var $arrColumnHandler = array();
	var $arrColNameHandler = [];
	var $tmpZahl = 0;
	var $tmpNull = NULL;
	var $aFldByNum = array();
	var $e = array(); // Ergebnis-Rowset
	var $TBLS_CONF;
	var $strItemList = "";
	var $dbError = "";
	var $defaultOrderFld = "";
	var $defaultOrderDir = "";
	var $ListRenderMode = "Auto";
	var $ListFunction = "";
	var $ListTemplate = "Auto";
	
	function __construct(&$ItemEdit, $selectFlds = "", $setAutoJoin = false, $useListConfId = "default") {
		global $_CONF;
		global $_SERVER;
                
		$this->TBLS_CONF = &$_CONF;
		$this->ItemClass = $ItemEdit;
		$this->ItemConf  = &$this->ItemClass->arrConf;
		$this->baseLink  = basename($_SERVER["PHP_SELF"]);
		// echo "#".__LINE__." \$this->baseLink: ".$this->baseLink.", \$_SERVER[PHP_SELF]:".$_SERVER["PHP_SELF"]."<br>\n";
		$this->set_defaultListButtons();
		
		//echo "#".__LINE__." ".basename(__FILE__)." \$selectFlds:$selectFlds<br>\n";
		if (!$selectFlds || $selectFlds == "*") {
			if ($useListConfId == "default") $useListConfId = 0;
			elseif (empty($this->ItemConf["Lists"][$useListConfId])) {
				echo "#".__LINE__." ".basename(__FILE__)." Es ist keine Liste mit der ID '$useListConfId' eingetragen!<br>\n";
			}
			if (!empty($this->ItemConf["Lists"][$useListConfId])) {
				$this->selectFlds = $this->ItemConf["Lists"][$useListConfId]["select"];
				$this->defaultOrderFld = $this->ItemConf["Lists"][$useListConfId]["defaultOrderFld"];
				$this->defaultOrderDir = $this->ItemConf["Lists"][$useListConfId]["defaultOrderDir"];
				$this->strBaseQuery = $this->ItemConf["Lists"][$useListConfId]["where"];
			}
			
		} else {
			$this->selectFlds = $selectFlds;
		}
		if (!$this->selectFlds) $this->selectFlds = "*";
		if ($setAutoJoin) $this->add_listAutoJoinByConf();
                
	}
	
	function set_listFeaturesByConf($aListConf) {
		if (!empty($aListConf["select"])) $this->selectFlds = $aListConf["select"];
		if (!empty($aListConf["join"])) $this->strJoin = $aListConf["join"];
		if (!empty($aListConf["group"])) $this->strGroup = $aListConf["group"];
		if (!empty($aListConf["defaultOrderFld"])) $this->defaultOrderFld = $aListConf["defaultOrderFld"];
		if (!empty($aListConf["defaultOrderDir"])) $this->defaultOrderDir = $aListConf["defaultOrderDir"];
		
		if (!empty($aListConf["ListRenderMode"])) $this->ListRenderMode = $aListConf["ListRenderMode"];
		if (!empty($aListConf["ListFunction"])) $this->ListFunction = $aListConf["ListFunction"];
		if (!empty($aListConf["ListTemplate"])) $this->ListTemplate = $aListConf["ListTemplate"];
		
		if ($this->ListRenderMode == "TemplateFile") {
			if (file_exists($this->ListTemplate)) {
				$this->ListTemplate = file_get_contents($this->ListTemplate);
				$this->ListRenderMode = "Template";
			}
		}
		
		if ($this->ListRenderMode == "Template") {
			if (empty($this->ListTemplate)) {
				$this->ListRenderMode = "Auto";
				echo "#".__LINE__." Listentemplate wurde nicht gefunden. Auto-List wurde aktiviert!<br>\n";
			}
		}
		
		
		if (!empty($aListConf["setDefaultButtons"])) {
			switch($aListConf["setDefaultButtons"]) {
				case "auto":
				$this->set_defaultListButtons();
				break;
				
				case "open":
				$this->set_defaultListButtons();
				$this->drop_listButton("kill");
				break;
				
				case "kill":
				$this->set_defaultListButtons();
				$this->drop_listButton("open");
				break;
				
				case "":
				case "no":
				case false:
				$this->drop_defaultListButtons();
				break;
			}
		}
		
		if (!empty($aListConf["addButtons"]) && is_array($aListConf["addButtons"])) {
			foreach($aListConf["addButtons"] as $btnName => $aV) {
				$this->addButton($btnName, $aV["createByFunction"], $aV["label"], $aV["link"], $aV["tplVars"], $aV["innerHTML"], $aV["beforeHTML"], $aV["behindHTML"]);
				if (!empty($aV["events"]) && is_array($aV["events"])) {
					foreach($aV["events"] as $btnEvent => $aV2) {
						$this->addButtonEvent($btnName, $btnEvent, $aV2["fnc"], $aV2["flds"]);
					}
				}
			}
		}
		
		if (!empty($aListConf["addFormFields"]) && is_array($aListConf["addFormFields"])) {
			foreach($aListConf["addFormFields"] as $name => $aV) {
				$this->addListFormField($name, $aV["createByFunction"], $aV["label"], $aV["type"], $aV["inputName"], $aV["inputId"], $aV["valueByDbFld"], $aV["default"], $aV["defChck"], $aV["options"]);
			}
		}
		
		if (!empty($aListConf["addColumnHandler"]) && is_array($aListConf["addColumnHandler"])) {
			foreach($aListConf["addColumnHandler"] as $colName => $fncName) {
				$this->addColumnHandler($colName, $fncName);
			}
		}

        if (!empty($aListConf["addColNameHandler"]) && is_array($aListConf["addColNameHandler"])) {
            foreach($aListConf["addColNameHandler"] as $colName => $fncName) {
                $this->addColNameHandler($colName, $fncName);
            }
        }
		//echo "#".__LINE__." ".basename(__FILE__)." this->selectFlds:".$this->selectFlds."<br>\n";
	}
	
	function add_listAutoJoinByConf() {
		$arrConfNames = array();
		if (isset($this->ItemConf["Joins"]) && is_array($this->ItemConf["Joins"])) {
			foreach($this->ItemConf["Joins"] as $joinName => $joinProps) {
				if (isset($joinProps["listAutoJoin"]) && $joinProps["listAutoJoin"] === true) {
					$arrConfNames[] = $joinName;
				}
			}
		}
		$this->add_listJoinByConf($arrConfNames);
	}
	
	function add_listJoinByConf($arrConfNames) {
            //die(print_r($this->ItemConf,1));
		if (isset($this->ItemConf["Joins"])) {
			
			for($i = 0; $i < count($arrConfNames); $i++) {
				$joinName = $arrConfNames[$i];
				
				if (isset($this->ItemConf["Joins"][$joinName])) {
					$joinProps = &$this->ItemConf["Joins"][$joinName];
					// echo "#".__LINE__." Found ListAutoJoin in _CONF für $joinName <br>\n";
					// "listFields"   => "",
					// "listHideKey"  => true,
					// "listHideFlds" => array(),
					// "listPosition" => "start" // start | append
				
					$this->use_join(
						$joinName, 
						$joinProps["listFields"], 
						$joinProps["listHideKey"], 
						$joinProps["listHideFlds"], 
						$joinProps["listPosition"]);
				} else {
					// echo "#".__LINE__." print_r(joinProps): ".print_r($joinProps, true)."<br>\n";
				}
			}
		}
	}
	
	function use_join($joinName, $joinListFlds, $blnHideKey, $arrHideFields = array(), $listPosition = "append") {
		if (0) echo "#".__LINE__." f:".__FUNCTION__."(". print_r(func_get_args(),1) . "<br>\n";
		$objJoin = &$this->ItemConf["Joins"][$joinName];
		$cnfkey  = $objJoin["confkey"];
                
                if (!isset($this->TBLS_CONF[$cnfkey])) {
                    global $_CONF;
                    require_once __DIR__ . '/../include/' . $cnfkey . '.inc.php';
                    $this->TBLS_CONF[$cnfkey] = $_CONF[$cnfkey];
                }
                if (is_string($arrHideFields) && trim($arrHideFields)) {
                    $arrHideFields = explode(',', $arrHideFields);
                }
                
                if ($pf = stripos($objJoin["foreignTbl"], ' as ')) {
                    $foreignTblName = trim(substr($objJoin["foreignTbl"], $pf+4));
                } else {
                    $foreignTblName = $objJoin["foreignTbl"];
                }
		$joinListFlds = str_replace("{table}", $this->TBLS_CONF[$cnfkey]["Table"], $joinListFlds);
		$this->arrListJoins[$joinName]["joinListFlds"] = $joinListFlds;
		$this->arrListJoins[$joinName]["rplKeyByFld"]  = ''; //$rplKeyByFld;
		
		$this->arrListJoinsTbl2Name[$objJoin["foreignTbl"]] = $joinName;
		
		if ($blnHideKey && !in_array("`".$this->ItemConf["Table"]."`.`".$objJoin["key"]."`", $this->arrHideFields)) {
			$this->arrHideFields[] = "`".$this->ItemConf["Table"]."`.`".$objJoin["key"]."`";
		}
                
		if (is_array($arrHideFields)) for ($i = 0; $i < count($arrHideFields); $i++) {
                    if (!in_array("`".$foreignTblName."`.`".$arrHideFields[$i]."`", $this->arrHideFields)) {
				$this->arrHideFields[] = "`".$foreignTblName."`.`".$arrHideFields[$i]."`";
			}
		}
		
		$leadingTbl = (empty($objJoin["leadingTblConfName"])) ? $this->ItemConf["Table"] : $this->TBLS_CONF[$objJoin["leadingTblConfName"]]["Table"];
		$this->strJoin.= "LEFT JOIN ".$objJoin["foreignTbl"]." ";
		$this->strJoin.= " ON `".$leadingTbl."`.`".$objJoin["key"]."`";
		$this->strJoin.= " = `".$foreignTblName."`.`".$objJoin["foreignKey"]."` \n";
		
		$keyIsSelected = false;
		$tmp2 = "";
		if (empty($joinListFlds) || $joinListFlds == "*") {
			if (!is_int(strpos($this->selectFlds, "`".$foreignTblName."`.")) ) {
				$tmp2 = "`".$foreignTblName."`.* ";
				$keyIsSelected = true;
			} else {
				echo "#".__LINE__." KONFLIKT: Doppelte Feldauswahl für Tabelle `".$foreignTblName."`";
				echo " in this->selectFlds u. joinListFlds!<br>\n";
			}
		} else {
			$tmp1 = explode(",", $joinListFlds);
			$tmp2 = '';
			for($i = 0; $i < count($tmp1); $i++) {
				// Vermeide Doppelte Feldbenennung in Select
				// Daher Abfrage, ob JoinFld bereits in Select enthalten ist
				if (false === strpos($this->selectFlds, "`".$foreignTblName."`.`".$tmp1[$i]."`") ) {
					if (false === strpos($tmp1[$i], "`") && false === strpos($tmp1[$i], "(") ) {
						$tmp2.= ($i ? ", " : "")."`".$foreignTblName."`.`".$tmp1[$i]."`";
					} else {
						$tmp2.= ($i ? ", " : "").$tmp1[$i];
					}
					if ($tmp1[$i] == $objJoin["key"]) $keyIsSelected = true;
				}
			}
			if (!$keyIsSelected) $this->selectFlds.= ", `".$foreignTblName."`.`".$objJoin["foreignKey"]."`";
		}
		
		$this->arrListJoins[$joinName]["joinListFlds"] = $tmp2;
		if ($tmp2) 
		switch($listPosition) {
			case "start":
			$this->selectFlds = $tmp2.", ".$this->selectFlds;
			break;
			
			case "append":
			default:
			$this->selectFlds.= ", ".$tmp2;
			break;
		}
	}
	
	
	function get_joinTblAndFld($joinField, &$getArrJoinProps) {
		global $_CONF;
		
		$fldDbPath =  explode(".", str_replace("`", "", $joinField));
		
		switch(count($fldDbPath)) {
			case 1:
			// Es wurde nur der Feldname angegeben.
			// Suche nach Tabelle, in dem das Feld vorkommt
			$jTbl = "";
			$jFld = $fldDbPath[0];
			if (0 == count($arrListJoins)) {
				echo "#".__LINE__." Unidentifizierbares Join-Feld:$joinField. Es wurden keine Joins für die Listenausgabe vorgegeben!<br>\n";
			}
			
			// Suche findet nur in Joins statt, die im Listen-Objekt angemeldet wurden
			if (is_array($_CONF) && count($_CONF)) {
				foreach($arrListJoins as $joinName => $arrProps) {
					
					$tblConfKey = $arrProps["confkey"];
					
					if (!isset($_CONF[$tblConfKey])) {
						echo "#".__LINE__." Ungültiger Conf-Key (_CONF[$tblConfKey]) für Tabellen-Join!<br>\n";
					}
					
					foreach($_CONF[$tblConfKey]["Fields"] as $fN => $fC) {
						if ($fC["dbField"] == $jFld) {
							$getArrJoinProps["JoinName"] = $joinName;
							$getArrJoinProps["TblConfKey"] = $tblConfKey;
							$getArrJoinProps["Table"] = $jTbl;
							$getArrJoinProps["Field"] = $jFld;
							return true;
						}
					}
				}
			}
			break;
			
			case 2:
			// Es wurde Tabellen- u. Feldname angegeben
			// Es wird geprüft, ob Join-Tabelle im Listen-Objekt angemeldet wurde
			// Weitere Prüfungen, ob Feld tatsächlichh in Tabelle enthalten ist, findet nicht statt
			$jTbl = $fldDbPath[0];
			$jFld = $fldDbPath[1];
			if (!isset($this->arrListJoinsTbl2Name[$jTbl])) {
				$getArrJoinProps["JoinName"] = $this->arrListJoinsTbl2Name[$jTbl];
				$getArrJoinProps["TblConfKey"] = $arrListJoins[$this->arrListJoinsTbl2Name[$jTbl]]["confkey"];
				$getArrJoinProps["Table"] = $jTbl;
				$getArrJoinProps["Field"] = $jFld;
				return true;
			} else {
				echo "#".__LINE__." Unbekannte Join-Tabelle:$jTbl. Es wurden kein Join für diese Tabelle registriert oder es liegt keine Conf!<br>\n";
			}
			break;
			
			default:
			// FEHLER: Evtll. wurde noch ein Datenbankname angegeben
			// Ungültige Option, da Abfragen nur in der Hauptdatenbank stattfinden !!!
			echo "#".__LINE_." ".basename(__FILE__)." ERROR IN -> class ".__CLASSNAME__." -> function ".__FUNCTION__."() ";
			echo "Zu viele Pfadpunkte(".count($fldDbPath).") in SearchField-Angabe: &quot;$searchField&quot;!<br>\n";
		}			
		return false;
	}
	
	function set_baseLink($baseLink) {
		$this->baseLink = $baseLink;
		// echo "#".__LINE__." \$this->baseLink: ".$this->baseLink." <br>\n";
	}
	
	function get_oDir($chckFld, $oFld, $oDir) {
		
		$dir = ($chckFld != $oFld ? "ASC" : ($oDir != "ASC" ? "ASC" : "DESC"));
		// echo "#".__LINE__." $dir = ".__FUNCTION__."($chckFld, $oFld, $oDir)<br>\n";
		return $dir;
	}
	
	function get_numAll($refresh = false) {
		if ($this->numAll != NULL) return $this->numAll;
		if (!$this->strSqlNumAll) {
			$SQL = "SELECT COUNT(*) FROM ";
			$SQL.= "`".$this->ItemClass->arrConf["Db"]."`.`".$this->ItemClass->arrConf["Table"]."`\n";
			if ($this->strJoin && $this->strWhere) $SQL.= $this->strJoin."\n";
			elseif (count($this->arrListJoins)) {}
			if ($this->strWhere) $SQL.= "WHERE ".$this->strWhere."\n";
			if ($this->strGroup) $SQL.= "GROUP BY ".$this->strGroup."\n";
		} else {
			$SQL = $this->strSqlNumAll;
		}
		$this->numAll = 0;
		$r = $this->ItemClass->db_query($SQL);
		if ($r) {
			$n = MyDB::num_rows($r);
			for ($i = 0; $i < $n; $i++) {
				list($cnt) = MyDB::fetch_array($r);
				$this->numAll+= $cnt;
			}
			$this->ItemClass->db_free_result($r);
			return $this->numAll;
		}
		return -1;
	}
	
	function addColumnHandler($colName, $fncName) {
		$this->arrColumnHandler[$colName] = $fncName;
        return $this;
	}
	
	function dropColumnHandler($colName) {
		if (isset($this->arrColumnHandler[$colName])) {
		    unset($this->arrColumnHandler[$colName]);
        }
        return $this;
	}

	function addColNameHandler($colName, $fncName) {
        $this->arrColNameHandler[$colName] = $fncName;
        return $this;
    }

    function dropColNameHandler($colName) {
        if (isset($this->arrColNameHandler[$colName])) {
            unset($this->arrColumnHandler[$colName]);
        }
        return $this;
    }
	
	function addListFormField($name, $createByF, $label, $type, $inputName, $inputId, $valueByDbFld, $default, $defChck, $options) {
		// echo "#".__LINE__." ".__FUNTION__." <br>\n";
		$this->arrListInput[$name] = array(
			"createByFunction" => $createByF,
			"label" => $label,
			"type" => $type,
			"inputName" => $inputName,
			"inputId" => $inputId,
			"setDbFldVal" => $valueByDbFld,
			"default" => $default,
			"html" => ""
		);
		
		switch($type) {
			case "select":
			break;
			
			case "text":
			case "hidden":
			break;
			
			case "checkbox":
			$this->arrListInput[$name]["html"] = "<input LINE=\"".__LINE__."\" type=$type name=".$inputName."[] id=$inputId{nr} ";
			if ($valueByDbFld) $this->arrListInput[$name]["html"].= " value=\"{".$valueByDbFld."}\" ";
			elseif ($default) $this->arrListInput[$name]["html"].= " value=\"".fb_htmlEntities($default)."\" ";
			if ($defChck) $this->arrListInput[$name]["html"].= " checked=\"true\" ";
			$this->arrListInput[$name]["html"].= ">";
			break;
			
			case "radio":
			$this->arrListInput[$name]["html"] = "<input LINE=\"".__LINE__."\" type=$type name=".$inputName." id=$inputId{nr} ";
			if ($valueByDbFld) $this->arrListInput[$name]["html"].= " value=\"{".$valueByDbFld."}\" ";
			elseif ($default) $this->arrListInput[$name]["html"].= " value=\"".fb_htmlEntities($default)."\" ";
			if ($defChck) $this->arrListInput[$name]["html"].= " checked=\"true\" ";
			$this->arrListInput[$name]["html"].= ">";
			break;
		}
		/**/
	}
	
	function dropListFormField($name) {
		unset($this->arrListInput[$name]);
	}
	
	function addButton($btnName, $createByF, $btnLabel, $btnLinkParams, $strTplVars = "", $strInnerHtml = "", $strBeforeHtml = "", $strBehindHtml = "") {
		//  "Nutzung", "&d={$data}&cmd=addUsage") {
		$this->arrListButtons[$btnName] = array(
			"createByFunction" => $createByF,
			"label" => $btnLabel,
			"link"  => $btnLinkParams,
			"tplVars" => ($strTplVars ? explode(",", $strTplVars) : array()),
			"innerHTML"  => $strInnerHtml,
			"beforeHTML" => $strBeforeHtml,
			"behindHTML" => $strBehindHtml
		);
	}
	
	function addButtonEvent($btnName, $btnEvent, $btnFunction, $strDataFlds) {
		 $this->edit_buttonEvent($btnName, $btnEvent, $btnFunction, $strDataFlds);
	}
	
	function edit_buttonEvent($btnName, $btnEvent, $btnFunction, $strDataFlds) {
		if (isset($this->arrListButtons[$btnName])) {
			$this->arrListButtons[$btnName]["events"][$btnEvent]["fnc"] = $btnFunction;
			$this->arrListButtons[$btnName]["events"][$btnEvent]["flds"] = explode(",", $strDataFlds);
		}
	}
	
	function drop_buttonEvent($btnName, $btnEvent) {
		if (isset($this->arrListButtons[$btnName])) {
			if (isset($this->arrListButtons[$btnName]["events"][$btnEvent])) {
				unset($this->arrListButtons[$btnName]["events"][$btnEvent]);
			}
		}
	}
	
	function drop_allBtnEvents($btnName) {
		if (isset($this->arrListButtons[$btnName])) {
			if (isset($this->arrListButtons[$btnName]["events"])) {
				unset($this->arrListButtons[$btnName]["events"]);
			}
		}
	}
	
	function set_btnInnerHTML($btnName, $btnInnerHTML) {
		if (isset($this->arrListButtons[$btnName])) {
			$this->arrListButtons[$btnName]["innerHTML"] = $btnInnerHTML;
		}
	}
	
	function set_btnOuterInnerHTML($btnName, $btnBeforeHTML, $btnBehindHTML) {
		if (isset($this->arrListButtons[$btnName])) {
			$this->arrListButtons[$btnName]["beforeHTML"] = $btnBeforeHTML;
			$this->arrListButtons[$btnName]["behindHTML"] = $btnBehindHTML;
		}
	}
	
	function set_defaultListButtons() {
		$AttrDel = " class=\"btnDelete\" onclick=\"return confirm('Möchten Sie den Datensatz wirklich l&ouml;schen?')\"";
		$this->addButton("open", "", "Bearbeiten", "&view=edit&{trackVars}", "");
		$this->addButton("kill", "", "L&ouml;schen", "&view=del&{trackVars}", "", $AttrDel);
	}
	
	function edit_listButton($btnName, $key, $val) {
		if (isset($this->arrListButtons[$btnName][$key])) {
			$this->arrListButtons[$btnName][$key] = $val;
		}
	}
	
	function drop_listButton($btnName) {
		if (isset($this->arrListButtons[$btnName])) {
			unset($this->arrListButtons[$btnName]);
		}
	}
	
	function drop_allListButtons() {
		$this->arrListButtons = array();
	}
	
	function drop_defaultListButtons() {
		drop_listButton("open");
		drop_listButton("kill");
	}
	
	function get_searchForm($defField = "*", $defTerm = "", $arrHidden = array()) {
		global $_CONF;
		$frmSearch  = "";
		$strOptions = "";
		$selected = " selected=\"true\"";
		
		// Feldauswahl für Maintable
		
		if (count($this->arrListJoins)) {
                    foreach($this->arrListJoins as $k => $v) {
			$strOptions.= "<optgroup label=\"".$this->ItemClass->arrConf["Title"]."\">\n";
			$strOptions.= "<option value=\"".$this->ItemClass->arrConf["Title"].".*\"".($defField != $k ? "" : $selected).">";
			$strOptions.= "Alle ".$this->ItemClass->arrConf["Title"]."-Felder</option>\n";
                    }
		}
		foreach($this->ItemClass->arrConf["Fields"] as $k => $v) {
			$r_label = (!empty($v["listlabel"]) ? $v["listlabel"] : $v["label"]);
			$aOptions[$v['label']] = "<option value=\"{$k}\"".($defField != $k ? "" : $selected).">{$r_label}</option>\n";
		}
		ksort($aOptions);
		$strOptions.= implode("",$aOptions);
		if (count($this->arrListJoins)) $strOptions.= "</optgroup>";
		
		// Feldauswahl für Join-Tables
		foreach($this->arrListJoins as $joinName => $joinProps) {
			$cnfkey = $this->ItemConf["Joins"][$joinName]["confkey"];
			$strOptions.= "<optgroup label=\"{$joinName}-Felder\">\n";
			$strOptions.= "<option value=\"{$joinName}.*\"".($defField != $k ? "" : $selected).">Alle {$joinName}-Felder</option>\n";
			
			foreach($_CONF[$cnfkey]["Fields"] as $k => $v) {
				$r_label = (!empty($v["listlabel"]) ? $v["listlabel"] : $v["label"]);
				$strOptions.= "<option value=\"{$joinName}.{$k}\"".($defField != $k ? "" : $selected).">{$r_label}</option>\n";
			}
			$strOptions.= "</optgroup>\n";
		}
		
		// $frmSearch = "<form>";
		$frmSearch = "<input LINE=\"".__LINE__."\" name=\"searchTerm\" value=\"".fb_htmlEntities($defTerm)."\">";
		$frmSearch.= "<select name=\"searchField\">";
		$frmSearch.= "<option value=\"*.*\"".($defField == "*.*" ? $selected : "").">In allen Feldern</option>";
		$frmSearch.= $strOptions;
		$frmSearch.= "</select>";
		// $frmSearch.= "<input LINE=\"".__LINE__."\" type=\"submit\" value=\"go\">";
		$frmSearch.= "<input LINE=\"".__LINE__."\" type=\"submit\" value=\"suchen\" alt=\"Suche starten\" align=\"absmiddle\">"; 
		
		foreach($arrHidden as $k => $v) {
			$frmSearch.= "<input LINE=\"".__LINE__."\" type=\"hidden\" name=\"$k\" value=\"".fb_htmlEntities($v)."\">\n";
		}
		
		// $frmSearch.= "</form>";
		return $frmSearch;
	}
	
	function render_sqlBySearchForm($searchField, $searchTerm) {
		global $_CONF;
		// echo "#".__LINE__." ".__FILE__." searchField:$searchField, searchTerm:$searchTerm<br>\n";
		
		if (empty($searchField)) $searchField = "*";
		$strWhere = "";
		$searchTerm = trim($searchTerm);
		if ($searchTerm) {
			// $this->strBaseQuery
			switch(substr($searchTerm, 0, 1)) {
				case "<":
				case ">":
				case "=":
				$offset = (substr($searchTerm, 1, 1) != "=") ? 1 : 2;
				$operator = substr($searchTerm, 0, $offset);
				$searchTerm = substr($searchTerm, $offset);
				$doSetJoker = false;
				break;
				
				case "!":
				$offset = (substr($searchTerm, 1, 1) != "=") ? 1 : 2;
				$searchTerm = substr($searchTerm, $offset);
				$operator = ($offset == 1) ? "NOT LIKE" : "!=";
				$doSetJoker = ($offset == 1);
				break;
				
				default:
				$operator = "LIKE";
				$doSetJoker = true;
			}
			$searchTerm = trim($searchTerm);
		}
		if (!empty($operator) || strlen($searchTerm)) {
			
			$tmp = explode(".", $searchField);
			if (count($tmp) == 2) list($searchArea, $searchField) = $tmp;
			else $searchArea = "";
			
			if (substr($searchField, -1) != "*") {
				if (!$searchArea) $searchArea = $this->ItemClass->arrConf["Title"];
				if ($searchArea == $this->ItemClass->arrConf["Title"]) {
					$searchTbl = $this->ItemClass->arrConf["Table"];
					$strWhere = " `{$searchTbl}`.`".$this->ItemClass->arrConf["Fields"][$searchField]["dbField"]."`";
				} elseif (isset($this->arrListJoins[$searchArea])) {
					$tblConfKey = $this->ItemClass->arrConf["Joins"][$searchArea]["confkey"];
					$searchTbl = $_CONF[$tblConfKey]["Table"];
					$strWhere = " `{$searchTbl}`.`".$_CONF[$tblConfKey]["Fields"][$searchField]["dbField"]."`";
					// echo "#".__LINE__." searchArea:{$searchArea}; tblConfKey:$tblConfKey; searchTbl:$searchTbl; strWhere = $strWhere<br>\n";
				} else {
					echo "Ungültige Feldauswahl: $searchField!<br>\n";
					return false;
				}
				
				$strWhere.= $operator;
				if ($doSetJoker) {
					$strWhere.= "\"%".str_replace("*", "%", MyDB::escape_string($searchTerm))."%\"";
				} else {
					$strWhere.= "\"".MyDB::escape_string($searchTerm)."\"";
				}
			} else {
				if (!$searchArea) $searchArea = "*";
				
				switch($operator) {
					case "LIKE":
					case "NOT LIKE":
					
					if ($this->searchFlds === "") $this->searchFlds = $this->selectFlds;
					// Suche in Main-Table
					if ($searchArea == "*" || $searchArea == $this->ItemClass->arrConf["Title"]) {
						
						foreach($this->ItemClass->arrConf["Fields"] as $k => $v) {
							
							if ($v["key"]) continue;
							if ($searchField == "*" || is_int(strpos($searchField, $v["dbField"])) ) {
								if ($strWhere) $strWhere.= ($operator == "LIKE") ? " OR \n" : "AND \n";
								$strWhere.= "(`".$this->ItemClass->arrConf["Table"]."`.`".$v["dbField"]."` $operator ";
								$strWhere.= "\"%".str_replace("*", "%", MyDB::escape_string($searchTerm))."%\"";
								if ($operator == "NOT LIKE") $strWhere.= " OR `".$v["dbField"]."` IS NULL";
								$strWhere.= ")";
							} else {
								// echo "#".__LINE__." ".__FILE__." NO: this->searchFlds:".$this->searchFlds.", v:".$v["dbField"]."<br>\n";
							}
						}
					}
					
					// Suche in Join-Tables
					foreach($this->arrListJoins as $joinName => $joinProps) {
						// echo "#".__LINE__." \$joinName: $joinName <br>\n";
						if ($searchArea == "*" || $searchArea == $joinName) {
							$tblConfKey = $this->ItemClass->arrConf["Joins"][$joinName]["confkey"];
							$jTbl = $_CONF[$tblConfKey]["Table"];
							$jFlds = $this->arrListJoins[$joinName]["joinListFlds"];
							
							// echo "#".__LINE__." ".gettype($_CONF[$tblConfKey]["Fields"])." = \$_CONF[$tblConfKey][Fields] <br>\n";
							foreach($_CONF[$tblConfKey]["Fields"] as $k => $v) {
								if (1 /*substr($jFlds, -1) == "*" 
								 || is_int(strpos($jFlds, "`{$jTbl}`.`{$v['dbField']}`")) 
								 || is_int(strpos($this->searchFlds, "`{$jTbl}`.`{$v['dbField']}`")) */) {
									if ($strWhere) $strWhere.= ($operator == "LIKE") ? " OR \n" : "AND \n";
									$strWhere.= "(`{$jTbl}`.`".$v["dbField"]."` $operator ";
									$strWhere.= "\"%".str_replace("*", "%", MyDB::escape_string($searchTerm))."%\"";
									if ($operator == "NOT LIKE") $strWhere.= " OR `".$v["dbField"]."` IS NULL";
									$strWhere.= ")";
								}
							}
						}
					}
					break;
					
					default:
					echo "Angegebener Suchoperator $operator kann nicht auf mehrere Felder angewendet werden!<br>\n";
					return false;
				}
			}
		}
		
		if ($strWhere && $this->strBaseQuery) {
			// echo "#".__LINE__." ".$this->strBaseQuery." AND (".$strWhere.")";
			return $this->strBaseQuery." AND (".$strWhere.")";
		}
		// echo "#".__LINE__." ".$this->strBaseQuery.$strWhere;
		return $this->strBaseQuery.$strWhere;
	}
	
	function get_e($fld, $tbl = "") {
		if (empty($fld)) return false;
		if (!$tbl) {
			$this->tmpZahl = array_search("`".$this->ItemClass->arrConf["Table"]."`.`".$fld."`", $this->aFldByNum);
			// echo "#".__LINE__." ".$this->tmpZahl." = array_search(\"`".$this->ItemClass->arrConf["Table"]."`.`".$fld."`\", ".print_r($this->aFldByNum,true).");<br>\n";
			if (!is_int($this->tmpZahl)) {
				
				echo "#".__LINE__." Feld `".$this->ItemClass->arrConf["Table"]."`.`".$fld."` ist nicht im Ergebnis enthalten!<br>\n";
			}
		} else {
			$this->tmpZahl = array_search("`".$tbl."`.`".$fld."`", $this->aFldByNum);
			if (!is_int($this->tmpZahl)) {
				echo "#".__LINE__." print_r(this->aFldByNum): ".print_r($this->aFldByNum, true)."<br>\n";
				echo "#".__LINE__." Feld `".$tbl."`.`".$fld."` ist nicht im Ergebnis enthalten!<br>\n";
			}
		}
		
		if (is_int($this->tmpZahl) && isset($this->e[$this->tmpZahl])) {
			// echo "#".__LINE__." this->tmpZahl:".$this->tmpZahl.", this->e[".$this->tmpZahl."]: ".$this->e[$this->tmpZahl]." <br>\n";
			// echo "#".__LINE__." print_r(this->aFldByNum): ".print_r($this->aFldByNum, true)."<br>\n";
			return $this->e[$this->tmpZahl];
		}
		
		echo "#".__LINE__." Ungültiger Index für Ergebnis: e[".$this->tmpZahl."] fld:$fld, tbl:$tbl existiert nicht!<br>\n";
		return false;
	}
	
	function fit_selectFlds() {
		if ($this->strJoin) {
			$arrSelectFlds = explode(",", $this->selectFlds);
			for ($i = 0; $i < count($arrSelectFlds); $i++) {
				if (!is_int(strpos($arrSelectFlds[$i], "."))) {
					$bt = (substr(trim($arrSelectFlds[$i]), 0, 1) == "`" ? "" : "`"); // Backticks
					if (trim($arrSelectFlds[$i]) != "*") {
						$arrSelectFlds[$i] = "`".$this->ItemClass->arrConf["Table"]."`.{$bt}".trim($arrSelectFlds[$i])."{$bt}";
					} else {
						$arrSelectFlds[$i] = "`".$this->ItemClass->arrConf["Table"]."`.".trim($arrSelectFlds[$i])."";
					}
				}
			}
			$this->selectFlds = implode(",", $arrSelectFlds);
		}
		// $this->arrListJoins[$joinName]["joinListFlds"]
		foreach($this->ItemConf["Joins"] as $joinName => $aJoinConf) {
			$confkey = $aJoinConf["confkey"];
			if (isset($this->TBLS_CONF[$confkey])) {
				$this->selectFlds = str_replace("{".$confkey."}", $this->TBLS_CONF[$confkey]["Table"], $this->selectFlds);
				$this->strJoin = str_replace("{".$confkey."}", $this->TBLS_CONF[$confkey]["Table"], $this->strJoin);
			}
		}
                
        foreach($this->arrListJoins as $joinName => $joinProps) {
            if (trim($joinProps["joinListFlds"])) $this->selectFlds.= (trim($this->selectFlds)?', ':'') . $joinProps["joinListFlds"];
        }
	}
	
	function get_fldConfNameByDbfld($dbfld, $dbtbl) {
		if ($this->ItemConf["Table"] == $dbtbl) {
			foreach ($this->ItemConf["Fields"] as $k => $v) {
			
				if ($dbfld == $v["dbField"]) {
					return $k;
				}
			}
		}
		return false;
	}
	
	function mkList($offset, $size, $orderFld, $orderDir) {
		$hideKey = "";
		$keyFld = $this->ItemClass->arrConf["PrimaryKey"];
		if (!$this->strSQL) {
			$this->fit_selectFlds();
			$this->aSelectFlds = explode(",", $this->selectFlds);
			
			$this->strItemList = "";
			if (is_null($this->ItemClass)) return false;
			if (!is_int(strpos($this->selectFlds, "`".$this->ItemClass->arrConf["Table"]."`"))) {
				$pattern = "$keyFld|*";
				if (!is_int(strpos($this->selectFlds, $keyFld)) && $this->selectFlds != "*" && !is_int(strpos($this->selectFlds, " *"))) $hideKey = $keyFld;
			} else {
				if (!is_int(strpos($this->selectFlds, "`".$this->ItemClass->arrConf["Table"]."`.`$keyFld`")) 
				 && !is_int(strpos($this->selectFlds, "`".$this->ItemClass->arrConf["Table"]."`.*"))) $hideKey = $keyFld;
			}
			
			// $hideKey = ( preg_match("/$pattern/", $this->selectFlds)) ? "" : $keyFld;
			if ($hideKey) $this->arrHideFields[] = "`".$this->ItemClass->arrConf["Table"]."`.`$keyFld`";
			
			$SQL = "SELECT ".( !$hideKey ? '' : "`".$this->ItemClass->arrConf["Table"]."`.`$keyFld`,").$this->selectFlds." FROM ";
			$SQL.= "`".$this->ItemClass->arrConf["Db"]."`.`".$this->ItemClass->arrConf["Table"]."`\n";
			if ($this->strJoin) $SQL.= $this->strJoin." \n";
			elseif (count($this->arrListJoins)) {}
			if ($this->strWhere) $SQL.= "WHERE ".$this->strWhere." \n";
			if ($this->strGroup) $SQL.= "GROUP BY ".$this->strGroup." \n";
		} else {
			$SQL = $this->strSQL;
		}
		
		if (!$orderFld && !empty($this->defaultOrderFld)) {
			$orderFld = $this->defaultOrderFld;
			$orderDir = $this->defaultOrderDir;
		}
		
		if ($orderFld) {
			$SQL.= "ORDER BY $orderFld ";
			if ($orderDir) $SQL.= $orderDir;
			$SQL.= "\n";
		}
		
		if ($size) $SQL.= "LIMIT $offset, $size \n";
		
		if (!empty($this->ItemConf["Joins"])) {
			foreach($this->ItemConf["Joins"] as $joinName => $aJoinConf) {
				$confkey = $aJoinConf["confkey"];
				if (isset($this->TBLS_CONF[$confkey])) {
					$SQL = str_replace("{".$confkey."}", $this->TBLS_CONF[$confkey]["Table"], $SQL);
				}
			}
		}
		$r = MyDB::query($SQL);
		if (0) echo "<pre>#".__LINE__." ERR:".MyDB::error()."\nSQL:".fb_htmlEntities($SQL)." </pre>\n";
		// echo "#".__LINE__." \$this->baseLink: ".$this->baseLink."<br>\n";
                
		if ($r) {
			$this->num = MyDB::num_rows($r);
			if ($this->num) {
				// echo "#".__LINE__." ".basename(__FILE__)."; \$this->num:".$this->num."<br>\n";
				$nf = MyDB::num_fields($r);
				
				$NULL = null;
				$aFldByNum = array();
				$aFldNameToNum = array();
				$aFldNameToCnf = array();
				for ($i = 0; $i < $nf; $i++) {
					$fname = MyDB::field_name($r, $i);
					$tname = MyDB::field_table($r, $i);
					$rFldCnfName = $this->get_fldConfNameByDbfld($fname, $tname);
					if ($rFldCnfName) {
						$aFldToCnf[$i] =  &$this->ItemConf["Fields"][$rFldCnfName];
					} else {
						$aFldToCnf[$i] =  false;
					}
					$this->aFldByNum[$i] = "`{$tname}`.`{$fname}`";
					$aFldNameToNum[$fname] = $i;
					$aFldNameToNum[$tname.".".$fname] = $i;
					if (in_array("`$tname`.`$fname`", $this->arrHideFields)) continue;
					
					$arrShowFieldsById[] = $i;
				}
				
				$actionBaseLink = strtr(
					$this->baseLink, 
					array(
						"{ofld}" => $orderFld,
						"{odir}" => $orderDir
					)
				);
				
				if ($this->ListRenderMode == "Auto") {
					$this->strItemList.= "<table class=\"".$this->tblClass."\" border=1>\n";
					$thead = "";
					$thead.= "<thead>\n<tr>\n";
					$thead.= "<td>#</td>\n";
					$actionCols = count($this->arrListButtons);
					if ($actionCols) {
						$thead.= "<td colspan=$actionCols>Aktionen(".$actionCols.")</td>\n";
					}
					foreach ($this->arrListInput as $el) {
						$thead.= "<td>".$el["label"]."</td>\n";
					}
					
					for ($t = 0; $t < count($arrShowFieldsById); $t++) {
						$i = $arrShowFieldsById[$t];
						$fname = MyDB::field_name($r, $i);
						$tname = MyDB::field_table($r, $i);
                        $colLabel = $fname;
						if (!@empty($this->ItemConf['Fields'][$fname]['listlabel'])) {
                            $colLabel = $this->ItemConf['Fields'][$fname]['listlabel'];
                        }
						if (isset($this->arrColNameHandler[$fname])) {
						    $cnameFunc = $this->arrColNameHandler[$fname];
						    try {
						        $colLabel = $cnameFunc($fname, $this->num, $this->numAll);
                            } catch(Exception $e) {
						        // Nothing
                            }
                        }
						// echo "#".__LINE__." fname:$fname, tname:$tname <br>\n";
						
						$link = strtr(
							$this->baseLink, 
							array(
								"{ofld}" => $fname, 
								"{odir}" => $this->get_oDir($fname, $orderFld, $orderDir)
							)
						);
						$link.= "&{trackVars}";
						$thead.= "<td><a href=\"$link\">$colLabel</a></a></td>\n";
					}
					// 
					// $this->arrKeyRplInList[$tbl][$key] = array("tbl" => "", "fld" => "");
					$wz = "";
					$thead.= "</tr>\n</thead>\n";
					$this->strItemList.= $thead;
				}
				$nr = 0;
				while($this->e = MyDB::fetch_array($r, MyDB::BOTH)) {
					$nr++;
					
					if ($this->ListRenderMode == "Function") {
						$this->strItemList.= call_user_func(
							$this->ListFunction,
							$this->e,
							$r,
							$this->num,
							$nr,
							$this->ListTemplate
						);
						continue;
						
					} elseif($this->ListRenderMode == "Template") {
						$LNKEDIT = $actionBaseLink."&id=".rawurlencode($this->get_e($keyFld))."&".$this->arrListButtons["open"]["link"];
						$LNKDROP = $actionBaseLink."&id=".rawurlencode($this->get_e($keyFld))."&".$this->arrListButtons["kill"]["link"];
						$tmp = $this->ListTemplate;
						$tmp = str_replace("{#RESULT-NR}", $nr, $tmp);
						$tmp = str_replace("{#RESULT-NUM}", $this->num, $tmp);
						$tmp = str_replace("{#NR}", $nr+$offset, $tmp);
						$tmp = str_replace("{#NUM}", $this->numAll, $tmp);
						$tmp = str_replace("{#LNKEDIT}", $LNKEDIT, $tmp);
						$tmp = str_replace("{#LNKDROP}", $LNKDROP, $tmp);
						
						$tmp = str_replace("{#OFFSET}", $offset, $tmp);
						$tmp = str_replace("{#SIZE}", $size, $tmp);
						$tmp = str_replace("{#OFLD}", $orderFld, $tmp);
						$tmp = str_replace("{#ODIR}", $orderDir, $tmp);
						
						foreach($this->e as $fk => $fv) {
							$tmp = str_replace("{".$fk."}", $fv, $tmp);
							$tmp = str_replace("{urlencode[".$fk."]}", urlencode($fv), $tmp);
							$tmp = str_replace("{input[".$fk."]}", fb_htmlEntities($fv), $tmp);
						}
						$this->strItemList.= $tmp;
						$tmp = "";
						continue;
					}
					
					$wz = ($wz != "wz1" ? "wz1" : "wz2");
					$this->strItemList.= "<tr class=\"{$wz}\">";
					$this->strItemList.= "<td align=\"right\">".($offset+$nr)."</td>\n";
					// $aFldByNum: array_search("`$tbl`.`$fld`", $aFldByNum)
					// echo "#".__LINE__.' intval($blnOpenLink):'.intval($blnOpenLink)."<br>\n";
					// echo "#".__LINE__." count(\$this->arrListButtons):".count($this->arrListButtons)."<br>\n";
					foreach($this->arrListButtons as $btnName => $btnVals) {
						$this->strItemList.= "<td>\n";
						if (!empty($btnVals["createByFunction"])) {
							if (function_exists($btnVals["createByFunction"])) {
								$this->strItemList.= call_user_func(
									$btnVals["createByFunction"],
									$val,
									$this->e,
									$tbl,
									$fld,
									$cellClass,
									$cellAttr,
									$nr
								);
							} else {
								echo "#".__LINE__." Error undefined Function '".$btnVals["createByFunction"]."()' für ListButton!<br>\n";
							}
						} else {
							$link = "#";
							if ($btnVals['link']) {
								if (substr($btnVals['link'], 0, 1) != "=") {
									// echo "#".__LINE__." \$keyFld:$keyFld <br>\n";
									$link = $actionBaseLink."&id=".rawurlencode($this->get_e($keyFld))."&".$btnVals['link'];
								} else {
									$link = substr($btnVals['link'], 1);
								}
								
								for ($i = 0; $i < count($btnVals["tplVars"]); $i++) {
									$t = &$btnVals["tplVars"][$i];
									if ($this->get_e($t) !== false) {
										$link = str_replace("{".$t."}", $this->get_e($t), $link);
										$btnVals["innerHTML"] = str_replace("{".$t."}", $this->get_e($t), $btnVals["innerHTML"]);
										$btnVals["beforeHTML"] = str_replace("{".$t."}", $this->get_e($t), $btnVals["beforeHTML"]);
										$btnVals["behindHTML"] = str_replace("{".$t."}", $this->get_e($t), $btnVals["behindHTML"]);
									}
								}
							}
							
							$this->strItemList.= "{$btnVals['beforeHTML']}<a href=\"$link\"";
							if (isset($btnVals["events"]) && count($btnVals["events"])) {
								foreach($btnVals["events"] as $event => $v) {
									$fncArgs = "";
									if ($v['flds']) {
										for($i = 0; $i < count($v['flds']); $i++) {
											$fncArgs.= ($i ? "," : "")."'".($this->get_e($v['flds'][$i]) !== false ? rawurlencode($this->get_e($v['flds'][$i])) : "")."'";
										}
									}/**/
									$this->strItemList.= " {$event}=\"".str_replace("{[ARGS]}", $fncArgs, $v['fnc'])."\"";
								}
							}
							$this->strItemList.= " {$btnVals['innerHTML']}>{$btnVals['label']}</a>{$btnVals['behindHTML']}";
						}
						$this->strItemList.= "</td>\n";
					}
					
					foreach ($this->arrListInput as $el) {
						$this->strItemList.= "<td>";
						if (!empty($el["createByFunction"])) {
							if (function_exists($el["createByFunction"])) {
								$this->strItemList.= call_user_func(
									$el["createByFunction"],
									$val,
									$this->e,
									$tbl,
									$fld,
									$cellClass,
									$cellAttr,
									$nr
								);
							} else {
								echo "#".__LINE__." Error undefined Function '".$el["createByFunction"]."()' für ListInput!<br>\n";
							}
						} else {
							$rplEl = array();
							// echo "#".__LINE__." 1:".$el["setDbFldVal"].", 2:".$aFldNameToNum[$el["setDbFldVal"]]."<br>\n";
							if ($el["setDbFldVal"] && isset($aFldNameToNum[$el["setDbFldVal"]])) {
								$el["html"] = str_replace("{".$el["setDbFldVal"]."}", $this->e[$aFldNameToNum[$el["setDbFldVal"]]], $el["html"]);
							}
							$el["html"] = str_replace("{nr}", $nr, $el["html"]);
							$this->strItemList.= $el["html"];
						}
						$this->strItemList.= "</td>\n";
					}
					
					for ($t = 0; $t < count($arrShowFieldsById); $t++) {
						$i = $arrShowFieldsById[$t];
						
						$tbl = MyDB::field_table($r, $i);
						$fld = MyDB::field_name($r, $i);
						
						$val = &$this->e[$i];
						$SetRawDbVal = true;
						if (isset($this->arrColumnHandler[$fld])) {
							if(function_exists($this->arrColumnHandler[$fld])) {
								 $cellClass = $fname . ' ' . $aFldToCnf[$i]["sysType"];
								 $cellAttr = '';
								 $cbVal = call_user_func_array(
								 	$this->arrColumnHandler[$fld],
									[
									    &$val,
                                        &$this->e,
                                        $tbl,
                                        $fld,
                                        &$cellClass,
                                        &$cellAttr,
                                        $nr
                                    ]
								);
								 if (is_string($cbVal)) {
								     $val = $cbVal;
                                 }
								$this->strItemList.= "<td class=\"{$cellClass}\" {$cellAttr}>".$val."</td>\n";
								$SetRawDbVal = false;
							} else {
								echo "#".__LINE__." ".__FILE__." Funktion ".$this->arrColumnHandler[$fld]." für Feld $fld nicht gefunden!<br>\n";
							}
						}
						if ($SetRawDbVal) {
							$tdClass = $fld;
							if ($aFldToCnf[$i]["sysType"]) $tdClass.= " ".$aFldToCnf[$i]["sysType"];
							$this->strItemList.= "<td class=\"{$tdClass}\">".$val."</td>\n";
						}
					}
					$this->strItemList.= "</tr>";
					
				}
				if ($this->ListRenderMode == "Auto") $this->strItemList.= "</table>\n";
							}
			MyDB::free_result($r);
		} else {
			echo MyDB::error()."<br>\n";
			echo $SQL."<br>\n";
		}
		return $this->strItemList;
	}
}


class ItemEdit
{
	var $classname = "ItemEdit";
	var $arrConf = array();
	var $arrDbdata = array();
	var $arrInput = array();
	var $arrPresetValues = array();
	var $id = false;
	var $itemExists = false;
	var $editMode = "Insert";
	var $editCmd = "Edit"; // Read | Edit | Preview | Correct | Save
	var $Error = "";
	var $Warning = "";
	var $dbError = "";
	var $Msg = "";
	var $SysError = "";
	var $arrErrFlds = array();
	var $dbConnId = "";
	var $lastQuery = '';
	var $checkRowAccess = false;
	var $checkColAccess = false;
	var $uname = "";
	var $uid = false;
	var $userRechte = false;
	var $dokUid = false;
	var $dokRechte = false;
	var $tplForm = "";
	var $output = "";
	var $classValidate = false;
	var $autoFormBuilder = "file"; // file, html, render
	var $Labels = array();
	
	function __construct(&$conf, $connid, $user, $id = false) 
	{
		// echo "#".__LINE__." connid:$connid, rechte:$userRechte, id:$id <br>\n";
		$this->set_dbConnId($connid);
		$this->loadConfByArray($conf);
		if (is_scalar($user)) {// user = rechte
			$this->set_userRechte($user);
		} elseif (is_array($user)) {
			if ($user["rechte"]) $this->set_userRechte($user["rechte"]);
			if ($user["uid"])    $this->set_uid($user["uid"]);
			if ($user["user"])   $this->set_uname($user["user"]);
		}
		$this->checkType = new checkType;
		if ($id) {
			// echo "#".__LINE__." connid:$connid, rechte:$userRechte, id:$id <br>\n";
			$this->id = $id;
			$this->editMode = "Update";
			$this->editCmd = "Edit";
			$this->checkIfExists();
			if ($this->itemExists) {
				$this->loadDbdata();
			} else {
				$this->Error.= "#".__LINE__." Es existiert kein Eintrag mit der ID: {$this->id}<br>";
			}
		} else {
			if ($user["uid"]) $this->set_dokUid($user["uid"]);
		}
		$this->set_defaultLabels();
	}
	
	function set_dbConnId($connid) { $this->dbConnId = $connid; }
	
	function set_userRechte($userRechte)    { $this->userRechte = $userRechte;     }
	function set_uid($uid)    { $this->uid = $uid;     }
	function set_uname($uname)    { $this->uname = $uname;     }
	function get_userRechte()    { return $this->userRechte;   }
	function get_uid()    { return $this->uid;   }
	function get_uname()    { return $this->uname;   }
	/*
	*/
	function set_dokRechte($dokRechte)    { $this->dokRechte = $dokRechte;     }
	function set_dokUid($dokUid)    { $this->dokUid = $dokUid;     }
	function get_dokRechte() { return $this->dokRechte; }
	function get_dokUid() { return $this->dokUid; }
        
        function &getElementByVarPath(&$var, &$pathElements, $offset, &$err) {
            $null = null;
            $err = '';
            
            if (empty($var)) {
                $err = 'BaseVar ist leer!';
                return $null;
            }
            if ($offset >= count($pathElements)) {
                $err = 'Ungueltiger VarPath-Offset: ' . $offset . '. Anzahl PathElements: ' . count($pathElements).'!';
            }
            
            if (empty($pathElements)) {
                return $var;
            }
            
            if (!isset($var[ $pathElements[$offset] ])) {
                $err = 'VarPath ungueltig: ' . implode('.', array_slice($pathElements, 0, $offset+1)).'!';
                return $null;
            }
            
            if ($offset+1 === count($pathElements)) {
                return $var[ $pathElements[$offset] ];
            }
            return $this->getElementByVarPath($var[ $pathElements[$offset] ], $pathElements, $offset+1, $err);
        }
        
        
        function replaceTplVars($str) {
            global $_TABLE;
            global $_CONF;
            global $MConf;
            
            $this->cachedTplVars = array();
            if ( false !== strpos($str, '{')) {
                if (preg_match_all('/{(.+?)}/', $str, $matches)) {
                    for($i = 0; $i < count($matches[1]); ++$i) {
                        if (isset($this->cachedTplVars[ $matches[1][$i] ])) {
                            $str = str_replace($matches[0][$i], $this->cachedTplVars[ $matches[1][$i] ], $str);
                            continue;
                        }
                        
                        $pathElements = explode('.', $matches[1][$i]);
                        
                        $err = '';
                        $errors = '';
                        $rpl = '';
                        switch($pathElements[0]) {
                            case 'TABLE':
                                if (isset($_TABLE[$pathElements[1]])) {
                                    $rpl = $_TABLE[$pathElements[1]];
                                } else {
                                    $errors.= 'PATH NOT FOUND: ' . $matches[1][$i] . '<br>' . PHP_EOL;
                                }
                                break;
                            
                            case 'SELF':
                                $rpl = $this->getElementByVarPath($this->arrConf, $pathElements, 1, $err);
                                if ($err) $errors.= $err . '<br>' . PHP_EOL;
                                break;
                            
                            case 'CONF':
                                $rpl = $this->getElementByVarPath($_CONF, $pathElements, 1, $err);
                                if ($err) $errors.= $err . '<br>' . PHP_EOL;
                                break;
                            
                            case 'MConf':
                                $rpl = $this->getElementByVarPath($MConf, $pathElements, 1, $err);
                                if ($err) $errors.= $err . '<br>' . PHP_EOL;
                                break;
                            
                            default:
                                $err = '#'.__LINE__ . ' ' . __METHOD__ . '(): IGNORE UNREGISTERED TPL-VAR ' . $pathElements[0];
                        }
                        if (!$err) {
                            $this->cachedTplVars[ $matches[1][$i] ] = $rpl;
                            $str = str_replace($matches[0][$i], $rpl, $str);
                        }
                    }
                }
                // die('#'.__LINE__ . ' ' . __FILE__ . ' ' . __METHOD__ . '<br> str:' . $str . '<br>matches: ' . print_r($matches,1) . '<br>errors:' . $errors);
            }
            return $str;
        }
	
	function loadConfByArray(&$conf) {
		global $_TABLE;
                global $MConf;
                if (empty($conf["Db"])) $conf["Db"] = $MConf['DB_Name'];
                elseif (false !== strpos( $conf['Db'], '{default}')) {
                    $conf["Db"] = str_replace('{default}',$MConf['DB_Name'], $conf['Db']);
                }
                
		if (!is_array($conf) || !isset($conf["Fields"])) {
                    $this->Error.= "#".__LINE__." Ungültige oder leere Konf-Daten!<br>\n";
                    return false;
                }
                
                $this->arrConf = $conf;
                
                foreach($this->arrConf['Fields'] as $fN => $fC) {
                    if (!empty($fC["sql"])) {
                        $this->arrConf['Fields'][$fN]['sql'] = $this->replaceTplVars($fC["sql"]);
                    }
                }
                
                
                foreach($this->arrConf['Joins'] as $jN => $jC) {
                    foreach($jC as $jjN => $jjC) {
                        switch($jjN) {
                            case 'key':
                            case 'foreignTbl':
                            case 'foreignKey':
                            case 'listFields':
                            case 'listHideKey':
                            case 'listHideFlds':
                            if (!empty($jjC) && is_string($jjC)) {
                                $this->arrConf['Joins'][$jN][$jjN] = $this->replaceTplVars($jjC);
                            }
                        }
                    }
                }
                
                foreach($this->arrConf['Lists'] as $lN => $lC) {
                    foreach($lC as $llN => $llC) {
                        switch($llC) {
                            case 'select':
                            case 'from':
                            case 'join':
                            case 'where':
                            case 'group':
                            case 'having':
                            case 'defaultOrderFld':
                            if (!empty($llC) && is_string($llC)) {
                                $this->arrConf['Lists'][$lN][$llN] = $this->replaceTplVars($llC);
                            }
                        }
                    }
                }               
                
                
                foreach($this->arrConf["Fields"] as $fN => $fC) {
                        if (!empty($fC["sql"])) {
                                $size = "";
                                if (!$fC['required']) $size='=';
                                $r = MyDB::query($fC["sql"], $this->dbConnId);
                                if ($r) { 
                                    //die('#' . __LINE__ . ' ' . $fC["sql"] . "\nrows:".MyDB::num_rows($r));
                                    for($i = 0; $i < MyDB::num_rows($r); $i++) {
                                            $e = MyDB::fetch_array($r, MyDB::NUM);
                                            $size.= ( $size ?",":"")."'".addslashes($e[0]).(isset($e[1])?"=".addslashes($e[1])."'":"'");
                                    }
                                    $this->arrConf["Fields"][$fN]["size"] = $size;
                                } else {
                                    die('#' . __LINE__ . ' ' . $fC["sql"] . "\nerror:".MyDB::error());
                                }
                        }
                }
                return true;
	}
	
	function set_editCmd($editCmd) {
		$this->editCmd = $editCmd;
	}
	
	function set_editMode($editMode) {
		$this->editMode = $editMode;
	}
	
	function checkIfExists() {
		if ($this->id !== false) {
			$SQL = "SELECT ".$this->arrConf["PrimaryKey"]."\n";
			$SQL.= " FROM `".$this->arrConf["Db"]."`.`".$this->arrConf["Table"]."`\n";
			$SQL.= " WHERE ".$this->arrConf["PrimaryKey"]." = ";
			if ($this->arrConf["Fields"][$this->arrConf["PrimaryKey"]]["type"] == "int" && is_int($this->id)) {
				$SQL.= $this->id;
			} else {
				$SQL.= "\"".MyDB::escape_string($this->id)."\"";
			}
			$r = $this->db_query($SQL, __LINE__);
			if ($r) {
				$n = MyDB::num_rows($r);
				$this->db_free_result($r);
				if ($n) {
					$this->itemExists = true;
				} else {
					$this->itemExists = false;
				}
				return $this->itemExists;
			}
			$this->Error.= "Es wurde kein Eintrag mit der ID:".$this->id." gefunden!<br>\n";
			
		} else $this->Error.= "Fehlende ID:".$this->id." für Datenabfrage!<br>\n";
		return false;
	}
	
	function loadDbdata() {
		if ($this->id !== false) {
			$SQL = "SELECT * FROM `".$this->arrConf["Db"]."`.`".$this->arrConf["Table"]."` \n";
			$SQL.= "WHERE ".$this->arrConf["PrimaryKey"]." = ";
			if ($this->arrConf["Fields"][$this->arrConf["PrimaryKey"]]["type"] == "int" && is_int($this->id)) {
				$SQL.= $this->id;
			} else {
				$SQL.= "\"".MyDB::escape_string($this->id)."\"";
			}
			$r = $this->db_query($SQL);
			if ($r) {
				$n = MyDB::num_rows($r);
				$this->arrDbdata = MyDB::fetch_array($r, MYSQL_ASSOC);
				$this->db_free_result($r);
				return true;
			}
		}
		return false;
	}
	
	function dbdataToInput($autoload = true) {
            global $msg;
            if (empty($this->arrDbdata) && $autoload) {
                $this->loadDbdata();
            }

            if (count($this->arrDbdata) || $this->loadDbdata()) {
                if (is_array($this->arrConf["Fields"]) && count($this->arrConf["Fields"]) ) {
                    $this->arrInput = array();
                    foreach($this->arrConf["Fields"] as $fN => $fC) {
                        $this->arrInput[$fN] = ($fC["dbField"] && !empty($this->arrDbdata[$fC["dbField"]])) ? $this->arrDbdata[$fC["dbField"]] : "";
                    }
                    //$msg.= print_r($this->arrConf,1)."<br>\r\n";
                    if (count($this->arrInput)) { return true; }
                    else { $this->Error.= "Es konnten keine DB-Daten übernommen werden!<br>\n"; }
                } else { $this->Error.= "Es wurde keine Feldkonfiguration geladen werden!<br>\n"; }
            } else { $this->Error.= "Eintrag aus Datenbank konnte nicht geladen werden!<br>\n"; }
            return false;
	}
	
	// überschreibbare Werte.
	// Mit setValue gesetzte Werte dürfen erst nach
	// -loadInput()
	// -dbdataToInput()
	// gesetzt, da sie sonst wieder überschrieben werden
	function setValue($f, $v) {
		$this->arrInput[$f] = $v;
		if (count($this->arrDbdata)) {
			$this->arrDbdata[$this->arrConf["Fields"][$f]["dbField"]] = $v;
		}
	}
	
	function set_defaultLabels() {
		if (empty($this->arrConf["Labels"]["gotoNextInsert"])) {
			$this->Labels["gotoNextInsert"] = "Anschlie&szlig;end neuen Eintrag anlegen";
		} else {
			$this->Labels["gotoNextInsert"] = $this->arrConf["Labels"]["gotoNextInsert"];
		}
	}
	
	// Fest voreingestellte Werte, die durch Posts nicht überschrieben werden können,
	// aber von dbdataToInput
	function presetValue($f, $v) {
		$this->arrPresetValues[$f] = true;
		$this->setValue($f, $v);
	}
	
	function loadInput($Input = array(), $checkInput = true) { 
		foreach($Input as $f => $v) {
			if (empty($this->arrPresetValues[$f])) $this->arrInput[$f] =  $v;
		}
		
		foreach($this->arrConf["Fields"] as $fN => $fC) {
			if (!isset($this->arrInput[$fN])) {
				switch($this->editMode) { //
					case "Insert":
					$this->arrInput[$fN] = $fC["default"];
					break;
					
					case "Update":
                                        
                                            if (!empty($fC["dbField"]) && !empty($this->arrDbdata[$fC["dbField"]])) {
                                                    $this->arrInput[$fN] = $this->arrDbdata[$fC["dbField"]];
                                            }
                                        
					break;
					
					default:
					$this->SysError.= "#".__LINE__." ".basename(__FILE__)." für Daten ".$this->arrConf["Title"]."!<br>\n";
					$this->Error.= "Interner Fehler: <br>\n";
				}
			}
		}
		
		if ($checkInput) {
			// echo "#".__LINE__." call checkInput by ".__FUNCTION__."()<br>\n";
			$this->checkInput();
		}
	}
	
	function openTplForm($path) {
		if ($path) {
			if (file_exists($path)) {
				$this->tplFormFile = $path;
				$this->tplForm = implode("", file($this->tplFormFile));
			} else $this->Error.= "Template-Datei existiert nicht:{$path}!<br>\n";
		} else $this->Error.= "Es wurde keine Template Datei zum öffnen angegeben!<br>\n";
	}
	
	function loadTplForm($text) {
		$this->tplForm = $text;
	}
	
	function generateAutoForm() { // view: insert,edit,correct,read
		
		foreach($this->arrConf["Fields"] as $fN => $fC) {
			// $this->editMode // Insert | Update
			// $this->editCmd // Read | Edit | Preview | Correct | Save
			// $this->userRechte 
			
			$fmod = $this->editCmd;
			switch($this->editMode.":".$this->editCmd) {
				case "Insert:Read":
				case "Update:Read":
				case "Insert:Save":
				case "Insert:Save":
				// if (!$this->userHasFldAccess($fN, $this->editMode)) $fmod = NULL;
				if ($this->userRechte < $fC["readMinAccess"]) $fmod = NULL;
				break;
				
				case "Insert:Edit":
				case "Insert:Preview":
				case "Insert:Correct":
				if ($this->userRechte < $fC["insertMinAccess"]) $fmod = NULL;
				break;
				
				case "Update:Edit":
				case "Update:Preview":
				case "Update:Correct":
				if ($this->userRechte < $fC["updateMinAccess"]) 
					$fmod = ($this->userRechte >= $fC["readMinAccess"]) ? "Read" : NULL;
				break;
				
			}
		}
	}
	
	function get_dynFormByAccess() {
	
	}
	
	function setEditForm($action = "", $inputArrayName = "eingabe", $maskierung = "{") {
		$this->tplForm = str_replace( "{action}", $action, $this->tplForm);
		$this->tplForm = str_replace( "{id}", ($this->id !== false ? fb_htmlEntities($this->id) : ''), $this->tplForm);
		$arrRpl = array();
		$cssPflichtIds = "";
		
		
		if ($maskierung == "{") {
			$m1 = "{";
			$m2 = "}";
		} else list($m1, $m2) = array($maskierung, $maskierung);
		
		$pre = ($inputArrayName) ? $inputArrayName."[" : "";
		$suf = ($inputArrayName) ? "]" : "";
		$NULL = NULL;
		
		foreach($this->arrErrFlds as $fN => $fErr) {
			$this->tplForm = str_replace( "errclass=\"{$fN}\"", "class=\"lblInputError\"", $this->tplForm);
		}
		
		// echo "#".__LINE__." ".basename(__FILE__)." \$this->arrDbdata:".print_r($this->arrDbdata, true)."<br>\n";
		foreach($this->arrConf["Fields"] as $fN => $fC) {
			$fldAccess = $this->userHasFldAccess($fN, $this->editMode);
			
			$val = &$NULL;
			$needle = $m1.$pre.$fN.$suf.$m2;
			if ($fC["required"]) {
				$cssPflichtIds.= ($cssPflichtIds ? ",\n":"")."#p".$fN;
			}
			
			if ($fldAccess) {
				switch($this->editCmd) {
					case "Edit":
					if ($this->editMode == "Update") {
						// UPDATE-Mode
						if ( isset($this->arrDbdata[$fC["dbField"]]))  {
							$val = &$this->arrDbdata[$fC["dbField"]];
						} //else echo "#".__LINE__." $fN <br>\n";
					} else {
						// INSERT-Mode
						// echo "#".__LINE__." ".basename(__FILE__)." $fN <br>\n";
						if (!isset($this->arrInput[$fN])) {
							if ( isset($fC["default"])) $val = &$fC["default"];
						} else  {
							$val = &$this->arrInput[$fN];
						} // else echo "#".__LINE__." $fN <br>\n";
					}
					break;
					
					case "Correct":
					case "Preview":
					case "Save":
					if ( isset($this->arrInput[$fN])) {
						$val = &$this->arrInput[$fN];
					}
					break;
					
					default:
					// echo "#".__LINE__." $fN <br>\n";
				}
				// echo "<div>" . json_encode(compact('fN', 'val', 'needle')) . "</div>\n";
				if (!empty($fC["formatEingabeFunction"]) && function_exists($fC["formatEingabeFunction"])) {
					call_user_func_array(
						$fC["formatEingabeFunction"],
						[
						    $this->editCmd,
                            $fN,
                            $val,
                            $fC,
                            $this->arrDbdata,
                            $this->arrInput,
                            &$this->tplForm,
                            $needle,
                            ]
					);
					// echo "<div style=\"border:1px solid #00f;\">#".__LINE__." tplForm AfterReplace:".fb_htmlEntities($this->tplForm)."</div>\n";
				}
				
				switch($fC["sysType"]) {
					case "password":
					$fN_wh = $fN."_wh";
					$needle_wh = $m1.$pre.$fN_wh.$suf.$m2;
					$val_wh = (isset($this->arrInput[$fN_wh])) ? $this->arrInput[$fN_wh] : (isset($this->arrInput[$fN])?$this->arrInput[$fN]:'');
					switch($this->editCmd) {
						case "Correct":
						case "Preview":
						$this->tplForm = str_replace( $needle, fb_htmlEntities($val), $this->tplForm);
						$this->tplForm = str_replace( $needle_wh, fb_htmlEntities($val_wh), $this->tplForm);
						break;
						
						default:
						$this->tplForm = str_replace( $needle, "", $this->tplForm);
						$this->tplForm = str_replace( $needle_wh, "", $this->tplForm);
					}
					break;
					
					case "set":
					$hiddenSetMarker = "<!-- {HiddenSet_".$pre.$fN.$suf."} -->";
					$hiddenSetValues = "";
					$aSetVals = (is_array($val)) ? $val : explode(",", $val);
					foreach($aSetVals as $setVal) {
						$hiddenSetValues.= "<input LINE=\"".__LINE__."\" type=\"hidden\" name=\"".$pre.$fN.$suf."[]\" value=\"".fb_htmlEntities($setVal)."\">\n";
					}
					$this->tplForm = str_replace( $hiddenSetMarker, $hiddenSetValues, $this->tplForm);
					break;
					
					default:
					// Nothing
				}
				
				if (is_null($val) || $val === "") {
					$this->tplForm = str_replace( $needle, "", $this->tplForm);
				} elseif (!is_array($val)) {
					$this->tplForm = str_replace( $needle, fb_htmlEntities($val), $this->tplForm);
				} else {
					$this->tplForm = str_replace( $needle, fb_htmlEntities(implode(",", $val)), $this->tplForm);
				}
				
				switch($fC["htmlType"]) {
					case "radio":
					case "select":
					case "select single":
					case "select multiple":
					case "checkbox":
					$checked = (preg_match("/select/", $fC["htmlType"])) ? "selected=\"true\"" : "checked=\"true\"";
					$needle_generate = "<!-- ".$m1."options_".$fN.$m2." -->";
					//echo fb_htmlEntities($needle_generate)."<br>\n";
					if (!strpos($this->tplForm, $needle_generate)) {
						
						if (!is_null($val) && !empty($val)) {
							if (!is_array($val) && $fC["sysType"] != "set") {
								$needle = "check_".$fN."=\"".$val."\"";
								//echo "#".__LINE__." ".basename(__FILE__)." needle:".$needle."<br>\n";
								$this->tplForm = str_replace( $needle, $checked, $this->tplForm );
							} else {
								$aSetVal = (is_array($val)) ? $val : explode(",", $val);
								for ($i = 0; $i < count($aSetVal); $i++) {
									$needle = "check_".$fN."=\"".$aSetVal[$i]."\"";
									$this->tplForm = str_replace( $needle, $checked, $this->tplForm );
									
									//check_standortverwaltung="Düsseldorf"
									//check_standortverwaltung="Düsseldorf"
								}
							}
						} else {
							$this->tplForm = str_replace( "check_".$fN."=\"\"", $checked, $this->tplForm );
						}
						$this->tplForm = str_replace( $needle, $checked, $this->tplForm );
					} else {
						$htmlOpt = "";
						$opt = ($fC["size"][0] == "'") ? explode("','", substr($fC["size"],1,-1)) : explode(",", $fC["size"]);
						//echo "#".__LINE__." ".__FILE__." fC[size]".$fC["size"]."<br>\n";
						for ($i = 0; $i < count($opt); $i++) {
							if (count(explode("=", $opt[$i])) > 1) {
								$o_key = array_shift(explode("=", $opt[$i]));
								$o_title = implode("=",array_slice(explode("=", $opt[$i]),1));
								//echo "#".__LINE__." ".__FILE__." opt:".$opt[$i].", o_key:$o_key, o_title:$o_title, val:$val<br>\n";
							} else {
								$o_key = $opt[$i];
								$o_title = $opt[$i];
								//echo "#".__LINE__." ".__FILE__." opt:".$opt[$i].", o_key:$o_key, o_title:$o_title<br>\n";
							}
							if (!is_null($val) && !empty($val)) {
								if (!is_array($val)) {
									$checked = ($o_key == $val);
									//echo "#".__LINE__." ".__FILE__." checked:".($checked?"TRUE":"FALSE")."<br>\n";
								} else $checked = in_array($opt[$i], $val);
							} else $checked = false;
							
							switch($fC["htmlType"]) {
								case "radio":
								case "checkbox":
								$htmlOpt.= "<input LINE=\"".__LINE__."\" type=\"".$fC["htmlType"]."\" name=\"".$pre.$fN.$suf."";
								if ($fC["htmlType"] == "checkbox") $htmlOpt.= "[]";
								$htmlOpt.= "\" value=\"".fb_htmlEntities($o_key)."\"";
								if ($checked) $htmlOpt.= " checked=\"true\"";
								$htmlOpt.= "> ".$o_title;
								break;
								
								case "select":
								case "select single":
								case "select multiple":
								$htmlOpt.= "<option value=\"".fb_htmlEntities($o_key)."\"";
								if ($checked) $htmlOpt.= " selected=\"true\"";
								$htmlOpt.= "> ".$o_title."</option>\n";
								break;
							}
						}
						$this->tplForm = str_replace($needle_generate, $htmlOpt, $this->tplForm);
					}
					break;
					

                    case 'file':
                        break;

					case "text":
					case "textarea":
					default:
					// Nothing
				}
			} else {
				$this->tplForm = str_replace( $needle, "", $this->tplForm);
			}
		}
		$this->tplForm = str_replace("/*cssPflichtIds*/", $cssPflichtIds, $this->tplForm);
		
	}
	
	
	function setReadForm($action = "", $inputArrayName = "lesen", $maskierung = "{") {
		
		$this->tplForm = str_replace( "{action}", $action, $this->tplForm);
		$this->tplForm = str_replace( "{id}", ($this->id !== false ? fb_htmlEntities($this->id) : ''), $this->tplForm);
		if ($maskierung == "{") {
			$pre = "{";
			$suf = "}";
		} else {
			list($pre, $suf) = array($maskierung, $maskierung);
		}
		if ($inputArrayName) {
			$pre.=  $inputArrayName."[";
			$suf = "]".$suf;
		}
		
		$NULL = NULL;
		foreach($this->arrConf["Fields"] as $fN => $fC) {
			$val = &$NULL;
			$needle = $pre.$fN.$suf;
			$fldReadAccess = $this->userHasFldAccess($fN, "Read");
			
			if ($this->editMode == "Update") {
				$fldUpdateAccess = $this->userHasFldAccess($fN, "Update");
				switch($this->editCmd) {
					case "Preview":
					if ($fldUpdateAccess) {
						if ( isset($this->arrInput[$fN])) $val = &$this->arrInput[$fN];
					} else {
						if ( isset($this->arrDbdata[$fC["dbField"]]))  $val = &$this->arrDbdata[$fC["dbField"]];
					}
					break;
					
					default:
					if ( isset($this->arrDbdata[$fC["dbField"]]))  $val = &$this->arrDbdata[$fC["dbField"]];
					break;
				}
			} else {
				$fldInsertAccess = $this->userHasFldAccess($fN, "Insert");
				switch($this->editCmd) {
					case "Edit":
					if ($fldInsertAccess && isset($fC["default"])) $val = &$fC["default"];
					break;
					
					case "Preview":
					case "Correct":
					if ($fldInsertAccess) {
						if ( isset($this->arrInput[$fN])) $val = &$this->arrInput[$fN];
					} elseif ($fldReadAccess) {
						if ($fldInsertAccess && isset($fC["default"])) $val = &$fC["default"];
					}
					case "Save":
					case "Read":
					if ($fldReadAccess) {
						if ( isset($this->arrDbdata[$fC["dbField"]]))  $val = &$this->arrDbdata[$fC["dbField"]];
					}
					break;
				}
			}
			
			if ($this->userHasFldAccess($fN, "Read")) {
				/*switch($this->editCmd) {
					case "Edit":
					echo "<b>#".__LINE__."</b> <br>\n";
					if ($this->editMode == "Update") {
						if ( isset($this->arrDbdata[$fC["dbField"]]))  $val = &$this->arrDbdata[$fC["dbField"]];
					} else {
						if ( isset($fC["default"]))  $val = &$fC["default"];
					}
					break;
					
					case "Correct":
					echo "<b>#".__LINE__."</b> <br>\n";
					if ( isset($this->arrInput[$fN])) $val = &$this->arrInput[$fN];
					break;
				}*/
				
				if (!empty($fC["formatLesenFunction"]) && !function_exists($fC["formatLesenFunction"])) {
					echo "#".__LINE__." missing function ".$fC["formatLesenFunction"]." <br>\n";
				}
				
				if (!empty($fC["formatLesenFunction"]) && function_exists($fC["formatLesenFunction"])) {
					
					call_user_func(
						$fC["formatLesenFunction"],
						$this->editCmd,
						$fN,
						$val,
						$fC,
						$this->arrDbdata, 
						$this->arrInput,
						$this->tplForm, 
						$needle
					);
					
				} else {
					$this->tplForm = str_replace(
						$needle,
						$this->checkType->formatDataToRead($val, $fC["type"], $fC["size"]),
						$this->tplForm);
				}
			} else {
				$this->tplForm = str_replace( $needle,	"&nbsp;",	$this->tplForm);
			}
		}
	}
	
	// Hierarchische Benutzerkennzahlen
	// für vierstufigen Zugriff:
	// - readMinAccess
	// - insertMinAccess
	// - updateMinAccess
	// - deleteMinAccess
	// Kennzahlen
	// 0 -> unregistered User
	// 1 -> registered User
	// u: Explizite Nennung kommagetrennter Uids
	// 2 -> User die in gleicher Gruppe sind wie Dok-Rechte
	// g: Explizite Nennung kommagetrennter Gids
	// 3 -> Ersteller des Eintrags
	// 4 -> Benutzer mit weitgehenden admin-Funktionen
	// 5 -> Admin/Root
	
	function userHasFldAccess($field, $editMode) {
		// return none, read, edit
		$fC = &$this->arrConf["Fields"][$field];
		// echo "#".__LINE__." ".__FUNCTION__."($field, $editMode)<br>\n";
		switch($editMode) {
			case "Insert": $accessKey = "insertMinAccess"; break;
			case "Update": $accessKey = "updateMinAccess"; break;
			case "Read":   $accessKey = "readMinAccess";   break;
			case "Delete": $accessKey = "deleteMinAccess"; break;
			default: return false;
		}
		
		//echo "#".__LINE__." ".basename(__FILE__)." editMode:$editMode; accessKey:$accessKey; rC[$accessKey]:".$rC[$accessKey]."<br>\n";
		// echo "#".__LINE__." rC[$accessKey]:".$rC[$accessKey].", this->uid:".$this->uid.", this-->userRechte:".$this->userRechte." <br>\n";
		if (empty($rC[$accessKey]) || $rC[$accessKey] == 0) return true;  // Wenn Zugriff für Jeder (gleich: 0) return true
		elseif (empty($this->uid)) return false; // Andernfalls, wenn user nicht registriert ist return false
		if ($rC[$accessKey] && $rC[$accessKey] > $this->userRechte) return false;
		if ($this->dokRechte && $this->dokRechte > $this->userRechte) return false;
		return true;
	}
	
	function userHasRowAccess($editMode) {
		// Noch nicht fertiggestellt
		// return none, read, edit
		$rC = &$this->arrConf;
		
		switch($editMode) {
			case "Insert": $accessKey = "insertMinAccess"; break;
			case "Update": $accessKey = "updateMinAccess"; break;
			case "Read":   $accessKey = "readMinAccess";   break;
			case "Delete": $accessKey = "deleteMinAccess"; break;
			default: return false;
		}
		
		//echo "#".__LINE__." ".basename(__FILE__)." editMode:$editMode; accessKey:$accessKey; rC[$accessKey]:".$rC[$accessKey]."<br>\n";
		//echo "#".__LINE__." rC[$accessKey]:".$rC[$accessKey].", this->uid:".$this->uid.", this-->dokRechte:".$this->dokRechte.", this-->userRechte:".$this->userRechte." <br>\n";
		if (empty($rC[$accessKey]) || $rC[$accessKey] == 0) return true;  // Wenn Zugriff für Jeder (gleich: 0) return true
		elseif (empty($this->uid)) return false; // Andernfalls, wenn user nicht registriert ist return false
		if ($rC[$accessKey] && $rC[$accessKey] > $this->userRechte) return false;
		if ($this->dokRechte && $this->dokRechte > $this->userRechte) return false;
		return true;
	}
	
	function checkFieldInput($field, $value, &$error) {
		$err = "";

		if (!isset($this->arrConf['Fields'][$field])) {
			$error = 'Das Feld ' . $field . ' ist nicht in der Konfiguration enthalten!';
			return false;
		}


		$fN = $field;
		$fC = $this->arrConf["Fields"][$fN];

		if ( !$this->userHasFldAccess($fN, $this->editMode) ) {
			if ($this->editMode == "Insert") {
				$error = "Sie haben nicht die erforderlichen Insert-Rechte für ".$fC['label']."!<br>\n";
			} else {
				$error = "Sie haben nicht die erforderlichen Update-Rechte für ".$fC['label']."!<br>\n";
			}
			return false;
		}

		// Prüfe, ob ein bzw. kein leerer Wert übergeben wurde
		if ($fC["editByRuntime"]) {
			$error = "Der Wert für " . $fN . " darf nur vom System gesetzt werden!";
			return false;
		}
		if (preg_match("/created|modified/", $fC["sysType"])) {
			$error = "Werte für Erstellungs- oder Modifizierungsdatum dürfen nur vom System gesetzt werden!";
			return false;
		}
		if (preg_match("/PRI/", $fC["key"])) {
			$error = "Primary-Key dürfen aus Gründen der Daten-Integrität nur vom System gesetzt werden!";
			return false;
		}
		if ($fC["sysType"] == "password") {
			if (isset($this->arrInput[$fN."_wh"]) && $this->arrInput[$fN."_wh"] != $value) {
				$error = "Passwort stimmt nicht mit Wiederholung überein!<br>\n";
				return false;
			}
		}
		$isValid = true;
		if ($this->editMode == "Insert" && isset($fC["createByFunction"]) && $fC["createByFunction"] !== "") {
			$error = 'Wert für ' . $fN . ' wird bei Erstellung des Eintrags vom System gesetzt!';
			return false;
		}

		if ($fC["checkByFunction"] !== "") {
			if (function_exists($fC["checkByFunction"])) {
				eval("\$isValid = ".$fC["checkByFunction"]."(\$fN, \$this->arrInput, \$this->arrConf, \$err);");
				//$isValid = call_user_func($fC["checkByFunction"], $fN, $this->arrInput, $this->arrConf, $err);
				if (!$isValid) {
					$error = $err;
					return false;
				}
			} else {
				$error = basename(__FILE__)." Fehlende userdefined-Prüffunktion in ".$this->arrConf["Title"].": ".$fC["checkByFunction"]." für Feld $fN!<br>\n";
				return false;
			}
		} elseif (empty($fC["createByFunction"])
				&& strlen(trim($value))>0 ) {
			// Prüfe Eingabe auf Typ und Länge
			$err = "";
			$isValid = $this->checkType->isValidType( 
					$value, 
					$fC["type"], 
					$fC["size"], 
					$fC["min"], 
					$fC["max"], 
					$err);
			
			if (!$isValid) {
				$error = $err;
				return false;
			}
			
			if ($fC["unique"]) {
				$isUnique = $this->isUnique(
					$value, 
					$this->dbConnId,
					$this->arrConf["Db"], 
					$this->arrConf["Table"], 
					$fC["dbField"],
					$this->arrConf["PrimaryKey"],
					(isset($this->arrInput[$this->arrConf["PrimaryKey"]]) ? $this->arrInput[$this->arrConf["PrimaryKey"]] : "")
				);
				if (!$isUnique) {
					$error = "Es existiert bereits ein Eintrag für: ".$value."!\n";
					return false;
				}
			}
		} else {
			// Prüfe, ob Eingabe erforderlich
			if ($fC["required"]) {
				$error = "Leere Angabe!";
				return false;
			}
		}
		return true;
	}
	
	function checkInput() {
		$this->Warning = "";
		$this->Error = "";
		if (count($this->arrInput)) {
			foreach($this->arrConf["Fields"] as $fN => $fC) { // f:fieldName, c:fieldConf
				$err = "";
				if ( !$this->userHasFldAccess($fN, $this->editMode) ) {
					if ($this->editMode == "Insert") {
						if ($fC["required"]) $this->arrInput[$fN] = $fC["default"];
						else {
							if (isset($this->arrInput[$fN]) )
								$this->Warning.= "Sie haben nicht die erforderlichen Insert-Rechte für ".$fC['label']."!<br>\n";
							continue;
						}
					} else {
						if (isset($this->arrInput[$fN]) )
							$this->Warning.= "Sie haben nicht die erforderlichen Update-Rechte für ".$fC['label']."!<br>\n";
						continue;
					}
				}
				
				// Prüfe, ob ein bzw. kein leerer Wert übergeben wurde
				if ($fC["editByRuntime"]) continue;
				if (preg_match("/created|modified/", $fC["sysType"])) continue;
				if (preg_match("/PRI/", $fC["key"]))  continue;
				if ($fC["sysType"] == "password") {
					if (isset($this->arrInput[$fN."_wh"]) && $this->arrInput[$fN."_wh"] != $this->arrInput[$fN]) {
						$err = "Passwort stimmt nicht mit Wiederholung überein!<br>\n";
						$this->Error.= $err;
						$this->arrErrFlds[$fN] = $fC["label"].($err !== "" ? ": ".$err : "");
					}
					if ($this->itemExists && empty($this->arrInput[$fN])) continue;
				}
				$isValid = true;
				$doCheck = true;
				if ($this->editMode == "Insert" && isset($fC["createByFunction"]) && $fC["createByFunction"] !== "") {
					$doCheck = false;
				}
				
				if ($fC["checkByFunction"] !== "") {
					if (function_exists($fC["checkByFunction"])) {
						eval("\$isValid = ".$fC["checkByFunction"]."(\$fN, \$this->arrInput, \$this->arrConf, \$err);");
						//$isValid = call_user_func($fC["checkByFunction"], $fN, $this->arrInput, $this->arrConf, $err);
						if (!$isValid) $this->arrErrFlds[$fN] = $fC["label"].": ".$err;
					} else {
						$this->SysError.= basename(__FILE__)." Fehlende userdefined-Prüffunktion in ".$this->arrConf["Title"].": ".$fC["checkByFunction"]." für Feld $fN!<br>\n";
						$this->arrErrFlds[$fN] = $fC["label"].": Interner Systemfehler";
						return false;
					}
				} elseif (empty($createByFunction) && isset($this->arrInput[$fN]) && is_string($this->arrInput[$fN]) && strlen(trim($this->arrInput[$fN])) ) {
					// Prüfe Eingabe auf Typ und Länge
					$err = "";
					$isValid = $this->checkType->isValidType( $this->arrInput[$fN], $fC["type"], $fC["size"], $fC["min"], $fC["max"], $err);
					// Unique-Prüfung
					// echo "#".__LINE__." print_r(fC):".print_r($fC, true)." <br>\n";
					// echo "#".__LINE__." this->arrConf[PrimaryKey]:".$this->arrConf["PrimaryKey"]." <br>\n";
					// echo "#".__LINE__." this->arrInput[".$this->arrConf['PrimaryKey']."]:".$this->arrInput[$this->arrConf["PrimaryKey"]]." <br>\n";
					if ($isValid && $fC["unique"]) {
						$isValid = $this->isUnique(
							$this->arrInput[$fN], 
							$this->dbConnId,
							$this->arrConf["Db"], 
							$this->arrConf["Table"], 
							$fC["dbField"],
							$this->arrConf["PrimaryKey"],
							(isset($this->arrInput[$this->arrConf["PrimaryKey"]]) ? $this->arrInput[$this->arrConf["PrimaryKey"]] : "")
						);
						if (!$isValid) {
						    $err = "Es existiert bereits ein Eintrag für: ".$this->arrInput[$fN]."!\n";
                        }
					}
					if (!$isValid) {
					    $this->arrErrFlds[$fN] = $fC["label"] . ($err !== "" ? ": " . $err : "") . '#' . __LINE__;
                    }
				} else {
					// Prüfe, ob Eingabe erforderlich
					if ($fC["required"]) {
						$this->arrErrFlds[$fN] = $fC["label"] . ": Fehlende Eingabe";
					}
				}
			}
			
			foreach($this->arrConf["Fields"] as $fN => $fC) {
				if ($this->editMode == "Insert" && isset($fC["createByFunction"]) && $fC["createByFunction"] !== "") {
					if (function_exists($fC["createByFunction"]) ) {
						if (!call_user_func($fC["createByFunction"], $fN, $this->arrInput, $this->arrConf, $err)) {
							$this->arrErrFlds[$fN] = $fC["label"].": " . $err;
						}
					} else {
						$this->Error.= basename(__FILE__)." Create-Funktion wurde nicht gefunden: ".$fC["createByFunction"]."()<br>\n";
						return false;
					}
				}
			}
			
			if (count($this->arrErrFlds) ) {
				// $this->Error.= "Es wurden einige Felder nicht korrekt ausgef&uuml;llt:<br>\n";
				foreach($this->arrErrFlds as $fld => $err) {
					// $this->Error.= '#2068 ' . $fld . ' '; // Just for debugging
					$this->Error.= $err."<br>";
				}
				return false;
			}
			return true;
		} else {
			$this->Error.= " Eingabedaten wurden noch nicht übergeben / geladen!<br>";
			$this->Error.= "Fügen Sie die Eingabedaten mit object->inputLoad(Array Eingaben)<br>!";
			return false;
		}
	}
	
	function save() { return $this->saveInput(); }

	function saveInput() {
		global $msg;
        $success = false;

		if (!$this->Error) {
			
			$SQL_SET = "";
			$arrSysFlds = array();

			foreach($this->arrConf["Fields"] as $fN => $fC) {
				if (empty($fC['dbField'])) {
				    continue;
                }

                if ($this->editMode !== 'Insert' && !isset($this->arrInput[$fN])) {
                    continue;
                }
				
				if (!$this->userHasFldAccess($fN, $this->editMode)) {
					if ($this->editMode == "Insert" && $fC["required"]) {
						$this->Error.= "#".__LINE__." Es ist ein Konflikt in der Konfiguratation des Feldes $fN aufgetreten, da Sie keine Eingaberechte für eine erforderliche Angabe haben!<br>\n";
						return false;
					}
					continue;
				}
				
				if (isset($fC["editByRuntime"]) && $fC["editByRuntime"]) continue;
				if (isset($fC["key"]) && preg_match("/PRI/",$fC["key"])) continue;
				
				switch($fC["sysType"]) {
					case "uid":
					case "rechte":
					case "gid":
					case "created":
					case "createdby":
					case "createduid":
					case "modified":
					case "modifiedby":
					case "modifieduid":
					case "key":
					$arrSysFlds[$fN] = &$this->arrConf["Fields"][$fN];
					continue 2;
					break;
					
					// NoString
					case "int":
					case "double":
					case "float":
					case "decimal":
					if (isset($this->arrInput[$fN]) && is_numeric($this->arrInput[$fN]) ) {
						if ($SQL_SET) $SQL_SET.= ",\n";
						$SQL_SET.= "`".$fC["dbField"]."` = \"" . MyDB::escape_string($this->arrInput[$fN]) . "\"";
					} elseif ($this->editMode === 'Insert' && $fC['null']) {
						 if ($SQL_SET) $SQL_SET.= ",\n";
						 $SQL_SET.= '`' . $fC['dbField'] . '` = NULL';
					}
					break;
					
					case "set":
                                            //die(print_r($this->arrInput,1));
					$setVal = (isset($this->arrInput[$fN]) && is_array($this->arrInput[$fN])) ? implode(",", $this->arrInput[$fN]) : '';
					if ($setVal !== "") {
						if ($SQL_SET) $SQL_SET.= ",\n";
						$SQL_SET.= "`".$fC["dbField"]."` = \"".MyDB::escape_string($setVal)."\"";
					} else {
					//} elseif ($this->editMode != "Insert") {
						if ($SQL_SET) $SQL_SET.= ",\n";
						$SQL_SET.= "`".$fC["dbField"]."` = ".($fC["null"] ? "NULL" : "''");
					}
					break;

					case "password":
					if ($this->arrInput[$fN] !== "") {
						if ($SQL_SET) $SQL_SET.= ",\n";
						$SQL_SET.= "`".$fC["dbField"]."` = \"".md5($this->arrInput[$fN])."\"";
					}
					break;

					case "date":
					case "datetime":
					if ($SQL_SET) $SQL_SET.= ",\n";
					if (!empty($this->arrInput[$fN])) {
						$SQL_SET.= "`".$fC["dbField"]."` = \"".MyDB::escape_string($this->arrInput[$fN])."\"";
					} else {
						$SQL_SET.= "`".$fC["dbField"]."` = NULL";
					}
					break;
					
					//String
					default:
					    if ($fC['htmlType'] === 'file' ) {
					        if (!empty($_FILES['eingabe']['name'][$fN])) {
                                $PostData = $_POST;
                                $FileData = $_FILES;
                                $_file = [
                                    'name' => $_FILES['eingabe']['name'][$fN],
                                    'temp' => $_FILES['eingabe']['tmp_name'][$fN],
                                    'type' => $_FILES['eingabe']['type'][$fN],
                                    'size' => $_FILES['eingabe']['size'][$fN],
                                    'error' => $_FILES['eingabe']['error'][$fN],
                                ];
                                if ($SQL_SET) {
                                    $SQL_SET .= ",\n";
                                }
                                $SQL_SET .= '`' . $fC['dbField'] . '` = "' . MyDB::escape_string(file_get_contents($_file['temp'])) . '"';

                                // print_r(compact('PostData', 'FileData', '_file'));
                            }
                        }
                        elseif (isset($this->arrInput[$fN]) && $this->arrInput[$fN] !== "") {
                            if ($SQL_SET) {
                                $SQL_SET.= ",\n";
                            }
                            $SQL_SET.= "`".$fC["dbField"]."` = \"".MyDB::escape_string($this->arrInput[$fN])."\"";
                        } else {
                        //} elseif ($this->editMode != "Insert") {
                            if ($SQL_SET){
                                $SQL_SET.= ",\n";
                            }
                            $SQL_SET.= "`".$fC["dbField"]."` = ".($fC["null"] ? "NULL" : "''");
                        }
					    break;
				}
			}
			
			if ($SQL_SET) {
				$SQL = $this->editMode." `".$this->arrConf["Db"]."`.`".$this->arrConf["Table"]."` SET\n";
				$SQL.= $SQL_SET;
				
				
				if ($this->editMode == "Update") {
					
					foreach($arrSysFlds as $fN => $fC) {
						switch($fC["sysType"]) {
							case "modified":   $SQL.= ",\n `".$fC["dbField"]."` = NOW() "; break;
							case "modifieduid": $SQL.= ",\n `".$fC["dbField"]."` = \"".$this->uid."\" "; break;
							case "modifiedby": 
							if ($fC["type"] == "int") {
								$SQL.= ",\n `".$fC["dbField"]."` = \"".$this->uid."\" "; 
							} else {
								$SQL.= ",\n `".$fC["dbField"]."` = \"".$this->uname."\" "; 
							}
							break;
						}
					}
					$SQL.= "\n WHERE `".$this->arrConf["PrimaryKey"]."` = \"".MyDB::escape_string($this->id)."\"";
					$success = $this->db_query($SQL, __LINE__);
				} else {
					foreach($arrSysFlds as $fN => $fC) {
						switch($fC["sysType"]) {
							case "created":
							case "modifed": $SQL.= ",\n`".$fC["dbField"]."` = NOW() "; break;
							
							case "createduid":
							case "modifieduid": $SQL.= ",\n`".$fC["dbField"]."` = \"".$this->uid."\" "; break;
							
							case "uid": $SQL.= ",\n`".$fC["dbField"]."` = \"".$this->uid."\" "; break;
							case "rechte": $SQL.= ",\n`".$fC["dbField"]."` = \"".$this->userRechte."\" "; break;
							case "gid": $SQL.= ",\n`".$fC["dbField"]."` = \"".$this->userRechte."\" "; break;
							
							case "createdby":
							case "modifiedby": 
							if ($fC["type"] == "int") {
								$SQL.= ",\n `".$fC["dbField"]."` = \"".$this->uid."\" "; 
							} else {
								$SQL.= ",\n `".$fC["dbField"]."` = \"".$this->uname."\" "; 
							}
							break;
						}
					}
                    $success = $this->db_query($SQL, __LINE__);
					$this->id = MyDB::insert_id();
                                        
                    file_put_contents('tmp/' . $this->arrConf["Table"] . '_' . $this->editMode .'_'.$this->id . '.sql', $SQL);
				}
				
				if (0) {
				    $msg.= "<pre>#".__LINE__." SQL:\n".fb_htmlEntities($SQL)."\nERROR:".MyDB::error()."</pre>\n";
                }

                $db = dbconn::getInstance();
                if (!MyDB::error()) {
                    return true;
                }
				else {
					$this->Error.= "Fehler beim Speichern (" . $this->editMode . ") der Daten!<br>\n";
					$this->dbError.= "#".__LINE__." ".basename(__FILE__)."\n";
                    $this->dbError.= "MYSQL:".MyDB::error()."\n";
                    $this->dbError.= "dbconn:". $db->error()."\n";
					$this->dbError.= "QUERY:".$SQL."\n";
					return false;
				}
			}
			
		} else {
			$this->Error.= "Datensatz kann nicht gespeichert werden, wenn bei der Pruefung Fehler aufgetreten sind!<br>\n";
		}

		return false;
	}
	
	function killItem() {
		$SQL = "DELETE FROM `".$this->arrConf["Db"]."`.`".$this->arrConf["Table"]."` \n";
		$SQL.= "\n WHERE `".$this->arrConf["PrimaryKey"]."` = \"".MyDB::escape_string($this->id)."\"";
		$this->db_query($SQL, __LINE__);
		if (!MyDB::error()) {
		    return true;
        }
		else {
			$this->Error.= "Fehler beim L&ouml;schen des Datensatzes!<br>\n";
			$this->dbError.= "#".__LINE__." ".basename(__FILE__)."\n";
			$this->dbError.= "MYSQL:".MyDB::error()."\n";
			$this->dbError.= "QUERY:".$SQL."\n";
			return false;
		}
	}
	
	function isUnique(&$val, $connid, $db, $tbl, &$fld, $key, $id = "") {
		// echo "#".__LINE__." ".basename(__FILE__)." isUnique($val, $connid, $db, $tbl, $fld, $key, $id)<br>\n";
		$SQL = "SELECT COUNT(*) FROM `$db`.`$tbl`";
		$SQL.= " WHERE `".$fld."` LIKE \"".MyDB::escape_string($val)."\"";
		if ($id !== "") {
			$SQL.= " AND NOT `".$key."` = \"".MyDB::escape_string($id)."\"";
		}
		$r = $this->db_query($SQL);
		
		if ($r) {
			list($count) = MyDB::fetch_array($r, MyDB::NUM);
			$this->db_free_result($r);
			return ($count == 0);
		} else {
			$this->Error.= "Fehler bei Unique-Abfrage!<br>\n";
			$this->dbError.= "#".__LINE__." ".basename(__FILE__)."\n";
			$this->dbError.= "MYSQL:".MyDB::error()."\n";
			$this->dbError.= "QUERY:".$SQL."\n";
		}
		return false;
	}
	
	// private
	function db_query($SQL, $line = "?") 
	{
	    $this->lastQuery = $SQL;
		$r = @MyDB::query($SQL, $this->dbConnId);
		if (MyDB::error()) {
		    $error = MyDB::error();
		    $this->dbError = "#$line error: $error.\nSQL: $SQL\n";
		    return false;
        }
		if ($r) {
			return $r;
		}
		return true;
	}
	
	// private
	function db_free_result($result) 
	{
		MyDB::free_result($result);
	}
	
	function get_dynFormByConf($inputArrayName = "eingabe", $view = "input,preview,read", $SetValues = false) {
		
		$makeInput   = ($view == "input");
		$makePreview = ($view == "preview");
		$makeRead    = ($view == "read");
		
		$prefix = ($inputArrayName ? $inputArrayName."[" : "");
		$suffix = ($inputArrayName ? "]" : "");
		
		$form = "";
		
		if ($makeInput) {
			$form.= "<h3 class=\"formTitle\">".$this->arrConf["Title"]."</h3>\n";
			if ($this->arrConf["Description"]) {
				$form.= "<div class=\"formDesc\">".$this->arrConf["Description"]."</div>\n";
			}
			$form.= "<div class=\"formBox\"><form action=\"{action}\" enctype='multipart/form-data' method=\"post\" name=\"frmInput\">";
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
			if ($v["required"]) {
				if ($strIdsShowPflicht) $strIdsShowPflicht.=",\n";
				$strIdsShowPflicht.= "#p{$k}";
			} else {
				if ($strIdsHidePflicht) $strIdsHidePflicht.=",\n";
				$strIdsHidePflicht.= "#p{$k}";
			}
		}
		
		if ($makeInput) {
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
			$size = trim($v["size"]);
			$fname = $prefix.$k.$suffix;
			$fN = $k;
			
			$tplRead = (!empty($v["tplRead"])) ? $v["tplRead"] : "";
			$tplInput = (!empty($v["tplInput"])) ? $v["tplInput"] : "";
			
			$iAttr = (!empty($v["inputAttribute"])) ? " ".$v["inputAttribute"] : "";
			$rAttr = (!empty($v["readAttribute"])) ? " ".$v["readAttribute"] : "";
			
			// $this->editMode = "Update";
			// $this->editCmd = "Edit";
			// echo "#".__LINE__." k:$k <br>\n";
			$fieldView = "none";
			if (is_int(strpos("created,createdby,modified,modifiedby",$v["sysType"])) || $v["editByRuntime"]) {
				if ($this->userHasFldAccess($k, "Read")) $fieldView = "Read";
			} elseif ($makeInput) {
				if ($this->userHasFldAccess($k, $this->editMode)) $fieldView = "Input";
				elseif ($this->userHasFldAccess($k, "Read") && $this->editMode == "Update") $fieldView = "Read";
				
			} elseif ($makePreview) {
				if ($this->userHasFldAccess($k, $this->editMode)) $fieldView = "Read";
				elseif ($this->userHasFldAccess($k, "Read") && $this->editMode == "Update") $fieldView = "Read";
				
			} elseif ($makeRead) {
				if ($this->userHasFldAccess($k, "Read")) $fieldView = "Read";
				
			}
			if ($fieldView == "none") continue;
			
			// $form.= "<table class=\"boxRenderHlpTbl\"><tr><td>";
			switch($fieldView) {
				case "Input":
				// $this->userHasFldAccess($field, $editMode)
				$form.= "<div class=\"input\" id=\"InputBox{$k}\">\n";
				if (!$tplInput) {
					if ($v["htmlType"] != "hidden") {
						
                                                if (substr($size, 0, 3) == "=,'") $opt = explode("','", "='," . substr($size,2,-1));
                                                elseif (substr($size,0,1) == "'") $opt = explode("','", substr($size, 1,-1));
                                                else $opt = explode(",", $size);
                                                
						//$muster = "/^(x)(.*)(x)$/U";
						for($i = 0; $i < count($opt); $i++) {
                                                    $opt[$i] = preg_replace("/^(')(.*)(')$/Us", "\\2", $opt[$i]);
						}
						
						// $this->arrErrFlds as $fN => $fErr class=\"lblInputError\"
						
						$str = "";
						$str.= "<div class=\"inputLbl\">";
						$str.= "<label errclass=\"{$k}\" ".(!isset($this->arrErrFlds[$k])?"":"class=\"lblInputError\"")." for=\"{$fname}\" id=\"InputLbl{$k}\">".$v["label"]."";
						if ($v["required"]) $str.= "<span class=\"showPflicht\" id=\"p{$k}\">*</span>";
						else $str.= "<span class=isPflicht id=\"p{$k}\">*</span>";
						
						$str.= "</label></div>\n";
						$str.= "<div class=\"inputFrm\">";
                                                
						if (isset($this->arrErrFlds[$k])) {
                                                    $_a = explode(":",$this->arrErrFlds[$k]);
                                                    $str.= "<em>".trim(array_pop($_a))."</em><br>\n";
                                                }
						
						switch($v["htmlType"]) {
							case "radio":
							for ($i = 0; $i < count($opt); $i++) {
								list($r_key, $r_ti) = (count(explode("=", $opt[$i])) == 2) ? explode("=", $opt[$i]) : array($opt[$i], $opt[$i]);
								if ($SetValues) {
									$SetChecked = ($this->arrInput[$fN] == $r_key ?"checked=true":"");
								} else {
									$SetChecked = " check_".$k."=\"".$r_key."\"";
								}
								$str.= "<input {$iAttr} LINE=\"".__LINE__."\" class=\"chckbox\" type=\"radio\" name=\"".$fname."\" value=\"".$r_key."\" $SetChecked>".$r_ti." ";
							}
							break;
							
							case "checkbox":
							for ($i = 0; $i < count($opt); $i++) {
								list($r_key, $r_ti) = (count(explode("=", $opt[$i])) == 2) ? explode("=", $opt[$i]) : array($opt[$i], $opt[$i]);
								if ($SetValues) {
									if (is_array($this->arrInput[$fN])) $SetChecked = (in_array($r_key, $this->arrInput[$fN]) ? "checked=true":"");
									else $SetChecked = (is_int(strpos($this->arrInput[$fN], $r_key)) ? "checked=true":"");
								} else {
									$SetChecked = " check_".$k."=\"".$r_key."\"";
								}
								$str.= "<input {$iAttr} LINE=\"".__LINE__."\" class=\"chckbox\" type=\"checkbox\" name=\"".$fname."[]\" value=\"".$r_key."\" $SetChecked>".$r_ti." ";
							}
							break;
							
							case "text":
							switch($v["sysType"]) {
								case "password":
								$str.= "<input {$iAttr} LINE=\"".__LINE__."\" class=\"ipw\" type=\"password\" name=\"".$fname."\" maxlength=\"$size\" value=\"{".$fname."}\">\n";
								$kWH = $k."_wh";
								$fnameWH = $prefix.$kWH.$suffix;
								$str.= "</div>\n";
								$str.= "</div>\n";
								$str.= "<div class=\"input\" id=\"InputBox{$kWH}\">\n";
								$str.= "<div class=\"inputLbl\"><label for=\"{$fnameWH}\" id=\"InputLbl{$kWH}\"><em>wiederholen</em></label><span class=isPflicht id=\"p{$k}\">*</span></div>\n";
								$str.= "<div class=\"inputFrm\">";
								$str.= "<input {$iAttr} LINE=\"".__LINE__."\" class=\"ipw\" type=\"password\" name=\"".$fnameWH."\" maxlength=\"$size\" value=\"{".$fnameWH."}\">\n";
								break;
								
								case "created":
								case "createdby":
								case "createduid":
								case "modified":
								case "modifiedby":
								case "modifieduid":
								case "system":
								$str.= "<div class=\"sysfield\">{".$fname."}</div>\n";
								break;
								
								default:
								$str.= "<input {$iAttr} LINE=\"".__LINE__."\" class=\"itext\" type=\"text\" name=\"".$fname."\" maxlength=\"$size\" value=\"{".$fname."}\">\n";
							}
							break;
							
							case "system":
							$str.= "<div class=\"sysfield\">{".$fname."}</div>\n";
							break;
							
							case "textarea":
							$str.= "<textarea {$iAttr} class=\"iarea\" name=\"".$fname."\">{".$fname."}</textarea>\n";
							break;
							
							case "select":
							case "select single":
							case "select multiple":
							$multiple = (!preg_match("/multiple/", $v["htmlType"]) ? " name=\"".$fname."\"" : "name=\"".$fname."[]\" multiple=\"true\"");
							$str.= "<select {$iAttr} class=\"iselect\" $multiple>\n";
							for ($i = 0; $i < count($opt); $i++) {
								list($r_key, $r_ti) = (count(explode("=", $opt[$i])) == 2) ? explode("=", $opt[$i]) : array($opt[$i], $opt[$i]);
								$str.= "<option value=\"".$r_key."\" check_".$k."=\"".$r_key."\">".$r_ti." </option>\n";
							}
							$str.= "</select>";
							break;

                            case "file":
                                $str.= "<input type=\"file\" name=\"" . $fname . "\" "
                                    . " id=\"" . $fname . "\" "
                                    . " value=\"{" . $fname . "}\">";
                                break;
							
							default:
							$this->Error.= "#".__LINE__." ".basename(__FILE__)." unbekannter htmlType:".$v["htmlType"]."<br>\n";
						}
						$str.= "</div>\n";
					} else {
						$str.= "<input type=\"hidden\" name=\"".$fname."\" id=\"".$fname."\" value=\"{".$fname."}\">";
					}
				} else {
					$str = $tplInput;
					$str = str_replace("{tpl_inputname}", $fname, $str);
					$str = str_replace("{tpl_inputvalue}", "{".$fname."}", $str);
				}
				$form.= $str;
				$form.= "</div>\n";
				break;
				
				case "Read":
				if (!$tplRead) {
					if ($v["sysType"] != "password") {
						$read = "<div class=\"input\">\n<div class=\"inputLbl\">".$v["label"]."</div>\n<div {$rAttr}>{lesen[".$k."]}&nbsp;</div>\n</div>";
					} elseif ($v["sysType"] != "set") {
						$read = "<div class=\"Input\">\n<div class=\"inputLbl\">".$v["label"]."</div>\n<div>***</div>\n</div>";
					}
				} else {
					$read = $tplRead;
					$read.= str_replace("{tpl_readlabel}", $v["label"], $read);
					$read.= str_replace("{tpl_readvalue}", "{lesen[".$k."]}&nbsp;", $read);
				}
				if ($makePreview) {
					if ($v["sysType"] != "set") {
						$hidden.= "<input LINE=\"".__LINE__."\" type=\"hidden\" name=\"".$fname."\" maxlength=\"$size\" value=\"{".$fname."}\">\n";
					} else {
						$hidden.= "<!-- {HiddenSet_".$fname."} -->\n";
					}
				}
				$form.= $read;
				break;
			}
			// $form.= "</td></tr></table>\n";
			
		}
		
		$hiddenUpdateById  = "<input LINE=\"".__LINE__."\" type=\"hidden\" name=\"id\" value=\"{id}\">\n";
		$arrBtn["preview"] = "<input LINE=\"".__LINE__."\" class=\"ibtn ibtnprev\" type=\"submit\" name=\"editCmd[Preview]\" value=\"Vorschau\">";
		$arrBtn["save"]    = "<input LINE=\"".__LINE__."\" class=\"ibtn ibtnsave\" type=\"submit\" name=\"editCmd[Save]\" value=\"Fertigstellen\">";
		$arrBtn["correct"] = "<input LINE=\"".__LINE__."\" class=\"ibtn ibtnedit\" type=\"submit\" name=\"editCmd[Correct]\" value=\"Korrigieren\">";
		$arrBtn["gotoNextInsert"] = "<br>
<input LINE=\"".__LINE__."\" class=\"ichck\" type=\"checkbox\" name=\"gotoNext\" value=\"New\" chck_gotoNext=\"New\"> ".$this->Labels["gotoNextInsert"];
		
		if ($makeInput)   $form.=   $hiddenUpdateById.$arrBtn["preview"].$arrBtn["save"].$arrBtn["gotoNextInsert"]."</form></div>";
		if ($makePreview) $form.= $hidden.$hiddenUpdateById.$arrBtn["save"].$arrBtn["correct"]."</form></div>";
		if ($makeRead)    $form.= "</div>";
		return $form;
	}
	
	function renderForm($view)  {
		$inputArrayName = "eingabe";
		// $view = "input,preview,read"
		$this->tplForm = $this->get_dynFormByConf($inputArrayName, $view);
	}
	
	function set_autoFormBuilder($mode) {
		
		switch($mode) {
			case "file":
			case "html":
			case "render";
			$this->autoFormBuilder = $mode;
			return true;
			break;
			
			default;
			$this->Error.= "Error In Line #".__LINE__." ".basename(__FILE__).": Unbekannte FormBuilder-Wert '$mode', zulässig sind 'file','html','render'!<br>\n";
			return false;
		}
	}
	
	function autoBuildForm($editCmd) {
		if ($editCmd == "Drop") return false;
		
		$aEditCmd2 = array(
			   "Read"=> array("RenderKey" => "read",    "FileKey" => "FormRead"),
			 // "Insert"=> array("RenderKey" => "input",   "FileKey" => "FormInput"),
			   "Edit"=> array("RenderKey" => "input",   "FileKey" => "FormInput"),
			"Preview"=> array("RenderKey" => "preview", "FileKey" => "FormPreview"),
			"Correct"=> array("RenderKey" => "input",   "FileKey" => "FormInput")
		);
		if (!isset($aEditCmd2[$editCmd])) {
			$this->Error.= "Error in #".__LINE__." in ".basename(__FILE__).": Interner Fehler, '$editCmd' ist nicht in Array \$aEditCmd zugeordnet!<br>\n";
			return false;
		}
		
		if ($this->autoFormBuilder == "file" && !file_exists($this->arrConf[$aEditCmd2[$editCmd]["FileKey"]])) {
			$this->autoFormBuilder = "render";
		}
		
		switch($this->autoFormBuilder) {
			case "file":
			// echo "#".__LINE__." \$aEditCmd2[$editCmd][RenderKey]:".$aEditCmd2[$editCmd]["FileKey"].":".$this->arrConf[$aEditCmd2[$editCmd]["FileKey"]]." <br>\n";
			$this->openTplForm($this->arrConf[$aEditCmd2[$editCmd]["FileKey"]]);
			break;
			
			case "render":
			$this->renderForm($aEditCmd2[$editCmd]["RenderKey"]);
			break;
			
			case "html":
			break;
			
			default:
			$this->Error.= "#".__LINE__." Ungültiger Wert für autoFormBuilder '".$this->autoFormBuilder."!<br>\n";
			return false;
		}
		return true;
	}
	
	function autorun_byConf(
		// Per Default, Modifizierbar
		$editCmd, $formAction, $fieldVals, $gotoNext) {
		// echo "#".__LINE__." editCmd:$editCmd <br>\n";
	    // echo "#".__LINE__." formAction:$formAction <br>\n";
		global $_POST;
		
		if (!$gotoNext && isset($_POST["gotoNext"])) {
		    $gotoNext = $_POST["gotoNext"];
        }
		
		$this->autorun_status = 0;
		// $_POST["editCmd"][{key}] // Edit | Preview | Correct | Save | Read | Drop
		
		// Ermitteln des Bearbeitungsmodus / -ansicht
		if (!$editCmd) {
			if (!empty($_POST["editCmd"])) {
			    $editCmd = key($_POST["editCmd"]);
            }
			else {
			    $editCmd = "Edit";
            }
		}
		
		$SentInput = (isset($_POST["eingabe"]) && is_array($_POST["eingabe"])) ? $_POST["eingabe"] : array();
		if (is_array($fieldVals) && !empty($fieldVals)) {
		    foreach($fieldVals as $k => $v) $SentInput[$k] = $v;
        }
		
		// Validieren gesendeter Daten
		switch($editCmd) {
			case "Preview":
			case "Correct":
			case "Save":
			
			if ($SentInput) {
				$this->loadInput($SentInput);
				if ($this->Error !== "") $editCmd = "Correct";
			} else {
				// ERROR: No-Data-Sent !!!
			}
			if (is_array($fieldVals) && !empty($fieldVals)) {
				foreach($fieldVals as $k => $v) $this->arrInput[$k] = $v;
			}
			break;
		}
		
		// Setzen des Bearbeitungsmodus im Datenobjekt
		switch($editCmd) {
			case "Read":
			case "Insert":
			case "Edit":
			case "Preview":
			case "Correct":
			case "Save":
			$this->set_editCmd($editCmd);
			break;
			
			default:
			// echo "<span data-class='ItemEdit' data-line='" . __LINE__ . "'>editCmd:$editCmd </span><br>\n";
		}
		
		// Speichern der gesendeten
		if ($editCmd == "Save") {
			$this->set_editCmd("Save");
			$saveResult = $this->saveInput();
			if ($saveResult) {
				$this->autorun_status = 2;
				if (isset($gotoNext) && $gotoNext == "New") {
					$editCmd = "Edit";
					$this->autorun_status = 3;
				} else {
					// if ($autoViewList) $view = "liste";
				}
				
				if ($this->id) {
					$this->Msg.= "Datensatz wurde aktualisiert!<br>\n";
				} else {
					$this->Msg.= "Datensatz wurde gespeichert!<br>\n";
				}
			} else {
                $editCmd = "Edit";
				$this->autorun_status = -2;
				$this->Error.= "Fehler beim Speichern der Daten!<br>\n";
			}
		}
		
		$this->autoBuildForm($editCmd);
		switch($editCmd) {
			case "Read":
			$this->setEditForm($formAction, "eingabe", "{");
			$this->setReadForm($formAction, "lesen", "{");
			break;
			
			case "Insert":
			case "Edit":
			if ($this->itemExists) $this->dbdataToInput();
			$this->setEditForm($formAction, "eingabe", "{");
			$this->setReadForm($formAction, "lesen", "{");
			break;
			
			case "Correct":
			$this->Error = "";
			$this->loadInput($_POST["eingabe"]);
			$this->setEditForm($formAction, "eingabe", "{");
			$this->setReadForm($formAction, "lesen", "{");
			break;
			
			case "Preview":
			$this->loadInput($_POST["eingabe"]);
			$this->setReadForm($formAction, "lesen", "{");
			$this->setEditForm($formAction, "eingabe", "{");
			break;
			
			case "Drop":
			$this->autorun_status = -1;
			if ($this->id) {
				if ($this->itemExists) {
					if ($this->killItem()) {
						$this->autorun_status = 1;
						$this->Msg.= "Datensatz wurde gel&ouml;scht!<br>\n";
					}
				} else $this->Error.= "Zu l&ouml;schender Datensatz existiert nicht (mehr)!<br>\n";
			}
			break;
		}
		return $this->autorun_status;
	}
	
	// aLP => assoziatives Array mit ListenParametern
	function autorun_itemlist($aLP = array()) {
		global $_POST;
		global $_GET;
		global $_COOKIE;
		
		$this->itemList = "";
		
		// START: Register Vars
		$_V = array(
			//array(varname, Reihenfolge , setDefault, default
			array(     "offset", "pg", true,  0),
			array(       "size", "pg", true,  50),
			array(       "ofld", "pg", true,  ""),
			array(       "odir", "pg", true,  ""),
			array(          "d", "pg", true,  ""),
			array(       "data", "pg", true,  ""),
			array( "searchTerm", "pg", true, ""),
			array("searchField", "pg", true, "*")
		);
		
		for ($i = 0; $i < count($_V); $i++) {
			$el = $_V[$i];
			$v = $el[0];
			if (empty($aLP[$v])) {
				for ($k = 0; $k < strlen($el[1]); $k++) {
					switch($el[1][$k]) {
						case "p": if (isset($_POST[$v]))   { $aLP[$v] = $_POST[$v];   continue 3;} break;
						case "g": if (isset($_GET[$v]))    { $aLP[$v] = $_GET[$v];    continue 3;} break;
						case "c": if (isset($_COOKIE[$v])) { $aLP[$v] = $_COOKIE[$v]; continue 3;} break;
					}
				} 
				if (!isset($aLP[$v]) && $el[2]) $aLP[$v] = $el[3];
			}
		}
		// ENDE: Register Vars
		
		if (empty($aLP["data"]) && !empty($aLP["d"])) $aLP["data"] = $aLP["d"];
		
		if( class_exists("ItemListClass")) {
			if (!isset($aLP["listid"]) || !isset($this->arrConf["Lists"][$aLP["listid"]])) $aLP["listid"] = 0;
			$baseLink = (empty($aLP["baseLink"])) ? basename($_SERVER["PHP_SELF"])."?confName=".$aLP["data"] : $aLP["baseLink"];
			
			if (!$this->objItemList) {
				$this->objItemList = new ItemListClass($this, $aLP["selectFlds"], true, $aLP["listid"]);
			}
			
			if (!empty($this->arrConf["Lists"][$aLP["listid"]])) {
				$this->objItemList->set_listFeaturesByConf($this->arrConf["Lists"][$aLP["listid"]]);
			}
			
			if (!isset($aLP["ofld"])) $aLP["ofld"] = "";
			if (!isset($aLP["odir"])) $aLP["odir"] = "";
			if (!isset($aLP["action"])) $aLP["action"] = "";
			
			if (!empty($aLP["selectFlds"])) $this->objItemList->selectFlds = $aLP["selectFlds"];
			
			if (!empty($aLP["searchTerm"])) $aLP["action"].= "&searchTerm=".rawurlencode($aLP["searchTerm"])."&searchField=".rawurlencode($aLP["searchField"]);
			
			if (!empty($aLP["searchTerm"]) || $this->objItemList->strBaseQuery) {
				$this->objItemList->strWhere = $this->objItemList->render_sqlBySearchForm($aLP["searchField"], $aLP["searchTerm"]);
			}
			$_CONF["p_entries"]["Lists"][0]["setDefaultButtons"] = "open";
			$num_all = $this->objItemList->get_numAll();
			
			$this->objItemList->set_baseLink($baseLink."&ofld={ofld}&odir={odir}");
			
			$list = $this->objItemList->mkList($aLP["offset"], $aLP["size"], $aLP["ofld"], $aLP["odir"]);
			
			
		    if( !class_exists("listbrowser") && file_exists("../class/listbrowser.class.php")) {
		        require_once("../class/listbrowser.class.php");
		    }
			
			if( class_exists("listbrowser")) {
				$rlist_nav = new listbrowser(array(
					"offset"     => $aLP["offset"],
					"limit"      => $aLP["size"],
					"num_result" => $this->objItemList->num,
					"num_all"    => $this->objItemList->numAll,
					"baselink"   => $baseLink."&view=liste&offset={offset}&size={limit}&ofld=".rawurlencode($aLP["ofld"])."&odir=".rawurlencode($aLP["odir"])));
				
				$rlist_nav->render_browser();
				$this->searchForm = "<form style=\"margin:0px;\" action=\"$baseLink\" method=\"POST\">".$this->objItemList->get_searchForm($aLP["searchField"], $aLP["searchTerm"], array("d"=>$aLP["data"], "view"=>"liste"))."</form>";
				
				$this->itemList.= "<div class=\"lbBox\">\n";
				$this->itemList.= "<div class=\"lbnavBox\">".$rlist_nav->get_nav("all")."</div>\n";
				$this->itemList.= "<div class=\"lbsearchBox\">".$this->searchForm."</div>\n";
				$this->itemList.= "<div style=\"clear:left;\"></div>\n";
				$this->itemList.= "</div>\n";
			} else {
				// echo "#".__LINE__." ".__FILE__." <br>\n";
			}
			
			if (isset($aLP["HtmlBeforeList"])) $this->itemList.= $aLP["HtmlBeforeList"];
			$this->itemList.= $list;
			if (isset($aLP["HtmlBehindList"])) $this->itemList.= $aLP["HtmlBehindList"];
		}
		return $this->itemList;
	}
}
// echo "#".__LINE__." ".date("H:i:s")." <br>\n";
