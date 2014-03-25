<?php

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
    public $config = array();
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

    // --------------------------------------------------------------------

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
            $this->logger->notice('Config key "' . $key . '" not found, be sure providing the right name');
            return false;
        }

        // UNDERSTANd THE "." PARAMETERS USE INDEX

        // if (strpos('.', $key) !== false) {
        //     $exp   = explode('.', $key);
        //     $index = $exp[0];
        // }
        // return $this->config[$index][$key];

        return $this->config[$key];
    }

    // --------------------------------------------------------------------

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

    // --------------------------------------------------------------------

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

    // --------------------------------------------------------------------

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
        global $config, $c;

        $this->config = $config;
        $this->logger = $c['Logger'];
        $this->logger->debug('Config Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * Load Config File
     *
     * @param string $filename the config file name
     * 
     * @return array if the file was loaded correctly
     */
    public function load($filename = '')
    {
        global $config;
        $_config = $config; // copy config array

        $folder = APP . 'config' . DS;
        if (in_array($filename, $config['environment_config_files'])) {
            $folder = APP . 'config' . DS . ENV. DS;
        }
        unset($config);

        $file = $folder . $filename . EXT;
        
        if (in_array($filename, $this->is_loaded, true)) {
            return true;
        }

        include $file;

        if ( ! isset($config) OR ! is_array($config)) {
            throw new Exception('Your ' . $file . ' file does not appear to contain a valid configuration array. Please create $config variables in your ' . $file);
        }
        if (isset($this->config[$filename])) {
            $this->config[$filename] = array_merge($this->config[$filename], $_config);
        } else {
            $this->config[$filename] = $config;
        }

        $this->is_loaded[] = $filename;

        unset($config);
        $this->logger->debug('Config file loaded: ' . $file);

        return $this->config[$filename];
    }

    // --------------------------------------------------------------------

    /**
     * Fetch a config file item - adds slash after item
     *
     * The second parameter allows a slash to be added to the end of
     * the item, in the case of a path.
     *
     * @param string $item the config item name
     * 
     * @return string
     */
    public function getSlashItem($item)
    {
        if ( ! isset($this->config[$item])) {
            return false;
        }
        $pref = $this->config[$item];
        if ($pref != '' AND substr($pref, -1) != '/') {
            $pref .= '/';
        }
        return $pref;
    }

}

// END Config.php File
/* End of file Config.php

/* Location: .Obullo/Config/Config.php */