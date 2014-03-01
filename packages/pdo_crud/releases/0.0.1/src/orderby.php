<?php
namespace Pdo_Crud\Src {

    // --------------------------------------------------------------------
    
    /**
    * Sets the ORDER BY value
    *
    * @access   public
    * @param    string
    * @param    string    direction: asc or desc
    * @return   object
    */
    function orderBy($orderby, $direction = '')
    {
        $crud = getInstance()->{\Db::$var};

        $direction = strtoupper(trim($direction));
        
        if($direction != '')
        {
            switch($direction)
            {
                case 'ASC':
                $direction = ' ASC';    
                break;
                
                case 'DESC':
                $direction = ' DESC';
                break;
                
                default:
                $direction = ' ASC';
            }
        }
                            
        if (strpos($orderby, ',') !== false)
        {
            $temp = array();
            foreach (explode(',', $orderby) as $part)
            {
                $part = trim($part);
                if ( ! in_array($part, $crud->ar_aliased_tables))
                {
                    $part = $crud->_protectIdentifiers(trim($part));
                }
                
                $temp[] = $part;
            }
            
            $orderby = implode(', ', $temp);            
        }
        else
        {
            $orderby = $crud->_protectIdentifiers($orderby);
        }
    
        $orderby_statement  = $orderby.$direction;
        $crud->ar_orderby[] = $orderby_statement;
        
        if ($crud->ar_caching === true)
        {
            $crud->ar_cache_orderby[] = $orderby_statement;
            $crud->ar_cache_exists[]  = 'orderby';
        }
        
        return $crud;
    }

}