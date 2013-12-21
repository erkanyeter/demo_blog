<?php
namespace Database_Pdo\Src\Crud {

    // --------------------------------------------------------------------

    function orWhereIn($key = null, $values = null)
    {
		$crud = getInstance()->{\Db::$var};

        return $crud->_whereIn($key, $values, false, 'OR ');
    }
}