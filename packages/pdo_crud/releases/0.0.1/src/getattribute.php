<?php
namespace Pdo_Crud\Src {

    // --------------------------------------------------------------------

    /**
    * Retrieve a statement attribute.
    *
    * @param   integer $key Attribute name.
    * @return  mixed      Attribute value.
    */
    function getAttribute($key)
    {
        $crud = getInstance()->{\Db::$var};

        return $crud->Stmt->getAttribute($key);
    }
    
}