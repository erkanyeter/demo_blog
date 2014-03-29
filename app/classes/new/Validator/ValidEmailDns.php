<?php
Class Validator_ValidEmailDns {
    
    // --------------------------------------------------------------------
    
    /**
     * Valid Email + DNS Check
     *
     * @access   public
     * @param    string
     * @return   bool
     */
    public function __invoke($str)
    {
        $validator = getInstance()->validator;

        if ($validator->validEmail($str) === true) {
            list($username, $domain) = explode('@', $str);
            
            if ( ! checkdnsrr($domain, 'MX')) {
                return false;
            }

            return true;
        }

        return false;
    }
    
}