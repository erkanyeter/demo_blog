<?php
namespace Database_Pdo\Src\Crud {

    // ------------------------------------------------------------------

    /**
    * Equal to PDO_Statement::bindParam()
    *
    * @param   mixed $param
    * @param   mixed $val
    * @param   mixed $type  PDO FETCH CONSTANT
    * @param   mixed $length
    * @param   mixed $driver_options
    */
    function bindParam($param, $val, $type, $length = null, $driver_options = null)
    {
		$crud = getInstance()->{\Db::$var};

        $crud->Stmt->bindParam($param, $val, $type, $length, $driver_options);

        $crud->use_bind_params = true;
        $crud->last_bind_params[$param] = $val;

        return $crud;
    }

}