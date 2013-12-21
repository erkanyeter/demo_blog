<?php
namespace Database_Pdo\Src\Crud {

    // --------------------------------------------------------------------
    
    /**
    * Where
    *
    * Called by where() or orwhere()
    *
    * @access   private
    * @param    mixed
    * @param    mixed
    * @param    string
    * @version  0.1
    * @return   void
    */
    function _where($key, $value = null, $type = 'AND ', $escape = null)
    {
        $crud = getInstance()->{\Db::$var};

        if ( ! is_array($key))
        {
            $key = array($key => $value);
        }
        
        // If the escape value was not set will base it on the global setting
        if ( ! is_bool($escape))
        {
            $escape = $crud->_protect_identifiers;
        }
        
        foreach ($key as $k => $v)
        {   
            $prefix = (count($crud->ar_where) == 0 AND count($crud->ar_cache_where) == 0) ? '' : $type;
            
            if (is_null($v) && ! $crud->_hasOperator($k))
            {
                $k .= ' IS null';  // value appears not to have been set, assign the test to IS null 
            } 
        
            if ( ! is_null($v))
            {
                if ($escape === true)
                {
                    $k = $crud->_protectIdentifiers($k, false, $escape);

                    $v = ' '.$crud->escape($v);
                
                } else  // Obullo changes 
                {   
                    // obullo changes.. 
                    // make sure is it bind value, if not ... 
                    if( strpos($v, ':') === false || strpos($v, ':') > 0)
                    {
                         if(is_string($v))
                         {
                             $v = "'{$v}'";  // obullo PDO changes..
                         }
                    }
                }
                
                if ( ! $crud->_hasOperator($k))
                {
                    $k .= ' =';
                }
            
            } else 
            {
                $k = $crud->_protectIdentifiers($k, false, $escape);
            }
             
            $crud->ar_where[] = $prefix.$k.$v;
            
            if ($crud->ar_caching === true)
            {
                $crud->ar_cache_where[]  = $prefix.$k.$v;
                $crud->ar_cache_exists[] = 'where';
            }
            
        }
        
        return ($crud);
    }

}