<?php
namespace Database_Pdo\Src\Crud {

    // ------------------------------------------------------------------------

    function select($select = '*', $escape = null)
    {
        $crud = getInstance()->{\Db::$var};

        // Set the global value if this was sepecified    
        if (is_bool($escape))
        {
            $crud->_protect_identifiers = $escape;
        }
        
        if (is_string($select))
        {
            $select = explode(',', $select);
        }
        
        foreach ($select as $val)
        {
            $val = trim($val);

            if ($val != '')
            {
                $crud->ar_select[] = $val;

                if ($crud->ar_caching === true)
                {
                    $crud->ar_cache_select[] = $val;
                    $crud->ar_cache_exists[] = 'select';
                }
            }
        }
        
        return $crud;
    }    

}