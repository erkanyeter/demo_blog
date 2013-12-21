<?php
namespace Database_Pdo\Src\Crud {

    // --------------------------------------------------------------------

    function orNotLike($field, $match = '', $side = 'both')
    {
		$crud = getInstance()->{\Db::$var};

        return $crud->_like($field, $match, 'OR ', $side, 'NOT');
    }

}