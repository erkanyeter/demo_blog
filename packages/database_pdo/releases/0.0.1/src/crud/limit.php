<?php
namespace Database_Pdo\Src\Crud {

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