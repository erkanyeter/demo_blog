<?php
Class Validator_NoSpace {

    // --------------------------------------------------------------------

    /**
     * Check string is contain
     * space
     *
     * @param string $str
     * @return bool
     */
    public function __invoke($str)
    {
       return (preg_match("#\s#", $str)) ? false : true;
    } 

}