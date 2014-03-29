<?php
Class Validator_PrepUrl {

    // --------------------------------------------------------------------
    
    /**
     * Prep URL
     *
     * @access    public
     * @param    string
     * @return    string
     */    
    public function __invoke($str = '')
    {
        if ($str == 'http://' OR $str == '') {
            return '';
        }
        
        if (substr($str, 0, 7) != 'http://' && substr($str, 0, 8) != 'https://') {
            $str = 'http://'.$str;
        }
        
        return $str;
    }

}