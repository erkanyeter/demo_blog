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
            getInstance()->cache = $this; // Make available it in the controller $this->cache->method();
        }

        $this->_setDriver($this->_config['driver']);
        $this->_isSupported($this->_config['driver']); // Checking for supporting driver.
        $this->_setConnection($this->_config);

        logMe('debug', 'Cache Class Initialized');
    }

    // --------------------------------------------------------------------
    
    /**
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
        elseif($this->_adapter == 'redis')
        {
            if(isset($params['servers']['weight']))
            {
                unset($params['servers']['weight']);
            }

            $paramsKey = array('hostname' => 1, 'port' => 2);
            $servers   = array_intersect_key($params['servers'], $paramsKey);

            if( ! isset($servers['hostname']))
            {
                throw new Exception('A defined hostname could not be found.');
                exit;
            }
            else
            {
                $this->_driver->connectionSet = $params;
                $this->_driver->connectRedis($this->_config['driver']);
            }

            return true;
        }

        return false;
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
        elseif(mb_strtolower($driver) == 'memcache' OR mb_strtolower($driver) == 'memcached' OR mb_strtolower($driver) == 'redis')
        {
            $this->_adapter = $driver;
        }

        $editDriverName  = str_replace('memcached','memcache',$driver); // Driver name is edited. Only for memcached.
        $className       = 'Cache\Src\Cache_' . ucfirst(mb_strtolower($editDriverName));
        $this->_driver   = new $className(); // Drivers needed for the class are created.
      
    	return true;
    }

    // --------------------------------------------------------------------
    
    /**
     * Get key
     * 
     * @param string $key
     * @return object
     */
    public function get($key)
    {
        return $this->_driver->get($key);
    }

    // --------------------------------------------------------------------
    
    /**
     * Get All Keys just for memcached and redis driver
     * 
     * @param string $pattern
     * @return object
     */
    public function getAllKeys($pattern = '')
    {
        if($this->_adapter == 'memcached')
        {
            return $this->_driver->getAllKeys();
        }
        elseif($this->_adapter == 'redis')
        {
            return $this->_driver->getAllKeys($pattern);
        }

        return false;
    }

    // --------------------------------------------------------------------
    
    /**
     * Save
     * 
     * @param string $key
     * @return object
     */
    public function set($key, $data, $ttl = null)
    {
        return $this->_driver->set($key, $data, $ttl);
    }

    // --------------------------------------------------------------------

    /**
     * Replace
     * @param  [type]  $key
     * @param  [type]  $data
     * @param  integer $ttl
     * @return [type]
     */
    public function replace($key, $data, $ttl = 60)
    {
        return $this->_driver->set($key, $data, $ttl);
    }

    // --------------------------------------------------------------------

    /**
     * Delete
     * 
     * @param string $key
     * @return object
     */
    public function delete($key)
    {
        return $this->_driver->delete($key);
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
     * @param string $key
     * @return object
     */
    public function getMetaData($key)
    {
        return $this->_driver->getMetaData($key);
    }


    // -----------
    // Redis Start
    // -----------


    // ------------------------------------------------------------------------ 

    /**
     * Method to determine if a phpredis object thinks it's connected to a server
     * 
     * @return boolean true or false
     */
    public function IsConnected()
    {
        return $this->_driver->IsConnected();
    }

    // ------------------------------------------------------------------------ 

    /**
     * Get last error
     * 
     * @return string with the last returned script based error message, or NULL if there is no error
     */
    public function getLastError()
    {
        return $this->_driver->getLastError();
    }


    // ------------------------------------------------------------------------ 

    /**
     * Get last save
     * 
     * @return timestamp the timestamp of the last disk save.
     */
    public function getLastSave()
    {
        return $this->_driver->getLastSave();
    }

    // ------------------------------------------------------------------------ 

    /**
     * Sets an expiration date (a timeout) on an item. pexpire requires a TTL in milliseconds.
     * 
     * @return boolean true or false
     */
    public function setTimeout()
    {
        return $this->_driver->setTimeout();
    }

    // ------------------------------------------------------------------------ 

    /**
     * Get All Data
     * 
     * @return array return all the key and data
     */
    public function getAllData()
    {
        return $this->_driver->getAllData();
    }

    // ------------------------------------------------------------------------ 

    /**
     * Remove all keys from all databases.
     * 
     * @return boolean always true
     */
    public function flushAll()
    {
        if($this->_adapter == 'redis')
        {
            return $this->_driver->flushAll();
        }

        return false;
    }

    // ------------------------------------------------------------------------ 

    /**
     * Remove all keys from the current database.
     * 
     * @return boolean always true
     */    
    public function flushDB()
    {
        if($this->_adapter == 'redis')
        {
            return $this->_driver->flushDB();
        }

        return false;
    }

    // ------------------------------------------------------------------------ 

    /**
     * Append specified string to the string stored in specified key.
     * 
     * @param string $key
     * @param string $data
     * @return object
     */
    public function append($key, $data)
    {
        return $this->_driver->append($key, $data);
    }

    // ------------------------------------------------------------------------ 

    /**
     * Verify if the specified key exists.
     * 
     * @param string $key
     * @return boolean true or false
     */
    public function keyExists($key)
    {
        return $this->_driver->keyExists($key);
    }

    // ------------------------------------------------------------------------ 

    /**
     * Get the values of all the specified keys. If one or more keys dont exist, the array will contain
     * 
     * @param  array $key
     * @return array containing the list of the keys
     */
    public function getMultiple($key)
    {
        if( ! is_array($key))
        {
            return false;
        }

        return $this->_driver->getMultiple($key);
    }

    // ------------------------------------------------------------------------ 

    /**
     * Sets a value and returns the previous entry at that key.
     * 
     * @param  string $key
     * @param  string $data
     * @return string the previous value located at this key.
     */
    public function getSet($key, $data)
    {
        return $this->_driver->getSet($key, $data);
    }

    // ------------------------------------------------------------------------ 

    /**
     * Renames a key.
     * 
     * @param  string $key
     * @param  string $newKey
     * @return boolean true or false
     */
    public function renameKey($key, $newKey)
    {
        return $this->_driver->renameKey($key, $newKey);
    }

    // ------------------------------------------------------------------------ 

    /**
     * Sort the elements in a list, set or sorted set.
     * 
     * @param  string $key
     * @param  array $sort optional
     * @return array the keys that match a certain pattern.
     */
    public function sort($key, $sort = array())
    {
        if(count($sort) > 0)
        {
            return $this->_driver->sort($key, $sort);
        }

        return $this->_driver->sort($sort);
    }

    // ------------------------------------------------------------------------ 

    /**
     * Adds a value to the set value stored at key. If this value is already in the set, FALSE is returned.
     * 
     * @param  string $key
     * @param  string $data
     * @return long the number of elements added to the set.
     */
    public function sAdd($key, $data)
    {
        return $this->_driver->sAdd($key, $data);
    }

    // ------------------------------------------------------------------------ 

    /**
     * Returns the cardinality of the set identified by key.
     * 
     * @param  string $key
     * @return long the cardinality of the set identified by key, 0 if the set doesn't exist.
     */
    public function sSize($key)
    {
        return $this->_driver->sSize($key);
    }

    // ------------------------------------------------------------------------ 

    /**
     * Returns the members of a set resulting from the intersection of all the sets held at the specified keys.
     * 
     * @param  array $key
     * @return array contain the result of the intersection between those keys. If the intersection beteen the different sets is empty, the return value will be empty array.
     */
    public function sInter($key = array())
    {
        return $this->_driver->sInter($key);
    }

    // ------------------------------------------------------------------------ 

    /**
     * Returns the contents of a set.
     * 
     * @param  string $key
     * @return array of elements, the contents of the set.
     */
    public function sGetMembers($key)
    {
        return $this->_driver->sGetMembers($key);
    }

    // ------------------------------------------------------------------------ 

    /**
     * Authenticate the connection using a password. Warning: The password is sent in plain-text over the network.
     * 
     * @param  string $password
     * @return boolean true or false
     */
    public function auth($password)
    {
        return $this->_driver->auth($password);
    }

    // ------------------------------------------------------------------------ 

    /**
     * Set client option.
     * 
     * @param  string $option 'SERIALIZER_NONE' | 'SERIALIZER_PHP' | 'SERIALIZER_IGBINARY'
     * @return boolean true or false
     */
    public function setOption($option)
    {
       return $this->_driver->setOption($option);
    }

    // ------------------------------------------------------------------------ 

    /**
     * Get client option.
     * 
     * @return string value
     */
    public function getOption()
    {
        return $this->_driver->getOption();
    }

    // --------------------------------------------------------------------

    /**
     * Controlling for supporting driver.
     * 
     * @param string $key
     * @return object
     */
    private function _isSupported($driver)
    {
        return $this->_driver->isSupported($driver);
    }

}

/* End of file cache.php */
/* Location: ./packages/cache/releases/0.0.1/cache.php */