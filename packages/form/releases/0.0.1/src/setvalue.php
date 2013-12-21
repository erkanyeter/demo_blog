<?php
namespace Form\Src {

    // ------------------------------------------------------------------------

    /**
    * Form Value
    *
    * Grabs a value from the POST array for the specified field so you can
    * re-populate an input field or textarea.  If Form Validation
    * is active it retrieves the info from the validation class
    *
    * @access	public
    * @param	string
    * @return	mixed
    */
    function setValue($field = '', $default = '')
    {
        $form = getInstance()->form;

        if (false === ($OBJ = $form->_getValidatorObject()))
        {
            if ( ! isset($_REQUEST[$field]))
            {
                return $default;
            }

            return $form->prep($_REQUEST[$field], $field);
        } 

        if($OBJ->setValue($field, $default) == '' AND isset($_REQUEST[$field]))
        {
            return $form->prep($_REQUEST[$field], $field);
        }

        return $form->prep($OBJ->setValue($field, $default), $field);
    }

}