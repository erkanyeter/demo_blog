<?php
namespace Uri\Src {

    // --------------------------------------------------------------------

    /**
    * Get extension of uri
    *
    * @return  string
    */
    function getExtension()
    {
        return \Uri::getInstance()->uri_extension;
    }
    
}