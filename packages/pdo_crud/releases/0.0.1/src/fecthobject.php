<?php
namespace Pdo_Crud\Src {

    // --------------------------------------------------------------------
    
    /**
    * Fetches the next row and returns it as an object.
    *
    * @param    string $class  OPTIONAL Name of the class to create.
    * @param    array  $config OPTIONAL Constructor arguments for the class.
    * @return   mixed One object instance of the specified class.
    */
    function fetchObject($class = 'stdClass', array $config = array())
    {
        $crud = getInstance()->{\Db::$var};

        return $crud->Stmt->fetchObject($class, $config);
    }

}