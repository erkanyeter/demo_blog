<?php
namespace Database_Pdo\Src\Crud {

    // --------------------------------------------------------------------
    
    function from($from)
    {
        $crud = getInstance()->{\Db::$var};
        
        foreach ((array)$from as $val) // beta 1.0 rc1 changes 
        {
            if (strpos($val, ',') !== false)
            {
                foreach (explode(',', $val) as $v)
                {
                    $v = trim($v);
                    $crud->_trackAliases($v);

                    $crud->ar_from[] = $crud->_protectIdentifiers($v, true, null, false);
                    
                    if ($crud->ar_caching === true)
                    {
                        $crud->ar_cache_from[] = $v;
                        $crud->ar_cache_exists[] = 'from';
                    }                
                }

            } else {
                
                $val = trim($val);

                // Extract any aliases that might exist.  We use this information
                // in the _protect_identifiers to know whether to add a table prefix 
                $crud->_trackAliases($val);

                $crud->ar_from[] = $crud->_protectIdentifiers($val, true, null, false);
                
                if ($crud->ar_caching === true)
                {
                    $crud->ar_cache_from[] = $crud->_protectIdentifiers($val, true, null, false);
                    $crud->ar_cache_exists[] = 'from';
                }
                
            }

        } // end foreach.

        return $crud;  
    }
    
}