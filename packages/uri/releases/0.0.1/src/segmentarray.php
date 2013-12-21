<?php
namespace Uri\Src {

    // --------------------------------------------------------------------

    /**
     * Segment Array
     *
     * @access    public
     * @return    array
     */
    function segmentArray()
    {
        $uriObject = getComponentInstance('uri');

        return $uriObject->segments;
    }

}