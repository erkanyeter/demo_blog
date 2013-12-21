<?php
namespace Sess_Database\Src {

    // ------------------------------------------------------------------------

    /**
    * Fetch a specific flashdata item from the session array
    *
    * @access   public
    * @param    string  $key you want to fetch
    * @param    string  $prefix html open tag
    * @param    string  $suffix html close tag
    * 
    * @version  0.1
    * @version  0.2     added prefix and suffix parameters.
    * 
    * @return   string
    */
    function getFlash($key, $prefix = '', $suffix = '')  // obullo changes ...
    {
        $sess = \Sess::$driver;

        $flashdata_key = $sess->flashdata_key.':old:'.$key;
        $value = $sess->get($flashdata_key);
        
        if($value == '')
        {
            $prefix = '';
            $suffix = '';
        }
        
        return $prefix.$value.$suffix;
    }

}