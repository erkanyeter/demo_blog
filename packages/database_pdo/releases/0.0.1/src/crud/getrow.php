<?php
namespace Database_Pdo\Src\Crud {

    // --------------------------------------------------------------------

    /**
    * Fetch one result as object
    *
    * @return object
    */
    function getRow()
    {
    	$crud = getInstance()->{\Db::$var};
    	
        return $crud->Stmt->fetch(\PDO::FETCH_OBJ);
    }

}