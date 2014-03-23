<?php
Class Validator_IsNumeric {

    // --------------------------------------------------------------------

    /**
     * Is Numeric
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    public function __invoke($str)
    {
        return ( ! is_numeric($str)) ? false : true;
    }

}