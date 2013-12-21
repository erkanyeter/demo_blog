<?php
namespace Database_Pdo\Src\Crud {

    // -------------------------------------------------------------------- 
    
    function having($key, $value = '', $escape = true)
    {
		$crud = getInstance()->{\Db::$var};

        return $crud->_having($key, $value, 'AND ', $escape);
    }
    
}