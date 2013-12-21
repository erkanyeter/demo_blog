<?php
namespace Database_Pdo\Src\Crud {

    // --------------------------------------------------------------------

    /**
    * Merge Cache
    *
    * When called, this function merges any cached AR arrays with 
    * locally called ones.
    *
    * @access    private
    * @return    void
    */
    function _mergeCache()
    {
        $crud = getInstance()->{\Db::$var};

        if (count($crud->ar_cache_exists) == 0)
        {
            return;   
        }

        foreach ($crud->ar_cache_exists as $val)
        {
            $ar_variable    = 'ar_'.$val;
            $ar_cache_var   = 'ar_cache_'.$val;

            if (count($crud->$ar_cache_var) == 0)
            {
                continue;   
            }
    
            $crud->$ar_variable = array_unique(array_merge($crud->$ar_cache_var, $crud->$ar_variable));
        }
    }

}