<?php
namespace Pdo_Crud\Src {

	// --------------------------------------------------------------------
	
    function where($key, $value = null, $escape = true)
    {
    	$crud = getInstance()->{\Db::$var};

        return $crud->_where($key, $value, 'AND ', $escape);
    }

}