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
        return \Uri::getInstance()->uri_string;
    }
    
}