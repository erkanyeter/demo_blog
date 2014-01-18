<?php
namespace Uri\Src {

    // --------------------------------------------------------------------

    /**
     * Fetch a URI Segment
     *
     * This function returns the URI segment based on the number provided.
     *
     * @access   public
     * @param    integer
     * @param    bool
     * @return   string
     */
    function getSegment($n, $no_result = false)
    {
        $uriObject = getComponentInstance('uri');
        
        return ( ! isset($uriObject->segments[$n])) ? $no_result : $uriObject->segments[$n];
    }
}