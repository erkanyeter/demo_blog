<?php

namespace Obullo\Cache;

use RunTimeException;

/**
 * Apc Caching Class
 *
 * @category  Cache
 * @package   Apc
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @author    Ali Ihsan Caglayan <ihsancaglayan@gmail.com>
 * @author    Ersin Guvenc <eguvenc@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/cache
 */
Class Apc implements DriverInterface
{
    /**
     * Constructor
     * 
     * @param array $params params
     */
    public function __construct($params = array())
    {
        if ( ! extension_loaded('apc') OR ini_get('apc.enabled') != '1') {
            throw new RunTimeException(
                sprintf(
                    ' %s driver is not installed.', get_class()
                )
            );
        }
    }

    /**
     * Get cache data.
     * 
     * @param string $key cache key.
     * 
     * @return array
     */
    public function get($key)
    {
        $data = apc_fetch($key);
        return (is_array($data)) ? $data[0] : false;
    }

    /**
     * Verify if the specified key exists.
     * 
     * @param string $key cache key.
     * 
     * @return boolean true or false
     */
    public function keyExists($key)
    {
        return apc_exists($key);
    }

    /**
     * Set array
     * 
     * @param array $data cache data.
     * @param int   $ttl  expiration time.
     * 
     * @return boolean
     */
    public function setArray($data, $ttl)
    {
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $this->set($k, $v, $ttl);
            }
            return true;
        }
        return false;
    }

    /**
     * Save
     * 
     * @param mix $key  cache key.
     * @param mix $data cache data.
     * @param int $ttl  expiration time
     * 
     * @return array
     */
    public function set($key = '', $data = 60, $ttl = 60) 
    {
        if ( ! is_array($key)) {
            return apc_store($key, array($data, time(), $ttl), $ttl);
        }
        return $this->setArray($key, $data);
    }

    /**
     * Remove specified key.
     * 
     * @param string $key cache key.
     * 
     * @return boolean
     */
    public function delete($key)
    {
        return apc_delete($key);
    }

    /**
     * Replace data
     * 
     * @param mix $key  cache key.
     * @param mix $data cache data.
     * @param int $ttl  expiration time
     * 
     * @return boolean
     */
    public function replace($key = '', $data = 60, $ttl = 60) 
    {
        if ( ! is_array($key)) {
            $this->delete($key);
            return apc_store($key, array($data, time(), $ttl), $ttl);
        }
        return $this->setArray($key, $data);
    }

    /**
     * Clean all data
     * 
     * @param string $type clean type
     * 
     * @return object
     */
    public function flushAll($type = 'user')
    {
        return apc_clear_cache($type);
    }

    /**
     * Cache Info
     * 
     * * Array
     * (
     *    [num_slots] => 2000
     *    [ttl] => 0
     *    [num_hits] => 9
     *    [num_misses] => 3
     *    [start_time] => 1123958803
     *    [cache_list] => Array
     *         (
     *            [0] => Array
     *                (
     *                    [filename] => /path/to/apc_test.php
     *                    [device] => 29954
     *                    [inode] => 1130511
     *                    [type] => file
     *                    [num_hits] => 1
     *                    [mtime] => 1123960686
     *                    [creation_time] => 1123960696
     *                    [deletion_time] => 0
     *                    [access_time] => 1123962864
     *                    [ref_count] => 1
     *                    [mem_size] => 677
     *                )
     *            [1] => Array (...iterates for each cached file)
     * )
     * 
     * @param string $type info type
     * 
     * Types:
     *     "user"
     *     "filehits"
     * 
     * @return array
     */
    public function info($type = null)
    {
        return apc_cache_info($type);
    }

    /**
     * Get meta data
     * 
     * @param string $key cache key.
     * 
     * @return array
     */
    public function getMetaData($key)
    {
        $stored = apc_fetch($key);
        if (count($stored) !== 3) {
            return false;
        }
        list($data, $time, $ttl) = $stored;
        return array(
            'expire' => $time + $ttl,
            'mtime'  => $time,
            'data'   => $data
        );
    }

    /**
     * Connect
     * 
     * @return void
     */
    public function connect()
    {
        return;
    }
}

// END Apc Class

/* End of file Apc.php */
/* Location: .Obullo/Cache/Apc.php */