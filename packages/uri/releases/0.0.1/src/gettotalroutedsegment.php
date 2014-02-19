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
        $uriObject = \Uri::getInstance();

        return sizeof($uriObject->rsegments);
    }

}