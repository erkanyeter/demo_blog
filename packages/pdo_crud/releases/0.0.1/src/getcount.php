<?php
namespace Pdo_Crud\Src {

    // --------------------------------------------------------------------

    /**
    * Equal to PDO::rowCount();
    *
    * @return  integer
    */
    function getCount()
    {
		$crud = getInstance()->{\Db::$var};

        return $crud->Stmt->rowCount();
    }

}