<?php
namespace Validator\Src {

    // --------------------------------------------------------------------
    
    /**
     * XSS Clean
     *
     * @access   public
     * @param    string
     * @return   string
     */    
    function xssClean($str)
    {   
        return \Security::getInstance()->xssClean($str);
    }
    
}