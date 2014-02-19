<?php
namespace Config\Src {

    // --------------------------------------------------------------------
    
    /**
    * Fetch a config file item
    *
    *
    * @access   public
    * @param    string    the config item name
    * @param    string    the index name
    * @param    bool
    * @return   string
    */
    function getItem($item, $index = '')
    {    
        $configObject = \Config::getInstance();

        if ($index == '')
        {    
            if ( ! isset($configObject->config[$item]))
            {
                logMe('info', 'Requested config item "'.$item.'" not found, be sure providing to right name');

                return false;
            }
            
            $pref = $configObject->config[$item];
        }
        else
        {
            if ( ! isset($configObject->config[$index]))
            {
                logMe('info', 'Requested config index "'.$item.'" not found, be sure providing to right name');

                return false;
            }

            if ( ! isset($configObject->config[$index][$item]))
            {
                logMe('info', 'Requested config item "'.$item.'" not found, be sure providing to right name');
                
                return false;
            }

            $pref = $configObject->config[$index][$item];
        }

        return $pref;
    }

}