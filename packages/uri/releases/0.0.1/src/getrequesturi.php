<?php
namespace Uri\Src {

    // --------------------------------------------------------------------
    
    /**
     * Get the complete request uri like native php
     * $_SERVER['REQUEST_URI'].
     * 
     * @access public
     * @param  boolean $urlencode
     * @return string
     */
    function getRequestUri($urlencode = false)
    {
        $uriObject = \Uri::getInstance();

        if(isset($_SERVER[$uriObject->getProtocol()]))
        {
           return ($urlencode) ? urlencode($_SERVER[$uriObject->getProtocol()]) : $_SERVER[$uriObject->getProtocol()];
        }

        return false;
    }

}