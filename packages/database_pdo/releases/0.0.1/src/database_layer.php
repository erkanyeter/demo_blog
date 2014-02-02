<?php
namespace Database_Pdo\Src;

/**
 * PDO Layer.
 *
 * @package       packages
 * @subpackage    database_pdo\src
 * @category      database
 * @link            
 * 
 */

Class Database_Layer extends Database_Crud {
    
    public $prepare                 = false;    // prepare switch
    public $p_opt                   = array();  // prepare options
    public $last_sql                = null;     // stores last queried sql
    public $last_values             = array();  // stores last executed PDO values by exec_count
    public $query_count             = 0;        // count all queries.
    public $exec_count              = 0;        // count exec methods.
    public $prep_queries            = array();
    public $benchmark               = '';       // stores benchmark info
    public $current_row             = 0;        // stores the current row
    public $stmt_result             = array();  // stores current result for firstRow() nextRow() iteration
    public $use_bind_values         = false;    // bind value usage switch
    public $use_bind_params         = false;    // bind param usage switch
    public $last_bind_values        = array();  // Last bindValues and bindParams
    public $last_bind_params        = array();  // We store binds values to array()
                                                // because of we need it in lastQuery() function

    public $Stmt                    = null;     // PDOStatement Object

    /**
    * Pdo connection object.
    *
    * @var string
    */
    public $_conn = null;

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
        global $config;

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
            logMe('debug', 'SQL: '.trim(preg_replace('/\n/', ' ', $sql), "\n").' time: '.number_format($end_time - $start_time, 4));   
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

/* End of file database_layer.php */
/* Location: ./packages/database_pdo/releases/0.0.1/src/database_layer.php */