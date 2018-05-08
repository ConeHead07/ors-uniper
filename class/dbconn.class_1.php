<?php
error_reporting(E_ALL);

if (isset($MConf) && $MConf["DB_Host"]) {
	$db = new dbconn($MConf["DB_Host"], $MConf["DB_Name"], $MConf["DB_User"], $MConf["DB_Pass"]);
	$conn = &$db->conn;
	$connid = &$db->conn;
}

class DbExpr {
    private $val = '';
    function __construct( $string ) {
        $this->val = $string;
    }
    
    function __toString() {
        return $this->val;
    }
}

abstract class DbFetchType {
    abstract public function toString();
    abstract public function fetch($result);
}

class DbFetchAssoc extends DbFetchType {
    public function toString() {
        return 'ASSOC';
    }
    public function fetch($result) {
        return MyDB::fetch_assoc($result);
    }
}

//  MyDB::BOTH
//  MyDB::NUM
//  MyDB::fetch_object($result)

class DbFetchBoth extends DbFetchType {
    public function toString() {
        return 'BOTH';
    }
    public function fetch($result) {
        return MyDB::fetch_assoc($result, MyDB::BOTH);
    }
}

class DbFetchNum extends DbFetchType {
    public function toString() {
        return 'NUM';
    }
    public function fetch($result) {
        return MyDB::fetch_assoc($result, MYSQLI_NUM);
    }
}

class DbFetchObj extends DbFetchType {
    public function toString() {
        return 'OBJ';
    }
    public function fetch($result) {
        return MyDB::fetch_object($result);
    }
}

class DbConnStatement {
    private $handle = null;
    /* @var $fetchType DbFetchType */
    private $fetchType = 'ASSOC';
    private $errno = 0;
    private $error = '';
    
    public function __construct($resultHandle) {
        $this->handle = $resultHandle;
        $this->fetchType = new DbFetchAssoc;
    }
    
    /**
     * 
     * @param int $errno
     * @return DbConnStatement
     */
    public function setErrno($errno) {
        $this->errno = (int)errno;
        return this;
    }
    
    /**
     * 
     * @param string $error
     * @return DbConnStatement
     */
    public function setError($error) {
        $this->error = error;
        return this;
    }
    
    /**
     * 
     * @return int
     */
    public function getErrno() {
        return $this->errno;
    }
    
    /**
     * 
     * @return string
     */
    public function getError() {
        return $this->error;
    }
    
    /**
     * 
     * @param DbFetchType $type
     * @return DbConnStatement
     */
    public function setFetchType(DbFetchType $type) {
        $this->fetchType = $type;
        return $this;
    }
    
    /**
     * 
     * @return DbFetchType
     */
    public function getFetchType() {
        return $this->fetchType;
    }
    
    public function fetch(DbFetchType $result_type = null) 
    {
        if (null === $fetchType) $fetchType = $this->fetchType;
        $fetchType->fetch($this->handle);
    }
    
    public function count() {
        return MyDB::num_rows($this->handle);
    }
    
    public function num_rows() {
        return MyDB::num_rows($this->handle);
    }
    
    public function countFields() {
        return MyDB::num_fields($this->handle);
    }
    public function num_fields() {
        return MyDB::num_fields($this->handle);
    }
    
    public function field($offset = null) {
        if (null !== $offset) return MyDB::field_name($this->handle, $offset);
        else return MyDB::field_name($this->handle);
    }
    
}

class dbconn {
	var $conn = false;
	var $dbconn = false;
	var $connected = false;
	var $errors = "";
	var $lasterror = "";
	var $sqllog = "";
	var $doLog = true;
        var $lastQuery = '';
	
	function dbconn($Host, $Db, $User, $Pass) {
		$this->conn = MyDB::connect($Host, $User, $Pass);
		if ($this->conn) {
			MyDB::query('SET NAMES "latin1"', $this->conn);
			$this->conndb = MyDB::select_db($Db);
		} else {
			echo "#".__LINE__." Error-DB-Conn: $Host, $Db <!-- , $User, $Pass --><br>\n";
			return false;
		}
		
		if ($this->conndb) {
			$this->connected = true;
		} else {
			return false;
		}
	}
	
	function close() {
		if (is_resource($this->conn)) {
			MyDB::close($this->conn);
		}
	}
	
	function __destruct() {
	    $this->close();
	}
	
	function checkconn() {
		return ($this->connected && is_resource($this->conn));
	}
        
