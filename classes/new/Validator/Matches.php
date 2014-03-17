<?php
Class Validator_Matches {

    // --------------------------------------------------------------------
    
    /**
     * Match one field to another
     *
     * @access   public
     * @param    string
     * @param    field
     * @return    bool
     */
    public function __invoke($str, $field)
    {
        if ( ! isset($_REQUEST[$field])) {
            return false;                
        }

        return ($str !== $_REQUEST[$field]) ? false : true;
    }

}