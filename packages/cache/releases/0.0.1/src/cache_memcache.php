<?php 
namespace Cache\Src;

/**
 * Memcached Caching Class 
 *
 * @package		Packages
 */

Class Cache_Memcache {

	public $connectionSet;

	private $_memcached;

	// ------------------------------------------------------------------------	

	/**
     * Get
     * 
     * @param string $key
     * @return object
     */
	public function get($key)
	{
		$data = $this->_memcached->get($key);
		return (is_array($data)) ? $data[0] : false;
	}

	// ------------------------------------------------------------------------	

	/**
     * Get
     * 
     * @param string $key
     * @return object
     */
	public function getAllKeys()
	{
		return $data = $this->_memcached->getAllKeys();
	}

	// ------------------------------------------------------------------------

    /**
     * Save
     * 
     * @param string $key
     * @return object
     */
	public function set($key, $data, $ttl = 60)
	{
		if (get_class($this->_memcached) == 'Memcached')
		{
			return $this->_memcached->set($key, array($data, time(), $ttl), $ttl);
		}
		elseif (get_class($this->_memcached) == 'Memcache')
		{
			return $this->_memcached->set($key, array($data, time(), $ttl), 0, $ttl);
		}
		
		return false;
	}

	// ------------------------------------------------------------------------

    /**
     * Delete
     * 
     * @param string $key
     * @return object
     */
	public function delete($key)
	{
		return $this->_memcached->delete($key);
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
	public function replace($key, $data, $ttl = 60)
	{
		if (get_class($this->_memcached) == 'Memcached')
		{
			return $this->_memcached->replace($key, array($data, time(), $ttl), $ttl);
		}
		elseif (get_class($this->_memcached) == 'Memcache')
		{
			return $this->_memcached->replace($key, array($data, time(), $ttl), 0, $ttl);
		}
		
		return false;
	}

	// ------------------------------------------------------------------------	

    /**
     * Clean all data
     * 
     * @param string $key
     * @return object
     */
    public function flushAll()
	{
		return $this->_memcached->flush();
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
		return $this->_memcached->getStats();
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
		$stored = $this->_memcached->get($key);

		if (count($stored) !== 3)
		{
			return false;
		}

		list($data, $time, $ttl) = $stored;

		return array(
					 'expire' => $time + $ttl,
					 'mtime'  => $time,
					 'data'   => $data
					);
	}

	// ------------------------------------------------------------------------

	public function connectMemcacheFamily($driver = null)
	{
		if($driver != null AND (mb_strtolower($driver) === 'memcache' OR mb_strtolower($driver) === 'memcached'))
		{
			$className        = ucfirst(strtolower($driver));
			$this->_memcached = new $className();

			foreach ($this->connectionSet['servers'] as $key => $value)
			{
				if(is_array($value))
				{
					$this->_memcached->addServer($value['hostname'], $value['port'], $value['weight']);
				}
				else
				{
					$this->_memcached->addServer($this->connectionSet['servers']['hostname'], $this->connectionSet['servers']['port'], $this->connectionSet['servers']['weight']);

					return true;
				}
			}

			return true;
		}
		else
		{
			return false;
		}
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

}

// END Cache_Memcache Class
/* End of file cache_memcache.php */
/* Location: ./packages/cache/releases/0.0.1/src/cache_memcache.php */