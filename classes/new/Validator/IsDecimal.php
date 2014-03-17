<?php
Class Validator_IsDecimal {

    // --------------------------------------------------------------------
    
    /**
     * is Decimal ?
     *
     * @access   public
     * @param    number
     * @return   bool
     */
    public function __invoke($number)
    {
        return is_numeric($number) && floor($number) != $number;
    }
}