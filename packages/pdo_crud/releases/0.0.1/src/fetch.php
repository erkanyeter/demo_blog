<?php
namespace Pdo_Crud\Src {

    // --------------------------------------------------------------------

    /**
    * Native PDOStatement::fetch() function
    *
    * @param    int $fetch_style = PDO::FETCH_BOTH
    * @param    int $cursor_orientation = PDO::FETCH_ORI_NEXT
    * @param    $cursor_offset = 0
    * @return   object
    */
    function fetch()
    {
        $crud = getInstance()->{\Db::$var};

        $arg  = func_get_args();

        switch (sizeof($arg))
        {
           case 0:
           return $crud->Stmt->fetch(\PDO::FETCH_OBJ);
             break;
           case 1:
           return $crud->Stmt->fetch($arg[0]);
             break;
           case 2:
           return $crud->Stmt->fetch($arg[0], $arg[1]);
             break;
           case 3:
           return $crud->Stmt->fetch($arg[0], $arg[1], $arg[2]);
             break;
        }
    }

}