        /**
         * 
         * @param string $sql
         * @param array $params
         * @return DbConnStatement
         */
        function query2stmt($sql, $params = null) {
            $r = query($sql, $params = null);
            $stmt = new DbConnStatement($r);
            if (MyDB::error()) {
                $stmt->setErrno(MyDB::errno());
                $stmt->setError(MyDB::error());
            }
            return $stmt;            
        }
	
        /**
         * 
         * @param string $sql
         * @param array $params
         * @return DbConnStatement
         */
	function query($sql, $params = null) {
		if (!$this->checkconn()) return false;
                if (is_array($params)) {
                    foreach($params as $k => $v) {
                        $sql = preg_replace('/' . preg_quote(':' . $k, '/') . '\b/', $this->quote($v), $sql);
                    }
                }
                $this->lastQuery = $sql;
		if ($this->doLog) $this->sqllog.= ($this->sqllog?"\n\n###+SQL+STATEMENT+###\n":"").$sql;
		$r = @MyDB::query($sql, $this->conn);
                
		if ($r) {
			return $r;
		} elseif (!$this->error()) {
			return true;
		} else {
			return false;
		}
	}
	
	function affected_rows() {
		return @MyDB::affected_rows($this->conn);
	}
	
	function free_result($r) {
		return @MyDB::free_result($r);
	}
	
	function num_rows($r) {
		return @MyDB::num_rows($r);
	}
	
	function num_fields($r) {
		return @MyDB::num_fields($r);
	}
	
	function field_name($r, $i) {
		return @MyDB::field_name($r, $i);
	}
	
	function fetch_array($r, $FetchKeys = MyDB::BOTH) {
		return @MyDB::fetch_array($r, $FetchKeys);
	}
	
	function fetch_assoc($r) {
		return @MyDB::fetch_assoc($r);
	}
	
	static function escape_string($string) {
		if (function_exists("MyDB::real_escape_string")) {
                    return @MyDB::real_escape_string($string);
		}
		return @MyDB::escape_string($string);
	}
	
	function error() {
		return (MyDB::error() ? MyDB::errno().": ".MyDB::error() : "");
	}
        
        static function quote($val) {
            $t = gettype($val);
            //echo '#' . __LINE__ . ' quote val ' . $val . ' => ' . gettype($val) . '<br>' . PHP_EOL;
            if ($val instanceof DbExpr) { return (string)$val; }
            if ($t === 'NULL'   ) { return 'NULL'; }
            if ($t === 'integer') { return $val;   }
            if ($t === 'double' ) { return $val;   }
//            if ($t === 'string' && preg_match('/^[0-9]+(\.[0-9]+$)?/', trim($val))) return $val;
            return '"' . self::escape_string($val) . '"';
        }
        
        static function quoteIdentifier($identifier) {
            return '`' . self::escape_string($identifier) . '`';
        }
	
	static function escape($string) {
		return self::escape_string($string);
	}
	
	function query_count($FromWhere) { return $this->count_rows($FromWhere); }
	function count_rows($FromWhere) {
		$q = "SELECT COUNT(*) AS count FROM ".$FromWhere;
		$r = $this->query($q);
		
		if ($r) {
			$n = $this->num_rows($r);
			$row = $this->fetch_array($r);
			$this->free_result($r);
			if ($n > 1) return $n;
			return $row["count"];
		}
		return false;
	}
	
	function countDistinct($DistField, $FromWhere) {
		$q = "SELECT COUNT(DISTINCT(`$DistField`) AS count FROM ".$FromWhere;
		$r = $this->query($q);
		
		if ($r) {
			$row = $this->fetch_array($r);
			$this->free_result($r);
			return $row["count"];;
		}
		return false;
	}
	
        /**
         * 
         * @param string $sql
         * @param int $max_rows
         * @param array $params
         * @return array assoc-recorditems
         */
	function query_rows($sql, $max_rows = 0, $params = array()) {
		$r = $this->query($sql, $params);
		$rows = array();
		if ($r) {
			$n = $this->num_rows($r);
			if ($max_rows && $n > $max_rows) $n = $max_rows;
			for ($i = 0; $i < $n; $i++) {
				$rows[] = $this->fetch_assoc($r);
			}
			$this->free_result($r);
			return $rows;
		}
		return false;
	}
	
	function setFieldValue($fld, $val, $typ = "string", $null = false) { // typ: string, integer, float
		$set = " `$fld` = ";
		if ($null && ($val === NULL || $val === false || $val === "")) $set.= " NULL";
		elseif ($typ == "integer" || $typ == "int") $set.= (int) $val;
		elseif ($typ == "float") $set.= (float) $val;
		elseif ($typ == "function") $set.= $val;
		else $set.= "\"".$this->escape($val)."\""; // ($typ == "string") 
		return $set;
	}
	
