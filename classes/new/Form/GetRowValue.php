<?php
Class Form_GetRowValue {

    // ------------------------------------------------------------------------

    /**
     * Get $_REQUEST value from
     * $_POST data or database $row 
     * using valid schema comparison.
     * 
     * @param  object | null $row
     * @return string
     */
    public function __invoke($row = null, $field)
    {
        if (is_array($field)) {
            $field = $field['name'];
        }

        $value = (isset($_REQUEST[$field])) ? getInstance()->form->setValue($field) : '';

        if ( ! isset($_REQUEST[$field])) { // If POST data not available use Database $row
            if (is_object($row) AND isset($row->{$field})) { // If field available in database $row Object
                $value = $row->{$field};
            } elseif (is_array($row) AND isset($row[$field])) {// If field available in database $row Array
                $value = $row[$field];   
            } 
        }

        return $value;
    }
    
}