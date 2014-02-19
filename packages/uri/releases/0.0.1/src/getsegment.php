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
        $uriObject = \Uri::getInstance();
        
        return ( ! isset($uriObject->segments[$n])) ? $no_result : $uriObject->segments[$n];
    }
}