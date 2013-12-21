<?php
namespace Uri\Src {

    // --------------------------------------------------------------------

    /**
     * Fetch the entire URI string
     *
     * @access    public
     * @return    string
     */
    function uriString()
    {
        $uriObject = getComponentInstance('uri');

        return $uriObject->uri_string;
    }
    
}