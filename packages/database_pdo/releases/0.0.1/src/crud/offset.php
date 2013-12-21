<?php
namespace Database_Pdo\Src\Crud {

    // --------------------------------------------------------------------

    function offset($offset)
    {
		$crud = getInstance()->{\Db::$var};
		
        $crud->ar_offset = $offset;
        return $crud;
    }

}