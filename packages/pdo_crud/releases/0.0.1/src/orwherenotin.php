<?php
namespace Pdo_Crud\Src {

    // --------------------------------------------------------------------

    function orWhereNotIn($key = null, $values = null)
    {
		$crud = getInstance()->{\Db::$var};

        return $crud->_whereIn($key, $values, true, 'OR ');
    }

}