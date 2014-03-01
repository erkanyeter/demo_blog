<?php
namespace Pdo_Crud\Src {

    // -------------------------------------------------------------------- 
        
    /**
    * Where_in
    *
    * Called by where_in, where_in_or, where_not_in, where_not_in_or
    *
    * @access   public
    * @param    string    The field to search
    * @param    array     The values searched on
    * @param    boolean   If the statement would be IN or NOT IN
    * @param    string    
    * @return   object
    */
    function _whereIn($key = null, $values = null, $not = false, $type = 'AND ')
    {
        $crud = getInstance()->{\Db::$var};

        if ($key === null OR $values === null)
        {
            return;
        }
        
        if ( ! is_array($values))
        {
            $values = array($values);
        }
        
        $not = ($not) ? ' NOT' : '';

        foreach ($values as $value)
        {
            $crud->ar_wherein[] = $crud->escape($value);
        }

        $prefix = (sizeof($crud->ar_where) == 0) ? '' : $type;
 
        $where_in = $prefix . $crud->_protectIdentifiers($key) . $not . " IN (" . implode(", ", $crud->ar_wherein) . ") ";

        $crud->ar_where[] = $where_in;
        if ($crud->ar_caching === true)
        {
            $crud->ar_cache_where[]  = $where_in;
            $crud->ar_cache_exists[] = 'where';
        }

        $crud->ar_wherein = array();   // reset the array for multiple calls
        
        return $crud;
    }

}