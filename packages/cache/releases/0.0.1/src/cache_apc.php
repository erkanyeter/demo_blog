<?php
namespace Cache\Src;

/**
 * APC Caching Class
 *
 * @package
 * @author
 */

Class Cache_Apc {

	/**
     * Get
     * 
     * @param string $id
     * @return object
     */
	public function get($id)
	{
		$data = apc_fetch($id);

		return (is_array($data)) ? $data[0] : false;
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
		return apc_store($id, array($data, time(), $ttl), $ttl);
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
		return apc_delete($id);
	}

	// ------------------------------------------------------------------------

    /**
     * Replace
     * 
     * @param string $id
     * @return object
     */
	public function replace($id, $data, $ttl=60)
	{
		$this->delete($id);
		
		return apc_store($id, array($data, time(), $ttl), $ttl);
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
		return apc_clear_cache('user');
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
		 return apc_cache_info($type);
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
		$stored = apc_fetch($id);

		if (count($stored) !== 3)
		{
			return false;
		}

		list($data, $time, $ttl) = $stored;

		return array(
			'expire'	=> $time + $ttl,
			'mtime'		=> $time,
			'data'		=> $data
		);
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
		if ( ! extension_loaded('apc') OR ini_get('apc.enabled') != "1")
		{
			throw new \Exception('The APC PHP extension must be loaded to use APC Cache.');

			return false;
		}
		
		return true;
	}
}


/* End of file cache_apc.php */
/* Location: ./packages/cache/releases/0.0.1/src/cache_apc.php */