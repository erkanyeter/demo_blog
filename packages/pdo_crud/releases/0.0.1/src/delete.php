<?php
namespace Pdo_Crud\Src {

    // --------------------------------------------------------------------

    /**
    * Delete
    *
    * Compiles a delete string and runs the query
    *
    * @access   public
    * @param    mixed    the table(s) to delete from. String or array
    * @param    mixed    the where clause
    * @param    mixed    the limit clause
    * @param    boolean
    * @return   object
    */
    function delete($table = '', $where = '', $options = array(), $reset_data = true)
    {
        $crud = getInstance()->{\Db::$var};

        $options = array();     // delete options
        $crud->_mergeCache();   // Combine any cached components with the current statements

        if ($table == '')
        {
            if ( ! isset($crud->ar_from[0]))
            {
                throw new \Exception('Please set table for delete operation.');
            }

            $table = $crud->ar_from[0];
        }
        elseif (is_array($table))
        {
            foreach($table as $single_table)
            {
                $crud->from($table); // set tablename for set() function.
                $crud->delete($single_table, $where, array(), false);
            }
        
            $crud->_resetWrite();
            return;

        } else 
        {
            $table = $crud->_protectIdentifiers($table, true, null, false);
        }

        // ----------------------

        global $config;

        if($config['model_auto_sync']) // Run sync , Create new schema if not exists.
        {
            \Schema::runSync($table);
        }

        // ----------------------
        
        if (sizeof($crud->ar_where) == 0 AND sizeof($crud->ar_wherein) == 0 AND sizeof($crud->ar_like) == 0)
        {
            throw new \Exception("Deletes are not allowed unless they contain a 'where' or 'like' clause.");
            
            return false;
        }        

        $crud->from($table); // set tablename for set() function.
        $sql = $crud->_delete($table, $crud->ar_where, $crud->ar_like, $crud->ar_limit);
        
        if ($reset_data)
        {
            $crud->_resetWrite();
        }
        
        return $crud->execQuery($sql); // return number of  affected rows
    }

}