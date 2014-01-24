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
        return getComponentInstance('uri')->uri_extension;
    }
    
}