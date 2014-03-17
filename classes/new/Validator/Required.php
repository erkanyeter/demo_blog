<?php
Class Validator_Required {
    
    // --------------------------------------------------------------------
    
    /**
     * Required
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    public function __invoke($str)
    {
        if ( ! is_array($str)) {
            return (trim($str) == '') ? false : true;
        } else {
            return ( ! empty($str));
        }
    }

}