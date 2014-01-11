<?php
namespace Database_Pdo\Src\Crud {

    // --------------------------------------------------------------------
                                            
    /**
    * Insert
    *
    * Compiles an insert string and runs the query
    *
    * @access   public
    * @param    string   the table to retrieve the results from
    * @param    array    an associative array of insert values
    * @param    array    insert options
    * @return   PDO exec number of affected rows.
    */
    function insert($table = '', $set = null, $options = array())
    {    
        $crud = getInstance()->{\Db::$var};

        $options = array();
        
        if( ! is_null($set))
        {
            $crud->set($set);
        }
        
        if (sizeof($crud->ar_set) == 0)
        {
            throw new \Exception('Please set values for insert operation.');
            
            return false;
        }

        if ($table == '')
        {
            if ( ! isset($crud->ar_from[0]))
            {
                throw new \Exception('Please set tablename for insert operation.');
                
                return false;
            }
            
            $table = $crud->ar_from[0];
        }

        $sql = $crud->_insert($crud->_protectIdentifiers($table, true, null, false), array_keys($crud->ar_set), array_values($crud->ar_set));
        
        $crud->_resetWrite();
        
        return $crud->execQuery($sql);  // return affected rows ( PDO support )
    }

}