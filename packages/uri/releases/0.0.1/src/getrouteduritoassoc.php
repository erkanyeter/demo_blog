<?php
namespace Uri\Src {

    // --------------------------------------------------------------------
    
    /**
     * Identical to above only it uses the re-routed segment array
     *
     */
    function getRoutedUriToAssoc($n = 3, $default = array())
    {
    	return \Uri::getInstance()->_uriToAssoc($n, $default, 'routedSegment');
    }

}