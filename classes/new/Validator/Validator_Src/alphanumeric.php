<?php
namespace Validator\Src {

    // --------------------------------------------------------------------
    
    /**
     * Alpha-numeric
     *
     * @access   public
     * @param    string
     * @return   bool
     */    
    function alphaNumeric($str)
    {
        return ( ! preg_match("/^([a-z0-9])+$/i", $str)) ? false : true;
    }

}