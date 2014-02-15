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
        $uriObject = \Uri::getInstance();
        
        return $uriObject->getSiteUrl($uriObject->getUriString());
    }

}