<?php
namespace Uri\Src {

    // --------------------------------------------------------------------

    /**
    * Get current url
    *
    * @access   public
    * @return   string
    */
    function currentUrl()
    {
        $uriObject = getComponentInstance('uri');
        
        return $uriObject->siteUrl($uriObject->uriString());
    }

}