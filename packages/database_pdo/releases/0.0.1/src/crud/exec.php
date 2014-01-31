<?php
namespace Database_Pdo\Src\Crud {

    // --------------------------------------------------------------------

    /**
    * Execute prepared query
    *
    * @param    array   $array bound, DEFAULT MUST BE null.
    * @param    string  $bind_value
    * @return   object  | void
    */
    function exec($array = null)
    {
        $crud = getInstance()->{\Db::$var};

        if(is_array($array)) // If data is array.
        {
            if( ! $crud->isAssocArray($array))
            {
                throw new \Exception('PDO bind data must be associative array');
            }
        }

        //------------------------------------
        
        list($sm, $ss) = explode(' ', microtime());
        $start_time = ($sm + $ss);
        
        $crud->Stmt->execute($array);
        
        list($em, $es) = explode(' ', microtime());
        $end_time = ($em + $es);
        
        //------------------------------------
        
        $config = getConfig();
    
        if($config['log_queries'])
        {
            if(sizeof($crud->prep_queries) > 0)
            {
                logMe('debug', 'SQL: '.trim(preg_replace('/\n/', ' ', end($crud->prep_queries)), "\n").' ( Prepared Query ) time: '.number_format($end_time - $start_time, 4));
            }
        }

        $crud->prepare = false;   // reset prepare variable and prevent collision with next query ..

        ++$crud->exec_count;        // count execute of prepared statements ..

        $crud->last_values = array();   // reset last bind values ..

        if(is_array($array))    // store last executed bind values for last_query method.
        {
            $crud->last_values[$crud->exec_count] = $array;
        }
        elseif($crud->use_bind_values)
        {
            $crud->last_values[$crud->exec_count] = $crud->last_bind_values;
        }
        elseif($crud->use_bind_params)
        {
            $crud->last_values[$crud->exec_count] = $crud->last_bind_params;
        }

        $crud->use_bind_values  = false;         // reset query bind usage data ..
        $crud->use_bind_params  = false;
        $crud->last_bind_values = array();
        $crud->last_bind_params = array();

        return $crud;
    }

}