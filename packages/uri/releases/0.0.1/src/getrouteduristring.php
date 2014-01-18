<?php
namespace Uri\Src {

    // --------------------------------------------------------------------

    /**
     * Fetch the entire Re-routed URI string
     *
     * @access    public
     * @return    string
     */
    function getRoutedUriString()
    {
        $uriObject = getComponentInstance('uri');

        return '/'.implode('/', $uriObject->getRoutedSegmentArray()).'/';
    }

}