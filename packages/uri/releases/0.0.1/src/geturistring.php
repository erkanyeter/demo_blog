<?php
namespace Uri\Src {

    // --------------------------------------------------------------------

    /**
     * Fetch the entire URI string
     *
     * @access    public
     * @return    string
     */
    function getUriString()
    {
        $uriObject = getComponentInstance('uri');

        return $uriObject->uri_string;
    }
    
}