<?php
namespace Pdo_Crud\Src {

    // --------------------------------------------------------------------

    /**
    * "Smart" Escape String via PDO
    *
    * Escapes data based on type
    * Sets boolean and null types
    *
    * @access    public
    * @param     string
    * @return    mixed
    */
    function escape($str)
    {
		$crud = getInstance()->{\Db::$var};

        if(is_string($str))
        {
            return $crud->escapeStr($str);
        }

        if(is_integer($str))
        {
            return (int)$str;
        }

        if(is_double($str))
        {
            return (double)$str;
        }

        if(is_float($str))
        {
            return (float)$str;
        }
        
        if(is_bool($str))
        {
            return ($str === false) ? 0 : 1;
        }
        
        if(is_null($str))
        {
            return 'null';
        }
    }

}