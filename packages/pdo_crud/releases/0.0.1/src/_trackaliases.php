<?php
namespace Pdo_Crud\Src {

    /**
    * Track Aliases
    *
    * Used to track SQL statements written with aliased tables.
    *
    * @access    private
    * @param     string    The table to inspect
    * @return    string
    */    
    function _trackAliases($table)
    {
        $crud = getInstance()->{\Db::$var};

        if (is_array($table))
        {
            foreach ($table as $t)
            {
                $crud->_trackAliases($t);
            }
            return;
        }
        
        // Does the string contain a comma?  If so, we need to separate
        // the string into discreet statements
        if (strpos($table, ',') !== false)
        {
            return $crud->_trackAliases(explode(',', $table));
        }
    
        // if a table alias is used we can recognize it by a space
        if (strpos($table, " ") !== false)
        {
            // if the alias is written with the AS keyword, remove it
            $table = preg_replace('/ AS /i', ' ', $table);
            
            // Grab the alias
            $table = trim(strrchr($table, " "));
            
            // Store the alias, if it doesn't already exist
            if ( ! in_array($table, $crud->ar_aliased_tables))
            {
                $crud->ar_aliased_tables[] = $table;
            }
        }
    }

}