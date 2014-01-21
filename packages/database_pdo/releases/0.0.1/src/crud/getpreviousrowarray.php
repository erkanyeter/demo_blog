<?php
namespace Database_Pdo\Src\Crud {

    // --------------------------------------------------------------------

    /**
    * Returns the "previous" row
    *
    * @access    public
    * @return    object
    */    
    function getPreviousRowArray()
    {
        $crud = getInstance()->{\Db::$var};

        $result = $crud->_stmtResult(2);

        if (sizeof($result) == 0)
        {
            return $result;
        }

        if (isset($result[$crud->current_row - 1]))
        {
            --$crud->current_row;
        }
        
        return $result[$crud->current_row];
    }

}