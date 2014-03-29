<?php
Class Validator_IsInteger {

    // --------------------------------------------------------------------
    
    /**
     * Integer
     *
     * @access    public
     * @param    string
     * @return    bool
     */    
    public function __invoke($str)
    {
        return (bool)preg_match( '/^[\-+]?[0-9]+$/', $str);
    }
}