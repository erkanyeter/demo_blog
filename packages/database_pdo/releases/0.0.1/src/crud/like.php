 <?php
namespace Database_Pdo\Src\Crud {

 	// --------------------------------------------------------------------
    
    function like($field, $match = '', $side = 'both')
    {
    	$crud = getInstance()->{\Db::$var};
    	
        return $crud->_like($field, $match, 'AND ', $side);
    }

}