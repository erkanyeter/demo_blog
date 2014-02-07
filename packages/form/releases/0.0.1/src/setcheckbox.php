<?php
namespace Form\Src {

    // ------------------------------------------------------------------------

    /**
    * Set Checkbox
    *
    * Let's you set the selected value of a checkbox via the value in the POST array.
    * If Form Validation is active it retrieves the info from the validation class
    *
    * @access	public
    * @param	string
    * @param	string
    * @param	bool
    * @return	string
    */
    function setCheckbox($field = '', $value = '', $default = false)
    {
        $OBJ = getInstance()->form->_getValidatorObject();

        if(is_object($value)) // $_POST & Db value schema sync
        {
            $value = getInstance()->form->_getSchemaPost($value, $field); 
        }

        if ($OBJ !== false)
        { 
            if ( ! isset($_REQUEST[$field]))
            {
                if (count($_REQUEST) === 0 AND $default == true)
                {
                    return ' checked="checked"';
                }

                return '';
            }

            $field = $_REQUEST[$field];

            if (is_array($field))
            {
                if ( ! in_array($value, $field))
                {
                    return '';
                }
            }
            else
            {
                if (($field == '' OR $value == '') OR ($field != $value))
                {
                    return '';
                }
            }

            return ' checked="checked"';
        }

        return $OBJ->setCheckbox($field, $value, $default);
    }
    
}