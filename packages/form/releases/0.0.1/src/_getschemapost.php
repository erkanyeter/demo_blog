<?php
namespace Form\Src {

    // ------------------------------------------------------------------------

    /**
     * Get $_REQUEST value from
     * $_POST data or database $row 
     * using valid schema comparison.
     *  
     * @param  object | null $row [description]
     * @return string
     */
    function _getSchemaPost($row = null, $field)
    {
        $schemaName = getInstance()->form->getSchema();

        if(is_array($field))
        {
            $field = $field['name'];
        }

        $value = (isset($_REQUEST[$field])) ? getInstance()->form->setValue($field) : '';

        if($schemaName != '')
        {
            $schema = getSchema($schemaName);
            unset($schema['*']);

            if(isset($schema[$field]) AND ! isset($_REQUEST[$field]))
            {
                if(is_object($row))
                {
                    $value = $row->{$field};
                }
                elseif(is_array($row))
                {
                    $value = $row[$field];   
                } 
            }
        }

        return $value;
    }
    
}