<?php
namespace Cache\Src;

/**
 * File Caching Class
 *
 * @package
 * @author
 */

Class Cache_File {

	private $_cache_path;

	public function __construct()
	{
		$this->config      = getConfig('cache');  // app/config/cache.php config
		$this->_cache_path = ROOT .str_replace('/', DS, trim($this->config['cache_path'], '/')). DS;
	}

	// ------------------------------------------------------------------------

	/**
     * Get
     * 
     * @param string $id
     * @return object
     */
	public function get($id)
	{
		if ( ! file_exists($this->_cache_path.$id))
		{
			return false;
		}
		
		$data = file_get_contents($this->_cache_path.$id);
		$data = unserialize($data);
		
		if (time() >  $data['time'] + $data['ttl'])
		{
			unlink($this->_cache_path.$id);
			return false;
		}
		
		return $data['data'];
	}

	// ------------------------------------------------------------------------

	/**
	 * Replace
	 * @param  [type]  $id   [description]
	 * @param  [type]  $data [description]
	 * @param  integer $ttl  [description]
	 * @return [type]        [description]
	 */
	public function replace($id, $data, $ttl = 60)
	{
		$this->delete($id);

		return $this->set($id, $data, $ttl);
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
		$contents = array(
						  'time' => time(),
						  'ttl'  => $ttl,
						  'data' => $data
						  );

		$fileName = $this->_cache_path.$id;
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
     * @param string $id
     * @return object
     */
	public function delete($id)
	{
		return unlink($this->_cache_path.$id);
	}

	// ------------------------------------------------------------------------
	
    /**
     * Clean all data
     * 
     * @param string $id
     * @return object
     */
	public function clean()
	{
		return delete_files($this->_cache_path);
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
		return get_dir_file_info($this->_cache_path);
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
		if ( ! file_exists($this->_cache_path.$id))
		{
			return false;
		}

		$data = file_get_contents($this->_cache_path.$id);
		$data = unserialize($data);

		if (is_array($data))
		{
			$mtime = filemtime($this->_cache_path.$id);

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
     * @param string $id
     * @return object
     */
	public function isSupported()
	{
		if(!is_writable($this->_cache_path))
		{
			throw new \Exception('Cache path '.$this->_cache_path.' is not writable.');
		}

		return false;
	}
}

/* End of file cache_file.php */
/* Location: ./packages/cache/releases/0.0.1/src/cache_file.php */