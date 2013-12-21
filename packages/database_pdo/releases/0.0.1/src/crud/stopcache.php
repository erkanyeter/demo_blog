<?php
namespace Database_Pdo\Src\Crud {

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