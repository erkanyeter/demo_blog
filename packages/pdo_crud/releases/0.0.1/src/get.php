<?php
namespace Pdo_Crud\Src {

    // --------------------------------------------------------------------
    
    /**
    * Get
    *
    * Compiles the select statement based on the other functions called
    * and runs the query
    *
    * @access   public
    * @param    string    the table
    * @param    string    the limit clause
    * @param    string    the offset clause
    * @return   object | void
    */
    function get($table = '', $limit = null, $offset = null)
    {
        $crud = getInstance()->{\Db::$var};

        if ($table != '') 
        {   
            $crud->_trackAliases($table);
            $crud->from($table);

            // ----------------------

            global $config;

            if($config['model_auto_sync']) // Create new schema if not exists.
            {
                \Schema::runSync($table);
            }

            // ----------------------
        }
        
        if ( ! is_null($limit))
        {
            $crud->limit($limit, $offset);
        }
            
        $crud->sql = $crud->_compileSelect();
        
        if($crud->prepare == false)    // pdo changes ..
        {
            $result = $crud->query($crud->sql);
            $crud->_resetSelect();
            
            return $result;
        
        } 
        elseif($crud->prepare) // passive mode...
        {
            $crud->query($crud->sql);  
            $crud->_resetSelect();
            
            return $crud;    // pdo changes ..
        }
    }

}