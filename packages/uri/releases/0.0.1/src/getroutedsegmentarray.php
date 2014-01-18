<?php
namespace Uri\Src {

    // --------------------------------------------------------------------

    /**
     * Routed Segment Array
     *
     * @access    public
     * @return    array
     */
    function getRoutedSegmentArray()
    {
        $uriObject = getComponentInstance('uri');

        return $uriObject->rsegments;
    }

}