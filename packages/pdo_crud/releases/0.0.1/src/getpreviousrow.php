<?php
namespace Pdo_Crud\Src {

    // --------------------------------------------------------------------

    /**
    * Returns the "previous" row
    *
    * @access    public
    * @return    object
    */    
    function getPreviousRow($type = 5)
    {
        $crud = getInstance()->{\Db::$var};

        $result = $crud->_stmtResult($type);

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