<?php

/**
* Session Class ( Static )
*
* @package       packages
* @subpackage    sess
* @category      sessions
*/

Class Sess {
        
    public static $driver;  // driver instance
    public static $params;  // session parameters

    /**
    * Constructor
    *
    * Sets the variables and runs the compilation routine
    * 
    * @access    public
    * @return    void
    */
    public function __construct($params = array())
    {
        global $logger, $config;
        static $logged = null;

        if( ! isset(getInstance()->sess))
        {
            getInstance()->sess = $this; // Available it in the contoller $this->sess->method();

            self::$params = $params;
            $this->init(self::$params);
        }

        if($logged == null AND $config['log_threshold'] > 0)
        {
            $logger->debug('$_SESSION: '.preg_replace('/\n/', '', print_r(self::$driver->getAllData())));
        }

        $logger->debug('Sess Class Initialized');
        $logged = true;
    }

    // --------------------------------------------------------------------

    /**
     * Start the sessions
     * 
     * @param  array  $params package configuration
     * @return void
     */
    public function init($params = array())
    {
        static $sessionStart = null;

        $sess = getConfig('sess');

        if ($sessionStart == null)
        {
            $driver   = (isset($params['driver'])) ? $params['driver'] : $sess['driver'];
            $database = (isset($params['db'])) ? $params['db'] : $sess['db'];

            self::$driver = $driver;              // Driver object.
            self::$driver->init($params, $sess);  // Start the sessions

            $sessionStart = true;
        }
    }
    
    // ------------------------------------------------------------------------

    /**
     * Call the driver
     * 
     * @param  string $method 
     * @param  array $arguments
     * @return void
     */
    public function __call($method, $arguments)
    {
        return call_user_func_array(array(self::$driver, $method), $arguments);
    }
    
}

/* End of file sess.php */
/* Location: ./packages/sess/releases/0.0.1/sess.php */