<?php
namespace Uri\Src {

    // --------------------------------------------------------------------

    /**
     * Segment Array
     *
     * @access    public
     * @return    array
     */
    function getSegmentArray()
    {
        $uriObject = getComponentInstance('uri');

        return $uriObject->segments;
    }

}