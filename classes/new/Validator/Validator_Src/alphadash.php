<?php
namespace Validator\Src {

    // --------------------------------------------------------------------
    
    /**
     * Alpha-numeric with underscores and dashes
     *
     * @access   public
     * @param    string
     * @return   bool
     */    
    function alphaDash($str)
    {
        return ( ! preg_match("/^([-a-z0-9_-])+$/i", $str)) ? false : true;
    }

}