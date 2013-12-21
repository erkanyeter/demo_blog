<?php
namespace Database_Pdo\Src\Crud {

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
    function nextRow($type = 5)
    {
        $crud = getInstance()->{\Db::$var};

        $result = $crud->_stmtResult($type);

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