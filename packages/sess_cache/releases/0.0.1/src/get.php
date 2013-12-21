<?php
namespace Sess_Cache\Src {     

    // --------------------------------------------------------------------

    /**
    * Fetch a specific item from the session array
    *
    * @access   public
    * @param    string
    * @return   string
    */        
    function get($item, $prefix = '')
    {
        $sess = \Sess::$driver;

        return ( ! isset($sess->userdata[$prefix.$item])) ? false : $sess->userdata[$prefix.$item];
    }

}