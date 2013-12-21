<?php 
namespace Cache\Src;

/**
 * Memcached Caching Class 
 *
 * @package		Packages
 */

Class Cache_Memcache {

	private $_memcached;
	public $connectionSet;

	// ------------------------------------------------------------------------	

	/**
     * Get
     * 
     * @param string $id
     * @return object
     */
	public function get($id)
	{
		$data = $this->_memcached->get($id);
		return (is_array($data)) ? $data[0] : false;
	}

	/**
     * Get
     * 
     * @param string $id
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
     * @param string $id
     * @return object
     */
	public function set($id, $data, $ttl = 60)
	{
		if (get_class($this->_memcached) == 'Memcached')
		{
			return $this->_memcached->set($id, array($data, time(), $ttl), $ttl);
		}
		elseif (get_class($this->_memcached) == 'Memcache')
		{
			return $this->_memcached->set($id, array($data, time(), $ttl), 0, $ttl);
		}
		
		return false;
	}

	// ------------------------------------------------------------------------

    /**
     * Delete
     * 
     * @param string $id
     * @return object
     */
	public function delete($id)
	{
		return $this->_memcached->delete($id);
	}

	// ------------------------------------------------------------------------

	public function replace($id, $data, $ttl = 60)
	{
		if (get_class($this->_memcached) == 'Memcached')
		{
			return $this->_memcached->replace($id, array($data, time(), $ttl), $ttl);
		}
		elseif (get_class($this->_memcached) == 'Memcache')
		{
			return $this->_memcached->replace($id, array($data, time(), $ttl), 0, $ttl);
		}
		
		return false;
	}

    /**
     * Clean all data
     * 
     * @param string $id
     * @return object
     */
    public function clean()
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
     * @param string $id
     * @return object
     */
	public function getMetaData($id)
	{
		$stored = $this->_memcached->get($id);

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
     * @param string $id
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

// END Cache_Memcache Class
/* End of file cache_memcache.php */
/* Location: ./packages/cache/releases/0.0.1/src/cache_memcache.php */