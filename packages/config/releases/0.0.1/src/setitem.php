<?php
namespace Config\Src {

    // --------------------------------------------------------------------
    
    /**
    * Set a config file item
    * alias of config_item we will deprecicate it later.
    *
    * @access   public
    * @param    string    the config item key
    * @param    string    the config item value
    * @return   void
    */
    function setItem($item, $value)
    {
        \Config::getInstance()->config[$item] = $value;
    }
    
}