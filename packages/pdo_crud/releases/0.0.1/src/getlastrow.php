<?php
namespace Pdo_Crud\Src {

    // --------------------------------------------------------------------
    
    /**
    * Returns the "last" row
    *
    * @access    public
    * @return    object
    */    
    function getLastRow($type = 5)
    {
		$crud = getInstance()->{\Db::$var};

        $result = $crud->_stmtResult($type);

        if (sizeof($result) == 0)
        {
            return $result;
        }

        return $result[count($result) -1];
    }    
    
}