<?php

/**
 * Config Class
 * 
 * This class contains functions that enable config files to be managed
 *
 * @package     packages
 * @subpackage  config
 * @category    configuration
 * @link        
 */
Class Config
{
    public $config = array();
    public $is_loaded = array();

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
     * @param string  $filename     the config file name
     * @param boolean $use_sections use folder
     * 
     * @return array if the file was loaded correctly
     */
    public function load($filename = '', $use_sections = false)
    {
        global $config;

        $file = APP . 'config' . DS . $filename . EXT;

        if (in_array($file, $this->is_loaded, true)) {
            return true;
        }

        include $file;

        if (!isset($config) OR !is_array($config)) {
            throw new Exception('Your ' . $file . ' file does not appear to contain a valid configuration array. Please create $config variables in your ' . $file);
        }
        if ($use_sections === true) {
            if (isset($this->config[$file])) {
                $this->config[$file] = array_merge($this->config[$file], $config);
            } else {
                $this->config[$file] = $config;
            }
        } else {
            $this->config = array_merge($this->config, $config);
        }
        $this->is_loaded[] = $file;

        unset($config);
        $this->logger->debug('Config file loaded: ' . $file);
        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Fetch a config file item
     *
     * @param string $item  the config item name
     * @param string $index the index name
     * 
     * @return   string
     */
    public function get($item, $index = '')
    {
        if ($index == '') {
            if (!isset($this->config[$item])) {
                $this->logger->info('Config item "' . $item . '" not found, be sure providing right name');
                return false;
            }
            $pref = $this->config[$item];
        } else {
            if (!isset($this->config[$index])) {
                $this->logger->info('Config index "' . $item . '" not found, be sure providing right name');
                return false;
            }
            if (!isset($this->config[$index][$item])) {
                $this->logger->info('Config item "' . $item . '" not found, be sure providing right name');
                return false;
            }
            $pref = $this->config[$index][$item];
        }
        return $pref;
    }

    // --------------------------------------------------------------------

    /**
     * Set a config file item
     * alias of config_item we will deprecicate it later.
     *
     * @param string $item  the config item key
     * @param string $value the config item value
     * 
     * @return void
     */
    public function set($item, $value)
    {
        $this->config[$item] = $value;
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
        if (!isset($this->config[$item])) {
            return false;
        }
        $pref = $this->config[$item];
        if ($pref != '' AND substr($pref, -1) != '/') {
            $pref .= '/';
        }
        return $pref;
    }

}

// END Config Class

/* End of file Config.php */
/* Location: ./packages/config/releases/0.0.1/config.php */