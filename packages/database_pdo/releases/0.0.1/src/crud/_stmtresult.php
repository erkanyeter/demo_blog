<?php
namespace Database_Pdo\Src\Crud {

    // --------------------------------------------------------------------
    
    /**
    * Get results for current db 
    * operation. (firstRow(), nextRow() .. )
    *     
    * @access   private
    * @param    integer $type
    * @return   array
    */
    function _stmtResult($type)
    {
        $crud = getInstance()->{\Db::$var};

        if(sizeof($crud->stmt_result) > 0)
        {
            return $crud->stmt_result;
        }
        
        $crud->stmt_result = $crud->Stmt->fetchAll($type);
        
        return $crud->stmt_result;
    }

}