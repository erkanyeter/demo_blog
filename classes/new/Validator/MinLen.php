<?php
Class Validator_MinLen {

    // --------------------------------------------------------------------
    
    /**
     * Minimum length
     *
     * @access    public
     * @param    string
     * @param    value
     * @return    bool
     */    
    public function __invoke($str, $val)
    {
        if (preg_match("/[^0-9]/", $val)) {
            return false;
        }
        
        return (mb_strlen($str) < $val) ? false : true;
    }

}