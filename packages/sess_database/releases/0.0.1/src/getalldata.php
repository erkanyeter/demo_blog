<?php
namespace Sess_Database\Src {    

    // --------------------------------------------------------------------

    /**
    * Fetch all session data
    *
    * @access    public
    * @return    mixed
    */
    function getAllData()
    {
        $sess = \Sess::$driver;

        return ( ! isset($sess->userdata)) ? false : $sess->userdata;
    }

}