<?php
namespace Uri\Src {

    // --------------------------------------------------------------------

    /**
     * Fetch a URI Segment and add a trailing slash - helper function
     *
     * @access   private
     * @param    integer
     * @param    string
     * @param    string
     * @return   string
     */
    function _slashSegment($n, $where = 'trailing', $which = 'getSegment')
    {
        $uriObject = \Uri::getInstance();
        
        if ($where == 'trailing')
        {
            $trailing    = '/';
            $leading    = '';
        }
        elseif ($where == 'leading')
        {
            $leading    = '/';
            $trailing    = '';
        }
        else
        {
            $leading    = '/';
            $trailing    = '/';
        }
        
        return $leading.$uriObject->$which($n).$trailing;
    }
    
}