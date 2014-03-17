<?php
Class Validator_Integer {

    // --------------------------------------------------------------------
    
    /**
     * Set type to "integer"
     *
     * @access   public
     * @param    integer
     * @return   integer
     */        
    public function __invoke($integer)
    {
        return (integer)$integer;
    }

}