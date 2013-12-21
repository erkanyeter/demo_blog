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
    function requestUri($urlencode = false)
    {
        $uriObject = getComponentInstance('uri');

        if(isset($_SERVER[$uriObject->protocol()]))
        {
           return ($urlencode) ? urlencode($_SERVER[$uriObject->protocol()]) : $_SERVER[$uriObject->protocol()];
        }

        return false;
    }

}