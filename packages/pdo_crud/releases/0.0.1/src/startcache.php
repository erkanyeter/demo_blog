<?php
namespace Pdo_Crud\Src {

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