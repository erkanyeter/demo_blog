<?php
namespace Pdo_Crud\Src {

    // --------------------------------------------------------------------
    
    /**
    * Returns the "first" row
    *
    * @access    public
    * @return    object
    */    
    function getFirstRow($type = 5)
    {
        $crud = getInstance()->{\Db::$var};

        $result = $crud->_stmtResult($type);

        if (sizeof($result) == 0)
        {
            return $result;
        }
        
        return $result[0];
    }

}