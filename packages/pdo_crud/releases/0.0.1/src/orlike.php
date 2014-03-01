<?php
namespace Pdo_Crud\Src {

    // --------------------------------------------------------------------

    function orLike($field, $match = '', $side = 'both')
    {
		$crud = getInstance()->{\Db::$var};

        return $crud->_like($field, $match, 'OR ', $side);
    }
}