<?php
Class Validator_Int {

    // --------------------------------------------------------------------
    
    /**
     * Set type to "integer" ( Alias of "integer" )
     *
     * @access   public
     * @param    integer
     * @return   integer
     */        
    public function __invoke($integer)
    {
        return (int)$integer;
    }

}