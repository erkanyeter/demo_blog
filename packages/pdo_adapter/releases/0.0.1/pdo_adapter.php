<?php

/**
 * Database Adapter Class
 *
 * @package       packages
 * @subpackage    database_pdo/src
 * @category      database adapter
 * @link
 */
Abstract Class Pdo_Adapter extends Pdo_Crud {

    public $hostname = '';
    public $username = ''; 
    public $password = ''; 
    public $database = '';
    public $driver   = ''; // optional
    public $char_set = ''; // optional
    public $dbh_port = ''; // optional
    public $dsn      = ''; // optional
    public $options  = array(); // optional
    
    public $prefix   = '';

    //--------------------------------------------------------------

    public $prepare          = false;    // prepare switch
    public $p_opt            = array();  // prepare options
    public $last_sql         = null;     // stores last queried sql
    public $last_values      = array();  // stores last executed PDO values by exec_count
    public $query_count      = 0;        // count all queries.
    public $exec_count       = 0;        // count exec methods.
    public $prep_queries     = array();
    public $benchmark        = '';       // stores benchmark info
    public $current_row      = 0;        // stores the current row
    public $stmt_result      = array();  // stores current result for firstRow() nextRow() iteration
    public $use_bind_values  = false;    // bind value usage switch
    public $use_bind_params  = false;    // bind param usage switch
    public $last_bind_values = array();  // Last bindValues and bindParams
    public $last_bind_params = array();  // We store binds values to array()
                                         // because of we need it in lastQuery()

    public $Stmt = null;     // PDOStatement Object

    //--------------------------------------------------------------

    /**
    * Pdo connection object.
    *
    * @var string
    */
    public $_conn = null;

    
    /**
    * Db object.
    * 
    * @var string
    */
    public $_pdo = null;  
    
    /**
    * The character used for escaping
    * 
    * @var string
    */
    public $_escape_char = '`';
    
    /**
     * Build database variables
     * 
     * @param array $param
     */
    public function __construct($param) 
    {
        $db_var = $param['variable'];

        if( isset($param['dsn']) AND ! empty($param['dsn']) )  // Dsn Connection..
        {
            $this->driver   = $param['driver'];  // optional
            $this->username = isset($param['username']) ? $param['username'] : '';    // required
            $this->password = isset($param['password']) ? $param['password'] : '';    // required
            $this->char_set = isset($param['char_set']) ? $param['char_set'] : '';    // optional
            $this->dsn      = $param['dsn'];                   // required
            $this->options  = isset($param['options']) ? $param['options'] : array(); // optional
            
        } else                 // Standart Connection..
        {

            $this->hostname = $param['hostname'];           // required
            $this->username = $param['username'];           // required
            $this->password = $param['password'];           // required
            $this->database = $param['database'];           // required
            $this->driver   = $param['driver'];  // optional
            $this->prefix   = strtolower($param['prefix']); // optional
            $this->char_set = isset($param['char_set']) ? $param['char_set'] : '';    // optional
            $this->dbh_port = isset($param['dbh_port']) ? $param['dbh_port'] : '';    // optional
            $this->options  = isset($param['options']) ? $param['options'] : array(); // optional
        }     
        
        if( ! is_array($this->options) ) $this->options = array();   
    }
    
    // --------------------------------------------------------------------                      
    
    /**
    * Connect to PDO (default Mysql)
    * 
    * @param    string $dsn  Dsn
    * @param    string $user Db username
    * @param    mixed  $pass Db password
    * @param    array  $options Db Driver options
    * @return   void
    */
    public function connect()
    {
        // If connection is ok .. not need to again connect..
        if ($this->_conn) { return; }
        
        $port = empty($this->dbh_port) ? '' : 'port:'.$this->dbh_port.';';
        $dsn  = empty($this->dsn) ? 'mysql:host='.$this->hostname.';'.$port.'dbname='.$this->database : $this->dsn;

        // array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $this->char_set") it occurs an error !
        $this->_pdo = $this->pdoConnect($dsn, $this->username, $this->password, $this->options);
             
        if( ! empty($this->char_set) )
        {
            $this->_conn->exec("SET NAMES '" . $this->char_set . "'");
        }
        
        // We set exception attribute for always showing the pdo exceptions errors. (ersin)
        $this->_conn->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
    }     
    
    // --------------------------------------------------------------------

    /**
    * Connect to PDO
    *
    * @author   Ersin Guvenc
    * @param    string $dsn  Dsn
    * @param    string $user Db username
    * @param    mixed  $pass Db password
    * @param    array  $options Db Driver options
    * @return   void
    */
    public function pdoConnect($dsn, $user = null, $pass = null, $options = null)
    {
        $this->_conn = new \PDO($dsn, $user, $pass, $options);

        return $this;
    }

    // --------------------------------------------------------------------

    /**
    * Set PDO native Prepare() function
    *
    * @param    array $options prepare options
    */
    public function prep($options = array())
    {
        $this->p_opt   = $options;
        $this->prepare = true;

        return $this;
    }

    // --------------------------------------------------------------------

    /**
    * Flexible Prepared or Direct Query
    *
    * @param   string $sql
    * @return  object PDOStatement
    */
    public function query($sql = null)
    {
        global $config, $logger;

        $this->last_sql = $sql;

        if($this->prepare)
        {
            $this->Stmt = $this->_conn->prepare($sql, $this->p_opt);

            $this->prep_queries[] = $sql;  // Save the  query for debugging

            ++$this->query_count;

            return $this;
        }

        //------------------------------------

        list($sm, $ss) = explode(' ', microtime());
        $start_time = ($sm + $ss);

        $this->Stmt = $this->_conn->query($sql);

        list($em, $es) = explode(' ', microtime());
        $end_time = ($em + $es);
        
        //------------------------------------
        
        if($config['log_queries'])
        {
            $logger->debug('SQL: '.trim(preg_replace('/\n/', ' ', $sql), "\n").' time: '.number_format($end_time - $start_time, 4));   
        }
        
        ++$this->query_count;

        return $this;
    }

    // --------------------------------------------------------------------

    /**
    * PDO Last Insert Id
    *
    * @return  object PDO::Statement
    */
    public function insertId()
    {
        return $this->_conn->lastInsertId();
    }
 
    // --------------------------------------------------------------------
    
    /**
    * Check simply array is associative ?
    * 
    * @param type $a
    * @return type 
    */
    public function isAssocArray( $arr )
    {
        return is_array( $arr ) AND array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
    * Get PDO Instance
    * for DBFactory Class ..
    */
    public function getConnection()
    {
        return $this->_pdo;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Test if a connection is active
     *
     * @return boolean
     */
    public function isConnected()
    {
        return ((bool) ($this->_conn instanceof \PDO));
    }

    // --------------------------------------------------------------------
    
    /**
    * Reconnect
    *
    * Keep / reestablish the db connection if no queries have been
    * sent for a length of time exceeding the server's idle timeout
    *
    * @access    public
    * @return    void
    */
    public function reconnect()
    {
        $this->connect();
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Get Database Version number.
    *
    * @access    public
    * @return    string
    */
    public function getVersion()
    {
        $this->connect();
        
        try
        {
            $version = $this->_conn->getAttribute(\PDO::ATTR_SERVER_VERSION);
            
        } catch (\PDOException $e)  // don't show excepiton
        {
            return null; // If the driver doesn't support getting attributes
        }
        
        $matches = null;
        if (preg_match('/((?:[0-9]{1,2}\.){1,3}[0-9]{1,2})/', $version, $matches))
        {
            return $matches[1];
        } 
        else 
        {
            return null;
        }
    }

    // --------------------------------------------------------------------
    
    /**
    * Begin a transaction.
    */
    public function transaction()
    {
        $this->__wakeup();
        $this->_conn->beginTransaction();
        
        return $this;
    }

    // --------------------------------------------------------------------
    
    /**
    * Commit a transaction.
    */
    public function commit()
    {
        $this->__wakeup(); 
        $this->_conn->commit();
        
        return $this;
    }

    // --------------------------------------------------------------------
    
    /**
     * Check active transaction status
     * 
     * @return bool
     */
    public function inTransaction()
    {
        return $this->_conn->inTransaction();
    }

    // --------------------------------------------------------------------

    /**
    * Roll-back a transaction.
    */
    public function rollBack()
    {    
        $this->__wakeup();
        $this->_conn->rollBack();
        
        return $this;
    }
    
    // --------------------------------------------------------------------
    
    public function __sleep()
    {   
        return array(
        'hostname', 
        'username', 
        'password', 
        'database', 
        'driver', 
        'char_set', 
        'dbh_port', 
        'dsn', 
        'options');
    }
    
    // --------------------------------------------------------------------
    
    public function __wakeup()
    {
        $this->connect();
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Set pdo attribute
    * 
    * @param type $key
    * @param type $val 
    */
    public function setAttribute($key, $val)
    {
        $this->_conn->setAttribute($key, $val);
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Return error info in PDO::PDO::ERRMODE_SILENT mode
    * 
    * @return type 
    */
    public function getErrors()
    {
        return $this->_conn->errorInfo();
    }
    
    // --------------------------------------------------------------------

    /**
    * Get available drivers on your host
    *
    * @return  object PDO::Statement
    */
    public function getDrivers()
    {
        return $this->_conn->getAvailableDrivers();
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Close the database connetion. 
     */
    public function __destruct()
    {
        $this->_conn = null;
    }
}


/*
| PDO paramater type constants
| @link http://php.net/manual/en/pdo.constants.php
| These prefs are used when working with query results.
*/
define('PARAM_NULL', 0);  // null
define('PARAM_INT' , 1);  // integer
define('PARAM_STR' , 2);  // string
define('PARAM_LOB' , 3);  // integer  Large Object Data (lob)
define('PARAM_STMT', 4);  // integer  Represents a recordset type ( Not currently supported by any drivers).
define('PARAM_BOOL', 5);  // boolean                                
define('PARAM_INOUT' , -2147483648); // PDO::PARAM_INPUT_OUTPUT integer
define('LAZY', 1);
define('ASSOC', 2);
define('NUM', 3);
define('BOTH', 4);
define('OBJ', 5);
define('ROW', 5);
define('BOUND', 6);
define('COLUMN', 7);
define('AS_CLASS', 8);
define('FUNC', 10);
define('NAMED', 11);
define('KEY_PAIR', 12);
define('GROUP', 65536);
define('UNIQUE', 196608);
define('CLASS_TYPE', 262144);
define('SERIALIZE', 524288);
define('PROPS_LATE', 1048576);
define('ORI_NEXT', 0);
define('ORI_PRIOR', 1);
define('ORI_FIRST', 2);
define('ORI_LAST', 3);
define('ORI_ABS', 4);
define('ORI_REL', 5);
define('INTO', 9);


/* End of file pdo_adapter.php */
/* Location: ./packages/pdo_adapter/releases/0.0.1/pdo_adapter.php */