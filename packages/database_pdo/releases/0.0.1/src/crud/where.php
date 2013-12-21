<?php
namespace Database_Pdo\Src\Crud {

	// --------------------------------------------------------------------
	
    function where($key, $value = null, $escape = true)
    {
    	$crud = getInstance()->{\Db::$var};

        return $crud->_where($key, $value, 'AND ', $escape);
    }

}