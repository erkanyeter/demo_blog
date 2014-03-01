<?php
namespace Pdo_Crud\Src {

    // --------------------------------------------------------------------

    function notLike($field, $match = '', $side = 'both')
    {
		$crud = getInstance()->{\Db::$var};
		
        return $crud->_like($field, $match, 'AND ', $side, 'NOT');
    }
    
}