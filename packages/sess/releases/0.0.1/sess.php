<?php

/**
* Session Helper
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
        if( ! isset(getInstance()->sess))
        {
            getInstance()->sess = $this; // Available it in the contoller $this->sess->method();

            self::$params = $params;
            $this->start(self::$params);
        }

        logMe('debug', "Sess Class Initialized");
    }

    // --------------------------------------------------------------------

    /**
     * Start the sessions
     * 
     * @param  array  $params package configuration
     * @return void
     */
    public function start($params = array())
    {
        static $sessionStart = null;

        if ($sessionStart == null)
        {
            $driver   = (isset($params['driver'])) ? $params['driver'] : config('driver', 'sess');
            $database = (isset($params['database'])) ? $params['database'] : config('database', 'sess');

            self::$driver = $driver;      // Driver object.
            self::$driver->init($params); // Start the sessions

            if(get_class($driver) != 'Sess_Database' AND is_object($database))
            {
                logMe('error', 'Please check sess.php config file database item should be set to "null" if you don\'t use it.');
            }

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
        global $packages;

        $packageName = get_class(self::$driver);

        $this->start(self::$params);

        return call_user_func_array(array(self::$driver, $method), $arguments);
    }

}

/* End of file sess.php */
/* Location: ./packages/sess/releases/0.0.1/sess.php */