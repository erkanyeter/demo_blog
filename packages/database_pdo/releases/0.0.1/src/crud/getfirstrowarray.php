<?php
namespace Database_Pdo\Src\Crud {

    // --------------------------------------------------------------------
    
    /**
    * Returns the "first" row
    *
    * @access    public
    * @return    object
    */    
    function getFirstRowArray()
    {
        $crud = getInstance()->{\Db::$var};

        $result = $crud->_stmtResult(2);

        if (sizeof($result) == 0)
        {
            return $result;
        }
        
        return $result[0];
    }

}