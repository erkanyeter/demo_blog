<?php
namespace Pdo_Crud\Src {

    // --------------------------------------------------------------------
    
    /**
    * Returns the "last" row
    *
    * @access    public
    * @return    object
    */    
    function getLastRowArray()
    {
		$crud = getInstance()->{\Db::$var};

        $result = $crud->_stmtResult(2);

        if (sizeof($result) == 0)
        {
            return $result;
        }

        return $result[count($result) -1];
    }    
    
}