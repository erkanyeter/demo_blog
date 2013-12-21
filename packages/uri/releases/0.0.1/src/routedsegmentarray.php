<?php
namespace Uri\Src {

    // --------------------------------------------------------------------

    /**
     * Routed Segment Array
     *
     * @access    public
     * @return    array
     */
    function routedSegmentArray()
    {
        $uriObject = getComponentInstance('uri');

        return $uriObject->rsegments;
    }

}