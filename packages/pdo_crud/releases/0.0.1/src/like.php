<?php
namespace Pdo_Crud\Src {

 	// --------------------------------------------------------------------
    
    function like($field, $match = '', $side = 'both')
    {
    	$crud = getInstance()->{\Db::$var};
    	
        return $crud->_like($field, $match, 'AND ', $side);
    }

}