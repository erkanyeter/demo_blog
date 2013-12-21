<?php
namespace Validator\Src {

    // --------------------------------------------------------------------
    
    /**
     * Alpha
     *
     * @access   public
     * @param    string
     * @return   bool
     */        
    function alpha($str)
    {
        return ( ! preg_match("/^([a-z])+$/i", $str)) ? false : true;
    }

}