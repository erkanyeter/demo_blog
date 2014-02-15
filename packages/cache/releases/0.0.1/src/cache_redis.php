<?php 
namespace Cache\Src;

/**
 * Redis Caching Class
 *
 * @package
 */

Class Cache_Redis {

	public $connectionSet;

	private $_redis;

	// ------------------------------------------------------------------------	

	/**
     * Method to determine if a phpredis object thinks it's connected to a server
     * 
     * @return boolean true or false
     */
	public function IsConnected()
	{
		return $this->_redis->IsConnected();
	}

	// ------------------------------------------------------------------------	

	/**
	 * Get last error
	 * 
	 * @return string with the last returned script based error message, or NULL if there is no error
	 */
	public function getLastError()
	{
		return $this->_redis->getLastError();
	}

	// ------------------------------------------------------------------------	

	/**
	 * Get last save
	 * 
	 * @return timestamp the timestamp of the last disk save.
	 */
	public function getLastSave()
	{
		return $this->_redis->lastSave();
	}

	// ------------------------------------------------------------------------	

	/**
	 * Returns the type of data pointed by a given type key.
	 * 
	 * @param  string $typeKey string | set | 
	 * @return Depending on the type of the data pointed by the type key
	 */
	public function setType($typeKey)
	{
		return $this->_redis->type($typeKey);
	}

	// ------------------------------------------------------------------------	

	/**
     * Sets an expiration date (a timeout) on an item. pexpire requires a TTL in milliseconds.
     *
     * @param string $key
     * @param int $ttl
     * @return boolean true or false
     */
	public function setTimeout($key, $ttl)
	{
		return $this->_redis->setTimeout($key, $ttl);
	}

	// ------------------------------------------------------------------------	

	/**
     * Get
     * 
     * @param string $key
     * @return object
     */
	public function get($key)
	{
		return $this->_redis->get($key);
	}

	// ------------------------------------------------------------------------	

	/**
     * Remove all keys from all databases. 
     * 
     * @return boolean always true
     */
	public function flushAll()
	{
		return $this->_redis->flushAll();
	}


	// ------------------------------------------------------------------------	

	/**
     * Remove all keys from the current database.
     * 
     * @return boolean always true
     */
	public function flushDB()
	{
		return $this->_redis->flushDB();
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
		return $this->_redis->append($key, $data);
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
		return $this->_redis->exists($key);
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

		return $this->_redis->mGet($key);
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
		return $this->_redis->getSet($key, $data);
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
		return $this->_redis->rename($key, $newKey);
	}

	// ------------------------------------------------------------------------	

	/**
     * Returns the keys that match a certain pattern.
     * 
     * @param  string $pattern
     * @return array the keys that match a certain pattern.
     */
	public function getAllKeys($pattern = '*')
	{
		return $this->_redis->keys($pattern);
	}

	// ------------------------------------------------------------------------	

	/**
     * Get All Data
     * 
     * @return array return all the key and data
     */
	public function getAllData()
	{
		$keys = $this->_redis->keys('*');

		foreach($keys as $k => $v)
		{
			$getData = $this->_redis->get($v);

			if(empty($getData))
			{
				$getData = $this->sGetMembers($v);
			}

			$data[][$v] = $getData;
		}

		return $data;
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
			return $this->_redis->sort($key, $sort);
		}

		return $this->_redis->sort($sort);
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
		if(is_array($data))
		{
			$data = "'" . implode("','", $data) . "'";
		}

		return $this->_redis->sAdd($key, $data);
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
		return $this->_redis->sCard($key);
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
		if(count($key) > 0 AND is_array($key))
		{
			$keys = "'" . implode("','", $key) . "'";

			return $this->_redis->sInter($keys);
		}

		return false;
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
		return $this->_redis->sMembers($key);
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
		return $this->_redis->auth($password);
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
		switch ($option)
		{
			case 'SERIALIZER_NONE': // don't serialize data
				return $this->_redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_NONE);
				break;
			case 'SERIALIZER_PHP': // use built-in serialize/unserialize
				$this->_redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
				return true;
				break;
			case 'SERIALIZER_IGBINARY': // use igBinary serialize/unserialize
				return $this->_redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_IGBINARY);
				break;

			default:
				return false;
				break;
		}
	}

	// ------------------------------------------------------------------------	

	/**
     * Get client option.
     * 
     * @return string value
     */
	public function getOption()
	{
		return $this->_redis->getOption(\Redis::OPT_SERIALIZER);
	}

	// ------------------------------------------------------------------------

    /**
     * Set
     * 
     * @param string $key
     * @param string or array $data
     * @param int $ttl
     */
	public function set($key, $data, $ttl = null) // If empty $ttl default timeout unlimited
	{
		return $this->_redis->set($key, $data, $ttl);
	}

	// ------------------------------------------------------------------------

    /**
     * Delete remove specified keys.
     * 
     * @param string $key
     * @return object
     */
	public function delete($key)
	{
		return $this->_redis->delete($key);
	}

	// ------------------------------------------------------------------------


	/**
	 * Replace key value
	 * 
	 * @param  string $key
	 * @param  string or array $data
	 * @param  int $ttl sec
	 * @return replace new value
	 */
	public function replace($key, $data, $ttl = null)
	{
		return $this->_redis->set($key, $data, $ttl);
	}

	// ------------------------------------------------------------------------

    /**
     * Cache Info
     * 
     * @param
     * @return object
     */
	public function cacheInfo()
	{
		return $this->_redis->info();
	}

	// ------------------------------------------------------------------------

    /**
     * Get Meta Data
     * 
     * @param string $key
     * @return object
     */
	public function getMetaData($key)
	{
		return false;
	}

	// ------------------------------------------------------------------------

	public function connectRedis($driver = null)
	{
		if($driver != null AND (mb_strtolower($driver) === 'redis'))
		{
			$className    = ucfirst(strtolower($driver));
			$this->_redis = new $className();

			if(isset($this->connectionSet['servers']['timeout']))
			{
				$this->_redis->connect($this->connectionSet['servers']['hostname'], $this->connectionSet['servers']['port'], $this->connectionSet['servers']['timeout']);
			}
			else
			{
				$this->_redis->connect($this->connectionSet['servers']['hostname'], $this->connectionSet['servers']['port']);
			}
			
			return true;
		}

		return false;
	}

	// ------------------------------------------------------------------------

    /**
     * Controlling for supporting driver.
     * 
     * @param string $key
     * @return object
     */
	public function isSupported($driver)
	{
		if( ! extension_loaded($driver))
		{
			throw new \Exception($driver.' is not installed.');

			return false;
		}

		return true;
	}

	// ------------------------------------------------------------------------

}

// END Cache_Redis Class
/* End of file cache_redis.php */
/* Location: ./packages/cache/releases/0.0.1/src/cache_redis.php */