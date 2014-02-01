<?php

/**
 * Database Connection Class.
 *
 * @package       packages 
 * @subpackage    db 
 * @category      database connection
 * @link            
 */

Class Db {
    
    /**
     * Database connection variable
     * We can grab it globally. ( Db::$var )
     * 
     * @var string
     */
    public static $var = 'db';

    /** 
     * Database config
     * 
     * @var array
     */
    public static $config = array(); 

    /**
     * $db
     * 
     * @var object
     */
    public $db;

    /**
     * Constructor
     * 
     * @param string $dbVar database configuration key
     */
    public function __construct($dbVar = 'db')
    {
        $this->db = $this->connect(strtolower($dbVar));
        
        logMe('debug', 'Db Class Initialized');
    }
       
    // --------------------------------------------------------------------
    
    /**
     * Call methods from CRUD object
     * 
     * @param  string $method
     * @param  array $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return call_user_func_array(array($this->db, $method), $arguments);
    }

    // --------------------------------------------------------------------

    /**
     * Connect to Database
     * 
     * @param  string $dbVar
     * @return object
     */
    public function connect($dbVar = 'db')
    {        
        if(isset(getInstance()->{$dbVar}) AND is_object(getInstance()->{$dbVar}))
        {
            return getInstance()->{$dbVar};   // Lazy Loading.  
        }

        self::$config = getConfig('database'); // Get configuration

        if( ! isset(self::$config[$dbVar]))
        {
            throw new Exception('Undefined database configuration please set configuration for '.$dbVar);
        }

        self::$var = $dbVar;  // Store current database key.
                              // We use it in active record class.


        $db = self::$config[$dbVar];  // Get database object Pdo_Driver(); Class
        $db->connect();

        getInstance()->{$dbVar} = &$db;

        return $db; // database
    }
    
}

/* End of file db.php */
/* Location: ./packages/db/releases/0.0.1/db.php */