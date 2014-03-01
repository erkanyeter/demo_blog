<?php
namespace Pdo_Crud\Src {

    // --------------------------------------------------------------------

    /**
    * Returns the "next" row
    *
    * Returns the next row results. next_rowset doesn't work for the mysql
    * driver. Also adds backwards compatibility for Codeigniter.
    *
    * @author CJ Lazell
    * @access	public
    * @return	object
    */	
    function getNextRowArray()
    {
        $crud = getInstance()->{\Db::$var};

        $result = $crud->_stmtResult(2);

        if(sizeof($result) == 0)
        {
            return $result;
        }
        
        if(isset($result[$crud->current_row + 1]))
        {
            ++$crud->current_row;
        }

        return $result[$crud->current_row];
    }

}