<?php
namespace Uri\Src {

    // --------------------------------------------------------------------

    /**
     * Generate a key value pair from the URI string or Re-routed URI string
     *
     * @access   private
     * @param    integer    the starting segment number
     * @param    array    an array of default values
     * @param    string    which array we should use
     * @return   array
     */
    function _uriToAssoc($n = 3, $default = array(), $which = 'getSegment')
    {
        $uriObject = getComponentInstance('uri');

        if ($which == 'getSegment')
        {
            $totalSegments = 'getTotalSegments';
            $segmentArray  = 'getSegmentArray';
        }
        else
        {
            $totalSegments = 'getTotalRoutedSegments';
            $segmentArray  = 'getRoutedSegmentArray';
        }

        if ( ! is_numeric($n))
        {
            return $default;
        }

        if (isset($uriObject->keyval[$n]))
        {
            return $uriObject->keyval[$n];
        }

        if ($uriObject->$totalSegments() < $n)
        {
            if (count($default) == 0)
            {
                return array();
            }

            $retval = array();
            foreach ($default as $val)
            {
                $retval[$val] = false;
            }

            return $retval;
        }

        $segments = array_slice($uriObject->$segmentArray(), ($n - 1));

        $i = 0;
        $lastval = '';
        $retval  = array();
        foreach ($segments as $seg)
        {
            if ($i % 2)
            {
                $retval[$lastval] = $seg;
            }
            else
            {
                $retval[$seg] = false;
                $lastval = $seg;
            }

            $i++;
        }

        if (count($default) > 0)
        {
            foreach ($default as $val)
            {
                if ( ! array_key_exists($val, $retval))
                {
                    $retval[$val] = false;
                }
            }
        }

        $uriObject->keyval[$n] = $retval;  // Cache the array for reuse
        
        return $retval;
    }

}