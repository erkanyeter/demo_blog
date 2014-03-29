<?php
Class Validator_XssClean {

    // --------------------------------------------------------------------
    
    /**
     * XSS Clean
     *
     * @access   public
     * @param    string
     * @return   string
     */    
    public function __invoke($str)
    {   
        if ( ! isset(getInstance()->security)) {
            new \Security;
        }

        return getInstance()->security->xssClean($str);
    }
    
}