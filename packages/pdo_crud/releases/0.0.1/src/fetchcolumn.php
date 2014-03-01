<?php
namespace Pdo_Crud\Src {

    // --------------------------------------------------------------------

    /**
    * Returns a single column from the next row of a result set
    *
    * @param object
    */
    function fetchColumn($col = null)
    {
        $crud = getInstance()->{\Db::$var};

        return $crud->Stmt->fetchColumn($col);
    }

}