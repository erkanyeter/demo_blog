<?php
namespace Database_Pdo\Src\Crud {

    // --------------------------------------------------------------------
    
    /**
    * Get "all results" by assoc, object, num, bound or
    * anything what u want
    *
    * @param    int $fetch_style  = PDO::FETCH_BOTH
    * @param    int $column_index = 0
    * @param    array $ctor_args  = array()
    * @return   object
    */
    function fetchAll()
    {
        $crud = getInstance()->{\Db::$var};

        $arg = func_get_args();

        switch (sizeof($arg))
        {
           case 0:
           return $crud->Stmt->fetchAll(\PDO::FETCH_OBJ);
             break;
           case 1:
           return $crud->Stmt->fetchAll($arg[0]);
             break;
           case 2:
           return $crud->Stmt->fetchAll($arg[0], $arg[1]);
             break;
           case 3:
           return $crud->Stmt->fetchAll($arg[0], $arg[1], $arg[2]);
             break;
        }
    }
    
}