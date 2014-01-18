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
    public $config          = array();
    public $is_loaded       = array();

    public static $instance;

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
        $this->config = getConfig();
    }
    
    // --------------------------------------------------------------------

    /**
     * Call Config Methods If we need them ( Less Memory )
     * 
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        global $packages;

        $package = strtolower(getComponent('config'));

        if( ! function_exists('Config\Src\\'.$method))
        {
            require PACKAGES .$package. DS .'releases'. DS .$packages['dependencies'][$package]['version']. DS .'src'. DS .strtolower($method). EXT;
        }

        return call_user_func_array('Config\Src\\'.$method, $arguments);
    }

    // --------------------------------------------------------------------
    
    public static function getInstance()
    {
       if( ! self::$instance instanceof self)
       {
           self::$instance = new self();
       } 
   
       return self::$instance;
    }
    
    // --------------------------------------------------------------------
    
    public static function setInstance($object)
    {
        if(is_object($object))
        {
            self::$instance = $object;
        }
        
        return self::$instance;
    }
    
}

// END Config Class

/* End of file Config.php */
/* Location: ./packages/config/releases/0.0.1/config.php */