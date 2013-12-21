<?php
namespace Database_Pdo\Src\Crud {

    // --------------------------------------------------------------------
                                             
    function join($table, $cond, $type = '')
    {        
        $crud = getInstance()->{\Db::$var};

        if ($type != '')
        {
            $type = strtoupper(trim($type));

            if ( ! in_array($type, array('LEFT', 'RIGHT', 'OUTER', 'INNER', 'LEFT OUTER', 'RIGHT OUTER')))
            {
                $type = '';
            }
            else
            {
                $type .= ' ';
            }
        }

        //
        // Extract any aliases that might exist.  We use this information
        // in the _protect_identifiers to know whether to add a table prefix 
        // 
        $crud->_trackAliases($table);

        if (preg_match('/([\w\.]+)([\W\s]+)(.+)/', $cond, $match))  // Strip apart the condition and protect the identifiers
        {
            $match[1] = $crud->_protectIdentifiers($match[1]);
            $match[3] = $crud->_protectIdentifiers($match[3]);
        
            $cond = $match[1].$match[2].$match[3];        
        }
        
        $join = $type.'JOIN '.$crud->_protectIdentifiers($table, true, null, false).' ON '.$cond;  // Assemble the JOIN statement
        $crud->ar_join[] = $join;

        if ($crud->ar_caching === true)
        {
            $crud->ar_cache_join[]   = $join;
            $crud->ar_cache_exists[] = 'join';
        }

        return $crud;
    }

}