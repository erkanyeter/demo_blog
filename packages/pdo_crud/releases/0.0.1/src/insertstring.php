<?php
namespace Pdo_Crud\Src {

    // --------------------------------------------------------------------
 
    /**
    * Generate an insert string
    *
    * @access   public
    * @param    string   the table upon which the query will be performed
    * @param    array    an associative array data of key/values
    * @return   string        
    */    
    function insertString($table, $data)
    {
        $crud = getInstance()->{\Db::$var};

        $fields = array();
        $values = array();
        
        foreach($data as $key => $val)
        {
            $fields[] = $crud->_escapeIdentifiers($key);
            $values[] = $crud->escape($val);
        }
                
        return $crud->_insert($table, $fields, $values);
    }

}