<?php
namespace Pdo_Crud\Src {

    // --------------------------------------------------------------------

    /**
    * Fetch all result as object
    *
    * @return object
    */
    function getResult()
    {
        $crud = getInstance()->{\Db::$var};

        return $crud->Stmt->fetchAll(\PDO::FETCH_OBJ);
    }

}