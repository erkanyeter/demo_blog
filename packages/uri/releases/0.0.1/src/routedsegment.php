<?php
namespace Uri\Src {

    // --------------------------------------------------------------------

    /**
     * Fetch a URI "routed" Segment ( Sub module isn't a rsegment based.)
     *
     * This function returns the re-routed URI segment (assuming routing rules are used)
     * based on the number provided.  If there is no routing this function returns the
     * same result as $uriObject->segment()
     *
     * @access   public
     * @param    integer
     * @param    bool
     * @return   string
     */
    function routedSegment($n, $no_result = false)
    {
        $uriObject = getComponentInstance('uri');

        return ( ! isset($uriObject->rsegments[$n])) ? $no_result : $uriObject->rsegments[$n];
    }
}