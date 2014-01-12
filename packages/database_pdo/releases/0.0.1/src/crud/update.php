<?php
namespace Database_Pdo\Src\Crud {

    /**
    * Update
    *
    * Compiles an update string and runs the query
    *
    * @access   public
    * @param    string   the table to retrieve the results from
    * @param    array    an associative array of update values
    * @param    array    update options
    * @return   PDO exec number of affected rows
    */
    function update($table = '', $set = null, $options = array())
    {
        $crud = getInstance()->{\Db::$var};
        
        $options = array(); // Update options.
        
        if ($table == '') // Set table
        {
            if ( ! isset($crud->ar_from[0]))
            {
                throw new \Exception('Please set tablename for update operation.');
            }
            
            $table = $crud->ar_from[0];
        } 
        else 
        {
            $crud->from($table); // set tablename for set() function.
        }

        $crud->_mergeCache(); // Combine any cached components with the current statements
        
        if ( ! is_null($set))
        {
            $crud->set($set);
        }
    
        if (sizeof($crud->ar_set) == 0)
        {
            throw new \Exception('Please set values for update operation.');
            
            return false;
        }

        $sql = $crud->_update($crud->_protectIdentifiers($table, true, null, false), $crud->ar_set, $crud->ar_where, $crud->ar_orderby, $crud->ar_limit);
                 
        $crud->_resetWrite();
        
        return $crud->execQuery($sql);  // return number of affected rows.  
    }

}