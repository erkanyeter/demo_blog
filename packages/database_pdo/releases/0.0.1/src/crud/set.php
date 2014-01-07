<?php

namespace Database_Pdo\Src\Crud {

    // --------------------------------------------------------------------
    
    /**
    * The "set" function.  Allows key / value pairs to be set for inserting or updating
    *
    * @access   public
    * @param    mixed
    * @param    string
    * @param    boolean
    * @return   void
    */
    function set($key, $value = '', $escape = true)
    {
        $crud = getInstance()->{\Db::$var};

        //-------------- Schema Support Begin -----------------//

        if(is_object($key))  // Model Object ( Schema Support )
        {
            $setSchemaArray = array();
            $schemaArray = getSchema($key->getTableName()); // Get tablename from model
            $colprefix   = (isset($schemaArray['*']['colprefix'])) ? $schemaArray['*']['colprefix'] : '';

            unset($schemaArray['*']); // Grab just the fields.

            foreach(array_keys($schemaArray) as $field)
            {
                if(isset($key->data[$field])) // Is schema field selected ?
                {
                    $setSchemaArray[$colprefix.$field] = $key->data[$field]; // Let's build insert data.
                }
            }
            
            unset($key);
            $key = $setSchemaArray;
        }
        
        //-------------- Schema Support End -----------------//
        
        if ( ! is_array($key))
        {
            $key = array($key => $value);
        }

        foreach ($key as $k => $v)
        {
            if ($escape === false)                      
            {                                           
                if( strpos($v, ':') === false || strpos($v, ':') > 0) // We ake sure is it bind value, if not ... 
                {
                     if(is_string($v))
                     {
                         $v = "'{$v}'";  // PDO changes.
                     }
                }

                $crud->ar_set[$crud->_protectIdentifiers($k)] = $v;  
            }
            else
            {
                $crud->ar_set[$crud->_protectIdentifiers($k)] = $crud->escape($v);
            }
        }
        
        return $crud; 
    }

}