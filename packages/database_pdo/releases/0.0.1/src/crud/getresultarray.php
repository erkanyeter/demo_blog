<?php
namespace Database_Pdo\Src\Crud {

    // --------------------------------------------------------------------

    /**
    * Fetch all results as array
    *
    * @return  array
    */
    function getResultArray()
    {
        $crud = getInstance()->{\Db::$var};

        return $crud->Stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
}