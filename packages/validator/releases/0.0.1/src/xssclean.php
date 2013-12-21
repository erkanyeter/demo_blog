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
        $security = getComponentInstance('security');
        
        return $security->xssClean($str);
    }
    
}