<?php
namespace Pdo_Crud\Src {

    // --------------------------------------------------------------------

    function whereNotIn($key = null, $values = null)
    {
 		$crud = getInstance()->{\Db::$var};

        return $crud->_whereIn($key, $values, true);
    }

}    