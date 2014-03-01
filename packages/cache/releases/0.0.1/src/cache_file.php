<?php
namespace Cache\Src;

/**
 * File Caching Class
 *
 * @package
 * @author
 */

Class Cache_File {

	private $cache_path;

	public function __construct()
	{
		$this->config     = getConfig('cache');  // app/config/cache.php config
		$this->cache_path = ROOT .str_replace('/', DS, trim($this->config['cache_path'], '/')). DS;
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
		if ( ! file_exists($this->cache_path.$key))
		{
			return false;
		}
		
		$data = file_get_contents($this->cache_path.$key);
		$data = unserialize($data);
		
		if (time() >  $data['time'] + $data['ttl'])
		{
			unlink($this->cache_path.$key);
			return false;
		}
		
		return $data['data'];
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
		if($this->get($key) == false)
		{
			return false;
		}

		return true;
	}

	// ------------------------------------------------------------------------

	/**
	 * Replace
	 * @param  [type]  $key   [description]
	 * @param  [type]  $data [description]
	 * @param  integer $ttl  [description]
	 * @return [type]        [description]
	 */
	public function replace($key, $data, $ttl = 60)
	{
		$this->delete($key);

		return $this->set($key, $data, $ttl);
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
		$contents = array(
						  'time' => time(),
						  'ttl'  => $ttl,
						  'data' => $data
						  );

		$fileName = $this->cache_path.$key;
        if ( ! $fp = fopen($fileName, 'wb'))
        {
            return false;
        }
        
		$serializeData = serialize($contents);
        flock($fp, LOCK_EX);
        fwrite($fp, $serializeData);
        flock($fp, LOCK_UN);
        fclose($fp);

		return true;
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
		return unlink($this->cache_path.$key);
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
		return delete_files($this->cache_path);
	}

	// ------------------------------------------------------------------------

    /**
     * Cache Info
     * 
     * @param
     * @return object
     */
	public function cacheInfo($type = NULL)
	{
		return get_dir_file_info($this->cache_path);
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
		if ( ! file_exists($this->cache_path.$key))
		{
			return false;
		}

		$data = file_get_contents($this->cache_path.$key);
		$data = unserialize($data);

		if (is_array($data))
		{
			$mtime = filemtime($this->cache_path.$key);

			if ( ! isset($data['ttl']))
			{
				return false;
			}

			return array(
				'expire'	=> $mtime + $data['ttl'],
				'mtime'		=> $mtime
			);
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
	public function isSupported()
	{
		if(!is_writable($this->cache_path))
		{
			throw new \Exception('Cache path '.$this->cache_path.' is not writable.');
		}

		return false;
	}
}

/* End of file cache_file.php */
/* Location: ./packages/cache/releases/0.0.1/src/cache_file.php */