	function insert_id() {
		if (!MyDB::error() && MyDB::insert_id()) return MyDB::insert_id();
		return false;
	}
	
        function query_row($sql, $params = null) {
                return $this->query_singlerow($sql, $params);
        }
	function query_singlerow($sql, $params = null) {
		$rows = $this->query_rows($sql, 1, $params);
		return (is_array($rows) && count($rows)) ? $rows[0] : false;
	}
	function query_one($sql, $params = null) {
		$rows = $this->query_rows($sql, 1, $params);
		return (is_array($rows) && count($rows)) ? current($rows[0]) : false;
	}
	
	function query_export_csv($sql, $file, $sep = ";", $encl = "\"", $enclMask = "\"\"", $fileAppend = false) {
		$fp = fopen($file, (!$fileAppend?"w+":"a+"));
		if ($fp) {
			$r = $this->query($sql);
			if ($r) {
				$num_fields = $this->num_fields($r);
				for($j = 0; $j < $num_fields; $j++) {
					fputs($fp, ($j?$sep:"#FIELDS: ").$encl.str_replace($encl, $enclMask,$this->field_name($r,$j)).$encl);
				}
				
				for($i = 0; $i < $this->num_rows($r); $i++) {
					$e = $this->fetch_array($r, MyDB::NUM);
					fputs($fp, "\n");
					for($j = 0; $j < $num_fields; $j++) {
						fputs($fp, ($j?$sep:"").$encl.str_replace($encl, $enclMask,$e[$j]).$encl);
					}
				}
				$this->free_result($r);
				return true;
			}
			fclose($fp);
		}
		return false;
	}
	
	function get_fields($tbl, $like = "") {
		$sql = "SHOW FULL COLUMNS FROM `$tbl`".($like?" LIKE \"$like\"":"");
		$rows = $this->query_rows($sql);
		if (!$rows) {
			$sql="SHOW FIELDS FROM $tbl".($like?" LIKE \"$like\"":"");
			$rows = $this->query_rows($sql);
			if (!$rows) return false;
		}
		return $rows;
	}
	
	function get_fields_assoc($tbl, $like = "") {
		$rows = $this->get_fields($tbl, $like);
		
		if ($rows) {
			$assoc_rows = array();
			foreach($rows as $k => $v) {
				$assoc_rows[$v["Field"]] = $v;
				$assoc_rows[$v["Field"]]["Size"] = "";
				if (strpos($v["Type"],"(")!==false) $assoc_rows[$v["Field"]]["Size"] = substr($v["Type"], strpos($v["Type"],"(")+1,-1); 
			}
			return $assoc_rows;
		}
		return false;
	}
	
	function get_field_definition($tbl, $fld) {
		$sql = "SHOW FULL COLUMNS FROM `$tbl` LIKE 'anrede'";
		$row = $this->query_singlerow($sql);
		if (!$row) {
			$sql="SHOW FIELDS FROM $tbl LIKE '$fld'";
			$row = $this->query_singlerow($sql);
		}
		return $row;
	}
	
	function get_field_options($tbl, $fld) {
		$sql="SHOW FIELDS FROM $tbl LIKE '$fld'";
		$row = $this->query_singlerow($sql);
		if ($row) {
			if ($row["Type"]) {
				if (strtolower(substr($row["Type"], 0, 6)) == "enum('") {
					return $options = explode("','", substr($row["Type"], 6, -2));
				}
				if (strtolower(substr($row["Type"], 0, 5)) == "set('") {
					return $options = explode("','", substr($row["Type"], 5, -2));
				}
			}
		}
		return false;
	}
	
	function set_field_options($tbl, $fld, $options, $row = false) {
		if (!$row) $row = $this->get_field_definition($tbl, $fld);
		$type = array_shift(explode("(", $row["Type"]));
		if ($type != "enum" && $type != "set") {
			return false;
		}
		
		// Build update sql
		$sql = "ALTER TABLE `$tbl` CHANGE `$fld` `$fld` ";
		$sql.= "$type($options) ";
		if (isset($row["Collation"]) && is_int(strpos($row["Collation"], "_"))) {
			$character = array_shift(explode("_", $row["Collation"]));
			$sql.= "CHARACTER SET $character COLLATE ".$row["Collation"]." ";
		}
		$sql.= ($row["Null"]) ? "NULL " : "NOT NULL ";
		$sql.= ($row["Default"]) ? "DEFAULT '".$row["Default"]."'" : "Default NULL ";
		return $this->query($sql);
	}
	
