<?php

namespace Obullo\Cache;

use RunTimeException, ReflectionClass;

/**
 * Memcached Caching Class
 *
 * @category  Cache
 * @package   Memcached
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @author    Ali Ihsan Caglayan <ihsancaglayan@gmail.com>
 * @author    Ersin Guvenc <eguvenc@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/cache
 */
Class Memcached implements DriverInterface
{
    const SERIALIZER_PHP      = 'SERIALIZER_PHP';
    const SERIALIZER_JSON     = 'SERIALIZER_JSON';
    const SERIALIZER_IGBINARY = 'SERIALIZER_IGBINARY';
    const OPTION_SERIALIZER   = -1003;  // Memcached::OPT_COMPRESSION
    
    /**
     * Serializer types
     * 
     * @var array
     */
    public $serializerTypes = array(
        self::SERIALIZER_PHP      => 1, // Memcached::SERIALIZER_PHP
        self::SERIALIZER_IGBINARY => 2, // Memcached::SERIALIZER_IGBINARY
        self::SERIALIZER_JSON     => 3  // Memcached::SERIALIZER_JSON
    );

    /**
     * Memcache object
     * 
     * @var object
     */
    public $memcached;

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
        if ( ! extension_loaded('memcached')) {
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
     * Set client option.
     * 
     * @param string $params
     * 
     * You can use this options:
     *     'serializer_php'
     *     'serializer_igbinary'
     *     'serializer_json'
     * 
     * @return boolean true or false
     */
    public function setOption($params)
    {
        switch ($params) {
        case static::SERIALIZER_PHP: // The default PHP serializer.
            $this->memcached->setOption(static::OPTION_SERIALIZER, $this->serializerTypes[static::SERIALIZER_PHP]);
            return true;
            break;
        case static::SERIALIZER_JSON: // The JSON serializer.
            return $this->memcached->setOption(static::OPTION_SERIALIZER, $this->serializerTypes[static::SERIALIZER_JSON]);
            break;
        case static::SERIALIZER_IGBINARY: // The » igbinary serializer.
                                          // Instead of textual representation it stores PHP data structures in a compact binary form, resulting in space and time gains.
                                          // https://github.com/igbinary/igbinary
            return $this->memcached->setOption(static::OPTION_SERIALIZER, $this->serializerTypes[static::SERIALIZER_IGBINARY]);
            break;

        default:
            return false;
            break;
        }
    }

    /**
     * Get client option.
     * http://www.php.net/manual/en/memcached.constants.php
     * 
     * @param string $option option constant
     * 
     * @return string value
     */
    public function getOption($option = 'OPTION_SERIALIZER')
    {
        $obj      = new ReflectionClass('Memcached');
        $constant = $obj->getconstant($option);

        return $this->memcached->getOption($constant);
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
        $data = $this->memcached->get($key);
        if (isset($data[0])) {
            return $data[0];
        }
        return $data;
    }

    /**
     * Returns the keys that match a certain pattern.
     * 
     * @return array the keys that match a certain pattern.
     */
    public function getAllKeys()
    {
        return $this->memcached->getAllKeys();
    }

    /**
     * Get All Data
     * 
     * @return array return all the key and data
     */
    public function getAllData()
    {
        return $this->memcached->fetchAll();
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
        if ($this->memcached->get($key)) {
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
                $this->memcached->set($k, $v, 0, $ttl);
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
            return $this->memcached->set($key, array($data, time(), $ttl), $ttl);
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
        return $this->memcached->delete($key);
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
            $this->memcached->replace($key, array($data, time(), $ttl), $ttl);
        }
        return $this->setArray($key, $data);
    }

    /**
     * Flush all items in 1 seconds (default)
     * 
     * @param int $expiration expiration time
     * 
     * @return boolean
     */
    public function flushAll($expiration = 1)
    {
        return $this->memcached->flush($expiration);
    }

    /**
     * Get software information installed on your server.
     *
     * Array
     * (
     *     [localhost:11211] => Array
     *     (
     *         [pid] => 4933
     *         [uptime] => 786123
     *         [threads] => 1
     *         [time] => 1233868010
     *         [pointer_size] => 32
     *         [rusage_user_seconds] => 0
     *         [rusage_user_microseconds] => 140000
     *         [rusage_system_seconds] => 23
     *     )
     * )
     * 
     * @return object
     */
    public function info()
    {
        return $this->memcached->getStats();
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
        $stored = $this->memcached->get($key);
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
     * Connect to Memcached
     * 
     * @return boolean
     */
    public function connect()
    {
        $this->memcached = new \Memcached;

        if ( ! isset($this->params['servers']['weight'])) {
            $this->params['servers']['weight'] = 1;
        }
        foreach ($this->params['servers'] as $key => $value) {
            if (is_array($value)) {
                if ( ! $this->memcached->addServer($value['hostname'], $value['port'], $value['weight'])) {
                    return false;
                }
            } else {
                if ( ! $this->memcached->addServer($this->params['servers']['hostname'], $this->params['servers']['port'], $this->params['servers']['weight'])) {
                    return false;
                }
            }
        }
        return true;
    }
}

// END Memcached Class

/* End of file Memcached.php */
/* Location: .Obullo/Cache/Memcached.php */