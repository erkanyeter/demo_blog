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
    function protocol()
    {
        $uriObject = getComponentInstance('uri');

        return $uriObject->uri_protocol;
    }

}