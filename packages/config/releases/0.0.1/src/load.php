<?php
namespace Config\Src {

    // --------------------------------------------------------------------
    
    /**
    * Load Config File
    *
    * @access   public
    * @param    string    the config file name
    * @return   boolean   if the file was loaded correctly
    */    
    function load($filename = '', $use_sections = false)
    {
        $configObject = \Config::getInstance();

        $file = APP .'config'. DS .$filename. EXT;

        if (in_array($file, $configObject->is_loaded, true))
        {
            return true;
        }
    
        ######################
        
        include($file);
                
        ######################

        if ( ! isset($config) OR ! is_array($config))
        {
            throw new \Exception('Your '. $file .' file does not appear to contain a valid configuration array. Please create $config variables in your ' .$file);
        }
        
        if ($use_sections === true)
        {
            if (isset($configObject->config[$file]))
            {
                $configObject->config[$file] = array_merge($configObject->config[$file], $config);
            }
            else
            {
                $configObject->config[$file] = $config;
            }
        }
        else
        {
            $configObject->config = array_merge($configObject->config, $config);
        }

        $configObject->is_loaded[] = $file;

        unset($config);

        logMe('debug', 'Config file loaded: '.$file);
        
        return true;
    }

}