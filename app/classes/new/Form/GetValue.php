<?php
Class Form_GetValue {

    // ------------------------------------------------------------------------

    /**
    * Form Get Value
    *
    * Grabs a value from the POST array for the specified field so you can
    * re-populate an input field or textarea.  If Form Validation
    * is active it retrieves the info from the validation class
    *
    * @access	public
    * @param	string
    * @return	mixed
    */
    public function __invoke($field = '')
    {
        $form = getInstance()->form;

        return $form->setValue($field);
    }
}