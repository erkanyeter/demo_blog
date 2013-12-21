<?php
namespace Database_Pdo\Src\Crud {

    // --------------------------------------------------------------------
   
    /**
    * Start Cache
    *
    * Starts AR caching
    *
    * @access    public
    * @return    void
    */        
    function startCache()
    {
        $crud = getInstance()->{\Db::$var};
        $crud->ar_caching = true;
    }

}