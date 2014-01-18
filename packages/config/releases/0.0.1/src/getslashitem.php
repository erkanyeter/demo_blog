<?php
namespace Config\Src {

    // --------------------------------------------------------------------

    /**
    * Fetch a config file item - adds slash after item
    *
    * The second parameter allows a slash to be added to the end of
    * the item, in the case of a path.
    *
    * @access   public
    * @param    string    the config item name
    * @param    bool
    * @return   string
    */
    function getSlashItem($item)
    {
        $configObject = getComponentInstance('config');

        if ( ! isset($configObject->config[$item]))
        {
            return false;
        }

        $pref = $configObject->config[$item];
        if ($pref != '' AND substr($pref, -1) != '/')
        {    
            $pref .= '/';
        }

        return $pref;
    }

}