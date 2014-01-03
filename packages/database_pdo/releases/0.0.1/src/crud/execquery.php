<?php
namespace Database_Pdo\Src\Crud {

    // --------------------------------------------------------------------

    /**
    * execQuery just for CREATE, DELETE, INSERT and
    * UPDATE operations it returns to
    * number of [affected rows] after the write
    * operations.
    *
    * @param    string $sql
    * @return   boolean
    */
    function execQuery($sql)
    {
		$crud = getInstance()->{\Db::$var};

        $crud->last_sql = $sql;

        //------------------------------------

        list($sm, $ss) = explode(' ', microtime());
        $start_time = ($sm + $ss);

        $affected_rows = $crud->_conn->exec($sql);

        list($em, $es) = explode(' ', microtime());
        $end_time = ($em + $es);

        //------------------------------------

        if(config('log_queries'))
        {
            if(sizeof($crud->prep_queries) > 0)
            {
                logMe('debug', 'SQL: '.trim(preg_replace('/\n/', ' ', end($crud->prep_queries)), "\n").' ( Exec Query ) time: '.number_format($end_time - $start_time, 4));
            } 
            else 
            {
                logMe('debug', 'SQL: '.trim(preg_replace('/\n/', ' ', $sql), "\n").' time: '.number_format($end_time - $start_time, 4));   
            }
        }

        return $affected_rows;
    }

}