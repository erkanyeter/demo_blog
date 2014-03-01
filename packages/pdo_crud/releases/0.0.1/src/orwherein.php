<?php
namespace Pdo_Crud\Src {

    // --------------------------------------------------------------------

    function orWhereIn($key = null, $values = null)
    {
		$crud = getInstance()->{\Db::$var};

        return $crud->_whereIn($key, $values, false, 'OR ');
    }
}