<?php
namespace Uri\Src {

    // --------------------------------------------------------------------

    /**
     * Total number of routed segments
     *
     * @access    public
     * @return    integer
     */
    function getTotalRoutedSegments()
    {
        $uriObject = getComponentInstance('uri');

        return count($uriObject->rsegments);
    }

}