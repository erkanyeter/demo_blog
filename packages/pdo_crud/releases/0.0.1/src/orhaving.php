<?php
namespace Pdo_Crud\Src {

    // --------------------------------------------------------------------
    
    function orHaving($key, $value = '', $escape = true)
    {
		$crud = getInstance()->{\Db::$var};

        return $crud->_having($key, $value, 'OR ', $escape);
    }

}