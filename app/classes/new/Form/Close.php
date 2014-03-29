<?php
Class Form_Close {

    // ------------------------------------------------------------------------

    /**
    * Form Close Tag
    *
    * @access   public
    * @param    string
    * @return   string
    */
    public function __invoke($extra = '')
    {
        return "</form>".$extra;
    }
    
}