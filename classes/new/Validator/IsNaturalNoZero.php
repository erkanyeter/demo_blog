<?php
Class Validator_IsNaturalNoZero {

    // --------------------------------------------------------------------

    /**
     * Is a Natural number, but not a zero  (1,2,3, etc.)
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    public function __invoke($str)
    {
        if ( ! preg_match( '/^[0-9]+$/', $str)) {
            return false;
        }
        
        if ($str == 0) {
            return false;
        }
    
        return true;
    }

}