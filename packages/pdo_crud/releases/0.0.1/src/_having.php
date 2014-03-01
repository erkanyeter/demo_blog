<?php
namespace Pdo_Crud\Src {

    // --------------------------------------------------------------------
    
    /**
    * Sets the HAVING values
    *
    * Called by having() or orHaving()
    *
    * @access   private
    * @param    string
    * @param    string
    * @return   object
    */
    function _having($key, $value = '', $type = 'AND ', $escape = true)
    {
        $crud = getInstance()->{\Db::$var};

        if ( ! is_array($key))
        {
            $key = array($key => $value);
        }
    
        foreach ($key as $k => $v)
        {
            $prefix = (sizeof($crud->ar_having) == 0) ? '' : $type;

            if ($escape === true)
            {
                $k = $crud->_protectIdentifiers($k);
            }

            if ( ! $crud->_hasOperator($k))
            {
                $k .= ' = ';
            }
            
            if ($v != '')
            {               
                $v = ' '.$crud->escape($v);  // obullo changes ..
            }
            
            $crud->ar_having[] = $prefix.$k.$v;
            if ($crud->ar_caching === true)
            {
                $crud->ar_cache_having[] = $prefix.$k.$v;
                $crud->ar_cache_exists[] = 'having';
            }
        }
        
        return $crud;
    }

}