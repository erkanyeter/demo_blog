<?php
namespace Database_Pdo\Src\Crud {

    // --------------------------------------------------------------------
    
    /**
    * DISTINCT
    *
    * Sets a flag which tells the query string compiler to add DISTINCT
    *
    * @access   public
    * @param    bool
    * @return   object
    */
    function distinct($val = true)
    {
        $crud = getInstance()->{\Db::$var};
        
        $crud->ar_distinct = (is_bool($val)) ? $val : true;
        
        return $crud;
    }

}