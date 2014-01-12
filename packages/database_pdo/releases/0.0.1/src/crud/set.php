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

        if(is_object($key) AND isset($crud->ar_from[0]))  // Model Object ( Schema Support )
        {
            $tablename   = str_replace($crud->_escape_char, '', $crud->ar_from[0]); // remove escape char "`" get pure tablename
            $schemaArray = $key->getMultiSchema();  // Get current multi schema array

            if( ! isset($schemaArray[$tablename]))  // Get schemas using tablenames
            {
                throw new \Exception('Schema '.$tablename.' file not found for crud operation.');
            }

            $setSchemaArray = array();
            foreach(array_keys($schemaArray[$tablename]) as $field)
            {
                if(isset($key->data[$tablename.'.'.$field]))  // Is column join request ? 
                {
                    $key->data[$field] = $key->data[$tablename.'.'.$field]; // Remove column join prefix
                    unset($key->data[$tablename.'.'.$field]);
                }

                if(isset($key->data[$field])) // Is schema field selected ?
                {
                    $setSchemaArray[$field] = $key->data[$field]; // Let's build insert data.
                }
            }

            if($tablename == 'posts')
            {
                var_dump($key->data);
                var_dump($setSchemaArray); exit;
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
                if( strpos($v, ':') === false || strpos($v, ':') > 0) // We make sure is it bind value, if not ... 
                {
                     if(is_string($v))
                     {
                         $v = "{$v}";  // PDO changes.
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