	function add_field_option($tbl, $fld, $option) {
		$row = $this->get_field_definition($tbl, $fld);
		$type_size = $row["Type"];
		$type = array_shift(explode("(", $type_size));
		if ($type != "enum" && $type != "set") {
			return false;
		}
		
		// Build new Optionlist
		$p = strpos($type_size, "('");
		$size = substr($type_size, $p+1, -1);
		if (strpos($size, "'".$option."'") === false) {
			$options = $size.($size ? "," : "")."'".$option."'";
			return $this->set_field_options($tbl, $fld, $options, $row);
		} return true;
	}
	
	function delete_field_option($tbl, $fld, $option) {
		$row = $this->get_field_definition($tbl, $fld);
		$type_size = $row["Type"];
		$type = array_shift(explode("(", $type_size));
		if ($type != "enum" && $type != "set") {
			return false;
		}
		
		// Build new Optionlist
		$p = strpos($type_size, "('");
		$size = substr($type_size, $p+1, -1);
		if (strpos($size, "'".$option."'") !== false) {
			$a = explode("','", substr($size, 1, -1));
			foreach($a as $k => $v) if ($v == $option) unset($a[$k]);
			$options = "'".implode("','", $a)."'";
			return $this->set_field_options($tbl, $fld, $options, $row);
		} return true;
	}
	
	function get_field_type($tbl, $fld, $re = "") {
		$sql="SHOW FIELDS FROM $tbl LIKE '$fld'";
		$row = $this->query_singlerow($sql);
		if ($row) {
			if ($row["Type"]) {
				switch($re) {
					case "typeonly":
					return array_shift(explode("(",$row["Type"]));
					
					case "size":
					if (is_int($p = strpos("(", $row["Type"]))) substr($row["Type"], $p+1, -1);
					
					default:
					return $row["Type"];
					
				}
			}
		}
		return false;
	}
	
	function get_table_list($like = "") {
		return $this->query_rows("show tables".($like?" LIKE \"$like\"":""));
	}
	
	function get_table_statuslist($like = "") {
		return $this->query_rows("show table status".($like?" LIKE \"$like\"":""));
	}
}


function sql_match_rows($sql) {
	global $SQL_MATCH_CACHE;
	global $db;
	global $error;
	$cacheKey = md5($sql);
	if (!isset($SQL_MATCH_CACHE) || !isset($SQL_MATCH_CACHE[$cacheKey])) {
		$row = $db->query_rows($sql);
		if ($db->error()) $error.= $db->error()."<br>\n";
		$SQL_MATCH_CACHE[$cacheKey] = (is_array($row) && !empty($row[0]));
	}
	//if (!$SQL_MATCH_CACHE[$cacheKey]) $error.= $sql."<br>\n";
	return $SQL_MATCH_CACHE[$cacheKey];
}

if (basename(__FILE__) == basename($_SERVER["PHP_SELF"])) {
	$modelReflector = new ReflectionClass('DBConn');
	//echo "#".__LINE__." ".basename(__FILE__)."<pre> getMethods:".print_r($modelReflector->getMethods(),1)."</pre><br>\n";
	
	//$db = new DBConn("localhost", "mt_move", "root", "");
	$db = new DBConn("localhost", "mt_move_bayer", "mt_move", "mt-MoVeX-04");
	//$db->close();
	
	echo "#".__LINE__." ".print_r($db->get_table_statuslist("%benutzer"),1)."<br>\n";
	echo "#".__LINE__." ".print_r($db->get_field_type("mm_umzuege","anrede"),1)."<br>\n";
	echo "#".__LINE__." ".print_r($db->get_fields("mm_umzuege","anrede"),1)."<br>\n";
	echo "#".__LINE__." ".print_r($db->get_field_options("mm_umzuege","anrede"),1)."<br>\n";
	echo "#".__LINE__." ".print_r($db->add_field_option("mm_umzuege","anrede","unbekannt"),1)."<br>\n";
	echo "#".__LINE__." ".print_r($db->get_field_options("mm_umzuege","anrede"),1)."<br>\n";
	echo "#".__LINE__." ".print_r($db->delete_field_option("mm_umzuege","anrede","unbekannt"),1)."<br>\n";
	echo "#".__LINE__." ".print_r($db->get_fields("mm_umzuege","anrede"),1)."<br>\n";
	echo "#".__LINE__." ".print_r($db->count_rows("mm_umzuege"),1)."<br>\n";
	echo "#".__LINE__." ".print_r($db->countDistinct("mm_umzuege","anrede"),1)."<br>\n";
	$csvExport = $db->query_export_csv("SELECT * FROM mm_user", "mm_user.csv");
	echo "csvExport: $csvExport<br>\n";
}
?>