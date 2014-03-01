<?php
namespace Pdo_Crud\Src {

    // --------------------------------------------------------------------

    function offset($offset)
    {
		$crud = getInstance()->{\Db::$var};
		
        $crud->ar_offset = $offset;
        return $crud;
    }

}