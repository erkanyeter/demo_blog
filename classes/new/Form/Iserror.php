<?php
Class Form_IsError  {

    // ------------------------------------------------------------------------

    /**
    * Check form field is return to error
    * if $return_string not empty it returns to
    * string otherwise it returns to booelan.
    * 
    * @param string $field
    * @param string $return_string
    * @return booelan | string
    */
    public function __invoke($field = '', $return_string = '')
    {
        $error = getInstance()->form->error($field);
        
        if ($error != '') {
            return ($return_string != '') ? $return_string : true;
        }

        return ($return_string != '') ? '' : false;
    }
    
}