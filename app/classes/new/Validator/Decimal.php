<?php
Class Validator_Decimal {

    // --------------------------------------------------------------------
    
    /**
     * Leave as "number" ( Alias of number )
     *
     * @access   public
     * @param    string
     * @return   bool
     */        
    public function __invoke($number)
    {
        if (is_numeric($number)) {
            return $number;
        }

        return 0;  // If its not a number return to 0.
                   // http://stackoverflow.com/questions/8529656/how-to-convert-string-to-number-in-php
    }

}