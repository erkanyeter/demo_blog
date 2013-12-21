<?php
namespace Uri\Src {

    // --------------------------------------------------------------------

    /**
     * Total number of segments
     *
     * @access    public
     * @return    integer
     */
    function totalSegments()
    {
        $uriObject = getComponentInstance('uri');

        return count($uriObject->segments);
    }
    
}