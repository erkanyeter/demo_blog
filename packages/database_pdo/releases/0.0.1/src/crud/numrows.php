<?php
namespace Database_Pdo\Src\Crud {

    // --------------------------------------------------------------------

    /**
    * Alias of rowCount();
    *
    * @return  integer
    */
    function numRows()
    {
        $crud = getInstance()->{\Db::$var};

        return $crud->Stmt->rowCount();
    }

}