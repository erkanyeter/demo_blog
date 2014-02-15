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
        return '/'.implode('/', \Uri::getInstance()->getRoutedSegmentArray()).'/';
    }

}