<?php
namespace Uri\Src {

    // --------------------------------------------------------------------

    /**
    * Get current url
    *
    * @access   public
    * @return   string
    */
    function getCurrentUrl()
    {
        $uriObject = getComponentInstance('uri');
        
        return $uriObject->getSiteUrl($uriObject->getUriString());
    }

}