<?php
namespace Database_Pdo\Src\Crud {

    // --------------------------------------------------------------------

    /**
     * Replace
     *
     * Compiles an replace into string and runs the query
     *
     * @param	string	the table to replace data into
     * @param	array	an associative array of insert values
     * @return	object
     */
    function replace($table = '', $set = null)
    {
        $crud = getInstance()->{\Db::$var};

        if ( ! is_null($set))
        {
            $crud->set($set);
        }

        if (sizeof($crud->ar_set) == 0)
        {
            throw new \Exception('Please set values for replace operation.');
        }

        if ($table == '')
        {
            if ( ! isset($crud->ar_from[0]))
            {
                throw new \Exception('Please set from for replace operation.');
            }

            $table = $crud->ar_from[0];
        }

        $sql = $crud->_replace($crud->_protectIdentifiers($table, true, null, false), array_keys($crud->ar_set), array_values($crud->ar_set));

        $crud->_resetWrite();
        
        return $crud->query($sql);
    }

}