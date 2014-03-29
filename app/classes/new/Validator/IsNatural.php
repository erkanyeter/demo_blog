<?php
Class Validator_IsNatural {

    // --------------------------------------------------------------------

    /**
     * Is a Natural number  (0,1,2,3, etc.)
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    public function __invoke($str)
    {
        return (bool)preg_match( '/^[0-9]+$/', $str);
    }
    
}