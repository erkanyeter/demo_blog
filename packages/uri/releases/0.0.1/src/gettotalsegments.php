<?php
namespace Uri\Src {

    // --------------------------------------------------------------------

    /**
     * Total number of segments
     *
     * @access    public
     * @return    integer
     */
    function getTotalSegments()
    {
        $uriObject = \Uri::getInstance();

        return sizeof($uriObject->segments);
    }
    
}