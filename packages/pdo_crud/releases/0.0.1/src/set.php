<?php
namespace Pdo_Crud\Src {

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