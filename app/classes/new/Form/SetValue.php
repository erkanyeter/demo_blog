<?php
Class Form_SetValue {

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
    public function __invoke($field = '', $default = '')
    {
        $form = getInstance()->form;

        if (false === ($OBJ = $form->_getValidatorObject())) {
            if ( ! isset($_REQUEST[$field])) {
                return $default;
            }

            return $form->prep($_REQUEST[$field], $field);
        }

        if (isset($_REQUEST[$field])) {
            return $form->prep($_REQUEST[$field], $field);
        } elseif ($OBJ->setValue($field, $default) != '') {
            return $form->prep($OBJ->setValue($field, $default), $field);
        }
    }

}