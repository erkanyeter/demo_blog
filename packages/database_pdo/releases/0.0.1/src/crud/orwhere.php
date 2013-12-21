<?php
namespace Database_Pdo\Src\Crud {

	// --------------------------------------------------------------------
    
    function orWhere($key, $value = null, $escape = true)
    {
		$crud = getInstance()->{\Db::$var};

        return $crud->_where($key, $value, 'OR ', $escape);
    }
}