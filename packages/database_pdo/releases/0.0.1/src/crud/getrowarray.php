<?php
namespace Database_Pdo\Src\Crud {

    // --------------------------------------------------------------------
    
    /**
    * Result row as array
    *
    * @author CJ Lazell
    * @return  array
    */
    function getRowArray()
    {
        $crud = getInstance()->{\Db::$var};

        return $crud->Stmt->fetch(\PDO::FETCH_ASSOC);
    }

}