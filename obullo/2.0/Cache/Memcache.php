<?php

namespace Obullo\Cache;

use RunTimeException;

/**
 * Memcache Caching Class
 *
 * @category  Cache
 * @package   Memcache
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @author    Ali Ihsan Caglayan <ihsancaglayan@gmail.com>
 * @author    Ersin Guvenc <eguvenc@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/cache
 */
Class Memcache implements DriverInterface
{
    /**
     * Memcache object
     * 
     * @var object
     */
    public $memcache;

    /**
     * Connection settings
     * 
     * @var array
     */
    public $params = array();

    /**
     * Constructor
     * 
     * @param array $params connection settings
     */
    public function __construct($params = array())
    {
        if ( ! extension_loaded('memcache')) {
            throw new RunTimeException(
                sprintf(
                    ' %s driver is not installed.', get_class()
                )
            );
        }
        $this->params = $params;

        if ( ! isset($this->params['servers']['hostname']) AND ! isset($this->params['servers']['port'])) {
            throw new RunTimeException(
                sprintf(
                    ' %s connection configuration items hostname or port can\'t be empty.', get_class()
                )
            );
        }
        if ( ! $this->connect()) {
            throw new RunTimeException(
                sprintf(
                    ' %s cache connection failed.', get_class()
                )
            );
        }
    }

    /**
     * Get cache data.
     * 
     * @param string $key cache key.
     * 
     * @return object
     */
    public function get($key)
    {
        $data = $this->memcache->get($key);
        if (isset($data[0])) {
            return $data[0];
        }
        return $data;
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
        if ($this->memcache->get($key)) {
            return true;
        }
        return false;
    }

    /**
     * Set Array
     * 
     * @param array $data cache data
     * @param int   $ttl  expiration time
     * 
     * @return void
     */
    public function setArray($data, $ttl = 60)
    {
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $this->memcache->set($k, $v, 0, $ttl);
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
     * @return boolean
     */
    public function set($key = '', $data = 60, $ttl = 60)
    {
        if ( ! is_array($key)) {
            return $this->memcache->set($key, array($data, time(), $ttl), 0, $ttl);
        }
        return $this->setArray($key, $data);
    }

    /**
     * Remove specified keys.
     * 
     * @param string $key cache key.
     * 
     * @return boolean
     */
    public function delete($key)
    {
        return $this->memcache->delete($key);
    }

    /**
     * Replace key value
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
            $this->memcache->replace($key, array($data, time(), $ttl), 0, $ttl);
        }
        return $this->setArray($key, $data);
    }

    /**
     * Remove all keys and data from the cache.
     * 
     * @return boolean
     */
    public function flushAll()
    {
        return $this->memcache->flush();
    }

    /**
     * Get software information installed on your server.
     * 
     * @return object
     */
    public function info()
    {
        return $this->memcache->getStats();
    }

    /**
     * Get Meta Data
     * 
     * @param string $key cache key.
     * 
     * @return object
     */
    public function getMetaData($key)
    {
        $stored = $this->memcache->get($key);
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
     * Connect to Memcache
     * 
     * @return boolean
     */
    public function connect()
    {
        $this->memcache = new \Memcache;

        if ( ! isset($this->params['servers']['weight'])) {
            $this->params['servers']['weight'] = 1;
        }
        foreach ($this->params['servers'] as $key => $value) {
            if (is_array($value)) {
                if ( ! $this->memcache->addServer($value['hostname'], $value['port'], $value['weight'])) {
                    return false;
                }
            } else {
                if ( ! $this->memcache->connect($this->params['servers']['hostname'], $this->params['servers']['port'], $this->params['servers']['weight'])) {
                    return false;
                }
            }
        }
        return true;
    }
}

// END Memcache Class

/* End of file Memcache.php */
/* Location: .Obullo/Cache/Memcache.php */