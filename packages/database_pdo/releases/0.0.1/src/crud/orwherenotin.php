<?php
namespace Database_Pdo\Src\Crud {

    // --------------------------------------------------------------------

    function orWhereNotIn($key = null, $values = null)
    {
		$crud = getInstance()->{\Db::$var};

        return $crud->_whereIn($key, $values, true, 'OR ');
    }

}