<?php
namespace Database_Pdo\Src\Crud {

    // --------------------------------------------------------------------

    /**
    * Fetch all result as object
    *
    * @return object
    */
    function result()
    {
        $crud = getInstance()->{\Db::$var};

        return $crud->Stmt->fetchAll(\PDO::FETCH_OBJ);
    }

}