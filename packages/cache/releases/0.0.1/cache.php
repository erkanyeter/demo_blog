<?php

 /**
 * Cace Class
 *
 * @package       packages
 * @subpackage    sess_database
 * @category      sessions
 * @link
 */
Class Cache {

    private $_config;
    private $_driver;
    private $_adapter;

    // --------------------------------------------------------------------
    
    /**
     * Constructor
     * 
     * @param array $config
     */
    public function __construct($config = array())
    {
        if(count($config) <= 0)
        {
            $this->_config = getConfig('cache'); // app/config/cache.php config
        }
        else
        {
            $this->_config = $config;
        }
        if( ! isset(getInstance()->cache))
        {
            getInstance()->cache = $this; // Make available it in the controller $this->catpcha->method();
        }

        $this->_setDriver($this->_config['driver']);
        $this->_isSupported($this->_config['driver']);        // Checking for supporting driver.
        $this->_setConnection($this->_config);

        logMe('debug', "Cache Class Initialized");
    }

    // --------------------------------------------------------------------
    
    /*
     * Set connection ( Optional Method )
     * 
     * @param string $driver
     * @return true or false
     */
    private function _setConnection($params = array())
    {
        if($this->_adapter == 'memcache' OR $this->_adapter == 'memcached')
        {
            $paramsKey    = array('hostname' => 1, 'port' => 2);
            $servers      = array_intersect_key($params['servers'], $paramsKey);
            $countServers = count($servers);

            if($countServers < 2)
            {
                return false;
            }
            else
            {
                $this->_driver->connectionSet = $params;
                $this->_driver->connectMemcacheFamily($this->_config['driver']);
            }
            return true;
        }
        else
        {
            return false;
        }
    }

    // --------------------------------------------------------------------

    /**
     * Set driver type
     * 
     * @param string $driver
     * @return object
     */
    private function _setDriver($driver = null)
    {
        if($driver == null)
        {
            $driver = $this->_config['driver'];
        }
        elseif(mb_strtolower($driver) == "memcache" OR mb_strtolower($driver) == "memcached")
        {
            $this->_adapter = $driver;
        }

        $editDriverName  = str_replace('memcached','memcache',$driver); // Driver name is edited. Only for memcached.
        $className       = 'Cache\Src\Cache_'.ucfirst(mb_strtolower($editDriverName));
        $this->_driver   = new $className();         // Drivers needed for the class are created.

    	return true;
    }

    // --------------------------------------------------------------------
    
    /**
     * Get
     * 
     * @param string $id
     * @return object
     */
    public function get($id)
    {
        return $this->_driver->get($id);
    }

    // --------------------------------------------------------------------
    
    /**
     * Get
     * 
     * @param string $id
     * @return object
     */
    public function getAllKeys()
    {
        if($this->_adapter == 'memcached')
        {
            return $this->_driver->getAllKeys();
        }
        else
        {
            return false;
        }
    }

    // --------------------------------------------------------------------
    
    /**
     * Save
     * 
     * @param string $id
     * @return object
     */
    public function set($id, $data, $ttl = 60)
    {
        return $this->_driver->set($id, $data, $ttl);
    }

    // --------------------------------------------------------------------

    /**
     * Replace
     * @param  [type]  $id
     * @param  [type]  $data
     * @param  integer $ttl
     * @return [type]
     */
    public function replace($id, $data, $ttl = 60)
    {
        return $this->_driver->set($id, $data, $ttl);
    }

    /**
     * Clean all data
     * 
     * @param string $id
     * @return object
     */
    public function clean()
    {
        return $this->_driver->clean();
    }

    // --------------------------------------------------------------------

    /**
     * Delete
     * 
     * @param string $id
     * @return object
     */
    public function delete($id)
    {
        return $this->_driver->delete($id);
    }

    // --------------------------------------------------------------------

    /**
     * Cache Info
     * 
     * @param
     * @return object
     */
    public function cacheInfo()
    {
        return $this->_driver->cacheInfo();
    }

    // --------------------------------------------------------------------

    /**
     * Get Meta Data
     * 
     * @param string $id
     * @return object
     */
    public function getMetaData($id)
    {
        return $this->_driver->getMetaData($id);
    }

    // --------------------------------------------------------------------

    /**
     * Controlling for supporting driver.
     * 
     * @param string $id
     * @return object
     */
    private function _isSupported($driver)
    {
        return $this->_driver->isSupported($driver);
    }

}

/* End of file cache.php */
/* Location: ./packages/cache/releases/0.0.1/cache.php */