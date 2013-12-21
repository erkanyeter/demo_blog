<?php
namespace Database_Pdo\Src\Crud {

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
        }
        
        if ( ! is_null($limit))
        {
            $crud->limit($limit, $offset);
        }
            
        $crud->sql = $crud->_compileSelect();
        
        if($crud->prepare == false)    // obullo changes ..
        {
            $result = $crud->query($crud->sql);
            $crud->_resetSelect();
            
            return $result;
        
        } 
        elseif($crud->prepare) // passive mode...
        {
            $crud->query($crud->sql);  
            $crud->_resetSelect();
            
            return $crud;    // obullo changes ..
        }
    }   

}