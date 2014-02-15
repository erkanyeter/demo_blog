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
    function getSlashRoutedSegment($n, $where = 'trailing')
    {
        return \Uri::getInstance()->_slashSegment($n, $where, 'rsegment');
    }
    
}