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
        $uriObject = getComponentInstance('uri');

        return count($uriObject->segments);
    }
    
}