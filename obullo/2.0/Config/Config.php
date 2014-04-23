<?php

namespace Obullo\Config;

use ArrayAccess, RunTimeException;

/**
 * Config Class
 * 
 * @category  Config
 * @package   Config
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/config
 */
Class Config implements ArrayAccess
{
    /**
     * Configuration container
     * 
     * @var array
     */
    public $config    = array();

    /**
     * A cache of whether file is loaded.
     * 
     * @var array
     */
    public $is_loaded = array();

    /**
     * Sets a parameter or an object.
     *
     * @param string $key   The unique identifier for the parameter
     * @param mixed  $value The value of the parameter
     *
     * @return void
     */
    public function offsetSet($key, $value)
    {        
        $this->config[$key] = $value;
    }

    /**
     * Gets a parameter or an object.
     *
     * @param string $key The unique identifier for the parameter
     *
     * @return mixed The value of the parameter or an object
     */
    public function offsetGet($key)
    {
        if ( ! isset($this->config[$key])) {
            return false;
        }
        return $this->config[$key];
    }

    /**
     * Checks if a parameter or an object is set.
     *
     * @param string $key The unique identifier for the parameter
     *
     * @return Boolean
     */
    public function offsetExists($key)
    {
        return isset($this->config[$key]);
    }

    /**
     * Unsets a parameter or an object.
     *
     * @param string $key The unique identifier for the parameter
     *
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->config[$key]);
    }

    /**
     * Constructor
     *
     * Sets the $config data from the primary config.php file as a class variable
     *
     * @access  public
     * @return  void
     */
    public function __construct()
    {
        include APP .'config'. DS . 'env'. DS . ENV . DS .'config'. EXT;  //  load current environment config file
        $this->config = $config;
    }

    /**
     * Load Config File
     *
     * @param string  $filename    the config file name
     * @param boolean $environment whether to load file from env path
     * 
     * @return array if the file was loaded correctly
     */
    public function load($filename = '', $environment = false)
    {
        $file = APP . 'config' . DS . str_replace('/', DS, $filename) . EXT;
        if ($environment) {
            $file = APP .'config'. DS . 'env'. DS . ENV . DS . str_replace('/', DS, $filename) . EXT;
        }
        if (in_array($filename, $this->is_loaded, true)) {
            return $this->config[$filename];
        }
        include $file;
        if ( ! isset($config) OR ! is_array($config)) {
            throw new RunTimeException(
                sprintf(
                    'Your %s file does not appear to contain a valid configuration array. Please create $config variables in it.', 
                    $file
                )
            );
        }
        $this->config[$filename] = $config;
        $this->is_loaded[]       = $filename;
        unset($config);

        return $this->config[$filename];
    }

}

$c['config'] = function () {  // store to container
    return new Config;
};

// END Config.php File
/* End of file Config.php

/* Location: .Obullo/Config/Config.php */