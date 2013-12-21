<?php
namespace Database_Pdo\Src\Crud {

    // --------------------------------------------------------------------

    /**
    * Equal to PDO::rowCount();
    *
    * @return  integer
    */
    function count()
    {
		$crud = getInstance()->{\Db::$var};

        return $crud->Stmt->rowCount();
    }

}