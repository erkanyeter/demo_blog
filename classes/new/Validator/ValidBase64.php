<?php
Class Validator_ValidBase64 {

    // --------------------------------------------------------------------
    
    /**
     * Valid Base64
     *
     * Tests a string for characters outside of the Base64 alphabet
     * as defined by RFC 2045 http://www.faqs.org/rfcs/rfc2045
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    public function __invoke($str)
    {
        return (bool) ! preg_match('/[^a-zA-Z0-9\/\+=]/', $str);
    }

}