<?php
namespace Database_Pdo\Src\Crud {

    // --------------------------------------------------------------------
                    
    /**
    * Generate an update string
    *
    * @access   public
    * @param    string   the table upon which the query will be performed
    * @param    array    an associative array data of key/values
    * @param    mixed    the "where" statement
    * @return   string        
    */    
    function updateString($table, $data, $where)
    {
        $crud = getInstance()->{\Db::$var};
        
        if ($where == '')
        {
            return false;
        }
                    
        $fields = array();
        foreach($data as $key => $val)
        {
            $fields[$crud->_protectIdentifiers($key)] = $crud->escape($val);
        }

        if ( ! is_array($where))
        {
            $dest = array($where);
        }
        else
        {
            $dest = array();
            foreach ($where as $key => $val)
            {
                $prefix = (sizeof($dest) == 0) ? '' : ' AND ';
    
                if ($val !== '')
                {
                    if ( ! $crud->_hasOperator($key))
                    {
                        $key .= ' =';
                    }
                
                    $val = ' '.$crud->escape($val);
                }
                            
                $dest[] = $prefix.$key.$val;
            }
        }        

        return $crud->_update($crud->_protectIdentifiers($table, true, null, false), $fields, $dest);
    }

}