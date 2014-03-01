<?php

 /**
 * Cache Class ( Static )
 *
 * @package       packages
 * @subpackage    cache
 * @category      cache
 * @link
 */

Class Cache {

    private static $config;
    private static $driver;       // driver instance

    // --------------------------------------------------------------------
    
    /**
     * Constructor
     * 
     * @param array $config
     */
    public function __construct($config = array())
    {
        global $logger;

        if( ! isset(getInstance()->cache))
        {
            self::$config = getConfig('cache'); // app/config/cache.php config

            getInstance()->cache = $this; // Make available it in the controller $this->cache->method();

            $this->init($config);
        }

        $logger->debug('Cache Class Initialized');
    }

    // --------------------------------------------------------------------
    
    /**
     * Initalize and grab instance of the auth.
     * 
     * @param array $params
     * @return object
     */
    public function init($params = array())
    {
        if(count($params) > 0)
        {
            self::$config = array_merge($params, self::$config);
        }

        $driver_name  = strtolower(self::$config['driver']);   
        $realname     = str_replace('memcached', 'memcache', $driver_name);    // Driver name is edited. Only for memcached.
        $className    = 'Cache\Src\Cache_' . ucfirst($realname);

        self::$driver = new $className();     // call driver
        
        //----------- Check Driver ----------//

        $this->isSupported(self::$config['driver']); // Checking for supporting driver.

        //----------- Check Driver ----------//
        
        if(self::$config['driver'] == 'memcache' OR self::$config['driver'] == 'memcached')
        {
            $paramsKey    = array('hostname' => 1, 'port' => 2);
            $servers      = array_intersect_key(self::$config['servers'], $paramsKey);
            $countServers = count($servers);

            if($countServers < 2)
            {
                return false;
            }
            else
            {
                self::$driver->connectionSet = self::$config;
                self::$driver->connectMemcacheFamily(self::$config['driver']);
            }

            return true;  // Memcache end

        }
        elseif(self::$config['driver'] == 'redis')  // Redis Start
        {
            if(isset(self::$config['servers']['weight']))
            {
                unset(self::$config['servers']['weight']);
            }

            $paramsKey = array('hostname' => 1, 'port' => 2);
            $servers   = array_intersect_key(self::$config['servers'], $paramsKey);

            if( ! isset($servers['hostname']))
            {
                throw new Exception('A defined hostname could not be found.');
                exit;
            }
            else
            {
                self::$driver->connectionSet = self::$config;
                self::$driver->connect(self::$config['driver']);
            }
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

/* End of file cache.php */
/* Location: ./packages/cache/releases/0.0.1/cache.php */