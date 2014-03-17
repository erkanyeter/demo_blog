<?php
namespace Validator\Src {

    // --------------------------------------------------------------------
    
    /**
     * Set type to "boolean"
     *
     * @access   public
     * @param    boolean
     * @return   boolean
     */        
    function _boolean($boolean)
    {
        return (boolean)$boolean;
    }

}