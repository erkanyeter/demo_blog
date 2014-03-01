<?php
namespace Pdo_Crud\Src {

    // --------------------------------------------------------------------

    /**
     * Escape LIKE String
     *
     * Calls the individual driver for platform
     * specific escaping for LIKE conditions
     *
     * @access   public
     * @param    string
     * @return   mixed
     */
    function escapeLike($str, $side = 'both')
    {
        $crud = getInstance()->{\Db::$var};

        return $crud->escapeStr($str, true, $side);
    }

}