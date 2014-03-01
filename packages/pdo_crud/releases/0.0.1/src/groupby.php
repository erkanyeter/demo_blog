<?php
namespace Pdo_Crud\Src {

    // --------------------------------------------------------------------
    
    /**
    * GROUP BY
    *
    * @access   public
    * @param    string
    * @return   object
    */
    function groupBy($by)
    {
        $crud = getInstance()->{\Db::$var};

        if (is_string($by))
        {
            $by = explode(',', $by);
        }
        
        foreach ($by as $val)
        {
            $val = trim($val);
        
            if ($val != '')
            {
                $crud->ar_groupby[] = $crud->_protectIdentifiers($val);
                
                if ($crud->ar_caching === true)
                {
                    $crud->ar_cache_groupby[] = $crud->_protectIdentifiers($val);
                    $crud->ar_cache_exists[]  = 'groupby';
                }
            }
        }
        
        return $crud;
    }

}