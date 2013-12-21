<?php
namespace Database_Pdo\Src\Crud {

    // --------------------------------------------------------------------
    
    function orHaving($key, $value = '', $escape = true)
    {
		$crud = getInstance()->{\Db::$var};

        return $crud->_having($key, $value, 'OR ', $escape);
    }

}