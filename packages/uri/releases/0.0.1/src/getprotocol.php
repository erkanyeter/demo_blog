<?php
namespace Uri\Src {

    // --------------------------------------------------------------------
    
    /**
     * Get the current server uri
     * protocol.
     * 
     * @access public
     * @return string
     */
    function getProtocol()
    {
        return \Uri::getInstance()->uri_protocol;
    }

}