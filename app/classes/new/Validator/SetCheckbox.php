<?php
Class Validator_SetCheckbox {

    // --------------------------------------------------------------------
    
    /**
     * Set Checkbox
     *
     * Enables checkboxes to be set to the value the user
     * selected in the event of an error
     *
     * @access    public
     * @param    string
     * @param    string
     * @return    string
     */    
    public function __invoke($field = '', $value = '', $default = false)
    {
        $validator = getInstance()->validator;

        if ( ! isset($validator->_field_data[$field]) OR ! isset($validator->_field_data[$field]['postdata'])) {
            if ($default === true AND count($validator->_field_data) === 0) {
                return ' checked="checked"';
            }
            
            return '';
        }
    
        $field = $validator->_field_data[$field]['postdata'];
        
        if (is_array($field)) {
            if ( ! in_array($value, $field)) {
                return '';
            }
        } else {
            if (($field == '' OR $value == '') OR ($field != $value)) {
                return '';
            }
        }
            
        return ' checked="checked"';
    }
    
}