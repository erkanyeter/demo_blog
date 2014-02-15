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

Class Config {    

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
        global $config;

        $this->config = $config;
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

        if( ! function_exists('Config\Src\\'.$method))
        {
            require PACKAGES .'config'. DS .'releases'. DS .$packages['dependencies']['config']['version']. DS .'src'. DS .strtolower($method). EXT;
        }

        return call_user_func_array('Config\Src\\'.$method, $arguments);
    }

    // --------------------------------------------------------------------
    
    /**
     * Get instance of config class
     * 
     * @return object
     */
    public static function getInstance()
    {
       if( ! self::$instance instanceof self)
       {
           self::$instance = new self();
       } 
   
       return self::$instance;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Set instance of config class
     * 
     * @param $object of instance
     * @return  void
     */
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