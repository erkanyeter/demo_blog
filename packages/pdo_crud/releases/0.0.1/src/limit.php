<?php
namespace Pdo_Crud\Src {

    // --------------------------------------------------------------------

    function limit($value, $offset = '')
    {
        $crud = getInstance()->{\Db::$var};
        
        $crud->ar_limit = $value;

        if ($offset != '')
        {
            $crud->ar_offset = $offset;   
        }
        
        return $crud;
    }

}