<?php
namespace Pdo_Crud\Src {

    // -------------------------------------------------------------------- 
    
    function whereIn($key = null, $values = null)
    {
 		$crud = getInstance()->{\Db::$var};

        return $crud->_whereIn($key, $values);
    }

}