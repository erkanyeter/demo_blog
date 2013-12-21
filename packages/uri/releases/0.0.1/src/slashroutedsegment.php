<?php
namespace Uri\Src {

    // --------------------------------------------------------------------

    /**
     * Fetch a URI Segment and add a trailing slash
     *
     * @access   public
     * @param    integer
     * @param    string
     * @return   string
     */
    function slashRoutedSegment($n, $where = 'trailing')
    {
        $uriObject = getComponentInstance('uri');

        return $uriObject->_slashSegment($n, $where, 'rsegment');
    }
    
}