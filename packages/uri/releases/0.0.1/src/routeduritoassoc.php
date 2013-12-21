<?php
namespace Uri\Src {

    // --------------------------------------------------------------------
    
    /**
     * Identical to above only it uses the re-routed segment array
     *
     */
    function routedUriToAssoc($n = 3, $default = array())
    {
    	$uriObject = getComponentInstance('uri');

    	return $uriObject->_uriToAssoc($n, $default, 'routedSegment');
    }

}