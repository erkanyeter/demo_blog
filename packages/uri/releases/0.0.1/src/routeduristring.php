<?php
namespace Uri\Src {

    // --------------------------------------------------------------------

    /**
     * Fetch the entire Re-routed URI string
     *
     * @access    public
     * @return    string
     */
    function routedUriString()
    {
        $uriObject = getComponentInstance('uri');

        return '/'.implode('/', $uriObject->routedSegmentArray()).'/';
    }

}