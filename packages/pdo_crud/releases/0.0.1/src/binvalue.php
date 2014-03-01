<?php
namespace Pdo_Crud\Src {

    // --------------------------------------------------------------------

    /**
    * Equal to PDO_Statement::bindValue()
    *
    * @param   string $param
    * @param   mixed $val
    * @param   string $type PDO FETCH CONSTANT
    */
   	function bindValue($param, $val, $type)
    {
		$crud = getInstance()->{\Db::$var};

        $crud->Stmt->bindValue($param, $val, $type);

        $crud->use_bind_values = true;
        $crud->last_bind_values[$param] = $val;

        return $crud;
    }

}