<?php
error_reporting(E_ALL);

if (isset($MConf) && $MConf["DB_Host"]) {
    $port = $MConf['DB_Port'] ?? '3306';
    $db = new dbconn($MConf["DB_Host"], $MConf["DB_Name"], $MConf["DB_User"], $MConf["DB_Pass"], $port);
    $conn = null;
    $connid = null;
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

class MyDB {

    const ASSOC = MYSQLI_ASSOC;
    const NUM   = MYSQLI_NUM;
    const BOTH  = MYSQLI_BOTH;

    public static function query($sql) {
        return dbconn::getInstance()->query($sql);
    }

    public static function __callStatic($name, $args) {
        $db = dbconn::getInstance();
        switch($name) {
            case 'query':
            case 'error':
            case 'errno':
                return call_user_func_array(array($db, $name), $args);

            case 'last_insert_id':
            case 'insert_id':
                return $db->insert_id();
            //return call_user_method_array($db, 'insert_id', $args);


            case 'escape_string':
            case 'real_escape_string':
                return $db->escape($args[0]);

            case 'connect':
            case 'select_db':
            case 'db_name':
                throw new Exception("Nicht unterstützter und adaptierter ehemaliger mysql-Funktion " . $name);
                break;
        }

        if (count($args)) {
            if ($args[0] instanceof mysqli_result) {
                /* @var $stmt mysqli_result */
                $stmt = array_shift($args);
                //$stmt = new mysqli_result();
                switch ($name) {
                    case 'free_result':
                        return $stmt->close();

                    case 'field_name':
                        return $stmt->fetch_field_direct($args[0])->name;

                    case 'field_table':
                        return $stmt->fetch_field_direct($args[0])->table;

                    case 'num_fields':
                        return $stmt->field_count;
                }
                if (method_exists($stmt, $name)) {
                    return call_user_func_array(array($stmt, $name), $args);
                }
                elseif (property_exists($stmt, $name)) {
                    return $stmt->$name;
                }
                else {
                    throw new Exception('Noch nicht adaptierte mysql-result-function: MyDB::' . $name);
                }
            }
        }
        echo "Nicht unterstützter und adaptierter ehemaliger mysql-Funktion " . $name;
        throw new Exception("Nicht unterstützter und adaptierter ehemaliger mysql-Funktion " . $name);
    }
}


class DbConnStatement extends mysqli_result {
    /* @var $handle mysqli_result */
    private $conn = null;
    private $handle = null;
    /* @var $fetchType DbFetchType */
    private $fetchType = 'ASSOC';
    public $errno = 0;
    public $error = '';

    public function __get($property) {
        if (property_exists($this->handle, $property)) {
            return $this->handle->$property;
        }
        return null;
    }

    public function __call($name, $arguments) {
        if (method_exists($this->handle, $name)) {
            return call_user_method_array($name, $this->handle, $arguments);
        }
        throw new BadMethodCallException("Unbekannte Methode " . $name);
    }

    public function __construct(mysqli $conn, mysqli_result $resultHandle) {
        $this->conn = $conn;
        $this->handle = $resultHandle;
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

    public function fetch($fetchType = '')
    {
        if (!$fetchType) $fetchType = $this->fetchType;
        $stmt = $this->handle();
        switch($fetchType) {
            case 'ASSOC':
                return $stmt->fetch_assoc();
            case 'BOTH':
                return $stmt->fetch_array();
            case 'NUM':
                $stmt->fetch_array(MYSQLI_NUM);
            case 'OBJECT':
                return $stmt->fetch_object();
        }
    }

    public function count() {
        return $this->handle->num_rows;
    }

    public function num_rows() {
        return $this->count();
    }

    public function countFields() {
        return $this->handle->field_count;
    }
    public function num_fields() {
        return $this->countFields();
    }

    public function field($offset = 0) {
        $field = $this->handle->fetch_field_direct($offset);
        if ($field) {
            return $field->name;
        }
        return null;
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
    private static $instance = null;
    private $connect_error = true;

    public function __construct($Host, $Db, $User, $Pass, $Port = 3306) {
        try {
            $this->conn = new mysqli($Host, $User, $Pass, $Db, $Port);
        } finally {
            $this->connect_error = $this->conn->connect_error;
        }

        if (!$this->connect_error) {
//                    echo 'C O N N E C T E D ! ! !<br>' . PHP_EOL;
            self::$instance = $this;
            $this->connected = true;
            $this->conn->query('SET NAMES "latin1"' );
            $this->conndb = $this->conn->select_db($Db);
        } else {
            $this->errors = "#".__LINE__." Error-DB-Conn: $Host:$Port, $Db <br>\n" . print_r($this->connect_error, 1);
            error_log($this->errors);
            throw new Exception( $this->errors);
        }
    }

    public function connect_error() {
        return $this->connect_error;
    }

    /**
     *
     * @return dbconn
     */
    public static function getInstance() {
        return self::$instance;
    }

    public static function dbquery($sql, $conn) {
        if (null !== self::$instance) return self::$instance->query($sql);
        throw new Exception("Datenbank wurde noch intitialisiert");
    }

    public function isConnected() {
        return $this->connected;
    }

    function close() {
        if (is_resource($this->conn)) {
            $this->conn->close();
        }
    }

    function __destruct() {
        $this->close();
    }

    function checkconn() {
        return $this->isConnected();
    }

    /**
     *
     * @param string $sql
     * @param array $params
     * @return DbConnStatement
     */
    function ___DEL___query2stmt($sql, $params = null) {
        $r = $this->query($sql, $params = null);
        $stmt = new DbConnStatement($r);

        if ($this->conn->error()) {
            $stmt->setErrno($this->conn->errno());
            $stmt->setError($this->conn->error());
        }
        return $stmt;
    }

    /**
     *
     * @param string $sql
     * @param array $params
     * @return mysqli_result
     */
    function query($sql, $params = null) {
        $r = null;
        if (!$this->checkconn()) return false;
        if (is_array($params)) {
            foreach($params as $k => $v) {
                $sql = preg_replace('/' . preg_quote(':' . $k, '/') . '\b/', $this->quote($v), $sql);
            }
        }

        $this->lastQuery = $sql;
        if ($this->doLog) $this->sqllog.= ($this->sqllog?"\n\n###+SQL+STATEMENT+###\n":"").$sql;
        try {
            if (!$this->conn || !method_exists($this->conn, 'query')) {
                echo 'this->conn-query kann nicht aufgerufen werden!<br>';
                try { throw new Exception("ShowStackTrace");}catch(Exception $e){die( $e->getTraceAsString());}
                return null;
            }
            $r = $this->conn->query($sql);
            // die('#'.__LINE__ . ' ' . __FUNCTION__ . ' sql: ' . $sql . '<br>' . print_r($r->fetch_all(),1));
        } catch(Exception $e) {
            echo $e->getMessage() . '<br>';
            echo $e->getTraceAsString();
        }

        return $r;
    }

    function affected_rows() {
        return @$this->conn->affected_rows($this->conn);
    }

    function free_result($r) {
        if ($r instanceof mysqli_result) return $r->close ();
    }

    function num_rows($r) {
        if (@isset($r->num_rows)) return $r->num_rows;
        return 0;
    }

    function num_fields($r) {
        return @$this->conn->num_fields($r);
    }

    function field_name($r, $i) {
        return @$this->conn->field_name($r, $i);
    }

    function fetch_array(mysqli_result $r, $fetchType = MyDB::BOTH) {
        if ($fetchType === MyDB::NUM && method_exists($r, 'fetch_array')) {
            return $r->fetch_array($fetchType);
        }
        if ($fetchType == MyDB::BOTH && method_exists($r, 'fetch_array')) {
            return $r->fetch_array($fetchType);
        }

        if ($fetchType == MyDB::ASSOC && method_exists($r, 'fetch_assoc')) {
            return $r->fetch_assoc();
        }

        if (method_exists($r, 'fetch_array')) {
            return $r->fetch_array();
        }
        return array();
    }

    function fetch_assoc($r) {
        return @$this->conn->fetch_assoc($r);
    }

    static function escape_string($string) {
        if (self::getInstance() && self::getInstance()->connect_error()) {
            return self::getInstance()->real_escape_string($string);
        }
        return $string;
    }

    function error() {
        if (!property_exists($this->conn, 'error')) {
            var_dump($this->conn);
            //throw new Exception("Unbekannter Fehler bei Aufruf von " . __METHOD__);
            return '';
        }
        return ($this->conn->error ? '#'.__LINE__ . ' ' . __METHOD__ . ' ' . $this->conn->errno.": ".$this->conn->error : "");
    }

    function errno() {
        if (!property_exists($this->conn, 'errno')) {
            var_dump($this->conn);
            //throw new Exception("Unbekannter Fehler bei Aufruf von " . __METHOD__);
            return '';
        }
        return $this->conn->errno;
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
            $n = $r->num_rows;
            if ($max_rows && $n > $max_rows) {
                $n = $max_rows;
            }
            for ($i = 0; $i < $n; $i++) {
                $rows[] = $r->fetch_assoc();
            }
            $r->close();
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
        if (!$this->error() && $this->conn->insert_id) {
            return $this->conn->insert_id;
        }
        return false;
    }

    function last_insert_id() {
        return $this->insert_id();
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
    $db = new DBConn("localhost", "mt_move_bayer", "root", "geheim3");
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
