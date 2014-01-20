<?php
namespace Database_Pdo\Src\Crud {

    // --------------------------------------------------------------------

    /**
    * Fetch prepared or none prepared last_query
    *
    * @author   Ersin Guvenc
    * @param    boolean $prepared
    * @return   string
    */
    function getLastQuery($prepared = false)
    {
        $crud = getInstance()->{\Db::$var};

        if($prepared == true AND $crud->isAssocArray($crud->last_values) AND isset($crud->last_values[$crud->exec_count])) // let's make sure, is it prepared query ?
        {
            $bind_keys = array();
            foreach(array_keys($crud->last_values[$crud->exec_count]) as $k)
            {
                $bind_chr = ':';
                if(strpos($k, ':') === 0) // If user use ':' characters ?
                {   
                    $bind_chr = '';
                }
                
                $bind_keys[]  = "/\\$bind_chr".$k.'\b/';  // escape bind ':' character
            }

            $quoted_vals = array();
            foreach(array_values($crud->last_values[$crud->exec_count]) as $v)
            {
                $quoted_vals[] = $crud->quote($v);
            }

            $crud->last_values = array();  // reset last values.

            return preg_replace($bind_keys, $quoted_vals, $crud->last_sql);
        }

        return $crud->last_sql;
    }

}