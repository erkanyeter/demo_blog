<?php
namespace Pdo_Crud\Src {

    // --------------------------------------------------------------------

    /**
    * Stop Cache
    *
    * Stops AR caching
    *
    * @access    public
    * @return    void
    */        
    function stopCache()
    {
        $crud = getInstance()->{\Db::$var};
        $crud->ar_caching = false;
    }

}